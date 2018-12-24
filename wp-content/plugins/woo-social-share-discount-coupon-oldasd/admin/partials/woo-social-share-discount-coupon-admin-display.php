<?php
/**
 * @class          Woo_Social_Share_Discount_Coupon_Admin_Display
 * @version        1.0.0
 * @package        woo-social-share-discount-coupon
 * @category       Class
 * @author         Multidots <inquiry@multidots.in>
 */
class Woo_Social_Share_Discount_Coupon_Admin_Display {

	/**
	 * Hook in methods
	 *
	 * @since    0.1.0
	 * @access   static
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_settings_menu' ) );
	}

	public static function add_settings_menu() {
		add_menu_page( 'WSSDC Settings', 'WSSDC Settings Page', 'manage_options', 'wssdcpage', array( __CLASS__, 'wssdc_general_setting' ) );
	}

	/**
	 * wssdc_general_setting_fields helper will trigger hook and handle all the settings section
	 *
	 * @since    0.1.0
	 * @access   public
	 */
	public static function wssdc_general_setting_fields() {
		$fields[] = array(
			'title' => __( 'Woo Social Share Discount Coupon General Settings', 'woo-social-share-discount-coupon' ),
			'type'  => 'title',
			'id'    => 'general_options_setting',
		);

		$fields[] = array(
			'title'   => __( 'Enable Facebook Sharing', 'woo-social-share-discount-coupon' ),
			'id'      => 'wssdc_fb_share',
			'type'    => 'checkbox',
			'label'   => __( 'Select if you want to enable Facebook sharing', 'woo-social-share-discount-coupon' ),
			'default' => '',
			'class'   => 'checkbox',
			'desc'    => sprintf( __( 'Select if you want to enable Facebook sharing.', 'woo-social-share-discount-coupon' ), '' ),

		);

		//		$fields[] = array(
		//			'title' => __('Facebook Application ID', 'woo-social-share-discount-coupon'),
		//			'id' => 'wssdc_fb_id',
		//			'type' => 'text',
		//			'label' => __('Facebook Application ID', 'woo-social-share-discount-coupon'),
		//			'default' => '',
		//			'class'=>'regular-text',
		//		);
		//
		//		$fields[] = array(
		//			'title' => __('Facebook Application Version', 'woo-social-share-discount-coupon'),
		//			'id' => 'wssdc_fb_version',
		//			'type' => 'text',
		//			'label' => __('Facebook Application Version', 'woo-social-share-discount-coupon'),
		//			'default' => '',
		//			'class'=>'regular-text',
		//		);


		$fields[] = array(
			'title'   => __( 'Facebook Description', 'woo-social-share-discount-coupon' ),
			'id'      => 'wssdc_fb_desc',
			'type'    => 'textarea',
			'label'   => __( 'Facebook Description', 'woo-social-share-discount-coupon' ),
			'default' => '',
			'class'   => 'wssdc_textarea',
		);

		$fields[] = array(
			'title'   => __( 'Enable Twitter Sharing', 'woo-social-share-discount-coupon' ),
			'id'      => 'wssdc_twitter_share',
			'type'    => 'checkbox',
			'label'   => __( 'Select if you want to enable Twitter sharing', 'woo-social-share-discount-coupon' ),
			'default' => '',
			'class'   => 'checkbox',
			'desc'    => sprintf( __( 'Select if you want to enable Twitter sharing.', 'woo-social-share-discount-coupon' ), '' ),

		);
		$fields[] = array(
			'title'   => __( 'Twitter Description', 'woo-social-share-discount-coupon' ),
			'id'      => 'wssdc_twitter_desc',
			'type'    => 'textarea',
			'label'   => __( 'Twitter Description', 'woo-social-share-discount-coupon' ),
			'default' => '',
			'class'   => 'wssdc_textarea',
		);

		$fields[] = array(
			'title'   => __( 'Coupon Amount', 'woo-social-share-discount-coupon' ),
			'id'      => 'wssdc_coupon_amt',
			'type'    => 'number',
			'label'   => __( 'Coupon Amount', 'woo-social-share-discount-coupon' ),
			'default' => '',
			'class'   => 'regular-text',
			'desc'    => sprintf( __( 'Only numeric values allowed', 'woo-social-share-discount-coupon' ), '' ),
		);
		$fields[] = array(
			'title'   => __( 'Coupon Amount is in % ?', 'woo-social-share-discount-coupon' ),
			'id'      => 'wssdc_coupon_amt_type',
			'type'    => 'checkbox',
			'label'   => __( 'Coupon Amount is in % ?', 'woo-social-share-discount-coupon' ),
			'default' => '',
			'class'   => 'checkbox',
			'desc'    => sprintf( __( 'Select checkbox if amount you entered above is in percentage.', 'woo-social-share-discount-coupon' ), '' ),

		);

		//		$fields[] = array(
		//			'title' => __('Email Limit', 'woo-social-share-discount-coupon'),
		//			'id' => 'wssdc_limit_amt',
		//			'type' => 'number',
		//			'label' => __('Email Limit', 'woo-social-share-discount-coupon'),
		//			'default' => '',
		//			'class'=>'regular-text',
		//			'desc' => sprintf(__('Only numeric values allowed', 'woo-social-share-discount-coupon'),'')
		//		);

		$fields[] = array(
			'title'   => __( 'Enable Coupon on', 'woo-social-share-discount-coupon' ),
			'id'      => 'wssdc_share_option',
			'type'    => 'radio',
			'label'   => __( 'Enable Coupon on', 'woo-social-share-discount-coupon' ),
			'default' => '0',
			'options' => array( 'Cart page', 'Thank you page' ),
		);


		$fields[] = array( 'type' => 'sectionend', 'id' => 'general_options_setting' );

		return $fields;
	}

	/**
	 * wssdc_general_setting function is responsible for settings.
	 */
	public static function wssdc_general_setting() {
		$genral_setting_fields = self::wssdc_general_setting_fields();
		$Html_output           = new Woo_Social_Share_Discount_Coupon_Html_output();

		$Html_output::save_fields( $genral_setting_fields );

		if ( isset( $_POST['wssdc_intigration'] ) ):
			?>
			<div id="setting-error-settings_updated" class="updated settings-error">
				<p>
					<strong>
						<?php echo __( 'Settings were saved successfully.', 'woo-social-share-discount-coupon' ); ?>
					</strong>
				</p>
			</div>
			<?php
		endif;
		?>
		<div class="div_general_settings">
		<div class="div_log_settings">
			<form id="button_manager_integration_form_general" enctype="multipart/form-data" action="" method="post">
				<?php $Html_output::init( $genral_setting_fields ); ?>
				<p class="submit">
					<input type="submit" name="wssdc_intigration" class="button-primary" value="<?php esc_attr_e( 'Save Settings', 'Option' ); ?>" />
				</p>
			</form>
		</div>
		<?php
	}
}

Woo_Social_Share_Discount_Coupon_Admin_Display::init();

