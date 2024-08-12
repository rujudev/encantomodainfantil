<?php
// Prevent direct access to this file
defined('ABSPATH') || die('Direct access to this file is not allowed.');
/**
 * Core class.
 *
 * @package  Moorabi
 * @since    1.0
 */
if (!class_exists('Moorabi_framework')) {
    class Moorabi_framework
    {
        /**
         * Define theme version.
         *
         * @var  string
         */
        const VERSION = '1.0.0';

        public function __construct()
        {
            add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
            add_filter('body_class', array($this, 'body_class'));
            add_filter('moorabi_carousel_data_attributes', array($this, 'moorabi_carousel_data_attributes'), 10, 2);
            add_filter('moorabi_get_products', array($this, 'moorabi_get_products'), 10, 3);
            /* CUSTOM IMAGE ELEMENT */
            if (!is_admin()) {
                add_filter('wp_kses_allowed_html', array($this, 'moorabi_wp_kses_allowed_html'), 10, 2);
            }
            add_filter('moorabi_resize_image', array($this, 'moorabi_resize_image'), 10, 5);
            $this->includes();
        }

        function body_class($classes)
        {
            $my_theme = wp_get_theme();
            $classes[] = $my_theme->get('Name') . "-" . $my_theme->get('Version');

            return $classes;
        }

        public function enqueue_scripts($hook)
        {
            /* CUSTOM FRAMEWORK */
            wp_enqueue_style('flaticon', get_theme_file_uri('/assets/fonts/flaticon/flaticon.css'), array(), '1.0');
            wp_enqueue_style('custom-admin', get_theme_file_uri('/framework/assets/admin.css'), array(), '1.0');
        }

        public function includes()
        {
            /* Classes */
            require_once get_parent_theme_file_path('/framework/includes/class-tgm-plugin-activation.php');
            require_once get_parent_theme_file_path('/framework/includes/breadcrumbs.php');
            /*Plugin load*/
            require_once get_parent_theme_file_path('/framework/settings/plugins-load.php');
            /*Theme Functions*/
            require_once get_parent_theme_file_path('/framework/includes/blog-functions.php');
            require_once get_parent_theme_file_path('/framework/includes/theme-functions.php');
            if (class_exists('Moorabi_Toolkit')) {
                require_once get_parent_theme_file_path('/framework/settings/theme-options.php');
            }
            /* Custom css and js*/
            require_once get_parent_theme_file_path('/framework/settings/custom-css.php');
            if (class_exists('Elementor\Plugin')) {
                require_once get_parent_theme_file_path('/framework/includes/elementor-plugin.php');
            }
            if (class_exists('WooCommerce')) {
                require_once get_parent_theme_file_path('/framework/includes/woo-functions.php');
            }
        }

        function moorabi_carousel_data_attributes($prefix = '', $atts)
        {
            $responsive = array();
            $slick = array();
            $results = '';
            if (isset($atts[$prefix . 'autoplay']) && $atts[$prefix . 'autoplay'] == 'true') {
                $slick['autoplay'] = true;
            }
            if (isset($atts[$prefix . 'autoplayspeed']) && $atts[$prefix . 'autoplay'] == 'true') {
                $slick['autoplaySpeed'] = intval($atts[$prefix . 'autoplayspeed']);
            }
            if (isset($atts[$prefix . 'navigation'])) {
                $slick['arrows'] = $atts[$prefix . 'navigation'] == 'true' ? true : false;
            }
            if (isset($atts[$prefix . 'slide_margin'])) {
                $slick['slidesMargin'] = intval($atts[$prefix . 'slide_margin']);
            }
            if (isset($atts[$prefix . 'dots'])) {
                $slick['dots'] = $atts[$prefix . 'dots'] == 'true' ? true : false;
            }
            if (isset($atts[$prefix . 'loop'])) {
                $slick['infinite'] = $atts[$prefix . 'loop'] == 'true' ? true : false;
            }
            if (isset($atts[$prefix . 'fade'])) {
                $slick['fade'] = $atts[$prefix . 'fade'] == 'true' ? true : false;
            }
            if (isset($atts[$prefix . 'slidespeed'])) {
                $slick['speed'] = intval($atts[$prefix . 'slidespeed']);
            }
            if (isset($atts[$prefix . 'ls_items'])) {
                $slick['slidesToShow'] = intval($atts[$prefix . 'ls_items']);
            }
            if (isset($atts[$prefix . 'slidestoscroll'])) {
                $slick['slidesToScroll'] = intval($atts[$prefix . 'slidestoscroll']);
            }
            if (isset($atts[$prefix . 'vertical']) && $atts[$prefix . 'vertical'] == 'true') {
                $slick['vertical'] = true;
            }
            if (isset($atts[$prefix . 'center_mode']) && $atts[$prefix . 'center_mode'] == 'true') {
                $slick['centerMode'] = true;
            }
            if (isset($atts[$prefix . 'focus_select']) && $atts[$prefix . 'focus_select'] == 'true') {
                $slick['focusOnSelect'] = true;
            }
            if (isset($atts[$prefix . 'verticalswiping']) && $atts[$prefix . 'verticalswiping'] == 'true') {
                $slick['verticalSwiping'] = true;
            }
            if (isset($atts[$prefix . 'number_row'])) {
                $slick['rows'] = intval($atts[$prefix . 'number_row']);
            }
            $results .= ' data-slick = ' . json_encode($slick) . ' ';
            if (isset($atts[$prefix . 'ts_items'])) {
                $responsive[] = array(
                    'breakpoint' => 480,
                    'settings' => array(
                        'slidesToShow' => intval($atts[$prefix . 'ts_items']),
                    ),
                );
            }
            if (isset($atts[$prefix . 'xs_items'])) {
                $responsive[] = array(
                    'breakpoint' => 768,
                    'settings' => array(
                        'slidesToShow' => intval($atts[$prefix . 'xs_items']),
                    ),
                );
            }
            if (isset($atts[$prefix . 'sm_items'])) {
                $responsive[] = array(
                    'breakpoint' => 992,
                    'settings' => array(
                        'slidesToShow' => intval($atts[$prefix . 'sm_items']),
                    ),
                );
            }
            if (isset($atts[$prefix . 'md_items'])) {
                $responsive[] = array(
                    'breakpoint' => 1200,
                    'settings' => array(
                        'slidesToShow' => intval($atts[$prefix . 'md_items']),
                    ),
                );
            }
            if (isset($atts[$prefix . 'lg_items'])) {
                $responsive[] = array(
                    'breakpoint' => 1500,
                    'settings' => array(
                        'slidesToShow' => intval($atts[$prefix . 'lg_items']),
                    ),
                );
            }
            if (isset($atts[$prefix . 'responsive_vertical']) && $atts[$prefix . 'responsive_vertical'] >= 480) {
                $responsive[0]['settings']['vertical'] = false;
            }
            if (isset($atts[$prefix . 'responsive_vertical']) && $atts[$prefix . 'responsive_vertical'] >= 768) {
                $responsive[1]['settings']['vertical'] = false;
            }
            if (isset($atts[$prefix . 'responsive_vertical']) && $atts[$prefix . 'responsive_vertical'] >= 992) {
                $responsive[2]['settings']['vertical'] = false;
            }
            if (isset($atts[$prefix . 'responsive_vertical']) && $atts[$prefix . 'responsive_vertical'] >= 1200) {
                $responsive[3]['settings']['vertical'] = false;
            }
            if (isset($atts[$prefix . 'responsive_vertical']) && $atts[$prefix . 'responsive_vertical'] >= 1500) {
                $responsive[4]['settings']['vertical'] = false;
            }
            if (isset($atts[$prefix . 'responsive_rows']) && $atts[$prefix . 'responsive_rows'] >= 480) {
                $responsive[0]['settings']['rows'] = 2;
            }
            if (isset($atts[$prefix . 'responsive_rows']) && $atts[$prefix . 'responsive_rows'] >= 768) {
                $responsive[1]['settings']['rows'] = 2;
            }
            if (isset($atts[$prefix . 'responsive_rows']) && $atts[$prefix . 'responsive_rows'] >= 992) {
                $responsive[2]['settings']['rows'] = 2;
            }
            if (isset($atts[$prefix . 'responsive_rows']) && $atts[$prefix . 'responsive_rows'] >= 1200) {
                $responsive[3]['settings']['rows'] = 2;
            }
            if (isset($atts[$prefix . 'responsive_rows']) && $atts[$prefix . 'responsive_rows'] >= 1500) {
                $responsive[4]['settings']['rows'] = 2;
            }
            if (isset($atts[$prefix . 'ts_slide_margin'])) {
                $responsive[0]['settings']['slidesMargin'] = $atts[$prefix . 'ts_slide_margin'];
            }
            if (isset($atts[$prefix . 'xs_slide_margin'])) {
                $responsive[1]['settings']['slidesMargin'] = $atts[$prefix . 'xs_slide_margin'];
            }
            if (isset($atts[$prefix . 'sm_slide_margin'])) {
                $responsive[2]['settings']['slidesMargin'] = $atts[$prefix . 'sm_slide_margin'];
            }
            if (isset($atts[$prefix . 'md_slide_margin'])) {
                $responsive[3]['settings']['slidesMargin'] = $atts[$prefix . 'md_slide_margin'];
            }
            if (isset($atts[$prefix . 'lg_slide_margin'])) {
                $responsive[4]['settings']['slidesMargin'] = $atts[$prefix . 'lg_slide_margin'];
            }
            $results .= 'data-responsive = ' . json_encode($responsive) . ' ';
            $results = apply_filters('moorabi_filter_carousel_data_attributes', $results, $prefix, $atts);

            return htmlspecialchars($results);
        }

        function moorabi_get_products($atts, $args = array(), $ignore_sticky_posts = 1)
        {
            extract($atts);
            $target = isset($target) ? $target : 'recent-product';
            $meta_query = WC()->query->get_meta_query();
            $tax_query = WC()->query->get_tax_query();
            $args['post_type'] = 'product';
            if (isset($atts['taxonomy']) and $atts['taxonomy']) {
                $tax_query[] = array(
                    'taxonomy' => 'product_cat',
                    'terms' => is_array($atts['taxonomy']) ? array_map('sanitize_title', $atts['taxonomy']) : array_map('sanitize_title', explode(',', $atts['taxonomy'])),
                    'field' => 'slug',
                    'operator' => 'IN',
                );
            }
            $args['post_status'] = 'publish';
            $args['ignore_sticky_posts'] = $ignore_sticky_posts;
            $args['suppress_filter'] = true;
            if (isset($atts['per_page']) && $atts['per_page']) {
                $args['posts_per_page'] = $atts['per_page'];
            }
            $ordering_args = WC()->query->get_catalog_ordering_args();
            $orderby = isset($atts['orderby']) ? $atts['orderby'] : $ordering_args['orderby'];
            $order = isset($atts['order']) ? $atts['order'] : $ordering_args['order'];
            $meta_key = isset($atts['meta_key']) ? $atts['meta_key'] : $ordering_args['meta_key'];
            switch ($target):
                case 'best-selling' :
                    $args['meta_key'] = 'total_sales';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = $order;
                    break;
                case 'top-rated' :
                    $args['meta_key'] = '_wc_average_rating';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = $order;
                    break;
                case 'product-category' :
                    $args['orderby'] = $orderby;
                    $args['order'] = $order;
                    $args['meta_key'] = $meta_key;
                    break;
                case 'products' :
                    $args['posts_per_page'] = -1;
                    if (!empty($ids)) {
                        $args['post__in'] = array_map('trim', explode(',', $ids));
                        $args['orderby'] = 'post__in';
                    }
                    if (!empty($skus)) {
                        $meta_query[] = array(
                            'key' => '_sku',
                            'value' => array_map('trim', explode(',', $skus)),
                            'compare' => 'IN',
                        );
                    }
                    break;
                case 'featured_products' :
                    $tax_query[] = array(
                        'taxonomy' => 'product_visibility',
                        'field' => 'name',
                        'terms' => 'featured',
                        'operator' => 'IN',
                    );
                    break;
                case 'on_new' :
                    $newness = Moorabi_Functions::moorabi_get_option('moorabi_product_newness', 7);    // Newness in days as defined by option
                    $args['date_query'] = array(
                        array(
                            'after' => '' . $newness . ' days ago',
                            'inclusive' => true,
                        ),
                    );
                    if ($orderby == '_sale_price') {
                        $orderby = 'date';
                        $order = 'DESC';
                    }
                    $args['orderby'] = $orderby;
                    $args['order'] = $order;
                    break;
                case 'on_sale' :
                    $product_ids_on_sale = wc_get_product_ids_on_sale();
                    $args['post__in'] = array_merge(array(0), $product_ids_on_sale);
                    if ($orderby == '_sale_price') {
                        $orderby = 'date';
                        $order = 'DESC';
                    }
                    $args['orderby'] = $orderby;
                    $args['order'] = $order;
                    break;
                default :
                    $args['orderby'] = $orderby;
                    $args['order'] = $order;
                    if (isset($ordering_args['meta_key'])) {
                        $args['meta_key'] = $ordering_args['meta_key'];
                    }
                    WC()->query->remove_ordering_args();
                    break;
            endswitch;
            $args['meta_query'] = $meta_query;
            $args['tax_query'] = $tax_query;

            return $products = new WP_Query(apply_filters('woocommerce_shortcode_products_query', $args, $atts));
        }

        function moorabi_wp_kses_allowed_html($allowedposttags, $context)
        {
            $allowedposttags['img']['data-src'] = true;
            $allowedposttags['img']['data-srcset'] = true;
            $allowedposttags['img']['data-sizes'] = true;

            return $allowedposttags;
        }

        function moorabi_get_attachment_image($attachment_id, $src, $width, $height)
        {
            $html = '';
            if ($src) {
                $hwstring = image_hwstring($width, $height);
                $size_class = $width . 'x' . $height;
                $attachment = get_post($attachment_id);
                $attr = array(
                    'src' => $src,
                    'class' => "img-responsive attachment-$size_class size-$size_class",
                    'alt' => trim(strip_tags(get_post_meta($attachment_id, '_wp_attachment_image_alt', true))),
                );
                // Generate 'srcset' and 'sizes' if not already present.
                /**
                 * Filters the list of attachment image attributes.
                 *
                 * @since 2.8.0
                 *
                 * @param array $attr Attributes for the image markup.
                 * @param WP_Post $attachment Image attachment post.
                 * @param string|array $size Requested size. Image size or array of width and height values
                 *                                 (in that order). Default 'thumbnail'.
                 */
                $attr = apply_filters('moorabi_get_attachment_image_attributes', $attr, $attachment);
                $attr = array_map('esc_attr', $attr);
                $html = rtrim("<img $hwstring");
                foreach ($attr as $name => $value) {
                    $html .= " $name=" . '"' . $value . '"';
                }
                $html .= ' />';
            }

            return $html;
        }

        function moorabi_resize_image($attachment_id = null, $width, $height, $crop = false)
        {
            $original = false;
            $image_src = array();
            $vt_image = '';
            if (is_singular() && !$attachment_id) {
                if (has_post_thumbnail() && !post_password_required()) {
                    $attachment_id = get_post_thumbnail_id();
                }
            }
            if ($attachment_id) {
                $image_src = wp_get_attachment_image_src($attachment_id, 'full');
                $actual_file_path = get_attached_file($attachment_id);
            }
            if ($width == false && $height == false) {
                $original = true;
            }
            if (!empty($actual_file_path) && file_exists($actual_file_path)) {
                if ($original == false && ($image_src[1] > $width || $image_src[2] > $height)) {
                    $file_info = pathinfo($actual_file_path);
                    $extension = '.' . $file_info['extension'];
                    $no_ext_path = $file_info['dirname'] . '/' . $file_info['filename'];
                    $cropped_img_path = $no_ext_path . '-' . $width . 'x' . $height . $extension;
                    /* start */
                    if (file_exists($cropped_img_path)) {
                        $cropped_img_url = str_replace(basename($image_src[0]), basename($cropped_img_path), $image_src[0]);
                        $vt_image = array(
                            'url' => $cropped_img_url,
                            'width' => $width,
                            'height' => $height,
                            'img' => $this->moorabi_get_attachment_image($attachment_id, $cropped_img_url, $width, $height),
                        );

                        return $vt_image;
                    }
                    if ($crop == false) {
                        $proportional_size = wp_constrain_dimensions($image_src[1], $image_src[2], $width, $height);
                        $resized_img_path = $no_ext_path . '-' . $proportional_size[0] . 'x' . $proportional_size[1] . $extension;
                        if (file_exists($resized_img_path)) {
                            $resized_img_url = str_replace(basename($image_src[0]), basename($resized_img_path), $image_src[0]);
                            $vt_image = array(
                                'url' => $resized_img_url,
                                'width' => $proportional_size[0],
                                'height' => $proportional_size[1],
                                'img' => $this->moorabi_get_attachment_image($attachment_id, $resized_img_url, $proportional_size[0], $proportional_size[1]),
                            );

                            return $vt_image;
                        }
                    }
                    /*no cache files - let's finally resize it*/
                    $img_editor = wp_get_image_editor($actual_file_path);
                    if (is_wp_error($img_editor) || is_wp_error($img_editor->resize($width, $height, $crop))) {
                        return array(
                            'url' => '',
                            'width' => '',
                            'height' => '',
                            'img' => '',
                        );
                    }
                    $new_img_path = $img_editor->generate_filename();
                    if (is_wp_error($img_editor->save($new_img_path))) {
                        return array(
                            'url' => '',
                            'width' => '',
                            'height' => '',
                            'img' => '',
                        );
                    }
                    if (!is_string($new_img_path)) {
                        return array(
                            'url' => '',
                            'width' => '',
                            'height' => '',
                            'img' => '',
                        );
                    }
                    $new_img_size = getimagesize($new_img_path);
                    $new_img = str_replace(basename($image_src[0]), basename($new_img_path), $image_src[0]);
                    $vt_image = array(
                        'url' => $new_img,
                        'width' => $new_img_size[0],
                        'height' => $new_img_size[1],
                        'img' => $this->moorabi_get_attachment_image($attachment_id, $new_img, $new_img_size[0], $new_img_size[1]),
                    );

                    return $vt_image;
                }
                $vt_image = array(
                    'url' => $image_src[0],
                    'width' => $image_src[1],
                    'height' => $image_src[2],
                    'img' => $this->moorabi_get_attachment_image($attachment_id, $image_src[0], $image_src[1], $image_src[2]),
                );
            }

            return $vt_image;
        }
    }

    new Moorabi_framework();
}