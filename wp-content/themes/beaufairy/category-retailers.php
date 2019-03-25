<?php get_header() ?>
<div class="container">
    <div class="piece">
        <div class="module module--no-border text-center">
            <h1 class="uppercase"><?php echo __( 'Authorized Retailers', 'beaufairy' ) ?></h1>
            <?php the_archive_description( '<p>', '</p>' ) ?>
            <?php if ( function_exists( 'bf_region_dropdown' ) ) : ?>
            <div class="form center-block" style="max-width: 250px">
                <?php bf_region_dropdown( array( 'class' => 'form-control bf-dropdown') ) ?>
            </div>
            <?php endif ?>
        </div> <!-- .module -->
        <section class="retailers">
            <?php if ( have_posts() ) : ?>
            <ol>
                <?php $counter = 1; ?>
                <?php while( have_posts() ) : the_post(); ?>
                <li class="region-<?php echo get_post_meta( $post->ID, '_region', true ) ?> retailers__item" data-myorder="<?php echo $counter ?>">
                   <!--  <h3 class="retailers__item__name"><?php the_title() ?></h3> -->
                    	<div class="stockist-img" style="text-align:center">
                    	<?php the_content() ?>
                    	</div>
                    <!-- <h4>
                        <address><?php echo get_post_meta( $post->ID, 'address', true ); ?></address>
                        <?php $contact = get_post_meta( $post->ID, 'contact_number', true ); ?>
                        <a class="retailers__item__phone" href="tel:<?php echo $contact ?>"><?php echo $contact ?></a>
                    </h4> -->
                </li>
                <?php $counter++; ?>
                <?php endwhile; ?>
            </ol> <!-- ol -->
             <?php beaufairy_paging_nav(); ?>
            <?php else : ?>
            <?php get_template_part( 'content', 'none' ) ?>
            <?php endif; ?>

        </section> <!-- .retailers -->
    </div> <!-- .piece -->
</div> <!-- .container -->
<?php get_footer() ?>