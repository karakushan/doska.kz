//(function( $ ) {
	'use strict';

	function terms_order_wp_change_taxonomy(element){
            jQuery('#terms_order_wp_form #cat').val(jQuery("#terms_order_wp_form #cat option:first").val());
            jQuery('#terms_order_wp_form').submit();
    }
	var array_to_object_conversion = function(array){
		var element_object = new Object();
		if(typeof array == "object"){
			for(var i in array){
				var element = array_to_object_conversion(array[i]);
				element_object[i] = element;
			}
		}else {
			element_object = array;
		}
		return element_object;
	}

//})( jQuery );
