/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-11-26
 *
 * API
 *
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
 
/*** display tab event when click li > a ***/
jQuery('#price-discount-tab a').click(function (e) {
  e.preventDefault();
  jQuery(this).tab('show');
});

var count_size 		= 7;         // default size (A0 -> A6)
var screen_row 		= 2;         // default row of screen table
var embroidery_row 	= 2;     // default row of embroidery tbale
var counttable 		= 1;         // count table
var counttable2 	= 1;        // count table

$(function(){
	/*** checkbox - textbox discount ***/ 
	// dtg
	jQuery('.chk_allow_dtg_discount_front').on('change', function(){
		var done = (jQuery(this).is(':checked')) ? true : false;
		if(done)
		{
			jQuery('.allow_dtg_discount_front').css('display', 'block');
		}
		else
		{
			jQuery('.allow_dtg_discount_front').css('display', 'none');
		}
	});
	jQuery('.chk_allow_dtg_discount_back').on('change', function(){
		var done = (jQuery(this).is(':checked')) ? true : false;
		if(done)
		{
			jQuery('.allow_dtg_discount_back').css('display', 'block');
		}
		else
		{
			jQuery('.allow_dtg_discount_back').css('display', 'none');
		}
	});
	jQuery('.chk_allow_dtg_discount_left').on('change', function(){
		var done = (jQuery(this).is(':checked')) ? true : false;
		if(done)
		{
			jQuery('.allow_dtg_discount_left').css('display', 'block');
		}
		else
		{
			jQuery('.allow_dtg_discount_left').css('display', 'none');
		}
	});
	jQuery('.chk_allow_dtg_discount_right').on('change', function(){
		var done = (jQuery(this).is(':checked')) ? true : false;
		if(done)
		{
			jQuery('.allow_dtg_discount_right').css('display', 'block');
		}
		else
		{
			jQuery('.allow_dtg_discount_right').css('display', 'none');
		}
	});
	// screen
	jQuery('.chk_allow_screen_discount_front').on('change', function(){
		var done = (jQuery(this).is(':checked')) ? true : false;
		if(done)
		{
			jQuery('.allow_screen_discount_front').css('display', 'block');
		}
		else
		{
			jQuery('.allow_screen_discount_front').css('display', 'none');
		}
	});
	jQuery('.chk_allow_screen_discount_back').on('change', function(){
		var done = (jQuery(this).is(':checked')) ? true : false;
		if(done)
		{
			jQuery('.allow_screen_discount_back').css('display', 'block');
		}
		else
		{
			jQuery('.allow_screen_discount_back').css('display', 'none');
		}
	});
	jQuery('.chk_allow_screen_discount_left').on('change', function(){
		var done = (jQuery(this).is(':checked')) ? true : false;
		if(done)
		{
			jQuery('.allow_screen_discount_left').css('display', 'block');
		}
		else
		{
			jQuery('.allow_screen_discount_left').css('display', 'none');
		}
	});
	jQuery('.chk_allow_screen_discount_right').on('change', function(){
		var done = (jQuery(this).is(':checked')) ? true : false;
		if(done)
		{
			jQuery('.allow_screen_discount_right').css('display', 'block');
		}
		else
		{
			jQuery('.allow_screen_discount_right').css('display', 'none');
		}
	});
	// submilation
	jQuery('.chk_allow_sublimation_discount_front').on('change', function(){
		var done = (jQuery(this).is(':checked')) ? true : false;
		if(done)
		{
			jQuery('.allow_sublimation_discount_front').css('display', 'block');
		}
		else
		{
			jQuery('.allow_sublimation_discount_front').css('display', 'none');
		}
	});
	jQuery('.chk_allow_sublimation_discount_back').on('change', function(){
		var done = (jQuery(this).is(':checked')) ? true : false;
		if(done)
		{
			jQuery('.allow_sublimation_discount_back').css('display', 'block');
		}
		else
		{
			jQuery('.allow_sublimation_discount_back').css('display', 'none');
		}
	});
	jQuery('.chk_allow_sublimation_discount_left').on('change', function(){
		var done = (jQuery(this).is(':checked')) ? true : false;
		if(done)
		{
			jQuery('.allow_sublimation_discount_left').css('display', 'block');
		}
		else
		{
			jQuery('.allow_sublimation_discount_left').css('display', 'none');
		}
	});
	jQuery('.chk_allow_sublimation_discount_right').on('change', function(){
		var done = (jQuery(this).is(':checked')) ? true : false;
		if(done)
		{
			jQuery('.allow_sublimation_discount_right').css('display', 'block');
		}
		else
		{
			jQuery('.allow_sublimation_discount_right').css('display', 'none');
		}
	});
	// embroidery
	jQuery('.chk_allow_embroidery_discount_front').on('change', function(){
		var done = (jQuery(this).is(':checked')) ? true : false;
		if(done)
		{
			jQuery('.allow_embroidery_discount_front').css('display', 'block');
		}
		else
		{
			jQuery('.allow_embroidery_discount_front').css('display', 'none');
		}
	});
	jQuery('.chk_allow_embroidery_discount_back').on('change', function(){
		var done = (jQuery(this).is(':checked')) ? true : false;
		if(done)
		{
			jQuery('.allow_embroidery_discount_back').css('display', 'block');
		}
		else
		{
			jQuery('.allow_embroidery_discount_back').css('display', 'none');
		}
	});
	jQuery('.chk_allow_embroidery_discount_left').on('change', function(){
		var done = (jQuery(this).is(':checked')) ? true : false;
		if(done)
		{
			jQuery('.allow_embroidery_discount_left').css('display', 'block');
		}
		else
		{
			jQuery('.allow_embroidery_discount_left').css('display', 'none');
		}
	});
	jQuery('.chk_allow_embroidery_discount_right').on('change', function(){
		var done = (jQuery(this).is(':checked')) ? true : false;
		if(done)
		{
			jQuery('.allow_embroidery_discount_right').css('display', 'block');
		}
		else
		{
			jQuery('.allow_embroidery_discount_right').css('display', 'none');
		}
	});

    /***add row to dtg table***/
    jQuery('#addMore1').on('click', function() {
        var data 	= $("#dtgtable tr.tr:last").clone(true).appendTo("#dtgtable");
        data.find("input.ivalue").val();
    });

     /***add row to screen table***/
     jQuery('#addMore2').on('click', function() {
        var valSize 	= $("#screensizeselect option:selected").val();
        var valColor 	= $("#screencolorselect option:selected").val();
        counttable 		= 1;
		var i 			= 1;
		
        if(valSize == 'Yes') counttable  = 7;        
        while(i <= counttable)
		{
             var tblid 			= 	'#screentable' + i;
             var iRows 			= 	$(tblid + ' tr.tr').length;
             var sClass 		= 	$(tblid + ' tr.tr:last').find('input').attr('class') + ""; 
             var fClass 		= 	sClass.split(" ");                                         
             var strClassName 	= 	'.' + fClass[2];                                     
             var iSizeName 		= 	7 - i;
			 
             if($(tblid).length){
                 //var qClass 	= 	'.qvalue' + iRows;
                 var qClass 	= 	strClassName;                                         
                 var qVal 		= 	$(tblid + ' tr.tr').find('input' + qClass).val();
                 var strHtml 	=	"<tr class='tr'>"
								+   "<td class='col-sm-2'>"
								+   "<div class='col-sm-6'>"
								+   "<input class='form-control input-sm qvalue" 
								+ 	(parseInt(iRows) + 1) + "' type='number' min='1'"
								+   " onblur='checkFinal(this)'"
								+   " name='setting[pricediscount][screen][A" + iSizeName + "][quantity][]'"
								+   " value='" + qVal + "'>"
								+   "</div>"
								+   "<label class='col-sm-6'>" + lang_price_discount.labeltext.product + "</label>"
								+   "</td>";
                 for(j = 1; j<= valColor; j++){
                     //var tClass 	= '.ivalue' + iRows + '_' + j;
                     var tClass = strClassName.replace('qvalue', 'ivalue') + '_' + j;
                     var iVal 	= $(tblid + ' tr.tr').find('input' + tClass).val();
                     strHtml    +=  "<td class='col-sm-1'>"
                                +   "<input class='form-control input-sm ivalue" 
								+ 	(parseInt(iRows) + 1) + '_' + j
                                +   "' onblur='checkFinal(this)'"
                                +   " type='text'"
                                +   " name='setting[pricediscount][screen][A" + iSizeName + "][" + j + "][]'"
                                +   " value='" + iVal + "'></td>"
                 }
                 strHtml    +=  "<td class='right col-sm-1'>"
                            +   "<a class='deleterow2 c" + i + "' href='javascript:void(0)'><i class='fa fa-times'></i></a>"
                            +   "</td>"
                            +   "</tr>";
                 $(tblid).append(strHtml);
             }
             i++;
         }
         screen_row++;
      });

      /*** add row to sublimation table ***/
      jQuery('#addMore3').on('click', function() {
          var data = $("#sublimationtable tr.tr:last").clone(true).appendTo("#sublimationtable"); // fix bug can not delete first row 2015.11.19
          data.find("input.ivalue").val();
       });

       /*** add row to emnroidery table ***/
       jQuery('#addMore4').on('click', function() {
           var valSize 	= $("#embroiderysizeselect option:selected").val();
           var valColor = $("#embroiderycolorselect option:selected").val();
           counttable 	= 1;
		   var i 		= 1;
		   
           if(valSize == 'Yes') counttable  = 7;           
           while(i <= counttable)
		   {
               var tblid 		= '#embroiderytable' + i;
               var iRows 		= $(tblid + ' tr.tr').length;
               var sClass 		= $(tblid + ' tr.tr:last').find('input').attr('class') + ""; 
               var fClass 		= sClass.split(" ");                                         
               var strClassName = '.' + fClass[2];                                     
               var iSizeName 	= 7 - i;
               if($(tblid).length){
                   //var qClass = '.qvalue' + iRows;
                   var qClass 	= strClassName;                                         
                   var qVal 	= $(tblid + ' tr.tr').find('input' + qClass).val();
                   var strHtml 	= "<tr class='tr'>"
								+   "<td class='col-sm-2'>"
								+   "<div class='col-sm-6'>"
								+   "<input class='form-control input-sm qvalue" 
								+ 	(parseInt(iRows) + 1) + "' type='number' min='1'"
								+   " onblur='checkFinal(this)'"
								+   " name='setting[pricediscount][embroidery][A" + iSizeName + "][quantity][]'"
								+   " value='" + qVal + "'>"
								+   "</div>"
								+   "<label class='col-sm-6'>" + lang_price_discount.labeltext.product + "</label>"
								+   "</td>";
                   for(j = 1; j<= valColor; j++){
                       //var tClass = '.ivalue' + iRows + '_' + j;
                       var tClass 	= strClassName.replace('qvalue', 'ivalue') + '_' + j;
                       var iVal 	= $(tblid + ' tr.tr').find('input' + tClass).val();
                       strHtml    	+=  "<td class='col-sm-1'>"
									+   "<input class='form-control input-sm ivalue" + (parseInt(iRows) + 1) + '_' + j
									+   "' onblur='checkFinal(this)'"
									+   " type='text'"
									+   " name='setting[pricediscount][embroidery][A" + iSizeName + "][" + j + "][]'"
									+   " value='" + iVal + "'></td>"
                   }
                   strHtml    +=  "<td class='right col-sm-1'>"
                              +   "<a class='deleterow4 c" + i + "' href='javascript:void(0)'><i class='fa fa-times'></i></a>"
                              +   "</td>"
                              +   "</tr>";
                   $(tblid).append(strHtml);
               }
               i++;
           }
           screen_row++;
        });

     /*** Delete row on table ***/
     jQuery(document).on('click', '.deleterow1', function() {
         var trIndex 	= $('#dtgtable tr.tr').length;           
         //var trIndex 	= $(this).closest("tr").index();       
         if(trIndex > 1) {
            $(this).closest("tr").remove();
         }
         else alert("Sorry! Can't remove all row!");
     });
     jQuery(document).on('click', '.deleterow3', function() {
         var trIndex 	= $('#sublimationtable tr.tr').length; 
         //var trIndex 	= $(this).closest("tr").index();     
         if(trIndex > 1) {
            $(this).closest("tr").remove(); // do remove action
         }
         else alert("Sorry! Can't remove all row!");        
     });
     jQuery(document).on('click', '.deleterow2', function() {
        var aClass 			= this.className;                                            
        var table_id 		= '#screentable' + aClass.replace('deleterow2 c','');      
        var trIndexAllow 	= $(table_id + ' tr.tr').length;                       
        var trIndex 		= $(this).closest("tr").index();
        if(trIndexAllow > 1) 
		{
            $(this).closest("tr").remove();
            screen_row--;

            var table_rows 	= $(table_id + ' tr.tr').length;                         
            var q_values 	= $(table_id + ' tr.tr').find('input[class*=qvalue]');     
            for(ix = 0; ix < q_values.length; ix++)                                 
            {                                                                       
                var q_class 		= q_values[ix].className;                               
                var q_class_str 	= q_class.split(' ');                               
                var q_class_first 	= q_class_str[2];				
                var q_index 		= q_class_first.replace('qvalue','');
				
                if(q_index > trIndex)
				{
                    q_index--;                                                      
                    var ibrefore 	= parseInt(q_index) + 1;
					
                    $(table_id + ' tr.tr').find('input.qvalue' + ibrefore).addClass('qvalue' + q_index);    
                    $(table_id + ' tr.tr').find('input.qvalue' + q_index).removeClass('qvalue' + ibrefore); 
                }                                                                   
            }                                                                       
            var i_values 	= $(table_id + ' tr.tr').find('input[class*=ivalue]');     
            for(ix = 0; ix < i_values.length; ix++)                                 
            {                                                                       
                var i_class 		= i_values[ix].className;                               
                var i_class_str 	= i_class.split(' ');                               
                var i_class_first 	= i_class_str[2];                                 
                var i_temp 			= i_class_first.replace('ivalue','');                    
                var i_temps 		= i_temp.split('_');
				
                if(i_temps[0] > trIndex){                                           
                    var i_index 	= parseInt(i_temps[0]) - 1;                         
                    var ibefore 	= i_temps[0];                                       
                    var i_index_col = i_temps[1];
					
                    $(table_id + ' tr.tr').find('input.ivalue' + ibefore + '_' + i_index_col).addClass('ivalue' + i_index + '_' + i_index_col);     
                    $(table_id + ' tr.tr').find('input.ivalue' + i_index + '_' + i_index_col).removeClass('ivalue' + ibefore + '_' + i_index_col);  
                }                                                                   
            }                                                                       
        }
        else alert("Sorry! Can't remove first row!");
     });
     jQuery(document).on('click', '.deleterow4', function() {
         var aClass 		= this.className;                                               
         var table_id 		= '#embroiderytable' + aClass.replace('deleterow4 c','');     
         var trIndexAllow 	= $(table_id + ' tr.tr').length;                       
         var trIndex 		= $(this).closest("tr").index();
         if(trIndexAllow > 1) 
		 {
             $(this).closest("tr").remove();
             screen_row--;

             var table_rows = $(table_id + ' tr.tr').length;
             var q_values 	= $(table_id + ' tr.tr').find('input[class*=qvalue]');
			 
             for(ix = 0; ix < q_values.length; ix++)                                 
             {                                                                       
                 var q_class 		= q_values[ix].className;                               
                 var q_class_str 	= q_class.split(' ');                               
                 var q_class_first 	= q_class_str[2];                                 
                 var q_index 		= q_class_first.replace('qvalue','');
				 
                 if(q_index > trIndex)
				 {
                     q_index--;
                     var ibrefore 	= parseInt(q_index) + 1;
					 
                     $(table_id + ' tr.tr').find('input.qvalue' + ibrefore).addClass('qvalue' + q_index);    
                     $(table_id + ' tr.tr').find('input.qvalue' + q_index).removeClass('qvalue' + ibrefore); 
                 }                                                                   
             }                                                                       
             var i_values = $(table_id + ' tr.tr').find('input[class*=ivalue]');     
             for(ix = 0; ix < i_values.length; ix++)                                 
             {                                                                       
                 var i_class 		= i_values[ix].className;
                 var i_class_str 	= i_class.split(' ');
                 var i_class_first 	= i_class_str[2];
                 var i_temp 		= i_class_first.replace('ivalue','');                    
                 var i_temps 		= i_temp.split('_');
                 if(i_temps[0] > trIndex){
                     var i_index		= parseInt(i_temps[0]) - 1;                         
                     var ibefore 		= i_temps[0];                                       
                     var i_index_col 	= i_temps[1];
					 
                     $(table_id + ' tr.tr').find('input.ivalue' + ibefore + '_' + i_index_col).addClass('ivalue' + i_index + '_' + i_index_col);     
                     $(table_id + ' tr.tr').find('input.ivalue' + i_index + '_' + i_index_col).removeClass('ivalue' + ibefore + '_' + i_index_col);  
                 }                                                                   
             }                                                                       
         }
         else alert("Sorry! Can't remove all row!");
     });
});
function checkFinal(item){
	jQuery(item).val(jQuery(item).val().replace(/[^0-9\.]/g,'')); // only allow input numeric
    if(item.value.trim() == '' || item.value.trim() == '.' || parseInt(item.value.trim()) < 0)
	{
        item.value = 0;
    }
}
function addproductquantity(item){
    var arrId 		= (item.id).split('_');
    var tab 		= arrId[0];
    var tableIndex 	= arrId[2];
	
    switch (tab) {
        case 'screen':
            var i 			 = 7 - tableIndex;
            var tblid 		 = '#screentable' + i;
            var valSize 	 = $("#screensizeselect option:selected").val();
            var valColor 	 = $("#screencolorselect option:selected").val();
            var iRows 		 = $(tblid + ' tr.tr').length;
            var sClass 		 = $(tblid + ' tr.tr:last').find('input').attr('class') + "";
            var fClass 		 = sClass.split(" ");
            var strClassName = '.' + fClass[2];
            var iSizeName 	 = tableIndex;
			
            if($(tblid).length)
			{
                var qClass 	= strClassName;
                var qVal 	= $(tblid + ' tr.tr').find('input' + qClass).val();
                var strHtml = "<tr class='tr'>"
						   +   "<td class='col-sm-2'>"
						   +   "<div class='col-sm-6'>"
						   +   "<input class='form-control input-sm qvalue" 
						   + 	(parseInt(iRows) + 1) + "' type='number' min='1'"
						   +   " onblur='checkFinal(this)'"
						   +   " name='setting[pricediscount][screen][A" + iSizeName + "][quantity][]'"
						   +   " value='" + qVal + "'>"
						   +   "</div>"
						   +   "<label class='col-sm-6'>" + lang_price_discount.labeltext.product + "</label>"
						   +   "</td>";
                for(j = 1; j<= valColor; j++){
                    var tClass 	= strClassName.replace('qvalue', 'ivalue') + '_' + j;
                    var iVal 	= $(tblid + ' tr.tr').find('input' + tClass).val();
                    strHtml    +=  "<td class='col-sm-1'>"
                               +   "<input class='form-control input-sm ivalue" + (parseInt(iRows) + 1) + '_' + j
                               +   "' onblur='checkFinal(this)'"
                               +   " type='text'"
                               +   " name='setting[pricediscount][screen][A" + iSizeName + "][" + j + "][]'"
                               +   " value='" + iVal + "'></td>"
                }
                strHtml    +=  "<td class='right col-sm-1'>"
                           +   "<a class='deleterow2 c" + i + "' href='javascript:void(0)'><i class='fa fa-times'></i></a>"
                           +   "</td>"
                           +   "</tr>";
                $(tblid).append(strHtml);
            }
            break;
        case 'embroidery':
            var i 				= 7 - tableIndex;
            var tblid 			= '#embroiderytable' + i;
            var valSize 		= $("#embroiderysizeselect option:selected").val();
            var valColor 		= $("#embroiderycolorselect option:selected").val();
            var iRows 			= $(tblid + ' tr.tr').length;
            var sClass 			= $(tblid + ' tr.tr:last').find('input').attr('class') + "";
            var fClass 			= sClass.split(" ");
            var strClassName 	= '.' + fClass[2];
            var iSizeName 		= tableIndex;
			
            if($(tblid).length)
			{
                var qClass 	= strClassName;
                var qVal 	= $(tblid + ' tr.tr').find('input' + qClass).val();
                var strHtml ="<tr class='tr'>"
						   +   "<td class='col-sm-2'>"
						   +   "<div class='col-sm-6'>"
						   +   "<input class='form-control input-sm qvalue" 
						   +  (parseInt(iRows) + 1) + "' type='number' min='1'"
						   +   " onblur='checkFinal(this)'"
						   +   " name='setting[pricediscount][embroidery][A" + iSizeName + "][quantity][]'"
						   +   " value='" + qVal + "'>"
						   +   "</div>"
						   +   "<label class='col-sm-6'>" + lang_price_discount.labeltext.product + "</label>"
						   +   "</td>";
                for(j = 1; j<= valColor; j++){
                    var tClass 	= strClassName.replace('qvalue', 'ivalue') + '_' + j;
                    var iVal 	= $(tblid + ' tr.tr').find('input' + tClass).val();
                    strHtml    +=  "<td class='col-sm-1'>"
                               +   "<input class='form-control input-sm ivalue" + (parseInt(iRows) + 1) + '_' + j
                               +   "' onblur='checkFinal(this)'"
                               +   " type='text'"
                               +   " name='setting[pricediscount][embroidery][A" + iSizeName + "][" + j + "][]'"
                               +   " value='" + iVal + "'></td>"
                }
                strHtml    +=  "<td class='right col-sm-1'>"
                           +   "<a class='deleterow4 c" + i + "' href='javascript:void(0)'><i class='fa fa-times'></i></a>"
                           +   "</td>"
                           +   "</tr>";
                $(tblid).append(strHtml);
            }
            break;
        default:
            break;
    }
}
/*** change colors number of sreen tab ***/
function changeScreenColors()
{
    var valColor 		= $("#screencolorselect option:selected").val();
    if(0 < valColor && valColor < 11) 
	{
        var currCols 	= $('#screentable1').find('tr')[0].cells.length - 2;
        var i 			= 1;
        // If new value > current table columns => add more
        if(valColor > currCols)
		{
            var sodu 	= valColor - currCols;
			
            while(i < 8){
                var tblid 		= '#screentable' + i;
                //var iRows 	= $(tblid + ' tr').length;
                var iSizeName 	= 7 - i;
                if($(tblid).length)
				{
                    var curr 	= parseInt(currCols) + 1;
                    while(curr <= valColor)
					{
                        var t 	= 0;
                        $(tblid).find('tr').each(function()
						{
                            var tClass 			= '.ivalue' + t + '_' + currCols;                            
                            //if(($(tblid + ' tr.tr').find('input' + tClass)).length)
                            //{
                                var addColVal 	= $(tblid + ' tr.tr').find('input' + tClass).val();

                                $(this).find('th').eq(-1).before("<th class='col-sm-1'>" + curr + "</th>");
                                $(this).find('td').eq(-1).before("<td class='col-sm-1'>"
                                    +   "<input class='form-control input-sm ivalue" + t + '_'+ curr
                                    +   "' onblur='checkFinal(this)'"
                                    +   " type='text'"
                                    +   " name='setting[pricediscount][screen][A"
                                    +   iSizeName + "][" + curr + "][]' value='"
                                    +   addColVal + "'></td>");
                            //}
                            t++;							
                        });
                        curr++;
                    }
                }
                i++;
            }
            currCols = currCols + sodu;
        }
        // If new value < current table columns => remove
        if(valColor < currCols)
		{
            var sodu = currCols - valColor;
            while(i < 8)
			{
                var tblid = '#screentable' + i;
                if($(tblid).length){
                    var curr = currCols;
                    while(curr > valColor)
					{
                        var iCell = $(tblid).find('th').eq(curr).html();
                        $(tblid + ' tr').find('td:eq(' + curr + '),th:eq(' + curr + ')').remove();
                        curr--;
                    }
                }
                i++;
            }
            currCols = currCols - sodu;
        }
    }
}
/*** change type print (size: Yes or No) ***/
function changeScreenSize(){
    var valColor 	= $("#screencolorselect option:selected").val();
    var valSize 	= $("#screensizeselect option:selected").val();
	counttable 		= 1;
	
    $('#divScreenA6').addClass('hidden');
    
    if(valSize == 'Yes')
	{
        counttable  = 7;
        $('#divScreenA6').removeClass('hidden');
    }
    
	jQuery('#screenregiontbl').empty();
    
	// Add tables and columns
    var iSize 	= counttable - 2;
    var iCount 	= 2;
    while(iCount <= counttable)
	{
        var strHtml = "";
        if(counttable > 1)
		{
            strHtml +=  "<div class='lblsize col-sm-12 form-group'>" + lang_price_discount.labeltext.size + ": A" + iSize
                    +   " <a href='javascript:void(0)' onclick='addproductquantity(this)' "
                    +   "class='btn btn-success btn-xs' id='screen_add_" + iSize + "'>"
                    +   lang_price_discount.buttontext.add_product_quantity
                    +   "</a>"
                    +   "</div>";
        }
        strHtml 	+=  "<table id='screentable" + iCount + "' class='table table-bordered'>"
					+   "<tr class='table-header'>"
					+   "<th class='col-sm-2'>" + lang_price_discount.labeltext.product_quantity + "</th>";
        for(col = 1; col <= valColor; col++)
		{
            strHtml += "<th class='col-sm-1'>" + col + "</th>";
        }		
        strHtml 	+= "<th class='right col-sm-1'>Remove</th></tr>";
		
        screen_row 	= $('#screentable1 tr.tr').length;		
		
        for(j = 1; j <= screen_row; j++)
		{
            var qClass 		= '.qvalue' + j;
            var qValInput 	= $('#screentable1 tr.tr').find('input' + qClass).val();
            strHtml 		+=  "<tr class='tr'><td class='col-sm-2'><div class='col-sm-6'>"
							+   "<input class='form-control input-sm qvalue" + j + "' type='number' min='1'"
							+   " onblur='checkFinal(this)'"
							+   " name='setting[pricediscount][screen][A" + iSize + "][quantity][]'"
							+   " value='" + qValInput + "'>"
							+   "</div><label class='col-sm-6'>" + lang_price_discount.labeltext.product + "</label></td>";
            for(col = 1; col <= valColor; col++)
			{
                var vClass 		= '.ivalue' + j + '_' + col;				
                //var tClass 	= strClassName.replace('qvalue', 'ivalue') + j;
                var iValInput 	= $('#screentable1 tr.tr').find('input' + vClass).val();
				
                strHtml 	+=  "<td class='col-sm-1'><input class='form-control input-sm ivalue" + j + '_' + col
							+   "' type='text'"
							+   " onblur='checkFinal(this)'"
							+   " name='setting[pricediscount][screen][A" + iSize + "][" + col + "][]'"
							+   " value='" + iValInput + "'></td>";
            }
            strHtml +=  "<td class='right col-sm-1'><a class='deleterow2 c" + iCount + "' href='javascript:void(0)'>"
                    +   "<i class='fa fa-times'></i></a></td></tr>";
        }
        jQuery('#screenregiontbl').append(strHtml);
        iSize--;
        iCount++;
    }
}

