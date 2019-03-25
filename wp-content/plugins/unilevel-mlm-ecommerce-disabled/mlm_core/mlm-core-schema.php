<?php
if(!defined('ABSPATH'))
	exit;
	
if (!function_exists('mlm_core_set_charset')) {

    function mlm_core_set_charset() {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        /* MLM Component DB Schemea */
        if (!empty($wpdb->charset)) {
            return "DEFAULT CHARACTER SET $wpdb->charset";
        }
        return '';
    }

}
if (!function_exists('mlm_core_get_table_prefix')) {

    function mlm_core_get_table_prefix() {
        global $wpdb;
        return $wpdb->base_prefix;
    }

}

/****************************************************************
MLM Users Table 
****************************************************************/
function mlm_core_install_users()
{
	global $wpdb;
	$charset_collate = mlm_core_set_charset();
	$table_prefix = mlm_core_get_table_prefix();
	
	$sql[] = "CREATE TABLE IF NOT EXISTS ".MLM_USERS."
			(
				id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				user_id BIGINT(20) NOT NULL COMMENT 'foreign key of the {$table_prefix}users table',
				username VARCHAR( 60 ) NOT NULL ,
				user_key VARCHAR( 15 ) NOT NULL ,
				parent_id int( 11 ) NOT NULL ,
				sponsor_id int( 11 ) NOT NULL ,
				payment_status int(1) NOT NULL DEFAULT '0' COMMENT '1 indicate paid and 0 indicate unpaid',
				payment_date datetime NOT NULL,
				banned int(1) NOT NULL DEFAULT '0',
				KEY index_parent_id (parent_id),
				KEY index_sponsor_id (sponsor_id),
				UNIQUE (username)
			) {$charset_collate} AUTO_INCREMENT=1";
			
		dbDelta($sql);
}


/****************************************************************
MLM Deleted Users Table 
****************************************************************/
function mlm_core_install_delete_users()
{
	global $wpdb;
	$charset_collate = mlm_core_set_charset();
	$table_prefix = mlm_core_get_table_prefix();
	
	$sql[] = "CREATE TABLE IF NOT EXISTS  ".MLM_DELETED_USERS." 
			(
				id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				user_id BIGINT(20) NOT NULL ,
				username VARCHAR( 60 ) NOT NULL ,
				user_key VARCHAR( 15 ) NOT NULL ,
				parent_id int( 11 ) NOT NULL ,
				sponsor_id int( 11 ) NOT NULL ,
				deleted_date datetime NOT NULL
				
			) {$charset_collate} AUTO_INCREMENT=1";
			
		dbDelta($sql);
}



/****************************************************************
team sales Table 
****************************************************************/
function mlm_core_install_team_sales() {
	global $wpdb;
	$charset_collate = mlm_core_set_charset();
	$table_prefix = mlm_core_get_table_prefix();
	
	$sql[] = "CREATE TABLE IF NOT EXISTS  ".MLM_TEAM_SALES." 
			(
				id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				user_id BIGINT(20) NOT NULL COMMENT 'foreign key of the {$table_prefix}users table',
				amount DOUBLE( 10,2 ) NOT NULL DEFAULT 0.00 ,
				order_id int( 11 ) NOT NULL ,
				added_date datetime NOT NULL
				
			) {$charset_collate} AUTO_INCREMENT=1";
			
		dbDelta($sql);
}




/****************************************************************
Commission Table 
****************************************************************/
function mlm_core_install_commission()
{
	global $wpdb;
	$charset_collate = mlm_core_set_charset();
	$table_prefix = mlm_core_get_table_prefix();
	
	$sql[] = "CREATE TABLE IF NOT EXISTS ".MLM_COMMISSION."
			(
				id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				date_notified datetime NOT NULL,
				parent_id int(11) NOT NULL,
				child_ids int(11) NOT NULL,
				amount DOUBLE( 10,2 ) NOT NULL DEFAULT 0.00 ,
				payout_id int(11) NOT NULL DEFAULT '0',
				bonus_status int(1) NOT NULL DEFAULT '0',
				`comm_type` int(1) NOT NULL DEFAULT '0' COMMENT 'Commission value level=1, direct referral=2, direct company=3, Left Over=4',
				KEY index_parentid (parent_id)
			) {$charset_collate} AUTO_INCREMENT=1";
			
		dbDelta($sql);
}


/****************************************************************
Payout Master Table 
****************************************************************/
function mlm_core_install_payout_master()
{
	global $wpdb;
	$charset_collate = mlm_core_set_charset();
	$table_prefix = mlm_core_get_table_prefix();
	
	$sql[] = "CREATE TABLE IF NOT EXISTS ".MLM_PAYOUT_MASTER."
			(
				id int(10) unsigned NOT NULL AUTO_INCREMENT,
				date date NOT NULL,
                cap_limit float NOT NULL DEFAULT '0.0',
				PRIMARY KEY (`id`)
			) {$charset_collate} AUTO_INCREMENT=1";
			
		dbDelta($sql);
}


/****************************************************************
Payout Table 
****************************************************************/
function mlm_core_install_payout()
{
	global $wpdb;
	$charset_collate = mlm_core_set_charset();
	$table_prefix = mlm_core_get_table_prefix();
	
	$sql[] = "CREATE TABLE IF NOT EXISTS ".MLM_PAYOUT."
			(
				  id int(10) unsigned NOT NULL AUTO_INCREMENT,
				  user_id bigint(20) NOT NULL,
				  date date NOT NULL,
				  payout_id int(11) NOT NULL,
				  commission_amount double(10,2) DEFAULT '0.00',
				  bonus_amount double(10,2) DEFAULT '0.00',
				  total_amt double(10,2) DEFAULT '0.00',
                  capped_amt VARCHAR( 100 ) NOT NULL DEFAULT '0.00',
				  cap_limit VARCHAR( 100 ) NOT NULL DEFAULT '0.00' ,
				  PRIMARY KEY (`id`)
			) {$charset_collate} AUTO_INCREMENT=1";

		dbDelta($sql);
}

