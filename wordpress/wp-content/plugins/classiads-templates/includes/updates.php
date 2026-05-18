<?php

global $Classiads_Templates;
if(is_object($Classiads_Templates) && $Classiads_Templates->is_registered()){
	
	$protocol		= is_ssl() ? 'https://' : 'http://';
	include_once DT_PATH. 'pacz-update-checker/pacz-update-checker.php';
	$url = $protocol .'assets.designinvento.net/plugins/classiadspro/6.2/update/';

	$classiads_templates_UpdateChecker = Puc_v4_Factory::buildUpdateChecker(
		$url .'classiads-templates.json',
		WP_PLUGIN_DIR .'/classiads-templates/classiads-templates.php',
		'classiads-templates'
	);
	if (file_exists(WP_PLUGIN_DIR .'/js_composer/js_composer.php')) {
		$vcUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
			$url .'vc-update.json',
			WP_PLUGIN_DIR .'/js_composer/js_composer.php',
			'js_composer'
		);  
		
	}
	/* if (file_exists(WP_PLUGIN_DIR .'/directorypress/directorypress.php')) {
		$directorypressUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
			$url .'directorypress.json',
			WP_PLUGIN_DIR .'/directorypress/directorypress.php',
			'directorypress'
		); 
	} */
	if (file_exists(WP_PLUGIN_DIR .'/dhvc-form/dhvc-form.php')) {
		$dhvcUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
			$url .'dhvc-update.json',
			WP_PLUGIN_DIR .'/dhvc-form/dhvc-form.php',
			'dhvc-form'
		); 
	}
	if (file_exists(WP_PLUGIN_DIR .'/revslider/revslider.php')) {
		$revolutionUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
			$url .'revolution-update.json',
			WP_PLUGIN_DIR .'/revslider/revslider.php',
			'revslider'
		);
	}
	if (file_exists(WP_PLUGIN_DIR .'/dhe-form/dhe-form.php')) {
		$dhe_form_UpdateChecker = Puc_v4_Factory::buildUpdateChecker(
			$url .'dhe-form.json',
			WP_PLUGIN_DIR .'/dhe-form/dhe-form.php',
			'dhe-form'
		);
	}
	if (file_exists(WP_PLUGIN_DIR .'/elkit/elkit.php')) {
		$elkit = Puc_v4_Factory::buildUpdateChecker(
			$url .'elkit.json',
			WP_PLUGIN_DIR .'/elkit/elkit.php',
			'elkit'
		);
	}
	if (file_exists(WP_PLUGIN_DIR .'/header-footer-builder/header-footer-builder.php')) {
		$header_footer_builder = Puc_v4_Factory::buildUpdateChecker(
			$url .'header-footer-builder.json',
			WP_PLUGIN_DIR .'/header-footer-builder/header-footer-builder.php',
			'header-footer-builder'
		);
	}
	if (file_exists(WP_PLUGIN_DIR .'/form-builder-wp/form-builder-wp.php')) {
		$form_builder_wp = Puc_v4_Factory::buildUpdateChecker(
			$url .'form-builder-wp.json',
			WP_PLUGIN_DIR .'/form-builder-wp/form-builder-wp.php',
			'form-builder-wp'
		);
	}
	if (file_exists(WP_PLUGIN_DIR .'/classiadspro-core/classiadspro-core.php')) {
		$classiadspro_core_UpdateChecker = Puc_v4_Factory::buildUpdateChecker(
			$url .'classiadspro-core.json',
			WP_PLUGIN_DIR .'/classiadspro-core/classiadspro-core.php',
			'classiadspro-core'
		); 
		
	}
	if (file_exists(WP_PLUGIN_DIR .'/designinvento-elementor-widgets/designinvento-elementor-widgets.php')) {
		$designinvento_elementor_widgets = Puc_v4_Factory::buildUpdateChecker(
			$url .'designinvento-elementor-widgets.json',
			WP_PLUGIN_DIR .'/designinvento-elementor-widgets/designinvento-elementor-widgets.php',
			'designinvento-elementor-widgets'
		); 
		
	}
	if (file_exists(WP_PLUGIN_DIR .'/directorypress-extended-locations/directorypress-extended-locations.php')) {
		$dpel = Puc_v4_Factory::buildUpdateChecker(
			$url .'dp-extended-locations.json',
			WP_PLUGIN_DIR .'/directorypress-extended-locations/directorypress-extended-locations.php',
			'directorypress-extended-locations'
		);
	}
	if (file_exists(WP_PLUGIN_DIR .'/directorypress-maps/directorypress-maps.php')) {
		$dpm = Puc_v4_Factory::buildUpdateChecker(
			$url .'dp-maps.json',
			WP_PLUGIN_DIR .'/directorypress-maps/directorypress-maps.php',
			'directorypress-maps'
		);
	}
	if (file_exists(WP_PLUGIN_DIR .'/directorypress-multidirectory/directorypress-multidirectory.php')) {
		$dpmd = Puc_v4_Factory::buildUpdateChecker(
			$url .'dp-multi-directory.json',
			WP_PLUGIN_DIR .'/directorypress-multidirectory/directorypress-multidirectory.php',
			'directorypress-multidirectory'
		);
	}
	if (file_exists(WP_PLUGIN_DIR .'/directorypress-payment-manager/directorypress-payment-manager.php')) {
		$dppm = Puc_v4_Factory::buildUpdateChecker(
			$url .'dp-payment-manager.json',
			WP_PLUGIN_DIR .'/directorypress-payment-manager/directorypress-payment-manager.php',
			'directorypress-payment-manager'
		);
	}
	if (file_exists(WP_PLUGIN_DIR .'/directorypress-advanced-fields/directorypress-advanced-fields.php')) {
		$dpaf = Puc_v4_Factory::buildUpdateChecker(
			$url .'directorypress-advanced-fields.json',
			WP_PLUGIN_DIR .'/directorypress-advanced-fields/directorypress-advanced-fields.php',
			'directorypress-advanced-fields'
		);
	}
	if (file_exists(WP_PLUGIN_DIR .'/directorypress-claim-listing/directorypress-claim-listing.php')) {
		$dpcl = Puc_v4_Factory::buildUpdateChecker(
			$url .'directorypress-claim-listing.json',
			WP_PLUGIN_DIR .'/directorypress-claim-listing/directorypress-claim-listing.php',
			'directorypress-claim-listing'
		);
	}
	if (file_exists(WP_PLUGIN_DIR .'/directorypress-frontend-messages/directorypress-frontend-messages.php')) {
		$dpfm = Puc_v4_Factory::buildUpdateChecker(
			$url .'dp-frontend-messages.json',
			WP_PLUGIN_DIR .'/directorypress-frontend-messages/directorypress-frontend-messages.php',
			'directorypress-frontend-messages'
		);
	}
}