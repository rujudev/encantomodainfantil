<?php

/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $product;
// Ensure visibility
if (!empty($product) || $product->is_visible()) {
    $moorabi_animate_class = 'wow fadeInUp product-item';

    // Custom columns
    $woo_bg_items = Moorabi_Functions::moorabi_get_option('woo_bg_items', 3);
    $woo_lg_items = Moorabi_Functions::moorabi_get_option('woo_lg_items', 3);
    $woo_md_items = Moorabi_Functions::moorabi_get_option('woo_md_items', 4);
    $woo_sm_items = Moorabi_Functions::moorabi_get_option('woo_sm_items', 6);
    $woo_xs_items = Moorabi_Functions::moorabi_get_option('woo_xs_items', 6);
    $woo_ts_items = Moorabi_Functions::moorabi_get_option('woo_ts_items', 6);
    $shop_display_mode = Moorabi_Functions::moorabi_get_option('shop_style', 'grid');
    $classes[] = 'product-item';
    $classes[] = $moorabi_animate_class;
    $shop_product_style = Moorabi_Functions::moorabi_get_option( 'shop_product_style', 'style-01' );
    if ($shop_display_mode == 'list') {
        $classes[] = 'list col-sm-12';
    } else {
        $classes[] = 'rows-space-30';
        $classes[] = 'col-bg-' . $woo_bg_items;
        $classes[] = 'col-lg-' . $woo_lg_items;
        $classes[] = 'col-md-' . $woo_md_items;
        $classes[] = 'col-sm-' . $woo_sm_items;
        $classes[] = 'col-xs-' . $woo_xs_items;
        $classes[] = 'col-ts-' . $woo_ts_items;
    }
    if ($shop_display_mode == 'grid') {
        $classes[] = $shop_product_style;
    }
    ?>
    <li <?php post_class($classes); ?> data-wow-duration="1s" data-wow-delay="0ms" data-wow="fadeInUp">
        <?php if ($shop_display_mode == 'list'):
            get_template_part('woocommerce/product-styles/content-product', 'list');
        else:
            get_template_part('woocommerce/product-styles/content-product', $shop_product_style);

        endif; ?>
    </li>
    <?php
}