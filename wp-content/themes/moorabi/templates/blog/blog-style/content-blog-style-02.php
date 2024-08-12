<div class="post-inner">
    <?php if (has_post_thumbnail()) { ?>
        <div class="post-thumb">
            <a href="<?php the_permalink(); ?>">
                <?php $thumb = apply_filters('moorabi_resize_image', get_post_thumbnail_id(), 230, 300, true, true);
                echo wp_specialchars_decode($thumb['img']); ?>
            </a>
        </div>
    <?php } ?>
    <div class="post-info">
        <?php moorabi_post_category(); ?>
        <div class="post-meta">
            <?php moorabi_post_datetime(); ?>
        </div>
        <?php moorabi_post_title(); ?>
        <?php moorabi_post_author(); ?>
    </div>
</div>