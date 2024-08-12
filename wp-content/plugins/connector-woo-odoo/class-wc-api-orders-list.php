<?php
/**
 * WooCommerce API Orders List Class
 * 
 * Adds a /orders/list endpoint to WooCommerce API
 *
 */
if ( !defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}
if ( !class_exists( 'WC_API_Orders_List' ) ) {
        class WC_API_Orders_List extends WC_API_Orders {
                /**
                 * @var string $base the route base
                 */
                protected $base = '/orders';
                /**
                 * Register the routes with callback for this class
                 * @param array $routes
                 * @return array
                 */
		function register_routes( $routes ) {

			# GET orders/list
			$routes[ $this->base. '/list' ] = array(array( $this, 'get_orders_list' ), WC_API_Server::READABLE 
			);


			return $routes;
		}
		/**
		 * Get all orders list
		 * @param string $fields
		 * @param array $filter
		 * @param string $status
		 * @param int $page
		 * @return array
		 */
		public function get_orders_list( $fields = null, $filter = array(), $status = null, $page = 1 ) {

			if ( ! empty( $status ) ) {
				$filter['status'] = $status;
			}

			$filter['page'] = $page;

			$query = $this->query_orders_list( $filter );

			$orders = array();

			foreach ( $query->posts as $order_id ) {

				if ( ! $this->is_readable( $order_id ) ) {
					continue;
				}

				$orders[] = current( $this->get_order_list( $order_id, $fields, $filter ) );
			}

			$this->server->add_pagination_headers( $query );
			return ($orders);
		}
/**
	 * Get the order list  for the given ID
	 * @param int $id the order ID
	 * @param array $fields
	 * @param array $filter
	 * @return array
	 */
	public function get_order_list( $id, $fields = null, $filter = array() ) {

		// ensure order ID is valid & user has permission to read
		$id = $this->validate_request( $id, $this->post_type, 'read' );

		if ( is_wp_error( $id ) ) {
			return $id;
		}

		// Get the decimal precession
		$dp         = ( isset( $filter['dp'] ) ? intval( $filter['dp'] ) : 2 );
		$order      = wc_get_order( $id );
		$order_post = get_post( $id );

		$order_data = array($order->id);


		

		return ($order_data);
	}


	protected function query_orders_list( $args ) {

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
