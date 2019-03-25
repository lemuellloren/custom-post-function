<?php
// content function 
include 'function-content.php';

if ( ! function_exists( 'allow_php' ) ) {
    
    /**
     * Allow a widget text to recognize a php script.
     * http://www.emanueleferonato.com
     *
     * @param  string  $html
     * @return string
     */
    function allow_php( $html ) {
        if( strpos($html,"<"."?php" ) !== false ) {
            ob_start();
            eval("?".">".$html);
            $html = ob_get_contents();
            ob_end_clean();
        }

         return $html;
    }

    add_filter( 'widget_text', 'allow_php', 100 );
}

function get_current_user_role() {
	global $wp_roles;
	$current_user = wp_get_current_user();
	$roles = $current_user->roles;
	$role = array_shift($roles);
	return isset($wp_roles->role_names[$role]) ? strtolower(translate_user_role($wp_roles->role_names[$role] )) : false;
}

if ( ! function_exists( 'beaufairy_register_site_resources' ) ) {

    /**
     * Registers beaufairy site-wide resources
     *
     * @return void
     */
    function beaufairy_register_site_resources() {
        // bunch of vendor css styles
        wp_enqueue_style( 'vendor', get_template_directory_uri() . '/css/main-vendor.min.css' );
        // main css file
        wp_enqueue_style( 'style', get_stylesheet_uri() );
        // register the main js file
        wp_register_script( 'all', get_template_directory_uri() . '/js/all.min.js', null, '', true );
        // enqueue the main js file
        wp_enqueue_script( 'all' );
    }

    add_action( 'wp_enqueue_scripts', 'beaufairy_register_site_resources', 5 );
}

if ( ! function_exists( 'beaufairy_register_checkout_resource' ) ) {

    /**
     * Registers beaufairy checkout resource
     *
     * @return void
     */
    function beaufairy_register_checkout_resource() {
        if ( is_checkout() && ! is_wc_endpoint_url() ) {
            wp_register_script( 'checkout', get_template_directory_uri() . '/js/checkout.js', null, '', true );
            wp_enqueue_script( 'checkout' );
        }
    }

    add_action( 'wp_enqueue_scripts', 'beaufairy_register_checkout_resource', 10 );
}


if ( ! function_exists( 'beaufairy_register_nav' ) ) {

    /**
     * Setup menu locations
     *
     * @return void
     */
    function beaufairy_register_nav() {

        /**
         * Primary navigation
         */
        register_nav_menu( 'main-menu', __( 'Main menu' ) );
    }

    add_action( 'init', 'beaufairy_register_nav' );
}

if ( ! function_exists( 'beaufairy_widgets_init' ) ) {

    /**
     * Register beaufairy widgets
     *
     * @return void
     */
    function beaufairy_widgets_init() {

        /**
         * Uppermost left column
         */
        register_sidebar([
            'name'          => 'Header left column',
            'id'            => 'header-left-column',
            'before_widget' => '<div>',
            'after_widget'  => '</div>',
            'before_title'  => '<p>',
            'after_title'   => '</p>'
        ]);

        /**
         * Uppermost right column
         */
        register_sidebar([
            'name'          => 'Header right column',
            'id'            => 'header-right-column',
            'before_widget' => '<div>',
            'after_widget'  => '</div>'
        ]);

        /**
         * Home middle row widget
         */
        register_sidebar([
            'name'          => 'Home Middle Row',
            'id'            => 'home-middle-row',
            'before_widget' => '<div>',
            'after_widget'  => '</div>',
        ]);

        /**
         * Home third row widget
         */
        register_sidebar([
            'name'          => 'Home Bottom Row',
            'id'            => 'home-bottom-row',
            'before_widget' => '<section class="piece"><div class="module-apart">',
            'after_widget'  => '</div></section>',
            'before_title'  => '<h2 class="module-apart__heading module-apart__heading--big module-apart__heading--center">
                                <span class="text text-muted">',
            'after_title'   => '</span></h2>'
        ]);

        /**
         * First column on the footer
         */
        register_sidebar([
            'name'          => 'Footer first column',
            'id'            => 'footer-first-column',
            'before_widget' => '<div class="site-footer__col">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="site-footer__heading">',
            'after_title'   => '</h3>'
        ]);

        /**
         * Second column on the footer
         */
        register_sidebar([
            'name'          => 'Footer second column',
            'id'            => 'footer-second-column',
            'before_widget' => '<div class="site-footer__col">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="site-footer__heading">',
            'after_title'   => '</h3>'
        ]);

        /**
         * Third column on the footer
         */
        register_sidebar([
            'name'          => 'Footer third column',
            'id'            => 'footer-third-column',
            'before_widget' => '<div class="site-footer__col">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="site-footer__heading">',
            'after_title'   => '</h3>'
        ]);

    }

    add_action( 'widgets_init', 'beaufairy_widgets_init' );
}

