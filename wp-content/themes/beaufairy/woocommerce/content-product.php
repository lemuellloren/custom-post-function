<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
	return;
}

?>
<div class="product-tile post-tile col-md-3 col-sm-4 col-xs-4">
    <figure class="product-tile__figure figure figure--violet">
        <div class="figure__img">
            <?php echo get_product_thumbnail( 'shop_catalog', array('class' => 'img-responsive') ) ?>                
        </div> <!-- .figure__img -->
        <a href="<?php the_permalink() ?>">
            <figcaption class="product-tile__link figure__caption text-center">
                <h5 class="figure__title"><?php echo __( 'View Details', 'beaufairy') ?></h5>
            </figcaption> <!-- .figure__caption -->
        </a>
        <?php woocommerce_template_loop_add_to_cart() ?>
    </figure> <!-- .figure -->
    <span class="product-tile__brand text-ellipsis"><?php the_title() ?></span>
    <?php woocommerce_template_loop_price() ?>
    </span>
</div>
