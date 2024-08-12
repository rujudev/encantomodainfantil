<?php
/**
 * Plugin Name: Connector Woo Odoo By Tech-Receptives
 * Description: This plugin extends WooCommerce Web Services by adding some additional endpoints which are required for integration with Odoo.
 * Version: 2.4.8
 * Author: Tech Receptives Solutions Pvt. Ltd.
 * Author URI:  https://www.techreceptives.com/
 */
class WC_API_Odoo_Loader{

        
        public function init(){
                // load after WooCommerce API has loaded
                // so that we can extend the orders endpoint class
                add_action( 'woocommerce_api_loaded', array( $this, 'load' ) );
        }
        
        public function load(){
		// include the class file
		require_once plugin_dir_path( __FILE__ ).'class-wc-api-products-list.php';
		require_once plugin_dir_path( __FILE__ ).'class-wc-api-coupons-list.php';
		require_once plugin_dir_path( __FILE__ ).'class-wc-api-customers-list.php';
		require_once plugin_dir_path( __FILE__ ).'class-wc-api-orders-list.php';
		require_once plugin_dir_path( __FILE__ ).'class-wc-api-cat-product.php';
		require_once plugin_dir_path( __FILE__ ).'class-wc-api-cat-parent.php';
		require_once plugin_dir_path( __FILE__ ).'class-wc-api-products-cat-list.php';
		require_once plugin_dir_path( __FILE__ ).'class-wc-api-product-details.php';
		require_once plugin_dir_path( __FILE__ ).'class-wc-api-woo-settings.php';
		require_once plugin_dir_path( __FILE__ ).'class-wc-api-variations.php';
		require_once plugin_dir_path( __FILE__ ).'class-wc-api-order-details.php';
		require_once plugin_dir_path( __FILE__ ).'class-wc-api-currencies.php';
		// filter the array of default api classes
		add_filter( 'woocommerce_api_classes', array( $this, 'register' ) );
        }
        
        public function register( $api_classes=array() ){
                
        // add our class to the existing API endpoints
        array_push( $api_classes, 'WC_API_Products_List' );
		array_push( $api_classes, 'WC_API_Coupons_List' );
        array_push( $api_classes, 'WC_API_Customers_List' );
		array_push( $api_classes, 'WC_API_Orders_List' );
		array_push( $api_classes, 'WC_API_Products_Cat_List' );
		array_push( $api_classes, 'WC_API_Category_Parent_List' );
		array_push( $api_classes, 'WC_API_Categories_List' );
		array_push( $api_classes, 'WC_API_Products_Details' );
		array_push( $api_classes, 'WC_Woo_Settings' );
		array_push( $api_classes, 'WC_API_Products_Variations' );
		array_push( $api_classes, 'WC_API_Orders_Details' );
		array_push( $api_classes, 'WC_API_Currencies');
		
                return $api_classes;
        }
	


}
//instantiate and initialise our loader class
$wc_api_recent_odoo_loader = new WC_API_Odoo_Loader();
$wc_api_recent_odoo_loader->init();
