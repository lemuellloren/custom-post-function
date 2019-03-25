<?php get_header() ?>

<div class="container">

    <div class="piece">

        <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : the_post(); ?>

            <article>
                <div class="module">
                    <h1><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h1>
                    <p><?php echo get_post_meta($post->ID, 'subtext', true); ?></p>
                </div> <!-- .module -->
                <div class="module module--no-border">
                    <?php the_excerpt() ?>
                </div> <!-- .module -->
            </article>

        <?php 
            endwhile;
            beaufairy_paging_nav();
        ?>
        <?php else : ?>
            <?php get_template_part( 'content', 'none' ) ?>
        <?php endif; ?>
        
    </div> <!-- .piece -->

</div> <!-- .container -->

<?php get_footer() ?>