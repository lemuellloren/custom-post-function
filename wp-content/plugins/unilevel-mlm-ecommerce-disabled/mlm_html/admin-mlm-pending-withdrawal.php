<?php 
function mlm_withdrawal_request() {
global $table_prefix;
global $wpdb;
global $date_format;
$url = plugins_url();

?>
<div class='wrap'>
	<div id="icon-users" class="icon32"></div><h1><?php _e('Pending User Withdrawals',MLM_PLUGIN_NAME);?></h1><br />

	<div class="notibar msginfo" style="margin:10px;">
		<a class="close"></a>
		<p><?php _e('Given below is the list of all pending User Withdrawals.',MLM_PLUGIN_NAME);?></p>
        <p><strong><?php _e('Process',MLM_PLUGIN_NAME);?></strong> - <?php _e("Input the payment details for the withdrawal. These payment details would also show up on the User's Payout Details Page.",MLM_PLUGIN_NAME);?> </p>
        <p><strong><?php _e('Delete',MLM_PLUGIN_NAME);?></strong> - <?php _e('This would mark the withdrawal as deleted. The user would need to initiate a fresh withdrawal for this payout from his interface.',MLM_PLUGIN_NAME);?> </p>
	</div>	

</div>
<?php
		
		 $sql = "SELECT  id,user_id,(amount+withdrawal_fee+witholding_tax)as amount,DATE_FORMAT(`withdrawal_initiated_date`,'%d %b %Y')withdrawal_initiated_date, withdrawal_initiated_comment, withdrawal_mode ,payment_processed_date
		 FROM ".MLM_WITHDRAWAL." WHERE  withdrawal_initiated= 1 AND `payment_processed`= 0";
		 
		 
		$rs = $wpdb->get_results($sql,ARRAY_A);
		
		$listArr[-1]['name'] = __('Member Username',MLM_PLUGIN_NAME);
		$listArr[-1]['email'] = __('Member Email', MLM_PLUGIN_NAME);
		$listArr[-1]['widate'] = __('Withdrawal Initiate Date', MLM_PLUGIN_NAME);
		$listArr[-1]['wicomment'] = __('Withdrawal Comment', MLM_PLUGIN_NAME);
		$listArr[-1]['netamount'] = __('Amount', MLM_PLUGIN_NAME);
		$listArr[-1]['withdrawalMode'] = __('Payment Mode', MLM_PLUGIN_NAME);
		
	
	$i = 0;
		 $payoutDetail=array();
		 $html_output="<table border='1' cellspacing='0' cellpadding='5' width='99%' style='border-color:#dadada;'>";
		 $html_output.="<tr><th>".__('Member Username',MLM_PLUGIN_NAME)."</th><th>".__('Member Email',MLM_PLUGIN_NAME)."</th><th>".__('Withdrawal Initiate Date',MLM_PLUGIN_NAME)
		 ."</th><th>".__('Withdrawal Comment',MLM_PLUGIN_NAME)."</th><th>".__('Amount',MLM_PLUGIN_NAME)."</th><th>".__('Payment Mode ',MLM_PLUGIN_NAME)."</th><th>".__('Action',MLM_PLUGIN_NAME)."</th></tr>";
		 if(!empty($rs))
		 {
		 	foreach( $rs as $row){
			
			$sql1 = "SELECT ".MLM_USERS.".username AS uname , {$table_prefix}users.user_email AS uemail FROM {$table_prefix}users,".MLM_USERS." 
			WHERE ".MLM_USERS.".username = {$table_prefix}users.user_login AND ".MLM_USERS.".user_id = '".$row['user_id']."'"; 
				
			$row1 = $wpdb->get_row($sql1,ARRAY_A);		
			$payoutDetail['id'] = $row['id'];
			$payoutDetail['memberId'] = $row['user_id'];
			$payoutDetail['name'] = $row1['uname']; 
			$payoutDetail['email'] = $row1['uemail']; 			
			$payoutDetail['withdrawalMode'] = $row['withdrawal_mode'];
			$withdrawal_date = date_create($row['withdrawal_initiated_date']);
			$payoutDetail['widate'] = date_format($withdrawal_date,$date_format);
			$payoutDetail['wicomment'] = $row['withdrawal_initiated_comment'];
			$payoutDetail['netamount'] = round(ceil($row['amount'] * 100) / 100,2);
			
			
			
			
            $html_output.="<tr><td>".$payoutDetail['name']."</td><td>".$payoutDetail['email']."</td><td>".$payoutDetail['widate']."</td><td>".$payoutDetail['wicomment']
			."</td><td>".$payoutDetail['netamount']."</td><td>".$payoutDetail['withdrawalMode']."</td>
			<td><form name='withdrawal_process' method='POST' action='".admin_url( 'admin.php' )."?page=admin-mlm-withdrawal-process' id='withdrawal_process'>
			<input type='hidden' name='id' value='".$payoutDetail['id']."'>
			<input type='hidden' name='member_name' value='".$payoutDetail['name']."'>
			<input type='hidden' name='member_id' value='".$payoutDetail['memberId']."'>
			<input type='hidden' name='withdrawalMode' value='".$payoutDetail['withdrawalMode']."'>
			<input type='hidden' name='member_email' value='".$payoutDetail['email']."'>
			<input type='hidden' name='withdrawal_amount' value='".$payoutDetail['netamount']."'>
			<input type='submit' value='".__('Process',MLM_PLUGIN_NAME)."' id='process' name='process-amount'>
			</form>&nbsp;|&nbsp;<a class='ajax-link' id='".$payoutDetail['memberId']."$".$payoutDetail['id']."' href='javascript:void(0);'>".__('Delete',MLM_PLUGIN_NAME)."</a></td>";
		       
		    $listArr[$i]['name'] = $payoutDetail['name'];
			$listArr[$i]['email'] = $payoutDetail['email'];
			$listArr[$i]['widate'] = $payoutDetail['widate'];
			$listArr[$i]['wicomment'] = $payoutDetail['wicomment'];
			$listArr[$i]['netamount'] = $payoutDetail['netamount'];
			$listArr[$i]['withdrawalMode'] = $payoutDetail['withdrawalMode'];
			$i++;
			
		 }
		 $html_output.="</table>";
		 _e($html_output);
		 $value = base64_encode(serialize($listArr));	
    //$value = serialize($listArr);
    ?>
    <form method="post" action="<?php echo plugins_url().'/'.MLM_PLUGIN_NAME.'/export.php'; ?>" >
        <input type="hidden" name ="listarray" value='<?php echo $value ?>' />
        <input type="hidden" name ="filename" value='pending-withdrawal-list-report' />
        <input type="submit" name="export_csv" value="<?php _e('Export to CSV',MLM_PLUGIN_NAME); ?>" class="button-primary" style="margin-top:20px;"/></form>
    <?php
		
		} else { _e("Hooray! Nothing in the pipeline to be processed.",MLM_PLUGIN_NAME); 		}
		?>
		
	<script type="text/javascript">
			jQuery(function(){
			jQuery(".ajax-link").click( function() {
			var b=jQuery(this).parent().parent();
			var id = jQuery(this).attr('id');
			var ids = id.split("$");
			var dataString = 'wdel_id='+ ids[0]+'&w_id='+ids[1];
			
			if(confirm("Confirm Delete withdrawal request?")){
			jQuery.ajax({ 
			type: "POST", 
			url: "<?php echo $url.'/'.MLM_PLUGIN_NAME.'/mlm_html/delete_withdrawal.php'; ?>",
            data: dataString,
			cache: true,
			success: function(e)
			{	
				window.location.reload(true);
			}
			});
				return false;
			}
			});
			});
	</script>
<?php } ?>