<?php
/**
 * My Account Navigation
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

wc_print_notices(); ?>

<?php
    wp_nav_menu( array( 
        'menu' => 'profile-menu',
        'container' => 'ul',
        'menu_class' => 'nav-profile'
    ) );
?>