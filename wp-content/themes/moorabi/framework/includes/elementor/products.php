<?php
if (!class_exists('Moorabi_Elementor_Products')) {
    class Moorabi_Elementor_Products extends Moorabi_Elementor
    {
        public $name = 'products';
        public $title = 'Products';
        public $icon = 'moorabi-elementor-icon eicon-woocommerce';

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
                'product_image_size',
                [
                    'label' => esc_html__('Image size', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => Moorabi_Elementor::moorabi_elementor_size(),
                    'description' => esc_html__('Select a size for product', 'moorabi'),
                    'label_block' => true

                ]
            );
            $this->add_control(
                'product_custom_thumb_width',
                [
                    'label' => esc_html__('Width', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'default' => 300,
                    'description' => esc_html__( 'Unit px', 'moorabi' ),
                    'condition' => array(
                        'product_image_size' => 'custom'
                    ),
                ]
            );
            $this->add_control(
                'product_custom_thumb_height',
                [
                    'label' => esc_html__('Height', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'default' => 300,
                    'description' => esc_html__( 'Unit px', 'moorabi' ),
                    'condition' => array(
                        'product_image_size' => 'custom'
                    ),
                ]
            );
            $this->add_control(
                'target',
                [
                    'label' => esc_html__('Target', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'recent-product' => esc_html__('Recent Products', 'moorabi'),
                        'best-selling' => esc_html__('Best Selling Products', 'moorabi'),
                        'top-rated' => esc_html__('Top Rated Products', 'moorabi'),
                        'product-category' => esc_html__('Product Category', 'moorabi'),
                        'featured_products' => esc_html__('Featured Products', 'moorabi'),
                        'on_sale' => esc_html__('On Sale', 'moorabi'),
                        'on_new' => esc_html__('On New', 'moorabi'),
                        'products' => esc_html__('Products', 'moorabi'),
                    ],
                    'default' => 'recent-product',
                    'description' => esc_html__('Choose the target to filter products', 'moorabi'),
                    'label_block' => true

                ]
            );
            $this->add_control(
                'taxonomy', [
                    'label' => esc_html__('Product Category', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => Moorabi_Elementor::moorabi_elementor_taxonomy(),
                    'description' => esc_html__('Note: If you want to narrow output, select category(s) above. Only selected categories will be displayed.', 'moorabi'),
                    'condition' => array(
                        'target' => array('recent-product', 'top-rated', 'product-category', 'featured_products', 'on_sale', 'on_new', 'product_attribute')
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'orderby',
                [
                    'label' => esc_html__('Order by', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'date' => esc_html__('Date', 'moorabi'),
                        'ID' => esc_html__('ID', 'moorabi'),
                        'author' => esc_html__('Author', 'moorabi'),
                        'title' => esc_html__('Title', 'moorabi'),
                        'modified' => esc_html__('Modified', 'moorabi'),
                        'rand' => esc_html__('Random', 'moorabi'),
                        'comment_count' => esc_html__('Comment count', 'moorabi'),
                        'menu_order' => esc_html__('Menu order', 'moorabi'),
                        '_sale_price' => esc_html__('Sale price', 'moorabi'),
                    ],
                    'default' => 'date',
                    'description' => esc_html__('Select how to sort', 'moorabi'),
                    'condition' => array(
                        'target' => array('recent-product', 'top-rated', 'product-category', 'featured_products', 'on_sale', 'on_new', 'product_attribute')
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
                        'target' => array('recent-product', 'top-rated', 'product-category', 'featured_products', 'on_sale', 'on_new', 'product_attribute')
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'per_page',
                [
                    'label' => esc_html__('Product per page', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 6,
                    'condition' => array(
                        'target' => array('recent-product', 'best-selling', 'top-rated', 'product-category', 'featured_products', 'on_sale', 'on_new', 'product_attribute')
                    ),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'ids',
                [
                    'label' => esc_html__('Product IDs', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Enter IDs', 'moorabi'),
                    'description' => esc_html__('Ex: 1,2,3,...', 'moorabi'),
                    'condition' => array(
                        'target' => 'products'
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
                    'default' => 'true',
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
                    'default' => 'false',
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
                    'default' => 4,
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
                    'default' => 4,
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
                    'default' => 2,
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
                    'default' => '3',
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
                    'default' => '3',
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
                    'label' => esc_html__('Items per row on landscape Tablet', 'moorabi'),
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
            $css_class = array('moorabi-products');
            $css_class[] = $atts['style'];
            if ($atts['product_image_size']) {
                if ($atts['product_image_size'] == 'custom') {
                    $thumb_width = $atts['product_custom_thumb_width'];
                    $thumb_height = $atts['product_custom_thumb_height'];
                } else {
                    $product_image_size = explode("x", $atts['product_image_size']);
                    $thumb_width = $product_image_size[0];
                    $thumb_height = $product_image_size[1];
                }
                if ($thumb_width > 0) {
                    add_filter('moorabi_shop_product_thumb_width', function () use ($thumb_width) {
                        return $thumb_width;
                    });
                }
                if ($thumb_height > 0) {
                    add_filter('moorabi_shop_product_thumb_height', function () use ($thumb_height) {
                        return $thumb_height;
                    });
                }
            }
            $products = apply_filters('moorabi_get_products', $atts);
            $total_product = $products->post_count;
            $list_class = array('response-product equal-container better-height');
            $item_class = array('product-item');
            $item_class[] = $atts['target'];
            $item_class[] = $atts['style'];
            $owl_settings = '';
            if ($atts['layout'] == 'grid') {
                $list_class[] = 'product-list-grid row auto-clear';
                $item_class[] = $atts['boostrap_rows_space'];
                $item_class[] = 'col-bg-' . $atts['boostrap_bg_items'];
                $item_class[] = 'col-lg-' . $atts['boostrap_lg_items'];
                $item_class[] = 'col-md-' . $atts['boostrap_md_items'];
                $item_class[] = 'col-sm-' . $atts['boostrap_sm_items'];
                $item_class[] = 'col-xs-' . $atts['boostrap_xs_items'];
                $item_class[] = 'col-ts-' . $atts['boostrap_ts_items'];
            }
            if ($atts['layout'] == 'carousel') {
                if ($total_product < $atts['owl_lg_items']) {
                    $atts['owl_loop'] = 'false';
                }
                $list_class[] = 'product-list-owl owl-slick';
                $item_class[] = $atts['owl_rows_space'];
                $owl_settings = apply_filters('moorabi_carousel_data_attributes', 'owl_', $atts);
            }
            $id_loop = array();
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
                <?php if ($products->have_posts()): ?>
                    <div class="<?php echo esc_attr(implode(' ', $list_class)); ?>" <?php echo esc_attr($owl_settings); ?>>
                        <?php while ($products->have_posts()) : $products->the_post(); ?>
                            <?php $id_loop[] = get_the_ID(); ?>
                            <div <?php post_class($item_class); ?>>
                                <?php wc_get_template_part('product-styles/content-product', $atts['style']); ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p>
                        <strong><?php esc_html_e('No Product', 'moorabi'); ?></strong>
                    </p>
                <?php endif; ?>
            </div>
            <?php wp_reset_postdata();
        }
    }
}