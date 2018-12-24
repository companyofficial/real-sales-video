<?php
// Direct access security
if ( !defined( 'TM_EPO_PLUGIN_SECURITY' ) ) {
	die();
}

if ( !function_exists( 'tm_get_price_decimal_separator' ) ) {
	/**
	 * @return string
	 */
	function tm_get_price_decimal_separator() {
		if ( function_exists( 'wc_get_price_decimal_separator' ) ) {
			return wc_get_price_decimal_separator();
		}
		$separator = stripslashes( get_option( 'woocommerce_price_decimal_sep' ) );

		return $separator ? $separator : '.';
	}
}

if ( !function_exists( 'tc_convert_local_numbers' ) ) {
	/**
	 * @param string $input
	 * @return mixed|string
	 */
	function tc_convert_local_numbers( $input = "" ) {
		$locale = localeconv();
		$decimals = array( tm_get_price_decimal_separator(), $locale['decimal_point'], $locale['mon_decimal_point'] );

		// Remove whitespace from string
		$input = preg_replace( '/\s+/', '', $input );

		// Remove locale from string
		$input = str_replace( $decimals, '.', $input );

		// Trim invalid start/end characters
		$input = rtrim( ltrim( $input, "\t\n\r\0\x0B+*/" ), "\t\n\r\0\x0B+-*/" );

		return $input;
	}
}

if ( !function_exists( 'tc_needs_wc_db_update' ) ) {
	/**
	 * @return bool|mixed|void
	 */
	function tc_needs_wc_db_update() {
		$_tm_current_woo_version = get_option( 'woocommerce_db_version' );
		$_tc_needs_wc_db_update = FALSE;
		if ( get_option( 'woocommerce_db_version' ) !== FALSE ) {
			if ( version_compare( get_option( 'woocommerce_db_version' ), '2.3', '<' ) && version_compare( get_option( 'woocommerce_version' ), '2.4', '<' ) ) {
				$_tm_notice_check = '_wc_needs_update';
				$_tc_needs_wc_db_update = get_option( $_tm_notice_check );
				// no check after 2.6 update
			} elseif ( version_compare( get_option( 'woocommerce_db_version' ), '2.5', '>=' ) ) {
				$_tc_needs_wc_db_update = FALSE;
			} else {
				$_tm_notice_check = 'woocommerce_admin_notices';
				$_tc_needs_wc_db_update = in_array( 'update', get_option( $_tm_notice_check, array() ) );
			}
		}

		return $_tc_needs_wc_db_update;
	}
}

if ( !tc_needs_wc_db_update() && !function_exists( 'wc_get_product' ) && get_option( 'woocommerce_db_version' ) !== FALSE && version_compare( get_option( 'woocommerce_db_version' ), '2.2', '<' ) ) {
	/**
	 * @param bool $the_product
	 * @param array $args
	 * @return null|WC_Product
	 */
	function wc_get_product( $the_product = FALSE, $args = array() ) {
		return get_product( $the_product, $args );
	}
}

if ( !function_exists( 'wp_get_ext_types' ) ) {
	/**
	 * @return mixed|void
	 */
	function wp_get_ext_types() {

		/**
		 * Filters file type based on the extension name.
		 *
		 * @since 2.5.0
		 *
		 * @see wp_ext2type()
		 *
		 * @param array $ext2type Multi-dimensional array with extensions for a default set
		 *                        of file types.
		 */
		return apply_filters( 'ext2type', array(
			'image'       => array( 'jpg', 'jpeg', 'jpe', 'gif', 'png', 'bmp', 'tif', 'tiff', 'ico' ),
			'audio'       => array( 'aac', 'ac3', 'aif', 'aiff', 'm3a', 'm4a', 'm4b', 'mka', 'mp1', 'mp2', 'mp3', 'ogg', 'oga', 'ram', 'wav', 'wma' ),
			'video'       => array( '3g2', '3gp', '3gpp', 'asf', 'avi', 'divx', 'dv', 'flv', 'm4v', 'mkv', 'mov', 'mp4', 'mpeg', 'mpg', 'mpv', 'ogm', 'ogv', 'qt', 'rm', 'vob', 'wmv' ),
			'document'    => array( 'doc', 'docx', 'docm', 'dotm', 'odt', 'pages', 'pdf', 'xps', 'oxps', 'rtf', 'wp', 'wpd', 'psd', 'xcf' ),
			'spreadsheet' => array( 'numbers', 'ods', 'xls', 'xlsx', 'xlsm', 'xlsb' ),
			'interactive' => array( 'swf', 'key', 'ppt', 'pptx', 'pptm', 'pps', 'ppsx', 'ppsm', 'sldx', 'sldm', 'odp' ),
			'text'        => array( 'asc', 'csv', 'tsv', 'txt' ),
			'archive'     => array( 'bz2', 'cab', 'dmg', 'gz', 'rar', 'sea', 'sit', 'sqx', 'tar', 'tgz', 'zip', '7z' ),
			'code'        => array( 'css', 'htm', 'html', 'php', 'js' ),
		) );
	}
}

