<?php
require_once("php-form-validation.php");

function join_network_page() {
    global $wpdb, $current_user;
    $user_id = $current_user->ID;
    $table_prefix = mlm_core_get_table_prefix();
    $error = '';
    $chk = 'error';
    global $current_user;
    if (!is_user_logged_in()) {  
    	$chk = ''; echo $msg="You need to logged in first!";  
   	echo "<script>window.location='".site_url()."'</script>";  
   }
    $mlm_general_settings = get_option('wp_mlm_general_settings');
    if ( current_user_can( 'manage_options' ) )  {  _e(ADMIN_MESSAGE_JOIN_PAGE,MLM_PLUGIN_NAME);  
    return false;
    // echo "<script>window.location='".site_url()."'</script>";  
    }


$registration = $mlm_general_settings['registration'];
 
 if($registration =='2')
 {
  $reg_cat = $mlm_general_settings['product-category'];
 }    
 else if($registration =='1') {
 $order_ids=ume_get_all_user_orders($user_id, 'completed');
 }
        get_currentuserinfo();
	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);
	?>
	
	 <script type="text/javascript">

            function checkReferrerAvailability(str)
            {
              /*  if (isSpclChar(str, 'sponsor') == false)
                {
                    document.getElementById('sponsor').focus();
                    return false;
                }*/
                var xmlhttp;

                if (str != "") {

                    if (window.XMLHttpRequest)
                    {// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp = new XMLHttpRequest();
                    }
                    else
                    {// code for IE6, IE5
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange = function()
                    {
                        if (xmlhttp.status == 200 && xmlhttp.readyState == 4)
                        {
                            document.getElementById("check_referrer").innerHTML = xmlhttp.responseText;
                        }
                    }
                    var data = "<?php echo MLM_PLUGIN_URL . 'ajax/check_username.php?action=sponsor&q=' ?>" + str; 
                    xmlhttp.open("GET", data, true);
                    xmlhttp.send();

                }
            }
            
        </script>
	
	
	
	
	
	<?php  
	
	if($user_role == 'mlm_user')
         {
         _e(ALREADY_MLM_USER,MLM_PLUGIN_NAME);
          }
         else if($registration =='1' && empty($order_ids)){
         
         $ume_shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
	 _e(NO_ORDER_PLACED_MLM_USER,MLM_PLUGIN_NAME); 
	 _e('<a href="'.$ume_shop_page_url.'">Click here</a> to go to our shop.',MLM_PLUGIN_NAME);
        
         }
         else 
         {

          _e(PURCHASE_FOR_JOIN_MLM_PART,MLM_PLUGIN_NAME);

        //  echo  do_shortcode('[product_category category="'.$reg_cat.'" per_page="10" columns="4" orderby="rand" order="desc"]');
         
         $chk = 'error';
         $error = '';
         if (isset($_POST['submit'])) {
         $sponsor = sanitize_text_field($_POST['sponsor']);
        if (checkInputField($sponsor))
            $error .= "\n Please enter your sponsor name.";
        if(!empty($sponsor)){
        
        $check = $wpdb->get_var("SELECT COUNT(*) ck FROM ".MLM_USERS." WHERE `username` = '" . $sponsor . "'");
        if ($check ==0) {
         $error .= "\n Please enter correct sponsor name.";
         }
         
        }
            
            if (empty($error)) {
             
             if($registration =='1') {
         
             $parent_id = $sponsor_id = $wpdb->get_var("SELECT user_id FROM ".MLM_USERS." where username='".$sponsor."'");
			
             $username = $wpdb->get_var("SELECT user_login FROM {$table_prefix}users WHERE ID = '".$user_id."'");
             
             
                //generate random numeric key for new user registration
		$user_key = generateKey();
		//if generated key is already exist in the DB then again re-generate key
		do
		{
			$check = $wpdb->get_var("SELECT COUNT(*) ck 
													FROM ".MLM_USERS." 
													WHERE `user_key` = '".$user_key."'");
			$flag = 1;
			if($check == 1)
			{
				$user_key = generateKey();
				$flag = 0;
			}
		}while($flag==0);
             
             $insert = "INSERT INTO ".MLM_USERS."
						   (
								user_id, username,user_key, parent_id, sponsor_id, payment_status,payment_date
							) 
							VALUES
							(
							'".$user_id."','".$username."','".$user_key."','".$parent_id."', '".$sponsor_id."','1', '".current_time('mysql')."'
							)"; 
							
	     $wpdb->query($insert);
				  
             update_user_meta($user_id, 'mlm_user_sponsor', $sponsor);
            
             wp_update_user(array('ID' => $user_id, 'role' => 'mlm_user'));  
             
             if(!empty($mlm_general_settings['coupon_amount'])) { GenerateCouponUsername($username); }
             
             if($u=get_option('network_mail', true)==1) { NewUserMailSend($user_id); }
             
             echo "<Script> window.location.reload();</script>";
             
             }
             else {
             
             update_user_meta($user_id, 'mlm_user_sponsor', $sponsor);
             
             //redirect to qualification product page
             
		$prod_cat_args = array(
		'taxonomy'     => 'product_cat', //woocommerce
		'orderby'      => 'name',
		'empty'        => 0
		);
		
		$woo_categories = get_categories( $prod_cat_args );
		
		$reg_cat = $mlm_general_settings['product-category'];
		
		foreach ( $woo_categories as $woo_cat ) {
		$woo_cat_id = $woo_cat->term_id; //category ID
		$woo_cat_name = $woo_cat->name; //category name
		$woo_cat_slug = $woo_cat->slug; //category slug
		if($reg_cat==$woo_cat_slug)
		{
		$url = get_term_link( $woo_cat_slug, 'product_cat' );
		}
		}
                
                if($url=='') { $url = site_url(); }
                echo "<Script> window.location.href = '".$url."'; </script>";
                
             }
             $chk = '';  
             
             }
            
         }   
         
         
         




       $spon =  get_user_meta($user_id, 'mlm_user_sponsor', true);
       $top =get_user_name_top_user();
       $user_spon = !empty($spon ) ? $spon : $top ;
         
if (!empty($error)) { $error = nl2br($error); }           
    
if ($chk != '' && $user_role != 'mlm_user' ) {

?>
            <span style='color:red;'><?php echo $error ?></span>
            <?php if (isset($msg) && $msg != "") echo $msg; ?>	
            <form name="frm" method="post" action="" onSubmit="return JoinNetworkformValidation();">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">


                    <tr>
                        
                        <td><?php _e('Sponsor Name', MLM_PLUGIN_NAME); ?> <span style="color:red;">*</span> :</td>
                        <td>
                            <input type="text" name="sponsor" id="sponsor" value="<?php !empty($_POST['sponsor'])? _e(htmlentities($_POST['sponsor'])): _e(htmlentities($user_spon)) ; ?>"  maxlength="20" size="37" onBlur="checkReferrerAvailability(this.value);" >
                            <br /><div id="check_referrer"></div>
                        </td>
                    </tr>
         
         <tr>
                        <td colspan="2"><input type="submit" name="submit" id="submit" value="<?php _e('Become a BFPreneur', MLM_PLUGIN_NAME); ?>" /></td>
                    </tr>
                </table>
            </form>
         <?php
 } else {   _e($msg);  }
         

         
         }
}

/* function end */
?>
