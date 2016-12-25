(function($) {
	$(document).ready(function() {
		function slider_add_file(event,elm) { 
			var frame;
			var $el = $(this);
			var wrapper=jQuery(elm).parents('.uploaded-images');
			event.preventDefault();

			// If the media frame already exists, reopen it.
			if ( frame ) {
				frame.open();
				return;
			}

			// Create the media frame.
			frame = wp.media({
				// Set the title of the modal.
				title: 'Upload/Select Images',
				multiple: true,
				// Customize the submit button.
				button: {
					// Set the text of the button.
					text: 'Add to Slider',
					// Tell the button not to close the modal, since we're
					// going to refresh the page when the image is selected.
					close: false
				}
			});
			frame.on( 'select', function() {
				// Grab the selected attachment.
				var attachments = frame.state().get('selection').toArray();
				frame.close();
				if(attachments.length>0){
					var imgdiv='';
					for(i=0;i<attachments.length;i++){
						var imgId=parseInt(attachments[i].id);
						imgdiv+='<div class="addedImg"><input type="hidden" name="imgID[]" value="'+imgId+'" /><div class="imgCont"><img title="'+attachments[i].attributes.title+'" src="'+attachments[i].attributes.url+'" width="200"  /><span class="addedImgEdit"></span><span class="addedImgDel"></span></div><div class="ImgDetails"><div class="fL"><span class="imgTitle"><input placeholder="Title" title="Enter Image Title" type="text" name="title['+imgId+']" value="'+attachments[i].attributes.title+'" /></span><span class="imgDesc"><textarea placeholder="Description" title="Enter Image Description" rows=3 name="desc['+imgId+']">'+attachments[i].attributes.description+'</textarea></span></div><div class="fR"><span class="imgLink"><strong>Link to: </strong><input type="text" value="" name="link['+imgId+']" /></span><span class="imgNoLink"><strong>Do not link to any url: &nbsp; </strong><input type="checkbox" value="1" name="nolink['+imgId+']" /></span></div></div></div>';
					};
					wrapper.find('.addImgForm').prepend(imgdiv);
					if(wrapper.find('.addSave').length<=0)wrapper.find('.image-uploader').prepend('<input type="submit" class="button-primary addSave" value="Save" name="addSave" />');
					if(attachments.length>0){wrapper.find('.slider_images_upload').val('Add More Images');}
					jQuery('.addedImg').hover(function(){ jQuery(this).find('img').css('opacity','0.6');jQuery(this).find('.addedImgEdit,.addedImgDel').fadeIn(500);},
					function(){jQuery(this).find('img').css('opacity','1');jQuery(this).find('.addedImgEdit,.addedImgDel').fadeOut('fast');});
					jQuery('.addedImgDel').click(function(){
						jQuery(this).parent('.imgCont').parent('.addedImg').fadeOut(400,function(){jQuery(this).remove();});
					});
					jQuery( '.addedImgEdit').unbind( "click" );
					jQuery('.ImgDetails').hide();
					jQuery('.addedImgEdit').click(function(){
						var imgDetails=jQuery(this).parent('.imgCont').parent('.addedImg').find('.ImgDetails');
						var imgWrapper=jQuery(this).parents('.uploaded-images');
						imgDetails.width((imgWrapper.width() - 220));
						imgDetails.fadeToggle("slow");
					});	
				}
			});
			// Finally, open the modal.
			frame.open();
		}
        
        $('.upload-button').click( function( event ) {
        	slider_add_file(event,this);
        });	
    });
})(jQuery);
