<?php

class Form_Builder_Wp_Widget_Captcha extends Form_Builder_Wp_Widget_Base {
	
	public function get_name() {
		return 'wpfb_form_captcha';
	}
	
	public function get_title() {
		return __( 'Captcha Field', 'form-builder-wp' );
	}
	
	public function get_icon() {
		return 'wpfb-form-icon-widget-captcha';
	}
	
	public function get_keywords() {
		return array('captcha');
	}
	
	protected function register_controls(){
		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'General', 'form-builder-wp' ),
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
			'placeholder',
			array(
				'label' => __( 'Placeholder text', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
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


function wpfb_form_field_captcha_tmp_dir() {
	return wpfb_form_upload_dir('dir').'/captcha';
}

function wpfb_form_field_captcha_tmp_url() {
	return wpfb_form_upload_dir('url').'/captcha';
}

function wpfb_form_field_captcha_img_url($filename) {
	$url = trailingslashit( wpfb_form_field_captcha_tmp_url() ) . $filename;

	if ( is_ssl() && 'http:' == substr( $url, 0, 5 ) ) {
		$url = 'https:' . substr( $url, 5 );
	}

	return apply_filters( 'wpfb_form_field_captcha_img_url', esc_url_raw( $url ) );
}

function wpfb_form_field_captcha_init(){
	static $captcha = null;

	if ( $captcha ) {
		return $captcha;
	}
	
	if ( !class_exists( 'Form_Builder_Wp_Simple_Captcha' ) ) {
		include_once FORM_BUILDER_WP_PATH .'includes/simple_captcha.php';
	} 
	
	$captcha = new Form_Builder_Wp_Simple_Captcha();

	$dir = trailingslashit( wpfb_form_field_captcha_tmp_dir() );

	$captcha->tmp_dir = $dir;
	if ( is_callable( array( $captcha, 'make_tmp_dir' ) ) ) {
		$result = $captcha->make_tmp_dir();

		if ( ! $result ) {
			return false;
		}

		return $captcha;
	}
}

function wpfb_form_field_captcha_generate(){
	if ( ! $captcha = wpfb_form_field_captcha_init() ) {
		return false;
	}

	if ( ! is_dir( $captcha->tmp_dir ) || ! wp_is_writable( $captcha->tmp_dir ) ) {
		return false;
	}

	$img_type = imagetypes();

	if ( $img_type & IMG_PNG ) {
		$captcha->img_type = 'png';
	} elseif ( $img_type & IMG_GIF ) {
		$captcha->img_type = 'gif';
	} elseif ( $img_type & IMG_JPG ) {
		$captcha->img_type = 'jpeg';
	} else {
		return false;
	}

	$captcha->img_size = array( 100, 30 );


	$prefix = wp_rand();
	$captcha_word = $captcha->generate_random_word();
	return $captcha->generate_image( $prefix, $captcha_word );
}

function wpfb_form_field_captcha_remove( $prefix ) {
	if ( ! $captcha = wpfb_form_field_captcha_init() ) {
		return false;
	}

	if ( preg_match( '/[^0-9]/', $prefix ) ) {
		return false;
	}

	$captcha->remove( $prefix );
}

function wpfb_form_field_captcha_check( $prefix, $response ) {
	if ( ! $captcha = wpfb_form_field_captcha_init() ) {
		return false;
	}
	return $captcha->check( $prefix, $response );
}

function wpfb_form_field_captcha_ajax_refill( $items, $submission) {
	if ( ! is_array( $items ) ) {
		return $items;
	}

	$fes = wpfb_form_find_field('captcha',$submission->get_form_id());
	if ( empty( $fes ) ) {
		return $items;
	}

	$refill = array();

	foreach ( $fes as $fe ) {
		$name = $fe->get_name();

		if ( empty( $name ) ) {
			continue;
		}
		if ( $filename = wpfb_form_field_captcha_generate() ) {
			$captcha_url = wpfb_form_field_captcha_img_url( $filename );
			$refill[$name] = $captcha_url;
		}
	}

	if ( ! empty( $refill ) ) {
		$items['captcha'] = $refill;
	}

	return $items;
}
add_filter( 'wpfb_form_ajax_json_echo', 'wpfb_form_field_captcha_ajax_refill',10,2);


function wpfb_form_field_captcha_cleanup_files(){
	if ( ! $captcha = wpfb_form_field_captcha_init() ) {
		return false;
	}
	if ( is_callable( array( $captcha, 'cleanup' ) ) ) {
		return $captcha->cleanup();
	}
}
add_action( 'template_redirect', 'wpfb_form_field_captcha_cleanup_files', 20 );

function wpfb_form_field_captcha_validation_filter($result, $field){
	$name = $field->get_name();

	$captchac = '_wpfb_form_captcha_challenge_' . $name;

	$prefix = isset( $_POST[$captchac] ) ? (string) $_POST[$captchac] : '';
	$response = isset( $_POST[$name] ) ? (string) $_POST[$name] : '';
	$response = wpfb_form_canonicalize( $response );

	if(''==$response){
		$result->invalidate($field, wpfb_form_get_message('invalid_required'));
	}elseif ( 0 == strlen( $prefix ) || ! wpfb_form_field_captcha_check( $prefix, $response ) ){
		$result->invalidate($field, wpfb_form_get_message('captcha_not_match'));
	}

	if ( 0 != strlen( $prefix ) ) {
		wpfb_form_field_captcha_remove( $prefix );
	}
	
	return $result;

}
add_filter( 'wpfb_form_validate_captcha', 'wpfb_form_field_captcha_validation_filter', 10, 2 );
