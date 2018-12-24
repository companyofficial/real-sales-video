<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Social_Share_Discount_Coupon
 * @subpackage Woo_Social_Share_Discount_Coupon/admin
 * @author     Multidots <inquiry@multidots.in>
 */
class Woo_Social_Share_Discount_Coupon_Admin {

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
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->load_dependencies();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-social-share-discount-coupon-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'wp-pointer' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'wp-jquery-ui-dialog' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-social-share-discount-coupon-admin.js', array(
			'jquery',
			'jquery-ui-dialog',
		), $this->version, false );
		wp_enqueue_script( 'wp-pointer' );
	}

	/**
	 * Function responsible for include required files
	 *
	 */
	public function load_dependencies() {
		/**
		 * The class responsible for defining function for display Html element
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/woo-social-share-discount-coupon-html-output.php';

		/**
		 * The class is responsible for display admin settings
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/woo-social-share-discount-coupon-admin-display.php';
	}

	/**
	 * Function For welcome page
	 *
	 */
	public function welcome_woo_social_share_discount_coupon_screen_do_activation_redirect() {

		if ( ! get_transient( '_welcome_screen_woo_social_share_discount_coupon_activation_redirect_data' ) ) {
			return;
		}

		// Delete the redirect transient
		delete_transient( '_welcome_screen_woo_social_share_discount_coupon_activation_redirect_data' );

		// if activating from network, or bulk
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}
		// Redirect to extra cost welcome  page
		wp_safe_redirect( add_query_arg( array( 'page' => 'woocommerce-social-share-discount-coupon&tab=about' ), admin_url( 'index.php' ) ) );
	}

	public function welcome_pages_screen_woo_social_share_discount_coupon() {
		add_dashboard_page(
			'WooCommerce-Social-Share-Discount-Coupon Dashboard', 'WooCommerce Social Share Discount Coupon Style', 'read', 'woocommerce-social-share-discount-coupon', array(
				&$this,
				'welcome_screen_content_woocommerce_social_share_discount_doupon',
			)
		);
	}

	public function welcome_screen_content_woocommerce_social_share_discount_doupon() {
		?>

		<div class="wrap about-wrap">
			<h1 style="font-size: 2.1em;"><?php printf( __( 'Welcome to WooCommerce Social Share Discount Coupon', 'woo-social-share-discount-coupon' ) ); ?></h1>

			<div class="about-text woocommerce-about-text">
				<?php
				$message = '';
				printf( __( '%s WooCommerce Social Share Discount Coupon plugin allows you to easily add a social coupon system to your e-commerce store that allows users to get instant discounts coupon for sharing your post on next purchase.', 'woo-social-share-discount-coupon' ), $message, $this->version );
				?>
				<img class="version_logo_img" src="<?php echo plugin_dir_url( __FILE__ ) . 'images/woo-social-share-discount-coupon.png'; ?>">
			</div>

			<?php
			$setting_tabs_wc = apply_filters( 'woo_social_share_discount_coupon_setting_tab', array( "about" => "Overview", "other_plugins" => "Checkout our other plugins" ) );
			$current_tab_wc  = ( isset( $_GET['tab'] ) ) ? $_GET['tab'] : 'general';
			?>
			<h2 id="woo-extra-cost-tab-wrapper" class="nav-tab-wrapper">
				<?php
				foreach ( $setting_tabs_wc as $name => $label ) {
					?>
					<a href="<?php echo esc_url( home_url( 'wp-admin/index.php?page=woocommerce-social-share-discount-coupon&tab=' . $name ) ); ?>"
					   class="nav-tab <?php echo $current_tab_wc == $name ? 'nav-tab-active' : ''; ?>"
					>
						<?php echo esc_html( $label ); ?>
					</a>
					<?php
				}
				?>
			</h2>

			<?php
			foreach ( $setting_tabs_wc as $setting_tabkey_wc => $setting_tabvalue ) {
				switch ( $setting_tabkey_wc ) {
					case $current_tab_wc:
						do_action( 'woocommerce_social_share_discount_coupon_' . $current_tab_wc );
						break;
				}
			}
			?>
			<hr />
			<div class="return-to-dashboard">
				<a href="<?php echo home_url( '/wp-admin/admin.php?page=wssdcpage' ); ?>">
					<?php _e( 'Go to WooCommerce Social Share Discount Coupon Settings', 'woo-social-share-discount-coupon' ); ?>
				</a>
			</div>
		</div>
		<?php
	}

	/**
	 * Extra flat rate overview welcome page content function
	 */
	public function woocommerce_social_share_discount_coupon_about() {
		//do_action('my_own');
		$current_user = wp_get_current_user();
		?>
		<div class="changelog">
			</br>
			<style type="text/css">
				p.woo_social_discount_coupon_overview {
					max-width: 100% !important;
					margin-left: auto;
					margin-right: auto;
					font-size: 15px;
					line-height: 1.5;
				}

				.woo_social_discount_coupon_content_ul ul li {
					margin-left: 3%;
					list-style: initial;
					line-height: 23px;
				}
			</style>
			<div class="changelog about-integrations">
				<div class="wc-feature feature-section col three-col">
					<div>
						<p class="woo_social_discount_coupon_overview"><?php _e( 'WooCommerce Social Share Discount Coupon plugin allows you to easily add a social coupon system to your e-commerce store that allows users to get instant discounts coupon for sharing your post on next purchase.', 'woo-social-share-discount-coupon' ); ?></p>

						<p class="woo_social_discount_coupon_overview"><strong>Plugin Functionality: </strong></p>
						<div class="woo_social_discount_coupon_content_ul">
							<ul>
								<li>Share description on order received page using facebook and twitter.</li>
								<li>Get Instant discount coupon after share description on next purchase.</li>
								<li>Valid for one time use coupon.</li>
								<li>Simple setup in the options panel.</li>
								<li>Fully tested with latest version of WooCommerce.</li>
							</ul>
						</div>

					</div>

				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * remove menu in dashboard
	 *
	 */
	public function woo_social_share_discount_coupon_the_wp_menu() {
		remove_submenu_page( 'index.php', 'woocommerce-social-share-discount-coupon' );
	}

	/**
	 * Function For add wp pointer notice in plugin
	 */
	public function custom_woocommerce_social_share_discount_coupon_pointers_footer() {

		$admin_pointers = custom_woocommerce_social_share_discount_coupon_admin_pointers();
		?>
		<script type="text/javascript">
					/* <![CDATA[ */
					(function( $ ) {
			  <?php
			  foreach ($admin_pointers as $pointer => $array) {
			  if ($array['active']) {
			  ?>
						$( '<?php echo esc_js( $array['anchor_id']) ; ?>' ).pointer( {
							content: '<?php echo esc_js( $array['content'] ); ?>',
							position: {
								edge: '<?php echo esc_js( $array['edge'] ); ?>',
								align: '<?php echo esc_js( $array['align'] ); ?>'
							},
							close: function() {
								$.post( ajaxurl, {
									pointer: '<?php echo esc_js( $pointer ) ; ?>',
									action: 'dismiss-wp-pointer'
								} );
							}
						} ).pointer( 'open' );
			  <?php
			  }
			  }
			  ?>
					})( jQuery );
					/* ]]> */
		</script>

		<?php
	}

}

function custom_woocommerce_social_share_discount_coupon_admin_pointers() {
	$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
	$version   = '1_0'; // replace all periods in 1.0 with an underscore
	$prefix    = 'custom_woocommerce_social_share_discount_pointers' . $version . '_';

	$new_pointer_content = '<h3>' . __( 'Welcome to WooCommerce Social Share Discount Coupon' ) . '</h3>';
	$new_pointer_content .= '<p>' . __( 'WooCommerce Social Share Discount Coupon plugin allows store admin to offer an instant discount coupon to customers if they share your post on their social media. You customer will be able to use that coupon on their next purchase.' ) . '</p>';

	return array(
		$prefix . 'custom_woocommerce_social_share_discount_pointers' => array(
			'content'   => $new_pointer_content,
			'anchor_id' => '#toplevel_page_wssdcpage',
			'edge'      => 'left',
			'align'     => 'left',
			'active'    => ( ! in_array( $prefix . 'custom_woocommerce_social_share_discount_pointers', $dismissed ) ),
		),
	);
}
