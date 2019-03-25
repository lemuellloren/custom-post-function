<?php
/**
 * Plugin Name: WooCommerce Local Pickup Plus
 * Plugin URI: http://www.woothemes.com/products/local-pickup-plus/
 * Description: A shipping plugin for WooCommerce that allows the store operator to define local pickup locations, which the customer can then choose from when making a purchase.
 * Author: SkyVerge
 * Author URI: http://www.skyverge.com
 * Version: 1.8.1
 * Text Domain: woocommerce-shipping-local-pickup-plus
 * Domain Path: /i18n/languages/
 *
 * Copyright: (c) 2012-2014 SkyVerge, Inc. (info@skyverge.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package     WC-Shipping-Local-Pickup-Plus
 * @author      SkyVerge
 * @category    Shipping
 * @copyright   Copyright (c) 2012-2014, SkyVerge, Inc.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Required functions
if ( ! function_exists( 'woothemes_queue_update' ) ) {
	require_once( 'woo-includes/woo-functions.php' );
}

// Plugin updates
woothemes_queue_update( plugin_basename( __FILE__ ), '4d6fbe9e8968a669d11cec40b85a0caa', '18696' );

// WC active check
if ( ! is_woocommerce_active() ) {
	return;
}

// Required library class
if ( ! class_exists( 'SV_WC_Framework_Bootstrap' ) ) {
	require_once( 'lib/skyverge/woocommerce/class-sv-wc-framework-bootstrap.php' );
}

SV_WC_Framework_Bootstrap::instance()->register_plugin( '3.0.0', __( 'WooCommerce Local Pickup Plus', 'woocommerce-shipping-local-pickup-plus' ), __FILE__, 'init_woocommerce_shipping_local_pickup_plus', array( 'minimum_wc_version' => '2.1', 'backwards_compatible' => '3.0.0' ) );

function init_woocommerce_shipping_local_pickup_plus() {

/**
 * # WooCommerce Local Pickup Plus Shipping Method
 *
 * ## Plugin Overview
 *
 * This shipping method allows the shop admin to define one or more pickup
 * locations, and for customers to choose between them when checking out.
 *
 * ## Class Description
 *
 * The main class for the Local Pickup Plus shipping method.  This class handles
 * all the non-gateway tasks such rendering the plugin "Configure" link, loading
 * the text domain, etc.  It also loads the Local Pickup Plus shipping method
 * when needed now that the method is only created on the cart/checkout &
 * admin settings pages.  The method is also loaded in the following instances:
 *
 * * Admin shop order process request, to persist pickup location
 * * Early in the checkout page request, to add some inline javascript
 * * From the checkout update order review action for when the shipping method
 *   or pickup location changes and the checkout page partial reloads
 * * The 'Thank You' and user account 'View Order' pages to render the selected
 *   pickup location
 * * Various order status changes, to store the order id for future reference
 * * The email templates, to render the pickup location
 *
 * ## Admin Considerations
 *
 * This plugin exposes a standard WooCommerce Shipping Method settings page
 * with all available plugin configuration.
 *
 * On the Edit Order page for orders that use Local Pickup Plus, a "Pickup
 * Locations" section is added to the Order Data panel that lists any pickup
 * locations addresses, along with the ability to change them.  Changing a
 * shipping method pickup location will also change that location in the order
 * line item meta for any items that are shipped by that method.
 *
 * ## Frontend Considerations
 *
 * On the frontend Local Pickup Plus is added as an available shipping method
 * as normal.  When selected, a "Pickup Location" line is added to the checkout
 * page so that a pickup location can be selected.
 *
 * The selected pickup locations are displayed on the "Thank You" page, "View
 * Order" account page, and order emails in a "Pickup Locations" section below
 * the Billing/Shipping address blocks.  The pickup location is also added as
 * a visible line item meta so it's easy to determine which products are picked
 * up from where.
 *
 * The plugin also defines an option to hide the Shipping Address fields on
 * checkout.
 *
 * ## Sessions
 *
 * + `chosen_pickup_locations` - Array of shipping package index -> pickup location id, this is the equivalent of chosen_shipping_methods
 *
 * ## Database
 *
 * The database considerations changed with the advent of WooCommerce 2.1
 *
 * ### Options Table
 *
 * + `woocommerce_pickup_locations` - Array of all configured pickup locations
 *
 * ### Order Item Meta
 *
 * + `_shipping_item_id` - Added to line items to associate them back to the shipping item they are included within
 * + `Pickup Location` - Added to line items which are shipped by local pickup plus, this is a human readable string
 * + `pickup_location` - Added to shipping items, this is the serialized location data (this replaces the old '_pickup_location' postmeta)
 *
 * ### Order Postmeeta
 *
 * + `_pickup_location` - (obsolete) Prior to WC 2.1 (which introduced multiple shipping methods support) the serialized location data was stored as an order postmeta.  This is removed and the new data structures used whenever an order placed pre-WC 2.1 is saved within the admin
 *
 */
