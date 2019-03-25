<div class="container-fluid">
    <div class="piece">
        <?php if ( have_posts() ) : ?>
        <?php /* the loop */ ?>
        <?php while ( have_posts() ) : the_post(); ?>
            <h1><?php the_title() ?></h1>
            <p><?php echo get_post_meta($post->ID, 'subtext', true); ?></p>
            <?php the_content() ?>
        <?php endwhile; ?>
        <?php else : ?>
            <?php get_template_part( 'content', 'none' ) ?>
        <?php endif; ?>
    </div> <!-- .piece -->
</div> <!-- .container-fluid -->