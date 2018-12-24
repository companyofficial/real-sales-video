<?php



/**



 * The template for displaying the footer.



 *



 * @package Betheme



 * @author Muffin group



 * @link http://muffingroup.com



 */











$back_to_top_class = mfn_opts_get('back-top-top');







if( $back_to_top_class == 'hide' ){



	$back_to_top_position = false;



} elseif( strpos( $back_to_top_class, 'sticky' ) !== false ){



	$back_to_top_position = 'body';



} elseif( mfn_opts_get('footer-hide') == 1 ){



	$back_to_top_position = 'footer';



} else {



	$back_to_top_position = 'copyright';



}







?>







<?php do_action( 'mfn_hook_content_after' ); ?>







<!-- #Footer -->		



<footer id="Footer" class="clearfix">



	



	<?php if ( $footer_call_to_action = mfn_opts_get('footer-call-to-action') ): ?>



	<div class="footer_action">



		<div class="container">



			<div class="column one column_column">



				<?php echo do_shortcode( $footer_call_to_action ); ?>



			</div>



		</div>



	</div>



	<?php endif; ?>



	



	<?php 



		$sidebars_count = 0;



		for( $i = 1; $i <= 5; $i++ ){



			if ( is_active_sidebar( 'footer-area-'. $i ) ) $sidebars_count++;



		}



		



		if( $sidebars_count > 0 ){



			



			$footer_style = '';



				



			if( mfn_opts_get( 'footer-padding' ) ){



				$footer_style .= 'padding:'. mfn_opts_get( 'footer-padding' ) .';';



			}



			



			echo '<div class="widgets_wrapper" style="'. $footer_style .'">';



				echo '<div class="container">';



						



					if( $footer_layout = mfn_opts_get( 'footer-layout' ) ){



						// Theme Options







						$footer_layout 	= explode( ';', $footer_layout );



						$footer_cols 	= $footer_layout[0];



		



						for( $i = 1; $i <= $footer_cols; $i++ ){



							if ( is_active_sidebar( 'footer-area-'. $i ) ){



								echo '<div class="column '. $footer_layout[$i] .'">';



									dynamic_sidebar( 'footer-area-'. $i );



								echo '</div>';



							}



						}						



						



					} else {



						// Default - Equal Width



						



						$sidebar_class = '';



						switch( $sidebars_count ){



							case 2: $sidebar_class = 'one-second'; break;



							case 3: $sidebar_class = 'one-third'; break;



							case 4: $sidebar_class = 'one-fourth'; break;



							case 5: $sidebar_class = 'one-fifth'; break;



							default: $sidebar_class = 'one';



						}



						



						for( $i = 1; $i <= 5; $i++ ){



							if ( is_active_sidebar( 'footer-area-'. $i ) ){



								echo '<div class="column '. $sidebar_class .'">';



									dynamic_sidebar( 'footer-area-'. $i );



								echo '</div>';



							}



						}



						



					}



				



				echo '</div>';



			echo '</div>';



		}



	?>











	<?php if( mfn_opts_get('footer-hide') != 1 ): ?>



	



		<div class="footer_copy">



			<div class="container">



				<div class="column one">







					<?php 



						if( $back_to_top_position == 'copyright' ){



							echo '<a id="back_to_top" class="button button_js" href=""><i class="icon-up-open-big"></i></a>';



						}



					?>



					



					<!-- Copyrights -->



					<div class="copyright">



						<?php 



							if( mfn_opts_get('footer-copy') ){



								echo do_shortcode( mfn_opts_get('footer-copy') );



							} else {



								echo '&copy; '. date( 'Y' ) .' '. get_bloginfo( 'name' ) .'. All Rights Reserved. <a target="_blank" rel="nofollow" href="http://muffingroup.com">Muffin group</a>';



							}



						?>



					</div>



					



					<?php 



						if( has_nav_menu( 'social-menu-bottom' ) ){



							mfn_wp_social_menu_bottom();



						} else {



							get_template_part( 'includes/include', 'social' );



						}



					?>



							



				</div>



			</div>



		</div>



	



	<?php endif; ?>



	



	



	<?php 



		if( $back_to_top_position == 'footer' ){



			echo '<a id="back_to_top" class="button button_js in_footer" href=""><i class="icon-up-open-big"></i></a>';



		}



	?>







	



</footer>







</div><!-- #Wrapper -->







<?php 



	// Responsive | Side Slide



	if( mfn_opts_get( 'responsive-mobile-menu' ) ){



		get_template_part( 'includes/header', 'side-slide' );



	}



?>







<?php



	if( $back_to_top_position == 'body' ){



		echo '<a id="back_to_top" class="button button_js '. $back_to_top_class .'" href=""><i class="icon-up-open-big"></i></a>';



	}



