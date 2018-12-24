<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Woo_Social_Share_Discount_Coupon
 * @subpackage Woo_Social_Share_Discount_Coupon/includes
 * @author     Multidots <inquiry@multidots.in>
 */
class Woo_Social_Share_Discount_Coupon_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;	
		set_transient( '_welcome_screen_woo_social_share_discount_coupon_activation_redirect_data', true, 30 );
	}

}
