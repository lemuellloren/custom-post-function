<?php
class MLM_Role_Discount {

	/**
	 * Holder for options class
	 * @var MLM_Options
	 */
	public $options;

	/**
	 * Holder for coupons class
	 * @var MLM_Coupons
	 */
	public $coupons;

	/**
	 * Class constructor
	 */
	function __construct() {
		// On wordpress init
		add_action( 'init', array( $this, 'wordpress_init' ) );

		// Load multilingual support
		load_plugin_textdomain( MLM_PLUGIN_NAME, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Called when WordPress has initialized
	 * @return void
	 */
	function wordpress_init() {
		// Check if we have WooCommerce active
		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
		 return FALSE;

		// Create options page
		$this->options 	= new MLM_Options;

		// Create coupons handling
		$this->coupons	= new MLM_Coupons;

		// Add role's related coupons
		$this->role_coupons_addition();
	}

	/**
	 * Add role's related coupons if we can
	 */
	function role_coupons_addition() {
		// Get WooCommerce
		global $woocommerce;

		// Check if user is logged in
		if( ! is_user_logged_in() ) return FALSE;

		// Get current user information (and roles)
		$user	= wp_get_current_user();
		$roles	= $user->roles;

		// Go through user roles
		foreach( $roles as $role ) {
			// Get role coupon key and ID in database
			$coupon_code	= $this->coupons->mlm_get_coupon_key( $role );
			$coupon_id 		= $this->coupons->mlm_get_coupon_id( $coupon_code );

			// Check if we got the coupon ID and we are not on administration side and we have not added the coupon
			if( $coupon_id && isset( $woocommerce->cart->applied_coupons ) && ! in_array( $coupon_code, $woocommerce->cart->applied_coupons ) && ! is_admin() ) {
				// Apply a new coupon
				$woocommerce->cart->applied_coupons[] = $coupon_code;

				// Do WooCommerce's action
				do_action( 'woocommerce_applied_coupon', $coupon_code );
			}
		}
	}
}
class MLM_Coupons {
	/**
	 * Simple class constructor
	 */
	function __construct() {
		// When user has added, updated or deleted a role
		add_action( 'mlm_ecom_new_role', array( $this, 'create_coupon' ), 10, 3 );
		add_action( 'mlm_ecom_updated_role', array( $this, 'update_coupon' ), 10, 3 );
		add_action( 'mlm_ecom_removed_role', array( $this, 'delete_coupon' ), 10, 1 );
		add_action( 'before_delete_post', array( $this, 'before_coupon_delete' ), 10, 1 );
	}

	/**
	 * Create new coupon
	 * @param  string $name Role name
	 * @param  string $slug Role slug
	 * @param  array  $args Role (coupon) settings
	 * @return void
	 */
	function create_coupon( $name, $slug, $args ) {
		// Get coupon key
		$role_coupon	= $this->mlm_get_coupon_key( $slug );

		// Insert new coupon
		$coupon_id		= wp_insert_post( array(
				'post_title'	=> $role_coupon,
				'post_parent'	=> 0,
				'post_type'		=> 'shop_coupon',
				'post_status'	=> 'publish',
				'post_content'	=> ' ',
				'post_excerpt'	=> sprintf( __( 'For %s', MLM_PLUGIN_NAME ), $name )
			)
		);

		// Add coupon related post meta
		add_post_meta( $coupon_id, 'discount_type', ( $args['discount_amount'] == 'amount' ) ? 'fixed_cart' : 'percent' );
		add_post_meta( $coupon_id, 'coupon_amount', $args['discount'] );
		add_post_meta( $coupon_id, 'individual_use', 'no' );
		add_post_meta( $coupon_id, 'apply_before_tax', 'no' );
		add_post_meta( $coupon_id, 'free_shipping', 'no' );
		add_post_meta( $coupon_id, 'exclude_sale_items', 'no' );

		// Add new coupon to our settings
		$added_coupons	= get_option( 'mlm_ecom_coupons', array() );
		$added_coupons[$coupon_id]= $slug;

		// Update our coupons
		update_option( 'mlm_ecom_coupons', $added_coupons );
	}

	/**
	 * Update a coupon
	 * @param  string $name Role name
	 * @param  string $slug Role slug
	 * @param  array  $args Role (coupon) settings
	 * @return void
	 */
	function update_coupon( $name, $slug, $args ) {
		// Get coupon key and ID
		$role_coupon	= $this->mlm_get_coupon_key( $slug );
		$coupon_id		= $this->mlm_get_coupon_id( $role_coupon );

		// Check if we have the coupon
		if( $coupon_id ) {
			// Update coupon settings
			update_post_meta( $coupon_id, 'discount_type', ( $args['discount_amount'] == 'amount' ) ? 'fixed_cart' : 'percent' );
			update_post_meta( $coupon_id, 'coupon_amount', $args['discount'] );
		}
		// Or do we not
		else {
			// Create a new coupon
			$this->create_coupon( $name, $slug, $args );
		}
	}

	/**
	 * Delete a coupon
	 * @param  string $slug Role slug
	 * @return void
	 */
	function delete_coupon( $slug, $delete_post = FALSE ) {
		// Get coupon key and ID
		$role_coupon	= $this->mlm_get_coupon_key( $slug );
		$coupon_id		= $this->mlm_get_coupon_id( $role_coupon );

		// Get all of our coupons
		$all_coupons	= get_option( 'mlm_ecom_coupons', array() );

		// Remove the coupon from our coupons
		unset( $all_coupons[ $coupon_id ] );

		// Update our coupons
		update_option( 'mlm_ecom_coupons', $all_coupons );

		// Delete the coupon entirely
		wp_delete_post( $coupon_id, true );
	}

	/**
	 * Delete the coupon from our settings if user deletes it from Coupons view directly
	 * @param  integer $postid Post that will be deleted
	 * @return void
	 */
	function before_coupon_delete( $postid ) {
		// Check if it is coupon
		if( get_post_type( $postid ) != 'shop_coupon' ) return FALSE;

		// Get all of our coupons
		$all_coupons	= get_option( 'mlm_ecom_coupons', array() );
		
		// Check if we have that coupon
		if( isset( $all_coupons[ $postid ] ) ) {
			// Get the role slug
			$role_slug			= $all_coupons[ $postid ];

			// Get all discounts
			$all_discounts		= get_option( 'mlmrd', array() );

			// Remove discount
			unset( $all_discounts[ $role_slug ] );

			// Remove the coupon from our coupons
			unset( $all_coupons[ $postid ] );

			// Update our coupons and discounts
			update_option( 'mlm_ecom_coupons', $all_coupons );
			update_option( 'mlmrd', $all_discounts );
		}

		// Check if we have a coupon like that
		if( in_array( $coupon_key, $all_coupons ) ) {
			// Remove the coupon from our coupons
			unset( $all_coupons[ $coupon_id ] );

			// Update our coupons
			update_option( 'mlm_ecom_coupons', $all_coupons );
		}
	}

	/**
	 * Fetches the coupon ID
	 * @param  string $coupon_key Coupon key
	 * @return integer            Coupon ID
	 */
	function mlm_get_coupon_id( $coupon_key ) {
		// Get WP database
		global $wpdb;

		// Fetch the ID
		$coupon_id 	= $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'shop_coupon' AND post_status = 'publish'", $coupon_key ) );

		// Return it
		return $coupon_id;
	}

	/**
	 * Generate coupon key
	 * @param  string $role Role slug
	 * @return string       SHA1 encrypted coupon code
	 */
	function mlm_get_coupon_key( $role ) {
		// Get our custom word for hash
		$settings	= get_option( 'mlmrd_settings', array( 'hashword' => 'secret' ) );
		$the_hash	= ( $settings['hashword'] != 'secret' ) ? $settings['hashword'] : 'secret';

		// Generate the key
		return sha1( 'mlmrd_' . $the_hash . $role );
	}
}