<?php
/***
 * Core Name: WooCommerce
 * Version: 1.0.0
 * Author: 
 */
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 *
 * CUSTOM PRODUCT VIDEO, 360deg
 */
add_action( 'woocommerce_before_single_product_summary', 'moorabi_show_product_extent', 10 );
if ( !function_exists( 'moorabi_show_product_extent' ) ) {
	function moorabi_show_product_extent()
	{
		global $product;
		$product_meta = get_post_meta( $product->get_id(), '_custom_product_woo_options', true );
		if ( isset( $product_meta['product_options'] ) && $product_meta['product_options'] == 'video' && isset( $product_meta['video_product_url'] ) && $product_meta['video_product_url'] != '' ) {
			echo '<div class="product-video-button"><a href="' . esc_url( $product_meta['video_product_url'] ) . '"><span class="flaticon-play-button"></span>' . esc_html__( 'Video', 'moorabi-toolkit' ) . '</a></div>';
		}
		if ( isset( $product_meta['product_options'] ) && $product_meta['product_options'] == '360deg' && isset( $product_meta['degree_product_gallery'] ) && $product_meta['degree_product_gallery'] != '' ) : ?>
			<?php
			$images = $product_meta['degree_product_gallery'];
			$images = explode( ',', $images );
			if ( empty( $images ) ) return;
			$id               = rand( 0, 999 );
			$title            = '';
			$frames_count     = count( $images );
			$images_js_string = '';
			?>
			<div id="product-360-view" class="product-360-view-wrapper mfp-hide">
				<div class="moorabi-threed-view threed-id-<?php echo esc_attr( $id ); ?>">
					<?php if ( !empty( $title ) ): ?>
						<h3 class="threed-title"><span><?php echo esc_html( $title ); ?></span></h3>
					<?php endif ?>
					<ul class="threed-view-images">
						<?php if ( count( $images ) > 0 ): ?>
							<?php $i = 0;
							foreach ( $images as $img_id ): $i++; ?>
								<?php
								$img              = wp_get_attachment_image_src( $img_id, 'full' );
								$images_js_string .= "'" . $img[0] . "'";
								$width            = $img[1];
								$height           = $img[2];
								if ( $i < $frames_count ) {
									$images_js_string .= ",";
								}
								?>
							<?php endforeach ?>
						<?php endif ?>
					</ul>
					<div class="spinner">
						<span>0%</span>
					</div>
				</div>
				<script type="text/javascript">
                    jQuery(document).ready(function ($) {
						$('.threed-id-<?php echo esc_attr( $id ); ?>').ThreeSixty({
							totalFrames: <?php echo esc_attr( $frames_count ); ?>,
							endFrame: <?php echo esc_attr( $frames_count ); ?>,
							currentFrame: 1,
							imgList: '.threed-view-images',
							progress: '.spinner',
							imgArray: [<?php echo wp_specialchars_decode( $images_js_string ); ?>],
							height: <?php echo esc_attr( $height ); ?>,
							width: <?php echo esc_attr( $width ); ?>,
							responsive: true,
							navigation: true
						});
                    });
				</script>
			</div>
			<div class="product-360-button">
				<a href="#product-360-view"><span class="flaticon-360-degrees"></span><?php echo esc_html__( 'Degree', 'moorabi-toolkit' ); ?></a>
			</div>
		<?php
		endif;
	}
}
add_action('moorabi_post_footer', 'moorabi_share_button', 1);
add_action('woocommerce_single_product_summary', 'moorabi_share_button', 50);
if (!function_exists('moorabi_share_button')) {
	function moorabi_share_button()
	{
		$share_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
		$share_link_url = get_permalink(get_the_ID());
		$share_link_title = get_the_title();
		$share_twitter_summary = get_the_excerpt();
		$twitter = '//twitter.com/share?url=' . $share_link_url . '&text=' . $share_twitter_summary;
		$facebook = '//www.facebook.com/sharer.php?s=100&title=' . $share_link_title . '&url=' . $share_link_url;
		$pinterest = '//pinterest.com/pin/create/button/?url=' . $share_link_url . '&description=' . $share_twitter_summary . '&media=' . $share_image_url[0];
		?>
		<div class="moorabi-share-socials">
			<h5 class="social-heading"><?php echo esc_html__('Share: ', 'moorabi-toolkit') ?></h5>
			<a target="_blank" class="facebook"
			   href="<?php echo esc_url($facebook); ?>"
			   title="<?php echo esc_attr('Facebook') ?>"
			   onclick='window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'>
				<i class="fa fa-facebook-f"></i>
				<?php echo esc_html__('Facebook','moorabi-toolkit') ?>
			</a>
			<a target="_blank" class="twitter"
			   href="<?php echo esc_url($twitter); ?>"
			   title="<?php echo esc_attr('Twitter') ?>"
			   onclick='window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'>
				<i class="fa fa-twitter"></i>
				<?php echo esc_html__('Twitter','moorabi-toolkit') ?>
			</a>
			<a target="_blank" class="pinterest"
			   href="<?php echo esc_url($pinterest); ?>"
			   title="<?php echo esc_attr('Pinterest') ?>"
			   onclick='window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'>
				<i class="fa fa-pinterest-square"></i>
				<?php echo esc_html__('Pinterest','moorabi-toolkit') ?>
			</a>
		</div>
		<?php
	}
}