<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Settings
 *
 *
 * @since 2.0.0
 */
class WC_Woo_Settings extends WC_API_Resource {
	
	
	protected $base = '/settings';
                /**
                 * Register the routes with callback for this class
                 * @param array $routes
                 * @return array
                 */
		function register_routes( $routes ) {

			$routes[ $this->base. '/general' ] = array(
				array( array( $this, 'get_general_settings' ), WC_API_Server::READABLE )
			);
			$routes[ $this->base. '/general/(?P<id>\d+)' ] = array(
				array( array( $this, 'get_general_settings_by_id' ), WC_API_Server::READABLE )
			);
			$routes[ $this->base. '/product_general' ] = array(
				array( array( $this, 'get_product_general_settings' ), WC_API_Server::READABLE )
			);
			$routes[ $this->base. '/product_display' ] = array(
				array( array( $this, 'get_product_display_settings' ), WC_API_Server::READABLE )
			);
			$routes[ $this->base. '/product_inventory' ] = array(
				array( array( $this, 'get_product_inventory_settings' ), WC_API_Server::READABLE )
			);
			$routes[ $this->base. '/product_downloadable' ] = array(
				array( array( $this, 'get_product_downloadable_settings' ), WC_API_Server::READABLE )
			);
			$routes[ $this->base. '/tax_options' ] = array(
				array( array( $this, 'get_tax_options_settings' ), WC_API_Server::READABLE )
			);
			$routes[ $this->base. '/checkout_options' ] = array(
				array( array( $this, 'get_checkout_options_settings' ), WC_API_Server::READABLE )
			);
			$routes[ $this->base. '/shipping_options' ] = array(
				array( array( $this, 'get_shipping_options_settings' ), WC_API_Server::READABLE )
			);
			$routes[ $this->base. '/shipping_options/flat_rate' ] = array(
				array( array( $this, 'get_flat_rate' ), WC_API_Server::READABLE )
			);
			$routes[ $this->base. '/shipping_options/free_shipping' ] = array(
				array( array( $this, 'get_free_shipping' ), WC_API_Server::READABLE )
			);
			$routes[ $this->base. '/shipping_options/international_delivery' ] = array(
				array( array( $this, 'get_international_delivery' ), WC_API_Server::READABLE )
			);
			$routes[ $this->base. '/shipping_options/local_delivery' ] = array(
				array( array( $this, 'get_local_delivery' ), WC_API_Server::READABLE )
			);
			$routes[ $this->base. '/shipping_options/local_pickup' ] = array(
				array( array( $this, 'get_local_pickup' ), WC_API_Server::READABLE )
			);
			$routes[ $this->base. '/checkout_options/list' ] = array(
				array( array( $this, 'get_payment_methods_list' ), WC_API_Server::READABLE )
			);
			$routes[ $this->base. '/shipping_options/list' ] = array(
				array( array( $this, 'get_shipping_methods_list' ), WC_API_Server::READABLE )
			);
			$routes[ $this->base. '/checkout_options/bacs' ] = array(
				array( array( $this, 'get_bacs' ), WC_API_Server::READABLE )
			);
			$routes[ $this->base. '/checkout_options/cheque' ] = array(
				array( array( $this, 'get_cheque' ), WC_API_Server::READABLE )
			);
			$routes[ $this->base. '/checkout_options/cod' ] = array(
				array( array( $this, 'get_cod' ), WC_API_Server::READABLE )
			);
			$routes[ $this->base. '/checkout_options/paypal' ] = array(
				array( array( $this, 'get_paypal' ), WC_API_Server::READABLE )
			);


			return $routes;
		}


		/**
		 * Get the setting fields
		 *
		 * @since  1.0.0
		 * @access private
		 *
		 */
		
