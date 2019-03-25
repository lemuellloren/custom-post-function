<?php
/*
Plugin Name: Beaufairy Recent Posts
Plugin URI: http://leetdigital.com
Description: Add the Beaufairy Recent Posts Widget
Version: 1.0
Author: Jedi
License: none
*/

class beaufairy_recent_posts extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'beaufairy_recent_posts_widget', // Base ID
            __( 'Beaufairy Recent Posts', 'text_domain' ), // Name
            array( 'description' => __( 'A Beaufairy Recent Posts widget', 'text_domain' ), ) // Args
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
            'posts_per_page' => 2,
            'order' => 'DESC',
            'ignore_sticky_posts' => true
        ) );

        // The Loop
        if ( $the_query->have_posts() ) {
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
        ?>
            <div class="post-thumbnail row">
                <div class="col-md-4 col-sm-5 col-xs-5">
                <a href="<?php echo get_the_permalink() ?>" class="post-thumbnail__image opacify">
                <?php if ( has_post_thumbnail() ) : ?>
                    <?php the_post_thumbnail( 'image-recent', array( 'class' => 'img-responsive', 'alt' => get_the_title() ) ); ?>
                <?php else : ?>
                    <img width="200" height="200" src="http://placehold.it/200x200" class="img-responsive" alt="<?php echo get_the_title() ?>">
                <?php endif; ?>
                </a>
                </div>
                <div class="col-md-8 col-sm-7 col-xs-7">
                    <h6 class="post-thumbnail__title text-ellipsis">
                        <a href="<?php echo get_the_permalink() ?>"><?php echo get_the_title() ?></a>
                    </h6>
                    <p>
                        <small>
                            <em class="post-thumbnail__author">Posted by <?php echo get_the_author() ?></em>
                            <br> <?php echo get_the_date('m/d/y') ?>
                        </small>
                    </p>
                </div>
            </div> <!--/.post-thumbnail -->
        <?php
            }
        } else {
            echo "<p>No item found.</p>";
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
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Beaufairy Recent Posts', 'text_domain' );
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
        <select id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>" class="widefat" style="width:100%;">
            <?php foreach(get_terms('category','parent=0&hide_empty=0') as $term) { ?>
            <option <?php selected( $instance['category'], $term->term_id ); ?> value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
            <?php } ?>      
        </select>
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

} // class beaufairy_recent_posts

// register beaufairy_recent_posts widget
add_action( 'widgets_init', function () {
    register_widget( 'beaufairy_recent_posts' );
});

// register the image size of the widget thumbnails.
add_action( 'after_setup_theme', function() {
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'image-recent', 200, 200, array( 'center', 'center') );
});