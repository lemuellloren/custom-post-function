<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $product;

/**
* woocommerce_before_single_product hook
*
* @hooked wc_print_notices - 10
*/
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
   echo get_the_password_form();
   return;
}

wc_print_notices();

?>
<div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="module module--no-border">
        <article class="product-single row">
            <div class="product-single__image col-md-5 col-sm-6">
                <figure class="figure">
                    <div class="figure__img">
                        <?php woocommerce_show_product_images() ?>
                    </div>
                </figure>
            </div>
            <div class="col-md-7 col-sm-6">
                <?php woocommerce_template_single_title() ?>
                
                <h3><?php echo $product->get_price_html() ?></h3>
                
                <p><?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ) ?></p>
                            
                <?php woocommerce_template_single_add_to_cart() ?>            
            </div>
            <?php echo do_shortcode( '[woocommerce_social_media_share_buttons]' ); ?>
        </article> <!-- .row -->
    </div> <!-- .module -->
</div>
<?php woocommerce_output_related_products() ?>
