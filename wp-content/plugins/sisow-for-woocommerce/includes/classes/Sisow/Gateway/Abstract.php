<?php
class Sisow_Gateway_Abstract extends WC_Payment_Gateway
{
	public static function NeedRedirect() { return true; }
	
	public static function getCode()
    {
        throw new Exception('Please implement the getCode method');
    }

    public static function getName()
    {
        throw new Exception('Please implement the getName method');
    }
	
	public static function getMerchantId()
    {
        return get_option('sisow_merchantid');
    }

    public static function getMerchantKey()
    {
        return get_option('sisow_merchantkey');
    }
	
	public static function getShopId()
    {
        return get_option('sisow_shopid');
    }
	
	public static function getWarning()
	{
		return null;
	}
	
	public static function canRefund()
	{
		return true;
	}
		
	public static function addScript(){}
	
	public function getIcon()
    {
		$code = $this->getCode();
		if($code == 'afterpayb2b')
			$code = 'afterpay';
		
		return plugins_url( 'Images/'.$code.'.png', dirname(__FILE__) );
    }
	
	public function __construct()
    {

        $this->id = $this->getCode();
        $this->icon = $this->getIcon();
        $this->has_fields = true;
        $this->method_title = 'Sisow - ' . $this->getName();
        $this->method_description = sprintf(__('Activate this module to accept %s transactions', 'woocommerce-sisow'), $this->getName());
		
		if($this->canRefund())
			$this->supports = array('products', 'refunds');
		else
			$this->supports = array('products');

        //$this->init_form_fields();
        $this->init_settings();

        $this->title = $this->get_option('title');
		
		if($this->get_option('enabled') == 'yes' && !is_admin())
			$this->addScript();
		
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }
	
	public function init_settings()
    {
		$this->form_fields = array();
		
		$this->form_fields['docs'] = array(
			'title'       => __( 'Documentation', 'woocommerce-sisow' ),
			'type'        => 'title',
			'description' => __( '<a href="https://www.sisow.nl/Sisow/down.aspx?doc=HandleidingWooCommerce.pdf" target="_blank">Click to open documentation</a>.', 'woocommerce-sisow' )
			);
		
		$warning = $this->getWarning();
		
		if(is_array($warning))
		{
			$this->form_fields['warning'] = $warning;
		}
		$this->form_fields['enabled'] = array(
				'title' => __('Enable/Disable', 'woocommerce'),
				'type' => 'checkbox',
				'label' => sprintf(__('Enable Sisow %s', 'woocommerce-sisow'), $this->getName()),
				'default' => 'no'
			);
						
		$this->form_fields['title'] = array(
				'title' => __('Title', 'woocommerce'),
				'type' => 'text',
				'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
				'default' => $this->getName(),
				'desc_tip' => true,
			);
			
		$this->form_fields['description'] = array(
				'title' => __('Customer Message', 'woocommerce'),
				'type' => 'textarea',
				'default' => sprintf(__('Pay with %s', 'woocommerce-sisow'), $this->getName()),
			);
			
		$this->form_fields['description_bankaccount'] = array(
				'title' => __('Description', 'woocommerce'),
				'type' => 'textarea',
				'default' => __('Description on the bank account', 'woocommerce-sisow'),
			);
		
		$this->form_fields['testmode'] = array( 
				'title' => __('Testmode', 'woocommerce-sisow'),
				'type' => 'checkbox',
				'label' => __('Enable testmode', 'woocommerce-sisow'),
				'default' => 'no',
			);
		
		if($this->getCode() == 'afterpay')
		{
			$this->form_fields['makeinvoice'] = array(
				'title' => __('Make AfterPay invoice', 'woocommerce-sisow'),
				'type' => 'checkbox',
				'label' => __('Create AfterPay invoice direct after credit check', 'woocommerce-sisow'),
				'default' => 'no',
			);
		}
		else if($this->id == 'overboeking' || $this->id == 'ebill')	
		{
			$this->form_fields['days'] = array(
				'title' => __('Days', 'woocommerce-sisow'),
				'type' => 'text',
				'default' => 30,
			);
			
			$label = $this->id == 'overboeking' ? __('Include paylink', 'woocommerce-sisow') : __('Include bank account details', 'woocommerce-sisow');
			
			$this->form_fields['including'] = array(
				'title' => __('Include', 'woocommerce-sisow'),
				'type' => 'checkbox',
				'label' => $label,
				'default' => 'no',
			);
		}

        parent::init_settings();
    }

