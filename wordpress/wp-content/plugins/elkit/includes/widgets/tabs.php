<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
//use Elkit\Elkit_Helper;

//use Elementor;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Scheme_Typography;
use Elementor\Utils;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

/**
 * Elementor tabs widget.
 *
 * Elementor widget that displays vertical or horizontal tabs with different
 * pieces of content.
 *
 * @since 1.0.0
 */
class Elkit_Elementor_Tabs_Widget extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve tabs widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'elkit-tabs';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve tabs widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Elkit Tabs', 'elkit' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve tabs widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-tabs';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'tabs', 'accordion', 'toggle' ];
	}
	
	public function get_categories() {
		return [ 'elkit' ];
	}

	/**
	 * Register tabs widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 3.1.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_tabs',
			[
				'label' => __( 'Tabs', 'elkit' ),
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'tab_title',
			[
				'label' => __( 'Title & Description', 'elkit' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Tab Title', 'elkit' ),
				'placeholder' => __( 'Tab Title', 'elkit' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);
		$repeater->add_control(
				'content_type',
				[
					'label' => esc_html__('Content Type', 'stratum'),
					'type' => Controls_Manager::SELECT,
					'default' => 'text',
					'label_block' => false,
					'options' => [
						'text_editor' => esc_html__('Text Editor', 'stratum'),
						'template' => esc_html__('Template', 'stratum'),
					],
				]
		);
		$repeater->add_control(
			'tab_content',
			[
				'label' => __( 'Content', 'elkit' ),
				'default' => __( 'Tab Content', 'elkit' ),
				'placeholder' => __( 'Tab Content', 'elkit' ),
				'type' => Controls_Manager::WYSIWYG,
				'show_label' => false,
				'condition' => [
					'content_type' => 'text_editor'
				],
			]
		);
		$repeater->add_control(
            'tab_template', [
                'type' => Controls_Manager::SELECT2,
                'options' => Elkit_Helper::Elkit_Drop_Posts('elementor_library'),
                'multiple' => false,
                'label' =>   esc_html__('Template', 'elkit'),
                'label_block' => true,
                'condition' => [
                    'content_type' => 'template',
                ],
            ]
        );
		$this->add_control(
			'tabs',
			[
				'label' => __( 'Tabs Items', 'elkit' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'tab_title' => __( 'Tab #1', 'elkit' ),
						'tab_content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elkit' ),
					],
					[
						'tab_title' => __( 'Tab #2', 'elkit' ),
						'tab_content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elkit' ),
					],
				],
				'title_field' => '{{{ tab_title }}}',
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'View', 'elkit' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->add_control(
			'type',
			[
				'label' => __( 'Position', 'elkit' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => __( 'Horizontal', 'elkit' ),
					'vertical' => __( 'Vertical', 'elkit' ),
				],
				'prefix_class' => 'elementor-tabs-view-',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tabs_align_horizontal',
			[
				'label' => __( 'Alignment', 'elkit' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'' => [
						'title' => __( 'Start', 'elkit' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elkit' ),
						'icon' => 'eicon-h-align-center',
					],
					'end' => [
						'title' => __( 'End', 'elkit' ),
						'icon' => 'eicon-h-align-right',
					],
					'stretch' => [
						'title' => __( 'Justified', 'elkit' ),
						'icon' => 'eicon-h-align-stretch',
					],
				],
				'prefix_class' => 'elementor-tabs-alignment-',
				'condition' => [
					'type' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'tabs_align_vertical',
			[
				'label' => __( 'Alignment', 'elkit' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'' => [
						'title' => __( 'Start', 'elkit' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __( 'Center', 'elkit' ),
						'icon' => 'eicon-v-align-middle',
					],
					'end' => [
						'title' => __( 'End', 'elkit' ),
						'icon' => 'eicon-v-align-bottom',
					],
					'stretch' => [
						'title' => __( 'Justified', 'elkit' ),
						'icon' => 'eicon-v-align-stretch',
					],
				],
				'prefix_class' => 'elementor-tabs-alignment-',
				'condition' => [
					'type' => 'vertical',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_tabs_style',
			[
				'label' => __( 'Tabs', 'elkit' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'navigation_width',
			[
				'label' => __( 'Navigation Width', 'elkit' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-tabs-wrapper' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'type' => 'vertical',
				],
			]
		);

		$this->add_control(
			'border_width',
			[
				'label' => __( 'Border Width', 'elkit' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-tab-title, {{WRAPPER}} .elementor-tab-title:before, {{WRAPPER}} .elementor-tab-title:after, {{WRAPPER}} .elementor-tab-content, {{WRAPPER}} .elementor-tabs-content-wrapper' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'border_color',
			[
				'label' => __( 'Border Color', 'elkit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-tab-mobile-title, {{WRAPPER}} .elementor-tab-desktop-title.elementor-active, {{WRAPPER}} .elementor-tab-title:before, {{WRAPPER}} .elementor-tab-title:after, {{WRAPPER}} .elementor-tab-content, {{WRAPPER}} .elementor-tabs-content-wrapper' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => __( 'Background Color', 'elkit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-tab-desktop-title.elementor-active' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-tabs-content-wrapper' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_title',
			[
				'label' => __( 'Title', 'elkit' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tab_color',
			[
				'label' => __( 'Color', 'elkit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-tab-title, {{WRAPPER}} .elementor-tab-title a' => 'color: {{VALUE}}',
				],
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
			]
		);

		$this->add_control(
			'tab_active_color',
			[
				'label' => __( 'Active Color', 'elkit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-tab-title.elementor-active,
					 {{WRAPPER}} .elementor-tab-title.elementor-active a' => 'color: {{VALUE}}',
				],
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_typography',
				'selector' => '{{WRAPPER}} .elementor-tab-title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_control(
			'title_align',
			[
				'label' => __( 'Alignment', 'elkit' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elkit' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elkit' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elkit' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-tab-title' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'tabs_align' => 'stretch',
				],
			]
		);

		$this->add_control(
			'heading_content',
			[
				'label' => __( 'Content', 'elkit' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => __( 'Color', 'elkit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-tab-content' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .elementor-tab-content',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->end_controls_section();
	}
	private function content($tab) { 

        $type = $tab['content_type'];
        if ($type == 'template'){
            return '<div class="tabs_item">'.do_shortcode('[ELKIT_INSERT_TPL id="'.$tab['tab_template'].'"]').'</div>';
        } else {
            return '<div class="tabs_item">'.$tab['tab_content'].'</div>';
        } 

    }
	/**
	 * Render tabs widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();
		echo '<div class="elkit-tabs-widget">';
			// nav
			echo '<div class="elkit-tabs-links">';
				foreach ($settings['tabs'] as $index => $item){
					$title = $item['tab_title']? '<span>'.$item['tab_title'].'</span>' : ''; 
				?>
					<button class="elkit-tabs-link" onclick="ElkitTabs(event, 'tab-<?php echo $index; ?>')"><?php echo $title; ?></button>
				<?php
				}
			echo '</div>';
			// content
			echo '<div class="elkit-tabs-content-wrapper">';
				foreach ($settings['tabs'] as $index => $item){
					echo '<div id="tab-'.$index.'" class="elkit-tabs-content">';
						echo $this->content($item);
					echo '</div>';
				}
			echo '</div>';
		echo '</div>';
	}
}

