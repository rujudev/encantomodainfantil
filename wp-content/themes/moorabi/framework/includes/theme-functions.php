<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
add_filter('get_the_archive_title', function ($title) {
    return preg_replace('/^\w+: /', '', $title);
});
/**
 *
 * HOOK AJAX
 */
add_action('wp_ajax_moorabi_ajax_tabs', 'moorabi_ajax_tabs');
add_action('wp_ajax_nopriv_moorabi_ajax_tabs', 'moorabi_ajax_tabs');
?>
<?php
/**
 *
 * HOOK TEMPLATE FUNCTIONS
 */
if (!function_exists('moorabi_get_logo')) {
    function moorabi_get_logo()
    {
        $id_page = Moorabi_Functions::moorabi_get_id();
        $logo_url = get_theme_file_uri('/assets/images/logo.svg');
        $data_meta = get_post_meta($id_page, '_custom_metabox_theme_options', true);
        $logo = Moorabi_Functions::moorabi_get_option('logo');
        if (isset($data_meta['metabox_logo']) && $data_meta['metabox_logo'] != '') {
            $logo = $data_meta['metabox_logo'];
        }
        if ($logo != '') {
            $logo_url = wp_get_attachment_image_url($logo, 'full');
        }

        $html = '<a href="' . esc_url(home_url('/')) . '"><img alt="' . esc_attr(get_bloginfo('name')) . '" src="' . esc_url($logo_url) . '" class="logo" /></a>';
        echo apply_filters('moorabi_site_logo', $html);
    }
}
if (!function_exists('moorabi_detected_shortcode')) {
    function moorabi_detected_shortcode($id, $tab_id = null, $product_id = null)
    {
        $post = get_post($id);
        $content = preg_replace('/\s+/', ' ', $post->post_content);
        $shortcode_section = '';
        if ($tab_id == null) {
            $out = array();
            preg_match_all('/\[moorabi_products(.*?)\]/', $content, $matches);
            if ($matches[0] && is_array($matches[0]) && count($matches[0]) > 0) {
                foreach ($matches[0] as $key => $value) {
                    if (shortcode_parse_atts($matches[1][$key])['products_custom_id'] == $product_id) {
                        $out['atts'] = shortcode_parse_atts($matches[1][$key]);
                        $out['content'] = $value;
                    }
                }
            }
            $shortcode_section = $out;
        }
        if ($product_id == null) {
            preg_match_all('/\[vc_tta_section(.*?)vc_tta_section\]/', $content, $matches);
            if ($matches[0] && is_array($matches[0]) && count($matches[0]) > 0) {
                foreach ($matches[0] as $key => $value) {
                    preg_match_all('/tab_id="([^"]+)"/', $matches[0][$key], $matches_ids);
                    foreach ($matches_ids[1] as $matches_id) {
                        if ($tab_id == $matches_id) {
                            $shortcode_section = $value;
                        }
                    }
                }
            }
        }

        return $shortcode_section;
    }
}
if (!function_exists('moorabi_ajax_tabs')) {
    function moorabi_ajax_tabs()
    {
        $response = array(
            'html' => '',
            'message' => '',
            'success' => 'no',
        );
        $section_id = isset($_POST['section_id']) ? $_POST['section_id'] : '';
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $shortcode = moorabi_detected_shortcode($id, $section_id, null);
        WPBMap::addAllMappedShortcodes();
        $response['html'] = wpb_js_remove_wpautop($shortcode);
        $response['success'] = 'ok';
        wp_send_json($response);
        die();
    }
}
if (!function_exists('moorabi_search_form')) {
    function moorabi_search_form()
    {
        $selected = '';
        if (isset($_GET['product_cat']) && $_GET['product_cat']) {
            $selected = $_GET['product_cat'];
        }
        $args = array(
            'show_option_none' => esc_html__('All Categories', 'moorabi'),
            'taxonomy' => 'product_cat',
            'class' => 'category-search-option',
            'hide_empty' => 1,
            'orderby' => 'name',
            'order' => 'ASC',
            'tab_index' => true,
            'hierarchical' => true,
            'id' => rand(),
            'name' => 'product_cat',
            'value_field' => 'slug',
            'selected' => $selected,
            'option_none_value' => '0',
        );
        ?>
        <div class="block-search">
            <h3 class="search-title"><?php echo esc_html__('Search', 'moorabi'); ?></h3>
            <form role="search" method="get" action="<?php echo esc_url(home_url('/')) ?>"
                  class="form-search block-search-form moorabi-live-search-form">
                <?php if (class_exists('WooCommerce')): ?>
                    <input type="hidden" name="post_type" value="product"/>
                    <input type="hidden" name="taxonomy" value="product_cat">
                    <div class="category">
                        <?php wp_dropdown_categories($args); ?>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="post_type" value="post"/>
                <?php endif; ?>
                <div class="form-content search-box results-search">
                    <div class="inner">
                        <input autocomplete="off" type="text" class="searchfield txt-livesearch input" name="s"
                               value="<?php echo esc_attr(get_search_query()); ?>"
                               placeholder="<?php esc_attr_e('Search here...', 'moorabi'); ?>">
                    </div>
                </div>
                <button type="submit" class="btn-submit">
                    <span class="flaticon-magnifying-glass-1"></span>
                </button>
            </form><!-- block search -->
        </div>
        <?php
    }
}
if (!function_exists('moorabi_header_vertical')) {
    function moorabi_header_vertical()
    {
        global $post;
        /* MAIN THEME OPTIONS */
        $enable_vertical = Moorabi_Functions::moorabi_get_option('enable_vertical_menu');
        $block_vertical = Moorabi_Functions::moorabi_get_option('block_vertical_menu');
        $item_visible = Moorabi_Functions::moorabi_get_option('vertical_item_visible', 10);
        if ($enable_vertical == 1 && has_nav_menu('vertical_menu')) :
            $locations = get_nav_menu_locations();
            $menu_id = $locations['vertical_menu'];
            $menu_items = wp_get_nav_menu_items($menu_id);
            $count = 0;
            foreach ($menu_items as $menu_item) {
                if ($menu_item->menu_item_parent == 0) {
                    $count++;
                }
            }
            /* MAIN THEME OPTIONS */
            $vertical_title = Moorabi_Functions::moorabi_get_option('vertical_menu_title', esc_html__('CATEGORIES', 'moorabi'));
            $vertical_button_all = Moorabi_Functions::moorabi_get_option('vertical_menu_button_all_text', esc_html__('All Categories', 'moorabi'));
            $vertical_button_close = Moorabi_Functions::moorabi_get_option('vertical_menu_button_close_text', esc_html__('Close', 'moorabi'));
            $block_class = array('vertical-wrapper block-nav-category');
            $id = '';
            $post_type = '';
            if ($enable_vertical == 1) {
                $block_class[] = 'has-vertical-menu';
            }
            if (isset($post->ID)) {
                $id = $post->ID;
            }
            if (isset($post->post_type)) {
                $post_type = $post->post_type;
            }
            if (is_array($block_vertical) && in_array($id, $block_vertical) && $post_type == 'page') {
                $moorabi_block_class[] = 'always-open';
            }
            ?>
            <!-- block category -->
            <div data-items="<?php echo esc_attr($item_visible); ?>"
                 class="<?php echo esc_attr(implode(' ', $block_class)); ?>">
                <div class="block-title">
                    <span class="before">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                    <span class="text-title"><?php echo esc_html($vertical_title); ?></span>
                </div>
                <div class="block-content verticalmenu-content">
                    <?php
                        wp_nav_menu(array(
                                'menu' => 'vertical_menu',
                                'theme_location' => 'vertical_menu',
                                'depth' => 3,
                                'container' => '',
                                'container_class' => '',
                                'container_id' => '',
                                'menu_class' => 'moorabi-nav vertical-menu',
                                'megamenu_layout' => 'vertical',
                            )
                        );
                    if ($count > $item_visible) : ?>
                        <div class="view-all-category">
                            <a href="javascript:void(0)" data-closetext="<?php echo esc_attr($vertical_button_close); ?>"
                               data-alltext="<?php echo esc_attr($vertical_button_all) ?>"
                               class="btn-view-all open-cate"><?php echo esc_html($vertical_button_all) ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div><!-- block category -->
        <?php endif;
    }
}
/**
 *
 * TEMPLATE HEADER
 */
