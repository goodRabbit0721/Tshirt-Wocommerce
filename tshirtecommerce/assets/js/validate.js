/*
	Plugin validate
	
	* options of html tags
	* data-minlength	
	* data-maxlength
	* data-msg
	* data-type: email, number, url
	* <input type="text" data-minlength="" data-maxlength="" data-msg="" data-type="" class="validate required" />
	
	* use plugin
	* submit form: 		$('from id, class').validate();
	* with ajax: 		check = $('#formid').validate({event: ''});		| check = true or false
	* Click a elment: $('#formid').validate({event: 'click', obj:'#id|.class of elment'});
*/
(function($){
    $.fn.validate =function(options){
		var settings=$.extend({
			event: 'submit',
			elment: '.validate',			
			obj: ''
		}, options); 
		var self = this;
		var check = false;
        this.each(function($this){
		
			if(settings.event == 'submit')
			{
				$(this).on(settings.event, function(e){
					check = checkForm(this, e);
				});
			}else if(settings.event == 'click' && settings.obj != ''){
				check = checkForm(self);
			}else{
				check = checkForm(this);				
			}
        });
		return check;
		
		function checkForm(obj, e)
		{
			var check = true;
			
			$(obj).find(settings.elment).each(function(){
				
				var value = $(this).val();
				
				// check required
				if(settings.required === true)
				{
					if($(this).hasClass('required') == true && value == '')
					{
						check = false;
					}
				}
				
				// check Min length
				var minlength = $(this).data('minlength');
				if(value.length < minlength){
					check = false;
				}
					
				// check Max length
				var maxlength = $(this).data('maxlength');
				if(value.length > maxlength){
					check = false;
				}
				
				// check type
				var type = $(this).data('type');
				switch(type){
					case 'email':
						if(email(value) == false)
							check = false;
						break;
					
					case 'number':
						if(number(value) == false)
							check = false;
						break;
						
					case 'url':
						if(url(value) == false)
							check = false;
						break;
				}
				
				if(check == false)
				{
					//$(this).css({'border':'1px solid #FF0000', 'color':'#FF0000'});
					
					var msg = $(this).data('msg');
					if(typeof msg != 'undefined') alert(msg);
					if(typeof e != 'undefined')	e.preventDefault();
					
					return false;
				}
				return true;
			});
			return check;
		}
    };
	
	function email(value){
		filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if (filter.test(value)) {
			return true;
		}
		else{
			return false;
		}
	}
	
	function number(value){
		filter = /^[0-9]+$/;
		if (filter.test(value)) {
			return true;
		}
		else{
			return false;
		}
	}
	
	function url(value){
		filter = /^(https?|http):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/;
		if (filter.test(value)) {
			return true;
		}
		else{
			return false;
		}
	}
})(jQuery);