/*** change colors number of sreen tab ***/
function changeembroiderycolor(){
    var valColor = $("#embroiderycolorselect option:selected").val();
    if(valColor > 0 && valColor < 11) 
	{
        var currCols = $('#embroiderytable1').find('tr')[0].cells.length - 2;
        var i = 1;
        // If new value > current table columns => add more
        if(valColor > currCols)
		{
            var sodu = valColor - currCols;
            while(i < 8)
			{
                var tblid = '#embroiderytable' + i;
                //var iRows = $(tblid + ' tr').length;
                var iSizeName = 7 - i;
                if($(tblid).length)
				{
                    var curr = parseInt(currCols) + 1;
                    while(curr <= valColor)
					{
                        var t = 0;
                        $(tblid).find('tr').each(function(){
                            var tClass 		= '.ivalue' + t + '_' + currCols;
                            var addColVal 	= $(tblid + ' tr.tr').find('input' + tClass).val();
                            $(this).find('th').eq(-1).before("<th class='col-sm-1'>" + curr + "</th>");
                            $(this).find('td').eq(-1).before("<td class='col-sm-1'>"
                                +   "<input class='form-control input-sm ivalue" + t + '_'+ curr
                                +   "' onblur='checkFinal(this)'"
                                +   " type='text'"
                                +   " name='setting[pricediscount][embroidery][A"
                                +   iSizeName + "][" + curr + "][]' value='"
                                +   addColVal + "'></td>");
                            t++;
                        });
                        curr++;
					}
                }
                i++;
            }
            currCols = currCols + sodu;
        }
        // If new value < current table columns => remove
        if(valColor < currCols)
		{
            var sodu = currCols - valColor;
            while(i < 8)
			{
                var tblid = '#embroiderytable' + i;
                if($(tblid).length)
				{
                    var curr = currCols;
                    while(curr > valColor)
					{
                        var iCell = $(tblid).find('th').eq(curr).html();
                        $(tblid + ' tr').find('td:eq(' + curr + '),th:eq(' + curr + ')').remove();
                        curr--;
                    }
                }
                i++;
            }
            currCols = currCols - sodu;
        }
    }
}

