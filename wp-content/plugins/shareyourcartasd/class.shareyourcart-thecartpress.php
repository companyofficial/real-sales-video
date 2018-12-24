<?php
/**
 * This file is part of TheCartPress-ShareYourCart.
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once( 'class.shareyourcart-wp.php' );

if(!class_exists('ShareYourCartTheCartPress',false)){

class ShareYourCartTheCartPress extends ShareYourCartWordpressPlugin {

	/**
	 * Check if TheCartPress is Active
	 */
	protected function isCartActive() {
		//check if thecartpress is active
		if ( ! function_exists( 'is_plugin_active' ) ) require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		return is_plugin_active( 'thecartpress/TheCartPress.class.php' );
	}

	/**
	 * Get the secret key
	 */
	protected function getSecretKey() {
		return 'e9c93b7f-9562-475c-8d9f-e2c620aa5fd2';
	}
	
	/**
	*
	* Return the jQuery sibling selector for the product button
	*
	*/
	protected function getProductButtonPosition(){
		$selector = parent::getProductButtonPosition();
		return (!empty($selector) ? $selector : ".tcp_unit_price");
	}
	
	/**
	*
	* Return the jQuery sibling selector for the cart button
	*
	*/
	protected function getCartButtonPosition(){
		$selector = parent::getCartButtonPosition();
		return (!empty($selector) ? $selector : "");  //TODO: create the selector for the cart button
	}

	/**
	 * Extend the base class implementation
	 */
	public function pluginsLoadedHook() {	
		parent::pluginsLoadedHook();
		if ( ! $this->isCartActive() ) return;
		add_action( 'init', array(&$this, 'init'));
		
		add_action( 'tcp_buy_button_bottom', array( &$this, 'showProductButton' ) );
		add_action( 'tcp_shopping_cart_after_cart', array( &$this, 'showCartButton' ) );
	}

	public function init() {
		if( isset( $_REQUEST['action'] ) ) switch( $_REQUEST['action'] ) {
			case 'shareyourcart_thecartpress':
				$this->buttonCallback();
				break;	
			case 'shareyourcart_thecartpress_coupon':
				$this->couponCallback();
				break;
			}
	}

	/**
	 * Gets the current product details
	 */
	public function getCurrentProductDetails() {
		//if this is not a single product page, do not return anything
		if ( ! $this->isSingleProduct() ) return false;
		$thumbnail = tcp_get_the_thumbnail();
		if ( isset( $thumbnail['url'] ) ) $url = $thumbnail['url'];
		else $url = '';
		return array (
			'item_name'			=> tcp_get_the_title(),
			'item_description'	=> substr( tcp_get_the_content(), 0, 255 ),
			'item_url'			=> tcp_get_permalink(),
			'item_price'		=> tcp_get_the_price(), 
			'item_picture_url'	=> $url,
		);
	}

	/**
	 * Returns the URL to be called when the button is pressed
	 */
	public function getButtonCallbackURL() {
		global $wp_query;
		$callback_url = get_bloginfo('wpurl').'/?action=shareyourcart_thecartpress';
		if ( $this->isSingleProduct() ) {
			//set the product id
			$callback_url .= '&p='. $wp_query->post->ID;
		}
		return $callback_url;
	}

	/**
	 * Check if this is a single product page
	 */
	protected function isSingleProduct() {
		global $wp_query;
		return tcp_is_saleable_post_type( $wp_query->post->post_type ) && ! is_archive() && $wp_query->post_count <= 1;
	}

	/**
	 * Called when the button is pressed
	 */
	public function buttonCallback() {
		if ( ! $this->isCartActive() ) return;
		//specify the parameters
		$params = array(
			'callback_url'	=> get_bloginfo('wpurl').'/?action=shareyourcart_thecartpress_coupon' . ( isset( $_REQUEST['p'] ) ? '&p='.$_REQUEST['p'] : '' ),
			'success_url'	=> tcp_get_the_shopping_cart_url(),
			'cancel_url'	=> tcp_get_the_shopping_cart_url(),
		);

		//there is no product set, thus send the products from the shopping cart
		if ( ! isset( $_REQUEST['p'] ) ) {	
			//add the cart items to the arguments
			$shoppingCart = TheCartPress::getShoppingCart();
			foreach( $shoppingCart->getItems() as $item ) {
				$params['cart'][] = array(
					'item_name'			=> $item->getTitle(),
					'item_url'			=> tcp_get_permalink( $item->getPostId() ),
					'item_price'		=> $item->getPriceToShow(),
					'item_description'	=> tcp_get_the_content( $item->getPostId() ), 
					'item_picture_url'	=> tcp_get_the_thumbnail( $item->getPostId() ),
				);
			}
		} else {
			$id = $_REQUEST['p'];
			$params['cart'][] = array(
				'item_name'			=> tcp_get_the_title( $id ),
				'item_description'	=> tcp_get_the_content( $id ),
				'item_url'			=> tcp_get_permalink( $id ),
				'item_price'		=> tcp_get_the_price_to_show( $id ), 
				'item_picture_url'	=> tcp_get_the_thumbnail( $id ),
			);
		}

		try {
			$this->startSession( $params );
		} catch( Exception $e ) {
			//display the error to the user
			echo $e->getMessage();
		}
		exit;
	}

	/**
	 * Load the cart data
	 */
	protected function loadSessionData() {
	}

	/**
	 * Insert coupon in database
	 */
	protected function saveCoupon($token, $coupon_code, $coupon_value, $coupon_type, $product_unique_ids = array()) {
		switch($coupon_type) {
		case 'amount':
			$discount_type = 'amount';
			break;
		case 'percent':
			$discount_type = 'percent';
			break;
		case 'free_shipping' :
			$discount_type = 'freeshipping';
			break;
		default : 
			$discount_type = 'amount';
		}
		$from_date = date( 'Y' ) . '-' . date( 'm' ) . '-' . date( 'd' );
		if ( function_exists( 'tcp_add_coupon' ) ) tcp_add_coupon( true, $coupon_code, $coupon_type, $coupon_value, $from_date );
		//call the base class method
		parent::saveCoupon( $token, $coupon_code, $coupon_value, $coupon_type );
	}

	/**
	 * Apply the coupon directly to the current shopping cart
	 */
	protected function applyCoupon( $coupon_code ){	
		//apply the coupon to the shopping cart
		$_SESSION['tcp_checkout']['coupon_code'] = $coupon_code;
	}
}

//TODO: see why this is not used
add_action(ShareYourCartWordpressPlugin::getPluginFile(), array('ShareYourCartTheCartPress','uninstallHook'));

} //ENDIF

?>