	public function payment_fields()
    {
        $description = $this->get_option('description');
        echo $description;
    }
	
	public function process_payment($order_id)
    {
		/** @var $wpdb wpdb The database */
        global $wpdb;
        global $woocommerce;
        $order = new WC_Order($order_id);
				
		$arg = array();
		
		if(method_exists($order, 'get_shipping_first_name'))
		{
			$arg = $this->GetWooCommerce3Data($order);
		}
		else
		{
			$arg = $this->GetWooCommerce2Data($order);
		}
		
		
		if($this->get_option('testmode') == 'yes')
			$arg['testmode'] = 'true';
		
		if($this->get_option('makeinvoice') == 'yes')
			$arg['makeinvoice'] = 'true';
		
		if($this->get_option('including') == 'yes')
			$arg['including'] = 'true';
		
		if($this->get_option('days') > 0)
			$arg['days'] = $this->get_option('days');
		
		if(array_key_exists($this->getCode() . '_bic', $_POST))
			$arg['bic'] = $_POST[$this->getCode() . '_bic'];
		
		if(array_key_exists($this->getCode() . '_gender', $_POST))
			$arg['gender'] = $_POST[$this->getCode() . '_gender'];
		
		if(array_key_exists($this->getCode() . '_iban', $_POST))
			$arg['iban'] = $_POST[$this->getCode() . '_iban'];
		
		if(array_key_exists($this->getCode() . '_coc', $_POST))
			$arg['billing_coc'] = $_POST[$this->getCode() . '_coc'];
				
		if(array_key_exists($this->getCode() . '_birthday_day', $_POST) && array_key_exists($this->getCode() . '_birthday_month', $_POST) && array_key_exists($this->getCode() . '_birthday_year', $_POST))
			$arg['birthdate'] = $_POST[$this->getCode() . '_birthday_day'] . $_POST[$this->getCode() . '_birthday_month'] . $_POST[$this->getCode() . '_birthday_year'];
		
		$sisow = new Sisow_Helper_Sisow(get_option('sisow_merchantid'), get_option('sisow_merchantkey'), get_option('sisow_shopid'));
        $sisow->purchaseId = ltrim($order->get_order_number(), '#');
		$description_bankaccount = $this->get_option('description_bankaccount');
        $sisow->description =  (!empty($description_bankaccount) && $description_bankaccount != '') ? rtrim($description_bankaccount) . ' ' . $order->get_order_number() : get_bloginfo('name') . ' ' . $order->get_order_number();
        $sisow->amount = $order->get_total();
		
		$code = $this->getCode();
		
		if($code == 'afterpayb2b')
			$code = 'afterpay';
        $sisow->payment = $code;
        $sisow->entranceCode = $order_id;
        $sisow->returnUrl = add_query_arg('wc-api', 'Wc_Sisow_Gateway_Return', home_url('/')) . (get_option('sisow_utm_nooverride') == 'yes' ? '&utm_nooverride=1' : '');
        $sisow->cancelUrl = $order->get_cancel_order_url() . (get_option('sisow_utm_nooverride') == 'yes' ? '&utm_nooverride=1' : '');
        $sisow->notifyUrl = add_query_arg('wc-api', 'Wc_Sisow_Gateway_Notify', home_url('/'))  . (get_option('sisow_utm_nooverride') == 'yes' ? '&utm_nooverride=1' : '');
		$sisow->callbackUrl = add_query_arg('wc-api', 'Wc_Sisow_Gateway_Notify', home_url('/'))  . (get_option('sisow_utm_nooverride') == 'yes' ? '&utm_nooverride=1' : '');
		if(array_key_exists($this->getCode() . '_issuer', $_POST))
			$sisow->issuerId = $_POST[$this->getCode() . '_issuer'];
		
		if( ($ex = $sisow->TransactionRequest($arg)) < 0 )
		{
			if($this->getCode() == 'focum')
				wc_add_notice( 'Betalen met Focum Achteraf Betalen is op dit moment niet mogelijk, betaal anders.', 'error' );
			else if($this->getCode() == 'capayable')
				wc_add_notice( 'Helaas kunt u geen gebruik maken van Capayable. Het is natuurlijk wel mogelijk om voor een andere betaalmethode te kiezen. Mocht u toch meer informatie willen? Dan kunt u contact opnemen met de klantenservice van Capayable. Dit kan per e-mail via klantenservice@capayable.com, telefonisch via 040-2595072 of via WhatsApp op 06-19256405.', 'error' );
			else if($code == 'afterpay')
				wc_add_notice( 'Het spijt ons u te moeten mededelen dat uw aanvraag om uw bestelling achteraf te betalen op dit moment niet door AfterPay wordt geaccepteerd. Dit kan om diverse (tijdelijke) redenen zijn.Voor vragen over uw afwijzing kunt u contact opnemen met de Klantenservice van AfterPay. Of kijk op de website van AfterPay bij “Veel gestelde vragen” via de link http://www.afterpay.nl/page/consument-faq onder het kopje “Gegevenscontrole”. Wij adviseren u voor een andere betaalmethode te kiezen om alsnog de betaling van uw bestelling af te ronden.', 'error' );
			else
				wc_add_notice( $ex . ' ' . $sisow->errorCode, 'error' );
			
			$order->cancel_order('Sisow fout: ' . $ex . ' ' . $sisow->errorCode. ' ' . $sisow->errorMessage);
		}
		else
		{
			update_post_meta($order_id, '_trxid', $sisow->trxId);
			
			if($this->getCode() == 'overboeking' || $this->getCode() == 'ebill')
				$order->update_status('on-hold', sprintf(__('%s created', 'woocommerce-sisow'), $this->getName()));
			else if($this->getCode() == 'focum' || $code == 'afterpay')
				$order->payment_complete($sisow->trxId);
			
			if($this->NeedRedirect())
				return array('result' => 'success', 'redirect' => $sisow->issuerUrl);
			else
			{
				// Return thankyou redirect
				return array(
					'result' 	=> 'success',
					'redirect'	=> $this->get_return_url( $order )
				);
			}
		}
	}

