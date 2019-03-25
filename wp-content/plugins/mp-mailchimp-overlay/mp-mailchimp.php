<?php
/*
Plugin Name: BeauFairy - Mailchimp Subscribe Form
Description: A simple AJAX Mailchimp Subscribe Form 
Version: 1.0
Author: Master Preenz
License: GPL2
*/

// Prevent direct access
if ( ! defined( 'ABSPATH' ) )
    exit;

class MPMailChimpOverlay {

    // Constructor
    public function __construct() {
        // Define constants
        define( 'MPM_VERSION', '1.0.0' );
        define( 'MPM_SLUG', plugin_basename(__FILE__));
        define( 'MPM_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
        define( 'MPM_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );

        // Load required files
        $this->mpm_includes();
    }

    /**
     * Include for MPM Plugin
     * @since 1.0.0
     */
    public function mpm_includes() {
        // MailChimp API Library
        require_once MPM_PLUGIN_DIR . '/inc/MCAPI.class.php';
        require_once MPM_PLUGIN_DIR . '/ajax.php';
        require_once MPM_PLUGIN_DIR . '/shortcode.php';
        require_once MPM_PLUGIN_DIR . '/script.php';
        require_once MPM_PLUGIN_DIR . '/admin.php';

        // Add stylesheet
        wp_enqueue_style( 'mpm-subscribe', MPM_PLUGIN_URL . '/css/style.css', array());
    }
}

$GLOBALS['master_preenz_mailchimp'] = new MPMailChimpOverlay();
?>