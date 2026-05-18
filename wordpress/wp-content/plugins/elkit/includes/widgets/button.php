<?php
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Control_Media;
use HFB\WidgetsManager\Widgets_Loader;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) exit;


class Elkit_Button extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.4.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'elkit-button';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.4.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Elkit Button', 'elkit' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.4.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'elkit-icon-menu-cart';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.4.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'elkit' ];
	}

    protected function register_controls() {


		$this->start_controls_section(
			'btn_section_content',
			array(
				'label' => esc_html__( 'Content', 'elkit' ),
			)
		);

		$this->add_control(
			'btn_text',
			[
				'label' =>esc_html__( 'Label', 'elkit' ),
				'type' => Controls_Manager::TEXT,
				'default' =>esc_html__( 'Submit', 'elkit' ),
				'placeholder' =>esc_html__( 'Submit', 'elkit' ),
				'dynamic' => [
                    'active' => true,
                ],
			]
		);


		$this->add_control(
			'btn_url',
			[
				'label' =>esc_html__( 'URL', 'elkit' ),
				'type' => Controls_Manager::URL,
				'placeholder' =>esc_url('https://designinvento.net/'),
				'dynamic' => [
                    'active' => true,
                ],
				'default' => [
					'url' => '#',
				],
			]
		);

        $this->add_control(
            'btn_section_settings',
            [
                'label' => esc_html__( 'Settings', 'elkit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
		);
		
		$this->add_control(
            'btn_icons__switch',
            [
                'label' => esc_html__('Add icon? ', 'elkit'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' =>esc_html__( 'Yes', 'elkit' ),
                'label_off' =>esc_html__( 'No', 'elkit' ),
            ]
		);
		
		$this->add_control(
			'btn_icons',
			[
				'label' =>esc_html__( 'Icon', 'elkit' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => true,
				'default' => [
                    'value' => '',
				],
				'condition'	=> [
					'btn_icons__switch'	=> 'yes'
				]
			]
		);
        $this->add_control(
            'btn_icon_align',
            [
                'label' =>esc_html__( 'Icon Position', 'elkit' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' =>esc_html__( 'Before', 'elkit' ),
                    'right' =>esc_html__( 'After', 'elkit' ),
                ],
                'condition'	=> [
					'btn_icons__switch'	=> 'yes'
				]
            ]
        );
		$this->add_responsive_control(
			'btn_align',
			[
				'label' =>esc_html__( 'Alignment', 'elkit' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' =>esc_html__( 'Left', 'elkit' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' =>esc_html__( 'Center', 'elkit' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' =>esc_html__( 'Right', 'elkit' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .btn-wraper' => 'text-align: {{VALUE}};',
				],
			]
		);
	    $this->add_control(
		    'btn_class',
		    [
			    'label' => esc_html__( 'Class', 'elkit' ),
			    'type' => Controls_Manager::TEXT,
			    'placeholder' => esc_html__( 'Class Name', 'elkit' ),
		    ]
	    );

	    $this->add_control(
		    'btn_id',
		    [
			    'label' => esc_html__( 'id', 'elkit' ),
			    'type' => Controls_Manager::TEXT,
			    'placeholder' => esc_html__( 'ID', 'elkit' ),
		    ]
	    );


		$this->end_controls_section();


        $this->start_controls_section(
			'btn_section_style',
			[
				'label' =>esc_html__( 'Button', 'elkit' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label'			=> esc_html__( 'Width (%)', 'elkit' ),
				'type'			=> Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'		=> [
					'{{WRAPPER}} .elkit-button' => 'width: {{SIZE}}{{UNIT}};',
				]
			]
		);
		$this->add_responsive_control(
			'height',
			[
				'label'			=> esc_html__( 'Height (px)', 'elkit' ),
				'type'			=> Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'		=> [
					'{{WRAPPER}} .elkit-button' => 'height: {{SIZE}}{{UNIT}};',
				]
			]
		);
		$this->add_responsive_control(
			'hover_effect',
			[
				'label' => esc_html__( 'Hover Effect', 'elkit' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'none', 'elkit' ),
					'overlay-effect-1' => esc_html__( 'Overlay Effect 1', 'elkit' ),
					'overlay-effect-2' => esc_html__( 'Overlay Effect 2', 'elkit' ),
					'overlay-effect-3' => esc_html__( 'Overlay Effect 3', 'elkit' ),
					'overlay-effect-4' => esc_html__( 'Overlay Effect 4', 'elkit' ),
					'overlay-effect-5' => esc_html__( 'Overlay Effect 5', 'elkit' ),
					'overlay-effect-6' => esc_html__( 'Overlay Effect 6', 'elkit' ),
					'overlay-effect-7' => esc_html__( 'Overlay Effect 7', 'elkit' ),
					'overlay-effect-8' => esc_html__( 'Overlay Effect 8', 'elkit' ),
				],
				'default' => '',
			]
		);
		$this->add_responsive_control(
			'btn_padding',
			[
				'label' =>esc_html__( 'Padding', 'elkit' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .elkit-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'btn_margin',
			[
				'label' =>esc_html__( 'Margin', 'elkit' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .elkit-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'label' =>esc_html__( 'Typography', 'elkit' ),
				'selector' => '{{WRAPPER}} .elkit-button',
			]
		);

        $this->add_group_control(
        	Group_Control_Text_Shadow::get_type(),
        	[
        		'name' => 'btn_shadow',
        		'selector' => '{{WRAPPER}} .elkit-button',
        	]
        );

		$this->start_controls_tabs( 'btn_tabs_style' );

		$this->start_controls_tab(
			'btn_tabnormal',
			[
				'label' =>esc_html__( 'Normal', 'elkit' ),
			]
		);

		$this->add_control(
			'btn_text_color',
			[
				'label' =>esc_html__( 'Text Color', 'elkit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elkit-button' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elkit-button svg path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
				'name'     => 'btn_bg_color',
				'default' => '',
				'selector' => '{{WRAPPER}} .elkit-button',
            )
        );
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
			  'name' => 'btn_box_shadow',
			  'selector' => '{{WRAPPER}} .elkit-button',
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'btn_tab_button_hover',
			[
				'label' =>esc_html__( 'Hover', 'elkit' ),
			]
		);

		$this->add_control(
			'btn_hover_color',
			[
				'label' =>esc_html__( 'Text Color', 'elkit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .elkit-button:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elkit-button:hover svg path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

	    $this->add_group_control(
		    Group_Control_Background::get_type(),
		    array(
			    'name'     => 'btn_bg_hover_color',
			    'default' => '',
			    'selector' => '{{WRAPPER}} .elkit-button:hover',
		    )
	    );
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
			  'name' => 'btn_box_shadow_hover',
			  'selector' => '{{WRAPPER}} .elkit-button:hover',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_control(
			'button_border_section',
			[
				'label' => esc_html__( 'Border', 'elkit' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'user_menu_icon_box_border',
				'label' => esc_html__( 'Icon Box Border', 'elkit' ),
				'selector' => '{{WRAPPER}} .elkit-button',
			]
		);
		$this->start_controls_tabs( 'tabs_button_border_style' );
		$this->start_controls_tab(
			'btn_tab_border_normal',
			[
				'label' =>esc_html__( 'Normal', 'elkit' ),
			]
		);
		$this->add_responsive_control(
			'btn_border_radius',
			[
				'label' =>esc_html__( 'Border Radius', 'elkit' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%'],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '' ,
					'left' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elkit-button' =>  'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'btn_tab_button_border_hover',
			[
				'label' =>esc_html__( 'Hover', 'elkit' ),
			]
		);
		$this->add_control(
			'btn_hover_border_color',
			[
				'label' => esc_html_x( 'Color', 'Border Control', 'elkit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elkit-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'btn_border_radius_hover',
			[
				'label' =>esc_html__( 'Border Radius', 'elkit' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%'],
				'selectors' => [
					'{{WRAPPER}} .elkit-button:hover' =>  'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		


		$this->end_controls_section();

        $this->start_controls_section(
			'btn_icon_width_style',
			[
				'label' =>esc_html__( 'Icon', 'elkit' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition'	=> [
					'btn_icons__switch'	=> 'yes'
				]
			]
		);
		$this->add_responsive_control(
			'btn_normal_icon_font_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'elkit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', 'rem',
				),
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elkit-button > i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elkit-button > svg'	=> 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'btn_icon_margin',
			[
				'label' =>esc_html__( 'Margin', 'elkit' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .elkit-button i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .elkit-button svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
    }

    protected function render( ) {
        $this->render_raw();
    }

    protected function render_raw( ) {
        $settings = $this->get_settings_for_display();

       // $btn_text = $settings['btn_text'];
        $class = ($settings['btn_class'] != '') ? $settings['btn_class'] : '';
        $id = ($settings['btn_id'] != '') ? 'id='.$settings['btn_id'] : '';
		 $hover_effect = (!empty($settings['hover_effect'])) ? (' elkit-button-'. $settings['hover_effect']) : '';
		if ( ! empty( $settings['btn_url']['url'] ) ) {
			$this->add_link_attributes( 'button', $settings['btn_url'] );
		}
		$icon_align = ($settings['btn_icons'])? ' icon-align-'. $settings['btn_icon_align'] : '';
		echo '<div class="elkit-button-wraper">';
			
			echo '<a ' . $this->get_render_attribute_string( 'button' ) .' class="elkit-button '. esc_attr( $class ) . $hover_effect . $icon_align .'" '. esc_attr($id) .'>';
				if($settings['btn_icon_align'] == 'left'){
					Icons_Manager::render_icon( $settings['btn_icons'], [ 'aria-hidden' => 'true' ] );
				}
				echo esc_html( $settings['btn_text'] );
				if($settings['btn_icon_align'] == 'right'){
					Icons_Manager::render_icon( $settings['btn_icons'], [ 'aria-hidden' => 'true' ] );
				}
			echo '</a>';
		echo '</div>';
    }
}
