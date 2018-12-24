<?php
// Direct access security
if ( !defined( 'TM_EPO_PLUGIN_SECURITY' ) ) {
	die();
}

final class TM_EPO_COMPATIBILITY_woocommerce_currency_switcher {

	protected static $_instance = NULL;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {

		add_action( 'wc_epo_add_compatibility', array( $this, 'add_compatibility' ) );

	}

	public function init() {

	}

	public function add_compatibility() {
		/** WooCommerce Currency Switcher support (realmag777) **/
		add_filter( 'wc_epo_product_price', array( $this, 'wc_epo_product_price' ), 10, 3 );
		add_filter( 'wc_epo_product_price_correction', array( $this, 'wc_epo_product_price_correction' ), 10, 3 );
		add_filter( 'wc_epo_option_price_correction', array( $this, 'wc_epo_option_price_correction' ), 10, 3 );

		add_filter( 'wc_epo_get_current_currency_price', array( $this, 'wc_epo_get_current_currency_price' ), 10, 5 );
		add_filter( 'wc_epo_remove_current_currency_price', array( $this, 'wc_epo_remove_current_currency_price' ), 10, 2 );

		add_filter( 'wc_epo_get_currency_price', array( $this, 'tm_wc_epo_get_currency_price' ), 10, 8 );
		add_filter( 'woocommerce_tm_epo_price_add_on_cart', array( $this, 'tm_epo_price_add_on_cart' ), 10, 2 );

		add_filter( 'woocommerce_tm_epo_price_per_currency_diff', array( $this, 'tm_epo_price_per_currency_diff' ), 10, 2 );

		add_filter( 'wc_epo_get_price_html', array( $this, 'wc_epo_get_price_html' ), 10, 2 );

		/** Aelia only **/
		add_filter( 'wc_epo_cart_set_price', array( $this, 'wc_epo_cart_set_price' ), 10, 2 );

		add_filter( 'wc_epo_cs_convert', array( $this, 'wc_epo_cs_convert' ), 10, 3 );
	}

	public function wc_epo_cart_set_price( $cart_item, $price ) {
		if ( class_exists( 'WC_Aelia_CurrencySwitcher' ) ) {
			if ( !property_exists( 'WC_Aelia_CurrencySwitcher', 'version' ) || (property_exists( 'WC_Aelia_CurrencySwitcher', 'version' ) && version_compare( WC_Aelia_CurrencySwitcher::$version, '4.4.7', '<' )) ) {
				$cart_item['data']->set_price( $price );
			}
		}

		return $cart_item;
	}

	/** Add additional info in price html **/
	public function wc_epo_get_price_html( $price_html, $product ) {

		if ( class_exists( 'WOOCS' ) ) {
			global $WOOCS;

			$currencies = $WOOCS->get_currencies();

			$customer_price_format = get_option( 'woocs_customer_price_format', '' );

			if ( !empty( $customer_price_format ) ) {
				$txt = '<span class="woocs_price_code" data-product-id="' . tc_get_id( $product ) . '">' . $customer_price_format . '</span>';
				$txt = str_replace( '__PRICE__', $price_html, $txt );
				$price_html = str_replace( '__CODE__', $WOOCS->current_currency, $txt );
			}


			//hide cents on front as html element
			if ( !in_array( $WOOCS->current_currency, $WOOCS->no_cents ) ) {
				if ( $currencies[ $WOOCS->current_currency ]['hide_cents'] == 1 ) {
					$price_html = preg_replace( '/\.[0-9][0-9]/', '', $price_html );
				}
			}

			if ( (get_option( 'woocs_price_info', 0 ) AND !is_admin()) OR isset( $_REQUEST['get_product_price_by_ajax'] ) ) {


				$info = "<ul>";
				$current_currency = $WOOCS->current_currency;
				foreach ( $currencies as $curr ) {
					if ( !isset( $curr['name'] ) || $curr['name'] == $current_currency ) {
						continue;
					}
					$WOOCS->current_currency = $curr['name'];
					$value = $product->get_price() * $currencies[ $curr['name'] ]['rate'];
					$value = number_format( $value, 2, $WOOCS->decimal_sep, '' );
					if ( tc_get_product_type( $product ) != 'variable' ) {
						$info .= "<li><b>" . $curr['name'] . "</b>: " . $WOOCS->wc_price( $value, FALSE, array( 'currency' => $curr['name'] ) ) . "</li>";
					} else {
						//https://gist.github.com/mikejolley/1600117
						$min_value = $product->min_variation_price * $currencies[ $curr['name'] ]['rate'];
						$min_value = number_format( $min_value, 2, $WOOCS->decimal_sep, '' );
						//***
						$max_value = $product->max_variation_price * $currencies[ $curr['name'] ]['rate'];
						$max_value = number_format( $max_value, 2, $WOOCS->decimal_sep, '' );
						//+++
						$var_price = $WOOCS->wc_price( $min_value, array( 'currency' => $curr['name'] ) );
						$var_price .= ' - ';
						$var_price .= $WOOCS->wc_price( $max_value, array( 'currency' => $curr['name'] ) );
						$info .= "<li><b>" . $curr['name'] . "</b>: " . $var_price . "</li>";
					}
				}
				$WOOCS->current_currency = $current_currency;
				$info .= "</ul>";
				$info = '<div class="woocs_price_info"><span class="woocs_price_info_icon"></span>' . $info . '</div>';
				$price_html .= $info;
			}
		}

		return $price_html;

	}

