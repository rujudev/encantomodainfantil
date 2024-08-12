<?php
/**
 * WooCommerce API Products Details Class
 * 
 * Adds a /details  and /detailsendpoint to WooCommerce API
 *
 */
if ( !defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}
if ( !class_exists( 'WC_API_Products_Details' ) ) {
        class WC_API_Products_Details extends WC_API_Products {
                /**
                 * @var string $base the route base
                 */
                protected $base = '/products';
                /**
                 * Register the routes with callback for this class
                 * @param array $routes
                 * @return array
                 */
		function register_routes( $routes ) {

			# GET products/details
			$routes[ $this->base. '/details' ] = array(
				array( array( $this, 'get_products_list' ), WC_API_Server::READABLE )
			);

			# GET products/details/ids
			$routes[ $this->base. '/details/(?P<id>\d+)' ] = array(
				array( array( $this, 'get_product_list' ), WC_API_Server::READABLE )
			);
			# GET products/<id>/attributes
			$routes[ $this->base. '/(?P<id>\d+)/attributes' ] = array(
				array( array( $this, 'get_attribute' ), WC_API_Server::READABLE )
			);

			return $routes;
		}
		function get_attribute($id,$fields=null) {
			try {
				global $wpdb;
				$product = wc_get_product( $id );
				$attribute_list = array();
				$attr = get_post_meta($product->id, '_product_attributes', true); 
				foreach($attr as $attrs){
					$attribute = $wpdb->get_row( $wpdb->prepare( "
						SELECT attribute_id
						FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
						WHERE attribute_name = %s
					 ",str_replace( 'pa_', '', $attrs['name'] ) ) );
					if ( $attrs['is_taxonomy'] ) {
						$options = explode( ',', $product->get_attribute( $attrs['name'] ) );
					} else {
						$options = explode( '|', $product->get_attribute( $attrs['name'] ) );
					}
					if ( ! $attribute ) {
				throw new WC_API_Exception( 'woocommerce_api_product_attribute', __( 'This Product id has no attributes', 'woocommerce' ), 401 );
			}

					$attribute_list[] = array(
						'id'=>intval( $attribute->attribute_id ),
						'name'      => wc_attribute_label( $attrs['name'] ),
						'slug'      => str_replace( 'pa_', '', $attrs['name'] ),
						'position'  => (int) $attrs['position'],
						'visible'   => (bool) $attrs['is_visible'],
						'variation' => (bool) $attrs['is_variation'],
						'options'   => array_map( 'trim', $options ),
					);
			
				}
			return $attribute_list;
			} catch ( WC_API_Exception $e ) {
			return new WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
		}
	
	}

		function get_products_list( $fields = null, $type = null, $filter = array(), $page = 1 ) {

							if ( ! empty( $type ) ) {
							    $filter['type'] = $type;
							}

							$filter['page'] = $page;

							$query = $this->query_products_list( $filter );

							$products_list = array();

							foreach ( $query->posts as $product_id ) {

							    if ( ! $this->is_readable( $product_id ) ) {
								continue;
							    }

							    $products_list[] = current( $this->get_product_list( $product_id, $fields ) );
							}

							$this->server->add_pagination_headers( $query );

							return ($products_list);
					    }
		public function get_combinations( $product ) {
			$combinations = array();
			try {

			if ( $product->is_type( 'variation' ) ) {

				// variation attributes
				foreach ( $product->get_variation_attributes() as $attribute_name => $attribute ) {
					 global $wpdb;
					
					$term_id = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `wp_term_taxonomy` AS e INNER JOIN `wp_terms` AS u ON e.term_id = u.term_id INNER JOIN `wp_woocommerce_attribute_taxonomies` AS v ON v.attribute_name = REPLACE(e.taxonomy, 'pa_', '') WHERE u.slug = %s",$attribute ) );

			if ( ! $term_id ) {
				throw new WC_API_Exception( 'woocommerce_api_product_combination', __( 'This Product id has no combinations', 'woocommerce' ), 401 );
			}

					// taxonomy-based attributes are prefixed with `pa_`, otherwise simply `attribute_`
					$combinations[] = array(
						'id' =>intval($term_id->term_id ),
						'attribute_id' => intval($term_id->attribute_id),
						'name'   => wc_attribute_label( str_replace( 'attribute_', '', $attribute_name ) ),
						'slug'   => str_replace( 'attribute_', '', str_replace( 'pa_', '', $attribute_name ) ),
						'option' => $attribute,
					);
				}
	}	
				return $combinations;
			
			} catch ( WC_API_Exception $e ) {
			return new WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
		}

		
	}		

	public function get_attributes( $product ) {

		$attributes = array();

		if ( $product->is_type( 'variation' ) ) {

			// variation attributes
			foreach ( $product->get_variation_attributes() as $attribute_name => $attribute ) {
				 global $wpdb;
					
				$term_id = $wpdb->get_row( $wpdb->prepare( "
						SELECT term_id
						FROM wp_terms
						WHERE slug = %s
					 ",  $attribute ) );



				// taxonomy-based attributes are prefixed with `pa_`, otherwise simply `attribute_`
				$attributes[] = array(
					'id' =>intval($term_id->term_id ),
					'name'   => wc_attribute_label( str_replace( 'attribute_', '', $attribute_name ) ),
					'slug'   => str_replace( 'attribute_', '', str_replace( 'pa_', '', $attribute_name ) ),
					'option' => $attribute,
				);
			}

		} else {

			foreach ($this->get_product_attributes($product) as $attribute ) {

				// taxonomy-based attributes are comma-separated, others are pipe (|) separated
				if ( $attribute['is_taxonomy'] ) {
					$options = explode( ',', $product->get_attributes( $attribute['name'] ) );
				} else {
					$options = explode( '|', $product->get_attributes( $attribute['name'] ) );
				}

				$attributes[] = array(
					'name'      => wc_attribute_label( $attribute['name'] ),
					'slug'      => str_replace( 'pa_', '', $attribute['name'] ),
					'position'  => (int) $attribute['position'],
					'visible'   => (bool) $attribute['is_visible'],
					'variation' => (bool) $attribute['is_variation'],
					'options'   => array_map( 'trim', $options ),
				);
			}
		}

		return $attributes;
	}
		/**
	     	* Get the product list for the given ID
	     	*
		* @param int $id the product ID
		* @param string $fields
		* @return array
		*/
		function get_product_list( $id, $fields = null ) {

			$id = $this->validate_request( $id, 'product', 'read' );

			if ( is_wp_error( $id ) ) {
			    return $id;
						}

		$product = wc_get_product( $id );

		// add data that applies to every product type
		$product_data = $this->get_product_list_data( $product );

		// add variations to variable products
		if ( $product->is_type( 'variable' ) && $product->has_child() ) {
		    
		    $product_data['variations'] = $this->get_variation_data( $product );
										}

		// add the parent product data to an individual variation
		if ( $product->is_type( 'variation' ) ) {
		    
		    $product_data['parent'] = $this->get_product_list_data( $product->parent );
							}

		return array( 'product' => apply_filters( 'woocommerce_api_product_response', $product_data, $product, $fields, $this->server ) );
	    }
		/**
	     	* Get standard product data that applies to every product type
	     	*
	     	* @param WC_Product $product
	     	* @return WC_Product
	     	*/
		function get_product_list_data( $product ) {
			$prices_precision = wc_get_price_decimals();
			return array(			'title'              => $product->get_title(),
			'id'                 => (int) $product->is_type( 'variation' ) ? $product->get_variation_id() : $product->id,
			'created_at'         => $this->server->format_datetime( $product->get_post_data()->post_date_gmt ),
			'updated_at'         => $this->server->format_datetime( $product->get_post_data()->post_modified_gmt ),
			'type'               => $product->product_type,
			'status'             => $product->get_post_data()->post_status,
			'downloadable'       => $product->is_downloadable(),
			'virtual'            => $product->is_virtual(),
			'permalink'          => $product->get_permalink(),
			'sku'                => $product->get_sku(),
			'price'              => wc_format_decimal( $product->get_price(), $prices_precision ),
			'regular_price'      => wc_format_decimal( $product->get_regular_price(), $prices_precision ),
			'sale_price'         => $product->get_sale_price() ? wc_format_decimal( $product->get_sale_price(), $prices_precision ) : null,
			'price_html'         => $product->get_price_html(),
			'taxable'            => $product->is_taxable(),
			'tax_status'         => $product->get_tax_status(),
			'tax_class'          => $product->get_tax_class(),
			'managing_stock'     => $product->managing_stock(),
			'stock_quantity'     => $product->get_stock_quantity(),
			'in_stock'           => $product->is_in_stock(),
			'backorders_allowed' => $product->backorders_allowed(),
			'backordered'        => $product->is_on_backorder(),
			'sold_individually'  => $product->is_sold_individually(),
			'purchaseable'       => $product->is_purchasable(),
			'featured'           => $product->is_featured(),
			'visible'            => $product->is_visible(),
			'catalog_visibility' => $product->visibility,
			'on_sale'            => $product->is_on_sale(),
			'weight'             => $product->get_weight() ? wc_format_decimal( $product->get_weight(), 2 ) : null,
			'dimensions'         => array(
				'length' => $product->length,
				'width'  => $product->width,
				'height' => $product->height,
				'unit'   => get_option( 'woocommerce_dimension_unit' ),
			),
			'shipping_required'  => $product->needs_shipping(),
			'shipping_taxable'   => $product->is_shipping_taxable(),
			'shipping_class'     => $product->get_shipping_class(),
			'shipping_class_id'  => ( 0 !== $product->get_shipping_class_id() ) ? $product->get_shipping_class_id() : null,
			'description'        => wpautop( do_shortcode( $product->get_post_data()->post_content ) ),
			'short_description'  => apply_filters( 'woocommerce_short_description', $product->get_post_data()->post_excerpt ),
			'reviews_allowed'    => ( 'open' === $product->get_post_data()->comment_status ),
			'average_rating'     => wc_format_decimal( $product->get_average_rating(), 2 ),
			'rating_count'       => (int) $product->get_rating_count(),
			'related_ids'        => array_map( 'absint', array_values( $product->get_related() ) ),
			'upsell_ids'         => array_map( 'absint', $product->get_upsells() ),
			'cross_sell_ids'     => array_map( 'absint', $product->get_cross_sells() ),
			'parent_id'          => $product->post->post_parent,
			'categories'         => wp_get_post_terms( $product->id, 'product_cat', array( 'fields' => 'ids' ) ),
			'tags'               => wp_get_post_terms( $product->id, 'product_tag', array( 'fields' => 'names' ) ),
			'images'             => $this->get_woo_images( $product ),
			'featured_src'       => wp_get_attachment_url( get_post_thumbnail_id( $product->is_type( 'variation' ) ? $product->variation_id : $product->id ) ),
			'attributes'         => $this->get_attribute( $product ),
			'downloads'          => $this->get_woo_downloads( $product ),
			'download_limit'     => (int) $product->download_limit,
			'download_expiry'    => (int) $product->download_expiry,
			'download_type'      => $product->download_type,
			'purchase_note'      => wpautop( do_shortcode( wp_kses_post( $product->purchase_note ) ) ),
			'total_sales'        => metadata_exists( 'post', $product->id, 'total_sales' ) ? (int) get_post_meta( $product->id, 'total_sales', true ) : 0,
			'variations'         => array(),
			'parent'             => array(),
			'combinations'=> $this->get_combinations($product),);
							}
	
		private function query_products_list( $args ) {

			// set base query arguments
			$query_args = array(
				'fields'      => 'ids',
				'post_type'   => 'product',
				'post_status' => 'publish',
				'meta_query'  => array(),
			);

			if ( ! empty( $args['type'] ) ) {

				$types = explode( ',', $args['type'] );

				$query_args['tax_query'] = array(
					array(
						'taxonomy' => 'product_type',
						'field'    => 'slug',
						'terms'    => $types,
					),
				);

				unset( $args['type'] );
			}

			$query_args = $this->merge_query_args( $query_args, $args );

			return new WP_Query( $query_args );
		}
		/**
	 * Get the images for a product or product variation
	 *
	 * @since 2.1
	 * @param WC_Product|WC_Product_Variation $product
	 * @return array
	 */
	private function get_woo_images( $product ) {

		$images = $attachment_ids = array();

		if ( $product->is_type( 'variation' ) ) {

			if ( has_post_thumbnail( $product->get_variation_id() ) ) {

				// Add variation image if set
				$attachment_ids[] = get_post_thumbnail_id( $product->get_variation_id() );

			} elseif ( has_post_thumbnail( $product->id ) ) {

				// Otherwise use the parent product featured image if set
				$attachment_ids[] = get_post_thumbnail_id( $product->id );
			}

		} else {

			// Add featured image
			if ( has_post_thumbnail( $product->id ) ) {
				$attachment_ids[] = get_post_thumbnail_id( $product->id );
			}

			// Add gallery images
			$attachment_ids = array_merge( $attachment_ids, $product->get_gallery_attachment_ids() );
		}

		// Build image data
		foreach ( $attachment_ids as $position => $attachment_id ) {

			$attachment_post = get_post( $attachment_id );

			if ( is_null( $attachment_post ) ) {
				continue;
			}

			$attachment = wp_get_attachment_image_src( $attachment_id, 'full' );

			if ( ! is_array( $attachment ) ) {
				continue;
			}

			$images[] = array(
				'id'         => (int) $attachment_id,
				'created_at' => $this->server->format_datetime( $attachment_post->post_date_gmt ),
				'updated_at' => $this->server->format_datetime( $attachment_post->post_modified_gmt ),
				'src'        => current( $attachment ),
				'title'      => get_the_title( $attachment_id ),
				'alt'        => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
				'position'   => $position,
			);
		}

		// Set a placeholder image if the product has no images set
		if ( empty( $images ) ) {

			$images[] = array(
				'id'         => 0,
				'created_at' => $this->server->format_datetime( time() ), // Default to now
				'updated_at' => $this->server->format_datetime( time() ),
				'src'        => wc_placeholder_img_src(),
				'title'      => __( 'Placeholder', 'woocommerce' ),
				'alt'        => __( 'Placeholder', 'woocommerce' ),
				'position'   => 0,
			);
		}

		return $images;
	}

/**
	 * Get the downloads for a product or product variation
	 *
	 * @since 2.1
	 * @param WC_Product|WC_Product_Variation $product
	 * @return array
	 */
	private function get_woo_downloads( $product ) {

		$downloads = array();

		if ( $product->is_downloadable() ) {

			foreach ( $product->get_files() as $file_id => $file ) {

				$downloads[] = array(
					'id'   => $file_id, // do not cast as int as this is a hash
					'name' => $file['name'],
					'file' => $file['file'],
				);
			}
		}

		return $downloads;
	}

	private function get_variation_data( $product ) {
		$prices_precision = wc_get_price_decimals();
		$variations       = array();

		foreach ( $product->get_children() as $child_id ) {

			$variation = $product->get_child( $child_id );

			if ( ! $variation->exists() ) {
				continue;
			}

			$variations[] = array(
					'id'                => $variation->get_variation_id(),
					'created_at'        => $this->server->format_datetime( $variation->get_post_data()->post_date_gmt ),
					'updated_at'        => $this->server->format_datetime( $variation->get_post_data()->post_modified_gmt ),
					'downloadable'      => $variation->is_downloadable(),
					'virtual'           => $variation->is_virtual(),
					'permalink'         => $variation->get_permalink(),
					'sku'               => $variation->get_sku(),
					'price'             => wc_format_decimal( $variation->get_price(), $prices_precision ),
					'regular_price'     => wc_format_decimal( $variation->get_regular_price(), $prices_precision ),
					'sale_price'        => $variation->get_sale_price() ? wc_format_decimal( $variation->get_sale_price(), $prices_precision ) : null,
					'taxable'           => $variation->is_taxable(),
					'tax_status'        => $variation->get_tax_status(),
					'tax_class'         => $variation->get_tax_class(),
					'managing_stock'    => $variation->managing_stock(),
					'stock_quantity'    => (int) $variation->get_stock_quantity(),
					'in_stock'          => $variation->is_in_stock(),
					'backordered'       => $variation->is_on_backorder(),
					'purchaseable'      => $variation->is_purchasable(),
					'visible'           => $variation->variation_is_visible(),
					'on_sale'           => $variation->is_on_sale(),
					'weight'            => $variation->get_weight() ? wc_format_decimal( $variation->get_weight(), 2 ) : null,
					'dimensions'        => array(
						'length' => $variation->length,
						'width'  => $variation->width,
						'height' => $variation->height,
						'unit'   => get_option( 'woocommerce_dimension_unit' ),
					),
					'shipping_class'    => $variation->get_shipping_class(),
					'shipping_class_id' => ( 0 !== $variation->get_shipping_class_id() ) ? $variation->get_shipping_class_id() : null,
					 'image'             => $this->get_woo_images( $variation ),
					'attributes'        => $this->get_attributes( $variation ),
					'downloads'         => $this->get_woo_downloads( $variation ),
					'download_limit'    => (int) $product->download_limit,
					'download_expiry'   => (int) $product->download_expiry,
			);
		}

		return $variations;
	}
}
}
