<?php
if (!class_exists('Moorabi_Pinmap_Shortcode')) {
    class Moorabi_Pinmap_Shortcode
    {
        /**
         * Initialize.
         *
         * @return  void
         */
        public function __construct()
        {
            // Enqueue style and script
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
            // Add shortcode
            add_shortcode('moorabi_pinmap', array($this, 'render_shortcode'));
        }

        /**
         * Render inline style.
         *
         * @param $attributes
         * @param  bool  $echo
         *
         * @return mixed
         */
        public function attribute($attributes, $echo = true)
        {
            $html = array_map(
                function ($key) use ($attributes) {

                    if (is_bool($attributes[$key])) {
                        return $attributes[$key] ? $key : '';
                    }
                    if ($key == 'style') {
                        $data = $attributes[$key];
                        $data = array_map(
                            function ($key) use ($data) {

                                return $key.':'.$data[$key];

                            }, array_keys($data)
                        );
                        $data = join(';', $data);
                    } else {
                        $data = is_array($attributes[$key]) ? join(' ', $attributes[$key]) : $attributes[$key];
                    }

                    return $key.'="'.$data.'"';

                }, array_keys($attributes)
            );

            if ($echo == false) {
                return join(' ', $html);
            }

            echo join(' ', $html);
        }

        public function content_image($pin)
        {
            $html = '';

            if ($pin['settings']['popup-title']) {
                $html .= '<div class="moorabi-popup-title">';
                $html .= '<h2>'.esc_html($pin['settings']['popup-title']).'</h2>';
                $html .= '</div>';
            }
            $html .= '<div class="content-image">';
            if (!empty($pin['settings']['image-link-to'])) {
                $html .= '<a target="'.esc_attr($pin['settings']['image-link-target']).'" href="'.esc_url($pin['settings']['image-link-to']).'">';
            }
            if ($pin['settings']['image']) {
                $html .= '<img src="'.esc_url($pin['settings']['image']).'" alt="'.esc_attr($pin['settings']['popup-title']).'"/>';
            }
            if (!empty($pin['settings']['image-link-to'])) {
                $html .= '</a>';
            }
            $html .= '</div>';

            return $html;
        }

        public function content_text($pin)
        {
            $html = '';
            if ($pin['settings']['popup-title']) {
                $html .= '<div class="moorabi-popup-header">';
                $html .= '<h2>'.esc_html($pin['settings']['popup-title']).'</h2>';
                $html .= '</div>';
            }
            if ($pin['settings']['text']) {
                $html .= '<div class="content-text">';
                $html .= do_shortcode($pin['settings']['text']);
                $html .= '</div>';
            }

            return $html;
        }

        public function content_link($pin)
        {
            $html = '<a class="moorabi-link" target="'.esc_attr($pin['settings']['image-link-target']).'" href="'.esc_url($pin['settings']['image-link-to']).'"></a>';

            if ($pin['settings']['popup-title']) {
                $html .= '<h3 class="moorabi-title">'.$pin['settings']['popup-title'].'</h3>';
            } else {
                $html .= '<h3 class="moorabi-title">'.esc_html__('Add your title in backend', 'moorabi-toolkit').'</h3>';
            }

            return $html;
        }

        public function content_woocommerce($pin)
        {
            if (!function_exists('wc_get_product')) {
                return '';
            }

            $product_id = $pin['settings']['product'];
            $product    = wc_get_product($product_id);

            ob_start();

            if (!empty($product)) {
                $permalink     = apply_filters('woocommerce_loop_product_link', $product->get_permalink(), $product);
                if ($pin['settings']['product-thumbnail']) :
                    $image_size = apply_filters('moorabi_pinmap_product_thumbnail', array(
                        $pin['settings']['woo-width'],
                        $pin['settings']['woo-height']
                    ));
                    $thumbnail = $product->get_image($image_size);
                    $thumbnail = apply_filters('moorabi_pinmap_product_thumbnail_html', $thumbnail, $product, $pin);
                    ?>
                    <div class="col-left product-thumb">
                        <a href="<?php echo esc_url($permalink); ?>">
                            <?php echo wp_kses_post($thumbnail); ?>
                        </a>
                    </div>
                <?php endif; ?>
                <div class="col-right product-info">
                    <h2 class="product-name product_title">
                        <a href="<?php echo esc_url($permalink); ?>">
                            <?php echo esc_html($product->get_name()); ?>
                        </a>
                    </h2>
                    <?php if ($pin['settings']['product-rate']) {
                        if (function_exists('moorabi_get_star_rating_html')) {
                            remove_filter('woocommerce_product_get_rating_html', 'moorabi_get_star_rating_html', 10, 3);
                        }
                        echo wc_get_rating_html($product->get_average_rating());
                        if (function_exists('moorabi_get_star_rating_html')) {
                            add_filter('woocommerce_product_get_rating_html', 'moorabi_get_star_rating_html', 10, 3);
                        }
                    }?>
                    <div class="price">
                        <?php echo $product->get_price_html();?>
                    </div>
                    <?php if ($pin['settings']['product-description']) {?>
                        <div class="description">
                            <?php echo wp_trim_words($product->get_short_description(), 10, '...');?>
                        </div>
                    <?php } ?>
                </div>
                <?php
            }

            wp_reset_postdata();

            return ob_get_clean();
        }

        public function popup_before($pin)
        {
            $popup_attr = array(
                'class' => array(
                    'moorabi-popup',
                    'popup-'.$pin['settings']['pin-type'],
                    $pin['settings']['popup-position'],
                )
            );
            ?>
            <div class="moorabi-popup-tooltip">
            <div <?php $this->attribute($popup_attr); ?>>
            <div class="moorabi-popup-main product-item style-01">
            <?php
        }

        public function popup_after($pin)
        {
            ?>
            </div>
            </div>
            </div>
            <?php
        }

        /**
         * Generate HTML code based on shortcode parameters.
         *
         * @param  array  $atts  Shortcode parameters.
         * @param  string  $content  Current content.
         *
         * @return mixed|string|void $html
         */
        public function render_shortcode($atts, $content = null)
        {
            // Extract shortcode parameters.
            if (!isset($atts['id'])) {
                return '';
            }
            // Check is publish
            if (get_post_status($atts['id']) != 'publish') {
                return '';
            }
            // Get current image.
            $attachment_id = get_post_meta($atts['id'], 'moorabi_pinmap_image', true);
            if (!$attachment_id) {
                return '';
            }
            // Get content source.
            $content_builder = apply_filters('the_content', get_post_field('post_content', $atts['id']));
            $content_builder = str_replace(']]>', ']]>', $content_builder);
            // Get image source.
            $image_data = wp_get_attachment_metadata($attachment_id);
            // Get general settings.
            $settings = get_post_meta($atts['id'], 'moorabi_pinmap_settings', true);
            // Get all pins.
            $pins = get_post_meta($atts['id'], 'moorabi_pinmap_pins', true);
            // Check is empty
            if (empty($pins)) {
                return '';
            }
            // Enqueue required assets.
            wp_enqueue_style('moorabi-pinmap');
            wp_enqueue_script('moorabi-pinmap');

            wp_add_inline_style('moorabi-pinmap',
                $this->inline_style($atts['id'], $settings, $pins)
            );
            // Generate HTML.
            $id_shortcode = 'shortcode-pinmap-'.$atts['id'];
            $pinmap_attr  = array(
                'id'          => 'moorabi-pinmap-'.$atts['id'],
                'class'       => array(
                    'moorabi-pinmap',
                    $settings['image-effect'],
                    $settings['tooltip-style'],
                    $settings['popup-show-effect'],
                ),
                'data-width'  => $image_data['width'],
                'data-height' => $image_data['height'],
            );
            ob_start();
            ?>
            <div id="<?php echo esc_attr($id_shortcode); ?>" class="shortcode-pinmap-builder">
                <div <?php $this->attribute($pinmap_attr); ?>>
                    <div class="wrap-image"><?php echo wp_get_attachment_image($attachment_id, 'full'); ?></div>
                    <?php if ($settings['image-effect'] == 'mask'): ?>
                        <div class="mask"></div>
                    <?php endif; ?>
                    <?php if (!Moorabi_Pinmap_Post_Type::is_elementor_editor()): ?>
                        <?php foreach ($pins as $index => $pin) :
                            $pin_attr = array(
                                'id'            => 'moorabi-pin-'.$pin['settings']['id'],
                                'class'         => array(
                                    'moorabi-pin',
                                    $pin['settings']['pin-type'],
                                    'position-'.$pin['settings']['popup-position'],
                                ),
                                'data-top'      => $pin['top'],
                                'data-left'     => $pin['left'],
                                'data-trigger'  => $settings['popup-trigger'],
                                'data-position' => $pin['settings']['popup-position'],
                            );
                            if ($pin['settings']['pin-type'] == 'text' || $pin['settings']['pin-type'] == 'link') {
                                $pin_attr['class'][] = $pin['settings']['text-style'];
                            }
                            $method = "content_{$pin['settings']['pin-type']}";
                            ?>
                            <div <?php $this->attribute($pin_attr); ?>>
                                <?php
                                if ($pin['settings']['icon-type'] == 'icon-image' && !empty($pin['settings']['image-template'])) {
                                    echo '<a href="javascript:void(0)" class="action-pin image-pin" tabindex="'.esc_attr($index).'">';
                                    echo '<img src="'.esc_attr($pin['settings']['image-template']).'" alt="Pin" />';
                                    echo '</a>';
                                } else {
                                    echo '<div class="action-pin text__area '.esc_attr($pin['settings']['icon-type']).'" tabindex="'.esc_attr($index).'">';
                                    if ($pin['settings']['icon-type'] == 'icon-theme') {
                                        echo '<span></span>';
                                    } elseif (!empty($pin['settings']['area-text'])) {
                                        echo wp_kses_post($pin['settings']['area-text']);
                                    }
                                    echo '</div>';
                                }
                                if (method_exists($this, $method)) {

                                    $this->popup_before($pin);

                                    echo apply_filters('moorabi_content_type_pinmap', $this->$method($pin), $atts, $this);

                                    $this->popup_after($pin);
                                }
                                ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <?php
                if ($content_builder) {
                    echo '<div class="content-builder"><div class="container">'.$content_builder.'</div></div>';
                }
                ?>
            </div>
            <?php

            return apply_filters('moorabi_render_shortcode_pinmap', ob_get_clean(), $atts);
        }

        /**
         * Enqueue custom scripts / stylesheets.
         *
         * @return  void
         */
        public function enqueue_scripts()
        {
            // Enqueue required assets.
            // https://getbootstrap.com/docs/3.4/getting-started/#download
            wp_register_style('moorabi-tooltip', MOORABI_TOOLKIT_URL . '/includes/mapper/assets/3rd-party/tooltip/tooltip.css');
            wp_register_script('moorabi-tooltip', MOORABI_TOOLKIT_URL . '/includes/mapper/assets/3rd-party/tooltip/tooltip.min.js', array(), false, true);

            wp_register_style('moorabi-pinmap', MOORABI_TOOLKIT_URL . '/includes/mapper/assets/css/pinmap.min.css', array('moorabi-tooltip'), false);
            wp_register_script('moorabi-pinmap', MOORABI_TOOLKIT_URL . '/includes/mapper/assets/js/pinmap.min.js', array('moorabi-tooltip'), false, true);
        }

        /**
         * Render inline style.
         *
         * @param  int  $id  Mapper ID.
         * @param  array  $settings  Mapper settings.
         * @param  array  $pins  Mapper pins.
         *
         * @return string|string[]|null $css
         */
        public function inline_style($id, $settings, $pins)
        {
            $css             = '';
            $post_custom_css = '';
            $post_custom_css .= get_post_meta($id, '_wpb_post_custom_css', true);
            $post_custom_css .= get_post_meta($id, '_wpb_shortcodes_custom_css', true);
            $post_custom_css .= get_post_meta($id, '_Moorabi_Shortcode_custom_css', true);
            $post_custom_css .= get_post_meta($id, '_Moorabi_VC_Shortcode_Custom_Css', true);
            // Generate CSS rules for general settings.
            $css .= '#MOORABIpopover'.$id.' .moorabi-popup {';
            $css .= 'padding: 10px;';
            if ($settings['popup-width']) {
                $css .= 'width: '.esc_attr($settings['popup-width']).'px;';
            }
            if ($settings['popup-height']) {
                $css .= 'min-height: '.esc_attr($settings['popup-height']).'px;';
            }
            if ($settings['popup-box-shadow']) {
                $css .= 'box-shadow: 0px 2px 10px 0px '.esc_attr($settings['popup-box-shadow']).';';
            }
            if ($settings['popup-border-radius']) {
                $css .= 'border-radius: '.(int) $settings['popup-border-radius'].'px;';
            }
            if ($settings['popup-border-width']) {
                $css .= 'border: '.(int) $settings['popup-border-width'].'px solid;';
            }
            if ($settings['popup-border-color']) {
                $css .= 'border-color: '.esc_attr($settings['popup-border-color']).';';
            }
            $css .= '}';
            if ($post_custom_css) {
                $css .= $post_custom_css;
            }
            if ($settings['image-effect'] == 'mask') {
                $css .= '
				#moorabi-pinmap-'.$id.' .mask {';
                if ($settings['mask-color']) {
                    $css .= 'background: '.esc_attr($settings['mask-color']).';';
                }
                $css .= '}';
            }
            // Generate CSS rules for each pin.
            if ($pins) {
                foreach ($pins as $pin) {
                    // Popup width & height
                    $css .= '
					#moorabi-pinmap-'.$id.' #moorabi-pin-'.esc_attr($pin['settings']['id']).' .moorabi-popup {';
                    if (isset($pin['settings']['popup-width']) && (int) $pin['settings']['popup-width'] > 0) {
                        $css .= 'width: '.(int) esc_attr($pin['settings']['popup-width']).'px;';
                    }
                    if (isset($pin['settings']['popup-height']) && (int) $pin['settings']['popup-height'] > 0) {
                        $css .= 'min-height: '.(int) esc_attr($pin['settings']['popup-height']).'px;';
                    }
                    $css .= '}';
                    // Pin style setting
                    $css .= '
					#moorabi-pinmap-'.$id.' #moorabi-pin-'.esc_attr($pin['settings']['id']).' .icon-pin {';
                    if (isset($pin['settings']['bg-color'])) {
                        $css .= 'background: '.esc_attr($pin['settings']['bg-color']).';';
                    }
                    if (isset($pin['settings']['icon-color'])) {
                        $css .= 'color: '.esc_attr($pin['settings']['icon-color']).';';
                    }
                    if (isset($pin['settings']['icon-size'])) {
                        $css .= 'font-size: '.(int) esc_attr($pin['settings']['icon-size']).'px;';
                        $css .= 'width: '.(int) esc_attr($pin['settings']['icon-size']) * 1.2 .'px;';
                        $css .= 'line-height: '.(int) esc_attr($pin['settings']['icon-size']) * 1.2 .'px;';
                    }
                    if (isset($pin['settings']['border-width']) && (int) $pin['settings']['border-width'] > 0) {
                        $css .= 'box-shadow: 0 0 0 '.(int) esc_attr($pin['settings']['border-width']).'px '.esc_attr($pin['settings']['border-color']).';';
                    }
                    $css .= '}';
                    // Pin hover setting
                    $css .= '
					#moorabi-pinmap-'.$id.' #moorabi-pin-'.esc_attr($pin['settings']['id']).' .icon-pin:hover {';
                    if (isset($pin['settings']['bg-color-hover'])) {
                        $css .= 'background: '.esc_attr($pin['settings']['bg-color-hover']).';';
                    }
                    if (isset($pin['settings']['icon-color-hover'])) {
                        $css .= 'color: '.esc_attr($pin['settings']['icon-color-hover']).';';
                    }
                    $css .= '}';
                    // Style text
                    if ($pin['settings']['icon-type'] == 'icon-area') {
                        $css .= '
					    #moorabi-pinmap-'.$id.' #moorabi-pin-'.esc_attr($pin['settings']['id']).' .text__area{';
                        $css .= 'line-height: '.(int) esc_attr($pin['settings']['area-text-line-height']).'px;';
                        $css .= 'font-size: '.(int) esc_attr($pin['settings']['area-text-size']).'px;';
                        $css .= 'color: '.esc_attr($pin['settings']['area-text-color']).';';
                        $css .= 'width: '.(int) esc_attr($pin['settings']['area-width']).'px;';
                        $css .= 'height: '.(int) esc_attr($pin['settings']['area-height']).'px;';
                        $css .= 'border-width: '.(int) esc_attr($pin['settings']['area-border-width']).'px;';
                        $css .= 'border-radius: '.(int) esc_attr($pin['settings']['area-border-radius']).'px;';
                        $css .= 'border-color: '.esc_attr($pin['settings']['area-border-color']).';';
                        $css .= 'border-style: solid;';
                        $css .= 'background: '.esc_attr($pin['settings']['area-bg-color']).';';
                        $css .= '}';
                        // Hover text
                        $css .= '
                	    #moorabi-pinmap-'.$id.' #moorabi-pin-'.esc_attr($pin['settings']['id']).' .text__area:hover,
                 	    #moorabi-pinmap-'.$id.' #moorabi-pin-'.esc_attr($pin['settings']['id']).' img:hover{ ';
                        $css .= '}';
                    }
                }
            }

            return preg_replace('/\s+/', ' ', $css);
        }
    }

    new Moorabi_Pinmap_Shortcode();
}
