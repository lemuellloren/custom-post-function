<?php
    //for use in the loop, list 3 post titles related to first tag on current post
    $tags = wp_get_post_tags( $post->ID );

    if ( $tags ) {

        $first_tag = $tags[0]->term_id;

        $args = array(
            'tag__in'           => array( $first_tag ),
            'post__not_in'      => array( $post->ID ),
            'posts_per_page'    => 3,
            'caller_get_posts'  => 1
        );

        $result = new WP_Query($args);

        if( $result->have_posts() ) {
        ?>
        <aside class="module-apart">
            <h2 class="module-apart__heading">
                <span class="text"><?php echo __( 'Related Posts', 'beaufairy' ) ?></span>
            </h2> <!-- .module-apart__heading -->
            <div class="row">
            <?php while ( $result->have_posts() ) : $result->the_post(); ?>
            <?php get_template_part( 'content', 'excerpt' ) ?>
            <?php endwhile; ?>
            </div> <!-- .row -->
        </aside> <!-- .module-apart -->
        <?php

        }

        wp_reset_query();
    }
?>
        

                    
