<?php
/**
 * WooCommerce API Recent Orders Class
 * 
 * Adds a /products/list endpoint to WooCommerce API
 *
 */
if ( !defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}
if ( !class_exists( 'WC_API_Products_List' ) ) {
        class WC_API_Products_List extends WC_API_Products {
                /**
                 * @var string $base the route base
                 */
                protected $base = '/products';
                /**
                 * Register the routes with callback for this class
                 * @param array $routes
                 * @return array
                 */
		function register_routes( $routes ) {

			# GET products/list
			$routes[ $this->base. '/list' ] = array(
				array( array( $this, 'get_products_list' ), WC_API_Server::READABLE )
			);


			return $routes;
		}
		function get_products_list( $fields = null, $type = null, $filter = array(), $page = 1 ) {

							if ( ! empty( $type ) ) {
							    $filter['type'] = $type;
							}

							$filter['page'] = $page;

							$query = $this->query_products_list( $filter );

							$products_list = array();

							foreach ( $query->posts as $product_id ) {

							    if ( ! $this->is_readable( $product_id ) ) {
								continue;
							    }

							    $products_list[] = current( $this->get_product_list( $product_id, $fields ) );
							}

							$this->server->add_pagination_headers( $query );

							return ($products_list);
					    }
		/**
	     	* Get the product list for the given ID
	     	*
		* @param int $id the product ID
		* @param string $fields
		* @return array
		*/
		function get_product_list( $id, $fields = null ) {

			$id = $this->validate_request( $id, 'product', 'read' );

			if ( is_wp_error( $id ) ) {
			    return $id;
						}

		$product = wc_get_product( $id );

		// add data that applies to every product type
		$product_data = $this->get_product_list_data( $product );


		// add the parent product data to an individual variation
		if ( $product->is_type( 'variation' ) ) {
		    
		    $product_data['parent'] = $this->get_product_list_data( $product->parent );
							}

		return array( 'product' => apply_filters( 'woocommerce_api_product_response', $product_data, $product, $fields, $this->server ) );
	    }
		/**
	     	* Get standard product data that applies to every product type
	     	*
	     	* @param WC_Product $product
	     	* @return WC_Product
	     	*/
		function get_product_list_data( $product ) {

			return((int) $product->is_type( 'variation' ) ? $product->get_variation_id() : $product->id);
							}
		private function query_products_list( $args ) {

			// set base query arguments
			$query_args = array(
				'fields'      => 'ids',
				'post_type'   => 'product',
				'post_status' => 'publish',
				'meta_query'  => array(),
			);

			if ( ! empty( $args['type'] ) ) {

				$types = explode( ',', $args['type'] );

				$query_args['tax_query'] = array(
					array(
						'taxonomy' => 'product_type',
						'field'    => 'slug',
						'terms'    => $types,
					),
				);

				unset( $args['type'] );
			}

			$query_args = $this->merge_query_args( $query_args, $args );

			return new WP_Query( $query_args );
		}

}



}
