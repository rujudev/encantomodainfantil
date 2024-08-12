<?php
if (!class_exists('Moorabi_Elementor_Pinmapper')) {
    class Moorabi_Elementor_Pinmapper extends Moorabi_Elementor
    {
        public $name = 'pinmapper';
        public $title = 'Pinmapper';
        public $icon = 'moorabi-elementor-icon eicon-map-pin';

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
            //General Section
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
                    'options' => Moorabi_Elementor::moorabi_elementor_pinmmaper(),
                    'default' => Moorabi_Elementor::moorabi_elementor_pinmmaper('default'),
                    'label_block' => true
                ]
            );
            foreach (Moorabi_Elementor::moorabi_elementor_pinmmaper('previews') as $key => $value) {
                $this->add_control(
                    $this->name.$key,
                    [
                        'label' => '<img alt="'.esc_attr($this->name).'" src="' . esc_url($value) . '"/>',
                        'type' => \Elementor\Controls_Manager::HEADING,
                        'condition' => array(
                            'style' => $key
                        ),
                    ]
                );
            }
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
            $css_class = array('moorabi-pinmapper');
            ?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                <?php echo do_shortcode('[moorabi_pinmap id="' . $atts['style'] . '"]'); ?>
            </div>
            <?php wp_reset_postdata();
        }
    }
}