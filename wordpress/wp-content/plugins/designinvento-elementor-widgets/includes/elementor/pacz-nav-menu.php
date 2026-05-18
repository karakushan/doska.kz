<?php
/**
 * Elementor Classes.
 *
 * @package header-footer-elementor
 */

namespace HFB\WidgetsManager\Widgets;

// Elementor Classes.
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Widget_Base;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Class Nav Menu.
 */
class Pacz_Nav_Menu extends Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		
		//add_action('wp_enqueue_scripts', array($this, 'scripts'));
		$this->scripts();
	}
	public function scripts() {
			wp_register_style('pacz-nav-menu', DEW_ASSETS_URL . 'css/nav-menu.css');
			wp_enqueue_style('pacz-nav-menu');	
	}
	/**
	 * Menu index.
	 *
	 * @access protected
	 * @var $nav_menu_index
	 */
	protected $nav_menu_index = 1;

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'pacz-navigation-menu';
	}
	
	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Designinvento Menu', 'designinvento-elementor-widgets' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'hfb-icon-navigation-menu';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'hfb-widgets' ];
	}

	/**
	 * Retrieve the list of scripts the navigation menu depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'dew_core_frontend' ];
	}

	/**
	 * Retrieve the menu index.
	 *
	 * Used to get index of nav menu.
	 *
	 * @since 1.3.0
	 * @access protected
	 *
	 * @return string nav index.
	 */
	protected function get_nav_menu_index() {
		return $this->nav_menu_index++;
	}

	/**
	 * Retrieve the list of available menus.
	 *
	 * Used to get the list of available menus.
	 *
	 * @since 1.3.0
	 * @access private
	 *
	 * @return array get WordPress menus list.
	 */
	private function get_available_menus() {

		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	/**
	 * Check if the Elementor is updated.
	 *
	 * @since 1.3.0
	 *
	 * @return boolean if Elementor updated.
	 */
	public static function is_elementor_updated() {
		if ( class_exists( 'Elementor\Icons_Manager' ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Register Nav Menu controls.
	 *
	 * @since 1.5.7
	 * @access protected
	 */
	protected function register_controls() {

		$this->register_general_content_controls();
		$this->register_desktop_layout_controls();
		$this->register_responsive_layout_controls();
		$this->register_style_content_controls();
		$this->register_dropdown_content_controls();
	}

	/**
	 * Register Nav Menu General Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function register_general_content_controls() {

		$this->start_controls_section(
			'section_menu',
			[
				'label' => __( 'Menu', 'designinvento-elementor-widgets' ),
			]
		);

		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'menu',
				[
					'label'        => __( 'Menu', 'designinvento-elementor-widgets' ),
					'type'         => Controls_Manager::SELECT,
					'options'      => $menus,
					'default'      => array_keys( $menus )[0],
					'save_default' => true,
					/* translators: %s Nav menu URL */
					'description'  => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'designinvento-elementor-widgets' ), admin_url( 'nav-menus.php' ) ),
				]
			);
		} else {
			$this->add_control(
				'menu',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %s Nav menu URL */
					'raw'             => sprintf( __( '<strong>There are no menus in your site.</strong><br>Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'designinvento-elementor-widgets' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}

		$this->add_control(
			'schema_support',
			[
				'label'        => __( 'Enable Schema Support', 'designinvento-elementor-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'designinvento-elementor-widgets' ),
				'label_off'    => __( 'No', 'designinvento-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'render_type'  => 'template',
				'separator'    => 'before',
			]
		);

		$this->end_controls_section();
	}
	protected function register_desktop_layout_controls() {
		$this->start_controls_section(
				'section_layout',
				[
					'label' => __( 'Desktop Layout', 'designinvento-elementor-widgets' ),
				]
		);

			$this->add_control(
				'layout',
				[
					'label'   => __( 'Layout', 'designinvento-elementor-widgets' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'horizontal',
					'options' => [
						'horizontal' => __( 'Horizontal', 'designinvento-elementor-widgets' ),
					],
				]
			);

			$this->add_control(
				'navmenu_align',
				[
					'label'        => __( 'Alignment', 'designinvento-elementor-widgets' ),
					'type'         => Controls_Manager::CHOOSE,
					'options'      => [
						'left'    => [
							'title' => __( 'Left', 'designinvento-elementor-widgets' ),
							'icon'  => 'eicon-h-align-left',
						],
						'center'  => [
							'title' => __( 'Center', 'designinvento-elementor-widgets' ),
							'icon'  => 'eicon-h-align-center',
						],
						'right'   => [
							'title' => __( 'Right', 'designinvento-elementor-widgets' ),
							'icon'  => 'eicon-h-align-right',
						],
						'justify' => [
							'title' => __( 'Justify', 'designinvento-elementor-widgets' ),
							'icon'  => 'eicon-h-align-stretch',
						],
					],
					'default'      => 'left',
					'condition'    => [
						'layout' => [ 'horizontal' ],
					],
					'prefix_class' => 'hfb-nav-menu__align-',
				]
			);

			$this->add_control(
				'submenu_icon',
				[
					'label'        => __( 'Submenu Icon', 'designinvento-elementor-widgets' ),
					'type'         => Controls_Manager::SELECT,
					'default'      => 'arrow',
					'options'      => [
						'arrow'   => __( 'Arrows', 'designinvento-elementor-widgets' ),
						'plus'    => __( 'Plus Sign', 'designinvento-elementor-widgets' ),
						'classic' => __( 'Classic', 'designinvento-elementor-widgets' ),
					],
					'prefix_class' => 'hfb-submenu-icon-',
				]
			);

			$this->add_control(
				'submenu_animation',
				[
					'label'        => __( 'Submenu Animation', 'designinvento-elementor-widgets' ),
					'type'         => Controls_Manager::SELECT,
					'default'      => 'none',
					'options'      => [
						'none'     => __( 'Default', 'designinvento-elementor-widgets' ),
						'slide_up' => __( 'Slide Up', 'designinvento-elementor-widgets' ),
					],
					'prefix_class' => 'hfb-submenu-animation-',
					'condition'    => [
						'layout' => 'horizontal',
					],
				]
			);

			$this->add_control(
				'link_redirect',
				[
					'label'        => __( 'Action On Menu Click', 'designinvento-elementor-widgets' ),
					'type'         => Controls_Manager::SELECT,
					'default'      => 'child',
					'description'  => __( 'For Horizontal layout, this will affect on the selected breakpoint', 'designinvento-elementor-widgets' ),
					'options'      => [
						'child'     => __( 'Open Submenu', 'designinvento-elementor-widgets' ),
						'self_link' => __( 'Redirect To Self Link', 'designinvento-elementor-widgets' ),
					],
					'prefix_class' => 'hfb-link-redirect-',
				]
			);
		$this->end_controls_section();
	}
	
	protected function register_responsive_layout_controls() {
		$this->start_controls_section(
				'section_responsive_layout',
				[
					'label' => __( 'Responsive Layout', 'designinvento-elementor-widgets' ),
				]
		);
		$this->add_control(
			'dropdown',
			[
				'label'        => __( 'Breakpoint', 'designinvento-elementor-widgets' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'tablet',
				'options'      => [
					'mobile' => __( 'Mobile (768px >)', 'designinvento-elementor-widgets' ),
					'tablet' => __( 'Tablet (1025px >)', 'designinvento-elementor-widgets' ),
					'desktop' => __( 'Desktop)', 'designinvento-elementor-widgets' ),
					'none'   => __( 'None', 'designinvento-elementor-widgets' ),
				],
				'prefix_class' => 'hfb-nav-menu__breakpoint-',
				'condition'    => [
					'layout' => ['horizontal'],
				],
				'render_type'  => 'template',
			]
		);

		$this->add_control(
			'resp_align',
			[
				'label'                => __( 'Alignment', 'designinvento-elementor-widgets' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => [
					'left'   => [
						'title' => __( 'Left', 'designinvento-elementor-widgets' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'designinvento-elementor-widgets' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'designinvento-elementor-widgets' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'              => 'center',
				'description'          => __( 'This is the alignement of menu icon on selected responsive breakpoints.', 'designinvento-elementor-widgets' ),
				'condition'            => [
					'layout'    => ['horizontal'],
					'dropdown!' => 'none',
				],
				'selectors_dictionary' => [
					'left'   => 'margin-right: auto',
					'center' => 'margin: 0 auto',
					'right'  => 'margin-left: auto',
				],
				'selectors'            => [
					'{{WRAPPER}} .hfb-nav-menu__toggle' => '{{VALUE}}',
				],
			]
		);

		
		$this->add_control(
				'dropdown_icon',
				[
					'label'       => __( 'Menu Icon', 'designinvento-elementor-widgets' ),
					'type'        => Controls_Manager::ICONS,
					'label_block' => 'true',
					'default'     => [
						'value'   => 'fas fa-ellipsis-v',
						'library' => 'fa-solid',
					],
					'condition'   => [
						'dropdown!' => 'none',
					],
				]
		);
		

		
		$this->add_control(
				'dropdown_close_icon',
				[
					'label'       => __( 'Close Icon', 'designinvento-elementor-widgets' ),
					'type'        => Controls_Manager::ICONS,
					'label_block' => 'true',
					'default'     => [
						'value'   => 'far fa-window-close',
						'library' => 'fa-regular',
					],
					'condition'   => [
						'dropdown!' => 'none',
					],
				]
		);
		$this->add_control(
			'heading_author_section',
			[
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Author Section', 'designinvento-elementor-widgets' ),
				'separator' => 'before',
			]
		);
		$this->add_control(
			'author_section',
			[
				'label'        => __( 'Enable Author Info Section', 'designinvento-elementor-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'1'     => __( 'Yes', 'designinvento-elementor-widgets' ),
				'0'    => __( 'No', 'designinvento-elementor-widgets' ),
				'default'      => 1,
			]
		);
		$this->add_control(
			'logged_out_content',
			[
				'label' => __( 'Logged out user content after author info', 'designinvento-elementor-widgets' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => '',
				/* 'condition' => [
					'author_section' => 1,
				] */
			]
		);
		$this->add_control(
			'logged_in_content',
			[
				'label' => __( 'Logged in user content after author info', 'designinvento-elementor-widgets' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => '',
				/* 'condition' => [
					'author_section' => 1,
				] */
			]
		);
		$this->end_controls_section();
	}

	protected function register_style_content_controls() {

		$this->start_controls_section(
			'section_style_main-menu',
			[
				'label'     => __( 'Main Menu', 'designinvento-elementor-widgets' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
				'padding_horizontal_menu_item',
				[
					'label'              => __( 'Horizontal Padding', 'designinvento-elementor-widgets' ),
					'type'               => Controls_Manager::SLIDER,
					'size_units'         => [ 'px' ],
					'range'              => [
						'px' => [
							'max' => 50,
						],
					],
					'default'            => [
						'size' => 15,
						'unit' => 'px',
					],
					'selectors'          => [
						'{{WRAPPER}} .menu-item a.hfb-menu-item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} .menu-item a.hfb-sub-menu-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} + 20px );padding-right: {{SIZE}}{{UNIT}};',
					],
					'frontend_available' => true,
				]
		);

			$this->add_responsive_control(
				'padding_vertical_menu_item',
				[
					'label'              => __( 'Vertical Padding', 'designinvento-elementor-widgets' ),
					'type'               => Controls_Manager::SLIDER,
					'size_units'         => [ 'px' ],
					'range'              => [
						'px' => [
							'max' => 50,
						],
					],
					'default'            => [
						'size' => 15,
						'unit' => 'px',
					],
					'selectors'          => [
						'{{WRAPPER}} .menu-item a.hfb-menu-item, {{WRAPPER}} .menu-item a.hfb-sub-menu-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
					],
					'frontend_available' => true,
				]
			);

			$this->add_responsive_control(
				'menu_space_between',
				[
					'label'              => __( 'Space Between', 'designinvento-elementor-widgets' ),
					'type'               => Controls_Manager::SLIDER,
					'size_units'         => [ 'px' ],
					'range'              => [
						'px' => [
							'max' => 100,
						],
					],
					'selectors'          => [
						'body:not(.rtl) {{WRAPPER}} .hfb-nav-menu__layout-horizontal .hfb-nav-menu > li.menu-item:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}',
						'body.rtl {{WRAPPER}} .hfb-nav-menu__layout-horizontal .hfb-nav-menu > li.menu-item:not(:last-child)' => 'margin-left: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} nav:not(.hfb-nav-menu__layout-horizontal) .hfb-nav-menu > li.menu-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
						'(tablet)body:not(.rtl) {{WRAPPER}}.hfb-nav-menu__breakpoint-tablet .hfb-nav-menu__layout-horizontal .hfb-nav-menu > li.menu-item:not(:last-child)' => 'margin-right: 0px',
						'(mobile)body:not(.rtl) {{WRAPPER}}.hfb-nav-menu__breakpoint-mobile .hfb-nav-menu__layout-horizontal .hfb-nav-menu > li.menu-item:not(:last-child)' => 'margin-right: 0px',
					],
					'frontend_available' => true,
				]
			);

			$this->add_responsive_control(
				'menu_row_space',
				[
					'label'              => __( 'Row Spacing', 'designinvento-elementor-widgets' ),
					'type'               => Controls_Manager::SLIDER,
					'size_units'         => [ 'px' ],
					'range'              => [
						'px' => [
							'max' => 100,
						],
					],
					'selectors'          => [
						'body:not(.rtl) {{WRAPPER}} .hfb-nav-menu__layout-horizontal .hfb-nav-menu > li.menu-item' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					],
					'condition'          => [
						'layout' => 'horizontal',
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'pointer',
				[
					'label'     => __( 'Link Hover Effect', 'designinvento-elementor-widgets' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'none',
					'options'   => [
						'none'        => __( 'None', 'designinvento-elementor-widgets' ),
						'underline'   => __( 'Underline', 'designinvento-elementor-widgets' ),
						'overline'    => __( 'Overline', 'designinvento-elementor-widgets' ),
						'double-line' => __( 'Double Line', 'designinvento-elementor-widgets' ),
						'framed'      => __( 'Framed', 'designinvento-elementor-widgets' ),
						'text'        => __( 'Text', 'designinvento-elementor-widgets' ),
					],
					'condition' => [
						'layout' => [ 'horizontal' ],
					],
				]
			);

		$this->add_control(
			'animation_line',
			[
				'label'     => __( 'Animation', 'designinvento-elementor-widgets' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'fade',
				'options'   => [
					'fade'     => 'Fade',
					'slide'    => 'Slide',
					'grow'     => 'Grow',
					'drop-in'  => 'Drop In',
					'drop-out' => 'Drop Out',
					'none'     => 'None',
				],
				'condition' => [
					'layout'  => [ 'horizontal' ],
					'pointer' => [ 'underline', 'overline', 'double-line' ],
				],
			]
		);

		$this->add_control(
			'animation_framed',
			[
				'label'     => __( 'Frame Animation', 'designinvento-elementor-widgets' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'fade',
				'options'   => [
					'fade'    => 'Fade',
					'grow'    => 'Grow',
					'shrink'  => 'Shrink',
					'draw'    => 'Draw',
					'corners' => 'Corners',
					'none'    => 'None',
				],
				'condition' => [
					'layout'  => [ 'horizontal' ],
					'pointer' => 'framed',
				],
			]
		);

		$this->add_control(
			'animation_text',
			[
				'label'     => __( 'Animation', 'designinvento-elementor-widgets' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'grow',
				'options'   => [
					'grow'   => 'Grow',
					'shrink' => 'Shrink',
					'sink'   => 'Sink',
					'float'  => 'Float',
					'skew'   => 'Skew',
					'rotate' => 'Rotate',
					'none'   => 'None',
				],
				'condition' => [
					'layout'  => [ 'horizontal' ],
					'pointer' => 'text',
				],
			]
		);

		$this->add_control(
			'style_divider',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'menu_typography',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} a.hfb-menu-item, {{WRAPPER}} a.hfb-sub-menu-item',
			]
		);

		$this->start_controls_tabs( 'tabs_menu_item_style' );

				$this->start_controls_tab(
					'tab_menu_item_normal',
					[
						'label' => __( 'Normal', 'designinvento-elementor-widgets' ),
					]
				);

					$this->add_control(
						'color_menu_item',
						[
							'label'     => __( 'Text Color', 'designinvento-elementor-widgets' ),
							'type'      => Controls_Manager::COLOR,
							'global'    => [
								'default' => Global_Colors::COLOR_TEXT,
							],
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .menu-item a.hfb-menu-item' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'bg_color_menu_item',
						[
							'label'     => __( 'Background Color', 'designinvento-elementor-widgets' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .menu-item a.hfb-menu-item, {{WRAPPER}} .sub-menu, {{WRAPPER}} nav.hfb-dropdown',
							],
						]
					);
					$this->add_responsive_control(
						'btn_border_radius',
						[
							'label' =>esc_html__( 'Border Radius', 'designinvento-elementor-widgets' ),
							'type' => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%'],
							'default' => [
								'top' => '',
								'right' => '',
								'bottom' => '' ,
								'left' => '',
							],
							'selectors' => [
								'{{WRAPPER}} .menu-item a.hfb-menu-item' =>  'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'menu_item_box_shadow',
							'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
							'selector' => '{{WRAPPER}} .menu-item a.hfb-menu-item',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_menu_item_hover',
					[
						'label' => __( 'Hover', 'designinvento-elementor-widgets' ),
					]
				);

					$this->add_control(
						'color_menu_item_hover',
						[
							'label'     => __( 'Text Color', 'designinvento-elementor-widgets' ),
							'type'      => Controls_Manager::COLOR,
							'global'    => [
								'default' => Global_Colors::COLOR_ACCENT,
							],
							'selectors' => [
								'{{WRAPPER}} .menu-item a.hfb-menu-item:hover,
								{{WRAPPER}} .menu-item.current-menu-item a.hfb-menu-item,
								{{WRAPPER}} .menu-item a.hfb-menu-item.highlighted,
								{{WRAPPER}} .menu-item a.hfb-menu-item:focus' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'bg_color_menu_item_hover',
						[
							'label'     => __( 'Background Color', 'designinvento-elementor-widgets' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .menu-item a.hfb-menu-item:hover,
								{{WRAPPER}} .sub-menu a.hfb-sub-menu-item:hover,
								{{WRAPPER}} .menu-item.current-menu-item a.hfb-menu-item,
								{{WRAPPER}} .menu-item a.hfb-menu-item.highlighted,
								{{WRAPPER}} .menu-item a.hfb-menu-item:focus' => 'background-color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'pointer_color_menu_item_hover',
						[
							'label'     => __( 'Link Hover Effect Color', 'designinvento-elementor-widgets' ),
							'type'      => Controls_Manager::COLOR,
							'global'    => [
								'default' => Global_Colors::COLOR_ACCENT,
							],
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .hfb-nav-menu-layout:not(.hfb-pointer__framed) .menu-item.parent a.hfb-menu-item:before,
								{{WRAPPER}} .hfb-nav-menu-layout:not(.hfb-pointer__framed) .menu-item.parent a.hfb-menu-item:after' => 'background-color: {{VALUE}}',
								'{{WRAPPER}} .hfb-nav-menu-layout:not(.hfb-pointer__framed) .menu-item.parent .sub-menu .hfb-has-submenu-container a:after' => 'background-color: unset',
								'{{WRAPPER}} .hfb-pointer__framed .menu-item.parent a.hfb-menu-item:before,
								{{WRAPPER}} .hfb-pointer__framed .menu-item.parent a.hfb-menu-item:after' => 'border-color: {{VALUE}}',
							],
							'condition' => [
								'pointer!' => [ 'none', 'text' ],
							],
						]
					);
					$this->add_responsive_control(
						'menu_item_border_radius_hover',
						[
							'label' =>esc_html__( 'Border Radius', 'designinvento-elementor-widgets' ),
							'type' => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%'],
							'default' => [
								'top' => '',
								'right' => '',
								'bottom' => '' ,
								'left' => '',
							],
							'selectors' => [
								'{{WRAPPER}} .menu-item a.hfb-menu-item:hover' =>  'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'menu_item_box_shadow_hover',
							'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
							'selector' => '{{WRAPPER}} .menu-item a.hfb-menu-item:hover',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_menu_item_active',
					[
						'label' => __( 'Active', 'designinvento-elementor-widgets' ),
					]
				);

					$this->add_control(
						'color_menu_item_active',
						[
							'label'     => __( 'Text Color', 'designinvento-elementor-widgets' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .menu-item.current-menu-item a.hfb-menu-item,
								{{WRAPPER}} .menu-item.current-menu-ancestor a.hfb-menu-item' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'bg_color_menu_item_active',
						[
							'label'     => __( 'Background Color', 'designinvento-elementor-widgets' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .menu-item.current-menu-item a.hfb-menu-item,
								{{WRAPPER}} .menu-item.current-menu-ancestor a.hfb-menu-item' => 'background-color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'pointer_color_menu_item_active',
						[
							'label'     => __( 'Link Hover Effect Color', 'designinvento-elementor-widgets' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .hfb-nav-menu-layout:not(.hfb-pointer__framed) .menu-item.parent.current-menu-item a.hfb-menu-item:before,
								{{WRAPPER}} .hfb-nav-menu-layout:not(.hfb-pointer__framed) .menu-item.parent.current-menu-item a.hfb-menu-item:after' => 'background-color: {{VALUE}}',
								'{{WRAPPER}} .hfb-nav-menu:not(.hfb-pointer__framed) .menu-item.parent .sub-menu .hfb-has-submenu-container a.current-menu-item:after' => 'background-color: unset',
								'{{WRAPPER}} .hfb-pointer__framed .menu-item.parent.current-menu-item a.hfb-menu-item:before,
								{{WRAPPER}} .hfb-pointer__framed .menu-item.parent.current-menu-item a.hfb-menu-item:after' => 'border-color: {{VALUE}}',
							],
							'condition' => [
								'pointer!' => [ 'none', 'text' ],
							],
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'menu_item_box_shadow_active',
							'label' => esc_html__( 'Box Shadow', 'designinvento-elementor-widgets' ),
							'selector' => '{{WRAPPER}} .menu-item.current-menu-item a.hfb-menu-item',
						]
					);
				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_dropdown_content_controls() {

		$this->start_controls_section(
			'section_style_dropdown',
			[
				'label' => __( 'Dropdown', 'designinvento-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'dropdown_description',
				[
					'raw'             => __( '<b>Note:</b> On desktop, below style options will apply to the submenu. On mobile, this will apply to the entire menu.', 'designinvento-elementor-widgets' ),
					'type'            => Controls_Manager::RAW_HTML,
					'content_classes' => 'elementor-descriptor',
				]
			);

			$this->start_controls_tabs( 'tabs_dropdown_item_style' );

				$this->start_controls_tab(
					'tab_dropdown_item_normal',
					[
						'label' => __( 'Normal', 'designinvento-elementor-widgets' ),
					]
				);

					$this->add_control(
						'color_dropdown_item',
						[
							'label'     => __( 'Text Color', 'designinvento-elementor-widgets' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .sub-menu a.hfb-sub-menu-item, 
								{{WRAPPER}} .elementor-menu-toggle,
								{{WRAPPER}} nav.hfb-dropdown li a.hfb-menu-item,
								{{WRAPPER}} nav.hfb-dropdown li a.hfb-sub-menu-item' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'background_color_dropdown_item',
						[
							'label'     => __( 'Background Color', 'designinvento-elementor-widgets' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '#fff',
							'selectors' => [
								'{{WRAPPER}} .sub-menu,
								{{WRAPPER}} nav.hfb-dropdown,
								{{WRAPPER}} nav.hfb-dropdown .menu-item a.hfb-menu-item,
								{{WRAPPER}} nav.hfb-dropdown .menu-item a.hfb-sub-menu-item' => 'background-color: {{VALUE}}',
							],
							'separator' => 'after',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_dropdown_item_hover',
					[
						'label' => __( 'Hover', 'designinvento-elementor-widgets' ),
					]
				);

					$this->add_control(
						'color_dropdown_item_hover',
						[
							'label'     => __( 'Text Color', 'designinvento-elementor-widgets' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .sub-menu a.hfb-sub-menu-item:hover, 
								{{WRAPPER}} .elementor-menu-toggle:hover,
								{{WRAPPER}} nav.hfb-dropdown li a.hfb-menu-item:hover,
								{{WRAPPER}} nav.hfb-dropdown li a.hfb-sub-menu-item:hover' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'background_color_dropdown_item_hover',
						[
							'label'     => __( 'Background Color', 'designinvento-elementor-widgets' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .sub-menu a.hfb-sub-menu-item:hover,
								{{WRAPPER}} nav.hfb-dropdown li a.hfb-menu-item:hover,
								{{WRAPPER}} nav.hfb-dropdown li a.hfb-sub-menu-item:hover' => 'background-color: {{VALUE}}',
							],
							'separator' => 'after',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_dropdown_item_active',
					[
						'label' => __( 'Active', 'designinvento-elementor-widgets' ),
					]
				);

				$this->add_control(
					'color_dropdown_item_active',
					[
						'label'     => __( 'Text Color', 'designinvento-elementor-widgets' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .sub-menu .menu-item.current-menu-item a.hfb-sub-menu-item.hfb-sub-menu-item-active,	
							{{WRAPPER}} nav.hfb-dropdown .menu-item.current-menu-item a.hfb-menu-item,
							{{WRAPPER}} nav.hfb-dropdown .menu-item.current-menu-ancestor a.hfb-menu-item,
							{{WRAPPER}} nav.hfb-dropdown .sub-menu .menu-item.current-menu-item a.hfb-sub-menu-item.hfb-sub-menu-item-active
							' => 'color: {{VALUE}}',

						],
					]
				);

				$this->add_control(
					'background_color_dropdown_item_active',
					[
						'label'     => __( 'Background Color', 'designinvento-elementor-widgets' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .sub-menu .menu-item.current-menu-item a.hfb-sub-menu-item.hfb-sub-menu-item-active,	
							{{WRAPPER}} nav.hfb-dropdown .menu-item.current-menu-item a.hfb-menu-item,
							{{WRAPPER}} nav.hfb-dropdown .menu-item.current-menu-ancestor a.hfb-menu-item,
							{{WRAPPER}} nav.hfb-dropdown .sub-menu .menu-item.current-menu-item a.hfb-sub-menu-item.hfb-sub-menu-item-active' => 'background-color: {{VALUE}}',
						],
						'separator' => 'after',
					]
				);

				$this->end_controls_tabs();

			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'      => 'dropdown_typography',
					'global'    => [
						'default' => Global_Typography::TYPOGRAPHY_ACCENT,
					],
					'separator' => 'before',
					'selector'  => '
						{{WRAPPER}} .sub-menu li a.hfb-sub-menu-item,
						{{WRAPPER}} nav.hfb-dropdown li a.hfb-sub-menu-item,
						{{WRAPPER}} nav.hfb-dropdown li a.hfb-menu-item',
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'     => 'dropdown_border',
					'selector' => '{{WRAPPER}} nav.hfb-nav-menu__layout-horizontal .sub-menu, 
							{{WRAPPER}} nav:not(.hfb-nav-menu__layout-horizontal) .sub-menu.sub-menu-open,
							{{WRAPPER}} nav.hfb-dropdown .hfb-nav-menu',
				]
			);

			$this->add_responsive_control(
				'dropdown_border_radius',
				[
					'label'              => __( 'Border Radius', 'designinvento-elementor-widgets' ),
					'type'               => Controls_Manager::DIMENSIONS,
					'size_units'         => [ 'px', '%' ],
					'selectors'          => [
						'{{WRAPPER}} .sub-menu'        => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .sub-menu li.menu-item:first-child' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}};overflow:hidden;',
						'{{WRAPPER}} .sub-menu li.menu-item:last-child' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}}; border-bottom-left-radius: {{LEFT}}{{UNIT}};overflow:hidden',
						'{{WRAPPER}} nav.hfb-dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} nav.hfb-dropdown li.menu-item:first-child' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}};overflow:hidden',
						'{{WRAPPER}} nav.hfb-dropdown li.menu-item:last-child' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}}; border-bottom-left-radius: {{LEFT}}{{UNIT}};overflow:hidden',
					],
					'frontend_available' => true,
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'      => 'dropdown_box_shadow',
					'exclude'   => [
						'box_shadow_position',
					],
					'selector'  => '{{WRAPPER}} .hfb-nav-menu .sub-menu,
								{{WRAPPER}} nav.hfb-dropdown',
					'separator' => 'after',
				]
			);

			$this->add_responsive_control(
				'width_dropdown_item',
				[
					'label'              => __( 'Dropdown Width (px)', 'designinvento-elementor-widgets' ),
					'type'               => Controls_Manager::SLIDER,
					'range'              => [
						'px' => [
							'min' => 0,
							'max' => 500,
						],
					],
					'default'            => [
						'size' => '220',
						'unit' => 'px',
					],
					'selectors'          => [
						'{{WRAPPER}} ul.sub-menu' => 'width: {{SIZE}}{{UNIT}}',
					],
					'condition'          => [
						'layout' => 'horizontal',
					],
					'frontend_available' => true,
				]
			);

			$this->add_responsive_control(
				'padding_horizontal_dropdown_item',
				[
					'label'              => __( 'Horizontal Padding', 'designinvento-elementor-widgets' ),
					'type'               => Controls_Manager::SLIDER,
					'size_units'         => [ 'px' ],
					'selectors'          => [
						'{{WRAPPER}} .sub-menu li a.hfb-sub-menu-item,
						{{WRAPPER}} nav.hfb-dropdown li a.hfb-menu-item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} nav.hfb-dropdown li a.hfb-sub-menu-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} + 20px );padding-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .hfb-dropdown .menu-item ul ul a.hfb-sub-menu-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} + 40px );padding-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .hfb-dropdown .menu-item ul ul ul a.hfb-sub-menu-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} + 60px );padding-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .hfb-dropdown .menu-item ul ul ul ul a.hfb-sub-menu-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} + 80px );padding-right: {{SIZE}}{{UNIT}};',
					],
					'frontend_available' => true,
				]
			);

			$this->add_responsive_control(
				'padding_vertical_dropdown_item',
				[
					'label'              => __( 'Vertical Padding', 'designinvento-elementor-widgets' ),
					'type'               => Controls_Manager::SLIDER,
					'size_units'         => [ 'px' ],
					'default'            => [
						'size' => 15,
						'unit' => 'px',
					],
					'range'              => [
						'px' => [
							'max' => 50,
						],
					],
					'selectors'          => [
						'{{WRAPPER}} .sub-menu a.hfb-sub-menu-item,
						 {{WRAPPER}} nav.hfb-dropdown li a.hfb-menu-item,
						 {{WRAPPER}} nav.hfb-dropdown li a.hfb-sub-menu-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
					],
					'frontend_available' => true,
				]
			);

			$this->add_responsive_control(
				'distance_from_menu',
				[
					'label'              => __( 'Top Distance', 'designinvento-elementor-widgets' ),
					'type'               => Controls_Manager::SLIDER,
					'range'              => [
						'px' => [
							'min' => -100,
							'max' => 100,
						],
					],
					'selectors'          => [
						'{{WRAPPER}} nav.hfb-nav-menu__layout-horizontal:not(.hfb-dropdown) ul.sub-menu, {{WRAPPER}} nav.hfb-nav-menu__layout-vertical:not(.hfb-dropdown) ul.sub-menu' => 'margin-top: {{SIZE}}px;',
						'{{WRAPPER}} .hfb-dropdown.menu-is-active' => 'margin-top: {{SIZE}}px;',
					],
					'condition'          => [
						'layout' => ['horizontal'],
					],
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'heading_dropdown_divider',
				[
					'label'     => __( 'Divider', 'designinvento-elementor-widgets' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'dropdown_divider_border',
				[
					'label'       => __( 'Border Style', 'designinvento-elementor-widgets' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'solid',
					'label_block' => false,
					'options'     => [
						'none'   => __( 'None', 'designinvento-elementor-widgets' ),
						'solid'  => __( 'Solid', 'designinvento-elementor-widgets' ),
						'double' => __( 'Double', 'designinvento-elementor-widgets' ),
						'dotted' => __( 'Dotted', 'designinvento-elementor-widgets' ),
						'dashed' => __( 'Dashed', 'designinvento-elementor-widgets' ),
					],
					'selectors'   => [
						'{{WRAPPER}} .sub-menu li.menu-item:not(:last-child), 
						{{WRAPPER}} nav.hfb-dropdown li.menu-item:not(:last-child)' => 'border-bottom-style: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'divider_border_color',
				[
					'label'     => __( 'Border Color', 'designinvento-elementor-widgets' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#c4c4c4',
					'selectors' => [
						'{{WRAPPER}} .sub-menu li.menu-item:not(:last-child), 
						{{WRAPPER}} nav.hfb-dropdown li.menu-item:not(:last-child)' => 'border-bottom-color: {{VALUE}};',
					],
					'condition' => [
						'dropdown_divider_border!' => 'none',
					],
				]
			);

			$this->add_control(
				'dropdown_divider_width',
				[
					'label'     => __( 'Border Width', 'designinvento-elementor-widgets' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'max' => 50,
						],
					],
					'default'   => [
						'size' => '1',
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} .sub-menu li.menu-item:not(:last-child), 
						{{WRAPPER}} nav.hfb-dropdown li.menu-item:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
					],
					'condition' => [
						'dropdown_divider_border!' => 'none',
					],
				]
			);
			$this->add_control(
				'heading_author_section_style',
				[
					'type'      => Controls_Manager::HEADING,
					'label'     => __( 'Author Section', 'designinvento-elementor-widgets' ),
					'separator' => 'before',
					'condition' => [
						'layout' => [ 'horizontal', 'vertical' ],
					],
				]
			);
			$this->add_control(
				'author_section_background',
				[
					'label'     => __( 'Author Section Background Color', 'designinvento-elementor-widgets' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .mobile-active-menu-user-wrap' => 'background-color: {{VALUE}};',
					],
				]
			);
		$this->end_controls_section();

		$this->start_controls_section(
			'style_toggle',
			[
				'label' => __( 'Responsive Menu Icon', 'designinvento-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_toggle_style' );

		$this->start_controls_tab(
			'toggle_style_normal',
			[
				'label' => __( 'Normal', 'designinvento-elementor-widgets' ),
			]
		);

		$this->add_control(
			'toggle_color',
			[
				'label'     => __( 'Color', 'designinvento-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hfb-nav-menu-open div.hfb-nav-menu-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .hfb-nav-menu-open div.hfb-nav-menu-icon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_background_color',
			[
				'label'     => __( 'Background Color', 'designinvento-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hfb-nav-menu-open .hfb-nav-menu-icon' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'toggle_border_color',
			[
				'label'     => __( 'Border Color', 'designinvento-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hfb-nav-menu-open .hfb-nav-menu-icon' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_hover',
			[
				'label' => __( 'Hover', 'designinvento-elementor-widgets' ),
			]
		);

		$this->add_control(
			'toggle_hover_color',
			[
				'label'     => __( 'Color', 'designinvento-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hfb-nav-menu-open div.hfb-nav-menu-icon:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .hfb-nav-menu-open div.hfb-nav-menu-icon:hover svg' => 'fill: {{VALUE}}',

				],
			]
		);

		$this->add_control(
			'toggle_hover_background_color',
			[
				'label'     => __( 'Background Color', 'designinvento-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hfb-nav-menu-open .hfb-nav-menu-icon:hover' => 'background-color: {{VALUE}}; padding: 0.35em;',
				],
			]
		);
		$this->add_control(
			'toggle_border_color_hover',
			[
				'label'     => __( 'Border Color', 'designinvento-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hfb-nav-menu-open .hfb-nav-menu-icon:hover' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->add_responsive_control(
			'toggle_container_width',
			[
				'label'              => __( 'Icon Width', 'designinvento-elementor-widgets' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => [
					'px' => [
						'min' => 20,
					],
				],
				'selectors'          => [
					'{{WRAPPER}} .hfb-nav-menu-open .hfb-nav-menu-icon'     => 'width: {{SIZE}}{{UNIT}}',
				],
				'separator'          => 'before',
			]
		);
		$this->add_responsive_control(
			'toggle_container_height',
			[
				'label'              => __( 'Icon Height', 'designinvento-elementor-widgets' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => [
					'px' => [
						'min' => 20,
					],
				],
				'selectors'          => [
					'{{WRAPPER}} .hfb-nav-menu-open .hfb-nav-menu-icon'     => 'height: {{SIZE}}{{UNIT}}',
				],
				'separator'          => 'before',
			]
		);
		$this->add_responsive_control(
			'toggle_size',
			[
				'label'              => __( 'Icon Size', 'designinvento-elementor-widgets' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => [
					'px' => [
						'min' => 15,
					],
				],
				'selectors'          => [
					'{{WRAPPER}} .hfb-nav-menu-open .hfb-nav-menu-icon'     => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .hfb-nav-menu-open .hfb-nav-menu-icon svg' => 'font-size: {{SIZE}}px;line-height: {{SIZE}}px;height: {{SIZE}}px;width: {{SIZE}}px;',
				],
				'frontend_available' => true,
				'separator'          => 'before',
			]
		);

		$this->add_responsive_control(
			'toggle_border_width',
			[
				'label'              => __( 'Border Width', 'designinvento-elementor-widgets' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => [
					'px' => [
						'max' => 10,
					],
				],
				'selectors'          => [
					'{{WRAPPER}} .hfb-nav-menu-open .hfb-nav-menu-icon' => 'border-width: {{SIZE}}{{UNIT}}; padding: 0.35em;',
				]
			]
		);

		$this->add_responsive_control(
			'toggle_border_radius',
			[
				'label'              => __( 'Border Radius', 'designinvento-elementor-widgets' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px', '%' ],
				'selectors'          => [
					'{{WRAPPER}} .hfb-nav-menu-open .hfb-nav-menu-icon' => 'border-radius: {{SIZE}}{{UNIT}}',
				]
			]
		);
			$this->add_control(
				'toggle_button_close_style',
				[
					'type'      => Controls_Manager::HEADING,
					'label'     => __( 'Author Section', 'designinvento-elementor-widgets' ),
					'separator' => 'before',
				]
			);
			$this->add_control(
				'toggle_button_close_background',
				[
					'label'     => __( 'Menu close icon background color', 'designinvento-elementor-widgets' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .hfb-nav-menu-close .hfb-nav-menu-icon' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'toggle_button_close_color',
				[
					'label'     => __( 'Menu close icon color', 'designinvento-elementor-widgets' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .hfb-nav-menu-close .hfb-nav-menu-icon i' => 'color: {{VALUE}};',
						'{{WRAPPER}} .hfb-nav-menu-open div.hfb-nav-menu-icon svg' => 'fill: {{VALUE}}',
					],
				]
			);
		$this->end_controls_section();
	}

	/**
	 * Add itemprop for Navigation Schema.
	 *
	 * @since 1.5.2
	 * @param string $atts link attributes.
	 * @access public
	 */
	public function handle_link_attrs( $atts ) {

		$atts .= ' itemprop="url"';
		return $atts;
	}

	/**
	 * Add itemprop to the li tag of Navigation Schema.
	 *
	 * @since 1.6.0
	 * @param string $value link attributes.
	 * @access public
	 */
	public function handle_li_values( $value ) {

		$value .= ' itemprop="name"';
		return $value;
	}

	/**
	 * Get the menu and close icon HTML.
	 *
	 * @since 1.5.2
	 * @param array $settings Widget settings array.
	 * @access public
	 */
	public function get_menu_close_icon( $settings ) {
		$menu_icon     = '';
		$close_icon    = '';
		$icons         = [];
		$icon_settings = [
			$settings['dropdown_icon'],
			$settings['dropdown_close_icon'],
		];

		foreach ( $icon_settings as $icon ) {
			
			ob_start();
			\Elementor\Icons_Manager::render_icon(
				$icon,
				[
					'aria-hidden' => 'true',
					'tabindex'    => '0',
				]
			);
			$menu_icon = ob_get_clean();

			array_push( $icons, $menu_icon );
		}

		return $icons;
	}

	/**
	 * Render Nav Menu output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function render() {

		$menus = $this->get_available_menus();

		if ( empty( $menus ) ) {
			return false;
		}

		$settings = $this->get_settings_for_display();

		$menu_close_icons = [];
		$menu_close_icons = $this->get_menu_close_icon( $settings );

		$args = [
			'echo'        => false,
			'menu'        => $settings['menu'],
			'menu_class'  => 'hfb-nav-menu',
			'menu_id'     => 'menu-' . $this->get_nav_menu_index() . '-' . $this->get_id(),
			'fallback_cb' => '__return_empty_string',
			'container'   => '',
			'walker'      => new Menu_Walker,
		];

		if ( 'yes' === $settings['schema_support'] ) {
			$this->add_render_attribute( 'hfb-nav-menu', 'itemscope', 'itemscope' );
			$this->add_render_attribute( 'hfb-nav-menu', 'itemtype', 'http://schema.org/SiteNavigationElement' );

			add_filter( 'hfb_nav_menu_attrs', [ $this, 'handle_link_attrs' ] );
			add_filter( 'nav_menu_li_values', [ $this, 'handle_li_values' ] );
		}
		
		$menu_html = wp_nav_menu( $args );

			$this->add_render_attribute(
				'hfb-main-menu',
				'class',
				[
					'hfb-nav-menu',
					'hfb-layout-' . $settings['layout'],
				]
			);

			$this->add_render_attribute( 'hfb-main-menu', 'class', 'hfb-nav-menu-layout' );

			$this->add_render_attribute( 'hfb-main-menu', 'class', $settings['layout'] );

			$this->add_render_attribute( 'hfb-main-menu', 'data-layout', $settings['layout'] );

			if ( $settings['pointer'] ) {
				
				$this->add_render_attribute( 'hfb-main-menu', 'class', 'hfb-pointer__' . $settings['pointer'] );

				if ( in_array( $settings['pointer'], [ 'double-line', 'underline', 'overline' ], true ) ) {
					$key = 'animation_line';
					$this->add_render_attribute( 'hfb-main-menu', 'class', 'hfb-animation__' . $settings[ $key ] );
				} elseif ( 'framed' === $settings['pointer'] || 'text' === $settings['pointer'] ) {
					$key = 'animation_' . $settings['pointer'];
					$this->add_render_attribute( 'hfb-main-menu', 'class', 'hfb-animation__' . $settings[ $key ] );
				}
				
			}

			$this->add_render_attribute(
				'hfb-nav-menu',
				'class',
				[
					'hfb-nav-menu__layout-' . $settings['layout'],
					'hfb-nav-menu__submenu-' . $settings['submenu_icon'],
				]
			);

			$this->add_render_attribute( 'hfb-nav-menu', 'data-toggle-icon', $menu_close_icons[0] );

			$this->add_render_attribute( 'hfb-nav-menu', 'data-close-icon', $menu_close_icons[1] );
			
			
			if(is_user_logged_in()){
				$after_author_info_content = $this->get_settings_for_display( 'logged_in_content' );
				$after_author_info_content = $this->parse_text_editor( $after_author_info_content );
			}else{
				$after_author_info_content = $this->get_settings_for_display( 'logged_out_content' );
				$after_author_info_content = $this->parse_text_editor( $after_author_info_content );
			}
			?>
			<div <?php echo $this->get_render_attribute_string( 'hfb-main-menu' ); ?>>
				<div class="hfb-nav-menu-open hfb-nav-menu__toggle elementor-clickable">
					<div class="hfb-nav-menu-icon">
						<?php echo isset( $menu_close_icons[0] ) ? $menu_close_icons[0] : ''; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
				
				
				<nav <?php echo $this->get_render_attribute_string( 'hfb-nav-menu' ); ?>>
					
					<div class="hfb-nav-menu-close hfb-nav-menu__toggle elementor-clickable">
						<div class="hfb-nav-menu-icon">
							<?php echo isset( $menu_close_icons[0] ) ? $menu_close_icons[0] : ''; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					</div>
					<?php if(wp_is_mobile()): ?>
						<?php do_action('pacz_header_login_active_menu_mobile', $after_author_info_content); ?>
					<?php endif; ?>
					<?php echo $menu_html; ?>
				</nav>              
				
			</div>
			<?php
		
	}
}