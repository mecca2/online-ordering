<?php
/**
 * My Orders
 *
 * @deprecated  2.6.0 this template file is no longer used. My Account shortcode uses orders.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$my_orders_columns = apply_filters( 'woocommerce_my_account_my_orders_columns', array(
	'order-number'  => __( 'Order', 'woocommerce' ),
	'order-date'    => __( 'Date', 'woocommerce' ),
	'order-status'  => __( 'Status', 'woocommerce' ),
	'order-total'   => __( 'Total', 'woocommerce' ),
	'order-actions' => '&nbsp;',
) );

$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
	'numberposts' => $order_count,
	'meta_key'    => '_customer_user',
	'meta_value'  => get_current_user_id(),
	'post_type'   => wc_get_order_types( 'view-orders' ),
	'post_status' => array_keys( wc_get_order_statuses() ),
) ) );

if ( $customer_orders ) : ?>

	<!--<table class="shop_table shop_table_responsive my_account_orders">

		<thead>
			<tr>
				<?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
					<th class="<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
				<?php endforeach; ?>
			</tr>
		</thead>

		<tbody>-->
		<h2>Recent Orders </h2>
			<?php foreach ( $customer_orders as $customer_order ) :
				$order      = wc_get_order( $customer_order );
				$item_count = $order->get_item_count();
				?>

				<!--<tr class="order">-->
					<?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
						<!--<td class="<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">-->
							<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
								<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

							<?php elseif ( 'order-number' === $column_id ) : ?>
								<div class="order-name">Order: <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
									<?php 
									if (get_post_meta( $order->id, 'order_name', true ) != ""){
										echo get_post_meta( $order->id, 'order_name', true ); 
									}
									else {
										echo _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number();
									} ?>
								</a></div>
							<?php elseif ( 'order-date' === $column_id ) : ?>
								<div class="order-date"<time datetime="<?php echo esc_attr( date( 'Y-m-d', $order->order_date ) ); ?>" title="<?php echo esc_attr( $order->order_date ); ?>">Date: <?php echo  date_i18n( 'F j, Y g:i A', strtotime($order->order_date) ) ; ?></time></div>

							<?php elseif ( 'order-status' === $column_id ) : ?>
								<div class="order-status">Status: <?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></div>

							<?php elseif ( 'order-total' === $column_id ) : ?>
								<div class="order-total">
								<?php
								/* translators: 1: formatted order total 2: total order items */
								printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count );
								?>
								</div>

							<?php elseif ( 'order-actions' === $column_id ) : ?>
								<div class="order-actions">
								<?php
									$actions = array(
										'pay'    => array(
											'url'  => $order->get_checkout_payment_url(),
											'name' => __( 'Pay', 'woocommerce' ),
										),
										'view'   => array(
											'url'  => $order->get_view_order_url(),
											'name' => __( 'View', 'woocommerce' ),
										),
										'cancel' => array(
											'url'  => $order->get_cancel_order_url( wc_get_page_permalink( 'myaccount' ) ),
											'name' => __( 'Cancel', 'woocommerce' ),
										),
									);

									if ( ! $order->needs_payment() ) {
										unset( $actions['pay'] );
									}

									if ( ! in_array( $order->get_status(), apply_filters( 'woocommerce_valid_order_statuses_for_cancel', array( 'pending', 'failed' ), $order ) ) ) {
										unset( $actions['cancel'] );
									}

									if ( $actions = apply_filters( 'woocommerce_my_account_my_orders_actions', $actions, $order ) ) {
										foreach ( $actions as $key => $action ) {
											echo '<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
										}
									}
								?>
								</div>
							<?php endif; ?>
						<!--</td>-->
					<?php endforeach; ?>
				<!--</tr>-->
			<?php endforeach; ?><!--
		</tbody>
	</table>-->
<?php endif; ?>