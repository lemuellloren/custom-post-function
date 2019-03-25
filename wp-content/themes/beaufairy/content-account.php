<div class="container">
    <div class="piece">
        <div class="row">
            <div class="col-md-8 col-sm-8 col-md-offset-2 col-sm-offset-2">
                <?php if ( have_posts() ) : ?>
                <?php /* the loop */ ?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <div class="module">
                        <h1><?php the_title() ?></h1>
                    </div> <!-- .module -->
                    <?php the_content() ?>
                <?php endwhile; ?>
                <?php else : ?>
                    <?php get_template_part( 'content', 'none' ) ?>
                <?php endif; ?>
            </div>
        </div> <!-- .row -->
    </div> <!-- .piece -->
</div> <!-- .container -->