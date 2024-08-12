<?php
/**
 * The Template for add more products to comparision list
 * This template can be overridden by copying it to yourtheme/dreaming-wccp/add-products-form.php.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="dreaming-wccp-add-products-wrap dreaming-wccp-form-wrap dreaming-wccp-popup">
    <div class="dreaming-wccp-add-products-inner dreaming-wccp-popup-inner">
        <form name="dreaming_wccp_search_product_form" class="dreaming-wccp-search-products-form dreaming-wccp-form">
            <div class="part-top">
                <h4 class="dreaming-wccp-title"><?php esc_html_e( 'Type keywords to search', 'moorabi-toolkit' ); ?></h4>
                <div class="dreaming-wccp-input-group">
                    <input type="text" name="dreaming_wccp_search_product" class="dreaming-wccp-add-products-input" value=""
                           placeholder="<?php esc_attr_e( 'Search products', 'moorabi-toolkit' ); ?>"/>
                    <button type="submit"
                            class="dreaming-wccp-search-products-btn"><?php esc_html_e( 'Search', 'moorabi-toolkit' ); ?></button>
                </div>
            </div>
            <div class="part-bottom">
                <div class="dreaming-wccp-search-results">

                </div>
            </div>
            <a href="#" title="<?php esc_attr_e( 'Close', 'moorabi-toolkit' ); ?>"
               class="dreaming-wccp-close-popup"><?php esc_html_e( 'Close', 'moorabi-toolkit' ); ?></a>
        </form>
    </div>
</div>

