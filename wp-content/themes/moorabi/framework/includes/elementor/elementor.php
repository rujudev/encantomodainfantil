<?php
if (!class_exists('Moorabi_Elementor')) {
    class Moorabi_Elementor extends \Elementor\Widget_Base
    {

        public $name = '';
        public $title = '';
        public $icon = '';
        public $categories = ['moorabi'];

        public function get_name()
        {
            return $this->name;
        }

        public function get_title()
        {
            return 'Moorabi: ' . $this->title;
        }

        public function get_icon()
        {
            return $this->icon;
        }

        public function get_categories()
        {
            return $this->categories;
        }

        public static function moorabi_elementor_preview($name)
        {
            $preview_options = array();
            $path = trailingslashit(get_template_directory()) . "assets/images/elementor/{$name}/";
            // Check if Elementor installed and activated
            if (!did_action('elementor/loaded')) {
                return array();
            }
            if (is_dir($path)) {
                $files = scandir($path);
                if ($files && is_array($files)) {
                    foreach ($files as $file) {
                        if ($file != '.' && $file != '..') {
                            $fileInfo = pathinfo($file);
                            if ($fileInfo['extension'] == 'jpg') {
                                $fileName = str_replace(
                                    array('_', '-'),
                                    array(' ', ' '),
                                    $fileInfo['filename']
                                );
                                /* PRINT OPTION */
                                $preview_options[$fileInfo['filename']] = ucwords($fileName);
                            }
                        }
                    }
                }
            }
            return $preview_options;
        }

        public static function moorabi_elementor_menu()
        {
            $all_menu = array();
            $menus = get_terms('nav_menu', array('hide_empty' => false));
            if ($menus && count($menus) > 0) {
                foreach ($menus as $m) {
                    $all_menu[$m->slug] = $m->name;
                }
            }
            return $all_menu;
        }

        public static function moorabi_elementor_pinmmaper($type = '')
        {
            $args = array(
                'post_type' => 'moorabi_pinmap',
                'posts_per_page' => -1,
                'post_status' => 'publish',
            );
            $loop = new wp_query($args);
            $options = array();
            $previews = array();
            $default = '';
            $i = 0;
            if ($loop->have_posts()) {
                while ($loop->have_posts()) {
                    $loop->the_post();
                    $i++;
                    if ($i == 1) {
                        $default = get_the_ID();
                    }
                    $attachment_id = get_post_meta(get_the_ID(), 'moorabi_pinmap_image', true);
                    $previews[get_the_ID()] = wp_get_attachment_image_url($attachment_id, 'medium');
                    $options[get_the_ID()] = get_the_title();
                }
            }
            if ($type == 'default') {
                return $default;
            } elseif ($type == 'previews') {
                return $previews;
            } else {
                return $options;
            }
        }

        public static function moorabi_elementor_taxonomy()
        {
            $taxonomy_options = array(
                '' => esc_html__('All', 'moorabi')
            );
            $args = array();
            $product_categories = get_terms('product_cat', $args);
            if (!empty($product_categories)) {
                foreach ($product_categories as $key => $value) {
                    $taxonomy_options[$value->slug] = $value->name;
                }
            }
            return $taxonomy_options;
        }

        public static function moorabi_elementor_category()
        {
            $category_options = array(
                '' => esc_html__('All', 'moorabi')
            );
            $args = array();
            $categories = get_categories($args);
            if (!empty($categories)) {
                foreach ($categories as $category) {
                    $category_options[$category->slug] = $category->name;
                }
            }
            return $category_options;
        }

        public static function moorabi_elementor_size()
        {
            $size_width_options = array();
            $width = 300;
            $height = 300;
            if (function_exists('wc_get_image_size')) {
                $size = wc_get_image_size('shop_catalog');
                $width = isset($size['width']) ? $size['width'] : $width;
                $height = isset($size['height']) ? $size['height'] : $height;
            }
            for ($i = 100; $i < $width; $i = $i + 10) {
                array_push($size_width_options, $i);
            }
            $size_options = array();
            $size_options[$width . 'x' . $height] = $width . 'x' . $height;
            foreach ($size_width_options as $k => $w) {
                $w = intval($w);
                if (isset($width) && $width > 0) {
                    $h = round($height * $w / $width);
                } else {
                    $h = $w;
                }
                $size_options[$w . 'x' . $h] = $w . 'x' . $h;
            }
            $size_options['custom'] = esc_html__('Custom', 'moorabi');
            return $size_options;
        }
        public static function moorabi_elementor_social()
        {
            $social_options = array();
            $all_socials = Moorabi_Functions::moorabi_get_option('user_all_social');
            if (!empty($all_socials)) {
                foreach ($all_socials as $key => $value) {
                    $social_options[$key] = $value['title_social'];
                }
            }
            return $social_options;
        }
        public static function moorabi_elementor_icon()
        {
            $elementor_icon = \Elementor\Control_Icon::get_icons();
            $moorabi_icon = array(
                'eicon-banner' => 'Eicon Banner',
                'eicon-post-content' => 'Eicon Post Content',
                'eicon-anchor' => 'Eicon Anchor',
                'eicon-icon-box' => 'Eicon Icon Box',
                'eicon-instagram-post' => 'Eicon Instagram Post',
                'eicon-mailchimp' => 'Eicon Mailchimp',
                'eicon-map-pin' => 'Eicon Map Pin',
                'eicon-product-tabs' => 'Eicon Product Tabs',
                'eicon-woocommerce' => 'Eicon WooCommerce',
                'eicon-social-icons' => 'Eicon Social Icons',
                'eicon-person' => 'Eicon Person',
                'eicon-testimonial-carousel' => 'Eicon Testimonial Carousel',
                'eicon-t-letter' => 'Eicon T Letter',
                'eicon-nav-menu' => 'Eicon Nav Nenu',
                'eicon-video-playlist' => 'Eicon Video Playlist',
                'flaticon-magnifying-glass' => 'Flaticon Magnifying Glass',
                'flaticon-profile' => 'Flaticon Profile',
                'flaticon-bag' => 'Flaticon Bag',
                'flaticon-right-arrow' => 'Flaticon Right Arrow',
                'flaticon-left-arrow' => 'Flaticon Left Arrow',
                'flaticon-right-arrow-1' => 'Flaticon Right Arrow1',
                'flaticon-left-arrow-1' => 'Flaticon Left Arrow1',
                'flaticon-mail' => 'Flaticon Mail',
                'flaticon-flame' => 'Flaticon Flame',
                'flaticon-clock' => 'Flaticon Clock',
                'flaticon-comment' => 'Flaticon Comment',
                'flaticon-chat' => 'Flaticon Chat',
                'flaticon-heart' => 'Flaticon Heart',
                'flaticon-valentines-heart' => 'Flaticon Valentines Heart',
                'flaticon-filter' => 'Flaticon Filter',
                'flaticon-loading' => 'Flaticon Loading',
                'flaticon-checked' => 'Flaticon Checked',
                'flaticon-tick' => 'Flaticon Tick',
                'flaticon-close' => 'Flaticon Close',
                'flaticon-circular-check-button' => 'Flaticon Circular Check Button',
                'flaticon-check' => 'Flaticon Check',
                'flaticon-play-button' => 'Flaticon Play Button',
                'flaticon-360-degrees' => 'Flaticon 360 Degrees',
                'flaticon-login' => 'Flaticon Login',
                'flaticon-menu' => 'Flaticon Menu',
                'flaticon-menu-1' => 'Flaticon Menu 1',
                'flaticon-placeholder' => 'Flaticon Placeholder',
                'flaticon-metre' => 'Flaticon Metre',
                'flaticon-share' => 'Flaticon Share',
                'flaticon-shuffle' => 'Flaticon Shuffle',
                'flaticon-running' => 'Flaticon Running',
                'flaticon-recycle' => 'Flaticon Recycle',
                'flaticon-instagram' => 'Flaticon Instagram',
                'flaticon-delivery-truck' => 'Flaticon Delivery Truck',
                'flaticon-closed-lock' => 'Flaticon Closed Lock',
                'flaticon-support' => 'Flaticon Support',
                'flaticon-diamond' => 'Flaticon Diabmond',
                'flaticon-high-heels' => 'Flaticon High Heels',
                'flaticon-shirt' => 'Flaticon Shirt',
                'flaticon-dress' => 'Flaticon Dress',
                'flaticon-shirt-1' => 'Flaticon Shirt 1',
                'flaticon-glasses' => 'Flaticon Glasses',
                'flaticon-shopping-bag' => 'Flaticon Shopping Bag',
                'flaticon-trousers' => 'Flaticon Trousers',
                'flaticon-user' => 'Flaticon User',
                'flaticon-magnifying-glass-1' => 'Flaticon Magnifying Glass 1',
                'flaticon-shopping-bag-1' => 'Flaticon Shopping Bag 1',
                'flaticon-envelope' => 'Flaticon Envelope',
                'flaticon-instagram-1' => 'Flaticon Instagram 1',
                'flaticon-rocket-ship' => 'Flaticon Rocket Ship',
                'flaticon-refresh' => 'Flaticon Refresh',
                'flaticon-return' => 'Flaticon Return',
                'flaticon-padlock' => 'Flaticon Padlock',
                'flaticon-random' => 'Flaticon Random',
                'flaticon-shopping-cart' => 'Flaticon Shopping Cart',
                'flaticon-cart' => 'Flaticon Cart',
                'flaticon-filter-1' => 'Flaticon Filter 1',
                'flaticon-startup' => 'Flaticon Startup',
                'flaticon-return-1' => 'Flaticon Return 1',
                'flaticon-letter' => 'Flaticon Letter',
                'flaticon-diamond-1' => 'Flaticon Diamond 1',
                'flaticon-key' => 'Flaticon Key',
                'flaticon-envelope-of-white-paper' => 'Flaticon Envelope Of White Paper',
            );
            $icon_options = array_merge($elementor_icon, $moorabi_icon);
            return $icon_options;
        }
    }
}