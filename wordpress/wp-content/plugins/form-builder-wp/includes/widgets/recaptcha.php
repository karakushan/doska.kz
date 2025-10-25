<?php

class Form_Builder_Wp_Widget_Recaptcha extends Form_Builder_Wp_Widget_Base {
	
	public function get_name() {
		return 'wpfb_form_recaptcha';
	}
	
	public function get_title() {
		return __( 'Recaptcha field', 'form-builder-wp' );
	}
	
	public function get_icon() {
		return 'wpfb-form-icon-widget-recaptcha';
	}
	
	public function get_keywords() {
		return array('recaptcha');
	}
	
	protected function register_controls(){
		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'General', 'form-builder-wp' ),
			)
		);
		
// 		$this->add_control(
// 			'captcha_type',
// 			array(
// 				'label' => __( 'Type', 'form-builder-wp' ),
// 				'type' => \Elementor\Controls_Manager::SELECT,
// 				'default' => '2',
// 				'options' => array(
// 	                '2'=>__('Version 2', 'form-builder-wp'),
// 	            	'3'=>__('Version 3', 'form-builder-wp')
// 				),
// 				'description' => __('Select reCaptcha version you want use.', 'form-builder-wp')
// 			)
// 		);
		
		$this->add_control(
			'theme',
			array(
				'label' => __( 'Theme', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'text',
				'options' => array(
					'red'=>__('Red', 'form-builder-wp'),
	                'clean'=> __('Clean', 'form-builder-wp'),
	                'white'=>__('White', 'form-builder-wp'),
	                'blackglass'=>__('BlackGlass', 'form-builder-wp')
				),
				'condition' =>array(
					'captcha_type'=>'1'
				),
				'description' => __('Defines which theme to use for reCAPTCHA.', 'form-builder-wp')
			)
		);
		
		$this->add_control(
			'language',
			array(
				'label' => __( 'Type', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'text',
				'options' => wpfb_form_get_recaptcha_lang(),
				'condition' =>array(
					'captcha_type'=>'1'
				),
				'description' => __('Select the language you would like to use for the reCAPTCHA display from the available options.', 'form-builder-wp')
			)
		);
		
		$this->add_control(
			'control_label',
			array(
				'label' => __( 'Label', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			)
		);
		
		$this->add_control(
			'control_name',
			array(
				'label' => __( 'Name', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => __('Field name is required.  Please enter single word, no spaces, no start with number. Underscores(_) allowed', 'form-builder-wp')
			)
		);
		
		$this->add_control(
			'help_text',
			array(
				'label' => __( 'Help text', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'description'=>__('This is the help text for this form control.', 'form-builder-wp')
			)
		);
		
		$this->add_control(
			'el_class',
			array(
				'label' => __( 'Extra class name', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'separator' => 'before',
				'description' => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'form-builder-wp')
			)
		);
		
	
		$this->end_controls_section();
	}
}

function wpfb_form_recaptcha_enqueue_api_script($ver='2'){
	if(!apply_filters('wpfb_form_recaptcha_enqueue_api_script', true)){
		return;
	}
	$language = apply_filters('wpfb_form_language_code','en');
	$render = '3'==$ver ? wpfb_form_get_option('recaptcha_public_key','') : 'explicit'; 
	$api_script_url = add_query_arg(
		array(
			'render'=>$render,
			'hl'=>$language
		),
		'https://www.google.com/recaptcha/api.js'	
	);
	wp_enqueue_script( 'wpfb-form-recaptcha', $api_script_url , array('jquery'), $ver,true );
}

function wpfb_form_recaptcha_verify($response_token ) {
	$is_human = false;

	if ( empty( $response_token ) ) {
		return $is_human;
	}

	$url = 'https://www.google.com/recaptcha/api/siteverify';
	$sitekey = wpfb_form_get_option('recaptcha_public_key');
	$secret = wpfb_form_get_option('recaptcha_private_key');
	$response = wp_safe_remote_post( $url, array(
		'body' => array(
			'secret' => $secret,
			'response' => $response_token,
			'remoteip' => $_SERVER['REMOTE_ADDR'] ) ) );

	if ( 200 != wp_remote_retrieve_response_code( $response ) ) {
		return $is_human;
	}

	$response = wp_remote_retrieve_body( $response );
	$response = json_decode( $response, true );
	$is_human = isset( $response['success'] ) && true == $response['success'];
	return $is_human;
}

/**
 * 
 * @param Form_Builder_Wp_Validation $result
 * @param Form_Builder_Wp_Field $field
 * @return Form_Builder_Wp_Validation
 */
function wpfb_form_field_recaptcha_validation_filter($result, $field){
	$type = $field->attr('captcha_type');
	$into = 'div#'.$field->get_name().'.wpfb-form-recaptcha';
	$response_token = isset( $_POST['g-recaptcha-response'] ) ? $_POST['g-recaptcha-response'] : '';
	if(empty($response_token)){
		$result->invalidate($field, wpfb_form_get_message('recaptcha_not_check'), $into);
	}elseif (!wpfb_form_recaptcha_verify($response_token)){
		$result->invalidate($field, wpfb_form_get_message('invalid_recaptcha'), $into);
	}
	return $result;

}
add_filter( 'wpfb_form_validate_recaptcha', 'wpfb_form_field_recaptcha_validation_filter', 10, 2 );