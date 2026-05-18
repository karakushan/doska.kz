<?php

class Form_Builder_Wp_Widget_File extends Form_Builder_Wp_Widget_Base {
	
	public function get_name() {
		return 'wpfb_form_file';
	}
	
	public function get_title() {
		return __( 'File field', 'form-builder-wp' );
	}
	
	public function get_icon() {
		return 'wpfb-form-icon-widget-file';
	}
	
	public function get_keywords() {
		return array('File');
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
			'help_text',
			array(
				'label' => __( 'Help text', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'description'=>__('This is the help text for this form control.', 'form-builder-wp')
			)
		);
		
		$this->add_control(
			'required',
			array(
				'label' => __( 'Required ?', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'form-builder-wp' ),
				'label_on' => __( 'Yes', 'form-builder-wp' ),
			)
		);
		
		$this->add_control(
			'attributes',
			array(
				'label' => __( 'Attributes', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				 'description' => __('Add attribute for this form control,eg: <em>onclick="" onchange="" </em> or \'<em>data-*</em>\'  attributes HTML5, not in attributes: <span style="color:#ff0000">type, value, name, required, placeholder, maxlength, id</span>', 'form-builder-wp')
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

function wpfb_form_field_file_validation_filter($result, $field){
	$name = $field->get_name();
	$file = isset( $_FILES[$name] ) ? $_FILES[$name] : null;
	if ( $file['error'] && UPLOAD_ERR_NO_FILE != $file['error'] ) {
		$result->invalidate( $tag, wpfb_form_get_message( 'upload_failed_php_error' ) );
		return $result;
	}
	if ( empty( $file['tmp_name'] ) && $field->is_required() ) {
		$result->invalidate( $field, wpfb_form_get_message( 'invalid_required' ) );
		return $result;
	}
	if ( ! is_uploaded_file( $file['tmp_name'] ) ) {
		return $result;
	}
	$file_type_pattern = implode( '|', wpfb_form_allowed_file_extension() );
	$file_type_pattern = trim( $file_type_pattern, '|' );
	$file_type_pattern = '(' . $file_type_pattern . ')';
	$file_type_pattern = '/\.' . $file_type_pattern . '$/i';
	if ( ! preg_match( $file_type_pattern, $file['name'] ) ) {
		$result->invalidate( $field, wpfb_form_get_message( 'upload_file_type_invalid' ) );
		return $result;
	}
	
	$allowed_size = wpfb_form_allowed_size(); // default size 1 MB
	
	if ( $file['size'] > $allowed_size ) {
		$result->invalidate( $field, wpfb_form_get_message( 'upload_file_too_large' ) );
		return $result;
	}
	
	if ( $submission = Form_Builder_Wp_Submission::get_instance() ) {
		$submission->add_upload_files( $name, $file );
	}
	
	return $result;

}
add_filter( 'wpfb_form_validate_file', 'wpfb_form_field_file_validation_filter', 10, 2 );
