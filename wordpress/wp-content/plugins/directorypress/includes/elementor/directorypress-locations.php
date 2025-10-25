<?php
/**
 * Elementor test Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
use Elementor\Plugin;
class DirectoryPress_Elementor_Location_Widget extends \Elementor\Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		
		add_action('wp_enqueue_scripts', array($this, 'scripts'));
	}
	public function scripts() {
		
	}
	
	/**
	 * Get widget name.
	 *
	 * Retrieve oEmbed widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'locations';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve oEmbed widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Locations', 'DIRECTORYPRESS' );
	}


	/**
	 * Get widget icon.
	 *
	 * Retrieve oEmbed widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fas fa-map-marked-alt';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'directorypress' ];
	}

	/**
	 * Register oEmbed widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		//$ordering = directorypress_sorting_options();
		//$directories = directorypress_directorytypes_array_options();
		$locations = directorypress_locations_array_options();
		$packages = directorypress_packages_array_options();
		
		// Setting Section
		$this->start_controls_section(
			'setting_section',
			[
				'label' => esc_html__( 'Setting', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'location_style',
			[
				'label' => esc_html__('Location Styles', 'DIRECTORYPRESS'),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => apply_filters("directorypress_locations_styles" , "directorypress_locations_styles_function"),
				'default' => 'default',
			]
		);
		$this->add_control(
			'parent',
			[
				'label' => esc_html__('Parent category', 'DIRECTORYPRESS'),
				'description' => esc_html__('ID of parent category (default 0 – this will build whole categories tree starting from the root).', 'DIRECTORYPRESS'),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Inset Id', 'DIRECTORYPRESS' ),
			]
		);
		$this->add_control(
			'depth',
			[
				'label' => esc_html__('Locations sub level', 'DIRECTORYPRESS'), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'description' => esc_html__('The max depth of locations tree. When set to 1 – only parent locations will be listed.', 'DIRECTORYPRESS'),
				'multiple' => false,
				'options' => [
					'1' => esc_html__( 'Level 1', 'DIRECTORYPRESS' ),
					'2' => esc_html__( 'Level 2', 'DIRECTORYPRESS' ),
				],
				//'condition' => [
					//'cat_style' => [ '3', '6', '7', '10' ],
				//],
				'default' => 1,
			]
		);
		$this->add_control(
			'sublocations',
			[
				'label' => esc_html__('Show sublocations items number', 'DIRECTORYPRESS'),
				'description' => esc_html__('This is the number of sublocations those will be displayed in the table, when Location item includes more than this number "View all" link appears at the bottom.', 'DIRECTORYPRESS'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'condition' => [
					'depth' => [ '2' ],
				],
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 5,
			]
		);
		
		$this->add_control(
			'columns',
			[
				'label' =>__('Locations column number', 'DIRECTORYPRESS'),
				'description' => esc_html__('Categories list is divided by columns.', 'DIRECTORYPRESS'),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'1' => esc_html__( '1 Column', 'DIRECTORYPRESS' ),
					'2' => esc_html__( '2 Column', 'DIRECTORYPRESS' ),
					'3' => esc_html__( '3 Column', 'DIRECTORYPRESS' ),
					'4' => esc_html__( '4 Column', 'DIRECTORYPRESS' ),
				],
				'default' => 4,
			]
		);
		$this->add_control(
			'count',
			[
				'label' =>  esc_html__('Show location listing count?', 'DIRECTORYPRESS'),
				'description' => esc_html__('Whether to show number of listings assigned with current location in brackets.', 'DIRECTORYPRESS'), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => esc_html__( 'No', 'DIRECTORYPRESS' ),
					'1' => esc_html__( 'Yes', 'DIRECTORYPRESS' ),
				],
				'default' => 1,
			]
		);
		$this->add_control(
			'height',
			[
				'label' => esc_html__( 'Column Height', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 500,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .location-style-custom.directorypress-locations-columns' => 'min-height: {{SIZE}}{{UNIT}}; width:100% !important',
					'{{WRAPPER}} .location-style-custom.directorypress-locations-columns .directorypress-location-item-holder' => 'height: {{SIZE}}{{UNIT}}; width:100%',
				],
			]
		);
		$this->end_controls_section(); 
		
		// content section
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'locations',
			[
				'label' => esc_html__( 'Locations', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $locations,
				'default' => 0,
			]
		);
		
		$this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => '',
					'library' => 'solid',
				],
			]
		);

		$this->end_controls_section();
		
		// Style tab and section
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Parent', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'label' => esc_html__( 'Title Typography', 'DIRECTORYPRESS' ),
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				/* 'selectors' => [
					'{{WRAPPER}} .directorypress-parent-location a',
					'{{WRAPPER}} .location-style7 .directorypress-location-item .directorypress-location-item-holder .directorypress-parent-location a',
				], */
				'selector' => '{{WRAPPER}} .directorypress-parent-location a, {{WRAPPER}} .location-style-custom .directorypress-location-item .directorypress-location-item-holder .directorypress-parent-location a',
			]
		);
		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Title Color', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .directorypress-parent-location a, .directorypress-parent-location a .location-icon-wrapper' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_control(
			'title_color_hover',
			[
				'label' => esc_html__( 'Title Color Hover', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .directorypress-parent-location a:hover, .directorypress-parent-location a:hover .location-icon-wrapper' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => esc_html__( 'Border', 'DIRECTORYPRESS' ),
				'selector' => '{{WRAPPER}} .directorypress-parent-location a',
			]
		);
		$this->end_controls_section();
		
		// Style tab and section sublocations
		$this->start_controls_section(
			'sublocations_style_section',
			[
				'label' => esc_html__( 'Sub Locations', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'sub_content_typography',
				'label' => esc_html__( 'Title Typography', 'DIRECTORYPRESS' ),
				//'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .sublocations a',
			]
		);
		$this->add_control(
			'sub_title_color',
			[
				'label' => esc_html__( 'Title Color', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sublocations a' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_control(
			'sub_title_color_hover',
			[
				'label' => esc_html__( 'Title Color Hover', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sublocations a:hover, .directorypress-parent-location a:hover .location-icon-wrapper' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'sub_border',
				'label' => esc_html__( 'Border', 'DIRECTORYPRESS' ),
				'selector' => '{{WRAPPER}} .sublocations a',
			]
		);
		$this->end_controls_section();
		
		// Icon styles
		$this->start_controls_section(
			'icon_section',
			[
				'label' => esc_html__( 'Icon', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'icon_bg_color',
			[
				'label' => esc_html__( 'Background', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .location-icon-wrapper' => 'background-color: {{VALUE}} !important',
				],
			]
		);
		$this->add_control(
			'icon_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color Hover', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .directorypress-elementor-locations-widget:hover .location-icon-wrapper' => 'background-color: {{VALUE}} !important',
				],
			]
		);
		$this->add_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 32,
				'placeholder' => esc_html__( 'Type your title here', 'DIRECTORYPRESS' ),
				'selectors' => [
					'{{WRAPPER}} .location-icon-wrapper i' => 'font-size: {{VALUE}}px;',
					'{{WRAPPER}} .location-icon-wrapper svg' => 'width: {{VALUE}}px; height: {{VALUE}}px;',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'icon_border',
				'label' => esc_html__( 'Border', 'DIRECTORYPRESS' ),
				'selector' => '{{WRAPPER}} .location-icon-wrapper',
			]
		);
		$this->add_control(
			'icon_border_radius',
			[
				'label' => esc_html__( 'Icon Border Radius', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .location-icon-wrapper' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_padding',
			[
				'label' => esc_html__( 'Padding', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => 10,
					'bottom' => 10,
					'left' => 10,
					'right' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .location-icon-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_position_top',
			[
				'label' => esc_html__( 'Icon Position Top', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'Position From Top', 'DIRECTORYPRESS' ),
				'selectors' => [
					'{{WRAPPER}} .location-icon-wrapper' => 'top: {{VALUE}}px;',
				],
			]
		);
		$this->add_control(
			'icon_position_left',
			[
				'label' => esc_html__( 'Icon Position Left', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '10',
				'placeholder' => esc_html__( 'Position From Left', 'DIRECTORYPRESS' ),
				'selectors' => [
					'{{WRAPPER}} .location-icon-wrapper' => 'left: {{VALUE}}px;',
				],
			]
		);
		$this->add_control(
			'icon_position_bottom',
			[
				'label' => esc_html__( 'Icon Position Bottom', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 10,
				'placeholder' => esc_html__( 'Position From Bottom', 'DIRECTORYPRESS' ),
				'selectors' => [
					'{{WRAPPER}} .location-icon-wrapper' => 'bottom: {{VALUE}}px;',
				],
			]
		);
		$this->add_control(
			'icon_position_right',
			[
				'label' => esc_html__( 'Icon Position Right', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'Position From Right', 'DIRECTORYPRESS' ),
				'selectors' => [
					'{{WRAPPER}} .location-icon-wrapper' => 'right: {{VALUE}}px;',
				],
			]
		);
		$this->end_controls_section();

	}

	
	protected function render() {
		$settings = $this->get_settings_for_display();
		
		$instance = array(
				'parent' => $settings['parent'],
				'depth' => $settings['depth'],
				'columns' => $settings['columns'],
				'count' => $settings['count'],
				'sublocations' => $settings['sublocations'],
				'locations' => $settings['locations'],
				'location_style' => $settings['location_style'],
				
		);
		
		$directorypress_handler = new directorypress_locations_handler();
		$directorypress_handler->init($instance);

		echo '<div class="directorypress-elementor-locations-widget clearfix">';
			if(isset($settings['icon']) && !empty($settings['icon']['value'])){
				echo '<div class="location-icon-wrapper">';
					\Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); 
				echo '</div>';
			}
			
			echo $directorypress_handler->display(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div>';
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
		echo '<script>
			( function( $ ) {
				//directorypress_slik_init();	
			} )( jQuery );
		</script>';
		};
	}

}