		public function get_general_settings() {
		
		return array( 1=>array('title' => __( 'General Options', 'woocommerce' ),
				'baselocation'=>wc_format_country_state_string( apply_filters( 'woocommerce_get_base_location', get_option( 'woocommerce_default_country' ) )),
				'Selling_Location'       => get_option('woocommerce_allowed_countries'),
				'Specific_Countries'      => get_option('woocommerce_specific_allowed_countries'),	
				'Default Customer Address'=>get_option( 'woocommerce_default_customer_address' ), 
				'Store_Notice'=>  get_option( 'woocommerce_demo_store' ),'Store_Notice_Text'=>get_option( 'woocommerce_demo_store_notice' ),
				'Currency'=>get_option('woocommerce_currency'),
				'Currency Position'=>get_option( 'woocommerce_currency_pos' ),
				'Thousand Seperator'=> get_option( 'woocommerce_price_thousand_sep' ), 
				'Decimal Seperator'=>get_option( 'woocommerce_price_decimal_sep' ),
				'Decimals'=> wc_get_price_decimals()));
		
	}
	public function get_general_settings_by_id($id,$fields=null) {
			try{
			$settings_options = $this->get_general_settings();
			
				
				if ( ! $settings_options[$id] ) {
				throw new WC_API_Exception( 'woocommerce_api_settings', __( 'General Settings have no such id', 'woocommerce' ), 401 );
			}

				
					return $settings_options[$id];
}catch ( WC_API_Exception $e ) {
			return new WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
}
	}
	public function get_product_general_settings() {
		
		return array( 'Weight Unit' => get_option('woocommerce_weight_unit'),
				'Dimensions Unit'=>get_option('woocommerce_dimension_unit'),
				'Product Ratings'       => get_option('woocommerce_enable_review_rating'),
				'Ratings are required to leave a review'      => get_option('woocommerce_review_rating_required'),	
				'Show "verified owner" label for customer reviews'=>get_option( 'woocommerce_review_rating_verification_label' ), 
				'Only allow reviews from "verified owners'=>  get_option( 'woocommerce_review_rating_verification_required' ));
		
	}
	public function get_product_display_settings() {
		
		return array( 'Shop Page' => get_option('woocommerce_shop_page_id'),
				'Shop Page Display'=>get_option('woocommerce_shop_page_display'),
				'Default Category Display'=> get_option('woocommerce_category_archive_display'),
				'Default Product Sorting'=> get_option('woocommerce_default_catalog_orderby'),	
				'Add to cart behaviour'=>get_option( 'woocommerce_cart_redirect_after_add' ), 
				'Enable AJAX add to cart buttons on archives'=>  get_option( 'woocommerce_enable_ajax_add_to_cart' ),
				'Catalog Images'=>  get_option( 'shop_catalog_image_size' ),
				'Single Product Image'=>  get_option( 'shop_single_image_size' ),
				'Product Thumbnails'=>  get_option( 'shop_thumbnail_image_size' ),
				'Product Image Gallery'=>  get_option( 'woocommerce_enable_lightbox' ),
				);
		
	}
	public function get_product_inventory_settings() {
		
		return array( 'Manage Stock' => get_option('woocommerce_manage_stock'),
				'Hold Stock (minutes)'=>get_option('woocommerce_hold_stock_minutes'),
				'Notifications'       => get_option('woocommerce_notify_low_stock'),
				'Enable out of stock notifications'      => get_option('woocommerce_notify_no_stock'),	
				'Notification Recipient'=>get_option( 'woocommerce_stock_email_recipient' ), 
				'Low Stock Threshold'=>  get_option( 'woocommerce_notify_low_stock_amount' ),
				'Out Of Stock Threshold'=>  get_option( 'woocommerce_notify_no_stock_amount' ),
				'Out Of Stock Visibility'=>  get_option( 'woocommerce_hide_out_of_stock_items' ),
				'Stock Display Format'=>  get_option( 'woocommerce_stock_format' ),
				);
		
	}
	public function get_product_downloadable_settings() {
		
		return array( 'File Download Method' => get_option('woocommerce_file_download_method'),
				'Access Restriction'=>get_option('woocommerce_downloads_require_login'),
				'Grant access to downloadable products after payment'       => get_option('woocommerce_downloads_grant_access_after_payment'),
				);
		
	}
	public function get_tax_options_settings() {
		
		return array( 'Enable Taxes' => get_option('woocommerce_calc_taxes'),
				'Prices Entered With Tax'=>get_option('woocommerce_prices_include_tax'),
				'Calculate Tax Based On:'       => get_option('woocommerce_tax_based_on'),
				'Shipping Tax Class'      => get_option('woocommerce_shipping_tax_class'),	
				'Rounding'=>get_option( 'woocommerce_tax_round_at_subtotal' ), 
				'Display Prices in the Shop'=>  get_option( 'woocommerce_tax_display_shop' ),
				'Display Prices During Cart and Checkout'=>  get_option( 'woocommerce_tax_display_cart' ),
				'Price Display Suffix'=>  get_option( 'woocommerce_price_display_suffix' ),
				'Display Tax Totals'=>  get_option( 'woocommerce_tax_total_display' ),
				);
		
	}
	public function get_checkout_options_settings() {
		
		return array( 'Checkout Process' => get_option('woocommerce_enable_coupons'),
				'Calculate Coupons Sequentially'=>get_option('woocommerce_calc_discounts_sequentially'),		
				'Checkout'=>get_option('woocommerce_enable_guest_checkout'),
				'Force secure checkout'       => get_option('woocommerce_force_ssl_checkout'),
				'Force HTTP when leaving the checkout'      => get_option('woocommerce_unforce_ssl_checkout'),	
				'Cart Page'=>get_option( 'woocommerce_cart_page_id' ), 
				'Checkout Page'=>  get_option( 'woocommerce_checkout_page_id' ),
				'Terms and Conditions'=>  get_option( 'woocommerce_terms_page_id' ),
				'Pay'=>  get_option( 'woocommerce_checkout_pay_endpoint	' ),
				'Order Received'=>  get_option( 'woocommerce_checkout_order_received_endpoint' ),
				'Add Payment Method'=> get_option( 'woocommerce_myaccount_add_payment_method_endpoint' ),
				'Payment Gateways'=>$this->get_payment_gateways(),
				);
		
	}
	public function get_shipping_methods() {
	    $active_methods = array();
	    $shipping_methods = WC()->shipping->load_shipping_methods();
		
	    foreach ( $shipping_methods as $id => $shipping_method ) {
		$active_methods[$shipping_method->id] = array('id'=>$shipping_method->id,'title'=>$shipping_method->title,'settings'=>$shipping_method->settings) ;
	    }
	    return $active_methods;
  }
	public function get_free_shipping() {
	    $active_methods = array();
	    $shipping_methods = WC()->shipping->load_shipping_methods();
		
	    foreach ( $shipping_methods as $id => $shipping_method ) {
		$active_methods[$shipping_method->id] = array('id'=>$shipping_method->id,'title'=>$shipping_method->title,'settings'=>$shipping_method->settings) ;
	    }
	    return $active_methods['free_shipping'];
  }
  public function get_international_delivery() {
	    $active_methods = array();
	    $shipping_methods = WC()->shipping->load_shipping_methods();
		
	    foreach ( $shipping_methods as $id => $shipping_method ) {
		$active_methods[$shipping_method->id] = array('id'=>$shipping_method->id,'title'=>$shipping_method->title,'settings'=>$shipping_method->settings) ;
	    }
	    return $active_methods['international_delivery'];
  }
    public function get_local_delivery() {
	    $active_methods = array();
	    $shipping_methods = WC()->shipping->load_shipping_methods();
		
	    foreach ( $shipping_methods as $id => $shipping_method ) {
		$active_methods[$shipping_method->id] = array('id'=>$shipping_method->id,'title'=>$shipping_method->title,'settings'=>$shipping_method->settings) ;
	    }
	    return $active_methods['local_delivery'];
  }
  public function get_local_pickup() {
	    $active_methods = array();
	    $shipping_methods = WC()->shipping->load_shipping_methods();
		
	    foreach ( $shipping_methods as $id => $shipping_method ) {
		$active_methods[$shipping_method->id] = array('id'=>$shipping_method->id,'title'=>$shipping_method->title,'settings'=>$shipping_method->settings) ;
	    }
	    return $active_methods['local_pickup'];
  }
  
