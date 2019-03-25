<?php
/**
 * Cart Page
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.3.8
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

wc_print_notices() ?>

<form action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post">
    <table class="cart-table">
        <thead>
            <tr>
                <th><?php _e( 'Item', 'woocommerce' ); ?></th>
                <th><?php _e( 'Quantity', 'woocommerce' ); ?></th>
                <th class="hidden-xs"><?php _e( 'Price', 'woocommerce' ); ?></th>
                <th><?php _e( 'Total', 'woocommerce' ); ?></th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>

            <?php
            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                $product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
            ?>
                <tr>
                    <td>
                        <?php
                            $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( array(100, 100) ), $cart_item, $cart_item_key );

                            if ( ! $_product->is_visible() ) {
                                echo $thumbnail;
                            } else {
                                printf( '<a href="%s" class="product-link hidden-xs">%s</a>', esc_url( $_product->get_permalink( $cart_item ) ), $thumbnail );
                            }

                            if ( ! $_product->is_visible() ) {
                                echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ) . '&nbsp;';
                            } else {
                                echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<div><a href="%s">%s </a></div>', esc_url( $_product->get_permalink( $cart_item ) ), $_product->get_title() ), $cart_item, $cart_item_key );
                            }

                            // Meta data
                            echo WC()->cart->get_item_data( $cart_item );

                            // Backorder notification
                            if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>';
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            if ( $_product->is_sold_individually() ) {
                                $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                            } else {
                                $product_quantity = woocommerce_quantity_input( array(
                                    'input_name'  => "cart[{$cart_item_key}][qty]",
                                    'input_value' => $cart_item['quantity'],
                                    'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
                                    'min_value'   => '0'
                                ), $_product, false );
                            }

                            echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
                        ?>
                    </td>
                    <td class="hidden-xs">
                        <?php
                            echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                        ?>
                    </td>
                    <td>
                        <?php
                            echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
                        ?>
                    </td>
                    <td>
                        <?php
                            echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="remove-link" title="%s"><span class="fa fa-times-circle-o"></span></a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) ), $cart_item_key );
                        ?>
                    </td>
                </tr>
                <?php
                }
            }
            ?>
        </tbody>
    </table>

    <div class="cart-footer row">
        <div class="cart-buttons col-md-7 col-sm-8">
            <button type="submit" class="text-uppercase cart-buttons__item btn btn-lg bf-btn bf-btn--violet" name="update_cart" value="<?php _e( 'Update Cart', 'woocommerce' ); ?>"><?php _e( 'Update Cart', 'woocommerce' ); ?></button>

            <?php do_action( 'woocommerce_cart_actions' ); ?>

            <?php wp_nonce_field( 'woocommerce-cart' ); ?>

            <a href="<?php echo get_permalink( woocommerce_get_page_id( 'shop' ) ) ?>" class="cart-buttons__item btn btn-lg bf-btn bf-btn--plain">Continue Shopping</a>

        </div> <!-- .cart-buttons -->
        <div class="form cart-total col-md-5 col-sm-4">
            <?php woocommerce_cart_totals() ?>
        </div> <!-- .cart-total -->
    </div> <!-- .row -->

</form>