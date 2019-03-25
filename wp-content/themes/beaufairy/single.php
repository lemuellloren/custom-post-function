<?php get_header() ?>

<div class="container">

    <div class="piece">
        <?php while ( have_posts() ) : the_post(); ?>
        
        <section class="post-single">

            <div class="module">
                
                <h1><?php the_title() ?></h1>

                <div class="post-single__meta">
                    <span><?php echo printf( __( 'Posted by %s', 'beaufairy' ), the_author( '', false ) ) ?></span>
                    <span><time datetime="<?php echo get_the_date( 'Y-m-d' ) ?>"><?php echo get_the_date() ?></time></span>
                </div> <!-- .post-single__meta -->

            </div> <!-- .module -->
            
            <article class="post-content module module--no-border">                
                <?php //if ( has_post_thumbnail() ) : ?>
                    <!-- <div class="text-center">
                        <?php the_post_thumbnail( 'medium' ) ?>
                    </div>
                    <br> -->
                <?php //endif; ?>
                <?php the_content() ?>
            </article> <!-- .post-content -->

            <aside class="module module--no-border">
                <p><strong>Posted in:</strong> <?php echo get_the_category_list( ', ', '', $post->ID ); ?></p>
            </aside><!-- .module -->

        </section> <!-- .post-single -->

        <?php get_template_part( 'content', 'related' ) ?>

        <?php endwhile; ?>
    </div> <!-- .piece -->

</div> <!-- .container -->

<?php get_footer() ?>