	public function process_refund( $order_id, $amount = null, $reason = '' ) 
	{
		$order = wc_get_order( $order_id );
		$sisow = new Sisow_Helper_Sisow(get_option('sisow_merchantid'), get_option('sisow_merchantkey'), get_option('sisow_shopid'));
		
		$refundid = $sisow->RefundRequest($order->get_transaction_id(), $amount);
		if($refundid > 0)
		{
			$order->add_order_note( sprintf( __( 'Refunded %s (Sisow amount: %s) - Refund ID: %s', 'woocommerce' ), $amount, $sisow->amount, $refundid ) );
			return true;
		}
		else 
		{
			$order->add_order_note( sprintf( __( 'Error while adding Sisow refund', 'woocommerce' ) ) );
			return false;
		}
	}
	
	private function GetWooCommerce2Data($order)
	{
		$arg = array();
		
		//add Shipping Address
		$arg['ipaddress'] = $_SERVER['REMOTE_ADDR'];
		$arg['shipping_firstname'] = $order->shipping_first_name;
		$arg['shipping_lastname'] = $order->shipping_last_name;
		$arg['shipping_mail'] = $order->billing_email;
		$arg['shipping_company'] = $order->shipping_company;
		$arg['shipping_address1'] = $order->shipping_address_1;
		$arg['shipping_address2'] = $order->shipping_address_2;
		$arg['shipping_zip'] = $order->shipping_postcode;
		$arg['shipping_city'] = $order->shipping_city;
		$arg['shipping_countrycode'] = $order->shipping_country;
		$arg['shipping_phone'] = array_key_exists($this->getCode() . '_phone', $_POST) ? $_POST[$this->getCode() . '_phone'] : $order->billing_phone;
		
		//add Billing Address
		$arg['billing_firstname'] = $order->billing_first_name;
		$arg['billing_lastname'] = $order->billing_last_name;
		$arg['billing_mail'] = $order->billing_email;
		$arg['billing_company'] = $order->billing_company;
		$arg['billing_address1'] = $order->billing_address_1;
		$arg['billing_address2'] = $order->billing_address_2;
		$arg['billing_zip'] = $order->billing_postcode;
		$arg['billing_city'] = $order->billing_city;
		$arg['billing_countrycode'] = $order->billing_country;
		$arg['billing_phone'] = array_key_exists($this->getCode() . '_phone', $_POST) ? $_POST[$this->getCode() . '_phone'] : $order->billing_phone;
		
		//$arg['weight'] = $order->;
		$arg['shipping'] = $order->order_shipping;
		//$arg['handling'] = $order->;
		//$arg['birthdate'] = $order->;
		$arg['tax'] = round($order->order_tax * 100.0);
		$arg['currency'] = $order->order_currency;
		
		//producten
        $item_loop = 0;
        if (sizeof($order->get_items()) > 0) : foreach ($order->get_items() as $item) :
                if ($item['qty']) :

                    $item_loop++;

                    $product = $order->get_product_from_item($item);

                    $_tax = new WC_Tax();

                    foreach ($_tax->get_shop_base_rate($product->tax_class) as $line_tax) {
                        $tax = $line_tax['rate'];
                    }
					
					

                    $arg['product_id_' . $item_loop] = $item['product_id'];
                    $arg['product_description_' . $item_loop] = $item['name'];
                    $arg['product_quantity_' . $item_loop] = $item['qty'];
                    $arg['product_netprice_' . $item_loop] = round($product->get_price_excluding_tax(), 2) * 100;
                    $arg['product_total_' . $item_loop] = round($item['line_total'] + $item['line_tax'], 2) * 100;
                    $arg['product_nettotal_' . $item_loop] = round($item['line_total'], 2) * 100;
                    $arg['product_tax_' . $item_loop] = round($item['line_tax'], 2) * 100;
                    $arg['product_taxrate_' . $item_loop] = (!isset($tax)) ? 0 : round($tax, 2) * 100;
                    $arg['product_weight_' . $item_loop] = round($product->weight, 2) * 100;
                endif;
            endforeach;
        endif;
		
		//verzendkosten
        if (isset($order->order_shipping)) {
            if ($order->order_shipping > 0) {
                $item_loop++;
                $arg['product_id_' . $item_loop] = 'shipping';
                $arg['product_description_' . $item_loop] = 'Verzendkosten';
                $arg['product_quantity_' . $item_loop] = '1';
                $arg['product_netprice_' . $item_loop] = round($order->order_shipping, 2) * 100;
                $arg['product_total_' . $item_loop] = round($order->order_shipping + $order->order_shipping_tax, 2) * 100;
                $arg['product_nettotal_' . $item_loop] = round($order->order_shipping, 2) * 100;
                $arg['product_tax_' . $item_loop] = round($order->order_shipping_tax, 2) * 100;
                $arg['product_taxrate_' . $item_loop] = round((($arg['product_tax_' . $item_loop] * 100.0) / $arg['product_nettotal_' . $item_loop])) * 100;
            }
        }
		
		//fees
		foreach($order->get_fees() as $fee)
		{			
			$item_loop++;
			$arg['product_id_' . $item_loop] = 'fee' . $item_loop;
			$arg['product_description_' . $item_loop] = $fee['name'];
			$arg['product_quantity_' . $item_loop] = '1';
			$arg['product_netprice_' . $item_loop] = round($fee['line_total'], 2) * 100;
			$arg['product_total_' . $item_loop] = round($fee['line_total'] + $fee['line_tax'], 2) * 100;
			$arg['product_nettotal_' . $item_loop] = round($fee['line_total'], 2) * 100;
			$arg['product_tax_' . $item_loop] = round($fee['line_tax'], 2) * 100;
			$arg['product_taxrate_' . $item_loop] = round((($arg['product_tax_' . $item_loop] * 100.0) / $arg['product_nettotal_' . $item_loop])) * 100;
		}
		
		return $arg;
	}	
	
