<?php
function change_mlm_sponsor() {
	global $wpdb;
        $url = plugins_url();
	$table_prefix = mlm_core_get_table_prefix();
	$error = '';
	$chk = 'error';
	
        if(isset($_POST['submit'])){
                $user = $_POST['username'];
        	$sponsor = $_POST['sponsor'];
                $check_user = $wpdb->get_var("SELECT `username` FROM ".MLM_USERS." WHERE `username` = '".$user."'");
		$check_sponsor = $wpdb->get_var("SELECT `username` FROM ".MLM_USERS." WHERE `username` = '".$sponsor."'");
	
	        if ( empty($user) ) {
			$error .= "Please specify Username. \n "; 
		} else if(!$check_user) {
	        	$error .= "Sorry, the specified Username could not be found. \n ";
	        }
        
                if ( empty($sponsor) ) {
			$error .= "Please specify Sponsor Username. \n "; 
		} else if(!$check_sponsor) {
	        	$error .= "Sorry, the specified Sponsor Username could not be found. \n ";
	        }

 		if (!empty($user) && !empty($sponsor) && $user == $sponsor ){
			$error .= "\n Username and Sponsor Username cannot be the same.";
		} else if (!empty($user) && !empty($sponsor)){
			$UserId = $wpdb->get_var("SELECT `user_id` FROM ".MLM_USERS." WHERE `username` = '".$user."'");
		        $new_sponsor = $wpdb->get_var("SELECT `user_id` FROM ".MLM_USERS." WHERE `username` = '".$sponsor."'");
                   	$parent_id = $wpdb->get_var("SELECT `parent_id` FROM ".MLM_USERS." WHERE `user_id` = '".$new_sponsor."'");
	         
	         	if( $UserId == $parent_id ){
	        	         $error .= "\n This move is not allowed. Users can only be shifted in the upline!";
	        	} else {             
				while ($parent_id != '0') {
                        		$query = "SELECT COUNT(*) num,parent_id FROM ".MLM_USERS." WHERE user_id = '".$parent_id."'";
                        		$result = $wpdb->get_row($query);
                        		if ($result->num == 1) {
                            			if ($result->parent_id != '0') {
							if($UserId==$result->parent_id){
								$error .= "\n This move is not allowed. Users can only be shifted in the upline!";
							}
                            			}
                            			$parent_id = $result->parent_id;
                        		} else {
                            			$parent_id = '0';
                        		}
                      		}
                	}  
		}

		if(!empty($error))
			$error = nl2br($error);
		else {
			$UID = $wpdb->get_var("SELECT `user_id` FROM ".MLM_USERS." WHERE `username` = '".$user."'");
			$p_Id = $wpdb->get_var("SELECT `user_id` FROM ".MLM_USERS." WHERE `username` = '".$sponsor."'");
			$wpdb->query("UPDATE ".MLM_USERS." SET `sponsor_id` = '".$p_Id."', parent_id = '".$p_Id."' WHERE `user_id` = '".$UID."'");
        		$wpdb->query("UPDATE {$table_prefix}usermeta SET meta_value = '".$sponsor."' WHERE meta_key = 'mlm_user_sponsor' AND  user_id = '".$UID."'");
        	
        		echo '<div class="notibar msgsuccess">';
				echo '<a class="close"></a><p><span style="color:green;">Sponsor change successful. <strong>'.$sponsor.'</strong> is the new sponsor for <strong>'.$user.'</strong>.</span></p>';
			echo '</div>';
			$_POST = array();
			
		
		}
	}
?>
	<style type="text/css"> 
	 
	#result { 
		position: absolute;
		background: rgb(241, 241, 241);
	}
        #result ul { 
		border: 1px solid #ccc;
		padding: 10px 5px;
		min-width: 150px;
	}
        #result ul li.name {
		cursor: pointer;
		padding: 2px 0px;
	}
	
        #data { 
		position: absolute;
		background: rgb(241, 241, 241); 
	}
	
        #data ul { 
		border: 1px solid #ccc;
		padding: 10px 5px;
		min-width: 150px;
	}
        #data ul li.newname {
		cursor: pointer;
		padding: 2px 0px;
	}
	
	</style>

	<script type="text/javascript">
	$("li.name").live('click',function(){
		//var id = $(this).attr("id");
		var label = $(this).text();
		$("#username").val(label);
		$("#result ul").hide();
	});

	$(function(){
		$(".usearch").keyup(function() { 
			var searchid = $(this).val();
			var dataString = 'do=changesponsor&username='+ searchid;
			if(searchid!='') {
	    			$.ajax({
		    			type: "POST",
		    			url: "<?php _e($url.'/'.MLM_PLUGIN_NAME.'/ajaxFunction.php'); ?>",
		    			data: dataString,
		    			cache: false,
		    			success: function(html) {
		    				$("#result").html(html).show();
		    			}
	    			});
			}
			return false;    
		});
	});
	
	$("li.newname").live('click',function(){
		//var id = $(this).attr("id");
		var label = $(this).text();
		$("#sponsor").val(label);
		$("#data ul").hide();
	});
		
	$(function(){
		$(".spon").keyup(function() { 
			var searchid = $(this).val();
			var dataString = 'do=sponsor&username='+ searchid;
			if(searchid!='') {
	    			$.ajax({
		    			type: "POST",
		    			url: "<?php _e($url.'/'.MLM_PLUGIN_NAME.'/ajaxFunction.php'); ?>",
		    			data: dataString,
		    			cache: false,
		    			success: function(html) {
						$("#data").html(html).show();
					}
	    			});
			}
			return false;    
		});
	});
	</script>
	<div class='wrap1'>
		<?php if($error) : ?>
			<div class="notibar msgerror">
				<a class="close"></a><p><?php _e($error,MLM_PLUGIN_NAME); ?></p>
			</div>
		<?php endif; ?>

		<form name="admin_withdrawalsettings" method="post" action="">
			<fieldset id="change-sponsor-settings" class="mlm-settings">
				<legend><?php _e('Change Sponsor Settings', MLM_PLUGIN_NAME); ?> <?php echo CHECK_SPON; ?></legend>
				<table id="dataTableheading" cellspacing="5" cellpadding="5" border="0" width="95%" class="form-table">
					<tr>
					<td align="left" width="10%">
						<strong><?php _e('Username', MLM_PLUGIN_NAME); ?>: <span style="color: red">*</span></strong>
					</td>
					<td align="left" width="35%">
						<input type="text" name="username" id="username" class="usearch" value="<?php echo !empty($_POST['username'])? $_POST['username'] : ''; ?>" 
						autocomplete="off">
					<br/> 
		                        <div id="result"></div>
		                        </td>
					</tr>
					
					<tr id="sname">
					<td align="left" width="10%">
						<strong><?php _e('Sponsor Username', MLM_PLUGIN_NAME); ?>: <span style="color: red">*</span></strong>
					</td>
					<td align="left" width="35%">
						<input type="text" name="sponsor" id="sponsor" class="spon" value="<?php echo !empty($_POST['sponsor'])? $_POST['sponsor'] : ''; ?>" 
						autocomplete="off">
						<br/><div id="data"></div>
					</td>
					</tr>
					
					<tr>
					<td colspan="2"></td>
					</tr>
					<tr>
					<td align="left" width="10%">
						<strong><input type="submit" name="submit" id="submit" value="<?php _e('Change Sponsor', MLM_PLUGIN_NAME);?>" class='button-primary' >
					</td>
					<td align="left" width="35%">&nbsp;</td>
					</tr>
				</table>
			</fieldset>
		</form>	
	</div>		
<?php
}