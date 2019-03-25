<?php
/**
 * Product quantity inputs
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="quantity-input">
    <?php /* Button to decrement the quantity input value */ ?>
    <span class="quantity-input__minus hidden-xs">
        <i class="fa fa-minus"></i>
    </span>

    <input class="quantity-count" type="text" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $input_value ); ?>" title="<?php _ex( 'Qty', 'Product quantity input tooltip', 'woocommerce' ) ?>" />

    <?php /* Button to increment the quantity input value */ ?>
    <span class="quantity-input__plus hidden-xs">
        <i class="fa fa-plus"></i>
    </span>
</div> <!-- .quantity-input -->