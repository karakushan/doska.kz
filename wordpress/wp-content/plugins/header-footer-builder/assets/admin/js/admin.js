jQuery(document).ready(function ($) {
	var ehf_hide_shortcode_field = function() {
		var selected = jQuery('#ehf_template_type').val() || 'none';
		jQuery( '.hfb-options-table' ).removeClass().addClass( 'hfb-options-table widefat hfb-selected-template-type-' + selected );
	}

	jQuery(document).on( 'change', '#ehf_template_type', function( e ) {
		ehf_hide_shortcode_field();
	});

	ehf_hide_shortcode_field();
});
