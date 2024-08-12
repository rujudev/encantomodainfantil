<?php
if (!class_exists('Moorabi_PluginLoad')) {
    class Moorabi_PluginLoad
    {
        public $plugins = array();
        public $config = array();

        public function __construct()
        {
            $this->plugins();
            $this->config();
            if (!class_exists('TGM_Plugin_Activation')) {
                return;
            }
            if (function_exists('tgmpa')) {
                tgmpa($this->plugins, $this->config);
            }
        }

        public function plugins()
        {
            $this->plugins = array(
                array(
                    'name' => 'Moorabi Toolkit',
                    'slug' => 'moorabi-toolkit',
                    'source' => get_template_directory() . '/framework/plugins/moorabi-toolkit.zip',
                    'version' => '1.0.1',
                    'required' => true,
                    'force_activation' => false,
                    'force_deactivation' => false,
                    'external_url' => '',
                    'image' => '',
                ),
                array(
                    'name' => 'Revolution Slider',
                    'slug' => 'revslider',
                    'source' => get_template_directory() . '/framework/plugins/revslider.zip',
                    'required' => true,
                    'version' => '',
                    'force_activation' => false,
                    'force_deactivation' => false,
                    'external_url' => '',
                    'image' => '',
                ),
                array(
                    'name' => 'Elementor Website Builder',
                    'slug' => 'elementor',
                    'required' => true,
                    'image' => '',
                ),
                array(
                    'name' => 'WooCommerce',
                    'slug' => 'woocommerce',
                    'required' => true,
                    'image' => '',
                ),
                array(
                    'name' => 'YITH WooCommerce Frequently Bought Together',
                    'slug' => 'yith-woocommerce-frequently-bought-together',
                    'required' => true,
                    'image' => '',
                ),
                array(
                    'name' => 'YITH WooCommerce Wishlist',
                    'slug' => 'yith-woocommerce-wishlist',
                    'required' => true,
                    'image' => '',
                ),
                array(
                    'name' => 'YITH WooCommerce Quick View',
                    'slug' => 'yith-woocommerce-quick-view',
                    'required' => true,
                    'image' => '',
                ),
                array(
                    'name' => 'Variation Swatches for WooCommerce',
                    'slug' => 'woo-variation-swatches',
                    'required' => true,
                    'image' => '',
                ),
                array(
                    'name' => 'Contact Form 7',
                    'slug' => 'contact-form-7',
                    'required' => true,
                    'image' => '',
                ),
                array(
                    'name' => 'MC4WP: Mailchimp for WordPress',
                    'slug' => 'mailchimp-for-wp',
                    'required' => true,
                    'image' => '',
                ),
                array(
                    'name' => 'Classic Editor',
                    'slug' => 'classic-editor',
                    'required' => true,
                    'image' => '',
                ),
                array(
                    'name' => 'Classic Widgets',
                    'slug' => 'classic-widgets',
                    'required' => true,
                    'image' => '',
                ),
                array(
                    'name' => 'All-in-One WP Migration',
                    'slug' => 'all-in-one-wp-migration',
                    'required' => true,
                    'image' => '',
                )
            );
        }

        public function config()
        {
            $this->config = array(
                'id' => 'moorabi',
                'default_path' => '',
                'menu' => 'moorabi-install-plugins',
                'parent_slug' => 'themes.php',
                'capability' => 'edit_theme_options',
                'has_notices' => true,
                'dismissable' => true,
                'dismiss_msg' => '',
                'is_automatic' => true,
                'message' => '',
            );
        }
    }
}
if (!function_exists('Moorabi_PluginLoad')) {
    function Moorabi_PluginLoad()
    {
        new  Moorabi_PluginLoad();
    }
}
add_action('tgmpa_register', 'Moorabi_PluginLoad');