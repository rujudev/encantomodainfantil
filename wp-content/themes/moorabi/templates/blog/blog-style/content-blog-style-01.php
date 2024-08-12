<div class="post-inner post-grid">
    <?php if (has_post_thumbnail()) { ?>
        <div class="post-thumb">
            <a href="<?php the_permalink(); ?>">
                <?php $thumb = apply_filters('moorabi_resize_image', get_post_thumbnail_id(), 370, 300, true, true);
                echo wp_specialchars_decode($thumb['img']); ?>
            </a>
        </div>
    <?php } ?>
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