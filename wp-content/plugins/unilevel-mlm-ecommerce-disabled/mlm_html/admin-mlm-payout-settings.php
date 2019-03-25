<?php
function mlmPayout()
{
	//get database table prefix
	$table_prefix = mlm_core_get_table_prefix();
	
	$error = '';
	$chk = 'error';
	
	//get no. of level
	$mlm_general_settings = get_option('wp_mlm_general_settings');
	$mlm_no_of_level=$mlm_general_settings['mlm-level'];
	
	//most outer if condition 
	if(isset($_POST['mlm_payout_settings']))
	{
     //echo "<pre>"; print_r($_POST); 

		
                for($k=1;$k<=$mlm_no_of_level;$k++) {
	
		if ( checkInputField(sanitize_text_field($_POST['level'.$k.'_ntwk_commission']))) 
		{
			 $error .= "\n Please specify your  Netowrk Users Level $k Commission.";
                 }

                }

                for($k=1;$k<=$mlm_no_of_level;$k++) {
	
		if ( checkInputField(sanitize_text_field($_POST['level'.$k.'_gnrl_commission']))) 
		{
			 $error .= "\n Please specify your  General Users Level $k Commission.";
                 }

                }
                
		
		//if any error occoured
		if(!empty($error))
			$error = nl2br($error);
		else
		{
			$chk = '';
			update_option('wp_mlm_payout_settings', $_POST);
			$url = get_bloginfo('url')."/wp-admin/admin.php?page=admin-settings&tab=deduction";
			_e("<script>window.location='$url'</script>");
			$msg = "<span style='color:green;'>Your payout settings has been successfully updated.</span>";
		}
	}// end outer if condition
	if($chk!='')
	{
		$mlm_settings = get_option('wp_mlm_payout_settings');
		?>


<div class='wrap1'>
	<h2><?php _e('Payout Settings',MLM_PLUGIN_NAME);?>  </h2>
	<div class="notibar msginfo">
		<a class="close"></a>
		<p><?php _e('These settings define the commissions that would be distributed in the network for a single sale in the network.',MLM_PLUGIN_NAME);?></p>
		
		<p><strong><?php _e('Level Commissions',MLM_PLUGIN_NAME);?> - </strong><?php _e('These are the amounts payable at various levels in the upline depending on the No. of Levels setting defined under the General Tab.',MLM_PLUGIN_NAME);?></p>
<p><strong><?php _e('Note :',MLM_PLUGIN_NAME);?>  </strong><?php _e('The sum of all the commissions mentioned above should always be less than or equal to the
Product Value as defined in the General Tab.',MLM_PLUGIN_NAME);?></p>		
	</div>
	<?php if($error) :?>
	<div class="notibar msgerror">
		<a class="close"></a>
		<p> <strong><?php _e('Please Correct the following Error',MLM_PLUGIN_NAME);?> :</strong> <?php _e($error); ?></p>
	</div>
	<?php endif; ?>
	
<?php




?>

<form name="admin_payout_settings" method="post" action="">
<fieldset id="payout-settings" class="mlm-settings">
                        		<legend><?php _e('Payout Settings', MLM_PLUGIN_NAME); ?></legend>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="form-table">
<tr> <td>

<table border="0" cellpadding="0" cellspacing="0" width="50%" class="form-table">	
<tr> <td colspan="2"> <h3> <?php _e('Network Users Commission',MLM_PLUGIN_NAME);?> <?php echo PAYOUT_1; ?></h3> </td> </tr>		
<?php    for($j=1;$j<=$mlm_no_of_level;$j++) {    
$data  = 'level'.$j.'_ntwk_commission';

$comType = (isset($_POST[$data]) ? $_POST[$data] : (isset($mlm_settings[$data]) ? $mlm_settings[$data] : '0'));
?>		  

<th scope="row" class="admin-settings">
				
			<?php _e('Level '.$j.' Commission',MLM_PLUGIN_NAME);?> :
			</th>
			<td>
			<input type="text" name="<?php echo $data; ?>" id="<?php echo $data; ?>" size="50" value="<?php  _e($comType);?>" > <?php  echo '%'; ?>
				
			</td>
		</tr>


<?php  }  ?>
</table>
</td>

<td>
<table border="0" cellpadding="0" cellspacing="0" width="50%" class="form-table">	
<tr> <td colspan="2"> <h3> <?php _e('General Customers Commission',MLM_PLUGIN_NAME);?> <?php echo PAYOUT_2; ?></h3> </td> </tr>	  
<?php    for($j=1;$j<=$mlm_no_of_level;$j++) {    
$data  = 'level'.$j.'_gnrl_commission';

$comType = (isset($_POST[$data]) ? $_POST[$data] : (isset($mlm_settings[$data]) ? $mlm_settings[$data] : '0'));
?>		  

	<th scope="row" class="admin-settings">

	<?php _e('Level '.$j.' Commission',MLM_PLUGIN_NAME);?> :
	</th>
	<td>
	<input type="text" name="<?php echo $data; ?>" id="<?php echo $data; ?>" size="50" value="<?php  _e($comType);?>" > <?php  echo '%'; ?>
	
	</td>
	</tr>


<?php  }  ?>

</table>
</td>

</table>
</fieldset>
<p class="submit">
	<input type="hidden" name="commission_type" id="commission_type" value="percent">	
	<input type="submit" name="mlm_payout_settings" id="mlm_payout_settings" value="<?php _e('Update Options', MLM_PLUGIN_NAME);?> &raquo;" class='button-primary' onclick="needToConfirm = false;">
	</p>
	</form>

	<script language="JavaScript">
  populateArrays();
</script>
<?php

		
	
	} // end if statement
	else
		 _e($msg);
		
		
} //end mlmPayout function
?>