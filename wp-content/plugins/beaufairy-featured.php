<?php
/*
Plugin Name: Beaufairy Featured Post
Plugin URI: http://leetdigital.com
Description: A plugin that allow setting a post as featured.
Version: 1.0
Author: Jedi
License: none
*/

/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function beaufairy_add_meta_box() {
    add_meta_box('featured-post', __( 'Featured Post', 'beaufairy' ), 'beaufairy_meta_box_callback', 'post');
}

add_action( 'add_meta_boxes', 'beaufairy_add_meta_box' );

function beaufairy_meta_box_callback( $post ) {

    // Add a nonce field so we can check for it later.
    wp_nonce_field( 'beaufairy_meta_box', 'beaufairy_meta_box_nonce' );

    $featured = get_post_meta($post->ID, '_is_featured', true);

    echo "<label for='_is_featured'>". __( 'Feature this post?', 'beaufairy' ) ."</label>";
    echo "<input type='hidden' name='beaufairy_featured_field' value='0'> ";
    echo "<input type='checkbox' name='beaufairy_featured_field' id='beaufairy_featured_field' value='1' ". checked( 1, $featured, false ) ." />";
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function beaufairy_save_meta_box_data( $post_id ) {

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
    if ( ! isset( $_POST['beaufairy_featured_field'] ) ) {
        return;
    }

    // Sanitize user input.
    $value = sanitize_text_field( $_POST['beaufairy_featured_field'] );

    // Update the meta field in the database.
    update_post_meta( $post_id, '_is_featured', $value );
}

add_action( 'save_post', 'beaufairy_save_meta_box_data' );
