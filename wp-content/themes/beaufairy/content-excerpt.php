<article id="post-<?php the_ID() ?>" class="archive-post post-tile col-md-4 col-sm-4 col-xs-6">
    <div class="card">
        <a href="<?php the_permalink() ?>" rel="bookmark">
            <div class="card__front">
                <figure class="figure">
                    <div class="figure__img">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'img-responsive', 'alt' => get_the_title() ) ); ?>
                        <?php else : ?>
                            <img width="400" height="270" src="http://placehold.it/400x270" class="img-responsive" alt="<?php the_title() ?>">
                        <?php endif; ?>
                    </div> <!-- .figure__img -->
                    <!--<figcaption class="figure__caption">
                        <p class="figure__title"><?php the_title() ?></p>
                    </figcaption>->
                </figure> <!-- figure -->
            </div> <!-- .card_front -->
            <div class="card__back">
                <?php echo get_the_excerpt() ?>
            </div> <!-- .card__back -->
        </a>
    </div> <!-- .card -->
    <h4 class="archive-post__title text-ellipsis"><?php the_title() ?></h4>
</article> <!-- .archive-post -->
