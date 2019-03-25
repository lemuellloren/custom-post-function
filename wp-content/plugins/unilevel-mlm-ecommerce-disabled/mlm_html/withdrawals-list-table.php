<?php
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Withdrawals_List_Table extends WP_List_Table {
    
	function column_default($item, $column_name){
        switch($column_name){
			case 'username':
            case 'useremail':
           	case 'withdrawaldate':
			case 'withdrawalamount':
			case 'withdrawalmode':
			case 'withdrawalcomments':
			case 'paymentdetails':
			//case 'paymentdate':
                return $item[$column_name];
            default:
                return print_r($item,true);
        }
    }
    
    function get_columns(){
        $columns = array(
			'username'    	 	=> __('User Name',MLM_PLUGIN_NAME),
            'useremail'    		=> __('User Email',MLM_PLUGIN_NAME),
            'withdrawaldate'    => __('Withdrawal Date',MLM_PLUGIN_NAME),
            'withdrawalamount'  => __('Withdrawal Amount',MLM_PLUGIN_NAME),
			'withdrawalmode'	  	=> __('Withdrawal Mode',MLM_PLUGIN_NAME),
			'withdrawalcomments'	  	=> __('Withdrawal Comments',MLM_PLUGIN_NAME),
			'paymentdetails'  	=> __('Payment Details',MLM_PLUGIN_NAME),
			//'paymentdate'  	=> __('Payment Details',MLM_PLUGIN_NAME)
			
        );
        return $columns;
    }
    
    function get_sortable_columns() {
        $sortable_columns = array(
            'username'     => array('username',false),     
            'useremail'    => array('useremail',false)
        );
        return $sortable_columns;
    }
    
    function prepare_items() {
        global $wpdb; 
		global $table_prefix;
		global $date_format;
        $per_page = 10;
        
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        
		$sql = "SELECT * FROM ".MLM_WITHDRAWAL." WHERE withdrawal_initiated= 1 AND `payment_processed`= 1";
		$rs = $wpdb->get_results($sql,ARRAY_A);
		$i = 0; 
		$ID = 1;
		$listArr = array();
		if(!empty($rs)){
			
		 	foreach($rs as $row){
			
			$sql1 = "SELECT ".MLM_USERS.".username AS uname , {$table_prefix}users.user_email AS uemail FROM {$table_prefix}users,".MLM_USERS." WHERE ".MLM_USERS.".username = {$table_prefix}users.user_login AND ".MLM_USERS.".user_id = '".$row['user_id']."'"; 
			
			$row1 = $wpdb->get_row($sql1,ARRAY_A);		
		
			$payoutDetail['memberId'] = $row['user_id'];
			
			$netPayable = $row['amount'];
			$withdrawalamount = number_format( $netPayable, 2);
			
			$withdrawalmode = $row['withdrawal_mode'];
			$withdrawalcomments=$row['withdrawal_initiated_comment'];
			$date = date_create($row['payment_processed_date']);
			$paymentdate = date_format($date, $date_format);
			
			/********************* Cheque Info *******************************/
			$cheque_no = $row['cheque_no'];
			$chdate = date_create($row['cheque_date']);
			$cheque_date = date_format($chdate, $date_format);
			$bank_name = $row['bank_name'];
			
			/********************** Bank Transfer Info ************************/
			$beneficiary= $row['beneficiary'];
			$ubank_name= $row['user_bank_name'];
			$ub_account_no= $row['user_bank_account_no'];
			$bt_code= $row['banktransfer_code'];
			
			/*************************** Other Info *****************************/
			$other= $row['other_comments'];
			
			if($withdrawalmode=='cheque'){ 
				$paymentdetail= 'Cheque No: '.$cheque_no.'<br/>Cheque Date: '.$cheque_date.'<br/>Bank Name: '.$bank_name.
					'<br/>Date: '.$paymentdate; 
			}
			elseif($withdrawalmode=='bank-transfer'){ 
				$paymentdetail= 'Benificiary: '.$beneficiary.'<br/>Bank Name: '.$ubank_name.'<br/>Account No: '
					.$ub_account_no.'<br/>Banktransfer Code: '.$bt_code.'<br/>Date: '.$paymentdate; 
			}
			elseif($withdrawalmode=='other'){ 
				$paymentdetail= 'Date: '.$paymentdate.'<br/>'.$other; 
			}
			else{ 
				$paymentdetail= 'Date: '.$paymentdate; 
			}
			
			if($withdrawalmode=='cheque'){ 
				$payment_mode = "Cheque"; 
			}
			elseif($withdrawalmode=='bank-transfer'){ 
				$payment_mode = "Bank Transfer"; 
			}
			elseif($withdrawalmode=='cash'){ 
				$payment_mode = "Cash"; 
			}
			else{ 
				$payment_mode = "Other"; 
			}
			
			$listArr[$i]['username'] = $row1['uname']; 
			$listArr[$i]['useremail'] = $row1['uemail']; 			
			
			$widate = date_create($row['withdrawal_initiated_date']);
			$listArr[$i]['withdrawaldate'] = date_format($widate, $date_format);
			$listArr[$i]['withdrawalamount'] = $withdrawalamount; 
			$listArr[$i]['withdrawalmode'] = $withdrawalmode; 			
		    $listArr[$i]['withdrawalcomments'] = $withdrawalcomments;
			$listArr[$i]['paymentdetails'] = $paymentdetail; 
			//$listArr[$i]['paymentdate'] = $paymentdate;   
			$i++;
			
			}
		}
		
        
		$data = $listArr;
		
		$listArrtitle['username'] = __('User Name', MLM_PLUGIN_NAME);
		$listArrtitle['useremail'] = __('User Email', MLM_PLUGIN_NAME);
		$listArrtitle['withdrawaldate'] = __('Withdrawal Date', MLM_PLUGIN_NAME);
		$listArrtitle['withdrawalamount'] = __('Withdrawal Amount', MLM_PLUGIN_NAME);
		$listArrtitle['withdrawalmode'] = __('Withdrawal Mode', MLM_PLUGIN_NAME);
		$listArrtitle['withdrawalcomments'] = __('Withdrawal Comment', MLM_PLUGIN_NAME);
		$listArrtitle['paymentdetails'] = __('Payment Details', MLM_PLUGIN_NAME);
		//$listArrtitle['paymentdate'] = __('Payment Date', MLM_PLUGIN_NAME);
		array_unshift($listArr,$listArrtitle);
		update_option('sucessed_withdrawal_list', $listArr);
        
		
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'withdrawaldate'; 
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc'; 
            $result = strcmp($a[$orderby], $b[$orderby]); 
            return ($order==='asc') ? $result : -$result; 
        }
        usort($data, 'usort_reorder');
        
        
        $current_page = $this->get_pagenum();
        $total_items = count($data);
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        $this->items = $data;
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  
            'per_page'    => $per_page,                     
            'total_pages' => ceil($total_items/$per_page)  
        ) );
    }
    
}

?>