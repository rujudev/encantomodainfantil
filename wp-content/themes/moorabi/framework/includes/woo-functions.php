<?php
/***
 * Core Name: WooCommerce
 * Version: 1.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
/**
 *
 * REMOVE CSS
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');
/**
 * HOOK TEMPLATE
 */
add_action('init', 'product_per_page_request');
add_action('init', 'product_display_mode_request');
add_action('wp_loaded', 'moorabi_action_wp_loaded');
add_action('moorabi_function_shop_loop_item_countdown', 'moorabi_function_shop_loop_item_countdown', 10);
add_action('moorabi_function_shop_loop_process_variable', 'moorabi_function_shop_loop_process_variable', 10);
if (!function_exists('product_per_page_request')) {
    function product_per_page_request()
    {
        if (isset($_POST['perpage_action_form'])) {
            wp_redirect(
                add_query_arg(
                    array(
                        'product_per_page' => $_POST['product_per_page_filter'],
                    ), $_POST['perpage_action_form']
                )
            );
            exit();
        }
    }
}
if (!function_exists('product_display_mode_request')) {
    function product_display_mode_request()
    {
        if (isset($_POST['display_mode_action'])) {
            wp_redirect(
                add_query_arg(
                    array(
                        'shop_display_mode' => $_POST['display_mode_value'],
                    ), $_POST['display_mode_action']
                )
            );
            exit();
        }
    }
}
if (!function_exists('moorabi_action_wp_loaded')) {
    function moorabi_action_wp_loaded()
    {
        /* QUICK VIEW */
        if (class_exists('YITH_WCQV_Frontend')) {
            // Class frontend
            $enable = get_option('yith-wcqv-enable') == 'yes' ? true : false;
            $enable_on_mobile = get_option('yith-wcqv-enable-mobile') == 'yes' ? true : false;
            // Class frontend
            if ((!wp_is_mobile() && $enable) || (wp_is_mobile() && $enable_on_mobile && $enable)) {
                remove_action('woocommerce_after_shop_loop_item', array(
                    YITH_WCQV_Frontend::get_instance(),
                    'yith_add_quick_view_button'
                ), 15);
                add_action('moorabi_function_shop_loop_item_quickview', array(
                    YITH_WCQV_Frontend::get_instance(),
                    'yith_add_quick_view_button'
                ), 5);
            }
        }
        /* WISH LIST */
        if (defined('YITH_WCWL')) {
            add_action('moorabi_function_shop_loop_item_wishlist', function () {
                echo do_shortcode("[yith_wcwl_add_to_wishlist]");
            }, 1);
        }
    }
}
if (!function_exists('moorabi_get_max_date_sale')) {
    function moorabi_get_max_date_sale($product_id)
    {
        $date_now = current_time('timestamp', 0);
        // Get variations
        $args = array(
            'post_type' => 'product_variation',
            'post_status' => array('private', 'publish'),
            'numberposts' => -1,
            'orderby' => 'menu_order',
            'order' => 'asc',
            'post_parent' => $product_id,
        );
        $variations = get_posts($args);
        $variation_ids = array();
        if ($variations) {
            foreach ($variations as $variation) {
                $variation_ids[] = $variation->ID;
            }
        }
        $sale_price_dates_to = false;
        if (!empty($variation_ids)) {
            global $wpdb;
            $sale_price_dates_to = $wpdb->get_var("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = '_sale_price_dates_to' and post_id IN(" . join(',', $variation_ids) . ") ORDER BY meta_value DESC LIMIT 1");
            if ($sale_price_dates_to != '') {
                return $sale_price_dates_to;
            }
        }
        if (!$sale_price_dates_to) {
            $sale_price_dates_to = get_post_meta($product_id, '_sale_price_dates_to', true);
            $sale_price_dates_from = get_post_meta($product_id, '_sale_price_dates_from', true);
            if ($sale_price_dates_to == '' || $date_now < $sale_price_dates_from) {
                $sale_price_dates_to = '0';
            }
        }

        return $sale_price_dates_to;
    }
}
if (!function_exists('moorabi_function_shop_loop_item_countdown')) {
    function moorabi_function_shop_loop_item_countdown()
    {
        global $product;
        $date = moorabi_get_max_date_sale($product->get_id());
        if ($date > 0) {
            ?>
            <div class="countdown-product">
                <?php if (is_product()) { ?>
                    <h4 class="deals-title"><?php echo esc_html__('Hungry Up ! Deals end in :', 'moorabi'); ?></h4>
                <?php } ?>
                <div class="moorabi-countdown"
                     data-datetime="<?php echo date('m/j/Y g:i:s', $date); ?>">
                </div>
            </div>
            <?php
        }
    }
}
if (!function_exists('moorabi_function_shop_loop_process_variable')) {
    function moorabi_function_shop_loop_process_variable()
    {
        global $product;
        $units_sold = get_post_meta($product->get_id(), 'total_sales', true);
        $availability = $product->get_stock_quantity();
        if ($availability) {
            $total_percent = $availability + $units_sold;
            $percent       = 100 - round((($units_sold / $total_percent) * 100), 0);
        } else {
            $percent = 0;
        }
        ?>
        <div class="process-valiable">
            <div class="valiable-text">
                <span class="text">
                    <?php
                    echo esc_attr($percent) . '%';
                    echo esc_html__(' Already Sold', 'moorabi');
                    ?>
                </span>
                <span class="text">
                    <?php echo esc_html__('Available: ', 'moorabi') ?>
                    <span>
                        <?php
                        if ($availability != '') {
                            echo esc_html($availability);
                        } else {
                            echo esc_html__('Unlimit', 'moorabi');
                        }
                        ?>
                    </span>
                </span>
            </div>
            <span class="valiable-total total">
                <span class="process" data-width="<?php echo esc_attr($percent) . '%' ?>"></span>
            </span>
        </div>
        <?php
    }
}
if (!function_exists('moorabi_single_product_size_guide')) {
    function moorabi_single_product_size_guide()
    {
        global $product;
        $product_meta = get_post_meta($product->get_id(), '_custom_product_woo_options', true);
        if (isset($product_meta['size_guide']) && $product_meta['size_guide'] != '') { ?>
            <span class="size-guide-button popup-inline" data-toggle="modal"
                  data-target="#popup-size-guide"><?php echo esc_html__('Size Guide', 'moorabi') ?></span>
            <div class="modal fade" id="popup-size-guide" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="<?php echo esc_attr__('Close', 'moorabi'); ?>">
                            <?php echo esc_html__('x', 'moorabi'); ?>
                        </button>
                        <div class="modal-inner">
                            <img src="<?php echo esc_url($product_meta['size_guide']) ?>" alt="<?php echo esc_attr__('Size Guide', 'moorabi') ?>">
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
/**
 *
 * HOOK MINI CART
 */
add_filter('woocommerce_add_to_cart_fragments', 'moorabi_cart_link_fragment');
add_action('moorabi_header_mini_cart', 'moorabi_header_mini_cart');
add_action('moorabi_header_wishlist', 'moorabi_header_wishlist');
if (!function_exists('moorabi_header_cart_link')) {
    function moorabi_header_cart_link()
    {
        ?>
        <div class="shopcart-dropdown block-cart-link" data-moorabi="moorabi-dropdown">
            <a class="block-link link-dropdown" href="<?php echo wc_get_cart_url(); ?>">
                <span class="minicart-icon flaticon-shopping-cart"></span>
                <span class="minicart-text"><?php echo esc_html__('Cart', 'moorabi'); ?></span>
                <span class="count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
            </a>
        </div>
        <?php
    }
}
if (!function_exists('moorabi_header_mini_cart')) {
    function moorabi_header_mini_cart()
    {
        ?>
        <div class="block-minicart block-woo moorabi-mini-cart moorabi-dropdown">
            <?php
            moorabi_header_cart_link();
            the_widget('WC_Widget_Cart', array('title' => esc_html__('Shopping Cart', 'moorabi')));
            ?>
        </div>
        <?php
    }
}
if (!function_exists('moorabi_cart_link_fragment')) {
    function moorabi_cart_link_fragment($fragments)
    {
        global $product;
        ob_start();
        moorabi_header_cart_link();
        $fragments['div.block-cart-link'] = ob_get_clean();

        return $fragments;
    }
}
if (!function_exists('moorabi_header_wishlist')) {
    function moorabi_header_wishlist()
    {
        if (defined('YITH_WCWL')) :
            $yith_wcwl_wishlist_page_id = get_option('yith_wcwl_wishlist_page_id');
            $wishlist_url = get_page_link($yith_wcwl_wishlist_page_id);
            if ($wishlist_url != '') : ?>
                <div class="block-wishlist block-woo">
                    <a class="block-link" href="<?php echo esc_url($wishlist_url); ?>">
                        <span class="flaticon-heart-shape-outline"></span>
                        <span class="count"><?php echo YITH_WCWL()->count_products(); ?></span>
                    </a>
                </div>
            <?php endif;
        endif;
    }
}
/**
 *
 * WRAPPER CONTENT
 */
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
remove_action('woocommerce_archive_description', 'woocommerce_product_archive_description', 10);
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action('woocommerce_before_main_content', 'moorabi_woocommerce_before_main_content', 10);
add_action('woocommerce_before_main_content', 'moorabi_woocommerce_before_loop_content', 50);
add_action('woocommerce_after_main_content', 'moorabi_woocommerce_after_loop_content', 50);
add_action('woocommerce_sidebar', 'moorabi_woocommerce_sidebar', 10);
add_action('woocommerce_sidebar', 'moorabi_woocommerce_after_main_content', 100);
add_action('woocommerce_before_shop_loop', 'moorabi_woocommerce_before_shop_loop', 50);
add_action('woocommerce_after_shop_loop', 'moorabi_woocommerce_after_shop_loop', 10);
add_action('woocommerce_before_main_content', 'woocommerce_product_archive_description', 60);
if (!function_exists('moorabi_woocommerce_before_main_content')) {
    function moorabi_woocommerce_before_main_content()
    {
        /*Main container class*/
        $main_container_class = array();
        $sidebar_isset = wp_get_sidebars_widgets();
        $shop_layout = Moorabi_Functions::moorabi_get_option('shop_sidebar_layout', 'left');
        $shop_sidebar = 'widget-shop';
        if (is_product()) {
            $shop_layout = Moorabi_Functions::moorabi_get_option('product_sidebar_layout', 'left');
            $shop_sidebar = 'widget-product';
            $thumbnail_layout = 'vertical';
            $main_container_class[] = 'single-thumb-' . $thumbnail_layout;
        }
        if (isset($sidebar_isset[$shop_sidebar]) && empty($sidebar_isset[$shop_sidebar])) {
            $shop_layout = 'full';
        }
        $main_container_class[] = 'main-container shop-page';
        if ($shop_layout == 'full') {
            $main_container_class[] = 'no-sidebar';
        } else {
            $main_container_class[] = $shop_layout . '-sidebar';
        }
        $main_container_class = apply_filters('moorabi_class_before_main_content_product', $main_container_class, $shop_layout);
        echo '<div class="' . esc_attr(implode(' ', $main_container_class)) . '">';
        echo '<div class="container">';
        echo '<div class="row">';
    }
}
if (!function_exists('moorabi_woocommerce_before_loop_content')) {
    function moorabi_woocommerce_before_loop_content()
    {
        $sidebar_isset = wp_get_sidebars_widgets();
        /*Shop layout*/
        $shop_layout = Moorabi_Functions::moorabi_get_option('shop_sidebar_layout', 'left');
        $shop_sidebar = 'widget-shop';
        if (is_product()) {
            $shop_layout = Moorabi_Functions::moorabi_get_option('product_sidebar_layout', 'left');
            $shop_sidebar = 'widget-product';
        }
        if (isset($sidebar_isset[$shop_sidebar]) && empty($sidebar_isset[$shop_sidebar])) {
            $shop_layout = 'full';
        }
        $main_content_class = array();
        $main_content_class[] = 'main-content';
        if ($shop_layout == 'full') {
            $main_content_class[] = 'col-sm-12';
        } else {
            $main_content_class[] = 'col-lg-9 col-md-8 col-sm-12 col-xs-12 has-sidebar';
        }
        $main_content_class = apply_filters('moorabi_class_archive_content', $main_content_class, $shop_layout);
        echo '<div class="' . esc_attr(implode(' ', $main_content_class)) . '">';
    }
}
if (!function_exists('moorabi_woocommerce_after_loop_content')) {
    function moorabi_woocommerce_after_loop_content()
    {
        echo '</div>';
    }
}
if (!function_exists('moorabi_woocommerce_sidebar')) {
    function moorabi_woocommerce_sidebar()
    {
        $shop_layout = Moorabi_Functions::moorabi_get_option('shop_sidebar_layout', 'left');
        $shop_sidebar = 'widget-shop';
        if (is_product()) {
            $shop_layout = Moorabi_Functions::moorabi_get_option('product_sidebar_layout', 'left');
            $shop_sidebar = 'widget-product';
        }
        $sidebar_class = array();
        $sidebar_isset = wp_get_sidebars_widgets();
        if (isset($sidebar_isset[$shop_sidebar]) && empty($sidebar_isset[$shop_sidebar])) {
            $shop_layout = 'full';
        }
        $sidebar_class[] = 'sidebar';
        if ($shop_layout != 'full') {
            $sidebar_class[] = 'col-lg-3 col-md-4 col-sm-12 col-xs-12';
        }
        $sidebar_class = apply_filters('moorabi_class_sidebar_content_product', $sidebar_class, $shop_layout, $shop_sidebar);
        if ($shop_layout != "full"): ?>
            <div class="<?php echo esc_attr(implode(' ', $sidebar_class)); ?>">
                <?php if (is_active_sidebar($shop_sidebar)) : ?>
                    <div id="widget-area" class="widget-area shop-sidebar">
                        <?php dynamic_sidebar($shop_sidebar); ?>
                    </div><!-- .widget-area -->
                <?php endif; ?>
            </div>
        <?php endif;
    }
}
if (!function_exists('moorabi_woocommerce_after_main_content')) {
    function moorabi_woocommerce_after_main_content()
    {
        echo '</div></div></div>';
    }
}
if (!function_exists('moorabi_woocommerce_before_shop_loop')) {
    function moorabi_woocommerce_before_shop_loop()
    {
        echo '<div class="row auto-clear equal-container better-height moorabi-products">';
    }
}
if (!function_exists('moorabi_woocommerce_after_shop_loop')) {
    function moorabi_woocommerce_after_shop_loop()
    {
        echo '</div>';
    }
}
/**
 *
 * SHOP SINGLE
 */
/**
 * woocommerce_single_product_summary hook
 *
 * @hooked woocommerce_template_single_title - 5
 * @hooked woocommerce_template_single_rating - 10
 * @hooked woocommerce_template_single_price - 10
 * @hooked woocommerce_template_single_excerpt - 20
 * @hooked woocommerce_template_single_add_to_cart - 30
 * @hooked woocommerce_template_single_meta - 40
 * @hooked woocommerce_template_single_sharing - 50
 */
add_action('woocommerce_before_single_product_summary', 'moorabi_before_main_content_left', 5);
add_action('woocommerce_before_single_product_summary', 'moorabi_after_main_content_left', 50);
add_action('woocommerce_after_single_product_summary', 'moorabi_woocommerce_after_single_product_summary_1', 5);
add_action('woocommerce_after_single_product_summary', 'moorabi_woocommerce_after_single_product_summary_2', 5);
if (!function_exists('moorabi_before_main_content_left')) {
    function moorabi_before_main_content_left()
    {
        global $product;
        $class = 'no-gallery ';
        $attachment_ids = $product->get_gallery_image_ids();
        if ($attachment_ids && has_post_thumbnail()) {
            $class = 'has-gallery ';
        }
        $product_meta = get_post_meta($product->get_id(), '_custom_product_woo_options', true);
        if (isset($product_meta['product_style']) && $product_meta['product_style'] != 'global') {
            $class .= $product_meta['product_style'];
        } else {
            $class .= Moorabi_Functions::moorabi_get_option('product_style', 'horizontal_thumbnail');
        }
        echo '<div class="main-contain-summary"><div class="contain-left ' . esc_attr($class) . '"><div class="single-left">';
    }
}
if (!function_exists('moorabi_after_main_content_left')) {
    function moorabi_after_main_content_left()
    {
        echo '</div>';
    }
}
if (!function_exists('moorabi_woocommerce_after_single_product_summary_1')) {
    function moorabi_woocommerce_after_single_product_summary_1()
    {
        echo '</div>';
    }
}
if (!function_exists('moorabi_woocommerce_after_single_product_summary_2')) {
    function moorabi_woocommerce_after_single_product_summary_2()
    {
        echo '</div>';
    }
}
/**
 *
 * REMOVE TITLE
 */
add_filter('woocommerce_show_page_title', '__return_false');
/**
 *
 * SHOP CONTROL
 */
add_action('moorabi_control_before_content', 'moorabi_product_per_page_tmp', 10);
add_action('moorabi_control_before_content', 'woocommerce_catalog_ordering', 20);
add_action('moorabi_control_before_content', 'moorabi_shop_display_mode_tmp', 30);
add_action('moorabi_control_after_content', 'moorabi_custom_pagination', 10);
if (!function_exists('moorabi_shop_display_mode_tmp')) {
    function moorabi_shop_display_mode_tmp()
    {
        global $wp;
        if ('' === get_option('permalink_structure')) {
            $form_action = remove_query_arg(array(
                'page',
                'paged',
                'product-page'
            ), add_query_arg($wp->query_string, '', home_url($wp->request)));
        } else {
            $form_action = preg_replace('%\/page/[0-9]+%', '', home_url(trailingslashit($wp->request)));
        }
        $shop_display_mode = Moorabi_Functions::moorabi_get_option('shop_style', 'grid');
        ?>
        <div class="grid-view-mode">
            <form method="get" action="<?php echo esc_url($form_action); ?>">
                <button type="submit"
                        data-toggle="tooltip"
                        data-placement="top"
                        class="modes-mode mode-list display-mode <?php if ($shop_display_mode == 'list'): ?>active<?php endif; ?>"
                        value="list"
                        name="shop_style">
                        <span class="button-inner">
                            <?php echo esc_html__('Shop List', 'moorabi'); ?>
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                </button>
                <button type="submit"
                        data-toggle="tooltip"
                        data-placement="top"
                        class="modes-mode mode-grid display-mode <?php if ($shop_display_mode == 'grid'): ?>active<?php endif; ?>"
                        value="grid"
                        name="shop_style">
                        <span class="button-inner">
                            <?php echo esc_html__('Shop Grid', 'moorabi'); ?>
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                </button>
                <?php wc_query_string_form_fields(null, array('shop_style', 'submit', 'paged', 'product-page')); ?>
            </form>
        </div>
        <?php
    }
}
if (!function_exists('moorabi_product_per_page_tmp')) {
    function moorabi_product_per_page_tmp()
    {
        $perpage = Moorabi_Functions::moorabi_get_option('product_per_page', '12');
        $parts = parse_url(home_url());
        $current_url = "{$parts['scheme']}://{$parts['host']}" . add_query_arg(null, null);
        $total_products = wc_get_loop_prop('total');
        ?>
        <form class="per-page-form" method="POST" action="#">
            <label>
                <select name="product_per_page_filter" class="option-perpage" onchange="this.form.submit();">
                    <option value="<?php echo esc_attr($perpage); ?>" <?php echo esc_attr('selected'); ?>>
                        <?php echo 'Show ' . zeroise($perpage, 2); ?>
                    </option>
                    <option value="<?php echo esc_attr('5'); ?>">
                        <?php echo esc_html__('Show 05', 'moorabi'); ?>
                    </option>
                    <option value="<?php echo esc_attr('10'); ?>">
                        <?php echo esc_html__('Show 10', 'moorabi'); ?>
                    </option>
                    <option value="<?php echo esc_attr('12'); ?>">
                        <?php echo esc_html__('Show 12', 'moorabi'); ?>
                    </option>
                    <option value="<?php echo esc_attr('15'); ?>">
                        <?php echo esc_html__('Show 15', 'moorabi'); ?>
                    </option>
                    <option value="<?php echo esc_attr($total_products); ?>">
                        <?php echo esc_html__('Show All', 'moorabi'); ?>
                    </option>
                </select>
            </label>
            <label>
                <input type="hidden" name="perpage_action_form" value="<?php echo esc_attr($current_url); ?>">
            </label>
        </form>
        <?php
    }
}
if (!function_exists('moorabi_custom_pagination')) {
    function moorabi_custom_pagination()
    {
        global $wp_query;
        if ($wp_query->max_num_pages > 1) {
            ?>
            <nav class="woocommerce-pagination">
                <?php
                echo paginate_links(apply_filters('woocommerce_pagination_args', array(
                    'base' => esc_url_raw(str_replace(999999999, '%#%', remove_query_arg('add-to-cart', get_pagenum_link(999999999, false)))),
                    'format' => '',
                    'add_args' => false,
                    'current' => max(1, get_query_var('paged')),
                    'total' => $wp_query->max_num_pages,
                    'prev_text' => esc_html__('Previous', 'moorabi'),
                    'next_text' => esc_html__('Next', 'moorabi'),
                    'type' => 'plain',
                    'end_size' => 3,
                    'mid_size' => 3,
                )));
                ?>
            </nav>
            <?php
        }
    }
}
/**
 * CUSTOM SHOP CONTROL
 */
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
add_action('woocommerce_before_shop_loop', 'moorabi_before_shop_control', 20);
add_action('woocommerce_after_shop_loop', 'moorabi_after_shop_control', 50);
if (!function_exists('moorabi_before_shop_control')) {
    function moorabi_before_shop_control()
    {
        ?>
        <div class="shop-control shop-before-control">
            <?php do_action('moorabi_control_before_content'); ?>
        </div>
        <?php
    }
}
if (!function_exists('moorabi_after_shop_control')) {
    function moorabi_after_shop_control()
    {
        ?>
        <div class="shop-control shop-after-control">
            <?php do_action('moorabi_control_after_content'); ?>
        </div>
        <?php
    }
}
/**
 * CUSTOM PRODUCT POST PER PAGE
 */
add_filter('loop_shop_per_page', 'moorabi_loop_shop_per_page', 20);
add_filter('woof_products_query', 'moorabi_woof_products_query', 20);
if (!function_exists('moorabi_loop_shop_per_page')) {
    function moorabi_loop_shop_per_page()
    {
        $moorabi_woo_products_perpage = Moorabi_Functions::moorabi_get_option('product_per_page', '12');

        return $moorabi_woo_products_perpage;
    }
}
if (!function_exists('moorabi_woof_products_query')) {
    function moorabi_woof_products_query($wr)
    {
        $moorabi_woo_products_perpage = Moorabi_Functions::moorabi_get_option('product_per_page', '12');
        $wr['posts_per_page'] = $moorabi_woo_products_perpage;

        return $wr;
    }
}
/**
 *
 * CUSTOM PRODUCT NAME
 */
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
add_action('woocommerce_shop_loop_item_title', 'moorabi_template_loop_product_title', 30);

if (!function_exists('moorabi_template_loop_product_title')) {
    function moorabi_template_loop_product_title()
    {
        $title_class = array('product-name product_title');
        ?>
        <h3 class="<?php echo esc_attr(implode(' ', $title_class)); ?>">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        <?php
    }
}
/**
 *
 * PRODUCT THUMBNAIL
 */
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action('woocommerce_before_shop_loop_item_title', 'moorabi_template_loop_product_thumbnail', 10);
if (!function_exists('moorabi_template_loop_product_thumbnail')) {
    function moorabi_template_loop_product_thumbnail()
    {
        global $product;
        // GET SIZE IMAGE SETTING
        $width = 320;
        $height = 320;
        $crop = true;
        $size = wc_get_image_size('shop_catalog');
        if ($size) {
            $width = $size['width'];
            $height = $size['height'];
            if (!$size['crop']) {
                $crop = false;
            }
        }
        $data_src = '';
        $attachment_ids = $product->get_gallery_image_ids();
        $gallery_class_img = $class_img = array('img-responsive');
        $thumb_gallery_class_img = $thumb_class_img = array('thumb-link');
        $width = apply_filters('moorabi_shop_product_thumb_width', $width);
        $height = apply_filters('moorabi_shop_product_thumb_height', $height);
        $image_thumb = apply_filters('moorabi_resize_image', get_post_thumbnail_id($product->get_id()), $width, $height, $crop, true);
        $image_url = $image_thumb['url'];
        if ($attachment_ids && has_post_thumbnail()) {
            $gallery_class_img[] = 'wp-post-image';
            $thumb_gallery_class_img[] = 'woocommerce-product-gallery__image';
        } else {
            $class_img[] = 'wp-post-image';
            $thumb_class_img[] = 'woocommerce-product-gallery__image';
        }
        ?>
        <a class="<?php echo esc_attr(implode(' ', $thumb_class_img)); ?>" href="<?php the_permalink(); ?>">
            <img class="<?php echo esc_attr(implode(' ', $class_img)); ?>" src="<?php echo esc_attr($image_url); ?>"
                <?php echo esc_attr($data_src); ?> <?php echo image_hwstring($width, $height); ?>
                 alt="<?php echo esc_attr(get_the_title()); ?>">
        </a>
        <?php
        if ($attachment_ids && has_post_thumbnail()) :
            $gallery_data_src = '';
            $gallery_thumb = apply_filters('moorabi_resize_image', $attachment_ids[0], $width, $height, $crop, true);
            $gallery_image_url = $gallery_thumb['url'];
            ?>
            <div class="second-image">
                <a href="<?php the_permalink(); ?>" class="<?php echo esc_attr(implode(' ', $thumb_gallery_class_img)); ?>">
                    <img class="<?php echo esc_attr(implode(' ', $gallery_class_img)); ?>"
                         src="<?php echo esc_attr($gallery_image_url); ?>"
                        <?php echo esc_attr($gallery_data_src); ?> <?php echo image_hwstring($width, $height); ?>
                         alt="<?php echo esc_attr(get_the_title()); ?>">
                </a>
            </div>
            <?php
        endif;
    }
}
/**
 * REMOVE "woocommerce_template_loop_product_link_open"
 */
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
/**
 *
 * CUSTOM FLASH
 */
add_action('moorabi_group_flash_content', 'woocommerce_show_product_loop_sale_flash', 5);
add_action('moorabi_group_flash_content', 'moorabi_custom_new_flash', 10);
add_filter('woocommerce_sale_flash', 'moorabi_custom_sale_flash');
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
add_action('woocommerce_single_product_summary', 'moorabi_function_shop_loop_item_countdown', 21);
add_action('woocommerce_single_product_summary', 'moorabi_single_product_size_guide', 23);
add_action('woocommerce_before_shop_loop_item_title', 'moorabi_woocommerce_group_flash', 10);
add_action('woocommerce_single_product_summary', 'moorabi_woocommerce_group_flash', 1);
if (!function_exists('moorabi_custom_new_flash')) {
    function moorabi_custom_new_flash()
    {
        global $post, $product;
        if ($product->is_in_stock()) {
            $postdate = get_the_time('Y-m-d');
            $postdatestamp = strtotime($postdate);
            $newness = Moorabi_Functions::moorabi_get_option('product_newness', 7);
            if ((time() - (60 * 60 * 24 * $newness)) < $postdatestamp) :
                echo apply_filters('woocommerce_new_flash', '<span class="onnew"><span class="text">' . esc_html__('New', 'moorabi') . '</span></span>', $post, $product);
            else:
                echo apply_filters('woocommerce_new_flash', '<span class="onnew hidden"></span>', $post, $product);
            endif;
        }
    }
}
if (!function_exists('moorabi_get_percent_discount')) {
    function moorabi_get_percent_discount()
    {
        global $product;
        $percent = '';
        if ($product->is_on_sale() && $product->is_in_stock()) {
            if ($product->is_type('variable')) {
                $available_variations = $product->get_available_variations();
                $maximumper = 0;
                $minimumper = 0;
                $percentage = 0;
                for ($i = 0; $i < count($available_variations); ++$i) {
                    $variation_id = $available_variations[$i]['variation_id'];
                    $variable_product1 = new WC_Product_Variation($variation_id);
                    $regular_price = $variable_product1->get_regular_price();
                    $sales_price = $variable_product1->get_sale_price();
                    if ($regular_price > 0 && $sales_price > 0) {
                        $percentage = round(((($regular_price - $sales_price) / $regular_price) * 100), 0);
                    }
                    if ($minimumper == 0) {
                        $minimumper = $percentage;
                    }
                    if ($percentage > $maximumper) {
                        $maximumper = $percentage;
                    }
                    if ($percentage < $minimumper) {
                        $minimumper = $percentage;
                    }
                }
                if ($minimumper == $maximumper) {
                    $percent .= '-' . $minimumper . '%';
                } else {
                    $percent .= '-(' . $minimumper . '-' . $maximumper . ')%';
                }
            } else {
                if ($product->get_regular_price() > 0 && $product->get_sale_price() > 0) {
                    $percentage = round(((($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100), 0);
                    $percent .= '-' . $percentage . '%';
                }
            }
        }

        return $percent;
    }
}
if (!function_exists('moorabi_custom_sale_flash')) {
    function moorabi_custom_sale_flash()
    {
        $percent = moorabi_get_percent_discount();
        if ($percent != '') {
            return '<span class="onsale"><span class="number">' . $percent . '</span></span>';
        }

        return '';
    }
}
if (!function_exists('moorabi_woocommerce_group_flash')) {
    function moorabi_woocommerce_group_flash()
    {
        ?>
        <div class="flash">
            <?php do_action('moorabi_group_flash_content'); ?>
        </div>
        <?php
    }
}
/**
 *
 * BREADCRUMB
 */
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
add_action('moorabi_woocommerce_breadcrumb', 'moorabi_woocommerce_breadcrumb', 20);
if (!function_exists('moorabi_woocommerce_breadcrumb')) {
    function moorabi_woocommerce_breadcrumb()
    {
        $args = array(
            'delimiter' => '<i class="fa fa-angle-double-right"></i>',
        );
        woocommerce_breadcrumb($args);
    }
}
/**
 *
 * RELATED UPSELL CROSSSELL
 */
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
add_action('woocommerce_sidebar', 'moorabi_related_products', 50);
if (!function_exists('moorabi_related_title_product')) {
    add_action('moorabi_before_related_single_product', 'moorabi_related_title_product');
    function moorabi_related_title_product($prefix)
    {
        if ($prefix == 'woo_crosssell') {
            $default_text = esc_html__('Cross Sell Products', 'moorabi');
        } elseif ($prefix == 'woo_related') {
            $default_text = esc_html__('Related Products', 'moorabi');
        } else {
            $default_text = esc_html__('Upsell Products', 'moorabi');
        }
        $title = Moorabi_Functions::moorabi_get_option($prefix . '_products_title', $default_text);
        ?>
        <div class="block-title">
            <h2 class="product-grid-title">
                <span><?php echo esc_html($title); ?></span>
            </h2>
        </div>
        <?php
    }
}

if (!function_exists('moorabi_carousel_products')) {
    function moorabi_carousel_products($prefix, $data_args)
    {
        $enable_product = Moorabi_Functions::moorabi_get_option('enable_' . $prefix, 'enable');
        if ($enable_product == 'disable' || empty($data_args)) {
            return;
        }
        $shop_product_style = Moorabi_Functions::moorabi_get_option( 'shop_product_style', 'style-01' );
        $list_class = array('moorabi-products');
        $list_class[] = $shop_product_style;
        $item_class = array('product-item');
        $item_class[] = $shop_product_style;
        $woo_ls_items = Moorabi_Functions::moorabi_get_option($prefix . '_bg_items', 4);
        $woo_lg_items = Moorabi_Functions::moorabi_get_option($prefix . '_lg_items', 3);
        $woo_md_items = Moorabi_Functions::moorabi_get_option($prefix . '_md_items', 3);
        $woo_sm_items = Moorabi_Functions::moorabi_get_option($prefix . '_sm_items', 2);
        $woo_xs_items = Moorabi_Functions::moorabi_get_option($prefix . '_xs_items', 2);
        $woo_ts_items = Moorabi_Functions::moorabi_get_option($prefix . '_ts_items', 2);
        $atts = array(
            'owl_navigation' => 'false',
            'owl_dots' => 'true',
            'owl_loop' => 'false',
            'owl_slide_margin' => '30',
            'owl_lg_slide_margin' => '30',
            'owl_md_slide_margin' => '20',
            'owl_sm_slide_margin' => '20',
            'owl_xs_slide_margin' => '10',
            'owl_ts_slide_margin' => '10',
            'owl_ts_items' => $woo_ts_items,
            'owl_xs_items' => $woo_xs_items,
            'owl_sm_items' => $woo_sm_items,
            'owl_md_items' => $woo_md_items,
            'owl_lg_items' => $woo_lg_items,
            'owl_ls_items' => $woo_ls_items,
        );
        $owl_settings = apply_filters('moorabi_carousel_data_attributes', 'owl_', $atts);
        if ($data_args) : ?>
            <div class="col-sm-12 col-xs-12 <?php echo esc_attr($prefix); ?>-product">
                <div class="<?php echo esc_attr(implode(' ', $list_class)); ?>">
                    <?php do_action('moorabi_before_related_single_product', $prefix); ?>
                    <div class="owl-slick owl-products equal-container better-height" <?php echo esc_attr($owl_settings); ?>>
                        <?php foreach ($data_args as $value) : ?>
                            <div <?php post_class($item_class) ?>>
                                <?php
                                $post_object = get_post($value->get_id());
                                setup_postdata($GLOBALS['post'] =& $post_object);
                                wc_get_template_part('product-styles/content-product', $shop_product_style);
                                ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif;
        wp_reset_postdata();
    }
}
if (!function_exists('moorabi_related_products')) {
    function moorabi_related_products()
    {
        global $product;
        $related_products = array();
        if ($product) {
            $defaults = array(
                'posts_per_page' => 6,
                'columns' => 6,
                'orderby' => 'rand',
                'order' => 'desc',
            );
            $args = wp_parse_args($defaults);
            $args['related_products'] = array_filter(array_map('wc_get_product', wc_get_related_products($product->get_id(), $args['posts_per_page'], $product->get_upsell_ids())), 'wc_products_array_filter_visible');
            $args['related_products'] = wc_products_array_orderby($args['related_products'], $args['orderby'], $args['order']);
            $woocommerce_loop['name'] = 'related';
            $woocommerce_loop['columns'] = apply_filters('woocommerce_related_products_columns', $args['columns']);
            $related_products = $args['related_products'];
        }

        if (!is_product()) {
            $related_products = array();
        }
        moorabi_carousel_products('woo_related', $related_products);
    }
}
/**
 *
 * UPSELL
 */
remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
add_action('woocommerce_sidebar', 'moorabi_upsell_display', 50);
if (!function_exists('moorabi_upsell_display')) {
    function moorabi_upsell_display($orderby = 'rand', $order = 'desc', $limit = '-1', $columns = 4)
    {
        global $product;
        $upsells = array();
        if ($product) {
            $args = array('posts_per_page' => 4, 'orderby' => 'rand', 'columns' => 4,);
            $woocommerce_loop['name'] = 'up-sells';
            $woocommerce_loop['columns'] = apply_filters('woocommerce_upsells_columns', isset($args['columns']) ? $args['columns'] : $columns);
            $orderby = apply_filters('woocommerce_upsells_orderby', isset($args['orderby']) ? $args['orderby'] : $orderby);
            $limit = apply_filters('woocommerce_upsells_total', isset($args['posts_per_page']) ? $args['posts_per_page'] : $limit);
            // Get visible upsells then sort them at random, then limit result set.
            $upsells = wc_products_array_orderby(array_filter(array_map('wc_get_product', $product->get_upsell_ids()), 'wc_products_array_filter_visible'), $orderby, $order);
            $upsells = $limit > 0 ? array_slice($upsells, 0, $limit) : $upsells;
        }

        if (!is_product()) {
            $upsells = array();
        }
        moorabi_carousel_products('moorabi_woo_upsell', $upsells);
    }
}
/**
 *
 * CROSS SELL
 */
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
add_action('woocommerce_after_cart', 'moorabi_cross_sell_products');
if (!function_exists('moorabi_cross_sell_products')) {
    function moorabi_cross_sell_products($limit = 2, $columns = 2, $orderby = 'rand', $order = 'desc')
    {
        if (is_checkout()) {
            return;
        }
        $cross_sells = array_filter(array_map('wc_get_product', WC()->cart->get_cross_sells()), 'wc_products_array_filter_visible');
        $woocommerce_loop['name'] = 'cross-sells';
        $woocommerce_loop['columns'] = apply_filters('woocommerce_cross_sells_columns', $columns);
        // Handle orderby and limit results.
        $orderby = apply_filters('woocommerce_cross_sells_orderby', $orderby);
        $cross_sells = wc_products_array_orderby($cross_sells, $orderby, $order);
        $limit = apply_filters('woocommerce_cross_sells_total', $limit);
        $cross_sells = $limit > 0 ? array_slice($cross_sells, 0, $limit) : $cross_sells;
        moorabi_carousel_products('woo_crosssell', $cross_sells);
    }
}
/**
 *
 * CHECKOUT
 */
remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10);
remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);

add_action('woocommerce_before_checkout_form', 'moorabi_checkout_login_open', 1);
add_action('woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 5);
add_action('woocommerce_before_checkout_form', 'moorabi_checkout_login_close', 6);
add_action('woocommerce_before_checkout_form', 'moorabi_checkout_coupon_open', 7);
add_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
add_action('woocommerce_before_checkout_form', 'moorabi_checkout_coupon_close', 11);
if (!function_exists('moorabi_checkout_login_open')) {
    function moorabi_checkout_login_open()
    {
        if (!is_user_logged_in() && get_option('woocommerce_enable_signup_and_login_from_checkout') === 'yes') {
            echo '<div class="checkout-before-top"><div class="moorabi-checkout-login">';
        }
    }
}
if (!function_exists('moorabi_checkout_login_close')) {
    function moorabi_checkout_login_close()
    {
        if (!is_user_logged_in() && get_option('woocommerce_enable_signup_and_login_from_checkout') === 'yes') {
            echo '</div>';
        }
    }
}
if (!function_exists('moorabi_checkout_coupon_open')) {
    function moorabi_checkout_coupon_open()
    {
        echo '<div class="moorabi-checkout-coupon">';
    }
}
if (!function_exists('moorabi_checkout_coupon_close')) {
    function moorabi_checkout_coupon_close()
    {
        echo '</div>';
        if (!is_user_logged_in()) {
            echo '</div>';
        }
    }
}
/* GALLERY PRODUCT */
if (!function_exists('moorabi_gallery_product_thumbnail')) {
    function moorabi_gallery_product_thumbnail()
    {
        global $post, $product;
        // GET SIZE IMAGE SETTING
        $width = 320;
        $height = 320;
        $crop = true;
        $size = wc_get_image_size('shop_catalog');
        if ($size) {
            $width = $size['width'];
            $height = $size['height'];
            if (!$size['crop']) {
                $crop = false;
            }
        }
        $html = '';
        $html_thumb = '';
        $attachment_ids = $product->get_gallery_image_ids();
        $width = apply_filters('moorabi_shop_product_thumb_width', $width);
        $height = apply_filters('moorabi_shop_product_thumb_height', $height);
        /* primary image */
        $image_thumb = apply_filters('moorabi_resize_image', get_post_thumbnail_id($product->get_id()), $width, $height, $crop, true);
        $thumbnail_primary = apply_filters('moorabi_resize_image', get_post_thumbnail_id($product->get_id()), 90, 90, $crop, true);
        $html .= '<figure class="product-gallery-image">';
        $html .= $image_thumb['img'];
        $html .= '</figure>';
        $html_thumb .= '<figure>' . $thumbnail_primary['img'] . '</figure>';
        /* thumbnail image */
        if ($attachment_ids && has_post_thumbnail()) {
            foreach ($attachment_ids as $attachment_id) {
                $gallery_thumb = apply_filters('moorabi_resize_image', $attachment_id, $width, $height, $crop, true);
                $thumbnail_image = apply_filters('moorabi_resize_image', $attachment_id, 90, 90, $crop, true);
                $html .= '<figure class="product-gallery-image">';
                $html .= $gallery_thumb['img'];
                $html .= '</figure>';
                $html_thumb .= '<figure>' . $thumbnail_image['img'] . '</figure>';
            }
        }
        ?>
        <div class="product-gallery">
            <div class="product-gallery-slick">
                <?php echo wp_specialchars_decode($html); ?>
            </div>
            <div class="gallery-dots">
                <?php echo wp_specialchars_decode($html_thumb); ?>
            </div>
        </div>
        <?php
    }
}

/**
 *
 * RATTING
 */
add_filter('woocommerce_product_get_rating_html', 'moorabi_get_star_rating_html', 10, 3);
if (!function_exists('moorabi_get_star_rating_html')) {
    function moorabi_get_star_rating_html($html, $rating, $count)
    {
        global $product;

        if (method_exists($product, 'get_review_count')) {
            $review_count = $product->get_review_count();
            $review_count = zeroise($review_count, 2);
            if ($review_count > 0) {
                return '<div class="rating-wapper">' . $html . '<span class="count">('. $review_count .')</span></div>';
            } else {
                return '<div class="rating-wapper"><div class="star-rating"><span></span></div><span class="count">('. $review_count .')</span></div>';
            }
        }
        return '';
    }
}