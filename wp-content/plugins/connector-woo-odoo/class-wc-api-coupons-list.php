<?php
/**
 * WooCommerce API Coupons List Class
 * 
 * Adds a /coupons/list endpoint to WooCommerce API
 *
 */
if ( !defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}
if ( !class_exists( 'WC_API_Coupons_List' ) ) {
        class WC_API_Coupons_List extends WC_API_Coupons {
                /**
                 * @var string $base the route base
                 */
                protected $base = '/coupons';
                /**
                 * Register the routes with callback for this class
                 *
                 *
                 * @param array $routes
                 * @return array
                 */
		function register_routes( $routes ) {

			# GET coupons/list
			$routes[ $this->base. '/list' ] = array(array( $this, 'get_coupons_list' ), WC_API_Server::READABLE );


			return $routes;
		}
		/**
		 * Get all coupons ids
		 *
		 * @since 2.1
		 * @param string $fields
		 * @param array $filter
		 * @param int $page
		 * @return array
		 */
		public function get_coupons_list( $fields = null, $filter = array(), $page = 1 ) {

			$filter['page'] = $page;

			$query = $this->query_coupons_list( $filter );

			$coupons = array();

			foreach( $query->posts as $coupon_id ) {

				if ( ! $this->is_readable( $coupon_id ) )
					continue;

				$coupons[] = current( $this->get_coupon_list( $coupon_id, $fields ) );
			}

			$this->server->add_pagination_headers( $query );

			return ($coupons);
		}

		/**
		 * Get the coupon ids list  for the given ID
		 *
		 * @since 2.1
		 * @param int $id the coupon ID
		 * @param string $fields fields to include in response
		 * @return array|WP_Error
		 */
		public function get_coupon_list( $id, $fields = null ) {
			global $wpdb;

			$id = $this->validate_request( $id, 'shop_coupon', 'read' );

			if ( is_wp_error( $id ) )
				return $id;

			// get the coupon code
			$code = $wpdb->get_var( $wpdb->prepare( "SELECT post_title FROM $wpdb->posts WHERE id = %s AND post_type = 'shop_coupon' AND post_status = 'publish'", $id ) );

			if ( is_null( $code ) )
				return new WP_Error( 'woocommerce_api_invalid_coupon_id', __( 'Invalid coupon ID', 'woocommerce' ), array( 'status' => 404 ) );

			$coupon = new WC_Coupon( $code );

			$coupon_post = get_post( $coupon->id );

			$coupon_data = ($coupon->id);

			return array('coupon' =>apply_filters( 'woocommerce_api_coupon_response', $coupon_data, $coupon, $fields, $this->server ) );
		}
		/**
		 * Helper method to get coupon post objects
		 *
		 * @since 2.1
		 * @param array $args request arguments for filtering query
		 * @return WP_Query
		 */
		private function query_coupons_list( $args ) {

			// set base query arguments
			$query_args = array(
				'fields'      => 'ids',
				'post_type'   => 'shop_coupon',
				'post_status' => 'publish',
			);

			$query_args = $this->merge_query_args( $query_args, $args );

			return new WP_Query( $query_args );
		}

		
}



}
