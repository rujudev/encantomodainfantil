<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WooCommerce' ) ) {
	echo '<div class="notice notice-error"><p>' . esc_html__( 'This plugin required WooCommerce installed and activate', 'moorabi-toolkit' ) . '</p></div>';
	
	return;
}

$tabs_args = array(
	'settings'        => esc_html__( 'General Settings', 'moorabi-toolkit' ),
	'compare-table'   => esc_html__( 'Compare Table', 'moorabi-toolkit' ),
	'import-export'   => esc_html__( 'Import/Export', 'moorabi-toolkit' )
);

$active_tab = 'settings';
if ( isset( $_REQUEST['tab'] ) ) {
	if ( array_key_exists( $_REQUEST['tab'], $tabs_args ) ) {
		$active_tab = Dreaming_Woocompare_Helper::clean( $_REQUEST['tab'] );
	}
}

$tab_head_html = '';
foreach ( $tabs_args as $tab_id => $tab_name ) {
	$nav_class     = $tab_id == $active_tab ? 'nav-tab nav-tab-active' : 'nav-tab';
	$tab_head_html .= '<a data-tab_id="' . esc_attr( $tab_id ) . '" href="?page=dreaming-wccp&tab=' . esc_attr( $tab_id ) . '" class="' . $nav_class . '">' . $tab_name . '</a>';
}

$all_settings     = Dreaming_Woocompare_Helper::get_all_settings();
?>

