<?php
    /**
     * Add AJAX handler
     * @since 1.0.0
     */

    function mpm_send_mail($to="", $subject="", $content="") {
        add_filter( 'wp_mail_content_type', 'mpm_set_html_content_type' );
        $status = wp_mail($to, $subject, $content);
        remove_filter(  'wp_mail_content_type', 'mpm_set_html_content_type' );
    }

    function generate_GUID() {
        if (function_exists('com_create_guid') === true)
        {
            return trim(com_create_guid(), '');
        }

        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }

    function mpm_set_html_content_type() {
        return 'text/html';
    }

    function mpm_generate_coupon($email="") {
        $discount = get_option( 'Discount' );
        $mail_subject = get_option( 'Coupon Mail Subject' );
        $mail_content = get_option( 'Coupon Mail Content' );

        $coupon_code = generate_GUID();
        $amount = isset($discount) ? $discount : 0;
        $discount_type = 'percent';
        $coupon = array(
            'post_title' => $coupon_code,
            'post_content' => '',
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'shop_coupon'
        );
        $new_coupon_id = wp_insert_post( $coupon );
        // Update coupon meta's
        update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
        update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
        update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
        update_post_meta( $new_coupon_id, 'product_ids', '' );
        update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
        update_post_meta( $new_coupon_id, 'usage_limit', '1' );
        update_post_meta( $new_coupon_id, 'expiry_date', '' );
        update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
        update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
        update_post_meta( $new_coupon_id, 'customer_emails ', $email );
        // Initialize parameters
        $to = $email;
        $subject = $mail_subject;
        $content = str_replace("{coupon}", $coupon_code, $mail_content);
        $content = wpautop($content);

        // Send Mail
        mpm_send_mail($to, $subject, $content);
        // Add data to database
        mpm_add_to_database($email, $coupon_code);
    }

    function mpm_add_to_database($email="", $coupon="") {
        global $wpdb;
        // Insert email to newsletter table
        $wpdb->insert(
            'newsletter_emails',
            array(
                'email' => $email,
                'coupon' => $coupon
            ),
            array(
                '%s',
                '%d'
            )
        );
    }

    function mpm_check_email_availability($email="") {
        global $wpdb;
        // Get row from table
        $result = $wpdb->get_row("SELECT FROM newsletter_emails WHERE `email` = '$email'");
        if ($result === null) {
            return true;
        } else {
            return false;
        }
    }

    function mpm_check_coupon($coupon=""){
        global $wpdb;
        // Let's be sure that this this plugin is the one generated the code
        // Get row from table
        $result = $wpdb->get_row("SELECT FROM newsletter_emails WHERE `coupon` = '$coupon'");
        if ($result === null) {
            // No it's not
            return false;
        } else {
            return true;
        }
    }

    function mpm_delete_coupon($coupon="") {
        $coupon_data = new WC_Coupon($coupon);
        if (!empty($coupon_data->id)) {
            wp_delete_post($coupon_data->id);
        }
    }

    function mpm_woo_order_completed($order_id) {
        $order = new WC_Order( $order_id);
        $coupons = $order->get_used_coupons();

        // foreach($coupons as $coupon) {
        //     if ( mpm_check_coupon( $coupon ) ) {
        //         // Delete coupon after use
        //         mpm_delete_coupon( $coupon );
        //     }
        // }
    }
    add_action( 'woocommerce_order_status_completed', 'mpm_woo_order_completed');
    add_action( 'woocommerce_order_status_on-hold', 'mpm_woo_order_completed' );

    function mpm_mailchimp_submit() {
        // Get values stored in mp admin
        $api_key = get_option( 'API Key' );
        $list_id = get_option( 'Default List ID' );
        // Get post data from request
        $email = $_POST['email'];
        $first_name = $_POST["first_name"];
        $last_name = $_POST["last_name"];

        $merge_vars = array('FNAME'=>$first_name, 'LNAME'=>$last_name);
        $api = new MCAPI($api_key);
        $result = $api->listSubscribe($list_id, $email, $merge_vars, 'html', true, true, true, true);

        if ($api->errorCode) {
            echo "Something went wrong. Please try again.";
        } else {
            echo "Subscription successfull, you have been added to the mailing list!";

            if (mpm_check_email_availability($email)) {
                // Create a coupon and send to user's email
                mpm_generate_coupon($email);
            } else {
                // Dont send coupon email if this email subscribed before
            }
        }

        die();
    }
    add_action('wp_ajax_mpm_mailchimp_submit', 'mpm_mailchimp_submit');
    add_action('wp_ajax_nopriv_mpm_mailchimp_submit', 'mpm_mailchimp_submit');
?>