/*** change type print (size: Yes or No) ***/
function changeembroideryprice()
{
    var valColor 	= $("#embroiderycolorselect option:selected").val();
    var valSize 	= $("#embroiderysizeselect option:selected").val();
	counttable 		= 1;
	
    $('#divEmbroideryA6').addClass('hidden');
    
    if(valSize == 'Yes')
	{
        counttable  = 7;
        $('#divEmbroideryA6').removeClass('hidden');
    }
    jQuery('#embroideryregiontbl').empty();
    
    var iSize 	= counttable - 2;
    var iCount 	= 2;
	
    while(iCount <= counttable)
	{
        var strHtml = "";
        if(counttable > 1)
		{
            strHtml +=  "<div class='lblsize col-sm-12 form-group'>" + lang_price_discount.labeltext.size + ": A" + iSize
                    +   " <a href='javascript:void(0)' onclick='addproductquantity(this)' "
                    +   "class='btn btn-success btn-xs' id='embroidery_add_" + iSize + "'>"
                    +   lang_price_discount.buttontext.add_product_quantity
                    +   "</a>"
                    +   "</div>";
        }
        strHtml +=  "<table id='embroiderytable" + iCount + "' class='table table-bordered'>"
                +   "<tr class='table-header'>"
                +   "<th class='col-sm-2'>" + lang_price_discount.labeltext.product_quantity + "</th>";
        for(col = 1; col <= valColor; col++)
		{
            strHtml += "<th class='col-sm-1'>" + col + "</th>";
        }
        strHtml 	+= "<th class='right col-sm-1'>Remove</th></tr>";
		
        screen_row 	= $('#embroiderytable1 tr.tr').length;
        for(j = 1; j <= screen_row; j++)
		{
            var qClass = '.qvalue' + j;
            //var qClass = '';
            var qValInput = $('#embroiderytable1 tr.tr').find('input' + qClass).val();
            strHtml +=  "<tr class='tr'><td class='col-sm-2'><div class='col-sm-6'>"
                    +   "<input class='form-control input-sm qvalue" + j + "' type='number' min='1'"
                    +   " onblur='checkFinal(this)'"
                    +   " name='setting[pricediscount][embroidery][A" + iSize + "][quantity][]'"
                    +   " value='" + qValInput + "'>"
                    +   "</div><label class='col-sm-6'>" + lang_price_discount.labeltext.product + "</label></td>";
            for(row = 1; row <= valColor; row++)
			{
                var vClass = '.ivalue' + j + '_' + row;
                //var tClass = strClassName.replace('qvalue', 'ivalue') + j;
                var iValInput = $('#embroiderytable1 tr.tr').find('input' + vClass).val();
                strHtml +=  "<td class='col-sm-1'><input class='form-control input-sm ivalue" + j + '_' + row
                        +   "' type='text'"
                        +   " onblur='checkFinal(this)'"
                        +   " name='setting[pricediscount][embroidery][A" + iSize + "][" + row + "][]'"
                        +   " value='" + iValInput + "'></td>";
            }
            strHtml +=  "<td class='right col-sm-1'><a class='deleterow4 c" + iCount + "' href='javascript:void(0)'>"
                    +   "<i class='fa fa-times'></i></a></td></tr>";
        }
        jQuery('#embroideryregiontbl').append(strHtml);
        iSize--;
        iCount++;
    }
}