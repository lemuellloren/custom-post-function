<?php
// Use subcategory id if exists, else use current category.
$category = ( isset( $_GET['subcat'] ) ) ? $_GET['subcat'] : get_query_var('cat');

// The Query
$featured_query = new WP_Query( array(
    'cat'                 => $category,
    'post_type'           => 'post',
    'order'               => 'DESC',
    'posts_per_page'      => 4,
    'meta_key'            => '_is_featured',
    'meta_value'          => 1,
    'ignore_sticky_posts' => true
) );

if ( $featured_query->have_posts() ) : ?>
    <div id="post-banner" class="carousel slide hidden-xs" data-ride="carousel">
        <div class="post-banner__images carousel-inner" role="listbox">
        <?php $counter = 1; ?>
        <?php while ( $featured_query->have_posts() ) : $featured_query->the_post(); ?>
            <figure class="figure item <?php echo ( $counter !== 1 ) ? '' : 'active'; ?>">
                <a href="<?php the_permalink() ?>">
                    <div class="figure__img">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'full', array( 'class' => 'img-responsive', 'alt' => get_the_title() ) ); ?>
                        <?php else : ?>
                            <img src="http://placehold.it/930x400" class="img-responsive" alt="<?php echo get_the_title() ?>">
                        <?php endif; ?>
                    </div> <!-- .figure__img -->
                </a>
                <!--<figcaption class="figure__caption">
                    <h5 class="figure__title"><?php the_title() ?></h5>
                    <p><?php the_excerpt() ?></p>
                </figcaption>-->
            </figure>
        <?php $counter++; ?>
        <?php endwhile; ?>
        </div>

        <?php if ( $featured_query->found_posts > 1 ) : ?>
        <!-- Left and right controls -->
        <a class="left carousel-control" href="#post-banner" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#post-banner" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
        <?php endif; ?>
    </div> <!-- #post-banner -->
<?php wp_reset_postdata(); endif; ?>
