<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Moorabi_Pinmap_Builder' ) ) {
	class  Moorabi_Pinmap_Builder
	{
		/**
		 * @var Moorabi_Pinmap_Builder The one true Moorabi_Pinmap_Builder
		 */
		private static $instance;

		public static function instance()
		{
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Moorabi_Pinmap_Builder ) ) {
				self::$instance = new Moorabi_Pinmap_Builder;
				self::$instance->includes();
				/* Add image size for woocommerce product */
				$size_thumb = apply_filters( 'moorabi_pinmap_product_thumbnail', array(
						'width'  => 100,
						'height' => 150,
						'crop'   => true
					)
				);
				add_image_size( 'moorabi-pinmap-thumbnail', $size_thumb['width'], $size_thumb['height'], $size_thumb['crop'] );
			}

			return self::$instance;
		}

		public function includes()
		{
			require_once MOORABI_TOOLKIT_PATH . 'includes/mapper/includes/post-type.php';
			require_once MOORABI_TOOLKIT_PATH . 'includes/mapper/includes/shortcode.php';
		}
	}
}
if ( ! function_exists( 'Moorabi_Pinmap_Builder' ) ) {
	function Moorabi_Pinmap_Builder()
	{
		return Moorabi_Pinmap_Builder::instance();
	}
}
Moorabi_Pinmap_Builder();