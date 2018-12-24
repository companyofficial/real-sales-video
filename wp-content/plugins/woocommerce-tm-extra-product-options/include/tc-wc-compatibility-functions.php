<?php
// Direct access security
if ( !defined( 'TM_EPO_PLUGIN_SECURITY' ) ) {
	die();
}

if ( !function_exists( 'wc_epo_attribute_orderby' ) ) {
	/**
	 * @param $name
	 * @return mixed|void
	 */
	function wc_epo_attribute_orderby( $name ) {
		global $wc_product_attributes, $wpdb;

		$name = str_replace( 'pa_', '', $name );

		if ( isset( $wc_product_attributes[ 'pa_' . $name ] ) ) {
			$orderby = $wc_product_attributes[ 'pa_' . $name ]->attribute_orderby;
		} else {
			$orderby = $wpdb->get_var( $wpdb->prepare( "SELECT attribute_orderby FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = %s;", $name ) );
		}

		return apply_filters( 'woocommerce_attribute_orderby', $orderby, $name );
	}
}

if ( !function_exists( 'tc_get_price_excluding_tax' ) ) {
	/**
	 * @param $product
	 * @param array $args
	 * @return float|mixed|void
	 */
	function tc_get_price_excluding_tax( $product, $args = array() ) {
		if ( function_exists( 'wc_get_price_excluding_tax' ) ) {
			if ( empty( $args['price'] ) ) {
				$args = wp_parse_args( $args, array(
					'qty'   => '',
					'price' => '',
				) );
				$qty = (int) $args['qty'] ? $args['qty'] : 1;

				return apply_filters( 'woocommerce_get_price_excluding_tax', 0, $qty, $product );
			}
			if ( (float) $args['price'] < 0){
				$args['price'] = - $args['price'];
				return - wc_get_price_excluding_tax( $product, $args );	
			}
			return wc_get_price_excluding_tax( $product, $args );
		}
		$args = wp_parse_args( $args, array(
			'qty'   => '',
			'price' => '',
		) );
		$price = $args['price'] ? $args['price'] : 0;
		$qty = $args['qty'] ? $args['qty'] : 1;

		return $product->get_price_excluding_tax( $qty, $price );
	}
}

if ( !function_exists( 'tc_get_price_including_tax' ) ) {
	/**
	 * @param $product
	 * @param array $args
	 * @return float|mixed|void
	 */
	function tc_get_price_including_tax( $product, $args = array() ) {
		if ( function_exists( 'wc_get_price_including_tax' ) ) {
			if ( empty( $args['price'] ) ) {
				$args = wp_parse_args( $args, array(
					'qty'   => '',
					'price' => '',
				) );
				$qty = (int) $args['qty'] ? $args['qty'] : 1;

				return apply_filters( 'woocommerce_get_price_including_tax', 0, $qty, $product );
			}
			if ( (float) $args['price'] < 0){
				$args['price'] = - $args['price'];
				return - wc_get_price_including_tax( $product, $args );	
			}
			return wc_get_price_including_tax( $product, $args );
		}
		$args = wp_parse_args( $args, array(
			'qty'   => '',
			'price' => '',
		) );
		$price = $args['price'] ? $args['price'] : 0;
		$qty = $args['qty'] ? $args['qty'] : 1;

		return $product->get_price_including_tax( $qty, $price );
	}
}

if ( !function_exists( 'tc_get_product_type' ) ) {
	/**
	 * @param null $product
	 * @return bool
	 */
	function tc_get_product_type( $product = NULL ) {
		if ( is_object( $product ) ) {
			if ( is_callable( array( $product, 'get_type' ) ) ) {
				return $product->get_type();
			} else {
				return $product->product_type;
			}
		}

		return FALSE;
	}
}

if ( !function_exists( 'tc_get_id' ) ) {
	/**
	 * @param $product
	 * @return mixed
	 */
	function tc_get_id( $product ) {
		if ( is_callable( array( $product, 'get_id' ) ) && is_callable( array( $product, 'get_parent_id' ) ) ) {
			if ( tc_get_product_type( $product ) == "variation" ) {
				return $product->get_parent_id();
			}

			return $product->get_id();
		}

		return $product->id;
	}
}

if ( !function_exists( 'tc_get_variation_id' ) ) {
	/**
	 * @param $product
	 * @return mixed
	 */
	function tc_get_variation_id( $product ) {
		if ( is_callable( array( $product, 'get_id' ) ) ) {
			return $product->get_id();
		}

		return $product->variation_id;
	}
}

