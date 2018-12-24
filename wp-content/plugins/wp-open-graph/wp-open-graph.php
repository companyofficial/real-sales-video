<?php 
/**
 * Plugin name: WP Open Graph
 * Plugin URI:  https://wordpress.org/plugins/wp-open-graph/
 * Description: WP Open Graph allows custom input open graph meta data to any content type. (Or use data from All-In-One-Seo-Pack or Wordpress-Seo-By-Yoast)
 * Version:     1.6
 * Author:      Custom4Web
 * Author URI:  https://www.custom4web.com/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

//Main Admin Setting
require_once 'main.admin.class.php';
NY_OG_Main_Admin::init();
//Taxonomy Admin Setting
//require_once 'taxonomy.admin.class.php';
//NY_OG_Taxonomy_Admin::init();
//Print og data
require_once 'output.class.php';
global $NY_OG_Output;
$NY_OG_Output = new NY_OG_Output();
//Admin 
require_once 'admin.class.php';
$NY_OG_Admin = new NY_OG_Admin();