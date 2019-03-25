<?php
function mlmGeneral(){
	global $wpdb;	
	//get database table prefix
	$table_prefix = mlm_core_get_table_prefix();
         
	$error = '';
	$chk = 'error';
	
	//most outer if condition
	if(isset($_POST['mlm_general_settings'])){
		$levels = sanitize_text_field( $_POST['mlm-level'] );
		$personal_referrer = sanitize_text_field( $_POST['personal_referrer'] );
		$register = $_POST['registration'];
		
		if ( checkInputField($personal_referrer) ) 
			$error .= "\n Please specify your personal active referrers.";
			
		if ( checkInputField($levels) ) 
			$error .= "\n Please specify No. of Levels.";

        if ( $register=='' ) 
			$error .= "\n Please select registration through option.";
		
		if ( $register=='2' ) {
			$pro_cat = $_POST['product-category'];
			if (checkInputField($pro_cat))
                $error .= "\n Please select the product category.";
			}	
			
        //if any error occoured
		if(!empty($error))
			$error = nl2br($error);
		else{
                                
			$chk = '';
			affiliate_rewrite();
			//create_new_url_querystring();
			update_option('wp_mlm_general_settings', $_POST);
			$url = get_bloginfo('url')."/wp-admin/admin.php?page=admin-settings&tab=payout";
			_e("<script>window.location='$url'</script>");
			$msg = "<span style='color:green;'>Your general settings has been successfully updated.</span>";
		}
	}// end outer if condition
?>


   <script language="javascript">
	function SelectOption(data) {
		if(data=='1'){
			jQuery("#Show_Prod").hide();
			jQuery("#Show_Prod_Category").hide();
		} else if(data=='2') {
			jQuery("#Show_Prod").show();
			jQuery("#Show_Prod_Category").show();
		}
	}
	</script>
	<?php	
	if($chk!='') {
		$mlm_settings = get_option('wp_mlm_general_settings');
		$URL=empty($mlm_settings['affiliate_url']) ? '' : $mlm_settings['affiliate_url'].'/';
	?>
		
	<div class='wrap1'>
		<div class="updated fade">
            <p><?php _e("In order to enable SEO Friendly Affiliate URLs please add the following line of code in your .htaccess file at the top of the file BEFORE the #Begin Wordpress line of code<br/><br/> <strong> RedirectMatch 301 u/(.*)  " . site_url() . "/" . $URL . "?sp_name=$1 </strong> <br/><br/>Please note that your Permalink setting in WordPress should be anything other than Default setting.", MLM_PLUGIN_NAME); ?> </p> </div>


	<?php if($error) :?>
		<div class="notibar msgerror">
		<a class="close"></a>
		<p> <strong><?php _e('Please Correct the following Error',MLM_PLUGIN_NAME);?> :</strong> <?php _e($error); ?></p>
		</div>
	<?php endif; 
	$personal_referrer = (isset($_POST['personal_referrer']) ? $_POST['personal_referrer'] : (isset($mlm_settings['personal_referrer']) ? $mlm_settings['personal_referrer'] : '0'));
	$mlm_level = (isset($_POST['mlm-level']) ? $_POST['mlm-level'] : (isset($mlm_settings['mlm-level']) ? $mlm_settings['mlm-level'] : ''));
	$Lvl = (isset($mlm_settings['mlm-level']) ? 'readonly' : '');
	$coupon_amount = (isset($_POST['coupon_amount']) ? $_POST['coupon_amount'] : (isset($mlm_settings['coupon_amount']) ? $mlm_settings['coupon_amount'] : '0'));
	$global_dis_amount = (isset($_POST['global_dis_amount']) ? $_POST['global_dis_amount'] : (isset($mlm_settings['global_dis_amount']) ? $mlm_settings['global_dis_amount'] : '0'));
	$registration = (isset($_POST['registration']) ? $_POST['registration'] : (isset($mlm_settings['registration']) ? $mlm_settings['registration'] : ''));	
	$show_prod = (isset($_POST['show_prod']) ? $_POST['show_prod'] : (isset($mlm_settings['show_prod']) ? $mlm_settings['show_prod'] : ''));
	$prod_category = (isset($_POST['product-category']) ? $_POST['product-category'] : (isset($mlm_settings['product-category']) ? $mlm_settings['product-category'] : ''));
	$affiliate_url = (isset($_POST['affiliate_url']) ? $_POST['affiliate_url'] : (isset($mlm_settings['affiliate_url']) ? $mlm_settings['affiliate_url'] : ''));	            
	?>

	<?php if(isset($mlm_settings['recur_payment']) && $mlm_settings['recur_payment']=='0') { ?>
		<script>jQuery(document).ready(function () { jQuery(".recur_id").hide(); });</script>
	<?php } ?>
		 
<form name="admin_general_settings" method="post" action="">
	<fieldset class="mlm-settings" id="eligibility-settings">
    	<legend><?php _e('Eligibility Settings',MLM_PLUGIN_NAME);?></legend>
		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="form-table">
			<tr>
				<th scope="row" class="admin-settings">
					<?php _e('No. of Personal Referrer(s)',MLM_PLUGIN_NAME);?> <span style="color:red;">*</span>:
				</th>
				<td>
					<input type="text" name="personal_referrer" id="personal_referrer" size="10" value="<?php  _e($personal_referrer);?>">
					<?php echo GENERAL_1; ?>
				</td>
			</tr>	
		</table>
	</fieldset>
	
	<fieldset class="mlm-settings" id="network-settings">
    	<legend><?php _e('Network Settings',MLM_PLUGIN_NAME);?></legend>
		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="form-table">				
			<tr>
				<th scope="row" class="admin-settings">
					<?php _e('No. of Levels',MLM_PLUGIN_NAME);?> <span style="color:red;">*</span>:
				</th>
				<td>
					<input type="text" name="mlm-level" id="mlm-level" size="10" value="<?php echo $mlm_level;?>" <?php echo $Lvl; ?>>
					<?php echo GENERAL_2; ?>
				</td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset class="mlm-settings" id="dynamic-compression">
		<legend><?php _e('Coupon Code',MLM_PLUGIN_NAME);?></legend>
		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="form-table">
			<tr>
				<th scope="row" class="admin-settings">
					<strong><?php _e('Coupon Code Value',MLM_PLUGIN_NAME);?> </strong>: </a>
				</th>
				<td>
					<input type="text" name="coupon_amount" id="coupon_amount" size="10" value="<?php echo $coupon_amount;?>" > %
					<?php echo GENERAL_3; ?>
				</td>
			</tr>

			<tr>
				<th scope="row" class="admin-setting" >
					<strong><?php _e('Global Distribution Amount', MLM_PLUGIN_NAME); ?> </strong> :
				</th>
				<td>
					<input type="text" name="global_dis_amount" id="global_dis_amount" value="<?php echo $global_dis_amount;?>"  /> %
					<?php echo GENERAL_4; ?>
				</td>
			</tr>

		</table>
	</fieldset>

	<fieldset class="mlm-settings" id="dynamic-compression">
    	<legend><?php _e('Registration Settings',MLM_PLUGIN_NAME);?></legend>
		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="form-table">
			<tr>
				<th scope="row" class="admin-setting" >
					<strong><?php _e('Redirect Affiliate URL', MLM_PLUGIN_NAME); ?>:</strong>
				</th>
				<td>
					<?php  echo   site_url() . '/' ?><input type="text" name="affiliate_url" id="affiliate_url" value="<?php echo $affiliate_url; ?>" />
					<?php echo GENERAL_5; ?>
				</td>
			</tr>

			<tr>
				<th scope="row" class="admin-settings">
					<strong><?php _e('Registration Through',MLM_PLUGIN_NAME);?> </strong><span style="color:red;">*</span>: </a>
				</th>
				<td>
					<input type="radio" name="registration" id="registration" size="10" value="1" <?php if($registration==1) echo 'checked="checked"'; ?> onclick="SelectOption(this.value);"> Woocommerce  <?php echo GENERAL_6; ?></br></br>
					<input type="radio" name="registration" id="registration" size="10" value="2" <?php if($registration==2) echo 'checked="checked"' ?> onclick="SelectOption(this.value);"> Qualification Product <?php echo GENERAL_7; ?>
				</td>
			</tr>

			<tr <?php if($registration!=2) { ?>style="display:none;" <?php } ?> id="Show_Prod_Category" >
				<th scope="row" class="admin-setting" >&nbsp;</th>
				<td>
					<select name="product-category" > 
						<option value=""><?php echo _e('Select Category',MLM_PLUGIN_NAME); ?></option> 
						<?php 
						$categories = get_terms('product_cat', array('hide_empty'=>false,'orderby'=>'title','order'=>'ASC'));
						$total= count($categories);
						if ( $total > 0 ){
							foreach ($categories as $category) {
								if($category->slug!='qualification-products') {
									if($prod_category==$category->slug) { $sel ='selected'; } else { $sel =''; }
									$option = '<option value="'.$category->slug.'" '.$sel.' >';
									$option .= $category->name;
									$option .= ' ('.$category->count.')';
									$option .= '</option>';
									echo $option;
								}
							}
						}
						?>
					</select> <?php echo GENERAL_8; ?>
				</td>
			</tr>

			<tr <?php if($registration!=2) { ?>style="display:none;" <?php } ?> id="Show_Prod" >
				<th scope="row" class="admin-setting" >&nbsp;</th>
				<td>
					<input type="checkbox" name="show_prod" id="show_prod" value="1" <?php echo ($show_prod == 1) ? ' checked="checked"' : ''; ?> /> Do not show qualification products on the Shop page.
					<?php echo GENERAL_9; ?>
				</td>
			</tr>
		</table>
	</fieldset>

	<p class="submit">
		<input type="submit" name="mlm_general_settings" id="mlm_general_settings" value="<?php _e('Update Options', MLM_PLUGIN_NAME);?> &raquo;" class='button-primary' onClick="needToConfirm = false;">
	</p>
</form>
</div>
		<?php
		
	} // end if statement
	else
		 _e($msg);
} //end mlmGeneral function
?>