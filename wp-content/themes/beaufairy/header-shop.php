<?php

get_header();

?>

<div class="container-fluid">
<div class="piece">
<div class="row">
<?php
    /**
     * woocommerce_sidebar hook
     *
     * @hooked woocommerce_get_sidebar - 10
     */
    do_action( 'woocommerce_sidebar' );
?>
<div class="col-md-9 col-sm-9">
<div class="module clearfix">
    <?php
        // Echo out the title if page is not for single product.
        if ( ! is_product() ) :

    ?>
        <div class="clearfix form">
    <?php
            do_action( 'woocommerce_before_shop_loop' );
    ?>
        </div>
    <?php

        // If page is for single product, list the categories where it belongs.
        else :            

            echo "<p>". __( 'In: ', 'beaufairy' ) . implode(', ', get_current_product_categories() ) ."</p>";

        endif;
    ?>
</div> <!-- .module -->