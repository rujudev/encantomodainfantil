<?php
/**
 * Name:  Header style 02
 **/
?>
<?php
$data_meta = get_post_meta(get_the_ID(), '_custom_metabox_theme_options', true);

$header_color = Moorabi_Functions::moorabi_get_option('header_color', 'header-dark');
$header_color = isset($data_meta['enable_header']) && $data_meta['enable_header'] == 1 && isset($data_meta['metabox_header_color']) && $data_meta['metabox_header_color'] != '' ? $data_meta['metabox_header_color'] : $header_color;

$enable_header_transparent = Moorabi_Functions::moorabi_get_option('enable_header_transparent');
$enable_header_transparent = isset($data_meta['enable_header']) && $data_meta['enable_header'] == 1 && isset($data_meta['metabox_enable_header_transparent']) ? $data_meta['metabox_enable_header_transparent'] : $enable_header_transparent;

$email_address = Moorabi_Functions::moorabi_get_option('email_address');
$phone_number = Moorabi_Functions::moorabi_get_option('phone_number');
$all_socials = Moorabi_Functions::moorabi_get_option('user_all_social');

$class = array('header', 'style-02', $header_color);
if (!is_single() && $enable_header_transparent == 1) {
    $class[] = 'header-transparent';
}
?>
<header id="header" class="<?php echo esc_attr(implode(' ', $class)); ?>">
    <?php if ($email_address || $phone_number || !empty($all_socials)) { ?>
        <div class="header-top">
            <div class="container">
                <div class="header-top-inner">
                    <div class="moorabi-menu-wapper">
                        <ul class="top-bar-menu left">
                            <?php if ($email_address) { ?>
                                <li class="menu-item email">
                                    <a href="mailto:<?php echo esc_attr($email_address) ?>"><?php echo esc_html($email_address); ?></a>
                                </li>
                            <?php } ?>
                            <?php if ($phone_number) {
                                $tel = str_replace(' ', '', $phone_number); ?>
                                <li class="menu-item phone">
                                    <a href="tel:<?php echo esc_attr($tel); ?>"><?php echo esc_html($phone_number); ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php if (!empty($all_socials)) { ?>
                        <div class="moorabi-menu-wapper">
                            <ul class="top-bar-menu right">
                                <?php foreach ($all_socials as $value) { ?>
                                    <li class="menu-item">
                                        <a href="<?php echo esc_url($value['link_social']) ?>">
                                            <i class="<?php echo esc_attr($value['icon_social']); ?>"></i>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="header-middle">
        <div class="container">
            <div class="header-middle-inner">
                <div class="header-logo">
                    <?php moorabi_get_logo(); ?>
                </div>
                <?php if (has_nav_menu('primary')) { ?>
                    <div class="box-header-nav">
                        <?php
                        wp_nav_menu(array(
                                'menu' => 'primary',
                                'theme_location' => 'primary',
                                'depth' => 3,
                                'container' => '',
                                'container_class' => '',
                                'container_id' => '',
                                'menu_class' => 'moorabi-nav main-menu horizontal-menu',
                                'megamenu_layout' => 'horizontal',
                            )
                        );
                        ?>
                    </div>
                <?php } ?>
                <div class="header-control">
                    <div class="header-control-inner">
                        <div class="meta-woo">
                            <div class="header-search moorabi-dropdown">
                                <div class="header-search-inner" data-moorabi="moorabi-dropdown">
                                    <a href="javascript:void(0)" class="link-dropdown block-link">
                                        <span class="flaticon-magnifying-glass-1"></span>
                                    </a>
                                </div>
                                <?php moorabi_search_form(); ?>
                            </div>
                            <?php
                            moorabi_user_link();
                            do_action('moorabi_header_mini_cart');
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-mobile-wrap">
        <div class="container">
            <div class="header-mobile">
                <div class="header-mobile-left">
                    <?php if (has_nav_menu('primary')) { ?>
                        <div class="block-menu-bar">
                            <a class="menu-bar menu-toggle" href="javascript:void(0)">
                                <span></span>
                                <span></span>
                                <span></span>
                            </a>
                        </div>
                    <?php } ?>
                    <div class="header-search moorabi-dropdown">
                        <div class="header-search-inner" data-moorabi="moorabi-dropdown">
                            <a href="javascript:void(0)" class="link-dropdown block-link">
                                <span class="flaticon-magnifying-glass-1"></span>
                            </a>
                        </div>
                        <?php moorabi_search_form(); ?>
                    </div>
                </div>
                <div class="header-mobile-mid">
                    <div class="header-logo">
                        <?php moorabi_get_logo(); ?>
                    </div>
                </div>
                <div class="header-mobile-right">
                    <div class="header-control-inner">
                        <div class="meta-woo">
                            <?php
                            moorabi_user_link();
                            do_action('moorabi_header_mini_cart');
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
