<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Woo_Social_Share_Discount_Coupon
 * @subpackage Woo_Social_Share_Discount_Coupon/includes
 * @author     Multidots <inquiry@multidots.in>
 */
class Woo_Social_Share_Discount_Coupon {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Woo_Social_Share_Discount_Coupon_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'woo-social-share-discount-coupon';
		$this->version     = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Woo_Social_Share_Discount_Coupon_Loader. Orchestrates the hooks of the plugin.
	 * - Woo_Social_Share_Discount_Coupon_i18n. Defines internationalization functionality.
	 * - Woo_Social_Share_Discount_Coupon_Admin. Defines all hooks for the admin area.
	 * - Woo_Social_Share_Discount_Coupon_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woo-social-share-discount-coupon-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woo-social-share-discount-coupon-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woo-social-share-discount-coupon-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-woo-social-share-discount-coupon-public.php';

		$this->loader = new Woo_Social_Share_Discount_Coupon_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Woo_Social_Share_Discount_Coupon_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Woo_Social_Share_Discount_Coupon_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Woo_Social_Share_Discount_Coupon_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_init', $plugin_admin, 'welcome_woo_social_share_discount_coupon_screen_do_activation_redirect' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'welcome_pages_screen_woo_social_share_discount_coupon' );
		$this->loader->add_action( 'woocommerce_social_share_discount_coupon_about', $plugin_admin, 'woocommerce_social_share_discount_coupon_about' );

		$this->loader->add_action( 'admin_print_footer_scripts', $plugin_admin, 'custom_woocommerce_social_share_discount_coupon_pointers_footer' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'woo_social_share_discount_coupon_the_wp_menu', 999 );
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Woo_Social_Share_Discount_Coupon_Public( $this->get_plugin_name(), $this->get_version() );

		$wssdc_fb_share      = get_option( 'wssdc_fb_share', true );
		$wssdc_twitter_share = get_option( 'wssdc_twitter_share', true );

		if ( ( isset( $wssdc_fb_share ) && ! empty( $wssdc_fb_share ) ) || ( isset( $wssdc_twitter_share ) && ! empty( $wssdc_twitter_share ) ) ) {
			if ( $wssdc_fb_share === 'yes' || $wssdc_twitter_share === 'yes' ) {
				$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
				$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
				//$appId = get_option('wssdc_fb_id', true);
				//$version_fb = get_option('wssdc_fb_version', true);
				$share_option        = get_option( 'wssdc_share_option', true );
				$wssdc_twitter_share = strtolower( get_option( 'wssdc_twitter_share', true ) );

				if ( $share_option == 0 ) {
					if ( ! session_id() ) {
						session_start();
					}
				}

				$this->loader->add_action( 'woocommerce_applied_coupon', $plugin_public, 'apply_product_on_coupon' );

				if ( isset( $wssdc_twitter_share ) && ! empty( $wssdc_twitter_share ) && $wssdc_twitter_share === 'yes' ) {
					$this->loader->add_action( 'woocommerce_thankyou', $plugin_public, 'wssdc_call_social_share' );
					$this->loader->add_action( 'woocommerce_cart_contents', $plugin_public, 'wssdc_call_social_share' );
				}
				$this->loader->add_action( 'woocommerce_thankyou', $plugin_public, 'wssdc_call_social_share' );
				$this->loader->add_action( 'woocommerce_cart_contents', $plugin_public, 'wssdc_call_social_share' );
				$this->loader->add_action( 'woocommerce_cart_contents', $plugin_public, 'wssdc_call_social_share_msg_html' );

				/*new hook*/
				$this->loader->add_action( 'wp_ajax_update_coupon_section', $plugin_public, 'update_coupon_section' );
				$this->loader->add_action( 'wp_ajax_nopriv_update_coupon_section', $plugin_public, 'update_coupon_section' );

				$this->loader->add_action( 'wp_head', $plugin_public, 'hook_javascript_fb' );
				$this->loader->add_action( 'wp_ajax_share_coupon_code', $plugin_public, 'share_coupon_code' );
				$this->loader->add_action( 'wp_ajax_nopriv_share_coupon_code', $plugin_public, 'share_coupon_code' );
				$this->loader->add_filter( 'woocommerce_paypal_args', $plugin_public, 'paypal_bn_code_filter_woo_social_share_discount_coupon', 99, 1 );
				$this->loader->add_action( 'woocommerce_thankyou', $plugin_public, 'wssdc_update_order_share_status' );
			}
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Woo_Social_Share_Discount_Coupon_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

}
