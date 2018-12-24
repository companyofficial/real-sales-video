<?php
/**
 * Plugin Name:       WooCommerce Social Share Discount Coupon
 * Plugin URI:        http://www.multidots.com/
 * Description:       WooCommerce Social Share Discount Coupon plugin allows store admin to offer an instant discount coupon to customers if they share your post on their social media. You customer will be able to use that coupon on their next purchase.
 * Version:           2.2
 * Author:            Multidots
 * Author URI:        http://www.multidots.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-social-share-discount-coupon
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if (!defined('WSSDC_TEXT_DOMAIN')) {
    define('WSSDC_TEXT_DOMAIN', 'woo-social-share-discount-coupon');
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woo-social-share-discount-coupon-activator.php
 */
function activate_woo_social_share_discount_coupon() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-social-share-discount-coupon-activator.php';
	Woo_Social_Share_Discount_Coupon_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woo-social-share-discount-coupon-deactivator.php
 */
function deactivate_woo_social_share_discount_coupon() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-social-share-discount-coupon-deactivator.php';
	Woo_Social_Share_Discount_Coupon_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woo_social_share_discount_coupon' );
register_deactivation_hook( __FILE__, 'deactivate_woo_social_share_discount_coupon' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woo-social-share-discount-coupon.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woo_social_share_discount_coupon() {

	$plugin = new Woo_Social_Share_Discount_Coupon();
	$plugin->run();

}
run_woo_social_share_discount_coupon();
