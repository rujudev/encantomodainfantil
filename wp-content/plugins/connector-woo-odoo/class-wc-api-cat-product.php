<?php
/**
 * WooCommerce API Products Category Details Class
 * 
 * Adds a /category/details endpoint to WooCommerce API
 *
 */
if ( !defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}
if ( !class_exists( 'WC_API_Products_Cat_List' ) ) {
        class WC_API_Products_Cat_List extends WC_API_Products {
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

			# GET products/id/category/details
			$routes[ $this->base. '/(?P<id>\d+)/category/details' ] = array(
				array( array( $this, 'get_category_in_product_details' ), WC_API_Server::READABLE )
			);


			return $routes;
		}
		public function get_category_in_product_details( $id, $fields = null ) {
			$terms = get_the_terms($id,'product_cat',array('fields'=>'ids'));
			$product_cat  = array();
			try{
			if ( ! $terms ) {
				throw new WC_API_Exception( 'woocommerce_api_product_category', __( 'Product has no categories', 'woocommerce' ), 401 );
			}


				foreach ( $terms as $term ) {

					$product_cat[] = array(
						'id'          => intval( $term->term_id ),
						'name'        => $term->name,
						'slug'        => $term->slug,
						'parent'      => $term->parent,
						'description' => $term->description,
						'count'       => intval( $term->count ),
					);
				}

			return $terms;


			}catch ( WC_API_Exception $e ) {
			return new WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
		}
}
		

}
}
