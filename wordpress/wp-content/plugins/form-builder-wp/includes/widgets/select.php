<?php
use Elementor\Controls_Manager;
class Form_Builder_Wp_Widget_Select extends Form_Builder_Wp_Widget_Base {
	
	public function get_name() {
		return 'wpfb_form_select';
	}
	
	public function get_title() {
		return __( 'Select Field', 'form-builder-wp' );
	}
	
	public function get_icon() {
		return 'wpfb-form-icon-widget-select';
	}
	
	public function get_keywords() {
		return array('select');
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
			'disabled',
			array(
				'label' => __( 'Disabled ?', 'form-builder-wp' ),
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
		
		$this->start_controls_section(
			'section_options',
			array(
				'label' => __( 'Options', 'form-builder-wp' ),
			)
		);
		
		$repeater = new \Elementor\Repeater();
		
		$repeater->add_control(
			'option_default',
			array(
				'label' => __( 'Deafult', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'form-builder-wp' ),
				'label_on' => __( 'Yes', 'form-builder-wp' ),
			)
		);
		
		$repeater->add_control(
			'option_label',
			array(
				'label' => __( 'Option Label', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => true,
			)
		);
		
		$repeater->add_control(
			'option_value',
			array(
				'label' => __( 'Option Value', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			)
		);
		
		$this->add_control(
			'options_list',
			array(
				'label' => __( 'Options', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => array(
					array(
						'option_label' => 'Option #1',
						'option_value' => 'value_1',
					),
					array(
						'option_label' => 'Option #2',
						'option_value' => 'value_2',
					),
				),
				'title_field' => '{{{ option_label }}}',
			)
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_conditional',
			array(
				'label' => __( 'Conditionals Logic', 'form-builder-wp' ),
			)
		);
		
		$repeater = new \Elementor\Repeater();
		
		$repeater->add_control(
			'type',
			array(
				'label' => __( 'If value this element', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options'=>array(
					'=' => __('Equals','form-builder-wp'),
					'>' => __('Is greater than','form-builder-wp'),
					'<' => __('Is less than','form-builder-wp'),
					'not_empty' => __('Not empty','form-builder-wp'),
					'is_empty' => __('Is empty','form-builder-wp'),
				)
			)
		);
		
		$repeater->add_control(
			'value',
			array(
				'label' => __( 'Value', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'condition' =>array(
					'type!'=>array('not_empty','is_empty')
				),
			)
		);
		
		$repeater->add_control(
			'action',
			array(
				'label' => __( 'Then', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options'=>array(
					'show'=>__('Show','form-builder-wp'),
					'hide'=>__('Hide','form-builder-wp')
				)
			)
		);
		
		$repeater->add_control(
			'element',
			array(
				'label' => __( 'Element(s) name', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder'=>'element_1,element_2'
			)
		);
		
		$this->add_control(
			'conditional',
			array(
				'label' => __( 'Conditionals Logic', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ type }}}',
				'prevent_empty' => false,
			)
		);
		
		$this->end_controls_section();
		
	}
	
	protected function _parse_settings(){
		$settings = $this->get_settings_for_display();
		$settings['options_list'] = isset($settings['options_list']) ? base64_encode(json_encode($settings['options_list'])) : array();
		$settings['conditional'] = isset($settings['conditional']) ? base64_encode(json_encode($settings['conditional'])) : array();
		return $settings;
	}
}


function wpfb_form_field_select_validation_filter($result, $field){
	$name = $field->get_name();
	if ( isset( $_POST[$name] ) && is_array( $_POST[$name] ) ) {
		foreach ( $_POST[$name] as $key => $value ) {
			if ( '' === $value ) {
				unset( $_POST[$name][$key] );
			}
		}
	}

	$empty = ! isset( $_POST[$name] ) || empty( $_POST[$name] ) && '0' !== $_POST[$name];

	if($field->is_required() && $empty){
		$result->invalidate($field, wpfb_form_get_message('invalid_select'));
	}
	return $result;

}
add_filter( 'wpfb_form_validate_select', 'wpfb_form_field_select_validation_filter', 10, 2 );
