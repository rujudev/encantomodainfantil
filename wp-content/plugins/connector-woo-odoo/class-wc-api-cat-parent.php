<?php
/**
 * WooCommerce API Parent Category Details
 * 
 * Adds a /categories/(?P<id>\d+)/parent endpoint to WooCommerce API
 *
 */
if ( !defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}
if ( !class_exists( 'WC_API_Category_Parent_List' ) ) {
        class WC_API_Category_Parent_List extends WC_API_Products {
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

			# GET /category/<id>/parent
			$routes[ $this->base. '/categories/(?P<id>\d+)/parent' ] = array(
				array( array( $this, 'get_category_parent' ), WC_API_Server::READABLE )
			);
			
			
			return $routes;
		}
		function get_parent_terms($term) {
		    if ($term->parent > 0) {
			$term = get_term_by("id", $term->parent, "product_cat");
			return $term;
		    }
		    else 
			return null;
}
		
		public function get_category_parent( $id, $fields = null ) {
		try {
			$id = absint( $id );



			$term = get_term( $id, 'product_cat' );
			$cat = $this->get_parent_terms($term);

			if ( ! $cat ) {
				throw new WC_API_Exception( 'woocommerce_api_product_category_parent', __( 'Category has no parent', 'woocommerce' ), 401 );
			}
			$parent_cat = array(
				'id'          => intval( $term->term_id ),
				'name'        => $term->name,
				'slug'        => $term->slug,
				'parent'      => array('id'=>$term->parent,'name'=>$cat->name,'slug'=>$cat->slug,'parent'=>$cat->parent),
				'description' => $term->description,
				'count'       => intval( $term->count )
			);

			return $cat;
		} catch ( WC_API_Exception $e ) {
			return new WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
		}
	}
		

}
}
