<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link       https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package    WordPress
 * @subpackage Moorabi
 * @since      1.0
 * @version    1.0
 */
get_header();
?>
    <div class="main-container error-404 not-found">
        <div class="container">
            <div class="row">
                <div class="col-md-offset-1 col-md-10 col-sm-12">
                    <div class="row">
                        <div class="col-sm-6 text-center">
                            <?php $image_url = get_theme_file_uri('/assets/images/404.png'); ?>
                            <img alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($image_url) ?>"/>
                        </div>
                        <div class="col-sm-6">
                            <h1 class="title-notfound"><?php esc_html_e('Error 404 Not Found ', 'moorabi'); ?></h1>
                            <p class="subtitle">
                                <?php echo esc_html__('We´re sorry but the page you are looking for does nor exist. You could return to ', 'moorabi'); ?>
                                <a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html__('Home page', 'moorabi'); ?></a>
                            </p>
                            <?php echo get_template_part('searchform'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
get_footer();
