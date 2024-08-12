<?php
/**
 * Moorabi Megamenu
 *
 * @author   
 * @category 
 * @package  Moorabi_Megamenu
 * @since    1.0.1
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('Moorabi_Megamenu')) {
    class Moorabi_Megamenu
    {
        /*
         * PARAMS
         * */
        public static $meta_key  = '_moorabi_menu_settings';
        public static $post_type = 'moorabi_menu';
        public static $defaults  = array(
            'enable_mega'     => 0,
            'hide_title'      => 0,
            'disable_link'    => 0,
            'menu_width'      => '',
            'menu_icon'       => '',
            'menu_content_id' => 0,
            'menu_bg'         => 0,
            'menu_content'    => '',
            'mega_responsive' => '',
            'icon_image'      => '',
            'label_image'     => '',
            'bg_position'     => 'center',
        );
        /*
         * INSTANCE CLASS
         * */
        private static $instance;

        public static function instance()
        {
            if (!isset(self::$instance) && !(self::$instance instanceof Moorabi_Megamenu)) {
                self::$instance = new Moorabi_Megamenu;
                self::$instance->setup_constants();
                self::$instance->includes();
                add_action('admin_enqueue_scripts', array(self::$instance, 'admin_scripts'), 999);
                add_action('wp_enqueue_scripts', array(self::$instance, 'megamenu_scripts'), 999);
            }

            return self::$instance;
        }

        public function setup_constants()
        {
            // Plugin Folder Path.
            if (!defined('MOORABI_MEGAMENU_DIR')) {
                define('MOORABI_MEGAMENU_DIR', plugin_dir_path(__FILE__));
            }
            // Plugin Folder URL.
            if (!defined('MOORABI_MEGAMENU_URL')) {
                define('MOORABI_MEGAMENU_URL', plugin_dir_url(__FILE__));
            }
        }

        public function includes()
        {
            require_once MOORABI_MEGAMENU_DIR.'includes/walker_nav_menu_edit_custom.php';
            require_once MOORABI_MEGAMENU_DIR.'includes/megamenu-settings.php';
        }

        function request_param($param, $default = null)
        {
            return isset($GLOBALS['post']->$param) ? $GLOBALS['post']->$param : $default;
        }

        public function admin_scripts($hook_suffix)
        {
            if (($hook_suffix === 'post-new.php' || $hook_suffix === 'post.php')) {
                if ($this->request_param('post_type') === self::$post_type) {
                    remove_all_actions('admin_notices');
                    remove_all_actions('all_admin_notices');
                    remove_all_actions('user_admin_notices');
                    remove_all_actions('network_admin_notices');
                    wp_enqueue_style('content-megamenu', MOORABI_MEGAMENU_URL.'assets/css/content-megamenu.css');
                }
            }
            if ($hook_suffix == 'nav-menus.php') {
                wp_enqueue_media();
                wp_enqueue_style(
                    'megamenu-backend',
                    MOORABI_MEGAMENU_URL.'assets/admin/megamenu.min.css'
                );
                wp_enqueue_script(
                    'megamenu-backend',
                    MOORABI_MEGAMENU_URL.'assets/admin/megamenu.min.js',
                    array('jquery', 'wp-util'),
                    '1.0'
                );
            }
        }

        public function megamenu_scripts()
        {
            wp_enqueue_style('megamenu-frontend',
                MOORABI_MEGAMENU_URL.'assets/css/megamenu.css'
            );
            wp_enqueue_script('megamenu-frontend',
                MOORABI_MEGAMENU_URL.'assets/js/megamenu.js',
                array('jquery'), false, true
            );

            $css   = '';
            $args  = array(
                'posts_per_page' => -1,
                'post_type'      => self::$post_type,
                'post_status'    => 'publish',
                'fields'         => 'ids',
            );
            $posts = get_posts($args);
            if ($posts) {
                foreach ($posts as $post_id) {
                    if (!MOORABI_TOOLKIT()->is_elementor($post_id)) {
                        $css .= get_post_meta($post_id, '_wpb_post_custom_css', true);
                        $css .= get_post_meta($post_id, '_wpb_shortcodes_custom_css', true);
                        $css .= get_post_meta($post_id, '_Moorabi_Shortcode_custom_css', true);
                        $css .= get_post_meta($post_id, '_Moorabi_VC_Shortcode_Custom_Css', true);
                    }
                }
            }
            if ($css != '') {
                wp_add_inline_style('megamenu-frontend', preg_replace('/\s+/', ' ', $css));
            }
            wp_localize_script('megamenu-frontend', 'moorabi_ajax_megamenu', array(
                    'ajaxurl'       => admin_url('admin-ajax.php'),
                    'security'      => wp_create_nonce('moorabi_ajax_megamenu'),
                    'load_menu'     => MOORABI_TOOLKIT()->get_config('mobile_menu'),
                    'delay'         => MOORABI_TOOLKIT()->get_config('mobile_delay'),
                    'resize'        => MOORABI_TOOLKIT()->get_config('megamenu_resize'),
                    'load_megamenu' => false,
                )
            );
        }
    }
}
if (!function_exists('Moorabi_Megamenu')) {
    function Moorabi_Megamenu()
    {
        return Moorabi_Megamenu::instance();
    }

    Moorabi_Megamenu();
}