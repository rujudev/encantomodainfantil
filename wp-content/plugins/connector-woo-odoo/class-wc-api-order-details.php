<?php
/**
 * WooCommerce API Orders Details Class
 *
 * Handles requests to the /orders/details, /orders/details/<id> endpoint
 *
 * @author      WooThemes
 * @category    API
 * @package     WooCommerce/API
 * @since       2.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( !class_exists( 'WC_API_Orders_Details' ) ) {
class WC_API_Orders_Details extends WC_API_Orders {

	/** @var string $base the route base */
	protected $base = '/orders';

	/** @var string $post_type the custom post type */
	protected $post_type = 'shop_order';

	public function register_routes( $routes ) {

		# /orders/details
		$routes[ $this->base . '/details' ] = array(
			array( array( $this, 'get_orders' ),     WC_API_Server::READABLE ),
		);


		#  /orders/details/<id>
		$routes[ $this->base . '/details/(?P<id>\d+)' ] = array(
			array( array( $this, 'get_order' ),  WC_API_Server::READABLE ),
		);
		# /orders/details/status
		$routes[ $this->base . '/details/status/list' ] = array(
			array( array( $this, 'get_all_order_status' ),    WC_API_Server::READABLE ),
		);
		$routes[ $this->base . '/details/status/pending' ] = array(
			array( array( $this, 'get_pending_status' ),    WC_API_Server::READABLE ),
		);
		$routes[ $this->base . '/details/status/processing' ] = array(
			array( array( $this, 'get_processing_status' ),    WC_API_Server::READABLE ),
		);
		$routes[ $this->base . '/details/status/on-hold' ] = array(
			array( array( $this, 'get_on_hold_status' ),    WC_API_Server::READABLE ),
		);
		$routes[ $this->base . '/details/status/completed' ] = array(
			array( array( $this, 'get_completed_status' ),    WC_API_Server::READABLE ),
		);
		$routes[ $this->base . '/details/status/cancelled' ] = array(
			array( array( $this, 'get_cancelled_status' ),    WC_API_Server::READABLE ),
		);
		$routes[ $this->base . '/details/status/refunded' ] = array(
			array( array( $this, 'get_refunded_status' ),    WC_API_Server::READABLE ),
		);
		$routes[ $this->base . '/details/status/failed' ] = array(
			array( array( $this, 'get_failed_status' ),    WC_API_Server::READABLE ),
		);

		return $routes;
	}
	public function get_all_order_status() {

		$all_order_status = array();

		foreach ( wc_get_order_statuses() as $slug => $name ) {
			$all_order_status[] = str_replace( 'wc-', '', $slug );
		}
		

		return apply_filters( 'woocommerce_api_all_order_status_response', $all_order_status, $this  );
	}
	public function get_processing_status() {

		$processing_order_status = array();

		foreach ( wc_get_order_statuses() as $slug => $name ) {
			$processing_order_status[str_replace( 'wc-', '', $slug )] = $name;
		}
		

		return array('name'=>apply_filters('woocommerce_api_pending_status_response', $processing_order_status['processing'], $this  ));
	}
	public function get_pending_status() {

		$pending_order_status = array();

		foreach ( wc_get_order_statuses() as $slug => $name ) {
			$pending_order_status[str_replace( 'wc-', '', $slug )] = $name;
		}
		

		return array('name'=>apply_filters('woocommerce_api_pending_status_response', $pending_order_status['pending'], $this  ));
	}
	public function get_on_hold_status() {

		$on_hold_order_status = array();

		foreach ( wc_get_order_statuses() as $slug => $name ) {
			$on_hold_order_status[str_replace( 'wc-', '', $slug )] = $name;
		}
		

		return array('name'=>apply_filters('woocommerce_api_on_hold_status_response', $on_hold_order_status['on-hold'], $this  ));
	}
	public function get_completed_status() {

		$completed_order_status = array();

		foreach ( wc_get_order_statuses() as $slug => $name ) {
			$completed_order_status[str_replace( 'wc-', '', $slug )] = $name;
		}
		

		return array('name'=>apply_filters('woocommerce_api_completed_status_response', $completed_order_status['completed'], $this  ));
	}
	public function get_cancelled_status() {

		$cancelled_order_status = array();

		foreach ( wc_get_order_statuses() as $slug => $name ) {
			$cancelled_order_status[str_replace( 'wc-', '', $slug )] = $name;
		}
		

		return array('name'=>apply_filters('woocommerce_api_cancelled_status_response', $cancelled_order_status['cancelled'], $this  ));
	}
	public function get_refunded_status() {

		$refunded_order_status = array();

		foreach ( wc_get_order_statuses() as $slug => $name ) {
			$refunded_order_status[str_replace( 'wc-', '', $slug )] = $name;
		}
		

		return array('name'=>apply_filters('woocommerce_api_refunded_status_response', $refunded_order_status['refunded'], $this  ));
	}
	public function get_failed_status() {

		$failed_order_status = array();

		foreach ( wc_get_order_statuses() as $slug => $name ) {
			$failed_order_status[str_replace( 'wc-', '', $slug )] = $name;
		}
		

		return array('name'=>apply_filters('woocommerce_api_failed_status_response', $failed_order_status['failed'], $this  ));
	}


	public function get_order( $id, $fields = null, $filter = array() ) {

		// ensure order ID is valid & user has permission to read
		$id = $this->validate_request( $id, $this->post_type, 'read' );

		if ( is_wp_error( $id ) ) {
			return $id;
		}

		// Get the decimal precession
		$dp         = ( isset( $filter['dp'] ) ? intval( $filter['dp'] ) : 2 );
		$order      = wc_get_order( $id );
		$order_post = get_post( $id );

		$order_data = array(
			'id'                        => $order->id,
			'order_number'              => $order->get_order_number(),
			'created_at'                => $this->server->format_datetime( $order_post->post_date_gmt ),
			'updated_at'                => $this->server->format_datetime( $order_post->post_modified_gmt ),
			'completed_at'              => $this->server->format_datetime( $order->completed_date, true ),
			'status'                    => $order->get_status(),
			'currency'                  => $order->get_order_currency(),
			'total'                     => wc_format_decimal( $order->get_total(), $dp ),
			'subtotal'                  => wc_format_decimal( $order->get_subtotal(), $dp ),
			'total_line_items_quantity' => $order->get_item_count(),
			'total_tax'                 => wc_format_decimal( $order->get_total_tax(), $dp ),
			'total_shipping'            => wc_format_decimal( $order->get_total_shipping(), $dp ),
			'cart_tax'                  => wc_format_decimal( $order->get_cart_tax(), $dp ),
			'shipping_tax'              => wc_format_decimal( $order->get_shipping_tax(), $dp ),
			'total_discount'            => wc_format_decimal( $order->get_total_discount(), $dp ),
			'shipping_methods'          => $order->get_shipping_method(),
			'payment_details' => array(
				'method_id'    => $order->payment_method,
				'method_title' => $order->payment_method_title,
				'paid'         => isset( $order->paid_date ),
			),
			'billing_address' => array(
				'first_name' => $order->billing_first_name,
				'last_name'  => $order->billing_last_name,
				'company'    => $order->billing_company,
				'address_1'  => $order->billing_address_1,
				'address_2'  => $order->billing_address_2,
				'city'       => $order->billing_city,
				'state'      => $order->billing_state,
				'postcode'   => $order->billing_postcode,
				'country'    => $order->billing_country,
				'email'      => $order->billing_email,
				'phone'      => $order->billing_phone,
			),
			'shipping_address' => array(
				'first_name' => $order->shipping_first_name,
				'last_name'  => $order->shipping_last_name,
				'company'    => $order->shipping_company,
				'address_1'  => $order->shipping_address_1,
				'address_2'  => $order->shipping_address_2,
				'city'       => $order->shipping_city,
				'state'      => $order->shipping_state,
				'postcode'   => $order->shipping_postcode,
				'country'    => $order->shipping_country,
			),
			'note'                      => $order->customer_note,
			'customer_ip'               => $order->customer_ip_address,
			'customer_user_agent'       => $order->customer_user_agent,
			'customer_id'               => $order->get_user_id(),
			'view_order_url'            => $order->get_view_order_url(),
			'line_items'                => array(),
			'shipping_lines'            => array(),
			'tax_lines'                 => array(),
			'fee_lines'                 => array(),
			'coupon_lines'              => array(),
		);

		// add line items
		foreach ( $order->get_items() as $item_id => $item ) {

			$product     = $order->get_product_from_item( $item );
			$product_id  = null;
			$product_sku = null;

			// Check if the product exists.
			if ( is_object( $product ) ) {
				$product_id  = ( isset( $product->variation_id ) ) ? $product->variation_id : $product->id;
				$product_sku = $product->get_sku();
			}

			$meta = new WC_Order_Item_Meta( $item, $product );

			$item_meta = array();

			$hideprefix = ( isset( $filter['all_item_meta'] ) && $filter['all_item_meta'] === 'true' ) ? null : '_';

			foreach ( $meta->get_formatted( $hideprefix ) as $meta_key => $formatted_meta ) {
				$item_meta[] = array(
					'key' => $meta_key,
					'label' => $formatted_meta['label'],
					'value' => $formatted_meta['value'],
				);
			}

			$order_data['line_items'][] = array(
				'id'           => $item_id,
				'subtotal'     => wc_format_decimal( $order->get_line_subtotal( $item, false, false ), $dp ),
				'subtotal_tax' => wc_format_decimal( $item['line_subtotal_tax'], $dp ),
				'total'        => wc_format_decimal( $order->get_line_total( $item, false, false ), $dp ),
				'total_tax'    => wc_format_decimal( $item['line_tax'], $dp ),
				'price'        => wc_format_decimal( $order->get_item_total( $item, false, false ), $dp ),
				'quantity'     => wc_stock_amount( $item['qty'] ),
				'tax_class'    => ( ! empty( $item['tax_class'] ) ) ? $item['tax_class'] : null,
				'name'         => $item['name'],
				'product_id'   => $product_id,
				'sku'          => $product_sku,
				'meta'         => $item_meta,
				'parent_id'=> (isset($product->parent->id)) ? $product->parent->id : null,
			);
		}

		// add shipping
		foreach ( $order->get_shipping_methods() as $shipping_item_id => $shipping_item ) {

			$order_data['shipping_lines'][] = array(
				'id'           => $shipping_item_id,
				'method_id'    => $shipping_item['method_id'],
				'method_title' => $shipping_item['name'],
				'total'        => wc_format_decimal( $shipping_item['cost'], $dp ),
			);
		}

		// add taxes
		foreach ( $order->get_tax_totals() as $tax_code => $tax ) {

			$order_data['tax_lines'][] = array(
				'id'       => $tax->id,
				'rate_id'  => $tax->rate_id,
				'code'     => $tax_code,
				'title'    => $tax->label,
				'total'    => wc_format_decimal( $tax->amount, $dp ),
				'compound' => (bool) $tax->is_compound,
			);
		}

		// add fees
		foreach ( $order->get_fees() as $fee_item_id => $fee_item ) {

			$order_data['fee_lines'][] = array(
				'id'        => $fee_item_id,
				'title'     => $fee_item['name'],
				'tax_class' => ( ! empty( $fee_item['tax_class'] ) ) ? $fee_item['tax_class'] : null,
				'total'     => wc_format_decimal( $order->get_line_total( $fee_item ), $dp ),
				'total_tax' => wc_format_decimal( $order->get_line_tax( $fee_item ), $dp ),
			);
		}

		// add coupons
		foreach ( $order->get_items( 'coupon' ) as $coupon_item_id => $coupon_item ) {

			$order_data['coupon_lines'][] = array(
				'id'     => $coupon_item_id,
				'code'   => $coupon_item['name'],
				'amount' => wc_format_decimal( $coupon_item['discount_amount'], $dp ),
			);
		}

		return array( 'order' => apply_filters( 'woocommerce_api_order_response', $order_data, $order, $fields, $this->server ) );
	}




	protected function query_orders_details( $args ) {

		// set base query arguments
		$query_args = array(
			'fields'      => 'ids',
			'post_type'   => $this->post_type,
			'post_status' => array_keys( wc_get_order_statuses() )
		);

		// add status argument
		if ( ! empty( $args['status'] ) ) {

			$statuses                  = 'wc-' . str_replace( ',', ',wc-', $args['status'] );
			$statuses                  = explode( ',', $statuses );
			$query_args['post_status'] = $statuses;

			unset( $args['status'] );

		}

		$query_args = $this->merge_query_args( $query_args, $args );

		return new WP_Query( $query_args );
	}

	

}
}
