<?php
global $post;
// Get current image.
$attachment_id = get_post_meta($post->ID, 'moorabi_pinmap_image', true);
if ($attachment_id) {
    // Get image source.
    $image_src = wp_get_attachment_url($attachment_id);
}
// Get general settings.
$settings = get_post_meta($post->ID, 'moorabi_pinmap_settings', true);
$pins     = get_post_meta($post->ID, 'moorabi_pinmap_pins', true);
?>
<script type="text/html" id="moorabi_pinmap_change_tmpl">
    <div class="tabs-moorabi-pinmap">
        <a href="#" class="change-editor active" data-show=".moorabi-pinmap-wrap"
           data-hide="#postdivrich,.composer-switch,#wpb_visual_composer">
            <?php esc_html_e('PIN MAP', 'moorabi-toolkit'); ?>
        </a>
        <a href="#" class="change-editor" data-show="#postdivrich,.composer-switch,#wpb_visual_composer"
           data-hide=".moorabi-pinmap-wrap">
            <?php esc_html_e('EDITOR', 'moorabi-toolkit'); ?>
        </a>
    </div>
</script>
<script type="text/html" id="moorabi_pinmap_tmpl">
    <div class="moorabi-pinmap-wrap" style="position: relative;z-index: 999">
        <div class="moorabi-pinmap bdgr bgw">
            <div class="moorabi-pinmap-mid fc aic jcsb pd__20 bggr">
                <div class="global-setting fc aic">
                    <div id="general-settings">
                        <a href="javascript:void(0)" class="btn br__3 dib">
                            <span class="dashicons dashicons-admin-generic"></span>
                            <?php esc_html_e('General Settings', 'moorabi-toolkit'); ?>
                        </a>

                        <div class="setting-box general-setting br__3 bgw bdgr">
                            <h4 class="mg__0 pr">
                                <?php esc_html_e('General Settings', 'moorabi-toolkit'); ?>
                                <i class="close-box pa fa fa-close"></i>
                            </h4>

                            <ul class="nav mg__0 fc">
                                <li data-nav="style"
                                    class="mg__0 active"><?php esc_html_e('Style Settings', 'moorabi-toolkit'); ?></li>
                                <li data-nav="image-effect"
                                    class="mg__0"><?php esc_html_e('Image Effect', 'moorabi-toolkit'); ?></li>
                            </ul>

                            <div class="tab-content">
                                <div data-tab="style" class="tab-item">
                                    <div class="row mb__25">
                                        <div class="cm-4">
                                            <label class="db mb__10"><?php esc_html_e('Width', 'moorabi-toolkit'); ?></label>
                                            <div class="input-unit pr">
                                                <input type="number" name="popup-width"
                                                       class="input-text input-large"
                                                       value="200">
                                                <span class="pa tc">px</span>
                                            </div>
                                        </div>
                                        <div class="cm-4">
                                            <label class="db mb__10"><?php esc_html_e('Height', 'moorabi-toolkit'); ?></label>
                                            <div class="input-unit pr">
                                                <input type="number" name="popup-height" min="50"
                                                       class="input-text input-large" value="300">
                                                <span class="pa tc">px</span>
                                            </div>
                                        </div>
                                        <div class="cm-4">
                                            <label class="db mb__10"><?php esc_html_e('Popup trigger', 'moorabi-toolkit'); ?></label>
                                            <div class="select-styled pr">
                                                <select name="popup-trigger" class="slt select-large">
                                                    <option value="click"><?php esc_html_e('Click', 'moorabi-toolkit'); ?></option>
                                                    <option value="hover"><?php esc_html_e('Hover', 'moorabi-toolkit'); ?></option>
                                                    <option value="focus"><?php esc_html_e('Focus', 'moorabi-toolkit'); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="cm-4">
                                            <label class="db mb__10"><?php esc_html_e('Tooltip style', 'moorabi-toolkit'); ?></label>
                                            <div class="select-styled pr">
                                                <select name="tooltip-style" class="slt select-large">
                                                    <option value="light"><?php esc_html_e('Light', 'moorabi-toolkit'); ?></option>
                                                    <option value="dark"><?php esc_html_e('Dark', 'moorabi-toolkit'); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="cm-4">
                                            <label class="db mb__10"><?php esc_html_e('Popup box shadow', 'moorabi-toolkit'); ?></label>
                                            <div class="picker-styled pr">
                                                <input type="text" name="popup-box-shadow" class="color-picker"
                                                       data-default-color="#f0f0f0" value="#f0f0f0">
                                            </div>
                                        </div>
                                        <div class="cm-4">
                                            <label class="db mb__10"><?php esc_html_e('Popup show effect', 'moorabi-toolkit'); ?></label>
                                            <div class="select-styled pr">
                                                <select name="popup-show-effect" class="slt select-large">
                                                    <option value=""><?php esc_html_e('None', 'moorabi-toolkit'); ?></option>
                                                    <option value="fade"><?php esc_html_e('Fade In', 'moorabi-toolkit'); ?></option>
                                                    <option value="slide-left"><?php esc_html_e('Slide From Left', 'moorabi-toolkit'); ?></option>
                                                    <option value="slide-right"><?php esc_html_e('Slide From Right', 'moorabi-toolkit'); ?></option>
                                                    <option value="slide-top"><?php esc_html_e('Slide From Top', 'moorabi-toolkit'); ?></option>
                                                    <option value="slide-bottom"><?php esc_html_e('Slide From Bottom', 'moorabi-toolkit'); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="cm-4">
                                            <label class="db mb__10"><?php esc_html_e('Popup border radius', 'moorabi-toolkit'); ?></label>
                                            <div class="input-unit pr">
                                                <input type="number" name="popup-border-radius"
                                                       class="input-text input-large" value="3">
                                                <span class="pa tc">px</span>
                                            </div>
                                        </div>
                                        <div class="cm-4">
                                            <label class="db mb__10"><?php esc_html_e('Popup border width', 'moorabi-toolkit'); ?></label>
                                            <div class="input-unit pr">
                                                <input type="number" name="popup-border-width"
                                                       class="input-text input-large" value="0">
                                                <span class="pa tc">px</span>
                                            </div>
                                        </div>
                                        <div class="cm-4">
                                            <label class="db mb__10"><?php esc_html_e('Popup border color', 'moorabi-toolkit'); ?></label>
                                            <div class="picker-styled pr setting-border-color-picker">
                                                <input type="text" name="popup-border-color" class="color-picker"
                                                       data-default-color="#dfdfdf" value="#dfdfdf">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div data-tab="image-effect" class="tab-item hidden">
                                    <div class="row">
                                        <div class="cm-4">
                                            <label class="db mb__10"><?php esc_html_e('Image effect', 'moorabi-toolkit'); ?></label>
                                            <div class="select-styled pr">
                                                <select name="image-effect" class="slt select-large">
                                                    <option value="none"><?php esc_html_e('None', 'moorabi-toolkit'); ?></option>
                                                    <option value="blur"><?php esc_html_e('Blur', 'moorabi-toolkit'); ?></option>
                                                    <option value="gray"><?php esc_html_e('Gray', 'moorabi-toolkit'); ?></option>
                                                    <option value="mask"><?php esc_html_e('Mask Overlay', 'moorabi-toolkit'); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="cm-4" data-image-effect="mask">
                                            <label class="db mb__10"><?php esc_html_e('Mask color', 'moorabi-toolkit'); ?></label>
                                            <div class="picker-styled pr">
                                                <input type="text" name="mask-color" class="color-picker"
                                                       data-default-color="#000000"
                                                       value="#000000">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="moorabi-copy-shortcode">
                        <span class="shortcode-name"><?php echo esc_html__('Copy Shortcode', 'moorabi-toolkit') ?></span>
                        <input id="shortcode-syntax" class="shortcode-syntax" readonly="readonly"
                               value='[moorabi_pinmap id="<?php echo absint($post->ID); ?>"]'>
                    </div>
                </div>
            </div><!-- .moorabi-pinmap-mid -->
            <div class="moorabi-pinmap-bot pr aic fc jcc<?php echo $attachment_id ? ' pr' : ''; ?>">
                <input type="hidden" id="moorabi_pinmap_image" name="moorabi_pinmap_image"
                       value="<?php echo absint($attachment_id); ?>">
                <?php if ($attachment_id) : ?>
                    <div class="edit-image pr">
                        <a id="change-image" href="#" class="btn-change-image pa db br__3">
                            <i class="wricon-camera mr__5"></i><?php esc_html_e('Change Image', 'moorabi-toolkit'); ?>
                        </a>
                        <div class="image-wrap">
                            <img src="<?php echo esc_url($image_src); ?>">
                        </div>
                    </div>
                <?php else : ?>
                    <div class="add-image">
                        <a href="#" class="btn-add-image db tc"><i class="fa fa-upload" aria-hidden="true"></i></a>
                        <span class="empty-mapper"><?php esc_html_e('Add your image mapping', 'moorabi-toolkit'); ?></span>
                    </div>
                <?php endif; ?>
            </div><!-- .moorabi-pinmap-bot -->
        </div><!-- .moorabi-pinmap -->
    </div>
