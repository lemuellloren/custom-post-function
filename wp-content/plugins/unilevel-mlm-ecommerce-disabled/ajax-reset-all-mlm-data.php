<?php 
require_once('../../../wp-config.php');
global $wpdb, $table_prefix;
if($_POST['name'] == 'reset_data' && is_super_admin() )
{
	
	delete_option('wp_mlm_general_settings'); 
	delete_option('wp_mlm_payout_settings');
	delete_option('wp_mlm_other_method_settings');
	delete_option('wp_mlm_withdrawal_method_settings');
       
        $user_ids = $wpdb->get_results("select user_id from ".MLM_USERS);
        $meta_keys = $wpdb->get_results("select meta_key from {$table_prefix}usermeta group by meta_key");
        foreach($user_ids as $user_id){ 
            foreach($meta_keys as $meta_key){
            delete_user_meta( $user_id->user_id, $meta_key->meta_key );
            }
        }
    $wpdb->query( "DELETE  FROM {$table_prefix}users  where ID IN (select user_id from ".MLM_USERS.") " );
    $wpdb->query( "TRUNCATE TABLE ".MLM_USERS);
	$wpdb->query( "TRUNCATE TABLE ".MLM_COMMISSION );
	$wpdb->query( "TRUNCATE TABLE ".MLM_PAYOUT );
	$wpdb->query( "TRUNCATE TABLE ".MLM_PAYOUT_MASTER );
	$wpdb->query( "TRUNCATE TABLE ".MLM_TRANSACTION );
	$wpdb->query( "TRUNCATE TABLE ".MLM_WITHDRAWAL);
	$wpdb->query( "TRUNCATE TABLE ".MLM_DELETED_USERS);
	$wpdb->query( "TRUNCATE TABLE ".MLM_TEAM_SALES);

        
	_e("<span class='msg'>All MLM data has been deleted from the system. You can now start a fresh by creating the First User of the network by <strong><a href='".admin_url()."admin.php?page=admin-settings&tab='>Clicking Here</a>.</strong>.</span>",MLM_PLUGIN_NAME);
	
}

?>