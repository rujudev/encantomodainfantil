<?php
/**
 * Moorabi Framework setup
 *
 * @author
 * @category API
 * @package  Moorabi_Framework_Options
 * @since    1.0.0
 */
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( !class_exists( 'Moorabi_Framework_Options' ) ) {
	class Moorabi_Framework_Options
	{
		public $version = '1.0.0';

		public function __construct()
		{
			$this->define_constants();
			add_action( 'admin_bar_menu', array( $this, 'moorabi_custom_menu' ), 1000 );
			add_action( 'plugins_loaded', array( $this, 'includes' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 999 );
		}

		/**
		 * Define WC Constants.
		 */
		private function define_constants()
		{
			$this->define( 'MOORABI_FRAMEWORK_VERSION', $this->version );
			$this->define( 'MOORABI_FRAMEWORK_URI', plugin_dir_url( __FILE__ ) );
			$this->define( 'MOORABI_FRAMEWORK_THEME_PATH', get_template_directory() );
			$this->define( 'MOORABI_FRAMEWORK_PATH', plugin_dir_path( __FILE__ ) );
		}

		/**
		 * Define constant if not already set.
		 *
		 * @param string $name Constant name.
		 * @param string|bool $value Constant value.
		 */
		private function define( $name, $value )
		{
			if ( !defined( $name ) ) {
				define( $name, $value );
			}
		}

		function includes()
		{
			include_once( 'includes/core/cs-framework.php' );
			include_once( 'includes/abstracts-widget.php' );
			if ( class_exists( 'WooCommerce' ) ) {
				include_once( 'includes/woo-function.php' );
                include_once( 'includes/widgets/widget-attribute-product.php' );
			}
			/* WIDGET */
			include_once( 'includes/widgets/widget-newsletter.php' );
			include_once( 'includes/widgets/widget-socials.php' );
			include_once( 'includes/widgets/widget-post.php' );
		}

		public function moorabi_custom_menu()
		{
			global $wp_admin_bar;
			if ( !is_super_admin() || !is_admin_bar_showing() ) return;
			// Add Parent Menu
			$argsParent = array(
				'id'    => 'theme_option',
				'title' => esc_html__( 'Moorabi Options', 'moorabi-toolkit' ),
				'href'  => admin_url( 'admin.php?page=moorabi' ),
			);
			$wp_admin_bar->add_menu( $argsParent );
		}

		function is_url_exist( $url )
		{
			$ch = curl_init( $url );
			curl_setopt( $ch, CURLOPT_NOBODY, true );
			curl_exec( $ch );
			$code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
			if ( $code == 200 ) {
				$status = true;
			} else {
				$status = false;
			}
			curl_close( $ch );

			return $status;
		}

		function admin_scripts( $hook )
		{
			wp_enqueue_style( 'moorabi-awesome', MOORABI_FRAMEWORK_URI . 'assets/css/font-awesome.min.css' );
			wp_enqueue_style( 'moorabi-chosen', MOORABI_FRAMEWORK_URI . 'assets/css/chosen.min.css' );
			wp_enqueue_style( 'moorabi-themify', MOORABI_FRAMEWORK_URI . 'assets/css/themify-icons.css' );
			wp_enqueue_style( 'moorabi-backend', MOORABI_FRAMEWORK_URI . 'assets/css/backend.css' );
			/* SCRIPTS */
			wp_enqueue_script( 'moorabi-chosen', MOORABI_FRAMEWORK_URI . 'assets/js/libs/chosen.min.js', array(), null );
			wp_enqueue_script( 'moorabi-backend', MOORABI_FRAMEWORK_URI . 'assets/js/backend.js', array(), null );
			if ( $hook == 'moorabi_page_moorabi' ) {
				// ACE Editor
				wp_enqueue_style( 'cs-vendor-ace-style', MOORABI_FRAMEWORK_URI . 'includes/core/fields/ace_editor/assets/ace.css', array(), '1.0' );
				wp_enqueue_script( 'cs-vendor-ace', MOORABI_FRAMEWORK_URI . 'includes/core/fields/ace_editor/assets/ace.js', array(), false, true );
				wp_enqueue_script( 'cs-vendor-ace-mode', MOORABI_FRAMEWORK_URI . 'includes/core/fields/ace_editor/assets/mode-css.js', array(), false, true );
				wp_enqueue_script( 'cs-vendor-ace-language_tools', MOORABI_FRAMEWORK_URI . 'includes/core/fields/ace_editor/assets/ext-language_tools.js', array(), false, true );
				wp_enqueue_script( 'cs-vendor-ace-css', MOORABI_FRAMEWORK_URI . 'includes/core/fields/ace_editor/assets/css.js', array(), false, true );
				wp_enqueue_script( 'cs-vendor-ace-text', MOORABI_FRAMEWORK_URI . 'includes/core/fields/ace_editor/assets/text.js', array(), false, true );
				wp_enqueue_script( 'cs-vendor-ace-javascript', MOORABI_FRAMEWORK_URI . 'includes/core/fields/ace_editor/assets/javascript.js', array(), false, true );
				// You do not need to use a separate file if you do not like.
				wp_enqueue_script( 'cs-vendor-ace-load', MOORABI_FRAMEWORK_URI . 'includes/core/fields/ace_editor/assets/ace-load.js', array(), false, true );
			}
		}
	}

	new Moorabi_Framework_Options();
}