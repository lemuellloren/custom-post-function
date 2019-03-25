<?php
/*
Plugin Name: Beaufairy Woo Carousel
Plugin URI: http://leetdigital.com
Description: Woocommerce Product Carousel built on top of slick.js
Version: 1.0
Author: Jedi
License: none
*/

// Register frontend assets.
add_action( 'wp_enqueue_scripts', function() {
    // slick stylesheet
    wp_enqueue_style( 'slick', get_template_directory_uri() . '/css/slick.css' );
    // register the carousel js file
    wp_register_script( 'carousel', get_template_directory_uri() . '/js/carousel.js', null, '', true );
    // enqueue the main js file
    wp_enqueue_script( 'carousel' );
}, 10 );

if ( ! function_exists( 'carousel_item_html' ) ) {

    /**
     * The carousel markup.
     *
     * @return string
     */
    function carousel_item_html() {
        return '<div><figure class="figure"><div class="figure__image"><a href="%s">%s</a></div>
                </figure><div class="text-center"><p><a href="%s" class="text-muted">%s</a></p>
                <p class="text-primary text-ellipsis"><strong>%s</strong></p>%s</div></div>';
    }
}

if ( ! function_exists( 'add_to_cart_button' ) ) {

    /**
     * Woocommerce-based cart button.
     *
     * @return string
     */
    function add_to_cart_button() {
        global $product;

        return apply_filters( 'woocommerce_loop_add_to_cart_link',
            sprintf( 
                '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s"
                class="form-control uppercase btn bf-btn bf-btn--plain %s product_type_%s"><b>%s</b></a>',
                esc_url( $product->add_to_cart_url() ),
                esc_attr( $product->id ),
                esc_attr( $product->get_sku() ),
                esc_attr( isset( $quantity ) ? $quantity : 1 ),
                $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                esc_attr( $product->product_type ),
                esc_html( $product->add_to_cart_text() )
            ),
            $product );
    }
}

if ( ! function_exists( 'beaufairy_carousel_shortcode' ) ) {
    
    /**
     * The entire product carousel.
     *
     * @param  array  $atts
     * @return string
     */
    function beaufairy_carousel_shortcode( $atts ) {

        extract( shortcode_atts( array(
            'product_cat' => '',
            'limit'       => 8,
            'by'          => 'latest',
        ), $atts ) );

        $args = array(
            'product_cat'    => $product_cat,
            'post_type'      => 'product',
            'posts_per_page' => $limit,
            'post_status'    => 'publish'
        );

        switch ( $by ) {
            case 'top':
                $args['meta_key'] = 'total_sales';
                $args['orderby'] = 'meta_value_num';
                break;
            
            default:
                $args['orderby'] = 'desc';
                break;
        }

        $loop = new WP_Query( $args );

        if ( $loop->have_posts() ) {
            $html = '<div class="posts-carousel">';
            while ( $loop->have_posts() ) : $loop->the_post();
                global $product;

                $price = ( $product->get_price_html() ) ? $product->get_price_html() : 'Price not set.';

                $html .= sprintf( 
                    carousel_item_html(),
                    get_the_permalink(),
                    get_product_thumbnail( 'shop_catalog', array('class' => 'img-responsive') ),
                    get_the_permalink(),
                    get_the_title(),
                    $price,
                    add_to_cart_button()
                );
            endwhile;
            $html .= '</div>';
        } else {
            $html = '<div class="well container">'. __( 'No products found', 'beaufairy' ) .'</div>';
        }

        wp_reset_postdata();

        return $html;
    }

    add_shortcode('beaufairy_carousel', 'beaufairy_carousel_shortcode');
}