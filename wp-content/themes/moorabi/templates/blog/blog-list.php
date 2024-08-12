<?php
if (have_posts()) : ?>
    <div class="blog-list content-post">
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class('post-item'); ?>>
                <div class="post-list post-inner">
                    <div class="post-thumb">
                        <a href="<?php the_permalink(); ?>">
                            <?php $thumb = apply_filters('moorabi_resize_image', get_post_thumbnail_id(), 370, 300, true, true);
                            echo wp_specialchars_decode($thumb['img']); ?>
                        </a>
                    </div>
                    <div class="post-info">
                        <div class="post-meta">
                            <?php
                                moorabi_post_author();
                                moorabi_post_date();
                            ?>
                        </div>
                        <?php moorabi_post_title(); ?>
                        <div class="post-content">
                            <?php echo wp_trim_words(apply_filters('the_excerpt', get_the_excerpt()), 15, '...'); ?>
                        </div>
                        <?php moorabi_post_readmore();?>
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