<?php
namespace Elementor;
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Elkit_Font_Icons' ) ) {

	class Elkit_Font_Icons {

		//private static $instance = null;

		public function __construct() { 
			//add_filter( 'elementor/icons_manager/additional_tabs', array( $this, 'add_icons_tabs' ) );
		}

		public function add_icons_tabs( $tabs = array() ) {

			/* if ( get_option('icon-elementie-captain') ){
				$tabs['captain'] = array(
					'name'          => 'captain',
					'label'         => esc_html__( 'Captain', 'icon-element' ),
					'labelIcon'     => 'xlcaptain-100',
					'prefix'        => 'xlcaptain-',
					'displayPrefix' => 'xlcpt',
					'url'           => ICON_ELEM_URL . 'assets/captain/captain.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/captain/fonts/captain.json',
					'ver'           => '3.0.1',
				);
			} */

			/*if ( get_option('icon-elementie-elementor') ){
				$tabs['elementor'] = array(
					'name'          => 'elementor',
					'label'         => esc_html__( 'Elementor', 'icon-element' ),
					'labelIcon'     => 'eicon-elementor-circle',
					'prefix'        => 'eicon-',
					'displayPrefix' => 'eicon',
					'url'           => ICON_ELEM_URL . 'assets/elementor/elementor.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/elementor/fonts/elementor.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-feather') ){
				$tabs['feather'] = array(
					'name'          => 'feather',
					'label'         => esc_html__( 'Feather', 'icon-element' ),
					'labelIcon'     => 'feather feather-feather',
					'prefix'        => 'feather-',
					'displayPrefix' => 'feather',
					'url'           => ICON_ELEM_URL . 'assets/feather/feather.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/feather/fonts/feather.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-vscode') ){
				$tabs['vscode'] = array(
					'name'          => 'vscode',
					'label'         => esc_html__( 'Vscode', 'icon-element' ),
					'labelIcon'     => 'vscode-debug-rerun',
					'prefix'        => 'vscode-',
					'displayPrefix' => 'vscode',
					'url'           => ICON_ELEM_URL . 'assets/vscode/vscode.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/vscode/fonts/vscode.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-ionicons') ){

				$tabs['ionicons'] = array(
					'name'          => 'ionicons',
					'label'         => esc_html__( 'Ionicons', 'icon-element' ),
					'labelIcon'     => 'ion-ios-appstore',
					'prefix'        => 'ion-',
					'displayPrefix' => 'xlio',
					'url'           => ICON_ELEM_URL . 'assets/ionicons/css/ionicons.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/ionicons/fonts/ionicons.json',
					'ver'           => '3.0.1',
				);

			}*/

			//if ( get_option('icon-elementie-material-design') ){

				$tabs['md-icons'] = array(
					'name'          => 'material-design',
					'label'         => esc_html__( 'Material Design', 'icon-element' ),
					'labelIcon'     => 'fab fa-google',
					'prefix'        => 'md-',
					'displayPrefix' => 'material-icons',
					'url'           => ELKIT_RESOURCES_URL . 'icons/material-icons/css/material-icons.css',
					'fetchJson'     => ELKIT_RESOURCES_URL . 'icons/material-icons/fonts/icons.json',
					'ver'           => '4.0.0',
				);

			//}

			/*if ( get_option('icon-elementie-metrize') ){

				$tabs['metrize'] = array(
					'name'          => 'metrize',
					'label'         => esc_html__( 'Metrize', 'icon-element' ),
					'labelIcon'     => 'metriz-yen',
					'prefix'        => 'metriz-',
					'displayPrefix' => 'xlmetriz',
					'url'           => ICON_ELEM_URL . 'assets/metrize/metrize.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/metrize/fonts/metrize.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-simpline') ){

				$tabs['simpline'] = array(
					'name'          => 'simpline',
					'label'         => esc_html__( 'Simple Line', 'icon-element' ),
					'labelIcon'     => 'simpline-user',
					'prefix'        => 'simpline-',
					'displayPrefix' => 'xlsmpli',
					'url'           => ICON_ELEM_URL . 'assets/simple-line-icons/css/simple-line-icons.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/simple-line-icons/fonts/simple-line-icons.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-wppagebuilder') ){

				$tabs['wppagebuilder'] = array(
					'name'          => 'wppagebuilder',
					'label'         => esc_html__( 'Wp pagebuilder', 'icon-element' ),
					'labelIcon'     => 'wppb-font-balance',
					'prefix'        => 'wppb-font-',
					'displayPrefix' => 'xlwpf',
					'url'           => ICON_ELEM_URL . 'assets/wppagebuilder/wppagebuilder.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/wppagebuilder/fonts/wppagebuilder.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-wppagebuilder') ){

				$tabs['wppagebuilder'] = array(
					'name'          => 'wppagebuilder',
					'label'         => esc_html__( 'Wp pagebuilder', 'icon-element' ),
					'labelIcon'     => 'wppb-font-balance',
					'prefix'        => 'wppb-font-',
					'displayPrefix' => 'xlwpf',
					'url'           => ICON_ELEM_URL . 'assets/wppagebuilder/wppagebuilder.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/wppagebuilder/fonts/wppagebuilder.json',
					'ver'           => '3.0.1',
				);

			}*/

			return $tabs;
		}

		/* public static function get_instance() {

			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		} */
	}

}
