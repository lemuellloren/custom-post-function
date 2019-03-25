<?php get_header() ?>

    <?php get_template_part( 'content', 'home-slider' ) ?>

    <section class="piece">
    <?php /* Second row block */ ?>
    <?php if ( dynamic_sidebar( 'home-middle-row' ) ) : else : endif; ?>
    </section>

    <?php /* Third row block */ ?>
    <?php if ( dynamic_sidebar( 'home-bottom-row' ) ) : else : endif; ?>

<?php get_footer() ?>