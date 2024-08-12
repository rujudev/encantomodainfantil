<?php
/**
 * The Template for products comparison page
 * This template can be overridden by copying it to yourtheme/dreaming-wccp/compare-table.php.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WPML Suppot:  Localize Ajax Call
 */
global $sitepress;

$all_settings    = Dreaming_Woocompare_Helper::get_all_settings();
$compare_fields  = Dreaming_Woocompare_Helper::get_selected_compare_fields_with_texts();
$CompareFrontend = new Dreaming_Woocompare_Frontend();
$products_list   = isset( $args['products_list'] ) ? $CompareFrontend->get_products_list( $args['products_list'] ) : $CompareFrontend->get_products_list();
$total_products  = count( $products_list );
$total_slots     = $total_products < 4 ? 4 : $total_products + 1;

$have_compare = $compare_fields && $total_products;
?>

<div class="dreaming-wccp-content-wrap">
	<?php
	if ( $have_compare ) {
		$html                 = '';
		$field_name_cols_html = '';
		$products_cols_html   = '';
		$slider_class         = 'compare-slick';
		
		$img_size = Dreaming_Woocompare_Helper::get_image_size( 'compare' );
		
		// Add more products
		$add_more_products_html = '';

		$add_more_products_html .= '<div class="dreaming-wccp-field dreaming-wccp-add-more-product-field product_0"><a href="#" class="dreaming-wccp-add-more-product"
		                           title="' . esc_attr__( 'Add more', 'moorabi-toolkit' ) . '">+</a></div>';

		$no_img_url             = Dreaming_Woocompare_Helper::no_images( $img_size );

		$field_name_cols_html .= '<div class="dreaming-wccp-field field-action"></div>';
		foreach ( $compare_fields as $field_key => $field_val ) {
			if ( $field_key == 'title' ) {
				continue;
			}

			if ( $field_key != 'image' ) {
				$field_name_cols_html   .= '<div class="dreaming-wccp-field field-' . esc_attr( $field_key ) . '">' . esc_html( $field_val ) . '</div>';
				$add_more_products_html .= '<div class="dreaming-wccp-field field-' . esc_attr( $field_key ) . ' ">' . esc_html__( '  ---  ', 'moorabi-toolkit' ) . '</div>';
			} else {
				$field_name_cols_html   .= '<div class="dreaming-wccp-field field-' . esc_attr( $field_key ) . '">' . esc_html__( 'Product', 'moorabi-toolkit' ) . '</div>';
				$add_more_products_html .= '<div class="dreaming-wccp-field field-' . esc_attr( $field_key ) . ' ">';
				$add_more_products_html .= '<div class="image-wrap dreaming-wccp-no-img"><img width="' . esc_attr( $img_size['width'] ) . '" height="' . esc_attr( $img_size['height'] ) . '" src="' . esc_url( $no_img_url ) . '"/></div>';
				$add_more_products_html .= '<h5 class="product-title"><a class="dreaming-wccp-add-more-product" href="#">' . esc_html__( 'Add products', 'moorabi-toolkit' ) . '</a></h5>';
				$add_more_products_html .= '</div>';
			}
		}

		$add_more_products_html = '<div class="dreaming-wccp-col dreaming-wccp-add-more-col">' . $add_more_products_html . '</div>';

		$add_more_products_html_tmp = $add_more_products_html;
		for ( $i = ( $total_products + 1 ); $i < $total_slots; $i ++ ) {
			$add_more_products_html .= $add_more_products_html_tmp;
		}
		
		$html .= '<div class="dreaming-wccp-left-part"><div class="field-names-col dreaming-wccp-col">' . $field_name_cols_html . '</div></div>';
		
		foreach ( $products_list as $product_id => $_product ) {
			$col_html = '';

			$col_html .= '<div class="dreaming-wccp-field field-remove product_' . $product_id . '"><a href="' . esc_url( $CompareFrontend->remove_product_url( $product_id ) ) . '"
		                           data-product_id="' . esc_attr( $product_id ) . '" class="dreaming-wccp-remove-product"
		                           title="' . esc_attr__( 'Remove', 'moorabi-toolkit' ) . '">x</a></div>';

			foreach ( $compare_fields as $field_key => $field_val ) {
				if ( $field_key == 'title' ) {
					continue;
				}
				$product_class = 'dreaming-wccp-field field-' . esc_attr( $field_key ) . ' product_' . $product_id;
				ob_start();
				
				echo '<div class="' . $product_class . '">';
				switch ( $field_key ) {
					case 'image':
						$img = Dreaming_Woocompare_Helper::resize_image( get_post_thumbnail_id( $product_id ), null, $img_size['width'], $img_size['height'], true, true, false );
						echo '<div class="image-wrap"><img width="' . esc_attr( $img['width'] ) . '" height="' . esc_attr( $img['height'] ) . '" src="' . esc_url( $img['url'] ) . '"/></div>';
						echo '<h5 class="product-title"><a href="' . esc_url( get_permalink( $product_id ) ) . '">' . esc_html( $_product->get_name() ) . '</a></h5>';
						break;
					case 'add-to-cart':
						global $product;
						$product = wc_get_product( $product_id );
						woocommerce_template_loop_add_to_cart();
						break;
					default:
						echo empty( $_product->fields[ $field_key ] ) ? '&nbsp;' : $_product->fields[ $field_key ];
						break;
				}
				echo '</div>';
				$col_html .= ob_get_clean();
			}

			
			$products_cols_html .= '<div class="dreaming-wccp-col">' . $col_html . '</div>';
		}
		
		$products_cols_html = '<div class="dreaming-wccp-right-part"><div class="products-list-cols ' . esc_attr( $slider_class ) . '" data-slick="{&quot;arrows&quot;:true,&quot;slidesMargin&quot;:0,&quot;dots&quot;:false,&quot;infinite&quot;:false,&quot;speed&quot;:300,&quot;slidesToShow&quot;:4,&quot;rows&quot;:1}"
                             data-responsive="[{&quot;breakpoint&quot;:480,&quot;settings&quot;:{&quot;slidesToShow&quot;:1,&quot;slidesMargin&quot;:&quot;0&quot;}},{&quot;breakpoint&quot;:768,&quot;settings&quot;:{&quot;slidesToShow&quot;:1,&quot;slidesMargin&quot;:&quot;0&quot;}},{&quot;breakpoint&quot;:992,&quot;settings&quot;:{&quot;slidesToShow&quot;:2,&quot;slidesMargin&quot;:&quot;0&quot;}},{&quot;breakpoint&quot;:1200,&quot;settings&quot;:{&quot;slidesToShow&quot;:3,&quot;slidesMargin&quot;:&quot;0&quot;}},{&quot;breakpoint&quot;:1500,&quot;settings&quot;:{&quot;slidesToShow&quot;:3,&quot;slidesMargin&quot;:&quot;0&quot;}}]">' . $products_cols_html . $add_more_products_html . '</div></div>';
		$html               .= $products_cols_html;
		
		echo $html;
	} // No products to compare
	else {
		?>
        <p class="compare-empty"><?php esc_html_e( 'There are no products to compare. You need to add some products to the comparison list before view.', 'moorabi-toolkit' ) ?></p>
		<?php
		if ( function_exists( 'wc_get_page_id' ) ) {
			if ( wc_get_page_id( 'shop' ) > 0 ) { ?>
                <p class="return-to-shop">
                    <a class="button wc-backward"
                       href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
						<?php esc_html_e( 'Return to shop', 'moorabi-toolkit' ); ?>
                    </a>
                </p>
			<?php }
		}
	}
	?>
</div>