if ( !function_exists( 'tc_woocommerce_check' ) ) {
	/**
	 * @return bool
	 */
	function tc_woocommerce_check() {
		$active_plugins = (array) get_option( 'active_plugins', array() );
		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		return !tc_needs_wc_db_update() && in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins );
	}
}

if ( !function_exists( 'tc_woocommerce_check_only' ) ) {
	/**
	 * @return bool
	 */
	function tc_woocommerce_check_only() {
		$active_plugins = (array) get_option( 'active_plugins', array() );
		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		return in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins );
	}
}

if ( !function_exists( 'tc_woocommerce_subscriptions_check' ) ) {
	/**
	 * @return bool
	 */
	function tc_woocommerce_subscriptions_check() {
		$active_plugins = (array) get_option( 'active_plugins', array() );
		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		return in_array( 'woocommerce-subscriptions/woocommerce-subscriptions.php', $active_plugins ) || array_key_exists( 'woocommerce-subscriptions/woocommerce-subscriptions.php', $active_plugins );
	}
}

/** Check for require json function for PHP 4 & 5.1 **/
if ( !function_exists( 'json_decode' ) ) {
	include_once(TM_EPO_PLUGIN_PATH . '/external/json/JSON.php');
	/**
	 * @param $data
	 * @return mixed
	 */
	function json_encode( $data ) {
		$json = new Services_JSON();

		return ($json->encode( $data ));
	}

	/**
	 * @param $data
	 * @return mixed
	 */
	function json_decode( $data ) {
		$json = new Services_JSON();

		return ($json->decode( $data ));
	}
}

if ( !function_exists( 'tc_get_roles' ) ) {
	/**
	 * @return array
	 */
	function tc_get_roles() {
		$result = array();
		$result["@everyone"] = __( 'Everyone', 'woocommerce-tm-extra-product-options' );
		$result["@loggedin"] = __( 'Logged in users', 'woocommerce-tm-extra-product-options' );
		global $wp_roles;
		if ( empty( $wp_roles ) ) {
			$all_roles = new WP_Roles();
		} else {
			$all_roles = $wp_roles;
		}
		$roles = $all_roles->roles;
		if ( $roles ) {
			foreach ( $roles as $role => $details ) {
				$name = translate_user_role( $details['name'] );
				$result[ $role ] = $name;
			}
		}

		return $result;
	}
}

if ( !function_exists( 'tc_price' ) ) {
	/**
	 * Format the price with a currency symbol.
	 *
	 * @param float $price
	 * @param array $args (default: array())
	 * @return string
	 */
	function tc_price( $price, $args = array() ) {
		extract( apply_filters( 'tc_price_args', wp_parse_args( $args, array(
			'ex_tax_label'       => FALSE,
			'currency'           => '',
			'decimal_separator'  => wc_get_price_decimal_separator(),
			'thousand_separator' => wc_get_price_thousand_separator(),
			'decimals'           => wc_get_price_decimals(),
			'price_format'       => get_woocommerce_price_format(),
		) ) ) );

		$negative = $price < 0;
		$price = apply_filters( 'tc_raw_woocommerce_price', floatval( $negative ? $price * -1 : $price ) );
		$price = apply_filters( 'formatted_woocommerce_price', number_format( $price, $decimals, $decimal_separator, $thousand_separator ), $price, $decimals, $decimal_separator, $thousand_separator );

		if ( apply_filters( 'woocommerce_price_trim_zeros', FALSE ) && $decimals > 0 ) {
			$price = wc_trim_zeros( $price );
		}

		$formatted_price = ($negative ? '-' : '') . sprintf( $price_format, get_woocommerce_currency_symbol( $currency ), $price );
		$return = '<span class="amount">' . $formatted_price . '</span>';

		if ( $ex_tax_label && wc_tax_enabled() ) {
			$return .= ' <small class="tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
		}

		return apply_filters( 'tc_price', $return, $price, $args );
	}
}

if ( !function_exists( 'tc_fix_woocommerce_bundle_rate_shipping_scripts' ) ) {
	/**
	 * woocommerce_bundle_rate_shipping chosen fix by removing
	 */
	function tc_fix_woocommerce_bundle_rate_shipping_scripts() {
		if ( !(isset( $_GET['page'] ) && isset( $_GET['tab'] ) && $_GET['page'] == 'wc-settings' && $_GET['tab'] == 'shipping') ) {
			wp_dequeue_script( 'woocommerce_bundle_rate_shipping_admin_js' );
		}
	}
}