	private function GetWooCommerce3Data($order)
	{
		$arg = array();
		
		//add Shipping Address
		$arg['ipaddress'] = $_SERVER['REMOTE_ADDR'];
		$arg['shipping_firstname'] = $order->get_shipping_first_name();
		$arg['shipping_lastname'] = $order->get_shipping_last_name();
		$arg['shipping_mail'] = $order->get_billing_email();
		$arg['shipping_company'] = $order->get_shipping_company();
		$arg['shipping_address1'] = $order->get_shipping_address_1();
		$arg['shipping_address2'] = $order->get_shipping_address_2();
		$arg['shipping_zip'] = $order->get_shipping_postcode();
		$arg['shipping_city'] = $order->get_shipping_city();
		$arg['shipping_countrycode'] = $order->get_shipping_country();
		$arg['shipping_phone'] = array_key_exists($this->getCode() . '_phone', $_POST) ? $_POST[$this->getCode() . '_phone'] : $order->get_billing_phone();
		
		//add Billing Address
		$arg['billing_firstname'] = $order->get_billing_first_name();
		$arg['billing_lastname'] = $order->get_billing_last_name();
		$arg['billing_mail'] = $order->get_billing_email();
		$arg['billing_company'] = $order->get_billing_company();
		$arg['companyname'] = $order->get_billing_company();
		$arg['billing_address1'] = $order->get_billing_address_1();
		$arg['billing_address2'] = $order->get_billing_address_2();
		$arg['billing_zip'] = $order->get_billing_postcode();
		$arg['billing_city'] = $order->get_billing_city();
		$arg['billing_countrycode'] = $order->get_billing_country();
		$arg['billing_phone'] = array_key_exists($this->getCode() . '_phone', $_POST) ? $_POST[$this->getCode() . '_phone'] : $order->get_billing_phone();
		
		//$arg['weight'] = $order->;
		$arg['shipping'] = round($order->get_shipping_total() * 100.0);
		//$arg['handling'] = $order->;
		//$arg['birthdate'] = $order->;
		$arg['tax'] = round($order->get_cart_tax() * 100.0);
		$arg['currency'] = $order->get_currency();
		
		//producten
        $item_loop = 0;
        if (sizeof($order->get_items()) > 0) : foreach ($order->get_items() as $item) :
                if ($item['qty']) :

                    $item_loop++;

                    $product = $order->get_product_from_item($item);

                    $_tax = new WC_Tax();

                    foreach ($_tax->get_shop_base_rate($product->get_tax_class()) as $line_tax) {
                        $tax = $line_tax['rate'];
                    }
					
                    $arg['product_id_' . $item_loop] = $item['product_id'];
                    $arg['product_description_' . $item_loop] = $item['name'];
                    $arg['product_quantity_' . $item_loop] = $item['qty'];
                    $arg['product_netprice_' . $item_loop] = round($item['line_total'] / $item['qty'], 2) * 100;
                    $arg['product_total_' . $item_loop] = round($item['line_total'] + $item['line_tax'], 2) * 100;
                    $arg['product_nettotal_' . $item_loop] = round($item['line_total'], 2) * 100;
                    $arg['product_tax_' . $item_loop] = round($item['line_tax'], 2) * 100;
                    $arg['product_taxrate_' . $item_loop] = (!isset($tax)) ? 0 : round($tax, 2) * 100;
                    $arg['product_weight_' . $item_loop] = round($product->get_weight(), 2) * 100;
                endif;
            endforeach;
        endif;
		
		//verzendkosten
		$shipping = $order->get_shipping_total();
		if ($shipping > 0) {
			$item_loop++;
			$arg['product_id_' . $item_loop] = 'shipping';
			$arg['product_description_' . $item_loop] = 'Verzendkosten';
			$arg['product_quantity_' . $item_loop] = '1';
			$arg['product_netprice_' . $item_loop] = round($order->get_shipping_total(), 2) * 100;
			$arg['product_total_' . $item_loop] = round($order->get_shipping_total() + $order->get_shipping_tax(), 2) * 100;
			$arg['product_nettotal_' . $item_loop] = round($order->get_shipping_total(), 2) * 100;
			$arg['product_tax_' . $item_loop] = round($order->get_shipping_tax(), 2) * 100;
			$arg['product_taxrate_' . $item_loop] = round((($arg['product_tax_' . $item_loop] * 100.0) / $arg['product_nettotal_' . $item_loop])) * 100;
		}
		
		//fees
		foreach($order->get_fees() as $fee)
		{			
			$taxrate = 0;
			$item_loop++;
			
			$taxes = $fee->get_taxes();
			if(count($taxes["total"] == 1))
			{
				$taxrate = ($taxes["total"]["1"] * 100.0) / $fee->get_total();
			}
			
			$arg['product_id_' . $item_loop] = 'fee' . $item_loop;
			$arg['product_description_' . $item_loop] = $fee->get_name();
			$arg['product_quantity_' . $item_loop] = $fee->get_quantity();
			$arg['product_netprice_' . $item_loop] = round($fee->get_total(), 2) * 100;
			$arg['product_total_' . $item_loop] = round($fee->get_total() + $fee->get_total_tax(), 2) * 100;
			$arg['product_nettotal_' . $item_loop] = round($fee->get_total(), 2) * 100;
			$arg['product_tax_' . $item_loop] = round($fee->get_total_tax(), 2) * 100;
			$arg['product_taxrate_' . $item_loop] = round($taxrate) * 100;
		}
				
		return $arg;
	}
}