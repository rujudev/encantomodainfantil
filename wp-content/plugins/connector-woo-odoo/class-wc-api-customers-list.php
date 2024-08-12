<?php
/**
 * WooCommerce API Customers List
 * 
 * Adds a /customers/list endpoint to WooCommerce API
 *
 */
if ( !defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}
if ( !class_exists( 'WC_API_Customers_List' ) ) {
        class WC_API_Customers_List extends WC_API_Customers {
		/**
		 * @var string $base the route base
		 */
		protected $base = '/customers';
		/**
		 * Register the routes with callback for this class
		 * @param array $routes
		 * @return array
		 */
		function register_routes( $routes ) {

			# GET coupons/list
			$routes[ $this->base. '/list' ] = array(array( $this, 'get_customers_list' ), WC_API_Server::READABLE );


			return $routes;
		}
		/**
		 * Get all customers list
		 *
		 * @since 2.2
		 * @param array $fields
		 * @param array $filter
		 * @param int $page
		 * @return array
		 */
		public function get_customers_list( $fields = null, $filter = array(), $page = 1 ) {

			$filter['page'] = $page;

			$query = $this->query_customers_list( $filter );

			$customers = array();

			foreach( $query->get_results() as $user_id ) {

				if ( ! $this->is_readable( $user_id ) )
					continue;

				$customers[] = current( $this->get_customer_list( $user_id, $fields ) );
			}

			$this->server->add_pagination_headers( $query );

			return ($customers );
		}

		/**
		 * Get the customer list of ids for the given ID
		 *
		 * @since 2.1
		 * @param int $id the customer ID
		 * @param string $fields
		 * @return array
		 */
		public function get_customer_list( $id, $fields = null ) {
			global $wpdb;

			$id = $this->validate_request( $id, 'customer', 'read' );

			if ( is_wp_error( $id ) )
				return $id;

			$customer = new WP_User( $id );

			// get info about user's last order
			$last_order = $wpdb->get_row( "SELECT id, post_date_gmt
							FROM $wpdb->posts AS posts
							LEFT JOIN {$wpdb->postmeta} AS meta on posts.ID = meta.post_id
							WHERE meta.meta_key = '_customer_user'
							AND   meta.meta_value = {$customer->ID}
							AND   posts.post_type = 'shop_order'
							AND   posts.post_status IN ( '" . implode( "','", array_keys( wc_get_order_statuses() ) ) . "' )
						" );

			$customer_data = ($customer->ID);

			return array( 'customer' => apply_filters( 'woocommerce_api_customer_response', $customer_data, $customer, $fields, $this->server ) );
		}
		private function query_customers_list( $args = array() ) {

			// default users per page
			$users_per_page = get_option( 'posts_per_page' );

			// set base query arguments
			$query_args = array(
				'fields'  => 'ID',
				'role'    => 'customer',
				'orderby' => 'registered',
				'number'  => $users_per_page,
			);

			// search
			if ( ! empty( $args['q'] ) ) {
				$query_args['search'] = $args['q'];
			}

			// limit number of users returned
			if ( ! empty( $args['limit'] ) ) {

				$query_args['number'] = absint( $args['limit'] );

				$users_per_page = absint( $args['limit'] );
			}

			// page
			$page = ( isset( $args['page'] ) ) ? absint( $args['page'] ) : 1;

			// offset
			if ( ! empty( $args['offset'] ) ) {
				$query_args['offset'] = absint( $args['offset'] );
			} else {
				$query_args['offset'] = $users_per_page * ( $page - 1 );
			}

			// created date
			if ( ! empty( $args['created_at_min'] ) ) {
				$this->created_at_min = $this->server->parse_datetime( $args['created_at_min'] );
			}

			if ( ! empty( $args['created_at_max'] ) ) {
				$this->created_at_max = $this->server->parse_datetime( $args['created_at_max'] );
			}

			$query = new WP_User_Query( $query_args );

			// helper members for pagination headers
			$query->total_pages = ceil( $query->get_total() / $users_per_page );
			$query->page = $page;

			return $query;
		}

		
}



}
