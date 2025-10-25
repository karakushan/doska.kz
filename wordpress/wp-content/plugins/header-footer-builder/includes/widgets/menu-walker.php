<?php
/**
 * HFB Menu Walker
 *
 * @package header-footer-elementor
 */

namespace HFB\WidgetsManager\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Menu_Walker.
 */
class Menu_Walker extends \Walker_Nav_Menu {

	/**
	 * Start element
	 *
	 * @since 1.3.0
	 * @param string $output Output HTML.
	 * @param object $item Individual Menu item.
	 * @param int    $depth Depth.
	 * @param array  $args Arguments array.
	 * @param int    $id Menu ID.
	 * @access public
	 */
	public function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$args   = (object) $args;

		$class_names = '';
		$value       = '';
		$rel_xfn     = '';
		$rel_blank   = '';

		$classes = empty( $item->classes ) ? [] : (array) $item->classes;
		$submenu = $args->has_children ? ' hfb-has-submenu' : '';

		if ( 0 === $depth ) {
			array_push( $classes, 'parent' );
		}
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = ' class="' . esc_attr( $class_names ) . $submenu . ' hfb-creative-menu"';
		$value       = apply_filters( 'nav_menu_li_values', $value );

		$output .= $indent . '<li id="menu-item-' . $item->ID . '"' . $value . $class_names . '>';

		if ( isset( $item->target ) && '_blank' === $item->target && isset( $item->xfn ) && false === strpos( $item->xfn, 'noopener' ) ) {
			$rel_xfn = ' noopener';
		}
		if ( isset( $item->target ) && '_blank' === $item->target && isset( $item->xfn ) && empty( $item->xfn ) ) {
			$rel_blank = 'rel="noopener"';
		}

		$attributes  = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . $rel_xfn . '"' : '' . $rel_blank;
		//$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';
		if (! empty( $item->url ) && $item->object == 'hfb_megamenu') {
			$attributes .= ' href="#"';
		}else{
			$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';
		}
		$atts = apply_filters( 'hfb_nav_menu_attrs', $attributes );
		
		
		$item_output  = $args->has_children ? '<div class="hfb-has-submenu-container">' : '';
		$item_output .= $args->before;
		$item_output .= '<a' . $atts;
		if ( 0 === $depth ) {
			$item_output .= ' class = "hfb-menu-item"';
		} else {
			$item_output .= in_array( 'current-menu-item', $item->classes ) ? ' class = "hfb-sub-menu-item hfb-sub-menu-item-active"' : ' class = "hfb-sub-menu-item"';
		}

		$item_output .= '>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		if ( $args->has_children || $item->object == 'hfb_megamenu') {
			$item_output .= "<span class='hfb-menu-toggle sub-arrow hfb-menu-child-";
			$item_output .= $depth;
			$item_output .= "'><i class='fa'></i></span>";
		}
		$item_output .= '</a>';
		if ( $item->object == 'hfb_megamenu' ) {
					$menu_post               = get_post( $item->object_id );
					$megamenu_max_width 	 = get_post_meta($item->object_id, '_wphb_megamenu_max_width', true);
					$hfb_megamenu_type 	 = get_post_meta($item->object_id, '_hfb_megamenu_type', true);
					$class_megamenu = array(
						apply_filters('hfb_css_class', 'hfb-mega-menu', $item, $args, $depth),
						'hfb-sub-menu',
						(empty($hfb_megamenu_type)) ? 'full-container' : $hfb_megamenu_type,
					);
					/*if (!empty($megamenu_max_width) && $hfb_megamenu_type == 'custom-width') {
						$class_css = uniqid('hfb_custom_');
						hfb_shortcode_custom_css_class(array('width' => $megamenu_max_width), $class_css);
						array_push($class_megamenu, $class_css);
					}*/
					
					//$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;

					
					$item_output .= '<div class="' . esc_attr( implode(' ', $class_megamenu) ) . '">' . do_shortcode( \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($menu_post->ID) ) . '</div>';
					
					
				}
		$item_output .= $args->after;
		$item_output .= $args->has_children ? '</div>' : '';

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Display element
	 *
	 * @since 1.3.0
	 * @param object $element Individual Menu element.
	 * @param object $children_elements Child Elements.
	 * @param int    $max_depth Maximum Depth.
	 * @param int    $depth Depth.
	 * @param array  $args Arguments array.
	 * @param string $output Output HTML.
	 * @access public
	 */
	function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {

		$id_field = $this->db_fields['id'];

		if ( is_object( $args[0] ) ) {
			$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
		}
		return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}
}

