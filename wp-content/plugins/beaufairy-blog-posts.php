<?php
/*
Plugin Name: Beaufairy Blog Posts
Plugin URI: http://leetdigital.com
Description: Add the Beaufairy Blog Posts Widget
Version: 1.0
Author: Jedi
License: none
*/

class beaufairy_blog_posts extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'beaufairy_blog_posts_widget', // Base ID
            __( 'Beaufairy Blog Posts', 'text_domain' ), // Name
            array( 'description' => __( 'A Beaufairy Blog Posts widget', 'text_domain' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
        }

        // The Query
        $the_query = new WP_Query( array(
            'cat' => $instance['category'],
            'post_type' => 'post',
            'posts_per_page' => '3',
            'order' => 'DESC',
            'ignore_sticky_posts' => true
        ) );

        // The Loop
        if ( $the_query->have_posts() ) {
            echo "<div class=\"blog-posts container\"><div class=\"row\">";
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
        ?>
            <div class="blog-posts__item post-tile col-md-4 col-sm-4 col-xs-6">
                <a href="<?php echo get_the_permalink() ?>">
                    <figure class="figure">
                        <div class="figure__img opacify">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'img-responsive', 'alt' => get_the_title() ) ); ?>
                            <?php else : ?>
                                <img width="400" height="270" src="http://placehold.it/400x270" class="img-responsive" alt="<?php echo get_the_title() ?>">
                            <?php endif; ?>
                        </div> <!-- .figure__img -->
                    </figure> <!-- figure -->
                </a>
                <h3 class="blog-posts__item__category"><?php echo get_the_category( $post->ID )[0]->cat_name ?></h3>
                <h4 class="blog-posts__item__heading"><a href="<?php echo get_the_permalink() ?>"><?php echo get_the_title() ?></a></h4>
                <p class="blog-posts__item__content"><?php echo get_the_excerpt() ?></p>
                <a class="blog-posts__item__link" href="<?php echo get_the_permalink() ?>"><?php echo __( 'Read more', 'beaufairy' ) ?></a>
            </div> <!-- .blog-posts__item -->

        <?php
            }
            echo "</div></div>";
        } else {
            // no posts found
        }

        // Restore original Post Data
        wp_reset_postdata();

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Beaufairy Blog Posts', 'text_domain' );
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category:' ); ?></label>
        <?php 
            echo wp_dropdown_categories( array(
                'name'             => $this->get_field_name( 'category' ),
                'show_option_none' => __( 'Select category' ),
                'hierarchical'     => true,
                'hide_empty'       => 0,
                'orderby'          => 'name',
                'echo'             => 0,
                'selected'         => $instance['category'],
                'class'            => 'widefat',
            ) );
        ?>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['category'] = ( ! empty( $new_instance['category'] ) ) ? strip_tags( $new_instance['category'] ) : '';

        return $instance;
    }

} // class beaufairy_blog_posts

// register beaufairy_blog_posts widget
add_action( 'widgets_init', function () {
    register_widget( 'beaufairy_blog_posts' );
});