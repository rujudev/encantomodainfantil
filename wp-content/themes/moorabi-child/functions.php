<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'flaticon','bootstrap','magnific-popup','jquery-scrollbar','chosen','moorabi-style' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

// END ENQUEUE PARENT ACTION

function enqueue_swiper_scripts() {
    wp_register_style( 'Swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css' );
    wp_enqueue_style('Swiper');
    wp_register_script( 'Swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', null, null, true );
    wp_enqueue_script('Swiper');
}
add_action('wp_enqueue_scripts', 'enqueue_swiper_scripts');

// Funciones personalizadas para cambiar ciertas funcionalidades del padre

/**
 * HOOK PARA ELIMINAR LAS FUNCIONALIDADES DEL PADRE Y AÑADIR LAS CUSTOM
 */
add_action('wp_loaded', 'override_moorabi_functions');

// woo-functions.php
/**
 *
 * PRODUCT THUMBNAIL
 */

function override_moorabi_functions() {
    remove_action('woocommerce_before_shop_loop_item_title', 'moorabi_template_loop_product_thumbnail', 10);
    add_action('woocommerce_before_shop_loop_item_title', 'custom_moorabi_template_loop_product_thumbnail', 10);
}

if (!function_exists('custom_moorabi_template_loop_product_thumbnail')) {
    function custom_moorabi_template_loop_product_thumbnail()
    {
        global $product;
        // GET SIZE IMAGE SETTING
        $width = 320;
        $height = 320;
        $crop = true;
        $size = wc_get_image_size('shop_catalog');
        if ($size) {
            $width = $size['width'];
            $height = $size['height'];
            if (!$size['crop']) {
                $crop = false;
            }
        }
        $data_src = '';
        $attachment_ids = $product->get_gallery_image_ids();
        // cambio realizado aqui
        $product_thumbnail = get_post_thumbnail_id($product->get_id()) !== 0 ? get_post_thumbnail_id($product->get_id()) : 6;
        $gallery_class_img = $class_img = array('img-responsive');
        $thumb_gallery_class_img = $thumb_class_img = array('thumb-link');
        $width = apply_filters('moorabi_shop_product_thumb_width', $width);
        $height = apply_filters('moorabi_shop_product_thumb_height', $height);
        // Cambio realizado aqui
        $image_thumb = apply_filters('moorabi_resize_image', $product_thumbnail, $width, $height, $crop, true);
        $image_url = $image_thumb['url'];
        if ($attachment_ids && has_post_thumbnail()) {
            $gallery_class_img[] = 'wp-post-image';
            $thumb_gallery_class_img[] = 'woocommerce-product-gallery__image';
        } else {
            $class_img[] = 'wp-post-image';
            $thumb_class_img[] = 'woocommerce-product-gallery__image';
        }
        ?>
        <a class="<?php echo esc_attr(implode(' ', $thumb_class_img)); ?>" href="<?php the_permalink(); ?>">
            <img class="<?php echo esc_attr(implode(' ', $class_img)); ?>" src="<?php echo esc_attr($image_url); ?>"
                <?php echo esc_attr($data_src); ?> <?php echo image_hwstring($width, $height); ?>
                 alt="<?php echo esc_attr(get_the_title()); ?>">
        </a>
        <?php
        if ($attachment_ids && has_post_thumbnail()) :
            $gallery_data_src = '';
            $gallery_thumb = apply_filters('moorabi_resize_image', $attachment_ids[0], $width, $height, $crop, true);
            $gallery_image_url = $gallery_thumb['url'];
            ?>
            <div class="second-image">
                <a href="<?php the_permalink(); ?>" class="<?php echo esc_attr(implode(' ', $thumb_gallery_class_img)); ?>">
                    <img class="<?php echo esc_attr(implode(' ', $gallery_class_img)); ?>"
                         src="<?php echo esc_attr($gallery_image_url); ?>"
                        <?php echo esc_attr($gallery_data_src); ?> <?php echo image_hwstring($width, $height); ?>
                         alt="<?php echo esc_attr(get_the_title()); ?>">
                </a>
            </div>
            <?php
        endif;
    }
}