/****************************************************************
Transaction Table 
****************************************************************/
function mlm_core_install_transaction()
{
	global $wpdb;
	$charset_collate = mlm_core_set_charset();
	$table_prefix = mlm_core_get_table_prefix();
	
	 $sql[] = "CREATE TABLE IF NOT EXISTS  ".MLM_TRANSACTION."
			(
				id BIGINT(20) NOT NULL  AUTO_INCREMENT PRIMARY KEY,
				user_id BIGINT(20) NOT NULL,
				cr_id BIGINT(20) NOT NULL,
				dr_id BIGINT(20) NOT NULL,
				tr_id BIGINT(20) NOT NULL,
				opening_bal DOUBLE(10,2) NOT NULL DEFAULT 0.00,
				cr_amount DOUBLE(10,2) NOT NULL DEFAULT 0.00,
				dr_amount DOUBLE(10,2) NOT NULL DEFAULT 0.00,
				closing_bal DOUBLE(10,2) NOT NULL DEFAULT 0.00,
				transaction_date DATE ,
				transaction_type ENUM('1','2','3') COMMENT '1=>cr,2=>dr,3=>tr',
				comment VARCHAR(100) 
			) {$charset_collate} AUTO_INCREMENT=1";
		dbDelta($sql); 
}

/****************************************************************
Withdrawal Table 
****************************************************************/
function mlm_core_install_withdrawal()
{
	global $wpdb;
	$charset_collate = mlm_core_set_charset();
	$table_prefix = mlm_core_get_table_prefix();
	
	$sql[] = "CREATE TABLE IF NOT EXISTS  ".MLM_WITHDRAWAL."
			(
				id BIGINT(20) NOT NULL  AUTO_INCREMENT PRIMARY KEY,
				user_id BIGINT(20) NOT NULL,
				amount  DOUBLE(10,2) NOT NULL,
				withdrawal_fee  DOUBLE(10,2) NOT NULL,
				witholding_tax double(10,2) DEFAULT '0.00',
				tax_status ENUM('0','1') DEFAULT '0',
				withdrawal_initiated TINYINT NOT NULL,
				withdrawal_mode VARCHAR(200) NOT NULL,
				other_method VARCHAR(200) NOT NULL,
				withdrawal_initiated_comment VARCHAR(200) NOT  NULL,
				withdrawal_initiated_date DATE NOT NULL,
				payment_processed TINYINT NOT NULL,
				payment_processed_date DATE NOT NULL,
				payment_mode VARCHAR(100) NOT NULL,
				banktransfer_code VARCHAR(100) NOT NULL,
				beneficiary VARCHAR(100),
				cheque_no VARCHAR(100) NOT NULL,
				cheque_date DATE NOT NULL,
				bank_name  VARCHAR(100) NOT NULL,
				user_bank_name VARCHAR(50) NOT NULL,
				user_bank_account_no VARCHAR(10) NOT NULL,
				courier_name VARCHAR(20) NOT NULL,
				awb_no VARCHAR(20) NOT NULL,
				dispatch_date DATE,
				other_comments VARCHAR(100) NOT NULL
			) {$charset_collate} AUTO_INCREMENT=1";
		dbDelta($sql); 
}

/****************************************************************
Delete User data from wp users & wp usermeta TABLE
****************************************************************/
function mlm_core_delete_users_data()
{
	global $wpdb;
	$table_prefix = mlm_core_get_table_prefix();
	$sql = "SELECT user_id FROM ".MLM_USERS;
								
	$results = $wpdb->get_results($sql);
	$user_id = '';

	foreach($results as $row)
	{
		$user_id .= $row->user_id.",";
	}
	$user_id = substr($user_id, 0, -1);
	if(!empty($user_id)){
	$wpdb->query("DELETE FROM {$table_prefix}users WHERE ID IN ($user_id)");
	$wpdb->query("DELETE FROM {$table_prefix}usermeta WHERE user_id IN ($user_id)"); }
}

/****************************************************************
Update All User Role from subscriber to MLM User in  wp mlm_user TABLE
****************************************************************/
function mlm_core_update_user_role()
{
	global $wpdb;
	$table_prefix = mlm_core_get_table_prefix();
	$sql = "SELECT user_id FROM ".MLM_USERS;
								
	$results = $wpdb->get_results($sql);
	$user_id = '';

	foreach($results as $row)
	{
		$user_id .= $row->user_id.",";
        wp_update_user( array ('ID' => $row->user_id, 'role' => 'mlm_user' ) ) ;        
	}
	 $user_id = substr($user_id, 0, -1);

}




/****************************************************************
Drop All Table 
****************************************************************/
function mlm_core_drop_tables()
{
	global $wpdb;
	$table_prefix = mlm_core_get_table_prefix();
	mlm_core_delete_users_data();
	$wpdb->query( "DROP TABLE ".MLM_USERS );
	$wpdb->query( "DROP TABLE ".MLM_COMMISSION );
	$wpdb->query( "DROP TABLE ".MLM_PAYOUT );
	$wpdb->query( "DROP TABLE ".MLM_PAYOUT_MASTER );
	$wpdb->query( "DROP TABLE ".MLM_TRANSACTION );
	$wpdb->query( "DROP TABLE ".MLM_WITHDRAWAL );
	$wpdb->query( "DROP TABLE ".MLM_DELETED_USERS );
	$wpdb->query( "DROP TABLE ".MLM_TEAM_SALES );
	
}
?>