<div class="wrap">
    <h1><?php esc_html_e( 'Dreaming Compare Settings', 'moorabi-toolkit' ); ?></h1>

    <div class="dreaming-wccp-admin-page-content-wrap">
        <div class="dreaming-wccp-tabs dreaming-all-settings-form">
            <h2 class="nav-tab-wrapper"><?php echo $tab_head_html; ?></h2>

            <div id="settings" class="dreaming-wccp-tab-content tab-content">
                <div class="dreaming-wccp-tab-content-inner">
                    <table>
                        <tr>
                            <th>
                                <label><?php esc_html_e( 'Compare Page', 'moorabi-toolkit' ) ?></label>
                            </th>
                            <td>
								<?php Dreaming_Woocompare_Helper::all_pages_select_html( $all_settings['compare_page'], 'dreaming-wccp-field', 'compare_page', 'compare-page' ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label><?php esc_html_e( 'Show button in products list', 'moorabi-toolkit' ) ?></label>
                            </th>
                            <td>
                                <label for="show-in-products-list">
                                    <input name="show_in_products_list"
                                           id="show-in-products-list" type="checkbox" class="dreaming-wccp-field"
                                           value="yes"
										<?php checked( $all_settings['show_in_products_list'], 'yes' ); ?>> <?php esc_html_e( 'Check it if you want to show the button in the products list', 'moorabi-toolkit' ); ?>
                                </label>
                                <br>
                                <p>
                                    <label for="products-loop-hook"><?php esc_html_e( 'Products List Hook', 'moorabi-toolkit' ) ?></label>
                                </p>
								<?php Dreaming_Woocompare_Helper::all_products_list_hooks_select_html( $all_settings['products_loop_hook'], 'dreaming-wccp-field', 'products_loop_hook', 'products-loop-hook' ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label><?php esc_html_e( 'Show button in single product', 'moorabi-toolkit' ) ?></label>
                            </th>
                            <td>
                                <label for="show-in-single-product">
                                    <input name="show_in_single_product"
                                           id="show-in-single-product" type="checkbox" class="dreaming-wccp-field"
                                           value="yes"
										<?php checked( $all_settings['show_in_single_product'], 'yes' ); ?>> <?php esc_html_e( 'Check it if you want to show the button in the single product page', 'moorabi-toolkit' ); ?>
                                </label>
                                <br>
                                <p>
                                    <label for="single-product-hook"><?php esc_html_e( 'Single Product Hook', 'moorabi-toolkit' ) ?></label>
                                </p>
								<?php Dreaming_Woocompare_Helper::all_single_product_hooks_select_html( $all_settings['single_product_hook'], 'dreaming-wccp-field', 'single_product_hook', 'single-product-hook' ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><label><?php esc_html_e( 'Compare bottom panel', 'moorabi-toolkit' ) ?></label>
                            </th>
                            <td>
                                <label for="show-compare-panel">
                                    <input name="show_compare_panel"
                                           id="show-compare-panel" type="checkbox" class="dreaming-wccp-field"
                                           value="yes"
										<?php checked( $all_settings['show_compare_panel'], 'yes' ); ?>> <?php esc_html_e( 'Check it if you want to show the panel when adding products to the comparison', 'moorabi-toolkit' ); ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th><label><?php esc_html_e( 'Panel image size', 'moorabi-toolkit' ) ?></label>
                            </th>
                            <td>
                                <div class="input-group">
                                    <input type="number" name="panel_img_size_w" id="panel-img-size-w"
                                           class="panel-img-size-w dreaming-wccp-field dreaming-wccp-field-small" step="1"
                                           min="0"
                                           value="<?php echo esc_attr( $all_settings['panel_img_size_w'] ); ?>"/>
                                    x
                                    <input type="number" name="panel_img_size_h" id="panel-img-size-h"
                                           class="panel-img-size-h dreaming-wccp-field dreaming-wccp-field-small" step="1"
                                           min="0"
                                           value="<?php echo esc_attr( $all_settings['panel_img_size_h'] ); ?>"/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th><label><?php esc_html_e( 'Compare page image size', 'moorabi-toolkit' ) ?></label>
                            </th>
                            <td>
                                <div class="input-group">
                                    <input type="number" name="compare_img_size_w" id="compare-img-size-w"
                                           class="compare-img-size-w dreaming-wccp-field dreaming-wccp-field-small" step="1"
                                           min="0"
                                           value="<?php echo esc_attr( $all_settings['compare_img_size_w'] ); ?>"/>
                                    x
                                    <input type="number" name="compare_img_size_h" id="compare-img-size-h"
                                           class="compare-img-size-h dreaming-wccp-field dreaming-wccp-field-small" step="1"
                                           min="0"
                                           value="<?php echo esc_attr( $all_settings['compare_img_size_h'] ); ?>"/>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div id="compare-table" class="dreaming-wccp-tab-content tab-content">
                <div class="dreaming-wccp-tab-content-inner">
                    <table>
                        <tr>
                            <th>
                                <label><?php esc_html_e( 'Compare what?', 'moorabi-toolkit' ) ?></label>
                            </th>
                            <td>
                                <p class="description"><?php esc_html_e( 'Select the fields to show in the comparison table and order them by drag&drop (are included also the woocommerce attributes)', 'moorabi-toolkit' ); ?></p>
								<?php echo Dreaming_Woocompare_Helper::compare_admin_fields_cb_html(); ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div id="import-export" class="dreaming-wccp-tab-content tab-content">
                <div class="dreaming-wccp-tab-content-inner">
                    <h3><?php esc_html_e( 'Export Settings', 'moorabi-toolkit' ); ?></h3>
                    <a href="<?php echo dreaming_wccp_export_settings_link(); ?>" target="_blank"
                       class="button dreaming-wccp-export-settings"><?php esc_html_e( 'Export Settings', 'moorabi-toolkit' ); ?></a>
                    <h3><?php esc_html_e( 'Import Settings', 'moorabi-toolkit' ); ?></h3>
                    <div class="dreaming-wccp-import-settings-wrap">
                        <form action="<?php echo dreaming_wccp_import_settings_action_link(); ?>"
                              name="dreaming_wccp_import_settings_form" method="post"
                              enctype="multipart/form-data">
                            <label><?php esc_html_e( 'Select json file:', 'moorabi-toolkit' ); ?></label>
                            <input type="file" name="dreaming_wccp_import_file" id="dreaming_wccp_import_file">
                            <button type="submit"
                                    class="button"><?php esc_html_e( 'Upload And Import', 'moorabi-toolkit' ); ?></button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <button type="button"
                class="button-primary dreaming-wccp-save-all-settings"><?php esc_html_e( 'Save All Settings', 'moorabi-toolkit' ); ?></button>
    </div>

</div>