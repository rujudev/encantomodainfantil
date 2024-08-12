<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<?php
if (class_exists('WooCommerce')) {
    if (is_woocommerce()) {
        do_action('moorabi_woocommerce_breadcrumb');
    } elseif (!is_front_page() && !is_page_template('templates/fullwidth.php')) {
        $args = array(
            'container' => 'div',
            'before' => '',
            'after' => '',
            'show_on_front' => true,
            'network' => false,
            'show_title' => true,
            'show_browse' => false,
            'post_taxonomy' => array(),
            'labels' => array(),
            'echo' => true,
        );
        do_action('moorabi_breadcrumb', $args);
    }
} elseif (!is_front_page() && !is_page_template('templates/fullwidth.php')) {
    $args = array(
        'container' => 'div',
        'before' => '',
        'after' => '',
        'show_on_front' => true,
        'network' => false,
        'show_title' => true,
        'show_browse' => false,
        'post_taxonomy' => array(),
        'labels' => array(),
        'echo' => true,
    );
    do_action('moorabi_breadcrumb', $args);
}
