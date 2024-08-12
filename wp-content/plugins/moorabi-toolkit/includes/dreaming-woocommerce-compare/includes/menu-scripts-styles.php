<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('dreamingWccpMenuScriptsStyles')) {
    class dreamingWccpMenuScriptsStyles
    {

        public function __construct()
        {
            add_action('admin_menu', array($this, 'add_plugin_page'), 999);
            add_action('admin_enqueue_scripts', array($this, 'admin_scripts'), 1000);
        }

        /**
         * Add options page
         */
        public function add_plugin_page()
        {
            // add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
            $menu_args = array(
                array(
                    'page_title' => esc_html__('Products Compare', 'moorabi-toolkit'),
                    'menu_title' => esc_html__('Products Compare', 'moorabi-toolkit'),
                    'cap' => 'manage_options',
                    'menu_slug' => 'dreaming-wccp',
                    'function' => array($this, 'menu_page_callback'),
                    'parrent' => 'moorabi_menu',
                )
            );
            foreach ($menu_args as $menu_arg) {
                if ($menu_arg['parrent'] == '') {
                    add_menu_page($menu_arg['page_title'], $menu_arg['menu_title'], $menu_arg['cap'], $menu_arg['menu_slug'], $menu_arg['function'], $menu_arg['icon'], $menu_arg['position']);
                } else {
                    add_submenu_page($menu_arg['parrent'], $menu_arg['page_title'], $menu_arg['menu_title'], $menu_arg['cap'], $menu_arg['menu_slug'], $menu_arg['function']);
                }
            }
        }

        public function menu_page_callback()
        {
            $page = isset($_REQUEST['page']) ? Dreaming_Woocompare_Helper::clean($_REQUEST['page']) : '';
            if (trim($page) != '') {
                $file_path = MOORABI_TOOLKIT_PATH . 'includes/dreaming-woocommerce-compare/includes/admin-pages/' . $page . '.php';
                if (file_exists($file_path)) {
                    require_once MOORABI_TOOLKIT_PATH . 'includes/dreaming-woocommerce-compare/includes/admin-pages/' . $page . '.php';
                }
            }
        }

        function admin_scripts($hook)
        {

            wp_enqueue_style('jquery-ui', MOORABI_TOOLKIT_URL . 'includes/dreaming-woocommerce-compare/assets/css/jquery-ui.css');
            wp_enqueue_script('jquery-ui-tabs');
            wp_enqueue_script('jquery-ui-sortable');

            wp_enqueue_style('dreaming-wccp-backend', MOORABI_TOOLKIT_URL . 'includes/dreaming-woocommerce-compare/assets/css/backend.css');

            wp_enqueue_script('dreaming-wccp-backend', MOORABI_TOOLKIT_URL . 'includes/dreaming-woocommerce-compare/assets/js/backend.js', array(), null);
            $import_settings_url = dreaming_wccp_import_settings_action_link();
            wp_localize_script('dreaming-wccp-backend', 'dreaming_wccp',
                array(
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'security' => wp_create_nonce('dreaming_wccp_backend_nonce'),
                    'import_settings_url' => $import_settings_url,
                    'text' => array(
                        'confirm_import_settings' => esc_html__('All current settings will be overwritten and CAN NOT BE UNDONE! Are you sure you want to import settings?', 'moorabi-toolkit')
                    )
                )
            );
        }
    }

    new dreamingWccpMenuScriptsStyles();
}