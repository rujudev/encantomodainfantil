<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<?php if (!is_single() && !is_page_template('templates/fullwidth.php')) {
    if (class_exists('WooCommerce')) {
        if (is_woocommerce()) { ?>
            <h1 class="page-title shop-title"><?php woocommerce_page_title() ?></h1>
        <?php } elseif (is_home()) { ?>
            <?php if (is_front_page()) { ?>
                <h1 class="page-title"><?php esc_html_e('Latest Posts', 'moorabi') ?></h1>
            <?php } else { ?>
                <h1 class="page-title"><?php single_post_title() ?></h1>
            <?php }
        } elseif (is_page()) { ?>
            <h1 class="page-title"><?php single_post_title() ?></h1>
        <?php } elseif (is_search()) { ?>
            <h1 class="page-title blog-title"><?php printf(esc_html__('Search Results for: %s', 'moorabi'), '<span>' . get_search_query() . '</span>'); ?></h1>
        <?php } else { ?>
            <h1 class="page-title"><?php the_archive_title() ?></h1>
            <?php the_archive_description('<div class="taxonomy-description">', '</div>'); ?>
            <?php
        }
    } elseif (is_home()) { ?>
        <?php if (is_front_page()) { ?>
            <h1 class="page-title"><?php esc_html_e('Latest Posts', 'moorabi') ?></h1>
        <?php } else { ?>
            <h1 class="page-title"><?php single_post_title() ?></h1>
        <?php }
    } elseif (is_page()) { ?>
        <h1 class="page-title"><?php single_post_title() ?></h1>
    <?php } elseif (is_search()) { ?>
        <h1 class="page-title blog-title"><?php printf(esc_html__('Search Results for: %s', 'moorabi'), '<span>' . get_search_query() . '</span>'); ?></h1>
    <?php } else { ?>
        <h1 class="page-title"><?php the_archive_title() ?></h1>
        <?php the_archive_description('<div class="taxonomy-description">', '</div>'); ?>
        <?php
    }
}

