<?php
if (!class_exists('Moorabi_Elementor_Banner')) {
    class Moorabi_Elementor_Banner extends Moorabi_Elementor
    {
        public $name = 'banner';
        public $title = 'Banner';
        public $icon = 'moorabi-elementor-icon eicon-banner';

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
                'subtitle', [
                    'label' => esc_html__('Subtitle', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Enter your subtitle', 'moorabi'),
                    'condition' => array(
                        'style' => array('style-03', 'style-06', 'style-07', 'style-11', 'style-12', 'style-13', 'style-14'),
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'title', [
                    'label' => esc_html__('Title', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Enter your title', 'moorabi'),
                    'rows' => 1,
                    'label_block' => true
                ]
            );
            $this->add_control(
                'desc', [
                    'label' => esc_html__('Description', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::WYSIWYG,
                    'placeholder' => esc_html__('Enter your description', 'moorabi'),
                    'condition' => array(
                        'style' => array('style-01', 'style-02', 'style-03', 'style-04', 'style-05', 'style-06', 'style-07', 'style-08', 'style-09', 'style-10', 'style-11', 'style-13', 'style-14'),
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'text', [
                    'label' => esc_html__('Button Text', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Enter your button text', 'moorabi'),
                    'condition' => array(
                        'style' => array('style-01', 'style-03', 'style-04', 'style-05', 'style-06', 'style-07', 'style-08', 'style-09', 'style-10', 'style-12', 'style-14'),
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'link', [
                    'label' => esc_html__('Button Link', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::URL,
                    'placeholder' => esc_html__('https://your-link.com', 'moorabi'),
                    'show_external' => true,
                    'default' => [
                        'url' => '',
                        'is_external' => true,
                        'nofollow' => false,
                    ],
                    'label_block' => true
                ]
            );
            $this->add_control(
                'text1', [
                    'label' => esc_html__('Button Text More', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Enter your button text', 'moorabi'),
                    'condition' => array(
                        'style' => array('style-05'),
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'link1', [
                    'label' => esc_html__('Button Link More', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::URL,
                    'placeholder' => esc_html__('https://your-link.com', 'moorabi'),
                    'show_external' => true,
                    'default' => [
                        'url' => '',
                        'is_external' => true,
                        'nofollow' => false,
                    ],
                    'condition' => array(
                        'style' => array('style-05'),
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'color', [
                    'label' => esc_html__('Background Color', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#71c0ef',
                    'selectors' => [
                        '{{WRAPPER}} .moorabi-banner.style-15 .title' => 'background-color: {{VALUE}}',
                    ],
                    'condition' => array(
                        'style' => array('style-15'),
                    ),
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
            $css_class = array('moorabi-banner');
            $css_class[] = $atts['style'];
            $container = array('banner-info');
            $container[] = 'clearfix';
            if (in_array($atts['style'], array('style-07', 'style-12'))) {
                $container[] = 'container';
            }
            $target = !empty($atts['link']['is_external']) ? '_blank' : '_self';
            $target1 = !empty($atts['link1']['is_external']) ? '_blank' : '_self';
            ?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                <div class="banner-inner">
                    <?php if ($atts['image']): ?>
                        <figure class="banner-thumb">
                            <?php echo wp_get_attachment_image($atts['image']['id'], 'full'); ?>
                        </figure>
                    <?php endif; ?>
                    <div class="<?php echo esc_attr(implode(' ', $container)); ?>">
                        <div class="banner-content">
                            <?php if ($atts['subtitle'] && in_array($atts['style'], array('style-03', 'style-06', 'style-07', 'style-11', 'style-12', 'style-13', 'style-14'))): ?>
                                <div class="subtitle"><?php echo esc_html($atts['subtitle']); ?></div>
                            <?php endif; ?>
                            <?php if ($atts['title']): ?>
                                <h3 class="title">
                                    <?php if (in_array($atts['style'], array('style-02', 'style-11', 'style-13', 'style-15')) && $atts['link']['url']) { ?>
                                        <a href="<?php echo esc_url($atts['link']['url']); ?>"
                                           target="<?php echo esc_attr($target); ?>"><?php echo esc_html($atts['title']); ?></a>
                                    <?php } else {
                                        echo esc_html($atts['title']);
                                    } ?>
                                </h3>
                            <?php endif; ?>
                            <?php if ($atts['desc'] && in_array($atts['style'], array('style-01', 'style-02', 'style-03', 'style-04', 'style-05', 'style-06', 'style-07', 'style-08', 'style-09', 'style-10', 'style-11', 'style-13', 'style-14'))): ?>
                                <div class="desc"><?php echo wp_specialchars_decode($atts['desc']); ?></div>
                            <?php endif; ?>
                            <?php if ($atts['text'] && $atts['link']['url'] &&  in_array($atts['style'], array('style-01', 'style-03', 'style-04', 'style-05', 'style-06', 'style-07', 'style-08', 'style-09', 'style-10', 'style-12', 'style-14'))): ?>
                                <a class="button" href="<?php echo esc_url($atts['link']['url']); ?>"
                                   target="<?php echo esc_attr($target); ?>"><?php echo esc_html($atts['text']); ?></a>
                            <?php endif; ?>
                            <?php if ($atts['text1'] && $atts['link1']['url'] &&  in_array($atts['style'], array('style-05'))): ?>
                                <a class="button" href="<?php echo esc_url($atts['link1']['url']); ?>"
                                   target="<?php echo esc_attr($target1); ?>"><?php echo esc_html($atts['text1']); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}