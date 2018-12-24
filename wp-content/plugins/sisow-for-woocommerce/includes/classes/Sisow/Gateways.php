<?php
class Sisow_Gateways
{
	public static function _getGateways($arrDefault)
    {

        $paymentOptions = array(
            'Sisow_Gateway_Afterpay',
            'Sisow_Gateway_Afterpayb2b',
            'Sisow_Gateway_Belfius',
            'Sisow_Gateway_Capayable',
            'Sisow_Gateway_Ebill',
			'Sisow_Gateway_Eps',
			'Sisow_Gateway_Focum',
			'Sisow_Gateway_Giropay',
			'Sisow_Gateway_Homepay',
			'Sisow_Gateway_Ideal',
			'Sisow_Gateway_Idealqr',
			'Sisow_Gateway_Maestro',
			'Sisow_Gateway_Mastercard',
			'Sisow_Gateway_Mistercash',
			'Sisow_Gateway_Overboeking',
			'Sisow_Gateway_Paypalec',
			'Sisow_Gateway_Sofort',
			'Sisow_Gateway_Visa',
			'Sisow_Gateway_Vpay',
			'Sisow_Gateway_Vvv',
			'Sisow_Gateway_Webshopgiftcard',
			'Sisow_Gateway_Bunq',
			'Sisow_Gateway_Cbc',
			'Sisow_Gateway_Kbc',
        );

        $arrDefault = array_merge($arrDefault, $paymentOptions);

        return $arrDefault;
    }
	
	/**
     * This function registers the Sisow Payment Gateways
     */
    public static function register()
    {
        add_filter('woocommerce_payment_gateways', array(__CLASS__, '_getGateways'));
    }
	
	/**
     * This function adds the Sisow Global Settings to the woocommerce payment method settings
     */
    public static function addSettings()
    {
        add_filter('woocommerce_payment_gateways_settings', array(__CLASS__, '_addGlobalSettings'));
    }
	
	/**
     * Register the API's to catch the return and exchange
     */
    public static function registerApi()
    {
        add_action('woocommerce_api_wc_sisow_gateway_return', array(__CLASS__, '_sisowReturn'));
        add_action('woocommerce_api_wc_sisow_gateway_notify', array(__CLASS__, '_sisowNotify'));
    }
	
	public static function _addGlobalSettings($settings)
    {
        $updatedSettings = array();

        $addedSettings = array();
        $addedSettings[] = array(
            'title' => __('Sisow settings', 'woocommerce-sisow'),
            'type' => 'title',
            'desc' => '<p>' . __('The following options are required to use the Sisow Gateway and are used by all Sisow Payment Methods', 'woocommerce-sisow') . '</p>',
            'id' => 'sisow_general_settings',
        );
        $addedSettings[] = array(
            'name' => __('Merchant ID', 'woocommerce-sisow'),
            'type' => 'text',
            'desc' => __('The Merchant ID from Sisow, you can find it in your Sisow account', 'woocommerce-sisow'),
            'id' => 'sisow_merchantid',
        );
		$addedSettings[] = array(
            'name' => __('Merchant Key', 'woocommerce-sisow'),
            'type' => 'text',
            'desc' => __('The Merchant Key from Sisow, you can find it in your Sisow account', 'woocommerce-sisow'),
            'id' => 'sisow_merchantkey',
        );
		$addedSettings[] = array(
            'name' => __('Shop ID', 'woocommerce-sisow'),
            'type' => 'text',
            'desc' => __('The Shop ID from Sisow, you can find it in your Sisow account', 'woocommerce-sisow'),
            'id' => 'sisow_shopid',
        );
		$addedSettings[] = array(
            'name' => __('Set completed', 'woocommerce-sisow'),
            'type' => 'checkbox',
            'desc' => __('Mark the order direct as completed', 'woocommerce-sisow'),
            'id' => 'sisow_completed',
        );
		$addedSettings[] = array( 
			'name' => __('Don\'t cancel', 'woocommerce-sisow'),
			'type' => 'checkbox',
			'desc' => __('Don\'t cancel the order after a failed payment', 'woocommerce-sisow'),
			'id' => 'sisow_nocancel',
		);
		$addedSettings[] = array(
            'name' => __('Add utm_nooverride=1', 'woocommerce-sisow'),
            'type' => 'checkbox',
            'desc' => __('Add utm_nooverride=1 recommended if you use Google Analytics', 'woocommerce-sisow'),
            'id' => 'sisow_utm_nooverride',
        );
        $addedSettings[] = array(
            'type' => 'sectionend',
            'id' => 'sisow_general_settings',
        );
        foreach ($settings as $setting)
        {
            if (isset($setting['id']) && $setting['id'] == 'payment_gateways_options' && $setting['type'] != 'sectionend')
            {
                $updatedSettings = array_merge($updatedSettings, $addedSettings);
            }
            $updatedSettings[] = $setting;
        }


        return $updatedSettings;
    }

