<?php get_header() ?>

    <div class="container">
        
        <div class="piece">

            <div class="module">
                <h1><?php echo __( single_cat_title( '', false ) ) ?></h1>
                <?php
                    the_archive_description( '<p>', '</p>' );
                    // The current category
                    $current = get_query_var('cat');

                    // Use subcategory id if exists, else use current category.
                    $cat_id = ( isset( $_GET['subcat'] ) ) ? $_GET['subcat'] : $current;

                    /* The subcategory filter */
                    include( locate_template( 'content-filter-tabs.php' ) );
                ?>
            </div> <!-- .module -->

            <div class="module">

                <?php /* Featured posts slider */ ?>
                <?php get_template_part( 'content', 'blog-slider' ) ?>

                <?php
                    // The meta key arguments
                    $meta = array(
                        'relation' => 'OR',
                        array(
                            'key'     => '_is_featured',
                            'value'   => 1,
                            'compare' => '!='
                        ),
                        array(
                            'key'     => '_is_featured',
                            'value'   => '1',
                            'compare' => 'NOT EXISTS'
                        )
                    );

                    // The Query
                    $the_query = new WP_Query( array(
                        'cat'                 => $cat_id,
                        'paged'               => get_query_var( 'paged', 1 ),
                        'post_type'           => 'post',
                        'meta_query'          => $meta,
                        'order'               => 'DESC',
                        'ignore_sticky_posts' => true
                    ) );
                    
                    if ( $the_query->have_posts() ) : 
                ?>

                <div class="row">
                <?php /* The loop */ ?>
                <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                <?php get_template_part( 'content', 'excerpt' ) ?>
                <?php endwhile; ?>
                </div> <!-- .row -->
                <?php
                    wp_reset_postdata();
                    // If no content, include the "No posts found" template.
                    else :
                        get_template_part( 'content', 'none' );

                    endif;
                ?>
            </div> <!-- .module -->

            <div class="clearfix">
                <?php /* The pagination */ ?>
                <?php beaufairy_paging_nav( $the_query ) ?>
            </div> <!-- .clearfix -->


        </div> <!-- .piece -->

    </div> <!-- .container -->

<?php get_footer() ?>