if (!function_exists('moorabi_template_header')) {
    function moorabi_template_header()
    {
        $data_meta = get_post_meta(get_the_ID(), '_custom_metabox_theme_options', true);
        $header_options = Moorabi_Functions::moorabi_get_option('header_options', 'style-01');
        $header_options = isset($data_meta['enable_header']) && $data_meta['enable_header'] == 1 && isset($data_meta['metabox_header_options']) && $data_meta['metabox_header_options'] != '' ? $data_meta['metabox_header_options'] : $header_options;
        get_template_part('templates/header/header', $header_options);


    }
}
if (!function_exists('moorabi_user_link')) {
    function moorabi_user_link()
    {
        $myaccount_link = wp_login_url();
        if (class_exists('WooCommerce')) {
            $myaccount_link = get_permalink(get_option('woocommerce_myaccount_page_id'));
        }
        ?>
        <div class="menu-item block-user block-woo moorabi-dropdown">
            <?php if ( is_user_logged_in() ): ?>
                <a data-moorabi="moorabi-dropdown" class="block-link"
                   href="<?php echo esc_url( $myaccount_link ); ?>">
                    <span class="user-icon flaticon-profile"></span>
                </a>
                <?php if ( function_exists( 'wc_get_account_menu_items' ) ): ?>
                    <ul class="sub-menu">
                        <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
                            <li class="menu-item <?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
                                <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <ul class="sub-menu">
                        <li class="menu-item">
                            <a href="<?php echo wp_logout_url( get_permalink() ); ?>"><?php esc_html_e( 'Logout', 'moorabi' ); ?></a>
                        </li>
                    </ul>
                <?php endif;
            else: ?>
                <a class="block-link" href="<?php echo esc_url( $myaccount_link ); ?>">
                    <span class="user-icon flaticon-profile"></span>
                </a>
            <?php endif; ?>
        </div>
        <?php
    }
}

