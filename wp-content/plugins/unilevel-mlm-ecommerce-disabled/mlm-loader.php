<?php

session_start();
/*
  Plugin Name: UniLevel MLM eCommerce
  Plugin URI: http://tradebooster.com
  Description: The only Unilevel MLM eCommerce plugin for Wordpress. Run a full blown Unilevel MLM eCommercewebsite within your favourite CMS.
  Version: 2.5
  Author: Tradebooster
  Author URI: http://wpbinarymlm.com
  License: GPL
 */

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/** Constants **************************************************************** */
global $wpdb;
mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);
// Path and URL
if (!defined('WP_BINARY_MLM_ULR'))
    define('WP_BINARY_MLM_ULR', 'http://wpbinarymlm.com');
	
if (!defined('MLM_PLUGIN_DIR'))
    define('MLM_PLUGIN_DIR', WP_PLUGIN_DIR . '/unilevel-mlm-ecommerce');

if (!defined('MLM_PLUGIN_NAME'))
    define('MLM_PLUGIN_NAME', 'unilevel-mlm-ecommerce');

define('MLM_URL', plugins_url('', __FILE__));

if (!defined('MYPLUGIN_VERSION_KEY'))
    define('MYPLUGIN_VERSION_KEY', 'myplugin_version');
if (!defined('MYPLUGIN_VERSION_NUM'))
    define('MYPLUGIN_VERSION_NUM', '2.5');
    
add_option(MYPLUGIN_VERSION_KEY, MYPLUGIN_VERSION_NUM);

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );


//include the the plugin upgrade class file
require_once(MLM_PLUGIN_DIR . '/Class.php');
//include the the core funcitons file
require_once(MLM_PLUGIN_DIR . '/mlm-constant.php');

// All constant define for Help Text
require_once(MLM_PLUGIN_DIR . '/help-text.php');

//this file create or update database schema
require_once(MLM_PLUGIN_DIR . '/mlm_core/mlm-core-schema.php');

require_once(MLM_PLUGIN_DIR . '/common-functions.php');
//include the html functions file

//this file contain the unilvel network building code
require_once(MLM_PLUGIN_DIR . '/mlm_html/mlm-view-network.php');

//this file contain the child level member code
require_once(MLM_PLUGIN_DIR . '/mlm_html/mlm-view-child-level-member.php');

//this file contatain the overview of network sales like left, right and personal
require_once(MLM_PLUGIN_DIR . '/mlm_html/mlm-network-details.php');


//this file contaian the personal or direct sales
require_once(MLM_PLUGIN_DIR . '/mlm_html/mlm-personal-group-details.php');


//this file contain the payouts
require_once(MLM_PLUGIN_DIR . '/mlm_html/mlm-my-payout-page.php');

//this file contain the payouts
require_once(MLM_PLUGIN_DIR . '/mlm_html/mlm-my-payout-details-page.php');

//this file contatin the common functions which is used in other files

require_once(MLM_PLUGIN_DIR . '/mlm_html/admin-mlm-dashboard.php');
//this is admin file that contain the creation of the top user of the network
require_once(MLM_PLUGIN_DIR . '/mlm_html/admin-create-first-user.php');

//in this file admin setup the mlm plugin settings
require_once(MLM_PLUGIN_DIR . '/mlm_html/admin-mlm-settings.php');

//in this file payout will be run
require_once( MLM_PLUGIN_DIR . '/mlm_html/admin-pay-cycle.php' );

// in this file we can look our downline team sales
require_once(MLM_PLUGIN_DIR . '/mlm_html/mlm-team-sales.php');

//in this file admin can change the user's profile details
require_once(MLM_PLUGIN_DIR . '/mlm_html/admin-user-update-profile.php');

require_once(MLM_PLUGIN_DIR . '/mlm_html/admin-user-account.php');
require_once(MLM_PLUGIN_DIR . '/mlm_html/admin-view-user-network.php');

require_once( MLM_PLUGIN_DIR . '/mlm_html/join-network.php');

////in this file withdrawals will be run
require_once( MLM_PLUGIN_DIR . '/mlm_html/admin-mlm-pending-withdrawal.php' );
require_once( MLM_PLUGIN_DIR . '/mlm_html/admin-mlm-withdrawal-process.php' );
require_once( MLM_PLUGIN_DIR . '/mlm_html/admin-mlm-sucessed-withdrawal.php' );
require_once( MLM_PLUGIN_DIR . '/mlm_html/mlm-financial-dashboard.php' );
require_once( MLM_PLUGIN_DIR . '/mlm_html/admin-reports.php');
require_once( MLM_PLUGIN_DIR . '/mlm_html/admin-mlm-reset-all-data.php');

