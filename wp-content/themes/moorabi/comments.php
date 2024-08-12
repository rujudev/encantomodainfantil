<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link       https://codex.wordpress.org/Template_Hierarchy
 *
 * @package    WordPress
 * @subpackage Moorabi
 * @since      1.0
 * @version    1.0
 */
if (post_password_required()) {
    return;
}
$fields = array(
    'name' => '<p class="comment-reply-content"><input type="text" name="author" id="name" class="input-form name" placeholder="' . esc_attr__('Name*', 'moorabi') . '"/></p>',
    'email' => '<p class="comment-reply-content"><input type="text" name="email" id="email" class="input-form email" placeholder="' . esc_attr__('Email*', 'moorabi') . '"/></p>',
);
$comment_field = '<p class="comment-form-comment"><textarea class="input-form" id="comment" name="comment" cols="45" rows="6" aria-required="true" placeholder="' . esc_attr__('Enter your comment here...', 'moorabi') . '"></textarea></p>';
$comment_field .= apply_filters('moorabi_add_field_comment', '');
$comment_form_args = array(
    'comment_field' => $comment_field,
    'fields' => $fields,
    'label_submit' => esc_html__('Post a comment', 'moorabi'),
    'title_reply' => esc_html__('Leave a comment', 'moorabi'),
);
?>
<div id="comments" class="comments-area">
    <?php if (have_comments()) :
        $comments_number = get_comments_number(); ?>
        <h2 class="comments-title">
            <span class="comment-number">
                <?php echo esc_html__('Comments', 'moorabi'); ?>
                (<?php echo number_format_i18n($comments_number); ?>)
            </span>
        </h2>
        <ol class="commentlist">
            <?php
            wp_list_comments(array(
                    'style' => 'ol',
                    'callback' => 'moorabi_callback_comment',
                )
            );
            ?>
        </ol>
        <?php
    endif;
    the_comments_pagination(array(
            'screen_reader_text' => '',
            'prev_text' => '<span class="screen-reader-text">' . esc_html__('Prev', 'moorabi') . '</span>',
            'next_text' => '<span class="screen-reader-text">' . esc_html__('Next', 'moorabi') . '</span>',
        )
    );
    if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
        <p class="no-comments"><?php echo esc_html__('Comments are closed.', 'moorabi'); ?></p>
        <?php
    endif;
    comment_form($comment_form_args);
    ?>
</div><!-- #comments -->
