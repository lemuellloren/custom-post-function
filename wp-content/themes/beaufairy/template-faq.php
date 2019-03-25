<?php 
/*
	Template Name: FAQ
*/
?>
<?php get_header(); ?>

<div class="blog" style="margin-top:5%;">
	<div class="container">
	

		<?php // Display blog posts on any page @ http://m0n.co/l
		$temp = $wp_query; $wp_query= null;
		$wp_query = new WP_Query(
						array(
							'post_type'			=> 'post',
							'posts_per_page'	=> 10,
							'no_found_rows'		=> true,
							'category_name'			=> 'FAQ'
						)
					); ?>
		<?php if ( have_posts() ) : ?>
		<?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>

		<h3><a href="<?php the_permalink(); ?>" title="Read more"><?php the_title(); ?></a></h3>
		<?php the_excerpt(); ?>
		<hr>
		<?php endwhile; ?>

		<?php beaufairy_paging_nav(); ?>
        <?php else : ?>
        <?php get_template_part( 'content', 'none' ) ?>
        <?php endif; ?>

	</div>
</div>

<?php get_footer(); ?>