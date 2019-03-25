<?php 
function wpmlm_run_pay_cycle()
{
	
	$returnVar  = wpmlm_run_PayCycleFunctions();	
	return $returnVar; 
	
}

function wpmlm_run_PayCycleFunctions()
{
	

	$payoutMasterId = createPayoutMaster(); 
	
	global $table_prefix, $wpdb; 
	
	
	$query = $wpdb->get_results("SELECT user_id FROM ".MLM_USERS."  WHERE payment_status= '1' AND banned = '0' ORDER BY id");
	$num = $wpdb->num_rows;
	
	if($num)
	{
	foreach($query as $row)
		{
	$sql=  "SELECT SUM(amount) AS commission FROM ".MLM_COMMISSION."  WHERE parent_id='".$row->user_id."' AND payout_id = 0 GROUP BY parent_id";
	
	$rs = $wpdb->get_var($sql);	
			
	if($rs){ $commissionAmt = $rs;}
	  else {  $commissionAmt=0;   }
	
	     $bonusAmt=0; 
    
	
	         $userId = $row->user_id; 
		 $capLimitAmt = 0; 
		 $totalAmt = $commissionAmt + $bonusAmt;
			
			if($totalAmt>0) {
			$total = $totalAmt; 
			
			if($u=get_option('payout_mail', true)==1)
			{
			PayoutGeneratedMail($userId,$total,$payoutMasterId);
			}
	       /***********************************************************
			INSERT INTO PAYOUT TABLE
			***********************************************************/ 
			$sql_payout = "INSERT INTO 
							".MLM_PAYOUT."
							(
								user_id, date, payout_id, commission_amount, 
								bonus_amount,total_amt
							) 
							VALUES 					
							(
								'".$userId."', '".date('Y-m-d H:i:s')."', '".$payoutMasterId."', '".$commissionAmt."', 
								'".$bonusAmt."','".$total."'
							)";  
							
							
		   					
				
			$closing_bal = $wpdb->get_var("select closing_bal from ".MLM_TRANSACTION."  where id= (select max(id) from ".MLM_TRANSACTION." where user_id ='".$userId."')");	
			$opening_bal = $closing_bal;
            $closing_bal = $opening_bal+$totalAmt;
			$sql_transaction = "INSERT INTO ".MLM_TRANSACTION." set	
								user_id ='".$userId."', 
								cr_id = '".$payoutMasterId."',
                                                                opening_bal = '".$opening_bal."',
								cr_amount = '".$totalAmt."', 
                                                                closing_bal = '".$closing_bal."',    
								transaction_date='".date('Y-m-d H:i:s')."',
								transaction_type='1',
                                                                comment = 'Amount Credited by payout ID {$payoutMasterId}'";
			$wpdb->query($sql_transaction);
                                                        
            $rs_payout = $wpdb->query($sql_payout);
			$insert_id = $wpdb->insert_id;
			
			/***********************************************************
			Update Commission table Payout Id
			***********************************************************/ 
			if(isset($insert_id) && $insert_id >0)
			{
				$sql_comm = "UPDATE ".MLM_COMMISSION." 
								SET 
									payout_id= '".$payoutMasterId."'
								WHERE 
									parent_id = '".$row->user_id."' AND 
									payout_id = '0'
								";
				$rs_comm = $wpdb->query($sql_comm); 					
			
			}
	 
	   }
	
	
	
	}
	}
	
	return "Payout Run Successfully";
	
	
	
	
	
	$sql=  "SELECT 
				id, date_notified, parent_id, child_ids, amount, SUM(amount) AS commission  
			FROM 
				".MLM_COMMISSION." 
			WHERE 
				payout_id = 0
			GROUP BY 
				parent_id	
			";
	
	//_e($sql); exit; 
	
		
	$rs = $wpdb->get_results($sql); 
	if($wpdb->num_rows > 0)
	{
		foreach($rs as $row)
		{
			
			$userId = $row->parent_id; 
			$commissionAmt = $row->commission;
			$bonusAmt=0;
			
			$capLimitAmt = 0; 
			$totalAmt = $commissionAmt + $bonusAmt;
			
			
			$total =$totalAmt; 
			
			
				
					
			/***********************************************************
			INSERT INTO PAYOUT TABLE
			***********************************************************/ 
			$sql_payout = "INSERT INTO 
							".MLM_PAYOUT."
							(
								user_id, date, payout_id, commission_amount, 
								bonus_amount,total_amt
							) 
							VALUES 					
							(
								'".$userId."', '".date('Y-m-d H:i:s')."', '".$payoutMasterId."', '".$commissionAmt."', 
								'".$bonusAmt."','".$total."'
							)";  
							
							
		   					
				
			$closing_bal = $wpdb->get_var("select closing_bal from ".MLM_TRANSACTION."  where id= (select max(id) from ".MLM_TRANSACTION." where user_id ='".$userId."')");	
			$opening_bal = $closing_bal;
                        $closing_bal = $opening_bal+$totalAmt;
			$sql_transaction = "INSERT INTO ".MLM_TRANSACTION." set	
								user_id ='".$userId."', 
								cr_id = '".$payoutMasterId."',
                                                                opening_bal = '".$opening_bal."',
								cr_amount = '".$totalAmt."', 
                                                                closing_bal = '".$closing_bal."',    
								transaction_date='".date('Y-m-d H:i:s')."',
								transaction_type='1',
                                                                comment = 'Amount Credited by payout ID {$payoutMasterId}'";
			$wpdb->query($sql_transaction);
                                                        
            $rs_payout = $wpdb->query($sql_payout);
			$insert_id = $wpdb->insert_id;
			
			/***********************************************************
			Update Commission table Payout Id
			***********************************************************/ 
			if(isset($insert_id) && $insert_id >0)
			{
				$sql_comm = "UPDATE ".MLM_COMMISSION." 
								SET 
									payout_id= '".$payoutMasterId."'
								WHERE 
									parent_id = '".$row->parent_id."' AND 
									payout_id = '0'
								";
				$rs_comm = $wpdb->query($sql_comm); 					
			
			}

						
		}	
	
        }
	
	return "Payout Run Successfully";
	
}

function createPayoutMaster()
{
	global $table_prefix,$wpdb; 
	
	//$mlm_payout = get_option('wp_mlm_payout_settings');
	//$capLimitAmt = $mlm_payout['cap_limit_amount'];
	$capLimitAmt = 0;
	$sql = "INSERT INTO ".MLM_PAYOUT_MASTER." (date,cap_limit) VALUES ('".date('Y-m-d H:i:s')."','$capLimitAmt')"; 
	$res = $wpdb->query($sql);
	$pay_master_id = $wpdb->insert_id;
	
	return $pay_master_id; 
}



?>