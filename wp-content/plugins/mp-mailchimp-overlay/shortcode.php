<?php
    /**
     * Add Form Shortcode
     * @since 1.0.0
     */
    function form_shortcode() {
        $string_close = get_option( 'Close' );
        $string_message = get_option( 'Message');
        ?>
        <div class="mpm-subscribe-overlay">
            <div class="wrapper">
                <p id="close"><?php echo $string_close; ?></p>
                <p class="message"><?php echo $string_message; ?></p>
                <form class="mpm-subscribe-form" name="mpm-subscribe-form" action="#" method="post">
                    <div class="overlay"></div>
                    <div class="field mpm-form-field">
                        <i class="fa fa-user"></i>
                        <input type="text" name="mpm-first-name" tabindex="1" class="fist-name" id="mpm-first-name" required/>
                        <label for="mpm-first-name" class="field-label req"><?php _e('First Name','mpm-mailchimp');?></label>
                    </div>
                    <div class="field mpm-form-field">
                        <i class="fa fa-user"></i>
                        <input type="text" name="mpm-last-name" tabindex="1" class="last-name" id="mpm-last-name" required/>
                        <label for="mpm-last-name" class="field-label req"><?php _e('Last Name','mpm-mailchimp');?></label>
                    </div>
                    <div class="field mpm-form-field">
                        <i class="fa fa-envelope"></i>
                        <input type="email" name="mpm-email" tabindex="1" class="email" id="mpm-email" required/>
                        <label for="mpm-email" class="field-label req"><?php _e('Email Address','mpm-mailchimp');?></label>
                    </div>
                    <div class="button-container">
                        <button type="submit" name="submit">
                            <i class="fa fa-location-arrow"></i>
                            <p><?php _e('Subscribe', 'mpm-mailchimp'); ?></p>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
    add_shortcode( 'mpm_mailchimp', 'form_shortcode');
?>