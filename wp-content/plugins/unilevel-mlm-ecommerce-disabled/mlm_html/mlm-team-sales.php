<?php

function team_sales_html_page() {

	//get logged in user info
	global $current_user, $wpdb;
	//get loged user's key
	$sales =0;
	$date_format = get_option( 'date_format' );
	$user_id = get_current_userID();
	$table_prefix = mlm_core_get_table_prefix();
	get_currentuserinfo();
	$username = $current_user->ID;
	
	$user_info = get_userdata($current_user->ID);
        $mlm_settings = get_option('wp_mlm_general_settings');
	$mlm_no_of_level=$mlm_settings['mlm-level'];
	
	        $top_user = get_user_id_admin();
		$parent_id = ReturnParentID($current_user->ID);
		$show=0;
		if($current_user->ID==$top_user)
		{
		$show=1;
		//$mlm_no_of_level=1000;
		}
		
	
	
	
	
	$Start_Date=date('Y-m').'-01';
	$End_Date=date('Y-m-d');
	
	if(isset($_POST['submit'])) {
	
	if(!empty($_POST['StartDate'])) {
		$Start_Date = $_POST['StartDate'];
	}
	if(!empty($_POST['EndDate'])){
		$End_Date = $_POST['EndDate'];
	}


}

?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($) { $('.custom_date').datepicker({dateFormat : 'yy-mm-dd', closeOnSelect:'true',changeMonth: true,
changeYear: true }); });

</script>	 
	<div id="team-sales">
		<form id="MyForm" name="MyForm" action="" method="POST" >
			<?php _e('Date From', MLM_PLUGIN_NAME); ?>:&nbsp;<input type="text" class ="custom_date" name ="StartDate" id="StartDate" placeholder= "Start Date" />
			<?php _e('Date To', MLM_PLUGIN_NAME); ?>:&nbsp;<input type="text" class ="custom_date" name ="EndDate" id="EndDate" placeholder= "End Date" />
			<input type = "submit" name = "submit" value = "<?php _e('Search', MLM_PLUGIN_NAME); ?>" style = "font-weight: bold; margin-left: 10px;"/>
		</form> 
	</div>
	<br />
	
	<?php 
		if(isset($_POST['submit'])) { 
			_e('Date From:', MLM_PLUGIN_NAME).""._e($Start_Date."&nbsp;&nbsp;")."". _e('Date To:', MLM_PLUGIN_NAME)."&nbsp;".""._e($End_Date);
		}
	?>
	
        <?php
for($a=1;$a<=$mlm_no_of_level;$a++){
?>
<table width="100%" border="0" cellspacing="10" cellpadding="1">
			<tr>
			<td colspan="6">
				<strong><?php _e('Level '.$a, MLM_PLUGIN_NAME); ?></strong>
			</td>
			</tr>
			<tr style="background: #CDCDCD;">
			<td scope="row">
				<?php _e('User ID', MLM_PLUGIN_NAME); ?>
			</td>
			<td scope="row">
				<?php _e('User Name', MLM_PLUGIN_NAME); ?>
			</td>
			<td scope="row">
				<?php _e('Sponsor Name', MLM_PLUGIN_NAME); ?>
			</td>
			<td scope="row">
				<?php _e('Order ID', MLM_PLUGIN_NAME); ?>
			</td>
			<td scope="row">
				<?php _e('Purchased', MLM_PLUGIN_NAME); ?> <?php echo MLM_CURRENCY; ?>
			</td>
			<td scope="row">
				<?php _e('Date', MLM_PLUGIN_NAME); ?>
			</td>
		</tr>
	
		<?php
			$level_data = getSalesInfo($current_user->ID, $a,$Start_Date,$End_Date);
			if(!empty($level_data)){
			for($i=0; $i<count($level_data); $i++) {
		    $sales = $sales + $level_data[$i]['amount'];
			
		$sponsor =$wpdb->get_var("SELECT username FROM ".MLM_USERS." WHERE user_id ='".returnMemberParent($level_data[$i]['user_id'])."'");	
		?>
		<tr>
		<td>
			<?php echo $level_data[$i]['user_id']; ?>
		</td>
		<td>
			<?php echo $level_data[$i]['username']; ?>
		</td>
		<td>
			<?php echo $sponsor; ?>
		</td>
		<td>
			<?php echo '# '.$level_data[$i]['order_id']; ?>
		</td>
		<td>
			<?php echo $level_data[$i]['amount']; ?> <?php echo MLM_CURRENCY; ?>
		</td>
		<td>
			<?php  echo  date($date_format,strtotime($level_data[$i]['order_date'])); ?></td>
		</tr>
		<?php  }
		} else {?>
		<tr>
		<td colspan="6"><strong><?PHP _e('No sales yet at this level!',MLM_PLUGIN_NAME);?></strong></td>
		</tr>
		<?php } ?>
	</table> 
	
	
<?php } ?>	
		
	
	<!--/************** Show Total Sales In a Month Period*********/-->
	<table width="100%" border="0" cellspacing="10" cellpadding="1">
		<tr>
			<td colspan="2">
				<strong><?php if(isset($_POST['submit'])) { _e('All Purchases', MLM_PLUGIN_NAME); } 
				else { _e('All Purchases ', MLM_PLUGIN_NAME).''._e(date('F Y'), MLM_PLUGIN_NAME);  } ?></strong>
			</td>
			<td>
				<strong><?php echo number_format($sales,2,'.','');?> <?php echo MLM_CURRENCY; ?></strong>
			</td>
		</tr>
	</table>
       <!--/************** Show Total Sales In a Month Period*********/-->
<?php
}