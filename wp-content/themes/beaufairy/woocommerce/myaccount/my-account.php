<?php
/**
 * My Account page
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

wc_print_notices(); ?>

<article class="module module--no-border">
    <p>
        <?php
        printf(
            __( 'Hello <strong>%1$s</strong> (not %1$s? <a href="%2$s">Sign out</a>).', 'woocommerce' ) . ' ',
            $current_user->display_name,
            wc_get_endpoint_url( 'customer-logout', '', wc_get_page_permalink( 'myaccount' ) )
        );

        printf( __( 'From your account dashboard you can view your recent orders, manage your shipping and billing addresses and <a href="%s">edit your password and account details</a>.', 'woocommerce' ),
            wc_customer_edit_account_url()
        );
        ?>
    </p>

    <br>

    <?php wc_get_template( 'myaccount/my-downloads.php' ); ?>

    <?php wc_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) ); ?>

    <?php wc_get_template( 'myaccount/my-address.php' ); ?>
</article> <!-- .post-content -->