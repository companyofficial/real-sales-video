<?php
/*
Plugin Name: Easy Smooth Scroll Links
Plugin URI: https://www.pandasilk.com/wordpress-easy-smooth-scroll-links-plugin/
Description: ESSL Wordpress plugin adds interesting scroll animation effects to page anchors, smooth scroll and more.
Version: 2.1.1
Author: pandasilk
Author URI: https://www.pandasilk.com/wordpress-easy-smooth-scroll-links-plugin/
Text Domain: easy-smooth-scroll-links
Domain Path: /languages
License: GPLv2 or later
*/

function essl_text_domain() {
	load_plugin_textdomain('easy-smooth-scroll-links', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'essl_text_domain' );

//Anchor Button to TinyMCE Editor
global $wp_version;
if ( $wp_version < 3.9 ) {
	if ( ! function_exists('enable_anchor_button') ) {
		function enable_anchor_button($buttons) {
		  $buttons[] = 'anchor';
		  return $buttons;
		}
	}
	add_filter("mce_buttons_2", "enable_anchor_button");
} else {
	add_action( 'init', 'anchor_button' );
	function anchor_button() {
		add_filter( "mce_external_plugins", "anchor_add_button" );
		add_filter( 'mce_buttons_2', 'anchor_register_button' );
	}
	function anchor_add_button( $plugin_array ) {
		$plugin_array['anchor'] = $dir = plugins_url( '/anchor/plugin.min.js', __FILE__ );
		return $plugin_array;
	}
	function anchor_register_button( $buttons ) {
		array_push( $buttons, 'anchor' );
		return $buttons;
	}
}

//Shortcode
if ( ! function_exists('essl_shortcode') ) {
function essl_shortcode( $atts, $content = null ) {
   return '<a id="' . $content . '">';
}
add_shortcode( 'anchor', 'essl_shortcode' );
}


/* 
Registering Options Page
*/	
if(!class_exists('ESSLPluginOptions')) :

// DEFINE PLUGIN ID
define('ESSLPluginOptions_ID', 'essl-plugin-options');
// DEFINE PLUGIN NICK
define('ESSLPluginOptions_NICK', 'ESSL');

    class ESSLPluginOptions
    {
		/** function/method
		* Usage: return absolute file path
		* Arg(1): string
		* Return: string
		*/
		public static function file_path($file)
		{
			return plugin_dir_path( __FILE__ ).$file;
		}
		/** function/method
		* Usage: hooking the plugin options/settings
		* Arg(0): null
		* Return: void
		*/
		public static function register()
		{
			register_setting(ESSLPluginOptions_ID.'_options', 'enable_essl_aggressive');
			register_setting(ESSLPluginOptions_ID.'_options', 'essl_speed');
			register_setting(ESSLPluginOptions_ID.'_options', 'essl_offset');
			register_setting(ESSLPluginOptions_ID.'_options', 'essl_easing');
			register_setting(ESSLPluginOptions_ID.'_options', 'essl_exclude_begin_1');
			register_setting(ESSLPluginOptions_ID.'_options', 'essl_exclude_begin_2');
			register_setting(ESSLPluginOptions_ID.'_options', 'essl_exclude_begin_3');
			register_setting(ESSLPluginOptions_ID.'_options', 'essl_exclude_begin_4');
			register_setting(ESSLPluginOptions_ID.'_options', 'essl_exclude_begin_5');
			register_setting(ESSLPluginOptions_ID.'_options', 'essl_exclude_match_1');
			register_setting(ESSLPluginOptions_ID.'_options', 'essl_exclude_match_2');
			register_setting(ESSLPluginOptions_ID.'_options', 'essl_exclude_match_3');
			register_setting(ESSLPluginOptions_ID.'_options', 'essl_exclude_match_4');			
			register_setting(ESSLPluginOptions_ID.'_options', 'essl_exclude_match_5');				
		}
		/** function/method
		* Usage: hooking (registering) the plugin menu
		* Arg(0): null
		* Return: void
		*/
		public static function menu()
		{
			// Create menu tab
			add_options_page(ESSLPluginOptions_NICK.' Plugin Options', ESSLPluginOptions_NICK, 'manage_options', ESSLPluginOptions_ID.'_options', array('ESSLPluginOptions', 'options_page'));
		}
		/** function/method
		* Usage: show options/settings form page
		* Arg(0): null
		* Return: void
		*/
		public static function options_page()
		{ 
			if (!current_user_can('manage_options')) 
			{
				wp_die( __('You do not have sufficient permissions to access this page.') );
			}
			
			$plugin_id = ESSLPluginOptions_ID;
			// display options page
			include(self::file_path('options.php'));
		}
		
    }
	
	
	// Add settings link on plugin page
	function essl_plugin_action_links($links) { 
	  $settings_link = '<a href="options-general.php?page=essl-plugin-options_options">'.__('Settings', 'easy-smooth-scroll-links' ).'</a>'; 
	  array_unshift($links, $settings_link); 
	  return $links; 
	}
	 
	$plugin = plugin_basename(__FILE__); 
	add_filter("plugin_action_links_$plugin", 'essl_plugin_action_links' );


	if ( is_admin() )
	{
		add_action('admin_init', array('ESSLPluginOptions', 'register'));
		add_action('admin_menu', array('ESSLPluginOptions', 'menu'));
		
	}
	
	if ( !is_admin() )
	{

		add_action('wp_enqueue_scripts', 'essl_enqueue_jquery', 999 );
		add_action('wp_footer', 'essl_script',100);
		
		
		function essl_enqueue_jquery() {
			wp_deregister_script('jquery-easing');
			wp_register_script('jquery-easing','//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js');
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-easing',array('jquery'));		
		}		

		function essl_script() {			
			$essl_exclude_begin_1=$essl_exclude_begin_2=$essl_exclude_begin_3=$essl_exclude_begin_4=$essl_exclude_begin_5=$essl_exclude_match_1=$essl_exclude_match_2=$essl_exclude_match_3=$essl_exclude_match_4=$essl_exclude_match_5='';			
			if(get_option('essl_exclude_begin_1')){ $essl_exclude_begin_1=":not([href^='".get_option('essl_exclude_begin_1')."'])"; }			
			if(get_option('essl_exclude_begin_2')){ $essl_exclude_begin_2=":not([href^='".get_option('essl_exclude_begin_2')."'])"; }			
			if(get_option('essl_exclude_begin_3')){ $essl_exclude_begin_3=":not([href^='".get_option('essl_exclude_begin_3')."'])"; }			
			if(get_option('essl_exclude_begin_4')){ $essl_exclude_begin_4=":not([href^='".get_option('essl_exclude_begin_4')."'])"; }			
			if(get_option('essl_exclude_begin_5')){ $essl_exclude_begin_5=":not([href^='".get_option('essl_exclude_begin_5')."'])"; }			
			if(get_option('essl_exclude_match_1')){ $essl_exclude_match_1=":not([href='".get_option('essl_exclude_match_1')."'])";}			
			if(get_option('essl_exclude_match_2')){ $essl_exclude_match_2=":not([href='".get_option('essl_exclude_match_2')."'])";}			
			if(get_option('essl_exclude_match_3')){ $essl_exclude_match_3=":not([href='".get_option('essl_exclude_match_3')."'])";}			
			if(get_option('essl_exclude_match_4')){ $essl_exclude_match_4=":not([href='".get_option('essl_exclude_match_4')."'])";}			
			if(get_option('essl_exclude_match_5')){ $essl_exclude_match_5=":not([href='".get_option('essl_exclude_match_5')."'])";}						
			$essl_exclude_begin= $essl_exclude_begin_1. $essl_exclude_begin_2. $essl_exclude_begin_3. $essl_exclude_begin_4. $essl_exclude_begin_5;
			$essl_exclude_match= $essl_exclude_match_1. $essl_exclude_match_2. $essl_exclude_match_3. $essl_exclude_match_4. $essl_exclude_match_5;		

			if(get_option('enable_essl_aggressive')=='1'){ ?>	
			<script type="text/javascript">
				jQuery.noConflict();
				(function($){
				  
					var jump=function(e)
					{
					   if (e){
						   var target = $(this).attr("href");
					   }else{
						   var target = location.hash;
					   }
					   
						var scrollToPosition = $(target).offset().top - <?php if (get_option('essl_offset')!='') {echo get_option('essl_offset');} else {echo '20';} ?>;
					
					   $('html,body').animate({scrollTop: scrollToPosition },<?php if (get_option('essl_speed')!='') {echo get_option('essl_speed');} else {echo '900';} ?> ,'<?php echo  get_option('essl_easing','easeInQuint');?>' );

					}

					$('html, body').hide()

					$(document).ready(function()
					{
						$("area[href*=\\#],a[href*=\\#]:not([href=\\#]):not([href^='\\#tab']):not([href^='\\#quicktab']):not([href^='\\#pane'])<?php if($essl_exclude_begin) echo $essl_exclude_begin; ?><?php if($essl_exclude_match) echo $essl_exclude_match; ?>").bind("click", jump);

						if (location.hash){
							setTimeout(function(){
								$('html, body').scrollTop(0).show()
								jump()
							}, 0);
						}else{
						  $('html, body').show()
						}
					});
				  
				})(jQuery)
			</script>
				<?php  } else {  ?>
			<script type="text/javascript">
				jQuery.noConflict();
				(function( $ ) {
					$(function() {
						// More code using $ as alias to jQuery
						$("area[href*=\\#],a[href*=\\#]:not([href=\\#]):not([href^='\\#tab']):not([href^='\\#quicktab']):not([href^='\\#pane'])<?php if($essl_exclude_begin) echo $essl_exclude_begin; ?><?php if($essl_exclude_match) echo $essl_exclude_match; ?>").click(function() {
							if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
								var target = $(this.hash);
								target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
								if (target.length) {
								$('html,body').animate({
								scrollTop: target.offset().top - <?php if (get_option('essl_offset')!='') {echo get_option('essl_offset');} else {echo '20';} ?>  
								},<?php if (get_option('essl_speed')!='') {echo get_option('essl_speed');} else {echo '900';} ?> ,'<?php echo  get_option('essl_easing','easeInQuint');?>');
								return false;
								}
							}
						});
					});
				})(jQuery);	
			</script>				
				<?php }	
		}					
	}	
endif;