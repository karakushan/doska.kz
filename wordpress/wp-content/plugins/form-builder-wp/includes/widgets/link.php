<?php

class Form_Builder_Wp_Widget_Link extends Form_Builder_Wp_Widget_Base {
	
	public function get_name() {
		return 'wpfb_form_link';
	}
	
	public function get_title() {
		return __( 'Link field', 'form-builder-wp' );
	}
	
	public function get_icon() {
		return 'wpfb-form-icon-widget-link';
	}
	
	public function get_keywords() {
		return array('link');
	}
	
	protected function register_controls(){
		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'General', 'form-builder-wp' ),
			)
		);
		$this->add_control(
			'link_text',
			[
				'label' => __( 'Title', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Type Link Title Here', 'form-builder-wp' ),
			]
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
			'link',
			[
				'label' => __( 'Link', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'form-builder-wp' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Field Styling', 'form-builder-wp' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'typography',
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .wpfb-form-link a',
			)
		);
		
		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_field_normal',
			array(
				'label' => __( 'Normal', 'form-builder-wp' ),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label' => __( 'Text Color', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .wpfb-form-link a' => 'color: {{VALUE}};',
				),
			)
		);


		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_field_hover',
			array(
				'label' => __( 'Hover', 'form-builder-wp' ),
			)
		);

		$this->add_control(
			'hover_color',
			array(
				'label' => __( 'Text Color', 'form-builder-wp' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wpfb-form-link a:hover,
					{{WRAPPER}} .wpfb-form-link a:hover,
					{{WRAPPER}} .wpfb-form-link a:active' => 'color: {{VALUE}};',
				),
			)
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		
		
		$this->end_controls_section();
	}
	
}