?>







<?php if( mfn_opts_get('popup-contact-form') ): ?>



	<div id="popup_contact">



		<a class="button button_js" href="#"><i class="<?php mfn_opts_show( 'popup-contact-form-icon', 'icon-mail-line' ); ?>"></i></a>



		<div class="popup_contact_wrapper">



			<?php echo do_shortcode( mfn_opts_get('popup-contact-form') ); ?>



			<span class="arrow"></span>



		</div>



	</div>



<?php endif; ?>







<?php do_action( 'mfn_hook_bottom' ); ?>



	



<!-- wp_footer() -->



<?php wp_footer(); ?>
<!-- Modal -->

<div id="myModal" class="modal fade" role="dialog">

  <div class="modal-dialog">



    <!-- Modal content-->

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

      </div>

      <div class="modal-body">
        <div class="get-in-touch">
<h2>Get in Touch with us</h2>
<h4>Get Free Quote and consultation for your project</h4>
       <?php echo do_shortcode('[formidable id=4]'); ?>
</div>
      </div>

      

    </div>



  </div>

</div>

<!-- Modal -->

<div id="myModal22" class="modal fade" role="dialog">

  <div class="modal-dialog">



    <!-- Modal content-->

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

      </div>

      <div class="modal-body">
        <div class="get-in-touch">
<h2>Get in Touch with us</h2>
<h4>Get Free Quote and consultation for your project</h4>
       <?php echo do_shortcode('[formidable id=10]'); ?>
</div>
      </div>

      

    </div>



  </div>

</div>

<script type="text/javascript">

	jQuery(document).ready(function(){

	jQuery('.action-menu a').attr("data-toggle","modal");

	jQuery('.action-menu a').attr("data-target","#myModal");
	
	jQuery('html[lang="nl-NL"] .action-menu a').attr("data-toggle","modal");

	jQuery('html[lang="nl-NL"] .action-menu a').attr("data-target","#myModal22");

jQuery('select#dutch-video-voiceover-e-250 option:contains("Choose an option")').remove();

    jQuery(".filter-button").click(function(){

        var value = jQuery(this).attr('data-filter');

		jQuery('.portfolio-head button').removeClass('asd');

		jQuery(this).addClass('asd');

        

        if(value == "all")

        {

            //$('.filter').removeClass('hidden');

            jQuery('.filter').show('1000');

        }

        else

        {

//            $('.filter[filter-item="'+value+'"]').removeClass('hidden');

//            $(".filter").not('.filter[filter-item="'+value+'"]').addClass('hidden');

            jQuery(".filter").not('.'+value).hide('3000');

            jQuery('.filter').filter('.'+value).show('3000');

            

        }

    });

    

    if (jQuery(".filter-button").removeClass("active")) {

jQuery(this).removeClass("active");

}

jQuery(this).addClass("active");









    //FANCYBOX

    //https://github.com/fancyapps/fancyBox

    jQuery(".fancybox").fancybox({

        openEffect: "none",

        closeEffect: "none"

    });

});

	</script>

 <script>



// Set the date were counting down to

var countDownDate = new Date("January 15, 2019 15:37:25").getTime();







// Update the count down every 1 second



var x = setInterval(function() {







    // Get todays date and time



    var now = new Date().getTime();



    



    // Find the distance between now an the count down date



    var distance = countDownDate - now;



    



    // Time calculations for days, hours, minutes and seconds



    var days = Math.floor(distance / (1000 * 60 * 60 * 24));



    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));



    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));



    var seconds = Math.floor((distance % (1000 * 60)) / 1000);



    



    // Output the result in an element with id="demo"



   // document.getElementById("demo").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";



    document.getElementById("days").innerHTML = days;



	document.getElementById("hours").innerHTML = hours;



	document.getElementById("mins").innerHTML = minutes;



	document.getElementById("sec").innerHTML = seconds;



	



	document.getElementById("days2").innerHTML = days;



	document.getElementById("hours2").innerHTML = hours;



	document.getElementById("mins2").innerHTML = minutes;



	document.getElementById("sec2").innerHTML = seconds;



	



	



	



    // If the count down is over, write some text 



    if (distance < 0) {



        clearInterval(x);



        document.getElementById("demo").innerHTML = "EXPIRED";



    }



}, 1000);



</script>

<?php if (is_page('Portfolio')) { ?>

<script src="<?php bloginfo('template_directory'); ?>/js/jquery-1.11.2.min.js"></script>

<?php } ?>

