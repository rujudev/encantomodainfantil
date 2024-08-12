<?php
if (!class_exists('Moorabi_Elementor_Title')) {
    class Moorabi_Elementor_Title extends Moorabi_Elementor
    {
        public $name = 'title';
        public $title = 'Title';
        public $icon = 'moorabi-elementor-icon eicon-t-letter';

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
                        'label' => '<img alt="'.esc_attr($this->name).'" src="' . esc_url(get_theme_file_uri('/assets/images/elementor/' . $this->name . '/' . $key . '.jpg')) . '"/>',
                        'type' => \Elementor\Controls_Manager::HEADING,
                        'condition' => array(
                            'style' => $key
                        ),
                    ]
                );
            }
            $this->add_control(
                'type', [
                    'label' => esc_html__('Icon library', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'media' => esc_html__('Media', 'moorabi'),
                        'icon' => esc_html__('Icon', 'moorabi'),
                    ],
                    'default' => 'media',
                    'label_block' => true
                ]
            );
            $this->add_control(
                'media', [
                    'label' => esc_html__('Media', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::ICONS,
                    'fa4compatibility' => 'icon',
                    'default' => [
                        'value' => 'fas fa-star',
                        'library' => 'fa-solid',
                    ],
                    'condition' => array(
                        'type' => array('media')
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'icon', [
                    'label' => esc_html__('Icon', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::ICON,
                    'options' => Moorabi_Elementor::moorabi_elementor_icon(),
                    'default' => 'fa fa-star',
                    'condition' => array(
                        'type' => array('icon')
                    ),
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
                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                    'placeholder' => esc_html__('Enter your description', 'moorabi'),
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
            $css_class = array('moorabi-title');
            $css_class[] = $atts['style'];
            ?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                <?php if ($atts['type'] == 'icon' && $atts['icon']): ?>
                    <div class="icon">
                        <span class="<?php echo esc_attr($atts['icon']) ?>"></span>
                    </div>
                <?php elseif ($atts['type'] == 'media' && $atts['media']['value']): ?>
                    <div class="icon">
                        <?php Elementor\Icons_Manager::render_icon($atts['media'], ['aria-hidden' => 'true']); ?>
                    </div>
                <?php endif; ?>
                <?php if ($atts['title']): ?>
                    <h3 class="title"><span><?php echo esc_html($atts['title']); ?></span></h3>
                <?php endif; ?>
                <?php if ($atts['desc']): ?>
                    <div class="desc"><?php echo wp_specialchars_decode($atts['desc']); ?></div>
                <?php endif; ?>
            </div>
            <?php
        }
    }
}