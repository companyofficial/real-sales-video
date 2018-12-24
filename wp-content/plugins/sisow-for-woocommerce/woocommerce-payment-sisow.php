<?php

/**
 * Plugin Name: Woocommerce Sisow Payment Methods
 * Plugin URI: https://wordpress.org/plugins/sisow-for-woocommerce/
 * Description: Sisow payment methods for woocommerce
 * Version: 5.1.1
 * Author: Sisow
 * Author URI: http://www.sisow.nl
 * Requires at least: 3.0.1
 * Tested up to: 4.9.4
 * WC requires at least: 2.0.0
 * WC tested up to: 3.4.4
 *
 * Text Domain: woocommerce-sisow
 * Domain Path: /languages/
 */
 
//Autoloader laden en registreren
require_once dirname(__FILE__) . '/includes/classes/Autoload.php';

//plugin functies inladen
require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

//textdomain inladen
load_plugin_textdomain( 'woocommerce-sisow', false, plugin_basename( dirname( __FILE__ ) ) . "/languages" );

function error_woocommerce_not_active() {
    echo '<div class="error"><p>' . __('The Sisow payment methods plugin requires woocommerce to be active', 'woocommerce-sisow') . '</p></div>';
}

function error_curl_not_installed() {
    echo '<div class="error"><p>' . __('Curl is not installed.<br />In order to use the Sisow payment methods, you must install install CURL.<br />Ask your system administrator to install php_curl', 'woocommerce-sisow') . '</p></div>';
}

// Curl is niet geinstalleerd. foutmelding weergeven
if (!function_exists('curl_version')) {
    add_action('admin_notices', 'error_curl_not_installed');
}

//Autoloader registreren
Sisow_Autoload::register();

if (is_plugin_active('woocommerce/woocommerce.php') || is_plugin_active_for_network('woocommerce/woocommerce.php')) {
    //Gateways van Sisow aan woocommerce koppelen
    Sisow_Gateways::register();

    //Globale settings van Sisow aan woocommerce koppelen
    Sisow_Gateways::addSettings();

    //Return en Notify functies koppelen aan de woocommerce API
    Sisow_Gateways::registerApi();
} else {
    // Woocommerce is niet actief. foutmelding weergeven
    add_action('admin_notices', error_woocommerce_not_active);
}