/**
 * Settings Page
 *
 * @param $settings
 * @return array
 */
function tc_add_epo_admin_settings( $settings ) {
	$_setting = new TM_EPO_ADMIN_SETTINGS();
	if ( $_setting instanceof WC_Settings_Page ) {
		$settings[] = $_setting;
	}

	return $settings;
}

/** Compatibility **/
function TM_EPO_COMPATIBILITY() {
	return TM_EPO_COMPATIBILITY_base::instance();
}

/** HTML functions **/
function TM_EPO_HTML() {
	return TM_EPO_HTML_base::instance();
}

/** HELPER functions **/
function TM_EPO_HELPER() {
	return TM_EPO_HELPER_base::instance();
}

/** WPML functions **/
function TM_EPO_WPML() {
	return TM_EPO_WPML_base::instance();
}

/** UPDATE functions **/
function TM_EPO_LICENSE() {
	return TM_EPO_UPDATE_Licenser::instance();
}

/**
 * @return null|TM_EPO_UPDATE_Updater
 */
function TM_EPO_UPDATER() {
	return TM_EPO_UPDATE_Updater::instance();
}

/** Plugin health check **/
function TM_EPO_CHECK() {
	return TM_EPO_CHECK_base::instance();
}

/** Field builder **/
function TM_EPO_BUILDER() {
	return TM_EPO_BUILDER_base::instance();
}

/** Main plugin interface **/
function TM_EPO() {
	return TM_Extra_Product_Options::instance();
}

/** Globals Admin Interface **/
function TM_EPO_ADMIN_GLOBAL() {
	return TM_EPO_ADMIN_Global_base::instance();
}

/** Admin Interface **/
function TM_EPO_ADMIN() {
	return TM_EPO_Admin_base::instance();
}

/** Settings Interface **/
function TM_EPO_SETTINGS() {
	return TM_EPO_SETTINGS_base::instance();
}

/** API helper Interface **/
function TM_EPO_API() {
	return TM_EPO_API_base::instance();
}

/** Load plugin textdomain **/
function tc_epo_load_textdomain() {
	$domain = TM_EPO_DIRECTORY;
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
	$global_mo = trailingslashit( WP_LANG_DIR ) . 'plugins' . '/' . $domain . '-' . $locale . '.mo';
	$global_mo2 = trailingslashit( WP_LANG_DIR ) . 'plugins/' . $domain . '/' . $domain . '-' . $locale . '.mo';
	if ( file_exists( $global_mo ) ) {
		// wp-content/languages/plugins/plugin-name-$locale.mo
		load_textdomain( $domain, $global_mo );
	} elseif ( file_exists( $global_mo2 ) ) {
		// wp-content/languages/plugins/plugin-name/plugin-name-$locale.mo
		load_textdomain( $domain, $global_mo2 );
	} else {
		// wp-content/plugins/plugin-name/languages/plugin-name-$locale.mo
		load_plugin_textdomain( 'woocommerce-tm-extra-product-options', FALSE, TM_EPO_DIRECTORY . '/languages/' );
	}
}

/** Register post types **/
function tc_epo_register_post_type() {
	register_post_type( TM_EPO_LOCAL_POST_TYPE,
		array(
			'labels'              => array(
				'name' => _x( 'TM Extra Product Options', 'post type general name', 'woocommerce-tm-extra-product-options' ),
			),
			'publicly_queryable'  => FALSE,
			'exclude_from_search' => TRUE,
			'rewrite'             => FALSE,
			'show_in_nav_menus'   => FALSE,
			'public'              => FALSE,
			'hierarchical'        => FALSE,
			'supports'            => FALSE,
			'_edit_link'          => 'post.php?post=%d' //WordPress 4.4 fix
		)
	);

	register_post_type( TM_EPO_GLOBAL_POST_TYPE,
		array(
			'labels'              => array(
				'name'               => __( 'TM Global Forms', 'woocommerce-tm-extra-product-options' ),
				'singular_name'      => __( 'TM Global Form', 'woocommerce-tm-extra-product-options' ),
				'menu_name'          => _x( 'TM Global Product Options', 'post type general name', 'woocommerce-tm-extra-product-options' ),
				'add_new'            => __( 'Add Global Form', 'woocommerce-tm-extra-product-options' ),
				'add_new_item'       => __( 'Add New Global Form', 'woocommerce-tm-extra-product-options' ),
				'edit'               => __( 'Edit', 'woocommerce-tm-extra-product-options' ),
				'edit_item'          => __( 'Edit Global Form', 'woocommerce-tm-extra-product-options' ),
				'new_item'           => __( 'New Global Form', 'woocommerce-tm-extra-product-options' ),
				'view'               => __( 'View Global Form', 'woocommerce-tm-extra-product-options' ),
				'view_item'          => __( 'View Global Form', 'woocommerce-tm-extra-product-options' ),
				'search_items'       => __( 'Search Global Form', 'woocommerce-tm-extra-product-options' ),
				'not_found'          => __( 'No Global Form found', 'woocommerce-tm-extra-product-options' ),
				'not_found_in_trash' => __( 'No Global Form found in trash', 'woocommerce-tm-extra-product-options' ),
				'parent'             => __( 'Parent Global Form', 'woocommerce-tm-extra-product-options' ),
			),
			'description'         => __( 'This is where you can add new products to your store.', 'woocommerce' ),
			'public'              => FALSE,
			'show_ui'             => FALSE,
			'capability_type'     => 'product',
			'map_meta_cap'        => TRUE,
			'publicly_queryable'  => FALSE,
			'exclude_from_search' => TRUE,
			'hierarchical'        => FALSE,
			'rewrite'             => FALSE,
			'query_var'           => FALSE,
			'supports'            => array( 'title', 'excerpt' ),
			'has_archive'         => FALSE,
			'show_in_nav_menus'   => FALSE,
			'_edit_link'          => 'post.php?post=%d' //WordPress 4.4 fix
		)

	);

	tc_epo_register_taxonomy();
}

