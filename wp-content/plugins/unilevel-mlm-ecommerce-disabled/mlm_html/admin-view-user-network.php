<?php
function adminViewUnilevelNetwork($id)
{ 


    $table_prefix = mlm_core_get_table_prefix();	
	//$obj = new UnilevelTree();
	global $wpdb;
	get_currentuserinfo();
		
	//get no. of level
	$mlm_general_settings = get_option('wp_mlm_general_settings');
	$mlm_no_of_level=$mlm_general_settings['mlm-level'];
			
	
	 $member_page_id = $wpdb->get_var("SELECT id FROM {$table_prefix}posts  WHERE `post_content` LIKE '%mlm-view-child-level-member%'	AND `post_type` != 'revision'");
	 
	 if(!empty($_GET['lvl']) && $_GET['lvl']!='') {
	 
	$level=$_GET['lvl'];
	
	$lvl_data=returncountLevelMember($id,1);
	$level_data =getLevelInfo($id,$level);
	if(!empty($level_data )){
	
?>	 
	
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load('visualization', '1', {packages: ['table']});
</script>
<script type="text/javascript">
var visualization;
var data;
var options = {'showRowNumber': true};
function drawVisualization() {
  // Create and populate the data table.
  var dataAsJson =
  {cols:[
	{id:'A',label:'<?php  echo   _e("Username",MLM_PLUGIN_NAME); ?>',type:'string'},
	{id:'B',label:'<?php  echo   _e("First Name",MLM_PLUGIN_NAME); ?>',type:'string'},
	{id:'C',label:'<?php  echo   _e("Last Name",MLM_PLUGIN_NAME); ?>',type:'string'},
    {id:'D',label:'<?php  echo   _e("Sponsor",MLM_PLUGIN_NAME); ?>',type:'string'},
	{id:'E',label:'<?php  echo   _e("Email",MLM_PLUGIN_NAME); ?>',type:'string'},
    {id:'F',label:'<?php  echo   _e("Status",MLM_PLUGIN_NAME); ?>',type:'string'}],
  rows:[
  <?php for($i=0;$i<count($level_data);$i++) {  
			
	         ?>
                        {c:[{v:'<?php  echo  $level_data[$i]['username']?>'},
                        {v:'<?php  echo  $level_data[$i]['first_name']?>'},
                        {v:'<?php  echo  $level_data[$i]['last_name'] ?>'},
                        {v:'<?php  echo  $level_data[$i]['sponsor']?>'},
			{v:'<?php  echo  $level_data[$i]['email']?>'},
                        {v:'<?php  echo  $level_data[$i]['status']?>'}]},
  <?php } ?>
  ]};
  data = new google.visualization.DataTable(dataAsJson);
  // Set paging configuration options
  // Note: these options are changed by the UI controls in the example.
  options['page'] = 'enable';
  options['pageSize'] = 10;
  options['pagingSymbols'] = {prev: 'prev', next: 'next'};
  options['pagingButtonsConfiguration'] = 'auto';
  //options['allowHtml'] = true;
  //data.sort({column:1, desc: false});
  // Create and draw the visualization.
  visualization = new google.visualization.Table(document.getElementById('table'));
  draw();
}
function draw() {
  visualization.draw(data, options);
}
google.setOnLoadCallback(drawVisualization);
// sets the number of pages according to the user selection.
function setNumberOfPages(value) {
  if (value) {
	options['pageSize'] = parseInt(value, 10);
	options['page'] = 'enable';
  } else {
	options['pageSize'] = null;
	options['page'] = null;  
  }
  draw();
}
// Sets custom paging symbols "Prev"/"Next"
function setCustomPagingButtons(toSet) {
  options['pagingSymbols'] = toSet ? {next: 'next', prev: 'prev'} : null;
  draw();  
}
function setPagingButtonsConfiguration(value) {
  options['pagingButtonsConfiguration'] = value;
  draw();
}
</script>
<!--va-matter-->
    <div class="va-matter">
    	<!--va-matterbox-->
    	<div class="va-matterbox">
        	<!--va-headname-->
        	<div class="va-headname"><strong><?php _e('Level '.$level.' Members',MLM_PLUGIN_NAME);?></strong></div>
            <!--/va-headname-->
			<div class="va-admin-leg-details">
            	<!--va-admin-mid-->
				<div class="paging">
				  <form action="">
					<div class="left-side" style="width:30%;float:left;">
						<?php _e('Display Number of Rows',MLM_PLUGIN_NAME);?> : &nbsp; 
					</div>
					<div class="right-side">
						<select style="font-size: 12px" onchange="setNumberOfPages(this.value)">
						  <option value="5">5</option>
						  <option selected="selected" value="10">10</option>
						  <option value="20">20</option>
						  <option  value="50">50</option>
						  <option value="100">100</option>
						  <option value="500">500</option>
						   <option value="">All</option>
						</select>
					</div>	
					</form>
					<div class="right-members">
					<?php _e('Total Records',MLM_PLUGIN_NAME);?>: <strong><?php  echo   count($level_data); ?></strong>
					</div>
					<div class="va-clear"></div>
				  </div>
				<div id="table"></div>
				<div class="va-clear"></div>
			</div>		
		</div>
	</div>	
	
	 
	 
<?php	
 } else {
_e('Oops.No Records Found.',MLM_PLUGIN_NAME);
} 
         }
	 else  {
?>

 		<table border="0" cellspacing="5" cellpadding="5" width="50%">
			<TR>
			<TD align="center" ><strong><?php _e('Levels',MLM_PLUGIN_NAME);?></strong></TD>
			<TD align="center" > <strong><?php _e('No. of Members',MLM_PLUGIN_NAME);?></strong></TD>
			</TR>
		<?php $total=0;
		for($j=1;$j<=$mlm_no_of_level;$j++) {  
		if (returncountLevelMember($id,$j)==0)
		{
		$num="Level ".$j;
		}
		else {
		$num="<a href='?page=mlm-user-account&ac=network&lvl=".$j."'> Level ".$j."</a>";
		$total =$total + returncountLevelMember($id,$j);
		}
		?>
		        <TR>
			<TD align="center" ><strong><?php _e($num,MLM_PLUGIN_NAME);?></strong></TD>
			<TD align="center" > <?php _e(returncountLevelMember($id,$j),MLM_PLUGIN_NAME);?></TD>
			</TR>
		    
			<?php }  ?> 
        <TR>
			<TD align="center" ><strong><?php _e('Total',MLM_PLUGIN_NAME);?></strong></TD>
			<TD align="center" > <?php _e($total,MLM_PLUGIN_NAME);?></TD>
			</TR>			
		</table>
		
<?php
}



}
?>