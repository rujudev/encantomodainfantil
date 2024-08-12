<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$id_page = Moorabi_Functions::moorabi_get_id();
$data_meta = get_post_meta($id_page, '_custom_metabox_theme_options', true);
$container = array('banner-wrapper');
$banner_type = Moorabi_Functions::moorabi_get_option('banner_type', 'no_background');
$banner_full_width = Moorabi_Functions::moorabi_get_option('banner_full_width', 0);
$banner_image = Moorabi_Functions::moorabi_get_option('banner_image');
$banner_rev_slide = Moorabi_Functions::moorabi_get_option('banner_rev_slide');
if (!is_front_page() && is_home()) {
    $banner_type = Moorabi_Functions::moorabi_get_option('blog_banner_type', 'no_background');
    $banner_full_width = Moorabi_Functions::moorabi_get_option('blog_banner_full_width', 0);
    $banner_image = Moorabi_Functions::moorabi_get_option('blog_banner_image');
    $banner_rev_slide = Moorabi_Functions::moorabi_get_option('blog_banner_rev_slide');
}
if (class_exists('WooCommerce')) {
    if (is_woocommerce()) {
        $banner_type = Moorabi_Functions::moorabi_get_option('shop_banner_type', 'no_background');
        $banner_full_width = Moorabi_Functions::moorabi_get_option('shop_banner_full_width', 0);
        $banner_image = Moorabi_Functions::moorabi_get_option('shop_banner_image');
        $banner_rev_slide = Moorabi_Functions::moorabi_get_option('shop_banner_rev_slide');
    }
}
$banner_type = !empty($data_meta['enable_banner']) && $data_meta['enable_banner'] == 1 && !empty($data_meta['metabox_banner_type']) ? $data_meta['metabox_banner_type'] : $banner_type;
$banner_full_width = !empty($data_meta['enable_banner']) && $data_meta['enable_banner'] == 1 && !empty($data_meta['metabox_banner_full_width']) ? $data_meta['metabox_banner_full_width'] : $banner_full_width;
$banner_image = !empty($data_meta['enable_banner']) && $data_meta['enable_banner'] == 1 && !empty($data_meta['metabox_banner_image']) ? $data_meta['metabox_banner_image'] : $banner_image;
$banner_rev_slide = !empty($data_meta['enable_banner']) && $data_meta['enable_banner'] == 1 && !empty($data_meta['metabox_banner_rev_slide']) ? $data_meta['metabox_banner_rev_slide'] : $banner_rev_slide;
$disable_revolution_on_mobile = !empty($data_meta['enable_banner']) && $data_meta['enable_banner'] == 1 && !empty($data_meta['disable_revolution_on_mobile']) ? $data_meta['disable_revolution_on_mobile'] : 0;
if ((is_single() || is_search())) {
    $banner_type = 'no_background';
    $banner_image = '';
}
$container[] = $banner_type;
if ($banner_full_width != 1) {
    $container[] = 'container';
}
?>
<!--Start-->
<?php if (is_404() || $banner_type == 'disable' || ($disable_revolution_on_mobile && $disable_revolution_on_mobile == 1 && wp_is_mobile())) {
    return;
} ?>
<!-- Banner page -->
<div class="<?php echo esc_attr(implode(' ', $container)); ?>">
    <?php if ($banner_type == 'has_background') { ?>
        <div class="banner-media">
            <?php if (!empty($banner_image)) {
                $banner_thumb = apply_filters('moorabi_resize_image', $banner_image, false, false, true, true);
                echo wp_specialchars_decode($banner_thumb['img']);
            } else { ?>
                <img alt="<?php echo esc_attr(get_bloginfo('name')) ?>" src="<?php echo esc_url(get_theme_file_uri('/assets/images/banner.jpg')); ?>"/>
            <?php } ?>
        </div>
    <?php } ?>
    <div class="banner-wrapper-inner">
        <div class="container">
            <?php if ($banner_type == 'rev_background' && class_exists('RevSliderOutput') && $banner_rev_slide && $banner_rev_slide != '') { ?>
                <div class="banner-media">
                    <?php echo do_shortcode("[rev_slider alias='" . $banner_rev_slide . "'][/rev_slider]"); ?>
                </div>
            <?php } ?>
            <?php get_template_part('templates-part/page', 'title'); ?>
            <?php get_template_part('templates-part/page', 'breadcrumb'); ?>
        </div>
    </div>
</div>