  	public function get_flat_rate() {
	    $active_methods = array();
	    $shipping_methods = WC()->shipping->load_shipping_methods();
		
	    foreach ( $shipping_methods as $id => $shipping_method ) {
		$active_methods[$shipping_method->id] = array('id'=>$shipping_method->id,'title'=>$shipping_method->title,'settings'=>$shipping_method->settings) ;
	    }
	    return $active_methods['flat_rate'];
  }
	public function get_payment_gateways() {
	    $active_gateways = array();
	    $payment_methods = WC()->payment_gateways->payment_gateways();;
		
	    foreach ( $payment_methods as $id => $payment_method ) {
		$active_gateways[] = array('id'=>$payment_method->id,'title'=>$payment_method->title);
		
	    }
	    return $active_gateways;
  }
	public function get_bacs() {
	    $active_gateways = array();
	    $payment_methods = WC()->payment_gateways->payment_gateways();;
		
	    foreach ( $payment_methods as $id => $payment_method ) {
		$active_gateways[$payment_method->id] = array('id'=>$payment_method->id,'title'=>$payment_method->title);
		
	    }
	    return $active_gateways['bacs'];
  }
	public function get_cheque() {
	    $active_gateways = array();
	    $payment_methods = WC()->payment_gateways->payment_gateways();;
		
	    foreach ( $payment_methods as $id => $payment_method ) {
		$active_gateways[$payment_method->id] = array('id'=>$payment_method->id,'title'=>$payment_method->title);
		
	    }
	    return $active_gateways['cheque'];
  }
	public function get_cod() {
	    $active_gateways = array();
	    $payment_methods = WC()->payment_gateways->payment_gateways();;
		
	    foreach ( $payment_methods as $id => $payment_method ) {
		$active_gateways[$payment_method->id] = array('id'=>$payment_method->id,'title'=>$payment_method->title);
		
	    }
	    return $active_gateways['cod'];
  }
	public function get_paypal() {
	    $active_gateways = array();
	    $payment_methods = WC()->payment_gateways->payment_gateways();;
		
	    foreach ( $payment_methods as $id => $payment_method ) {
		$active_gateways[$payment_method->id] = array('id'=>$payment_method->id,'title'=>$payment_method->title);
		
	    }
	    return $active_gateways['paypal'];
  }
	public function get_shipping_options_settings() {
		return array( 'Shipping Calculations' => get_option('woocommerce_calc_shipping'),
				'Enable the shipping calculator'=>get_option('woocommerce_enable_shipping_calc'),		
				'Hide shipping costs'=>get_option('woocommerce_shipping_cost_requires_address'),
				'Shipping Display Mode'=> get_option('woocommerce_shipping_method_format'),
				'Shipping Destination'      => get_option('woocommerce_ship_to_destination'),	
				'Restrict shipping to Location(s)'=>get_option( 'woocommerce_ship_to_countries' ), 
				'Specific Countries'=>  get_option( 'woocommerce_specific_ship_to_countries' ),
				'Shipping Methods'=> $this->get_shipping_methods(),
				);
		
	}
	public function get_payment_methods_list() {
	    $active_gateways = array();
	    $payment_methods = WC()->payment_gateways->payment_gateways();;
		
	    foreach ( $payment_methods as $id => $payment_method ) {
		$active_gateways[] = $payment_method->id;
		
	    }
	    return $active_gateways;
		
	}
	public function get_shipping_methods_list() {
	    $active_methods = array();
	    $shipping_methods = WC()->shipping->load_shipping_methods();
		
	    foreach ( $shipping_methods as $id => $shipping_method ) {
		$active_methods[] = $shipping_method->id ;
	    }
	    return $active_methods;
  }
	




}