if ( ! function_exists( 'beaufairy_paging_nav' ) ) {

    /**
     * Tweaked gist from a big-hearted fellow.
     * Show a beaufairy pagination widget.
     *
     * @return void
     */
    function beaufairy_paging_nav( $query = null ) {

        if ( is_singular() ) {
            return;
        }

        global $wp_query;

        if ( ! is_null( $query ) ) {
            $wp_query = $query;
        }
        
        // if there is only one page, then abort.
        if ( $wp_query->max_num_pages <= 1 ) {
            return;
        }

        $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;

        $max = intval( $wp_query->max_num_pages );

        // Add current page to the array
        if ( $paged >= 1 ) {
            $links[] = $paged;
        }

        // Add the pages around the current page to the array
        if ( $paged >= 3 ) {
            $links[] = $paged - 1;
            $links[] = $paged - 2;
        }
        if ( ( $paged + 2 ) <= $max ) {
            $links[] = $paged + 2;
            $links[] = $paged + 1;
        }

        echo '<div class="pagination_wrapper"><ul class="pagination">' . "\n";

        // Previous Post Link
        if ( get_previous_posts_link() ) {
            printf('<li class="pagination__maneuver">%s</li>' . "\n", get_previous_posts_link( "<span aria-hidden=\"true\" class=\"fa fa-chevron-left\"></span>" ) );
        }

        // Link to first page, plus ellipses if necessary
        if ( ! in_array( 1, $links ) ) {

            $class = 1 == $paged ? ' class="active"' : '';
            printf('<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );
            if ( ! in_array( 2, $links ) ) {
                echo '<li><a class="btn disabled">…</a></li>';
            }
        }

        // Link to current page, plus 2 pages in either direction if necessary
        sort( $links );
        foreach ( ( array ) $links as $link ) {
            $class = $paged == $link ? ' class="active"' : '';
            printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
        }
        
        // Link to last page, plus ellipses if necessary
        if ( ! in_array( $max, $links ) ) {
            if ( ! in_array( $max - 1, $links ) ) {
                echo '<li><a class="btn disabled">…</a></li>' . "\n";
            }
            $class = $paged == $max ? ' class="active"' : '';
            printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
        }
        
        // Next Post Link
        if ( get_next_posts_link() ) {
            printf( '<li class="pagination__maneuver">%s</li>' . "\n", get_next_posts_link( "<span aria-hidden=\"true\" class=\"fa fa-chevron-right\"></span>" ) );
        }

        echo '</ul></div>' . "\n";
    }
}

if ( ! function_exists( 'custom_excerpt_length' ) ) {

    /**
     * Custom excerpt length.
     *
     * @param  int  $length
     * @return int
     */
    function custom_excerpt_length( $length ) {
        return 15;
    }

    add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );
}

if ( ! function_exists( 'get_category_filter_opening_tag' ) ) {

    /**
     * Print a category filter opening tag.
     *
     * @param  bool  $is_active
     * @return void
     */
    function get_category_filter_opening_tag( $is_active ) {

        if ( $is_active ) {
            echo "<li class='active'>";
        } else {
            echo "<li>";
        }
    }

    add_action( 'before_category_filter_link', 'get_category_filter_opening_tag', 10, 1 );
}

if ( ! function_exists( 'get_current_product_categories' ) ) {

    /**
     * Get a current product's categories.
     *
     * @return array
     */
    function get_current_product_categories() {

        global $post;

        $categories = array();

        foreach( get_the_terms( $post->ID, 'product_cat' ) as $category ) {
            $categories[] = $category->name;
        }

        if ( empty( $categories ) ) return [ __( 'Uncategorized', 'beaufairy' ) ];
        
        return $categories;
    }
}

if ( ! function_exists( 'get_product_thumbnail' ) ) {

    /**
     * Get the product thumbnail, or the placeholder if not set.
     * woocommerce_get_product_thumbnail() alternative method.
     *
     * @subpackage  Loop
     * @param  string  $size (default: 'shop_catalog')
     * @param  array  $attr
     * @return string
     */
    function get_product_thumbnail( $size = 'shop_catalog', $attr = array() ) {
        global $post;

        if ( has_post_thumbnail() ) {
            return get_the_post_thumbnail( $post->ID, $size, $attr );
        } elseif ( wc_placeholder_img_src() ) {
            return get_product_placeholder_img( $size );
        }
    }
}

if ( ! function_exists( 'get_product_placeholder_img' ) ) {

    /**
     * Get product placeholder image.
     * 
     * @param  mixed  $size
     * @return string
     */
    function get_product_placeholder_img( $size = 'shop_thumbnail' ) {
        $dimensions = wc_get_image_size( $size );

        return apply_filters('woocommerce_placeholder_img', '<img src="' . wc_placeholder_img_src() . '" alt="' . __( 'Placeholder', 'woocommerce' ) . '" width="' . esc_attr( $dimensions['width'] ) . '" class="woocommerce-placeholder wp-post-image img-responsive" height="' . esc_attr( $dimensions['height'] ) . '" />', $size, $dimensions );
    }
}

