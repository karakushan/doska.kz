<?php
/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     ReduxFramework
 * @subpackage  Field_Color_Gradient
 * @author      Luciano "WebCaos" Ubertini
 * @author      Daniel J Griffiths (Ghost1227)
 * @author      Dovy Paukstys
 * @version     3.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
// Don't duplicate me!
if ( ! class_exists( 'Redux_Nav_Color', false ) ) {

	/**
	 * Main Redux_link_color class
	 *
	 * @since       1.0.0
	 */
	class Redux_Nav_Color extends Redux_Field {
    
        /**
         * Field Constructor.
         *
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function set_defaults() {
        
            $defaults = array(
                'regular' => true,
                'hover' => true,
                'bg' => false,
                'bg-hover' => false,
				'bg-active' => false,
            );
            $this->field = wp_parse_args( $this->field, $defaults );

            $defaults = array(
                'regular' => '',
                'hover' => '',
                'bg' => '',
                'bg-hover' => '',
				'bg-active' => '',
            );

            $this->value = wp_parse_args( $this->value, $defaults );  

            // In case user passes no default values.
			if ( isset( $this->field['default'] ) ) {
				$this->field['default'] = wp_parse_args( $this->field['default'], $defaults );
			} else {
				$this->field['default'] = $defaults;
			}    
        
        }

        /**
         * Field Render Function.
         *
         * Takes the vars and outputs the HTML for the field in the settings
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function render() {
			
			if ( true === $this->field['regular'] && false !== $this->field['default']['regular'] ) {
				echo '<span class="linkColor">';
				echo '<strong>' . esc_html__( 'Regular', 'redux-framework' ) . '</strong>&nbsp;';
				echo '<input ';
				echo 'id="' . esc_attr( $this->field['id'] ) . '-regular" ';
				echo 'name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[regular]"';
				echo 'value="' . esc_attr( $this->value['regular'] ) . '"';
				echo 'class="color-picker redux-color redux-color-regular redux-color-init ' . esc_attr( $this->field['class'] ) . '"';
				echo 'type="text"';
				echo 'data-default-color="' . esc_attr( $this->field['default']['regular'] ) . '"';

				$data = array(
					'field' => $this->field,
					'index' => 'regular',
				);

				echo Redux_Functions_Ex::output_alpha_data( $data ); // phpcs:ignore WordPress.Security.EscapeOutput

				echo '>';
				echo '</span>';
			}

			if ( true === $this->field['hover'] && false !== $this->field['default']['hover'] ) {
				echo '<span class="linkColor">';
				echo '<strong>' . esc_html__( 'Hover', 'redux-framework' ) . '</strong>&nbsp;';
				echo '<input ';
				echo 'id="' . esc_attr( $this->field['id'] ) . '-hover"';
				echo 'name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[hover]"';
				echo 'value="' . esc_attr( $this->value['hover'] ) . '"';
				echo 'class="color-picker redux-color redux-color-hover redux-color-init ' . esc_attr( $this->field['class'] ) . '"';
				echo 'type="text"';
				echo 'data-default-color="' . esc_attr( $this->field['default']['hover'] ) . '"';

				$data = array(
					'field' => $this->field,
					'index' => 'hover',
				);

				echo Redux_Functions_Ex::output_alpha_data( $data ); // phpcs:ignore WordPress.Security.EscapeOutput

				echo '>';
				echo '</span>';
			}

			if ( true === $this->field['bg'] && false !== $this->field['default']['bg'] ) {
				echo '<span class="linkColor">';
				echo '<strong>' . esc_html__( 'Background Color', 'redux-framework' ) . '</strong>&nbsp;';
				echo '<input ';
				echo 'id="' . esc_attr( $this->field['id'] ) . '-bg"';
				echo 'name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[bg]"';
				echo 'value="' . esc_attr( $this->value['bg'] ) . '"';
				echo 'class="color-picker redux-color redux-color-bg redux-color-init ' . esc_attr( $this->field['class'] ) . '"';
				echo 'type="text"';
				echo 'data-default-color="' . esc_attr( $this->field['default']['bg'] ) . '"';

				$data = array(
					'field' => $this->field,
					'index' => 'visited',
				);

				echo Redux_Functions_Ex::output_alpha_data( $data ); // phpcs:ignore WordPress.Security.EscapeOutput

				echo '>';
				echo '</span>';
			}

			if ( true === $this->field['bg-hover'] && false !== $this->field['default']['bg-hover'] ) {
				echo '<span class="linkColor">';
				echo '<strong>' . esc_html__( 'Hover Background Color', 'redux-framework' ) . '</strong>&nbsp;';
				echo '<input ';
				echo 'id="' . esc_attr( $this->field['id'] ) . '-bg-hover"';
				echo 'name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[bg-hover]"';
				echo 'value="' . esc_attr( $this->value['bg-hover'] ) . '"';
				echo 'class="color-picker redux-color redux-color-bg-hover redux-color-init ' . esc_attr( $this->field['class'] ) . '"';
				echo 'type="text"';
				echo 'data-default-color="' . esc_attr( $this->field['default']['bg-hover'] ) . '"';

				$data = array(
					'field' => $this->field,
					'index' => 'bg-hover',
				);

				echo Redux_Functions_Ex::output_alpha_data( $data ); // phpcs:ignore WordPress.Security.EscapeOutput

				echo '>';
				echo '</span>';
			}

			if ( true === $this->field['bg-active'] && false !== $this->field['default']['bg-active'] ) {
				echo '<span class="linkColor">';
				echo '<strong>' . esc_html__( 'Active Background Color', 'redux-framework' ) . '</strong>&nbsp;';
				echo '<input ';
				echo 'id="' . esc_attr( $this->field['id'] ) . '-bg-active"';
				echo 'name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[bg-active]"';
				echo 'value="' . esc_attr( $this->value['bg-active'] ) . '"';
				echo 'class="color-picker redux-color redux-color-bg-active redux-color-init ' . esc_attr( $this->field['class'] ) . '"';
				echo 'type="text"';
				echo 'data-default-color="' . esc_attr( $this->field['default']['bg-active'] ) . '"';

				$data = array(
					'field' => $this->field,
					'index' => 'bg-active',
				);

				echo Redux_Functions_Ex::output_alpha_data( $data ); // phpcs:ignore WordPress.Security.EscapeOutput

				echo '>';
				echo '</span>';
			}
        
        }



         /**
             * Enqueue Function.
             * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
             *
             * @since       1.0.0
             * @access      public
             * @return      void
             */
            public function enqueue() {


               wp_enqueue_script(
                    'redux-field-nav-color-js',
                    Redux_Core::$url . 'inc/fields/nav_color/redux-nav-color.js',
                    array( 'jquery', 'wp-color-picker', 'redux-js' ),
                    time(),
                    true
                );
            }
    
    }
}
?>
