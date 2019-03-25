<?php
/**
 * Checkout shipping information form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php if ( WC()->cart->needs_shipping_address() === true ) { ?>

	<?php
		if ( empty( $_POST ) ) {

			$ship_to_different_address = get_option( 'woocommerce_ship_to_destination' ) === 'shipping' ? 1 : 0;
			$ship_to_different_address = apply_filters( 'woocommerce_ship_to_different_address_checked', $ship_to_different_address );

		} else {

			$ship_to_different_address = $checkout->get_value( 'ship_to_different_address' );

		}
	?>
	<div class="panel panel-default" data-panel-title="<?php echo __( 'Shipping Info', 'beaufairy' ) ?>">
		<div class="panel-heading uppercase" role="tab" id="shippingPanel">
		    <h4 class="panel-title clearfix">
		    	<?php echo __( 'Shipping Details', 'woocommerce' ) ?>
		    	<span class="pull-right panel-edit">Edit</span>
		    </h4>
		</div>

		<div class="panel-collapse collapse" role="tabpanel" aria-labelledby="shippingPanel">
	        <div class="panel-body">
				<div class="woocommerce-shipping-fields container-fluid">
					<p id="ship-to-different-address" class="uppercase form-row">
						<input id="ship-to-different-address-checkbox" class="input-checkbox different" <?php checked( $ship_to_different_address, 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" data-message="<?php echo __( 'Same as billing', 'woocommerce' ) ?>" />
						<label for="ship-to-different-address-checkbox" class="checkbox" style="display:inline"><?php _e( 'Ship to a different address?', 'woocommerce' ); ?></label>
					</p>

					<div class="shipping_address">

						<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

						<?php foreach ( $checkout->checkout_fields['shipping'] as $key => $field ) { ?>

							<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

						<?php } ?>

						<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

					</div>

					<div class="form-row">
						<div class="pull-right">				
							<span class="next btn bf-btn bf-btn--pink"><?php echo __( 'Next', 'woocommerce' ) ?></span>
						</div>
					</div>
				</div> <!-- .container-fluid -->
			</div>
		</div>
	</div>
<?php } ?>

<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', get_option( 'woocommerce_enable_order_comments', 'yes' ) === 'yes' ) ) { ?>
	<div class="panel panel-default" data-panel-title="<?php echo __( 'Additional Details', 'beaufairy' ) ?>">
		<div class="panel-heading uppercase" role="tab" id="additionalPanel">
		    <h4 class="panel-title clearfix">
		    	<?php echo __( 'Additional Details', 'woocommerce' ) ?>
		    	<span class="pull-right panel-edit">Edit</span>
		    </h4>
		</div>

		<div class="panel-collapse collapse" role="tabpanel" aria-labelledby="additionalPanel">
	        <div class="panel-body">
	        	<div class="container-fluid">
					<?php foreach ( $checkout->checkout_fields['order'] as $key => $field ) { ?>
						<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
					<?php } ?>
					
					<div class="form-row">
						<div class="pull-right">				
							<span class="next btn bf-btn bf-btn--pink"><?php echo __( 'Next', 'woocommerce' ) ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php } ?>

<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
		
