<?php
    class MPMailchimpAdminPage {
        private $options;

        public function __construct() {
            add_action( 'admin_menu', array( $this, 'mp_plugin_page' ) );
            add_action( 'admin_init', array( $this, 'mp_admin_init' ) );
            add_action( 'wp_ajax_mp_update_admin', array( $this, 'mp_update_admin' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'mp_admin_script' ) );
        }

        public function mp_plugin_page() {
            add_theme_page(
                'MP Mailchimp',
                'MP Mailchimp',
                'manage_options',
                'mp-setting-admin',
                array( $this, 'mp_admin_form')
            );
        }

        public function mp_admin_form() {
            $this->options = array(
                "api_key" => get_option( 'API Key' ),
                "default_list_id" => get_option( 'Default List ID' ),
                "string_close" => get_option( 'Close' ),
                "string_message" => get_option( 'Message' ),
                "string_coupon_subject" => get_option( 'Subject' ),
                "string_coupon_content" => get_option( 'Content' ),
                "coupon_discount" => get_option( 'Discount' ),
                "coupon_mail_subject" => get_option( 'Coupon Mail Subject' ),
                "coupon_mail_content" => get_option( 'Coupon Mail Content' )
            );

            ?>
                <div class="wrap">
                    <h2>MP Mailchimp</h2>
                    <form name="mpm-settings-form" method="post" action="options.php">
                    <?php
                        // Print MP Option input group
                        settings_fields( 'mp_option_group' );   
                        do_settings_sections( 'mp-setting-admin' );
                    ?>
                    <div class="message">
                        <i class="fa"></i>
                        <p></p>
                    </div>
                    <?php
                        submit_button(); 
                    ?>
                    </form>
                </div>
            <?php    
        }

        public function mp_admin_script() {
            wp_enqueue_script( 'mpm-script-admin', plugin_dir_url( __FILE__ ) . 'js/script.js' );
            wp_localize_script( 'mpm-script-admin', 'ajax_object',
                array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
        }

        public function mp_admin_init() {
            register_setting(
                'mp_option_group',
                'mp_options',
                array($this, 'sanitize')
            );
            // Add API and LIST ID input fields
            add_settings_section(
                'mp_settings_section',
                'Mailchimp Settings',
                array($this, 'section_callback'),
                'mp-setting-admin'
            );
            add_settings_field(
                'api_key',
                'API Key',
                array($this, 'api_key_callback'),
                'mp-setting-admin',
                'mp_settings_section'
            );
            add_settings_field(
                'default_list_id',
                'Default List ID',
                array($this, 'default_list_id_callback'),
                'mp-setting-admin',
                'mp_settings_section'
            );

            // Add String input fields
            add_settings_section(
                'mp_strings_section',
                'Mailchimp Strings',
                array($this, 'strings_callback'),
                'mp-setting-admin'
            );
            // String Close
            add_settings_field(
                'string_close',
                'Close',
                array($this, 'string_close_callback'),
                'mp-setting-admin',
                'mp_strings_section'
            );
            // String Message
            add_settings_field(
                'string_message',
                'Message',
                array($this, 'string_message_callback'),
                'mp-setting-admin',
                'mp_strings_section'
            );

            // Add Coupon input fields
            add_settings_section(
                'mp_coupon_section',
                'Coupon Settings',
                array($this, 'coupon_callback'),
                'mp-setting-admin'
            );
            // Data Discount
            add_settings_field(
                'string_coupon_discount',
                'Discount',
                array($this, 'coupon_discount_callback'),
                'mp-setting-admin',
                'mp_coupon_section'
            );
            // Coupon Mail Subject
            add_settings_field(
                'string_coupon_subject',
                'Coupon Mail Subject',
                array($this, 'coupon_mail_subject_callback'),
                'mp-setting-admin',
                'mp_coupon_section'
            );
            // Coupon Mail Content
            add_settings_field(
                'string_coupon_content',
                'Coupon Mail Content',
                array($this, 'coupon_mail_content_callback'),
                'mp-setting-admin',
                'mp_coupon_section'
            );
        }

        public function mp_update_admin() {
            $api_key = $_POST[ 'mp_api_key' ];
            $list_id = $_POST[ 'mp_list_id' ];
            $string_close = $_POST[ 'mp_string_close' ];
            $string_message = $_POST[ 'mp_string_message' ];
            $coupon_discount = $_POST[ 'mp_coupon_discount' ];
            $coupon_mail_subject = $_POST[ 'mp_coupon_mail_subject' ];
            $coupon_mail_content = $_POST[ 'mp_coupon_mail_content' ];

            if ( isset( $api_key ) ) 
                update_option( 'API Key', $api_key );

            if ( isset( $list_id ) ) 
                update_option( 'Default List ID', $list_id );

            if ( isset( $string_close) )
                update_option( 'Close', $string_close);

            if ( isset( $string_message) )
                update_option( 'Message', $string_message);

            if ( isset ( $coupon_discount ) )
                update_option( 'Discount', $coupon_discount);

            if ( isset ( $coupon_mail_subject ) )
                update_option( 'Coupon Mail Subject', $coupon_mail_subject);

            if ( isset ( $coupon_mail_content ) )
                update_option( 'Coupon Mail Content', $coupon_mail_content);

            die();
        }

        public function sanitize( $input ) {
            return $input;
        }

        public function section_callback() {
            print 'Enter your Mailchimp Settings Below';
        }

        public function strings_callback() {
            print 'You can update mailchimp strings below';
        }

        public function coupon_callback() {
            print 'Update coupon discount and mailing strings here';
        }

        /*
            String Close Field
         */
        public function string_close_callback() {
            printf(
                '<input style="min-width: 300px;" type="text" id="string_close" name="mp_string_close" value="%s" />',
                isset( $this->options['string_close'] ) ? esc_attr( $this->options['string_close'] ) : ''
            );
        }

        /*
            String Message Field
         */
        public function string_message_callback() {
            printf(
                '<textarea style="min-width: 300px;" id="string_message" name="mp_string_message">%s</textarea>',
                isset( $this->options['string_message'] ) ? esc_attr( $this->options['string_message'] ) : ''
            );   
        }

        /*
            Coupon Discount Field
         */
        public function coupon_discount_callback() {
            printf(
                '<input style="width: 50px;" type="number" min="0" max="50" id="coupon_discount" name="mp_coupon_discount" value="%s" />%%',
                isset( $this->options['coupon_discount'] ) ? esc_attr( $this->options['coupon_discount'] ) : ''
            );
        }

        /*
            String Coupon Subject
         */
        public function coupon_mail_subject_callback() {
            printf(
                '<textarea style="min-width: 300px;" id="coupon_mail_subject" name="mp_coupon_mail_subject">%s</textarea>',
                isset( $this->options['coupon_mail_subject'] ) ? esc_attr( $this->options['coupon_mail_subject'] ) : ''
            );   
        }

        /*
            String Coupon Content
         */
        public function coupon_mail_content_callback() {
            printf(
                  '<textarea style="min-width: 300px;" id="coupon_mail_content" name="mp_coupon_mail_content">%s</textarea>',
                isset( $this->options['coupon_mail_content'] ) ? esc_attr( $this->options['coupon_mail_content'] ) : ''
            );
            print '<p>Put {coupon} on where you want to display the coupon code.</p>';
        }

        public function api_key_callback() {
            printf(
                '<input style="min-width: 300px;" type="text" id="api_key" name="mp_api_key" value="%s" />',
                isset( $this->options['api_key'] ) ? esc_attr( $this->options['api_key'] ) : ''
            );
        }

        public function default_list_id_callback() {
            printf(
                '<input style="min-width: 300px;" type="text" id="list_id" name="mp_list_id" value="%s" />',
                isset( $this->options['default_list_id'] ) ? esc_attr( $this->options['default_list_id'] ) : ''
            );
        }
     }

    if ( is_admin() )
        $mp_mailchimp_settings_page = new MPMailchimpAdminPage();
?>