jQuery(document).ready(function ($) {
    "use strict";
    
    // Add a product to compare list
    $(document).on('click', '.dreaming-wccp-button:not(.added), .dreaming-wccp-search-result-item', function (e) {
        e.preventDefault();
        var $this = $(this);
        
        if ($this.is('.processing')) {
            return false;
        }
        
        $this.addClass('processing');
        var id = $this.data('product_id');
        var include_return = '';
        if ($this.is('.dreaming-wccp-search-result-item')) {
            include_return = 'compare_table';
        }
        
        var data = {
            action: dreaming_wccp['ajax_actions']['action_add'],
            id: id,
            include_return: include_return,
            context: 'frontend'
        };
        
        $.post(dreaming_wccp['ajaxurl'], data, function (response) {
            
            $this.removeClass('processing').addClass('added').attr('href', response['compare_page_url']).text(dreaming_wccp['text']['added']);
            if (!$('.dreaming-wccp-products-list-wrap .dreaming-wccp-products-list').length) {
                $('body').append('<div class="dreaming-wccp-products-list-wrap"></div>');
            }
            var products_list_tmp_html = dreaming_wccp['template']['products_list'];
            var go_to_compare_html = '<a href="' + response['compare_page_url'] + '" class="dreaming-wccp-go-to-compare">' + dreaming_wccp['text']['compare'] + '</a>';
            products_list_tmp_html = products_list_tmp_html.replace('{{products_list}}', response['list_products_html']).replace('{{go_to_compare_page}}', go_to_compare_html);
            $('.dreaming-wccp-products-list-wrap').html(products_list_tmp_html);
            
            if (include_return == 'compare_table') {
                $('.dreaming-wccp-content-wrap').replaceWith(response['compare_table_html']);
            }
            
            $(document).trigger('dreaming_wccp_added_to_compare');
            
        });
        
        return false;
    });
    
    // Remove product from compare list
    $(document).on('click', '.dreaming-wccp-remove-product, .clear-all-compare-btn', function (e) {
        e.preventDefault();
        var $this = $(this);
        var $thisItem = $this.closest('.compare-item');
        var response_type = 'product_list';
        if ($this.closest('.dreaming-wccp-col').length) {
            $thisItem = $this.closest('.dreaming-wccp-col');
            response_type = 'compare_table';
        }
        
        if ($this.is('.clear-all-compare-btn')) {
            $this.closest('.products-compare-list').addClass('processing');
        }
        
        if ($thisItem.is('.processing') || $this.is('.processing')) {
            return false;
        }
        
        $thisItem.addClass('processing');
        $this.addClass('processing');
        var id = $this.data('product_id');
        var data = {
            action: dreaming_wccp['ajax_actions']['action_remove'],
            id: id,
            context: 'frontend',
            response_type: response_type
        };
        $.post(dreaming_wccp['ajaxurl'], data, function (response) {
            $this.removeClass('processing');
            if ($this.is('.clear-all-compare-btn')) {
                $this.closest('.products-compare-list').removeClass('processing');
            }
            if (response_type == 'product_list') {
                $('.products-compare-list').replaceWith(response);
            }
            else {
                if($('.dreaming-wccp-remove-product').length > 1 ) {
                    $('.dreaming-wccp-content-wrap').replaceWith(response);
                } else {
                    window.location.reload();
                }
            }

        });
        
        return false;
    });
    
    // Added to compare list event
    $(document).on('dreaming_wccp_added_to_compare', function () {
        dreaming_wccp_show_products_list();
    });
    
    // Add more products to comparison list (show popup)
    $(document).on('click', '.dreaming-wccp-add-more-product', function (e) {
        e.preventDefault();
        if (!$('body form[name="dreaming_wccp_search_product_form"]').length) {
            $('body').append(dreaming_wccp['template']['add_product_form']);
        }
        $('body').toggleClass('dreaming-wccp-show-popup');
        return false;
    });
    
    $(document).on('submit', 'form[name="dreaming_wccp_search_product_form"]', function (e) {
        e.preventDefault();
        var $thisForm = $(this);
        var search_keyword = $thisForm.find('input[name="dreaming_wccp_search_product"]').val();
        
        if ($thisForm.is('.processing')) {
            return false;
        }
        
        $thisForm.addClass('processing');
        
        var data = {
            action: 'dreaming_wccp_search_product_via_ajax',
            search_keyword: search_keyword,
            context: 'frontend'
        };
        
        $.post(dreaming_wccp['ajaxurl'], data, function (response) {
            
            $thisForm.removeClass('processing');
            $thisForm.find('.dreaming-wccp-search-results').html(response['html']);
            
        });
        return false;
    });
    
    $(document).on('change', 'input[name="dreaming_wccp_search_product"]', function (e) {
        var $this = $(this);
        var $thisForm = $this.closest('form');
        $thisForm.trigger('submit');
        return false;
    });
    
    // Close popup
    $(document).on('click', '.dreaming-wccp-close-popup', function (e) {
        e.preventDefault();
        $('body').removeClass('dreaming-wccp-show-popup');
        return false;
    });
    
    $(document).on('click', '.dreaming-wccp-popup', function (e) {
        if (!$(e.target).is('.dreaming-wccp-popup-inner') && !$(e.target).closest('.dreaming-wccp-popup-inner').length) {
            $('body').removeClass('dreaming-wccp-show-popup');
        }
    });
    
    // Close compare panel
    $(document).on('click', '.dreaming-wccp-products-list .dreaming-wccp-close', function (e) {
        e.preventDefault();
        $('body').removeClass('dreaming-wccp-show-products-list');
        return false;
    });
    
    $(document).on('click', '.dreaming-wccp-show-products-list', function (e) {
        var $comparePanel = $('.dreaming-wccp-products-list-wrap');
        if (!$comparePanel.is(e.target) && $comparePanel.has(e.target).length === 0) {
            $('body').removeClass('dreaming-wccp-show-products-list');
        }
    });
    
    // Show compare list (panel)
    function dreaming_wccp_show_products_list() {
        if (!$('.dreaming-wccp-products-list').length) {
            return;
        }
        $('body').addClass('dreaming-wccp-show-products-list');
    }

    function dreaming_init_carousel($elem) {
        $elem.not('.slick-initialized').each(function () {
            var _this = $(this),
                _responsive = _this.data('responsive'),
                _config = [];

            _config.prevArrow = '<span class="fa fa-angle-left prev"></span>';
            _config.nextArrow = '<span class="fa fa-angle-right next"></span>';

            _config.responsive = _responsive;

            _this.slick(_config);
        });
    }

    if ($('.compare-slick').length) {
        $('.compare-slick').each(function () {
            dreaming_init_carousel($(this));
        });
    }
    // Re-init some important things after ajax
    $(document).ajaxComplete(function (event, xhr, settings) {
        if ($('.compare-slick').length) {
            $('.compare-slick').each(function () {
                dreaming_init_carousel($(this));
            });
        }
    });
});