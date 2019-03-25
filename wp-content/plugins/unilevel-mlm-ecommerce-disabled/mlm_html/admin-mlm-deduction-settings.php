<?php
function mlmDeduction()
{

	//get database table prefix
	$table_prefix = mlm_core_get_table_prefix();
	
	$error = '';
	$chk = 'error';
	
	//most outer if condition
	if(isset($_POST['mlm_withdrawal_settings']))
	{

		$withdwl_mthd =  count(array_filter($_POST['withdwl_mthd']));
	
		$withdwl_type = $_POST['withdwl_type'];
		$withdwl_amt = count(array_filter($_POST['withdwl_amt']));
		
		
		if ( $withdwl_mthd==0 ) 
			$error .= "\n Please Specify Name of Withdrawal Method.";
		
		/*if ( $withdwl_amt==0 ) 
			$error .= "\n Please Specify Amount.";*/

		if ( count(array_filter($_POST['withdwl_type']))==0 ) 
			$error .= "\n Please Specify Type.";
			


		
		//if any error occoured
		if(!empty($error))
			$error = nl2br($error);
		else
		{
			$chk = '';
			
			update_option('wp_mlm_withdrawal_method_settings', $_POST);
			$url = get_bloginfo('url')."/wp-admin/admin.php?page=admin-settings&tab=deduction";
			_e("<script>window.location='$url'</script>");
			$msg = _e("<span style='color:green;'>Your Withdrawal Settings has been successfully updated.</span>",MLM_PLUGIN_NAME);
		}
	}// end outer if condition
	else if(isset($_POST['mlm_other_method_settings']))
	{

		$othr_mthd =  count(array_filter($_POST['othr_mthd']));
	
		$othr_type = $_POST['othr_type'];
		$othr_amt = count(array_filter($_POST['othr_amt']));

		
		if ( $othr_mthd==0 ) 
			$error .= "\n Please Specify Name of Other Method.";
		
			
		/*if ( $othr_amt==0 ) 
			$error .= "\n Please Specify Amount.";*/

		//if ( checkInputField($othr_type) ) 
		if ( count(array_filter($_POST['othr_type']))==0 ) 
			$error .= "\n Please Specify Type.";

 

		
		//if any error occoured
		if(!empty($error))
			$error = nl2br($error);
		else
		{
			$chk = '';
			
			update_option('wp_mlm_other_method_settings', $_POST);
			$url = get_bloginfo('url')."/wp-admin/admin.php?page=admin-settings&tab=deduction";
			_e("<script>window.location='$url'</script>");
			$msg = _e("<span style='color:green;'>Your Other Method Settings has been successfully updated.</span>",MLM_PLUGIN_NAME);
		}
	}// end outer if condition
	
	
	if($chk!='')
	{
		$mlm_withdrawal_settings = get_option('wp_mlm_withdrawal_method_settings');
		$mlm_other_settings = get_option('wp_mlm_other_method_settings');
?>
<div class='wrap1'>
	<h2><?php _e('Deductions Settings',MLM_PLUGIN_NAME);?>  </h2>
	<div class="notibar msginfo">
		<a class="close"></a>
		<p><?php _e('These settings will define the amounts that would be deducted from the withdrawals made by members in their Member\'s Area.',MLM_PLUGIN_NAME);?></p>
<p><strong><?php _e('Note ',MLM_PLUGIN_NAME);?> : </strong> 
<?php _e('All % amounts would always apply the specified % to the actual amount withdrawn.',MLM_PLUGIN_NAME);?></p>
</div>
	
	<?php if($error) :?>
	<div class="notibar msgerror">
		<a class="close"></a>
		<p> <strong><?php _e('Please Correct the following Error',MLM_PLUGIN_NAME);?> :</strong> <?php _e($error); ?></p>
	</div>
	<?php endif; ?>
	<?php if(!empty($msg)) :?>
	<div class="notibar msgerror">
		<a class="close"></a>
		<p><?php  _e($msg); ?></p>
	</div>
	<?php endif; ?>
	

<?php
	if(empty($mlm_withdrawal_settings))
	{
?>
<form name="admin_withdrawalsettings" method="post" action="">
<fieldset id="withdrawal-method-settings" class="mlm-settings">
                        		<legend><?php _e('Withdrawal Method Settings', MLM_PLUGIN_NAME); ?><?php echo DEDUCTION_1 ?></legend>
	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="form-table">

		<tr>

			<td>
				<INPUT type="button" value="<?php _e('Add Row',MLM_PLUGIN_NAME)?>" onclick="addRow('dataTable')" class='button-primary' />
    			<INPUT type="button" value="<?php _e('Delete Row',MLM_PLUGIN_NAME)?>" onclick="deleteRow('dataTable')" class='button-primary' />
				<div class="toggle-visibility" id="admin-mlm-bonus-slab"><?php _e('Add or remove bonus slab.',MLM_PLUGIN_NAME);?></div>
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
	</table>
	<TABLE id="dataTableheading" cellspacing="5" cellpadding="5"  border="0" width="95%">
		<TR>
			<TD align="left" width="10%"><strong><?php _e('Select',MLM_PLUGIN_NAME);?></strong></TD>
			<TD align="left" width="35%"> <strong><?php _e('Withdrawal Method',MLM_PLUGIN_NAME);?></strong></TD>
			<TD align="left" width="20%"> <strong><?php _e('Amount',MLM_PLUGIN_NAME);?></strong></TD>
			<TD align="left" width="20%"> <strong><?php _e('Min. </br>Amount',MLM_PLUGIN_NAME);?></strong></TD>
			<TD align="left" width="20%"><strong><?php _e('Type',MLM_PLUGIN_NAME);?></strong></TD>
			
			
		</TR>
	</TABLE>
	<br\>
	
	<TABLE id="dataTable"  cellspacing="5" cellpadding="5" border="0" width="92%">
		<TR>
			<TD align="left" width="10%"><INPUT type="checkbox" name="chk[]"/></TD>
			<TD align="left" width="40%"> <INPUT type="text" name="withdwl_mthd[]" size="15" /> </TD>
			<TD align="left" width="20%"> <INPUT type="text" name="withdwl_amt[]" size="5" /> </TD>
			<TD align="left" width="20%"> <INPUT type="text" name="min_amount[]" size="5" /> </TD>
			<TD align="left" width="20%"> 
			<select name="withdwl_type[]" id="withdwl_type">
				<option value=""><?php _e('Select Type',MLM_PLUGIN_NAME);?></option>
				<option value="fixed" <?php  echo   (isset($_POST['withdwl_type']) && $_POST['withdwl_type']=='fixed') ? 'selected':''?>><?php _e('Fixed',MLM_PLUGIN_NAME);?></option>
				<option value="percent" <?php  echo   (isset($_POST['withdwl_type']) && $_POST['withdwl_type']=='percent') ? 'selected':''?>><?php _e('Percentage',MLM_PLUGIN_NAME);?></option>
				</select> </TD>

		</TR>
	</TABLE>
	
	<table border="0" width="100%">	
		<tr>
			<td>
		<p class="submit">
	<input type="submit" name="mlm_withdrawal_settings" id="mlm_withdrawal_settings" value="<?php _e('Update Options', MLM_PLUGIN_NAME);?> &raquo;" class='button-primary' onclick="needToConfirm = false;">
	</p>
			</td>
		<tr>
	</table>
	</fieldset>
</form>

<script language="JavaScript">
  populateArrays();
</script>
<?php
		}
		else if(!empty($mlm_withdrawal_settings))
		{
			?>
		
			<form name="admin_withdrawal_settings" method="post" action="">
			<fieldset id="withdrawal-method-settings" class="mlm-settings">
                        		<legend><?php _e('Withdrawal Method Settings', MLM_PLUGIN_NAME); ?><?php echo DEDUCTION_1 ?></legend>
	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="form-table">
		<tr>
		<tr>
			<td>
				<INPUT type="button" value="<?php _e('Add Row',MLM_PLUGIN_NAME)?>" onclick="addRow('dataTable')" class='button-primary'/>
    			<INPUT type="button" value="<?php _e('Delete Row',MLM_PLUGIN_NAME)?>" onclick="deleteRow('dataTable')" class='button-primary'/>
				<div class="toggle-visibility" id="admin-mlm-bonus-slab"><?php _e('Add or remove bonus slab.',MLM_PLUGIN_NAME);?></div>
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
	</table>
	<TABLE id="dataTableheading" cellspacing="5" cellpadding="5"  border="0" width="95%" >
		<TR>
			<TD align="left" width="10%"><strong><?php _e('Select',MLM_PLUGIN_NAME);?></strong></TD>
			<TD align="left" width="35%"> <strong><?php _e('Withdrawal Method',MLM_PLUGIN_NAME);?></strong></TD>
			<TD align="left" width="20%"> <strong><?php _e('Amount',MLM_PLUGIN_NAME);?></strong></TD>
			<TD align="left" width="20%"> <strong><?php _e('Min. </br> Amount',MLM_PLUGIN_NAME);?></strong></TD>
			<TD align="left" width="20%"><strong><?php _e('Type',MLM_PLUGIN_NAME);?></strong></TD>
			
			
		</TR>
		
	</TABLE>
	<br\>
			<TABLE id="dataTable"   cellspacing="5" cellpadding="5" border="0" width="92%">
		<?php
			$i = 0;
			while( $i<count($mlm_withdrawal_settings['withdwl_mthd']) )
			{
		?>
        	<TR>
           		<TD  align="left" width="10%"><INPUT type="checkbox" name="chk[]"/></TD>
				<TD  align="left" width="40%"> <INPUT type="text" name="withdwl_mthd[]" size="15" value="<?php  echo   $mlm_withdrawal_settings['withdwl_mthd'][$i]?>"/> </TD>
				
				<TD> <INPUT type="text" name="withdwl_amt[]" size="5" value="<?php  echo   $mlm_withdrawal_settings['withdwl_amt'][$i]?>"/> </TD>
				<TD> <INPUT type="text" name="min_amount[]" size="5" value="<?php  echo   $mlm_withdrawal_settings['min_amount'][$i]?>"/> </TD>
				<TD align="center" width="25%"> 
			<select name="withdwl_type[]" id="withdwl_type">
				<option value=""><?php _e('Select Type',MLM_PLUGIN_NAME);?></option>
				<option value="fixed" <?php  echo   $mlm_withdrawal_settings['withdwl_type'][$i]=='fixed' ? 'selected':''?>><?php _e('Fixed',MLM_PLUGIN_NAME);?></option>
				<option value="percent" <?php  echo   $mlm_withdrawal_settings['withdwl_type'][$i]=='percent' ? 'selected':''?>><?php _e('Percentage',MLM_PLUGIN_NAME);?></option>
				</select> </TD>
				
				
				
				
        	</TR>    	
		<?php
				$i++;
			}
		?>
		</TABLE>
	</fieldset>
	<p class="submit">
	<input type="submit" name="mlm_withdrawal_settings" id="mlm_withdrawal_settings" value="<?php _e('Update Options', MLM_PLUGIN_NAME);?> &raquo;" class='button-primary' onclick="needToConfirm = false;">
	</p>
</form>

<script language="JavaScript">
  populateArrays();
</script>
			<?php
		}
	?>
<?php
	if(empty($mlm_other_settings))
	{
?>
<form name="admin_other_settings" method="post" action="">
<fieldset id="other-deductions-settings" class="mlm-settings">
                        		<legend><?php _e('Other Deductions Settings', MLM_PLUGIN_NAME); ?><?php echo DEDUCTION_2 ?></legend>
	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="form-table">	
		
		<tr>

			<td>
				<INPUT type="button" value="<?php _e('Add Row',MLM_PLUGIN_NAME)?>" onclick="addRow('dataWithDrawalTable')" class='button-primary' />
    			<INPUT type="button" value="<?php _e('Delete Row',MLM_PLUGIN_NAME)?>" onclick="deleteRow('dataWithDrawalTable')" class='button-primary' />
				<div class="toggle-visibility" id="admin-mlm-bonus-slab"><?php _e('Add or remove bonus slab.',MLM_PLUGIN_NAME);?></div>
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
	</table>
	<TABLE id="dataTableheading" cellspacing="5" cellpadding="5"  border="0" width="99%">
		<TR>
			<TD align="left" width="10%"><strong><?php _e('Select',MLM_PLUGIN_NAME);?></strong></TD>
			<TD align="left" width="40%"> <strong><?php _e('Name of Deduction',MLM_PLUGIN_NAME);?></strong></TD>

			<TD align="left" width="25%"> <strong><?php _e('Amount',MLM_PLUGIN_NAME);?></strong></TD>
			<TD align="left" width="25%"><strong><?php _e('Type',MLM_PLUGIN_NAME);?></strong></TD>
		</TR>
	</TABLE>
	<br\>
	<TABLE id="dataWithDrawalTable"  cellspacing="5" cellpadding="5" border="0" width="89%">
		<TR>
			<TD align="left" width="10%"><INPUT type="checkbox" name="chk[]"/></TD>
			<TD align="left" width="40%"> <INPUT type="text" name="othr_mthd[]" size="15" /> </TD>

			<TD align="center" width="25%"> <INPUT type="text" name="othr_amt[]" size="10" /> </TD>
			<TD align="center" width="25%"> 
			<select name="othr_type[]" id="othr_type">
				<option value=""><?php _e('Select Type',MLM_PLUGIN_NAME);?></option>
				<option value="fixed" <?php  echo   (isset($_POST['othr_type']) && $_POST['othr_type']=='fixed') ? 'selected':''?>><?php _e('Fixed',MLM_PLUGIN_NAME);?></option>
				<option value="percent" <?php  echo   (isset($_POST['othr_type']) && $_POST['othr_type']=='fixed') ? 'selected':''?>><?php _e('Percentage',MLM_PLUGIN_NAME);?></option>
				</select> </TD>
		</TR>
	</TABLE>
	
	<table border="0" width="100%">	
		<tr>
			<td>
		<p class="submit">
	<input type="submit" name="mlm_other_method_settings" id="mlm_other_method_settings" value="<?php _e('Update Options', MLM_PLUGIN_NAME);?> &raquo;" class='button-primary' onclick="needToConfirm = false;">
	</p>
			</td>
		<tr>
	</table>
	</fieldset>
</form>
</div>
<script language="JavaScript">
  populateArrays();
</script>
<?php
		}
		else if(!empty($mlm_other_settings))
		{
			?>
		
			<form name="admin_other_settings" method="post" action="">
			<fieldset id="other-deductions-settings" class="mlm-settings">
                        		<legend><?php _e('Other Deductions Settings', MLM_PLUGIN_NAME); ?><?php echo DEDUCTION_2 ?></legend>
	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="form-table">
	<tr>
			<td>
				<INPUT type="button" value="<?php _e('Add Row',MLM_PLUGIN_NAME)?>" onclick="addRow('dataWithDrawalTable')" class='button-primary'/>
    			<INPUT type="button" value="<?php _e('Delete Row',MLM_PLUGIN_NAME)?>" onclick="deleteRow('dataWithDrawalTable')" class='button-primary'/>
				<div class="toggle-visibility" id="admin-mlm-bonus-slab"><?php _e('Add or remove bonus slab.',MLM_PLUGIN_NAME);?></div>
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
	</table>
	<TABLE id="dataTableheading" cellspacing="5" cellpadding="5"  border="0" width="99%" >
		<TR>
			<TD align="left" width="10%"><strong><?php _e('Select',MLM_PLUGIN_NAME);?></strong></TD>
			<TD align="left" width="40%"> <strong><?php _e('Name of Deduction',MLM_PLUGIN_NAME);?></strong></TD>

			<TD align="left" width="25%"> <strong><?php _e('Amount',MLM_PLUGIN_NAME);?></strong></TD>
			<TD align="left" width="25%"><strong><?php _e('Type',MLM_PLUGIN_NAME);?></strong></TD>
		</TR>
	</TABLE>
	<br\>
		<TABLE id="dataWithDrawalTable"   cellspacing="5" cellpadding="5" border="0" width="89%">
		<?php
			$i = 0;
			while( $i<count($mlm_other_settings['othr_mthd']) )
			{
		?>
        	<TR>
           		<TD  align="left" width="10%"><INPUT type="checkbox" name="chk[]"/></TD>
				<TD  align="left" width="40%"> <INPUT type="text" name="othr_mthd[]" size="15" value="<?php  echo   $mlm_other_settings['othr_mthd'][$i]?>"/> </TD>
				<TD align="left" width="25%"> 
			 <INPUT type="text" name="othr_amt[]" size="10" value="<?php  echo   $mlm_other_settings['othr_amt'][$i]?>"/> </TD>
				<TD align="center" width="25%"> 
			<select name="othr_type[]" id="othr_type">
				<option value=""><?php _e('Select Type',MLM_PLUGIN_NAME);?></option>
				<option value="fixed" <?php  echo   $mlm_other_settings['othr_type'][$i]=='fixed' ? 'selected':''?>><?php _e('Fixed',MLM_PLUGIN_NAME);?></option>
				<option value="percent" <?php  echo   $mlm_other_settings['othr_type'][$i]=='percent' ? 'selected':''?>><?php _e('Percentage',MLM_PLUGIN_NAME);?></option>
				</select> </TD>
				
				
        	</TR>    	
		<?php
				$i++;
			}
		?>
		</TABLE>
		</fieldset>
	
	<p class="submit">
	<input type="submit" name="mlm_other_method_settings" id="mlm_other_method_settings" value="<?php _e('Update Options', MLM_PLUGIN_NAME);?> &raquo;" class='button-primary' onclick="needToConfirm = false;">
	</p>
</form>
</div>
<script language="JavaScript">
  populateArrays();
</script>
<?php 
} 
	
	} // end if statement
	else
		 _e($msg);
} //end mlmBonus function
?>