<?php
if (!class_exists('Moorabi_Elementor_Customlink')) {
    class Moorabi_Elementor_Customlink extends Moorabi_Elementor
    {
        public $name = 'customlink';
        public $title = 'Customlink';
        public $icon = 'moorabi-elementor-icon eicon-anchor';

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
                    'label' => esc_html__('Alignment', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => esc_html__('Left', 'moorabi'),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__('Center', 'moorabi'),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => esc_html__('Right', 'moorabi'),
                            'icon' => 'eicon-text-align-right',
                        ],
                        'justify' => [
                            'title' => esc_html__('Justified', 'moorabi'),
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
                    'condition' => array(
                        'style' => array('style-01', 'style-04', 'style-05', 'style-06')
                    ),
                    'label_block' => true
                ]
            );
            $repeater = new \Elementor\Repeater();
            $repeater->add_control(
                'type', [
                    'label' => esc_html__('Icon library', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        '' => esc_html__('None', 'moorabi'),
                        'media' => esc_html__('Media', 'moorabi'),
                        'icon' => esc_html__('Icon', 'moorabi'),
                    ],
                    'default' => '',
                    'label_block' => true
                ]
            );
            $repeater->add_control(
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
            $repeater->add_control(
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
            $repeater->add_control(
                'text', [
                    'label' => esc_html__('Text', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Enter your text', 'moorabi'),
                    'label_block' => true
                ]
            );
            $repeater->add_control(
                'link', [
                    'label' => esc_html__('Link', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::URL,
                    'default' => [
                        'url' => '',
                        'is_external' => false,
                        'nofollow' => false,
                    ],
                    'label_block' => true
                ]
            );
            $repeater->add_control(
                'add_home', [
                    'label' => esc_html__('Add Link Home', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'no' => esc_html__('No', 'moorabi'),
                        'yes' => esc_html__('Yes', 'moorabi'),
                    ],
                    'default' => 'no',
                    'label_block' => true
                ]
            );
            $this->add_control(
                'list',
                [
                    'label' => __('List Links', 'moorabi'),
                    'type' => \Elementor\Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'title_field' => '{{{ text }}}',
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
            $css_class = array('moorabi-customlink moorabi-custommenu');
            $css_class[] = $atts['style'];
            ?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                <?php if ($atts['title'] && in_array($atts['style'], array('style-01', 'style-04', 'style-05', 'style-06'))): ?>
                    <h2 class="widgettitle"><?php echo esc_html($atts['title']);?></h2>
                <?php endif; ?>
                <?php if ($atts['list']) { ?>
                    <ul class="menu">
                        <?php foreach ($atts['list'] as $list): ?>
                            <?php if ($list['text'] && $list['link']['url']):
                                $target = !empty($list['link']['is_external']) ? '_blank' : '_self';
                                $link = $list['add_home'] && $list['add_home'] == 'yes' ? get_home_url().$list['link']['url'] : $list['link']['url'];?>
                                <li class="menu-item">
                                    <?php if ($list['type'] == 'icon' && $list['icon']): ?>
                                        <span class="icon">
                                            <span class="<?php echo esc_attr($list['icon']) ?>"></span>
                                        </span>
                                    <?php elseif ($list['type'] == 'media' && $list['media']): ?>
                                        <span class="icon">
                                            <?php Elementor\Icons_Manager::render_icon($list['media'], ['aria-hidden' => 'true']); ?>
                                        </span>
                                    <?php endif; ?>
                                    <a href="<?php echo esc_url($link); ?>"
                                       target="<?php echo esc_attr($target); ?>">
                                        <?php echo esc_html($list['text']); ?>
                                    </a>
                               </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                <?php } ?>
            </div>
            <?php
        }
    }
}