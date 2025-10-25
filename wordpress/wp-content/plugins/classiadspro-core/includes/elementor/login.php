<?php
/**
 * Elementor Classes.
 *
 * @package header-footer-elementor
 */

namespace HFB\WidgetsManager\Widgets;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * HFB Cart Widget
 *
 * @since 1.4.0
 */
class Pacz_Elementor_Login extends Widget_Base {
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		
		//add_action('wp_enqueue_scripts', array($this, 'scripts'));
		$this->scripts();
	}
	public function scripts() {
			wp_register_style( 'hfb-user-menu', PCPT_ASSETS_URL .'css/user-menu.css');
			wp_enqueue_style('hfb-user-menu');	
	}
	
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
		return 'pacz-login-menu';
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
		return __( 'Login Menu', 'header-footer-elementor' );
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
	
	/* public function get_style_depends() {

		wp_register_style( 'hfb-user-menu', PCPT_ASSETS_URL .'css/user-menu.css');
		return [
			'hfb-user-menu'
		];

	} */

	protected function register_controls(){
		
		$this->container();
        $this->login();
		$this->register();
		$this->divider();
		$this->user_menu();
		$this->login_styling();
		$this->register_styling();
		$this->divider_styling();
		$this->user_menu_styling();
		$this->user_menu_dropdown_styling();
    }
	protected function container() {
		$this->start_controls_section(
            'user_menu_container_section',
            [
                'label' => esc_html__('Container', 'pacz'),
            ]
        );
		$this->add_responsive_control(
            'user_menu_container_align',
            [
                'label' => esc_html__( 'Alignment', 'pacz' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'pacz' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'pacz' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'pacz' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
				'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'user_menu_container_direction',
            [
                'label' => esc_html__( 'Direction', 'pacz' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'ltr' => [
                        'title' => esc_html__( 'Left to right', 'pacz' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'rtl' => [
                        'title' => esc_html__( 'Right to left', 'pacz' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
				'selectors' => [
                    '{{WRAPPER}}' => 'direction: {{VALUE}};',
                ],
            ]
        );
		$this->end_controls_section();
	}
	protected function login() {
		$this->start_controls_section(
            'login_button_section',
            [
                'label' => esc_html__('Login Button', 'pacz'),
            ]
        );

       $this->add_responsive_control(
			'login_button',
			[
				'label' => esc_html__( 'Login Button', 'pacz' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'pacz' ),
				'label_off' => esc_html__( 'Hide', 'pacz' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
        );
		$this->add_responsive_control(
			'login_button_style',
			[
				'label' => esc_html__( 'Choose Style', 'pacz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'both',
				'options' => [
					'icon'  => esc_html__( 'Icon', 'pacz' ),
					'text' => esc_html__( 'Text', 'pacz' ),
					'both' => esc_html__( 'Both', 'pacz' ),
				],
			]
        );
        $this->add_responsive_control(
            'login_button_icon',
            [
                'label'         => esc_html__('Login Button Icon', 'pacz'),
                'label_block'   => true,
                'type'          => Controls_Manager::ICONS,
				'condition' => [
                    'login_button_style' => ['icon', 'both'],
                ]
            ]
        );

        $this->add_responsive_control(
            'login_button_text',
            [
                'label' => esc_html__('Button Text', 'pacz'),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => 'login',
				'condition' => [
					'login_button_style' => ['text', 'both'],
                ]
            ]
        );
        $this->add_responsive_control(
            'login_button_url',
            [
                'label' => esc_html__( 'Url', 'pacz' ),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://designinvento.net/', 'pacz' ),
                'show_external' => true,
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->end_controls_section();
	}
	protected function register() {
		$this->start_controls_section(
            'register_button_section',
            [
                'label' => esc_html__('Register Button', 'pacz'),
            ]
        );

       $this->add_responsive_control(
			'register_button',
			[
				'label' => esc_html__( 'Register Button', 'pacz' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'pacz' ),
				'label_off' => esc_html__( 'Hide', 'pacz' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
        );
		$this->add_responsive_control(
			'register_button_style',
			[
				'label' => esc_html__( 'Choose Style', 'pacz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'both',
				'options' => [
					'icon'  => esc_html__( 'Icon', 'pacz' ),
					'text' => esc_html__( 'Text', 'pacz' ),
					'both' => esc_html__( 'Both', 'pacz' ),
				],
			]
        );
        $this->add_responsive_control(
            'register_button_icon',
            [
                'label'         => esc_html__('Register Button Icon', 'pacz'),
                'label_block'   => true,
                'type'          => Controls_Manager::ICONS,
				'condition' => [
                    'register_button_style' => ['icon', 'both'],
                ]
            ]
        );

        $this->add_responsive_control(
            'register_button_text',
            [
                'label' => esc_html__('Button Text', 'pacz'),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => 'Register',
				'condition' => [
                    'register_button_style' => ['text', 'both'],
                ]
            ]
        );
        $this->add_responsive_control(
            'register_button_url',
            [
                'label' => esc_html__( 'Url', 'pacz' ),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://designinvento.net/', 'pacz' ),
                'show_external' => true,
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->end_controls_section();
	}
	protected function divider() {
		$this->start_controls_section(
            'button_divider_section',
            [
                'label' => esc_html__('Divider', 'pacz'),
            ]
        );

       $this->add_responsive_control(
			'button_divider',
			[
				'label' => esc_html__( 'turm on divider', 'pacz' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'pacz' ),
				'label_off' => esc_html__( 'Hide', 'pacz' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
        );
		$this->add_responsive_control(
			'button_divider_style',
			[
				'label' => esc_html__( 'Choose Style', 'pacz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'icon'  => esc_html__( 'Icon', 'pacz' ),
					'text' => esc_html__( 'Text', 'pacz' ),
				],
			]
        );
        $this->add_responsive_control(
            'button_divider_icon',
            [
                'label'         => esc_html__('Icon', 'pacz'),
                'label_block'   => true,
                'type'          => Controls_Manager::ICONS,
				'condition' => [
                    'button_divider_style' => 'icon',
                ]
            ]
        );

        $this->add_responsive_control(
            'button_divider_text',
            [
                'label' => esc_html__('Text', 'pacz'),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => 'Or',
				'condition' => [
					'button_divider_style' => 'text',
               ]
            ]
        );

        $this->end_controls_section();
	}
	protected function user_menu() {
		$this->start_controls_section(
            'user_menu_section',
            [
                'label' => esc_html__('User Menu', 'pacz'),
            ]
        );
		$this->add_responsive_control(
            'user_menu_align',
            [
                'label' => esc_html__( 'Alignment', 'pacz' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'pacz' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'pacz' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'pacz' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
				'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu' => 'text-align: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'user_dropdown_menu_direction',
            [
                'label' => esc_html__( 'Direction', 'pacz' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'ltr' => [
                        'title' => esc_html__( 'Left to right', 'pacz' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'rtl' => [
                        'title' => esc_html__( 'Right to left', 'pacz' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
				'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu' => 'direction: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'user_dropdown_menu_align',
            [
                'label' => esc_html__( 'Dropdown Items Alignment', 'pacz' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'pacz' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'pacz' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'pacz' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
				'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropdown-content a' => 'text-align: {{VALUE}};',
                ],
            ]
        );
		$this->add_control(
			'user_profile_image',
			[
				'label' => esc_html__( 'User Profile Image', 'pacz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'profile_photo',
				'options' => [
					'profile_photo'  => esc_html__( 'profile photo', 'pacz' ),
					'custom_icon' => esc_html__( 'Custom Icon', 'pacz' ),
					'disable' => esc_html__( 'Disable', 'pacz' ),
				],
				//'frontend_available' => true,
			]
        );
		$this->add_control(
            'user_menu_icon',
            [
                'label'         => esc_html__('Login Button Icon', 'pacz'),
                'label_block'   => true,
                'type'          => Controls_Manager::ICONS,
				'condition' => [
                    'user_profile_image' => 'custom_icon',
                ]
            ]
        );
		$this->add_control(
			'user_display_name',
			[
				'label' => esc_html__( 'User Display Name', 'pacz' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'pacz' ),
				'label_off' => esc_html__( 'Hide', 'pacz' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
        );
		$this->add_control(
			'user_nicename',
			[
				'label' => esc_html__( 'User Nicename', 'pacz' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'pacz' ),
				'label_off' => esc_html__( 'Hide', 'pacz' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
        );
		$this->add_responsive_control(
			'user_menu_dropdown',
			[
				'label' => esc_html__( 'User Menu Dropdown', 'pacz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default'  => esc_html__( 'Default', 'pacz' ),
					'custom' => esc_html__( 'Custom', 'pacz' ),
				],
			]
        );
		$user_menu_links = new \Elementor\Repeater();
		// set social icon label
        $user_menu_links->add_responsive_control(
            'user_menu_dropdown_link_label',
            [
                'label' => esc_html__( 'Label', 'pacz' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Dashboard',
            ]
        );

		// set social link
        $user_menu_links->add_responsive_control(
            'user_menu_dropdown_link_url',
            [
                'label' => esc_html__( 'url', 'pacz' ),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => 'https://designinvento.net/dashboard',
                ],
            ]
        );
		$user_menu_links->add_responsive_control(
            'user_menu_dropdown_link_icon',
            [
                'label'         => esc_html__('Icon', 'pacz'),
                'label_block'   => true,
                'type'          => Controls_Manager::ICONS,
            ]
        );
		// set social icon add new control
        $this->add_control(
            'user_menu_dropdown_links',
            [
                'label' => esc_html__('Add user dropdown menu', 'pacz'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $user_menu_links->get_controls(),
                'title_field' => '{{{ user_menu_dropdown_link_label }}}',
				'condition' => [
                    'user_menu_dropdown' => 'custom'
                ]

            ]
        );
		$this->end_controls_section();
	}
	protected function login_styling() {
		$this->start_controls_section(
            'login_button_style_section',
            [
                'label' => esc_html__( 'Login Menu', 'pacz' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
			'login_button_margin',
			[
				'label' => esc_html__( 'Margin', 'pacz' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-login-menu' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );
        
        $this->add_responsive_control(
			'login_button_padding',
			[
				'label' => esc_html__( 'Padding', 'pacz' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-login-menu-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
            'login_button_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'pacz' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 5,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .hfb-login-menu-link i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .hfb-login-menu-link svg' => 'max-width: {{SIZE}}{{UNIT}}; height: auto',
                ],
            ]
        );
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'login_button_typography',
                'label' => esc_html__( 'Typography', 'pacz' ),
                'selector' => '{{WRAPPER}} .hfb-login-menu-link',
            ]
        );
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'login_button_border',
				'label' => esc_html__( 'Border', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .hfb-login-menu-link',
			]
		);
		// border radius
		 $this->add_responsive_control(
            'login_button_border_radius',
            [
                'label' => esc_html__( 'Border radius', 'pacz' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '' ,
					'left' => '',
					'unit' => 'px',
				],
                'selectors' => [
                    '{{WRAPPER}} .hfb-login-menu-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		// start tab for content
		$this->start_controls_tabs(
            'login_button_style_tabs'
        );

		// start normal tab
        $this->start_controls_tab(
            'login_button_style_normal',
            [
                'label' => esc_html__( 'Normal', 'pacz' ),
            ]
        );
		
        $this->add_responsive_control(
            'login_button_text_color',
            [
                'label' => esc_html__( 'Text Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-login-menu-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'login_button_bg',
            [
                'label'     => esc_html__( 'Background Color', 'pacz' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hfb-login-menu-link' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'login_button_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-login-menu-link i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .hfb-login-menu-link svg path'   => 'stroke: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'login_button_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .hfb-login-menu-link',
			]
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'login_buttontext_shadow',
				'label' => esc_html__( 'Text Shadow', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .hfb-login-menu-link',
			]
		);
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'login_button_css_filters',
				'selector' => '{{WRAPPER}} .hfb-login-menu-link',
			]
		);
		$this->end_controls_tab();
		
		// start hover tab
        $this->start_controls_tab(
            'login_button_style_hover',
            [
                'label' => esc_html__( 'Hover', 'pacz' ),
            ]
        );
		$this->add_responsive_control(
            'login_button_text_color_hover',
            [
                'label' => esc_html__( 'Text Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-login-menu-link:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'login_button_bg_hover',
            [
                'label'     => esc_html__( 'Background Color', 'pacz' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hfb-login-menu-link:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'login_button_icon_color_hover',
            [
                'label' => esc_html__( 'Icon Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-login-menu-link:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .hfb-login-menu-link:hover svg path'   => 'stroke: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'login_button_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-login-menu-link:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'login_button_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .hfb-login-menu-link:hover',
			]
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'login_button_text_shadow_hover',
				'label' => esc_html__( 'Text Shadow', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .hfb-login-menu-link:hover',
			]
		);
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'login_button_css_filters_hover',
				'selector' => '{{WRAPPER}} .hfb-login-menu-link:hover',
			]
		);
		$this->end_controls_tab();
		//end  hover tab
		
		
		$this->end_controls_tabs();

        $this->end_controls_section();
	}
	protected function register_styling() {
		$this->start_controls_section(
            'register_button_style_section',
            [
                'label' => esc_html__( 'Register Menu', 'pacz' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
			'register_button_margin',
			[
				'label' => esc_html__( 'Margin', 'pacz' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-register-menu' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );
        
        $this->add_responsive_control(
			'register_button_padding',
			[
				'label' => esc_html__( 'Padding', 'pacz' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-register-menu-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
            'register_button_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'pacz' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 5,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .hfb-register-menu-link i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .hfb-register-menu-link svg' => 'max-width: {{SIZE}}{{UNIT}}; height: auto',
                ],
            ]
        );
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'register_button_typography',
                'label' => esc_html__( 'Typography', 'pacz' ),
                'selector' => '{{WRAPPER}} .hfb-register-menu-link',
            ]
        );
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'register_button_border',
				'label' => esc_html__( 'Border', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .hfb-register-menu-link',
			]
		);
		// border radius
		 $this->add_responsive_control(
            'register_button_border_radius',
            [
                'label' => esc_html__( 'Border radius', 'pacz' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '' ,
					'left' => '',
					'unit' => 'px',
				],
                'selectors' => [
                    '{{WRAPPER}} .hfb-register-menu-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		// start tab for content
		$this->start_controls_tabs(
            'register_button_style_tabs'
        );

		// start normal tab
        $this->start_controls_tab(
            'register_button_style_normal',
            [
                'label' => esc_html__( 'Normal', 'pacz' ),
            ]
        );
		
        $this->add_responsive_control(
            'register_button_text_color',
            [
                'label' => esc_html__( 'Text Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-register-menu-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'register_button_bg',
            [
                'label'     => esc_html__( 'Background Color', 'pacz' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hfb-register-menu-link' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'register_button_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-register-menu-link i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .hfb-register-menu-link svg path'   => 'stroke: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'register_button_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .hfb-register-menu-link',
			]
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'register_buttontext_shadow',
				'label' => esc_html__( 'Text Shadow', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .hfb-register-menu-link',
			]
		);
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'register_button_css_filters',
				'selector' => '{{WRAPPER}} .hfb-register-menu-link',
			]
		);
		$this->end_controls_tab();
		
		// start hover tab
        $this->start_controls_tab(
            'register_button_style_hover',
            [
                'label' => esc_html__( 'Hover', 'pacz' ),
            ]
        );
		$this->add_responsive_control(
            'register_button_text_color_hover',
            [
                'label' => esc_html__( 'Text Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-register-menu-link:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'register_button_bg_hover',
            [
                'label'     => esc_html__( 'Background Color', 'pacz' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hfb-register-menu-link:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'register_button_icon_color_hover',
            [
                'label' => esc_html__( 'Icon Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-register-menu-link:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .hfb-register-menu-link:hover svg path'   => 'stroke: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'register_button_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-register-menu-link:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'register_button_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .hfb-register-menu-link:hover',
			]
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'register_button_text_shadow_hover',
				'label' => esc_html__( 'Text Shadow', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .hfb-register-menu-link:hover',
			]
		);
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'register_button_css_filters_hover',
				'selector' => '{{WRAPPER}} .hfb-register-menu-link:hover',
			]
		);
		$this->end_controls_tab();
		//end  hover tab
		
		
		$this->end_controls_tabs();

        $this->end_controls_section();
	}
	protected function divider_styling() {
		$this->start_controls_section(
            'divider_style_section',
            [
                'label' => esc_html__( 'Divider', 'pacz' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
			'divider_margin',
			[
				'label' => esc_html__( 'Margin', 'pacz' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-login-register-divider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );
        
        $this->add_responsive_control(
			'divider_padding',
			[
				'label' => esc_html__( 'Padding', 'pacz' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-login-register-divider' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
            'divider_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'pacz' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 5,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .hfb-login-register-divider i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .hfb-login-register-divider svg' => 'max-width: {{SIZE}}{{UNIT}}; height: auto',
                ],
            ]
        );
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'divider_typography',
                'label' => esc_html__( 'Typography', 'pacz' ),
                'selector' => '{{WRAPPER}} .hfb-login-register-divider',
            ]
        );
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'divider_border',
				'label' => esc_html__( 'Border', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .hfb-login-register-divider',
			]
		);
		// border radius
		 $this->add_responsive_control(
            'divider_border_radius',
            [
                'label' => esc_html__( 'Border radius', 'pacz' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '' ,
					'left' => '',
					'unit' => 'px',
				],
                'selectors' => [
                    '{{WRAPPER}} .hfb-login-register-divider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		// start tab for content
		$this->start_controls_tabs(
            'divider_style_tabs'
        );

		// start normal tab
        $this->start_controls_tab(
            'divider_style_normal',
            [
                'label' => esc_html__( 'Normal', 'pacz' ),
            ]
        );
		
        $this->add_responsive_control(
            'divider_text_color',
            [
                'label' => esc_html__( 'Text Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-login-register-divider' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'divider_bg',
            [
                'label'     => esc_html__( 'Background Color', 'pacz' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hfb-login-register-divider' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'divider_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-login-register-divider i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .hfb-login-register-divider svg path'   => 'stroke: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'divider_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .hfb-login-register-divider',
			]
		);
		$this->end_controls_tab();
		
		// start hover tab
        $this->start_controls_tab(
            'divider_style_hover',
            [
                'label' => esc_html__( 'Hover', 'pacz' ),
            ]
        );
		$this->add_responsive_control(
            'divider_text_color_hover',
            [
                'label' => esc_html__( 'Text Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-login-register-divider:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'divider_bg_hover',
            [
                'label'     => esc_html__( 'Background Color', 'pacz' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hfb-login-register-divider:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'divider_icon_color_hover',
            [
                'label' => esc_html__( 'Icon Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-login-register-divider:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .hfb-login-register-divider:hover svg path'   => 'stroke: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'divider_border_color_hover',
            [
                'label' => esc_html__( 'Border Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-login-register-divider:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'divider_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .hfb-login-register-divider:hover',
			]
		);
		$this->end_controls_tab();
		//end  hover tab
		
		
		$this->end_controls_tabs();

        $this->end_controls_section();
	}
	protected function user_menu_styling() {
		$this->start_controls_section(
            'user_menu_style_section',
            [
                'label' => esc_html__( 'User Menu', 'pacz' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
			'user_menu_margin',
			[
				'label' => esc_html__( 'Margin', 'pacz' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-user-menu .dropbtn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );
        
        $this->add_responsive_control(
			'user_menu_padding',
			[
				'label' => esc_html__( 'Padding', 'pacz' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-user-menu .dropbtn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'user_menu_typography',
                'label' => esc_html__( 'Display Name Typography', 'pacz' ),
                'selector' => '{{WRAPPER}} .hfb-user-menu .dropbtn .user-displayname',
            ]
        );
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'user_menu_nicename_typography',
                'label' => esc_html__( 'Display Nicename Typography', 'pacz' ),
                'selector' => '{{WRAPPER}} .hfb-user-menu .dropbtn .user-nicename',
            ]
        );	
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'user_menu_border',
				'label' => esc_html__( 'Border', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .hfb-user-menu .dropbtn',
			]
		);
		// border radius
		 $this->add_responsive_control(
            'user_menu_border_radius',
            [
                'label' => esc_html__( 'Border radius', 'pacz' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '' ,
					'left' => '',
					'unit' => 'px',
				],
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropbtn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		// start tab for content
		$this->start_controls_tabs(
            'user_menu_style_tabs'
        );

		// start normal tab
        $this->start_controls_tab(
            'user_menu_style_normal',
            [
                'label' => esc_html__( 'Normal', 'pacz' ),
            ]
        );
		
        $this->add_responsive_control(
            'user_menu_text_color',
            [
                'label' => esc_html__( 'Display Name Text Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropbtn .user-displayname' => 'color: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'user_menu_nicename_text_color',
            [
                'label' => esc_html__( 'Nicename Text Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropbtn .user-nicename' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'user_menu_bg',
            [
                'label'     => esc_html__( 'Background Color', 'pacz' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropbtn' => 'background-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'user_menu_icon_box_bg_color',
            [
                'label' => esc_html__( 'Icon Box background Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropbtn .user-menu-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'user_menu_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropbtn i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .hfb-user-menu .dropbtn svg path'   => 'stroke: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'user_menu_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .hfb-user-menu .dropbtn',
			]
		);
		$this->end_controls_tab();
		
		// start hover tab
        $this->start_controls_tab(
            'user_menu_style_hover',
            [
                'label' => esc_html__( 'Hover', 'pacz' ),
            ]
        );
		$this->add_responsive_control(
            'user_menu_text_color_hover',
            [
                'label' => esc_html__( 'Display Name Text Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropbtn:hover .user-displayname' => 'color: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'user_menu_nicename_text_color_hover',
            [
                'label' => esc_html__( 'Nicename Text Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropbtn:hover .user-nicename' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'user_menu_bg_hover',
            [
                'label'     => esc_html__( 'Background Color', 'pacz' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropbtn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'user_menu_icon_box_bg_color_hover',
            [
                'label' => esc_html__( 'Icon Box background Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropbtn:hover .user-menu-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'user_menu_icon_box_color_hover',
            [
                'label' => esc_html__( 'Icon Box border Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropbtn:hover .user-menu-icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'user_menu_icon_color_hover',
            [
                'label' => esc_html__( 'Icon Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropbtn:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .hfb-user-menu .dropbtn:hover svg path'   => 'stroke: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'user_menu_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .hfb-user-menu .dropbtn:hover',
			]
		);
		
		
		$this->end_controls_tab();
		//end  hover tab
		
		
		$this->end_controls_tabs();
		
		$this->add_control(
			'user_menu_icon_section',
			[
				'label' => esc_html__( 'Icon', 'plugin-name' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
            'user_menu_icon_box_size',
            [
                'label' => esc_html__( 'Icon Box Size', 'pacz' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 5,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropbtn .user-menu-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'user_menu_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'pacz' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 5,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropbtn i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .hfb-user-menu .dropbtn svg' => 'max-width: {{SIZE}}{{UNIT}}; height: auto',
					'{{WRAPPER}} .hfb-user-menu .dropbtn img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
		$this->add_responsive_control(
			'user_menu_icon_box_margin',
			[
				'label' => esc_html__( 'Icon Box Margin', 'pacz' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-user-menu .dropbtn .user-menu-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'user_menu_icon_box_border',
				'label' => esc_html__( 'Icon Box Border', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .hfb-user-menu .dropbtn .user-menu-icon',
			]
		);
		// border radius
		 $this->add_responsive_control(
            'user_menu__icon_box_border_radius',
            [
                'label' => esc_html__( 'Icon Box Border radius', 'pacz' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '' ,
					'left' => '',
					'unit' => 'px',
				],
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropbtn .user-menu-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .hfb-user-menu .dropbtn svg path' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .hfb-user-menu .dropbtn img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		$this->add_responsive_control(
			'user_menu_icon_box_padding',
			[
				'label' => esc_html__( 'icon box Padding', 'pacz' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-user-menu .dropbtn i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .hfb-user-menu .dropbtn svg path' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .hfb-user-menu .dropbtn img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();
	}
    protected function user_menu_dropdown_styling() {
		$this->start_controls_section(
            'user_menu_dropdown_style_section',
            [
                'label' => esc_html__( 'User Menu Dropdown', 'pacz' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_control(
			'user_menu_dropdown_box_section',
			[
				'label' => esc_html__( 'Menu Box', 'plugin-name' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_responsive_control(
			'user_menu_dropdown_margin',
			[
				'label' => esc_html__( 'Margin', 'pacz' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-user-menu .dropdown-content ul' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );
        
        $this->add_responsive_control(
			'user_menu_dropdown_padding',
			[
				'label' => esc_html__( 'Padding', 'pacz' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-user-menu .dropdown-content ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'user_menu_dropdown_border',
				'label' => esc_html__( 'Border', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .hfb-user-menu .dropdown-content ul',
			]
		);
		// border radius
		 $this->add_responsive_control(
            'user_menu_dropdown_border_radius',
            [
                'label' => esc_html__( 'Border radius', 'pacz' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '' ,
					'left' => '',
					'unit' => 'px',
				],
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropdown-content ul' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		
		// start tab for content
		$this->start_controls_tabs(
            'user_menu_dropdown_style_tabs'
        );

		// start normal tab
        $this->start_controls_tab(
            'user_menu_dropdown_style_normal',
            [
                'label' => esc_html__( 'Normal', 'pacz' ),
            ]
        );
		$this->add_responsive_control(
            'user_menu_dropdown_bg',
            [
                'label'     => esc_html__( 'Box Background Color', 'pacz' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropdown-content ul' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .hfb-user-menu .dropdown-content ul:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'user_menu_dropdown_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .hfb-user-menu .dropdown-content ul',
			]
		);
		$this->end_controls_tab();
		
		// start hover tab
        $this->start_controls_tab(
            'user_menu_dropdown_style_hover',
            [
                'label' => esc_html__( 'Hover', 'pacz' ),
            ]
        );
		$this->add_responsive_control(
            'user_menu_dropdown_bg_hover',
            [
                'label'     => esc_html__( 'Box Background Color', 'pacz' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropdown-content ul:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .hfb-user-menu .dropdown-content ul:hover:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'user_menu_dropdown_box_border_color_hover',
            [
                'label'     => esc_html__( 'Box Border Color', 'pacz' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropdown-content ul:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'user_menu_dropdown_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .hfb-user-menu .dropdown-content ul:hover',
			]
		);
		
		$this->end_controls_tab();
		//end  hover tab
		$this->end_controls_tabs();
		
		// Menu Item
		
		$this->add_control(
			'user_menu_dropdown_item_section',
			[
				'label' => esc_html__( 'Menu Item', 'plugin-name' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'user_menu_dropdown_item_margin',
			[
				'label' => esc_html__( 'Menu Item Margin', 'pacz' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-user-menu .dropdown-content ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );
        $this->add_responsive_control(
			'user_menu_dropdown_item_padding',
			[
				'label' => esc_html__( 'Menu Item Padding', 'pacz' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-user-menu .dropdown-content ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'user_menu_dropdown_link_typography',
                'label' => esc_html__( 'Links Typography', 'pacz' ),
                'selector' => '{{WRAPPER}} .hfb-user-menu .dropdown-content li a',
            ]
        );
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'user_menu_dropdown_item_border',
				'label' => esc_html__( 'Menu Item Border', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .hfb-user-menu .dropdown-content ul li a',
			]
		);
		// border radius
		 $this->add_responsive_control(
            'user_menu_dropdown_item_border_radius',
            [
                'label' => esc_html__( 'Menu Item Border radius', 'pacz' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '' ,
					'left' => '',
					'unit' => 'px',
				],
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropdown-content ul li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		// start tab for content
		$this->start_controls_tabs(
            'user_menu_dropdown_item_style_tabs'
        );

		// start normal tab
        $this->start_controls_tab(
            'user_menu_dropdown_item_style_normal',
            [
                'label' => esc_html__( 'Normal', 'pacz' ),
            ]
        );
		$this->add_responsive_control(
            'user_menu_dropdown_item_bg',
            [
                'label'     => esc_html__( 'Menu Item Background Color', 'pacz' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropdown-content ul li a' => 'background-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'user_menu_dropdown_box_item_shadow',
				'label' => esc_html__( 'Menu Item Shadow', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .hfb-user-menu .dropdown-content ul li a',
			]
		);
        $this->add_responsive_control(
            'user_menu_dropdown_links_text_color',
            [
                'label' => esc_html__( 'Links Text Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropdown-content li a' => 'color: {{VALUE}};',
                ],
            ]
        );
		$this->end_controls_tab();
		
		// start hover tab
        $this->start_controls_tab(
            'user_menu_dropdown_item_style_hover',
            [
                'label' => esc_html__( 'Hover', 'pacz' ),
            ]
        );
		$this->add_responsive_control(
            'user_menu_dropdown_item_bg_hover',
            [
                'label'     => esc_html__( 'Menu Item Background Color', 'pacz' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropdown-content ul li a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'user_menu_dropdown_box_item_shadow_hover',
				'label' => esc_html__( 'Menu Item Shadow', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .hfb-user-menu .dropdown-content ul li a:hover',
			]
		);
		$this->add_responsive_control(
            'user_menu_dropdown_item_border_color_hover',
            [
                'label'     => esc_html__( 'Menu Item Border Color', 'pacz' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropdown-content ul li a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
            'user_menu_dropdown_links_text_color_hover',
            [
                'label' => esc_html__( 'Links Text Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropdown-content li a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
		
		$this->end_controls_tab();
		//end  hover tab
		$this->end_controls_tabs();
		
		// icon section
		$this->add_control(
			'user_menu_dropdown_icon_section',
			[
				'label' => esc_html__( 'Icon', 'plugin-name' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'user_menu_dropdown_icon_margin',
			[
				'label' => esc_html__( 'Icon Margin', 'pacz' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .hfb-user-menu .dropdown-content ul li a i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .hfb-user-menu .dropdown-content ul li a svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );
		$this->add_responsive_control(
            'user_menu_dropdown_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'pacz' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 5,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropdown-content li a i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .hfb-user-menu .dropdown-content li a i svg' => 'max-width: {{SIZE}}{{UNIT}}; height: auto',
                ],
            ]
        );
		// start tab for content
		$this->start_controls_tabs(
            'user_menu_dropdown_icon_style_tabs'
        );

		// start normal tab
        $this->start_controls_tab(
            'user_menu_dropdown_icon_style_normal',
            [
                'label' => esc_html__( 'Normal', 'pacz' ),
            ]
        );
		$this->add_responsive_control(
            'user_menu_dropdown_icon_color',
            [
                'label' => esc_html__( 'Links Icon Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropdown-content li a i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .hfb-user-menu .dropdown-content li a svg path'   => 'stroke: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
		$this->end_controls_tab();
		
		// start hover tab
        $this->start_controls_tab(
            'user_menu_dropdown_icon_style_hover',
            [
                'label' => esc_html__( 'Hover', 'pacz' ),
            ]
        );
		$this->add_responsive_control(
            'user_menu_dropdown_links_icon_color_hover',
            [
                'label' => esc_html__( 'Icon Color', 'pacz' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hfb-user-menu .dropdown-content li:hover a i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .hfb-user-menu .dropdown-content li:hover a svg path'   => 'stroke: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );
		
		$this->end_controls_tab();
		//end  hover tab
		
		
		$this->end_controls_tabs();
        $this->end_controls_section();
	}
	
	protected function render() {
       
        $this->render_raw();
       
    }
	
    protected function render_raw( ) {
        $settings = $this->get_settings_for_display();
		//$breakpoints_default_config = Breakpoints_Manager::get_default_config();
		//$breakpoint_key_mobile = Breakpoints_Manager::BREAKPOINT_KEY_MOBILE;
		//$breakpoint_key_tablet = Breakpoints_Manager::BREAKPOINT_KEY_TABLET;
		//$active_devices = Plugin::$instance->breakpoints->get_active_devices_list( [ 'reverse' => true ] );
		//var_dump($active_devices);
		//echo $breakpoint_key_mobile;
		//echo $breakpoint_key_tablet;
		
		$user_id = wp_get_current_user()->ID;
		$display_name = get_the_author_meta( 'display_name', $user_id );
		$nicename = get_the_author_meta( 'user_nicename', $user_id );
		require_once ELEMENTOR_PATH . 'includes/libraries/bfi-thumb/bfi-thumb.php';
	//var_dump($settings['user_profile_image']);
		$avatar_id = get_user_meta( $user_id, 'avatar_id', true );
		$icon_size = (isset($settings['user_menu_icon_size']['size']) && !empty($settings['user_menu_icon_size']['size']))? $settings['user_menu_icon_size']['size']: 40;
		if(!empty($avatar_id) && is_numeric($avatar_id)) {
			$avatar_url = wp_get_attachment_image_src( $avatar_id, 'full' ); 
			$image_src_array = $avatar_url[0];
			$params = array( 'width' => $icon_size, 'height' => $icon_size, 'crop' => true );
			$avatar = bfi_thumb( $image_src_array, $params );
		} else{  
			$avatar = get_avatar_url($user_id, ['size' => $icon_size]);
		}
		
        echo '<div class="hfb-user-menu">';
			if(is_user_logged_in()){
				if(class_exists('Directorypress_Frontend') && class_exists('DirectoryPress')){
					echo '<div class="dropdown">';
						echo '<button class="dropbtn">';
							if(isset($settings['user_profile_image']) && $settings['user_profile_image'] == 'profile_photo'){
								echo '<div class="user-menu-icon">';
									echo '<img src="' . $avatar . '" alt="'. $display_name .'" />';
								echo '</div>';
							}elseif(isset($settings['user_profile_image']) && $settings['user_profile_image'] == 'custom_icon'){
								echo '<div class="user-menu-icon">';
									Icons_Manager::render_icon( $settings['user_menu_icon'], [ 'aria-hidden' => 'true' ] );
								echo '</div>';
							}
							echo '<div class="user-info">';
								if($settings['user_display_name']){
									echo '<span class="user-displayname">'. $display_name .'</span>';
								}
								if($settings['user_nicename']){
									echo '<span class="user-nicename">'. $nicename .'</span>';
								}
							echo '</div>';
						echo '</button>';
						echo '<div class="dropdown-content '. $settings['user_dropdown_menu_direction'] .'">';
							echo '<ul class="clearfix">';
								if($settings['user_menu_dropdown'] == 'custom'){
									$this->custom_user_menu_links();
								}else{
									echo '<li><a href="'. directorypress_dashboardUrl().'"><i class="fas fa-tachometer-alt"></i>'. esc_html__('My Dashboard', 'pacz') .'</a></li>';
									echo '<li><a href="'. directorypress_dashboardUrl().'"><i class="fas fa-ad"></i>'. esc_html__('My Listings', 'pacz') .'</a></li>';
									echo '<li><a href="'. directorypress_dashboardUrl(array('directory_action' => 'profile')) .'"><i class="fas fa-user"></i>'. esc_html__('Edit Profile', 'pacz') .'</a></li>';
									echo '<li><a href="'. wp_logout_url(esc_url( home_url('/'))) .'"><i class="fas fa-sign-out-alt"></i>'. esc_html__('logout', 'pacz') .'</a></li>';
								}
							echo '</ul>';
						echo '</div>';
					echo '</div>'; 
				}else{
					echo '<a class="hfb-logout-menu clearfix" href="'.wp_logout_url(esc_url( home_url('/'))).'">'.esc_html__('logout', 'pacz').'</a>';
				} 
			}else{
				$this->login_button();
				$this->button_divider();
				$this->register_button();
			}
        echo '</div>';
    }
	protected function login_button() {
		$settings = $this->get_settings_for_display();
		extract($settings);
		
		if($login_button && !empty($login_button_url['url'])){
			echo '<div class="hfb-login-menu">';	
				echo '<a class="hfb-login-menu-link" href="'. esc_url($login_button_url['url']) .'">';
					if(($login_button_style == 'icon' || $login_button_style == 'both') && $login_button_icon){
						Icons_Manager::render_icon( $login_button_icon, [ 'aria-hidden' => 'true' ] );
					}
					if(($login_button_style == 'text' || $login_button_style == 'both') && !empty($login_button_text)){
						echo esc_html($login_button_text);
					}
				echo '</a>';
			echo '</div>';
		}
	}
	
	protected function register_button() {
       $settings = $this->get_settings_for_display();
	   extract($settings);
	   if($register_button && !empty($register_button_url['url'])){
			echo '<div class="hfb-register-menu">';	
				echo '<a class="hfb-register-menu-link" href="'. esc_url($register_button_url['url']) .'">';
					if(($register_button_style == 'icon' || $register_button_style == 'both') && $register_button_icon){
						Icons_Manager::render_icon( $register_button_icon, [ 'aria-hidden' => 'true' ] );
					}
					if(($register_button_style == 'text' || $register_button_style == 'both') && !empty($register_button_text)){
						echo esc_html($register_button_text);
					}
				echo '</a>';
			echo '</div>';
		}
	}
	
	protected function custom_user_menu_links() {
       $settings = $this->get_settings_for_display();
	   extract($settings);
	   foreach ($user_menu_dropdown_links as $key => $item){
			if ( ! empty( $item['user_menu_dropdown_link_url']['url'] ) ) {
				$this->add_link_attributes( 'link-' . $key, $item['user_menu_dropdown_link_url'] );
				
			}
			if ( ! empty( $item['user_menu_dropdown_link_url']['url'] ) ) {
				echo '<li class="elementor-repeater-item-'. esc_attr( $item[ '_id' ] ) .'">';
					echo '<a '. $this->get_render_attribute_string(  'link-' . $key ) .'>';
						if($item['user_menu_dropdown_link_icon']){
							Icons_Manager::render_icon( $item['user_menu_dropdown_link_icon'], [ 'aria-hidden' => 'true' ] );
						}
						echo esc_html($item['user_menu_dropdown_link_label']) .'</a>';
					echo '</a>';
					
				echo '</li>';
			}
	   }
        
       
    }
	protected function button_divider() {
		$settings = $this->get_settings_for_display();
		extract($settings);
		
		if($button_divider){
			echo '<div class="hfb-login-register-divider">';	
					if($button_divider_style == 'icon' && $button_divider_icon){
						Icons_Manager::render_icon( $button_divider_icon, [ 'aria-hidden' => 'true' ] );
					}
					if($button_divider_style == 'text' && !empty($button_divider_text)){
						echo esc_html($button_divider_text);
					}
			echo '</div>';
		}
	}
}
