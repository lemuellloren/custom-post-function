<?php
/**
 * Empty cart page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices();

?>

<p>
    <?php _e( 'Your cart is currently empty.', 'woocommerce' ) ?>
    <a class="btn bf-btn bf-btn--violet wc-backward" href="<?php echo apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ); ?>">
    <span class="fa fa-shopping-cart"></span> <?php _e( 'Return To Shop', 'woocommerce' ) ?>
    </a>
</p>

<?php do_action( 'woocommerce_cart_is_empty' ); ?>
