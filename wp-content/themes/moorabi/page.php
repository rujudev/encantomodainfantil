<?php get_header(); ?>
<?php
$sidebar_isset = wp_get_sidebars_widgets();
/* Data MetaBox */
$data_meta = get_post_meta(get_the_ID(), '_custom_page_side_options', true);
/* Data MetaBox */
$data_meta_banner = get_post_meta(get_the_ID(), '_custom_metabox_theme_options', true);
/*Default page layout*/
$moorabi_page_extra_class = isset($data_meta['page_extra_class']) ? $data_meta['page_extra_class'] : '';
$moorabi_page_layout = isset($data_meta['page_sidebar_layout']) ? $data_meta['page_sidebar_layout'] : 'left';
$moorabi_page_sidebar = isset($data_meta['page_sidebar']) ? $data_meta['page_sidebar'] : 'widget-area';
if (isset($sidebar_isset[$moorabi_page_sidebar]) && empty($sidebar_isset[$moorabi_page_sidebar])) {
    $moorabi_page_layout = 'full';
}
/*Main container class*/
$moorabi_main_container_class = array();
$moorabi_main_container_class[] = $moorabi_page_extra_class;
$moorabi_main_container_class[] = 'main-container';
if ($moorabi_page_layout == 'full') {
    $moorabi_main_container_class[] = 'no-sidebar';
} else {
    $moorabi_main_container_class[] = $moorabi_page_layout . '-sidebar has-sidebar';
}
$moorabi_main_content_class = array();
$moorabi_main_content_class[] = 'main-content';
if ($moorabi_page_layout == 'full') {
    $moorabi_main_content_class[] = 'col-sm-12';
} else {
    $moorabi_main_content_class[] = 'col-lg-9 col-md-8 col-sm-8 col-xs-12';
}
$moorabi_sidebar_class = array();
$moorabi_sidebar_class[] = 'sidebar';
if ($moorabi_page_layout != 'full') {
    $moorabi_sidebar_class[] = 'col-lg-3 col-md-4 col-sm-4 col-xs-12';
}
?>
    <main class="site-main <?php echo esc_attr(implode(' ', $moorabi_main_container_class)); ?>">
        <div class="container">
            <div class="row">
                <div class="<?php echo esc_attr(implode(' ', $moorabi_main_content_class)); ?>">
                    <?php
                    if (have_posts()) {
                        while (have_posts()) {
                            the_post();
                            ?>
                            <div class="page-main-content">
                                <?php
                                the_content();
                                wp_link_pages(array(
                                        'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__('Pages:', 'moorabi') . '</span>',
                                        'after' => '</div>',
                                        'link_before' => '<span>',
                                        'link_after' => '</span>',
                                        'pagelink' => '<span class="screen-reader-text">' . esc_html__('Page', 'moorabi') . ' </span>%',
                                        'separator' => '<span class="screen-reader-text">, </span>',
                                    )
                                );
                                ?>
                            </div>
                            <?php
                            // If comments are open or we have at least one comment, load up the comment template.
                            if (comments_open() || get_comments_number()) :
                                comments_template();
                            endif;
                        }
                    }
                    ?>
                </div>
                <?php if ($moorabi_page_layout != "full"):
                    if (is_active_sidebar($moorabi_page_sidebar)) : ?>
                        <div id="widget-area"
                             class="widget-area <?php echo esc_attr(implode(' ', $moorabi_sidebar_class)); ?>">
                            <?php dynamic_sidebar($moorabi_page_sidebar); ?>
                        </div><!-- .widget-area -->
                    <?php endif;
                endif; ?>
            </div>
        </div>
    </main>
<?php get_footer();