<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $post;
$enable_popup = Moorabi_Functions::moorabi_get_option('enable_popup');
$popup_title = Moorabi_Functions::moorabi_get_option('popup_title', 'Sign up & connect to Moorabi');
$popup_desc = Moorabi_Functions::moorabi_get_option('popup_desc', '');
$popup_input_submit = Moorabi_Functions::moorabi_get_option('popup_input_submit', '');
$popup_input_placeholder = Moorabi_Functions::moorabi_get_option('popup_input_placeholder', 'Email address here...');
$popup_background = Moorabi_Functions::moorabi_get_option('popup_background');
$page_newsletter = Moorabi_Functions::moorabi_get_option('select_newsletter_page');
if (isset($post->ID))
    $id = $post->ID;
if (isset($post->post_type))
    $post_type = $post->post_type;
if (is_array($page_newsletter) && in_array($id, $page_newsletter) && $post_type == 'page' && $enable_popup == 1) :?>
    <!--  Popup Newsletter-->
    <div class="modal fade" id="popup-newsletter" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                <div class="modal-inner">
                    <?php if ($popup_background) : ?>
                        <div class="modal-thumb">
                            <?php
                            $image_thumb = wp_get_attachment_image_src($popup_background, 'full');
                            ?>
                            <img src="<?php echo esc_url($image_thumb[0]) ?>"
                                <?php echo image_hwstring($image_thumb[1], $image_thumb[2]); ?>
                                 alt="<?php echo esc_attr__('Newsletter', 'moorabi'); ?>">
                        </div>
                    <?php endif; ?>
                    <div class="modal-info">
                        <?php if ($popup_title): ?>
                            <h2 class="title"><?php echo esc_html($popup_title); ?></h2>
                        <?php endif; ?>
                        <?php if ($popup_desc): ?>
                            <p class="des"><?php echo wp_specialchars_decode($popup_desc); ?></p>
                        <?php endif; ?>
                        <div class="newsletter-form-wrap">
                            <input class="email" type="email" name="email"
                                   placeholder="<?php echo esc_html($popup_input_placeholder); ?>">
                            <button type="submit" name="submit_button" class="btn-submit submit-newsletter">
                                <?php echo esc_html($popup_input_submit); ?>
                            </button>
                        </div>
                        <div class="checkbox btn-checkbox">
                            <label>
                                <input class="moorabi_disabled_popup_by_user" type="checkbox">
                                <span><?php echo esc_html__('Don&rsquo;t show this popup again!', 'moorabi'); ?></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--  Popup Newsletter-->
<?php endif;