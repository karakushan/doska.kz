<?php
namespace HFB\WidgetsManager\Widgets;
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


class Back_To_Top_Button extends Widget_Base {

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
		return 'hfb-btt-button';
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
		return __( 'Back To Top Button', 'header-footer-builder' );
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
		return 'hfb-icon-menu-cart';
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
		return [ 'hfb-widgets' ];
	}

    protected function register_controls() {


		$this->start_controls_section(
			'btn_section_content',
			array(
				'label' => esc_html__( 'Content', 'header-footer-builder' ),
			)
		);

		$this->add_control(
			'btn_text',
			[
				'label' =>esc_html__( 'Label', 'header-footer-builder' ),
				'type' => Controls_Manager::TEXT,
				'default' =>esc_html__( 'Submit', 'header-footer-builder' ),
				'placeholder' =>esc_html__( 'Submit', 'header-footer-builder' ),
				'dynamic' => [
                    'active' => true,
                ],
			]
		);


		$this->add_control(
			'btn_url',
			[
				'label' =>esc_html__( 'URL', 'header-footer-builder' ),
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
                'label' => esc_html__( 'Settings', 'header-footer-builder' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
		);
		
		$this->add_control(
            'btn_icons__switch',
            [
                'label' => esc_html__('Add icon? ', 'header-footer-builder'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' =>esc_html__( 'Yes', 'header-footer-builder' ),
                'label_off' =>esc_html__( 'No', 'header-footer-builder' ),
            ]
		);
		
		$this->add_control(
			'btn_icons',
			[
				'label' =>esc_html__( 'Icon', 'header-footer-builder' ),
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
                'label' =>esc_html__( 'Icon Position', 'header-footer-builder' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' =>esc_html__( 'Before', 'header-footer-builder' ),
                    'right' =>esc_html__( 'After', 'header-footer-builder' ),
                ],
                'condition'	=> [
					'btn_icons__switch'	=> 'yes'
				]
            ]
        );
		$this->add_responsive_control(
			'btn_align',
			[
				'label' =>esc_html__( 'Alignment', 'header-footer-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' =>esc_html__( 'Left', 'header-footer-builder' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' =>esc_html__( 'Center', 'header-footer-builder' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' =>esc_html__( 'Right', 'header-footer-builder' ),
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
			    'label' => esc_html__( 'Class', 'header-footer-builder' ),
			    'type' => Controls_Manager::TEXT,
			    'placeholder' => esc_html__( 'Class Name', 'header-footer-builder' ),
		    ]
	    );

	    $this->add_control(
		    'btn_id',
		    [
			    'label' => esc_html__( 'id', 'header-footer-builder' ),
			    'type' => Controls_Manager::TEXT,
			    'placeholder' => esc_html__( 'ID', 'header-footer-builder' ),
		    ]
	    );


		$this->end_controls_section();


        $this->start_controls_section(
			'btn_section_style',
			[
				'label' =>esc_html__( 'Button', 'header-footer-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label'			=> esc_html__( 'Width (%)', 'header-footer-builder' ),
				'type'			=> Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'		=> [
					'{{WRAPPER}} .hfb-button' => 'width: {{SIZE}}{{UNIT}};',
				]
			]
		);
		$this->add_responsive_control(
			'btn_padding',
			[
				'label' =>esc_html__( 'Padding', 'header-footer-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'btn_margin',
			[
				'label' =>esc_html__( 'Margin', 'header-footer-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'label' =>esc_html__( 'Typography', 'header-footer-builder' ),
				'selector' => '{{WRAPPER}} .hfb-button',
			]
		);

        $this->add_group_control(
        	Group_Control_Text_Shadow::get_type(),
        	[
        		'name' => 'btn_shadow',
        		'selector' => '{{WRAPPER}} .hfb-button',
        	]
        );

		$this->start_controls_tabs( 'btn_tabs_style' );

		$this->start_controls_tab(
			'btn_tabnormal',
			[
				'label' =>esc_html__( 'Normal', 'header-footer-builder' ),
			]
		);

		$this->add_control(
			'btn_text_color',
			[
				'label' =>esc_html__( 'Text Color', 'header-footer-builder' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .hfb-button' => 'color: {{VALUE}};',
					'{{WRAPPER}} .hfb-button svg path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
				'name'     => 'btn_bg_color',
				'default' => '',
				'selector' => '{{WRAPPER}} .hfb-button',
            )
        );
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
			  'name' => 'btn_box_shadow',
			  'selector' => '{{WRAPPER}} .hfb-button',
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'btn_tab_button_hover',
			[
				'label' =>esc_html__( 'Hover', 'header-footer-builder' ),
			]
		);

		$this->add_control(
			'btn_hover_color',
			[
				'label' =>esc_html__( 'Text Color', 'header-footer-builder' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .hfb-button:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .hfb-button:hover svg path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

	    $this->add_group_control(
		    Group_Control_Background::get_type(),
		    array(
			    'name'     => 'btn_bg_hover_color',
			    'default' => '',
			    'selector' => '{{WRAPPER}} .hfb-button:hover',
		    )
	    );
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
			  'name' => 'btn_box_shadow_hover',
			  'selector' => '{{WRAPPER}} .hfb-button:hover',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_control(
			'button_border_section',
			[
				'label' => esc_html__( 'Border', 'header-footer-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'user_menu_icon_box_border',
				'label' => esc_html__( 'Icon Box Border', 'header-footer-builder' ),
				'selector' => '{{WRAPPER}} .hfb-button',
			]
		);
		$this->start_controls_tabs( 'tabs_button_border_style' );
		$this->start_controls_tab(
			'btn_tab_border_normal',
			[
				'label' =>esc_html__( 'Normal', 'header-footer-builder' ),
			]
		);
		$this->add_responsive_control(
			'btn_border_radius',
			[
				'label' =>esc_html__( 'Border Radius', 'header-footer-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%'],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '' ,
					'left' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .hfb-button' =>  'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'btn_tab_button_border_hover',
			[
				'label' =>esc_html__( 'Hover', 'header-footer-builder' ),
			]
		);
		$this->add_control(
			'btn_hover_border_color',
			[
				'label' => esc_html_x( 'Color', 'Border Control', 'header-footer-builder' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .hfb-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'btn_border_radius_hover',
			[
				'label' =>esc_html__( 'Border Radius', 'header-footer-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%'],
				'selectors' => [
					'{{WRAPPER}} .hfb-button:hover' =>  'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		


		$this->end_controls_section();

        $this->start_controls_section(
			'btn_icon_width_style',
			[
				'label' =>esc_html__( 'Icon', 'header-footer-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition'	=> [
					'btn_icons__switch'	=> 'yes'
				]
			]
		);
		$this->add_responsive_control(
			'btn_normal_icon_font_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'header-footer-builder' ),
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
					'{{WRAPPER}} .hfb-button > i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .hfb-button > svg'	=> 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'btn_icon_margin',
			[
				'label' =>esc_html__( 'Margin', 'header-footer-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-button i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .hfb-button svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		if ( ! empty( $settings['btn_url']['url'] ) ) {
			$this->add_link_attributes( 'button', $settings['btn_url'] );
		}
		$icon_align = ($settings['btn_icons'])? ' icon-align-'. $settings['btn_icon_align'] : '';
		echo '<div class="hfb-back-top-button">';
			echo '<a href="#" class="pacz-go-top off">';
				Icons_Manager::render_icon( $settings['btn_icons'], [ 'aria-hidden' => 'true' ] );
			echo '</a>';
		echo '</div>';
    }
}
