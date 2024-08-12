<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Moorabi
 */
?>
<?php
get_header();
$sidebar_isset = wp_get_sidebars_widgets();
$blog_layout = Moorabi_Functions::moorabi_get_option('post_sidebar_layout', 'left');
$blog_used_sidebar = 'widget-area';
$moorabi_container_class = array('main-container');
if (isset($sidebar_isset[$blog_used_sidebar]) && empty($sidebar_isset[$blog_used_sidebar])) {
    $blog_layout = 'full';
}
if ($blog_layout == 'full') {
    $moorabi_container_class[] = 'no-sidebar';
} else {
    $moorabi_container_class[] = $blog_layout . '-sidebar has-sidebar';
}
$moorabi_content_class = array();
$moorabi_content_class[] = 'main-content';
if ($blog_layout == 'full') {
    $moorabi_content_class[] = 'col-sm-12 col-xs-12';
} else {
    $moorabi_content_class[] = 'col-lg-9 col-md-8 col-sm-12 col-xs-12';
}
$moorabi_sidebar_class = array();
$moorabi_sidebar_class[] = 'sidebar moorabi_sidebar';
if ($blog_layout != 'full') {
    $moorabi_sidebar_class[] = 'col-lg-3 col-md-4 col-sm-12 col-xs-12';
}
?>
<div class="<?php echo esc_attr(implode(' ', $moorabi_container_class)); ?>">
    <!-- POST LAYOUT -->
    <article <?php post_class('post-item post-single'); ?>>
        <div class="container">
            <?php while (have_posts()): the_post(); ?>
                <div class="single-post-thumb">
                    <?php moorabi_post_format(); ?>
                </div>
                <div class="row">
                    <div class="<?php echo esc_attr(implode(' ', $moorabi_content_class)); ?>">
                        <div class="single-post-info">
                            <?php
                            moorabi_post_title();
                            ?>
                            <div class="post-meta">
                                <?php
                                moorabi_post_date();
                                moorabi_post_category();
                                ?>
                            </div>
                        </div>
                        <?php moorabi_post_full_content(); ?>
                        <div class="post-footer">
                            <?php
                            do_action('moorabi_post_footer');
                            moorabi_post_tags();
                            ?>
                        </div>
                        <?php moorabi_single_post_author(); ?>
                        <?php
                        /*If comments are open or we have at least one comment, load up the comment template.*/
                        if (comments_open() || get_comments_number()) :
                            comments_template();
                        endif;
                        ?>
                    </div>
                    <?php if ($blog_layout != 'full'): ?>
                        <div class="<?php echo esc_attr(implode(' ', $moorabi_sidebar_class)); ?>">
                            <?php get_sidebar(); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </article>
</div>

<?php get_footer(); ?>
