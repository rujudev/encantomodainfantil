<?php
if (!class_exists('Moorabi_Elementor_Blog')) {
    class Moorabi_Elementor_Blog extends Moorabi_Elementor
    {
        public $name = 'blog';
        public $title = 'Blog';
        public $icon = 'moorabi-elementor-icon eicon-post-content';

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
                'target',
                [
                    'label' => esc_html__('Target', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'category' => esc_html__('Category', 'moorabi'),
                        'posts' => esc_html__('Post(s)', 'moorabi'),
                    ],
                    'default' => 'category',
                    'label_block' => true

                ]
            );
            $this->add_control(
                'post_ids',
                [
                    'label' => esc_html__('Post IDs', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Enter IDs', 'moorabi'),
                    'description' => esc_html__('Ex: 1,2,3,...', 'moorabi'),
                    'condition' => array(
                        'target' => 'posts'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'category_slug',
                [
                    'label' => esc_html__('Categorys', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => Moorabi_Elementor::moorabi_elementor_category(),
                    'default' => '',
                    'condition' => array(
                        'target' => 'category'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'per_page',
                [
                    'label' => esc_html__('Number Post', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                    'default' => 5,
                    'condition' => array(
                        'target' => 'category'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'orderby',
                [
                    'label' => esc_html__('Orderby', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'none' => esc_html__('None', 'moorabi'),
                        'ID' => esc_html__('ID', 'moorabi'),
                        'author' => esc_html__('Author', 'moorabi'),
                        'name' => esc_html__('Name', 'moorabi'),
                        'date' => esc_html__('Date', 'moorabi'),
                        'modified' => esc_html__('Modified', 'moorabi'),
                        'rand' => esc_html__('Random', 'moorabi'),
                    ],
                    'default' => 'date',
                    'condition' => array(
                        'target' => 'category'
                    ),
                    'label_block' => true

                ]
            );
            $this->add_control(
                'order',
                [
                    'label' => esc_html__('Order', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'ASC' => esc_html__('Ascending', 'moorabi'),
                        'DESC' => esc_html__('Descending', 'moorabi'),
                    ],
                    'default' => 'DESC',
                    'condition' => array(
                        'target' => 'category'
                    ),
                    'label_block' => true
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
                        'grid' => esc_html__('Grid', 'moorabi'),
                    ],
                    'default' => 'carousel',
                    'label_block' => true
                ]
            );
            $this->add_control(
                'carousel',
                [
                    'label' => '<img alt="' . esc_attr($this->name) . '" src="' . esc_url(get_theme_file_uri('/assets/images/elementor/layout/carousel.png')) . '"/>',
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'condition' => array(
                        'layout' => 'carousel'
                    ),
                ]
            );
            $this->add_control(
                'grid',
                [
                    'label' => '<img alt="' . esc_attr($this->name) . '" src="' . esc_url(get_theme_file_uri('/assets/images/elementor/layout/grid.png')) . '"/>',
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'condition' => array(
                        'layout' => 'grid'
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
                    'default' => 3,
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
                    'default' => 3,
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
                    'default' => 3,
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
                    'default' => 2,
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
                    'default' => 2,
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
            $this->add_control(
                'boostrap_rows_space',
                [
                    'label' => esc_html__('Rows space', 'moorabi'),
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
                        'layout' => 'grid'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'boostrap_bg_items',
                [
                    'label' => esc_html__('Items per row on Wide Screens', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        '12' => esc_html__('1 item', 'moorabi'),
                        '6' => esc_html__('2 items', 'moorabi'),
                        '4' => esc_html__('3 items', 'moorabi'),
                        '3' => esc_html__('4 items', 'moorabi'),
                        '15' => esc_html__('5 items', 'moorabi'),
                        '2' => esc_html__('6 items', 'moorabi'),
                    ],
                    'default' => '4',
                    'description' => esc_html__('Items per row on screen resolution of device >= 1500px', 'moorabi'),
                    'condition' => array(
                        'layout' => 'grid'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'boostrap_lg_items',
                [
                    'label' => esc_html__('Items per row on Desktop', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        '12' => esc_html__('1 item', 'moorabi'),
                        '6' => esc_html__('2 items', 'moorabi'),
                        '4' => esc_html__('3 items', 'moorabi'),
                        '3' => esc_html__('4 items', 'moorabi'),
                        '15' => esc_html__('5 items', 'moorabi'),
                        '2' => esc_html__('6 items', 'moorabi'),
                    ],
                    'default' => '4',
                    'description' => esc_html__('Items per row on screen resolution of device >= 1200px and < 1500px', 'moorabi'),
                    'condition' => array(
                        'layout' => 'grid'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'boostrap_md_items',
                [
                    'label' => esc_html__('Items per row on landscape tablet', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        '12' => esc_html__('1 item', 'moorabi'),
                        '6' => esc_html__('2 items', 'moorabi'),
                        '4' => esc_html__('3 items', 'moorabi'),
                        '3' => esc_html__('4 items', 'moorabi'),
                        '15' => esc_html__('5 items', 'moorabi'),
                        '2' => esc_html__('6 items', 'moorabi'),
                    ],
                    'default' => '4',
                    'description' => esc_html__('Items per row on screen resolution of device >=992px and < 1200px', 'moorabi'),
                    'condition' => array(
                        'layout' => 'grid'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'boostrap_sm_items',
                [
                    'label' => esc_html__('Items per row on portrait tablet', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        '12' => esc_html__('1 item', 'moorabi'),
                        '6' => esc_html__('2 items', 'moorabi'),
                        '4' => esc_html__('3 items', 'moorabi'),
                        '3' => esc_html__('4 items', 'moorabi'),
                        '15' => esc_html__('5 items', 'moorabi'),
                        '2' => esc_html__('6 items', 'moorabi'),
                    ],
                    'default' => '6',
                    'description' => esc_html__('Items per row on screen resolution of device >=768px and < 992px', 'moorabi'),
                    'condition' => array(
                        'layout' => 'grid'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'boostrap_xs_items',
                [
                    'label' => esc_html__('Items per row on Mobile', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        '12' => esc_html__('1 item', 'moorabi'),
                        '6' => esc_html__('2 items', 'moorabi'),
                        '4' => esc_html__('3 items', 'moorabi'),
                        '3' => esc_html__('4 items', 'moorabi'),
                        '15' => esc_html__('5 items', 'moorabi'),
                        '2' => esc_html__('6 items', 'moorabi'),
                    ],
                    'default' => '6',
                    'description' => esc_html__('Items per row on screen resolution of device >=480  add < 768px', 'moorabi'),
                    'condition' => array(
                        'layout' => 'grid'
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'boostrap_ts_items',
                [
                    'label' => esc_html__('Items per row on Mobile', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        '12' => esc_html__('1 item', 'moorabi'),
                        '6' => esc_html__('2 items', 'moorabi'),
                        '4' => esc_html__('3 items', 'moorabi'),
                        '3' => esc_html__('4 items', 'moorabi'),
                        '15' => esc_html__('5 items', 'moorabi'),
                        '2' => esc_html__('6 items', 'moorabi'),
                    ],
                    'default' => '6',
                    'description' => esc_html__('Items per row on screen resolution of device < 480px', 'moorabi'),
                    'condition' => array(
                        'layout' => 'grid'
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
            $css_class = array('moorabi-blog');
            $css_class[] = $atts['style'];
            $owl_settings = '';
            $list_class = array('equal-container better-height');
            $item_class = array('post-item');
            if ($atts['layout'] == 'grid') {
                $list_class[] = 'blog-list-grid row auto-clear';
                $item_class[] = $atts['boostrap_rows_space'];
                $item_class[] = 'col-bg-' . $atts['boostrap_bg_items'];
                $item_class[] = 'col-lg-' . $atts['boostrap_lg_items'];
                $item_class[] = 'col-md-' . $atts['boostrap_md_items'];
                $item_class[] = 'col-sm-' . $atts['boostrap_sm_items'];
                $item_class[] = 'col-xs-' . $atts['boostrap_xs_items'];
                $item_class[] = 'col-ts-' . $atts['boostrap_ts_items'];
            }
            if ($atts['layout'] == 'carousel') {
                $list_class[] = 'blog-list-owl owl-slick';
                $item_class[] = $atts['owl_rows_space'];
                $owl_settings .= apply_filters('moorabi_carousel_data_attributes', 'owl_', $atts);
            }
            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'ignore_sticky_posts' => 1,
                'posts_per_page' => $atts['per_page'],
                'suppress_filter' => true,
                'orderby' => $atts['orderby'],
                'order' => $atts['order']
            );
            if ($atts['target'] == 'category') {
                /* Get category id*/
                if ($atts['category_slug']) {
                    $idObj = get_category_by_slug($atts['category_slug']);
                    if (is_object($idObj)) {
                        $args['cat'] = $idObj->term_id;
                    }
                }
            }
            if ($atts['target'] == 'posts') {
                $args['post__in'] = array_map('trim', explode(',', $atts['post_ids']));
                $args['orderby'] = 'post__in';
            }
            $posts = new WP_Query($args);
            ?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                <?php if ($atts['title'] || $atts['desc']): ?>
                    <div class="moorabi-title style-01">
                        <?php if ($atts['title']) : ?>
                            <h3 class="title">
                                <span><?php echo esc_html($atts['title']); ?></span>
                            </h3>
                        <?php endif; ?>
                        <?php if ($atts['desc']) : ?>
                            <div class="desc">
                                <?php echo wp_specialchars_decode($atts['desc']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if ($posts->have_posts()) : ?>
                    <div
                        class="<?php echo esc_attr(implode(' ', $list_class)); ?>" <?php echo esc_attr($owl_settings); ?>>
                        <?php while ($posts->have_posts()) : $posts->the_post(); ?>
                            <article <?php post_class($item_class); ?>>
                                <?php get_template_part('/templates/blog/blog-style/content-blog', $atts['style']); ?>
                            </article>
                        <?php endwhile; ?>
                    </div>
                <?php else : ?>
                    <p><?php esc_html_e('No Content', 'moorabi'); ?></p>
                <?php endif; ?>
            </div>
            <?php wp_reset_postdata();
        }
    }
}