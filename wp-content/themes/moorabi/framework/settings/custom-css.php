<?php
if (!function_exists('moorabi_custom_inline_css')) {
    function moorabi_custom_inline_css()
    {
        $css = moorabi_theme_color();
        $content = preg_replace('/\s+/', ' ', $css);
        wp_add_inline_style('moorabi-style', $content);
    }
}
add_action('wp_enqueue_scripts', 'moorabi_custom_inline_css', 999);
if (!function_exists('moorabi_theme_color')) {
    function moorabi_theme_color()
    {
        $main_color = Moorabi_Functions::moorabi_get_option('main_color', '#71c0ef');
        $secondary_color = Moorabi_Functions::moorabi_get_option('secondary_color', '#ed71a3');
        $tertiary_color = Moorabi_Functions::moorabi_get_option('tertiary_color', '#f7c86f');
        $enable_typography = Moorabi_Functions::moorabi_get_option('enable_typography');
        $typography_group = Moorabi_Functions::moorabi_get_option('typography_group');
        $width = Moorabi_Functions::moorabi_get_option('width_logo', '150');
        $width .= 'px';
        $css = '
        header .logo {
            width: ' . $width . ';
        }
        ';
        if ($enable_typography == 1 && !empty($moorabi_typography_group)) {
            foreach ($typography_group as $item) {
                $css .= '
					' . $item['element_tag'] . '{
						font-family: ' . $item['typography_font_family']['family'] . ';
						font-weight: ' . $item['typography_font_family']['variant'] . ';
						font-size: ' . $item['typography_font_size'] . 'px;
						line-height: ' . $item['typography_line_height'] . 'px;
						color: ' . $item['body_text_color'] . ';
					}
				';
            }
        }
        $css .= '
        .page-links > span:not(.page-links-title),
        .page-links > a:hover {
            border-color: ' . $main_color . ';
            background-color: ' . $main_color . ';
        }
        .post-password-form input[type="submit"]:hover,
        .woocommerce-error .button, .woocommerce-info .button, .woocommerce-message .button,
        .widget_shopping_cart .woocommerce-mini-cart__buttons .button,
        #widget-area .widget .select2-container--default .select2-selection--multiple .select2-selection__choice,
        .owl-slick .slick-arrow,
        .block-menu-bar .menu-bar:hover span,
        .chosen-results > .scroll-element .scroll-bar:hover,
        .meta-woo .block-woo .block-link,
        .block-user #wp-submit,
        .block-minicart .cart_list > .scroll-element .scroll-bar:hover,
        .vertical-wrapper.block-nav-category .block-title,
        .header.style-03 .block-search-form .btn-submit,
        a.backtotop,
        .post-item .post-standard .readmore,
        .post-item .slick-arrow,
        .widget-moorabi-post .post-thumb a::before,
        #widget-area .widget .select2-container--default .select2-selection--multiple .select2-selection__choice,
        .woocommerce-widget-layered-nav-dropdown .woocommerce-widget-layered-nav-dropdown__submit,
        .widget_price_filter .ui-slider-range,
        .widget_price_filter .ui-slider-range::before,
        .moorabi_widget_layered_nav .inline-group a:hover,
        .moorabi_widget_layered_nav .inline-group a.selected,
        .woocommerce-pagination span.page-numbers.current,
        .woocommerce-pagination span.page-numbers:hover,
        .woocommerce-pagination a.page-numbers.current,
        .woocommerce-pagination a.page-numbers:hover,
        .woocommerce-pagination li .page-numbers.current,
        .woocommerce-pagination li .page-numbers:hover,
        .comments-pagination .page-numbers.current,
        .comments-pagination a.page-numbers:hover,
        .post-pagination > span:not(.title),
        .post-pagination a span:hover,
        .pagination .page-numbers.current,
        .pagination .page-numbers:hover,
        #yith-quick-view-close,
        .onnew,
         #yith-wcwl-popup-message,
        .process-valiable .valiable-total .process,
        .countdown-product .moorabi-countdown > span,
        .product-item.style-01 .group-button .add-to-cart,
        .product-item.style-02 .yith-wcqv-button:hover,
        .product-item.style-02 .group-button .add-to-cart,
        .product-item.style-03 .add-to-cart a,
        .product-item.style-04 .group-button .add-to-cart,
        .product-item.list .add-to-cart a,
        .product-item.list .add-to-cart a:hover,
        .product-360-button a,
        .single-left .product-video-button a,
        .woocommerce-product-gallery .woocommerce-product-gallery__trigger,
        .woocommerce-product-gallery .flex-control-nav.flex-control-thumbs .slick-arrow,
        .entry-summary form.cart.variations_form .variations .reset_variations,
        .entry-summary .moorabi-share-socials a,
        .yith-wfbt-section .yith-wfbt-submit-block .yith-wfbt-submit-button,
        .wc-tabs li a:hover,
        .wc-tabs li.active a,
        body.woocommerce-cart .return-to-shop a,
        .wc-proceed-to-checkout .checkout-button,
        .moorabi-checkout-login .woocommerce-info::before,
        .moorabi-checkout-coupon .woocommerce-info::before,
        .checkout_coupon .button,
        #order_review_heading,
        #place_order,
        .woocommerce-order .woocommerce-notice,
        .woocommerce-order .woocommerce-order-details .woocommerce-order-details__title,
        .woocommerce-order .woocommerce-customer-details .woocommerce-column__title,
        body.woocommerce-account .woocommerce-notices-wrapper ~ h2::before,
        .woocommerce-ResetPassword .form-row .woocommerce-Button:hover,
        .woocommerce table.wishlist_table tr td.product-stock-status span.wishlist-in-stock,
        .woocommerce table.wishlist_table td.product-add-to-cart a,
        .main-container.error-404 .search-form .search-submit,
        .moorabi-banner .button,
        .moorabi-banner.style-15 .title,
        .moorabi-title.style-01 .icon,
        .wpcf7-form .wpcf7-submit,
        .moorabi-team .social-list a,
        .moorabi-socials.style-01 .socials-list li a,
        .moorabi-tabs.style-01 .tab-link li a,
        .moorabi-testimonial.style-02 .desc,
        .moorabi-verticalmenu.block-nav-category .block-title,
        .dreaming-wccp-content-wrap .return-to-shop a,
        .dreaming-wccp-products-list-wrap .actions-wrap a.dreaming-wccp-go-to-compare,
        .dreaming-wccp-content-wrap .dreaming-wccp-col .dreaming-wccp-field .added_to_cart,
        .dreaming-wccp-content-wrap .dreaming-wccp-col .dreaming-wccp-field .button {
            background-color: ' . $main_color . ';
        }
        .woocommerce-error, .woocommerce-info, .woocommerce-message {
            border-top: 3px solid ' . $main_color . ';
        }
        .mc4wp-response .mc4wp-alert.mc4wp-success,
        article.sticky .post-title a::before,
        .widget_shopping_cart .woocommerce-mini-cart__total .woocommerce-Price-amount,
        a:hover, a:focus, a:active,
        blockquote, q,
        .widget_product_tag_cloud .tagcloud a:hover,
        .widget_tag_cloud .tagcloud a:hover {
            border-color: ' . $main_color . ';
            color: ' . $main_color . ';
        }
        button, input[type="submit"], input[type="button"],
        .post-single-author .author-info a,
        .moorabi-newsletter.style-01 *[type="submit"],
        .moorabi-newsletter.style-02 *[type="submit"],
        .moorabi-newsletter.style-03 *[type="submit"],
        .moorabi-newsletter.style-04 *[type="submit"],
        .moorabi-videopopup .product-video-button a {
            background: ' . $main_color . ';
        }
        .moorabi-products.style-03 .owl-slick .slick-arrow:hover,
        .box-header-nav .main-menu .menu-item .sub-menu .menu-item:hover > a,
        .box-header-nav .main-menu .menu-item:hover > .toggle-submenu,
        .box-header-nav .main-menu > .menu-item.menu-item-right:hover > a,
        .woocommerce-widget-layered-nav-list li.chosen a,
        .widget_categories .cat-item.current-cat > a,
        .widget_pages .page_item.current_page_item > a,
        .widget_product_categories .cat-item.current-cat > a,
        .widget_product_search .woocommerce-product-search button[type="submit"]::before,
        .widget_search .search-form button::before,
        .vertical-menu .menu-item.parent:hover > a::after,
        .vertical-menu > .menu-item:hover > a,
        .vertical-menu > .menu-item.show-submenu > a,
        .vertical-menu > .menu-item.parent:hover > a::after,
        .vertical-menu > .menu-item.show-submenu > a::after,
        .block-nav-category .view-all-category a,
        .comment-form .comment-form-cookies-consent #wp-comment-cookies-consent:checked + label::before,
        .dreaming-wccp-button.added::before,
        .dreaming-wccp-button:hover,
        .dreaming-wccp-content-wrap .dreaming-wccp-right-part .dreaming-wccp-col .dreaming-wccp-field.field-price {
            color: ' . $main_color . ';
        }
        .moorabi-live-search-form.loading .search-box::before {
            border-top-color: ' . $main_color . ';
        }
        .post-item .tags a:hover,
        .grid-view-mode .modes-mode:hover,
        .grid-view-mode .modes-mode:focus,
        .grid-view-mode .modes-mode:active,
        .grid-view-mode .modes-mode.active,
        .price,
        .size-guide-button:hover,
        #single-product-enquiry-form .close:hover,
        #popup-size-guide .close:hover,
        .stock.in-stock,
        .yith-wfbt-section .yith-wfbt-submit-block .price_text .total_price,
        .shop_table .product-name a:not(.button):hover,
        .woocommerce-form__label-for-checkbox .woocommerce-form__input-checkbox:checked + span::after,
        #payment .input-radio:hover + label::after,
        #payment .input-radio:checked + label::after,
        #order_review .shop_table tfoot tr.order-total td strong,
        .woocommerce-MyAccount-navigation > ul li.is-active a,
        .woocommerce table.wishlist_table .product-price,
        .wishlist_table.mobile li table.additional-info td.value .wishlist-in-stock,
        #popup-newsletter button.close:hover,
        .moorabi-banner.style-02 .desc strong,
        .moorabi-customlink .menu .menu-item .icon,
        .woocommerce-product-gallery .flex-control-nav.flex-control-thumbs li img.flex-active {
            border-color: ' . $main_color . ';
        }
        .widget-moorabi-socials .socials-list li a::before,
        #widget-area .widget_nav_menu li a::before,
        .widget_archive li a::before,
        .widget_recent_entries li a::before,
        .woocommerce-widget-layered-nav-list li a::before,
        .widget_categories .cat-item > a::before,
        .widget_pages .page_item > a::before,
        .widget_product_categories .cat-item > a::before,
        .moorabi-videopopup .product-video-button a span::after {
            border-color: transparent transparent transparent ' . $main_color . ';
        }
        .widget_price_filter .ui-slider-handle {
            border: 8px solid ' . $main_color . ';
        }
        .moorabi-testimonial.style-02 .testimonial-info::before {
            border-color: ' . $main_color . ' transparent;
        }
        .moorabi-verticalmenu.block-nav-category .block-content {
            border: 2px solid ' . $main_color . ';
        }
        
        .widget_shopping_cart .woocommerce-mini-cart__buttons .button:hover,
        .widget_shopping_cart .woocommerce-mini-cart__buttons .button.checkout,
        .owl-slick .slick-arrow:hover,
        .meta-woo .block-woo.block-user .block-link,
        a.backtotop:hover,
        .post-single.post-item .post-meta .date,
        .post-single-author .author-info a:hover,
        .blog-standard .post-item .post-meta .date,
        .post-item .post-standard .readmore:hover,
        .post-item .slick-arrow:hover,
        .comment-form .form-submit #submit:hover,
        .widget-moorabi-mailchimp .newsletter-form-wrap *[type="submit"]:hover,
        .widget_price_filter .button:hover,
        .onsale,
        .product-item.style-01 .group-button .yith-wcwl-add-to-wishlist,
        .product-item.style-02 .group-button .yith-wcwl-add-to-wishlist,
        .product-item.style-03 .add-to-cart a:hover,
        .product-item.style-04 .group-button .yith-wcwl-add-to-wishlist,
        .product-360-button a:hover,
        .single-left .product-video-button a:hover,
        .woocommerce-product-gallery .woocommerce-product-gallery__trigger:hover,
        .woocommerce-product-gallery .flex-control-nav.flex-control-thumbs .slick-arrow:hover,
        .entry-summary form.cart.variations_form .variations .reset_variations:hover,
        .entry-summary .cart .single_add_to_cart_button:hover,
        .entry-summary .yith-wcwl-add-to-wishlist,
        .entry-summary .moorabi-share-socials a.pinterest,
        .yith-wfbt-section .yith-wfbt-submit-block .yith-wfbt-submit-button:hover,
        body.woocommerce-cart .return-to-shop a:hover,
        .woocommerce-cart-form .shop_table .actions button.button:not(:disabled):hover,
        .wc-proceed-to-checkout .checkout-button:hover,
        .checkout_coupon .button:hover,
        form.woocommerce-form-login .button:hover,
        form.register .button:hover,
        .woocommerce-MyAccount-content fieldset ~ p .woocommerce-Button:hover,
         .wishlist_table .product-stock-status span.wishlist-out-of-stock,
        .woocommerce table.wishlist_table td.product-add-to-cart a:hover,
        .track_order .button:hover,
        #popup-newsletter .newsletter-form-wrap .submit-newsletter:hover,
        .moorabi-banner .button:hover,
        .moorabi-banner.style-08 .button,
        .moorabi-blog.style-02 .post-item .categories,
        .wpcf7-form .wpcf7-submit:hover,
        .moorabi-team .social-list a:nth-child(3n),
         .moorabi-newsletter.style-01 *[type="submit"]:hover,
        .moorabi-newsletter.style-02 *[type="submit"]:hover,
         .moorabi-newsletter.style-03 *[type="submit"]:hover,
        .moorabi-newsletter.style-04 *[type="submit"]:hover,
        .moorabi-newsletter.style-05 *[type="submit"]:hover,
         .moorabi-videopopup .product-video-button a:hover,
        .moorabi-socials.style-01 .socials-list li:nth-child(3n) a,
        .moorabi-tabs.style-01 .tab-link li a:hover,
        .moorabi-tabs.style-01 .tab-link li.active a,
        .dreaming-wccp-content-wrap .return-to-shop a:hover,
        .dreaming-wccp-content-wrap .dreaming-wccp-col .dreaming-wccp-field .added_to_cart:hover,
        .dreaming-wccp-content-wrap .dreaming-wccp-col .dreaming-wccp-field .button:hover {
            background-color: '. $secondary_color .';
        }
        .moorabi-live-search-form .product-price ins,
        .woocommerce-mini-cart__empty-message,
        .post-title a:hover,
        .breadcrumbs .breadcrumb li + li::before,
        .woocommerce-breadcrumb i::before,
        .widget_product_search .woocommerce-product-search button[type="submit"]:hover::before,
        .widget_search .search-form button:hover::before,
        .price ins,
        .stock.out-of-stock,
        .woocommerce table.wishlist_table .product-price ins,
        .wishlist_table.mobile li table.additional-info td.value .wishlist-out-of-stock,
        .moorabi-banner .subtitle,
        .moorabi-banner .desc p + p strong,
        .moorabi-testimonial.style-01 .position {
            color: '. $secondary_color .';
        }
        .post-grid .post-title::before,
        .page-title::before,
        .block-title .product-grid-title::before,
        .moorabi-title.style-01 .title::before,
        .moorabi-title.style-02 .title::before,
        .moorabi-title.style-03 .title::before,
        .moorabi-customlink.style-01 .widgettitle::before,
        .moorabi-newsletter.style-01 .title::before,
        .moorabi-newsletter.style-03 .title::before,
        .moorabi-newsletter.style-04 .title::before,
        .moorabi-videopopup .title::before,
        .moorabi-socials.style-01 .title::before,
        .dreaming-wccp-products-list-content .dreaming-wccp-title:before {
            border-bottom: 2px solid '. $secondary_color .';
        }
        #widget-area .widgettitle::before {
            border-bottom: 3px solid '. $secondary_color .';
        }
        .product-item.style-01 .product-inner:hover,
        .product-item.style-02 .product-inner:hover,
        .product-item.style-02 .product-inner:hover .group-button,
        .product-item.style-03:hover .product-inner,
        .product-item.style-04 .product-inner:hover,
        .product-item.list .product-inner:hover {
            border-color: '. $secondary_color .';
        }
        .moorabi-videopopup .product-video-button a:hover span::after {
            border-color: transparent transparent transparent '. $secondary_color .';
        }
        .slick-dots li button:hover,
        .slick-dots li.slick-active button {
            background-color: '. $tertiary_color .';
            border-color: '. $tertiary_color .';
        }
        .block-menu-bar .menu-bar,
        .header-search .link-dropdown,
        .meta-woo .block-woo .block-link .count,
        .product-item.style-01 .group-button .dreaming-wccp-button,
        .product-item.style-02 .group-button .dreaming-wccp-button,
        .product-item.style-04 .group-button .dreaming-wccp-button,
        .entry-summary .moorabi-share-socials a.twitter,
        .moorabi-team .social-list a:nth-child(3n+2),
        .moorabi-socials.style-01 .socials-list li:nth-child(3n+2) a {
            background-color: '. $tertiary_color .';
        }
        .single-product #reviews #review_form_wrapper .comment-respond #reply-title {
            color: '. $tertiary_color .';
            border: 2px solid '. $tertiary_color .';
        }
        .star-rating::before,
        .star-rating > span::before,
        p.stars a.active,
        .moorabi-banner .desc strong {
            color: '. $tertiary_color .';
        }
       
       
		';
        return apply_filters('moorabi_main_custom_css', $css);
    }
}
