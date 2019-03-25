<?php
/*
Plugin Name: Beaufairy Single Page
Plugin URI: http://leetdigital.com
Description: Add a Beaufairy page content to widget.
Version: 1.0
Author: Jedi
License: none
*/

class beaufairy_page extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'beaufairy_page_widget', // Base ID
            __( 'Beaufairy Page', 'text_domain' ), // Name
            array( 'description' => __( 'A Beaufairy Page widget', 'text_domain' ), ) // Args
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
        $post = get_post( $instance['page'] );

        echo do_shortcode( $post->post_content );

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
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'page' ); ?>"><?php _e( 'Page:' ); ?></label>
        <?php wp_dropdown_pages( array(
            'name'     => $this->get_field_name( 'page' ),
            'selected' => $instance['page'],
        ) ) ?>
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
        $instance['page'] = ( ! empty( $new_instance['page'] ) ) ? strip_tags( $new_instance['page'] ) : '';

        return $instance;
    }

} // class beaufairy_page

// register beaufairy_page widget
add_action( 'widgets_init', function () {
    register_widget( 'beaufairy_page' );
});