<?php

require_once("php-form-validation.php");
require_once("admin-mlm-general-settings.php");
require_once("admin-mlm-payout-settings.php");
require_once("admin-mlm-deduction-settings.php");
require_once("admin-create-first-user.php");
require_once("admin-mlm-payout-run.php");
require_once("admin-mlm-email-template.php");
require_once("admin-mlm-change-sponsor.php");


function adminMLMSettings()
{	
	global $pagenow, $wpdb;
	$table_prefix = mlm_core_get_table_prefix();
	$mlm_settings = get_option('wp_mlm_general_settings');	
	$sql = "SELECT COUNT(*) AS num FROM ".MLM_USERS;
	$num = $wpdb->get_var($sql);

	if($num == 0)
	{
		$tabs = array( 
						'createuser' => __('Create First User',MLM_PLUGIN_NAME), 
						'general' => __('General',MLM_PLUGIN_NAME) ,
						'payout' => __('Payout',MLM_PLUGIN_NAME) ,
						'deduction' => __('Deductions',MLM_PLUGIN_NAME),
						'sponsor_change' => __('Change Sponsor',MLM_PLUGIN_NAME),
                                                'email' => __('Email Settings',MLM_PLUGIN_NAME)
						);
		/**
		* Detect plugin. For use in Admin area only.
		*/
		//$tabs['license_detail'] = __('License Detail',MLM_PLUGIN_NAME);
        $tabs['reset_all_data'] = __('Reset All MLM Data',MLM_PLUGIN_NAME);	 		
		$tabval = 'createuser';
		$tabfun = 'register_first_user';
	}
	else
	{
		$tabs = array(
						'general' => __('General',MLM_PLUGIN_NAME) ,
						'payout' => __('Payout',MLM_PLUGIN_NAME) ,
						'deduction' => __('Deductions',MLM_PLUGIN_NAME),
						'sponsor_change' => __('Change Sponsor',MLM_PLUGIN_NAME),
                                                'email' => __('Email Settings',MLM_PLUGIN_NAME)
						
					  ); 

	//	$tabs['license_detail'] = __('License Detail',MLM_PLUGIN_NAME);
		  $tabs['reset_all_data'] = __('Reset All MLM Data',MLM_PLUGIN_NAME);	 			  
		$tabval = 'general';
		$tabfun = 'mlmGeneral';
	}
	if(!empty($_GET['tab'])){
	if( $pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'createuser')
		$current = 'createuser';
	else if(  $pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'general')
		$current = 'general';
	else if( $pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'payout')
		$current = 'payout';
	else if( $pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'deduction')
		$current = 'deduction';
	else if( $pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'sponsor_change')
		$current = 'sponsor_change';	
        else if( $pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'email')
		$current = 'email';
	else if ($pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'paypal_detail')
            $current = 'paypal_detail';		
	else if( $pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'reset_all_data')
		$current = 'reset_all_data';
	}	
	else
 		$current = $tabval;
		
    $links = array();
	  
	_e('<div id="icon-themes" class="icon32"><br></div>');
	 _e("<h1>MLM Settings</h1>",MLM_PLUGIN_NAME);
    _e('<h2 class="nav-tab-wrapper">');
    foreach( $tabs as $tab => $name )
	{
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        _e("<a class='nav-tab$class' href='?page=admin-settings&tab=$tab'>$name</a>");    
    }
    _e('</h2>');
    
    if($pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && (isset($_GET['tab']) && $_GET['tab'] != 'reset_all_data')) {
	?>
	    <script type="text/javascript">
		//<![CDATA[
		$(function(){ $('.demo-tip-darkgray').poshytip({className: 'tip-darkgray',bgImageFrameSize: 11,offsetX: -25});});
		//]]>
	</script>
	<?php
      }
	
	if(!empty($_GET['tab'])){
	if($pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'createuser')
			register_first_user();
	else if($pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'general')
		 mlmGeneral();
	else if($pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'payout')
		mlmPayout();
	else if($pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'deduction')
		mlmDeduction();
	else if($pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'sponsor_change')
		change_mlm_sponsor();	
        else if($pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'email')
		mlmEmailTemplates();
	else if ($pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'paypal_detail')
        Paypal_Detail();					
	else if($pagenow == 'admin.php' && $_GET['page'] == 'admin-settings' && $_GET['tab'] == 'reset_all_data')
                adminMlmReserAllData();		  
		}
	else
		 $tabfun();
		 
}//end function






?>