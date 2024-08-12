<?php
if (!class_exists('Moorabi_Elementor_Socials')) {
    class Moorabi_Elementor_Socials extends Moorabi_Elementor
    {
        public $name = 'socials';
        public $title = 'Socials';
        public $icon = 'moorabi-elementor-icon eicon-social-icons';

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
            };
            $this->add_responsive_control(
                'align',
                [
                    'label' => esc_html__( 'Alignment', 'moorabi' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => esc_html__( 'Left', 'moorabi' ),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'moorabi' ),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => esc_html__( 'Right', 'moorabi' ),
                            'icon' => 'eicon-text-align-right',
                        ],
                        'justify' => [
                            'title' => esc_html__( 'Justified', 'moorabi' ),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                    ],
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
                'list', [
                    'label' => esc_html__('Social List', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => Moorabi_Elementor::moorabi_elementor_social(),
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
            $css_class = array('moorabi-socials');
            $css_class[] = $atts['style'];
            $all_socials = Moorabi_Functions::moorabi_get_option('user_all_social');
            ?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                <?php if ($atts['title']): ?>
                    <h3 class="title"><?php echo esc_html($atts['title']); ?></h3>
                <?php endif; ?>
                <?php if (!empty($all_socials)) : ?>
                    <ul class="socials-list">
                        <?php foreach ($atts['list'] as $value) : ?>
                            <?php if (isset($all_socials[$value])) :
                                $array_socials = $all_socials[$value]; ?>
                                <li>
                                    <a href="<?php echo esc_url($array_socials['link_social']) ?>">
                                        <i class="<?php echo esc_attr($array_socials['icon_social']); ?>"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <?php
        }
    }
}