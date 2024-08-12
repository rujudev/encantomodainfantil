<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Moorabi
 * @since 1.0
 * @version 1.0
 */
?>
<?php
do_action('moorabi_footer_content');
get_template_part('templates-part/popup', 'content'); ?>
<a href="javascript:void(0)" class="backtotop">
    <i class="fa fa-angle-double-up"></i>
</a>
</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>
