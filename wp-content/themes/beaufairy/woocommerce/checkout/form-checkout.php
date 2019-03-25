<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}

// filter hook for include new pages inside the payment method
$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', WC()->cart->get_checkout_url() ); ?>

<section class="checkout">
	<form name="checkout" method="post" class="form form--checkout checkout woocommerce-checkout" action="<?php echo esc_url( $get_checkout_url ); ?>" enctype="multipart/form-data">
    	<div class="row">
	    	<div class="checkout__form col-md-6 col-sm-6">
				<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>

					<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
					<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	                    
	                    <div class="panel panel--active panel-default" data-panel-title="<?php echo __( 'Billing Info', 'beaufairy' ) ?>">
	                        <?php do_action( 'woocommerce_checkout_billing' ); ?>           
						</div> <!-- .panel -->

						<?php do_action( 'woocommerce_checkout_shipping' ); ?>

						<div class="panel panel-default">
							<div class="panel-heading uppercase" role="tab" id="reviewPanel">
							    <h4 class="panel-title clearfix">
							    	<?php echo __( 'Review and Confirm', 'woocommerce' ) ?>
							    	<span class="pull-right panel-edit">Edit</span>
							    </h4>
							</div>

							<div class="panel-collapse collapse" role="tabpanel" aria-labelledby="reviewPanel">
						        <div class="panel-body">
									<p>
										<?php echo __( 
											'Please review all of the information below and if necessary, make changes before placing order.', 
											'woocommerce' 
										) ?>
									</p>
									<div class="checkout-summary"></div>
								</div>
							</div>
						</div>
					</div> <!-- .panel-group -->
					<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

				<?php endif; ?>
			</div> <!-- .checkout__form -->
			<div class="checkout__orders col-md-6 col-sm-6">
				<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
				
				<div id="order_review" class="woocommerce-checkout-review-order">
					<h3 id="order_review_heading"><?php _e( 'Your order', 'woocommerce' ); ?></h3>
					<?php do_action( 'woocommerce_checkout_order_review' ); ?>
				</div>

				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
			</div> <!-- .checkout__orders -->
		</div><!-- .row -->
	</form>
</section>
