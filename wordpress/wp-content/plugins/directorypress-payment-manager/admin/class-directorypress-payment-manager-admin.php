<?php

/**
 * @package    Directorypress_Payment_Manager
 * @subpackage Directorypress_Payment_Manager/admin
 * @author     Designinvento <developers@designinvento.net>
 */
class Directorypress_Payment_Manager_Admin {

	private $plugin_name;
	private $version;
	
	public function __construct() {
		global $directorypress_object;
		if(is_object( $directorypress_object)){
			$directorypress_object->packages_manager = new directorypress_packages_manager;
		}
		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'), 0);
		add_action('directorypress_after_general_settings', array($this, 'settings'), 10, 2);
	}
	
	public function enqueue_scripts() {
		
		wp_enqueue_script( 'dppm-admin-js', DPPM_URL . 'assets/js/directorypress-payment-manager-admin.js', array( 'jquery' ), false, false );

	}
	
	public function settings($redux, $opt_name) {
		if ( ! class_exists( 'Redux' ) ) {
			return;
		}
		$redux::setSection( $opt_name, array(
			'title' => __( 'Payment Manager Addon', 'directorypress-payment-manager' ),
			'id'    => 'payment_manager_addon',
			'icon'  => 'fas fa-money-check-alt',
		) );
		$redux::setSection( $opt_name, array(
			'title'      => __( 'Payment Settings', 'directorypress-payment-manager' ),
			'subsection' => true,
			'id'         => 'listing_addon_section',
			'fields' => array(
				array(
					'type' => 'select',
					'id' => 'directorypress_payments_addon',
					'title' => __('Payments addon', 'directorypress-payment-manager'),
					'desc' => __('Includes payments processing and invoices management functionality into directory/classifieds website.', 'directorypress-payment-manager'),
					"options" => array(
						'directorypress_woo_payment' => esc_html__('Woocomerce  Payment System', 'directorypress-payment-manager'),
						'directorypress_no_payment' => esc_html__('Disabled', 'directorypress-payment-manager'),
					),
					'default' => 'directorypress_no_payment'
				),
				array(
					'type' => 'switch',
					'id' => 'directorypress_payments_free_for_admins',
					'title' => __('Turn of payment for Administrator', 'directorypress-payment-manager'),
					'default' => false,			
				),
				array(
					'type' => 'switch',
					'id' => 'directorypress_woocommerce_frontend_links',
					'title' => __('Show WooCommerce Menus in front-end user panel', 'directorypress-payment-manager'),
					'default' => true,
					'required' => array('directorypress_payments_addon', 'equals', 'directorypress_woo_payment'),
				),
				array(
					'type' => 'switch',
					'id' => 'directorypress_woocommerce_enabled_subscriptions',
					'title' => __('On checkout page subscription enabled by default', 'directorypress-payment-manager'),
					'default' => true,
				),
			)
		) );
		$redux::setSection( $opt_name, array(
			'id' => 'Pricing',
			'title' => __('Pricing Plan', 'directorypress-payment-manager'),
			'subsection' => true,
			'fields' => array(
				array(
					'type' => 'select',
					'id' => 'directorypress_pricing_plan_style',
					'title' => __('Pricing Plan Style', 'directorypress-payment-manager'),
					'options' => apply_filters("directorypress_pricing_plan_style_option" , "directorypress_pricing_plan_styles_function"),
					'default' => 1,
				),
				array(
					'type' => 'switch',
					'id' => 'pp_option_has_sticky',
					'title' => __('Turn/Off has_sticky option on pricing plan', 'directorypress-payment-manager'),
					'default' => true,
				),
				array(
					'type' => 'switch',
					'id' => 'pp_option_has_featured',
					'title' => __('Turn/Off has_featured option on pricing plan', 'directorypress-payment-manager'),
					'default' => true,
				),
				array(
					'type' => 'switch',
					'id' => 'pp_option_resurva',
					'title' => __('Turn/Off resurva option on pricing plan', 'directorypress-payment-manager'),
					'default' => true,
				),
				array(
					'type' => 'switch',
					'id' => 'pp_option_map',
					'title' => __('Turn/Off Google map option on pricing plan', 'directorypress-payment-manager'),
					'default' => true,
				),
				array(
					'type' => 'switch',
					'id' => 'pp_option_category',
					'title' => __('Turn/Off category option on pricing plan', 'directorypress-payment-manager'),
					'default' => true,
				),
				array(
					'type' => 'switch',
					'id' => 'pp_option_locations',
					'title' => __('Turn/Off locations option on pricing plan', 'directorypress-payment-manager'),
					'default' => true,
				),
				array(
					'type' => 'switch',
					'id' => 'pp_option_images',
					'title' => __('Turn/Off images option on pricing plan', 'directorypress-payment-manager'),
					'default' => true,
				),
				array(
					'type' => 'switch',
					'id' => 'pp_option_video',
					'title' => __('Turn/Off videos option on pricing plan', 'directorypress-payment-manager'),
					'default' => true,
				),
				array(
					'type' => 'switch',
					'id' => 'pp_option_period',
					'title' => __('Turn/Off Ad period option on pricing plan', 'directorypress-payment-manager'),
					'default' => true,
				),
				array(
					'type' => 'switch',
					'id' => 'pp_option_steps',
					'title' => __('Turn/Off Steps option on pricing plan', 'directorypress-payment-manager'),
					'default' => false,
				),
				array(
					'type' => 'select',
					'id' => 'pp_option_col',
					'title' => __('Pricing plan columns', 'directorypress-payment-manager'),
					'options' => array(
						'1' => __('Style1', 'directorypress-payment-manager'),
						'2' => __('Style2', 'directorypress-payment-manager'),
						'3' => __('Style3', 'directorypress-payment-manager'),
						'4' => __('Style3', 'directorypress-payment-manager'),
					),
					'default' => 3,
				),
				
			),
		) );
		$redux::setSection( $opt_name, array(
			'id' => 'pricing-skin',
			'title' => __('Pricing Plan Styling', 'directorypress-payment-manager'),
			'subsection' => true,
			'fields' => array(
				array(
					'id' => 'pp_typo',
					'type' => 'typography',
					'title' => esc_html__('Pricing Plan Title', 'directorypress-payment-manager'),
					'compiler' => false, // Use if you want to hook in your own CSS compiler
					'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
					'font-backup' => false, // Select a backup non-google font in addition to a google font
					'font-style' => true, // Includes font-style and weight. Can use font-style or font-weight to declare
					'subsets' => false, // Only appears if google is true and subsets not set to false
					'font-size' => true,
					'line-height' => true,
					'color' => false,
					'preview' => false, // Disable the previewer
					'all_styles' => false, // Enable all Google Font style/weight variations to be added to the page
					'units' => 'px', // Defaults to px
					'default' => array(
						'font-family' => '',
						'google' => true,
						'font-size' => '',
						'font-weight' => '',
						'line-height' => '',
					),
				),
				array(
					'id' => 'pp_price_typo',
					'type' => 'typography',
					'title' => esc_html__('Pricing Plan Price Field Typography', 'directorypress-payment-manager'),
					'compiler' => false, // Use if you want to hook in your own CSS compiler
					'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
					'font-backup' => false, // Select a backup non-google font in addition to a google font
					'font-style' => true, // Includes font-style and weight. Can use font-style or font-weight to declare
					'subsets' => false, // Only appears if google is true and subsets not set to false
					'font-size' => true,
					'line-height' => true,
					'color' => false,
					'preview' => false, // Disable the previewer
					'all_styles' => false, // Enable all Google Font style/weight variations to be added to the page
					'units' => 'px', // Defaults to px
					'default' => array(
						'font-family' => '',
						'google' => true,
						'font-size' => '',
						'font-weight' => '',
						'line-height' => '',
					),
				),
				array(
					'id' => 'pp_list_typo',
					'type' => 'typography',
					'title' => esc_html__('Pricing Plan Feature List Typography', 'directorypress-payment-manager'),
					'compiler' => false, // Use if you want to hook in your own CSS compiler
					'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
					'font-backup' => false, // Select a backup non-google font in addition to a google font
					'font-style' => true, // Includes font-style and weight. Can use font-style or font-weight to declare
					'subsets' => false, // Only appears if google is true and subsets not set to false
					'font-size' => true,
					'line-height' => true,
					'color' => false,
					'preview' => false, // Disable the previewer
					'all_styles' => false, // Enable all Google Font style/weight variations to be added to the page
					'units' => 'px', // Defaults to px
					'default' => array(
						'font-family' => '',
						'google' => true,
						'font-size' => '',
						'font-weight' => '',
						'line-height' => '',
					),
				),
				array(
					'id' => 'pp_title_typo_transform',
					'type' => 'button_set',
					'title' => esc_html__('Pricing Plan Title Text Transform', 'directorypress-payment-manager'),
					'options' => array('uppercase' => 'Uppercase', 'capitalize' => 'Capitalize', 'lowercase' => 'Lower Case'), 
					'default' => 'capitalize',
				),
				array(
					'id' => 'pp_price_typo_transform',
					'type' => 'button_set',
					'title' => esc_html__('Pricing Plan Price Text Transform', 'directorypress-payment-manager'),
					'options' => array('uppercase' => 'Uppercase', 'capitalize' => 'Capitalize', 'lowercase' => 'Lower Case'), 
					'default' => 'capitalize',
				),
				array(
					'id' => 'pp_list_typo_transform',
					'type' => 'button_set',
					'title' => esc_html__('Pricing Plan Feature List Text Transform', 'directorypress-payment-manager'),
					'options' => array('uppercase' => 'Uppercase', 'capitalize' => 'Capitalize', 'lowercase' => 'Lower Case'), 
					'default' => 'capitalize',
				),
				array(
					'id' => 'pp_title_color',
					'type' => 'link_color',
					'title' => esc_html__('Pricing Plan title color', 'directorypress-payment-manager'),
					'regular' => true,
					'hover' => true,
					'bg' => false,
					'bg-hover' => false,
					'default' => array(
						'regular' => '',
						'hover' => '',
					)
				),
				array(
					'id' => 'pp_price_color',
					'type' => 'link_color',
					'title' => esc_html__('Pricing Plan price color', 'directorypress-payment-manager'),
					'regular' => true,
					'hover' => true,
					'bg' => false,
					'bg-hover' => false,
					'default' => array(
						'regular' => '',
						'hover' => '',
					)
				),
				array(
					'id' => 'pp_list_color',
					'type' => 'link_color',
					'title' => esc_html__('Pricing Plan feature list color', 'directorypress-payment-manager'),
					'regular' => true,
					'hover' => true,
					'bg' => false,
					'bg-hover' => false,
					'default' => array(
						'regular' => '',
						'hover' => '',
					)
				),
				array(
					'id' => 'pp_button_color',
					'type' => 'link_color',
					'title' => esc_html__('Pricing Plan Button color', 'directorypress-payment-manager'),
					'regular' => true,
					'hover' => true,
					'bg' => false,
					'bg-hover' => false,
					'default' => array(
						'regular' => '',
						'hover' => '',
					)
				),
				array(
					'id' => 'pp_check_icon_color',
					'type' => 'color_rgba',
					'title' => esc_html__('Pricing Plan feature list check icon color', 'directorypress-payment-manager'),
					'default' => '',
				),
				array(
					'id' => 'pp_remove_icon_color',
					'type' => 'color_rgba',
					'title' => esc_html__('Pricing Plan feature list cross icon color', 'directorypress-payment-manager'),
					'default' => '',
				),
				array(
					'id' => 'pp_wrapper_bg',
					'type' => 'color_rgba',
					'title' => esc_html__('Pricing Plan Wrapper background', 'directorypress-payment-manager'),
					'default' => '',
				),
				array(
					'id' => 'pp_wrapper_bg_hover',
					'type' => 'color_rgba',
					'title' => esc_html__('Pricing Plan Wrapper background on hover', 'directorypress-payment-manager'),
					'default' => '',
				),
				array(
					'id' => 'pp_list_bg',
					'type' => 'color_rgba',
					'title' => esc_html__('Pricing Plan List Item background', 'directorypress-payment-manager'),
					'default' => '',
				),
				array(
					'id' => 'pp_list_bg_hover',
					'type' => 'color_rgba',
					'title' => esc_html__('Pricing Plan List Item background on hover', 'directorypress-payment-manager'),
					'default' => '',
				),
				array(
					'id' => 'pp_wrapper_border_color',
					'type' => 'color_rgba',
					'title' => esc_html__('Pricing Plan Wrapper Border color', 'directorypress-payment-manager'),
					'default' => '',
				),
				array(
					'id' => 'pp_wrapper_border_color_hover',
					'type' => 'color_rgba',
					'title' => esc_html__('Pricing Plan Wrapper Border color on hover', 'directorypress-payment-manager'),
					'default' => '',
				),
				array(
					'id' => 'pp_list_border_color',
					'type' => 'color_rgba',
					'title' => esc_html__('Pricing Plan Feature list Border color', 'directorypress-payment-manager'),
					'default' => '',
				),
				array(
					'id' => 'pp_list_border_color_hover',
					'type' => 'color_rgba',
					'title' => esc_html__('Pricing Plan Feature list Border color on hover', 'directorypress-payment-manager'),
					'default' => '',
				),
				array(
					'id' => 'pp_button_bg',
					'type' => 'color_rgba',
					'title' => esc_html__('Pricing Plan Button Background', 'directorypress-payment-manager'),
					'default' => '',
				),
				array(
					'id' => 'pp_button_bg_hover',
					'type' => 'color_rgba',
					'title' => esc_html__('Pricing Plan Button Background on hover', 'directorypress-payment-manager'),
					'default' => '',
				),
				array(
					'id' => 'pp_button_border_color',
					'type' => 'color_rgba',
					'title' => esc_html__('Pricing Plan Button Border color', 'directorypress-payment-manager'),
					'default' => '',
				),
				array(
					'id' => 'pp_button_border_color_hover',
					'type' => 'color_rgba',
					'title' => esc_html__('Pricing Plan Feature list Border color on hover', 'directorypress-payment-manager'),
					'default' => '',
				),
				array(
					'id' => 'pp_wrapper_shadow',
					'type' => 'text',
					'title' => esc_html__('Pricing Plan Wrapper Shadow', 'directorypress-payment-manager'),
					'default' => '',
				),
				array(
					'type' => 'slider',
					'id' => 'pp_wrapper_border_width',
					'title' => __('Pricing Plan Wrapper Border Width', 'directorypress-payment-manager'),
					'min' => '0',
					'max' => 25,
					'default' => '',
				),
				array(
					'type' => 'slider',
					'id' => 'pp_list_border_width',
					'title' => __('Pricing Plan Feature Border top Width', 'directorypress-payment-manager'),
					'min' => '0',
					'max' => 25,
					'default' => '',
				),
				array(
					'type' => 'slider',
					'id' => 'pp_button_border_width',
					'title' => __('Pricing Plan Button Border Width', 'directorypress-payment-manager'),
					'min' => '0',
					'max' => 25,
					'default' => '',
				),
				array(
					'id'             => 'pp_wrapper_radius',
					'type'           => 'spacing',
					'output'         => array(''),
					'mode'           => 'padding',
					'units'          => array('px'),
					'units_extended' => 'false',
					'title'          => __('Pricing Plan box border radius', 'directorypress-payment-manager'),
					'subtitle'       => __('Set Border Radius For Pricing Plan box', 'directorypress-payment-manager'),
					'desc' => __('you can set radius for each corner separately e.g (top =  top left, right = top right, bottom =  bottom right, left = bottom left )', 'directorypress-payment-manager'),
					'default'            => array(
						'margin-top'     => '', 
						'margin-right'   => '', 
						'margin-bottom'  => '', 
						'margin-left'    => '',
						'units'          => 'px', 
					)
				),
				array(
					'id'             => 'pp_button_radius',
					'type'           => 'spacing',
					'output'         => array(''),
					'mode'           => 'padding',
					'units'          => array('px'),
					'units_extended' => 'false',
					'title'          => __('Pricing Plan Button border radius', 'directorypress-payment-manager'),
					'subtitle'       => __('Set Border Radius For Pricing Plan Button', 'directorypress-payment-manager'),
					'desc' => __('you can set radius for each corner separately e.g (top =  top left, right = top right, bottom =  bottom right, left = bottom left )', 'directorypress-payment-manager'),
					'default'            => array(
						'margin-top'     => '', 
						'margin-right'   => '', 
						'margin-bottom'  => '', 
						'margin-left'    => '',
						'units'          => 'px', 
					)
				),
			),
		) );
	}

}
