<?php
// Direct access security
if ( !defined( 'TM_EPO_PLUGIN_SECURITY' ) ) {
	die();
}

final class TM_EPO_COMPATIBILITY_woothemes_measurement_calculator {

	protected static $_instance = NULL;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {

		add_action( 'plugins_loaded', array( $this, 'add_compatibility' ) );

	}

	public function init() {

	}

	public function add_compatibility() {
		if ( !class_exists( 'WC_Measurement_Price_Calculator' ) ) {
			return;
		}
		add_filter( 'woocommerce_cart_item_price', array( $this, 'cart_item_price' ), 10, 3 );
		add_filter( 'wc_epo_add_cart_item_original_price', array( $this, 'wc_epo_add_cart_item_original_price' ), 10, 2 );
		add_filter( 'wc_epo_add_cart_item_calculated_price1', array( $this, 'wc_epo_add_cart_item_calculated_price' ), 10, 2 );
		add_filter( 'wc_epo_add_cart_item_calculated_price2', array( $this, 'wc_epo_add_cart_item_calculated_price' ), 10, 2 );
	}

	public function wc_epo_add_cart_item_calculated_price( $price = "", $cart_item = "" ) {
		if (
			isset( $cart_item['pricing_item_meta_data'] ) &&
			!empty( $cart_item['pricing_item_meta_data']['_quantity'] ) &&
			!empty( $cart_item['quantity'] )
		) {
			$price = $price / floatval( $cart_item['quantity'] );
		}

		return $price;
	}

	public function wc_epo_add_cart_item_original_price( $price = "", $cart_item = "" ) {
		if ( isset( $cart_item['pricing_item_meta_data'] ) && isset( $cart_item['pricing_item_meta_data']['_price'] ) ) {
			$price = $cart_item['pricing_item_meta_data']['_price'];
		}

		return $price;
	}

	public function cart_item_price( $item_price = "", $cart_item = "", $cart_item_key = "" ) {

		if (
			!empty( $cart_item['tmcartepo'] ) &&
			isset( $cart_item['tm_epo_product_price_with_options'] ) &&
			isset( $cart_item['pricing_item_meta_data'] ) &&
			!empty( $cart_item['pricing_item_meta_data']['_quantity'] ) &&
			!empty( $cart_item['quantity'] )
		) {
			$item_price = wc_price( (float) $cart_item['data']->get_price() * floatval( $cart_item['quantity'] ) );
		}

		return $item_price;
	}

}


