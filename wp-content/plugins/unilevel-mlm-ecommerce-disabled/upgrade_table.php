<?php
require_once('../../../wp-config.php');
global $wpdb, $table_prefix;

if($_POST['upgrade'] == 'version' && is_super_admin() )
{

$run_once = get_option('upgrade_ume_table');

if (!$run_once)
{

global $wpdb,$table_prefix;


/********* Payout Table ************/
$payout = $wpdb->query("INSERT INTO ".MLM_PAYOUT." SELECT * FROM {$table_prefix}mlm_payout");
if($payout){
$wpdb->query("UPDATE ".MLM_PAYOUT." as a INNER JOIN {$table_prefix}mlm_users as b  ON a.user_id=b.id SET a.user_id = b.user_id");
}

/********* Payout Table ************/




/********** Transaction Table *************/
$transaction = $wpdb->query("INSERT INTO ".MLM_TRANSACTION." SELECT * FROM {$table_prefix}mlm_transaction");
if($transaction){
$wpdb->query("UPDATE ".MLM_TRANSACTION." as a INNER JOIN {$table_prefix}mlm_users as b  ON a.user_id=b.id SET a.user_id = b.user_id");
}

/********** Transaction Table *************/





/********** Withdrawal Table *************/
$withdrawal = $wpdb->query("INSERT INTO ".MLM_WITHDRAWAL." SELECT * FROM {$table_prefix}mlm_withdrawal");
if($withdrawal)
{
$wpdb->query("UPDATE ".MLM_WITHDRAWAL." as a INNER JOIN {$table_prefix}mlm_users as b  ON a.user_id=b.id SET a.user_id = b.user_id");
}

/********** Withdrawal Table *************/




/********** Payout Master Table *************/
$pay_master = $wpdb->query("INSERT INTO ".MLM_PAYOUT_MASTER." SELECT * FROM {$table_prefix}mlm_payout_master");

/********** Payout Master Table *************/




/*********** MLM Users Table********/

//INSERT INTO wp_mlm_users (user_id,username,user_key,payment_status,payment_date,banned)  SELECT user_id,username,user_key,payment_status,payment_date,banned FROM mynet_mlm_users 

$Inser_User = $wpdb->query("INSERT INTO ".MLM_USERS." (user_id,username,user_key,parent_id,sponsor_id,payment_date)  SELECT user_id,username,user_key,parent_key,sponsor_key,payment_date FROM {$table_prefix}mlm_users ");

//UPDATE wp_mlm_users as a INNER JOIN mynet_mlm_users as b  ON a.parent_id=b.user_key SET a.parent_id= b.user_id,a.sponsor_id = b.user_id
$wpdb->query("UPDATE ".MLM_USERS." as a INNER JOIN {$table_prefix}mlm_users as b  ON a.parent_id=b.user_key SET a.parent_id = b.user_id,a.sponsor_id = b.user_id");

//UPDATE wp_mlm_users as a INNER JOIN mynet_mlm_users as b  ON a.user_id=b.user_id SET a.payment_status = b.payment_status where b.payment_status!='0'

$wpdb->query("UPDATE ".MLM_USERS." as a INNER JOIN {$table_prefix}mlm_users as b  ON a.user_id=b.user_id SET a.payment_status = b.payment_status where b.payment_status!='0'");



$total = $wpdb->get_results("SELECT * FROM ".MLM_USERS." ORDER BY ID ASC");

foreach($total as $results){

if(!empty($results->sponsor_id)){
 $parent = getusernamebyId($results->sponsor_id);
 add_user_meta( $results->user_id, 'mlm_user_sponsor', $parent, FALSE );
 
     $user = new WP_User($results->user_id);
     $user->remove_role('customer');
     $user->add_role('mlm_user');
 
  }

}
/*********** MLM Users Table********/



/********* Commission Table **********/
$commission = $wpdb->query("INSERT INTO ".MLM_COMMISSION." SELECT * FROM {$table_prefix}mlm_commission");

/* UPDATE wp_mlm_commission as a INNER JOIN mynet_mlm_users as b  ON a.parent_id=b.user_key SET a.parent_id = b.user_id*/

$wpdb->query("UPDATE ".MLM_COMMISSION." as a INNER JOIN {$table_prefix}mlm_users as b  ON a.parent_id=b.user_key SET a.parent_id = b.user_id");

/* UPDATE wp_mlm_commission as a INNER JOIN mynet_mlm_users as b  ON a.child_ids=b.user_key SET a.child_ids = b.user_id*/
$wpdb->query("UPDATE ".MLM_COMMISSION." as a INNER JOIN {$table_prefix}mlm_users as b  ON a.child_ids=b.user_key SET a.child_ids = b.user_id");
/********* Commission Table **********/



          update_option('upgrade_ume_table',true);
          
          update_option('upgrade_ume_250', true);
          /************** Update General Settings Options *********************************/
          
          $eligibility = get_option('wp_mlm_eligibility_settings');
          
          $mlm_general = get_option('wp_mlm_general_settings'); 
          $mlm_general['personal_referrer']=$eligibility['personal_referrer'];
          $mlm_general['registration'] = '1';
          update_option('wp_mlm_general_settings',$mlm_general);
          
          /**************EOF***************************/ 
           
          /***************** Update Payout Settings Options ******************************/
          
          $payout = get_option('wp_mlm_payout_settings');
          $general = get_option('wp_mlm_general_settings'); 
          $level =$general['mlm-level'];
          for($i=1;$i<=$level;$i++)
          {
          $old = 'level'.$i.'_commission';
          $new ='level'.$i.'_ntwk_commission';
          $payout[$new] =$payout[$old];
          }
          update_option('wp_mlm_payout_settings',$payout);
          
          /**************EOF***************************/ 
          

         _e("<div class='notibar msgsuccess'><p>Data Upgraded Successfully!</p></div>",MLM_PLUGIN_NAME);

}
else {
         _e("<div class='notibar msgerror'><p>Data Up gradation failed!</p></div>",MLM_PLUGIN_NAME);
     }
}

//delete_option('upgrade_ume_table');