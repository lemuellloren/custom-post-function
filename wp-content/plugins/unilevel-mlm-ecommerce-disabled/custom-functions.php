<?php
if (!function_exists('get_user_name_top_user')) {
function get_user_name_top_user() {
    global $wpdb;
    $table_prefix = mlm_core_get_table_prefix();
    $username = $wpdb->get_var("SELECT username FROM ".MLM_USERS." order by id ASC Limit 1");
    return $username;
}


}

if (!function_exists('getusernamebyId')) {
function getusernamebyId($ID)
{
	$table_prefix = mlm_core_get_table_prefix();
	global $wpdb;
	$id = $wpdb->get_var("
							SELECT user_login 
							FROM {$table_prefix}users 
							WHERE `ID` = '".$ID."'
				");
	return $id;
}
}


if (!function_exists('getuser_keybyId')) {
function getuser_keybyId($ID)
{
	$table_prefix = mlm_core_get_table_prefix();
	global $wpdb;
	$id = $wpdb->get_var("
							SELECT user_key 
							FROM ".MLM_USERS." 
							WHERE `user_id` = '".$ID."'
				");
	return $id;
}
}


//return the logeed user's fa_user key
if (!function_exists('get_current_userID')) {
function get_current_userID()
{
	$table_prefix = mlm_core_get_table_prefix();
	
	global $current_user, $wpdb;
	
	get_currentuserinfo();

	$userid = $current_user->ID;

	return $userid;
}


}
if (!function_exists('ReturnParentID')) {
function ReturnParentID($id) {
    $table_prefix = mlm_core_get_table_prefix();

    global $wpdb;

    $num = $wpdb->get_var("SELECT parent_id FROM ".MLM_USERS." WHERE user_id = '" . $id . "'");


    return $num;
}
}



function NewUserMailSend($userId){
    global $wpdb;
    $table_prefix = mlm_core_get_table_prefix();
    //get no. of level
    $mlm_general_settings = get_option('wp_mlm_general_settings');
    $mlm_no_of_level = $mlm_general_settings['mlm-level'];
    $mlm_payout_settings = get_option('wp_mlm_payout_settings');
    
    $sponsorId= $wpdb->get_var("select sponsor_id from ".MLM_USERS." where user_id='".$userId."'");
    
    $parentUserid[0]=$userId;
    for ($i = 1; $i <= $mlm_no_of_level; $i++)  {
    $parentUserid[$i]=ReturnParentID($parentUserid[$i-1]);
		 if($parentUserid[$i]==0 || $parentUserid[$i]=='')
		{ break;
		}
		else 
		{
		SendMailToAll($parentUserid[$i],$userId,$sponsorId);
		
		}
    
    }
}

function InsertHierarchy($user_id,$sponsorId)
{
	global $wpdb;
	$table_prefix = mlm_core_get_table_prefix();
	
	$mlm_general_settings = get_option('wp_mlm_general_settings');
	$mlm_no_of_level=$mlm_general_settings['mlm-level'];
	
			$parentUserid[0]=$user_id;
			for($i=1;$i<=$mlm_no_of_level;$i++) {
			
			$parentUserid[$i]=returnMemberParent($parentUserid[$i-1]);
			if($u=get_option('network_mail', true)==1) {
			SendMailToAll($parentUserid[$i],$user_id,$sponsorId);
			}
			if($parentUserid[$i]==0 || $parentUserid[$i]=='')
			{
			
			break;
			}
			else 
			{
			$qry_insert="INSERT INTO {$table_prefix}mlm_hierarchy
			(pid, cid, level) VALUES
			('".$parentUserid[$i]."','".$user_id."', '".$i."')";
			
			$wpdb->query($qry_insert);
				
}
 }
}

add_action('get_footer','saveCookies',25);

function saveCookies()
{
global $wpdb,$table_prefix;

  if(!empty($_GET['sp_name'])) {
	
	$sp_name = $wpdb->get_var("select username from ".MLM_USERS." where username='".$_GET['sp_name']."'");
	if($sp_name)
	{
	?>
	<script type='text/javascript'>
	function setCookie(key, value) {
            var expires = new Date();
            expires.setTime(expires.getTime() + (1 * 24 * 60 * 60 * 1000));
            document.cookie = key + '=' + value + ';path=/;expires=' + expires.toUTCString();
        }
         setCookie('s_name','<?php  echo   $sp_name ?>');
		//jQuery.cookie('s_name','<?php  echo   $sp_name ?>',{ path: '/' });
	</script>
	<?php 
	}
	else {
	 $page ='not-found';
    
	 ?>
	<script> window.location.href ='<?php echo get_permalink( get_page_by_path($page));?>';</script>
	<?php exit();
	    }
	}
}



function SendMailToAll($users_id,$user_id,$enrollerId)
{

global $wpdb;
$table_prefix = mlm_core_get_table_prefix();

 $admin_mail=get_option('admin_email');
 $parent_username=$wpdb->get_var("SELECT user_login FROM {$table_prefix}users WHERE `ID` = '".$enrollerId."'");
 
 if(!empty($users_id))
 {
 $enrol_mail=$wpdb->get_var("SELECT user_email FROM {$table_prefix}users WHERE `ID` = '".$users_id."'");
 $enroll_info=get_userdata($users_id);
 $enrl_fname=$enroll_info->first_name;
 }
 else if($users_id==0){
 $id = $wpdb->get_var("SELECT user_id FROM ".MLM_USERS." ORDER BY id ASC LIMIT 1");
 $enrol_mail=$wpdb->get_var("SELECT user_email FROM {$table_prefix}users WHERE `ID` = '".$id."'");
  }
 
 $user_info = get_userdata($user_id);
 $username = $user_info->user_login;
 $email=$user_info->user_email;
 $first_name = $user_info->first_name;
 $last_name = $user_info->last_name;
 $siteownwer= get_bloginfo('name');
 $to =$enrol_mail;

$headers = "MIME-Version: 1.0"."\r\n";
$headers .= "Content-type:text/html; charset=iso-8859-1" . "\r\n";
$headers .= "From: ".get_bloginfo('name')."<".$admin_mail.">"."\r\n";

$subject=get_option('networkgrowing_email_subject', true);

$message = nl2br(htmlspecialchars(get_option('networkgrowing_email_message', true)));

$message = str_replace('[firstname]', $first_name, $message);

$message = str_replace('[lastname]', $last_name, $message);

$message = str_replace('[email]', $email, $message);

$message = str_replace('[username]', $username, $message);

$message = str_replace('[sponsor]', $parent_username, $message);

$message = str_replace('[sitename]', $siteownwer, $message);


wp_mail( $to, $subject, $message, $headers);

}

// If apply for with drawal From Front End
function WithDrawalProcessMail($Id,$_my_post)
{

global $wpdb;
$table_prefix = mlm_core_get_table_prefix();


$deduction = get_option('wp_mlm_withdrawal_method_settings');
$method=unserialize(stripcslashes($_my_post['withdrawalMode']));
$mode=$method[0];

$admin_mail=get_option('admin_email');


$user_info = get_userdata($Id);
$username = $user_info->user_login;
$first_name = $user_info->first_name;
$last_name = $user_info->last_name;
$email = $user_info->user_email;
 $name=$first_name." ".$last_name;
if(empty($first_name) && empty($first_name)) {
$name=$username;
} 
      $siteownwer = get_bloginfo('name');
$to =$admin_mail;

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
$headers .= 'From: Admin <'.$admin_mail.'>' . "\r\n";


$subject=get_option('withdrawalintiate_email_subject', true);

$message = nl2br(htmlspecialchars(get_option('withdrawalintiate_email_message', true)));

$message = str_replace('[firstname]', $first_name, $message);

$message = str_replace('[lastname]', $last_name, $message);

$message = str_replace('[email]', $email, $message);

$message = str_replace('[username]', $username, $message);

$message = str_replace('[sitename]', $siteownwer, $message);

wp_mail( $to, $subject, $message, $headers);

}



function WithDrawalMemberMail($userId,$mode,$amount)
{

global $wpdb;
$table_prefix = mlm_core_get_table_prefix();


$deduction = get_option('wp_mlm_withdrawal_method_settings');

$admin_mail=get_option('admin_email');

$user_info = get_userdata($userId);
$username = $user_info->user_login;
 $email=$user_info->user_email;
 $first_name = $user_info->first_name;
 $last_name = $user_info->last_name;

$siteownwer= get_bloginfo('name');

$name=$first_name." ".$last_name;
if(empty($first_name) && empty($first_name)) {
$name=$username;
} 
  
$to =$admin_mail;

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
$headers .= 'From: Admin <'.$admin_mail.'>' . "\r\n";


$subject=get_option('withdrawalProcess_email_subject', true);

$message = nl2br(htmlspecialchars(get_option('withdrawalProcess_email_message', true)));

$message = str_replace('[firstname]', $first_name, $message);

$message = str_replace('[lastname]', $last_name, $message);

$message = str_replace('[email]', $email, $message);

$message = str_replace('[username]', $username, $message);

$message = str_replace('[amount]', $amount, $message);

$message = str_replace('[withdrawalmode]', $mode, $message);

$message = str_replace('[sitename]', $siteownwer, $message);

wp_mail( $to, $subject, $message, $headers);

}



function PayoutGeneratedMail($userId,$total,$payoutMasterId)
{

global $wpdb;
$table_prefix = mlm_core_get_table_prefix();


$deduction = get_option('wp_mlm_withdrawal_method_settings');

$amount=number_format($total, 2, '.', '');

$payId=$payoutMasterId;
//$comment=$_my_post['wcomment'];
$admin_mail=get_option('admin_email');

$user_info = get_userdata($userId);
$username = $user_info->user_login;
$first_name = $user_info->first_name;
$last_name = $user_info->last_name;
$email = $user_info->user_email;
$name=$first_name." ".$last_name;
if(empty($first_name) && empty($first_name)) {
$name=$username;
} 
  $siteownwer = get_bloginfo('name');
  $to =$email;

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
$headers .= 'From: Admin <'.$admin_mail.'>' . "\r\n";


$subject=get_option('runpayout_email_subject', true);

$message = nl2br(htmlspecialchars(get_option('runpayout_email_message', true)));
 
$message = str_replace('[firstname]', $first_name, $message);

$message = str_replace('[lastname]', $last_name, $message);

$message = str_replace('[email]', $email, $message);

$message = str_replace('[username]', $username, $message);

$message = str_replace('[amount]', $amount, $message);

$message = str_replace('[payoutid]', $payId, $message);

$message = str_replace('[sitename]', $siteownwer, $message);

wp_mail( $to, $subject, $message, $headers);

}

// Display Fields
add_action( 'woocommerce_product_options_general_product_data', 'woo_add_custom_general_fields' );
 
// Save Fields
add_action( 'woocommerce_process_product_meta', 'woo_add_custom_general_fields_save' );

function woo_add_custom_general_fields_save( $post_id ){
	// Number Field
	$woocommerce_number_field = $_POST['mlm_commission_product'];
	if( !empty( $woocommerce_number_field ) )
		update_post_meta( $post_id, 'mlm_commission_product', esc_attr( $woocommerce_number_field ) );
		
}


function woo_add_custom_general_fields() {
 
  global $woocommerce, $post;
  
  echo '<div class="options_group">';
woocommerce_wp_text_input( 
	array( 
		'id'                => 'mlm_commission_product', 
		'label'             => __( 'Distribution Amount (%)', 'woocommerce' ), 
		'placeholder'       => '', 
		'description'       => __( 'Amount used during commision distribution', 'woocommerce' ),
		'type'              => 'number', 
		'custom_attributes' => array(
				'step' 	=> 'any',
				'min'	=> '0'
			) 
	)
);
  // Custom fields will be created here...
  
  echo '</div>';
	
}


/**
* Outputs a rasio button form field
*/
function mlm_woocommerce_form_field_radio( $key, $args, $value = '' ) {
global $woocommerce;
$defaults = array(
'type' => 'radio',
'label' => '',
'placeholder' => '',
'required' => false,
'class' => array( ),
'label_class' => array( ),
'return' => false,
'options' => array( )
);
$args     = wp_parse_args( $args, $defaults );
if ( ( isset( $args[ 'clear' ] ) && $args[ 'clear' ] ) )
$after = '<div class="clear"></div>';
else
$after = '';

$required = ( $args[ 'required' ] ) ? ' <abbr class="required" title="' . esc_attr__( 'required', 'woocommerce' ) . '">*</abbr>' : '';

switch ( $args[ 'type' ] ) {
case "select":
$options = '';
if ( !empty( $args[ 'options' ] ) )
foreach ( $args[ 'options' ] as $option_key => $option_text )
$options .= '<input type="radio" name="' . $key . '" id="' . $key . '" value="' . $option_key . '" ' . selected( $value, $option_key, false ) . 'class="select"> ' . $option_text . '' . "</br>";
$field = '<p class="form-row ' . implode( ' ', $args[ 'class' ] ) . '" id="' . $key . '_field">
<label for="' . $key . '" class="' . implode( ' ', $args[ 'label_class' ] ) . '">' . $args[ 'label' ] . $required . '</label>
' . $options . '
</p>' . $after;
break;
} 

if ( $args[ 'return' ] )
return $field;
else
echo $field;
}

add_action('init','check_general_settings');

function check_general_settings() {

$mlm_general_settings = get_option('wp_mlm_general_settings');
$registration = $mlm_general_settings['registration'];
 
 if($registration =='1' && !is_user_logged_in())
 {
add_action( 'woocommerce_checkout_process', 'join_mlm_checkout_field_process' );
add_action( 'woocommerce_after_order_notes', 'join_mlm_checkout_field' );
  
  }

}

/**
* Add the field to the checkout
**/
function join_mlm_checkout_field( $checkout ) {
echo '<div id="join_mlm_checkout_field"><h3>' . __( 'Do you want to join the MLM?' ) . '</h3>';
mlm_woocommerce_form_field_radio( 'join_mlm_checkout', array(
'type' => 'select',
'class' => array(
'join-mlm-checkout form-row-wide'
),
'label' => __( '' ),
'placeholder' => __( '' ),
'required' => true,
'options' => array(
'Yes' => 'Yes',
'No' => 'No'
)
), $checkout->get_value( 'join_mlm_checkout' ) );
echo '</div>';
}
/**
* Process the checkout
**/

function join_mlm_checkout_field_process() {
global $woocommerce;
// Check if set, if its not set add an error.
if ( !$_POST[ 'join_mlm_checkout' ] )
//$woocommerce->add_error( __( 'Please select any option do you want to join the MLM?' ) );
wc_add_notice(  __( 'Please select any option do you want to join the MLM?', 'woocommerce' ), 'error' );
}

/**
* Update the order meta with field value

add_action( 'woocommerce_checkout_update_order_meta', 'join_mlm_checkout_field_update_order_meta' );
function join_mlm_checkout_field_update_order_meta( $order_id ) {
if ( $_POST[ 'join_mlm_checkout' ] )
update_post_meta( $order_id, 'Do you want to join the MLM?', esc_attr( $_POST[ 'join_mlm_checkout' ] ) );
}
**/

add_action( 'delete_user', 'mlm_delete_user' );

function mlm_delete_user( $user_id ) {
	global $wpdb,$table_prefix;

        
        $id = $wpdb->get_row("select parent_id from  ".MLM_USERS." where user_id='".$user_id."'");
        
        $p_Id = $id->parent_id;
        $p_Name = $wpdb->get_var("select username from  ".MLM_USERS." where user_id='".$p_Id."'");
        
        $data = $wpdb->get_results("select user_id from  ".MLM_USERS." where parent_id='".$user_id."'");

        foreach($data as $result)
        {
        $UID = $result->user_id ;
     
        $wpdb->query("update ".MLM_USERS." set sponsor_id='".$p_Id."',parent_id='".$p_Id."' where user_id='".$UID."'");
        
        $wpdb->query("update {$table_prefix}usermeta set meta_value='".$p_Name."' where meta_key='mlm_user_sponsor' AND  user_id='".$UID."'");
        

        }
        
        $insert =$wpdb->query("insert into ".MLM_DELETED_USERS." (user_id,username,user_key,parent_id,sponsor_id,deleted_date) values ($user_id,'".getusernamebyId($user_id)."','".getuser_keybyId($user_id)."',$p_Id,$p_Id,'".current_time('mysql')."')");
        
        if($insert){ 
        $wpdb->query("delete from ".MLM_USERS." where user_id='$user_id'");
        }
        
}



/**
 * Returns all the orders made by the user
 *
 * @param int $user_id
 * @param string $status (completed|processing|canceled|on-hold etc)
 * @return array of order ids
 */
function ume_get_all_user_orders($user_id,$status='completed'){
    if(!$user_id)
        return false;
    
    $orders=array();//order ids
     
    $args = array(
        'numberposts'     => -1,
        'meta_key'        => '_customer_user',
        'meta_value'      => $user_id,
        'post_type'       => 'shop_order',
        'post_status'     => 'publish',
        'tax_query'=>array(
                array(
                    'taxonomy'  =>'shop_order_status',
                    'field'     => 'slug',
                    'terms'     =>$status
                    )
        )  
    );
    
    $posts=get_posts($args);
    //get the post ids as order ids
    $orders=wp_list_pluck( $posts, 'ID' );
    
    return $orders;
 
}

/*
* Use for add affiliate rewrite rule
*/
function affiliate_rewrite() {
	global $wp_rewrite;
	$Url = site_url();
	//add_rewrite_rule('^actor/([^/]*)$', $Url.'register-new-user/?sp_name=$1', 'bottom');
	add_rewrite_rule('u/(.*)$', 'http://dev.wordpressmlm.com/ume/v250/shop/?sp_name=$1', 'bottom');
	$wp_rewrite->flush_rules(true);
}


add_action( 'woocommerce_product_quick_edit_end', function(){

    /*
    Notes:
    Take a look at the name of the text field, '_custom_field_demo', that is the name of the custom field, basically its just a post meta
    The value of the text field is blank, it is intentional
    */

    ?>  <br class="clear">
	<div class="ume_comm_amt_field" style="float:left;">
	<h4><?php _e('Distribution Amount', 'woocommerce' ); ?></h4>
	<label class="alignleft">
	<span class="title"><?php _e('Value ', 'woocommerce' ); ?></span>
	<span class="input-text-wrap">
	<input type="text" name="mlm_commission_product" class="text" style="width:75%;" placeholder="<?php _e( 'Distribution Amount', 'woocommerce' ); ?>" value=""> %
	</span>
        </label>
	</div>
    <?php

} );



add_action('woocommerce_product_quick_edit_save', function($product){

/*
Notes:
$_REQUEST['mlm_commission_product'] -> the custom field we added above
Only save custom fields on quick edit option on appropriate product types (simple, etc..)
Custom fields are just post meta
*/

if ( $product->is_type('simple') || $product->is_type('external') ) {

    $post_id = $product->id;

    if ( isset( $_REQUEST['mlm_commission_product'] ) ) {

        $pro_comm_amt = trim(esc_attr( $_REQUEST['mlm_commission_product'] ));

        // Do sanitation and Validation here

        update_post_meta( $post_id, 'mlm_commission_product', wc_clean( $pro_comm_amt ) );
    }

}

}, 10, 1);



add_action( 'manage_product_posts_custom_column', function($column,$post_id){

switch ( $column ) {
    case 'name' :

        ?>
        <div class="hidden mlm_commission_product_inline" id="mlm_commission_product_inline_<?php echo $post_id; ?>">
            <div id="mlm_commission_product"><?php echo get_post_meta($post_id,'mlm_commission_product',true); ?></div>
        </div>
        <?php

        break;

        default :
        break;
} }, 99, 2);



/*
add_action('woocommerce_before_cart_table', 'ume_check_coupon');
add_action('woocommerce_before_checkout_process', 'ume_check_coupon');
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
if (!function_exists('ume_check_coupon')) {
     function ume_check_coupon() {

        global $woocommerce;
echo '123456';
        print_r($_REQUEST);
        $coupon_code = $_REQUEST['coupon_code'];
       
       
             if ($woocommerce->cart->has_discount(sanitize_text_field($coupon_code))) {

                if ($woocommerce->cart->remove_coupons(sanitize_text_field($coupon_code))) {

                  
                }
                wc_add_notice(  __( 'you can not used this coupon?', 'woocommerce' ), 'error' );
                // Manually recalculate totals.  If you do not do this, a refresh is required before user will see updated totals when discount is removed.
                $woocommerce->cart->calculate_totals();

 } } }*/