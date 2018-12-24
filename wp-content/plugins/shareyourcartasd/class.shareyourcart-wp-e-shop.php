<?php
/**
 *	CLASS: Share Your Cart Wordpress WP E-Shop
 *	AUTHOR: Barandi Solutions
 *	COUNTRY: Romania
 *	EMAIL: vlad.barliba@barandisolutions.ro
 *	VERSION : 2.0
 *	DESCRIPTION: Compatible with WP E-Shop 6.2.8+
 *     Copyright (C) 2011 Barandi Solutions
 */

require_once("class.shareyourcart-wp.php");

if(!class_exists('ShareYourCartWPEShop',false)){

class ShareYourCartWPEShop extends ShareYourCartWordpressPlugin {

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

		return is_plugin_active( 'eshop/eshop.php' );
	}

	/**
	 *
	 * Get the secret key
	 *
	 */
	protected function getSecretKey()
	{
		return 'e77348b4-f280-4e8a-bb0f-5f409bc72a55';
	}
	
	/**
	*
	* Return the jQuery sibling selector for the cart button
	*
	*/
	protected function getCartButtonPosition(){
		$selector = parent::getCartButtonPosition();
		return (!empty($selector) ? $selector : "/*before*/ .cartopt p");
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

		/**** CHECKOUT PAGE *****/
		if(version_compare(ESHOP_VERSION,'6.2.8') >= 0)
		{
			remove_shortcode('eshop_show_checkout');
			add_shortcode('eshop_show_checkout', array(&$this, 'cartHook'));

			remove_shortcode('eshop_show_cart');
			add_shortcode('eshop_show_cart', array(&$this, 'cartHook'));

			//remove_shortcode('eshop_details');
			//add_shortcode('eshop_details', 'shareyourcart_eshop_details');
		}
	}
	
	/*************
	*
	* Called when Wordpress has been initialized
	*
	************/
	public function processInit(){
	
		if(isset($_REQUEST['action'])){
			switch($_REQUEST['action']){
			
			case 'shareyourcart_eshop':
				$this->buttonCallback();
				break;
				
			case 'shareyourcart_eshop_coupon':
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

		$callback_url = get_bloginfo('wpurl').'/?action=shareyourcart_eshop';

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

		return isset($_REQUEST['p']) ;  //TODO: this is not working. it will break when permalinks are used
	}

	/*
	 *
	 * Called when the button is pressed
	 *
	 */
	public function buttonCallback(){
		global  $blog_id, $eshopoptions, $post;
		if(!$this->isCartActive()) return;

		//specify the parameters
		$params = array(
			'callback_url' => get_bloginfo('wpurl').'/?action=shareyourcart_eshop_coupon'.(isset($_REQUEST['p']) ? '&p='.$_REQUEST['p'] : '' ),
			'success_url' => get_permalink($eshopoptions['cart']),
			'cancel_url' => get_permalink($eshopoptions['cart']),
		);

		//there is no product set, thus send the products from the shopping cart
		if(!isset($_REQUEST['p']))
		{
			$eshopcartarray = $_SESSION['eshopcart'.$blog_id];

			foreach($eshopcartarray as $productid => $opt)
			{
				$post_id = $opt['postid'];
					
				//get the product data from the db
				$eshop_product=maybe_unserialize(get_post_meta($post_id, '_eshop_product','true'));
				$img = wp_get_attachment_image_src(get_post_thumbnail_id($post_id));

				$params['cart'][] = array(
			'item_name' => get_the_title($post_id),
			'item_description' => $eshop_product['description'],
			'item_url' => get_permalink($post_id),
			'item_price' => sprintf( __('%1$s%2$s','eshop'), $currsymbol, number_format_i18n($opt['price'],__('2','eshop'))),
			'item_picture_url' =>  !empty($img) ? $img[0] : '',
				);
			}
		}
		else
		{
			$the_query = new WP_Query( array(
		'post_type' => 'any',
		'p'  => $_REQUEST['p'],
			));
			$posts = $the_query->get_posts();
			$post = $posts[0];
			setup_postdata($post);

			//get the product information from the db
			$eshop_product=maybe_unserialize(get_post_meta($post->ID, '_eshop_product','true'));
			$img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID));

			$params['cart'][] = array(
		'item_name' => get_the_title(),
		'item_url' => get_permalink(),
		'item_price' => sprintf( __('%1$s%2$s|1-currency symbol 2-amount','eshop'), $currsymbol, number_format_i18n($eshop_product['products'][1]['price'],__('2','eshop'))),
		'item_description' => $eshop_product['description'],
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

		global $wpdb, $blog_id, $eshopcartarray;

		$eshopcartarray = $_SESSION['eshopcart'.$blog_id];
	}

	/**
	 *
	 * 	 Insert coupon in database
	 *
	 */
	protected function saveCoupon($token, $coupon_code, $coupon_value, $coupon_type, $product_unique_ids = array()) {
		global $wpdb, $blog_id, $eshopcartarray,$eshopoptions, $shiparray, $post;

		if(!isset($_REQUEST['p'])) //there is no product specified, so look at the shopping cart
		{
			//$eshopcartarray = $_SESSION['eshopcart'.$blog_id];

			//calculate the total amount of the cart
			$total = 0;
			
			foreach($eshopcartarray as $productid => $opt)
			{
				$total += $opt['price'] * $opt['qty'];
			}			
		}
		else
		{
			//get the product
			$eshop_product=maybe_unserialize(get_post_meta($_REQUEST['p'], '_eshop_product','true'));

			//get it's price
			$total = $eshop_product['products'][1]['price'];
		}
		
		switch($coupon_type)
		{
			case 'amount':
				{
					//since eShop does not support fixed amount coupons, convert it to a % one
					$coupon_value = ($coupon_value / ((float)$total));
					$eshop_code_type =1;
					break;
				}
			case 'percent': $eshop_code_type = 1; break;
			case 'free_shipping' :
				{
					$coupon_value = 0; //put this 0 in order not to interfere with other calculus
					$eshop_code_type = 4;
					break;
				}
			default : $eshop_code_type = 1;
		}

		$coupon_data = array(
			'dtype' => $eshop_code_type,
			'disccode' => $coupon_code,
			'percent' => $coupon_value,
			'remain' => 1,
			'enddate' => date('Y-m-d', strtotime("now +1 day")),
			'live' => 'yes',
		);

		if($wpdb->insert($wpdb->prefix.'eshop_discount_codes',$coupon_data) === FALSE)
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

	}

	/**
	 *
	 * Append the button to the checkout page
	 *
	 **/
	function cartHook($atts, $content = '', $code=""){
		global  $blog_id;

		$output = '';

		//if there is at least an item in the cart
		if(!empty($_SESSION['eshopcart'.$blog_id])&&!get_option('_shareyourcart_hide_on_checkout'))
		{

			$output = $this->getCartButton();
		}

		switch ($code)
		{
			case 'eshop_show_checkout':
				$result = eshop_show_checkout($atts, $content);
				break;
			case 'eshop_show_cart':
				$result = eshop_show_cart($atts, $content);
				break;
			default:
				$result = '';
				break;
		}

		return $output .$result ;
	}
}

//TODO: see why this is not used
add_action(ShareYourCartWordpressPlugin::getPluginFile(), array('ShareYourCartWPEShop','uninstallHook'));

} //END IF
?>