;(function($) {
jQuery.fn.smoothSlider=function(args){
	var defaults={
			sliderWidth		:900,
			sliderHeight		:320,
			navArr			:0,
			img_align		:'left'
	}
	var options=jQuery.extend({},defaults,args);
	var self=this;
	this.smoothSliderSize=function(){
		var wrapWidth=this.width();
		var slideri=this.find('.smooth_slideri');
		var slideriW;			
		//calculate max-width of slideri
		if(options.navArr==0) slideriW=wrapWidth;
		else slideriW=wrapWidth-(48+10); //48px for arrows and 10 for additional margin for text
		slideri.css('max-width',slideriW+'px');
		//float excerpt below image 
		if(options.img_align=='left' || options.img_align=='right'){
			var sldrThumb=this.find('.smooth_slider_thumbnail');	
			var sldrThumbW=sldrThumb.outerWidth(true);
			if(slideriW-sldrThumbW < 70){
				if(options.img_align=='right')sldrThumb.removeClass('smoothRight');
				else sldrThumb.removeClass('smoothLeft');
				sldrThumb.addClass('smoothNone');
			}
			else{
				sldrThumb.removeClass('smoothNone');
				if(options.img_align=='right')sldrThumb.addClass('smoothRight');
				else sldrThumb.addClass('smoothLeft');
			}
		}
		//slider height
		var iht=0;
		this.find(".smooth_slideri").each(function(idx,el){
			if(jQuery(el).outerHeight(true)>iht)iht=jQuery(el).outerHeight(true);
		});
		var eHt=this.find(".sldr_title").outerHeight(true) + this.find(".smooth_nav").outerHeight(true);
		var ht=iht + eHt;
		this.height(ht);
		this.find(".smooth_slider_thumbnail").on('load',function(e){
			var pHt=jQuery(this).parents(".smooth_slideri").outerHeight(true)+eHt;
			if(pHt > ht)ht=pHt;		
			self.height(ht);		
		});
		return this;
	};
	this.smoothSliderSize();
	
	//On Window Resize
	jQuery(window).resize(function() { 
		self.smoothSliderSize();
	});
}
})(jQuery);
