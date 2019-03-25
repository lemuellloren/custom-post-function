<?php 
require_once('../../../wp-config.php');
$g_criteria = ""; 
$g_criteria1 = ""; 
$g_criteria2 = ""; 
$g_criteria3 = "";
 
if(isset($_REQUEST['do'])) {
	$g_criteria1 = trim($_REQUEST['do']);
}

if(isset($_REQUEST['event'])) {
	$g_criteria2 = trim($_REQUEST['event']);
}


switch($g_criteria1)
{
	
	case "statuschange": 
		updatePaymentStatus();
		
	break;
	
	case "changesponsor":
	changesponsor();
	
	break;
	case "sponsor":
	entersponsor();
	
	break;
}


function updatePaymentStatus() 
{ 
	global $wpdb,$table_prefix;
	if(isset($_REQUEST['userId']) && isset($_REQUEST['status']))
	{
		
		if($_REQUEST['status']=='0')
		{
		 $sql = "UPDATE ".MLM_USERS."  SET `payment_status` = '0' WHERE user_id = '".$_REQUEST['userId']."'";
		$wpdb->query($sql);
		
		}
        else
		{						 
          UserStatusUpdate($_REQUEST['userId']);
	        
			
		}

		
	}  //end of else condition
	
}

function changesponsor()
{

global $wpdb,$table_prefix;
if(isset($_REQUEST['username']))
{
$q=$_REQUEST['username'];
$res=$wpdb->get_results("select username from ".MLM_USERS." where username like '$q%' order by id LIMIT 5");

if(!empty($res)) {
echo "<ul style='margin:0px;'>";
foreach($res as $data)
{
$username=$data->username;

//$email=$row['email'];
$b_username='<strong>'.$q.'</strong>';
//$b_email='<strong>'.$q.'</strong>';
$final_username = str_ireplace($q, $b_username, $username);
//$final_email = str_ireplace($q, $b_email, $email);
?>

<li class="name"><?php echo $final_username; ?></li>

<?php

  }
  
  echo "</ul>";
  }
 /* elseif (strlen($q) > 2){
  echo '<li class="name">Not Found</li>';
  }*/
  
}

}


function entersponsor(){

global $wpdb,$table_prefix;
if(isset($_REQUEST['username']))
{
$q=$_REQUEST['username'];
$res=$wpdb->get_results("select username from ".MLM_USERS." where username like '$q%' order by id LIMIT 5");

if(!empty($res)) {
echo "<ul style='margin:0px;'>";
foreach($res as $data)
{
$username=$data->username;

//$email=$row['email'];
$b_username='<strong>'.$q.'</strong>';
//$b_email='<strong>'.$q.'</strong>';
$final_username = str_ireplace($q, $b_username, $username);
//$final_email = str_ireplace($q, $b_email, $email);
?>

<li class="newname"><?php echo $final_username; ?></li>

<?php

  }
  
  echo "</ul>";
  }
}

}

?>