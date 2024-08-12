<?php
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Moorabi Mailchimp
 *
 * Displays Mailchimp widget.
 *
 * @category Widgets
 * @package  Moorabi/Widgets
 * @version  1.0.0
 * @extends  MOORABI_Widget
 */
if (!class_exists('Moorabi_Mailchimp_Widget')) {
    class Moorabi_Mailchimp_Widget extends MOORABI_Widget
    {
        /**
         * Constructor.
         */
        public function __construct()
        {
            $array_settings = apply_filters('moorabi_filter_settings_widget_mailchimp',
                array(
                    'title' => array(
                        'type' => 'text',
                        'title' => esc_html__('Title', 'moorabi-toolkit'),
                        'default' => esc_html__('Newsletter', 'moorabi-toolkit'),
                    ),
                    'description' => array(
                        'type' => 'text',
                        'title' => esc_html__('Description:', 'moorabi-toolkit'),
                        'default' => esc_html__('To stay up-to-date on our promotions, discounts, sales and more', 'moorabi-toolkit'),
                    ),
                )
            );
            $this->widget_cssclass = 'widget-moorabi-mailchimp';
            $this->widget_description = esc_html__('Display the customer Newsletter.', 'moorabi-toolkit');
            $this->widget_id = 'widget_moorabi_mailchimp';
            $this->widget_name = esc_html__('Moorabi: Newsletter', 'moorabi-toolkit');
            $this->settings = $array_settings;
            parent::__construct();
        }

        /**
         * Output widget.
         *
         * @see WP_Widget
         *
         * @param array $args
         * @param array $instance
         */
        public function widget($args, $instance)
        {
            $this->widget_start($args, $instance);
            ob_start();
            ?>
            <div class="newsletter-form-wrap">
                <div class="desc"><?php echo esc_html($instance['description']); ?></div>
                <div class="form-newsletter">
                    <?php
                    if (function_exists('mc4wp_show_form')) {
                        $mc4wparr  = array(
                            'posts_per_page' => 1,
                            'post_type'      => 'mc4wp-form',
                            'post_status'    => 'publish',
                            'fields'         => 'ids',
                        );
                        $mc4wp = get_posts($mc4wparr);
                        if ($mc4wp) {
                            foreach ($mc4wp as $post_id) {
                                mc4wp_show_form(intval($post_id));
                            }
                        }
                    }
                    ?>
                </div>
            </div>
            <?php
            echo apply_filters('moorabi_filter_widget_newsletter', ob_get_clean(), $instance);
            $this->widget_end($args);
        }
    }
}
add_action('widgets_init', 'Moorabi_Mailchimp_Widget');
if (!function_exists('Moorabi_Mailchimp_Widget')) {
    function Moorabi_Mailchimp_Widget()
    {
        register_widget('Moorabi_Mailchimp_Widget');
    }
}