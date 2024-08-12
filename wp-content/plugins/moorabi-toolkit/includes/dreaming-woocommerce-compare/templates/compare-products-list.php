<?php
/**
 * This template can be overridden by copying it to yourtheme/dreaming-wccp/compare-products-list.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="dreaming-wccp-products-list container">
    <div class="dreaming-wccp-products-list-content">
        <div class="part-left">
            <h4 class="dreaming-wccp-title"><?php esc_html_e( 'Compare Products', 'moorabi-toolkit' ); ?></h4>
            <a href="#" class="dreaming-wccp-close"><?php esc_html_e( 'Close', 'moorabi-toolkit' ); ?></a>
        </div>
        <div class="part-right">
            {{products_list}}
            <div class="actions-wrap">
                <a href="#" data-product_id="all"
                   class="clear-all-compare-btn"><?php esc_attr_e( 'Clear', 'moorabi-toolkit' ); ?></a>
                {{go_to_compare_page}}
            </div>
        </div>
    </div>
</div>
