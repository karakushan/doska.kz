<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
$Pacz_Admin = new Pacz_Admin();
$template_Admin = new Classiads_Templates();
$validation_class = ($template_Admin->is_registered())? 'enabled':'disabled';
$key = get_option($template_Admin->option_name);
$ks_html = array(
    'div' => array(
        'class' => array()
    )
);

?>
<div class="wrap about-wrap pacz-admin-wrap theme-setup-page">
	<?php Pacz_Admin::pacz_dashboard_header(); ?>
	<div class="pacz-plugins pacz-theme-browser-wrap">
		<div class="theme-browser rendered">
			<div id="license-setup" class="pacz-box">
				<div class="pacz-box-head">
					<?php esc_html_e('Registration And Setup','classiadspro'); ?>
				</div>
				<div class="pacz-box-content clearfix">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<form class="register_form">
							<img src="<?php echo PACZ_THEME_CONTROL_PANEL_URI . '/assets/images/enrollment.svg'; ?>" alt="classiadspro" />
							<div class="pacz-notice"><?php echo wp_kses($template_Admin->get_status(), $ks_html); ?></div>
							<input class="input-field" name="register_key" placeholder="Purchase Code" required="required" type="password" value="<?php echo esc_attr($key); ?>">
							<?php wp_nonce_field('save_registration', 'save_registration'); ?>
							<div class="registration-button">
								<a href="#" class="btn btn-primary product_register_action"><?php esc_html_e('Register', 'classiadspro'); ?></a>
							</div>
						</form>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<div class="pacz-setup-content">
							<img src="<?php echo PACZ_THEME_CONTROL_PANEL_URI . '/assets/images/settings.svg'; ?>" alt="classiadspro" />
							<?php if(get_option( 'designinvento_templates_classiadspro_completed') && !empty(get_option( 'designinvento_templates_classiadspro_completed'))): ?>
								<p>
									<?php echo esc_html__('Classiadspro setup is already complete, Please reset before re-installing.', 'classiadspro'); ?>
								</p>
							<?php else: ?>
								<p>
									<?php echo esc_html__('ClassiadsPro theme setup wizard provide an opportunity clone any demo site from range of provided layouts to quick start your business website. This setup wizard required license validation. Make sure you have already registered your purchase code.', 'classiadspro'); ?>
								</p>
								<div class="setup-button">
									<a class="btn btn-primary <?php echo esc_attr($validation_class); ?>" href="<?php echo DESIGNINVENTO_TEMPLATES_PAGE; ?>"><?php echo esc_html__('Start Theme Setup', 'classiadspro'); ?></a>
								</div>
							<?php endif; ?>
						</div>
					</div>
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
					jQuery('.setup-button a').removeClass('disabled').addClass('enabled');
			   } else {			
					jQuery('.register_form .pacz-notice').html('<div class="alert alert-danger alert-dismissible">'+response.message+'<a href="#" class="close" data-bs-dismiss="alert" aria-label="close">&times;</a></div>');  
					jQuery('.setup-button a').removeClass('enabled').addClass('disabled');
				}
            }
        });
    });
})( jQuery );
</script>