<?php
/**
 *	CLASS: Share Your Cart Wordpress WP E-Commerce
 *	AUTHOR: Barandi Solutions
 *	COUNTRY: Romania
 *	EMAIL: catalin.paun@barandisolutions.ro
 *	VERSION : 2.0
 *	DESCRIPTION: Compatible with WP E-Commerce 3.7+
 *     Copyright (C) 2011 Barandi Solutions
 */

require_once("class.shareyourcart-wp.php");

if(!class_exists('ShareYourCartWPECommerce',false)){

class ShareYourCartWPECommerce extends ShareYourCartWordpressPlugin {
    
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
	
		return is_plugin_active( 'wp-e-commerce/wp-shopping-cart.php' );
	}
	
	/**
	*
	* Get the secret key
	*
	*/
	protected function getSecretKey()
	{
		return 'f41b15ac-b497-4a08-9840-99c057195a1a';
	}
	
	/**
	*
	* Return the jQuery sibling selector for the product button
	*
	*/
	protected function getProductButtonPosition(){
		$selector = parent::getProductButtonPosition();
		return (!empty($selector) ? $selector : ".wpsc_product_price");
	}
	
	/**
	*
	* Return the jQuery sibling selector for the cart button
	*
	*/
	protected function getCartButtonPosition(){
		$selector = parent::getCartButtonPosition();
		return (!empty($selector) ? $selector : ".wpsc_total_amount_before_shipping .pricedisplay");
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
		
		add_action('wpsc_top_of_products_page',                         array(&$this,'showProductButton')); //wp e-commerce v3.7+
		
		/**** CHECKOUT PAGE *****/
		if(version_compare(WPSC_VERSION,'3.8') >= 0)
			add_action('wpsc_before_shipping_of_shopping_cart',         array(&$this,'showCartButton'));  //wp e-commerce v3.8+
		else
			add_action('wpsc_before_form_of_shopping_cart',             array(&$this,'showCartButton')); //wp e-commerce v3.7
	}
	
	/*************
	*
	* Called when Wordpress has been initialized
	*
	************/
	public function processInit(){
	
		if(isset($_REQUEST['action'])){
			switch($_REQUEST['action']){
			
			case 'shareyourcart_wp_e_commerce':
				$this->buttonCallback();
				break;
				
			case 'shareyourcart_wp_e_commerce_coupon':
				$this->couponCallback();
				break;
			}
		}
	}
	
	/**
	*
	* Get the current product details
	*
	*/
	public function getCurrentProductDetails(){

		//if this is not a single product page, do not return anything
		if(!$this->isSingleProduct()) return FALSE;
	
		return array(
			"item_name" => wpsc_the_product_title(),
			"item_description" => substr(wpsc_the_product_description(), 0, 255),
			"item_url" => wpsc_the_product_permalink(),
			"item_price" => wpsc_the_product_price(), 
			"item_picture_url" => wpsc_the_product_image(),
		);
	}
	
	/**
	*
	* Return the URL to be called when the button is pressed
	*
	*/
	public function getButtonCallbackURL(){
	
		global $wp_query;
		
		$callback_url = get_bloginfo('wpurl').'/?action=shareyourcart_wp_e_commerce';
		
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
		
		return 'wpsc-product' == $wp_query->post->post_type && !is_archive() && $wp_query->post_count <= 1;
	}
	
	/*
	*
	* Called when the button is pressed
	*
	*/
	public function buttonCallback(){
	
		if(!$this->isCartActive()) return;
		
		//specify the parameters
		$params = array(
			'callback_url' => get_bloginfo('wpurl').'/?action=shareyourcart_wp_e_commerce_coupon'.(isset($_REQUEST['p']) ? '&p='.$_REQUEST['p'] : '' ),
			'success_url' => get_option('shopping_cart_url'),
			'cancel_url' => get_option('shopping_cart_url'),
		);
	
		//there is no product set, thus send the products from the shopping cart
		if(!isset($_REQUEST['p']))
		{	
			//add the cart items to the arguments
			while (wpsc_have_cart_items()) : wpsc_the_cart_item();

			$params['cart'][] = array(
			"item_unique_id" => wpsc_cart_item_name(),   //the unique id for wp e-commerce is it's name
			"item_name" => wpsc_cart_item_name(),
			"item_url" => wpsc_cart_item_url(),
			"item_price" => function_exists('wpsc_cart_single_item_price') ? wpsc_cart_single_item_price() : wpsc_cart_item_price(),
			"item_description" => '', //TODO: find a way to get the product description. wpsc_the_product_description() won't work as wpsc_cart_item_product_id() can return the variation id, which does not have a description
			"item_picture_url" => SyC::rel2abs(wpsc_cart_item_image(90,90),get_bloginfo('wpurl')),
			);
		
			endwhile; //cart loop
		}
		else
		{
			//get the details of the specified product
			global $wp_query;
			
			if(version_compare(WPSC_VERSION,'3.8') >= 0)
			{	
				//get the details of the specified product
				global $wp_query;
				$wp_query = new WP_Query(array(
				'post_type' => 'wpsc-product',
				'p'  => $_REQUEST['p'],
				)); 
			}
			else if (version_compare(WPSC_VERSION, '3.7') >= 0)
			{			
				//get the details of the specified product
				global $wpsc_query;
				$wpsc_query = new WPSC_Query(array(
				'product_id'  => $_REQUEST['p'],
				)); 
			}
			
			while (wpsc_have_products()) : wpsc_the_product();
			
			$params['cart'][] = array(
			"item_unique_id" => wpsc_the_product_title(), //the unique id for wp e-commerce is it's name
			"item_name" => wpsc_the_product_title(),
			"item_description" => substr(wpsc_the_product_description(), 0, 255),
			"item_url" => wpsc_the_product_permalink(),
			"item_price" => wpsc_the_product_price(), 
			"item_picture_url" => wpsc_the_product_image(),
			);
			
			endwhile;
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
	
		//initialize the cart object from the loaded $_SESSION
		if(version_compare(WPSC_VERSION,'3.8')>=0)
		{
			wpsc_core_setup_cart();
		}
		else //v3.7
		{
			wpsc_initialisation();
		}
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
			case 'amount': $discount_type = 0; break;
			case 'percent': $discount_type = 1; break;
			case 'free_shipping' : $discount_type = 2; break;
			default : $discount_type = 0;
		}
		
		//for wp E-commerce, the unique_ids are actually the item names
		$conditions = array();
		foreach($product_unique_ids as $product_name){
			$conditions[] = array(
				'property' => 'item_name',
				'logic' => 'equal',
				'value' => $product_name,
			);
		}

		$coupon_data = array(
			'coupon_code' => $coupon_code,
			'value' => $coupon_value,
			'is-percentage' => $discount_type,
			'use-once' => 1,
			'is-used' => 0,
			'active' => 1,
			'every_product' => 0,
			'start' => date('Y-m-d', strtotime("now")),
			'expiry' => date('Y-m-d', strtotime("now +1 day")),
			'condition' => serialize($conditions),
		);
		
		

		if($wpdb->insert(WPSC_TABLE_COUPON_CODES,$coupon_data) === FALSE)
		{
			//the save failed
			throw new Exception('Failed to save the coupon');
		}
		
		//call the base class method
		parent::saveCoupon($token, $coupon_code, $coupon_value, $coupon_type,$product_unique_ids);
	}
	
	/**
	*
	* Apply the coupon directly to the current shopping cart
	*
	*/
	protected function applyCoupon($coupon_code){
		
		//apply the coupon to the shopping cart
		$_POST['coupon_num'] = $coupon_code;
		wpsc_coupon_price();
	}
}

//TODO: see why this is not used
add_action(ShareYourCartWordpressPlugin::getPluginFile(), array('ShareYourCartWPECommerce','uninstallHook'));

} //END IF
?>