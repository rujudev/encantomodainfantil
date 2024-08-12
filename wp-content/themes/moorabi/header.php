<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Appliances
 * @since 1.0
 * @version 1.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="profile" href="//gmpg.org/xfn/11">
        <?php wp_head(); ?>
    </head>
<body <?php body_class(); ?> >
<?php wp_body_open(); ?>
<div id="page" class="site">
<!-- #page -->
    <?php if (function_exists('moorabi_template_header')) {
        moorabi_template_header();
        if ( !has_nav_menu('moorabi_mobile_menu')) {
            echo "jñldsfjañf";
            moorabi_mobile_menu( 'primary' );
        }
    }
    get_template_part('templates-part/page', 'banner');