/** Register post types **/
function tc_epo_register_taxonomy() {
	register_taxonomy_for_object_type( 'product_cat', TM_EPO_GLOBAL_POST_TYPE );
}

/** Shortcode tc_epo_show (Used for echoing a custom action) **/
function tc_epo_show_shortcode( $atts, $content = NULL ) {
	extract( shortcode_atts( array(
		'action' => '',
	), $atts ) );

	ob_start();
	do_action( $action );

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}

/** Shortcode tc_epo (Used for echoing options) **/
function tc_epo_shortcode( $atts, $content = NULL ) {
	extract( shortcode_atts( array(
		'id'     => '',
		'prefix' => '',
	), $atts ) );

	ob_start();

	if ( $id ) {
		TM_EPO()->tm_epo_fields( $id, $prefix, TRUE );
		TM_EPO()->tm_add_inline_style();
	}

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}

/** Shortcode tc_epo_totals (Used for echoing options totals) **/
function tc_epo_totals_shortcode( $atts, $content = NULL ) {
	extract( shortcode_atts( array(
		'id'     => '',
		'prefix' => '',
	), $atts ) );

	ob_start();

	if ( $id ) {
		TM_EPO()->tm_epo_totals( $id, $prefix, TRUE );
	}

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}

/** Epo Widget (Used for for echoing a custom action) **/
class TC_EPO_Widget extends WP_Widget {

	/**
	 * TC_EPO_Widget constructor.
	 */
	function __construct() {
		$widget_ops = array( 'classname' => 'tc_epo_show_widget', 'description' => __( 'Echo a custom action', 'woocommerce-tm-extra-product-options' ) );

		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'tc_epo_show_widget' );

		parent::__construct( 'tc_epo_show_widget', __( 'EPO custom action', 'woocommerce-tm-extra-product-options' ), $widget_ops, $control_ops );
	}

	/**
	 * @param array $args
	 * @param array $instance
	 */
	function widget( $args, $instance ) {

		$cache = wp_cache_get( 'widget_recent_posts', 'widget' );

		if ( !is_array( $cache ) ) {
			$cache = array();
		}

		if ( !isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];

			return;
		}

		ob_start();
		extract( $args );

		$title = empty( $instance['title'] ) ? '' : $instance['title'];
		$action = empty( $instance['action'] ) ? 'tc_show_epo' : $instance['action'];

		echo $before_widget;
		echo $title;
		do_action( $action );

		echo $after_widget;

		$cache[ $args['widget_id'] ] = ob_get_flush();
		wp_cache_set( 'widget_recent_posts', $cache, 'widget' );
	}

	/**
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['action'] = strip_tags( $new_instance['action'] );

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['widget_recent_entries'] ) ) {
			delete_option( 'widget_recent_entries' );
		}

		return $instance;
	}

	/**
	 *
	 */
	function flush_widget_cache() {
		wp_cache_delete( 'widget_recent_posts', 'widget' );
	}

	/**
	 * @param array $instance
	 */
	function form( $instance ) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$action = isset( $instance['action'] ) ? esc_attr( $instance['action'] ) : '';

		?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'woocommerce-tm-extra-product-options' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                   name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'action' ); ?>"><?php _e( 'Custom action:', 'woocommerce-tm-extra-product-options' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'action' ); ?>"
                   name="<?php echo $this->get_field_name( 'action' ); ?>" type="text" value="<?php echo $action; ?>"/>
        </p>

		<?php
	}
}

/**
 *
 */
function tc_epo_widget() {
	register_widget( 'TC_EPO_Widget' );
}

