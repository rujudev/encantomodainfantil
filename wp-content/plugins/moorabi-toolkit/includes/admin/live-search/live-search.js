(function ($) {
    "use strict"; // Start of use strict
    
    $.fn.donetyping = function (callback) {
        var _this = $(this);
        var x_timer;
        _this.keyup(function () {
            clearTimeout(x_timer);
            x_timer = setTimeout(clear_timer, 1000);
        });
        
        function clear_timer() {
            clearTimeout(x_timer);
            callback.call(_this);
        }
    }
    
    $(document).ready(function () {
        var typingTimer;                // timer identifier
        var liveSearchAjax = null;
        
        $(document).on('keydown', '.moorabi-live-search-form .txt-livesearch', function () {
            if (liveSearchAjax != null) {
                liveSearchAjax.abort();
            }
        });
        
        $('.moorabi-live-search-form .txt-livesearch').donetyping(function (callback) {
            var _this = $(this),
                container = _this.closest('.moorabi-live-search-form'),
                keyword = _this.val(),
                product_cat = _this.closest('.moorabi-live-search-form').find('select[name="product_cat"]').val();
            
            if (typeof product_cat === "undefined" || product_cat == 0) {
                product_cat = '';
            }
            if (keyword.length < moorabi_ajax_live_search.moorabi_live_search_min_characters) {
                if (!container.find('.results-search .products-search').length) {
                    container.find('.results-search').append('<div class="products-search"></div>');
                }
                container.find('.products-search').html('<div class="product-search-item">' + moorabi_ajax_live_search['limit_char_message'] + '</div>');
                container.removeClass('loading');
                return false;
            }
            
            var data = {
                action: 'moorabi_live_search',
                security: moorabi_ajax_live_search.security,
                keyword: keyword,
                product_cat: product_cat
            };
            
            container.addClass('loading');
            container.find('.suggestion-search-data').remove();
            container.find('.not-results-search').remove();
            container.find('.products-search').remove();
            
            liveSearchAjax = $.ajax({
                type: 'POST',
                url: moorabi_ajax_live_search.ajaxurl,
                data: data,
                dataType: 'json',
                beforeSend: function () {
                    if (liveSearchAjax != null) {
                        liveSearchAjax.abort();
                    }
                },
                success: function (response) {
                    container.removeClass('loading');
                    
                    // Prepare response.
                    if (response.message) {
                        container.find('.results-search').append('<div class="not-results-search">' + response.message + '</div>');
                    } else {
                        container.find('.results-search').append('<div class="products-search"></div>');
                        // Show suggestion.
                        if (response.suggestion) {
                            container.find('.results-search').append('<div class="suggestion-search suggestion-search-data">' + response.suggestion + '</div>');
                        }
                        
                        // Show results.
                        $.each(response.list_product, function (key, value) {
                            container.find('.products-search').append('<div class="product-search-item"><div class="product-image">' + value.image + '</div><div class="product-title-price"><div class="product-title"><a class="mask-link" href="' + value.url + '">' + value.title.replace(new RegExp('(' + keyword + ')', 'ig'), '<span class="keyword-current">$1</span>') + '</a></div><div class="product-price">' + value.price + '</div></div></div>');
                        });
                    }
                }
            });
        });
        
        $('body').on('focus', '.moorabi-live-search-form .txt-livesearch', function () {
            var container = $(this).closest('.moorabi-live-search-form');
            container.removeClass('loading');
            container.find('.suggestion-search-data').show();
            container.find('.not-results-search').show();
            container.find('.products-search').show();
        });
        
        $('body').on('blur', '.moorabi-live-search-form .txt-livesearch', function () {
            var container = $(this).closest('.moorabi-live-search-form');
            container.removeClass('loading');
            container.find('.suggestion-search-data').hide();
            container.find('.not-results-search').hide();
            container.find('.products-search').fadeOut(300);
        });
        
        $('body').on('click', '.moorabi-live-search-form .view-all', function () {
            var _this = $(this);
            var parent = _this.closest('.moorabi-live-search-form ').submit();
        });
        
        $(document).click(function (event) {
            var container = $(event.target).closest(".moorabi-live-search-form")
            if (container.length <= 0) {
                container.hide();
            }
        });
        
    });
})(jQuery); // End of use strict