<?php
/* 
 * 
Plugin Name: ShareYourCart
Plugin URI: http://www.shareyourcart.com
Description: <strong>Increase your social media exposure by 10%!</strong> ShareYourCart helps you get more customers by motivating satisfied clients to talk about your products. 
Version: 1.11.7
Author: Barandi Solutions
Author URI: http://www.barandisolutions.com
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/


require_once("class.shareyourcart-wp-e-commerce.php");
require_once("class.shareyourcart-estore.php");
require_once("class.shareyourcart-wp-e-shop.php");
require_once("class.shareyourcart-wpstorecart.php");
require_once("class.shareyourcart-wp-woocommerce.php");
require_once("class.shareyourcart-thecartpress.php");

//make sure this is the last one loaded
require_once("class.shareyourcart-wp-lite.php");


new ShareYourCartWPECommerce();
new ShareYourCartEStore();
new ShareYourCartWPEShop();
new ShareYourCartWPStoreCart();
new ShareYourCartWooCommerce();
new ShareYourCartTheCartPress();

//make sure this is the last one loaded
new ShareYourCartWPLite();
