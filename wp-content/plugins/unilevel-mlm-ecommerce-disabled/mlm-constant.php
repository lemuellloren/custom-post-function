<?php
define("PLUGIN_NAME", "Beau Fairy Membership");
define("MENU_NAME", "Beau Fairy Membership Menu");

define("MLM_REGISTRATION_TITLE", "Register New User");
define("MLM_REGISTRATIN_SHORTCODE", "register-mlm-page");

define("NOT_FOUND_TITLE", "404 Not Found");
define("NOT_FOUND_SHORTCODE", "not-found-404-page");

define("TEAM_SALES_TITLE", "Team Sales");
define("TEAM_SALES_SHORTCODE", "team-sales-page");

define("MLM_VIEW_NETWORK_TITLE", "Network");
define("MLM_VIEW_NETWORK_SHORTCODE", "view-mlm-unilevel-network");

define("MLM_VIEW_GENEALOGY_TITLE", "Genealogy");
define("MLM_VIEW_GENEALOGY_SHORTCODE", "view-mlm-unilevel-network");


define("MLM_MY_CHILD_MEMBER_DETAILS_TITLE", "View Members");
define("MLM_MY_CHILD_MEMBER_DETAILS_SHORTCODE", "mlm-view-child-level-member");


define("MLM_NETWORK_DETAILS_TITLE", "Dashboard");
define("MLM_NETWORK_DETAILS_SHORTCODE", "mlm-network-details");

define("MLM_LEFT_GROUP_DETAILS_TITLE", "Left Group");
define("MLM_LEFT_GROUP_DETAILS_SHORTCODE", "mlm-left-group");

define("MLM_RIGHT_GROUP_DETAILS_TITLE", "Right Group");
define("MLM_RIGHT_GROUP_DETAILS_SHORTCODE", "mlm-right-group");

define("MLM_PERSONAL_GROUP_DETAILS_TITLE", "Personal Group");
define("MLM_PERSONAL_GROUP_DETAILS_SHORTCODE", "mlm-personal-group");

define("MLM_MY_CONSULTANT_TITLE", "My Consultants");
define("MLM_MY_CONSULTANT_SHORTCODE", "mlm-consultant-group");

define("MLM_MY_PAYOUTS", "My Payouts");
define("MLM_MY_PAYOUTS_SHORTCODE", "mlm-payouts");

define("MLM_MY_PAYOUT_DETAILS", "My Payouts Details");
define("MLM_MY_PAYOUT_DETAILS_SHORTCODE", "mlm-payout-details");

define("MLM_DISTRIBUTE_COMMISSION_TITLE", "Distribute Commission");
define("MLM_DISTRIBUTE_COMMISSION_SHORTCODE", "mlm-distribute-commission");

define("MLM_DISTRIBUTE_BONUS_TITLE", "Distribute Bonus");
define("MLM_DISTRIBUTE_BONUS_SHORTCODE", "mlm-distribute-bonus"); 

define("MLM_FINANCIAL_DASHBOARD_TITLE", "Financial Dashboard");
define("MLM_FINANCIAL_DASHBOARD_SHORTCODE", "mlm-financial-dashboard");

define("MLM_QUALIFICATION_PRODUCTS_TITLE", "Qualification Products");
define("MLM_QUALIFICATION_PRODUCTS_SHORTCODE", "mlm-qualification-products-list");

define( "MLM_PAGE_PATH", plugins_url().'/'.MLM_PLUGIN_NAME.'/mlm_html');

//Dynamic PATH From Root to Plugins Folder
define( 'MLM_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
//Dynamic URL From Root to Plugins Folder
define( 'MLM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define("JOIN_NETWORK", "Join Network");
define("JOIN_NETWORK_SHORTCODE", "join_network");



/*********Check if WooCommerce is active *********/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    define( 'MLM_CURRENCY',get_option('woocommerce_currency'));
    }
else {
/****** IF NOT SET THEN BY DEFAULT CURRENCY **********/
define( 'MLM_CURRENCY','USD');
}


/*************SHOW DEFAULT MESSAGE IF NOT A MLM USER VISIT THIS PAGES********************/
$admin_email = get_option( 'admin_email' );
$admin_email='<a mailto="'.$admin_email.'">'.$admin_email.'</a>';
define("NOT_MLM_USER_MESSAGE",'Oops. It seems you do not have sufficient privileges to access this page. Only users who are part of the network will be able to access this page. Please contact the system administrator at '.$admin_email.' if you would like to join the network.');



/************MY PAYOUTS DETAILS************/
define('PAYOUT_DETAILS','<div class="notfound">This page shows the details of one particular payout and cannot be accessed directly. Please go to the My Payouts page and click on View Details link against a particular payout to view the full details of that payout.</div>');


/********VIEW MEMBERS********/
define('CHILD_MEMBER_DETAILS','<div class="notfound">This page shows the details of one particular level and cannot be accessed directly. Please go to the Genealogy page and click on Levels link against a particular level to view the all level members.</div>');


/*********MLM QUALIFICATION PRODUCTS*******/
define('QUALIFICATION_PRODUCT_LIST','<div class="notfound">This page shows the lists of qualification products and currently not active. Please go to the Shop page and purchase the product.</div>');



/******** Join Network Page **************/

define('ADMIN_MESSAGE_JOIN_PAGE','You have logged in as admin! admin can not become a part of Beau Fairy Membership Network.');
define('ALREADY_MLM_USER','You have already a Beau Fairy Membership.');
define('NO_ORDER_PLACED_MLM_USER','You need to have atleast one completed order in your account before joining the network.');
define('PURCHASE_FOR_JOIN_MLM_PART','<p>In order to become a member of the network you would also need to complete a purchase of the qualification products.</p>');


/***********Not Found Page***************/
define('ALREADY_MLM_USER','You are already a Beau Fairy VIP member.');
define('MLM_NOT_FOUND','<div class="notfound">Page Not Found.</div>');


/****************Tables Name Define***************/
$Prefix=$wpdb->prefix;
define('MLM_USERS',$Prefix."ume_users");
define('MLM_COMMISSION',$Prefix."ume_commission");
define('MLM_PAYOUT',$Prefix."ume_payout");
define('MLM_PAYOUT_MASTER',$Prefix."ume_payout_master");
define('MLM_TRANSACTION',$Prefix."ume_transaction");
define('MLM_WITHDRAWAL',$Prefix."ume_withdrawal");
define('MLM_DELETED_USERS',$Prefix."ume_deleted_users");
define('MLM_TEAM_SALES',$Prefix."ume_team_sales");

