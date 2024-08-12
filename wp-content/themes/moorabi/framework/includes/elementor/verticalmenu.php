<?php
if (!class_exists('Moorabi_Elementor_Verticalmenu')) {
    class Moorabi_Elementor_Verticalmenu extends Moorabi_Elementor
    {
        public $name = 'verticalmenu';
        public $title = 'Verticalmenu';
        public $icon = 'moorabi-elementor-icon eicon-nav-menu';

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
                'position_menu',
                [
                    'label' => esc_html__('Position absolute menu', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'yes' => esc_html__('Yes', 'moorabi'),
                        'no' => esc_html__('No', 'moorabi'),
                    ],
                    'default' => 'no',
                    'label_block' => true
                ]
            );
            $this->add_control(
                'title', [
                    'label' => esc_html__('Title', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Enter your title', 'moorabi'),
                    'default' => esc_html__('All Categories', 'moorabi'),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'menu',
                [
                    'label' => esc_html__('Menu', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => Moorabi_Elementor::moorabi_elementor_menu(),
                    'description' => esc_html__('Select menu to display.', 'moorabi'),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'button_close_text', [
                    'label' => esc_html__('Button Close Text', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Enter your button close text', 'moorabi'),
                    'default' => esc_html__('Close', 'moorabi'),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'button_all_text', [
                    'label' => esc_html__('Button All Text', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Enter your button all text', 'moorabi'),
                    'default' => esc_html__('Show All', 'moorabi'),
                    'label_block' => true
                ]
            );
            $this->add_control(
                'limit_items', [
                    'label' => esc_html__('Limit items', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                    'default' => 9,
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
            $css_class = array('moorabi-verticalmenu block-nav-category');
            $css_class[] = $atts['style'];
            if ($atts['position_menu'] == 'yes') {
                $css_class[] = 'absolute-menu';
            }
            $nav_menu = get_term_by('slug', $atts['menu'], 'nav_menu');
            $button_close_text = $atts['button_close_text'];
            $button_all_text = $atts['button_all_text'];
            $limit_items = absint($atts['limit_items']);
            ?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>"
                 data-items="<?php echo esc_attr($limit_items); ?>">
                <?php if ($atts['title']): ?>
                    <div class="block-title">
                        <span class="text-title">
                            <?php echo esc_html($atts['title']); ?>
                        </span>
                    </div>
                <?php endif; ?>
                <div class="block-content verticalmenu-content">
                    <?php if (is_object($nav_menu)): ?>
                        <?php
                        wp_nav_menu(array(
                                'menu' => $nav_menu,
                                'depth' => 3,
                                'container' => '',
                                'container_class' => '',
                                'container_id' => '',
                                'menu_class' => 'moorabi-nav vertical-menu',
                                'megamenu_layout' => 'vertical',
                            )
                        );
                        $menu_id = $nav_menu->term_id;
                        $menu_items = wp_get_nav_menu_items($menu_id);
                        $count = 0;
                        foreach ($menu_items as $menu_item) {
                            if ($menu_item->menu_item_parent == 0)
                                $count++;
                        }
                        if ($count > $limit_items) : ?>
                            <div class="view-all-category">
                                <a href="javascript:void(0)"
                                   data-closetext="<?php echo esc_attr($button_close_text); ?>"
                                   data-alltext="<?php echo esc_attr($button_all_text) ?>"
                                   class="btn-view-all open-cate"><?php echo esc_html($button_all_text) ?></a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                </div>
            </div>
            <?php
        }
    }
}