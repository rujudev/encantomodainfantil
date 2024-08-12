<?php
if (!class_exists('Moorabi_Elementor_Newsletter')) {
    class Moorabi_Elementor_Newsletter extends Moorabi_Elementor
    {
        public $name = 'newsletter';
        public $title = 'Newsletter';
        public $icon = 'moorabi-elementor-icon eicon-mailchimp';

        /**
         * Register the widget controls.
         *
         * Adds different input fields to allow the user to change and customize the widget settings.
         *
         * @since 1.0.0
         *
         * @access protected
         */
        protected function register_controls()
        {

            $this->start_controls_section(
                'general_section',
                [
                    'label' => esc_html__('General', 'moorabi'),
                ]
            );
            $this->add_control(
                'style',
                [
                    'label' => esc_html__('Style', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => Moorabi_Elementor::moorabi_elementor_preview($this->name),
                    'default' => 'style-01',
                    'label_block' => true
                ]
            );
            foreach (Moorabi_Elementor::moorabi_elementor_preview($this->name) as $key => $value) {
                $this->add_control(
                    $key,
                    [
                        'label' => '<img alt="' . esc_attr($this->name) . '" src="' . esc_url(get_theme_file_uri('/assets/images/elementor/' . $this->name . '/' . $key . '.jpg')) . '"/>',
                        'type' => \Elementor\Controls_Manager::HEADING,
                        'condition' => array(
                            'style' => $key
                        ),
                    ]
                );
            }
            $this->add_control(
                'title', [
                    'label' => esc_html__('Title', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Enter your title', 'moorabi'),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'desc', [
                    'label' => esc_html__('Description', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                    'placeholder' => esc_html__('Enter your description', 'moorabi'),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'form_id', [
                    'label' => esc_html__('Select a form', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Enter your shortcode', 'moorabi'),
                    'default' => '[mc4wp_form id="0"]',
                    'label_block' => true
                ]
            );
            $this->end_controls_section();

        }

        /**
         * Render the widget output on the frontend.
         *
         * Written in PHP and used to generate the final HTML.
         *
         * @since 1.0.0
         *
         * @access protected
         */
        protected function render()
        {
            $atts = $this->get_settings_for_display();
            $css_class = array('moorabi-newsletter');
            $css_class[] = $atts['style'];
            ?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                <div class="newsletter-inner">
                    <?php if ($atts['title']): ?>
                        <h3 class="title"><?php echo esc_html($atts['title']); ?></h3>
                    <?php endif; ?>
                    <?php if ($atts['desc']) : ?>
                        <p class="desc"><?php echo esc_html($atts['desc']); ?></p>
                    <?php endif; ?>
                    <?php if ($atts['form_id']): ?>
                        <div class="newsletter-form-wrap">
                            <div class="newsletter-form-inner">
                                <?php echo do_shortcode($atts['form_id']); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        }
    }
}