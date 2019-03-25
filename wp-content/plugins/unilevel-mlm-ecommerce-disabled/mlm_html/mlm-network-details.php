<?php
function mlmNetworkDetailsPage()
{
        //get logged in user info
	global $current_user, $wpdb;
	//get loged user's key
	
	$user_id = get_current_userID();

	//Total my personal sales
	$personalSales = totalMyPersonalSales($user_id);
	
	//Total my personal sales active users
	$activePersonalSales = activeUsersOnPersonalSales($user_id);
	
	
	//show five users on personal sales
	$fivePersonalUsers = myFivePersonalUsers($user_id);
	

	$table_prefix = mlm_core_get_table_prefix();
	get_currentuserinfo();
	$username = $current_user->ID;

	$user_info = get_userdata($current_user->ID);
	$_SESSION['ajax'] = 'ajax_check';
	
	$add_page_id = get_post_id('mlm_registration_page');
	$sponsor_name = $current_user->user_login;
	
	    
		/**********Affiliate URL CODE***********************/
    $mlm_settings = get_option('wp_mlm_general_settings');
   		
    $affiliateURLold = site_url() . '?page_id=' . $add_page_id . '&sp_name=' . $sponsor_name;
    $affiliateURLnew = site_url() . '/u/' . $sponsor_name;

    $permalink = get_permalink(empty($_GET['page_id']) ? '' : $_GET['page_id']);
    $postidparamalink = strstr($permalink, 'page_id');
    $affiliateURL = ($postidparamalink) ? $affiliateURLold : $affiliateURLnew;
    
     //   $affiliateURL = site_url().'/u/'.$sponsor_name;
		
		/*********E O F Affiliate URL Code************************/
	 
	 
	 $view_memberpage_id = $wpdb->get_var("SELECT id FROM {$table_prefix}posts  WHERE `post_content` LIKE '%mlm-view-child-level-member%'	AND `post_type` != 'revision'");
	
	$mlm_general_settings = get_option('wp_mlm_general_settings');
	$mlm_no_of_level=$mlm_general_settings['mlm-level'];
	$spon_name = $wpdb->get_var("select username from ".MLM_USERS." where user_id IN(select parent_id from ".MLM_USERS." where user_id='".$current_user->ID."')");


?>
	     <?php if (function_exists('Update_Paypal_Notification')) { Update_Paypal_Notification();  } ?>	
<p class="affiliate_url"><strong>Affiliate URL :</strong> <?php  echo   $affiliateURL ?> </p><br /> 
		<table width="100%" border="0" cellspacing="10" cellpadding="1">
		  <tr>
			<td width="40%" valign="top">
				<table width="100%" border="0" cellspacing="10" cellpadding="1">
				  <tr>
					<td colspan="2"><strong><?PHP _e('Personal Information',MLM_PLUGIN_NAME);?></strong></td>
				  </tr>
				  <tr>
					<td scope="row"><?php _e('Title',MLM_PLUGIN_NAME);?></td>
					<td><?PHP _e('Details',MLM_PLUGIN_NAME);?></td>
				  </tr>
				  <tr>
					<td scope="row"><?PHP _e('First Name',MLM_PLUGIN_NAME);?></td>
					<td><?php  echo  $user_info->first_name ?></td>
				  </tr>
				  <tr>
					<td scope="row"><?PHP _e('Last Name',MLM_PLUGIN_NAME);?></td>
					<td style="white-space:normal;"><?php  echo  $user_info->last_name ?></td>
				  </tr>
				  <tr>
					<td scope="row"><?PHP _e('Username',MLM_PLUGIN_NAME);?></td>
					<td><?php  echo  $user_info->user_login ?></td>
				  </tr>
				  <tr>
					<td scope="row"><?PHP _e('Email',MLM_PLUGIN_NAME);?>.</td>
					<td><?php  echo  $user_info->user_email ?></td>
				  </tr>
				  <?php if($spon_name) { ?>
				  <tr>
					<td scope="row"><?PHP _e('Sponsor',MLM_PLUGIN_NAME);?></td>
					<td><?php  echo  $spon_name ?></td>
				  </tr>
				  <?php } ?>
				  
				  
				  <tr>
		<td>&nbsp;</td>
		<td><a href="<?php  echo   get_post_id_or_postname('mlm_network_genealogy_page',MLM_PLUGIN_NAME);?>" style="text-decoration: none"><?php _e('View Genealogy',MLM_PLUGIN_NAME);?></a></td>
		</tr>
				</table> 
					<table width="100%" border="0" cellspacing="10" cellpadding="1">
				  <tr>
					<td colspan="2"><strong><?php _e('My Payouts',MLM_PLUGIN_NAME);?></strong></td>
				  </tr>
				  <tr>
					<td scope="row"><?php _e('Date',MLM_PLUGIN_NAME);?></td>
					<td><?php _e('Amount',MLM_PLUGIN_NAME);?></td>
                                        <td><?php _e('Action',MLM_PLUGIN_NAME);?></td>
				  </tr>
<?php $detailsArr =  my_payout_function();
//_e("<pre>");print_r($detailsArr); exit; 
//$page_id = get_post_id('mlm_my_payout_details_page');
if(count($detailsArr)>0){
$mlm_settings = get_option('wp_mlm_general_settings');
	?>
			<?php foreach($detailsArr as $row) :  
					
		$amount = $row->commission_amount + $row->bonus_amount;

		?>
		<tr>
			<td><?php  echo   $row->payoutDate ?></td>
			<td><?php  echo   MLM_CURRENCY.' '.$amount ?></td>
			<td><a href="<?php  echo  get_post_id_or_postname_for_payout('mlm_my_payout_details_page', $row->payout_id)?>">View</a></td>
			
		</tr>
		
		<?php endforeach; ?>
	<?php 
	}else{

	?>
	<div class="no-payout"><?php _e('You have not earned any commisssions yet.',MLM_PLUGIN_NAME);?> </div>
	
	<?php 
	}
	?>
				</table>
				</td>
				<td width="40%">
				<table width="100%" border="0" cellspacing="10" cellpadding="1">
				<tr>
				<td><strong><?php _e('Network Details',MLM_PLUGIN_NAME);?></strong></td>
				</tr>
				
				
				
				<tr>
				<td>
				<table width="100%" border="0" cellspacing="10" cellpadding="1">
				<tr>
				<td colspan="2"><strong><?php _e('Personal Sales',MLM_PLUGIN_NAME);?></strong></td>
				</tr>
				
				<tr>
				<td><?php _e('My Personal Sales',MLM_PLUGIN_NAME);?>: <?php  echo   $personalSales?></td>
				<td><?php _e('Active',MLM_PLUGIN_NAME);?>: <?php  echo   $activePersonalSales?></td>
				</tr>
							<?php
		foreach($fivePersonalUsers as $key => $value)
		{
			_e("<tr>");
			foreach($value as $k=>$val)
			{
				_e("<td>".$val."</td>");
			}
			_e("</tr>");
		}
		?>
							<tr>
		<td colspan="2"><a href="?page_id=<?php  echo   $view_memberpage_id?>&lvl=1" style="text-decoration: none"><?php _e('View All',MLM_PLUGIN_NAME);?></a></td>
		</tr>
							
						</table>
					</td>
				  </tr> 
				  
				</table>
			</td>
		  </tr>
		</table>

<?php



}
?>