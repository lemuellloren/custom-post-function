<?php 
function mlm_my_payout_page($id='')
{

if($id=='')
{
$detailsArr =  my_payout_function();
}
else
{ 
$detailsArr =  my_payout_function($id);

}
//_e("<pre>");print_r($detailsArr); exit; 
$page_id = get_post_id('mlm_my_payout_details_page');

if(count($detailsArr)>0){
$mlm_settings = get_option('wp_mlm_general_settings');

	?>
	<table width="100%" border="0" cellspacing="10" cellpadding="1" id="payout-page">
		<tr>
			<td><?PHP _e('Date',MLM_PLUGIN_NAME);?></td>
			<td><?PHP _e('Amount',MLM_PLUGIN_NAME);?></td>
			<td><?PHP _e('Action',MLM_PLUGIN_NAME);?></td>
		</tr>
		<?php foreach($detailsArr as $row) :  
			$amount= $row->commission_amount  + $row->bonus_amount ; 
		?>
		<tr>
			<td><?php  echo   $row->payoutDate ?></td>
			<td><?php  echo   MLM_CURRENCY.' '.$amount ?></td>
			<?php if($id == ''){?>
			<td><a href="<?php  echo  get_post_id_or_postname_for_payout('mlm_my_payout_details_page', $row->payout_id)?>" style="text-decoration:none;"><?php _e('View',MLM_PLUGIN_NAME);?></a></td>
			<?php }
				else{	?>
			<td><a href="?page=mlm-user-account&ac=payout-details&pid=<?php  echo  $row->payout_id?>" style="text-decoration:none;"><?php _e('View',MLM_PLUGIN_NAME);?></a></td>				
				<?php
			}
			?>
			
		</tr>
		
		<?php endforeach; ?>
		
	</table>
	<?php 
	}else{

	?>
	<div class="no-payout"><?php _e('You have not earned any commisssions yet.',MLM_PLUGIN_NAME);?> </div>
	
	<?php 
	}
	

 
}

?>
