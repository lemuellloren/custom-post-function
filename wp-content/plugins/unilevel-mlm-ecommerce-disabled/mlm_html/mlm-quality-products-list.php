<?php
function mlm_qualification_product_list_page() {
    global $wpdb, $current_user;
    $user_id = $current_user->ID;
    $table_prefix = mlm_core_get_table_prefix();
    $error = '';
    $chk = 'error';
    global $current_user;
    
    $mlm_general_settings = get_option('wp_mlm_general_settings');
    $registration = $mlm_general_settings['registration'];

         if($registration =='2')
         {
         $reg_cat = $mlm_general_settings['product-category'];

          echo  do_shortcode('[product_category category="'.$reg_cat.'" per_page="10" columns="4" orderby="rand" order="desc"]');
         
         }
         else
         _e(QUALIFICATION_PRODUCT_LIST,MLM_PLUGIN_NAME);
}         

/*
function mlm_qualification_product_list_page_old() {
?>
<ul class="products">
    <?php
        $args = array( 'post_type' => 'product', 'posts_per_page' => '', 'product_cat' => 'qualification-products', 'orderby' => 'rand' );
        $loop = new WP_Query( $args );
        while ( $loop->have_posts() ) : $loop->the_post(); global $product,$post; ?>

       

                <li class="product">    

                    <a href="<?php echo get_permalink( $loop->post->ID ) ?>" title="<?php echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>">

                        <?php woocommerce_show_product_sale_flash( $post, $product ); ?>

                        <?php if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="'.woocommerce_placeholder_img_src().'" alt="Placeholder" width="150px" height="150px" />'; ?>

                        <h3><?php the_title(); ?></h3>

                        <span class="price"><?php echo $product->get_price_html(); ?></span>                    

                    </a>

                    <?php woocommerce_template_loop_add_to_cart( $loop->post, $product ); ?>

                </li>

    <?php endwhile; ?>
    <?php wp_reset_query(); ?>
</ul><!--/.products-->

<?php
}
*/

?>