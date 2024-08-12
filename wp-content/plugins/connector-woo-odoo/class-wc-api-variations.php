<?php
/**
 * WooCommerce API Product Variations Class
 * 
 * Adds a /products/variations/<id> endpoint to WooCommerce API
 *
 */
if ( !defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}
if ( !class_exists( 'WC_API_Products_Variations' ) ) {
        class WC_API_Products_Variations extends WC_API_Products {
                /**
                 * @var string $base the route base
                 */
                protected $base = '/products';
		function register_routes( $routes ) {

			$routes[ $this->base. '/variations/(?P<id>\d+)' ] = array(
				array( array( $this, 'get_variations' ), WC_API_Server::READABLE )
			);


			return $routes;
		}
 function get_variations($id,$fields=null) {
				global $wpdb;
				$attributes = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `wp_term_taxonomy` AS e INNER JOIN `wp_terms` AS u ON e.term_id = u.term_id WHERE e.term_id = %d",$id ) );
				$taxanomy = str_replace( 'pa_', '', $attributes->taxonomy );
				$attributes = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `wp_term_taxonomy` AS e INNER JOIN `wp_terms` AS u ON e.term_id = u.term_id INNER JOIN `wp_woocommerce_attribute_taxonomies` AS v ON v.attribute_name = '$taxanomy' WHERE e.term_id = %d",$id ) );	
				
									

				return $attributes;
}
		

}
}
