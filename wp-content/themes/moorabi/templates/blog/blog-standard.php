<?php
if (have_posts()) : ?>
    <?php do_action('moorabi_before_blog_content'); ?>
    <div class="blog-standard content-post">
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class('post-item'); ?>>
                <div class="post-standard post-inner">
                    <?php moorabi_post_format(); ?>
                    <div class="post-info">
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
                    <?php
                    moorabi_post_excerpt();
                    moorabi_post_readmore();
                    ?>
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
endif; ?>