class WC_Local_Pickup_Plus extends SV_WC_Plugin {


	/** plugin version number */
	const VERSION = '1.8.1';

	/** shipping method id */
	const METHOD_ID = 'local_pickup_plus';

	/** plugin text domain */
	const TEXT_DOMAIN = 'woocommerce-shipping-local-pickup-plus';

	/** shipping method class name */
	const METHOD_CLASS_NAME = 'WC_Shipping_Local_Pickup_Plus';


	/**
	 * @var mixed local pickup plus gateway class or object
	 */
	private $gateway_class;

	/**
	 * @var int associates order id with dispatched email
	 */
	private $email_order_id;


	/**
	 * Setup main plugin class
	 *
	 * @since 1.4
	 * @see SV_WC_Plugin::__construct()
	 */
	public function __construct() {

		parent::__construct(
			self::METHOD_ID,
			self::VERSION,
			self::TEXT_DOMAIN
		);

		$this->gateway_class = self::METHOD_CLASS_NAME;

		$this->includes();

		// Load Local Pickup Plus shipping method
		add_action( 'sv_wc_framework_plugins_loaded',      array( $this, 'load_classes' ) );
		add_action( 'wc_shipping_local_pickup_plus_init',  array( $this, 'store_local_pickup_plus_gateway' ) );
		add_action( 'init',                                array( $this, 'ajax_update_shipping_method_load' ), 10 );
		add_action( 'wp_ajax_woocommerce_checkout',        array( $this, 'load_shipping_method' ), 5 );
		add_action( 'wp_ajax_nopriv_woocommerce_checkout', array( $this, 'load_shipping_method' ), 5 );

		add_action( 'woocommerce_cart_shipping_method_full_label', array( $this, 'cart_shipping_method_full_label' ), 10, 2 );
		add_action( 'woocommerce_calculate_totals',                array( $this, 'apply_discount' ) );
		add_action( 'wp_enqueue_scripts',                          array( $this, 'frontend_scripts' ) );
		add_action( 'woocommerce_check_cart_items',                array( $this, 'check_cart_items' ) );
		add_action( 'woocommerce_checkout_update_order_review',    array( $this, 'checkout_update_order_review' ) );
		add_action( 'woocommerce_after_checkout_validation',       array( $this, 'after_checkout_validation' ) );
		add_action( 'woocommerce_thankyou',                        array( $this, 'order_pickup_location' ), 20 );
		add_action( 'woocommerce_view_order',                      array( $this, 'order_pickup_location' ), 20 );
		add_action( 'woocommerce_after_template_part',             array( $this, 'email_pickup_location' ), 10, 3 );
		add_filter( 'woocommerce_order_hide_shipping_address',     array( $this, 'hide_shipping_address' ) );

		// hoops to jump through to get at the order_id associated with the email being sent so we can add the local pickup
		//  location to the email body from the woocommerce_after_template_part action.  Note that we can't replace this with
		//  the simpler seeming 'woocommerce_order_status_changed' because by then the email has already been sent :(
		add_action( 'woocommerce_order_status_pending_to_processing_notification', array( $this, 'store_order_id' ), 1 );
		add_action( 'woocommerce_order_status_pending_to_completed_notification',  array( $this, 'store_order_id' ), 1 );
		add_action( 'woocommerce_order_status_pending_to_on-hold_notification',    array( $this, 'store_order_id' ), 1 );
		add_action( 'woocommerce_order_status_failed_to_processing_notification',  array( $this, 'store_order_id' ), 1 );
		add_action( 'woocommerce_order_status_failed_to_completed_notification',   array( $this, 'store_order_id' ), 1 );
		add_action( 'woocommerce_order_status_pending_to_processing_notification', array( $this, 'store_order_id' ), 1 );
		add_action( 'woocommerce_order_status_pending_to_on-hold_notification',    array( $this, 'store_order_id' ), 1 );
		add_action( 'woocommerce_order_status_completed_notification',             array( $this, 'store_order_id' ), 1 );
		add_action( 'woocommerce_new_customer_note_notification',                  array( $this, 'store_order_id' ), 1 );

		// Admin
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {

			// Order page persist any selected pickup locations - lower priority than WC_Meta_Box_Order_Totals::save()
			add_action( 'woocommerce_process_shop_order_meta', array( $this, 'admin_process_shop_order_meta' ), 35, 2 );
		}
	}


