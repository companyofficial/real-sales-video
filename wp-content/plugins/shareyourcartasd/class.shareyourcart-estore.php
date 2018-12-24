<?php
/**
 *	CLASS: Share Your Cart Wordpress WP eStore
 *	AUTHOR: Barandi Solutions
 *	COUNTRY: Romania
 *	EMAIL: catalin.paun@barandisolutions.ro
 *	VERSION : 2.0
 *	DESCRIPTION: Compatible with eStore
 *     Copyright (C) 2011 Barandi Solutions
 */

require_once("class.shareyourcart-wp.php");

if(!class_exists('ShareYourCartEStore',false)){

class ShareYourCartEStore extends ShareYourCartWordpressPlugin {
	
	/**
	*
	* Check if WP E-Commerce is Active
	*
	*/
	protected function isCartActive()
	{
		//check if wp-ecommerce is active
		if (!function_exists( 'is_plugin_active' ) )
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	
		return is_plugin_active( 'wp-cart-for-digital-products/wp_cart_for_digital_products.php' );
	}
	
	/**
	*
	* Get the secret key
	*
	*/
	protected function getSecretKey()
	{
		return '9d5db5f3-ec0a-4222-957a-462b90116d74';
	}
	
	/**
	*
	* Return the jQuery sibling selector for the cart button
	*
	*/
	protected function getCartButtonPosition(){
		$selector = parent::getCartButtonPosition();
		return (!empty($selector) ? $selector : "/*before*/ .eStore_empty_cart_button");
	}
	
	/*
	*
	* Extend the base class implementation
	*
	*/
	public function pluginsLoadedHook() {
		
		parent::pluginsLoadedHook();
		
		if(!$this->isCartActive()) return;
		
		//instead of using wp_ajax, better hook at init function
		//wp_ajax is not allways reliable, as some plugins might affect
		//it's behavior
		add_action('init', array(&$this, 'processInit'));
		
		//hook the product. make sure it is executed BEFORE the eStore hook, which has priority 11
		add_filter('the_content',                                array(&$this, 'contentHook'), 10);

		//hook the cart
		remove_shortcode('wp_eStore_cart');
		add_shortcode('wp_eStore_cart', array(&$this,'cartHook'));
		remove_shortcode('wp_eStore_cart_when_not_empty');
		add_shortcode('wp_eStore_cart_when_not_empty', array(&$this,'cartHook'));
		remove_shortcode('wp_eStore_cart_fancy1');
		add_shortcode('wp_eStore_cart_fancy1', array(&$this,'cartHook'));
		remove_shortcode('wp_eStore_cart_fancy1_when_not_empty');
		add_shortcode('wp_eStore_cart_fancy1_when_not_empty', array(&$this,'cartHook'));
	}
	
	/*************
	*
	* Called when Wordpress has been initialized
	*
	************/
	public function processInit(){
	
		if(isset($_REQUEST['action'])){
			switch($_REQUEST['action']){
			
			case 'shareyourcart_estore':
				$this->buttonCallback();
				break;
				
			case 'shareyourcart_estore_coupon':
				$this->couponCallback();
				break;
			}
		}
	}
	
	/**
	*
	* Return the URL to be called when the button is pressed
	*
	*/
	public function getButtonCallbackURL(){
	
		global $wp_query;
		
		$callback_url = get_bloginfo('wpurl').'/?action=shareyourcart_estore';
		
		if($this->isSingleProduct())
		{
			//set the product id
			$callback_url .= '&p='. $wp_query->post->ID;
		}

		return $callback_url;
	}
	
	/*
	*
	* Check if this is a single product page
	*
	*/
	protected function isSingleProduct(){
		global $wp_query;

		$pattern = '#\[wp_eStore:product_id:.+:end]#';
		preg_match_all($pattern, $wp_query->post->post_content, $matches);
		
		// If nothing is found try the fancy scheme for eStore
		if(!$matches[0][0]) {
			$pattern = '#\[wp_eStore_fancy.+ id=.+]#';
			preg_match_all($pattern, $wp_query->post->post_content, $matches);	
		}

		// If multiple products are displayed on one page break;
		if(count($matches[0]) > 1) return false;
		
		// If nothing is found try the old scheme for eStore with the old shortcode
		if(!$matches[0][0]) {

			$pattern = '#\wp_eStore_add_to_cart#';
			preg_match_all($pattern, $wp_query->post->post_content, $matches);
			
		}
		
		// If we found any match
		if($matches[0][0]) {
			
			// If the pattern is found verifies that we are not on a category listing page
			if(is_single() or is_page()) return true;
			
		}
		
		// Return false otherwise
		return false;
	}
	
	/*
	* 
	* Called when the content is rendered
	*
	*/
	public function contentHook($content){
		
		//append the button before the shortcode
		return preg_replace_callback('#\[wp_eStore:product_id:.+:end]#', 
				array(&$this,'productMatch'), 
				$content
				);
	}
	
	/*
	* 
	* Called when a product shortcode is found
	*
	*/
	protected function productMatch($match){
	
		return $this->getProductButton().$match[0];
	}

	/*
	* 
	* Called when the cart is rendered
	*
	*/	
	public function cartHook($atts, $content=null, $code="" ){
	
		$output = '';
		
		if(digi_cart_not_empty()){
		
			$output .= $this->getCartButton();
		}
		
		switch($code){
		
			case 'wp_eStore_cart':
				$functionName = 'wp_digi_cart_always_show';
				break;
				
			case 'wp_eStore_cart_fancy1':
				$functionName = 'eStore_shopping_cart_fancy1';
				break;
				
			case 'wp_eStore_cart_fancy1_when_not_empty':
				$functionName = 'eStore_shopping_cart_fancy1_when_not_empty';
				break;
				
			case 'wp_eStore_cart_when_not_empty':
				$functionName = 'eStore_cart_when_not_empty';
				break;
		}
		
		
		return $output.call_user_func($functionName, $atts, $content, $code);
	}
	
	/*
	*
	* Called when the button is pressed
	*
	*/
	public function buttonCallback(){
	
		global  $wpdb;
	
		if(!$this->isCartActive()) return;
		
		//specify the parameters
		$params = array(
			'callback_url' => get_bloginfo('wpurl').'/?action=shareyourcart_estore_coupon'.(isset($_REQUEST['p']) ? '&p='.$_REQUEST['p'] : '' ),
			'success_url' => digi_cart_current_page_url(),
			'cancel_url' => digi_cart_current_page_url(),
		);
	
		//there is no product set, thus send the products from the shopping cart
		if(!isset($_REQUEST['p']))
		{	
			global $wp_query;
			
			if(!WP_ESTORE_CURRENCY_SYMBOL) {
				if(get_option('cart_currency_symbol'))
				$currency = get_option('cart_currency_symbol');
				else 
				$currency = "USD";
			} else {
				$currency = WP_ESTORE_CURRENCY_SYMBOL;
			}
			
			//add the cart items to the arguments
                        if($_SESSION['eStore_cart'] && count($_SESSION['eStore_cart']) > 0) {
                            foreach ($_SESSION['eStore_cart'] as $item) {

                                    $params['cart'][] = array(
                                    "item_name" => $item['name'],
                                    "item_url" => $item['cartLink'],
                                    "item_price" => print_digi_cart_payment_currency($item['price'], $currency, "."),
                                    "item_description" => "", 
                                    "item_picture_url" => $item['thumbnail_url'],
                                    );

                            } //cart loop
                        }
		}
		else
		{
			//get the details of the specified product
			global $wp_query;
			
			$sql  = "SELECT * FROM " . $wpdb->prefix . "posts WHERE id = " . $_REQUEST['p'];
			$results = $wpdb->get_results($sql);	
			
			$pattern = '#\[wp_eStore:product_id:.+:end]#';
			$old = false;
			$fancy = false;
			preg_match_all($pattern, $results[0]->post_content, $matches);
			
			// Maybe it's the fancy alternative
			if(!$matches[0][0]) {
				$pattern = '#\[wp_eStore_fancy.+ id=.+]#';
				$fancy = true;
				preg_match_all($pattern, $results[0]->post_content, $matches);	
			}
			
			// Maybe it's old shortcode
			if(!$matches[0][0]) {
				$pattern = '#\?wp_eStore_add_to_cart=.+"]#';
				$old = true;
				$fancy = false;
				preg_match_all($pattern, $results[0]->post_content, $matches);
			}
			
			if($matches[0][0]) {
				foreach ($matches[0] as $match) {
					if(!$old) $pattern = '[wp_eStore:product_id:';
					else $pattern = '?wp_eStore_add_to_cart=';
					
					if($fancy) {
						$m = str_replace('[wp_eStore_fancy1 id=', '', $match);
						$m = str_replace('[wp_eStore_fancy2 id=', '', $m);
					} else {
						$m = str_replace($pattern, '', $match);
					}
					
					if(!$old) $pattern = ':end]';
					else  $pattern = '"]';
					
					if($fancy) $m = str_replace (']', '', $m);
					else $m = str_replace ($pattern, '', $m);
					
					$pieces = explode('|',$m);
					$key = $pieces[0];
				}
			}

			// Query the database to find the details of the product
			$sql2  = "SELECT * FROM " . $wpdb->prefix . "wp_eStore_tbl WHERE id = " . $key;
			$results2 = $wpdb->get_results($sql2);	
			
			$img = wp_get_attachment_image_src(get_post_thumbnail_id($results[0]->ID));
			
			$params['cart'][] = array(
			'item_name' => $results2[0]->name,
			'item_url' => $results[0]->guid,
			'item_price' => print_digi_cart_payment_currency($results2[0]->price, WP_ESTORE_CURRENCY_SYMBOL),
			'item_description' => $results2[0]->description,
			'item_picture_url' =>  !empty($img) ? $img[0] : '',
			);                          
		}
	
		try 
		{
			$this->startSession($params);
		} 
		catch(Exception $e) 
		{
			//display the error to the user
			echo $e->getMessage();
		}
		exit;
	}
	
	/**
	*
	* Load the cart data
	*
	*/
	protected function loadSessionData() {
	}
	
	/**
     *
     * 	 Insert coupon in database
     *
     */
	protected function saveCoupon($token, $coupon_code, $coupon_value, $coupon_type, $product_unique_ids = array()) {
		global $wpdb;
		
		switch($coupon_type)
		{
			case 'amount': $discount_type = 1; break;
            case 'percent': $discount_type = 0; break;
            default : $discount_type = 0;
		}

		$coupon_data = array(
			'coupon_code' => $coupon_code,
			'discount_value' => $coupon_value,
			'discount_type' => $discount_type,
			'active' => 'Yes',
			'redemption_limit' => 1,
			'redemption_count' => 0,
			'property' => 1,
			'logic' => 1,
			'value' => 0,
		);
		
		//since wordpress thinks the ACTIVE column is by default a number
		//we need to specify the format manually for ALL fields
		$coupon_data_format = array(
		    '%s', //coupon_code
			'%s', //discount_value
			'%s', //discount_type
			'%s', //active
			'%d', //redemption_limit
			'%d', //redemption_count
			'%d', //property
			'%d', //logic
			'%d', //value
		);
			
		
		if(version_compare(get_option("wp_eStore_db_version"),'6.8')  >= 0) {
			$coupon_data['expiry_date'] = date('Y-m-d', strtotime('now +1 day'));
			$coupon_data_format[] = '%s'; //expiry_date
		}
		
		
		
		if($wpdb->insert($wpdb->prefix . "wp_eStore_coupon_tbl",$coupon_data, $coupon_data_format ) === FALSE)
		{
			//the save failed
			throw new Exception(SyC::t('sdk','Failed to save the coupon'));
		}
		
		//call the base class method
		parent::saveCoupon($token, $coupon_code, $coupon_value, $coupon_type);
	}
	
	/**
	*
	* Apply the coupon directly to the current shopping cart
	*
	*/
	protected function applyCoupon($coupon_code){
		
		//apply the coupon to the shopping cart
		$_POST['coupon_code'] = $coupon_code;        
		eStore_apply_discount($coupon_code);
	}
}

//TODO: see why this is not used
add_action(ShareYourCartWordpressPlugin::getPluginFile(), array('ShareYourCartEStore','uninstallHook'));

} //END IF
?>