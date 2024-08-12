<?php
/**
 * Moorabi Footer Builder setup
 *
 * @author
 * @category
 * @package  Moorabi_Footer_Builder
 * @since   1.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('Moorabi_Footer_Builder')) :
    class Moorabi_Footer_Builder
    {
        public $post_type = 'moorabi_footer';

        public function __construct()
        {
            add_action('init', array(&$this, 'post_type'));
            /* admin bar footer */
            add_action('admin_bar_menu', array($this, 'footer_admin_bar'), 999);
            /* enqueue */
            add_action('wp_enqueue_scripts', array($this, 'inline_css'), 999);
            /* content footer */
            add_action('moorabi_footer_content', array($this, 'footer_content'));
        }

        public function is_support_elementor($type)
        {
            $post_type   = is_numeric($type) ? get_post_type($type) : $type;
            $cpt_support = get_option('elementor_cpt_support', ['page', 'post']);

            if (class_exists('Elementor\Plugin') && in_array($post_type, $cpt_support)) {
                return true;
            }

            return false;
        }

        public function is_elementor($post_id)
        {
            if (class_exists('Elementor\Plugin') && $this->is_support_elementor($post_id)) {
                if (get_post_meta($post_id, '_elementor_edit_mode', true)) {
                    return true;
                }
            }

            return false;
        }


        public function get_footer_option()
        {
            $data_meta           = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );
            $footer_option      = ( class_exists( 'Moorabi_Functions' ) ) ? Moorabi_Functions::moorabi_get_option( 'footer_options' ) : cs_get_option( 'footer_options', '' );
            $footer_option      = isset( $data_meta['enable_footer'] ) && $data_meta['enable_footer'] == 1 && isset( $data_meta['metabox_footer_options'] ) && $data_meta['metabox_footer_options'] != '' ? $data_meta['metabox_footer_options'] : $footer_option;

            return $footer_option;
        }

        public function get_footer_query()
        {
            $options = $this->get_footer_option();

            if (empty($options)) {
                return array();
            }
            $args = array(
                'post_type'      => $this->post_type,
                'posts_per_page' => 1,
            );
            if (is_numeric($options)) {
                $args['p'] = $options;
            } else {
                $args['name'] = $options;
            }

            return get_posts($args);
        }

        public function footer_admin_bar()
        {
            global $wp_admin_bar;

            if (!is_super_admin() || !is_admin_bar_showing() || is_network_admin()) {
                return;
            }
            // Add Parent Menu
            $options = $this->get_footer_option();
            if ($post = get_page_by_path($options, OBJECT, $this->post_type)) {
                $post_id = $post->ID;
            } else {
                $post_id = 0;
            }
            if ($post_id > 0 && !$this->is_elementor($post_id)) {
                $args = array(
                    'id'    => 'footer_option',
                    'title' => esc_html__('Edit Footer', 'moorabi-toolkit'),
                    'href'  => admin_url('post.php?post='.$post_id.'&action=edit'),
                );
                $wp_admin_bar->add_menu($args);
            }
        }

        public function inline_css()
        {
            $css   = '';
            $posts = $this->get_footer_query();
            if (!empty($posts)) {
                foreach ($posts as $post) {
                    if (!$this->is_elementor($post->ID)) {
                        $post_custom_css[] = get_post_meta($post->ID, '_wpb_post_custom_css', true);
                        $post_custom_css[] = get_post_meta($post->ID, '_wpb_shortcodes_custom_css', true);
                        $post_custom_css[] = get_post_meta($post->ID, '_Moorabi_Shortcode_custom_css', true);
                        $post_custom_css[] = get_post_meta($post->ID, '_Moorabi_VC_Shortcode_Custom_Css', true);
                        if (count($post_custom_css) > 0) {
                            $css = implode(' ', $post_custom_css);
                        }
                    }
                }
            }
            wp_add_inline_style('moorabi-core', preg_replace('/\s+/', ' ', $css));
        }

        public function footer_content()
        {
            $posts = $this->get_footer_query();
            if (!empty($posts)):
                foreach ($posts as $post): ?>
                    <?php
                    $options = $this->get_footer_option();
                    $class   = apply_filters('moorabi_footer_main_class',
                        array('footer', $post->post_name),
                        $options
                    );
                    ?>
                    <footer class="<?php echo esc_attr(implode(' ', $class)); ?>">
                        <?php
                        if ($this->is_elementor($post->ID)) {
                            $content = Elementor\Plugin::$instance->frontend->get_builder_content_for_display($post->ID);
                        } else {
                            $content = $post->post_content;
                            $content = apply_filters('the_content', $content);
                            $content = str_replace(']]>', ']]>', $content);
                        }
                        $content = '<div class="container">'.$content.'</div>';

                        echo apply_filters('moorabi_footer_main_content', $content, $post, $options);
                        ?>
                    </footer>
                <?php
                endforeach;
            endif;
        }

        public function post_type()
        {
            /* Footer */
            $args = array(
                'labels'              => array(
                    'name'          => __('Footer'),
                    'singular_name' => __('Footer'),
                    'all_items'     => __('Footer Builder'),
                ),
                'hierarchical'        => false,
                'supports'            => array(
                    'title',
                    'editor',
                    'thumbnail',
                    'revisions',
                    'elementor',
                ),
                'public'              => true,
                'show_ui'             => true,
                'show_in_menu'        => 'moorabi_menu',
                'menu_position'       => 4,
                'show_in_nav_menus'   => true,
                'publicly_queryable'  => true,
                'exclude_from_search' => true,
                'has_archive'         => false,
                'query_var'           => true,
                'can_export'          => true,
                'show_in_rest'        => true,
                'capability_type'     => 'page',
                'rewrite'             => array(
                    'slug'       => 'footer',
                    'with_front' => false
                ),
            );
            register_post_type($this->post_type, $args);
        }
    }

    new Moorabi_Footer_Builder();
endif;