<script src="<?php bloginfo('template_directory'); ?>/js/bootstrap.js"></script>
<?php if(!is_page('Cart')) { ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/css/slick.css">
  <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/css/slick-theme.css">
  
<script src="<?php bloginfo('template_url'); ?>/js/slick.js" type="text/javascript" charset="utf-8"></script>
<?php } ?>
<?php if(is_page('Cart')) { ?>
<link rel='stylesheet' id='woo-social-share-discount-couponone-css'  href='<?php bloginfo('template_url'); ?>/css/jquery.fancybox-1.3.4.css?ver=1.0.0' type='text/css' media='all' />
<script type='text/javascript' src='<?php bloginfo('template_directory'); ?>/js/jquery.fancybox-1.3.4.pack.js?ver=1.0.0'></script>
<script type='text/javascript' src='<?php bloginfo('template_directory'); ?>/js/jquery.easing-1.3.pack.js?ver=1.0.0'></script>
<script type='text/javascript' src='<?php bloginfo('template_directory'); ?>/js/jquery.mousewheel-3.0.4.pack.js?ver=1.0.0'></script>
<?php } ?>
  

	 <script type="text/javascript">
    jQuery(document).on('ready', function() {

var flag = jQuery('.wpml-ls-item').wrapAll();
jQuery('li.carttop').after(flag);

	 jQuery(".regular").slick({
        dots: false,
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
		arrows: true,
		nextArrow: '<i class="ultsl-arrow-right4 slick-next default" style="color:#ffffff; font-size:25px;"></i>',
		prevArrow: '<i class="ultsl-arrow-left4 slick-prev default" style="color:#ffffff; font-size:25px;"></i>',
		responsive: [
							{
							  breakpoint: 1025,
							  settings: {
								slidesToShow: 3,
								slidesToScroll: 3,  
							  }
							},
							{
							  breakpoint: 769,
							  settings: {
								slidesToShow: 3,
								slidesToScroll: 3
							  }
							},
							{
							  breakpoint: 481,
							  settings: {
								slidesToShow: 1,
								slidesToScroll: 1
							  }
							}
						],
		pauseOnHover: true,
      });
	  });

jQuery(".vids video").click(function(){
jQuery(this).trigger("play");
jQuery(".text").hide();


});
</script>
 <script type="text/javascript">
    jQuery(document).ready(function(){


var videocontainer = '#asd';
jQuery(videocontainer).on('play', function() {
  //Actions when video play selected
  jQuery('.text').fadeOut(400);
});
})

</script>
<?php 
if(is_page('Pricing')) { ?>
<script type="text/javascript">
jQuery(document).ready(function(){
  jQuery('html[lang="nl-NL"] form.variations_form.cart table tr td select option').removeAttr("selected");
jQuery('html[lang="nl-NL"] form.variations_form.cart table tr td select').find('option[value=YES]').attr('selected','selected');
jQuery('html[lang="nl-NL"] .woocommerce ul.products li.product table tr td.labelsn label[for="select-your-style"]').text('Selecteer uw stijil');
jQuery('html[lang="nl-NL"] .woocommerce ul.products li.product table tr td.labelsn label[for="dutch-video-voiceover-e-250"]').text('NL video + Voice over (+€250,-)');
jQuery('html[lang="nl-NL"] .woocommerce ul.products li.product table tr td.last').text('100% Tevredenheids garantie');
});
//jQuery('form[data-product_id="94"] select#select-your-style option:first-child').attr('value','Choose an option');

//jQuery(".single_add_to_cart_button").addClass('disable');

//jQuery("form[data-product_id="94"] select#select-your-style").change(function(){
//alert("");
//var asd = jQuery(this).val();
//console.log(asd);
//  		if((asd) == "Choose an option") {

//alert("asdd");
//jQuery(".single_add_to_cart_button").addClass('disable');
//jQuery(".disable").click(function(){
//alert("asd");

//});
//} else{
//jQuery(".single_add_to_cart_button").removeClass('disable');
//}
//		});



</script>
<?php } ?>
<script type="text/javascript">
jQuery('.woocommerce .shop_table .product-name a').removeAttr('href');


</script>
</body>
<link rel='stylesheet' id='formidable-css'  href='https://www.realsalesvideo.com/wp-content/plugins/formidable/css/formidableforms.css?ver=11301355' type='text/css' media='all' />
<link rel='stylesheet' id='frm_fonts-css'  href='https://www.realsalesvideo.com/wp-content/plugins/formidable/css/frm_fonts.css?ver=3.04' type='text/css' media='all' />
<script type='text/javascript' src='https://www.realsalesvideo.com/wp-content/plugins/formidable/js/frm.min.js?ver=3.04'></script>
<script type='text/javascript' defer="defer" async="async" src='https://www.google.com/recaptcha/api.js?ver=4.9.8'></script>


</html>