	private function _get_woos_price_calculation() {

		$oldway = FALSE;
		if ( class_exists( 'WOOCS' ) ) {
			global $WOOCS;
			if ( property_exists( $WOOCS, 'the_plugin_version' ) || defined( 'WOOCS_VERSION' ) ) {
				$vi = property_exists( $WOOCS, 'the_plugin_version' ) ? $WOOCS->the_plugin_version : (defined( 'WOOCS_VERSION' ) ? WOOCS_VERSION : FALSE);
				$v = intval( $vi );
				if ( $vi !== FALSE ) {
					if ( $v == 1 ) {
						if ( version_compare( $vi, '1.0.9', '<' ) ) {
							$oldway = TRUE;
						}
					} else {
						if ( version_compare( $vi, '2.0.9', '<' ) ) {
							$oldway = TRUE;
						}
					}
				}
			}
		}

		return $oldway;

	}

	/** WooCommerce Currency Switcher support (realmag777)
	 * This filter is currently only used for product prices.
	 */
	public function wc_epo_product_price( $price = "", $type = "", $is_meta_value = TRUE, $currency = FALSE ) {
		if ( class_exists( 'WOOCS' ) ) {
			global $WOOCS;
			if ( property_exists( $WOOCS, 'the_plugin_version' ) || defined( 'WOOCS_VERSION' ) ) {
				if ( !$is_meta_value && !$this->_get_woos_price_calculation() ) {
					if ( $WOOCS->is_multiple_allowed ) {
						// no converting needed
					} else {
						$price = apply_filters( 'woocs_exchange_value', $price );
					}
				} else {
					$currencies = $WOOCS->get_currencies();
					if ( !$currency ) {
						$current_currency = $WOOCS->current_currency;
					} else {
						$current_currency = $currency;
					}
					if ( isset( $currencies[ $current_currency ] ) && isset( $currencies[ $current_currency ]['rate'] ) ) {
						$price = (double) $price * (double) $currencies[ $current_currency ]['rate'];
					}
				}
			}
		} elseif ( class_exists( 'WooCommerce_All_in_One_Currency_Converter_Main' ) ) {
			global $woocommerce_all_in_one_currency_converter;
			$user_currency = $woocommerce_all_in_one_currency_converter->settings->session_currency;
			$currency_data = $woocommerce_all_in_one_currency_converter->settings->get_currency_data();
			$conversion_method = $woocommerce_all_in_one_currency_converter->settings->get_conversion_method();

			if ( !$currency ) {
				$current_currency = $user_currency;
			} else {
				$current_currency = $currency;
			}
			if ( isset( $currency_data[ $current_currency ] ) && isset( $currency_data[ $current_currency ]['rate'] ) ) {
				$price = (double) $price * (double) $currency_data[ $current_currency ]['rate'];
			}
		}

		return $price;
	}

	/** WooCommerce Currency Switcher support (realmag777)
	 * Adjusts option prices when using different currency price for versions > 2.0.9
	 * MUST BE USED ONLY WHEN IT IS KNOWN THAT THE PRICE IS DIFFERENT !
	 */
	public function tm_epo_price_per_currency_diff( $price = 0, $to_currency = NULL ) {
		if ( class_exists( 'WOOCS' ) && !$this->_get_woos_price_calculation() ) {
			global $WOOCS;
			if ( $to_currency === NULL || ($to_currency !== NULL && $WOOCS->default_currency == $to_currency) ) {
				$price = $this->wc_epo_remove_current_currency_price( $price );
			}
		}

		return $price;
	}

	public function wc_epo_product_price_correction( $price ) {
		if ( class_exists( 'WOOCS' ) || class_exists( 'WooCommerce_All_in_One_Currency_Converter_Main' ) ) {
			global $WOOCS;
			if ( $WOOCS->is_multiple_allowed ) {
				return apply_filters( 'wc_epo_remove_current_currency_price', $price );
			}

		}

		return $price;
	}

