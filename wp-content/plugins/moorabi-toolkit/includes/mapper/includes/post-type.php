<?php
if (!class_exists('Moorabi_Pinmap_Post_Type')) {
    class Moorabi_Pinmap_Post_Type
    {
        /**
         * Initialize.
         *
         * @return  void
         */
        public function __construct()
        {
            // Register Post_Type.
            add_action('init', array($this, 'register_post_type'));
            // Register Ajax actions / filters.
            add_filter('woocommerce_json_search_found_products', array($this, 'search_products'));
        }

        public function is_pinmap()
        {
            global $pagenow, $post_type, $post;

            if (in_array($pagenow, array('edit.php', 'post.php', 'post-new.php'))) {
                if (!isset($post_type)) {
                    $post_type = isset($_REQUEST['post_type']) ? wp_unslash($_REQUEST['post_type']) : null;
                }
                if (empty($post_type) && (isset($post) || isset($_REQUEST['post']))) {
                    $post_type = isset($post) ? $post->post_type : get_post_type(absint($_REQUEST['post']));
                }
                if ('moorabi_pinmap' == $post_type) {
                    return true;
                }
            }

            return false;
        }

        /**
         * is Elementor editor?
         *
         * @return bool
         */
        public static function is_elementor_editor()
        {
            if (class_exists('Elementor\Plugin')) {
                if (Elementor\Plugin::$instance->preview->is_preview_mode() || Elementor\Plugin::$instance->editor->is_edit_mode()) {
                    return true;
                }
            }

            return false;
        }

        public function register_post_type()
        {
            global $pagenow;

            $labels = array(
                'name'               => esc_html__('Pinmap', 'moorabi-toolkit'),
                'singular_name'      => esc_html__('Pinmap', 'moorabi-toolkit'),
                'menu_name'          => esc_html__('Pinmap', 'moorabi-toolkit'),
                'name_admin_bar'     => esc_html__('Pinmap', 'moorabi-toolkit'),
                'add_new'            => esc_html__('Add New', 'moorabi-toolkit'),
                'add_new_item'       => sprintf(esc_html__('Add New %s', 'moorabi-toolkit'), 'Pinmap'),
                'new_item'           => sprintf(esc_html__('New %s', 'moorabi-toolkit'), 'Pinmap'),
                'edit_item'          => sprintf(esc_html__('Edit %s', 'moorabi-toolkit'), 'Pinmap'),
                'view_item'          => sprintf(esc_html__('View %s', 'moorabi-toolkit'), 'Pinmap'),
                'all_items'          => sprintf(esc_html__('%s', 'moorabi-toolkit'), 'Pinmap'),
                'search_items'       => sprintf(esc_html__('Search %s', 'moorabi-toolkit'), 'Pinmap'),
                'parent_item_colon'  => sprintf(esc_html__('Parent %s:', 'moorabi-toolkit'), 'Pinmap'),
                'not_found'          => sprintf(esc_html__('No %s found.', 'moorabi-toolkit'), 'Pinmap'),
                'not_found_in_trash' => sprintf(esc_html__('No %s found in Trash.', 'moorabi-toolkit'), 'Pinmap'),
            );
            $args   = array(
                'labels'              => $labels,
                'description'         => '',
                'public'              => true,
                'publicly_queryable'  => false,
                'show_ui'             => true,
                'show_in_menu'        => 'moorabi_menu',
                'query_var'           => true,
                'rewrite'             => false,
                'capability_type'     => 'post',
                'has_archive'         => false,
                'hierarchical'        => false,
                'show_in_rest'        => false,
                'menu_position'       => 2,
                'show_in_nav_menus'   => true,
                'exclude_from_search' => true,
                'can_export'          => true,
                'supports'            => array('title', 'editor'),
            );
            register_post_type('moorabi_pinmap', $args);

            // Check if WR Mapper page is requested.
            if ($this->is_pinmap()) {
                if ('edit.php' == $pagenow) {
                    // Register necessary actions / filters to customize All Items screen.
                    add_filter('bulk_actions-edit-moorabi_pinmap', array($this, 'bulk_actions'));
                    add_filter('manage_moorabi_pinmap_posts_columns', array($this, 'register_columns'));
                    add_action('manage_posts_custom_column', array($this, 'display_columns'), 10, 2);
                } elseif (in_array($pagenow, array('post.php', 'post-new.php'))) {
                    if (!isset($_REQUEST['action']) || 'trash' != $_REQUEST['action']) {
                        // Register necessary actions / filters to override Item Details screen.
                        add_action('admin_footer', array($this, 'load_edit_form'));
                        add_action('save_post', array($this, 'save_post'), 10, 2);
                    }
                }
                add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'), 999);
            }
        }

        /**
         * Setup bulk actions for in stock alert subscription screen.
         *
         * @param    $actions
         *
         * @return  array
         */
        public function bulk_actions($actions)
        {
            // Remove edit action.
            unset($actions['edit']);

            return $actions;
        }

        /**
         * Register columns for in stock alert subscription screen.
         *
         * @param  $columns
         *
         * @return  array
         */
        public function register_columns($columns)
        {
            $columns = array(
                'cb'                  => '<input type="checkbox" />',
                'title'               => esc_html__('Name', 'moorabi-toolkit'),
                'image'               => esc_html__('Image', 'moorabi-toolkit'),
                'num_pins'            => esc_html__('Number of Pins', 'moorabi-toolkit'),
                'shortcode'           => esc_html__('Shortcode', 'moorabi-toolkit'),
                'date'                => esc_html__('Time', 'moorabi-toolkit'),
            );

            return $columns;
        }

        /**
         * Display columns for in stock alert subscription screen.
         *
         * @param    $column
         * @param    $post_id
         *
         */
        public function display_columns($column, $post_id)
        {
            switch ($column) {
                case 'image' :
                    // Get current image.
                    $attachment_id = get_post_meta($post_id, 'moorabi_pinmap_image', true);
                    if ($attachment_id) {
                        // Print image source.
                        echo wp_get_attachment_image($attachment_id, array(70, 70));
                    } else {
                        echo esc_html__('No image', 'moorabi-toolkit');
                    }
                    break;
                case 'num_pins' :
                    // Get all pins.
                    $pins = get_post_meta($post_id, 'moorabi_pinmap_pins', true);
                    echo $pins ? count($pins) : 0;
                    break;
                case 'shortcode' :
                    ?>
                    <span>[moorabi_pinmap id="<?php echo absint($post_id); ?>"]</span>
                    <?php
                    break;
            }
        }

        /**
         * Enqueue assets for custom add/edit item form.
         */
        public function enqueue_assets()
        {
            // Check if WR Mapper page is requested.
            global $pagenow;

            if ($this->is_pinmap()) {
                wp_dequeue_script('select2');
            }

            // Register action to print inline initialization script.
            if ($this->is_pinmap() && 'edit.php' == $pagenow) {
                add_action('admin_print_footer_scripts', array($this, 'print_footer_scripts'));
            }

            if ($this->is_pinmap() && in_array($pagenow, array('post.php', 'post-new.php'))) {
                // Enqueue media.
                wp_enqueue_media();
                // Enqueue Select2.
                wp_enqueue_style('select2', MOORABI_TOOLKIT_URL . '/includes/mapper/assets/3rd-party/select2/select2.min.css');
                wp_enqueue_script('wr_select2', MOORABI_TOOLKIT_URL . '/includes/mapper/assets/3rd-party/select2/select2.min.js', array(), false, true);
                // Enqueue custom color picker library.
                wp_enqueue_style('moorabi-color-picker', MOORABI_TOOLKIT_URL . '/includes/mapper/assets/3rd-party/color-picker/color-picker.css', array('wp-color-picker'));
                wp_enqueue_script('moorabi-color-picker', MOORABI_TOOLKIT_URL . '/includes/mapper/assets/3rd-party/color-picker/color-picker.js', array('wp-color-picker'), false, true);
                // Awesome
                wp_enqueue_style('font-awesome', MOORABI_TOOLKIT_URL . '/includes/mapper/assets/3rd-party/font-awesome/css/font-awesome.min.css', array(), '2.4');
                // Enqueue assets for custom add/edit item form.
                wp_enqueue_style('moorabi-pinmap', MOORABI_TOOLKIT_URL . '/includes/mapper/assets/admin/pinmap.css');
                wp_enqueue_script('moorabi-pinmap', MOORABI_TOOLKIT_URL . '/includes/mapper/assets/admin/pinmap.js', array(), false, true);
                wp_localize_script('moorabi-pinmap', 'moorabi_pinmap_params',
                    array(
                        'is_elementor'     => (bool) $this->is_elementor_editor(),
                        'product_selector' => array(
                            'url'      => admin_url('admin-ajax.php?action=woocommerce_json_search_products'),
                            'security' => wp_create_nonce('search-products'),
                        ),
                        'text'             => array(
                            'img_selector_btn_label'   => esc_html__('Select', 'moorabi-toolkit'),
                            'img_selector_modal_title' => esc_html__('Select or upload an image', 'moorabi-toolkit'),
                            'ask_for_saving_changes'   => esc_html__('Your changes on this page are not saved!', 'moorabi-toolkit'),
                            'confirm_removing_pin'     => esc_html__('Are you sure you want to remove this pin?', 'moorabi-toolkit'),
                            'please_input_a_title'     => esc_html__('Please input a title for this pin', 'moorabi-toolkit'),
                        ),
                    )
                );
            }
        }

        /**
         * Method to print inline initialization script for items list screen.
         *
         * @return  void
         */
        public function print_footer_scripts()
        {
            ?>
            <script type="text/javascript">
                jQuery(function ($) {
                    // Init action to copy shortcode to clipboard.
                    $('[data-clipboard-target]').each(function () {
                        var clipboard = new Clipboard('#' + $(this).attr('id'));

                        $(this).data('original-text', $(this).text());

                        clipboard.on('success', $.proxy(function (e) {
                            e.clearSelection();

                            // Swap button status.
                            $(this).text($(this).attr('data-success-text')).attr('disabled', 'disabled');

                            // Restore button after 5 seconds.
                            setTimeout($.proxy(function () {
                                $(this).text($(this).data('original-text')).removeAttr('disabled');
                            }, this), 5000);
                        }, this));

                        clipboard.on('error', $.proxy(function (e) {
                            // Swap button status.
                            $(this).text($(this).attr('data-error-text')).attr('disabled', 'disabled');

                            // Restore button after 5 seconds.
                            setTimeout($.proxy(function () {
                                $(this).text($(this).data('original-text')).removeAttr('disabled');
                            }, this), 5000);
                        }, this));
                    });
                });
            </script>
            <?php
        }

        /**
         * Hide default add/edit item form.
         *
         * @return  void
         */
        public function hide_default_form()
        {
            ?>
            <style type="text/css">
                #screen-meta, #wpb_visual_composer, #screen-meta-links, #submitdiv, #pageparentdiv > .wrap {
                    display: none;
                }
            </style>
            <?php
        }

        /**
         * Load custom add/edit item form.
         *
         * @return  void
         */
        public function load_edit_form()
        {
            if ($this->is_pinmap()) {
                // Load template file.
                include_once MOORABI_TOOLKIT_PATH . 'includes/mapper/includes/templates.php';
            }
        }

        /**
         * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
         * Non-scalar values are ignored.
         *
         * @param  string|array  $var  Data to sanitize.
         *
         * @return string|array
         */
        public function clean($var)
        {
            if (is_array($var)) {
                return array_map(array($this, 'clean'), $var);
            } else {
                return is_scalar($var) ? sanitize_text_field($var) : $var;
            }
        }

        /**
         * Save custom post type extra data.
         *
         * @param $id
         *
         * @return mixed
         */
        public function save_post($id)
        {
            if (isset($_POST['moorabi_pinmap_image'])) {
                update_post_meta($id, 'moorabi_pinmap_image', absint($_POST['moorabi_pinmap_image']));
            }
            if (isset($_POST['moorabi_pinmap_settings']) && is_array($_POST['moorabi_pinmap_settings'])) {
                // Sanitize input data.
                $settings = array();
                $data     = $this->clean(wp_unslash($_POST['moorabi_pinmap_settings']));
                foreach ($data as $key => $value) {
                    $settings[$key] = sanitize_text_field($value);
                }
                update_post_meta($id, 'moorabi_pinmap_settings', $settings);
            }
            if (isset($_POST['moorabi_pinmap_pins']) && is_array($_POST['moorabi_pinmap_pins'])) {
                $pins = array();
                $data = $this->clean(wp_unslash($_POST['moorabi_pinmap_pins']));
                foreach ($data as $k => $pin) {
                    // Sanitize input data.
                    foreach ($pin as $key => $value) {
                        if ('settings' == $key) {
                            foreach ($value as $settings_key => $settings_value) {
                                if ('text' == $settings_key || 'area-text' == $settings_key) {
                                    $pins[$k][$key][$settings_key] = esc_sql(
                                        str_replace(
                                            array("\r\n", "\r", "\n", '\\'),
                                            array('<br>', '<br>', '<br>', ''),
                                            $settings_value
                                        )
                                    );
                                } else {
                                    $pins[$k][$key][$settings_key] = sanitize_text_field($settings_value);
                                }
                                if ('id' == $settings_key && empty($settings_value)) {
                                    $pins[$k][$key][$settings_key] = wp_generate_password(5, false, false);
                                }
                            }
                        } else {
                            $pins[$k][$key] = sanitize_text_field($value);
                        }
                    }
                }
                update_post_meta($id, 'moorabi_pinmap_pins', $pins);
            } else {
                delete_post_meta($id, 'moorabi_pinmap_pins');
            }
            // Publish post if needed.
            if (!defined('DOING_AUTOSAVE') || !DOING_AUTOSAVE) {
                $post = get_post($id);
                if (__('Auto Draft') != $post->post_title && 'publish' != $post->post_status) {
                    wp_publish_post($post);
                }
            }
            // Image Tesst
            if (!isset($_POST['pinmapper_image_fields']) || !wp_verify_nonce($_POST['pinmapper_image_fields'], basename(__FILE__))) {
                return $id;
            }
            // Check Autosave
            if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || (defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit'])) {
                return $id;
            }
            // Don't save if only a revision
            if (isset($post->post_type) && $post->post_type == 'revision') {
                return $id;
            }
            // Check permissions
            if (!current_user_can('edit_post', $post->ID)) {
                return $id;
            }
            $meta['pin_style_select'] = isset($_POST['pin_style_select']) ? wp_unslash($_POST['pin_style_select']) : '';

            foreach ($meta as $key => $value) {
                update_post_meta($id, $key, $value);
            }

            return $id;
        }

        /**
         * Method to alter results of WooCommerce's product search function.
         *
         * @param $found_products
         *
         * @return  array
         */
        public function search_products($found_products)
        {
            // Check if term is a number.
            $id = ( string ) wc_clean(stripslashes($_GET['term']));
            if (preg_match('/^\d+$/', $id)) {
                // Get product.
                $product        = wc_get_product(( int ) $id);
                $found_products = array(
                    $id => rawurldecode(str_replace('&ndash;', ' - ', $product->get_formatted_name())),
                );
            }

            return $found_products;
        }
    }

    new Moorabi_Pinmap_Post_Type();
}