<?php
/*
Plugin Name: Regions Metabox
Plugin URI: http://leetdigital.com
Description: Add a region meta box to a post.
Version: 1.0
Author: Jedi
License: none
*/

if ( ! function_exists( 'beaufairy_add_region_meta_box' ) ) {

    /**
     * Add a region dropdown metabox.
     *
     * @return void
     */
    function beaufairy_add_region_meta_box( $post ) {

        if ( ! in_category( 'Retailers', $post->ID ) ) return;

        add_meta_box( 'region-metabox', __( 'Region', 'beaufairy' ), 'beaufairy_region_meta_box_callback', 'post' );
    }

    add_action( 'add_meta_boxes', 'beaufairy_add_region_meta_box' );
}

if ( ! function_exists( 'beaufairy_region_meta_box_callback' ) ) {

    /**
     * Callback function for region metabox.
     *
     * @param  mixed  $post
     * @return void
     */
    function beaufairy_region_meta_box_callback( $post ) {

        // Add a nonce field so we can check for it later.
        wp_nonce_field( 'beaufairy_meta_box', 'beaufairy_meta_box_nonce' );

        $selected = get_post_meta( $post->ID, '_region', true );

        bf_region_dropdown( array( 'name' => 'beaufairy_region_field', 'selected' => $selected, 'class' => 'widefat' ) );
    }
}

if ( ! function_exists( 'beaufairy_save_region_meta_box_data' ) ) {

    /**
     * When the post is saved, saves our custom data.
     *
     * @param  int  $post_id
     * @return void
     */
    function beaufairy_save_region_meta_box_data( $post_id ) {

        /*
         * We need to verify this came from our screen and with proper authorization,
         * because the save_post action can be triggered at other times.
         */

        // Check if our nonce is set.
        if ( ! isset( $_POST['beaufairy_meta_box_nonce'] ) ) {
            return;
        }

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $_POST['beaufairy_meta_box_nonce'], 'beaufairy_meta_box' ) ) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Check the user's permissions.
        if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return;
            }

        } else {

            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        }
        
        // Make sure that it is set.
        if ( ! isset( $_POST['beaufairy_region_field'] ) ) {
            return;
        }

        // Sanitize user input.
        $value = sanitize_text_field( $_POST['beaufairy_region_field'] );

        // Update the meta field in the database.
        update_post_meta( $post_id, '_region', $value );
    }

    add_action( 'save_post', 'beaufairy_save_region_meta_box_data' );
}

if ( ! function_exists( 'bf_region_dropdown' ) ) {
    
    /**
     * Dropdown helper for Philippine Regions. 
     *
     * @param  array  $args
     * @return void
     */
    function bf_region_dropdown( $args = array() ) {
        $args = array_merge( array(
            'name'     => 'bf-dropdown',
            'id'       => 'bf-dropdown',
            'class'    => 'bf-dropdown',
            'selected' => ''
        ), $args );

        extract( $args );

        $regions = array(
            'I'    => 'Ilocos Region',
            'II'   => 'Cagayan Valley',
            'III'  => 'Central Luzon',
            'IV'   => 'Southern Tagalog',
            'V'    => 'Bicol region',
            'VI'   => 'Western Visayas',
            'VII'  => 'Central Visayas',
            'VIII' => 'Eastern Visayas',
            'IX'   => 'Zambaonga Peninsula',
            'X'    => 'Northern Mindanao',
            'XI'   => 'Davao Region',
            'XII'  => 'Soccsksargen',
            'Ca'   => 'Caraga',
            'NCR'  => 'National Capital Region',
            'ARMM' => 'Autonomous Region in Muslim Mindanao',
            'CAR'  => 'Cordillera Administrative Region'
        );
        ?>
        <select name="<?php echo $name ?>" id="<?php echo $id ?>" class="<?php echo $class ?>">
            <option value="all"><?php echo __( 'All', 'beaufairy' ) ?></option>
            <?php
                foreach( $regions as $number => $label ) {
                    $select = ( $selected == $number ) ? 'selected' : '';
                    ?>
                    <option <?php echo $select ?> value="<?php echo $number ?>"><?php echo $label ?></option>
                    <?php
                }
            ?>
        </select>
        <?php
    }
}