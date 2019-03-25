<?php 
function adminMLMPayout()
{
	$msg = '';
	$displayData = '';
	
	if(isset($_REQUEST['pay_cycle']))
	{	
		$payoutArr = payoutRun(); 
		 
	}
	
	if(isset($_REQUEST['pay_actual_amount']))
	{	
		$msg = wpmlm_run_pay_cycle();		
	}
?>
<div class='wrap'>
	<div id="icon-users" class="icon32"></div><h1><?php _e('Beau Fairy Membership Payout',MLM_PLUGIN_NAME);?></h1><br />

	<div class="notibar msginfo">
		<a class="close"></a>
		<p>	<?php _e('The commissions would not show up in member account till the time the Payout Routine is not run. This script can be run manually once every week, every fortnight or every month depending on the payout cycle of your network.',MLM_PLUGIN_NAME);?>
		
		</p>
		
		<p><?php _e('Alternately, you can also schedule (cron job) the following URL as per the frequency of the payout cycle.',MLM_PLUGIN_NAME);?></p>
		<p><?php  echo   MLM_URL ?>/cronjobs/paycycle.php</p>
		
		<?php  echo   payoutLicMsg() ?>
		
	</div>
	<div style="font-size:18px; padding:10px; color:#0000CC; "><?php if(!empty($payoutArr['directRun'])) _e($payoutArr['directRun'])?><?php if(!empty($msg)) _e($msg) ?></div>	


		
		<form name="frm" method="post" action="">

        		<div class="payout-run">
				<input class="button-primary" type="submit" name="pay_cycle" value="<?php _e('Run Payout Routine',MLM_PLUGIN_NAME);?>" id="pay_cycle" /> 
			</div>
			<!-- Dislay data -->
			<?php if(!empty($payoutArr['displayData']) && $payoutArr['displayData'] != '')
			{?>
				<table width="100%" border="1" cellspacing="0" cellpadding="0" align="left" style="margin:20px 0 20px 0">
<tr>
	<th scope="row"><?php _e('S.No',MLM_PLUGIN_NAME);?></th>
		        <th scope="row"><?php _e('Username',MLM_PLUGIN_NAME);?></th>
			<th scope="row"><?php _e('Name',MLM_PLUGIN_NAME);?></th>
			<th scope="row"><?php _e('Commission',MLM_PLUGIN_NAME);?></th>
			<th scope="row"><?php _e('Net Amount',MLM_PLUGIN_NAME);?></th>
		  </tr>
		  
		<?php 
		if($payoutArr['displayData'] != 'None'){
				$i = 1;
			foreach( $payoutArr['displayData'] as $row)   
		{	
			?>
			  <tr>
				<td align="center"><?php  echo   $i; ?></td>
				<td align="center"><?php  echo   $row['username']; ?></td>
				<td align="center"><?php  echo   $row['first_name']." ".$row['last_name']; ?></td>
				<td align="center"><?php  echo   number_format($row['commission'], 2, '.','');?></td>
				<td align="center"><?php  echo   number_format($row['net_amount'], 2, '.','');?></td>
			  </tr>
		   	  <?php $i++; 
		    }
		    }else{
			?>
			<tr>
				<td colspan="8" align="center"><?php _e('There is no any eligible member Found in this Payout.',MLM_PLUGIN_NAME);?> </td>
			</tr>
			<?php 
			}
		?>
	   </table><br>
	   <div class="payout-run" style="float:right;">
				<input class="button-primary" type="submit" name="pay_actual_amount" value="<?php _e('All is Well. Commit.',MLM_PLUGIN_NAME);?>" id="pay_actual_amount" /> 
			</div>
			<div class="payout-run" style="float:right;">
				<a class="button-primary" href="?page=mlm-payout" ><?php _e('Something wrong. Cancel.',MLM_PLUGIN_NAME);?></a> 
			</div>
				<?php }
			
			?>
			<!-- End display data -->
			<div style="clear:both;"></div>	
	
		</form>
				
	
</div>
<?php 
}
?>
