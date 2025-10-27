<?php

    /**
     * ReduxFramework Barebones Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }
	

    // This is your option name where all the Redux data is stored.
    $opt_name = "pacz_settings";

    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

     $theme = wp_get_theme(); // For use with some settings. Not necessary.
	$menu_parent = 'pacz-admin-classiads-settings';
    $args = array(
        // TYPICAL -> Change these values as you need/desire
        'opt_name'             => $opt_name,
        // This is where your data is stored in the database and also becomes your global variable name.
        'display_name'         => $theme->get( 'Name' ),
        // Name that appears at the top of your panel
        'display_version'      => $theme->get( 'Version' ),
        // Version that appears at the top of your panel
        'menu_type'            => 'submenu',
        //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
        'allow_sub_menu'       => true,
        // Show the sections below the admin menu item or not
        'menu_title'           => esc_html__( 'Theme Settings', 'classiadspro' ),
        'page_title'           => esc_html__( 'Theme Settings', 'classiadspro' ),
        // You will need to generate a Google API key to use this feature.
        // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_api_key'       => '',
        // Set it you want google fonts to update weekly. A google_api_key value is required.
        'google_update_weekly' => false,
        // Must be defined to add google fonts to the typography module
        'async_typography'     => true,
        // Use a asynchronous font on the front end or font string
        //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
        'admin_bar'            => true,
        // Show the panel pages on the admin bar
        'admin_bar_icon'       => 'dashicons-portfolio',
        // Choose an icon for the admin bar menu
        'admin_bar_priority'   => 50,
        // Choose an priority for the admin bar menu
        'global_variable'      => '',
        // Set a different name for your global variable other than the opt_name
        'dev_mode'             => false,
        // Show the time the page took to load, etc
        'update_notice'        => false,
        // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
        'customizer'           => false,
        // Enable basic customizer support
        //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
        //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

        // OPTIONAL -> Give you extra features
        'page_priority'        => null,
        // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
        'page_parent'          => $menu_parent,
        // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions'     => 'manage_options',
        // Permissions needed to access the options panel.
        'menu_icon'            => '',
        // Specify a custom URL to an icon
        'last_tab'             => '',
        // Force your panel to always open to a specific tab (by id)
        'page_icon'            => 'icon-themes',
        // Icon displayed in the admin panel next to your menu_title
        'page_slug'            => 'pacz-admin-theme_settings',
        // Page slug used to denote the panel
        'save_defaults'        => true,
        // On load save the defaults to DB before user clicks save or not
        'default_show'         => false,
        // If true, shows the default value next to each field that is not the default value.
        'default_mark'         => '',
        // What to print by the field's title if the value shown is default. Suggested: *
        'show_import_export'   => true,
        // Shows the Import/Export panel when not used as a field.

        // CAREFUL -> These options are for advanced use only
        'transient_time'       => 60 * MINUTE_IN_SECONDS,
        'output'               => true,
        // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
        'output_tag'           => true,
        // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
        // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

        // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
        'database'             => '',
        // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!

        'use_cdn'              => true,
        // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

        //'compiler'             => true,

        // HINTS
        'hints'                => array(
            'icon'          => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color'    => 'blue',
            'icon_size'     => 'large',
            'tip_style'     => array(
                'color'   => 'blue',
                'shadow'  => true,
                'rounded' => false,
                'style'   => 'tipped',
            ),
            'tip_position'  => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect'    => array(
                'show' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click',
                ),
                'hide' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click',
                ),
            ),
        )
    );

    // Panel Intro text -> before the form
   /* if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
        if ( ! empty( $args['global_variable'] ) ) {
            $v = $args['global_variable'];
        } else {
            $v = str_replace( '-', '_', $args['opt_name'] );
        }
        $args['intro_text'] = sprintf( __( '<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'redux-framework-demo' ), $v );
    } else {
        $args['intro_text'] = __( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'redux-framework-demo' );
    }*/

    // Add content after the form.
   // $args['footer_text'] = __( '<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'redux-framework-demo' );

    Redux::setArgs( $opt_name, $args );
	

    /*
     * ---> END ARGUMENTS
     */

    /*
     * ---> START HELP TABS
     */

    $tabs = array(
        array(
            'id'      => 'redux-help-tab-1',
            'title'   => __( 'Theme Information 1', 'classiadspro' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'classiadspro' )
        ),
        array(
            'id'      => 'redux-help-tab-2',
            'title'   => __( 'Theme Information 2', 'classiadspro' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'classiadspro' )
        )
    );
	Redux::set_help_tab( $opt_name, $tabs );

	// Set the help sidebar.
	//$content = '<p>' . esc_html__( 'This is the sidebar content, HTML is allowed.', 'classiadspro' ) . '</p>';
	//Redux::set_help_sidebar( $opt_name, $content );
	

    /*
     * <--- END HELP TABS
     */


    /*
     *
     * ---> START SECTIONS
     *
     */

    /*

        As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for


     */

	$pacz_settings = get_option('pacz_settings');
	$heading_font_family = (isset($pacz_settings['heading-font']['font-family']) && !empty($pacz_settings['heading-font']['font-family'])) ? $pacz_settings['heading-font']['font-family'] : '';
	  Redux::setSection( $opt_name, array(
		'title' => esc_html__('General Settings', 'classiadspro'),
		'icon' => 'el-icon-globe-alt',
		'fields' => array(
			array(
				'id' => 'body-layout',
				'type' => 'button_set',
				'title' => esc_html__('Site Layout', 'classiadspro'),
				'desc' => esc_html__('Boxed layout best works on standart header style.', 'classiadspro'),
				'options' => array('full' => 'Full Width', 'boxed' => 'Boxed'), //Must provide key => value pairs for radio options
				'default' => 'full',
			),
			array(
				'id' => 'grid-width',
				'type' => 'slider',
				'title' => esc_html__('Main grid Width', 'classiadspro'),
				"default" => "1170",
				"min" => "960",
				"step" => "1",
				"max" => "1380",
			),
			array(
				'id' => 'content-width',
				'type' => 'slider',
				'title' => esc_html__('Content Width', 'classiadspro'),
				'subtitle' => esc_html__('Using this option you can define the width of the content.', 'classiadspro'),
				'desc' => esc_html__('please note that this option is in percent, lets say if you set it 60%, sidebar will occupy 40% of the main content space.', 'classiadspro'),
				"default" => "67",
				"min" => "50",
				"step" => "1",
				"max" => "80",
			),
			array(
				'id' => 'pages-padding',
				'type' => 'slider',
				'title' => esc_html__('Pages Padding TOP/BOTTOM', 'classiadspro'),
				"default" => array(
					1 => 70,
					2 => 70,
				),
				"min" => 0,
				"step" => 1,
				"max" => "300",
				'display_value' => 'select',
				'handles' => 2, 
			),
			array(
				'id' => 'archive-pages-padding',
				'type' => 'slider',
				'title' => esc_html__('Archive Pages Padding TOP/BOTTOM', 'classiadspro'),
				"default" => array(
					1 => 70,
					2 => 70,
				),
				"min" => 0,
				"step" => 1,
				"max" => "300",
				'display_value' => 'select',
				'handles' => 2, 
			),
			array(
				'id' => 'single-pages-padding',
				'type' => 'slider',
				'title' => esc_html__('Singular Pages Padding TOP/BOTTOM', 'classiadspro'),
				"default" => array(
					1 => 70,
					2 => 70,
				),
				"min" => 0,
				"step" => 1,
				"max" => "300",
				'display_value' => 'select',
				'handles' => 2, 
			),
			array(
				'id' => 'body-bg',
				'type' => 'background',
				'title' => esc_html__('Body Background', 'classiadspro'),
				'subtitle' => esc_html__('Affects body background Properties, use this option when boxed layout is chosen.', 'classiadspro'),
				'preset' => false,
				'default' => array(
					'background-image' => '',
					'background-color' => '#f9fafc',
					'background-position' => '',
					'background-repeat' => 'repeat',
					'background-attachment' => 'scroll',
					'background-size' => '',
				)
			),
			array(
				'id' => 'remove-js-css-ver',
				'type' => 'switch',
				'title' => esc_html__('Remove query string from Static Files', 'classiadspro'),
				'subtitle' => esc_html__('Removes "ver" query string from JS and CSS files.', 'classiadspro'),
				'desc' => __('For More info Please <a href="https://developers.google.com/speed/docs/best-practices/caching#LeverageProxyCaching">Read Here</a>.', 'classiadspro'),
				"default" => true,
			),
		),
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Page Settings', 'classiadspro'),
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'mobile_front_page',
				'type' => 'select',
				'title' => esc_html__('Select Custom Mobile homepage', 'classiadspro'),
				'subtitle' => esc_html__('select page will be delivered as mobile homepage', 'classiadspro'),
				'data' => 'pages'
			),
			array(
				'id' => 'pages-layout',
				'type' => 'image_select',
				'title' => esc_html__('Pages Layout', 'classiadspro'),
				'subtitle' => esc_html__('Defines Pages layout.', 'classiadspro'),
				'options' => array(
					'left' => array('alt' => '1 Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/left_layout.png'),
					'right' => array('alt' => '2 Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/right_layout.png'),
					'full' => array('alt' => '3 Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/full_layout.png'),
				),
				'default' => 'right'
			),
			array(
				'id' => 'page-title-pages',
				'type' => 'switch',
				'title' => esc_html__('Page Title : Pages', 'classiadspro'),
				'subtitle' => esc_html__('This option will affect Pages.', 'classiadspro'),
				'desc' => esc_html__('If you don\'t want to show page title section (title, breadcrumb) in Pages disable this option. this option will not affect archive, search, 404, category templates as well as blog and portfolio single posts.', 'classiadspro'),
				"default" => 1,
				'on' => 'Enable',
				'off' => 'Disable',
			),
			array(
				'id' => 'page-bg',
				'type' => 'background',
				'title' => esc_html__('Page Background', 'classiadspro'),
				'preset' => false,
				'default' => array(
					'background-image' => '',
					'background-color' => '#f9f9f9',
					'background-position' => '',
					'background-repeat' => 'repeat',
					'background-attachment' => 'scroll',
					'background-size' => '',
				)
			),
			array(
				'id' => 'page-title-bg',
				'type' => 'background',
				'title' => esc_html__('Page Title Background', 'classiadspro'),
				'preset' => false,
				'border' => true,
				'default' => array(
					'background-image' => '',
					'background-color' => '#333333',
					'background-position' => '',
					'background-repeat' => 'repeat',
					'background-attachment' => 'scroll',
					'background-size' => '',
				)
			),
			array(
				'id' => 'page-title-color',
				'type' => 'color',
				'title' => esc_html__('Page Title', 'classiadspro'),
				'default' => '#ffffff',
				'validate' => 'color',
			),
			array(
				'id' => 'breadcrumb',
				'type' => 'switch',
				'title' => esc_html__('Breadcrumb', 'classiadspro'),
				'subtitle' => esc_html__('Breadcrumbs will appear horizontally across the top of all pages below header.', 'classiadspro'),
				'desc' => esc_html__('Using this option you can disable breadcrumbs throughout the site.', 'classiadspro'),
				"default" => 1,
				'on' => 'Enable',
				'off' => 'Disable',
			),
			array(
				'id' => 'pages-comments',
				'type' => 'switch',
				'title' => esc_html__('Page Comments', 'classiadspro'),
				'subtitle' => esc_html__('Option to globally enable/disable comments in pages.', 'classiadspro'),
				"default" => 1,
				'on' => 'Enable',
				'off' => 'Disable',
			),
			array(
				'id' => 'custom-sidebar',
				'type' => 'multi_text',
				'title' => esc_html__('Custom Sidebars', 'classiadspro'),
				'validate' => 'no_special_chars',
				'subtitle' => esc_html__('Will create custom widget areas to help you make custom sidebars in pages & posts.', 'classiadspro'),
				'desc' => esc_html__('No Special characters please! eg: "contact page 3"', 'classiadspro')
			),
		),
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('404 Page', 'classiadspro'),
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'error_page',
				'type' => 'select',
				'title' => esc_html__('404 Page template', 'classiadspro'),
				'subtitle' => esc_html__('Defines 404 Pages template.', 'classiadspro'),
				'options' => array(
					'1' => esc_html__('Default', 'classiadspro'),
					'2' => esc_html__('Custom', 'classiadspro'),
				),
				'default' => '1'
			),
			array(
				'id' => 'error_page_id',
				'type' => 'select',
				'title' => esc_html__('Select Custom 404 Page', 'classiadspro'),
				'subtitle' => esc_html__('you can build custom page with any page builder', 'classiadspro'),
				'data' => 'pages',
				'required' => array('error_page', 'equals', '2'),
			),
			array(
				'id' => 'error-layout',
				'type' => 'image_select',
				'title' => esc_html__('404 Page Layout', 'classiadspro'),
				'subtitle' => esc_html__('Defines Pages layout.', 'classiadspro'),
				'options' => array(
					'left' => array('alt' => '1 Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/left_layout.png'),
					'right' => array('alt' => '2 Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/right_layout.png'),
					'full' => array('alt' => '3 Column', 'img' =>   PACZ_THEME_ADMIN_ASSETS_URI . '/img/full_layout.png'),
				),
				'default' => 'full'
			),
			array(
				'id' => 'error_page_small_text',
				'type' => 'textarea',
				'title' => esc_html__('404 Page small text', 'classiadspro'),
				'desc' => esc_html__('small text message to show at 404 page', 'classiadspro'),
				'default' => esc_html__('Far far away, behind the word mountains, far from the countries Vokalia and there live the blind texts. Sepraed. they live in Boo marksgrove right at the coast of the Semantics, a large language ocean A small river named Duden flows by their place and su plies it.', "classiadspro")
			),
		),
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Blog Search Page', 'classiadspro'),
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'search-layout',
				'type' => 'image_select',
				'title' => esc_html__('Search Page Layout', 'classiadspro'),
				'subtitle' => esc_html__('Defines search Page layout.', 'classiadspro'),
				'options' => array(
					'left' => array('alt' => '1 Column', 'img' =>  PACZ_THEME_ADMIN_ASSETS_URI . '/img/left_layout.png'),
					'right' => array('alt' => '2 Column', 'img' =>  PACZ_THEME_ADMIN_ASSETS_URI . '/img/right_layout.png'),
					'full' => array('alt' => '3 Column', 'img' =>  PACZ_THEME_ADMIN_ASSETS_URI . '/img/full_layout.png'),
				),
				'default' => 'full'
			),
		),
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('UI Style', 'classiadspro'),
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'checkbox_styles',
				'type' => 'select',
				'title' => esc_html__('Checkbox style', 'classiadspro'),
				'options' => array(
					'1' => esc_html__('Round (Default)', 'classiadspro'),
					'2' => esc_html__('Rectangle', 'classiadspro'),
				),
				'default' => '1'
			),
		),
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Header', 'classiadspro'),
		'icon' => 'el-icon-website',
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Header Layout', 'classiadspro'),
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'res-nav-width',
				'type' => 'slider',
				'title' => esc_html__('Main Navigation Responsive Width', 'classiadspro'),
				'subtitle' => esc_html__('The width Main navigation converts to responsive mode.', 'classiadspro'),
				'desc' => esc_html__('Navigation item can vary from site to site and it totally depends on you to define a the best width Main Navigation convert to responsive mode. you can find the right value by just resizing the window to find the best fit coresponding to navigation items.', 'classiadspro'),
				"default" => "1170",
				"min" => "600",
				"step" => "1",
				"max" => "1380",
			),
			array(
				'id' => 'preset_headers',
				'type' => 'select',
				'title' => esc_html__('Preset Header Layout', 'classiadspro'),
				'subtitle' => esc_html__('Select Preset Header', 'classiadspro'),
				'options' => array('1' => 'header1', '2' => 'Header2', '3' => 'Header3', '4' => 'Header4', '5' => 'Header5', '6' => 'Header6', '7' => 'Header7', '8' => 'Header8',  '9' => 'Header9',  '10' => 'Header10', '11' => 'Custom Header', '12' => 'Fantro', '13' => 'Directory' ), //Must provide key => value pairs for radio options 
				'default' => '1',
			),
			array(
				'id' => '_header_style',
				'type' => 'select',
				'title' => esc_html__('Header Styles', 'classiadspro'),
				'subtitle' => esc_html__('Block/Transparent', 'classiadspro'),
				'options' => array('block_module' => 'Block Module', 'transparent' => 'Transparent'), 
				'default' => 'block_module',
			),
			array(
				'id' => 'preset_headers_skin',
				'type' => 'switch',
				'title' => esc_html__('Preset Header Skin ', 'classiadspro'),
				'subtitle' => esc_html__('Turn on Preset Header custom Skins', 'classiadspro'), 
				'default' => false,
			),
			array(
				'id' => 'header-structure',
				'type' => 'select',
				'title' => esc_html__('Header Structure', 'classiadspro'),
				'options' => array('standard' => 'Standard', 'margin' => 'Margin', 'vertical' => 'Vertical', 'full' => 'Full Width'), //Must provide key => value pairs for radio options 
				'default' => 'standard',
			),
			
			array(
				'id' => 'header-location',
				'type' => 'select',
				'required' => array('header-structure', 'equals', 'standard'),
				'title' => esc_html__('Header Location', 'classiadspro'),
				'desc' => esc_html__('Whether stay at the top or bottom of the screen.', 'classiadspro'),
				'options' => array('top' => 'Top', 'bottom' => 'Bottom'), //Must provide key => value pairs for radio options
				'default' => 'top'
			),

			array(
				'id' => 'vertical-header-state',
				'type' => 'select',
				'required' => array('header-structure', 'equals', 'vertical'),
				'title' => esc_html__('Vertical Header State', 'classiadspro'),
				'subtitle' => esc_html__('Choose vertical header defaut state.', 'classiadspro'),
				'desc' => esc_html__('If condensed header chosen, header will be narrow by default and by clicking burger icon it will be expanded to reveal logo and navigation.', 'classiadspro'),
				'options' => array('condensed' => 'Expandable', 'expanded' => 'Always Open'), //Must provide key => value pairs for radio options
				'default' => 'expanded'
			),
			
			array(
				'id' => 'header-vertical-width',
				'type' => 'slider',
				'required' => array('header-structure', 'equals', 'vertical'),
				'title' => esc_html__('Header Vertical Width?', 'classiadspro'),
				'subtitle' => esc_html__('Default : 280px', 'classiadspro'),
				'desc' => esc_html__('Using this option you can increase or decrease header width.', 'classiadspro'),
				"default" => "280",
				"min" => "200",
				"step" => "1",
				"max" => "500",
			),
			array(
				'id' => 'header-padding',
				'type' => 'slider',
				'title' => esc_html__('Header Padding', 'classiadspro'),
				'subtitle' => esc_html__('Top & Bottom. default : 30px', 'classiadspro'),
				'desc' => esc_html__('Using this option you can increase or decrease header padding from its top and bottom.', 'classiadspro'),
				"default" => "34",
				"min" => "0",
				"step" => "1",
				"max" => "200",
			),
			array(
				'id' => 'header-padding-vertical',
				'type' => 'slider',
				'required' => array('header-structure', 'equals', 'vertical'),
				'title' => esc_html__('Header Padding', 'classiadspro'),
				'subtitle' => esc_html__('Left & Right. default : 30px', 'classiadspro'),
				'desc' => esc_html__('Using this option you can increase or decrease header padding from its top and bottom.', 'classiadspro'),
				"default" => "30",
				"min" => "0",
				"step" => "1",
				"max" => "100",
			),
			array(
				'id' => 'header-align',
				'type' => 'select',
				'title' => esc_html__('Header Align', 'classiadspro'),
				'desc' => esc_html__('Please note that this option does not work for vertical header style. Use below option instead', 'classiadspro'),
				'options' => array('left' => 'Left', 'center' => 'Center', 'right' => 'Right'), //Must provide key => value pairs for radio options
				'default' => 'left'
			),
			array(
				'id' => 'nav-alignment', 
				'type' => 'select',
				'title' => esc_html__('Vertical Header Menu Align', 'classiadspro'),
				'options' => array('left' => 'Left', 'center' => 'Center', 'right' => 'Right'), 
				'default' => 'left',
			),
			array(
				'id' => 'boxed-header',
				'type' => 'switch',
				'title' => esc_html__('Boxed Header', 'classiadspro'),
				'subtitle' => esc_html__('Full screen wide header content or inside main grid?.', 'classiadspro'),
				'desc' => esc_html__('If you want the cotent be stretched screen wide, disable this option.', 'classiadspro'),
				"default" => 1,
				'on' => 'Enable',
				'off' => 'Disable',
			),
			array(
				'id' => 'header-grid',
				'type' => 'switch',
				'title' => esc_html__('Header in Grid', 'classiadspro'),
				'subtitle' => esc_html__('Header will be in grid', 'classiadspro'),
				"default" => 0,
				'off' => 'Disable',
				'on' => 'Enable',
				
			),
			array(
				'id' => 'header-grid_postion',
				'type' => 'switch',
				'title' => esc_html__('Header in Grid Position Absolute', 'classiadspro'),
				"default" => false,
				
			),
			array(
				"title" => esc_html__( "In Grid Header Margin Top", "classiadspro" ),
				"id" => "header-grid-margin-top",
				"default" => "0",
				"min" => "-100",
				"max" => "50",
				"step" => "1",
				"unit" => 'px',
				"type" => "slider"
			),
			array(
				'id' => '_header_search_form',
				'type' => 'switch',
				'title' => esc_html__('Header Listing Search Form', 'classiadspro'),
				"default" => 0,
			),
			array(
				'id' => 'sticky-header',
				'type' => 'switch',
				'title' => esc_html__('Sticky Header', 'classiadspro'),
				'subtitle' => esc_html__('Will make header stay in top of browser while scrolling down', 'classiadspro'),
				'desc' => esc_html__('If you don\'t want this feature just disable it from this option.', 'classiadspro'),
				"default" => 0,
				'on' => 'Enable',
				'off' => 'Disable',
			),
			array(
				'id' => 'squeeze-sticky-header',
				'type' => 'switch',
				'title' => esc_html__('Squeeze Sticky Header', 'classiadspro'),
				'subtitle' => esc_html__('This option to give you the control on whether to squeeze the sticky header or keep it same height as none-sticky.', 'classiadspro'),
				'desc' => esc_html__('Disable this option if you dont want this feature.', 'classiadspro'),
				"default" => 1,
				'on' => 'Enable',
				'off' => 'Disable',
			),
			array(
				'id' => 'sticky_header_offset',
				'type' => 'slider',
				'title' => esc_html__('Sticky Header Offest', 'classiadspro'),
				"default" => 0,
				"min" => 0,
				"step" => 1,
				"max" => 1000,
			),
			array(
				"title" => esc_html__("Main Navigation Hover Style", "classiadspro"),
				"id" => "header-hover-style",
				"default" => 'primary-menu',
				"options" => array(
					"1" => esc_html__('Style 1', "classiadspro"),
					"2" => esc_html__('Disabled', "classiadspro")
				),
				"type" => "select"
			),
			array(
				'id' => 'header-border-top',
				'type' => 'switch',
				'title' => esc_html__('Show Header Border Top?', 'classiadspro'),
				"default" => 0,
				'on' => 'Enable',
				'off' => 'Disable',
			),
			array(
				'id' => 'header-search',
				'type' => 'switch',
				'title' => esc_html__('Header Search Form', 'classiadspro'),
				'subtitle' => esc_html__('Will stay on right hand of main navigation.', 'classiadspro'),
				'desc' => esc_html__('If you don\'t want this feature just disable it from this option.', 'classiadspro'),
				"default" => 0,
				'on' => 'Enable',
				'off' => 'Disable',
			),
			array(
				'id' => 'header-search-location',
				'type' => 'select',
				'required' => array(array('header-align', 'equals', 'center'),array('header-search', 'equals', '1')),
				'title' => esc_html__('Header Search Location', 'classiadspro'),
				'options' => array('left' => 'Left', 'right' => 'Right'), //Must provide key => value pairs for radio options
				'default' => 'right'
			),
			array(
				"title" => esc_html__("Main Navigation for Logged In User", "classiadspro"),
				"desc" => esc_html__("Please choose the menu location that you would like to show as global main navigation for logged in users.", "classiadspro"),
				"id" => "loggedin_menu",
				"default" => 'primary-menu',
				"options" => array(
					"primary-menu" => esc_html__('Primary Navigation', "classiadspro"),
					"second-menu" => esc_html__('Second Navigation', "classiadspro"),
					"third-menu" => esc_html__('Third Navigation', "classiadspro"),
					"fourth-menu" => esc_html__('Fourth Navigation', "classiadspro"),
					"fifth-menu" => esc_html__('Fifth Navigation', "classiadspro"),
					"sixth-menu" => esc_html__('Sixth Navigation', "classiadspro"),
					"seventh-menu" => esc_html__('Seventh Navigation', "classiadspro"),
				),
				"type" => "select"
			),
			array(
				'id' => 'header-bg',
				'type' => 'background',
				'title' => esc_html__('Header Background', 'classiadspro'),
				'preset' => false,
				'default' => array(
					'background-image' => '',
					'background-color' => '#ffffff',
					'background-position' => '',
					'background-repeat' => 'repeat',
					'background-attachment' => 'scroll',
					'background-size' => '',
				)
			),
			array(
				'id' => 'theader-bg',
				'type' => 'color_rgba',
				'title' => esc_html__('Transparent Header Background', 'classiadspro'),
				'default' => array(
				'color' =>'',
				'alpha'     => 1,
				)
			),
			array(
				'id' => 'header-bottom-border',
				'type' => 'color',
				'title' => esc_html__('Header Bottom Border Color', 'classiadspro'),
				'default' => '',
				'validate' => 'color',
			),
			array(
				'id' => 'header_shadow',
				'type' => 'switch',
				'title' => esc_html__('Header Shadow Bottom', 'classiadspro'),
				"default" => true,
			),
		)
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Header Toolbar', 'classiadspro'),
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'header-toolbar',
				'type' => 'switch',
				'title' => esc_html__('Header Toolbar', 'classiadspro'),
				'subtitle' => esc_html__('Enable/Disable Header Toolbar', 'classiadspro'),
				"default" => 0,
				'on' => 'Enable',
				'off' => 'Disable',
			),
			array(
				'id' => 'toolbar-grid',
				'type' => 'switch',
				'required' => array('header-toolbar', 'equals', '1'),
				'title' => esc_html__('Header Toolbar in grid', 'classiadspro'),
				'subtitle' => esc_html__('Enable/Disable Header Toolbar', 'classiadspro'),
				"default" => 0,
				'on' => 'Enable',
				'off' => 'Disable',
			),
			array(
				"title" => esc_html__("Header Toolbar Custom Menu", "classiadspro"),
				'required' => array('header-toolbar', 'equals', '1'),
				"id" => "toolbar-custom-menu",
				"default" => '',
				"data" => 'menus',
				"type" => "select"
			),
			array(
				"title" => esc_html__( "Header toolbar Height", "classiadspro" ),
				"id" => "toolbar_height",
				"default" => "110",
				"min" => "0",
				"max" => "200",
				"step" => "1",
				"unit" => 'px',
				"type" => "slider"
			),
			array(
				'id' => 'toolbar-font',
				'type' => 'typography',
				'title' => esc_html__('Toolbar Font', 'classiadspro'),
				'compiler' => true, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => false, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => false, // Only appears if google is true and subsets not set to false
				'font-size' => true,
				'line-height' => false,
				'color' => false,
				'preview' => true, // Disable the previewer
				'all_styles' => false, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'subtitle' => esc_html__('Choose your header toolbar font properties.', 'classiadspro'),
				'default' => array(
					'font-family' => '',
					'google' => true,
					'font-size' => '13px',
					'font-weight' => '400',
				),
			),
			array(
				'id' => 'toolbar-bg',
				'type' => 'background',
				'title' => esc_html__('Header Toolbar Background', 'classiadspro'),
				'preset' => false,
				'default' => array(
					'background-image' => '',
					'background-color' => '#ffffff',
					'background-position' => '',
					'background-repeat' => 'repeat',
					'background-attachment' => 'scroll',
					'background-size' => '',
				)
			),
			array(
				'id' => 'toolbar-border-top',
				'type' => 'switch',
				'title' => esc_html__('Show Toolbar Border Top?', 'classiadspro'),
				"default" => 0,
				'on' => 'Enable',
				'off' => 'Disable',
			),
			array(
				'id' => 'toolbar-border-bottom-color',
				'type' => 'color',
				'title' => esc_html__('Header Toolbar Border Bottom Color', 'classiadspro'),
				'default' => '#eee',
				'validate' => 'color',
			),
		)
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Header Menu', 'classiadspro'),
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'main-nav-font',
				'type' => 'typography',
				'title' => esc_html__('Main Navigation Top level', 'classiadspro'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => true, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => false, // Only appears if google is true and subsets not set to false
				'font-size' => true,
				'line-height' => false,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => false, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'default' => array(
					'font-family' => '',
					'google' => true,
					'font-size' => '13px',
					'font-weight' => '400',
				),
			),	
			array(
				'id' => 'main-nav-item-space',
				'type' => 'slider',
				'title' => esc_html__('Main Menu Items Gutter Space', 'classiadspro'),
				'subtitle' => esc_html__('Left & Right. default : 17px', 'classiadspro'),
				'desc' => esc_html__('This Value will be applied as padding to left and right of the item.', 'classiadspro'),
				"default" => "15",
				"min" => "0",
				"step" => "1",
				"max" => "100",
			),
			array(
				'id' => 'vertical-nav-item-space',
				'type' => 'slider',
				'required' => array('header-structure', 'equals', 'vertical'),
				'title' => esc_html__('Main Menu Items Top & Bottom Padding', 'classiadspro'),
				'subtitle' => esc_html__('Top & Bottom. default : 10px', 'classiadspro'),
				'desc' => esc_html__('This Value will be applied as padding to top and bottom of the item.', 'classiadspro'),
				"default" => "10",
				"min" => "0",
				"step" => "1",
				"max" => "25",
			),
			array(
				'id' => 'main-nav-top-transform',
				'type' => 'button_set',
				'title' => esc_html__('Main Menu Top Level Text Transform', 'classiadspro'),
				'options' => array('uppercase' => 'Uppercase', 'capitalize' => 'Capitalize', 'lowercase' => 'Lower Case'), 
				'default' => 'uppercase',
			),
			array(
				'id' => 'sub-nav-top-size',
				'type' => 'slider',
				'title' => esc_html__('Main Menu Sub Level Font Size', 'classiadspro'),
				"default" => "13", 
				"min" => "10",
				"step" => "1",
				"max" => "50",
			),
			array(
				'id' => 'sub-nav-top-transform',
				'type' => 'button_set',
				'title' => esc_html__('Main Menu Sub Level Text Transform', 'classiadspro'),
				'options' => array('uppercase' => 'Uppercase', 'capitalize' => 'Capitalize', 'lowercase' => 'Lower Case'), 
				'default' => 'capitalize',
			),
			array(
				'id' => 'sub-nav-top-weight',
				'type' => 'button_set',
				'title' => esc_html__('Main Menu Sub Level Font Weight', 'classiadspro'),
				'options' => array('lighter' => 'Light (300)', 'normal' => 'Normal (400)', '500' => '500', '600' => '600', 'bold' => 'Bold (700)', 'bolder' => 'Bolder', '8000' => 'Extra Bold (800)', '900' => '900'), 
				'default' => 'normal',
			),
			array(
				'id' => 'main-nav-top-color',
				'type' => 'nav_color',
				'title' => esc_html__('Main Navigation Top Level', 'classiadspro'),
				'subtitle' => esc_html__('Will affect main navigation top level links', 'classiadspro'),
				'regular' => true,
				'hover' => true,
				'bg' => true,
				'bg-active' => true,
				'bg-hover' => true,
				'default' => array(
					'regular' => '#333333',
					'hover' => '#c32026',
					'bg' => '',
					'bg-hover' => '',
					'bg-active' => '',
				)
			),
			array(
				'id' => 'main-nav-top-color-transparent',
				'type' => 'nav_color',
				'title' => esc_html__('Main Navigation Top Level (Transparent Header)', 'classiadspro'),
				'subtitle' => esc_html__('Will affect main navigation top level links on trnasparent header', 'classiadspro'),
				'regular' => true,
				'hover' => true,
				'bg' => true,
				'bg-active' => true,
				'bg-hover' => true,
				'default' => array(
					'regular' => '#fff',
					'hover' => '#c32026',
					'bg' => '',
					'bg-hover' => '',
					'bg-active' => '',
				)
			),
			array(
				'id' => 'main-nav-sub-bg',
				'type' => 'color',
				'title' => esc_html__('Main Navigation Sub Level Background Color', 'classiadspro'),
				'subtitle' => esc_html__('This value will affect Sub level background color including mega menu.', 'classiadspro'),
				'default' => '#fff',
				'validate' => 'color',
			),
			array(
				'id' => 'main-nav-sub-color',
				'type' => 'nav_color',
				'title' => esc_html__('Main Navigation Sub Level', 'classiadspro'),
				'subtitle' => esc_html__('all sub level menus ans sidebar links.', 'classiadspro'),
				'regular' => true,
				'hover' => true,
				'bg' => true,
				'bg-hover' => true,
				'bg-active' => true,
				'default' => array(
					'regular' => '#333',
					'hover' => '#fff',
					'bg' => '',
					'bg-hover' => '#c32026',
					'bg-active' => '#222',
				)
			),
			array(
				'id' => 'navigation-border-top',
				'type' => 'switch',
				'title' => esc_html__('Show Main Navigation Border Top?', 'classiadspro'),
				"default" => 0,
				'on' => 'Enable',
				'off' => 'Disable',
			),
		)
	));
	 Redux::setSection( $opt_name, array(
		'title' => esc_html__('Header Logo', 'classiadspro'),
		'subsection' => true,
		'fields' => array(
			array(
				"title" => esc_html__("Header logo location", "classiadspro"),
				"id" => "header-logo-location",
				"default" => 'header_section',
				"options" => array(
					"header_toolbar" => esc_html__('Header Toolbar', "classiadspro"),
					"header_section" => esc_html__('Header Section', "classiadspro"),
				),
				"type" => "select"
			),
			array(
				'id' => 'header-logo-align',
				'type' => 'select',
				'title' => esc_html__('Header Toolbar Logo Align', 'classiadspro'),
				'subtitle' => esc_html__('if logo location is toolbar selected ', 'classiadspro'),
				'desc' => esc_html__('Please note that this option does not work for vertical header style. Use below option instead', 'classiadspro'),
				'options' => array('left' => 'Left', 'center' => 'Center', 'right' => 'Right'), //Must provide key => value pairs for radio options
				'default' => 'left'
			),
			array(
				'id' => 'logo_dimensions',
				'type' => 'text',
				'title' => __('Logo Height', 'classiadspro'),
				'desc' => __('Set logo height', 'classiadspro'),
			),
			array(
				'id' => 'logo',
				'type' => 'media',
				'url' => false,
				'title' => esc_html__('Upload Logo', 'classiadspro'),
				'mode' => false,
				'default' => false,
			),
			array(
				'id' => 'transparent-logo',
				'type' => 'media',
				'url' => false,
				'title' => esc_html__('Upload Transparent Header Logo', 'classiadspro'),
				'mode' => false,
				'default' => false,
			),
			array(
				'id' => 'logo-retina',
				'type' => 'media',
				'url' => false,
				'title' => esc_html__('Upload Retina Logo', 'classiadspro'),
				'mode' => false,
				'desc' => esc_html__('Please note that the image you are uploading should be exactly 2x size of the original logo you have uploaded in above option.', 'classiadspro'),
				'subtitle' => esc_html__('Use this option if you want your logo appear crystal clean in retina devices(eg. macbook retina, ipad, iphone).', 'classiadspro'),
				'default' => false,
			),
			array(
				'id' => 'transparent-logo-retina',
				'type' => 'media',
				'url' => false,
				'title' => esc_html__('Upload Transparent Header Retina Logo', 'classiadspro'),
				'mode' => false,
				'desc' => esc_html__('Please note that the image you are uploading should be exactly 2x size of the original logo you have uploaded in above option.', 'classiadspro'),
				'subtitle' => esc_html__('Use this option if you want your logo appear crystal clean in retina devices(eg. macbook retina, ipad, iphone).', 'classiadspro'),
				'default' => false,
			),
		)
	));
	 Redux::setSection( $opt_name, array(
		'title' => esc_html__('Header Login Register', 'classiadspro'),
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'pacz-logreg-header-btn',
				'type' => 'switch',
				'title' => esc_html__('Add login and register button in top header', 'classiadspro'),
				"default" => 1,
				'on' => 'Enable',
				'off' => 'Disable',
			),
            array(
                'id'         => 'pacz-login-slug',
                'type'       => 'text',
                'title'      => 'Login Page Slug',
                'subtitle'   => 'set login page slug according to your login page',
                "default" 	 => 'login',
            ),
			array(
                'id'         => 'pacz-register-slug',
                'type'       => 'text',
                'title'      => 'Register Page Slug',
                'subtitle'   => 'set Register page slug according to your register page',
                "default" 	 => 'register',
            ),
			array(
                'id'         => 'pacz-forgot-slug',
                'type'       => 'text',
                'title'      => 'Forgot password Page Slug',
                'subtitle'   => 'set Forgot Password page slug according to your Forgot Password page',
                "default" 	 => 'forget-password',
            ),
			array(
				"title" => esc_html__("Login/Register Button Location", "classiadspro"),
				"id" => "header-login-reg-location",
				"default" => 'header_toolbar',
				"options" => array(
					"header_toolbar" => esc_html__('Header Toolbar', "classiadspro"),
					"header_section" => esc_html__('Header Section', "classiadspro"),
					"disabled" => esc_html__('Disabled', "classiadspro"),
				),
				"type" => "select"
			),
			array(
				'id' => 'log-reg-btn-align',
				'type' => 'select',
				'title' => esc_html__('Longin/Registered Button Align on Toolbar', 'classiadspro'),
				'subtitle' => esc_html__('if logo location is toolbar selected ', 'classiadspro'),
				'desc' => esc_html__('Please note that this option does not work for vertical header style. Use below option instead', 'classiadspro'),
				'options' => array('left' => 'Left', 'center' => 'Center', 'right' => 'Right', 'none' => 'none'), //Must provide key => value pairs for radio options
				'default' => 'right'
			),
		)
	));
	 Redux::setSection( $opt_name, array(
		'title' => esc_html__('Header Listing Button', 'classiadspro'),
		'subsection' => true,
		'fields' => array(
			array(
				"title" => esc_html__("Listing Button Location", "classiadspro"),
				"id" => "listing-btn-location",
				"default" => 'header_section',
				"options" => array(
					"header_toolbar" => esc_html__('Header Toolbar', "classiadspro"),
					"header_section" => esc_html__('Header Section', "classiadspro"),
					"disable" => esc_html__('Disable', "classiadspro"),
				),
				"type" => "select"
			),
			array(
				'id' => 'listing-btn-align',
				'type' => 'select',
				'title' => esc_html__('Header Listing Button Align', 'classiadspro'),
				'subtitle' => esc_html__('if logo location is toolbar selected ', 'classiadspro'),
				'desc' => esc_html__('Please note that this option does not work for vertical header style. Use below option instead', 'classiadspro'),
				'options' => array('left' => 'Left', 'center' => 'Center', 'right' => 'Right'), //Must provide key => value pairs for radio options
				'default' => 'right'
			),
			array(
				'id' => 'listing-btn-text',
				'type' => 'text',
				'title' => esc_html__('Header New Listing Button Text', 'classiadspro'),
				'desc' => esc_html__('button text', 'classiadspro'),
				'subtitle' => esc_html__('Header New Listing Button', 'classiadspro'),
				'default' => '',
			),
			array(
				'id'             => 'listing_button_padding',
				'type'           => 'spacing',
				'output'         => array(''),
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Header Listing Button Padding', 'classiadspro'),
				'desc' => __('you can set padding for each corner separately e.g (top =  top left, right = top right, bottom =  bottom right, left = bottom left )', 'classiadspro'),
				'default'            => array(
					'margin-top'     => '', 
					'margin-right'   => '', 
					'margin-bottom'  => '', 
					'margin-left'    => '',
					'units'          => 'px', 
				)
			),
			array(
				'id' => 'listing_button_border_width',
				'type' => 'slider',
				'title' => esc_html__('Header Listing Button Border Width', 'classiadspro'),
				"default" => "0",
				"min" => "0",
				"step" => "1",
				"max" => "10",
			),
			array(
				'id'             => 'listing_button_border_radius',
				'type'           => 'spacing',
				'output'         => array(''),
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Header Listing Button Border Radius', 'classiadspro'),
				'desc' => __('you can set radius for each corner separately e.g (top =  top left, right = top right, bottom =  bottom right, left = bottom left )', 'classiadspro'),
				'default'            => array(
					'margin-top'     => '', 
					'margin-right'   => '', 
					'margin-bottom'  => '', 
					'margin-left'    => '',
					'units'          => 'px', 
				)
			),
			array(
				'id' => 'listing-header-btn-color',
				'type' => 'nav_color',
				'title' => esc_html__('Header listing button colors', 'classiadspro'),
				'regular' => true,
				'hover' => true,
				'bg' => true,
				'bg-hover' => true,
				'default' => array(
					'regular' => '#ffffff',
					'hover' => '#fff',
					'bg' => '#3d51b2',
					'bg-hover' => '#77c04b',
				)
			),
			array(
				'id' => 'listing-header-btn-color-transparent',
				'type' => 'nav_color',
				'title' => esc_html__('Transparent Header listing button colors', 'classiadspro'),
				'regular' => true,
				'hover' => true,
				'bg' => true,
				'bg-hover' => true,
				'default' => array(
					'regular' => '#ffffff',
					'hover' => '#fff',
					'bg' => '#3d51b2',
					'bg-hover' => '#77c04b',
				)
			),
			array(
				'id' => 'header_listing_button_border_color',
				'type' => 'color_rgba',
				'title' => esc_html__('Header Listing Button Border Color ', 'classiadspro'),
				'default' => array(
				'color' =>'',
				'alpha'     => 1,
				)
			),
			array(
				'id' => 'header_listing_button_border_color_transparent',
				'type' => 'color_rgba',
				'title' => esc_html__('Transparent Header Listing Button Border Color ', 'classiadspro'),
				'default' => array(
				'color' =>'',
				'alpha'     => 1,
				)
			),
			array(
				'id' => 'header_listing_button_border_color_hover',
				'type' => 'color_rgba',
				'title' => esc_html__('Header Listing Button Border Color (Hover) ', 'classiadspro'),
				'default' => array(
				'color' =>'',
				'alpha'     => 1,
				)
			),
			array(
				'id' => 'header_listing_button_border_color_hover_transparent',
				'type' => 'color_rgba',
				'title' => esc_html__('Transparent Header Listing Button Border Color (Hover) ', 'classiadspro'),
				'default' => array(
				'color' =>'',
				'alpha'     => 1,
				)
			),
		)
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Header Listing Search', 'classiadspro'),
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'search_keyword_field',
				'type' => 'switch',
				'title' => esc_html__('Turn On Keyword Field', 'classiadspro'), 
				'default' => true,
			),
			array(
				'id' => 'search_keyword_ajax_field',
				'type' => 'switch',
				'title' => esc_html__('Turn On Ajax Keyword Search', 'classiadspro'), 
				'default' => true,
			),
			array(
				'id' => 'search_keyword_categories_field',
				'type' => 'switch',
				'title' => esc_html__('Turn On Categories In Keyword Field', 'classiadspro'), 
				'default' => true,
			),
			array(
				'id' => 'search_address_field',
				'type' => 'switch',
				'title' => esc_html__('Turn On Address Field', 'classiadspro'), 
				'default' => true,
			),
			array(
				'id' => 'search_address_locations_field',
				'type' => 'switch',
				'title' => esc_html__('Turn On Locations In Address Field', 'classiadspro'), 
				'default' => true,
			),
			array(
				'id' => 'search_button_icon',
				'type' => 'text',
				'title' => esc_html__('Search Button Icon', 'classiadspro'),
				'subtitle' => esc_html__('Add button icon class e.g fas fa-search-plus', 'classiadspro'),
				'default' => 'fas fa-search-plus',
			),
			array(
				'id'             => 'header_search_button_border_radius',
				'type'           => 'spacing',
				'output'         => array(''),
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Header search Button Border Radius', 'classiadspro'),
				'desc' => __('you can set radius for each corner separately e.g (top =  top left, right = top right, bottom =  bottom right, left = bottom left )', 'classiadspro'),
				'default'            => array(
					'margin-top'     => '', 
					'margin-right'   => '', 
					'margin-bottom'  => '', 
					'margin-left'    => '',
					'units'          => 'px', 
				)
			),
			array(
				'id' => 'header-search-icon-color',
				'type' => 'color',
				'title' => esc_html__('Header Search Icon Color', 'classiadspro'),
				'default' => '#333333',
				'validate' => 'color',
			),
		)
	));
	 Redux::setSection( $opt_name, array(
		'title' => esc_html__('Toolbar Contact info ', 'classiadspro'),
		'subsection' => true,
		'fields' => array(
			array(
				"title" => esc_html__("Header contact", "classiadspro"),
				"id" => "header-contact-select",
				"default" => 'header_toolbar',
				"options" => array(
					"header_toolbar" => esc_html__('Header Toolbar', "classiadspro"),
					"disabled" => esc_html__('Disabled', "classiadspro")
				),
				"type" => "select"
			),
			array(
				'id' => 'header-contact-align',
				'type' => 'select',
				'title' => esc_html__('Header Toolbar contact Align', 'classiadspro'),
				'subtitle' => esc_html__('if logo location is toolbar selected ', 'classiadspro'),
				'desc' => esc_html__('Please note that this option does not work for vertical header style. Use below option instead', 'classiadspro'),
				'options' => array('left' => 'Left', 'center' => 'Center', 'right' => 'Right'), //Must provide key => value pairs for radio options
				'default' => 'right'
			),
			array(
				'id' => 'header-toolbar-phone',
				'type' => 'text',
				'required' => array('header-toolbar', 'equals', '1'),
				'title' => esc_html__('Header Toolbar Phone Number', 'classiadspro'),
				'default' => '',
			),
			array(
				'id' => 'header-toolbar-phone-icon',
				'type' => 'text',
				'required' => array('header-toolbar', 'equals', '1'),
				'title' => esc_html__('Header Toolbar Phone Icon', 'classiadspro'),
				'desc' => esc_html__("This option will give you the ability to add any icon you want to use for front of the email address.  to get the icon class name.", 'classiadspro'),
				'default' => '',
			),
			array(
				'id' => 'header-toolbar-email',
				'type' => 'text',
				'required' => array('header-toolbar', 'equals', '1'),
				'title' => esc_html__('Header Toolbar Email Address', 'classiadspro'),
				'default' => '',
			),
			array(
				'id' => 'header-toolbar-email-icon',
				'type' => 'text',
				'required' => array('header-toolbar', 'equals', '1'),
				'title' => esc_html__('Header Toolbar Email Icon', 'classiadspro'),
				'desc' => esc_html__("This option will give you the ability to add any icon you want to use for front of the email address.  to get the icon class name.", 'classiadspro'),
				'default' => '',
			),
			array(
				'id' => 'toolbar-text-color',
				'type' => 'color',
				'title' => esc_html__('Header Toolbar Text Color', 'classiadspro'),
				'default' => '#999999',
				'validate' => 'color',
			),
			array(
				'id' => 'toolbar-phone-email-icon-color',
				'type' => 'color',
				'title' => esc_html__('Header Toolbar Phone & Email Icon Color', 'classiadspro'),
				'default' => '#ffffff',
				'validate' => 'color',
			),
			array(
				'id' => 'toolbar-link-color',
				'type' => 'nav_color',
				'title' => esc_html__('Header Toolbar Link Color', 'classiadspro'),
				'regular' => true,
				'hover' => true,
				'default' => array(
					'regular' => '#999999',
					'hover' => '#c32026'
				)
			),
		)
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Header Social Networks', 'classiadspro'),
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'toolbar-social-link-color',
				'type' => 'nav_color',
				'title' => esc_html__('Header Toolbar Social Network Link Color', 'classiadspro'),
				'regular' => true,
				'hover' => true,
				'bg' => true,
				'bg-hover' => true,
				'default' => array(
					'regular' => '#ffffff',
					'hover' => '#c32026',
					'bg-hover' => '#c32026',
				)
			),
			array(
				'id' => 'toolbar-social-link-color-bg',
				'type' => 'color_rgba',
				'title' => esc_html__('toolbar Social links background ', 'classiadspro'),
				'subtitle' => esc_html__('you can use rgba color ', 'classiadspro'),
				'default' => array(
				'color' =>'#eee',
				'alpha'     => 1,
				)
			),
			array(
				"title" => esc_html__("Header Social Networks", "classiadspro"),
				"id" => "header-social-select",
				"default" => 'header_toolbar',
				"options" => array(
					"header_toolbar" => esc_html__('Header Toolbar', "classiadspro"),
					"header_section" => esc_html__('Header Section', "classiadspro"),
					"disabled" => esc_html__('Disabled', "classiadspro")
				),
				"type" => "select"
			),
			array(
				'id' => 'header-social-align',
				'type' => 'select',
				'title' => esc_html__('Header Toolbar SAocial Align', 'classiadspro'),
				'subtitle' => esc_html__('if logo location is toolbar selected ', 'classiadspro'),
				'desc' => esc_html__('Please note that this option does not work for vertical header style. Use below option instead', 'classiadspro'),
				'options' => array('left' => 'Left', 'center' => 'Center', 'right' => 'Right'), //Must provide key => value pairs for radio options
				'default' => 'left'
			),
			array(
				'id' => 'header-social-facebook',
				'required' => array('header-social-select', 'equals', array( 'header_toolbar', 'header_section' )),
				'type' => 'text',
				'title' => esc_html__('Facebook', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Header Social Networks', 'classiadspro'),
				'default' => '',
			),

			array(
				'id' => 'header-social-twitter',
				'required' => array('header-social-select', 'equals', array( 'header_toolbar', 'header_section' )),
				'type' => 'text',
				'title' => esc_html__('Twitter', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Header Social Networks', 'classiadspro'),
				'default' => '',
			),

			array(
				'id' => 'header-social-rss',
				'required' => array('header-social-select', 'equals', array( 'header_toolbar', 'header_section' )),
				'type' => 'text',
				'title' => esc_html__('RSS', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Header Social Networks', 'classiadspro'),
				'default' => '',
			),

			array(
				'id' => 'header-social-dribbble',
				'required' => array('header-social-select', 'equals', array( 'header_toolbar', 'header_section' )),
				'type' => 'text',
				'title' => esc_html__('Dribbble', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Header Social Networks', 'classiadspro'),
				'default' => '',
			),

			array(
				'id' => 'header-social-pinterest',
				'required' => array('header-social-select', 'equals', array( 'header_toolbar', 'header_section' )),
				'type' => 'text',
				'title' => esc_html__('Pinterest', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Header Social Networks', 'classiadspro'),
				'default' => '',
			),

			array(
				'id' => 'header-social-instagram',
				'required' => array('header-social-select', 'equals', array( 'header_toolbar', 'header_section' )),
				'type' => 'text',
				'title' => esc_html__('Instagram', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Header Social Networks', 'classiadspro'),
				'default' => '',
			),

			array(
				'id' => 'header-social-google-plus',
				'required' => array('header-social-select', 'equals', array( 'header_toolbar', 'header_section' )),
				'type' => 'text',
				'title' => esc_html__('Google Plus', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Header Social Networks', 'classiadspro'),
				'default' => '',
			),

			array(
				'id' => 'header-social-linkedin',
				'required' => array('header-social-select', 'equals', array( 'header_toolbar', 'header_section' )),
				'type' => 'text',
				'title' => esc_html__('Linkedin', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Header Social Networks', 'classiadspro'),
				'default' => '',
			),

			array(
				'id' => 'header-social-youtube',
				'required' => array('header-social-select', 'equals', array( 'header_toolbar', 'header_section' )),
				'type' => 'text',
				'title' => esc_html__('Youtube', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Header Social Networks', 'classiadspro'),
				'default' => '',
			),
			array(
				'id' => 'header-social-vimeo',
				'required' => array('header-social-select', 'equals', array( 'header_toolbar', 'header_section' )),
				'type' => 'text',
				'title' => esc_html__('Vimeo', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Header Social Networks', 'classiadspro'),
				'default' => '',
			),
			array(
				'id' => 'header-social-spotify',
				'required' => array('header-social-select', 'equals', array( 'header_toolbar', 'header_section' )),
				'type' => 'text',
				'title' => esc_html__('Spotify', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Header Social Networks', 'classiadspro'),
				'default' => '',
			),

			array(
				'id' => 'header-social-tumblr',
				'required' => array('header-social-select', 'equals', array( 'header_toolbar', 'header_section' )),
				'type' => 'text',
				'title' => esc_html__('Tumblr', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Header Social Networks', 'classiadspro'),
				'default' => '',
			),

			array(
				'id' => 'header-social-behance',
				'required' => array('header-social-select', 'equals', array( 'header_toolbar', 'header_section' )),
				'type' => 'text',
				'title' => esc_html__('Behance', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Header Social Networks', 'classiadspro'),
				'default' => '',
			),
			array(
				'id' => 'header-social-WhatsApp',
				'required' => array('header-social-select', 'equals', array( 'header_toolbar', 'header_section' )),
				'type' => 'text',
				'title' => esc_html__('WhatsApp', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Header Social Networks', 'classiadspro'),
				'default' => '',
			),
			array(
				'id' => 'header-social-qzone',
				'required' => array('header-social-select', 'equals', array( 'header_toolbar', 'header_section' )),
				'type' => 'text',
				'title' => esc_html__('qzone', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Header Social Networks', 'classiadspro'),
				'default' => '',
			),
			array(
				'id' => 'header-social-vkcom',
				'required' => array('header-social-select', 'equals', array( 'header_toolbar', 'header_section' )),
				'type' => 'text',
				'title' => esc_html__('vk.com', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Header Social Networks', 'classiadspro'),
				'default' => '',
			),
			array(
				'id' => 'header-social-imdb',
				'required' => array('header-social-select', 'equals', array( 'header_toolbar', 'header_section' )),
				'type' => 'text',
				'title' => esc_html__('IMDb', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Header Social Networks', 'classiadspro'),
				'default' => '',
			),
			array(
				'id' => 'header-social-renren',
				'required' => array('header-social-select', 'equals', array( 'header_toolbar', 'header_section' )),
				'type' => 'text',
				'title' => esc_html__('Renren', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Header Social Networks', 'classiadspro'),
				'default' => '',
			),
			array(
				'id' => 'header-social-weibo',
				'required' => array('header-social-select', 'equals', array( 'header_toolbar', 'header_section' )),
				'type' => 'text',
				'title' => esc_html__('Weibo', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Header Social Networks', 'classiadspro'),
				'default' => '',
			),
		)
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Woocomerce Cart Button', 'classiadspro'),
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'checkout-box',
				'type' => 'switch',
				'title' => esc_html__('Header Cart Button', 'classiadspro'),
				'subtitle' => esc_html__('Using This option you can remove cart button from header.', 'classiadspro'),
				"default" => 0,
				'on' => 'Enable',
				'off' => 'Disable',
			),
			array(
				"title" => esc_html__("Header Cart Button", "classiadspro"),
				'required' => array('checkout-box', 'equals', 1),
				"id" => "checkout-box-location",
				"default" => 'disabled',
				"options" => array(
					"header_toolbar" => esc_html__('Header Toolbar', "classiadspro"),
					"header_section" => esc_html__('Header Section', "classiadspro"),
					"disabled" => esc_html__('Disabled', "classiadspro"),
				),
				"type" => "select"
			),
			array(
				'id' => 'checkout-box-align',
				'type' => 'button_set',
				'title' => esc_html__('Header Cart Button align on toolbar', 'classiadspro'),
				'options' => array('left' => 'Left', 'right' => 'Right', 'center' => 'Center'),
				'default' => 'right'
			),
			
			array(
				'id' => 'header_cart_link_color',
				'type' => 'nav_color',
				'title' => esc_html__('Header cart Link Color', 'classiadspro'),
				'regular' => true,
				'hover' => true,
				'bg' => true,
				'bg-hover' => true,
				'default' => array(
					'regular' => '#ffffff',
					'hover' => '#fff',
					'bg' => '#c32026',
					'bg-hover' => '#c32026',
				)
			),
		)
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('WPML Link', 'classiadspro'),
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'header-wpml',
				'type' => 'switch',
				'title' => esc_html__('Header Wpml Language Selector', 'classiadspro'),
				'desc' => esc_html__('If you don\'t want this feature just disable it from this option.', 'classiadspro'),
				"default" => 0,
				'on' => 'Enable',
				'off' => 'Disable',
			),
		)
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Mobile Header', 'classiadspro'),
		'icon' => 'el-icon-website',
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Header Background', 'classiadspro'),
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'mobile-header-bg',
				'type' => 'background',
				'title' => esc_html__('Background', 'classiadspro'),
				'preset' => false,
				'default' => array(
					'background-image' => '',
					'background-color' => '#ffffff',
					'background-position' => '',
					'background-repeat' => 'repeat',
					'background-attachment' => 'scroll',
					'background-size' => '',
				)
			),
		)
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Mobile Logo', 'classiadspro'),
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'mobile-logo',
				'type' => 'media',
				'url' => false,
				'title' => esc_html__('Upload Mobile Logo', 'classiadspro'),
				'mode' => false,
				'subtitle' => esc_html__('This option comes handly when your logo is just too long for a mobile device and you would like to upload a shorter and smaller logo just to fit the header area.', 'classiadspro'),
				'default' => false,
			),

			array(
				'id' => 'mobile-logo-retina',
				'type' => 'media',
				'url' => false,
				'title' => esc_html__('Upload Mobile Retina Logo', 'classiadspro'),
				'mode' => false,
				'desc' => esc_html__('Please note that the image you are uploading should be exactly 2x size of the original logo you have uploaded in above option (Upload Mobile Logo).', 'classiadspro'),
				'default' => false,
			),
		)
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Listing Button', 'classiadspro'),
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'mobile-listing-button',
				'type' => 'switch',
				'title' => esc_html__('Mobile Header Listing Button', 'classiadspro'),
				"default" => true,
			),
			array(
				'id' => 'mobile-listing-button-skin',
				'type' => 'nav_color',
				'title' => esc_html__('Mobile Header Listing Button Skin', 'classiadspro'),
				'regular' => true,
				'hover' => true,
				'bg' => true,
				'bg-hover' => true,
				'default' => array(
					'regular' => '#1c1e21',
					'hover' => '#fff',
					'bg' => '#F2F3F5',
					'bg-hover' => '#f35359',
				)
			),
			array(
                'id'         => 'mobile-listing-button-icon',
                'type'       => 'text',
                'title'      => esc_html__('Mobile Header Listing Button Icon Class', 'classiadspro'),
                'subtitle'   => esc_html__('you can set custom icon class e.g fas fa-plus', 'classiadspro'),
                "default" 	 => 'fas fa-plus',
            ),
		)
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Login Button', 'classiadspro'),
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'mobile-login-button',
				'type' => 'switch',
				'title' => esc_html__('Mobile Header Login Button', 'classiadspro'),
				"default" => true,
			),
			array(
				'id' => 'mobile-login-button-skin',
				'type' => 'nav_color',
				'title' => esc_html__('Mobile Header Login Button Skin', 'classiadspro'),
				'regular' => true,
				'hover' => true,
				'bg' => true,
				'bg-hover' => true,
				'default' => array(
					'regular' => '#1c1e21',
					'hover' => '#fff',
					'bg' => '#F2F3F5',
					'bg-hover' => '#f35359',
				)
			),
			array(
                'id'         => 'mobile-login-button-icon',
                'type'       => 'text',
                'title'      => esc_html__('Mobile Header Login Button Icon Class', 'classiadspro'),
                'subtitle'   => esc_html__('you can set custom icon class e.g far fa-user', 'classiadspro'),
                "default" 	 => 'far fa-user',
            ),
		)
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Search Button', 'classiadspro'),
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'mobile-search-button',
				'type' => 'switch',
				'title' => esc_html__('Mobile Header Search Button', 'classiadspro'),
				"default" => true,
			),
			array(
				'id' => 'mobile-search-button-skin',
				'type' => 'nav_color',
				'title' => esc_html__('Mobile Header Listing Button Skin', 'classiadspro'),
				'regular' => true,
				'hover' => true,
				'bg' => true,
				'bg-hover' => true,
				'default' => array(
					'regular' => '#1c1e21',
					'hover' => '#fff',
					'bg' => '#F2F3F5',
					'bg-hover' => '#f35359',
				)
			),
			array(
                'id'         => 'mobile-search-button-icon',
                'type'       => 'text',
                'title'      => esc_html__('Mobile Header Listing Button Icon Class', 'classiadspro'),
                'subtitle'   => esc_html__('you can set custom icon class e.g fas fa-search', 'classiadspro'),
                "default" 	 => 'fas fa-search',
            ),
		)
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Mobile Header Menu', 'classiadspro'),
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'mobile-header-author-bg',
				'type' => 'background',
				'title' => esc_html__('Author Box Background', 'classiadspro'),
				'preset' => false,
				'default' => array(
					'background-image' => '',
					'background-color' => '#15a949',
					'background-position' => '',
					'background-repeat' => 'repeat',
					'background-attachment' => 'scroll',
					'background-size' => '',
				)
			),
			array(
				'id' => 'mobile-header-author-display-name-color',
				'type' => 'color',
				'title' => esc_html__('Author Display Name Color', 'classiadspro'),
				'default' => '#333',
				'validate' => 'color',
			),
			array(
				'id' => 'mobile-header-author-nickname-color',
				'type' => 'color',
				'title' => esc_html__('Author Nick Name Color', 'classiadspro'),
				'default' => '#fff',
				'validate' => 'color',
			),
			array(
				'id' => 'mobile-header-author-links-color',
				'type' => 'link_color',
				'title' => esc_html__('Author Box Links Color', 'classiadspro'),
				'active' => false,
				'default' => array(
					'regular' => '#393c71',
					'hover' => '#393c71'
				)
			),
			array(
				'id' => 'mobile-header-menu-icon-color',
				'type' => 'link_color',
				'title' => esc_html__('Mobile Menu Burger Skin', 'classiadspro'),
				'default' => array(
					'regular' => '#1c1e21',
					'hover' => '#f35359',
					'active' => '#f35359',
				)
			),
			array(
				'id' => 'mobile-header-menu-wrapper-bg',
				'type' => 'background',
				'title' => esc_html__('Menu Wrapper Background', 'classiadspro'),
				'preset' => false,
				'default' => array(
					'background-image' => '',
					'background-color' => '#fff',
					'background-position' => '',
					'background-repeat' => 'repeat',
					'background-attachment' => 'scroll',
					'background-size' => '',
				)
			),
			array(
				'id' => 'mobile-nav-top-color',
				'type' => 'nav_color',
				'title' => esc_html__('Mobile Navigation Top Level', 'classiadspro'),
				'subtitle' => esc_html__('Will affect main navigation top level links', 'classiadspro'),
				'regular' => true,
				'hover' => true,
				'bg' => true,
				'bg-active' => true,
				'bg-hover' => true,
				'default' => array(
					'regular' => '#333333',
					'hover' => '#c32026',
					'bg' => '#fff',
					'bg-hover' => '',
					'bg-active' => '',
				)
			),
			array(
				'id' => 'mobile-top-menu-border-color',
				'type' => 'color',
				'title' => esc_html__('Mobile Navigation Top Level border color', 'classiadspro'),
				'default' => '#eee',
				'validate' => 'color',
			),
			array(
				'id' => 'mobile-nav-sub-menu-color',
				'type' => 'nav_color',
				'title' => esc_html__('Mobile Navigation Sub Menu Skin', 'classiadspro'),
				'regular' => true,
				'hover' => true,
				'bg' => true,
				'bg-hover' => true,
				'bg-active' => true,
				'default' => array(
					'regular' => '#333',
					'hover' => '#fff',
					'bg' => '#f5f5f5',
					'bg-hover' => '#555',
					'bg-active' => '#333',
				)
			)
		)
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Footer', 'classiadspro'),
		'icon' => 'el-icon-photo',
		'fields' => array(
			array(
				'id' => 'footer',
				'type' => 'switch',
				'title' => esc_html__('Footer', 'classiadspro'),
				'subtitle' => esc_html__('Will be located after content. Please note that sub footer will not be affected by this option.', 'classiadspro'),
				'desc' => esc_html__('If you don\'t want to have footer section you can disable it.', 'classiadspro'),
				"default" => 1,
				'on' => 'Enable',
				'off' => 'Disable',
			),
			array(
				'id' => 'footer-layout',
				'required' => array('footer', 'equals', '1'),
				'type' => 'image_select',
				'title' => esc_html__('Footer Widget Area Columns', 'classiadspro'),
				'subtitle' => esc_html__('Defines in which strcuture footer widget areas would be divided', 'classiadspro'),
				'desc' => esc_html__('Please choose your footer widget area column strucutre.', 'classiadspro'),
				'options' => array(
					'1' => array('alt' => '1 Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/column_1.png'),
					'2' => array('alt' => '2 Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/column_2.png'),
					'3' => array('alt' => '3 Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/column_3.png'),
					'4' => array('alt' => '4 Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/column_4.png'),
					'5' => array('alt' => '5 Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/column_5.png'),
					'6' => array('alt' => '6 Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/column_6.png'),
					'half_sub_half' => array('alt' => 'Half Sub Half Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/column_half_sub_half.png'),
					'half_sub_third' => array('alt' => 'Half Sub Third Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/column_half_sub_third.png'),
					'third_sub_third' => array('alt' => 'Third Sub Third Column', 'img' =>  PACZ_THEME_ADMIN_ASSETS_URI . '/img/column_third_sub_third.png'),
					'third_sub_fourth' => array('alt' => 'Third Sub Fourth Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/column_third_sub_fourth.png'),
					'sub_half_half' => array('alt' => 'Sub Half Half Column', 'img' =>  PACZ_THEME_ADMIN_ASSETS_URI . '/img/column_sub_half_half.png'),
					'sub_third_half' => array('alt' => 'Sub Third Half Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/column_sub_third_half.png'),
					'sub_third_third' => array('alt' => 'Sub Third Third Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/column_sub_third_third.png'),
					'sub_fourth_third' => array('alt' => 'Sub Fourth Third Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/column_sub_fourth_third.png'),

				),
				'default' => 'half_sub_half'
			),
			array(
				'id' => 'top-footer',
				'type' => 'switch',
				'title' => esc_html__('Top Footer', 'classiadspro'),
				'subtitle' => esc_html__('Locates below footer.', 'classiadspro'),
				'desc' => esc_html__('If you don\'t want to have Top footer section you can disable it.', 'classiadspro'),
				"default" => 0,
				'on' => 'Enable',
				'off' => 'Disable',
			),
			array(
				"title" => esc_html__("footer subscription form styles", "classiadspro"),
				'required' => array('top-footer', 'equals', '1'),
				"desc" => esc_html__("Please choose the menu location that you would like to show as global main navigation for logged in users.", "classiadspro"),
				"id" => "footer_form_style",
				"default" => '',
				"options" => array(
					"1" => esc_html__('Style 1', "classiadspro"),
					"2" => esc_html__('Style 2', "classiadspro"),
					"3" => esc_html__('Style 3', "classiadspro"),
					"4" => esc_html__('Style 4', "classiadspro"),
				),
				"type" => "select"
			),
			array(
				'id' => 'footer_top_logo',
				'type' => 'media',
				'url' => true,
				'required' => array('footer_form_style', 'equals', '4'),
				'title' => esc_html__('Upload Top Footer Logo', 'classiadspro'),
				'mode' => false,
				'default' => false,
			),
			array (
				"type" => "text",
				"title" => esc_html__ ( "Form id", 'classiadspro' ),
				"id" => "form_id",
				"default" => '2015',
			),
			array(
				'id' => 'sub-footer',
				'type' => 'switch',
				'title' => esc_html__('Sub Footer', 'classiadspro'),
				'subtitle' => esc_html__('Locates below footer.', 'classiadspro'),
				'desc' => esc_html__('If you don\'t want to have sub footer section you can disable it.', 'classiadspro'),
				"default" => 1,
				'on' => 'Enable',
				'off' => 'Disable',
			),
			array(
				'id' => 'back-to-top',
				'type' => 'switch',
				'title' => esc_html__('Sub Footer Back to Top Button', 'classiadspro'),
				"default" => 0,
				'on' => 'Enable',
				'off' => 'Disable',
			),
			array(
				"title" => esc_html__("footer back to top button style", "classiadspro"),
				'required' => array('sub-footer', 'equals', '1'),
				"id" => "back_to_top_style",
				"default" => '1',
				"options" => array(
					"1" => esc_html__('Style 1', "classiadspro"),
					"2" => esc_html__('Style 2', "classiadspro"),
					"3" => esc_html__('Style 3', "classiadspro"),
					"4" => esc_html__('Style 4', "classiadspro"),
				),
				"type" => "select"
			),
			array(
				'id' => 'footer_sell_btn',
				'type' => 'switch',
				'title' => esc_html__('Footer Sell Button', 'classiadspro'),
				'subtitle' => '',
				'desc' => '',
				"default" => 0,
				'on' => 'Enable',
				'off' => 'Disable',
			),
			array(
				'id' => 'sell_btn_text',
				'type' => 'text',
				'title' => 'Footer Sell Button Text',
				'default' => esc_html__('Sell', 'classiadspro')
			),
			array(
				'id' => 'footer-copyright',
				'type' => 'textarea',
				'required' => array('sub-footer', 'equals', '1'),
				'title' => esc_html__('Sub Footer Copyright text', 'classiadspro'),
				'subtitle' => esc_html__('You may write your site copyright information.', 'classiadspro'),
				'desc' => '',
				'default' => 'Copyright All Rights Reserved'
			),
			array(
				'id' => 'subfooter-logos-src',
				'required' => array('sub-footer', 'equals', '1'),
				'type' => 'media',
				'url' => true,
				'title' => esc_html__('Sub Footer Right Section Logo Image', 'classiadspro'),
				'mode' => false,
				'default' => false,
			),
			array(
				'id' => 'subfooter-logos-link',
				'required' => array('sub-footer', 'equals', '1'),
				'type' => 'text',
				'title' => esc_html__('Sub Footer Right Section Logo Link', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'default' => '',
			),
			array(
				'id' => 'footer-bg',
				'type' => 'background',
				'title' => esc_html__('Footer Background', 'classiadspro'),
				'preset' => false,
				'default' => array(
					'background-image' => '',
					'background-color' => '#f2f2f2',
					'background-position' => '',
					'background-repeat' => 'repeat',
					'background-attachment' => 'scroll',
					'background-size' => '',
				)
			),
			array(
				'id' => 'sub-footer-bg',
				'type' => 'color',
				'title' => esc_html__('Sub Footer Background Color', 'classiadspro'),
				'default' => '#222222',
				'validate' => 'color',
			),
			array(
				'id' => 'top-footer-bg',
				'type' => 'color',
				'title' => esc_html__('Top Footer Background Color', 'classiadspro'),
				'default' => '#eee',
				'validate' => 'color',
			),
			array(
				'id' => 'footer-title-color',
				'type' => 'color',
				'title' => esc_html__('Footer Widget Title', 'classiadspro'),
				'default' => '#ffffff',
				'validate' => 'color',
			),

			array(
				'id' => 'footer-txt-color',
				'type' => 'color',
				'title' => esc_html__('Footer Widget Texts', 'classiadspro'),
				'subtitle' => esc_html__('Will affect all texts in footer widget (unless there is a color value for the specific option in theme styles)', 'classiadspro'),
				'default' => '#777',
				'validate' => 'color',
			),

			array(
				'id' => 'footer-link-color',
				'type' => 'link_color',
				'title' => esc_html__('Footer Widget Links', 'classiadspro'),
				'subtitle' => esc_html__('Will affect all links in footer section.', 'classiadspro'),
				'regular' => true,
				'hover' => true,
				'default' => array(
					'regular' => '#777',
					'hover' => '#c32026',
				)
			),
			array(
				'id' => 'footer-recent-lisitng-border-color',
				'type' => 'color',
				'title' => esc_html__('Footer recent Posts border Bottom', 'classiadspro'),
				'regular' => true,
				'hover' => true,
				'default' => '#777',
				'validate' => 'color',
			),
			array(
				'id' => 'sub-footer-border-top',
				'type' => 'switch',
				'title' => esc_html__('Show Sub Footer Border Top?', 'classiadspro'),
				"default" => 0,
				'on' => 'Enable',
				'off' => 'Disable',
			),
			array(
				'id' => 'sub-footer-border-top-color',
				'type' => 'color_rgba',
				'title' => esc_html__('Sub-Footer border top color', 'classiadspro'),
				'subtitle' => '',
				'color' => '',
				'alpha' => 1,
			),
			array(
				'id' => 'footer-col-border',
				'type' => 'switch',
				'title' => esc_html__('Show Border Right to Footer Collumns?', 'classiadspro'),
				"default" => 1,
				'on' => 'Enable',
				'off' => 'Disable',
			),
			array(
				'id' => 'footer-col-border-color',
				'type' => 'color',
				'required' => array('footer-col-border', 'equals', '1'),
				'title' => esc_html__('Footer Collumn Border Color', 'classiadspro'),
				'subtitle' => esc_html__('Will affect all social network icons in sub footer. you can set its active and hover values.', 'classiadspro'),
				'default' => '#2e2e2e',
				'validate' => 'color',
				
			),
			array(
				'id' => 'footer-social-color',
				'type' => 'nav_color',
				'title' => esc_html__('Sub-Footer Social Networks Color', 'classiadspro'),
				'subtitle' => esc_html__('Will affect all social network icons in sub footer. you can set its active and hover values.', 'classiadspro'),
				'regular' => true,
				'hover' => true,
				'bg' => true,
				'bg-hover' => true,
				'default' => array(
					'regular' => '#777',
					'hover' => '#c32026',
					'bg' => '',
					'bg-hover' => '',
				)
				
			),
			array(
				'id' => 'footer-socket-color',
				'type' => 'color',
				'title' => esc_html__('Sub-Footer Copyright Color', 'classiadspro'),
				'subtitle' => esc_html__('Will affect sub footer left side copyright text.', 'classiadspro'),
				'default' => '#777',
				'validate' => 'color',
			),
			array(
				"title" => esc_html__("Footer Social Location", "classiadspro"),
				"desc" => esc_html__("Please choose the Social Network location for footer. location top will show social networks links with subscription form if form style 3 is selected, location bottom and will show icons on sub-footer and both with show icons on both locations ", "classiadspro"),
				"id" => "footer-social-location",
				"default" => '2',
				"options" => array(
					"1" => esc_html__('Top', "classiadspro"),
					"2" => esc_html__('Bottom', "classiadspro"),
					"3" => esc_html__('Both', "classiadspro"),
				),
				"type" => "select"
			),
			array(
				'id' => 'social-facebook',
				'required' => array('sub-footer', 'equals', '1'),
				'type' => 'text',
				'title' => esc_html__('Facebook', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Sub Footer Social Networks', 'classiadspro'),
				'default' => '',
			),

			array(
				'id' => 'social-twitter',
				'required' => array('sub-footer', 'equals', '1'),
				'type' => 'text',
				'title' => esc_html__('Twitter', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Sub Footer Social Networks', 'classiadspro'),
				'default' => '',
			),

			array(
				'id' => 'social-rss',
				'required' => array('sub-footer', 'equals', '1'),
				'type' => 'text',
				'title' => esc_html__('RSS', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Sub Footer Social Networks', 'classiadspro'),
				'default' => '',
			),

			array(
				'id' => 'social-dribbble',
				'required' => array('sub-footer', 'equals', '1'),
				'type' => 'text',
				'title' => esc_html__('Dribbble', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Sub Footer Social Networks', 'classiadspro'),
				'default' => '',
			),

			array(
				'id' => 'social-pinterest',
				'required' => array('sub-footer', 'equals', '1'),
				'type' => 'text',
				'title' => esc_html__('Pinterest', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Sub Footer Social Networks', 'classiadspro'),
				'default' => '',
			),

			array(
				'id' => 'social-instagram',
				'required' => array('sub-footer', 'equals', '1'),
				'type' => 'text',
				'title' => esc_html__('Instagram', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Sub Footer Social Networks', 'classiadspro'),
				'default' => '',
			),

			array(
				'id' => 'social-google-plus',
				'required' => array('sub-footer', 'equals', '1'),
				'type' => 'text',
				'title' => esc_html__('Google Plus', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Sub Footer Social Networks', 'classiadspro'),
				'default' => '',
			),

			array(
				'id' => 'social-linkedin',
				'required' => array('sub-footer', 'equals', '1'),
				'type' => 'text',
				'title' => esc_html__('Linkedin', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Sub Footer Social Networks', 'classiadspro'),
				'default' => '',
			),

			array(
				'id' => 'social-youtube',
				'required' => array('sub-footer', 'equals', '1'),
				'type' => 'text',
				'title' => esc_html__('Youtube', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Sub Footer Social Networks', 'classiadspro'),
				'default' => '',
			),

			array(
				'id' => 'social-vimeo',
				'required' => array('sub-footer', 'equals', '1'),
				'type' => 'text',
				'title' => esc_html__('Vimeo', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Sub Footer Social Networks', 'classiadspro'),
				'default' => '',
			),

			array(
				'id' => 'social-spotify',
				'required' => array('sub-footer', 'equals', '1'),
				'type' => 'text',
				'title' => esc_html__('Spotify', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Sub Footer Social Networks', 'classiadspro'),
				'default' => '',
			),

			array(
				'id' => 'social-tumblr',
				'required' => array('sub-footer', 'equals', '1'),
				'type' => 'text',
				'title' => esc_html__('Tumblr', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Sub Footer Social Networks', 'classiadspro'),
				'default' => '',
			),

			array(
				'id' => 'social-behance',
				'required' => array('sub-footer', 'equals', '1'),
				'type' => 'text',
				'title' => esc_html__('Behance', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Sub Footer Social Networks', 'classiadspro'),
				'default' => '',
			),
			array(
				'id' => 'social-whatsapp',
				'required' => array('sub-footer', 'equals', '1'),
				'type' => 'text',
				'title' => esc_html__('WhatsApp', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Sub Footer Social Networks', 'classiadspro'),
				'default' => '',
			),
			array(
				'id' => 'social-wechat',
				'required' => array('sub-footer', 'equals', '1'),
				'type' => 'text',
				'title' => esc_html__('Wechat', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Sub Footer Social Networks', 'classiadspro'),
				'default' => '',
			),
			array(
				'id' => 'social-qzone',
				'required' => array('sub-footer', 'equals', '1'),
				'type' => 'text',
				'title' => esc_html__('qzone', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Sub Footer Social Networks', 'classiadspro'),
				'default' => '',
			),
			array(
				'id' => 'social-vkcom',
				'required' => array('sub-footer', 'equals', '1'),
				'type' => 'text',
				'title' => esc_html__('vk.com', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Sub Footer Social Networks', 'classiadspro'),
				'default' => '',
			),
			array(
				'id' => 'social-imdb',
				'required' => array('sub-footer', 'equals', '1'),
				'type' => 'text',
				'title' => esc_html__('IMDb', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Sub Footer Social Networks', 'classiadspro'),
				'default' => '',
			),
			array(
				'id' => 'social-renren',
				'required' => array('sub-footer', 'equals', '1'),
				'type' => 'text',
				'title' => esc_html__('Renren', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Sub Footer Social Networks', 'classiadspro'),
				'default' => '',
			),
			array(
				'id' => 'social-weibo',
				'required' => array('sub-footer', 'equals', '1'),
				'type' => 'text',
				'title' => esc_html__('Weibo', 'classiadspro'),
				'desc' => esc_html__('Including http://', 'classiadspro'),
				'subtitle' => esc_html__('Sub Footer Social Networks', 'classiadspro'),
				'default' => '',
			),

		),
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Sidebar Widgets', 'classiadspro'),
		'icon' => 'el-icon-font',
		'fields' => array(
			array(
				'id' => 'widget-title',
				'type' => 'typography',
				'title' => esc_html__('Widgets Title', 'classiadspro'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => false, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => true, // Only appears if google is true and subsets not set to false
				'font-size' => true,
				'line-height' => false,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => false, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'subtitle' => esc_html__('This will apply to all widget areas title including footer, sidebar and side dashboard', 'classiadspro'),
				'default' => array(
					'font-family' => '',
					'google' => true,
					'font-size' => '14px',
					'font-weight' => 'bold',
				),
			),
			array(
				'id' => 'sidebar-title-color',
				'type' => 'color',
				'title' => esc_html__('Sidebar Widget Title', 'classiadspro'),
				'default' => '#333333',
				'validate' => 'color',
			),
			array(
				'id' => 'sidebar-txt-color',
				'type' => 'color',
				'title' => esc_html__('Sidebar Widget Texts', 'classiadspro'),
				'subtitle' => esc_html__('Will affect all texts in sidebar widget (unless there is a color value for the specific option in theme styles)', 'classiadspro'),
				'default' => '#777777',
				'validate' => 'color',
			),
			array(
				'id' => 'sidebar-link-color',
				'type' => 'link_color',
				'title' => esc_html__('Sidebar Widget Links', 'classiadspro'),
				'subtitle' => esc_html__('Will affect all links in sidebar section.', 'classiadspro'),
				'regular' => true,
				'hover' => true,
				'default' => array(
					'regular' => '#555555',
					'hover' => '#444444',
				)
			),
			array(
				'id' => 'sidebar-widget-background-color',
				'type' => 'color',
				'title' => esc_html__('Sidebar Widge Backgroud', 'classiadspro'),
				'default' => '#ffffff',
				'validate' => 'color',
			),
			array(
				'id'       => 'sidebar-widget-border',
				'type'     => 'border',
				'title'    => esc_html__('Sidebar Widge Border', 'classiadspro'),
				//'subtitle' => esc_html__('Only color validation can be done on this field type', 'classiadspro'),
				//'output'   => array('.site-header'),
				//'desc'     => esc_html__('This is the description field, again good for additional info.', 'classiadspro'),
				'default'  => array(
					'border-color'  => '', 
					'border-style'  => 'solid', 
					'border-top'    => '', 
					'border-right'  => '', 
					'border-bottom' => '', 
					'border-left'   => ''
				)
			),
			array(
				'id'       => 'sidebar-widget-box-shadow',
				'type'     => 'box_shadow',
				'inset-shadow' => false,
				//'checked' => false,
				//'output'   => array( '.site-header' ),
				'title'       => esc_html__( 'Widget Box Shadow', 'classiadspro' ),
				'default' => array(
					'drop-shadow'  => array(
						'checked'    => true,
						'color'      => '',
						'horizontal' => 0,
						'vertical'   => 0,
						'blur'       => 0,
						'spread'     => 0,
					)
				),
				//'subtitle'    => esc_html__( 'Site Header Box Shadow with inset and drop shadows.', 'classiadspro' ),
				//'desc'        => esc_html__( 'This is the description field, again good for additional info.', 'classiadspro' ),
			),
			array(
				'id'        => 'sidebar-widget-border-radius',
				'type'      => 'slider',
				'title'     => esc_html__('Widget Border Radius', 'classiadspro'),
				'desc'        => esc_html__( 'value is in pixel', 'classiadspro' ),
				"default"   => 4,
				"min"       => 0,
				"step"      => 1,
				"max"       => 50,
				'display_value' => 'label'
			)
		),
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Typography and Skin', 'classiadspro'),
		'icon' => 'el-icon-font',
		'fields' => array(
			array(
				'id' => 'body-font',
				'type' => 'typography',
				'title' => esc_html__('Body Font', 'classiadspro'),
				'compiler' => true, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => true, // Select a backup non-google font in addition to a google font
				'font-style' => false, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => true, // Only appears if google is true and subsets not set to false
				'font-size' => true,
				'line-height' => false,
				'color' => false,
				'preview' => true, // Disable the previewer
				'all_styles' => false, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'subtitle' => esc_html__('Choose your body font properties.', 'classiadspro'),
				'default' => array(
					'font-family' => 'Montserrat',
					'google' => true,
					'font-size' => '14px',
				),
			),
			array(
				'id' => 'heading-font',
				'type' => 'typography',
				'title' => esc_html__('Headings Font H1', 'classiadspro'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => false, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => true, // Only appears if google is true and subsets not set to false
				'font-size' => false,
				'line-height' => false,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => false, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'subtitle' => esc_html__('Choose your Heading fonts properties. <br>(will affect H1)', 'classiadspro'),
				'default' => array(
					'font-family' => 'Montserrat',
					'google' => true,
					'font-weight' => '700',
				),
			),
			array(
				'id' => 'heading-font-h2',
				'type' => 'typography',
				'title' => esc_html__('Headings Font H2', 'classiadspro'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => false, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => true, // Only appears if google is true and subsets not set to false
				'font-size' => false,
				'line-height' => false,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => false, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'subtitle' => esc_html__('Choose your Heading fonts properties. <br>(will affect H2)', 'classiadspro'),
				'default' => array(
					'font-family' => $heading_font_family,
					'google' => true,
					'font-weight' => '700',
				),
			),
			array(
				'id' => 'heading-font-h3',
				'type' => 'typography',
				'title' => esc_html__('Headings Font H3', 'classiadspro'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => false, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => true, // Only appears if google is true and subsets not set to false
				'font-size' => false,
				'line-height' => false,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => false, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'subtitle' => esc_html__('Choose your Heading fonts properties. <br>(will affect H3)', 'classiadspro'),
				'default' => array(
					'font-family' => $heading_font_family,
					'google' => true,
					'font-weight' => '700',
				),
			),
			array(
				'id' => 'heading-font-h4',
				'type' => 'typography',
				'title' => esc_html__('Headings Font H4', 'classiadspro'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => false, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => true, // Only appears if google is true and subsets not set to false
				'font-size' => false,
				'line-height' => false,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => false, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'subtitle' => esc_html__('Choose your Heading fonts properties. <br>(will affect H4)', 'classiadspro'),
				'default' => array(
					'font-family' => $heading_font_family,
					'google' => true,
					'font-weight' => '700',
				),
			),
			array(
				'id' => 'heading-font-h5',
				'type' => 'typography',
				'title' => esc_html__('Headings Font H5', 'classiadspro'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => false, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => true, // Only appears if google is true and subsets not set to false
				'font-size' => false,
				'line-height' => false,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => false, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'subtitle' => esc_html__('Choose your Heading fonts properties. <br>(will affect H5)', 'classiadspro'),
				'default' => array(
					'font-family' => $heading_font_family,
					'google' => true,
					'font-weight' => '700',
				),
			),
			array(
				'id' => 'heading-font-h6',
				'type' => 'typography',
				'title' => esc_html__('Headings Font H6', 'classiadspro'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => false, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => true, // Only appears if google is true and subsets not set to false
				'font-size' => false,
				'line-height' => false,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => false, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'subtitle' => esc_html__('Choose your Heading fonts properties. <br>(will affect H6)', 'classiadspro'),
				'default' => array(
					'font-family' => $heading_font_family,
					'google' => true,
					'font-weight' => '700',
				),
			),
			array(
				'id' => 'headings_font_family',
				'type' => 'typography',
				'title' => esc_html__('Headings Font', 'classiadspro'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => false, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => true, // Only appears if google is true and subsets not set to false
				'font-size' => false,
				'line-height' => false,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'subtitle' => esc_html__('Choose your Heading fonts properties. <br>(will affect all headings otherthan h1 to h6)', 'classiadspro'),
				'default' => array(
					'font-family' => '',
					'google' => true,
					'font-weight' => '',
				),
			),
			array(
				'id' => 'buttons_font_family',
				'type' => 'typography',
				'title' => esc_html__('Buttons Font', 'classiadspro'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => false, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => true, // Only appears if google is true and subsets not set to false
				'font-size' => false,
				'line-height' => false,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'subtitle' => esc_html__('(will affect all buttons)', 'classiadspro'),
				'default' => array(
					'font-family' => '',
					'google' => true,
					'font-weight' => '',
				),
			),

			array(
				'id' => 'page-title-size',
				'type' => 'slider',
				'title' => esc_html__('Page Title Text Size', 'classiadspro'),
				"default" => "18",
				"min" => "12",
				"step" => "1",
				"max" => "100",
			),
			array(
				'id' => 'p-text-size',
				'type' => 'slider',
				'title' => esc_html__('Paragraph Text Size', 'classiadspro'),
				"default" => "14",
				"min" => "12",
				"step" => "1",
				"max" => "100",
			),
			array(
				'id' => 'p-line-height',
				'type' => 'slider',
				'title' => esc_html__('Paragraph Line Height', 'classiadspro'),
				"default" => "28",
				"min" => "12",
				"step" => "1",
				"max" => "100",
			),
			array(
				'id' => 'footer-p-text-size',
				'type' => 'slider',
				'title' => esc_html__('Footer Paragraph Text Size', 'classiadspro'),
				"default" => "12",
				"min" => "12",
				"step" => "1",
				"max" => "100",
			),
			array(
				'id' => 'typekit-id',
				'type' => 'text',
				'title' => esc_html__('Typekit Kit ID', 'classiadspro'),
				'desc' => esc_html__("If you want to use typekit in your site simply enter The Type Kit ID you get from Typekit site.", 'classiadspro'). __("<a target='_blank' href='http://help.typekit.com/customer/portal/articles/6840-using-typekit-with-wordpress-com'>Read More</a>", "classiadspro"),
				'default' => '',
			),
			array(
				'id' => 'typekit-info',
				'type' => 'info',
				'style' => 'warning',
				'desc' => esc_html__("Note: Adobe Typekit is a premium service", 'classiadspro'). __(" ", 'classiadspro'),
				'default' => '',
			),
			array(
				'id' => 'typekit-font-family',
				'type' => 'text',
				'title' => esc_html__('Choose a Typekit Font', 'classiadspro'),
				'desc' => esc_html__("Type the name of the font family you have picked from typekit library.", 'classiadspro'),
				'default' => '',
			),
			array(
				'id' => 'typekit-element-names',
				'type' => 'text',
				'title' => esc_html__('Add Typekit Elements Class Names.', 'classiadspro'),
				'desc' => esc_html__("Add class names you want the Typekit apply the above font family. Add Class, ID or tag names (e.g. : body, p, #custom-id, .custom-class).", 'classiadspro'),
				'default' => '',
			),
			array(
				'id' => 'accent-color',
				'type' => 'color',
				'title' => esc_html__('Accent Color', 'classiadspro'),
				'subtitle' => esc_html__('Main color scheme. Choose a vivid and bold color.', 'classiadspro'),
				'default' => '#c32026',
				'validate' => 'color',
			),
			array(
				'id' => 'secondary-color',
				'type' => 'color',
				'title' => esc_html__('Secondary Color', 'classiadspro'),
				'subtitle' => esc_html__('used in cobinatin with primary color at multiple points.', 'classiadspro'),
				'default' => '',
				'validate' => 'color',
			),
			array(
				'id' => 'third-color',
				'type' => 'color',
				'title' => esc_html__('Third Color', 'classiadspro'),
				'subtitle' => esc_html__('mostly used for button with primary and secondary colors.', 'classiadspro'),
				'default' => '',
				'validate' => 'color',
			),
			array(
				'id' => 'body-txt-color',
				'type' => 'color',
				'title' => esc_html__('Body text Color', 'classiadspro'),
				'subtitle' => esc_html__('Will affect all texts if no color is defined for them.', 'classiadspro'),
				'default' => '#777',
				'validate' => 'color',
			),
			array(
				'id' => 'heading-color',
				'type' => 'color',
				'title' => esc_html__('Headings Color', 'classiadspro'),
				'subtitle' => esc_html__('Will affect all headings (h1,h2,h3,h4,h5,h6)', 'classiadspro'),
				'default' => '#333333',
				'validate' => 'color',
			),
			array(
				'id' => 'link-color',
				'type' => 'link_color',
				'title' => esc_html__('Links Color', 'classiadspro'),
				'subtitle' => esc_html__('Will affect all links color.', 'classiadspro'),
				'regular' => true,
				'hover' => true,
				'default' => array(
					'regular' => '#555',
					'hover' => '#444444',
				)
			),
			array(
				'id' => 'btn-hover',
				'type' => 'color',
				'title' => esc_html__('Buttons hover color', 'classiadspro'),
				'subtitle' => esc_html__('setting will effect all dynamic buttons', 'classiadspro'),
				'default' => '#3e5c92',
				'validate' => 'color',
			),
			array(
				'id' => 'subs-btn-hover',
				'type' => 'color',
				'title' => esc_html__('Subscription Button hover color', 'classiadspro'),
				'subtitle' => esc_html__('setting will effect all dynamic buttons', 'classiadspro'),
				'default' => '#3e5c92',
				'validate' => 'color',
			),
			array(
				'id' => 'breadcrumb-skin',
				'type' => 'select',
				'title' => esc_html__('Breadcrumb Skin', 'classiadspro'),
				'options' => array(
					'light' => 'Light',
					'custom' => 'Custom',
				),
				'default' => 'light',
			),
			array(
				'id' => 'breadcrumb-skin-custom',
				'type' => 'nav_color',
				'title' => esc_html__('Breadcrumb Custom Skin Color', 'classiadspro'),
				'regular' => true,
				'hover' => true,
				'default' => array(
					'regular' => '#ffffff',
					'hover' => '#ffffff'
				)
			),

			array(
				'id' => 'custom-css',
				'type' => 'ace_editor',
				'title' => esc_html__('Custom CSS', 'classiadspro'),
				'subtitle' => esc_html__('Add some quick css into this box.', 'classiadspro'),
				'desc' => esc_html__('For larger scale css modifications use custom.css file in theme root or consider using a child theme.', 'classiadspro'),
				'mode' => 'css',
				'theme' => 'monokai',
				'default' => "",
			),
			array(
				'id' => 'custom-js',
				'type' => 'ace_editor',
				'title' => esc_html__('Custom JS', 'classiadspro'),
				'subtitle' => esc_html__('Script will be placed in an script tag in document footer', 'classiadspro'),
				'mode' => 'javascript',
				'theme' => 'chrome',
				'desc' => 'For larger scale css modifications js custom.js file in theme root or consider using a child theme.',
				'default' => "",
			),
		),
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('PreLoader', 'classiadspro'),
		'icon' => 'el-icon-brush',
		'fields' => array(
			array(
				'id' => 'preloader-bg-color',
				'type' => 'color',
				'title' => esc_html__('Preloader Overlay Backgroud Color', 'classiadspro'),
				'subtitle' => esc_html__('Will affect global site preloader Background color.', 'classiadspro'),
				'default' => '#fff',
				'validate' => 'color',
			),
			array(
				'id' => 'preloader-logo',
				'type' => 'media',
				'url' => false,
				'title' => esc_html__('PreLoader Image', 'classiadspro'),
				'mode' => false,
				'desc' => esc_html__('Can be a gif,png or jpg image', 'classiadspro'),
				'subtitle' => esc_html__('Image size is up to you.', 'classiadspro'),
				'default' => false,
			),
		)
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Blog', 'classiadspro'),
		'icon' => 'el-icon-pencil',
		'fields' => array(
			array(
				'id' => 'page-title-blog',
				'type' => 'switch',
				'title' => esc_html__('Page Title : Blog Posts', 'classiadspro'),
				'subtitle' => esc_html__('This option will affect Blog single posts.', 'classiadspro'),
				'desc' => esc_html__('If you don\'t want to show page title section (title, breadcrumb) in blog single posts disable this option.', 'classiadspro'),
				"default" => 1,
				'on' => 'Enable',
				'off' => 'Disable',
			),
			array(
				'id' => 'blog-featured-image',
				'type' => 'switch',
				'title' => esc_html__('Blog Single Featured image, audio, video ', 'classiadspro'),
				'subtitle' => esc_html__('Will completely disable Featued Image, Video and Audio players from blog single post.', 'classiadspro'),
				"default" => 1,
				'on' => 'Enable',
				'off' => 'Disable',
			),

			array(
				'id' => 'blog-image-crop',
				'type' => 'switch',
				'title' => esc_html__('Featured image hard cropping', 'classiadspro'),
				'subtitle' => esc_html__('This option will affect single blog post featrued image.', 'classiadspro'),
				'desc' => esc_html__('If you want to disable automatic image cropping for featured image, disable this option. The original image size will be used. However it will be responsive and fit to container.', 'classiadspro'),
				"default" => 1,
				'on' => 'Enable',
				'off' => 'Disable',
			),

			array(
				'id' => 'blog-single-image-height',
				'required' => array('blog-image-crop', 'equals', '1'),
				'type' => 'slider',
				'title' => esc_html__('Single Post Featured Image Height', 'classiadspro'),
				'subtitle' => esc_html__('This height applies to featured image and gallery post type slideshow..', 'classiadspro'),
				"default" => "380",
				"min" => "100",
				"step" => "1",
				"max" => "1000",
			),
			array(
				'id' => 'blog-grid-image-width',
				'required' => array('blog-image-crop', 'equals', '1'),
				'type' => 'slider',
				'title' => esc_html__('Blog Post Grid Thumbnail Image Width', 'classiadspro'),
				'subtitle' => esc_html__('This height applies to thumbanil image', 'classiadspro'),
				"default" => "370",
				"min" => "100",
				"step" => "1",
				"max" => "1000",
			),
			array(
				'id' => 'blog-grid-image-height',
				'required' => array('blog-image-crop', 'equals', '1'),
				'type' => 'slider',
				'title' => esc_html__('Blog Post Grid Thumbnail Image Height', 'classiadspro'),
				'subtitle' => esc_html__('This height applies to thumbanil image', 'classiadspro'),
				"default" => "230",
				"min" => "100",
				"step" => "1",
				"max" => "1000",
			),
			array(
				'id' => 'blog-single-about-author',
				'type' => 'switch',
				'title' => esc_html__('About Author Section', 'classiadspro'),
				"default" => 1,
				'on' => 'Enable',
				'off' => 'Disable',
			),

			array(
				'id' => 'blog-single-social-share',
				'type' => 'switch',
				'title' => esc_html__('Blog Single Social Share', 'classiadspro'),
				"default" => 1,
				'on' => 'Enable',
				'off' => 'Disable',
			),

			array(
				'id' => 'blog-single-comments',
				'type' => 'switch',
				'title' => esc_html__('Comments', 'classiadspro'),
				"default" => 1,
				'on' => 'Enable',
				'off' => 'Disable',
			),

			array(
				'id' => 'archive-layout',
				'type' => 'image_select',
				'title' => esc_html__('Archive Layout', 'classiadspro'),
				'subtitle' => esc_html__('Defines archive loop layout.', 'classiadspro'),
				'options' => array(
					'left' => array('alt' => '1 Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/left_layout.png'),
					'right' => array('alt' => '2 Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/right_layout.png'),
					'full' => array('alt' => '3 Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/full_layout.png'),
				),
				'default' => 'right'
			),
			array(
				'id' => 'archive-columns',
				'type' => 'slider',
				'title' => esc_html__('Archive columns', 'classiadspro'),
				'subtitle' => esc_html__('Defines archive loop layout.', 'classiadspro'),
				'min' => '1',
				'max' => '4',
				'default' => '2'
			),
			array(
				'id' => 'archive-loop-style',
				'type' => 'select',
				'title' => esc_html__('Archive Loop Style', 'classiadspro'),
				'options' => array(
					'classic' => esc_html__('Classic', 'classiadspro'),
					'grid' => esc_html__('Grid', 'classiadspro'),
					'tile' => esc_html__('Tile', 'classiadspro'),
					'tile_elegant' => esc_html__('Tile Elegant', 'classiadspro'),
					'tile_mod' => esc_html__('Tile Mod', 'classiadspro'),
				),
				'default' => 'classic',
			),
			array(
				'id' => 'archive-page-title',
				'type' => 'switch',
				'title' => esc_html__('Archive Loop Page Title', 'classiadspro'),
				'subtitle' => esc_html__('Using this option you can enable/disable page title section (including breadcrumbs)', 'classiadspro'),
				"default" => 1,
				'on' => 'Enable',
				'off' => 'Disable',
			),
			array(
				'id' => 'single-post-content-box-background',
				'type' => 'color',
				'title' => esc_html__('Single Post content area background', 'classiadspro'),
				'default' => '#ffffff',
				'validate' => 'color',
			),
			array(
				'id' => 'single-post-comments-box-background',
				'type' => 'color',
				'title' => esc_html__('Single Post Comments background', 'classiadspro'),
				'default' => '#ffffff',
				'validate' => 'color',
			),
			array(
				'id'       => 'single-post-content-box-border',
				'type'     => 'border',
				'title'    => esc_html__('Sidebar Widge Border', 'classiadspro'),
				'default'  => array(
					'border-color'  => '', 
					'border-style'  => 'solid', 
					'border-top'    => '', 
					'border-right'  => '', 
					'border-bottom' => '', 
					'border-left'   => ''
				)
			),
			array(
				'id'       => 'single-post-content-box-shadow',
				'type'     => 'box_shadow',
				'inset-shadow' => false,
				//'checked' => false,
				//'output'   => array( '.site-header' ),
				'title'       => esc_html__( 'Widget Box Shadow', 'classiadspro' ),
				'default' => array(
					'drop-shadow'  => array(
						'checked'    => true,
						'color'      => '',
						'horizontal' => 0,
						'vertical'   => 0,
						'blur'       => 0,
						'spread'     => 0,
					)
				),
				//'subtitle'    => esc_html__( 'Site Header Box Shadow with inset and drop shadows.', 'classiadspro' ),
				//'desc'        => esc_html__( 'This is the description field, again good for additional info.', 'classiadspro' ),
			),
			array(
				'id'        => 'single-post-content-box-border-radius',
				'type'      => 'slider',
				'title'     => esc_html__('Widget Border Radius', 'classiadspro'),
				'desc'        => esc_html__( 'value is in pixel', 'classiadspro' ),
				"default"   => 4,
				"min"       => 0,
				"step"      => 1,
				"max"       => 50,
				'display_value' => 'label'
			)
		),
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Woocommerce', 'classiadspro'),
		'icon' => 'el-icon-shopping-cart',
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Woocommerce Layout', 'classiadspro'),
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'woo-shop-layout',
				'type' => 'image_select',
				'title' => esc_html__('Shop Layout', 'classiadspro'),
				'subtitle' => esc_html__('Defines shop loop layout.', 'classiadspro'),
				'options' => array(
					'left' => array('alt' => '1 Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/left_layout.png'),
					'right' => array('alt' => '2 Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/right_layout.png'),
					'full' => array('alt' => '3 Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/full_layout.png'),
				),
				'default' => 'right'
			),
			array(
				'id' => 'woo-shop-columns',
				'type' => 'slider',
				'title' => esc_html__('Shop Columns', 'classiadspro'),
				'subtitle' => esc_html__('Defines SHOP loop COLUMNS.', 'classiadspro'),
				'min' => '1',
				'max' => '6',
				'default' => '3'
			),
			array(
				'id' => 'woo-loop-thumb-height',
				'type' => 'slider',
				'title' => esc_html__('Product Loop Image Height', 'classiadspro'),
				'subtitle' => esc_html__('Using this option you can change the product loop image height.', 'classiadspro'),
				'desc' => esc_html__('default : 330', 'classiadspro'),
				"default" => "330",
				"min" => "100",
				"step" => "1",
				"max" => "1000",
			),
		    array(
		        "title" => esc_html__("Shop Loop Image Size", 'classiadspro'),
		        "id" => "woo_loop_image_size",
		        "default" => "crop",
		        "options" => array(
		            "crop" => esc_html__("Resize & Crop", 'classiadspro'),
		            "full" => esc_html__("Original Size", 'classiadspro'),
		            "large" => esc_html__("Large Size", 'classiadspro'),
		            "medium" => esc_html__("Medium Size", 'classiadspro'),
		        ),
		        "type" => "select"
		    ),
			array(
				'id' => 'woo-single-thumb-height',
				'type' => 'slider',
				'title' => esc_html__('Single Product Image Height', 'classiadspro'),
				'subtitle' => esc_html__('Using this option you can change the single product image height.', 'classiadspro'),
				'desc' => esc_html__('default : 400', 'classiadspro'),
				"default" => "400",
				"min" => "100",
				"step" => "1",
				"max" => "1000",
			),
		    array(
		        "title" => esc_html__("Shop Single Product Image Size", 'classiadspro'),
		        "id" => "woo_single_image_size",
		        "default" => "crop",
		        "options" => array(
		            "crop" => esc_html__("Resize & Crop", 'classiadspro'),
		            "full" => esc_html__("Original Size", 'classiadspro'),
		            "large" => esc_html__("Large Size", 'classiadspro'),
		            "medium" => esc_html__("Medium Size", 'classiadspro'),
		        ),
		        "type" => "select"
		    ),
			array(
				'id' => 'woo-single-layout',
				'type' => 'image_select',
				'title' => esc_html__('Single Layout', 'classiadspro'),
				'subtitle' => esc_html__('Defines shop single product layout.', 'classiadspro'),
				'options' => array(
					'left' => array('alt' => '1 Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/left_layout.png'),
					'right' => array('alt' => '2 Column', 'img' => PACZ_THEME_ADMIN_ASSETS_URI . '/img/right_layout.png'),
					'full' => array('alt' => '3 Column', 'img' =>  PACZ_THEME_ADMIN_ASSETS_URI . '/img/full_layout.png'),
				),
				'default' => 'right'
			),
			array(
				'id' => 'woo-single-related-columns',
				'type' => 'slider',
				'title' => esc_html__('Single Product Related Columns', 'classiadspro'),
				'subtitle' => esc_html__('Defines sHOP loop COLUMNS.', 'classiadspro'),
				'min' => '1',
				'max' => '4',
				'default' => '3'
			),
			array(
				'id' => 'woo-image-quality',
				'type' => 'button_set',
				'title' => esc_html__('Product Loops image quality', 'classiadspro'),
				'options' => array('1' => 'Normal Size', '2' => 'Retina Compatible'), //Must provide key => value pairs for radio options
				'default' => '1'
			),
			array(
				'id' => 'woo-single-title',
				'type' => 'switch',
				'title' => esc_html__('Show Product Category as Product Single Title.', 'classiadspro'),
				'subtitle' => esc_html__('If you want to show product category(if multiple only first will be used) as single product page title enable this option. having this option disabled shop page title will be used.', 'classiadspro'),
				"default" => 1,
				'on' => 'Enable',
				'off' => 'Disable',
			),
			array(
				'id' => 'woo-single-show-title',
				'type' => 'switch',
				'title' => esc_html__('Woocommerce Single Product Page Title', 'classiadspro'),
				'subtitle' => esc_html__('Using this option you can disable/enable single product page title (including breadcrumbs).', 'classiadspro'),
				"default" => 1,
				'on' => 'Enable',
				'off' => 'Disable',
			),
			array(
				'id' => 'woo-shop-loop-title',
				'type' => 'switch',
				'title' => esc_html__('Woocommerce Shop Loop Page Title', 'classiadspro'),
				'subtitle' => esc_html__('Using this option you can disable/enable Shop product Loop title (including breadcrumbs).', 'classiadspro'),
				"default" => 1,
				'on' => 'Enable',
				'off' => 'Disable',
			),
			array(
				'id' => 'woo-bg',
				'type' => 'background',
				'title' => esc_html__('woocommerce pages Background', 'classiadspro'),
				'preset' => false,
				'default' => array(
					'background-image' => '',
					'background-color' => '#f2f2f2',
					'background-position' => '',
					'background-repeat' => 'repeat',
					'background-attachment' => 'scroll',
					'background-size' => '',
				)
			),
		),
	));
	Redux::setSection( $opt_name, array(
		'title' => esc_html__('Woocommerce Styling', 'classiadspro'),
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'pacz-woo-loop-product_title',
				'type' => 'typography',
				'title' => esc_html__('Product Title Typography', 'classiadspro'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => false, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => false, // Only appears if google is true and subsets not set to false
				'font-size' => true,
				'line-height' => true,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => false, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'subtitle' => esc_html__('Choose your typography for the product title', 'classiadspro'),
				'default' => array(
					'font-family' => 'Montserrat',
					'google' => true,
					'font-weight' => '700',
				),
			),
			// title
			array(
				'id' => 'pacz-woo-loop-product_title-color',
				'type' => 'color',
				'title' => esc_html__('Product Title Color', 'classiadspro'),
				'default' => '#222',
				'validate' => 'color',
			),
			array(
				'id' => 'pacz-woo-loop-product_title-color-hover',
				'type' => 'color',
				'title' => esc_html__('Product Title Color Hover', 'classiadspro'),
				'default' => '',
				'validate' => 'color',
			),
			// Category
			array(
				'id' => 'pacz-woo-loop-product_cat',
				'type' => 'typography',
				'title' => esc_html__('Product Category Typography', 'classiadspro'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => false, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => false, // Only appears if google is true and subsets not set to false
				'font-size' => true,
				'line-height' => true,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => false, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'subtitle' => esc_html__('Choose your typography for the product category', 'classiadspro'),
				'default' => array(
					'font-family' => 'Montserrat',
					'google' => true,
					'font-weight' => '700',
				),
			),
			array(
				'id' => 'pacz-woo-loop-product_cat-color',
				'type' => 'color',
				'title' => esc_html__('Product Category Color', 'classiadspro'),
				'default' => '#546b7e',
				'validate' => 'color',
			),
			
			// price 
			array(
				'id' => 'pacz-woo-loop-product_price',
				'type' => 'typography',
				'title' => esc_html__('Product Price Typography', 'classiadspro'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => false, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => false, // Only appears if google is true and subsets not set to false
				'font-size' => true,
				'line-height' => true,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => false, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'subtitle' => esc_html__('Choose your typography for the product price', 'classiadspro'),
				'default' => array(
					'font-family' => 'Montserrat',
					'google' => true,
					'font-weight' => '700',
				),
			),
			array(
				'id' => 'pacz-woo-loop-product_price-color',
				'type' => 'color',
				'title' => esc_html__('Product Price Color', 'classiadspro'),
				'default' => '#ef5d50',
				'validate' => 'color',
			),
			// sale tag
			array(
				'id' => 'pacz-woo-product_sale-tag-color',
				'type' => 'color',
				'title' => esc_html__('Product sale tag Color', 'classiadspro'),
				'default' => '#fff',
				'validate' => 'color',
			),
			array(
				'id' => 'pacz-woo-product_sale-tag-background-color',
				'type' => 'color',
				'title' => esc_html__('Product sale tag background Color', 'classiadspro'),
				'default' => '#0b93d7',
				'validate' => 'color',
			),
			// add to cart
			array(
				'id' => 'pacz-woo-product_addtocart-icon-color',
				'type' => 'color',
				'title' => esc_html__('Product add to cart icon Color', 'classiadspro'),
				'default' => '#8c969b',
				'validate' => 'color',
			),
			array(
				'id' => 'pacz-woo-product_addtocart-icon-color-hover',
				'type' => 'color',
				'title' => esc_html__('Product add to cart icon Color hover', 'classiadspro'),
				'default' => '#fff',
				'validate' => 'color',
			),
			array(
				'id' => 'pacz-woo-product_addtocart-background-color',
				'type' => 'color',
				'title' => esc_html__('Product add to cart background Color', 'classiadspro'),
				'default' => '#fff',
				'validate' => 'color',
			),
			array(
				'id' => 'pacz-woo-product_addtocart-background-color-hover',
				'type' => 'color',
				'title' => esc_html__('Product add to cart background Color Hover', 'classiadspro'),
				'default' => '#ef5d50',
				'validate' => 'color',
			),
			array(
				'id'       => 'pacz-woo-product_addtocart-border',
				'type'     => 'border',
				'title'    => esc_html__('Product add to cart border', 'classiadspro'),
				'default'  => array(
					'border-color'  => '#cfd9e0', 
					'border-style'  => 'solid', 
					'border-top'    => '', 
					'border-right'  => '', 
					'border-bottom' => '', 
					'border-left'   => ''
				)
			),
			array(
				'id'       => 'pacz-woo-product_addtocart-border-hover',
				'type'     => 'border',
				'title'    => esc_html__('Product add to cart border hover', 'classiadspro'),
				'default'  => array(
					'border-color'  => '', 
					'border-style'  => 'solid', 
					'border-top'    => '', 
					'border-right'  => '', 
					'border-bottom' => '', 
					'border-left'   => ''
				)
			),
			array(
				'id'        => 'pacz-woo-product_addtocart-border-radius',
				'type'      => 'slider',
				'title'     => esc_html__('Product Add to Cart Border Radius', 'classiadspro'),
				'desc'        => esc_html__( 'value is in pixel', 'classiadspro' ),
				"default"   => 4,
				"min"       => 0,
				"step"      => 1,
				"max"       => 50,
				'display_value' => 'label'
			),
			
			// Wishlist
			
			array(
				'id' => 'pacz-woo-product_wishlist-icon-color',
				'type' => 'color',
				'title' => esc_html__('Product wishlist icon Color', 'classiadspro'),
				'default' => '#8c969b',
				'validate' => 'color',
			),
			array(
				'id' => 'pacz-woo-product_wishlist-icon-color-hover',
				'type' => 'color',
				'title' => esc_html__('Product wishlist icon Color hover', 'classiadspro'),
				'default' => '#ef5d50',
				'validate' => 'color',
			),
			array(
				'id' => 'pacz-woo-product_wishlist-background-color',
				'type' => 'color',
				'title' => esc_html__('Product wishlist background Color', 'classiadspro'),
				'default' => '#fff',
				'validate' => 'color',
			),
			array(
				'id' => 'pacz-woo-product_wishlist-background-color-hover',
				'type' => 'color',
				'title' => esc_html__('Product wishlist background Color Hover', 'classiadspro'),
				'default' => '#fff',
				'validate' => 'color',
			),
			array(
				'id'       => 'pacz-woo-product_wishlist-border',
				'type'     => 'border',
				'title'    => esc_html__('Product wishlist border', 'classiadspro'),
				'default'  => array(
					'border-color'  => '', 
					'border-style'  => 'solid', 
					'border-top'    => '', 
					'border-right'  => '', 
					'border-bottom' => '', 
					'border-left'   => ''
				)
			),
			array(
				'id'       => 'pacz-woo-product_wishlist-border-hover',
				'type'     => 'border',
				'title'    => esc_html__('Product wishlist border hover', 'classiadspro'),
				'default'  => array(
					'border-color'  => '', 
					'border-style'  => 'solid', 
					'border-top'    => '', 
					'border-right'  => '', 
					'border-bottom' => '', 
					'border-left'   => ''
				)
			),
			array(
				'id'        => 'pacz-woo-product_wishlist-border-radius',
				'type'      => 'slider',
				'title'     => esc_html__('Product wishlist Border Radius', 'classiadspro'),
				'desc'        => esc_html__( 'value is in pixel', 'classiadspro' ),
				"default"   => 4,
				"min"       => 0,
				"step"      => 1,
				"max"       => 50,
				'display_value' => 'label'
			),
			
			// wrapper
			array(
				'id' => 'product-loop-wrapper-bg',
				'type' => 'color',
				'title' => esc_html__('Product Wrapper Background', 'classiadspro'),
				'default' => '#fff',
				'validate' => 'color',
			),
			array(
				'id' => 'product-loop-wrapper-bg-hover',
				'type' => 'color',
				'title' => esc_html__('Product Wrapper Background Hover', 'classiadspro'),
				'default' => '#fff',
				'validate' => 'color',
			),
			array(
				'id'       => 'product-loop-wrapper-border',
				'type'     => 'border',
				'title'    => esc_html__('Product Wrapper Border', 'classiadspro'),
				'default'  => array(
					'border-color'  => '#e2e5e7', 
					'border-style'  => 'solid', 
					'border-top'    => '', 
					'border-right'  => '', 
					'border-bottom' => '', 
					'border-left'   => ''
				)
			),
			array(
				'id'       => 'product-loop-wrapper-border-hover',
				'type'     => 'border',
				'title'    => esc_html__('Product Wrapper Border Hover', 'classiadspro'),
				'default'  => array(
					'border-color'  => '#e2e5e7', 
					'border-style'  => 'solid', 
					'border-top'    => '', 
					'border-right'  => '', 
					'border-bottom' => '', 
					'border-left'   => ''
				)
			),
			array(
				'id'        => 'product-loop-wrapper-border-radius',
				'type'      => 'slider',
				'title'     => esc_html__('Product Wrapper Border Radius', 'classiadspro'),
				'desc'        => esc_html__( 'value is in pixel', 'classiadspro' ),
				"default"   => 6,
				"min"       => 0,
				"step"      => 1,
				"max"       => 50,
				'display_value' => 'label'
			),
			array(
				'id'       => 'product-loop-wrapper-box-shadow',
				'type'    => 'box_shadow',
				'inset-shadow' => false,
				'checked' => true,
				'title'   => __('Product Wrapper Box Shadow', 'classiadspro'),
				//'output'  => ( '.header' ),
				'opacity' => true,
				'rgba'    => true,
				'default' => array (
					'drop-shadow'  => array(
						'checked'    => true,
						'color'      => '',
						'horizontal' => 0,
						'vertical'   => 0,
						'blur'       => 0,
						'spread'     => 0,
					)
				),
			),
			array(
				'id'       => 'product-loop-wrapper-box-shadow-hover',
				'type'    => 'box_shadow',
				'inset-shadow' => false,
				//'checked' => false,
				'title'   => __('Product Wrapper Box Shadow Hover', 'classiadspro'),
				//'output'  => ( '.header' ),
				'opacity' => true,
				'rgba'    => true,
				'default' => array (
					'drop-shadow'  => array(
						'checked'    => true,
						'color'      => '',
						'horizontal' => 0,
						'vertical'   => 0,
						'blur'       => 0,
						'spread'     => 0,
					)
				),
			),
			array(
				'id'             => 'product-loop-wrapper_padding',
				'type'           => 'spacing',
				'output'         => array(''),
				'mode'           => 'padding',
				'units'          => array( 'px', 'em' ),
				'units_extended' => 'false',
				'title'          => __('Product Wrapper Padding', 'classiadspro'),
				'desc' => __('you can set padding for each corner separately e.g (top =  top left, right = top right, bottom =  bottom right, left = bottom left )', 'classiadspro'),
				'default'            => array(
					'margin-top'     => '', 
					'margin-right'   => '', 
					'margin-bottom'  => '', 
					'margin-left'    => '',
					'units'          => 'px', 'em',
				)
			),
			
			// content area
			array(
				'id'             => 'product-loop-content_padding',
				'type'           => 'spacing',
				'output'         => array(''),
				'mode'           => 'padding',
				'units'          => array( 'px', 'em' ),
				'units_extended' => 'false',
				'title'          => __('Product content area Padding', 'classiadspro'),
				'desc' => __('you can set padding for each corner separately e.g (top =  top left, right = top right, bottom =  bottom right, left = bottom left )', 'classiadspro'),
				'default'            => array(
					'margin-top'     => '', 
					'margin-right'   => '', 
					'margin-bottom'  => '', 
					'margin-left'    => '',
					'units'          => 'px', 'em',
				)
			),
			
		),
		
	));
	
	
     Redux::setSection( $opt_name, array(
        'title'  => esc_html__( 'Import / Export', 'classiadspro' ),
        'desc'   => esc_html__( 'Import and Export your Redux Framework settings from file, text or URL.', 'classiadspro' ),
        'icon'   => 'el el-refresh',
        'fields' => array(
            array(
                'id'         => 'opt-import-export',
                'type'       => 'import_export',
                'title'      => 'Import Export',
                'subtitle'   => 'Save and restore your Redux options',
                'full_width' => false,
            ),
        ),
    ));
    Redux::setSection( $opt_name, array(
        'type' => 'divide',
    ));

    /*
     * <--- END SECTIONS
     */
	 

