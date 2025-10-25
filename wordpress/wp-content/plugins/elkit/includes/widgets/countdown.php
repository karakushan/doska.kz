<?php

use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Elkit_Countdown extends Widget_Base {

	public function get_name() {
		return 'elkit-countdown';
	}

	public function get_title() {
		return esc_html__( 'Countdown', 'elementor-pro' );
	}
	
	public function get_icon() {
		return 'eicon-countdown';
	}

	public function get_keywords() {
		return [ 'countdown', 'number', 'timer', 'time', 'date', 'evergreen' ];
	}
	public function get_categories() {
		return [ 'elkit' ];
	}
	public function get_script_depends() {
		return [ 'elkit-countdown' ];
	}
	protected function register_controls() {
        $this->start_controls_section(
            'section_tab', [
                'label' =>esc_html__( 'Presets', 'elkit' ),
            ]
        );
        $this->end_controls_section();
        // Timer setting


        $this->start_controls_section(
            'elkit_countdown_timer_timer_setting', [
                'label' =>esc_html__( 'Timer Settings  ', 'elkit' ),
            ]
        );


		$this->add_control(
			'elkit_countdown_timer_due_time',
			[
				'label' => esc_html__( 'Countdown Due Date', 'elkit' ),
				'type' => Controls_Manager::DATE_TIME,
				'default' => date("Y-m-d", strtotime("+ 1 day")),
                'description' => esc_html__( 'Set the due date and time', 'elkit' ),
			]
		);
        $this->add_control(
            'elkit_countdown_timer_content_setting',
            [
                'label' => esc_html__( 'Custom Labels', 'elkit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

		$this->add_control(
			'elkit_countdown_timer_weeks_label',
			[
				'label' => esc_html__( 'Weeks', 'elkit' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Weeks', 'elkit' ),
                'condition' => ['elkit_countdown_timer_style' => 'style3'],
			]
		);


		$this->add_control(
			'elkit_countdown_timer_days_label',
			[
				'label' => esc_html__( 'Days', 'elkit' ),
				'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Days', 'elkit' ),
			]
		);

		$this->add_control(
			'elkit_countdown_timer_hours_label',
			[
				'label' => esc_html__( 'Hours', 'elkit' ),
				'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Hours', 'elkit' ),
			]
		);

		$this->add_control(
			'elkit_countdown_timer_minutes_hours_label',
			[
				'label' => esc_html__( 'Minutes', 'elkit' ),
				'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Minutes', 'elkit' ),
			]
		);

		$this->add_control(
			'elkit_countdown_timer_seconds_hours_label',
			[
				'label' => esc_html__( 'Seconds', 'elkit' ),
				'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Seconds', 'elkit' ),
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'elkit_countdown_timer_on_expire_settings',
			[
				'label' => esc_html__( 'Expire Action' , 'elkit' )
			]
		);

		$this->add_control(
			'elkit_countdown_timer_title',
			[
				'label'			=> esc_html__('On Expiry Title', 'elkit'),
				'type'			=> Controls_Manager::TEXTAREA,
                'default'		=> esc_html__('Countdown is finished!','elkit'),
			]
		);

		$this->add_control(
			'elkit_countdown_timer_expiry_content',
			[
				'label'			=> esc_html__('On Expiry Content', 'elkit'),
				'type'			=> Controls_Manager::TEXTAREA,
                'default'		=> esc_html__('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s','elkit'),
			]
		);

        $this->end_controls_section();

        // start style here........

		// content settings styles start
		 $this->start_controls_section(
            'elkit_countdown_timer_content_style', [
                'label'	 =>esc_html__( 'Item box', 'elkit' ),
                'tab'	 => Controls_Manager::TAB_STYLE,

            ]
        );
		// set width for Days
        $this->add_responsive_control(
			'elkit_countdown_timer_days_width',
			[
				'label' => esc_html__( 'Width', 'elkit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => '',
				],
				'selectors'		 => [
                    '{{WRAPPER}} .elkit-timer-item'	=> 'width: {{SIZE}}{{UNIT}};',
                ],

			]
		);
		// set Height for Days
        $this->add_responsive_control(
            'elkit_countdown_timer_days_height', [
                'label'			 =>esc_html__( 'Height', 'elkit' ),
                'type'			 => Controls_Manager::SLIDER,
                'default'		 => [
                    'size' => '',
                    'unit' => 'px',
                ],
                'range'			 => [
                    'px' => [
                        'min'	 => 0,
                        'max'	 => 500,
						'step' => 1,
                    ],
                ],
                'size_units'	 => ['px'],
                'selectors'		 => [
                    '{{WRAPPER}} .elkit-timer-item'	=> 'height: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'elkit_countdown_timer_content_margin_bottom', [
                'label'			 =>esc_html__( 'Margin Bottom', 'elkit' ),
                'type'			 => Controls_Manager::SLIDER,
                'range'			 => [
                    'px' => [
                        'min'	 => 0,
                        'step'	 => 1,
                    ],
                ],
                'desktop_default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 30,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 15,
					'unit' => 'px',
				],
                'size_units'	 => ['px'],
                'selectors'		 => [
                    '{{WRAPPER}} .elkit-timer-item'	=> 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		 $this->end_controls_section();

		 // end content settings


		//Days Style Section
        $this->start_controls_section(
            'elkit_countdown_timer_days_style', [
                'label'	 =>esc_html__( 'Days', 'elkit' ),
                'tab'	 => Controls_Manager::TAB_STYLE,
            ]
        );

		// Start Digits for Days
        $this->add_control(
            'elkit_countdown_timer_days_heading_digits',
            [
                'label' => esc_html__( 'Digits', 'elkit' ),
                'type' => Controls_Manager::HEADING,
            ]
        );
		// Set Digits color for Days
        $this->add_control(
            'elkit_countdown_timer_days_digits_color', [
                'label'		 =>esc_html__( 'Color', 'elkit' ),
                'type'		 => Controls_Manager::COLOR,
                'selectors'	 => [
                    '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-days .elkit-timer-content > span.elkit-timer-count,
                    {{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-days .elkit-timer-count,
                    {{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-days .elkit-timer-count,
                    {{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-days .elkit-timer-count,
                    {{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-days .elkit-timer-count,
					{{WRAPPER}} .elkit-flip-clock .elkit-days .elkit-count' => 'color: {{VALUE}};'
                ],
            ]
        );
		// Set Digits typeography for Days
        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name'		 => 'elkit_countdown_timer_days_digits_typography_group',
                'selector'	 => '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-days .elkit-timer-content > span.elkit-timer-count,
				{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-days .elkit-timer-count,
				{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-days .elkit-timer-count,
				{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-days .elkit-timer-count,
				{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-days .elkit-timer-count,
				{{WRAPPER}} .elkit-flip-clock .elkit-days .elkit-count',
            ]
        );

		// Set Digits margin for Days
        $this->add_responsive_control(
            'elkit_countdown_timer_days_digits_margin_bottom', [
                'label'			 =>esc_html__( 'Margin Bottom', 'elkit' ),
                'type'			 => Controls_Manager::SLIDER,
                'range'			 => [
                    'px' => [
                        'min'	 => -30,
                        'step'	 => 1,
                    ],
                ],
                'size_units'	 => ['px'],
                'selectors'		 => [
                    '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-days .elkit-timer-content > span.elkit-timer-count,
					{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-days .elkit-timer-count,
					{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-days .elkit-timer-count,
					{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-days .elkit-timer-count,
					{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-days .elkit-timer-count,
					{{WRAPPER}} .elkit-flip-clock .elkit-days .elkit-count'	=> 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'elkit_countdown_timer_days_label_title',
            [
                'label' => esc_html__( 'Label', 'elkit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'elkit_countdown_timer_days_label_color', [
                'label'		 =>esc_html__( 'Color', 'elkit' ),
                'type'		 => Controls_Manager::COLOR,
                'selectors'	 => [
                    '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-days .elkit-timer-content > span.elkit-timer-title,
                    {{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-days .elkit-timer-title,
                    {{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-days .elkit-timer-title,
                    {{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-days .elkit-timer-title,
                    {{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-days .elkit-timer-title,
					{{WRAPPER}} .elkit-flip-clock .elkit-days .elkit-label' => 'color: {{VALUE}};'
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name'		 => 'elkit_countdown_timer_days_label_typography_group',
                'selector'	 => '{{WRAPPER}} .elkit-flip-clock .elkit-days .elkit-label,
								{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-days .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-days .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-days .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-days .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-days .elkit-timer-title',
                'fields_options' => [
                    // Inner control name
                    'font_weight' => [
                        // Inner control settings
							'default' => '400',
                    ],
                    'font_family' => [
                        'default' => 'Lato',
                    ],
                    'font_size' => [ 'default' => [ 'unit' => 'px', 'size' => 14 ] ]
                ],
                'seperator' => 'before'
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'elkit_countdown_timer_days_label_background_group',
                'label' => esc_html__( 'Background', 'elkit' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .elkit-flip-clock .elkit-days .elkit-label,
								{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-days .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-days .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-days .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-days .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-days .elkit-timer-title
								',
                'seperator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'elkit_countdown_timer_days_label_border_color',
                'label' => esc_html__( 'Border', 'elkit' ),
                'selector' => ' {{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-days .elkit-timer-content > span.elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-days .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-days .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-days .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-days .elkit-timer-title,
								{{WRAPPER}} .elkit-flip-clock .elkit-days .elkit-label
								',
            ]
        );
        $this->add_responsive_control(
            'elkit_countdown_timer_days_label_border_radious_open',
            [
                'label' => esc_html__( 'Border Radius', 'elkit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
								'{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-days .elkit-timer-content > span.elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-days .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-days .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-days .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-days .elkit-timer-title,
								{{WRAPPER}} .elkit-flip-clock .elkit-days .elkit-label
					' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'elkit_countdown_timer_days_label_box_shadow_group',
                'label' => esc_html__( 'Box Shadow', 'elkit' ),
                'selector' => '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-days .elkit-timer-content > span.elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-days .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-days .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-days .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-days .elkit-timer-title,
								{{WRAPPER}} .elkit-flip-clock .elkit-days .elkit-label
				',
            ]
        );

		$this->add_responsive_control(
            'elkit_countdown_timer_days_lebel_margin',
            [
                'label' => esc_html__( 'Margin', 'elkit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],

                'selectors' => [
								'{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-days .elkit-timer-content > span.elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-days .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-days .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-days .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-days .elkit-timer-title,
								{{WRAPPER}} .elkit-flip-clock .elkit-days .elkit-label
					' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
// start genaral settings
        $this->add_control(
            'elkit_countdown_timer_days_heading_general',
            [
                'label' => esc_html__( 'General', 'elkit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );



        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'elkit_countdown_timer_days_background_group',
                'label' => esc_html__( 'Background', 'elkit' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-days',
                'seperator' => 'before'
            ]
        );

		// overlay color

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'elkit_countdown_timer_days_border_color_group',
                'label' => esc_html__( 'Border', 'elkit' ),
                'selector' => '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-days',
            ]
        );
        $this->add_responsive_control(
            'elkit_countdown_timer_days_border_radious_open',
            [
                'label' => esc_html__( 'Border Radius', 'elkit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-days' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'elkit_countdown_timer_days_box_shadow_group',
                'label' => esc_html__( 'Box Shadow', 'elkit' ),
                'selector' => '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-days',
            ]
        );

        $this->end_controls_section();

        // end digit section styles for Days


        //Hours Style Section start
        $this->start_controls_section(
            'elkit_countdown_timer_hours_style', [
                'label'	 =>esc_html__( 'Hours', 'elkit' ),
                'tab'	 => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'elkit_countdown_timer_hours_heading_digits',
            [
                'label' => esc_html__( 'Digits', 'elkit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'elkit_countdown_timer_hours_digits_color', [
                'label'		 =>esc_html__( 'Color', 'elkit' ),
                'type'		 => Controls_Manager::COLOR,
				'selectors'	 => [
								'{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-hours .elkit-timer-content > span.elkit-timer-count,
								{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-hours .elkit-timer-count,
								{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-hours .elkit-timer-count,
								{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-hours .elkit-timer-count,
								{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-hours .elkit-timer-count,
								{{WRAPPER}} .elkit-flip-clock .elkit-hrs .elkit-count' => 'color: {{VALUE}};'
							],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name'		 => 'elkit_countdown_timer_hours_digits_typography_group',
                'selector'	 => '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-hours .elkit-timer-content > span.elkit-timer-count,
								{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-hours .elkit-timer-count,
								{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-hours .elkit-timer-count,
								{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-hours .elkit-timer-count,
								{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-hours .elkit-timer-count,
								{{WRAPPER}} .elkit-flip-clock .elkit-hrs .elkit-count',
            ]
        );
        $this->add_responsive_control(
            'elkit_countdown_timer_hours_digits_margin_bottom', [
                'label'			 =>esc_html__( 'Margin Bottom', 'elkit' ),
                'type'			 => Controls_Manager::SLIDER,
                'default'		 => [
                    'size' => '',
                ],
                'range'			 => [
                    'px' => [
                        'min'	 => -30,
                        'step'	 => 1,
                    ],
                ],
                'size_units'	 => ['px'],

				'selectors'		 => [
                    '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-hours .elkit-timer-content > span.elkit-timer-count,
					{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-hours .elkit-timer-count,
					{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-hours .elkit-timer-count,
					{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-hours .elkit-timer-count,
					{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-hours .elkit-timer-count,
					{{WRAPPER}} .elkit-flip-clock .elkit-hrs .elkit-count'	=> 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'elkit_countdown_timer_hours_label_title',
            [
                'label' => esc_html__( 'Label', 'elkit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'elkit_countdown_timer_hours_label_color', [
                'label'		 =>esc_html__( 'Color', 'elkit' ),
                'type'		 => Controls_Manager::COLOR,

				 'selectors'	 => [
                    '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-hours .elkit-timer-content > span.elkit-timer-title,
                    {{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-hours .elkit-timer-title,
                    {{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-hours .elkit-timer-title,
                    {{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-hours .elkit-timer-title,
                    {{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-hours .elkit-timer-title,
					{{WRAPPER}} .elkit-flip-clock .elkit-hrs .elkit-label' => 'color: {{VALUE}};'
                ],
            ]
        );

		 $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name'		 => 'elkit_countdown_timer_hours_label_typography_group',
                'selector'	 => '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-hours .elkit-timer-content > span.elkit-timer-title,
                {{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-hours .elkit-timer-title,
                {{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-hours .elkit-timer-title,
                {{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-hours .elkit-timer-title,
                {{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-hours .elkit-timer-title,
                {{WRAPPER}} .elkit-flip-clock .elkit-hrs .elkit-label',
                'fields_options' => [
                    // Inner control name
                    'font_weight' => [
                        // Inner control settings
							'default' => '400',
                    ],
                    'font_family' => [
                        'default' => 'Lato',
                    ],
                    'font_size' => [ 'default' => [ 'unit' => 'px', 'size' => 14 ] ]
                ],
                'seperator' => 'before'
            ]
        );

		$this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'elkit_countdown_timer_hours_label_background_group',
                'label' => esc_html__( 'Background', 'elkit' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .elkit-flip-clock .elkit-hrs .elkit-label,
                {{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-hours .elkit-timer-title,
                {{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-hours .elkit-timer-title,
                {{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-hours .elkit-timer-title,
                {{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-hours .elkit-timer-title,
                {{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-hours .elkit-timer-title
								',
                'seperator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'elkit_countdown_timer_hours_label_border_color',
                'label' => esc_html__( 'Border', 'elkit' ),
                'selector' => ' {{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-hours .elkit-timer-content > span.elkit-timer-title,
                {{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-hours .elkit-timer-title,
                {{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-hours .elkit-timer-title,
                {{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-hours .elkit-timer-title,
                {{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-hours .elkit-timer-title,
                {{WRAPPER}} .elkit-flip-clock .elkit-hrs .elkit-label
                ',
            ]
        );
        $this->add_responsive_control(
            'elkit_countdown_timer_hours_label_border_radious_open',
            [
                'label' => esc_html__( 'Border Radius', 'elkit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
								'{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-hours .elkit-timer-content > span.elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-hours .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-hours .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-hours .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-hours .elkit-timer-title,
								{{WRAPPER}} .elkit-flip-clock .elkit-hrs .elkit-label
					' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'elkit_countdown_timer_hours_label_box_shadow_group',
                'label' => esc_html__( 'Box Shadow', 'elkit' ),
                'selector' => '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-hours .elkit-timer-content > span.elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-hours .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-hours .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-hours .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-hours .elkit-timer-title,
								{{WRAPPER}} .elkit-flip-clock .elkit-hrs .elkit-label
				',
            ]
        );


		$this->add_responsive_control(
            'elkit_countdown_timer_hours_lebel_margin',
            [
                'label' => esc_html__( 'Margin', 'elkit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],

                'selectors' => [
								'{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-hours .elkit-timer-content > span.elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-hours .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-hours .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-hours .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-hours .elkit-timer-title,
								{{WRAPPER}} .elkit-flip-clock .elkit-hrs .elkit-label
					' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

// start genaral styles
        $this->add_control(
            'elkit_countdown_timer_hours_heading_general',
            [
                'label' => esc_html__( 'General', 'elkit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'elkit_countdown_timer_hours_background',
                'label' => esc_html__( 'Background', 'elkit' ),
                'types' => [ 'classic', 'gradient' ],

				'selector' => '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-hours]',
            ]
        );

       $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'elkit_countdown_timer_hours_border_color_group',
                'label' => esc_html__( 'Border', 'elkit' ),
                'selector' => '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-hours',
            ]
        );

		$this->add_responsive_control(
            'elkit_countdown_timer_hours_border_radious_open',
            [
                'label' => esc_html__( 'Border Radius', 'elkit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-hours' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
       $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'elkit_countdown_timer_hours_box_shadow_group',
                'label' => esc_html__( 'Box Shadow', 'elkit' ),
                'selector' => '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-hours .elkit-inner-container',
            ]
        );

        $this->end_controls_section();


        //Minutes Style Section

        $this->start_controls_section(
            'elkit_countdown_timer_minutes_style', [
                'label'	 =>esc_html__( 'Minutes', 'elkit' ),
                'tab'	 => Controls_Manager::TAB_STYLE,
            ]
        );

		// Start Digits for Days
        $this->add_control(
            'elkit_countdown_timer_minutes_heading_digits',
            [
                'label' => esc_html__( 'Digits', 'elkit' ),
                'type' => Controls_Manager::HEADING,
            ]
        );
		// Set Digits color for Days
        $this->add_control(
            'elkit_countdown_timer_minutes_digits_color', [
                'label'		 =>esc_html__( 'Color', 'elkit' ),
                'type'		 => Controls_Manager::COLOR,
                'selectors'	 => [
                    '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-minutes .elkit-timer-content > span.elkit-timer-count,
                    {{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-minutes .elkit-timer-count,
                    {{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-minutes .elkit-timer-count,
                    {{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-minutes .elkit-timer-count,
                    {{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-minutes .elkit-timer-count,
					{{WRAPPER}} .elkit-flip-clock .elkit-mins .elkit-count' => 'color: {{VALUE}};'
                ],
            ]
        );
		// Set Digits typeography for Days
        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name'		 => 'elkit_countdown_timer_minutes_digits_typography_group',
                'selector'	 => '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-minutes .elkit-timer-content > span.elkit-timer-count,
				{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-minutes .elkit-timer-count,
				{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-minutes .elkit-timer-count,
				{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-minutes .elkit-timer-count,
				{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-minutes .elkit-timer-count,
				{{WRAPPER}} .elkit-flip-clock .eins .eount, {{WRAPPER}} .elkit-flip-clock .elkit-mins .elkit-count',
            ]
        );

		// Set Digits margin for Days
        $this->add_responsive_control(
            'elkit_countdown_timer_minutes_digits_margin_bottom', [
                'label'			 =>esc_html__( 'Margin Bottom', 'elkit' ),
                'type'			 => Controls_Manager::SLIDER,
                'default'		 => [
                    'size' => '',
                ],
                'range'			 => [
                    'px' => [
                        'min'	 => -30,
                        'step'	 => 1,
                    ],
                ],
                'size_units'	 => ['px'],
                'selectors'		 => [
                    '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-minutes .elkit-timer-content > span.elkit-timer-count,
					{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-minutes .elkit-timer-count,
					{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-minutes .elkit-timer-count,
					{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-minutes .elkit-timer-count,
					{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-minutes .elkit-timer-count,
					{{WRAPPER}} .elkit-flip-clock .elkit-mins .elkit-count'	=> 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'elkit_countdown_timer_minutes_label_title',
            [
                'label' => esc_html__( 'Label', 'elkit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'elkit_countdown_timer_minutes_label_color', [
                'label'		 =>esc_html__( 'Color', 'elkit' ),
                'type'		 => Controls_Manager::COLOR,
                'selectors'	 => [
                    '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-minutes .elkit-timer-content > span.elkit-timer-title,
                    {{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-minutes .elkit-timer-title,
                    {{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-minutes .elkit-timer-title,
                    {{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-minutes .elkit-timer-title,
                    {{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-minutes .elkit-timer-title,
					{{WRAPPER}} .elkit-flip-clock .elkit-mins .elkit-label' => 'color: {{VALUE}};'
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name'		 => 'elkit_countdown_timer_minutes_label_typography_group',
                'selector'	 => '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-minutes .elkit-timer-content > span.elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-minutes .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-minutes .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-minutes .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-minutes .elkit-timer-title,
								{{WRAPPER}} .elkit-flip-clock .elkit-mins .elkit-label',
                'fields_options' => [
                    // Inner control name
                    'font_weight' => [
                        // Inner control settings
							'default' => '400',
                    ],
                    'font_family' => [
                        'default' => 'Lato',
                    ],
                    'font_size' => [ 'default' => [ 'unit' => 'px', 'size' => 14 ] ]
                ],
                'seperator' => 'before'
            ]
        );

		$this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'elkit_countdown_timer_minutes_label_background_group',
                'label' => esc_html__( 'Background', 'elkit' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .elkit-flip-clock .elkit-mins .elkit-label,
								{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-minutes .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-minutes .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-minutes .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-minutes .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-minutes .elkit-timer-title
								',
                'seperator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'elkit_countdown_timer_minutes_label_border_color',
                'label' => esc_html__( 'Border', 'elkit' ),
                'selector' => ' {{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-minutes .elkit-timer-content > span.elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-minutes .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-minutes .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-minutes .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-minutes .elkit-timer-title,
								{{WRAPPER}} .elkit-flip-clock .elkit-mins .elkit-label
								',
            ]
        );
        $this->add_responsive_control(
            'elkit_countdown_timer_minutes_label_border_radious_open',
            [
                'label' => esc_html__( 'Border Radius', 'elkit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
								'{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-minutes .elkit-timer-content > span.elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-minutes .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-minutes .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-minutes .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-minutes .elkit-timer-title,
								{{WRAPPER}} .elkit-flip-clock .elkit-mins .elkit-label
					' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'elkit_countdown_timer_minutes_label_box_shadow_group',
                'label' => esc_html__( 'Box Shadow', 'elkit' ),
                'selector' => '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-minutes .elkit-timer-content > span.elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-minutes .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-minutes .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-minutes .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-minutes .elkit-timer-title,
								{{WRAPPER}} .elkit-flip-clock .elkit-mins .elkit-label
				',
            ]
        );

		$this->add_responsive_control(
            'elkit_countdown_timer_minutes_lebel_margin',
            [
                'label' => esc_html__( 'Margin', 'elkit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],

                'selectors' => [
								'{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-minutes .elkit-timer-content > span.elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-minutes .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-minutes .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-minutes .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-minutes .elkit-timer-title,
								{{WRAPPER}} .elkit-flip-clock .elkit-mins .elkit-label
					' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


// start genaral styles
        $this->add_control(
            'elkit_countdown_timer_minutes_heading_general',
            [
                'label' => esc_html__( 'General', 'elkit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'elkit_countdown_timer_minutes_background',
                'label' => esc_html__( 'Background', 'elkit' ),
                'types' => [ 'classic', 'gradient' ],

				'selector' => '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-minutes',
            ]
        );

       $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'elkit_countdown_timer_minutes_border_color_group',
                'label' => esc_html__( 'Border', 'elkit' ),
                'selector' => '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-minutes',
            ]
        );

		$this->add_responsive_control(
            'elkit_countdown_timer_minutes_border_radious_open',
            [
                'label' => esc_html__( 'Border Radius', 'elkit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-minutes' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
       $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'elkit_countdown_timer_minutes_box_shadow_group',
                'label' => esc_html__( 'Box Shadow', 'elkit' ),
                'selector' => '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-minutes',
            ]
        );


        $this->end_controls_section();

		// end minutes style section


        //Seconds Style Section

        $this->start_controls_section(
            'elkit_countdown_timer_seconds_style', [
                'label'	 =>esc_html__( 'Seconds', 'elkit' ),
                'tab'	 => Controls_Manager::TAB_STYLE,
            ]
        );

		// Start Digits for Days
        $this->add_control(
            'elkit_countdown_timer_seconds_heading_digits',
            [
                'label' => esc_html__( 'Digits', 'elkit' ),
                'type' => Controls_Manager::HEADING,
            ]
        );
		// Set Digits color for Days
        $this->add_control(
            'elkit_countdown_timer_seconds_digits_color', [
                'label'		 =>esc_html__( 'Color', 'elkit' ),
                'type'		 => Controls_Manager::COLOR,
                'selectors'	 => [
                    '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-seconds .elkit-timer-content > span.elkit-timer-count,
                    {{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-seconds .elkit-timer-count,
                    {{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-seconds .elkit-timer-count,
                    {{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-seconds .elkit-timer-count,
                    {{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-seconds .elkit-timer-count,
					{{WRAPPER}} .elkit-flip-clock .elkit-secs .elkit-count' => 'color: {{VALUE}};'
                ],
            ]
        );
		// Set Digits typeography for Days
        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name'		 => 'elkit_countdown_timer_seconds_digits_typography_group',
                'selector'	 => '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-seconds .elkit-timer-content > span.elkit-timer-count,
				{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-seconds .elkit-timer-count,
				{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-seconds .elkit-timer-count,
				{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-seconds .elkit-timer-count,
				{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-seconds .elkit-timer-count,
				{{WRAPPER}} .elkit-flip-clock .elkit-secs .elkit-count',
            ]
        );

		// Set Digits margin for Days
        $this->add_responsive_control(
            'elkit_countdown_timer_seconds_digits_margin_bottom', [
                'label'			 =>esc_html__( 'Margin Bottom', 'elkit' ),
                'type'			 => Controls_Manager::SLIDER,
                'default'		 => [
                    'size' => '',
                ],
                'range'			 => [
                    'px' => [
                        'min'	 => -30,
                        'step'	 => 1,
                    ],
                ],
                'size_units'	 => ['px'],
                'selectors'		 => [
                    '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-seconds .elkit-timer-content > span.elkit-timer-count,
					{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-seconds .elkit-timer-count,
					{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-seconds .elkit-timer-count,
					{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-seconds .elkit-timer-count,
					{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-seconds .elkit-timer-count,
					{{WRAPPER}} .elkit-flip-clock .elkit-secs .elkit-count'	=> 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'elkit_countdown_timer_seconds_label_title',
            [
                'label' => esc_html__( 'Label', 'elkit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'elkit_countdown_timer_seconds_label_color', [
                'label'		 =>esc_html__( 'Color', 'elkit' ),
                'type'		 => Controls_Manager::COLOR,
                'selectors'	 => [
                    '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-seconds .elkit-timer-content > span.elkit-timer-title,
                    {{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-seconds .elkit-timer-title,
                    {{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-seconds .elkit-timer-title,
                    {{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-seconds .elkit-timer-title,
                    {{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-seconds .elkit-timer-title,
					{{WRAPPER}} .elkit-flip-clock .elkit-secs .elkit-label' => 'color: {{VALUE}};'
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name'		 => 'elkit_countdown_timer_seconds_label_typography_group',
                'selector'	 => '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-seconds .elkit-timer-content > span.elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-seconds .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-seconds .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-seconds .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-seconds .elkit-timer-title,
								{{WRAPPER}} .elkit-flip-clock .elkit-secs .elkit-label',
                'fields_options' => [
                    // Inner control name
                    'font_weight' => [
                        // Inner control settings
							'default' => '400',
                    ],
                    'font_family' => [
                        'default' => 'Lato',
                    ],
                    'font_size' => [ 'default' => [ 'unit' => 'px', 'size' => 14 ] ]
                ],
                'seperator' => 'before'
            ]
        );

		$this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'elkit_countdown_timer_seconds_label_background_group',
                'label' => esc_html__( 'Background', 'elkit' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .elkit-flip-clock .elkit-secs .elkit-label,
								{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-seconds .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-seconds .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-seconds .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-seconds .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-seconds .elkit-timer-title
								',
                'seperator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'elkit_countdown_timer_seconds_label_border_color',
                'label' => esc_html__( 'Border', 'elkit' ),
                'selector' => ' {{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-seconds .elkit-timer-content > span.elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-seconds .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-seconds .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-seconds .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-seconds .elkit-timer-title,
								{{WRAPPER}} .elkit-flip-clock .elkit-secs .elkit-label
								',
            ]
        );
        $this->add_responsive_control(
            'elkit_countdown_timer_seconds_label_border_radious_open',
            [
                'label' => esc_html__( 'Border Radius', 'elkit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
								'{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-seconds .elkit-timer-content > span.elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-2 .elkit-timer-item.elkit-seconds .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3 .elkit-timer-item.elkit-seconds .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-3.elkit-version-box .elkit-timer-item.elkit-seconds .elkit-timer-title,
								{{WRAPPER}} .elkit-countdown-timer-4 .elkit-timer-item.elkit-seconds .elkit-timer-title,
								{{WRAPPER}} .elkit-flip-clock .elkit-secs .elkit-label
					' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'elkit_countdown_timer_seconds_label_box_shadow_group',
                'label' => esc_html__( 'Box Shadow', 'elkit' ),
                'selector' => '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-seconds .elkit-timer-content > span.elkit-timer-title',
            ]
        );

		$this->add_responsive_control(
            'elkit_countdown_timer_seconds_lebel_margin',
            [
                'label' => esc_html__( 'Margin', 'elkit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],

                'selectors' => [
								'{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-seconds .elkit-timer-content > span.elkit-timer-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

// start genaral styles
        $this->add_control(
            'elkit_countdown_timer_seconds_heading_general',
            [
                'label' => esc_html__( 'General', 'elkit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'elkit_countdown_timer_seconds_background',
                'label' => esc_html__( 'Background', 'elkit' ),
                'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-seconds',
            ]
        );

       $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'elkit_countdown_timer_seconds_border_color_group',
                'label' => esc_html__( 'Border', 'elkit' ),
                'selector' => '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-seconds',
            ]
        );

		$this->add_responsive_control(
            'elkit_countdown_timer_seconds_border_radious_open',
            [
                'label' => esc_html__( 'Border Radius', 'elkit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-seconds' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
       $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'elkit_countdown_timer_seconds_box_shadow_group',
                'label' => esc_html__( 'Box Shadow', 'elkit' ),
                'selector' => '{{WRAPPER}} .elkit-countdown-timer .elkit-timer-item.elkit-seconds',
            ]
        );
        $this->end_controls_section();
		// end seconds style section
    }

    protected function render( ) {
        echo '<div class="elkit-wid-con" >';
            $this->render_raw();
        echo '</div>';
    }

    protected function render_raw( ) {
        $settings = $this->get_settings_for_display();
        extract($settings);

	   $data = '';
		if(isset($elkit_countdown_timer_weeks_label)){
			$data .= ' data-date-elkit-week="'.esc_attr($elkit_countdown_timer_weeks_label).'"';
		}
		if(isset($elkit_countdown_timer_days_label)){
			$data .= ' data-date-elkit-day="'.esc_attr($elkit_countdown_timer_days_label).'"';
		}
		if(isset($elkit_countdown_timer_hours_label)){
			$data .= ' data-date-elkit-hour="'.esc_attr($elkit_countdown_timer_hours_label).'"';
		}
		if(isset($elkit_countdown_timer_minutes_hours_label)){
			$data .= ' data-date-elkit-minute="'.esc_attr($elkit_countdown_timer_minutes_hours_label).'"';
		}
		if(isset($elkit_countdown_timer_seconds_hours_label)){
			$data .= ' data-date-elkit-second="'.esc_attr($elkit_countdown_timer_seconds_hours_label).'"';
		}
		if(isset($elkit_countdown_timer_due_time)){
			$data .= ' data-elkit-countdown="'.esc_attr($elkit_countdown_timer_due_time).'"';
        }

        $data .= ' data-finish-title="'.esc_attr($elkit_countdown_timer_title).'"';
        $data .= ' data-finish-content="'.esc_attr($elkit_countdown_timer_expiry_content).'"';
		
        ?>
		<div class="elkit-countdown-timer elkit-countdown text-center" <?php echo wp_kses_post($data); ?>></div>
		<?php
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
		echo '<script>
			( function( $ ) {
				Countdown_Timer();				
			} )( jQuery );
		</script>';
		};
    }
}