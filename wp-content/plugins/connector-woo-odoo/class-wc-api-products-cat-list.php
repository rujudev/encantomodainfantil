<?php
/**
 * WooCommerce API Categories List Class
 * 
 * Adds a /categories/list endpoint to WooCommerce API
 *
 */
if ( !defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}
if ( !class_exists( 'WC_API_Categories_List' ) ) {
        class WC_API_Categories_List extends WC_API_Products {
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

			# GET products/categories/list
			$routes[ $this->base. '/categories/list' ] = array(
				array( array( $this, 'get_product_categories_list' ), WC_API_Server::READABLE )
			);


			return $routes;
		}
			/**
	 * Get a listing of product categories list
	 * @since 2.2
	 * @param string|null $fields fields to limit response to
	 * @return array
	 */
	public function get_product_categories_list( $fields = null ) {
		try {
			// Permissions check
			if ( ! current_user_can( 'manage_product_terms' ) ) {
				throw new WC_API_Exception( 'woocommerce_api_user_cannot_read_product_categories', __( 'You do not have permission to read product categories', 'woocommerce' ), 401 );
			}

			$product_categories_list = array();

			$terms = get_terms( 'product_cat', array( 'hide_empty' => false, 'fields' => 'ids' ) );

			foreach ( $terms as $term_id ) {
				$product_categories[] = current( $this->get_product_category_list( $term_id, $fields ) );
			}

			return (apply_filters( 'woocommerce_api_product_categories_response', $product_categories, $terms, $fields, $this ) );
		} catch ( WC_API_Exception $e ) {
			return new WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
		}
	}

	/**
	 * Get the product category list for the given ID
	 * @since 2.2
	 * @param string $id product category term ID
	 * @param string|null $fields fields to limit response to
	 * @return array
	 */
	public function get_product_category_list( $id, $fields = null ) {
		try {
			$id = absint( $id );

			// Validate ID
			if ( empty( $id ) ) {
				throw new WC_API_Exception( 'woocommerce_api_invalid_product_category_id', __( 'Invalid product category ID', 'woocommerce' ), 400 );
			}

			// Permissions check
			if ( ! current_user_can( 'manage_product_terms' ) ) {
				throw new WC_API_Exception( 'woocommerce_api_user_cannot_read_product_categories', __( 'You do not have permission to read product categories', 'woocommerce' ), 401 );
			}

			$term = get_term( $id, 'product_cat' );

			if ( is_wp_error( $term ) || is_null( $term ) ) {
				throw new WC_API_Exception( 'woocommerce_api_invalid_product_category_id', __( 'A product category with the provided ID could not be found', 'woocommerce' ), 404 );
			}

			$term_id = intval( $term->term_id );

			$product_category_list = array($term_id);

			return $product_category_list;
		} catch ( WC_API_Exception $e ) {
			return new WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
		}
	}
							}
		



}