	public static function _sisowReturn()
	{
		global $woocommerce;
		
		$order = new WC_Order($_GET['ec']);

		if($_GET['status'] == 'Success')
		{
			$order = new WC_Order($_GET['ec']);
			$return_url = $order->get_checkout_order_received_url();
			if (is_ssl() || get_option('woocommerce_force_ssl_checkout') == 'yes') {
				$return_url = str_replace('http:', 'https:', $return_url);
			}
			
			$utm_nooverride = array_key_exists('utm_nooverride', $_GET);
			
			wp_redirect(apply_filters('woocommerce_get_return_url', $return_url) . ($utm_nooverride ? '&utm_nooverride=1' : ''));
		}
		else
			wp_redirect($woocommerce->cart->get_checkout_url() . ($utm_nooverride ? '&utm_nooverride=1' : ''));
	}
	
	public static function _sisowNotify($return = false)
	{
		global $woocommerce;
		
		if(sha1($_GET['trxid'] . $_GET['ec'] . $_GET['status'] . get_option('sisow_merchantid') . get_option('sisow_merchantkey')) != $_GET['sha1'])
			exit('Invalid Notify');
		
		$order = new WC_Order($_GET['ec']);
		
		if ( version_compare( $woocommerce->version, 3, ">=" ) )
			$trxid = get_post_meta($order->get_id(), '_trxid', true);
		else
			$trxid = $_GET['trxid'];

		if(empty($trxid))
			$trxid = $_GET['trxid'];
		
		$sisow = new Sisow_Helper_Sisow(get_option('sisow_merchantid'), get_option('sisow_merchantkey'), get_option('sisow_shopid'));
		if($sisow->StatusRequest($_GET['trxid']) < 0)
		{
			if(!$return)
				exit('StatusRequest failed ' . $ex);
			else
				return;
		}
		
		if($order->get_status() != 'pending' && $order->get_status() != 'on-hold' && $sisow->status != 'Success' && $sisow->status != 'Reservation' && $sisow->status != 'Reversed')
		{
			if(!$return)
				exit('Not open anymore');
			else
				return;
		}
		else if(($order->get_status() == 'processing' || $order->get_status() == 'completed') && $sisow->status != 'Reversed' && $sisow->status != 'Refund')
		{
			if(!$return)
				exit ('Order already Success (new status forbidden)');
			else
				return;
		}

		switch($sisow->status)
		{
			case "Success":
				$order->add_order_note(sprintf(__('Status recieved from Sisow: %s', 'woocommerce-sisow'), $sisow->status));
				$order->payment_complete($_GET['trxid']);
				if(get_option('sisow_completed') == "yes")
					$order->update_status('completed');
				break;
			case "Reservation":
				$order->add_order_note(sprintf(__('Status recieved from Sisow: %s', 'woocommerce-sisow'), $sisow->status));
				$order->payment_complete($_GET['trxid']);
				break;
			case "Pending":
				$order->update_status('on-hold', sprintf(__('Status recieved from Sisow: %s', 'woocommerce-sisow'), $sisow->status));
				break;
			default:
				if(get_option('sisow_nocancel') != "yes")
					$order->cancel_order(sprintf(__('Status recieved from Sisow: %s', 'woocommerce-sisow'), $sisow->status));
				break;
		}
		
		if(!$return)
			exit;
		else
			return;
	}
}
