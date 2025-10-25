<?php
/**
 * Elementor Classes.
 *
 * @package header-footer-elementor
 */

namespace HFB\WidgetsManager\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

use HFB\WidgetsManager\Widgets_Loader;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * HFB Page Title widget
 *
 * HFB widget for Page Title.
 *
 * @since 1.3.0
 */
class Pacz_Page_Title extends Widget_Base {


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
		return 'pacz-page-title';
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
		return __( 'Classiads Page Title', 'pacz' );
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
		return 'hfb-icon-page-title';
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
	 * Register Page Title controls.
	 *
	 * @since 1.5.7
	 * @access protected
	 */
	protected function register_controls() {
		$this->register_content_page_title_controls();
		$this->register_page_title_style_controls();
	}

	/**
	 * Register Page Title General Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function register_content_page_title_controls() {
		$this->start_controls_section(
			'section_general_fields',
			[
				'label' => __( 'Title', 'pacz' ),
			]
		);

		$this->add_control(
			'archive_title_note',
			[
				'type'            => Controls_Manager::RAW_HTML,
				/* translators: %1$s doc link */
				'raw'             => sprintf( __( '<b>Note:</b> Archive page title will be visible on frontend.', 'pacz' ) ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
			]
		);
		$this->add_control(
			'full_width_container',
			[
				'label'        => __( 'Enable Full Width Container', 'pacz' ),
				'type'         => Controls_Manager::SWITCHER,
				'1'     => __( 'Yes', 'pacz' ),
				'0'    => __( 'No', 'pacz' ),
				'description' => __( 'Section should be full width as well to make it work.', 'pacz' ),
				'default'      => 0,
			]
		);
		$this->add_control(
			'layout',
			[
				'label'   => __( 'Layout', 'pacz' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'title_breadcrumb' => __( 'Title + Breadcrumbs (title at top)', 'pacz' ),
					'breadcrumb_title' => __( 'Breadcrumbs (Breadcrumbs at top)', 'pacz' ),
					'title_only' => __( 'Title only', 'pacz' ),
				],
				'default' => 'title_breadcrumb',
			]
		);
		$this->add_control(
			'heading_tag',
			[
				'label'   => __( 'HTML Tag', 'pacz' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1' => __( 'H1', 'pacz' ),
					'h2' => __( 'H2', 'pacz' ),
					'h3' => __( 'H3', 'pacz' ),
					'h4' => __( 'H4', 'pacz' ),
					'h5' => __( 'H5', 'pacz' ),
					'h6' => __( 'H6', 'pacz' ),
				],
				'default' => 'h2',
			]
		);

		$this->add_responsive_control(
			'title_align',
			[
				'label'              => __( ' Title Alignment', 'pacz' ),
				'type'               => Controls_Manager::CHOOSE,
				'options'            => [
					'left'    => [
						'title' => __( 'Left', 'pacz' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => __( 'Center', 'pacz' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => __( 'Right', 'pacz' ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'pacz' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'default'            => '',
				'selectors'          => [
					'{{WRAPPER}} .hfb-page-title-wrapper #pacz-hfb-page-title' => 'text-align: {{VALUE}};',
				],
				'frontend_available' => true,
			]
		);
		$this->add_responsive_control(
			'breadcrumbs_align',
			[
				'label'              => __( 'Breadcrumbs Alignment', 'pacz' ),
				'type'               => Controls_Manager::CHOOSE,
				'options'            => [
					'left'    => [
						'title' => __( 'Left', 'pacz' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => __( 'Center', 'pacz' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => __( 'Right', 'pacz' ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'pacz' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'default'            => '',
				'selectors'          => [
					'{{WRAPPER}} .hfb-page-title-wrapper #pacz-hfb-breadcrumbs' => 'text-align: {{VALUE}};',
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}
	/**
	 * Register Page Title Style Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function register_page_title_style_controls() {
		$this->start_controls_section(
			'container_styling',
			[
				'label' => __( 'Container', 'pacz' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'location_padding',
			[
				'label' => __( 'Padding', 'pacz' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .hfb-page-title-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'container_background',
				'label' => esc_html__( 'Background', 'pacz' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .hfb-page-title-wrapper',
			]
		);
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_title_typography',
			[
				'label' => __( 'Title', 'pacz' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'title_typography',
					'global'   => [
						'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'selector' => '{{WRAPPER}} .pacz-hfb-page-heading',
				]
			);

			$this->add_control(
				'title_color',
				[
					'label'     => __( 'Color', 'pacz' ),
					'type'      => Controls_Manager::COLOR,
					'global'    => [
						'default' => Global_Colors::COLOR_PRIMARY,
					],
					'selectors' => [
						'{{WRAPPER}} .pacz-hfb-page-heading' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name'     => 'title_shadow',
					'selector' => '{{WRAPPER}} .pacz-hfb-page-heading',
				]
			);

			$this->add_control(
				'blend_mode',
				[
					'label'     => __( 'Blend Mode', 'pacz' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => [
						''            => __( 'Normal', 'pacz' ),
						'multiply'    => 'Multiply',
						'screen'      => 'Screen',
						'overlay'     => 'Overlay',
						'darken'      => 'Darken',
						'lighten'     => 'Lighten',
						'color-dodge' => 'Color Dodge',
						'saturation'  => 'Saturation',
						'color'       => 'Color',
						'difference'  => 'Difference',
						'exclusion'   => 'Exclusion',
						'hue'         => 'Hue',
						'luminosity'  => 'Luminosity',
					],
					'selectors' => [
						'{{WRAPPER}} .pacz-hfb-page-heading' => 'mix-blend-mode: {{VALUE}}',
					],
				]
			);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_breadcrumbs_typography',
			[
				'label' => __( 'Breadcrumbs', 'pacz' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'breadcrumbs_typography',
					'global'   => [
						'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'selector' => '{{WRAPPER}} .pacz-hfb-breadcrumbs-inner, {{WRAPPER}} .pacz-hfb-breadcrumbs-inner a',
				]
			);

			$this->add_control(
				'breadcrumbs_color',
				[
					'label'     => __( 'Color', 'pacz' ),
					'type'      => Controls_Manager::COLOR,
					'global'    => [
						'default' => Global_Colors::COLOR_PRIMARY,
					],
					'selectors' => [
						'{{WRAPPER}} .pacz-hfb-breadcrumbs-inner' => 'color: {{VALUE}};',
						'{{WRAPPER}} #pacz-hfb-breadcrumbs .pacz-hfb-breadcrumbs-inner a' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name'     => 'breadcrumbs_shadow',
					'selector' => '{{WRAPPER}} .pacz-hfb-breadcrumbs-inner, {{WRAPPER}} .pacz-hfb-breadcrumbs-inner a',
				]
			);

			$this->add_control(
				'breadcrumbs_blend_mode',
				[
					'label'     => __( 'Blend Mode', 'pacz' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => [
						''            => __( 'Normal', 'pacz' ),
						'multiply'    => 'Multiply',
						'screen'      => 'Screen',
						'overlay'     => 'Overlay',
						'darken'      => 'Darken',
						'lighten'     => 'Lighten',
						'color-dodge' => 'Color Dodge',
						'saturation'  => 'Saturation',
						'color'       => 'Color',
						'difference'  => 'Difference',
						'exclusion'   => 'Exclusion',
						'hue'         => 'Hue',
						'luminosity'  => 'Luminosity',
					],
					'selectors' => [
						'{{WRAPPER}} .pacz-hfb-breadcrumbs-inner' => 'mix-blend-mode: {{VALUE}}',
						'{{WRAPPER}} .pacz-hfb-breadcrumbs-inner a' => 'mix-blend-mode: {{VALUE}}',
					],
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Render page title widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'page_title', 'basic' );
		if ( ! empty( $settings['page_heading_link']['url'] ) ) {
			$this->add_link_attributes( 'url', $settings['page_heading_link'] );
		}

		$heading_size_tag = Widgets_Loader::validate_html_tag( $settings['heading_tag'] );
		
		$this->pacz_page_title();


	}
	
	public function pacz_page_title() {
		global $pacz_settings,$event_id,$post,$page_id,$event;
		$settings = $this->get_settings_for_display();
		extract($settings);
		$post_id = global_get_post_id();
		$heading_size_tag = Widgets_Loader::validate_html_tag( $settings['heading_tag'] );
		$title = $breadcrumb = '';
		$align = 'center';
		if ($post_id) {
			$template = get_post_meta( $post_id, '_template', true );
			$breadcrumb = get_post_meta( $post_id, '_breadcrumb', true );
			$align = ($align != '') ? $align : 'center';

			if ( $template == 'no-title' ) return false;
			$title = get_the_title( $post_id );
		}
		if(is_archive() && $pacz_settings['archive-page-title'] == 0) return false;
		
		if(is_home() && get_option('page_for_posts')){
			
			$title = get_the_title( get_option('page_for_posts', true) );
			
			
		}elseif(is_home()){
			$title = esc_html__( 'Blog', 'pacz' );
		}
		if(function_exists( 'is_woocommerce' ) && is_woocommerce() && is_shop() && $pacz_settings['woo-shop-loop-title'] == 0) return false;

		if(function_exists( 'is_woocommerce' ) && is_woocommerce() && is_singular('product') && $pacz_settings['woo-single-show-title'] == 0) return false;

		if(is_singular('post')) {
			if (isset($pacz_settings['page-title-blog']) && $pacz_settings['page-title-blog'] == 0 ) return false;
			if (isset($pacz_settings['page-title-blog']) && $pacz_settings['page-title-blog'] == 1 ) {
				$title = esc_html__( 'Latest News', 'pacz' );
			}
		}
		if ( is_search() ) {
			$title = esc_html__( 'Search', 'pacz' );
		}
		if ( is_archive() ) {
			if ( is_category() ) {
				$title = sprintf( esc_html__( ' %s', 'pacz' ), single_cat_title( '', false ) );
			}
			elseif ( is_tag() ) {
				$title = sprintf( esc_html__( ' %s', 'pacz' ), single_tag_title( '', false ) );
			}
			elseif ( is_day() ) {
				$title = sprintf( esc_html__( ' %s', 'pacz' ), get_the_time( 'F jS, Y' ) );
			}
			elseif ( is_month() ) {
				$title = sprintf( esc_html__( '%s', 'pacz' ), get_the_time( 'F, Y' ) );
			}
			elseif ( is_year() ) {
				$title = sprintf( esc_html__( ' %s', 'pacz' ), get_the_time( 'Y' ) );
			}
			elseif ( is_author() ) {
				$title = esc_html__( 'Author Profile', 'pacz' );
			}
			elseif ( is_tax() ) {
				$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
				$title = sprintf( esc_html__( ' %s', 'pacz' ), $term->name );
			}
		}
		if ( is_404() ) {
			$title = esc_html__('404 Not Found', 'pacz');

		}
		
		
		if ( function_exists( 'is_bbpress' ) && is_bbpress() ) {
			if ( bbp_is_forum_archive() ) {
				$title = bbp_get_forum_archive_title();

			} elseif ( bbp_is_topic_archive() ) {
				$title = bbp_get_topic_archive_title();

			} elseif ( bbp_is_single_view() ) {
				$title = bbp_get_view_title();
			} elseif ( bbp_is_single_forum() ) {

				$forum_id = get_queried_object_id();
				$forum_parent_id = bbp_get_forum_parent_id( $forum_id );

				if ( 0 !== $forum_parent_id )
					$title = array_merge( $item, breadcrumbs_plus_get_parents( $forum_parent_id ) );

				$title = bbp_get_forum_title( $forum_id );
			}
			elseif ( bbp_is_single_topic() ) {
				$topic_id = get_queried_object_id();
				$title = bbp_get_topic_title( $topic_id );
			}

			elseif ( bbp_is_single_user() || bbp_is_single_user_edit() ) {
				$title = bbp_get_displayed_user_field( 'display_name' );
			}
		}


		if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
			if(is_single() && isset($pacz_settings['woo-single-title']) && $pacz_settings['woo-single-title'] == 1) {
				$terms = get_the_terms( $post_id, 'product_cat' );
				if(is_array($terms) && $terms != null) {
				foreach ($terms as $term) {
				    $product_category = $term->name;
				    break;
				}
					$title = $product_category;
				} else {
					ob_start();
					woocommerce_page_title();
					$title = ob_get_clean();
				}
			} else {
				ob_start();
				woocommerce_page_title();
				$title = ob_get_clean();
			}


		}
		
		$has_breadcrumb = ($pacz_settings['breadcrumb'] && $breadcrumb != 'false')? 'has_creadcrumb' : 'no-breadcrumb';
		if(!empty($title)){
			echo '<div class="hfb-page-title hfb-page-title-wrapper elementor-widget-heading">';
				if(!$settings['full_width_container']){
					echo '<div class="container">';
				}
					echo '<section id="pacz-hfb-page-title" class="'. $has_breadcrumb .'">';
						if($settings['layout'] == 'breadcrumb_title'){
							if ( $pacz_settings['breadcrumb'] != 0 ) {
								if ( $breadcrumb != 'false' ) {
									$this->pacz_theme_breadcrumbs();
								}
							}
						}
						echo '<'. $heading_size_tag .' class="pacz-hfb-page-heading">' . $title . '</'. $heading_size_tag .'>';
						if($settings['layout'] == 'title_breadcrumb'){
							if ( $pacz_settings['breadcrumb'] != 0 ) {
								if ( $breadcrumb != 'false' ) {
									$this->pacz_theme_breadcrumbs();
								}
							}
						}
				
					echo '</section>';
				if(!$settings['full_width_container']){
					echo '</div>';
				}
			echo '</div>';
		}

	}
	public function pacz_theme_breadcrumbs() {
        global $pacz_settings, $post;
		$output = '';

		$post_id = global_get_post_id();

		$breadcrumb_skin = (isset($pacz_settings['breadcrumb-skin']) && !empty($pacz_settings['breadcrumb-skin'])) ? $pacz_settings['breadcrumb-skin'].'-skin' : 'dark-skin';


		$delimiter =  ' &#8739; ';

        echo '<div id="pacz-hfb-breadcrumbs"><div class="pacz-hfb-breadcrumbs-inner '.$breadcrumb_skin.'">';

        if ( !is_front_page() ) {
	        echo '<a href="';
				echo esc_url(home_url('/'));
					echo '">'.esc_html__('Home', 'pacz');
	        echo "</a>";
        }
		
        if(function_exists('is_woocommerce') && is_woocommerce() && is_archive()) {
        	$shop_page_id = wc_get_page_id( 'shop' );
			$shop_page    = get_post( $shop_page_id );
			$permalinks   = get_option( 'woocommerce_permalinks' );
        	if ( $shop_page_id && $shop_page ) {
				echo  esc_html($delimiter) .'<a href="' . get_permalink( $shop_page ) . '">' . $shop_page->post_title . '</a> ';
			}
        }
		
		if(is_singular('post')) {
            echo esc_html($delimiter) .'<span>'.get_the_title().'</span>';

        } else if ( is_single() && ! is_attachment()) {
		      	
		       if ( get_post_type() == 'product' ) {

					if ( $terms = wc_get_product_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {

						$main_term = $terms[0];

						$ancestors = get_ancestors( $main_term->term_id, 'product_cat' );

						$ancestors = array_reverse( $ancestors );

						foreach ( $ancestors as $ancestor ) {
							$ancestor = get_term( $ancestor, 'product_cat' );

							if ( ! is_wp_error( $ancestor ) && $ancestor )
								echo esc_html($delimiter) .'<a href="' . get_term_link( $ancestor->slug, 'product_cat' ) . '">' . $ancestor->name . '</a>' .  $delimiter;
						}

						echo  esc_html($delimiter) .'<a href="' . get_term_link( $main_term->slug, 'product_cat' ) . '">' . $main_term->name . '</a>' . $delimiter;

					}

					echo  get_the_title();

				} elseif ( get_post_type() != 'post') {

		        	if(function_exists( 'is_bbpress' ) && is_bbpress()) {

		        	} else {
		        		$post_type = get_post_type_object( get_post_type() );
						$slug = $post_type->rewrite;
							echo  esc_html($delimiter) .'<a href="' . get_post_type_archive_link( get_post_type() ) . '">' . $post_type->labels->singular_name . '</a>' .$delimiter;
						echo get_the_title();
		        	}

				} else {
						echo esc_html($delimiter) . get_the_title();	
				}
		}  elseif ( is_page() && !$post->post_parent ) {

			echo esc_html($delimiter) . get_the_title();

		} elseif ( is_page() && $post->post_parent ) {

			$parent_id  = $post->post_parent;
			$breadcrumbs = array();

			while ( $parent_id ) {
				$page = get_page( $parent_id );
				$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title( $page->ID ) . '</a>';
				$parent_id  = $page->post_parent;
			}

			$breadcrumbs = array_reverse( $breadcrumbs );

			foreach ( $breadcrumbs as $crumb )
				echo wp_kses_post($crumb) . '' . $delimiter;

			echo get_the_title();

		} elseif ( is_attachment() ) {

			$parent = get_post( $post->post_parent );
			$cat = get_the_category( $parent->ID );
			$cat = $cat[0];
			/* admin@innodron.com patch: 
	        Fix for Catchable fatal error: Object of class WP_Error could not be converted to string
	        ref: https://wordpress.org/support/topic/catchable-fatal-error-object-of-class-wp_error-could-not-be-converted-to-string-11
		    */
		    echo is_wp_error( $cat_parents = get_category_parents($cat, TRUE, '' . $delimiter . '') ) ? '' : $cat_parents;
		   /* end admin@innodron.com patch */
			echo esc_html($delimiter) .' <a href="' . get_permalink( $parent ) . '">' . $parent->post_title . '</a>' . $delimiter;
			echo  get_the_title();

		}	elseif ( is_search() ) {

		echo esc_html($delimiter) . esc_html__( 'Search results for &ldquo;', 'pacz' ) . get_search_query() . '&rdquo;';

		} elseif ( is_tag() ) {

				echo esc_html($delimiter) . esc_html__( 'Tag &ldquo;', 'pacz' ) . single_tag_title('', false) . '&rdquo;';

		} elseif ( is_author() ) {

			$userdata = get_userdata(get_the_author_meta('ID'));
			echo  esc_html($delimiter) . esc_html__( 'Author:', 'pacz' ) . ' ' . $userdata->display_name;

		} elseif ( is_day() ) {

			echo esc_html($delimiter) .'<a href="' . get_year_link( get_the_time( 'Y' ) ) . '">' . get_the_time( 'Y' ) . '</a>' . $delimiter;
			echo '<a href="' . get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) . '">' . get_the_time( 'F' ) . '</a>' . $delimiter;
			echo get_the_time( 'd' );

		} elseif ( is_month() ) {

			echo esc_html($delimiter) .'<a href="' . get_year_link( get_the_time( 'Y' ) ) . '">' . get_the_time( 'Y' ) . '</a>' . $delimiter;
			echo get_the_time( 'F' );

		} elseif ( is_year() ) {

			echo  esc_html($delimiter) . get_the_time( 'Y' );

		} 

		if ( get_query_var( 'paged' ) )
			echo esc_html($delimiter) .' (' . esc_html__( 'Page', 'pacz' ) . ' ' . get_query_var( 'paged' ) . ')';
		

        if (is_tax()) {
            $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
            echo esc_attr($delimiter) . '<span>'.$term->name.'</span>';
        }
        
        if ( function_exists( 'is_bbpress' ) && is_bbpress() ) {
        	$item = array();

				$post_type_object = get_post_type_object( bbp_get_forum_post_type() );

				if ( !empty( $post_type_object->has_archive ) && !bbp_is_forum_archive() ){
					$item[] = '<a href="' . get_post_type_archive_link( bbp_get_forum_post_type() ) . '">' . bbp_get_forum_archive_title() . '</a>';
				}

				if ( bbp_is_forum_archive() ){
					$item[] = bbp_get_forum_archive_title();
				}

				elseif ( bbp_is_topic_archive() ){
					$item[] = bbp_get_topic_archive_title();
				}

				elseif ( bbp_is_single_view() ){
					$item[] = bbp_get_view_title();
				}

				elseif ( bbp_is_single_topic() ) {

					$topic_id = get_queried_object_id();

					$item = array_merge( $item, pacz_breadcrumbs_get_parents( bbp_get_topic_forum_id( $topic_id ) ) );

					if ( bbp_is_topic_split() || bbp_is_topic_merge() || bbp_is_topic_edit() )
						$item[] = '<a href="' . bbp_get_topic_permalink( $topic_id ) . '">' . bbp_get_topic_title( $topic_id ) . '</a>';
					else
						$item[] = bbp_get_topic_title( $topic_id );

					if ( bbp_is_topic_split() )
						$item[] = esc_html__( 'Split', 'pacz' );

					elseif ( bbp_is_topic_merge() )
						$item[] = esc_html__( 'Merge', 'pacz' );

					elseif ( bbp_is_topic_edit() )
						$item[] = esc_html__( 'Edit', 'pacz' );
				}

				elseif ( bbp_is_single_reply() ) {

					$reply_id = get_queried_object_id();

					$item = array_merge( $item, pacz_breadcrumbs_get_parents( bbp_get_reply_topic_id( $reply_id ) ) );

					if ( !bbp_is_reply_edit() ) {
						$item[] = bbp_get_reply_title( $reply_id );

					} else {
						$item[] = '<a href="' . bbp_get_reply_url( $reply_id ) . '">' . bbp_get_reply_title( $reply_id ) . '</a>' ; 
						$item[] = esc_html__( 'Edit', 'pacz' );
					}

				}

				elseif ( bbp_is_single_forum() ) {

					$forum_id = get_queried_object_id();
					$forum_parent_id = bbp_get_forum_parent_id( $forum_id );

					if ( 0 !== $forum_parent_id)
						$item = array_merge( $item, pacz_breadcrumbs_get_parents( $forum_parent_id ) );

					$item[] = bbp_get_forum_title( $forum_id );
				}

				elseif ( bbp_is_single_user() || bbp_is_single_user_edit() ) {

					if ( bbp_is_single_user_edit() ) {
						$item[] = '<a href="' . bbp_get_user_profile_url() . '">' . bbp_get_displayed_user_field( 'display_name' ) . '</a>';
						$item[] = esc_html__( 'Edit', 'pacz'  );
					} else {
						$item[] = bbp_get_displayed_user_field( 'display_name' );
					}
				}

				echo implode($delimiter, $item);


        }
	
        echo "</div></div>";
	}

	/**
	 * Render page title output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function content_template() {

		
	}
}
