<?php

function designinvento_template_plugin($selected_import_index)
{
	/**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
	 $protocol		= is_ssl() ? 'https://' : 'http://';
	$dynamic_url = $protocol .'assets.designinvento.net/plugins/';
    $plugins = array(



        // This is an example of how to include a plugin from the WordPress Plugin Repository
		
        array(
            'name' => 'ClassiadsPro Core',
            'slug' => 'classiadspro-core',
            'source' => $dynamic_url.'classiadspro/6.2/classiadspro-core.zip',
            'required' => true,
            'version' => '1.3.1',
			'is_automatic' => true, // automatically activate plugins after installation
            'force_activation' => false,
            'force_deactivation' => false
        ),
		array(
            'name' => 'Designinvento Elementor Widgets',
            'slug' => 'designinvento-elementor-widgets',
            'source' => $dynamic_url.'designinvento-elementor-widgets/1.1.2/designinvento-elementor-widgets.zip',
            'required' => true,
            'version' => '1.1.2',
			'is_automatic' => true, // automatically activate plugins after installation
            'force_activation' => true,
            'force_deactivation' => false
        ),
		array(
            'name' => 'DirectoryPress',
            'slug' => 'directorypress',
            'source' => '',
            'required' => false,
            'version' => '',
			'is_automatic' => true, // automatically activate plugins after installation
            'force_activation' => false,
            'force_deactivation' => false
        ),
		array(
            'name' => 'DirectoryPress Extended Location',
            'slug' => 'directorypress-extended-locations',
            'source' => $dynamic_url.'directorypress/directorypress-addons/directorypress-extended-locations/1.7.8/directorypress-extended-locations.zip',
            'required' => false,
            'version' => '1.7.8',
			'is_automatic' => true, // automatically activate plugins after installation
            'force_activation' => false,
            'force_deactivation' => false
        ),
		array(
            'name' => 'DirectoryPress Frontend',
            'slug' => 'directorypress-frontend',
            'source' => '',
            'required' => false,
            'version' => '',
			'is_automatic' => true, // automatically activate plugins after installation
            'force_activation' => false,
            'force_deactivation' => false
        ),
		array(
            'name' => 'DirectoryPress Maps',
            'slug' => 'directorypress-maps',
            'source' => $dynamic_url.'directorypress/directorypress-addons/directorypress-maps/1.4.7/directorypress-maps.zip',
            'required' => false,
            'version' => '1.4.7',
			'is_automatic' => true, // automatically activate plugins after installation
            'force_activation' => false,
            'force_deactivation' => false
        ),
		array(
            'name' => 'DirectoryPress MultiDirectory',
            'slug' => 'directorypress-multidirectory',
            'source' => $dynamic_url.'directorypress/directorypress-addons/directorypress-multidirectory/2.8.9/directorypress-multidirectory.zip',
            'required' => false,
            'version' => '2.8.9',
			'is_automatic' => true, // automatically activate plugins after installation
            'force_activation' => false,
            'force_deactivation' => false
        ),
		array(
            'name' => 'DirectoryPress Payment Manager',
            'slug' => 'directorypress-payment-manager',
            'source' => $dynamic_url.'directorypress/directorypress-addons/directorypress-payment-manager/3.1.5/directorypress-payment-manager.zip',
            'required' => false,
            'version' => '3.1.5',
			'is_automatic' => true, // automatically activate plugins after installation
            'force_activation' => false,
            'force_deactivation' => false
        ),
		array(
            'name' => 'DirectoryPress Advanced Fields',
            'slug' => 'directorypress-advanced-fields',
            'source' => $dynamic_url.'directorypress/directorypress-addons/directorypress-advanced-fields/1.1.7/directorypress-advanced-fields.zip',
            'required' => false,
            'version' => '1.1.7',
			'is_automatic' => true, // automatically activate plugins after installation
            'force_activation' => false,
            'force_deactivation' => false
        ),
		array(
            'name' => 'DirectoryPress Claim Listing',
            'slug' => 'directorypress-claim-listing',
            'source' => $dynamic_url.'directorypress/directorypress-addons/directorypress-claim-listing/1.0.9/directorypress-claim-listing.zip',
            'required' => false,
            'version' => '1.0.9',
			'is_automatic' => true, // automatically activate plugins after installation
            'force_activation' => false,
            'force_deactivation' => false
        ),
		array(
            'name' => 'Frontend Messages DirectoryPress',
            'slug' => 'directorypress-frontend-messages',
            'source' => $dynamic_url.'directorypress/directorypress-addons/directorypress-frontend-messages/5.4.7/directorypress-frontend-messages.zip',
            'required' => false,
            'version' => '5.4.7',
			'is_automatic' => true, // automatically activate plugins after installation
            'force_activation' => false,
            'force_deactivation' => false
        ),
		array(
            'name' => 'Slider Revolution',
            'slug' => 'revslider',
            'source' => $dynamic_url.'revolution/6.7.35/revslider.zip',
            'required' => false,
            'version' => '6.7.35',
			'is_automatic' => true, // automatically activate plugins after installation
            'force_activation' => false,
            'force_deactivation' => false
        ),
		array(
            'name' => 'Terms Order WP',
            'slug' => 'terms-order-wp',
            'source' => '',
            'required' => false,
            'version' => '',
			'is_automatic' => true, // automatically activate plugins after installation
            'force_activation' => false,
            'force_deactivation' => false
        ),
		array(
            'name' => 'WooCommerce',
            'slug' => 'woocommerce',
            'source' => '',
            'required' => false,
            'version' => '',
            'force_activation' => false,
            'force_deactivation' => false
        ),
		array(
            'name' => 'Elementor',
            'slug' => 'elementor',
            'required' => false,
        ),
		array(
            'name' => 'Form Build WP',
            'slug' => 'form-builder-wp',
            'source' => $dynamic_url.'form-builder-wp/1.1.8/form-builder-wp.zip',
            'required' => false,
            'version' => '1.1.8',
			'is_automatic' => false, // automatically activate plugins after installation
            'force_activation' => false,
            'force_deactivation' => false
        ),
		array(
            'name' => 'ElKit ( Elementor Addons Kit )',
            'slug' => 'elkit',
            'source' => $dynamic_url.'elkit/1.0.7/elkit.zip',
            'required' => false,
            'version' => '1.0.7',
			'is_automatic' => false, // automatically activate plugins after installation
            'force_activation' => false,
            'force_deactivation' => false
        ),
		array(
            'name' => 'Header Footer Builder',
            'slug' => 'header-footer-builder',
            'source' => $dynamic_url.'header-footer-builder/1.0.8/header-footer-builder.zip',
            'required' => false,
            'version' => '1.0.8',
			'is_automatic' => false, // automatically activate plugins after installation
            'force_activation' => false,
            'force_deactivation' => false
        ),
		array(
            'name' => 'Dicode Icons Pack',
            'slug' => 'dicode-icons-pack',
            'source' => '',
            'required' => false,
            'version' => '',
			'is_automatic' => false, // automatically activate plugins after installation
            'force_activation' => false,
            'force_deactivation' => false
        )
	);

/**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'id'           => 'designinvento-templates',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'plugins.php',            // Parent menu slug.
		'capability'   => 'manage_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',  
    );

	tgmpa( $plugins, $config );
}