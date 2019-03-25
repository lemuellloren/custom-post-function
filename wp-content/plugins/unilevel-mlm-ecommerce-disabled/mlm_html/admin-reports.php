<?php
function adminReports()
{
	global $pagenow, $wpdb;
	$tabs = array(
					/*	'earningreports' => __('Earning Reports',MLM_PLUGIN_NAME) ,  */
						'withdrawalsreports'=> __('Withdrawal Reports',MLM_PLUGIN_NAME)
					  ); 
					  
		$tabval = 'withdrawalsreports';
		$tabfun = 'adminMLMSucessWithdrawals';
		
/*          if(!empty($_GET['tab'])){
			if( $pagenow == 'admin.php' && $_GET['page'] == 'admin-reports' && $_GET['tab'] == 'earningreports')
				$current = 'earningreports';
			else if( $pagenow == 'admin.php' && $_GET['page'] == 'admin-reports' && $_GET['tab'] == 'withdrawalsreports')
				$current = 'withdrawalsreports';
		          }	
		          else*/
		$current = $tabval;
		
		$links = array();
	  
		_e('<div id="icon-themes" class="icon32"><br></div>');
/*		_e("<h1>MLM Reports</h1>",MLM_PLUGIN_NAME);

				_e('<h2 class="nav-tab-wrapper">');
		
		foreach( $tabs as $tab => $name )
		{
			$class = ( $tab == $current ) ? ' nav-tab-active' : '';
			_e("<a class='nav-tab$class' href='?page=admin-reports&tab=$tab'>$name</a>");    
		}
		_e('</h2>');
		
		
		if(!empty($_GET['tab'])){
			if($pagenow == 'admin.php' && $_GET['page'] == 'admin-reports' && $_GET['tab'] == 'earningreports')
				earningReports();
			else if($pagenow == 'admin.php' && $_GET['page'] == 'admin-reports' && $_GET['tab'] == 'withdrawalsreports')
				adminMLMSucessWithdrawals();
	
        }
		else */
		 $tabfun();
}



function earningReports()
{
	require_once('earning-reports.php');
}
?>