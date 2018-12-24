=== ShareYourCart ===
Plugin Name: ShareYourCart
Contributors: barandisolutions
Donate link: http://www.shareyourcart.com/
Tags: twitter, Facebook, tweet, affiliate, button, social, discount, coupon
Requires at least: 3.0
Tested up to: 3.3.2
Stable tag: 1.11.7

Increase by 10% the number of Facebook shares and Twitter tweets that your customers do about your business.
This means ShareYourCart� helps you get more customers by motivating satisfied customers to talk with their friends about your products. 

== Description ==
Increase by 10% the number of Facebook shares and Twitter tweets that your customers do about your business.

[ShareYourCart](http://www.shareyourcart.com/ "Share your cart on social media like facebook and twitter") enables owners to reward their customers for spreading the word about their products / services to their friends, by offering them a coupon code for their purchases, thus helping increase sales conversion

You can choose how much of a discount to give (in fixed amount, percentage, or free shipping) and to which social media channels it should it be applied. You can also define what the advertisement should say, so that it fully benefits your sales.

= Compatibility =
The button is currently fully compatible with:

* WP e-Commerce v3.7+
* eShop v6.2.8+
* WP eStore v3.4.9+
* wpStoreCart v2.5.1+
* WooCommerce v1.5.x+
* WooCommerce v1.6.x+
* TheCartPress v1.x+

For other shopping carts, it will fallback to the Lite implementation.

= Known Issues =
* WP e-Commerce v3.7 as well as eShop v6.2.8+ do not show the ShareYourCart button in the product page
* eShop v6.2.8 and v6.2.9 do not automatically apply the generated coupon to the user's cart, so he has to do it manually

== Installation ==
1. Upload the folder 'shareyourcart' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress

It's that easy!

== Frequently Asked Questions ==
Please check out our [support forums](http://shareyourcart.uservoice.com "ShareYourCart Support Forums"). This is also the place where you can submit your suggestions.

== Screenshots ==

1. The [ShareYourCart](http://www.shareyourcart.com/ "Share your cart on social media like facebook and twitter") button will appear on your website

2. By clicking the button, the customer can choose what type of social media channel to spread the word through. You can adjust the discount (in fixed amount, percentage, or free shipping) as well as how much should be given for each channel. The most important feature is the *message* sent, which you can easilly change to benefit your sales efforts

3. The customer will receive a coupon which he can apply to his cart 

And the best thing is the entire process is automatic, and it simply works.
Thus, you can focus on further building your business, and not on generating coupon codes.

== Changelog ==
= 1.11.7 =
* Upgrade to SDK 1.11
* FIX WooCommerce 2.0 compatibility

= 1.9.6 =
* Fix woocommerce coupons so that they are only applied to the shared product

= 1.9.5 =
* Compatibility with WooCommerce 1.6 Release
* Fixed the ShareYourCart button being shown twice on the product page

= 1.9.4 =
* Upgraded to SDK 1.9
* The ShareYourCart Button now appears on WooCommerce Variable Product pages
* Fixed: image is not shared for WooCommerce Products

= 1.8.3 =
* Added support for TheCartPress 1.x (Thanks to the TheCartPress team for helping)
* Improved positioning of the button so that it allows the user to better engage with the shop
* Added Debug mode for easier debugging
* Language is set according to the one set in your ShareYourCart account
* Fixed WooCommerce minor issues, like button not appearing of product's page, or sending of wrong product link to the API

= 1.7.2 =
* Added support for WooCommerce 1.5.x
* The custom button images are now saved in the Wordpress Uploads folder

= 1.7.1 =
* Fixed some path disclosure vulnerabilities in the SDK

= 1.6.1 =
* Added two new smaller types of buttons ( light / dark ). What do you think?
* Added multi-languagesupport
 
= 1.5 =
* Improved credential management. Now you have the option to recover your account directly in the admin, in case you lose them
* Added a heigh to the button, so that it does not overlap anything bellow it, commonly found in FireFox

= 1.4.2 =
Our latest release in 2011 fixes some small issues encountered on a few custom themes. Happy Hollidays!

= 1.4.1 =
* Switched the method of building the callback URL from wp-ajax to a normal post. wp-ajax was not reliable enough as some 3rd party plugins might make it mallfunction

= 1.4 =
* Completelly rewritten the plugin using the ShareYourCart SDK 2.0
* Added support to easily create custom buttons

= 1.3.4 =
* Fixed eStore issue where the shortcode does not appear anymore if the checkout checkbox is unchecked 

= 1.3.3 =
* Added support for older WP eStore Shopping Cart versions, like 3.4.9
* Improved CSS loading speed

= 1.3.2 =
* Added support for the WP eStore Shopping Cart

= 1.3.1 =
* Fixed but with older versions of PHP, which do not support inline functions

= 1.3 =
* Added support for custom buttons that can be styled according to your needs
* Added support for other shopping cart plugins, not just WP e-commerce or eShop

= 1.2.5 =
* Added support for WP e-commerce product variations. Now the user will share not only the product, but also the selected variation, thus making the message even more personal
* The ShareYourCart button now uses SSL if the shop uses HTTPS, and normal communication if it uses only HTTP, thus being compatible with the latest Firefox / Chrome security protections
* Fixed a bug where the ShareYourCart button is not clickable on some themes

= 1.2.4 =
* NEW: button now appears automatically on product pages

= 1.2.3 =
* Fixed plugin folder naming issue

= 1.2.2 =
* Improved shortcode documentation

= 1.2.1 =
* Fixed Configure button not working

= 1.2 =
* Made it compatible with the latest ShareYourCart API v1.2
* NEW: support for users to select exactly which friend to share their cart with, as well as attach a personal message to it
* NEW: if the cart contains more than one item, the user can now select which one to share with his friends
* Lot of bug fixes

= 1.1 =
Developer Version

= 1.0 =
First Release

== Upgrade Notice ==

= 1.2.1 =
Uninstall the old plugin and install the new one

= 1.0 - 1.2 =
There are no known issues when upgrading