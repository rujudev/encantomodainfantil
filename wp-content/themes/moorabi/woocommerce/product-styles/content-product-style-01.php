<?php
/**
 * Name: Product style 01
 * Slug: content-product-style-01
 **/
?>
<div class="product-inner">
    <div class="product-thumb">
        <?php
        /**
         * woocommerce_before_shop_loop_item_title hook.
         *
         * @hooked moorabi_woocommerce_group_flash - 10
         * @hooked moorabi_template_loop_product_thumbnail - 10
         */
        do_action('woocommerce_before_shop_loop_item_title');
        ?>
        <div class="group-button">
            <?php
            do_action('moorabi_function_shop_loop_item_wishlist');
            do_action('dreaming_wccp_shop_loop');
            ?>
            <div class="add-to-cart">
                <?php
                /**
                 * woocommerce_after_shop_loop_item hook.
                 *
                 * @removed woocommerce_template_loop_product_link_close - 5
                 * @hooked woocommerce_template_loop_add_to_cart - 10
                 */
                do_action('woocommerce_after_shop_loop_item');
                ?>
            </div>
        </div>
    </div>
    <div class="product-info equal-elem">
        <?php
        /**
         * woocommerce_shop_loop_item_title hook.
         *
         * @hooked moorabi_template_loop_product_title - 10
         */
        do_action('woocommerce_shop_loop_item_title');
        /**
         * woocommerce_after_shop_loop_item_title hook.
         *
         * @hooked woocommerce_template_loop_rating - 5
         * @hooked woocommerce_template_loop_price - 10
         */
        do_action('woocommerce_after_shop_loop_item_title');
        ?>
    </div>
</div>
