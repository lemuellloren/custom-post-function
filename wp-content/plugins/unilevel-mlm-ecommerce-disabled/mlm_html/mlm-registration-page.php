<?php
require_once("php-form-validation.php");
function register_user_html_page()
{
	global $wpdb;
	$table_prefix = mlm_core_get_table_prefix();
	$error = '';
	$chk = 'error';
	global $current_user;
	get_currentuserinfo();
        $user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);

		if(!empty($_GET['sp_name']))
		   {
		
		   $sp_name= $_GET['sp_name'];
	       ?>
			<script>function setCookie(key, value) {
            var expires = new Date();
            expires.setTime(expires.getTime() + (1 * 24 * 60 * 60 * 1000));
            document.cookie = key + '=' + value + ';path=/;expires=' + expires.toUTCString();
        }
         setCookie('s_name','<?php  echo   $sp_name ?>');
         //$.cookie('s_name','<?php  echo   $sp_name ?>',{ path: '/' });</script>
	       
	       <?php 
		   }
		   else 
		   {
	       $sp_name= isset($_COOKIE["s_name"])?$_COOKIE["s_name"] : '';
	       }

	//get no. of level
	$mlm_general_settings = get_option('wp_mlm_general_settings');
	$mlm_no_of_level=$mlm_general_settings['mlm-level'];

	 
	if(is_user_logged_in())
	{
	
		$sponsor_name = $current_user->user_login;
		$readonly_sponsor = 'readonly';
	    
		$spnsr_set=1;
	}

	else if(isset($_REQUEST['sp_name']) &&  $_REQUEST['sp_name'] != '')
	{
		
		  
		 $sponsorName = $_REQUEST['sp_name'];	
		if(isset($sponsorName) && $sponsorName !='' )		
		{
			$readonly_sponsor = 'readonly';
			$sponsor_name = $sponsorName;
		}else{
			
			redirectPage(home_url(), array()); exit; 

		}
		
	}
		else if(isset($_COOKIE["s_name"]) && $_COOKIE["s_name"] !='')
	{
			$readonly_sponsor = 'readonly';
			$sponsor_name = $_COOKIE["s_name"];
	
	}
	else
	{
		$readonly_sponsor = '';
	}
	

	//most outer if condition
	if(isset($_POST['submit']))
	{
		$firstname = sanitize_text_field( $_POST['firstname'] );
		$lastname = sanitize_text_field( $_POST['lastname'] );
		$username = sanitize_text_field( $_POST['username'] );
		
		
		$password = sanitize_text_field( $_POST['password'] );
		$confirm_pass = sanitize_text_field( $_POST['confirm_password'] );
		$email = sanitize_text_field( $_POST['email'] );
		$confirm_email = sanitize_text_field( $_POST['confirm_email'] );
		$sponsor = sanitize_text_field( $_POST['sponsor'] );
	
		
		//Add usernames we don't want used
		$invalid_usernames = array( 'admin' );
		//Do username validation
		$username = sanitize_user( $username );
		
		if(!validate_username($username) || in_array($username, $invalid_usernames)) 
			$error .= "\n Username is invalid.";
			
		if ( username_exists( $username ) ) 
			$error .= "\n Username already exists.";
		
		if ( checkInputField($password) ) 
			$error .= "\n Please enter your password.";
			
		if ( confirmPassword($password, $confirm_pass) ) 
			$error .= "\n Please confirm your password.";
		

		//Do e-mail address validation
		if ( !is_email( $email ) )
			$error .= "\n E-mail address is invalid.";
			
		if (email_exists($email))
			$error .= "\n E-mail address is already in use.";
		
		if ( confirmEmail($email, $confirm_email) ) 
			$error .= "\n Please confirm your email address.";

		
		if ( checkInputField($firstname) ) 
			$error .= "\n Please enter your first name.";
			
		if ( checkInputField($lastname) ) 
			$error .= "\n Please enter your last name.";

		if ( checkInputField($sponsor) && !empty($sponsor) ) 
			$error .= "\n Please enter your sponsor name.";

	
		//Case If User is not fill the Sponser field
		if(empty($_POST['sponsor']))
		{		
		$sponsor = get_top_level_user();
		}
		$mlm_sponsor = $sponsor;
		
		$sql = "SELECT COUNT(*) num, `user_id` FROM ".MLM_USERS." WHERE `username` = '".$sponsor."'";
		$intro = $wpdb->get_row($sql);

		// outer if condition
		if(empty($error))
		{
			// inner if condition
			if($intro->num==1)
			{
				$sponsor = $intro->user_id;
			
				$user = array
				(
					'user_login' => $username,
					'user_pass' => $password,
					'first_name' => $firstname,
					'last_name' => $lastname,
					'user_email' => $email,
					'role'  => 'customer'
				);
				
				// return the wp_users table inserted user's ID
				$user_id = wp_insert_user($user);
				
				
				/*Send e-mail to admin and new user - 
				You could create your own e-mail instead of using this function*/
				wp_new_user_notification($user_id, $password);
			    
				add_user_meta( $user_id, 'mlm_user_sponsor', $mlm_sponsor, FALSE );
			           
			    
				$paymentStatus = '0'; 
				
      			$chk = '';
				$msg = "<span style='color:green;'>Congratulations! You have successfully registered in the system.</span>";

				 			
			} //end inner if condition
			else
				$error =  "\n Sponsor does not exist in the system.";
		} //end outer if condition
	} //end most outer if condition
	
	//if any error occoured
	if(!empty($error))
		$error = nl2br($error);

	if($chk!='')
	{
?>

 
<script type="text/javascript">
var popup1,popup2,splofferpopup1;
var bas_cal, dp_cal1,dp_cal2, ms_cal; // declare the calendars as global variables 
window.onload = function() {
	dp_cal1 = new Epoch('dp_cal1','popup',document.getElementById('dob'));  
};

function checkUserNameAvailability(str)
{
	//alert(url); return true; 
		
	if(isSpclChar(str, 'username')==false)
	{
		document.getElementById('username').focus();
		return false;
	}
	var xmlhttp;    
	if (str!="")
  	{
	
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	if (xmlhttp.status==200 && xmlhttp.readyState==4)
	{
	 document.getElementById("check_user").innerHTML=xmlhttp.responseText;
	 //alert(xmlhttp.responseText);
	}
	}   
	
	xmlhttp.open("GET", "<?php  echo   MLM_PLUGIN_URL.'ajax/check_username.php'?>"+"?action=username&q="+str,true);
	xmlhttp.send();
     }

}


