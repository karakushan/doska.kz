<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
$Pacz_Admin = new Classiads_Templates();
$key = get_option($Pacz_Admin->option_name);
$ks_html = array(
    'div' => array(
        'class' => array()
    )
);
?>
<div class="wrap about-wrap pacz-admin-wrap product-register-page">
	<?php Pacz_Admin::pacz_dashboard_header(); ?>
	<div class="pacz-plugins pacz-theme-browser-wrap">
		<div class="theme-browser rendered">
			<div class="pacz-box">
				<div class="pacz-box-head">
					<?php esc_html_e('Extensions','classiadspro'); ?>
				</div>
				<div class="pacz-box-content">
					<?php $Pacz_Admin->designinvento_templates_plugins(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
(function( $ ) {
	'use strict';
    var loader = '<div class="pacz-loader"><div class="lds-ripple"><div></div><div></div></div></div>';
    jQuery(document).on('click', '.product_register_action', function (e) {
        e.preventDefault();      
		var url = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
		var key = $('input[name=register_key]').val();
        var data = new FormData();
		data.append("action", "save_registration");
		data.append("key", key);
		//console.log(...data);
        jQuery.ajax({
           url: url,
			type: 'POST',
			data: data,
			cache: false,
			dataType: 'json',
			processData: false,
			contentType: false,
            success: function (response) {
               jQuery('.product-register-page').find('.pacz-loader').remove();
                if (response.type == 'success') {
					jQuery('.register_form .pacz-notice').html('<div class="alert alert-success alert-dismissible">'+response.message+'<a href="#" class="close" data-bs-dismiss="alert" aria-label="close">&times;</a></div>');									
                } else {			
					jQuery('.register_form .pacz-notice').html('<div class="alert alert-danger alert-dismissible">'+response.message+'<a href="#" class="close" data-bs-dismiss="alert" aria-label="close">&times;</a></div>');  
                }
            }
        });
    });
})( jQuery );
</script>
