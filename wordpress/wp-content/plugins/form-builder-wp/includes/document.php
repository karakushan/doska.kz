<?php

class Form_Builder_Wp_Document extends \Elementor\Core\Base\Document {
	
	public static function get_properties() {
		$properties = parent::get_properties();
		$properties['admin_tab_group'] = '';
		$properties['support_wp_page_templates'] = true;
		$properties['cpt'] = array('wpfbform');
		

		return $properties;
	}
	
	public function get_initial_config() {
		$config = parent::get_initial_config();
	
		$config['library'] = array(
			'save_as_same_type'=>true,
			'type'=>$this->get_name()
		);
	
		return $config;
	}
	
	public function get_name() {
		return 'wpfbform';
	}

	public static function get_title() {
		return __( 'WP Form Builder', 'form-builder-wp' );
	}

	public function get_css_wrapper_selector() {
		return '#wpfbform-' . $this->get_main_id();
	}
	
	protected static function get_editor_panel_categories() {
		$categories = parent::get_editor_panel_categories();
		unset($categories['theme-elements']);
		unset($categories['woocommerce-elements']);
		unset($categories['wordpress']);
		unset($categories['form-builder-wp']);
		$categories = array(
			'wpfb-form-fields' => array(
				'title' => __( 'Form fields', 'form-builder-wp' ),
				'active' => true,
			)
		) + $categories;
		
	
		return $categories;
	}
	
