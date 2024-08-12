<?php
$classes = array('post-item', 'post-grid');
$classes[] = 'col-bg-' . Moorabi_Functions::moorabi_get_option('blog_bg_items', 4);
$classes[] = 'col-lg-' . Moorabi_Functions::moorabi_get_option('blog_lg_items', 4);
$classes[] = 'col-md-' . Moorabi_Functions::moorabi_get_option('blog_md_items', 4);
$classes[] = 'col-sm-' . Moorabi_Functions::moorabi_get_option('blog_sm_items', 6);
$classes[] = 'col-xs-' . Moorabi_Functions::moorabi_get_option('blog_xs_items', 12);
$classes[] = 'col-ts-' . Moorabi_Functions::moorabi_get_option('blog_ts_items', 12);
if (have_posts()) : ?>
    <div class="blog-grid auto-clear content-post row">
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class($classes); ?>>
                <div class="post-inner">
                    <div class="post-thumb">
                        <a href="<?php the_permalink(); ?>">
                            <?php $thumb = apply_filters('moorabi_resize_image', get_post_thumbnail_id(), 370, 300, true, true);
                            echo wp_specialchars_decode($thumb['img']); ?>
                        </a>
                    </div>
                    <div class="post-info">
                        <div class="post-meta">
                            <?php moorabi_post_datetime(); ?>
                        </div>
                        <?php moorabi_post_title(); ?>
                        <div class="post-content">
                            <?php echo wp_trim_words(apply_filters('the_excerpt', get_the_excerpt()), 15, '...'); ?>
                        </div>
                        <?php moorabi_post_readmore(); ?>
                    </div>
                </div>
            </article>
        <?php endwhile;
        wp_reset_postdata(); ?>
    </div>
    <?php
    /**
     * Functions hooked into moorabi_after_blog_content action
     *
     * @hooked moorabi_paging_nav               - 10
     */
    do_action('moorabi_after_blog_content'); ?>
<?php else :
    get_template_part('content', 'none');
endif;