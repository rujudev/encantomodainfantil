<?php
if (!class_exists('Moorabi_Elementor_Videopopup')) {
    class Moorabi_Elementor_Videopopup extends Moorabi_Elementor
    {
        public $name = 'videopopup';
        public $title = 'Video Popup';
        public $icon = 'moorabi-elementor-icon eicon-video-playlist';

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
                'image', [
                    'label' => esc_html__('Image', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::MEDIA,
                    'default' => [
                        'url' => \Elementor\Utils::get_placeholder_image_src(),
                    ],
                    'label_block' => true
                ]
            );
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
                    'type' => \Elementor\Controls_Manager::WYSIWYG,
                    'placeholder' => esc_html__('Enter your description', 'moorabi'),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'text', [
                    'label' => esc_html__('Video Text', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Enter your text', 'moorabi'),
                    'default' => esc_html__('Watch Video', 'moorabi'),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'link', [
                    'label' => esc_html__('Video Link', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('https://your-link.com', 'moorabi'),
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
            $css_class = array('moorabi-videopopup');
            $css_class[] = $atts['style'];
            ?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                <div class="videopopup-inner">
                    <?php if ($atts['image']): ?>
                        <figure class="videopopup-thumb">
                            <?php echo wp_get_attachment_image($atts['image']['id'], 'full'); ?>
                        </figure>
                    <?php endif; ?>
                    <div class="videopopup-info">
                        <?php if ($atts['title']): ?>
                            <h3 class="title">
                                <?php echo esc_html($atts['title']); ?>
                            </h3>
                        <?php endif; ?>
                        <?php if ($atts['desc']): ?>
                            <div class="desc"><?php echo wp_specialchars_decode($atts['desc']); ?></div>
                        <?php endif; ?>
                        <?php if ($atts['text'] && $atts['link']):?>
                            <div class="product-video-button">
                                <a href="<?php echo esc_url($atts['link']); ?>"><span></span><?php echo esc_html($atts['text']); ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}