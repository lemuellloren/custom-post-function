<?php 
require_once( "../../../../wp-config.php" );

$action = $_REQUEST['action'];
//$table_prefix = mlm_core_get_table_prefix();
 
 global $wpdb, $table_prefix;
if($action == 'username')
{
	$q = $_GET['q'];
	$uname = $wpdb->get_var("SELECT user_login FROM {$table_prefix}users WHERE user_login = '$q'");
	if($uname)
		_e("<span class='errormsg'>Sorry! The specified username is not available for registration.</span>");
	else
		_e("<span class='msg'>Congratulations! The username is available.</span>");
}
else if($action == 'sponsor')
{
	
	 $q = $_GET['q'];
	
	$sname = $wpdb->get_var("SELECT count(*) num FROM ".MLM_USERS." WHERE `username` = '".$q."'");
	
	if($sname==0){
		echo "<span class='errormsg'>Sorry! The specified sponsor is not available for registration.</span>";
		}
	else
		echo "<span class='msg'>Congratulations! Your sponsor is <b> ".ucwords(strtolower($q))."</b> .</span>";
}
?>