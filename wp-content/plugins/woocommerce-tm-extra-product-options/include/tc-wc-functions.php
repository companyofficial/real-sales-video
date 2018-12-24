<?php
// Direct access security
if ( !defined( 'TM_EPO_PLUGIN_SECURITY' ) ) {
	die();
}

if ( !function_exists( 'wc_get_price_decimal_separator' ) ) {
	/**
	 * @return string
	 */
	function wc_get_price_decimal_separator() {
		$separator = stripslashes( get_option( 'woocommerce_price_decimal_sep' ) );

		return $separator ? $separator : '.';
	}
}
if ( !function_exists( 'wc_get_price_thousand_separator' ) ) {
	/**
	 * @return string
	 */
	function wc_get_price_thousand_separator() {
		$separator = stripslashes( get_option( 'woocommerce_price_thousand_sep' ) );

		return $separator;
	}
}

if ( !function_exists( 'wc_get_price_decimals' ) ) {
	/**
	 * Return the number of decimals after the decimal point.
	 * @since  2.3
	 * @return int
	 */
	function wc_get_price_decimals() {
		return absint( get_option( 'woocommerce_price_num_decimals', 2 ) );
	}
}
if ( !function_exists( 'wc_get_rounding_precision' ) ) {
	/**
	 * Get rounding precision for internal WC calculations.
	 * Will increase the precision of wc_get_price_decimals by 2 decimals, unless WC_ROUNDING_PRECISION is set to a higher number.
	 *
	 * @since 2.6.3
	 * @return int
	 */
	function wc_get_rounding_precision() {
		$precision = wc_get_price_decimals() + 2;
		if ( defined( 'WC_ROUNDING_PRECISION' ) && absint( WC_ROUNDING_PRECISION ) > $precision ) {
			$precision = absint( WC_ROUNDING_PRECISION );
		}

		return $precision;
	}
}