	/**
	 * Loads Shipping Method classes
	 *
	 * @since  1.4
	 */
	public function load_classes() {

		require_once( 'classes/class-wc-shipping-local-pickup-plus.php' );

		// Add class to WC Shipping Methods
		add_filter( 'woocommerce_shipping_methods', array( $this, 'load_method' ) );
	}


	/**
	 * Include required files
	 *
	 * @since 1.8.0
	 */
	private function includes() {

		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {

			// load order list table/edit order customizations
			require_once( 'classes/admin/class-wc-shipping-local-pickup-plus-shop-order-cpt.php' );
			new WC_Shipping_Local_Pickup_Plus_CPT();
		}
	}

	/**
	 * Loads the local pickup plus class from the woocommerce_update_shipping_method
	 * AJAX action early, which otherwise would not be loaded in time to update
	 * the shipping package
	 *
	 * @since 1.5
	 */
	public function ajax_update_shipping_method_load() {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['action'] ) && 'woocommerce_update_shipping_method' == $_REQUEST['action'] ) {
			$this->get_local_pickup_plus();
		}
	}


	/**
	 * Ensure the Local Pickup Plus shipping method is loaded in time to hook
	 * into the woocommerce_cart_shipping_packages filter from the checkout
	 * ajax action
	 *
	 * @since 1.5.1
	 */
	public function load_shipping_method() {
		$this->get_local_pickup_plus();
	}


	/**
	 * Load plugin text domain.
	 *
	 * @since 1.4
	 * @see SV_WC_Plugin::load_translation()
	 */
	public function load_translation() {

		load_plugin_textdomain( 'woocommerce-shipping-local-pickup-plus', false, dirname( plugin_basename( $this->get_file() ) ) . '/i18n/languages' );
	}


	/**
	 * Stores the local pickup plus gateway class once it's instantiated, so that
	 * if shipping methods are loaded more than once during a request, we can
	 * avoid instantiating the class a second time and duplicating action hooks
	 *
	 * @since 1.4
	 * @param WC_Shipping_Local_Pickup_Plus $local_pickup_plus local pickup plus gateway class
	 */
	public function store_local_pickup_plus_gateway( $local_pickup_plus ) {
		$this->gateway_class = $local_pickup_plus;
	}


	/**
	 * Add the Shipping Method to WooCommerce
	 *
	 * @since 1.4
	 * @param array array of shipping method class names or objects
	 * @return array of shipping method class names or objects
	 */
	public function load_method( $methods ) {

		// since the gateway is always constructed, we'll pass it in to the register filter so it doesn't have to be re-instantiated
		$methods[] = $this->gateway_class;  // this is either the class name, or the class object if we've already instantiated it
		return $methods;
	}


	/** Admin methods ******************************************************/


	/**
	 * Gets the plugin documentation url, which for Local Pickup Plus is non-standard
	 *
	 * @since 1.5
	 * @see SV_WC_Plugin::get_documentation_url()
	 * @return string documentation URL
	 */
	public function get_documentation_url() {
		return 'http://docs.woothemes.com/document/local-pickup-plus/';
	}


	/**
	 * Gets the shipping method configuration URL
	 *
	 * @since 1.5
	 * @see SV_WC_Plugin::get_settings_url()
	 * @param string $plugin_id the plugin identifier
	 * @return string plugin settings URL
	 */
	public function get_settings_url( $plugin_id = null ) {
		return admin_url( 'admin.php?page=wc-settings&tab=shipping&section=' . strtolower( self::METHOD_CLASS_NAME  ) );
	}


	/**
	 * Returns true if on the shipping method settings page
	 *
	 * @since 1.5
	 * @see SV_WC_Plugin::is_plugin_settings()
	 * @return boolean true if on the admin shipping method settings page
	 */
	public function is_plugin_settings() {
		return
			isset( $_GET['page'] )    && 'wc-settings' == $_GET['page'] &&
			isset( $_GET['tab'] )     && 'shipping' == $_GET['tab'] &&
			isset( $_GET['section'] ) && strtolower( self::METHOD_CLASS_NAME  ) == $_GET['section'];
	}


	/**
	 * Admin order update, if there's a pickup location to persist, hand off to
	 * the local pickup plus shipping class, which must be instantiated
	 *
	 * @since 1.4
	 * @param int $post_id order post identifier
	 * @param object $post order post object
	 */
	public function admin_process_shop_order_meta( $post_id, $post ) {

		// _pickup_location is for pre WC 2.1 support
		if ( isset( $_POST['_pickup_location'] ) || isset( $_POST['pickup_location'] ) ) {
			$local_pickup_plus = $this->get_local_pickup_plus();
			$local_pickup_plus->admin_process_shop_order_meta( $post_id, $post );
		}
	}


	/** Frontend methods ******************************************************/


	/**
	 * Remove the '(Free)' text from the shipping method label which is
	 * displayed on the cart/checkout pages when the cost of the shipping
	 * method is zero, and looks pretty funky when we add in the (save $5)
	 * text when there is a discount
	 *
	 * @since 1.4.4
	 * @param string $full_label the shipping method full label including price
	 * @param WC_Shipping_Rate $method the shipping method rate object
	 */
	public function cart_shipping_method_full_label( $full_label, $method ) {

		if ( self::METHOD_ID == $method->id ) {
			$local_pickup_plus = $this->get_local_pickup_plus();
			$full_label = $local_pickup_plus->cart_shipping_method_full_label( $full_label, $method );
		}

		return $full_label;
	}


	/**
	 * If the chosen shipping method is Local Pickup Plus apply any discounts
	 * on the cart/checkout pages
	 *
	 * @since 1.4.4
	 * @param WC_Cart the cart object
	 */
	public function apply_discount( $cart ) {

		$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' ) ? WC()->session->get( 'chosen_shipping_methods' ) : array();

		if ( in_array( self::METHOD_ID, $chosen_shipping_methods ) ) {
			$local_pickup_plus = $this->get_local_pickup_plus();
			$local_pickup_plus->apply_discount( $cart );
		}
	}


	/**
	 * Makes the pickup location select box a chosen element if the woocommerce
	 * chosen option is enabled.
	 *
	 * @since 1.4
	 */
	public function frontend_scripts() {

		$chosen_en = 'yes' == get_option( 'woocommerce_enable_chosen' ) ? true : false;

		if ( is_checkout() ) {

			$local_pickup_plus = $this->get_local_pickup_plus();

			if ( $local_pickup_plus->is_available( array() ) && ( $chosen_en || $local_pickup_plus->hide_shipping_address() ) ) {
				ob_start();

				if ( $chosen_en ) {
					?>
					// Pickup location chosen select on page load and checkout updated
					$( 'body' ).bind( 'updated_checkout', function() {
						$( 'select.pickup_location' ).chosen();
					} );
					<?php
				}

				if ( $local_pickup_plus->hide_shipping_address() ) {
					?>
					$( 'body' ).bind( 'updated_checkout', function() {

						var methods = new Array();
						$.each( $( '.shipping_method' ), function( index, el ) {
							if ( 'select' == $( el ).prop( 'tagName' ).toLowerCase() ) {
								methods.push( $( el ).val() );
							} else if ( 'input' == $( el ).prop( 'tagName' ).toLowerCase() && 'hidden' == $( el ).prop( 'type' ).toLowerCase() ) {
								methods.push( $( el ) .val() );
							} else if ( 'input' == $( el ).prop( 'tagName' ).toLowerCase() && 'radio' == $( el ).prop( 'type' ).toLowerCase() ) {
								if ( $( el ).is( ':checked' ) ) {
									methods.push( $( el ).val() );
								}
							}
						} );

						// local pickup plus only for shipping methods?
						var localPickupPlusOnly = true;
						$.each( methods, function( index, method ) {
							if ( '<?php echo self::METHOD_ID; ?>' != method ) {
								localPickupPlusOnly = false;
								return false; // break the loop
							}
						} );

						if ( localPickupPlusOnly && ! $( '#ship-to-different-address-checkbox' ).prop( 'checked' ) ) {
							// only local pickup plus is being used, hide the shipping address fields
							$( '#shiptobilling, #ship-to-different-address' ).hide();
							$( '#shiptobilling, #ship-to-different-address' ).parent().find( 'h3' ).hide();
							$( '.shipping_address' ).hide();
						} else {
							// some other shipping method is being used, show the shipping address fields
							$( '#shiptobilling, #ship-to-different-address' ).show();
							$( '#shiptobilling, #ship-to-different-address' ).parent().find( 'h3' ).show();
							if ( ( $( '#shiptobilling input' ).length > 0 && ! $( '#shiptobilling input' ).is( ':checked' ) ) || $( '#ship-to-different-address input' ).is( ':checked' ) ) {
								$( '.shipping_address' ).show();
							}
						}
					} );
					<?php
				}

				$javascript = ob_get_clean();
				wc_enqueue_js( $javascript );
			}
		}
	}


	/**
	 * Record the chosen pickup_location from the order review form, if set.
	 * This isn't guaranteed to be called, but if the shipping method is
	 * switched from 'local pickup' to something else, we'll record the selected
	 * pickup location
	 *
	 * @since 1.4
	 * @param string $post_data the data sent from the checkout form
	 */
	public function checkout_update_order_review( $post_data ) {

		$local_pickup_plus = $this->get_local_pickup_plus();

		if ( $local_pickup_plus->is_available( array() ) ) {
			$local_pickup_plus->checkout_update_order_review( $post_data );
		}
	}


	/**
	 * Validate the selected pickup location
	 *
	 * @since 1.4.1
	 * @param array $posted data from checkout form
	 */
	public function after_checkout_validation( $posted ) {

		$local_pickup_plus = $this->get_local_pickup_plus();

		if ( $local_pickup_plus->is_available( array() ) ) {
			$local_pickup_plus->after_checkout_validation( $posted );
		}
	}


	/**
	 * Display the pickup location on the 'thank you' page, and the order review
	 * page accessed from the user account
	 *
	 * @since 1.4
	 * @param int $order_id order identifier
	 */
	public function order_pickup_location( $order_id ) {

		$order = SV_WC_Plugin_Compatibility::wc_get_order( $order_id );

		if ( $order->has_shipping_method( self::METHOD_ID ) ) {
			$local_pickup_plus = $this->get_local_pickup_plus();
			$local_pickup_plus->order_pickup_location( $order );
		}
	}


	/**
	 * Don't display the shipping address if Local Pickup Plus is the only
	 * shipping method
	 *
	 * @since 1.8.1
	 * @param array $hide_shipping_address array of shipping methods that don't require a shipping address
	 * @param array $hide_shipping_address
	 */
	public function hide_shipping_address( $hide_shipping_address ) {

		return array_merge( $hide_shipping_address, array( self::METHOD_ID ) );
	}


	/** Email methods ******************************************************/


	/**
	 * Swoop in and grab the order_id from the *_notification actions that are
	 * invoked in order to send the emails.  Store the relevant order_id so that
	 * it's available from within the email_pickup_location() method below, which
	 * is hooked to the woocommerce_after_template_part action and thus does not
	 * have access to the order being emailed
	 *
	 * @since 1.4
	 * @see WC_Local_Pickup_Plus::email_pickup_location()
	 * @param int|array $arg either an order identifier or array with order_id member
	 */
	public function store_order_id( $arg ) {

		if ( is_int( $arg ) ) {
			$this->email_order_id = $arg;
		} elseif ( is_array( $arg ) && array_key_exists( 'order_id', $arg ) ) {
			$this->email_order_id = $arg['order_id'];
		}
	}


	/**
	 * Display the pickup location on the 'admin new order', 'customer completed order'
	 * 'customer note' admin new order' emails
	 *
	 * @since 1.4
	 * @see WC_Local_Pickup_Plus::store_order_id()
	 */
	public function email_pickup_location( $template_name, $template_path, $located ) {

		// nothing to do here
		if ( ! $this->email_order_id ) {
			return;
		}

		$order = SV_WC_Plugin_Compatibility::wc_get_order( $this->email_order_id );

		// also nothing to do here
		if ( ! $order->has_shipping_method( self::METHOD_ID ) ) {
			return;
		}

		$local_pickup_plus = $this->get_local_pickup_plus();

		if ( 'emails/email-addresses.php' == $template_name ) {
			$local_pickup_plus->email_pickup_location( $order );
		} else if ( 'emails/plain/email-addresses.php' == $template_name ) {
			$local_pickup_plus->email_pickup_location_plain( $order );
		}
	}


	/**
	 * Validates the cart items with the selected shipping method.
	 *
	 * @since 1.4.2
	 */
	public function check_cart_items() {

		$local_pickup_plus = $this->get_local_pickup_plus();

		if ( $local_pickup_plus->is_available( array() ) ) {
			if ( is_cart() ) {
				// From the cart page we need to defer validation until after cart totals (and shipping methods) have
				//  been calculated.  This is so the local pickup plus option can be removed from the available list
				//  if Categories Only are enabled and the cart contains no products that can be picked up.  Otherwise
				//  we would get an extra error message in that situation *after* the pickup product was removed from
				//  the cart
				add_action( 'woocommerce_calculate_totals', array( $local_pickup_plus, 'woocommerce_check_cart_items' ) );
			} else {
				// normal behavior, validate the cart items
				$local_pickup_plus->woocommerce_check_cart_items();
			}
		}
	}


	/** Helper methods ******************************************************/


	/**
	 * Returns the plugin name, localized
	 *
	 * @since 1.5
	 * @see SV_WC_Payment_Gateway::get_plugin_name()
	 * @return string the plugin name
	 */
	public function get_plugin_name() {
		return __( 'WooCommerce Local Pickup Plus', self::TEXT_DOMAIN );
	}


	/**
	 * Returns __FILE__
	 *
	 * @since 1.5
	 * @return string the full path and filename of the plugin file
	 */
	protected function get_file() {
		return __FILE__;
	}


	/**
	 * Gets the local pickup plus gateway object
	 * @since 1.4
	 *
	 * @return WC_Shipping_Local_Pickup_Plus local pickup plus gateway object
	 */
	private function get_local_pickup_plus() {

		if ( ! is_object( $this->gateway_class ) ) {
			$this->gateway_class = new WC_Shipping_Local_Pickup_Plus();
		}

		return $this->gateway_class;
	}


	/** Lifecycle methods ******************************************************/


	/**
	 * Perform any initial install steps
	 *
	 * @since 1.5
	 * @see SV_WC_Plugin::install()
	 */
	protected function install() {

		if ( get_option( 'woocommerce_pickup_locations' ) ) {
			// upgrading from pre-versioned plugin, set a default country for all
			//  pickup locations that are missing this due to an old bug

			add_action( 'woocommerce_init', array( $this, 'set_default_location_country' ) );
		}
	}


	/**
	 * Set a default location country when upgrading from pre-versioned plugin
	 * with an old bug
	 *
	 * @since 1.5
	 */
	public function set_default_location_country() {

		if ( $pickup_locations = get_option( 'woocommerce_pickup_locations' ) ) {

			foreach ( $pickup_locations as $key => $location ) {
				if ( ! isset( $location['country'] ) || ! $location['country'] ) {
					$pickup_locations[ $key ]['country'] = WC()->countries->get_base_country();
				}
			}

			update_option( 'woocommerce_pickup_locations', $pickup_locations );
		}
	}


} // WC_Local_Pickup_Plus


/**
 * The WC_Local_Pickup_Plus global object
 * @name $wc_local_pickup_plus
 * @global WC_Local_Pickup_Plus $GLOBALS['wc_local_pickup_plus']
 */
$GLOBALS['wc_local_pickup_plus'] = new WC_Local_Pickup_Plus();

} // init_woocommerce_shipping_local_pickup_plus()