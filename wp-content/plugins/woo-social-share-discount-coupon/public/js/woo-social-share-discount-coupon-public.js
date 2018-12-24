
jQuery(document).ready(function() {

    jQuery("a.fancybox_social_share").fancybox({
        type: "html",
        content: jQuery('#data1').html(),
        scrolling: "no",
        width: 400,
        height: 300,
        showCloseButton: true,
        model: true,
        onStart: function() {
            jQuery("#fancybox-wrap").addClass("thankyou-fancy");
        },
        onCancel: function() {
            jQuery("#fancybox-wrap").addClass("thankyou-fancy");
        },
        onComplete: function() {
            jQuery("#fancybox-wrap").addClass("thankyou-fancy");
        },
        onClosed: function() {
            jQuery("#fancybox-wrap").addClass("thankyou-fancy");
        }

    });

    jQuery('form').each(function() {
        var cmdcode = jQuery(this).find('input[name="cmd"]').val();
        var bncode = jQuery(this).find('input[name="bn"]').val();

        if (cmdcode && bncode) {
            jQuery('input[name="bn"]').val("Multidots_SP");
        } else if ((cmdcode) && (!bncode)) {
            jQuery(this).find('input[name="cmd"]').after("<input type='hidden' name='bn' value='Multidots_SP' />");
        }


    });


});
