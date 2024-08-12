<?php
/**
 * WooCommerce API Currencies Class
 * 
 * Adds a /currencies, /currencies/list, /currencies/<id> endpoint to WooCommerce API
 *
 */
if ( !defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}
if ( !class_exists( 'WC_API_Currencies' ) ) {
        class WC_API_Currencies extends WC_API_Products{
                /**
                 * @var string $base the route base
                 */
                protected $base = '/currencies';
		function register_routes( $routes ) {
			
			$routes[ $this->base ] = array(
				array( array( $this, 'get_all_currencies' ), WC_API_Server::READABLE )
			);

			$routes[ $this->base . '/(?P<id>\d+)'] = array(
				array( array( $this, 'get_currencies_by_id' ), WC_API_Server::READABLE )
			);
			$routes[ $this->base . '/list'] = array(
				array( array( $this, 'get_currencies_list' ), WC_API_Server::READABLE )
			);
			

			return $routes;
		}
	function get_woocommerce_currencies() {
		$currency_id = array(1=>array('code'=>'AED','value'=> __( 'United Arab Emirates Dirham', 'woocommerce' )),
					array('code'=>'ARS','value'=> __(  'Argentine Peso', 'woocommerce')), 
					array('code'=>'AUD', 'value'=> __( 'Australian Dollars', 'woocommerce' )),
					array('code'=>'BDT', 'value' => __( 'Bangladeshi Taka', 'woocommerce' )),
					array('code'=>'BRL', 'value' => __( 'Brazilian Real', 'woocommerce' )),
					array('code'=>'BGN', 'value' => __( 'Bulgarian Lev', 'woocommerce' )),
					array('code'=>'CAD', 'value' => __( 'Canadian Dollars', 'woocommerce' )),
					array('code'=>'CLP','value' => __( 'Chilean Peso', 'woocommerce' )),
					array('code'=>'CNY', 'value'=> __( 'Chinese Yuan', 'woocommerce' )),
					array('code'=>'COP', 'value' => __( 'Colombian Peso', 'woocommerce' )),
					array('code'=>'CZK','value' => __( 'Czech Koruna', 'woocommerce' )),
					array('code'=>'DKK', 'value' => __( 'Danish Krone', 'woocommerce' )),
					array('code'=>'DOP','value' => __( 'Dominican Peso', 'woocommerce' )), 
					array('code'=>'EUR', 'value' => __( 'Euros', 'woocommerce' )),
					array('code'=>'HKD', 'value' => __( 'Hong Kong Dollar', 'woocommerce' )),
					array('code'=>'HRK', 'value' => __( 'Croatia kuna', 'woocommerce' )),
					array('code'=>'HUF', 'value' => __( 'Hungarian Forint', 'woocommerce' )),
					array('code'=>'ISK', 'value' => __( 'Icelandic krona', 'woocommerce' )),
					array('code'=>'IDR', 'value' => __( 'Indonesia Rupiah', 'woocommerce' )),
					array('code'=>'INR', 'value' => __( 'Indian Rupee', 'woocommerce' )),
					array('code'=>'NPR', 'value' => __( 'Nepali Rupee', 'woocommerce' )),
					array('code'=>'ILS', 'value' => __( 'Israeli Shekel', 'woocommerce' )),
					array('code'=>'JPY', 'value' => __( 'Japanese Yen', 'woocommerce' )),
					array('code'=>'KIP', 'value' => __( 'Lao Kip', 'woocommerce' )),
					array('code'=>'MYR', 'value' => __( 'Malaysian Ringgits', 'woocommerce' )),
					array('code'=>'MXN', 'value' => __( 'Mexican Peso', 'woocommerce' )),
					array('code'=>'NGN', 'value' => __( 'Nigerian Naira', 'woocommerce' )),
					array('code'=>'NOK', 'value' => __( 'Norwegian Krone', 'woocommerce' )),
					array('code'=>'NZD', 'value' => __( 'New Zealand Dollar', 'woocommerce' )),
					array('code'=>'PYG', 'value' => __( 'Paraguayan Guaraní', 'woocommerce' )),
					array('code'=>'PHP', 'value' => __( 'Philippine Pesos', 'woocommerce' )),
					array('code'=>'PLN', 'value' => __( 'Polish Zloty', 'woocommerce' )),
					array('code'=>'GBP', 'value' => __( 'Pounds Sterling', 'woocommerce' )),
					array('code'=>'RON', 'value' => __( 'Romanian Leu', 'woocommerce' )),
					array('code'=>'RUB', 'value' => __( 'Russian Ruble', 'woocommerce' )),
					array('code'=>'SGD', 'value' => __( 'Singapore Dollar', 'woocommerce' )),
					array('code'=>'ZAR', 'value' => __( 'South African rand', 'woocommerce' )),
					array('code'=>'SEK', 'value' => __( 'Swedish Krona', 'woocommerce' )),
					array('code'=>'CHF', 'value' => __( 'Swiss Franc', 'woocommerce' )),
					array('code'=>'TWD', 'value' => __( 'Taiwan New Dollars', 'woocommerce' )),
					array('code'=>'THB', 'value' => __( 'Thai Baht', 'woocommerce' )),
					array('code'=>'TRY', 'value' => __( 'Turkish Lira', 'woocommerce' )),
					array('code'=>'UAH', 'value' => __( 'Ukrainian Hryvnia', 'woocommerce' )),
					array('code'=>'USD', 'value' => __( 'US Dollars', 'woocommerce' )),
					array('code'=>'VND', 'value' => __( 'Vietnamese Dong', 'woocommerce' )),
					array('code'=>'EGP', 'value' => __( 'Egyptian Pound', 'woocommerce' )),		
);
		return $currency_id;
}
 	function get_currencies_by_id($id,$fields=null) {
			try{
			$currency_code_options = $this->get_woocommerce_currencies();
			
				
				if ( ! $currency_code_options[$id] ) {
				throw new WC_API_Exception( 'woocommerce_api_currencies', __( 'Currency Have no such id', 'woocommerce' ), 401 );
			}

				
					return $currency_code_options[$id];
}catch ( WC_API_Exception $e ) {
			return new WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
}
}
	function get_currencies_list($fields=null) {
				$currency_code_options = $this->get_woocommerce_currencies();
					
				return array_keys($currency_code_options);
}
	function get_all_currencies($fields=null) {
				$currency_code_options = $this->get_woocommerce_currencies();
					
				return $currency_code_options;
}
		

}
}