require_once(MLM_PLUGIN_DIR . '/mlm_html/admin-mlm-role-discounts.php');
require_once(MLM_PLUGIN_DIR . '/mlm_html/admin-mlm-coupon-settings.php');

require_once(MLM_PLUGIN_DIR . '/mlm_html/mlm-404-not-found.php');


/* Runs
  when plugin is activated */
register_activation_hook(__FILE__, 'mlm_install');

/* Runs wher plugin is deactivated */
register_deactivation_hook(__FILE__, 'mlm_deactivate');

/* Runs wher plugin is Uninstall */
register_uninstall_hook(__FILE__, 'mlm_remove');
//HOOK INTO WORDPRESS
add_action('init', 'register_shortcodes');

add_action('after_setup_theme', 'load_custom_widgets');

function load_custom_widgets() {
       require( dirname(__FILE__) . '/default-widgets.php' );
   }


function get_page_by_name($pagename) {
    $pages = get_pages();
    foreach ($pages as $page)
        if ($page->post_title == $pagename)
            return true;
    return false;
}

function mlm_install() {
    mlm_core_install_users();
    mlm_core_install_commission();
    mlm_core_install_payout_master();
    mlm_core_install_payout();
    mlm_core_install_transaction();
    mlm_core_install_withdrawal();
    myplugin_load_textdomain();
    mlm_core_install_delete_users();
    mlm_core_install_team_sales();
}

//this code add the registration page
//1st agru is the TITLE & second is CONTENT
$pages = array(
    0 => array('title' => MLM_VIEW_NETWORK_TITLE,
        'slug' => 'network',
        'shortcode' => MLM_VIEW_NETWORK_SHORTCODE,
        'page' => 'mlm_network_page'),
    1 => array('title' => MLM_NETWORK_DETAILS_TITLE,
        'slug' => 'dashboard',
        'shortcode' => MLM_NETWORK_DETAILS_SHORTCODE,
        'page' => 'mlm_network_details_page'),
    2 => array('title' => MLM_VIEW_GENEALOGY_TITLE,
        'slug' => 'genealogy',
        'shortcode' => MLM_VIEW_GENEALOGY_SHORTCODE,
        'page' => 'mlm_network_genealogy_page'),
    3 => array('title' => MLM_MY_CHILD_MEMBER_DETAILS_TITLE,
        'slug' => 'view-members',
        'shortcode' => MLM_MY_CHILD_MEMBER_DETAILS_SHORTCODE,
        'page' => 'mlm_my_child_member_details_page'),
    4 => array('title' => MLM_FINANCIAL_DASHBOARD_TITLE,
        'slug' => 'mlm-financial-dashboard',
        'shortcode' => MLM_FINANCIAL_DASHBOARD_SHORTCODE,
        'page' => 'mlm_my_financial_dashboard_page'),
    5 => array('title' => MLM_MY_PAYOUTS,
        'slug' => 'my-payouts',
        'shortcode' => MLM_MY_PAYOUTS_SHORTCODE,
        'page' => 'mlm_my_payout_page'),
    6 => array('title' => MLM_MY_PAYOUT_DETAILS,
        'slug' => 'my-payouts-details',
        'shortcode' => MLM_MY_PAYOUT_DETAILS_SHORTCODE,
        'page' => 'mlm_my_payout_details_page'),
    7 => array('title' => JOIN_NETWORK,
        'slug' => 'join-network',
        'shortcode' => JOIN_NETWORK_SHORTCODE,
        'page' => 'join_network'),
    8 => array('title' => TEAM_SALES_TITLE,
	'slug' => 'team-sales',
	'shortcode' => TEAM_SALES_SHORTCODE,
	'page' => 'team_sales_page'),
    9 => array('title' => NOT_FOUND_TITLE,
	'slug' => 'not-found',
	'shortcode' => NOT_FOUND_SHORTCODE,
	'page' => 'not_found_page')  	           	
);

$run_once = get_option('menu_check');
if (!$run_once) {
    add_action('create_pages', 'register_plugin_page', 10, 1);
    do_action('create_pages', $pages);
    
    require_once(MLM_PLUGIN_DIR . '/TemplateValues.php');
    foreach ($MLMMemberInitialData AS $key => $value) {
     update_option($key, $value);
    }
    update_option('menu_check', true);
} 


function register_plugin_page($pages) {

    foreach ($pages as $page) {
         $post_id = register_page($page['title'], $page['slug'], $page['shortcode']);
         if (!empty($post_id)) { 
            update_post_meta($post_id, $page['page'], $page['page']);
            if ($page['page'] != 'mlm_registration_page' || $page['page'] != 'mlm_qualification_product_list_page'){
               // update_post_meta($post_id, '_mlm_is_members_only', 'true');
               }
        } 
    }
  
}

//shows custom message after plugin activation
add_action('admin_notices', 'show_message_after_plugin_activation');