	protected function register_controls() {
		parent::register_controls();
		self::register_style_controls( $this );
	}
	/**
	 * 
	 * @param \Elementor\Core\Base\Document $document
	 */
	protected function register_style_controls( $document ){
		
		$document->start_controls_section(
			'section_form_style',
			array(
				'label' => __( 'Form Style', 'form-builder-wp' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
		$document->add_control(
			'form_layout',
			array (
				"type" => \Elementor\Controls_Manager::SELECT,
				"label" => __ ( "Form layout", 'form-builder-wp' ),
				"name" => "form_layout",
				"options" => array (
					'vertical'=>__ ( 'Vertical', 'form-builder-wp' ),
					'horizontal'=>__ ( 'Horizontal', 'form-builder-wp' ),
				),
			)
		);
		
		$document->add_control(
			'input_icon_position',
			array (
				"type" => \Elementor\Controls_Manager::SELECT,
				"label" => __ ( "Input icon position", 'form-builder-wp' ),
				"options" => array (
					'right'=>__ ( 'Right', 'form-builder-wp' ),
					'left'=>__ ( 'Left', 'form-builder-wp' ),
				),
			)
		);
		
		$document->end_controls_section();
		$document->start_controls_section(
			'section_form_label_style',
			array(
				'label' => __( 'Label Style', 'form-builder-wp' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		$document->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array (
				'name' => 'label_typography',
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .wpfb-form-group .wpfb-form-label,
					{{WRAPPER}} .wpfb-form-group label:not(.wpfb-form-rate-star):not(.wpfb-form-radio__option-label):not(.wpfb-form-checkbox__option-label)',
			)
		);
		$document->add_control(
			'label_color',
			array (
				'type' =>\Elementor\Controls_Manager::COLOR,
				'label' => __ ( 'Label Color', 'form-builder-wp' ),
				'selectors'=>array(
					'{{WRAPPER}} .wpfb-form-group .wpfb-form-label,
					{{WRAPPER}} .wpfb-form-group label:not(.wpfb-form-rate-star)'=>'color:{{VALUE}};'
				)
			)
		);
		$document->end_controls_section();
		
		$document->start_controls_section(
			'section_form_input_style',
			array(
				'label' => __( 'Input Style', 'form-builder-wp' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
		$document->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array (
				'name' => 'input_typography',
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .wpfb-form-input input,
					{{WRAPPER}} .wpfb-form-file input[type="text"],
					{{WRAPPER}} .wpfb-form-captcha input,
					{{WRAPPER}} .wpfb-form-select select,
					{{WRAPPER}} .wpfb-form-textarea textarea,
					{{WRAPPER}} .wpfb-form-group .wpfb-form-radio__option-label,
					{{WRAPPER}} .wpfb-form-group .wpfb-form-checkbox__option-label',
			)
		);
		$document->add_control(
			'placeholder_color',
			array (
				'type' => \Elementor\Controls_Manager::COLOR,
				'label' => __ ( 'Placeholder Text Color', 'form-builder-wp' ),
				'selectors'=>array( //{{SELECTOR}}
					'{{WRAPPER}} .wpfb-form-group .wpfb-form-add-on'=>'color:{{VALUE}};',
					'{{WRAPPER}} .wpfb-form-group .wpfb-form-control::placeholder'=>'color:{{VALUE}};',
					'{{WRAPPER}} .wpfb-form-group .wpfb-form-control::-webkit-input-placeholder'=>'color:{{VALUE}};',
					'{{WRAPPER}} .wpfb-form-group .wpfb-form-control::-moz-input-placeholder'=>'color:{{VALUE}};',
					'{{WRAPPER}} .wpfb-form-group .wpfb-form-control::-ms-input-placeholder'=>'color:{{VALUE}};',
					'{{WRAPPER}} .wpfb-form-group .wpfb-form-control:focus::-webkit-input-placeholder'=>'color:transparent;'
				),
			)
		);
		$document->add_control(
			'input_height',
			array (
				'type' => \Elementor\Controls_Manager::SLIDER,
				'label' => __ ( 'Height', 'form-builder-wp' ),
				'size_units' => array('px', 'em', '%'),
				'selectors'=>array(
					'{{WRAPPER}} .wpfb-form-input input,
					{{WRAPPER}} .wpfb-form-file input[type="text"],
					{{WRAPPER}} .wpfb-form-captcha input,
					{{WRAPPER}} .wpfb-form-select select,
					{{WRAPPER}} .wpfb-form-group .wpfb-form-add-on,
					{{WRAPPER}} .wpfb-form-file-button i,
					{{WRAPPER}} .wpfb-form-select i'=>'height:{{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .wpfb-form-select i,
					{{WRAPPER}} .wpfb-form-file-button i,
					{{WRAPPER}} .wpfb-form-group .wpfb-form-add-on'=>'line-height:{{SIZE}}{{UNIT}}',
				),
			)
		);
		$document->add_control(
			'input_bg_color',
			array (
				'type' => \Elementor\Controls_Manager::COLOR,
				'label' => __ ( 'Background Color', 'form-builder-wp' ),
				'selectors'=>array(
					'{{WRAPPER}} .wpfb-form-input input,
					{{WRAPPER}} .wpfb-form-file input[type="text"],
					{{WRAPPER}} .wpfb-form-captcha input,
					{{WRAPPER}} .wpfb-form-select select,
					{{WRAPPER}} .wpfb-form-textarea textarea'=>'background-color:{{VALUE}};'
				)
			)
		);
		$document->add_control(
			'input_text_color',
			array (
				'type' => \Elementor\Controls_Manager::COLOR,
				'label' => __ ( 'Text Color', 'form-builder-wp' ),
				'selectors'=>array(
					'{{WRAPPER}} .wpfb-form-input input,
					{{WRAPPER}} .wpfb-form-file input[type="text"],
					{{WRAPPER}} .wpfb-form-captcha input,
					{{WRAPPER}} .wpfb-form-select select,
					{{WRAPPER}} .wpfb-form-textarea textarea'=>'color:{{VALUE}};'
				)
			)
		);
		
		$document->add_control(
			'border',
			array (
				'label' => __( 'Border Type', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'default' => __( 'Default', 'form-builder-wp' ),
					'none' => __( 'None', 'form-builder-wp' ),
					'solid' => _x( 'Solid', 'Border Control', 'form-builder-wp' ),
					'double' => _x( 'Double', 'Border Control', 'form-builder-wp' ),
					'dotted' => _x( 'Dotted', 'Border Control', 'form-builder-wp' ),
					'dashed' => _x( 'Dashed', 'Border Control', 'form-builder-wp' ),
					'groove' => _x( 'Groove', 'Border Control', 'form-builder-wp' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .wpfb-form-input input, 
					{{WRAPPER}} .wpfb-form-file input[type="text"], 
					{{WRAPPER}} .wpfb-form-captcha input, 
					{{WRAPPER}} .wpfb-form-select select,
					{{WRAPPER}} .wpfb-form-textarea textarea' => 'border-style: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);
		
		$document->add_control(
			'border_width',
			array (
				'label' => __( 'Border Width', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => array (
					'{{WRAPPER}} .wpfb-form-input input, 
					{{WRAPPER}} .wpfb-form-file input[type="text"], 
					{{WRAPPER}} .wpfb-form-captcha input, 
					{{WRAPPER}} .wpfb-form-select select,
					{{WRAPPER}} .wpfb-form-textarea textarea' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' =>array (
					'border!' => array('none','default'),
				),
				'responsive' => false,
			)
		);
		
		$document->add_control(
			'border_color',
			array (
				'label' => __( 'Border Color', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => array (
					'{{WRAPPER}} .wpfb-form-input input, 
					{{WRAPPER}} .wpfb-form-file input[type="text"], 
					{{WRAPPER}} .wpfb-form-captcha input, 
					{{WRAPPER}} .wpfb-form-select select,
					{{WRAPPER}} .wpfb-form-textarea textarea' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .wpfb-form-checkbox i,{{WRAPPER}} .wpfb-form-radio i' => 'color: {{VALUE}};',
				),
				'condition' => array (
					'border!' => array('none','default'),
				),
			)
		);
		
		$document->end_controls_section();
	}
}