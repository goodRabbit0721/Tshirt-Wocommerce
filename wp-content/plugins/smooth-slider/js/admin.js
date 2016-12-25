jQuery(document).ready(function(){
  jQuery('#later').on("click", function(){
	var r = jQuery('#smooth_reviewme').val();
	var data = {};
	data['reviewme'] = parseInt(r);
	data['action'] = 'smooth_update_review_me';
	jQuery.post(ajaxurl, data, function(response) {
		if(response) {
			alert(jQuery('#smooth_reviewme').val(response));
			jQuery('#smooth_reviewme').val(response);
		}
		jQuery('#reviewme').remove();
	 });
     });
  jQuery('#already').on("click", function(){
	var data = {};
	data['reviewme'] = 0;
	data['action'] = 'smooth_update_review_me';
	jQuery.post(ajaxurl, data, function(response) {
		if(response) {
			jQuery('#smooth_reviewme').val(response);
		}
		jQuery('#reviewme').remove();
	 });
   });
});
jQuery(function () {
    jQuery('.moreInfo').each(function () {
    // options
    var distance = 10;
    var time = 250;
    var hideDelay = 200;

    var hideDelayTimer = null;

    // tracker
    var beingShown = false;
    var shown = false;
    
    var trigger = jQuery('.trigger', this);
    var tooltip = jQuery('.tooltip', this).css('opacity', 0);
	
    // set the mouseover and mouseout on both element
    jQuery([trigger.get(0), tooltip.get(0)]).mouseover(function () {
      // stops the hide event if we move from the trigger to the tooltip element
      if (hideDelayTimer) clearTimeout(hideDelayTimer);

      // don't trigger the animation again if we're being shown, or already visible
      if (beingShown || shown) {
        return;
      } else {
        beingShown = true;

        // reset position of tooltip box
        tooltip.css({
          display: 'block' // brings the tooltip back in to view
        })

        // (we're using chaining on the tooltip) now animate it's opacity and position
        .animate({
          /*top: '-=' + distance + 'px',*/
          opacity: 1
        }, time, 'swing', function() {
          // once the animation is complete, set the tracker variables
          beingShown = false;
          shown = true;
        });
      }
    }).mouseout(function () {
      // reset the timer if we get fired again - avoids double animations
      if (hideDelayTimer) clearTimeout(hideDelayTimer);
      
      // store the timer so that it can be cleared in the mouseover if required
      hideDelayTimer = setTimeout(function () {
        hideDelayTimer = null;
        tooltip.animate({
          /*top: '-=' + distance + 'px',*/
          opacity: 0
        }, time, 'swing', function () {
          // once the animate is complete, set the tracker variables
          shown = false;
          // hide the tooltip entirely after the effect (opacity alone doesn't do the job)
          tooltip.css('display', 'none');
        });
      }, hideDelay);
    });
  });
/* Validation Shifted here from setting.php start 2.6  */
  jQuery('#smooth_slider_form').submit(function(event) { 
			
			var slide_animationlen=jQuery("#smooth_slider_transition").val();
			if(slide_animationlen=='' || slide_animationlen <= 0 || isNaN(slide_animationlen)) {
				alert("Slide Animation Length should be a number greater than 0!"); 
				jQuery("#smooth_slider_transition").addClass('error');
				jQuery("html,body").animate({scrollTop:jQuery('#smooth_slider_transition').offset().top-50}, 600);
				return false;
			}
			var slider_speed=jQuery("#smooth_slider_speed").val();
			if(slider_speed=='' || slider_speed <= 0 || isNaN(slider_speed)) {
				alert("Slide Pause Interval should be a number greater than 0!"); 
				jQuery("#smooth_slider_speed").addClass('error');
				jQuery("html,body").animate({scrollTop:jQuery('#smooth_slider_speed').offset().top-50}, 600);
				return false;
			}
			var slider_posts=jQuery("#smooth_slider_no_posts").val();
			if(slider_posts=='' || slider_posts <= 0 || isNaN(slider_posts)) {
				alert("Number of Posts in the Slideshow should be a number greater than 0!"); 
				jQuery("#smooth_slider_no_posts").addClass('error');
				jQuery("html,body").animate({scrollTop:jQuery('#smooth_slider_no_posts').offset().top-50}, 600);
				return false;
			}			
			var slider_width=jQuery("#smooth_slider_width").val();
			if(slider_width=='' || slider_width <= 0 || isNaN(slider_width)) {
				alert("Slider Width should be a number greater than 0!"); 
				jQuery("#smooth_slider_width").addClass('error');
				jQuery("html,body").animate({scrollTop:jQuery('#smooth_slider_width').offset().top-50}, 600);
				return false;
			}	
			var slider_height=jQuery("#smooth_slider_height").val();
			if(slider_height=='' || slider_height <= 0 || isNaN(slider_height)) {
				alert("Slider Height should be a number greater than 0!"); 
				jQuery("#smooth_slider_height").addClass('error');
				jQuery("html,body").animate({scrollTop:jQuery('#smooth_slider_height').offset().top-50}, 600);
				return false;
			}
			
			/*Added for slider name selection Start*/

			var slider_id = jQuery("#smooth_slider_id").val(),	
			    hiddensliderid=jQuery("#hidden_sliderid").val(),		
			    slider_catslug=jQuery("#smooth_slider_catslug").val(),
			    hiddencatslug=jQuery("#hidden_category").val(),
			    prev=jQuery("#smooth_slider_preview").val(),
			    hiddenpreview=jQuery("#hidden_preview").val(),
			    new_save=jQuery("#oldnew").val();
			if(prev=='1' && slider_catslug=='') {
				alert("Select the category whose posts you want to show!"); 
				jQuery("#smooth_slider_catslug").addClass('error');
				jQuery("html,body").animate({scrollTop:jQuery('#smooth_slider_catslug').offset().top-50}, 600);
				return false;
			}
			if(prev=='0') {
				if(slider_id=='' || isNaN(slider_id) || slider_id<=0){
					alert("Slider Name Should be selected!"); 
					jQuery("#smooth_slider_id").addClass('error');
					jQuery("html,body").animate({scrollTop:jQuery('#smooth_slider_id').offset().top-50}, 600);
					return false;
				}
			}

                              /* Added for slider name selection End*/
			var prev=jQuery("#smooth_slider_preview").val(),
			    hiddenpreview=jQuery("#hidden_preview").val(),
			    hiddencatslug=jQuery("#hidden_category").val(),
			    hiddensliderid=jQuery("#hidden_sliderid").val(),
			    slider_id = jQuery("#smooth_slider_id").val(),			
			    slider_catslug=jQuery("#smooth_slider_catslug").val();				
			if(prev == "0" && slider_id == ''){
				alert("Slider id should be mentioned");
				jQuery("#smooth_slider_id").addClass('error');
				jQuery("html,body").animate({scrollTop:jQuery('#smooth_slider_id').offset().top-50}, 600);
				return false;
			}
			if(prev == "1" && slider_catslug == ''){
				alert("Category slug should be mentioned whose posts you want to display in slider");
				jQuery("#smooth_slider_catslug").addClass('error');
				jQuery("html,body").animate({scrollTop:jQuery('#smooth_slider_catslug').offset().top-50}, 600);
				return false;
			}
			if(hiddenpreview != prev || slider_id != hiddensliderid || slider_catslug != hiddencatslug ) jQuery('#smoothpopup').val("1");					
			else jQuery('#smoothpopup').val("0");	
		});

/* Validation Shifted here from setting.php end 2.6  */

/* Added for preview - start 2.6 */
var selpreview=jQuery("#smooth_slider_preview").val();
if(selpreview=='2')
	jQuery("#smooth_slider_form .form-table tr.smooth_slider_params").css("display","none");
else if(selpreview=='1'){
	jQuery("#smooth_slider_form .smooth_sid").css("display","none");
	jQuery("#smooth_slider_form .form-table tr.smooth_slider_params").css("display","table-row");
	jQuery("#smooth_slider_form .smooth_catslug").css("display","block");
}
else if(selpreview=='0'){
	jQuery("#smooth_slider_form .smooth_catslug").css("display","none");
	jQuery("#smooth_slider_form .form-table tr.smooth_slider_params").css("display","table-row");
	jQuery("#smooth_slider_form .smooth_sid").css("display","block");
}
/* Added for preview - end 2.6*/

});

/* Added for preview start 2.6*/
function checkpreview(curr_preview){
	if(curr_preview=='2')
		jQuery("#smooth_slider_form .form-table tr.smooth_slider_params").css("display","none");
	else if(curr_preview=='1'){
		jQuery("#smooth_slider_form .smooth_sid").css("display","none");
		jQuery("#smooth_slider_form .form-table tr.smooth_slider_params").css("display","table-row");
		jQuery("#smooth_slider_form .smooth_catslug").css("display","block");
	}
	else if(curr_preview=='0'){
		jQuery("#smooth_slider_form .smooth_catslug").css("display","none");
		jQuery("#smooth_slider_form .form-table tr.smooth_slider_params").css("display","table-row");
		jQuery("#smooth_slider_form .smooth_sid").css("display","block");
	}
}
/* Added for preview start 2.6*/