function mlm_deactivate() {

    $mlmPages = array('mlm_network_page', 'mlm_network_genealogy_page', 'mlm_network_details_page', 'mlm_my_payout_page', 'mlm_my_payout_details_page','mlm_distribute_commission', 'mlm_personal_group_details_page', 'mlm_payment_status_details_page', 'mlm_my_financial_dashboard_page',  'mlm_my_child_member_details_page','join_network','mlm_team_sales');
    //delete post from wp_posts and wp_postmeta table
    foreach ($mlmPages as $value) {
       $post_id = get_post_id($value);
        wp_delete_post($post_id, true);
    }
   delete_option('menu_check');
   /*  $term = get_term_by('name', MENU_NAME, 'nav_menu');
    wp_delete_term($term->term_id, 'nav_menu'); */
	
}

function mlm_remove() {
    mlm_core_drop_tables();

    //$mlmPages contain the meta_key of the created mlm plugin pages
    $mlmPages = array('mlm_network_page', 'mlm_network_genealogy_page', 'mlm_network_details_page', 'mlm_my_payout_page', 'mlm_my_payout_details_page', 'mlm_distribute_commission', 'mlm_personal_group_details_page', 'mlm_payment_status_details_page', 'mlm_my_financial_dashboard_page', 'mlm_my_child_member_details_page','join_network','mlm_team_sales');
    //delete post from wp_posts and wp_postmeta table
    foreach ($mlmPages as $value) {
        $post_id = get_post_id($value);
        wp_delete_post($post_id, true);
    }

    //delete the data from wp_options table
    delete_option('wp_mlm_general_settings');
    delete_option('wp_mlm_payout_settings');
    delete_option('wp_mlm_other_method_settings');
    delete_option('wp_mlm_withdrawal_method_settings');

}

if (is_admin()) {
    /* Call the html code */
    add_action('admin_menu', 'mlm_admin_menu');
}


/* * *********Upgrade plugin process************** */

$RunOnce = get_option('upgrade_ume_250');
if (!$RunOnce) {
    add_action('init', 'mlm_install');
  
}



/* Array */
$paymenntStatusArr = array(0 => 'Unpaid', 1 => 'Paid');
add_action('init', 'load_javascript');

function fb_redirect_2() {
    global $current_user;
    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);

    $post_id = get_post_id('mlm_network_details_page');
    if ($user_role == 'subscriber' && $_SESSION['ajax'] != 'ajax_check') {
        //if ( preg_match('#wp-admin/?(index.php)?$#', $_SERVER['REQUEST_URI']) ) 
        {
            if (function_exists('admin_url')) {
                wp_redirect(get_option('siteurl') . "/?page_id=$post_id");
            }
            else {
                wp_redirect(get_option('siteurl'));
            }
        }
    }
}

//add_action('init', 'fb_redirect_2');

add_filter("login_redirect", "mlm_login_redirect", 10, 3);

function mlm_login_redirect($redirect_to, $request, $user) {
    //is there a user to check?
    if (!empty($user->roles)) {
        if (is_array($user->roles)) {
            //check for admins
            if (in_array("administrator", $user->roles)) {
                // redirect them to the default place
                return admin_url();
            }
            else {
                return home_url();
            }
        }
    }
}

add_action('wp_logout', 'logout_session');

function logout_session() {
    unset($_SESSION['search_user']);
    unset($_SESSION['session_set']);
    unset($_SESSION['userID']);
    unset($_SESSION['ajax']);
}

add_action('init', 'myplugin_load_textdomain');

function myplugin_load_textdomain() {
load_plugin_textdomain(MLM_PLUGIN_NAME, NULL, '/'.MLM_PLUGIN_NAME.'/languages/');
}


add_role('mlm_user', __('MLM User'));



$RunOnce = get_option('upgrade_plugin_mlm');
if (!$RunOnce) {
    update_option('upgrade_plugin_mlm', true);
}


/* * *********Upgrade plugin process************** */
$UMP_Instance = new UMP();

add_filter('site_transient_update_plugins', array(&$UMP_Instance, 'Plugin_Update_Notice'));
add_filter('plugins_api', array(&$UMP_Instance, 'Plugin_Info_Hook'), 10, 3);

add_filter('upgrader_pre_install', array(&$UMP_Instance, 'Pre_Upgrade'), 10, 2);
add_filter('upgrader_post_install', array(&$UMP_Instance, 'Post_Upgrade'), 10, 2);

add_action('admin_notices', array(&$UMP_Instance, 'UpdateNag'));
add_action('admin_init', array(&$UMP_Instance, 'dismiss_mlm_update_notice'));
add_action('admin_init', array(&$UMP_Instance, 'Upgrade_Check'));

add_action( 'plugins_loaded', 'role_discounts_plugins_loaded' );
?>