/**
 * Añadir las categorías de producto en forma de lista. Añadido como shortcode
 */

function show_product_categories_list() {
    $taxonomy     = 'product_cat';
    $orderby      = 'name';
    $show_count   = false;      // 1 for yes, 0 for no
    $pad_counts   = true;      // 1 for yes, 0 for no
    $hierarchical = false;      // 1 for yes, 0 for no
    $title        = '';
    $hide_empty   = true;

    $args = array(
        'taxonomy'     => $taxonomy,
        'orderby'      => $orderby,
        'show_count'   => $show_count,
        'pad_counts'   => $pad_counts,
        'hierarchical' => $hierarchical,
        'title_li'     => $title,
        'hide_empty'   => $hide_empty
    );

    $all_categories = get_terms($args);

    echo '<div class="moorabi-title style-01">';
    echo '<h3 class="title">';
    echo '<span>Categorías</span>';
    echo '</h3>';
    echo '</div>';

    echo '<div class="swiper-categories">';
    echo '<button class="fa fa-angle-left prev navigation"></button>';
    echo '<div class="swiper">';
    echo '<div class="swiper-wrapper">';

    foreach ($all_categories as $category) {
        if ($category->parent !== 0) continue;

        $category_id = $category->term_id;
        $category_thumbnail = get_term_meta( $category_id, 'thumbnail_id', true);
        $category_thumbnail_url = $category_thumbnail ? wp_get_attachment_url( $category_thumbnail ) : 'https://via.placeholder.com/300x300';

        echo '<div class="swiper-slide">';
        echo '<a aria-label="'. $category->description .'" href="'. get_term_link($category->slug, 'product_cat') .'">';
        echo '<img loading="lazy" decoding="async" src="' . $category_thumbnail_url .'" alt="'. $category->name .'">';
        echo '<h2 class="woocommerce-loop-category__title">'. $category->name .'</h2>';
        echo '</a>';
        echo '</div>';
    }

    echo '</div>';
    echo '<div class="swiper-pagination"></div>';
    echo '</div>';
    echo '<button class="fa fa-angle-right next navigation"></button>';
    echo '</div>';

    ?>
        <script type="module">
            import Swiper from 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.mjs';

            document.addEventListener('DOMContentLoaded', () => {
                const prevButton = document.querySelector('#categories .prev');
                const nextButton = document.querySelector('#categories .next');
                const swiper = new Swiper('.swiper', {
                    slidesPerView: 2,
                    spaceBetween: 30,
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true
                    },
                    breakpoints: {
                        992: {
                            slidesPerView: 3,
                        },
                        1200: {
                            slidesPerView: 4,
                        },
                        1500: {
                            slidesPerView: 5,
                        },
                    },
                });
    
                prevButton.addEventListener('click', () => {
                    swiper.slidePrev();
                })

                nextButton.addEventListener('click', () => {
                    swiper.slideNext();
                })
            })
        </script>
    <?php
}

add_shortcode('custom_product_categories', 'show_product_categories_list');

function custom_get_account_menu_items() {
	$menu_items = wc_get_account_menu_items();
	$icons = [
		'dashboard'		  => '<i class="fa fa-desktop"></i>',
		'orders'          => '<i class="fa fa-shopping-basket"></i>',
		'edit-address'    => '<i class="fa fa-home"></i>',
		'payment-methods' => '<i class="lab la-cc-visa la-lg"></i>',
		'edit-account'    => '<i class="flaticon-profile"></i>',
		'customer-logout' => '<i class="fa fa-sign-out"></i>'
	];

	foreach($menu_items as $item_key => $label) { ?>
        <?php if ($item_key === 'view-wepos' || $item_key === 'downloads') continue; ?>

		<li class="<?php echo wc_get_account_menu_item_classes( $item_key ); ?>">
			<a href="<?php echo esc_url( wc_get_account_endpoint_url( $item_key ) ); ?>">
                <?php echo $icons[$item_key]; ?>
				<span>
					<?php echo esc_html( $label ); ?>
				</span>
			</a>
		</li>
	<?php

	}
}

add_action( 'get_account_menu_items', 'custom_get_account_menu_items');