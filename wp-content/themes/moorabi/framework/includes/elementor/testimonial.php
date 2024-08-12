<?php
if (!class_exists('Moorabi_Elementor_Testimonial')) {
    class Moorabi_Elementor_Testimonial extends Moorabi_Elementor
    {
        public $name = 'testimonial';
        public $title = 'Testimonial';
        public $icon = 'moorabi-elementor-icon eicon-testimonial-carousel';

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
            $repeater = new \Elementor\Repeater();
            $repeater->add_control(
                'avatar', [
                    'label' => esc_html__('Avatar', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::MEDIA,
                    'default' => [
                        'url' => \Elementor\Utils::get_placeholder_image_src(),
                    ],
                    'label_block' => true
                ]
            );
            $repeater->add_control(
                'name', [
                    'label' => esc_html__('Name', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Enter your name', 'moorabi'),
                    'label_block' => true
                ]
            );
            $repeater->add_control(
                'position', [
                    'label' => esc_html__('Position', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Enter your position', 'moorabi'),
                    'label_block' => true
                ]
            );
            $repeater->add_control(
                'desc', [
                    'label' => esc_html__('Description', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                    'placeholder' => esc_html__('Enter your description', 'moorabi'),
                    'label_block' => true
                ]
            );
            $repeater->add_control(
                'color', [
                    'label' => esc_html__('Color', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#71c0ef',
                    'selectors' => [
                        '{{WRAPPER}} .moorabi-testimonial.style-02 {{CURRENT_ITEM}} .desc' => 'background-color: {{VALUE}}',
                        '{{WRAPPER}} .moorabi-testimonial.style-02 {{CURRENT_ITEM}} .testimonial-info::before' => 'border-color: {{VALUE}} transparent',
                        '{{WRAPPER}} .moorabi-testimonial.style-01 {{CURRENT_ITEM}} .position' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_control(
                'list',
                [
                    'label' => __('Testimonial List', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'title_field' => '{{{ name }}}',
                ]
            );
            $this->end_controls_section();
            //Layout Section
            $this->start_controls_section(
                'layout_section',
                [
                    'label' => esc_html__('Layout', 'moorabi'),
                ]
            );
            $this->add_control(
                'layout',
                [
                    'label' => esc_html__('Layout', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'carousel' => esc_html__('Carousel', 'moorabi'),
                    ],
                    'default' => 'carousel',
                    'label_block' => true
                ]
            );
            $this->add_control(
                'carousel',
                [
                    'label' => '<img alt="'.esc_attr($this->name).'" src="' . esc_url(get_theme_file_uri('/assets/images/elementor/layout/carousel.png')) . '"/>',
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'condition' => array(
                        'layout' => 'carousel'
                    ),
                ]
            );
            $this->add_control(
                'owl_number_row',
                [
                    'label' => esc_html__('The Number Of Rows', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        '1' => esc_html__('1 Row', 'moorabi'),
                        '2' => esc_html__('2 Rows', 'moorabi'),
                        '3' => esc_html__('3 Rows', 'moorabi'),
                        '4' => esc_html__('4 Rows', 'moorabi'),
                        '5' => esc_html__('5 Rows', 'moorabi'),
                        '6' => esc_html__('6 Rows', 'moorabi'),
                    ],
                    'default' => '1',
                    'condition' => array(
                        'layout' => 'carousel'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'owl_rows_space',
                [
                    'label' => esc_html__('Rows Space', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'rows-space-0' => esc_html__('Default', 'moorabi'),
                        'rows-space-10' => esc_html__('10px', 'moorabi'),
                        'rows-space-20' => esc_html__('20px', 'moorabi'),
                        'rows-space-30' => esc_html__('30px', 'moorabi'),
                        'rows-space-40' => esc_html__('40px', 'moorabi'),
                        'rows-space-50' => esc_html__('50px', 'moorabi'),
                        'rows-space-60' => esc_html__('60px', 'moorabi'),
                        'rows-space-70' => esc_html__('70px', 'moorabi'),
                        'rows-space-80' => esc_html__('80px', 'moorabi'),
                        'rows-space-90' => esc_html__('90px', 'moorabi'),
                        'rows-space-100' => esc_html__('100px', 'moorabi'),
                    ],
                    'default' => 'rows-space-0',
                    'condition' => array(
                        'layout' => 'carousel'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'owl_vertical',
                [
                    'label' => esc_html__('Vertical Slide Mode', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'true' => esc_html__('Yes', 'moorabi'),
                        'false' => esc_html__('No', 'moorabi'),
                    ],
                    'default' => 'false',
                    'condition' => array(
                        'layout' => 'carousel'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'owl_verticalswiping',
                [
                    'label' => esc_html__('Vertical Swipe Mode', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'true' => esc_html__('Yes', 'moorabi'),
                        'false' => esc_html__('No', 'moorabi'),
                    ],
                    'default' => 'false',
                    'condition' => array(
                        'layout' => 'carousel',
                        'owl_vertical' => 'true'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'owl_autoplay',
                [
                    'label' => esc_html__('AutoPlay', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'true' => esc_html__('Yes', 'moorabi'),
                        'false' => esc_html__('No', 'moorabi'),
                    ],
                    'default' => 'false',
                    'description' => esc_html__('Enables Autoplay', 'moorabi'),
                    'condition' => array(
                        'layout' => 'carousel'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'owl_autoplayspeed',
                [
                    'label' => esc_html__('Autoplay Speed', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1000,
                    'default' => 3000,
                    'description' => esc_html__('Autoplay Speed in milliseconds', 'moorabi'),
                    'condition' => array(
                        'layout' => 'carousel',
                        'owl_autoplay' => 'true'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'owl_navigation',
                [
                    'label' => esc_html__('Navigation', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'true' => esc_html__('Yes', 'moorabi'),
                        'false' => esc_html__('No', 'moorabi'),
                    ],
                    'default' => 'false',
                    'description' => esc_html__('Prev/Next Arrows', 'moorabi'),
                    'condition' => array(
                        'layout' => 'carousel'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'owl_dots',
                [
                    'label' => esc_html__('Dots', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'true' => esc_html__('Yes', 'moorabi'),
                        'false' => esc_html__('No', 'moorabi'),
                    ],
                    'default' => 'true',
                    'description' => esc_html__('Show dot indicators', 'moorabi'),
                    'condition' => array(
                        'layout' => 'carousel'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'owl_loop',
                [
                    'label' => esc_html__('Loop', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'true' => esc_html__('Yes', 'moorabi'),
                        'false' => esc_html__('No', 'moorabi'),
                    ],
                    'default' => 'false',
                    'description' => esc_html__('Infinite loop sliding', 'moorabi'),
                    'condition' => array(
                        'layout' => 'carousel'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'owl_slidespeed',
                [
                    'label' => esc_html__('Slide Speed', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 300,
                    'default' => 300,
                    'description' => esc_html__('Slide Speed in milliseconds', 'moorabi'),
                    'condition' => array(
                        'layout' => 'carousel'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'owl_ls_items',
                [
                    'label' => esc_html__('Items per row on Wide Screens', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 1,
                    'description' => esc_html__('Items per row on screen resolution of device >= 1500px', 'moorabi'),
                    'condition' => array(
                        'layout' => 'carousel'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'owl_slide_margin',
                [
                    'label' => esc_html__('Margin on Wide Screens', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 0,
                    'default' => 30,
                    'description' => esc_html__('Distance( or space) between 2 items on screen resolution of device >= 1500px', 'moorabi'),
                    'condition' => array(
                        'layout' => 'carousel',
                        'owl_vertical' => 'false'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'owl_lg_items',
                [
                    'label' => esc_html__('Items per row on Desktop', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 1,
                    'description' => esc_html__('Items per row on screen resolution of device >= 1200px and < 1500px', 'moorabi'),
                    'condition' => array(
                        'layout' => 'carousel'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'owl_lg_slide_margin',
                [
                    'label' => esc_html__('Margin on Desktop', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 0,
                    'default' => 30,
                    'description' => esc_html__('Distance( or space) between 2 items on screen resolution of device >= 1200px and < 1500px', 'moorabi'),
                    'condition' => array(
                        'layout' => 'carousel',
                        'owl_vertical' => 'false'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'owl_md_items',
                [
                    'label' => esc_html__('Items per row on Landscape Tablet', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 1,
                    'description' => esc_html__('Items per row on screen resolution of device >=992px and < 1200px', 'moorabi'),
                    'condition' => array(
                        'layout' => 'carousel'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'owl_md_slide_margin',
                [
                    'label' => esc_html__('Margin on Landscape Tablet', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 0,
                    'default' => 20,
                    'description' => esc_html__('Distance( or space) between 2 items on screen resolution of device >=992px and < 1200px', 'moorabi'),
                    'condition' => array(
                        'layout' => 'carousel',
                        'owl_vertical' => 'false'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'owl_sm_items',
                [
                    'label' => esc_html__('Items per row on Tablet', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 1,
                    'description' => esc_html__('Items per row on screen resolution of device >=768px and < 992px', 'moorabi'),
                    'condition' => array(
                        'layout' => 'carousel'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'owl_sm_slide_margin',
                [
                    'label' => esc_html__('Margin on Tablet', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 0,
                    'default' => 20,
                    'description' => esc_html__('Distance( or space) between 2 items on screen resolution of device >=768px and < 992px', 'moorabi'),
                    'condition' => array(
                        'layout' => 'carousel',
                        'owl_vertical' => 'false'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'owl_xs_items',
                [
                    'label' => esc_html__('Items per row on Mobile Landscape', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 1,
                    'description' => esc_html__('Items per row on screen resolution of device >=480px and < 768px', 'moorabi'),
                    'condition' => array(
                        'layout' => 'carousel'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'owl_xs_slide_margin',
                [
                    'label' => esc_html__('Margin on Mobile Landscape', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 0,
                    'default' => 20,
                    'description' => esc_html__('Distance( or space) between 2 items on screen resolution of device >=480px and < 768px', 'moorabi'),
                    'condition' => array(
                        'layout' => 'carousel',
                        'owl_vertical' => 'false'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'owl_ts_items',
                [
                    'label' => esc_html__('Items per row on Mobile', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 1,
                    'description' => esc_html__('Items per row on screen resolution of device < 480px', 'moorabi'),
                    'condition' => array(
                        'layout' => 'carousel'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'owl_ts_slide_margin',
                [
                    'label' => esc_html__('Margin on Mobile', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 0,
                    'default' => 10,
                    'description' => esc_html__('Distance( or space) between 2 items on screen resolution of device < 480px', 'moorabi'),
                    'condition' => array(
                        'layout' => 'carousel',
                        'owl_vertical' => 'false'
                    ),
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
            $css_class = array('moorabi-testimonial');
            $css_class[] = $atts['style'];
            $owl_settings = '';
            $list_class = array('testimonial-list-owl owl-slick equal-container better-height');
            $item_class = array();
            $item_class[] = $atts['owl_rows_space'];
            $owl_settings .= apply_filters('moorabi_carousel_data_attributes', 'owl_', $atts);
            ?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                <?php if ($atts['list']) { ?>
                    <div class="<?php echo esc_attr(implode(' ', $list_class)); ?>"
                        <?php echo esc_attr($owl_settings); ?>>
                        <?php foreach ($atts['list'] as $list): ?>
                            <div class="<?php echo esc_attr(implode(' ', $item_class)); ?>">
                                <div class="testimonial-inner <?php echo 'elementor-repeater-item-'.esc_attr( $list['_id']); ?>">
                                    <?php if($atts['style'] == 'style-02'):?>
                                        <div class="testimonial-info">
                                            <?php if ($list['desc']) : ?>
                                                <div class="desc"><?php echo wp_specialchars_decode($list['desc']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($list['avatar']) : ?>
                                            <div class="thumb-avatar">
                                                <?php echo wp_get_attachment_image($list['avatar']['id'], 'full'); ?>
                                                <div class="thumb-info">
                                                    <?php if ($list['name']) : ?>
                                                        <h3 class="name"><?php echo esc_html($list['name']); ?></h3>
                                                    <?php endif; ?>
                                                    <?php if ($list['position']): ?>
                                                        <div class="position"><?php echo esc_html($list['position']); ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if ($list['avatar']) : ?>
                                            <div class="thumb-avatar">
                                                <?php echo wp_get_attachment_image($list['avatar']['id'], 'full'); ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="testimonial-info">
                                            <?php if ($list['desc']) : ?>
                                                <div class="desc"><?php echo wp_specialchars_decode($list['desc']); ?></div>
                                            <?php endif; ?>
                                            <?php if ($list['name']) : ?>
                                                <h3 class="name"><?php echo esc_html($list['name']); ?></h3>
                                            <?php endif; ?>
                                            <?php if ($list['position']): ?>
                                                <div class="position"><?php echo esc_html($list['position']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php } ?>
            </div>
            <?php
        }
    }
}