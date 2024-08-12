/**
 * Alpha Color Picker JS
 */

(function ($) {

    //
    // WP Color Picker
    //
    if (typeof Color === 'function') {

        Color.prototype.toString = function () {

            if (this._alpha < 1) {
                return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
            }

            var hex = parseInt(this._color, 10).toString(16);

            if (this.error) {
                return '';
            }

            if (hex.length < 6) {
                for (var i = 6 - hex.length - 1; i >= 0; i--) {
                    hex = '0' + hex;
                }
            }

            return '#' + hex;

        };

    }

    var parse_color = function (color) {

        var value = color.replace(/\s+/g, ''),
            trans = (value.indexOf('rgba') !== -1) ? parseFloat(value.replace(/^.*,(.+)\)/, '$1') * 100) : 100,
            rgba  = (trans < 100) ? true : false;

        return {value: value, transparent: trans, rgba: rgba};

    };

    $.fn.moorabiColorPicker = function () {
        return this.each(function () {

            var $input        = $(this),
                picker_color  = parse_color($input.val()),
                palette_color = true,
                $container;

            // Destroy and Reinit
            if ($input.hasClass('wp-color-picker')) {
                $input.closest('.wp-picker-container').after($input).remove();
            }

            $input.wpColorPicker({
                palettes: palette_color,
                change  : function (event, ui) {

                    var ui_color_value = ui.color.toString();

                    $container.removeClass('moorabi--transparent-active');
                    $container.find('.moorabi--transparent-offset').css('background-color', ui_color_value);
                    $input.val(ui_color_value).trigger('change');

                },
                create  : function () {

                    $container = $input.closest('.wp-picker-container');

                    var a8cIris             = $input.data('a8cIris'),
                        $transparent_wrap   = $('<div class="moorabi--transparent-wrap">' +
                                                '<div class="moorabi--transparent-slider"></div>' +
                                                '<div class="moorabi--transparent-offset"></div>' +
                                                '<div class="moorabi--transparent-text"></div>' +
                                                '<div class="moorabi--transparent-button">transparent <i class="fas fa-toggle-off"></i></div>' +
                                                '</div>').appendTo($container.find('.wp-picker-holder')),
                        $transparent_slider = $transparent_wrap.find('.moorabi--transparent-slider'),
                        $transparent_text   = $transparent_wrap.find('.moorabi--transparent-text'),
                        $transparent_offset = $transparent_wrap.find('.moorabi--transparent-offset'),
                        $transparent_button = $transparent_wrap.find('.moorabi--transparent-button');

                    if ($input.val() === 'transparent') {
                        $container.addClass('moorabi--transparent-active');
                    }

                    $transparent_button.on('click', function () {
                        if ($input.val() !== 'transparent') {
                            $input.val('transparent').trigger('change').removeClass('iris-error');
                            $container.addClass('moorabi--transparent-active');
                        } else {
                            $input.val(a8cIris._color.toString()).trigger('change');
                            $container.removeClass('moorabi--transparent-active');
                        }
                    });

                    $transparent_slider.slider({
                        value : picker_color.transparent,
                        step  : 1,
                        min   : 0,
                        max   : 100,
                        slide : function (event, ui) {

                            var slide_value       = parseFloat(ui.value / 100);
                            a8cIris._color._alpha = slide_value;
                            $input.wpColorPicker('color', a8cIris._color.toString());
                            $transparent_text.text((slide_value === 1 || slide_value === 0 ? '' : slide_value));

                        },
                        create: function () {

                            var slide_value = parseFloat(picker_color.transparent / 100),
                                text_value  = slide_value < 1 ? slide_value : '';

                            $transparent_text.text(text_value);
                            $transparent_offset.css('background-color', picker_color.value);

                            $container.on('click', '.wp-picker-clear', function () {

                                a8cIris._color._alpha = 1;
                                $transparent_text.text('');
                                $transparent_slider.slider('option', 'value', 100);
                                $container.removeClass('moorabi--transparent-active');
                                $input.trigger('change');

                            });

                            $container.on('click', '.wp-picker-default', function () {

                                var default_color = parse_color($input.data('default-color')),
                                    default_value = parseFloat(default_color.transparent / 100),
                                    default_text  = default_value < 1 ? default_value : '';

                                a8cIris._color._alpha = default_value;
                                $transparent_text.text(default_text);
                                $transparent_slider.slider('option', 'value', default_color.transparent);

                            });

                        }
                    });
                }
            });

        });
    };

}(jQuery));