if ( !function_exists( 'tc_get_tax_class' ) ) {
	/**
	 * @param $product
	 * @return mixed
	 */
	function tc_get_tax_class( $product ) {
		if ( is_callable( array( $product, 'get_tax_class' ) ) ) {
			return $product->get_tax_class();
		}

		return $product->tax_class;
	}
}

if ( !function_exists( 'tc_get_woocommerce_currency' ) ) {
	/**
	 * @return string
	 */
	function tc_get_woocommerce_currency() {
		$currency = get_woocommerce_currency();
		if ( class_exists( 'WooCommerce_All_in_One_Currency_Converter_Main' ) ) {
			global $woocommerce_all_in_one_currency_converter;
			$currency = $woocommerce_all_in_one_currency_converter->settings->session_currency;
		}

		return $currency;

	}
}


if ( !function_exists( 'tc_get_post_meta' ) ) {
	/**
	 * @param $post_id
	 * @param string $meta_key
	 * @param bool $single
	 * @return bool|mixed
	 */
	function tc_get_post_meta( $post_id, $meta_key = '', $single = FALSE ) {
		if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
			return get_post_meta( $post_id, $meta_key, $single );
		} else {
			if ( is_numeric( $post_id ) ) {
				$product = wc_get_product( $post_id );
				if ( is_object( $product ) ) {
					return $product->get_meta( $meta_key, TRUE );
				} else {
					return get_post_meta( $post_id, $meta_key, $single );
				}

			}
		}

		return FALSE;
	}
}

if ( !function_exists( 'tc_update_post_meta' ) ) {
	/**
	 * @param $post_id
	 * @param $meta_key
	 * @param $meta_value
	 * @param string $prev_value
	 * @return bool|int
	 */
	function tc_update_post_meta( $post_id, $meta_key, $meta_value, $prev_value = '' ) {

		if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
			return update_post_meta( $post_id, $meta_key, $meta_value, $prev_value );
		} else {
			if ( is_numeric( $post_id ) ) {
				$product = wc_get_product( $post_id );
				if ( is_object( $product ) ) {
					$product->update_meta_data( $meta_key, $meta_value );
					$product->save_meta_data();

					return TRUE;
				} else {
					return update_post_meta( $post_id, $meta_key, $meta_value );
				}
			}
		}

		return FALSE;

	}
}

if ( !function_exists( 'tc_delete_post_meta' ) ) {
	/**
	 * @param $post_id
	 * @param $meta_key
	 * @param string $meta_value
	 * @return bool
	 */
	function tc_delete_post_meta( $post_id, $meta_key, $meta_value = '' ) {

		if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
			return delete_post_meta( $post_id, $meta_key, $meta_value );
		} else {
			if ( is_numeric( $post_id ) ) {
				$product = wc_get_product( $post_id );
				if ( is_object( $product ) ) {
					$product->delete_meta_data( $meta_key );
					$product->save_meta_data();

					return TRUE;
				} else {
					return delete_post_meta( $post_id, $meta_key, $meta_value );
				}
			}
		}

		return FALSE;

	}
}

if ( !function_exists( 'tc_add_post_meta' ) ) {
	/**
	 * @param $post_id
	 * @param $meta_key
	 * @param $meta_value
	 * @param bool $unique
	 * @return bool|false|int
	 */
	function tc_add_post_meta( $post_id, $meta_key, $meta_value, $unique = FALSE ) {

		if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
			return add_post_meta( $post_id, $meta_key, $meta_value, $unique );
		} else {
			if ( is_numeric( $post_id ) ) {
				$product = wc_get_product( $post_id );
				if ( is_object( $product ) ) {
					$product->add_meta_data( $meta_key, $meta_value, $unique );
					$product->save_meta_data();

					return TRUE;
				} else {
					return add_post_meta( $post_id, $meta_key, $meta_value, $unique );
				}
			}
		}

		return FALSE;

	}
}

if ( !function_exists( 'tc_get_attributes' ) ) {
	/**
	 * @param $post_id
	 * @return mix
	 */
	function tc_get_attributes( $post_id ) {
		
		if ( is_numeric( $post_id ) ){
			$product = wc_get_product($post_id);	
		}elseif( is_object( $post_id ) ){
			$product = $post_id;
		}
		
		if ( is_object( $product ) && is_callable( array( $product, 'get_attributes' ) ) ){
		    $attributes = $product->get_attributes();
		}else{
		    $attributes = maybe_unserialize( tc_get_post_meta( $post_id, '_product_attributes', TRUE ) );
		}

		return $attributes;

	}

}