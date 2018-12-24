<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Social_Share_Discount_Coupon
 * @subpackage Woo_Social_Share_Discount_Coupon/public
 * @author     Multidots <inquiry@multidots.in>
 */
class Woo_Social_Share_Discount_Coupon_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-social-share-discount-coupon-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . 'one', plugin_dir_url( __FILE__ ) . 'fancybox/jquery.fancybox-1.3.4.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'woo_social_share', plugin_dir_url( __FILE__ ) . 'js/woo-social-share-discount-coupon-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( 'woo_social_share', 'adminajax', array(
			'ajaxurl'   => admin_url( 'admin-ajax.php' ),
			'ajax_icon' => plugin_dir_url( __FILE__ ) . '/images/ajax-loader.gif',
		) );
		wp_enqueue_script( $this->plugin_name . 'two', plugin_dir_url( __FILE__ ) . 'fancybox/jquery.fancybox-1.3.4.pack.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . 'three', plugin_dir_url( __FILE__ ) . 'fancybox/jquery.easing-1.3.pack.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . 'four', plugin_dir_url( __FILE__ ) . 'fancybox/jquery.mousewheel-3.0.4.pack.js', array( 'jquery' ), $this->version, false );
	}

	public function wssdc_update_order_share_status() {
		$share_option = get_option( 'wssdc_share_option' );
		if ( $share_option == 0 ) {
			if ( ! session_id() ) {
				session_start();
			}
			unset( $_SESSION['share_status'] );
		}
	}

	public function wssdc_call_social_share( $order ) {

		if ( 'not_apply' === $this->apply_product_on_coupon() ) {
			$get_coupon_amt      = get_option( 'wssdc_coupon_amt', true );
			$cur                 = get_woocommerce_currency_symbol();
			$is_sent             = get_post_meta( $order, '_wsscd_sent', true );
			$limit_email         = get_option( 'wssdc_limit_amt' );
			$twit_desc_cont      = get_option( 'wssdc_twitter_desc', true );
			$twit_desc           = $twit_desc_cont;
			$wssdc_fb_share      = get_option( 'wssdc_fb_share', true );
			$wssdc_twitter_share = get_option( 'wssdc_twitter_share', true );
			$orimg               = '';

			$output_fb = '';
			if ( isset( $wssdc_fb_share ) && ! empty( $wssdc_fb_share ) ) {
				if ( $wssdc_fb_share === 'yes' ) {
					$output_fb = '<span id="fbShareButton" class="btn btn-success clearfix" data-url="' . site_url() . '" style="cursor: pointer;"><img src="' . plugin_dir_url( __FILE__ ) . 'images/fb.png" alt=""/></span>';
					// $output_fb = '<a id="fbShareButton" href="https://www.facebook.com/sharer/sharer.php?u=' . site_url() . '&title=' . $twit_desc . '"><img src="' . plugin_dir_url(__FILE__) . 'images/fb.png" alt=""/></a>';
				} else {
					$output_fb = '';
				}
			}
			$output_twitter = '';
			if ( isset( $wssdc_twitter_share ) && ! empty( $wssdc_twitter_share ) ) {
				if ( $wssdc_twitter_share === 'yes' ) {
					$output_twitter = '<a href="https://twitter.com/intent/tweet?url=' . site_url() . ';text=' . $twit_desc . ';size=l&amp;count=none"><img src="' . plugin_dir_url( __FILE__ ) . 'images/twitter.png" /></a>';
				} else {
					$output_twitter = '';
				}
			}

			$check_discount_type = get_option( 'wssdc_coupon_amt_type', true );
			if ( isset( $check_discount_type ) && ! empty( $check_discount_type ) ) {
				if ( $check_discount_type === 'yes' ) {
					$discount_type_own = '%';
				} else {
					$discount_type_own = '';
				}
			} else {
				$discount_type_own = '';
			}
			if ( $discount_type_own === '%' ) {
				$cur_own = '';
			} else {
				$cur_own = get_woocommerce_currency_symbol();
			}

			$customer_orders = get_posts( array(
				'numberposts' => - 1,
				'meta_key'    => '_customer_user',
				'meta_value'  => get_current_user_id(),
				'post_type'   => wc_get_order_types(),
				'post_status' => array_keys( wc_get_order_statuses() ),
			) );
			$customer        = wp_get_current_user();
			$total_order     = count( $customer_orders );

			if ( isset( $get_coupon_amt ) && ! empty( $get_coupon_amt ) ) {
				$twit_desc = get_option( 'wssdc_twitter_desc', true );
				$html      = '';
				if ( ! empty( $total_order ) && ! empty( $limit_email ) ) {
					if ( $total_order <= $limit_email ) {
						$html .= '<div class="wssdc_div"><span class="wssdc_message_span">Thanks for your purchase. <a class="fancybox_social_share" id="social_share_roduct" href="#data1">Click here</a> to get a ' . $cur_own . ' ' . $get_coupon_amt . '' . $discount_type_own . ' discount coupon.</span></div>';
					} else {
						$html .= '';
					}
				} elseif ( empty( $limit_email ) ) {
					$share_option = get_option( 'wssdc_share_option', true );
					if ( isset( $share_option ) && $share_option == 1 ) {
						$html .= '<div class="wssdc_div"><span class="wssdc_message_span">Thanks for your purchase. <a class="fancybox_social_share" id="social_share_roduct" href="#data1">Click here</a> to get a ' . $cur_own . ' ' . $get_coupon_amt . '' . $discount_type_own . ' discount coupon.</span></div>';
					} else {
						$html .= '<div class="wssdc_div"><span class="wssdc_message_span"><a class="fancybox_social_share" id="social_share_roduct" href="#data1">Click here</a> to get a ' . $cur_own . ' ' . $get_coupon_amt . '' . $discount_type_own . ' discount coupon.</span></div>';
					}
				}
				$html .= '<div id="fb-root"></div><div style="display:none">';
				$html .= '<div id="data1">';
				$html .= '<div class="product_socialicons">';
				$html .= '<div class="shareiconts">';
				$html .= '<div class="facebook_share">';
				$html .= $output_fb;
				$html .= '</div>';

				$html .= '<div class="twitter">';
				$html .= $output_twitter;
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';

				if ( ( $wssdc_twitter_share === 'yes' || $wssdc_fb_share === 'yes' ) && ( isset( $is_sent ) && empty( $is_sent ) ) ) {
					echo $html;
				}
			}
			?>

			<script type="text/javascript">
							jQuery( document ).on( 'click', '#fbShareButton', function( e ) {

								var dataUrl = jQuery( this ).attr( 'data-url' );
								window.open( 'https://www.facebook.com/sharer/sharer.php?u=' + dataUrl, 'pop', 'width=600, height=400, scrollbars=no' );

								parent.jQuery( '#fancybox-overlay' ).hide();
								parent.jQuery( '#fancybox-wrap' ).hide();
								jQuery( '#fancybox-wrap' ).css( 'display', 'none' );
								var data = {
									action: 'share_coupon_code',
									value: 'yes',
									security: '<?php echo esc_js( wp_create_nonce( "WSSDC" ) ); ?>',
									orderData: '<?php echo $order; ?>',
									userEmailAddress: '<?php echo isset( $order->billing_email ) ? esc_js( $order->billing_email ) : ''; ?>'
								};
								var url = '<?php echo site_url(); ?>/wp-admin/admin-ajax.php";
								jQuery.post( url, data, function( response ) {
									jQuery( '#coupon_msg' ).html( '' );
									jQuery( '.wssdc_div' ).html( '' );
									jQuery( '#coupon_msg' ).html( 'Your Coupon code is ' + response );
					<?php

					$share_option = get_option( 'wssdc_share_option' );

					if (is_user_logged_in() || $share_option == 1) {
					?>
									//alert('Coupon code has been successfully sent to your email address!');
					<?php } else { ?>
									jQuery( '#coupon_msg' ).html( 'Your Coupon code is ' + response );
					<?php } ?>
								} );
							} );
			</script>

			<script type="text/javascript">
							window.twttr = (function( d, s, id ) {
								var t, js, fjs = d.getElementsByTagName( s )[ 0 ];
								if ( d.getElementById( id ) )
									return;
								js = d.createElement( s );
								js.id = id;
								js.src = 'https://platform.twitter.com/widgets.js';
								fjs.parentNode.insertBefore( js, fjs );
								return window.twttr || (t = {
									_e: [], ready: function( f ) {
										t._e.push( f );
									}
								});
							}( document, 'script', 'twitter-wjs' ));

							twttr.ready( function( twttr ) {

								twttr.events.bind( 'tweet', function( event ) {

									parent.jQuery( '#fancybox-overlay' ).hide();
									parent.jQuery( '#fancybox-wrap' ).hide();
									jQuery( '#fancybox-wrap' ).css( 'display', 'none' );
									var data = {
										action: 'share_coupon_code',
										value: 'yes',
										security: '<?php echo esc_js( wp_create_nonce( "WSSDC" ) ); ?>',
										orderData: '<?php echo $order; ?>',
										userEmailAddress: '<?php echo isset( $order->billing_email ) ? esc_js( $order->billing_email ) : ''; ?>'
									};
									var url = '<?php echo site_url(); ?>/wp-admin/admin-ajax.php";
									// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
									jQuery.post( url, data, function( response ) {

										jQuery( '#coupon_msg' ).html( '' );
										jQuery( '.wssdc_div' ).html( '' );
										jQuery( '#coupon_msg' ).html( 'Your Coupon code is ' + response );
					  <?php
					  $share_option = get_option( 'wssdc_share_option' );
					  if (is_user_logged_in() || $share_option == 1) {
					  ?>
										//alert('Coupon code has been successfully sent to your email address!');
					  <?php } else { ?>
										jQuery( '#coupon_msg' ).html( 'Your Coupon code is ' + response );
					  <?php } ?>
									} );
								} );
							} );
			</script>
			<?php
		}
	}


	/**
	 * Set Html for coupon message when user not login
	 *
	 */
	public function wssdc_call_social_share_msg_html() {
		echo '<div id="coupon_msg"></div>';
	}

	/**
	 * Function responsible for include required files
	 *
	 */
	public function hook_javascript_fb() {
		?>
		<script type="text/javascript">
					//            (function (d, s, id) {
					//                var js, fjs = d.getElementsByTagName(s)[0];
					//                if (d.getElementById(id)) {
					//                    return;
					//                }
					//                js = d.createElement(s);
					//                js.id = id;
					//                js.src = "//connect.facebook.net/en_US/sdk.js";
					//                fjs.parentNode.insertBefore(js, fjs);
					//            }(document, 'script', 'facebook-jssdk'));

					//            });
		</script>

		<?php
	}

	public function share_coupon_code() {
		$current_user = wp_get_current_user();
		$digits       = 6;
		$coupon_code  = "code" . str_pad( rand( 0, pow( 10, $digits ) - 1 ), $digits, '0', STR_PAD_LEFT );
		$coupon_amt   = get_option( 'wssdc_coupon_amt', true );
		if ( isset( $coupon_amt ) && ! empty( $coupon_amt ) ) {
			$amount = $coupon_amt; // Amount
		}
		$check_discount_type = get_option( 'wssdc_coupon_amt_type', true );
		$share_option        = get_option( 'wssdc_share_option' );
		if ( isset( $check_discount_type ) && ! empty( $check_discount_type ) ) {
			if ( $check_discount_type === 'yes' ) {
				$discount_type = 'percent';
			} else {
				$discount_type = 'fixed_cart';
			}
		} else {
			$discount_type = 'fixed_cart';
		}
		// Type: fixed_cart, percent, fixed_product, percent_product
		//$discount_type = 'fixed_cart'; // Type: fixed_cart, percent, fixed_product, percent_product
		$is_sent = isset( $_POST['orderData'] ) ? get_post_meta( $_POST['orderData'], '_wsscd_sent', true ) : false;

		if ( isset( $is_sent ) && empty( $is_sent ) ) {
			$coupon = array(
				'post_title'   => esc_attr( $coupon_code ),
				'post_content' => '',
				'post_status'  => 'publish',
				'post_author'  => 1,
				'post_type'    => 'shop_coupon',
			);

			$new_coupon_id = wp_insert_post( $coupon );

			// Add meta
			update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
			update_post_meta( $new_coupon_id, 'coupon_amount', isset( $amount ) ? $amount : '' );
			update_post_meta( $new_coupon_id, 'individual_use', 'no' );
			update_post_meta( $new_coupon_id, 'product_ids', '' );
			update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
			update_post_meta( $new_coupon_id, 'usage_limit', '1' );
			update_post_meta( $new_coupon_id, 'expiry_date', '' );
			update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
			update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
			add_post_meta( $_POST['orderData'], '_wsscd_sent', '1' );
			if ( $share_option == 0 ) {
				if ( ! session_id() ) {
					session_start();
				}
				$_SESSION['share_status'] = 1;
			}
			//            if (is_user_logged_in() && $share_option == 0) {
			//                mail($current_user->user_email, 'Coupon', "Your Coupon code is $coupon_code");
			//            }
			//            if ($share_option == 1) {
			//                mail($_POST['userEmailAddress'], 'Coupon', "Your Coupon code is $coupon_code");
			//            }
		}
		echo $coupon_code;
		wp_die();
	}

	public function apply_product_on_coupon() {
		global $woocommerce;
		if ( ! empty( $woocommerce->cart->applied_coupons ) ) {
			foreach ( $woocommerce->cart->applied_coupons as $v_key => $v_value ) {
				if ( strpos( $v_value, 'code' ) !== false ) {
					return "apply";
				}

				return "not_apply";
			}
		} else {
			return "not_apply";
		}
	}

	public function update_coupon_section() {
		$available_data_coupon = $_REQUEST['available_data_coupon'];
		if ( strpos( $available_data_coupon, 'code' ) !== false ) {
			//update_option('ds_coupon', 'not_exists');
			echo "not_apply";
		}
		die();
	}

	/**
	 * BN code added
	 */
	function paypal_bn_code_filter_woo_social_share_discount_coupon( $paypal_args ) {
		$paypal_args['bn'] = 'Multidots_SP';

		return $paypal_args;
	}
}
