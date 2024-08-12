<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
/**
 *
 * HOOK AFTER BLOG CONTENT
 */
add_action('moorabi_after_blog_content', 'moorabi_paging_nav', 10);
/**
 *
 * HOOK TEMPLATE
 */
if (!function_exists('moorabi_paging_nav')) {
    function moorabi_paging_nav()
    {
        global $wp_query;
        $max = $wp_query->max_num_pages;
        // Don't print empty markup if there's only one page.
        if ($max >= 2) {
            echo get_the_posts_pagination(
                array(
                    'screen_reader_text' => '&nbsp;',
                    'before_page_number' => '',
                    'prev_text' => '<i class="fa fa-angle-left"></i>',
                    'next_text' => '<i class="fa fa-angle-right"></i>',
                )
            );
        }
    }
}
if (!function_exists('moorabi_post_thumbnail')) {
    function moorabi_post_thumbnail()
    {
        $blog_style = Moorabi_Functions::moorabi_get_option('blog_style', 'standard');
        $thumb = apply_filters('moorabi_resize_image', get_post_thumbnail_id(), 370, 330, true, true);
        if (has_post_thumbnail()) :
            ?>
            <div class="post-thumb">
                <?php
                if (is_single()) {
                    the_post_thumbnail('full');
                } else {
                    echo '<a href="' . get_the_permalink() . '">';
                    if ($blog_style == 'grid') {
                        echo wp_specialchars_decode($thumb['img']);
                    } else {
                        the_post_thumbnail('full');
                    }
                    echo '</a>';
                }
                ?>
            </div>
            <?php
        endif;
    }
}
if (!function_exists('moorabi_post_format')) {
    function moorabi_post_format()
    {
        $blog_style = Moorabi_Functions::moorabi_get_option('blog_style', 'standard');
        $moorabi_post_meta = get_post_meta(get_the_ID(), '_custom_metabox_post_options', true);
        $gallery_post = isset($moorabi_post_meta['gallery_post']) ? $moorabi_post_meta['gallery_post'] : '';
        $video_post = isset($moorabi_post_meta['video_post']) ? $moorabi_post_meta['video_post'] : '';
        $post_format = get_post_format();
        $class = 'post-thumb';
        $check = false;
        if ($gallery_post != '' && $post_format == 'gallery') {
            $check = true;
        }
        if ($video_post != '' && $post_format == 'video') {
            $check = true;
        }
        $width = 1400;
        $height = 970;
        if (has_post_thumbnail()) :
            ?>
            <div class="<?php echo esc_attr($class); ?>">
                <?php
                if ($check == true && $blog_style != 'grid') {
                    if ($post_format == 'gallery') :
                        $gallery_post = explode(',', $gallery_post);
                        ?>
                        <div class="owl-slick"
                             data-slick='{"arrows": true, "dots": false, "infinite": false, "slidesToShow": 1}'>
                            <figure>
                                <?php
                                $image_thumb = apply_filters('moorabi_resize_image', get_post_thumbnail_id(), $width, $height, true, true);
                                echo wp_specialchars_decode($image_thumb['img']);
                                ?>
                            </figure>
                            <?php foreach ($gallery_post as $item) : ?>
                                <figure>
                                    <?php
                                    $image_gallery = apply_filters('moorabi_resize_image', $item, $width, $height, true, true);
                                    echo wp_specialchars_decode($image_gallery['img']);
                                    ?>
                                </figure>
                            <?php endforeach; ?>
                        </div>
                    <?php endif;
                    if ($post_format == 'video') {
                        the_widget('WP_Widget_Media_Video', 'url=' . $video_post . '');
                    }
                } else {
                    if (is_single()) {
                        the_post_thumbnail('full');
                    } else {
                        $image_thumb = apply_filters('moorabi_resize_image', get_post_thumbnail_id(), $width, $height, true, true);
                        echo '<a href="' . get_permalink() . '">';
                        echo wp_specialchars_decode($image_thumb['img']);
                        echo '</a>';
                    }
                }
                ?>
            </div>
            <?php
        endif;
    }
}
if (!function_exists('moorabi_post_author')) {
    function moorabi_post_author()
    {
        ?>
        <div class="post-author">
            <?php
            echo get_avatar(get_the_author_meta('ID'), 28, '', get_the_author_meta('user_nicename'))
            ?>
            <a href="<?php echo get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename')); ?>">
                <?php the_author(); ?>
            </a>
        </div>
        <?php
    }
}
if (!function_exists('moorabi_post_comment')) {
    function moorabi_post_comment()
    {
        ?>
        <div class="post-comment">
            <a href="<?php the_permalink(); ?>#comments">
                <?php
                comments_number(
                    esc_html__('0 Comments', 'moorabi'),
                    esc_html__('1 Comment', 'moorabi'),
                    esc_html__('% Comments', 'moorabi')
                );
                ?>
            </a>
        </div>
        <?php
    }
}
if (!function_exists('moorabi_post_comment_icon')) {
    function moorabi_post_comment_icon()
    {
        ?>
        <div class="post-comment-icon">
            <a href="<?php the_permalink(); ?>#comments">
                <?php
                comments_number(
                    esc_html__('(0)', 'moorabi'),
                    esc_html__('(1)', 'moorabi'),
                    esc_html__('(%)', 'moorabi')
                );
                ?>
            </a>
        </div>
        <?php
    }
}
if (!function_exists('moorabi_callback_comment')) {
    /**
     * Moorabi comment template
     *
     * @param array $comment the comment array.
     * @param array $args the comment args.
     * @param int $depth the comment depth.
     *
     * @since 1.0.0
     */
    function moorabi_callback_comment($comment, $args, $depth)
    {
        if ('div' == $args['style']) {
            $tag = 'div ';
            $add_below = 'comment';
        } else {
            $tag = 'li ';
            $add_below = 'div-comment';
        }
        ?>
        <<?php echo esc_attr($tag); ?><?php comment_class(empty($args['has_children']) ? '' : 'parent') ?> id="comment-<?php echo get_comment_ID(); ?>">
        <div class="comment_container">
            <div class="comment-avatar">
                <?php echo get_avatar($comment, 90); ?>
            </div>
            <div class="comment-text commentmetadata">
                <strong class="comment-author vcard">
                    <?php printf(wp_kses_post('%s', 'moorabi'), get_comment_author_link()); ?>
                </strong>
                <?php if ('0' == $comment->comment_approved) : ?>
                    <em class="comment-awaiting-moderation"><?php esc_attr_e('Your comment is awaiting moderation.', 'moorabi'); ?></em>
                    <br/>
                <?php endif; ?>
                <a href="<?php echo esc_url(htmlspecialchars(get_comment_link(get_comment_ID()))); ?>"
                   class="comment-date">
                    <?php echo '<time datetime="' . get_comment_date('c') . '">' . get_comment_date() . '</time>'; ?>
                </a>
                <?php edit_comment_link(__('Edit', 'moorabi'), '  ', ''); ?>
                <?php comment_reply_link(array_merge($args, array(
                    'add_below' => $add_below,
                    'depth' => $depth,
                    'max_depth' => $args['max_depth']
                ))); ?>
                <?php echo ('div' != $args['style']) ? '<div id="div-comment-' . get_comment_ID() . '" class="comment-content">' : '' ?>
                <?php comment_text(); ?>
                <?php echo 'div' != $args['style'] ? '</div>' : ''; ?>
            </div>
        </div>
        <?php
    }
}
if (!function_exists('moorabi_post_title')) {
    function moorabi_post_title()
    {
        ?>
        <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <?php
    }
}
if (!function_exists('moorabi_post_readmore')) {
    function moorabi_post_readmore()
    {
        ?>
        <a href="<?php the_permalink(); ?>"
           class="readmore"><?php echo esc_html__('Read more', 'moorabi'); ?></a>
        <?php
    }
}
if (!function_exists('moorabi_post_excerpt')) {
    function moorabi_post_excerpt()
    {
        ?>
        <div class="post-content">
            <?php echo wp_trim_words(apply_filters('the_excerpt', get_the_excerpt()), 50, esc_html__('...', 'moorabi')); ?>
        </div>
        <?php
    }
}
if (!function_exists('moorabi_post_full_content')) {
    function moorabi_post_full_content()
    {
        ?>
        <div class="post-content">
            <?php
            /* translators: %s: Name of current post */
            the_content(sprintf(
                    esc_html__('Continue reading %s', 'moorabi'),
                    the_title('<span class="screen-reader-text">', '</span>', false)
                )
            );
            wp_link_pages(array(
                    'before' => '<div class="post-pagination"><span class="title">' . esc_html__('Pages:', 'moorabi') . '</span>',
                    'after' => '</div>',
                    'link_before' => '<span>',
                    'link_after' => '</span>',
                )
            );
            ?>
        </div>
        <?php
    }
}
if (!function_exists('moorabi_post_date')) {
    function moorabi_post_date()
    {
        $archive_year = get_the_time('Y');
        $archive_month = get_the_time('m');
        $archive_day = get_the_time('d');
        ?>
        <div class="date">
            <a href="<?php echo get_day_link($archive_year, $archive_month, $archive_day); ?>">
                <?php echo get_the_date(); ?>
            </a>
        </div>
        <?php
    }
}
if (!function_exists('moorabi_post_datetime')) {
    function moorabi_post_datetime()
    {
        $archive_year = get_the_time('Y');
        $archive_month = get_the_time('m');
        $archive_day = get_the_time('d');
        ?>
        <div class="datetime">
            <a href="<?php echo get_day_link($archive_year, $archive_month, $archive_day); ?>">
                <?php echo get_the_date('F d, h:i a'); ?>
            </a>
        </div>
        <?php
    }
}
if (!function_exists('moorabi_post_tags')) {
    function moorabi_post_tags()
    {
        if (!empty(get_the_terms(get_the_ID(), 'post_tag'))) : ?>
            <div class="tags"><span><?php echo esc_html__('Tags: ', 'moorabi') ?></span>
                <?php $tags_list = get_the_tag_list('', '');
                if ($tags_list) {
                    printf(esc_html__('%1$s', 'moorabi'), $tags_list);
                } ?></div>
        <?php endif;
    }
}
if (!function_exists('moorabi_post_category')) {
    function moorabi_post_category()
    {
        $items = array();
        $taxonomy_names = get_post_taxonomies();
        if (isset($taxonomy_names[0]) && !empty($taxonomy_names)) {
            $get_terms = get_the_terms(get_the_ID(), $taxonomy_names[0]);
        } else {
            $get_terms = array();
        }
        if (!is_wp_error($get_terms) && !empty($get_terms)) : ?>
            <div class="categories">
                <?php
                foreach ($get_terms as $term) {
                    $link = get_term_link($term->term_id, $taxonomy_names[0]);
                    $items[] = '<a href="' . esc_url($link) . '">' . esc_html($term->name) . '</a>';
                }
                echo join(', ', $items);
                ?>
            </div>
        <?php endif;
    }
}
if ( ! function_exists( 'moorabi_single_post_author' ) ) {
    function moorabi_single_post_author() {
        $enable_author_info = Moorabi_Functions::moorabi_get_option( 'enable_author_info' );
        if ( $enable_author_info == 1 ):
            ?>
            <div class="post-single-author">
                <figure class="avatar"><?php echo get_avatar( get_the_author_meta( 'ID' ), 140 ); ?></figure>
                <div class="author-info">
                    <h4 class="name"><?php the_author(); ?></h4>
                    <p class="desc">
                        <?php the_author_meta( 'description' ); ?>
                    </p>
                    <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>">
                        <?php echo esc_html__( 'All author posts', 'moorabi' ); ?>
                        <span class="fa fa-angle-right"></span>
                    </a>
                </div>
            </div>
            <?php
        endif;
    }
}