function checkReferrerAvailability(str)
{ 
	if(isSpclChar(str, 'sponsor')==false)
	{
		document.getElementById('sponsor').focus();
		return false;
	}
	var xmlhttp;    
	
	if (str!="") {
	
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	if (xmlhttp.status==200 && xmlhttp.readyState==4)
	{
	 document.getElementById("check_referrer").innerHTML=xmlhttp.responseText;
	}
	}
	xmlhttp.open("GET", "<?php  echo   MLM_PLUGIN_URL.'ajax/check_username.php'?>"+"?action=sponsor&q="+str,true);
	xmlhttp.send();

	}
}


</script>

        <?php
        $general_setting = get_option('wp_mlm_general_settings');
		if ( is_user_logged_in() ) {
		 if (!empty($general_setting['wp_reg']) && !empty($general_setting['reg_url']) && $user_role!='mlm_user'){
            echo "<script>window.location ='" . site_url() . '/' . $general_setting['reg_url'] . "'</script>"; }
		}	
		else {
		if (!empty($general_setting['wp_reg']) && !empty($general_setting['reg_url'])){
            echo "<script>window.location ='" . site_url() . '/' . $general_setting['reg_url'] . "'</script>"; }
		}
        ?>
		
		
<span style='color:red;'><?php  echo  $error?></span>
<?php if(isset($msg) && $msg!="") echo $msg;?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<form name="frm" method="post" action="" onSubmit="return formValidationNewVer();">
		<tr>
			<td><?php _e('Create Username',MLM_PLUGIN_NAME);?><span style="color:red;">*</span> :</td>
			<td><input type="text" name="username" id="username" value="<?php if(!empty($_POST['username']))  _e( htmlentities($_POST['username'])); ?>" maxlength="20" size="37" onBlur="checkUserNameAvailability(this.value);"><br /><div id="check_user"></div></td>
		</tr>
		
		<tr><td colspan="2">&nbsp;</td></tr>
		
		<tr>
			<td><?php _e('Create Password',MLM_PLUGIN_NAME) ?> <span style="color:red;">*</span> :</td>
			<td>	<input type="password" name="password" id="password" maxlength="20" size="37" >
				<br /><span style="font-size:12px; font-style:italic; color:#006633"><?php _e('Password length atleast 6 character',MLM_PLUGIN_NAME);?></span>
			</td>
		</tr>
		
		<tr><td colspan="2">&nbsp;</td></tr>
		
		<tr>
			<td><?php _e('Confirm Password',MLM_PLUGIN_NAME) ?>  <span style="color:red;">*</span> :</td>
			<td><input type="password" name="confirm_password" id="confirm_password" maxlength="20" size="37" ></td>
		</tr>
		
		<tr><td colspan="2">&nbsp;</td></tr>

		
		<tr>
			<td><?php  _e('Email Address',MLM_PLUGIN_NAME);?> <span style="color:red;">*</span> :</td>
			<td><input type="text" name="email" id="email" value="<?php if(!empty($_POST['email']))  _e(htmlentities($_POST['email']));?>"  size="37" ></td>
		</tr>
		
		<tr><td colspan="2">&nbsp;</td></tr><tr>
		
		<tr>
			<td><?php  _e('Confirm Email Address',MLM_PLUGIN_NAME);?> <span style="color:red;">*</span> :</td>
			<td><input type="text" name="confirm_email" id="confirm_email" value="<?php if(!empty($_POST['confirm_email']))  _e(htmlentities($_POST['confirm_email']));?>" size="37" ></td>
		</tr>
		
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
			<td><?php  _e('First Name',MLM_PLUGIN_NAME);?> <span style="color:red;">*</span> :</td>
			<td><input type="text" name="firstname" id="firstname" value="<?php if(!empty($_POST['firstname']))  _e(htmlentities($_POST['firstname'])); ?>" maxlength="20" size="37" onBlur="return checkname(this.value, 'firstname');" ></td>
		</tr>
		
		<tr><td colspan="2">&nbsp;</td></tr>
		
		<tr>
			<td><?php  _e('Last Name',MLM_PLUGIN_NAME);?> <span style="color:red;">*</span> :</td>
			<td><input type="text" name="lastname" id="lastname" value="<?php if(!empty($_POST['lastname'])) _e(htmlentities($_POST['lastname']));?>" maxlength="20" size="37" onBlur="return checkname(this.value, 'lastname');"></td>
		</tr>
		
		<tr><td colspan="2">&nbsp;</td></tr>
		
		<tr>
			<?php
			if(isset($sponsor_name) && $sponsor_name!='')
			{
				$spon = $sponsor_name;
			}
			else if(isset($_POST['sponsor']))
				$spon =  htmlentities($_POST['sponsor']);
			?>
			<td><?php  _e('Sponsor Name',MLM_PLUGIN_NAME);?> <span style="color:red;">*</span> :</td>
			<td>
			<input type="text" name="sponsor" id="sponsor" value="<?php if(!empty($spon)) _e($spon);?>" maxlength="20" size="37" onBlur="checkReferrerAvailability(this.value);" <?php  echo   $readonly_sponsor;?>>
			<br /><div id="check_referrer"></div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
			
			<input type="submit" name="submit" id="submit" value="<?php _e('Submit',MLM_PLUGIN_NAME)?>" /></td>
		</tr>
	</form>
</table>
<?php
	}
	else
		 _e($msg);
		 
 
}//function end

?>