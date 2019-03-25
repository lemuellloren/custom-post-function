<?php
/**
 * Loop Price
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
?>

<?php $price = ( $product->get_price_html() ) ? $product->get_price_html() : __( 'Price not set.', 'beaufairy' ); ?>
<p class="product-tile__price text-ellipsis"><?php echo $price; ?></p>
