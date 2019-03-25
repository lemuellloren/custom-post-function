<?php 
function mlm_my_payout_details_page($id=''){


global $table_prefix;
global $wpdb;
global $date_format;
$url = plugins_url();
	
if($id == '')
	$detailArr = my_payout_details_function();
else 
	$detailArr = my_payout_details_function($id);

if(!isset($_REQUEST['pid']) || empty($_REQUEST['pid'])) 
{
$details_page_id = $wpdb->get_var("SELECT id FROM {$table_prefix}posts  WHERE `post_content` LIKE '%mlm-payouts%'	AND `post_type` != 'revision'");
$url = home_url().'?page_id='.$details_page_id;
echo'<script>window.location="'.$url.'";</script>';

}

	if(count($detailArr)>0)
	{

	    $memberId = $detailArr['memberId']; 
		$payoutId = $detailArr['payoutId'];
		$comissionArr = getCommissionByPayoutId($memberId,$payoutId );
		
		$mlm_settings = get_option('wp_mlm_general_settings');
		$comm_ssion=array('','Level','Refferral','Company','Left over','Customer Commission','Customer Commission');			
		?>
		<!--<script src="initiate.js" type="text/javascript"></script>-->
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
					<td scope="row"><?php _e('Name',MLM_PLUGIN_NAME);?></td>
					<td><?php  echo  $detailArr['name'] ?></td>
				  </tr>

				  <tr>
					<td scope="row"><?php _e('Payout ID',MLM_PLUGIN_NAME);?></td>
					<td><?php  echo  $detailArr['payoutId'] ?></td>
				  </tr>
				  <tr>
					<td scope="row"><?php _e('Date',MLM_PLUGIN_NAME);?></td>
					<td><?php  echo  $detailArr['payoutDate'] ?></td>
				  </tr>
				</table>
			</td>
			<td width="40%">
				<table width="100%" border="0" cellspacing="10" cellpadding="1">
				  <tr>
					<td><strong><?php _e('Payout Details',MLM_PLUGIN_NAME);?></strong></td>
				  </tr>
				   <tr>
					<td>
						<table width="100%" border="0" cellspacing="10" cellpadding="1">
							<tr>
								<td colspan="3"><strong><?php _e('Commission',MLM_PLUGIN_NAME);?></strong></td>
							</tr>
							
							<tr>
								<td><?php _e('User Name',MLM_PLUGIN_NAME);?></td>
								<td><?php _e('Commission Type',MLM_PLUGIN_NAME);?></td>
								<td><?php _e('Amount',MLM_PLUGIN_NAME);?></td>
							</tr>
							<?php foreach($comissionArr as $comm ) :
						
						 $check =$wpdb->get_var("select username FROM ".MLM_DELETED_USERS."  WHERE user_id='".$comm['child_ids']."'");
		 
						 if($check!=''){ $name= $check .' {Deleted User}';  }
						 else { $name = getusernamebyId($comm['child_ids']); }
							
							?>
							
							<tr>
								<td><?php  echo  $name;  ?></td>
								<td><?php  echo   $comm_ssion[$comm['comm_type']] ?></td>
								<td><?php  echo   MLM_CURRENCY.' '.$comm['amount'] ?></td>
							</tr>
							
							<?php endforeach; ?>
							
							
						</table>
                        
					</td>
				  </tr>
				     
				</table>
			</td>
		  </tr>
		</table>
		
		
		<table width="100%" border="0" cellspacing="10" cellpadding="1" class="payout-summary">
			<tr>
				<td colspan="2"><strong><?php _e('Payout Summary',MLM_PLUGIN_NAME);?></strong></td>
			</tr>
			<tr>
				<td width="50%"><?php _e('Commission Amount',MLM_PLUGIN_NAME);?></td>
				<td width="50%" class="right"><?php  echo   MLM_CURRENCY.' '.$detailArr['commamount'] ?></td>
			</tr>
            
			<tr>
				<td width="50%"><?php _e('Sub-Total',MLM_PLUGIN_NAME);?></td>
				<td width="50%" class="right"><?php  echo  MLM_CURRENCY.' '.$detailArr['subtotal'] ?></td>
			</tr>
			
			<tr>
				<td width="50%"><strong><?php _e('Net Amount',MLM_PLUGIN_NAME);?><?php if(!empty($cap)) _e($cap); ?></strong>	</td>
				<td width="50%" class="right"><strong><?php  echo  MLM_CURRENCY.' '.$detailArr['netamount'] ?></strong></td>
			</tr>
			<tr>
				<td colspan="2" class="right">
				
				
				
				</td>
			</tr>
			<div class='show-payment-detail' style="display:none;">
			<tr class='show-payment-detail' style="display:none;"><td colspan='2'><?php _e(paymentDeatil($memberId,$payoutId));?></td></tr></div>
		</table>
		
		<script type="text/javascript">
			jQuery(document).ready(function ($){
				$(".view-payment").click( function(){
					$(".show-payment-detail").toggle();
				});
			});

			$(function(){
				$(".button").click(function() {
					var name = $("input#name").val();
					var memberid=$('#memberid').val();
					var payoutid=$('#payoutid').val();
					var dataString = 'name='+ name + '&wint_id=' + memberid + '&pay_id=' + payoutid;

					$.ajax({
						type: "POST",
						url: "<?php echo $url.'/'.MLM_PLUGIN_NAME.'/mlm_html/delete_withdrawal.php'; ?>",
						data: dataString,
						success: function() {
							$('#comment_form').html("<div id='message'></div>");
							$('#message').html("<h3 class='initiatedmsg'>Your Withdrawal Request Initiated.</h3>")
							.hide()
							.fadeIn(1500, function() {
								$('#message').append("<?php __('Thanks for Patience.',MLM_PLUGIN_NAME)?>");
							});
						}
					});
					return false;
				});
			});
		</script>
			
		<?php
	}else{
	
		_e(PAYOUT_DETAILS,MLM_PLUGIN_NAME);
	
	}	
	

	
}