if (!function_exists('moorabi_mobile_menu')) {
    function moorabi_mobile_menu($menu_locations, $default = 'primary')
    {
        if (!empty($menu_locations)) {
            $count       = 0;
            $mobile_menu = '';
            $array_menus = array();
            $array_child = array();
            $mobile_menu .= "<div class='moorabi-menu-clone-wrap'>";
            $mobile_menu .= "<div class='moorabi-menu-panels-actions-wrap'>";
            $mobile_menu .= "<span class='moorabi-menu-current-panel-title'>".esc_html__('Main Menu', 'moorabi')."</span>";
            $mobile_menu .= "<a href='javascript:void(0)' class='moorabi-menu-close-btn moorabi-menu-close-panels'>x</a>";
            $mobile_menu .= "</div>";
            $mobile_menu .= "<div class='moorabi-menu-panels'>";
            foreach ((array) $menu_locations as $location) {
                $menu_items = array();
                if (wp_get_nav_menu_items($location)) {
                    $menu_items = wp_get_nav_menu_items($location);
                } else {
                    $locations = get_nav_menu_locations();
                    if (isset($locations[$default])) {
                        $menu       = wp_get_nav_menu_object($locations[$default]);
                        $menu_items = wp_get_nav_menu_items($menu->name);
                    }
                }
                if (!empty($menu_items)) {
                    foreach ($menu_items as $key => $menu_item) {
                        $parent_id = $menu_item->menu_item_parent;
                        /* REND CLASS */
                        $classes   = empty($menu_item->classes) ? array() : (array) $menu_item->classes;
                        $classes[] = 'menu-item';
                        $classes[] = 'menu-item-'.$menu_item->ID;
                        /* REND ARGS */
                        $array_menus[$parent_id][$menu_item->ID] = array(
                            'url'   => $menu_item->url,
                            'class' => $classes,
                            'title' => $menu_item->title,
                        );
                        if ($parent_id > 0) {
                            $array_child[] = $parent_id;
                        }
                    }
                }
            }
            foreach ($array_menus as $parent_id => $menus) {
                $main_id = uniqid('main-');
                if ($count == 0) {
                    $mobile_menu .= "<div id='moorabi-menu-panel-{$main_id}' class='moorabi-menu-panel moorabi-menu-panel-main'>";
                } else {
                    $mobile_menu .= "<div id='moorabi-menu-panel-{$parent_id}' class='moorabi-menu-panel moorabi-menu-sub-panel moorabi-menu-hidden'>";
                }
                $mobile_menu .= "<ul class='depth-{$count}'>";
                foreach ($menus as $id => $menu) {
                    $class_menu  = join(' ', $menu['class']);
                    $mobile_menu .= "<li id='moorabi-menu-clone-menu-item-{$id}' class='{$class_menu}'>";
                    if (in_array($id, $array_child)) {
                        $mobile_menu .= "<a class='moorabi-menu-next-panel' href='#".esc_attr("moorabi-menu-panel-{$id}")."' data-target='#".esc_attr("moorabi-menu-panel-{$id}")."'></a>";
                    }
                    $mobile_menu .= "<a href='{$menu['url']}'>{$menu['title']}</a>";
                    $mobile_menu .= "</li>";
                }
                $mobile_menu .= "</ul></div>";
                $count++;
            }
            $mobile_menu .= "</div></div>";
            /*
             * Export Html
             * */
            echo wp_specialchars_decode($mobile_menu);
        }
    }
}
