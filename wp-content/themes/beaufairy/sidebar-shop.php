<?php

$args = array(
    'taxonomy'     => 'product_cat',
    'orderby'      => 'name',
    'show_count'   => 0,
    'pad_counts'   => 0,
    'hierarchical' => 0,
    'title_li'     => '',
    'hide_empty'   => 0,
    'parent'       => 0
);

// Get all top level categories.
$categories = get_categories( $args );

?>

<div class="col-md-3 col-sm-3">
    <aside class="sidebar-filter">
        
        <?php if ( ! empty( $categories ) ) : ?>

        <h4>
            <span class="sidebar-filter__toggle fa fa-bars"></span> 
            <?php echo __( 'Categories', 'beaufairy' ) ?>
        </h4>

        <ul>

            <?php
                // Loop through each categories.
                foreach ( $categories as $category ) :

                    // If a single product page.
                    if ( is_product() ) :
                        
                        // Set current product's categories link to an active state.
                        do_action( 'before_category_filter_link', in_array( $category->name, get_current_product_categories() ) );

                    elseif ( is_product_category() ) :
                        
                        // Set current category link to active state.
                        do_action( 'before_category_filter_link', single_cat_title( '', false ) == $category->name );
                    else :
                        // When no product or category is selected.
                        do_action( 'before_category_filter_link', false );
                    endif;

                    echo "<a href=". get_term_link( $category->slug, 'product_cat' ) .">". $category->name . "</a>";

                    echo "</li>";
                endforeach;
            ?>

        </ul> <!-- ul -->

        <?php else : ?>

        <p><?php echo __( 'No categories.', 'beaufairy' ) ?></p>

        <?php endif; ?>

    </aside> <!-- first column -->
</div>
