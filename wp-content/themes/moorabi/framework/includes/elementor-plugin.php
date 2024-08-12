<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('Moorabi')) {
    class Moorabi
    {
        /**
         * Define valid class prefix for autoloading.
         *
         * @var  string
         */

        protected static $prefix = 'Moorabi_';
        protected static $initialized = false;

        public function __construct()
        {
            self::initialize();
            add_action('elementor/editor/before_enqueue_styles', array($this, 'editor_before_enqueue_styles'));
            add_action('elementor/preview/enqueue_styles',  array($this, 'preview_enqueue_styles'));
        }

        public static function initialize()
        {
            // Register class autoloader.
            spl_autoload_register(array(__CLASS__, 'autoload'));
            // Register necessary actions.
            add_action('moorabi_before_content', array(__CLASS__, 'preloader_html'), 1);
            if (did_action('elementor/loaded')) {
                if (self::$initialized) {
                    return;
                }
                add_action('elementor/elements/categories_registered', array(__CLASS__, 'categories_registered'));
                add_action('elementor/widgets/widgets_registered', array(__CLASS__, 'register_widgets'));
                // State that initialization completed.
                self::$initialized = true;
            }
        }

        public function editor_before_enqueue_styles()
        {
            wp_enqueue_style('elementor-admin', get_theme_file_uri('/framework/assets/admin.css'), array(), '1.0');
            wp_enqueue_style('elementor-flaticon', get_theme_file_uri('/assets/fonts/flaticon/flaticon.css'), array(), '1.0');
        }

        public function preview_enqueue_styles()
        {
            wp_enqueue_style('elementor-preview', get_theme_file_uri('/framework/assets/preview.css'), array(), '1.0');
        }

        public static function register_widgets()
        {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Moorabi_Elementor_Banner());
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Moorabi_Elementor_Blog());
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Moorabi_Elementor_Customlink());
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Moorabi_Elementor_Iconbox());
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Moorabi_Elementor_Instagram());
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Moorabi_Elementor_Title());
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Moorabi_Elementor_Newsletter());
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Moorabi_Elementor_Pinmapper());
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Moorabi_Elementor_Products());
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Moorabi_Elementor_Product_Tabs());
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Moorabi_Elementor_Socials());
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Moorabi_Elementor_Team());
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Moorabi_Elementor_Testimonial());
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Moorabi_Elementor_Verticalmenu());
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Moorabi_Elementor_Videopopup());
        }

        public static function categories_registered($elementsManager)
        {

            $elementsManager->add_category(
                'moorabi',
                [
                    'title' => esc_html__('moorabi', 'moorabi'),
                    'icon' => 'fa fa-plug',
                ]
            );
        }

        /**
         * Method to autoload class declaration file.
         *
         * @param   string $class_name Name of class to load declaration file for.
         *
         * @return  mixed
         */

        public static function autoload($class_name)
        {
            // Verify class prefix.
            if (0 !== strpos($class_name, self::$prefix)) {
                return false;
            }
            // Generate file path from class name.
            $base = get_template_directory() . '/framework/includes/';
            $path = strtolower(str_replace('_', '/', substr($class_name, strlen(self::$prefix))));
            // Check if class file exists.
            $standard = $path . '.php';
            $alternative = $path . '/' . current(array_slice(explode('/', str_replace('\\', '/', $path)), -1)) . '.php';
            while (true) {
                // Check if file exists in standard path.
                if (file_exists($base . $standard)) {
                    $exists = $standard;
                    break;
                }
                // Check if file exists in alternative path.
                if (file_exists($base . $alternative)) {
                    $exists = $alternative;
                    break;
                }
                // If there is no more alternative file, quit the loop.
                if (false === strrpos($standard, '/') || 0 === strrpos($standard, '/')) {
                    break;
                }
                // Generate more alternative files.
                $standard = preg_replace('#/([^/]+)$#', '-\\1', $standard);
                $alternative = implode('/', array_slice(explode('/', str_replace('\\', '/', $standard)), 0, -1)) . '/' . substr(current(array_slice(explode('/', str_replace('\\', '/', $standard)), -1)), 0, -4) . '/' . current(array_slice(explode('/', str_replace('\\', '/', $standard)), -1));
            }
            // Include class declaration file if exists.
            if (isset($exists)) {
                include_once $base . $exists;
            }
            return false;
        }

    }
    new Moorabi();
}