if ( ! function_exists( 'woocommerce_header_add_to_cart_fragment' ) ) {

    /**
     * Ajaxify the cart viewer.
     * http://docs.woothemes.com/document/show-cart-contents-total
     *
     * @param  array  $fragments
     * @return array
     */
    function woocommerce_header_add_to_cart_fragment( $fragments ) {
        ob_start();
        
        get_woo_cart_link();

        $fragments['a.cart-contents'] = ob_get_clean();
        
        return $fragments;
    }

    // Ensure cart contents update when products are added to the cart via AJAX (place the following in functions.php)
    add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );
}

if ( ! function_exists( 'get_woo_cart_link' ) ) {

    /**
     * Custom woocommerce cart anchor tag.
     *
     * @return void
     */
    function get_woo_cart_link() {
        ?>
        <a class="cart-contents" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>">
            <span class="text"><?php echo __( 'My Bag', 'beaufairy' ) ?></span>
            <span class="fa fa-suitcase"></span>
            <?php echo sprintf( '(%d)', WC()->cart->cart_contents_count ); ?>
        </a>
        <?php
    }
}

if ( ! function_exists( 'get_woo_account_link' ) ) {

    /**
     * Custom woocommerce account anchor tag.
     *
     * @param  string  $unauth_text
     * @param  string  $loggedin_text
     * @return void
     */
    function get_woo_account_link( $unauth_text = 'Login / Register', $loggedin_text = 'Account' ) {

        $display_text = $unauth_text;

        if ( is_user_logged_in() ) {
            $display_text = $loggedin_text;
        }

        ?>
        <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>">
            <?php printf(
                "<span class='text'>%s</span> ", 
                __( $display_text, 'beaufairy' ));
            ?>
            <span class='fa fa-user'></span>
        </a>
        <?php
    }
}

if ( ! function_exists( 'get_woo_logout_link' ) ) {

    function get_woo_logout_link() {

        if ( is_user_logged_in() ) {
            ?>
                <li class="logout-link">
                    <a href="<?php echo wp_logout_url(); ?>">
                        <span class='text'>Logout</span>
                        <span class='fa fa-sign-out'></span>
                    </a>
                </li>
            <?php
        }
    } 
}

if ( ! function_exists( 'customize_checkout_field' ) ) {

    /**
     * Customize the checkout fields attributes.
     *
     * @param  array  $fields
     * @return mixed
     */
    function customize_checkout_field( $fields ) {

        foreach ( $fields as &$fieldset ) {
            foreach ( $fieldset as &$field ) {
                $field['input_class'][] = 'form-control';
            }
        }

        return $fields;
    }

    add_filter( 'woocommerce_checkout_fields', 'customize_checkout_field' );
}

if ( ! function_exists( 'woo_dequeue_select2' ) ) {

    /**
     * Disable woocommerce select2 plugin.
     *
     * @return void
     */
    function woo_dequeue_select2() {     
        if ( class_exists( 'woocommerce' ) ) {
            wp_dequeue_style( 'select2' );
            wp_deregister_style( 'select2' );
            wp_dequeue_script( 'select2' );
            wp_deregister_script('select2' );
        }  
    }

    add_action( 'wp_enqueue_scripts', 'woo_dequeue_select2', 100 );
}

function send_mail($to="", $subject="", $content="") {
    add_filter( 'wp_mail_content_type', 'set_html_content_type' );
    $status = wp_mail($to, $subject, $content);
    remove_filter( 'wp_mail_content_type', 'set_html_content_type');
}

function set_html_content_type() {
    return 'text/html';
}

/*
 * Send an email when WooCommerce receives an order complete
 */
function order_completed($order_id) {
    $order = new WC_Order( $order_id );
    $to = "sales@beaufairy.com";
    $subject = "";
    $content = "";
    $first_name = $order->billing_first_name;
    $last_name = $order->billing_last_name;
    $order_url = $order->get_view_order_url();
    $total = $order->get_total();
    $subject = $first_name . " " . $last_name . " has sent an order";
    $content .= "<p>First Name : $first_name</p>";
    $content .= "<p>Last Name  : $last_name</p>";
    $content .= "<p>Order Total: $total</p>";
    $content .= "<br/>";
    $content .= "<p>Please click the link below to view the order</p>";
    $content .= "<a href='$order_url' target='_blank'>$order_url</a>";

    send_mail($to, $subject, $content);
}
add_action( 'woocommerce_order_status_completed', 'order_completed' );
add_action( 'woocommerce_order_status_on-hold', 'order_completed' );
/**
 * Display 12 products per page.
 * http://docs.woothemes.com/document/change-number-of-products-displayed-per-page/
 */
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 12;' ), 20 );

remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );
?>