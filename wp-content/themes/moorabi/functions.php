<?php
if (!isset($content_width)) {
    $content_width = 900;
}
if (!class_exists('Moorabi_Functions')) {
    class Moorabi_Functions
    {
        /**
         * @var Moorabi_Functions The one true Moorabi_Functions
         * @since 1.0
         */
        private static $instance;

        public static function instance()
        {
            if (!isset(self::$instance) && !(self::$instance instanceof Moorabi_Functions)) {
                self::$instance = new Moorabi_Functions;
            }
            add_action('after_setup_theme', array(self::$instance, 'moorabi_setup'));
            add_action('widgets_init', array(self::$instance, 'moorabi_widgets_init'));
            add_action('wp_enqueue_scripts', array(self::$instance, 'moorabi_enqueue_scripts'), 99);
            add_filter('get_default_comment_status', array(
                self::$instance,
                'moorabi_open_default_comments_for_page'
            ), 10, 3);
            self::moorabi_includes();

            return self::$instance;
        }

        public function moorabi_setup()
        {
            load_theme_textdomain('moorabi', get_template_directory() . '/languages');
            add_theme_support('automatic-feed-links');
            add_theme_support('title-tag');
            add_theme_support('post-thumbnails');
            add_theme_support('custom-background');
            add_theme_support('customize-selective-refresh-widgets');
            /*This theme uses wp_nav_menu() in two locations.*/
            register_nav_menus(array(
                    'primary' => esc_html__('Primary Menu', 'moorabi'),
                    'vertical_menu' => esc_html__('Vertical Menu', 'moorabi'),
                )
            );
            add_theme_support('html5',
                array(
                    'search-form',
                    'comment-form',
                    'comment-list',
                    'gallery',
                    'caption',
                )
            );
            add_theme_support('post-formats',
                array(
                    'image',
                    'video',
                    'quote',
                    'link',
                    'gallery',
                    'audio',
                )
            );
            /*Support WooCommerce*/
            add_theme_support('woocommerce');
            add_theme_support('wc-product-gallery-lightbox');
            add_theme_support('wc-product-gallery-slider');
            add_theme_support('wc-product-gallery-zoom');
        }

        /**
         * Register widget area.
         *
         * @since moorabi 1.0
         *
         * @link  https://codex.wordpress.org/Function_Reference/register_sidebar
         */
        function moorabi_widgets_init()
        {
            register_sidebar(
                array(
                    'name' => esc_html__('Widget Blog', 'moorabi'),
                    'id' => 'widget-area',
                    'description' => esc_html__('Add widgets here to appear in your blog sidebar.', 'moorabi'),
                    'before_widget' => '<div id="%1$s" class="widget %2$s">',
                    'after_widget' => '</div>',
                    'before_title' => '<h2 class="widgettitle">',
                    'after_title' => '<span class="arrow"></span></h2>',
                )
            );
            register_sidebar(array(
                    'name' => esc_html__('Widget Shop', 'moorabi'),
                    'id' => 'widget-shop',
                    'description' => esc_html__('Add widgets here to appear in your shop sidebar.', 'moorabi'),
                    'before_widget' => '<div id="%1$s" class="widget %2$s">',
                    'after_widget' => '</div>',
                    'before_title' => '<h2 class="widgettitle">',
                    'after_title' => '<span class="arrow"></span></h2>',
                )
            );
            register_sidebar(array(
                    'name' => esc_html__('Widget Product', 'moorabi'),
                    'id' => 'widget-product',
                    'description' => esc_html__('Add widgets here to appear in your single product sidebar.', 'moorabi'),
                    'before_widget' => '<div id="%1$s" class="widget %2$s">',
                    'after_widget' => '</div>',
                    'before_title' => '<h2 class="widgettitle">',
                    'after_title' => '<span class="arrow"></span></h2>',
                )
            );
        }

        /**
         * Register custom fonts.
         */
        function moorabi_fonts_url()
        {
            /**
             * Translators: If there are characters in your language that are not
             * supported by Montserrat, translate this to 'off'. Do not translate
             * into your own language.
             */
            $moorabi_enable_typography = $this->moorabi_get_option('moorabi_enable_typography');
            $moorabi_typography_group = $this->moorabi_get_option('typography_group');
            $settings = get_option('wpb_js_google_fonts_subsets');
            $font_families = array();
            if ($moorabi_enable_typography == 1 && !empty($moorabi_typography_group)) {
                foreach ($moorabi_typography_group as $item) {
                    $font_families[] = str_replace(' ', '+', $item['moorabi_typography_font_family']['family']);
                }
            }
            $font_families[] = 'Work Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900';
            $font_families[] = 'Baloo 2:wght@400;500;600;700;800';
            $font_families_str = '';
            $i = 0;
            foreach ($font_families as $font_family) {
                $i++;
                $pre_key = $i > 1 ? '&family=' : '';
                $font_families_str .= $pre_key . urlencode($font_family);
            }
            $query_args = array(
                'family' => $font_families_str,
            );
            if (!empty($settings)) {
                $query_args['subset'] = implode(',', $settings);
            }
            $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css2');

            return esc_url_raw($fonts_url);
        }

        /**
         * Enqueue scripts and styles.
         *
         * @since moorabi 1.0
         */
        function moorabi_enqueue_scripts()
        {
            wp_dequeue_style('woocommerce_prettyPhoto_css');
            wp_dequeue_style('yith-wcwl-font-awesome');
            wp_dequeue_style('yith-quick-view');

            // Add custom fonts, used in the main stylesheet.
            wp_enqueue_style('moorabi-fonts', self::moorabi_fonts_url(), array(), null);
            /* Theme stylesheet. */
            wp_enqueue_style('animate-css');
            wp_enqueue_style('flaticon', get_theme_file_uri('/assets/fonts/flaticon/flaticon.css'), array(), '1.0');
            wp_enqueue_style('font-awesome', get_theme_file_uri('/assets/css/font-awesome.min.css'), array(), '1.0');
            wp_enqueue_style('bootstrap', get_theme_file_uri('/assets/css/bootstrap.min.css'), array(), '1.0');
            wp_enqueue_style('magnific-popup', get_theme_file_uri('/assets/css/magnific-popup.css'), array(), '1.0');
            wp_enqueue_style('slick', get_theme_file_uri('/assets/css/slick.min.css'), array(), '1.0');
            wp_enqueue_style('jquery-scrollbar', get_theme_file_uri('/assets/css/jquery.scrollbar.css'), array(), '1.0');
            wp_enqueue_style('chosen', get_theme_file_uri('/assets/css/chosen.min.css'), array(), '1.0');
            if (!class_exists('Moorabi_Toolkit')) {
                wp_enqueue_style('mobile-menu', get_theme_file_uri('/assets/css/mobile-menu.css'), array(), '1.0');
            }
            wp_enqueue_style('moorabi-style', get_theme_file_uri('/assets/css/style.css'), array(), '1.0', 'all');
            if (is_singular() && comments_open() && get_option('thread_comments')) {
                wp_enqueue_script('comment-reply');
            }
            /* SCRIPTS */
            if (!is_admin()) {
                wp_dequeue_style('woocommerce_admin_styles');
            }
            wp_enqueue_script('chosen', get_theme_file_uri('/assets/js/libs/chosen.min.js'), array(), '1.0', true);
            wp_enqueue_script('bootstrap', get_theme_file_uri('/assets/js/libs/bootstrap.min.js'), array(), '3.3.7', true);
            wp_enqueue_script('threesixty', get_theme_file_uri('/assets/js/libs/threesixty.min.js'), array(), '1.0.7', true);
            wp_enqueue_script('magnific-popup', get_theme_file_uri('/assets/js/libs/magnific-popup.min.js'), array(), '1.1.0', true);
            wp_enqueue_script('slick', get_theme_file_uri('/assets/js/libs/slick.min.js'), array(), '3.3.7', true);
            wp_enqueue_script('jquery-scrollbar', get_theme_file_uri('/assets/js/libs/jquery.scrollbar.min.js'), array(), '1.0.0', true);
            wp_enqueue_script('countdown', get_theme_file_uri('/assets/js/libs/countdown.min.js'), array(), '1.0.0', true);
            wp_enqueue_script('theia-sticky-sidebar', get_theme_file_uri('/assets/js/libs/theia-sticky-sidebar.min.js'), array(), '1.0.0', true);
            if (!class_exists('Moorabi_Toolkit')) {
                wp_enqueue_script('mobile-menu', get_theme_file_uri('/assets/js/libs/mobile-menu.js'), array(), '1.0.0', true);
            }
            wp_enqueue_script('moorabi-frontend', get_theme_file_uri('/assets/js/frontend.js'), array(), '1.0', true);
            wp_localize_script('moorabi-frontend', 'moorabi_ajax_frontend',
                array(
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'security' => wp_create_nonce('moorabi_ajax_frontend'),
                    'added_to_cart_notification_text' => apply_filters('moorabi_added_to_cart_notification_text', esc_html__('has been added to cart!', 'moorabi')),
                    'view_cart_notification_text' => apply_filters('moorabi_view_cart_notification_text', esc_html__('View Cart', 'moorabi')),
                    'added_to_cart_text' => apply_filters('moorabi_adding_to_cart_text', esc_html__('Product has been added to cart!', 'moorabi')),
                    'wc_cart_url' => (function_exists('wc_get_cart_url') ? esc_url(wc_get_cart_url()) : ''),
                    'added_to_wishlist_text' => get_option('yith_wcwl_product_added_text', esc_html__('Product has been added to wishlist!', 'moorabi')),
                    'wishlist_url' => (function_exists('YITH_WCWL') ? esc_url(YITH_WCWL()->get_wishlist_url()) : ''),
                    'browse_wishlist_text' => get_option('yith_wcwl_browse_wishlist_text', esc_html__('Browse Wishlist', 'moorabi')),
                    'removed_cart_text' => esc_html__('Product Removed', 'moorabi'),
                    'wp_nonce_url' => (function_exists('wc_get_cart_url') ? wp_nonce_url(wc_get_cart_url()) : ''),
                )
            );
            $moorabi_enable_popup = $this->moorabi_get_option('enable_popup');
            $moorabi_enable_popup_mobile = $this->moorabi_get_option('enable_popup_mobile');
            $moorabi_popup_delay_time = $this->moorabi_get_option('popup_delay_time');
            $atts = array(
                'owl_responsive_vertical' => 1199,
                'owl_loop' => false,
                'owl_slide_margin' => '14',
                'owl_focus_select' => true,
                'owl_ts_items' => $this->moorabi_get_option('product_thumbnail_ts_items', 5),
                'owl_xs_items' => $this->moorabi_get_option('product_thumbnail_xs_items', 5),
                'owl_sm_items' => $this->moorabi_get_option('product_thumbnail_sm_items', 5),
                'owl_md_items' => $this->moorabi_get_option('product_thumbnail_md_items', 5),
                'owl_lg_items' => $this->moorabi_get_option('product_thumbnail_lg_items', 5),
                'owl_ls_items' => $this->moorabi_get_option('product_thumbnail_bg_items', 5),
            );
            $atts = apply_filters('moorabi_thumb_product_single_slide', $atts);
            $owl_settings = explode(' ', apply_filters('moorabi_carousel_data_attributes', 'owl_', $atts));
            wp_localize_script('moorabi-frontend', 'moorabi_global_frontend',
                array(
                    'moorabi_enable_popup' => $moorabi_enable_popup,
                    'moorabi_popup_delay_time' => $moorabi_popup_delay_time,
                    'moorabi_enable_popup_mobile' => $moorabi_enable_popup_mobile,
                    'data_slick' => urldecode($owl_settings[3]),
                    'data_responsive' => urldecode($owl_settings[6]),
                    'countdown_day' => esc_html__('Days', 'moorabi'),
                    'countdown_hrs' => esc_html__('Hours', 'moorabi'),
                    'countdown_mins' => esc_html__('Mins', 'moorabi'),
                    'countdown_secs' => esc_html__('Secs', 'moorabi'),
                )
            );
        }

        public static function moorabi_get_id()
        {
            $id_page = get_the_ID();
            if (class_exists('WooCommerce') && is_woocommerce() && !is_product()) {
                $id_page = get_option('woocommerce_shop_page_id');
                if (!$id_page) {
                    $id_page = get_the_ID();
                }
            }

            return $id_page;
        }

        public static function moorabi_get_option($option_name, $default = '')
        {
            $get_value = isset($_GET[$option_name]) ? $_GET[$option_name] : '';
            $cs_option = null;
            if (defined('CS_VERSION')) {
                $cs_option = get_option(CS_OPTION);
            }
            if (isset($_GET[$option_name])) {
                $cs_option = $get_value;
                $default = $get_value;
            }
            $options = apply_filters('cs_get_option', $cs_option, $option_name, $default);
            if (!empty($option_name) && !empty($options[$option_name])) {
                $option = $options[$option_name];
                if (is_array($option) && isset($option['multilang']) && $option['multilang'] == true) {
                    if (defined('ICL_LANGUAGE_CODE')) {
                        if (isset($option[ICL_LANGUAGE_CODE])) {
                            return $option[ICL_LANGUAGE_CODE];
                        }
                    }
                }

                return $option;
            } else {
                return (!empty($default)) ? $default : null;
            }
        }

        /**
         * Filter whether comments are open for a given post type.
         *
         * @param string $status Default status for the given post type,
         *                             either 'open' or 'closed'.
         * @param string $post_type Post type. Default is `post`.
         * @param string $comment_type Type of comment. Default is `comment`.
         *
         * @return string (Maybe) filtered default status for the given post type.
         */
        function moorabi_open_default_comments_for_page($status, $post_type, $comment_type)
        {
            if ('page' == $post_type) {
                return 'open';
            }

            return $status;
        }

        public static function moorabi_includes()
        {
            include_once get_parent_theme_file_path('/framework/framework.php');
            defined('CS_ACTIVE_FRAMEWORK') or define('CS_ACTIVE_FRAMEWORK', true);
            defined('CS_ACTIVE_METABOX') or define('CS_ACTIVE_METABOX', true);
            defined('CS_ACTIVE_TAXONOMY') or define('CS_ACTIVE_TAXONOMY', false);
            defined('CS_ACTIVE_SHORTCODE') or define('CS_ACTIVE_SHORTCODE', false);
            defined('CS_ACTIVE_CUSTOMIZE') or define('CS_ACTIVE_CUSTOMIZE', false);
        }
    }
}
if (!function_exists('Moorabi_Functions')) {
    function Moorabi_Functions()
    {
        return Moorabi_Functions::instance();
    }

    Moorabi_Functions();
}