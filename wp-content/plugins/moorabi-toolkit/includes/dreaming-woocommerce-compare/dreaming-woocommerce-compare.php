<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'dreamingWcCompare' ) ) {
	
	class  dreamingWcCompare {
		
		private static $instance;
		
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof dreamingWcCompare ) ) {
				
				self::$instance = new dreamingWcCompare;
				self::$instance->includes();
				
			}
			
			return self::$instance;
		}
		
		
		public function includes() {
			require_once MOORABI_TOOLKIT_PATH . 'includes/dreaming-woocommerce-compare/includes/menu-scripts-styles.php';
			require_once MOORABI_TOOLKIT_PATH . 'includes/dreaming-woocommerce-compare/includes/class.dreaming-wc-helper.php';

			if ( is_admin() ) {
				require_once MOORABI_TOOLKIT_PATH . 'includes/dreaming-woocommerce-compare/includes/backend.php';
			}

			require_once MOORABI_TOOLKIT_PATH . 'includes/dreaming-woocommerce-compare/includes/class.dreaming-wc-frontend.php';
			new Dreaming_Woocompare_Frontend();
			
		}
		
	}
}

if ( ! function_exists( 'dreaming_wccp_init' ) ) {
	function dreaming_wccp_init() {
		return dreamingWcCompare::instance();
	}
	
	dreaming_wccp_init();
}