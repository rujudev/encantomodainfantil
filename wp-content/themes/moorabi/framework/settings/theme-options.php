<?php if (!defined('ABSPATH')) {
    die;
} // Cannot access pages directly.
if (!class_exists('Moorabi_ThemeOption')) {
    class Moorabi_ThemeOption
    {
        public function __construct()
        {
            add_filter('cs_framework_settings', array($this, 'framework_settings'));
            add_filter('cs_framework_options', array($this, 'framework_options'));
            add_filter('cs_metabox_options', array($this, 'metabox_options'));
        }

        public function get_header_preview()
        {
            $layoutDir = get_template_directory() . '/templates/header/';
            $header_options = array();
            if (is_dir($layoutDir)) {
                $files = scandir($layoutDir);
                if ($files && is_array($files)) {
                    foreach ($files as $file) {
                        if ($file != '.' && $file != '..') {
                            $fileInfo = pathinfo($file);
                            if ($fileInfo['extension'] == 'php' && $fileInfo['basename'] != 'index.php') {
                                $file_data = get_file_data($layoutDir . $file, array('Name' => 'Name'));
                                $file_name = str_replace('header-', '', $fileInfo['filename']);
                                $header_options[$file_name] = array(
                                    'title' => $file_data['Name'],
                                    'preview' => get_theme_file_uri('/templates/header/header-' . $file_name . '.jpg'),
                                );
                            }
                        }
                    }
                }
            }

            return $header_options;
        }

        public function get_social_options()
        {
            $socials = array();
            $all_socials = cs_get_option('user_all_social');
            if ($all_socials) {
                foreach ($all_socials as $key => $social) {
                    $socials[$key] = $social['title_social'];
                }
            }

            return $socials;
        }

        public function rev_slide_options()
        {
            $rev_slide_options = array('' => esc_html__('--- Choose Revolution Slider ---', 'moorabi'));
            if (class_exists('RevSlider')) {
                global $wpdb;
                if (shortcode_exists('rev_slider')) {
                    $rev_sql = $wpdb->prepare(
                        "SELECT *
                FROM {$wpdb->prefix}revslider_sliders
                WHERE %d", 1
                    );
                    $rev_rows = $wpdb->get_results($rev_sql);
                    if (count($rev_rows) > 0) {
                        foreach ($rev_rows as $rev_row):
                            $rev_slide_options[$rev_row->alias] = $rev_row->title;
                        endforeach;
                    }
                }
            }

            return $rev_slide_options;
        }

        public function get_footer_preview()
        {
            $footer_preview = array(
                'none'    => array(
                    'title'   => esc_html__('None', 'moorabi'),
                    'preview' => get_theme_file_uri('/assets/images/placeholder.jpg'),
                ),
            );
            $args           = array(
                'post_type'      => 'moorabi_footer',
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC',
            );
            $posts          = get_posts($args);
            if (!empty($posts)) {
                foreach ($posts as $post) {
                    setup_postdata($post);
                    $url     = get_edit_post_link($post->ID);
                    $preview = get_theme_file_uri('/assets/images/placeholder.jpg');
                    if (has_post_thumbnail($post)) {
                        $preview = wp_get_attachment_image_url(get_post_thumbnail_id($post->ID), 'full');
                    }
                    $footer_preview[$post->post_name] = array(
                        'title'   => $post->post_title,
                        'preview' => $preview,
                        'url'     => $url,
                    );
                }
            }
            wp_reset_postdata();

            return $footer_preview;
        }

        public function get_sidebar_options()
        {
            $sidebars = array();
            global $wp_registered_sidebars;
            foreach ($wp_registered_sidebars as $sidebar) {
                $sidebars[$sidebar['id']] = $sidebar['name'];
            }

            return $sidebars;
        }
        public function get_shop_product_preview() {
            $layoutDir            = get_template_directory() . '/woocommerce/product-styles/';
            $shop_product_options = array();
            if ( is_dir( $layoutDir ) ) {
                $files = scandir( $layoutDir );
                if ( $files && is_array( $files ) ) {
                    foreach ( $files as $file ) {
                        if ( $file != '.' && $file != '..' ) {
                            $fileInfo = pathinfo( $file );
                            if ( $fileInfo['extension'] == 'php' && $fileInfo['basename'] != 'index.php' && $fileInfo['filename'] != 'content-product-list' && $fileInfo['filename'] != 'content-product-style-03') {
                                $file_data                          = get_file_data( $layoutDir . $file, array( 'Name' => 'Name' ) );
                                $file_name                          = str_replace( 'content-product-', '', $fileInfo['filename'] );
                                $shop_product_options[ $file_name ] = array(
                                    'title'   => $file_data['Name'],
                                    'preview' => get_theme_file_uri( '/assets/images/elementor/products/' . $file_name . '.jpg' ),
                                );
                            }
                        }
                    }
                }
            }

            return $shop_product_options;
        }
        public function get_attributes_options()
        {
            $attributes = array();
            $attributes_tax = array();
            if (function_exists('wc_get_attribute_taxonomies')) {
                $attributes_tax = wc_get_attribute_taxonomies();
            }
            if (is_array($attributes_tax) && count($attributes_tax) > 0) {
                foreach ($attributes_tax as $attribute) {
                    $attributes[$attribute->attribute_name] = $attribute->attribute_label;
                }
            }

            return $attributes;
        }

        function framework_settings($settings)
        {
            $settings = array(
                'menu_title' => esc_html__('Theme Options', 'moorabi'),
                'menu_type' => 'submenu', // menu, submenu, options, theme, etc.
                'menu_slug' => 'moorabi',
                'ajax_save' => false,
                'menu_parent' => 'moorabi_menu',
                'show_reset_all' => true,
                'menu_position' => 5,
                'framework_title' => '',
            );

            return $settings;
        }

        function framework_options($options)
        {
            // ===============================================================================================
            // -----------------------------------------------------------------------------------------------
            // FRAMEWORK OPTIONS
            // -----------------------------------------------------------------------------------------------
            // ===============================================================================================
            $options = array();
            // ----------------------------------------
            // a option section for options overview  -
            // ----------------------------------------
            $options[] = array(
                'name' => 'general',
                'title' => esc_html__('General', 'moorabi'),
                'icon' => 'fa fa-wordpress',
                'sections' => array(
                    array(
                        'name' => 'main_settings',
                        'title' => esc_html__('Main Settings', 'moorabi'),
                        'fields' => array(
                            array(
                                'id' => 'logo',
                                'type' => 'image',
                                'title' => esc_html__('Logo', 'moorabi'),
                            ),
                            array(
                                'id' => 'width_logo',
                                'type' => 'number',
                                'default' => '150',
                                'title' => esc_html__('Width Logo', 'moorabi'),
                                'desc' => esc_html__('Unit PX', 'moorabi')
                            ),
                            array(
                                'id' => 'main_color',
                                'type' => 'color_picker',
                                'title' => esc_html__('Main Color', 'moorabi'),
                                'default' => '#71c0ef',
                                'rgba' => true,
                            ),
                            array(
                                'id' => 'secondary_color',
                                'type' => 'color_picker',
                                'title' => esc_html__('Secondary Color', 'moorabi'),
                                'default' => '#ed71a3',
                                'rgba' => true,
                            ),
                            array(
                                'id' => 'tertiary_color',
                                'type' => 'color_picker',
                                'title' => esc_html__('Tertiary Color', 'moorabi'),
                                'default' => '#f7c86f',
                                'rgba' => true,
                            ),
                        ),
                    ),
                    array(
                        'name' => 'popup_settings',
                        'title' => esc_html__('Newsletter Settings', 'moorabi'),
                        'fields' => array(
                            array(
                                'id' => 'enable_popup',
                                'type' => 'switcher',
                                'title' => esc_html__('Enable Newsletter Popup', 'moorabi'),
                            ),
                            array(
                                'id' => 'select_newsletter_page',
                                'type' => 'select',
                                'title' => esc_html__('Page Newsletter Popup', 'moorabi'),
                                'options' => 'pages',
                                'query_args' => array(
                                    'sort_order' => 'ASC',
                                    'sort_column' => 'post_title',
                                ),
                                'attributes' => array(
                                    'multiple' => 'multiple',
                                ),
                                'class' => 'chosen',
                                'dependency' => array('enable_popup', '==', '1'),
                            ),
                            array(
                                'id' => 'popup_background',
                                'type' => 'image',
                                'title' => esc_html__('Popup Background', 'moorabi'),
                                'dependency' => array('enable_popup', '==', '1'),
                            ),
                            array(
                                'id' => 'popup_title',
                                'type' => 'text',
                                'title' => esc_html__('Title', 'moorabi'),
                                'dependency' => array('enable_popup', '==', '1'),
                                'default' => esc_html__('Join Our Newsletter', 'moorabi'),
                            ),
                            array(
                                'id' => 'popup_desc',
                                'type' => 'text',
                                'title' => esc_html__('Description', 'moorabi'),
                                'dependency' => array('enable_popup', '==', '1'),
                                'default' => esc_html__('Subscribe to our newsletters now and stay up-to-date with new collections, the latest lookbooks and exclusive offers.', 'moorabi'),
                            ),
                            array(
                                'id' => 'popup_input_placeholder',
                                'type' => 'text',
                                'title' => esc_html__('Placeholder Input', 'moorabi'),
                                'default' => esc_html__('Email address here...', 'moorabi'),
                                'dependency' => array('enable_popup', '==', '1'),
                            ),
                            array(
                                'id' => 'popup_input_submit',
                                'type' => 'text',
                                'title' => esc_html__('Button', 'moorabi'),
                                'default' => esc_html__('Subscribe', 'moorabi'),
                                'dependency' => array('enable_popup', '==', '1'),
                            ),
                            array(
                                'id' => 'popup_delay_time',
                                'type' => 'number',
                                'title' => esc_html__('Delay Time', 'moorabi'),
                                'default' => '0',
                                'dependency' => array('enable_popup', '==', '1'),
                            ),
                            array(
                                'id' => 'enable_popup_mobile',
                                'type' => 'switcher',
                                'title' => esc_html__('Enable Popup on Mobile', 'moorabi'),
                                'default' => false,
                                'dependency' => array('enable_popup', '==', '1'),
                            ),
                        ),
                    ),
                    array(
                        'name' => 'live_search_settings',
                        'title' => esc_html__('Live Search Settings', 'moorabi'),
                        'fields' => array(
                            array(
                                'id' => 'enable_live_search',
                                'type' => 'switcher',
                                'attributes' => array(
                                    'data-depend-id' => 'enable_live_search',
                                ),
                                'title' => esc_html__('Enable Live Search', 'moorabi'),
                                'default' => false,
                            ),
                            array(
                                'id' => 'show_suggestion',
                                'type' => 'switcher',
                                'title' => esc_html__('Display Suggestion', 'moorabi'),
                                'dependency' => array(
                                    'enable_live_search', '==', true,
                                ),
                            ),
                            array(
                                'id' => 'min_characters',
                                'type' => 'number',
                                'default' => 3,
                                'title' => esc_html__('Min Search Characters', 'moorabi'),
                                'dependency' => array(
                                    'enable_live_search', '==', true,
                                ),
                            ),
                            array(
                                'id' => 'max_results',
                                'type' => 'number',
                                'default' => 3,
                                'title' => esc_html__('Max Search Characters', 'moorabi'),
                                'dependency' => array(
                                    'enable_live_search', '==', true,
                                ),
                            ),
                            array(
                                'id' => 'search_in',
                                'type' => 'checkbox',
                                'title' => esc_html__('Search In', 'moorabi'),
                                'options' => array(
                                    'title' => esc_html__('Title', 'moorabi'),
                                    'description' => esc_html__('Description', 'moorabi'),
                                    'content' => esc_html__('Content', 'moorabi'),
                                    'sku' => esc_html__('SKU', 'moorabi'),
                                ),
                                'dependency' => array(
                                    'enable_live_search', '==', true,
                                ),
                            ),
                        ),
                    ),
                ),
            );
            $options[] = array(
                'name' => 'header',
                'title' => esc_html__('Header Settings', 'moorabi'),
                'icon' => 'fa fa-header',
                'sections' => array(
                    array(
                        'name' => 'main_header',
                        'title' => esc_html__('Header Settings', 'moorabi'),
                        'fields' => array(
                            array(
                                'id' => 'header_options',
                                'type' => 'select_preview',
                                'title' => esc_html__('Header Layout', 'moorabi'),
                                'desc' => esc_html__('Select a header layout', 'moorabi'),
                                'options' => self::get_header_preview(),
                                'default' => 'style-01',
                                'attributes' => array(
                                    'data-depend-id' => 'header_options',
                                ),
                            ),
                            array(
                                'id' => 'header_color',
                                'type' => 'select',
                                'title' => esc_html__('Header Color', 'moorabi'),
                                'options' => array(
                                    'header-dark' => esc_html__('Header Dark', 'moorabi'),
                                    'header-light' => esc_html__('Header Light', 'moorabi'),
                                ),
                                'default' => 'header-dark'
                            ),
                            array(
                                'id' => 'enable_header_transparent',
                                'type' => 'switcher',
                                'title' => esc_html__('Enable Header Transparent', 'moorabi'),
                            ),
                            array(
                                'id' => 'email_address',
                                'type' => 'text',
                                'title' => esc_html__('Email Address', 'moorabi'),
                                'default' => esc_html__('contact@yourcompany.com', 'moorabi'),
                            ),
                            array(
                                'id' => 'phone_number',
                                'type' => 'text',
                                'title' => esc_html__('Phone Number', 'moorabi'),
                                'default' => esc_html__('0123 456 789', 'moorabi'),
                            ),
                            array(
                                'id' => 'phone_text',
                                'type' => 'text',
                                'title' => esc_html__('Phone Text', 'moorabi'),
                                'default' => esc_html__('Call Us Now', 'moorabi'),
                            ),
                        ),
                    ),
                    array(
                        'name' => 'vertical',
                        'title' => esc_html__('Vertical Settings', 'moorabi'),
                        'fields' => array(
                            array(
                                'id' => 'enable_vertical_menu',
                                'type' => 'switcher',
                                'attributes' => array(
                                    'data-depend-id' => 'enable_vertical_menu',
                                ),
                                'title' => esc_html__('Enable Vertical Menu', 'moorabi'),
                            ),
                            array(
                                'id' => 'block_vertical_menu',
                                'type' => 'select',
                                'title' => esc_html__('Vertical Menu Always Open', 'moorabi'),
                                'options' => 'page',
                                'class' => 'chosen',
                                'attributes' => array(
                                    'placeholder' => 'Select a page',
                                    'multiple' => 'multiple',
                                ),
                                'dependency' => array(
                                    'enable_vertical_menu', '==', true,
                                ),
                                'after' => '<i class="moorabi-text-desc">' . esc_html__('-- Vertical menu will be always open --', 'moorabi') . '</i>',
                            ),
                            array(
                                'id' => 'vertical_menu_title',
                                'type' => 'text',
                                'title' => esc_html__('Vertical Menu Title', 'moorabi'),
                                'dependency' => array(
                                    'enable_vertical_menu', '==', true,
                                ),
                                'default' => esc_html__('All Categories', 'moorabi'),
                            ),
                            array(
                                'id' => 'vertical_menu_button_all_text',
                                'type' => 'text',
                                'title' => esc_html__('Vertical Menu Button Show All Text', 'moorabi'),
                                'dependency' => array(
                                    'enable_vertical_menu', '==', true,
                                ),
                                'default' => esc_html__('Show All', 'moorabi'),
                            ),
                            array(
                                'id' => 'vertical_menu_button_close_text',
                                'type' => 'text',
                                'title' => esc_html__('Vertical Menu Button Close Text', 'moorabi'),
                                'dependency' => array(
                                    'enable_vertical_menu', '==', true,
                                ),
                                'default' => esc_html__('Close', 'moorabi'),
                            ),
                            array(
                                'id' => 'vertical_item_visible',
                                'type' => 'number',
                                'title' => esc_html__('The Number of Visible Vertical Menu Items', 'moorabi'),
                                'desc' => esc_html__('The Number of Visible Vertical Menu Items', 'moorabi'),
                                'dependency' => array(
                                    'enable_vertical_menu', '==', true,
                                ),
                                'default' => 10,
                            ),
                        ),
                    ),
                ),
            );
            $options[] = array(
                'name' => 'banner_page',
                'title' => esc_html__('Banner Page Settings', 'moorabi'),
                'icon' => 'fa fa-window-maximize',
                'fields' => array(
                    array(
                        'id' => 'banner_type',
                        'type' => 'select',
                        'title' => esc_html__('Banner Type', 'moorabi'),
                        'options' => array(
                            'no_background' => esc_html__('No Background ', 'moorabi'),
                            'has_background' => esc_html__('Has Background', 'moorabi'),
                            'rev_background' => esc_html__('Revolution', 'moorabi'),
                            'disable' => esc_html__('Disable', 'moorabi'),
                        ),
                        'default' => 'no_background'
                    ),
                    array(
                        'id' => 'banner_full_width',
                        'type' => 'switcher',
                        'title' => esc_html__('Banner Full Width', 'moorabi'),
                        'default' => false,
                        'dependency' => array('banner_type', 'any', 'has_background,rev_background'),
                    ),
                    array(
                        'id' => 'banner_image',
                        'type' => 'image',
                        'title' => esc_html__('Banner Image', 'moorabi'),
                        'add_title' => esc_html__('Upload', 'moorabi'),
                        'dependency' => array('banner_type', '==', 'has_background'),
                    ),
                    array(
                        'id' => 'banner_rev_slide',
                        'type' => 'select',
                        'options' => self::rev_slide_options(),
                        'title' => esc_html__('Select Slide', 'moorabi'),
                        'dependency' => array('banner_type', '==', 'rev_background'),
                    ),
                ),
            );
            $options[] = array(
                'name' => 'footer',
                'title' => esc_html__('Footer Settings', 'moorabi'),
                'icon' => 'fa fa-underline',
                'fields' => array(
                    array(
                        'id' => 'footer_options',
                        'type' => 'select_preview',
                        'title' => esc_html__('Select Footer Builder', 'moorabi'),
                        'options' => self::get_footer_preview(),
                        'default' => 'default',
                    ),
                ),
            );
            $options[] = array(
                'name' => 'blog_main',
                'title' => esc_html__('Blog Settings', 'moorabi'),
                'icon' => 'fa fa-rss',
                'sections' => array(
                    array(
                        'name' => 'blog',
                        'title' => esc_html__('Blog', 'moorabi'),
                        'fields' => array(
                            array(
                                'id' => 'blog_banner_type',
                                'type' => 'select',
                                'title' => esc_html__('Blog Banner Type', 'moorabi'),
                                'options' => array(
                                    'no_background' => esc_html__('No Background ', 'moorabi'),
                                    'has_background' => esc_html__('Has Background', 'moorabi'),
                                    'rev_background' => esc_html__('Revolution', 'moorabi'),
                                    'disable' => esc_html__('Disable', 'moorabi'),
                                ),
                                'default' => 'no_background'
                            ),
                            array(
                                'id' => 'blog_banner_full_width',
                                'type' => 'switcher',
                                'title' => esc_html__('Blog Banner Full Width', 'moorabi'),
                                'default' => false,
                                'dependency' => array('blog_banner_type', 'any', 'has_background,rev_background'),
                            ),
                            array(
                                'id' => 'blog_banner_image',
                                'type' => 'image',
                                'title' => esc_html__('Blog Banner Image', 'moorabi'),
                                'add_title' => esc_html__('Upload', 'moorabi'),
                                'dependency' => array('blog_banner_type', '==', 'has_background'),
                            ),
                            array(
                                'id' => 'blog_banner_rev_slide',
                                'type' => 'select',
                                'options' => self::rev_slide_options(),
                                'title' => esc_html__('Select Slide', 'moorabi'),
                                'dependency' => array('blog_banner_type', '==', 'rev_background'),
                            ),
                            array(
                                'id' => 'blog_sidebar_layout',
                                'type' => 'image_select',
                                'title' => esc_html__('Blog Sidebar Layout', 'moorabi'),
                                'desc' => esc_html__('Select sidebar position on Blog.', 'moorabi'),
                                'options' => array(
                                    'left' => esc_attr(' data:image/png;base64,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'),
                                    'right' => esc_attr(' data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC0AAAAkCAYAAAAdFbNSAAAACXBIWXMAAAsTAAALEwEAmpwYAAAKT2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AUkSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXXPues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgABeNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAtAGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dXLh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzABhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/phCJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhMWE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQAkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+IoUspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdpr+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZD5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61MbU2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllirSKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79up+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6VhlWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lOk06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7RyFDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3IveRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+BZ7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5pDoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5qPNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIsOpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQrAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1dT1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aXDm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3SPVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKaRptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfVP1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAAAAgY0hSTQAAeiUAAICDAAD5/wAAgOkAAHUwAADqYAAAOpgAABdvkl/FRgAAANRJREFUeNrs2TEKwkAQheF/Y0QUMSKIWOjZPJLn8SZptbSKSEQkjoVTiF0SXQ28aWanmN2PJWlmg5nRtUgB8jzfA5NvH2ZmZa+XbmaL5a6qqq3ZfVNzi9NiNl2nXqwiXVIGjIEAzL2u20/iRREJXQJ3X18a9Bev6FhhwNXzrekmyQ/+o/CWO4FuHUILLbTQQgsttNBCCy200EILLbTQQn8u7C3/PToAA8/9tugsEnr0cuawQX8GPlQHDkQYqvMc9Z790zhSf8R8AghdfL54AAAA//8DAAqrKVvBESHfAAAAAElFTkSuQmCC'),
                                    'full' => esc_attr(' data:image/png;base64,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'),
                                ),
                                'default' => 'left',
                            ),
                            array(
                                'id' => 'blog_style',
                                'type' => 'select',
                                'default' => 'standard',
                                'title' => esc_html__('Blog Style', 'moorabi'),
                                'options' => array(
                                    'standard' => esc_html__('Standard', 'moorabi'),
                                    'grid' => esc_html__('Grid', 'moorabi'),
                                ),
                            ),
                            array(
                                'type' => 'heading',
                                'content' => esc_html__('Grid Settings', 'moorabi'),
                                'dependency' => array('blog_style', '==', 'grid'),
                            ),
                            array(
                                'id' => 'blog_bg_items',
                                'type' => 'select',
                                'title' => esc_html__('Items per row on Desktop( For grid mode )', 'moorabi'),
                                'desc' => esc_html__('(Screen resolution of device >= 1500px )', 'moorabi'),
                                'options' => array(
                                    '12' => esc_html__('1 item', 'moorabi'),
                                    '6' => esc_html__('2 items', 'moorabi'),
                                    '4' => esc_html__('3 items', 'moorabi'),
                                    '3' => esc_html__('4 items', 'moorabi'),
                                    '15' => esc_html__('5 items', 'moorabi'),
                                    '2' => esc_html__('6 items', 'moorabi'),
                                ),
                                'default' => '4',
                                'dependency' => array('blog_style', '==', 'grid'),
                            ),
                            array(
                                'id' => 'blog_lg_items',
                                'default' => '4',
                                'type' => 'select',
                                'title' => esc_html__('Items per row on Desktop( For grid mode )', 'moorabi'),
                                'desc' => esc_html__('(Screen resolution of device >= 1200px < 1500px )', 'moorabi'),
                                'options' => array(
                                    '12' => esc_html__('1 item', 'moorabi'),
                                    '6' => esc_html__('2 items', 'moorabi'),
                                    '4' => esc_html__('3 items', 'moorabi'),
                                    '3' => esc_html__('4 items', 'moorabi'),
                                    '15' => esc_html__('5 items', 'moorabi'),
                                    '2' => esc_html__('6 items', 'moorabi'),
                                ),
                                'dependency' => array('blog_style', '==', 'grid'),
                            ),
                            array(
                                'id' => 'blog_md_items',
                                'default' => '4',
                                'type' => 'select',
                                'title' => esc_html__('Items per row on Desktop( For grid mode )', 'moorabi'),
                                'desc' => esc_html__('(Screen resolution of device >=992px and < 1200px )', 'moorabi'),
                                'options' => array(
                                    '12' => esc_html__('1 item', 'moorabi'),
                                    '6' => esc_html__('2 items', 'moorabi'),
                                    '4' => esc_html__('3 items', 'moorabi'),
                                    '3' => esc_html__('4 items', 'moorabi'),
                                    '15' => esc_html__('5 items', 'moorabi'),
                                    '2' => esc_html__('6 items', 'moorabi'),
                                ),
                                'dependency' => array('blog_style', '==', 'grid'),
                            ),
                            array(
                                'id' => 'blog_sm_items',
                                'default' => '6',
                                'type' => 'select',
                                'title' => esc_html__('Items per row on Desktop( For grid mode )', 'moorabi'),
                                'desc' => esc_html__('(Screen resolution of device >=768px and < 992px )', 'moorabi'),
                                'options' => array(
                                    '12' => esc_html__('1 item', 'moorabi'),
                                    '6' => esc_html__('2 items', 'moorabi'),
                                    '4' => esc_html__('3 items', 'moorabi'),
                                    '3' => esc_html__('4 items', 'moorabi'),
                                    '15' => esc_html__('5 items', 'moorabi'),
                                    '2' => esc_html__('6 items', 'moorabi'),
                                ),
                                'dependency' => array('blog_style', '==', 'grid'),
                            ),
                            array(
                                'id' => 'blog_xs_items',
                                'default' => '6',
                                'type' => 'select',
                                'title' => esc_html__('Items per row on Desktop( For grid mode )', 'moorabi'),
                                'desc' => esc_html__('(Screen resolution of device >=480  add < 768px)', 'moorabi'),
                                'options' => array(
                                    '12' => esc_html__('1 item', 'moorabi'),
                                    '6' => esc_html__('2 items', 'moorabi'),
                                    '4' => esc_html__('3 items', 'moorabi'),
                                    '3' => esc_html__('4 items', 'moorabi'),
                                    '15' => esc_html__('5 items', 'moorabi'),
                                    '2' => esc_html__('6 items', 'moorabi'),
                                ),
                                'dependency' => array('blog_style', '==', 'grid'),
                            ),
                            array(
                                'id' => 'blog_ts_items',
                                'default' => '12',
                                'type' => 'select',
                                'title' => esc_html__('Items per row on Desktop( For grid mode )', 'moorabi'),
                                'desc' => esc_html__('(Screen resolution of device < 480px)', 'moorabi'),
                                'options' => array(
                                    '12' => esc_html__('1 item', 'moorabi'),
                                    '6' => esc_html__('2 items', 'moorabi'),
                                    '4' => esc_html__('3 items', 'moorabi'),
                                    '3' => esc_html__('4 items', 'moorabi'),
                                    '15' => esc_html__('5 items', 'moorabi'),
                                    '2' => esc_html__('6 items', 'moorabi'),
                                ),
                                'dependency' => array('blog_style', '==', 'grid'),
                            ),
                        ),
                    ),
                    array(
                        'name' => 'blog_single',
                        'title' => esc_html__('Single Post', 'moorabi'),
                        'fields' => array(
                            array(
                                'id' => 'post_sidebar_layout',
                                'type' => 'image_select',
                                'default' => 'left',
                                'title' => esc_html__('Single Post Sidebar Layout', 'moorabi'),
                                'desc' => esc_html__('Select sidebar position on Blog.', 'moorabi'),
                                'options' => array(
                                    'left' => esc_attr(' data:image/png;base64,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'),
                                    'right' => esc_attr(' data:image/png;base64,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'),
                                    'full' => esc_attr(' data:image/png;base64,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'),
                                ),
                            ),
                            'enable_author_info'          => array(
                                'id'    => 'enable_author_info',
                                'type'  => 'switcher',
                                'title' => esc_html__( 'Enable Author Info', 'moorabi' ),
                            ),
                        ),
                    ),
                ),
            );
            if (class_exists('WooCommerce')) {
                $options[] = array(
                    'name' => 'woocommerce_main',
                    'title' => esc_html__('WooCommerce', 'moorabi'),
                    'icon' => 'fa fa-shopping-cart',
                    'sections' => array(
                        array(
                            'name' => 'shop',
                            'title' => esc_html__('Shop', 'moorabi'),
                            'fields' => array(
                                array(
                                    'id' => 'shop_banner_type',
                                    'type' => 'select',
                                    'title' => esc_html__('Shop Banner Type', 'moorabi'),
                                    'options' => array(
                                        'no_background' => esc_html__('No Background ', 'moorabi'),
                                        'has_background' => esc_html__('Has Background', 'moorabi'),
                                        'rev_background' => esc_html__('Revolution', 'moorabi'),
                                        'disable' => esc_html__('Disable', 'moorabi'),
                                    ),
                                    'default' => 'no_background'
                                ),
                                array(
                                    'id' => 'shop_banner_full_width',
                                    'type' => 'switcher',
                                    'title' => esc_html__('Shop Banner Full Width', 'moorabi'),
                                    'default' => false,
                                    'dependency' => array('shop_banner_type', 'any', 'has_background,rev_background'),
                                ),
                                array(
                                    'id' => 'shop_banner_image',
                                    'type' => 'image',
                                    'title' => esc_html__('Shop Banner Image', 'moorabi'),
                                    'add_title' => esc_html__('Upload', 'moorabi'),
                                    'dependency' => array('shop_banner_type', '==', 'has_background'),
                                ),
                                array(
                                    'id' => 'shop_banner_rev_slide',
                                    'type' => 'select',
                                    'options' => self::rev_slide_options(),
                                    'title' => esc_html__('Select Slide', 'moorabi'),
                                    'dependency' => array('shop_banner_type', '==', 'rev_background'),
                                ),
                                array(
                                    'id' => 'product_newness',
                                    'default' => '10',
                                    'type' => 'number',
                                    'title' => esc_html__('Products Newness', 'moorabi'),
                                ),
                                array(
                                    'id' => 'product_per_page',
                                    'type' => 'number',
                                    'default' => '12',
                                    'title' => esc_html__('Products perpage', 'moorabi'),
                                    'desc' => esc_html__('Number of products on shop page.', 'moorabi'),
                                ),
                                array(
                                    'id' => 'shop_sidebar_layout',
                                    'type' => 'image_select',
                                    'default' => 'left',
                                    'title' => esc_html__('Shop Page Sidebar Layout', 'moorabi'),
                                    'desc' => esc_html__('Select sidebar position on Shop Page.', 'moorabi'),
                                    'options' => array(
                                        'left' => esc_attr(' data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC0AAAAkCAYAAAAdFbNSAAAACXBIWXMAAAsTAAALEwEAmpwYAAAKT2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AUkSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXXPues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgABeNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAtAGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dXLh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzABhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/phCJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhMWE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQAkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+IoUspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdpr+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZD5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61MbU2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllirSKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79up+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6VhlWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lOk06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7RyFDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3IveRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+BZ7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5pDoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5qPNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIsOpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQrAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1dT1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aXDm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3SPVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKaRptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfVP1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAAAAgY0hSTQAAeiUAAICDAAD5/wAAgOkAAHUwAADqYAAAOpgAABdvkl/FRgAAANNJREFUeNrs2b0KwjAUhuG3NkUsYicHB117J16Pl9Rr00H8QaxItQjGwQilTo0QKXzfcshwDg8h00lkraVvMQC703kNTLo0xiYpyuN+Vd+rZRybAkgDeC95ni+MO8w9BkyBCBgDs0CXnAEM3KH0GHBz9QlUgdBlE+2TB2CB2tVg+QUdtWov0H+L0EILLbTQQgsttNBCCy200EILLbTQ37Gt2gt0wnslNiTwauyDzjx6R40ZaSBvBm6pDmzouFQHDu5pXIFtIPgFIOrj98ULAAD//wMA7UQkYA5MJngAAAAASUVORK5CYII='),
                                        'right' => esc_attr(' data:image/png;base64,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'),
                                        'full' => esc_attr(' data:image/png;base64,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'),
                                    ),
                                ),
                                array(
                                    'id' => 'shop_style',
                                    'type' => 'image_select',
                                    'default' => 'grid',
                                    'title' => esc_html__('Shop Default Layout', 'moorabi'),
                                    'desc' => esc_html__('Select default layout for shop, product category archive.', 'moorabi'),
                                    'options' => array(
                                        'grid' => get_theme_file_uri('assets/images/grid-display.png'),
                                        'list' => get_theme_file_uri('assets/images/list-display.png'),
                                    ),
                                ),
                                array(
                                    'type' => 'heading',
                                    'content' => esc_html__('Grid Settings', 'moorabi'),
                                    'dependency' => array('shop_style_grid', '==', true),
                                ),
                                array(
                                    'id'      => 'shop_product_style',
                                    'type'    => 'select_preview',
                                    'title'   => esc_html__( 'Shop Product Style', 'moorabi' ),
                                    'desc'    => esc_html__( 'Select default style for product shop, product category archive.', 'moorabi' ),
                                    'options' => self::get_shop_product_preview(),
                                    'default' => 'style-01',
                                    'dependency' => array( 'shop_style_grid', '==', true ),
                                ),
                                array(
                                    'id' => 'woo_bg_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Items per row on Desktop( For grid mode )', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >= 1500px )', 'moorabi'),
                                    'options' => array(
                                        '12' => esc_html__('1 item', 'moorabi'),
                                        '6' => esc_html__('2 items', 'moorabi'),
                                        '4' => esc_html__('3 items', 'moorabi'),
                                        '3' => esc_html__('4 items', 'moorabi'),
                                        '15' => esc_html__('5 items', 'moorabi'),
                                        '2' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '4',
                                    'dependency' => array('shop_style_grid', '==', true),
                                ),
                                array(
                                    'id' => 'woo_lg_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Items per row on Desktop( For grid mode )', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >= 1200px < 1500px )', 'moorabi'),
                                    'options' => array(
                                        '12' => esc_html__('1 item', 'moorabi'),
                                        '6' => esc_html__('2 items', 'moorabi'),
                                        '4' => esc_html__('3 items', 'moorabi'),
                                        '3' => esc_html__('4 items', 'moorabi'),
                                        '15' => esc_html__('5 items', 'moorabi'),
                                        '2' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '4',
                                    'dependency' => array('shop_style_grid', '==', true),
                                ),
                                array(
                                    'id' => 'woo_md_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Items per row on landscape tablet( For grid mode )', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >=992px and < 1200px )', 'moorabi'),
                                    'options' => array(
                                        '12' => esc_html__('1 item', 'moorabi'),
                                        '6' => esc_html__('2 items', 'moorabi'),
                                        '4' => esc_html__('3 items', 'moorabi'),
                                        '3' => esc_html__('4 items', 'moorabi'),
                                        '15' => esc_html__('5 items', 'moorabi'),
                                        '2' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '6',
                                    'dependency' => array('shop_style_grid', '==', true),
                                ),
                                array(
                                    'id' => 'woo_sm_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Items per row on portrait tablet( For grid mode )', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >=768px and < 992px )', 'moorabi'),
                                    'options' => array(
                                        '12' => esc_html__('1 item', 'moorabi'),
                                        '6' => esc_html__('2 items', 'moorabi'),
                                        '4' => esc_html__('3 items', 'moorabi'),
                                        '3' => esc_html__('4 items', 'moorabi'),
                                        '15' => esc_html__('5 items', 'moorabi'),
                                        '2' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '6',
                                    'dependency' => array('shop_style_grid', '==', true),
                                ),
                                array(
                                    'id' => 'woo_xs_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Items per row on Mobile( For grid mode )', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >=480  add < 768px)', 'moorabi'),
                                    'options' => array(
                                        '12' => esc_html__('1 item', 'moorabi'),
                                        '6' => esc_html__('2 items', 'moorabi'),
                                        '4' => esc_html__('3 items', 'moorabi'),
                                        '3' => esc_html__('4 items', 'moorabi'),
                                        '15' => esc_html__('5 items', 'moorabi'),
                                        '2' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '6',
                                    'dependency' => array('shop_style_grid', '==', true),
                                ),
                                array(
                                    'id' => 'woo_ts_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Items per row on Mobile( For grid mode )', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device < 480px)', 'moorabi'),
                                    'options' => array(
                                        '12' => esc_html__('1 item', 'moorabi'),
                                        '6' => esc_html__('2 items', 'moorabi'),
                                        '4' => esc_html__('3 items', 'moorabi'),
                                        '3' => esc_html__('4 items', 'moorabi'),
                                        '15' => esc_html__('5 items', 'moorabi'),
                                        '2' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '6',
                                    'dependency' => array('shop_style_grid', '==', true),
                                ),
                            ),
                        ),
                        array(
                            'name' => 'single_product',
                            'title' => esc_html__('Single Products', 'moorabi'),
                            'fields' => array(
                                array(
                                    'id' => 'product_sidebar_layout',
                                    'type' => 'image_select',
                                    'default' => 'left',
                                    'title' => esc_html__('Product Page Sidebar Layout', 'moorabi'),
                                    'desc' => esc_html__('Select sidebar position on Product Page.', 'moorabi'),
                                    'options' => array(
                                        'left' => esc_attr(' data:image/png;base64,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'),
                                        'right' => esc_attr(' data:image/png;base64,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'),
                                        'full' => esc_attr(' data:image/png;base64,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'),
                                    ),
                                ),
                                array(
                                    'id' => 'product_style',
                                    'type' => 'select',
                                    'default' => 'horizontal_thumbnail',
                                    'title' => esc_html__('Single Product Style', 'moorabi'),
                                    'options' => array(
                                        'horizontal_thumbnail' => esc_html__('Horizontal Thumbnail', 'moorabi'),
                                        'vertical_thumbnail' => esc_html__('Vertical Thumbnail', 'moorabi'),
                                        'gallery_thumbnail' => esc_html__('Gallery Thumbnail', 'moorabi'),
                                        'sticky_detail' => esc_html__('Sticky Detail', 'moorabi'),
                                    ),
                                ),
                                array(
                                    'type' => 'heading',
                                    'content' => esc_html__('Product Thumbnail Settings', 'moorabi'),
                                ),
                                array(
                                    'id' => 'product_thumbnail_bg_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Thumbnail items per row on Desktop', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >= 1500px )', 'moorabi'),
                                    'options' => array(
                                        '1' => esc_html__('1 item', 'moorabi'),
                                        '2' => esc_html__('2 items', 'moorabi'),
                                        '3' => esc_html__('3 items', 'moorabi'),
                                        '4' => esc_html__('4 items', 'moorabi'),
                                        '5' => esc_html__('5 items', 'moorabi'),
                                        '6' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '5',
                                ),
                                array(
                                    'id' => 'product_thumbnail_lg_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Thumbnail items per row on Desktop', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >= 1200px < 1500px )', 'moorabi'),
                                    'options' => array(
                                        '1' => esc_html__('1 item', 'moorabi'),
                                        '2' => esc_html__('2 items', 'moorabi'),
                                        '3' => esc_html__('3 items', 'moorabi'),
                                        '4' => esc_html__('4 items', 'moorabi'),
                                        '5' => esc_html__('5 items', 'moorabi'),
                                        '6' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '5',
                                ),
                                array(
                                    'id' => 'product_thumbnail_md_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Thumbnail items per row on landscape tablet', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >=992px and < 1200px )', 'moorabi'),
                                    'options' => array(
                                        '1' => esc_html__('1 item', 'moorabi'),
                                        '2' => esc_html__('2 items', 'moorabi'),
                                        '3' => esc_html__('3 items', 'moorabi'),
                                        '4' => esc_html__('4 items', 'moorabi'),
                                        '5' => esc_html__('5 items', 'moorabi'),
                                        '6' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '5',
                                ),
                                array(
                                    'id' => 'product_thumbnail_sm_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Thumbnail items per row on portrait tablet', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >=768px and < 992px )', 'moorabi'),
                                    'options' => array(
                                        '1' => esc_html__('1 item', 'moorabi'),
                                        '2' => esc_html__('2 items', 'moorabi'),
                                        '3' => esc_html__('3 items', 'moorabi'),
                                        '4' => esc_html__('4 items', 'moorabi'),
                                        '5' => esc_html__('5 items', 'moorabi'),
                                        '6' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '5',
                                ),
                                array(
                                    'id' => 'product_thumbnail_xs_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Thumbnail items per row on Mobile', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >=480  add < 768px)', 'moorabi'),
                                    'options' => array(
                                        '1' => esc_html__('1 item', 'moorabi'),
                                        '2' => esc_html__('2 items', 'moorabi'),
                                        '3' => esc_html__('3 items', 'moorabi'),
                                        '4' => esc_html__('4 items', 'moorabi'),
                                        '5' => esc_html__('5 items', 'moorabi'),
                                        '6' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '5',
                                ),
                                array(
                                    'id' => 'product_thumbnail_ts_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Thumbnail items per row on Mobile', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device < 480px)', 'moorabi'),
                                    'options' => array(
                                        '1' => esc_html__('1 item', 'moorabi'),
                                        '2' => esc_html__('2 items', 'moorabi'),
                                        '3' => esc_html__('3 items', 'moorabi'),
                                        '4' => esc_html__('4 items', 'moorabi'),
                                        '5' => esc_html__('5 items', 'moorabi'),
                                        '6' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '5',
                                ),
                            ),
                        ),
                        array(
                            'name' => 'related_product',
                            'title' => esc_html__('Related Products', 'moorabi'),
                            'fields' => array(
                                array(
                                    'id' => 'enable_woo_related',
                                    'type' => 'select',
                                    'default' => 'enable',
                                    'options' => array(
                                        'enable' => esc_html__('Enable', 'moorabi'),
                                        'disable' => esc_html__('Disable', 'moorabi'),
                                    ),
                                    'title' => esc_html__('Enable Related Products', 'moorabi'),
                                ),
                                array(
                                    'id' => 'woo_related_products_title',
                                    'type' => 'text',
                                    'title' => esc_html__('Related products title', 'moorabi'),
                                    'desc' => esc_html__('Related products title', 'moorabi'),
                                    'dependency' => array('enable_woo_related', '==', 'enable'),
                                    'default' => esc_html__('Related Products', 'moorabi'),
                                ),
                                array(
                                    'id' => 'woo_related_bg_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Related products items per row on Desktop', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >= 1500px )', 'moorabi'),
                                    'options' => array(
                                        '1' => esc_html__('1 item', 'moorabi'),
                                        '2' => esc_html__('2 items', 'moorabi'),
                                        '3' => esc_html__('3 items', 'moorabi'),
                                        '4' => esc_html__('4 items', 'moorabi'),
                                        '5' => esc_html__('5 items', 'moorabi'),
                                        '6' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '4',
                                    'dependency' => array('enable_woo_related', '==', 'enable'),
                                ),
                                array(
                                    'id' => 'woo_related_lg_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Related products items per row on Desktop', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >= 1200px < 1500px )', 'moorabi'),
                                    'options' => array(
                                        '1' => esc_html__('1 item', 'moorabi'),
                                        '2' => esc_html__('2 items', 'moorabi'),
                                        '3' => esc_html__('3 items', 'moorabi'),
                                        '4' => esc_html__('4 items', 'moorabi'),
                                        '5' => esc_html__('5 items', 'moorabi'),
                                        '6' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '4',
                                    'dependency' => array('enable_woo_related', '==', 'enable'),
                                ),
                                array(
                                    'id' => 'woo_related_md_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Related products items per row on landscape tablet', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >=992px and < 1200px )', 'moorabi'),
                                    'options' => array(
                                        '1' => esc_html__('1 item', 'moorabi'),
                                        '2' => esc_html__('2 items', 'moorabi'),
                                        '3' => esc_html__('3 items', 'moorabi'),
                                        '4' => esc_html__('4 items', 'moorabi'),
                                        '5' => esc_html__('5 items', 'moorabi'),
                                        '6' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '3',
                                    'dependency' => array('enable_woo_related', '==', 'enable'),
                                ),
                                array(
                                    'id' => 'woo_related_sm_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Related product items per row on portrait tablet', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >=768px and < 992px )', 'moorabi'),
                                    'options' => array(
                                        '1' => esc_html__('1 item', 'moorabi'),
                                        '2' => esc_html__('2 items', 'moorabi'),
                                        '3' => esc_html__('3 items', 'moorabi'),
                                        '4' => esc_html__('4 items', 'moorabi'),
                                        '5' => esc_html__('5 items', 'moorabi'),
                                        '6' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '2',
                                    'dependency' => array('enable_woo_related', '==', 'enable'),
                                ),
                                array(
                                    'id' => 'woo_related_xs_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Related products items per row on Mobile', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >=480  add < 768px)', 'moorabi'),
                                    'options' => array(
                                        '1' => esc_html__('1 item', 'moorabi'),
                                        '2' => esc_html__('2 items', 'moorabi'),
                                        '3' => esc_html__('3 items', 'moorabi'),
                                        '4' => esc_html__('4 items', 'moorabi'),
                                        '5' => esc_html__('5 items', 'moorabi'),
                                        '6' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '2',
                                    'dependency' => array('enable_woo_related', '==', 'enable'),
                                ),
                                array(
                                    'id' => 'woo_related_ts_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Related products items per row on Mobile', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device < 480px)', 'moorabi'),
                                    'options' => array(
                                        '1' => esc_html__('1 item', 'moorabi'),
                                        '2' => esc_html__('2 items', 'moorabi'),
                                        '3' => esc_html__('3 items', 'moorabi'),
                                        '4' => esc_html__('4 items', 'moorabi'),
                                        '5' => esc_html__('5 items', 'moorabi'),
                                        '6' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '2',
                                    'dependency' => array('enable_woo_related', '==', 'enable'),
                                ),
                            ),
                        ),
                        array(
                            'name' => 'crosssell_product',
                            'title' => esc_html__('Cross Sell Products', 'moorabi'),
                            'fields' => array(
                                array(
                                    'id' => 'enable_woo_crosssell',
                                    'type' => 'select',
                                    'default' => 'enable',
                                    'options' => array(
                                        'enable' => esc_html__('Enable', 'moorabi'),
                                        'disable' => esc_html__('Disable', 'moorabi'),
                                    ),
                                    'title' => esc_html__('Enable Cross Sell Products', 'moorabi'),
                                ),
                                array(
                                    'id' => 'woo_crosssell_products_title',
                                    'type' => 'text',
                                    'title' => esc_html__('Cross Sell products title', 'moorabi'),
                                    'desc' => esc_html__('Cross Sell products title', 'moorabi'),
                                    'dependency' => array('enable_woo_crosssell', '==', 'enable'),
                                    'default' => esc_html__('Cross Sell Products', 'moorabi'),
                                ),
                                array(
                                    'id' => 'woo_crosssell_bg_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Cross Sell products items per row on Desktop', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >= 1500px )', 'moorabi'),
                                    'options' => array(
                                        '1' => esc_html__('1 item', 'moorabi'),
                                        '2' => esc_html__('2 items', 'moorabi'),
                                        '3' => esc_html__('3 items', 'moorabi'),
                                        '4' => esc_html__('4 items', 'moorabi'),
                                        '5' => esc_html__('5 items', 'moorabi'),
                                        '6' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '4',
                                    'dependency' => array('enable_woo_crosssell', '==', 'enable'),
                                ),
                                array(
                                    'id' => 'woo_crosssell_lg_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Cross Sell products items per row on Desktop', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >= 1200px < 1500px )', 'moorabi'),
                                    'options' => array(
                                        '1' => esc_html__('1 item', 'moorabi'),
                                        '2' => esc_html__('2 items', 'moorabi'),
                                        '3' => esc_html__('3 items', 'moorabi'),
                                        '4' => esc_html__('4 items', 'moorabi'),
                                        '5' => esc_html__('5 items', 'moorabi'),
                                        '6' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '4',
                                    'dependency' => array('enable_woo_crosssell', '==', 'enable'),
                                ),
                                array(
                                    'id' => 'woo_crosssell_md_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Cross Sell products items per row on landscape tablet', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >=992px and < 1200px )', 'moorabi'),
                                    'options' => array(
                                        '1' => esc_html__('1 item', 'moorabi'),
                                        '2' => esc_html__('2 items', 'moorabi'),
                                        '3' => esc_html__('3 items', 'moorabi'),
                                        '4' => esc_html__('4 items', 'moorabi'),
                                        '5' => esc_html__('5 items', 'moorabi'),
                                        '6' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '3',
                                    'dependency' => array('enable_woo_crosssell', '==', 'enable'),
                                ),
                                array(
                                    'id' => 'woo_crosssell_sm_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Cross Sell product items per row on portrait tablet', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >=768px and < 992px )', 'moorabi'),
                                    'options' => array(
                                        '1' => esc_html__('1 item', 'moorabi'),
                                        '2' => esc_html__('2 items', 'moorabi'),
                                        '3' => esc_html__('3 items', 'moorabi'),
                                        '4' => esc_html__('4 items', 'moorabi'),
                                        '5' => esc_html__('5 items', 'moorabi'),
                                        '6' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '2',
                                    'dependency' => array('enable_woo_crosssell', '==', 'enable'),
                                ),
                                array(
                                    'id' => 'woo_crosssell_xs_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Cross Sell products items per row on Mobile', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >=480  add < 768px)', 'moorabi'),
                                    'options' => array(
                                        '1' => esc_html__('1 item', 'moorabi'),
                                        '2' => esc_html__('2 items', 'moorabi'),
                                        '3' => esc_html__('3 items', 'moorabi'),
                                        '4' => esc_html__('4 items', 'moorabi'),
                                        '5' => esc_html__('5 items', 'moorabi'),
                                        '6' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '2',
                                    'dependency' => array('enable_woo_crosssell', '==', 'enable'),
                                ),
                                array(
                                    'id' => 'woo_crosssell_ts_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Cross Sell products items per row on Mobile', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device < 480px)', 'moorabi'),
                                    'options' => array(
                                        '1' => esc_html__('1 item', 'moorabi'),
                                        '2' => esc_html__('2 items', 'moorabi'),
                                        '3' => esc_html__('3 items', 'moorabi'),
                                        '4' => esc_html__('4 items', 'moorabi'),
                                        '5' => esc_html__('5 items', 'moorabi'),
                                        '6' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '2',
                                    'dependency' => array('enable_woo_crosssell', '==', 'enable'),
                                ),
                            ),
                        ),
                        array(
                            'name' => 'upsell_product',
                            'title' => esc_html__('Upsell Products', 'moorabi'),
                            'fields' => array(
                                array(
                                    'id' => 'enable_woo_upsell',
                                    'type' => 'select',
                                    'default' => 'enable',
                                    'options' => array(
                                        'enable' => esc_html__('Enable', 'moorabi'),
                                        'disable' => esc_html__('Disable', 'moorabi'),
                                    ),
                                    'title' => esc_html__('Enable Upsell Products', 'moorabi'),
                                ),
                                array(
                                    'id' => 'woo_upsell_products_title',
                                    'type' => 'text',
                                    'title' => esc_html__('Upsell products title', 'moorabi'),
                                    'desc' => esc_html__('Upsell products title', 'moorabi'),
                                    'dependency' => array('enable_woo_upsell', '==', 'enable'),
                                    'default' => esc_html__('Upsell Products', 'moorabi'),
                                ),
                                array(
                                    'id' => 'woo_upsell_bg_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Upsell products items per row on Desktop', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >= 1500px )', 'moorabi'),
                                    'options' => array(
                                        '1' => esc_html__('1 item', 'moorabi'),
                                        '2' => esc_html__('2 items', 'moorabi'),
                                        '3' => esc_html__('3 items', 'moorabi'),
                                        '4' => esc_html__('4 items', 'moorabi'),
                                        '5' => esc_html__('5 items', 'moorabi'),
                                        '6' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '4',
                                    'dependency' => array('enable_woo_upsell', '==', 'enable'),
                                ),
                                array(
                                    'id' => 'woo_upsell_lg_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Upsell products items per row on Desktop', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >= 1200px < 1500px )', 'moorabi'),
                                    'options' => array(
                                        '1' => esc_html__('1 item', 'moorabi'),
                                        '2' => esc_html__('2 items', 'moorabi'),
                                        '3' => esc_html__('3 items', 'moorabi'),
                                        '4' => esc_html__('4 items', 'moorabi'),
                                        '5' => esc_html__('5 items', 'moorabi'),
                                        '6' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '4',
                                    'dependency' => array('enable_woo_upsell', '==', 'enable'),
                                ),
                                array(
                                    'id' => 'woo_upsell_md_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Upsell products items per row on landscape tablet', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >=992px and < 1200px )', 'moorabi'),
                                    'options' => array(
                                        '1' => esc_html__('1 item', 'moorabi'),
                                        '2' => esc_html__('2 items', 'moorabi'),
                                        '3' => esc_html__('3 items', 'moorabi'),
                                        '4' => esc_html__('4 items', 'moorabi'),
                                        '5' => esc_html__('5 items', 'moorabi'),
                                        '6' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '3',
                                    'dependency' => array('enable_woo_upsell', '==', 'enable'),
                                ),
                                array(
                                    'id' => 'woo_upsell_sm_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Upsell product items per row on portrait tablet', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >=768px and < 992px )', 'moorabi'),
                                    'options' => array(
                                        '1' => esc_html__('1 item', 'moorabi'),
                                        '2' => esc_html__('2 items', 'moorabi'),
                                        '3' => esc_html__('3 items', 'moorabi'),
                                        '4' => esc_html__('4 items', 'moorabi'),
                                        '5' => esc_html__('5 items', 'moorabi'),
                                        '6' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '2',
                                    'dependency' => array('enable_woo_upsell', '==', 'enable'),
                                ),
                                array(
                                    'id' => 'woo_upsell_xs_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Upsell products items per row on Mobile', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device >=480  add < 768px)', 'moorabi'),
                                    'options' => array(
                                        '1' => esc_html__('1 item', 'moorabi'),
                                        '2' => esc_html__('2 items', 'moorabi'),
                                        '3' => esc_html__('3 items', 'moorabi'),
                                        '4' => esc_html__('4 items', 'moorabi'),
                                        '5' => esc_html__('5 items', 'moorabi'),
                                        '6' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '2',
                                    'dependency' => array('enable_woo_upsell', '==', 'enable'),
                                ),
                                array(
                                    'id' => 'woo_upsell_ts_items',
                                    'type' => 'select',
                                    'title' => esc_html__('Upsell products items per row on Mobile', 'moorabi'),
                                    'desc' => esc_html__('(Screen resolution of device < 480px)', 'moorabi'),
                                    'options' => array(
                                        '1' => esc_html__('1 item', 'moorabi'),
                                        '2' => esc_html__('2 items', 'moorabi'),
                                        '3' => esc_html__('3 items', 'moorabi'),
                                        '4' => esc_html__('4 items', 'moorabi'),
                                        '5' => esc_html__('5 items', 'moorabi'),
                                        '6' => esc_html__('6 items', 'moorabi'),
                                    ),
                                    'default' => '2',
                                    'dependency' => array('enable_woo_upsell', '==', 'enable'),
                                ),
                            ),
                        ),
                    ),
                );
            }
            $options[] = array(
                'name' => 'social_settings',
                'title' => esc_html__('Social Settings', 'moorabi'),
                'icon' => 'fa fa-share-alt',
                'fields' => array(
                    array(
                        'type' => 'subheading',
                        'content' => esc_html__('Social User', 'moorabi'),
                    ),
                    array(
                        'id' => 'user_all_social',
                        'type' => 'group',
                        'title' => esc_html__('Social', 'moorabi'),
                        'button_title' => esc_html__('Add New Social', 'moorabi'),
                        'accordion_title' => esc_html__('Social Settings', 'moorabi'),
                        'fields' => array(
                            array(
                                'id' => 'title_social',
                                'type' => 'text',
                                'title' => esc_html__('Title Social', 'moorabi'),
                                'default' => 'Facebook',
                            ),
                            array(
                                'id' => 'link_social',
                                'type' => 'text',
                                'title' => esc_html__('Link Social', 'moorabi'),
                                'default' => '#',
                            ),
                            array(
                                'id' => 'icon_social',
                                'type' => 'icon',
                                'title' => esc_html__('Icon Social', 'moorabi'),
                                'default' => 'fa fa-facebook',
                            ),
                        ),
                    ),
                ),
            );
            $options[] = array(
                'name' => 'typography',
                'title' => esc_html__('Typography Options', 'moorabi'),
                'icon' => 'fa fa-font',
                'fields' => array(
                    array(
                        'id' => 'enable_typography',
                        'type' => 'switcher',
                        'title' => esc_html__('Enable Typography', 'moorabi'),
                    ),
                    array(
                        'id' => 'typography_group',
                        'type' => 'group',
                        'title' => esc_html__('Typography Options', 'moorabi'),
                        'button_title' => esc_html__('Add New Typography', 'moorabi'),
                        'accordion_title' => esc_html__('Typography Item', 'moorabi'),
                        'dependency' => array(
                            'enable_typography', '==', true,
                        ),
                        'fields' => array(
                            'element_tag' => array(
                                'id' => 'element_tag',
                                'type' => 'select',
                                'options' => array(
                                    'body' => esc_html__('Body', 'moorabi'),
                                    'h1' => esc_html__('H1', 'moorabi'),
                                    'h2' => esc_html__('H2', 'moorabi'),
                                    'h3' => esc_html__('H3', 'moorabi'),
                                    'h4' => esc_html__('H4', 'moorabi'),
                                    'h5' => esc_html__('H5', 'moorabi'),
                                    'h6' => esc_html__('H6', 'moorabi'),
                                    'p' => esc_html__('P', 'moorabi'),
                                ),
                                'title' => esc_html__('Element Tag', 'moorabi'),
                                'desc' => esc_html__('Select a Element Tag HTML', 'moorabi'),
                            ),
                            'typography_font_family' => array(
                                'id' => 'typography_font_family',
                                'type' => 'typography',
                                'title' => esc_html__('Font Family', 'moorabi'),
                                'desc' => esc_html__('Select a Font Family', 'moorabi'),
                                'chosen' => false,
                            ),
                            'body_text_color' => array(
                                'id' => 'body_text_color',
                                'type' => 'color_picker',
                                'title' => esc_html__('Body Text Color', 'moorabi'),
                            ),
                            'typography_font_size' => array(
                                'id' => 'typography_font_size',
                                'type' => 'number',
                                'default' => 14,
                                'title' => esc_html__('Font Size', 'moorabi'),
                                'desc' => esc_html__('Unit PX', 'moorabi'),
                            ),
                            'typography_line_height' => array(
                                'id' => 'typography_line_height',
                                'type' => 'number',
                                'default' => 24,
                                'title' => esc_html__('Line Height', 'moorabi'),
                                'desc' => esc_html__('Unit PX', 'moorabi'),
                            ),
                        ),
                        'default' => array(
                            array(
                                'element_tag' => 'body',
                                'typography_font_family' => 'Arial',
                                'body_text_color' => '#868686',
                                'typography_font_size' => 14,
                                'typography_line_height' => 24,
                            ),
                        ),
                    ),
                ),
            );
            $options[] = array(
                'name' => 'backup_option',
                'title' => esc_html__('Backup Options', 'moorabi'),
                'icon' => 'fa fa-bold',
                'fields' => array(
                    array(
                        'type' => 'backup',
                        'title' => esc_html__('Backup Field', 'moorabi'),
                    ),
                ),
            );

            return $options;
        }

        function metabox_options($options)
        {
            $options = array();
            // -----------------------------------------
            // Page Meta box Options                   -
            // -----------------------------------------
            $options[] = array(
                'id' => '_custom_metabox_theme_options',
                'title' => esc_html__('Custom Theme Options', 'moorabi'),
                'post_type' => 'page',
                'context' => 'normal',
                'priority' => 'high',
                'sections' => array(
                    'general' => array(
                        'name' => 'main_settings',
                        'title' => esc_html__('General Settings', 'moorabi'),
                        'icon' => 'fa fa-wordpress',
                        'fields' => array(
                            array(
                                'id' => 'metabox_logo',
                                'type' => 'image',
                                'title' => esc_html__('Main Logo', 'moorabi'),
                            ),
                        ),
                    ),
                    'header' => array(
                        'name' => 'header',
                        'title' => esc_html__('Header Settings', 'moorabi'),
                        'icon' => 'fa fa-header',
                        'fields' => array(
                            array(
                                'id' => 'enable_header',
                                'type' => 'switcher',
                                'title' => esc_html__('Enable Custom header', 'moorabi'),
                            ),
                            array(
                                'id' => 'metabox_header_options',
                                'type' => 'select_preview',
                                'title' => esc_html__('Header Layout', 'moorabi'),
                                'desc' => esc_html__('Select a header layout', 'moorabi'),
                                'options' => self::get_header_preview(),
                                'default' => 'style-01',
                                'dependency' => array('enable_header', '==', true),
                            ),
                            array(
                                'id' => 'metabox_header_color',
                                'type' => 'select',
                                'title' => esc_html__('Header Color', 'moorabi'),
                                'options' => array(
                                    'header-dark' => esc_html__('Header Dark', 'moorabi'),
                                    'header-light' => esc_html__('Header Light', 'moorabi'),
                                ),
                                'default' => 'header-dark',
                                'dependency' => array('enable_header', '==', true),
                            ),
                            array(
                                'id' => 'metabox_enable_header_transparent',
                                'type' => 'switcher',
                                'title' => esc_html__('Enable Header Transparent', 'moorabi'),
                                'dependency' => array('enable_header', '==', true),
                            ),
                        ),
                    ),
                    'banner_page' => array(
                        'name' => 'banner_page',
                        'title' => esc_html__('Banner Page Settings', 'moorabi'),
                        'icon' => 'fa fa-window-maximize',
                        'fields' => array(
                            array(
                                'id' => 'enable_banner',
                                'type' => 'switcher',
                                'title' => esc_html__('Enable Custom Banner', 'moorabi'),
                            ),
                            array(
                                'id' => 'metabox_banner_type',
                                'type' => 'select',
                                'title' => esc_html__('Banner Type', 'moorabi'),
                                'options' => array(
                                    'no_background' => esc_html__('No Background ', 'moorabi'),
                                    'has_background' => esc_html__('Has Background', 'moorabi'),
                                    'rev_background' => esc_html__('Revolution', 'moorabi'),
                                    'disable' => esc_html__('Disable', 'moorabi'),
                                ),
                                'default' => 'no_background',
                                'dependency' => array('enable_banner', '==', 'true'),
                            ),
                            array(
                                'id' => 'metabox_banner_full_width',
                                'type' => 'switcher',
                                'title' => esc_html__('Banner Full Width', 'moorabi'),
                                'default' => true,
                                'dependency' => array('enable_banner|metabox_banner_type', '==|any', 'true|has_background,rev_background'),
                            ),
                            array(
                                'id' => 'metabox_banner_image',
                                'type' => 'image',
                                'title' => esc_html__('Banner Image', 'moorabi'),
                                'add_title' => esc_html__('Upload', 'moorabi'),
                                'dependency' => array('enable_banner|metabox_banner_type', '==|==', 'true|has_background'),
                            ),
                            array(
                                'id' => 'metabox_banner_rev_slide',
                                'type' => 'select',
                                'options' => self::rev_slide_options(),
                                'title' => esc_html__('Select Slide', 'moorabi'),
                                'dependency' => array('enable_banner|metabox_banner_type', '==|==', 'true|rev_background'),
                            ),
                            array(
                                'id' => 'disable_revolution_on_mobile',
                                'type' => 'switcher',
                                'title' => esc_html__('Disable Revolution On Mobile', 'moorabi'),
                                'default' => false,
                                'dependency' => array('enable_banner|metabox_banner_type', '==', 'true|rev_background'),
                            ),
                        ),
                    ),
                    'footer' => array(
                        'name' => 'footer',
                        'title' => esc_html__('Footer Settings', 'moorabi'),
                        'icon' => 'fa fa-underline',
                        'fields' => array(
                            array(
                                'id' => 'enable_footer',
                                'type' => 'switcher',
                                'title' => esc_html__('Enable Custom Footer', 'moorabi'),
                            ),
                            array(
                                'id' => 'metabox_footer_options',
                                'type' => 'select_preview',
                                'title' => esc_html__('Select Footer Builder', 'moorabi'),
                                'options' => self::get_footer_preview(),
                                'default' => 'default',
                                'dependency' => array('enable_footer', '==', true),
                            ),
                        ),
                    ),
                ),
            );
            // -----------------------------------------
            // Post Meta box Options                   -
            // -----------------------------------------
            $options[] = array(
                'id' => '_custom_metabox_post_options',
                'title' => esc_html__('Custom Post Options', 'moorabi'),
                'post_type' => 'post',
                'context' => 'normal',
                'priority' => 'high',
                'sections' => array(
                    array(
                        'name' => 'gallery_settings',
                        'title' => esc_html__('Gallery Settings', 'moorabi'),
                        'fields' => array(
                            array(
                                'id' => 'gallery_post',
                                'type' => 'gallery',
                                'title' => esc_html__('Gallery', 'moorabi'),
                            ),
                        ),
                    ),
                    array(
                        'name' => 'video_settings',
                        'title' => esc_html__('Video Settings', 'moorabi'),
                        'fields' => array(
                            array(
                                'id' => 'video_post',
                                'type' => 'upload',
                                'title' => esc_html__('Video Url', 'moorabi'),
                                'settings' => array(
                                    'upload_type' => 'video',
                                    'button_title' => esc_html__('Video', 'moorabi'),
                                    'frame_title' => esc_html__('Select a video', 'moorabi'),
                                    'insert_title' => esc_html__('Use this video', 'moorabi'),
                                ),
                                'desc' => esc_html__('Supports video Url Youtube and upload.', 'moorabi'),
                            ),
                        ),
                    ),
                ),
            );
            // -----------------------------------------
            // Page Side Meta box Options              -
            // -----------------------------------------
            $options[] = array(
                'id' => '_custom_page_side_options',
                'title' => esc_html__('Custom Page Side Options', 'moorabi'),
                'post_type' => 'page',
                'context' => 'side',
                'priority' => 'default',
                'sections' => array(
                    array(
                        'name' => 'page_option',
                        'fields' => array(
                            array(
                                'id' => 'page_sidebar_layout',
                                'type' => 'image_select',
                                'title' => esc_html__('Single Post Sidebar Position', 'moorabi'),
                                'desc' => esc_html__('Select sidebar position on Page.', 'moorabi'),
                                'options' => array(
                                    'left' => esc_attr(' data:image/png;base64,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'),
                                    'right' => esc_attr(' data:image/png;base64,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'),
                                    'full' => esc_attr(' data:image/png;base64,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'),
                                ),
                                'default' => 'left',
                            ),
                            array(
                                'id' => 'page_sidebar',
                                'type' => 'select',
                                'title' => esc_html__('Page Sidebar', 'moorabi'),
                                'options' => self::get_sidebar_options(),
                                'default' => 'widget-area',
                                'dependency' => array('page_sidebar_layout_full', '==', false),
                            ),
                            array(
                                'id' => 'page_extra_class',
                                'type' => 'text',
                                'title' => esc_html__('Extra Class', 'moorabi'),
                            ),
                        ),
                    ),
                ),
            );
            // -----------------------------------------
            // Page Product Meta box Options      	   -
            // -----------------------------------------
            $global_product_style = Moorabi_Functions::moorabi_get_option('product_style', 'horizontal_thumbnail');
            $all_product_styles = array(
                'horizontal_thumbnail' => esc_html__('Horizontal Thumbnail', 'moorabi'),
                'vertical_thumbnail' => esc_html__('Vertical Thumbnail', 'moorabi'),
                'gallery_thumbnail' => esc_html__('Gallery Thumbnail', 'moorabi'),
                'sticky_detail' => esc_html__('Sticky Detail', 'moorabi'),
            );
            $global_product_style_text = isset($all_product_styles[$global_product_style]) ? $all_product_styles[$global_product_style] : $global_product_style;
            $options[] = array(
                'id' => '_custom_product_woo_options',
                'title' => esc_html__('Custom Product Options', 'moorabi'),
                'post_type' => 'product',
                'context' => 'side',
                'priority' => 'high',
                'sections' => array(
                    array(
                        'name' => 'product_detail',
                        'fields' => array(
                            array(
                                'id' => 'product_style',
                                'type' => 'select',
                                'title' => esc_html__('Product Style', 'moorabi'),
                                'options' => array(
                                    'global' => sprintf(esc_html__('Use Theme Options: %s', 'moorabi'), $global_product_style_text),
                                    'horizontal_thumbnail' => esc_html__('Horizontal Thumbnail', 'moorabi'),
                                    'vertical_thumbnail' => esc_html__('Vertical Thumbnail', 'moorabi'),
                                    'gallery_thumbnail' => esc_html__('Gallery Thumbnail', 'moorabi'),
                                    'sticky_detail' => esc_html__('Sticky Detail', 'moorabi'),
                                ),
                            ),
                            array(
                                'id' => 'size_guide',
                                'type' => 'upload',
                                'title' => esc_html__('Size guide', 'moorabi'),
                            ),
                            array(
                                'id' => 'product_options',
                                'type' => 'select',
                                'title' => esc_html__('Format Product', 'moorabi'),
                                'options' => array(
                                    'video' => esc_html__('Video', 'moorabi'),
                                    '360deg' => esc_html__('360 Degree', 'moorabi'),
                                ),
                            ),
                            array(
                                'id' => 'degree_product_gallery',
                                'type' => 'gallery',
                                'title' => esc_html__('360 Degree Product', 'moorabi'),
                                'dependency' => array('product_options', '==', '360deg'),
                            ),
                            array(
                                'id' => 'video_product_url',
                                'type' => 'upload',
                                'title' => esc_html__('Video Url', 'moorabi'),
                                'dependency' => array('product_options', '==', 'video'),
                            ),
                        ),
                    ),
                ),
            );

            return $options;
        }

        function taxonomy_options($options)
        {
            $options = array();
            // -----------------------------------------
            // Taxonomy Options                        -
            // -----------------------------------------
            $options[] = array(
                'id' => '_custom_taxonomy_options',
                'taxonomy' => 'product_cat', // category, post_tag or your custom taxonomy name
                'fields' => array(
                    array(
                        'id' => 'icon_taxonomy',
                        'type' => 'icon',
                        'title' => esc_html__('Icon Taxonomy', 'moorabi'),
                        'default' => '',
                    ),
                ),
            );

            return $options;
        }
    }

    new Moorabi_ThemeOption();
}