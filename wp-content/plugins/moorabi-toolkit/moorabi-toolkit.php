<?php
/**
 * Plugin Name: Moorabi Toolkit
 * Plugin URI:
 * Description: The Moorabi Toolkit For WordPress Theme WooCommerce Shop.
 * Author: Moorabi
 * Author URI: 
 * Version: 1.0.1
 * Text Domain: moorabi-toolkit
 */
// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;
if ( !class_exists( 'Moorabi_Toolkit' ) ) {
	class  Moorabi_Toolkit
	{
		/**
		 * @var Moorabi_Toolkit The one true Moorabi_Toolkit
		 * @since 1.0
		 */
		private static $instance;

		public static function instance()
		{
			if ( !isset( self::$instance ) && !( self::$instance instanceof Moorabi_Toolkit ) ) {
				self::$instance = new Moorabi_Toolkit;
				self::$instance->setup_constants();
				add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
				self::$instance->includes();
				add_action( 'after_setup_theme', array( self::$instance, 'after_setup_theme' ) );
                add_action('upload_mimes', array(self::$instance, 'moorabi_toolkit_add_file_types_to_uploads'));
			}

			return self::$instance;
		}

		public function after_setup_theme()
		{
			require_once MOORABI_TOOLKIT_PATH . 'includes/admin/live-search/live-search.php';
			require_once MOORABI_TOOLKIT_PATH . 'includes/mapper/addon.php';
			require_once MOORABI_TOOLKIT_PATH . 'includes/footer-builder/footer-builder.php';
			require_once MOORABI_TOOLKIT_PATH . 'includes/megamenu/megamenu.php';
            if ( class_exists( 'WooCommerce' ) ) {
                require_once MOORABI_TOOLKIT_PATH . 'includes/dreaming-woocommerce-compare/dreaming-woocommerce-compare.php';
            }
		}

		public function setup_constants()
		{
			// Plugin version.
			if ( !defined( 'MOORABI_TOOLKIT_VERSION' ) ) {
				define( 'MOORABI_TOOLKIT_VERSION', '1.0.1' );
			}
			// Plugin Folder Path.
			if ( !defined( 'MOORABI_TOOLKIT_PATH' ) ) {
				define( 'MOORABI_TOOLKIT_PATH', plugin_dir_path( __FILE__ ) );
			}
			// Plugin Folder URL.
			if ( !defined( 'MOORABI_TOOLKIT_URL' ) ) {
				define( 'MOORABI_TOOLKIT_URL', plugin_dir_url( __FILE__ ) );
			}
		}

		public function includes()
		{
			require_once MOORABI_TOOLKIT_PATH . 'includes/admin/welcome.php';
			require_once MOORABI_TOOLKIT_PATH . 'includes/frontend/framework.php';
		}

		public function load_textdomain()
		{
			load_plugin_textdomain( 'moorabi-toolkit', false, MOORABI_TOOLKIT_URL . 'languages' );
		}

        function moorabi_toolkit_add_file_types_to_uploads($file_types)
        {
            $new_filetypes = array();
            $new_filetypes['svg'] = 'image/svg+xml';
            $file_types = array_merge($file_types, $new_filetypes);

            return $file_types;
        }

        public $default = array(
            'footer'           => true,
            'megamenu'         => true,
            'mobile'           => true,
            'product_brand'    => true,
            'post_like'        => true,
            'add_to_cart'      => true,
            'popup_notice'     => true,
            'editor_term'      => true,
            'auto_update'      => true,
            'lazyload'         => false,
            'crop'             => false,
            'photo_editor'     => false,
            'question_answers' => false,
            'remote_source'    => false,
            'demo_mode'        => false,
            'fa4_support'      => false,
            'snow_effect'      => false,
            'placeholder'      => '',
            'fontawesome'      => 'fa4',
            'snow_text'        => '❅',
            'snow_color'       => '#fff',
            'snow_background'  => 'transparent',
            'snow_limit'       => 60,
            'snow_speed'       => 30,
            'snow_size'        => array(
                'width'  => 20,
                'height' => 30,
                'unit'   => 'px',
            ),
            'mobile_delay'     => 0,
            'mobile_menu'      => 'click',
            'clear_cache'      => '',
            'megamenu_resize'  => 'tablet',
        );
        public static function get_config($key = '', $default = false)
        {
            $args   = array(
                'fa4_support' => false,
                'fontawesome' => 'fa4',
            );
            $key    = trim($key);
            $config = get_option('moorabi_addon_settings');
            $config = wp_parse_args($config, $args);

            if (empty($key)) {
                return $config;
            }

            if (!empty($config[$key])) {
                return $config[$key];
            }

            return $default;
        }
        public function is_request($type)
        {
            switch ($type) {
                case 'admin':
                    return is_admin();
                case 'ajax':
                    return function_exists('wp_doing_ajax') ? wp_doing_ajax() : defined('DOING_AJAX');
                case 'cron':
                    return defined('DOING_CRON');
                case 'frontend':
                    return !is_admin() && !defined('DOING_CRON');
                default:
                    return false;
            }
        }
        /**
         * What is support elementor or not?
         *
         * @param  string  $type  post_type or id.
         *
         * @return bool
         */
        public function is_support_elementor($type)
        {
            $post_type   = is_numeric($type) ? get_post_type($type) : $type;
            $cpt_support = get_option('elementor_cpt_support', ['page', 'post']);

            if (class_exists('Elementor\Plugin') && in_array($post_type, $cpt_support)) {
                return true;
            }

            return false;
        }

        /**
         * What is elementor or not?
         *
         * @param  int  $post_id  post_type or id.
         *
         * @return bool
         */
        public function is_elementor($post_id)
        {
            if (class_exists('Elementor\Plugin') && $this->is_support_elementor($post_id)) {
                if (get_post_meta($post_id, '_elementor_edit_mode', true)) {
                    return true;
                }
            }

            return false;
        }

        /**
         * is WPBackery editor?
         *
         * @return bool
         */
        public function is_vc_editor()
        {
            if ($get_referer = wp_get_referer()) {
                if (strpos(parse_url($get_referer, PHP_URL_QUERY), 'vc_action=vc_inline') !== false) {
                    return true;
                }
            }

            return false;
        }

        /**
         * is Elementor editor?
         *
         * @return bool
         */
        public function is_elementor_editor()
        {
            if (class_exists('Elementor\Plugin')) {
                if (Elementor\Plugin::$instance->preview->is_preview_mode() || Elementor\Plugin::$instance->editor->is_edit_mode()) {
                    return true;
                }
            }

            return false;
        }
	}
}
if ( !function_exists( 'MOORABI_TOOLKIT' ) ) {
	function MOORABI_TOOLKIT()
	{
		return Moorabi_Toolkit::instance();
	}

	MOORABI_TOOLKIT();
	add_action( 'plugins_loaded', 'MOORABI_TOOLKIT', 10 );
}
//check load mobile
if ( !function_exists( 'moorabi_toolkit_is_mobile' ) ) {
	function moorabi_toolkit_is_mobile() {
		if ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$is_mobile = false;
		} elseif ( strpos( $_SERVER['HTTP_USER_AGENT'], 'Mobile' ) !== false // many mobile devices (all iPhone, iPad, etc.)
		           || strpos( $_SERVER['HTTP_USER_AGENT'], 'Android' ) !== false
		           || strpos( $_SERVER['HTTP_USER_AGENT'], 'Silk/' ) !== false
		           || strpos( $_SERVER['HTTP_USER_AGENT'], 'Kindle' ) !== false
		           || strpos( $_SERVER['HTTP_USER_AGENT'], 'BlackBerry' ) !== false
		           || strpos( $_SERVER['HTTP_USER_AGENT'], 'Opera Mini' ) !== false
		           || strpos( $_SERVER['HTTP_USER_AGENT'], 'Opera Mobi' ) !== false ) {
			$is_mobile = true;
		} else {
			$is_mobile = false;
		}
		
		return apply_filters( 'wp_is_mobile', $is_mobile );
	}
}