<?php

get_header();

?>

<div class="container">
    
    <div class="piece">
        <?php while ( have_posts() ) : the_post(); ?>
            <h1><?php the_title() ?></h1> <br>
            <?php the_content() ?>
        <?php endwhile; ?>
    </div> <!-- .piece -->

</div> <!-- .container -->

<?php get_footer() ?>