	public function wc_epo_option_price_correction( $price ) {
		if ( class_exists( 'WOOCS' ) || class_exists( 'WooCommerce_All_in_One_Currency_Converter_Main' ) ) {
			return apply_filters( 'wc_epo_remove_current_currency_price', $price );
		}

		return $price;
	}

	/** WooCommerce Currency Switcher support (realmag777) **/
	public function wc_epo_get_current_currency_price( $price = "", $type = "", $is_meta_value = TRUE, $currencies = NULL, $currency = false ) {
		global $woocommerce_wpml;
		if ( is_array( $type ) ) {
			$type = "";
		}
		// Check if the price should be processed only once
		if ( in_array( (string) $type, array( '', 'char', 'step', 'intervalstep', 'charnofirst', 'charnospaces', 'charnon', 'charnonnospaces', 'fee', 'stepfee', 'subscriptionfee' ) ) ) {// 'percentcurrenttotal',

			if ( TM_EPO_WPML()->is_active() && $woocommerce_wpml && property_exists( $woocommerce_wpml, 'multi_currency' ) && $woocommerce_wpml->multi_currency ) {

				if ( is_callable( array( $woocommerce_wpml->multi_currency, 'convert_price_amount' ) ) ) {
					$price = $woocommerce_wpml->multi_currency->convert_price_amount( $price, $currency );
				} elseif ( property_exists( $woocommerce_wpml->multi_currency, 'prices' ) && is_callable( array( $woocommerce_wpml->multi_currency->prices, 'convert_price_amount' ) ) ) {
					$price = $woocommerce_wpml->multi_currency->prices->convert_price_amount( $price, $currency );
				}

			} elseif ( class_exists( 'WOOCS' ) || class_exists( 'WooCommerce_All_in_One_Currency_Converter_Main' ) ) {
				global $WOOCS;
				if ( is_array( $currencies ) && isset( $currencies[ $WOOCS->current_currency ] ) ) {
					$price = $currencies[ $WOOCS->current_currency ];
				} else {
					$price = $this->wc_epo_product_price( $price, $type, $is_meta_value );
				}

			} else {

				$price = $this->get_price_in_currency( $price, NULL, NULL, $currencies, $type );

			}

		}

		return $price;

	}

	public function tm_wc_epo_get_currency_price( $price = "", $currency = FALSE, $price_type = "", $is_meta_value = TRUE, $current_currency = FALSE, $price_per_currencies = NULL, $key = NULL, $attribute = NULL ) {

		if ( !$currency ) {
			return $this->wc_epo_get_current_currency_price( $price, $price_type, $is_meta_value );
		}
		if ( $current_currency && $current_currency == $currency ) {
			return $price;
		}

		global $woocommerce_wpml;
		if ( TM_EPO_WPML()->is_active() && $woocommerce_wpml && property_exists( $woocommerce_wpml, 'multi_currency' ) && $woocommerce_wpml->multi_currency ) {
			//todo:doesn't work at the moment
			$price = apply_filters( 'wcml_raw_price_amount', $price );

		} elseif ( class_exists( 'WOOCS' ) || class_exists( 'WooCommerce_All_in_One_Currency_Converter_Main' ) ) {

			$price = $this->wc_epo_product_price( $price, $price_type, $is_meta_value, $currency );

		} else {

			$price = $this->get_price_in_currency( $price, $currency, NULL, $price_per_currencies, $price_type, $key, $attribute );

		}

		return $price;

	}

	/** WooCommerce Currency Switcher support (realmag777) **/
	public function wc_epo_remove_current_currency_price( $price = "", $type = "" ) {
		global $woocommerce_wpml;
		if ( class_exists( 'WOOCS' ) ) {
			global $WOOCS;
			$currencies = $WOOCS->get_currencies();
			$current_currency = $WOOCS->current_currency;
			if ( !empty( $currencies[ $current_currency ]['rate'] ) ) {
				$price = (double) $price / $currencies[ $current_currency ]['rate'];
			}
		} elseif ( class_exists( 'WooCommerce_All_in_One_Currency_Converter_Main' ) ) {
			global $woocommerce_all_in_one_currency_converter;
			$user_currency = $woocommerce_all_in_one_currency_converter->settings->session_currency;
			$currency_data = $woocommerce_all_in_one_currency_converter->settings->get_currency_data();
			$conversion_method = $woocommerce_all_in_one_currency_converter->settings->get_conversion_method();

			if ( !$currency ) {
				$current_currency = $user_currency;
			} else {
				$current_currency = $currency;
			}
			if ( isset( $currency_data[ $current_currency ] ) && !empty( $currency_data[ $current_currency ]['rate'] ) ) {
				$price = (double) $price / (double) $currency_data[ $current_currency ]['rate'];
			}
		} elseif ( TM_EPO_WPML()->is_active() && $woocommerce_wpml && property_exists( $woocommerce_wpml, 'multi_currency' ) && $woocommerce_wpml->multi_currency ) {
			if ( is_callable( array( $woocommerce_wpml->multi_currency, 'unconvert_price_amount' ) ) ) {
				$price = $woocommerce_wpml->multi_currency->unconvert_price_amount( $price );
			} elseif ( property_exists( $woocommerce_wpml->multi_currency, 'prices' ) && is_callable( array( $woocommerce_wpml->multi_currency->prices, 'unconvert_price_amount' ) ) ) {
				$price = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount( $price );
			}
		} else {
			$from_currency = get_option( 'woocommerce_currency' );
			$to_currency = tc_get_woocommerce_currency();
			$price = $this->get_price_in_currency( $price, $from_currency, $to_currency );
		}

		return $price;
	}

