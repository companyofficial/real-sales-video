<?php
/**
 *	CLASS: Share Your Cart Wordpress
 *	AUTHOR: Barandi Solutions
 *	COUNTRY: Romania
 *	EMAIL: catalin.paun@barandisolutions.ro
 *	VERSION : 2.0
 *	DESCRIPTION: Lite Wordpress Integration
 *     Copyright (C) 2011 Barandi Solutions
 */

require_once("class.shareyourcart-wp.php");

if(!class_exists('ShareYourCartWPLite',false)){

class ShareYourCartWPLite extends ShareYourCartWordpressPlugin {
    
	/**
	*
	* Check if WP E-Commerce is Active
	*
	*/
	protected function isCartActive()
	{
		//activate the lite version ONLY when there is no other cart active
		foreach(self::$_INSTANCES as $instance)
		{
			//if we can find another WP instance 
			//that is currently active, then this means
			//the current one IS NOT ACTIVE
			if($instance != $this && $instance->isCartActive())
				return false;
		
		}
		
		return true;
	}
	
	/**
	*
	* Get the secret key
	*
	*/
	protected function getSecretKey()
	{
		return 'a0f41adf-8d07-4703-b141-977592ad098b';
	}
	
	/*
	*
	* Extend the base class implementation
	*
	*/
	public function pluginsLoadedHook() {
		
		parent::pluginsLoadedHook();
		
	}
	
	public function showAdminMenu() {
	
		parent::showAdminMenu();
		
		if(!$this->isCartActive()) return;
		
		//Add the meta options to supported
		add_meta_box( 'shareyourcart_metabox', 'ShareYourCart', array(&$this,'showAdminPostDetailsMetabox'), 'post', 'normal', 'high' );
		add_meta_box( 'shareyourcart_metabox', 'ShareYourCart', array(&$this,'showAdminPostDetailsMetabox'), 'page', 'normal', 'high' );
		add_action( 'save_post', array(&$this,'saveAdminPostDetails') );
	}
	
	/**
	*
	* Get the current product details
	*
	*/
	public function getCurrentProductDetails(){

		global $post;
		
		//if this is not a single product page, do not return anything
		if(!$this->isSingleProduct()) return FALSE;
	
		//get the page/post title
		$title = the_title('', '', false);
		
		//get the page description
		$description = get_post_meta( $post->ID, 'syc_description', true);
		if(empty($description)) $description = trim(get_the_excerpt());
	
		//get the post image
		$image = null;
		if(has_post_thumbnail()){
			$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id());
			if(is_array($thumbnail) && count($thumbnail) > 0) //there is indeed a thumbnail attached
				$image = $thumbnail[0]; //get it's source			
		}
		
		return array(
			"item_name" => htmlspecialchars($title),
			"item_description" => htmlspecialchars($description),
			"item_url" => get_permalink(),
			"item_price" => htmlspecialchars(get_post_meta( $post->ID , 'syc_price', true )), 
			"item_picture_url" => $image,
		);
	}
	
	
	/*
	*
	* Check if this is a single page
	*
	*/
	protected function isSingleProduct(){
		return is_single() || is_page();
	}
	
	/**
	 * show the metabox containing the post details
	 * @param null
	 * @return boolean
	 */
	public function showAdminPostDetailsMetabox($post) {

		$price = get_post_meta( $post->ID , 'syc_price', true );
		$description = get_post_meta( $post->ID, 'syc_description', true);

		include(dirname(__FILE__).'/views/post-meta.php');
	}

	/**
	 * saveAdminPostDetails
	 * @param null
	 * @return boolean
	 */
	public function saveAdminPostDetails($post_id) {
	
		// verify this came from the our screen and with proper authorisation,
		// because save_post can be triggered at other times		
		if ( !isset( $_POST['syc_nonce'] ) || !wp_verify_nonce( $_POST['syc_nonce'], $this->_PLUGIN_PATH )) {
			return;
		}
	
		if ( 'page' == $_POST['post_type'] ) {
			if ( !current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
		} else {
			if ( !current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		//save the price
		$price = isset( $_POST['syc_price'] ) ? trim( $_POST['syc_price'] ) : '';
		if ( !empty($price) ) {
			update_post_meta( $post_id, 'syc_price', $price );
		} else {
			delete_post_meta( $post_id, 'syc_price' );
		}
	
		//save the description
		$description = isset( $_POST['syc_description'] ) ? trim( $_POST['syc_description'] ) : '';
		if ( !empty($description) ) {
			update_post_meta( $post_id, 'syc_description', $description );
		} else {
			delete_post_meta( $post_id, 'syc_description' );
		}
	}
	
	/**
	*
	* The lite version has no callback, so return NULL
	*
	*/
	protected function getButtonCallbackURL(){
		return null;
	}
	
	/**
	*
	* This is not used here 
	*
	*/
	protected function loadSessionData() {
	}
	
	/**
	*
	* This is not used here
	*
	*/
	protected function applyCoupon($coupon_code){
	}
}

//TODO: see why this is not used
add_action(ShareYourCartWordpressPlugin::getPluginFile(), array('ShareYourCartWPLite','uninstallHook'));

} //END IF
?>