function my_payout_details_function($id='')
{
	if ( is_user_logged_in() && isset($_REQUEST['pid']))
	{
	
		global $table_prefix;
		global $wpdb;
		global $current_user;
    	get_currentuserinfo();
		
if($id == '')		
		$userId = $current_user->ID;
else 
		$userId = $id; 	
		
		
		 $sql = 	"SELECT 
					id, user_id, DATE_FORMAT(`date`,'%d %b %Y') as payoutDate, payout_id, commission_amount,bonus_amount,total_amt
                                        FROM 
					".MLM_PAYOUT." 
                                        WHERE 
					payout_id = '".$_REQUEST['pid']."' AND 
					user_id = '".$userId."'";
		
		 $row = $wpdb->get_row($sql,ARRAY_A);
		 $payoutDetail = array(); 
		 
		 if(!empty($row))
		 {
		 
		 $check =$wpdb->get_var("select username FROM ".MLM_DELETED_USERS."  WHERE user_id='".$userId."'");
		 
		 if($check!=''){ $name= $check .' {Deleted User}';  }
		 else { $name = get_user_meta($userId,'first_name',true).' '.get_user_meta($userId,'last_name',true); }
		 
		 	$payoutDetail['memberId'] = $userId;
			$payoutDetail['name'] =  $name;
			$payoutDetail['payoutId'] = $_REQUEST['pid']; 
			$payoutDetail['payoutDate'] = $row['payoutDate'];
			$payoutDetail['commamount'] = $row['commission_amount'];
			$payoutDetail['subtotal'] = $row['total_amt'] ;
			$payoutDetail['netamount'] = number_format($payoutDetail['subtotal'], 2); 
		}
		 
		return $payoutDetail;	 
		 
	}	else{
	
		return null;
	}

}

function getCommissionByPayoutId($memberId,$payoutId )
{
	global $table_prefix;
	global $wpdb;
	if(isset($memberId) && isset($payoutId))
	{
	
		$sql = "SELECT 
					id, date_notified, parent_id, child_ids, amount, payout_id, comm_type 
				FROM 
					".MLM_COMMISSION." 
				WHERE 
					parent_id = '".$memberId."' AND 
					payout_id = '".$payoutId."' 
				";
				
		$myrows = $wpdb->get_results($sql, ARRAY_A );
			
		return $myrows;
	
	}else
	return null;

}


function paymentDeatil($memberId,$payoutId){
	global $table_prefix;
	global $wpdb;
	global $date_format;

	
	if(isset($memberId) && isset($payoutId))
	{
		$sql = "SELECT *
				FROM 
					".MLM_PAYOUT."
				WHERE 
					user_id = '".$memberId."' AND 
					payout_id = '".$payoutId."' 
				";
				
		$myrows = $wpdb->get_results($sql, ARRAY_A );
			
		$detail=$myrows[0];
		if($detail['payment_mode']=='cheque'){

			_e("<table><tr><th>Withdrawal Date</th><th>Payment Date</th><th>Payment Mode</th><th>Payment Detail</th></tr><tr><td>".$detail['withdrawal_initiated_date']."</td><td>".$detail['payment_processed_date']."</td><td>".$detail['payment_mode']."</td><td>Cheque No:&nbsp;".$detail['cheque_no']."<br/>Cheque Date: &nbsp;".$detail['cheque_date']."<br/>Bank Name:&nbsp;".$detail['bank_name']." <br/></td></tr></table>");
		}
		else
		if($detail['payment_mode']=='bank-transfer')
		{
			_e("<table><tr><th>Withdrawal Date</th><th>Payment Date</th><th>Payment Mode</th><th>Payment Detail</th></tr><tr><td>".$detail['withdrawal_initiated_date']."</td><td>".$detail['payment_processed_date']."</td><td>".$detail['payment_mode']."</td><td>Benificiary:&nbsp;".$detail['beneficiary']."<br/>Account No: &nbsp;".$detail['user_bank_account_no']."<br/>Banktransfer Code: &nbsp;".$detail['banktransfer_code']."<br/>Bank Name:&nbsp;".$detail['user_bank_name']." <br/></td></tr></table>");
		}
		else if($detail['payment_mode']=='cash')
		{
			_e("<table><tr><th>Withdrawal Date</th><th>Payment Date</th><th>Payment Mode</th></tr><tr><td>".$detail['withdrawal_initiated_date']."</td><td>".$detail['payment_processed_date']."</td><td>".$detail['payment_mode']."</td></tr></table>");
		}
		else
		if($detail['payment_mode']=='other')
		{
			_e("<table><tr><th>Withdrawal Date</th><th>Payment Date</th><th>Payment Mode</th><th>Payment Detail</th></tr><tr><td>".$detail['withdrawal_initiated_date']."</td><td>".$detail['payment_processed_date']."</td><td>".$detail['payment_mode']."</td><td>".$detail['other_comments']."</td></tr></table>");
		}
		
			
	
	}
	
}

?>