	/**
	 * Basic integration with WooCommerce Currency Switcher, developed by Aelia
	 * (http://aelia.co). This method can be used by any 3rd party plugin to
	 * return prices converted to the active currency.
	 *
	 * @param double price The source price.
	 * @param string to_currency The target currency. If empty, the active currency
	 * will be taken.
	 * @param string from_currency The source currency. If empty, WooCommerce base
	 * currency will be taken.
	 * @return double The price converted from source to destination currency.
	 * @author Aelia <support@aelia.co>
	 * @link http://aelia.co
	 */
	protected function get_price_in_currency( $price, $to_currency = NULL, $from_currency = NULL, $currencies = NULL, $type = NULL, $key = NULL, $attribute = NULL ) {
		if ( empty( $from_currency ) ) {
			$from_currency = get_option( 'woocommerce_currency' );
		}
		if ( empty( $to_currency ) ) {
			$to_currency = tc_get_woocommerce_currency();
		}
		if ( $type !== NULL && in_array( $type, array( '', 'char', 'step', 'intervalstep', 'charnofirst', 'charnospaces', 'charnon', 'charnonnospaces', 'fee', 'stepfee', 'subscriptionfee' ) ) && is_array( $currencies ) && isset( $currencies[ $to_currency ] ) ) {// 'percentcurrenttotal',
			$v = $currencies[ $to_currency ];
			if ( $key !== NULL && isset( $v[ $key ] ) ) {
				$v = $v[ $key ];
			}
			if ( is_array( $v ) ) {
				$v = array_values( $v );
				$v = $v[0];
				if ( is_array( $v ) ) {
					$v = array_values( $v );
					$v = $v[0];
				}
			}

			return $v;
		}

		return apply_filters( 'wc_epo_cs_convert', apply_filters( 'wc_aelia_cs_convert', $price, $from_currency, $to_currency ), $from_currency, $to_currency );
	}

	public function wc_epo_cs_convert( $amount, $from_currency, $to_currency, $include_markup = TRUE ) {
		if ( class_exists( 'WC_Aelia_CurrencySwitcher' ) ) {
			return $amount;
		}
		// No need to try converting an amount that is not numeric. This can happen
		// quite easily, as "no value" is passed as an empty string
		if ( !is_numeric( $amount ) ) {
			//return $amount;
		}

		// No need to convert a zero amount, it will stay zero
		if ( $amount == 0 ) {
			return $amount;
		}

		// No need to spend time converting a currency to itself
		if ( $from_currency == $to_currency ) {
			return $amount;
		}

		// Retrieve exchange rates from the configuration
		$exchange_rate = FALSE;
		if ( class_exists( 'WC_Product_Price_Based_Country' ) && function_exists( 'WCPBC' ) ) {
			$customer = WCPBC()->customer;
			if ( !$customer ) {
				foreach ( WCPBC()->get_regions() as $key => $value ) {
					if ( $value['currency'] == $to_currency ) {
						$exchange_rate = $value['exchange_rate'];
						break;
					}
				}
			} else {
				$exchange_rate = $customer->exchange_rate;
			}
		}
		if ( !$exchange_rate ) {
			return $amount;
		}

		return apply_filters( 'wc_epo_cs_converted_amount',
			round( $amount * $exchange_rate ),
			$amount,
			$from_currency,
			$to_currency );
	}

	/** WooCommerce Currency Switcher support (realmag777) **/
	public function tm_epo_price_add_on_cart( $price = "", $price_type = "" ) {
		//global $woocommerce_wpml;
		if ( !class_exists( 'WooCommerce_All_in_One_Currency_Converter_Main' ) ) {
			$price = apply_filters( 'wc_epo_get_current_currency_price', $price, $price_type );
		}

		return $price;
	}

}


