<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Form_Builder_Wp_Assets {
	
	public static function init(){
		if(is_admin()){
			add_action('admin_init', array( __CLASS__, 'register_assets' ));
		}else{
			add_action( 'template_redirect', array( __CLASS__, 'register_assets' ) );
			add_action( 'template_redirect', array( __CLASS__, 'frontend_scripts' ) );
			add_action('wp_enqueue_scripts', array(__CLASS__, 'frontend_enqueue_assets'));
		}
	}
	
	public static function register_assets(){
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		
		wp_register_script('jquery-blockui',FORM_BUILDER_WP_URL . 'assets/js/jquery.blockUI.min.js',array('jquery'),'2.70',true);
		
		wp_register_style('jquery-xdsoft-datetimepicker',FORM_BUILDER_WP_URL . 'assets/datetimepicker/jquery.datetimepicker.css', array(),'2.2.9');
		wp_register_script('jquery-xdsoft-datetimepicker',FORM_BUILDER_WP_URL . 'assets/datetimepicker/jquery.datetimepicker'.$suffix.'.js',array('jquery'),'2.4.6',true);
		
		wp_register_style('jquery-minicolors',FORM_BUILDER_WP_URL . 'assets/minicolors/jquery.minicolors'.$suffix.'.css', array(),'2.1');
		wp_register_script('jquery-minicolors',FORM_BUILDER_WP_URL . 'assets/minicolors/jquery.minicolors'.$suffix.'.js',array('jquery'),'2.1',true);
	
		wp_register_style('bootstrap-tooltip',FORM_BUILDER_WP_URL . 'assets/css/bootstrap-tooltip.css', array(),'3.2.0');
		wp_register_script('bootstrap-tooltip',FORM_BUILDER_WP_URL . 'assets/js/bootstrap-tooltip.js',array('jquery'),'3.2.0',true);

		wp_register_script('js-cookie',FORM_BUILDER_WP_URL . 'assets/js/js.cookie.min.js',array(),'2.1.4',true);		
		
	}
	
	public static function frontend_scripts(){
		
		wp_register_style('form-builder-wp',FORM_BUILDER_WP_URL . 'assets/css/style.css', array(), FORM_BUILDER_WP_VERSION);
		wp_register_script('form-builder-wp',FORM_BUILDER_WP_URL . 'assets/js/script.min.js',array('jquery'),FORM_BUILDER_WP_VERSION,true);
		
		$wpfb_form_params = array(
			'ajax_url'=>admin_url( 'admin-ajax.php', 'relative' ),
			'ajax_submit_url'=> add_query_arg( 'wpfb-form-ajax', 'submit',  home_url( '/', 'relative' ) ),
			'plugin_url'=>FORM_BUILDER_WP_URL,
			'recaptcha_public_key'=>wpfb_form_get_option('recaptcha_public_key'),
			'_ajax_nonce'=>wp_create_nonce( 'wpfb_form_ajax_nonce' ),
			'date_format'=>wpfb_form_get_option('date_format','Y/m/d'),
			'time_format'=>wpfb_form_get_option('time_format','H:i'),
			'time_picker_step'=>wpfb_form_get_option('time_picker_step',60),
			'dayofweekstart'=>apply_filters('wpfb_form_dayofweekstart',1),
			'datetimepicker_lang'=>wpfb_form_get_option('datetimepicker_lang','en'),
			'container_class'=>wpfb_form_get_option('container_class','.elementor-column-wrap'),
			'is_edit_mode'=> \Elementor\Plugin::$instance->editor->is_edit_mode() ? 'yes':'no',
			'is_preview_mode'=> \Elementor\Plugin::$instance->preview->is_preview_mode() ? 'yes':'no'
		);
		
		wp_localize_script('form-builder-wp', 'wpfb_form_params', $wpfb_form_params);
	}
	
	public static function frontend_enqueue_assets(){
		wp_enqueue_style('form-builder-wp');
	}
}

Form_Builder_Wp_Assets::init();