</script>

<script type="text/html" id="moorabi_pinmap_image_tmpl">
    <div class="edit-image pr">
        <a id="change-image" href="#" class="btn-change-image pa db br__3">
            <i class="fa fa-camera-retro mr__5" aria-hidden="true"></i>
            <?php esc_html_e('Change Image', 'moorabi-toolkit'); ?>
        </a>
        <div class="image-wrap">
            <img src="%URL%">
        </div>
    </div>
</script>

<script type="text/html" id="moorabi_pinmap_pin_tmpl">
    <i class="icon-pin fa fa-plus"></i>
    <div class="text__area hidden"></div>
    <a class="pin-action delete-pin" href="#" title="<?php esc_html_e('Remove Pin', 'moorabi-toolkit'); ?>"><i
                class="fa fa-close"></i></a>
    <a class="pin-action duplicate-pin" href="#" title="<?php esc_html_e('Duplicate Pin', 'moorabi-toolkit'); ?>"><i
                class="fa fa-files-o"></i></a>
    <div class="setting-box pin-setting br__3 bgw bdgr">
        <h4 class="mg__0 pr">
            <?php esc_html_e('Pin Settings', 'moorabi-toolkit'); ?>
            <i class="close-box pa fa fa-close"></i>
        </h4>

        <input type="hidden" data-option="top" value="<%= top %>">
        <input type="hidden" data-option="left" value="<%= left %>">
        <input type="hidden" data-option="settings[id]" value="">

        <ul class="nav mg__0 fc">
            <li data-nav="general" class="mg__0 active"><?php esc_html_e('General', 'moorabi-toolkit'); ?></li>
            <li data-nav="icon-settings" class="mg__0"><?php esc_html_e('Icon Settings', 'moorabi-toolkit'); ?></li>
            <li data-nav="popup-settings" class="mg__0"><?php esc_html_e('Popup Settings', 'moorabi-toolkit'); ?></li>
        </ul>

        <div class="tab-content">
            <div data-tab="general" class="tab-item">
                <div class="radio-group fc mb__25">
                    <div class="item-styled">
                        <label class="pr">
                            <input type="radio" data-option="settings[pin-type]" value="woocommerce" checked="checked">
                            <span></span>
                            <?php esc_html_e('WooCommerce', 'moorabi-toolkit'); ?>
                        </label>
                    </div>
                    <div class="item-styled">
                        <label class="pr">
                            <input type="radio" data-option="settings[pin-type]" value="image">
                            <span></span>
                            <?php esc_html_e('Image', 'moorabi-toolkit'); ?>
                        </label>
                    </div>
                    <div class="item-styled">
                        <label class="pr">
                            <input type="radio" data-option="settings[pin-type]" value="text">
                            <span></span>
                            <?php esc_html_e('Text', 'moorabi-toolkit'); ?>
                        </label>
                    </div>
                    <div class="item-styled">
                        <label class="pr">
                            <input type="radio" data-option="settings[pin-type]" value="link">
                            <span></span>
                            <?php esc_html_e('Link', 'moorabi-toolkit'); ?>
                        </label>
                    </div>
                </div>

                <!-- WooCommerce Settings -->

                <?php do_action('moorabi_pinmap_before_woocommerce_settings', $settings, $pins); ?>

                <div class="form-input mb__25" data-pin-type="woocommerce">
                    <label class="db mb__10"><?php esc_html_e('Select product', 'moorabi-toolkit'); ?></label>
                    <input type="hidden" data-option="settings[product]" class="input-text input-large product-selector"
                           value="0">
                </div>
                <div class="row mb--20" data-pin-type="woocommerce">
                    <div class="cm-3 mb__25">
                        <label class="db mb__10"><?php esc_html_e('Thumbnail Width', 'moorabi-toolkit'); ?></label>
                        <div class="input-unit pr">
                            <input type="number" data-option="settings[woo-width]"
                                   class="input-text input-large"
                                   value="300">
                            <span class="pa tc">px</span>
                        </div>
                    </div>
                    <div class="cm-3 mb__25">
                        <label class="db mb__10"><?php esc_html_e('Thumbnail Height', 'moorabi-toolkit'); ?></label>
                        <div class="input-unit pr">
                            <input type="number" data-option="settings[woo-height]"
                                   class="input-text input-large"
                                   value="300">
                            <span class="pa tc">px</span>
                        </div>
                    </div>
                </div>
                <div class="checkbox-group fc mb__25" data-pin-type="woocommerce">
                    <div class="item-styled">
                        <label class="pr">
                            <input type="hidden" data-option="settings[product-thumbnail]" value="1">
                            <input type="checkbox" onchange="jQuery(this).prev().val(this.checked ? 1 : 0);"
                                   checked="checked">
                            <span></span>
                            <?php esc_html_e('Show thumbnail', 'moorabi-toolkit'); ?>
                        </label>
                    </div>
                    <div class="item-styled">
                        <label class="pr">
                            <input type="hidden" data-option="settings[product-description]" value="1">
                            <input type="checkbox" onchange="jQuery(this).prev().val(this.checked ? 1 : 0);"
                                   checked="checked">
                            <span></span>
                            <?php esc_html_e('Show description', 'moorabi-toolkit'); ?>
                        </label>
                    </div>
                    <div class="item-styled">
                        <label class="pr">
                            <input type="hidden" data-option="settings[product-rate]" value="1">
                            <input type="checkbox" onchange="jQuery(this).prev().val(this.checked ? 1 : 0);"
                                   checked="checked">
                            <span></span>
                            <?php esc_html_e('Show rate', 'moorabi-toolkit'); ?>
                        </label>
                    </div>
                </div>

                <?php do_action('moorabi_pinmap_after_woocommerce_settings', $settings, $pins); ?>

                <!-- End WooCommerce Settings -->

                <!-- Image / Text / Link Settings -->
                <div class="form-input mb__25" data-pin-type="text|link">
                    <label class="db mb__10"><?php esc_html_e('Text style', 'moorabi-toolkit'); ?></label>
                    <select data-option="settings[text-style]" class="slt select-large" style="max-width:100%">
                        <option value=""><?php esc_html_e('Default', 'moorabi-toolkit'); ?></option>
                        <option value="text-line"><?php esc_html_e('Line', 'moorabi-toolkit'); ?></option>
                    </select>
                </div>
                <div class="form-input mb__25" data-pin-type="image|text|link">
                    <label class="db mb__10"><?php esc_html_e('Popup title', 'moorabi-toolkit'); ?></label>
                    <input type="text" data-option="settings[popup-title]" class="input-text input-large" value=""
                           placeholder="<?php esc_html_e('Input a title for the popup here...', 'moorabi-toolkit'); ?>">
                </div>

                <!-- Image Settings -->
                <div class="input-group mb__25" data-pin-type="image">
                    <label class="db mb__10"><?php esc_html_e('Select image', 'moorabi-toolkit'); ?></label>
                    <div class="pr">
                        <input type="text" class="input-image input-large" data-option="settings[image]" value="">
                        <a href="#" class="pa image-selector"><i class="fa fa-upload" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="row mb__25" data-pin-type="image|link">
                    <div class="cm-7">
                        <div class="form-input">
                            <label class="db mb__10"><?php esc_html_e('Link To', 'moorabi-toolkit'); ?></label>
                            <input type="text" data-option="settings[image-link-to]" class="input-text input-large"
                                   value="">
                        </div>
                    </div>
                    <div class="cm-5">
                        <label class="db mb__10"><?php esc_html_e('Target', 'moorabi-toolkit'); ?></label>
                        <div class="select-styled pr">
                            <select data-option="settings[image-link-target]" class="slt select-large">
                                <option value="_self"><?php esc_html_e('Default', 'moorabi-toolkit'); ?></option>
                                <option value="_blank"><?php esc_html_e('New Tab', 'moorabi-toolkit'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- End Image Settings -->

                <!-- Text Settings -->
                <div class="form-input" data-pin-type="text">
                    <label class="db mb__10"><?php esc_html_e('Text Content', 'moorabi-toolkit'); ?></label>
                    <textarea data-option="settings[text]" rows="6"
                              placeholder="<?php esc_html_e('Input some content for the popup here...', 'moorabi-toolkit'); ?>"></textarea>
                </div>
                <!-- End Text Settings -->
            </div>

            <div data-tab="icon-settings" class="tab-item hidden">
                <div class="radio-group fc mb__25">
                    <div class="item-styled">
                        <label class="pr">
                            <input type="radio" data-option="settings[icon-type]" value="icon-area" checked="checked">
                            <span></span>
                            <?php esc_html_e('Area Text', 'moorabi-toolkit'); ?>
                        </label>
                    </div>
                    <div class="item-styled">
                        <label class="pr">
                            <input type="radio" data-option="settings[icon-type]" value="icon-image">
                            <span></span>
                            <?php esc_html_e('Image', 'moorabi-toolkit'); ?>
                        </label>
                    </div>
                    <div class="item-styled">
                        <label class="pr">
                            <input type="radio" data-option="settings[icon-type]" value="icon-theme">
                            <span></span>
                            <?php esc_html_e('Theme Style', 'moorabi-toolkit'); ?>
                        </label>
                    </div>
                </div>
                <hr>
                <div class="input-group mb__25" data-icon-type="icon-image">
                    <label class="db mb__10"><?php esc_html_e('Select image', 'moorabi-toolkit'); ?></label>
                    <div class="pr">
                        <input type="text" class="input-image input-large" data-option="settings[image-template]"
                               value="">
                        <a href="#" class="pa image-selector"><i class="fa fa-upload" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div data-icon-type="icon-area">
                    <div class="row mb__20">
                        <div class="cm-6 mb__25">
                            <label class="db mb__10"><?php esc_html_e('Text', 'moorabi-toolkit'); ?></label>
                            <input type="text" data-option="settings[area-text]"
                                   class="text-custom input-text input-large" value="">
                        </div>
                        <div class="cm-3 mb__25">
                            <label class="db mb__10"><?php esc_html_e('Font Size', 'moorabi-toolkit'); ?></label>
                            <div class="input-unit pr">
                                <input type="number" data-option="settings[area-text-size]"
                                       class="input-text input-large"
                                       value="13">
                                <span class="pa tc">px</span>
                            </div>
                        </div>
                        <div class="cm-3 mb__25">
                            <label class="db mb__10"><?php esc_html_e('Line Height', 'moorabi-toolkit'); ?></label>
                            <div class="input-unit pr">
                                <input type="number" data-option="settings[area-text-line-height]"
                                       class="input-text input-large"
                                       value="32">
                                <span class="pa tc">px</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb--20">
                        <div class="cm-3 mb__25">
                            <label class="db mb__10"><?php esc_html_e('Width', 'moorabi-toolkit'); ?></label>
                            <div class="input-unit pr">
                                <input type="number" data-option="settings[area-width]"
                                       class="input-text input-large"
                                       value="32">
                                <span class="pa tc">px</span>
                            </div>
                        </div>
                        <div class="cm-3 mb__25">
                            <label class="db mb__10"><?php esc_html_e('Height', 'moorabi-toolkit'); ?></label>
                            <div class="input-unit pr">
                                <input type="number" data-option="settings[area-height]"
                                       class="input-text input-large"
                                       value="32">
                                <span class="pa tc">px</span>
                            </div>
                        </div>
                        <div class="cm-3 mb__25">
                            <label class="db mb__10"><?php esc_html_e('Border Width', 'moorabi-toolkit'); ?></label>
                            <div class="input-unit pr">
                                <input type="number" data-option="settings[area-border-width]"
                                       class="input-text input-large"
                                       value="0">
                                <span class="pa tc">px</span>
                            </div>
                        </div>
                        <div class="cm-3 mb__25">
                            <label class="db mb__10"><?php esc_html_e('Border Radius', 'moorabi-toolkit'); ?></label>
                            <div class="input-unit pr">
                                <input type="number" data-option="settings[area-border-radius]"
                                       class="input-text input-large"
                                       value="50">
                                <span class="pa tc">px</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="cm-4 mb__25">
                            <label class="db mb__10"><?php esc_html_e('Background', 'moorabi-toolkit'); ?></label>
                            <div class="picker-styled pr">
                                <input type="text" data-option="settings[area-bg-color]" class="w__150 color-picker"
                                       data-default-color="#71c0ef" value="#71c0ef">
                            </div>
                        </div>
                        <div class="cm-4 mb__25">
                            <label class="db mb__10"><?php esc_html_e('Border Color', 'moorabi-toolkit'); ?></label>
                            <div class="picker-styled pr">
                                <input type="text" data-option="settings[area-border-color]" class="w__150 color-picker"
                                       data-default-color="#000000" value="#000000">
                            </div>
                        </div>
                        <div class="cm-4 mb__25">
                            <label class="db mb__10"><?php esc_html_e('Text Color', 'moorabi-toolkit'); ?></label>
                            <div class="picker-styled pr">
                                <input type="text" data-option="settings[area-text-color]" class="color-picker"
                                       data-default-color="#ffffff" value="#ffffff">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div data-tab="popup-settings" class="tab-item hidden">
                <div class="row">
                    <div class="cm-4">
                        <label class="db mb__10"><?php esc_html_e('Width', 'moorabi-toolkit'); ?></label>
                        <div class="input-unit pr">
                            <input type="number" data-option="settings[popup-width]" class="input-text input-large"
                                   value="">
                            <span class="pa tc">px</span>
                        </div>
                    </div>
                    <div class="cm-4">
                        <label class="db mb__10"><?php esc_html_e('Height', 'moorabi-toolkit'); ?></label>
                        <div class="input-unit pr">
                            <input type="number" data-option="settings[popup-height]" class="input-text input-large"
                                   value="">
                            <span class="pa tc">px</span>
                        </div>
                    </div>
                    <div class="cm-4">
                        <label class="db mb__10"><?php esc_html_e('Position', 'moorabi-toolkit'); ?></label>
                        <div class="select-styled pr">
                            <select data-option="settings[popup-position]" class="slt select-large">
                                <option value="right"><?php esc_html_e('Right', 'moorabi-toolkit'); ?></option>
                                <option value="left"><?php esc_html_e('Left', 'moorabi-toolkit'); ?></option>
                                <option value="top"><?php esc_html_e('Top', 'moorabi-toolkit'); ?></option>
                                <option value="bottom"><?php esc_html_e('Bottom', 'moorabi-toolkit'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>
<?php $fonts = array(
    array('fa-glass' => 'Fa Glass'),
    array('fa-music' => 'Fa Music'),
    array('fa-search' => 'Fa Search'),
); ?>
<script type="text/html" id="moorabi_pinmap_icon_selector_tmpl">
    <div class="icon-selector select-styled pr bdgr br__3">
        <div class="icon-selected"><i class="fa fa-%SELECTED%"></i></div>
        <div class="icon-wrap pa bdgr">
            <h5><?php esc_html_e('Select icon', 'moorabi-toolkit'); ?><i class="close fa fa-close"></i></h5>
            <div class="moorabi-icon-list bgw bdgr fc fcw">
                <?php foreach ($fonts as $font => $key):
                    $class_key = key($key);
                    ?>
                    <a data-value="<?php echo esc_attr($class_key); ?>" href="#">
                        <i class="fa <?php echo esc_attr($class_key); ?>"></i>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</script>

<script type="text/javascript">
    jQuery(document).ready(
        function ($) {
            // Override default UI.
            var form   = $($('#moorabi_pinmap_tmpl').text()).prepend($('#post').children('input[type="hidden"]')),
                editor = $('#moorabi_pinmap_change_tmpl').text();

            $('#screen-meta, #screen-meta-links').remove();

            $('#post-body > div#post-body-content #titlediv').after(form).before(editor);

            $('.tabs-moorabi-pinmap .change-editor').on('click', function () {
                var $this    = $(this),
                    $show    = $this.data('show'),
                    $hide    = $this.data('hide'),
                    $content = $this.closest('#post-body');

                $content.find($show).show();
                $content.find($hide).hide();
                $this.addClass('active').siblings().removeClass('active');
                $(window).trigger('resize');
            });

            // Trigger event to initialize application.
            setTimeout(function () {
                $(document).trigger('init_moorabi_pinmap');
            }, 500);

            // Pass data to client-side.
            window.moorabi_pinmap_settings = <?php echo json_encode($settings ? $settings : new stdClass()); ?>;
            window.moorabi_pinmap_pins     = <?php echo json_encode($pins ? array_values($pins) : array()); ?>;
        }
    );
</script>
