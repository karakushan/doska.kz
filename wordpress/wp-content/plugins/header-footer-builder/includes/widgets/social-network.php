<?php
/**
 * Elementor Classes.
 *
 * @package header-footer-elementor
 */

namespace HFB\WidgetsManager\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * HFB Cart Widget
 *
 * @since 1.4.0
 */
class Social_Network extends Widget_Base {

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
		return 'hfb-social-network';
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
		return __( 'Social Network', 'header-footer-builder' );
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

		// start content section for social media
        $this->start_controls_section(
            'hfb_socialmedia_section_tab_content',
            [
                'label' => esc_html__('Social Icons', 'header-footer-builder'),
            ]
        );

        $this->add_control(
			'hfb_socialmedia_style',
			[
				'label' => esc_html__( 'Choose Style', 'header-footer-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'icon'  => esc_html__( 'Icon', 'header-footer-builder' ),
					'text' => esc_html__( 'Text', 'header-footer-builder' ),
					'both' => esc_html__( 'Both', 'header-footer-builder' ),
				],
			]
        );

        $this->add_control(
			'hfb_socialmedia_style_icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'header-footer-builder' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'before',
				'options' => [
					'before'  => esc_html__( 'Before', 'header-footer-builder' ),
					'after' => esc_html__( 'After', 'header-footer-builder' ),
                ],
                'condition' => [
                    'hfb_socialmedia_style' => 'both'
                ]
			]
        );

        $this->add_responsive_control(
			'hfb_socialmedia_icon_padding_right',
			[
				'label' => esc_html__( 'Spacing Right', 'header-footer-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} a > i' => 'padding-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'hfb_socialmedia_style' => 'both',
                    'hfb_socialmedia_style_icon_position' => 'before',
                ]
			]
		);

        $this->add_responsive_control(
			'hfb_socialmedia_icon_padding_left',
			[
				'label' => esc_html__( 'Spacing Left', 'header-footer-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} a > i' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'hfb_socialmedia_style' => 'both',
                    'hfb_socialmedia_style_icon_position' => 'after',
                ]
			]
		);

        $this->add_responsive_control(
            'hfb_socialmedai_list_align',
            [
                'label' => esc_html__( 'Alignment', 'header-footer-builder' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'header-footer-builder' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'header-footer-builder' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'header-footer-builder' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
				'selectors' => [
                    '{{WRAPPER}} .hfb_social_media' => 'text-align: {{VALUE}};',
                ],
            ]
        );

		$socialMedia = new \Elementor\Repeater();

		// set social icon
        $socialMedia->add_control(
            'hfb_socialmedia_icons',
            [
                'label' => esc_html__( 'Icon', 'header-footer-builder' ),
                'label_block' => true,
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'hfb_socialmedia_icon',
                'default' => [
                    'value' => 'icon icon-facebook',
                    'library' => 'hfbicons',
                ]
            ]
        );

		// set social icon label
        $socialMedia->add_control(
            'hfb_socialmedia_label',
            [
                'label' => esc_html__( 'Label', 'header-footer-builder' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Facebook',
            ]
        );

		// set social link
        $socialMedia->add_control(
            'hfb_socialmedia_link',
            [
                'label' => esc_html__( 'Link', 'header-footer-builder' ),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => 'https://facebook.com',
                ],
            ]
        );

		// start tab for content
		$socialMedia->start_controls_tabs(
            'hfb_socialmedia_tabs'
        );

		// start normal tab
        $socialMedia->start_controls_tab(
            'hfb_socialmedia_normal',
            [
                'label' => esc_html__( 'Normal', 'header-footer-builder' ),
            ]
        );

		// set social icon color
        $socialMedia->add_responsive_control(
			'hfb_socialmedia_icon_color',
			[
				'label' =>esc_html__( 'Color', 'header-footer-builder' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} > a' => 'color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} > a svg path'	=> 'stroke: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		// set social icon background color
        $socialMedia->add_responsive_control(
			'hfb_socialmedia_icon_bg_color',
			[
				'label' =>esc_html__( 'Background Color', 'header-footer-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} > a' => 'background-color: {{VALUE}};',
				],
			]
        );

        $socialMedia->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'hfb_socialmedia_border',
				'label' => esc_html__( 'Border', 'header-footer-builder' ),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} > a',
			]
		);

         $socialMedia->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'hfb_socialmedia_icon_normal_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'header-footer-builder' ),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} > a',
			]
        );

        $socialMedia->add_group_control(
            Group_Control_Box_Shadow::get_type(), [
                'name'       => 'hfb_socialmedai_list_box_shadow',
                'selector'   => '{{WRAPPER}} {{CURRENT_ITEM}} > a',
            ]
        );

		$socialMedia->end_controls_tab();
		// end normal tab

		//start hover tab
		$socialMedia->start_controls_tab(
            'hfb_socialmedia_hover',
            [
                'label' => esc_html__( 'Hover', 'header-footer-builder' ),
            ]
        );

		// set social icon color
        $socialMedia->add_responsive_control(
			'hfb_socialmedia_icon_hover_color',
			[
				'label' =>esc_html__( 'Color', 'header-footer-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} > a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} > a:hover svg path'	=> 'stroke: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		// set social icon background color
        $socialMedia->add_responsive_control(
			'hfb_socialmedia_icon_hover_bg_color',
			[
				'label' =>esc_html__( 'Background Color', 'header-footer-builder' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#3b5998',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} > a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);


		$socialMedia->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'hfb_socialmedia_icon_hover_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'header-footer-builder' ),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} > a:hover',
			]
        );

        $socialMedia->add_group_control(
            Group_Control_Box_Shadow::get_type(), [
                'name'       => 'hfb_socialmedai_list_box_shadow_hover',
                'selector'   => '{{WRAPPER}} {{CURRENT_ITEM}} > a:hover',
            ]
        );

        $socialMedia->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'hfb_socialmedia_border_hover',
				'label' => esc_html__( 'Border', 'header-footer-builder' ),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} > a:hover',
			]
		);

		$socialMedia->end_controls_tab();
		//end hover tab

		$socialMedia->end_controls_tabs();


		// set social icon add new control
        $this->add_control(
            'hfb_socialmedia_add_icons',
            [
                'label' => esc_html__('Add Social Media', 'header-footer-builder'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $socialMedia->get_controls(),
                'default' => [
                    [
                        'hfb_socialmedia_icons' => [
							'value'	=> 'icon icon-facebook',
							'library'	=> 'hfbicons'
						],
                        'hfb_socialmedia_label' => 'Facebook',
                        'hfb_socialmedia_icon_hover_bg_color' => '#3b5998',
                    ],
					[
                        'hfb_socialmedia_icons' => [
							'value'	=> 'icon icon-twitter',
							'library'	=> 'hfbicons'
						],
                        'hfb_socialmedia_label' => 'Twitter',
						'hfb_socialmedia_icon_hover_bg_color' => '#1da1f2',
                    ],
					[
                        'hfb_socialmedia_icons' => [
							'value'	=> 'icon icon-linkedin',
							'library'	=> 'hfbicons'
						],
                        'hfb_socialmedia_label' => 'LinkedIn',
						'hfb_socialmedia_icon_hover_bg_color' => '#0077b5',
                    ],
                ],
                'title_field' => '{{{ hfb_socialmedia_label }}}',

            ]
        );

		$this->end_controls_section();
		// end content section

	// start style section control

		// start Social media tab
		 $this->start_controls_section(
            'hfb_socialmedia_section_tab_style',
            [
                'label' => esc_html__('Social Media', 'header-footer-builder'),
				 'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
		// Alignment
        $this->add_responsive_control(
            'hfb_socialmedai_list_item_align',
            [
                'label' => esc_html__( 'Alignment', 'header-footer-builder' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'header-footer-builder' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'header-footer-builder' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'header-footer-builder' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
				'selectors' => [
                    '{{WRAPPER}} .hfb_social_media > li > a' => 'text-align: {{VALUE}};',
                ],
            ]
        );

		// Display design
		 $this->add_responsive_control(
            'hfb_socialmedai_list_display',
            [
                'label' => esc_html__( 'Display', 'header-footer-builder' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline-block',
                'options' => [
                    'inline-block' => esc_html__( 'Inline Block', 'header-footer-builder' ),
                    'block' => esc_html__( 'Block', 'header-footer-builder' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .hfb_social_media > li' => 'display: {{VALUE}};',
                ],
            ]
        );

		// text decoration
		 $this->add_responsive_control(
            'hfb_socialmedai_list_decoration_box',
            [
                'label' => esc_html__( 'Decoration', 'header-footer-builder' ),
                'type' => Controls_Manager::SELECT,
				'default' => 'none',
                'options' => [
                    'none' => esc_html__( 'None', 'header-footer-builder' ),
                    'underline' => esc_html__( 'Underline', 'header-footer-builder' ),
                    'overline' => esc_html__( 'Overline', 'header-footer-builder' ),
                    'line-through' => esc_html__( 'Line Through', 'header-footer-builder' ),

                ],
                'selectors' => ['{{WRAPPER}} .hfb_social_media > li > a' => 'text-decoration: {{VALUE}};'],
            ]
        );

		// border radius
		 $this->add_responsive_control(
            'hfb_socialmedai_list_border_radius',
            [
                'label' => esc_html__( 'Border radius', 'header-footer-builder' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => '50',
					'right' => '50',
					'bottom' => '50' ,
					'left' => '50',
					'unit' => '%',
				],
                'selectors' => [
                    '{{WRAPPER}} .hfb_social_media > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		// Padding style

		 $this->add_responsive_control(
            'hfb_socialmedai_list_padding',
            [
                'label'         => esc_html__('Padding', 'header-footer-builder'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .hfb_social_media > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		// margin style

		$this->add_responsive_control(
            'hfb_socialmedai_list_margin',
            [
                'label'         => esc_html__('Margin', 'header-footer-builder'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em'],
				'default' => [
					'top' => '5',
					'right' => '5',
					'bottom' => '5' ,
					'left' => '5',
				],
                'selectors' => [
                    '{{WRAPPER}} .hfb_social_media > li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
		);
		
		$this->add_responsive_control(
            'hfb_socialmedai_list_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'header-footer-builder' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .hfb_social_media > li > a i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .hfb_social_media > li > a svg' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'hfb_socialmedai_list_typography',
				'label' => esc_html__( 'Typography', 'header-footer-builder' ),
				'selector' => '{{WRAPPER}} .hfb_social_media > li > a',
			]
		);

        $this->add_control(
			'hfb_socialmedai_list_style_use_height_and_width',
			[
				'label' => esc_html__( 'Use Height Width', 'header-footer-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'header-footer-builder' ),
				'label_off' => esc_html__( 'Hide', 'header-footer-builder' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);


        $this->add_responsive_control(
			'hfb_socialmedai_list_width',
			[
				'label' => esc_html__( 'Width', 'header-footer-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .hfb_social_media > li > a' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
					'hfb_socialmedai_list_style_use_height_and_width' => 'yes',
					'hfb_socialmedia_style' => 'icon',
                ]
			]
		);

        $this->add_responsive_control(
			'hfb_socialmedai_list_height',
			[
				'label' => esc_html__( 'Height', 'header-footer-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .hfb_social_media > li > a' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'hfb_socialmedai_list_style_use_height_and_width' => 'yes',
					'hfb_socialmedia_style' => 'icon',
                ]
			]
		);

        $this->add_responsive_control(
			'hfb_socialmedai_list_line_height',
			[
				'label' => esc_html__( 'Line Height', 'header-footer-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 28,
				],
				'selectors' => [
					'{{WRAPPER}} .hfb_social_media > li > a' => 'line-height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'hfb_socialmedai_list_style_use_height_and_width' => 'yes'
                ]
			]
		);


		$this->end_controls_section();
	 }
	 protected function render( ) {
        echo '<div class="hfb-wid-con" >';
            $this->render_raw();
        echo '</div>';
    }

    protected function render_raw( ) {
		   $settings = $this->get_settings();
		   extract($settings);

		 ?>
			 <ul class="hfb_social_media">
				<?php foreach ($hfb_socialmedia_add_icons as $key => $icon): ?>
					<?php if($icon['hfb_socialmedia_icons'] != ''):

						if ( ! empty( $icon['hfb_socialmedia_link']['url'] ) ) {
							$this->add_link_attributes( 'button-' . $key, $icon['hfb_socialmedia_link'] );
						}
						
					?>
					<li class="elementor-repeater-item-<?php echo esc_attr( $icon[ '_id' ] ); ?>">
					    <a <?php echo $this->get_render_attribute_string(  'button-' . $key );
						
						// new icon
						$migrated = isset( $icon['__fa4_migrated']['hfb_socialmedia_icons'] );
						// Check if its a new widget without previously selected icon using the old Icon control
						$is_new = empty( $icon['hfb_socialmedia_icon'] );



						$getClass = explode('-', ($is_new || $migrated) ? $icon['hfb_socialmedia_icons']['library'] != 'svg' ? $icon['hfb_socialmedia_icons']['value'] : '' : $icon['hfb_socialmedia_icon'] );
						 $iconClass = !empty($getClass) ? end($getClass) : ''; ?> class="<?php echo esc_attr( $iconClass ); ?>" >
							<?php if($settings['hfb_socialmedia_style'] != 'text' && $settings['hfb_socialmedia_style_icon_position'] == 'before'): ?>
							
							<?php
								if ( $is_new || $migrated ) {
									// new icon
									Icons_Manager::render_icon( $icon['hfb_socialmedia_icons'], [ 'aria-hidden' => 'true' ] );
								} else {
									?>
									<i class="<?php echo esc_attr($icon['hfb_socialmedia_icon']); ?>" aria-hidden="true"></i>
									<?php
								}
							?>
									
                            <?php endif; ?>
                            <?php if($settings['hfb_socialmedia_style'] != 'icon' ): ?>
                            <?php echo esc_html($icon['hfb_socialmedia_label'])?>
                            <?php endif; ?>
                            <?php if($settings['hfb_socialmedia_style'] != 'text' && $settings['hfb_socialmedia_style_icon_position'] == 'after'): ?>
							
							<?php
								
								if ( $is_new || $migrated ) {
									// new icon
									Icons_Manager::render_icon( $icon['hfb_socialmedia_icons'], [ 'aria-hidden' => 'true' ] );
								} else {
									?>
									<i class="<?php echo esc_attr($icon['hfb_socialmedia_icon']); ?>" aria-hidden="true"></i>
									<?php
								}
							?>
							
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php endif; ?>
				<?php endforeach; ?>
			</ul>
		<?php
  	}
}
