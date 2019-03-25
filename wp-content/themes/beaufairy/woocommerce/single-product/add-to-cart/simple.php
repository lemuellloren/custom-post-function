<?php
/**
 * Simple product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

?>

<?php
	// Availability
	$availability      = $product->get_availability();
	$availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>';

	echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product );
?>

<?php if ( $product->is_in_stock() ) : ?>

	<h3 class="text-muted hidden-xs"><?php echo __( 'Quantity' ) ?></h3>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<div class="quantity-input-group">
	
		<form class="cart" method="post" enctype='multipart/form-data'>
		 	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

			<label class="visible-xs">Quantity</label>
		 	
		 	<?php
		 		if ( ! $product->is_sold_individually() )
		 			woocommerce_quantity_input( array(
		 				'min_value' => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
		 				'max_value' => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product )
		 			) );
		 	?>

		 	<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />

		 	<button type="submit" class="btn bf-btn bf-btn--violet single_add_to_cart_button alt"><?php echo $product->single_add_to_cart_text(); ?></button>

			<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
		</form>

	</div> <!-- .quantity-input-group -->

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>
