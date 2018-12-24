=== WooCommerce - Sisow Payment Options ===
Plugin Name: Sisow Payment for WooCommerce
Contributors: sisow
Donate link: http://www.sisow.nl
Tags: Sisow, Afterpay, Belfius, bunq, iDEAL, Creditcard, WooCommerce, Payment, MisterCash, SofortBanking, OverBoeking, Ebill, Giftcard, PayPal, Visa, Mastercard, Maestro, Giropay, EPS, Focum, VVV, Homepay
Requires at least: 3.0.1
Tested up to: 4.9.4
Stable tag: 5.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Sisow Payment methods for WooCommerce 2.X and 3.X

== Description ==

This plug-in contains all the payment methods from Sisow for WooCommerce.
The plug-in works on WooCommerce 2.X and 3.X!

For now this plug-in contains the following payment methods:
<ul>
<li>Afterpay</li>
<li>Beflius Pay Button</li>
<li>bunq</li>
<li>Capayable Gespreid Betalen</li>
<li>CreditCard</li>
<li>Ebill</li>
<li>EPS</li>
<li>Focum Achteraf Betalen</li>
<li>iDEAL</li>
<li>iDEAL QR</li>
<li>ING Home'Pay</li>
<li>Giropay</li>
<li>Maestro</li>
<li>Mastercard</li>
<li>MisterCash</li>
<li>SofortBanking</li>
<li>OverBoeking</li>
<li>PayPal</li>
<li>Visa</li>
<li>VVV GiftCard</li>
<li>Webshop GiftCard</li>
</ul>

== Installation ==

1. Upload the zip file with the Wordpress plug-in manager
2. Activate the desired payment methods in your plug-in manager
3. Configure the plug-in methods in the WooCommerce configuration

To make use of this plug-in you need a account on www.sisow.nl

== Frequently Asked Questions ==

= When I start a Transaction I get a error message -1 =

You didn't enter a MerchantID in the configuration

= When I start a Transaction I get a error message -2 =

You didn't enter a MerchantKey in the configuration

= When I start a Transaction I get a error message -4 =

The order total is lower than 0.45

= When I start a Transaction I get a error message -7 =

You didn't choose a payment method in the checkout

= When I start a Transaction I get a error message -8 =

Error on your server, check or CURL is enabled. If it is installed reboot your server.

= When I start a Transaction I get a error message -9 =

A general error, please send the TA code to support@sisow.nl

= When I start a Transaction I get a error message -9, TA3410 =

Enable the Test Mode in you Sisow Account under "Mijn Profiel" tab "Geavanceerd". Enable the first checkbox "Testen met behulp van simulator (toestaan)".

= When I receive an TA.... error =

Send an e-mail to support@sisow.nl with the given TA code.

= When your site uses WP-engine and you receive the message Incorrect URL! =

Contact WP-engine, this is an cache setting at their side, the returnURL may not be cached.

== Screenshots ==

1. The added plug-ins
2. The configuration in WooCommerce

== Changelog ==
= 5.1.1 =
* Add: CBC/KBC images

= 5.1.0 =
* Add: CBC/KBC payment option

= 5.0.5 =
* Fix: Small tweak in order totals for Afterpay/Capayable/Focum

= 5.0.3 =
* Fix: Afterpay GPDR

= 5.0.2 =
* Fix: WooCommerce 2 notify fix

= 5.0.1 =
* Fix: small tweak in product totals send to Sisow

== Changelog ==
= 5.0.0 =
* Add: Capayable Gespreid Betalen

= 4.9.1 =
* Add: Option to don't cancel order

= 4.9.0 =
* Add: Belfius Pay Button

= 4.8.0 =
* Add: Afterpay

= 4.7.3 =
* Fix: Customer description now displayed in the Focum form!

= 4.7.2 =
* Fix: Works on WooCommerce 2 and WooCommerce 3!

= 4.7.1 =
* Fix: remove WooCommerce deprecated functions
* Fix: refund
* Plug-in isn't working on WooCommerce 2!

= 4.7.0 =
* Add: bunq

= 4.6.0 =
* Add: iDEAL QR
* Add: V-Pay

= 4.5.11 =
* Fix: removed is_paid check on return, not all woocommerce versions have this function

= 4.5.10 =
* Fix: added utm_nooverride to Cancel and Success page
* Fix: added utm_nooverride to NotifyUrl
* New: added new Bancontact logo

= 4.5.9 =
* Fix: self error on return

= 4.5.8 =
* Fix: Description bankaccount is now correctly send to Sisow

= 4.5.7 =
* Fix: WooCommerce 2.6

= 4.5.6 =
* Added: Added option to add utm_nooverride=1 on return URL

= 4.5.5 =
* Added: Set order direct on completed

= 4.5.4 =
* Fix: Better view on Focum Accepted/Denied payments

= 4.5.3 =
* Fix: Additional scripts only loaded on frontpage
* Fix: Better Refund logging

= 4.5.2 =
* Fix: Expired doesn't overwrite success
* Fix: Loading language file on the right location

= 4.5.1 =
* Add Dutch language

= 4.5.0 =
* Reconfig the Sisow settings! (Enter the MerchantID and MerchantKey in the tab Checkout!)
* Full WPML integration
* Added ING Home'Pay
* Added VVV Giftcard

= 4.3.9 =
* Better WPML integration

= 4.3.7 =
* Getissuer Fix
* Better Focum validation

= 4.3.6 =
* Supports other payment fee plug-ins

= 4.3.5 =
* Added smaller payment logo's

= 4.3.4 =
* Added option to display/hide logo in checkout

= 4.3.3 =
* Fix: Logo on checkout page

= 4.3.2 =
* Fix: Order do not get status pending/open when the paymentmethod is not Ebill or Bankwire/Overboeking

= 4.3.0 =
* Added: Giropay
* Added: EPS

= 4.2.1 =
* Fix: compatibility with WooCommerce 2.3.4

= 4.2.0 =
* Added: Focum AchterafBetalen
* Fix: compatibility with WooCommerce 2.3.4

= 3.5.4 =
* Fix: removed http link from checkout page

= 3.5.2 =
* New: order status can be set to completed

= 3.5.1 =
* Fix: error handling

= 3.5.0 =
* Added: Maestro/MasterCard/Visa

= 3.3.17 =
* Fix: correct result page for iDEAL

= 3.3.16 =
* Fix: correct result page for ebill/overboeking

= 3.3.8 =
* Fix: redirect from ebill/overboeking

= 3.3.0 =
* Added CreditCard

= 3.2.2 =
* Fix for sequential order numbers
* Removed Sisow Ecare

= 3.2.1 =
* Fix for Sisow Ecare

= 3.2.0 =
* Added Sisow Ecare
* Addes Sisow PayPal

== Upgrade Notice ==

= 4.7.2 =
Works on WooCommerce 2 and WooCommerce 3!
