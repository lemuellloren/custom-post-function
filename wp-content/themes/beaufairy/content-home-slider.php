<?php

// The Query
$loop = new WP_Query( array(
    'post_type'           => 'post',
    'order'               => 'DESC',
    'posts_per_page'      => 4,
    'meta_key'            => '_is_featured',
    'meta_value'          => 1,
    'ignore_sticky_posts' => true
) );

if ( $loop->have_posts() ) : ?>
<section id="home-slider" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <?php if ( $loop->found_posts > 1 ) : ?>
    <ol class="carousel-indicators">
    <?php for ( $i = 0; $i < $loop->found_posts; $i++ ) : ?>
        <?php $class = ( $i === 0 ) ? 'class="active"' : '' ; ?>
        <li data-target="#home-slider" data-slide-to="<?php echo $i ?>" <?php echo $class ?>></li>
    <?php endfor; ?>
    </ol>
    <?php endif; ?>

    <div class="carousel-inner" role="listbox">
    <?php $loop_counter = 0 ?>
    <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
        <?php $class = ( $loop_counter === 0 ) ? 'active' : '' ; ?>
        <?php
            if ( ( has_post_thumbnail() ) ) :
                $image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), '' )[0];
            else :
                $image_url = 'http://placehold.it/930x400';
            endif;
        ?>
        <div class="item <?php echo $class ?>" style="background:url( <?php echo $image_url ?> )" title="<?php echo get_the_title() ?>" onclick="location.href='<?php the_permalink() ?>'"></div>
    <?php $loop_counter++; endwhile; ?>
    </div>
</section> <!-- #home-slider -->
<!-- booknow snipper section -->
<?php include 'content-booknow-snippet.php';?>
<!-- booknow snipper section -->
<?php endif; ?>
    