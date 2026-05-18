<?php
namespace Elementor;

if ( ! function_exists('Elkit_Insert_Elementor') ){

	function Elkit_Insert_Elementor($atts){
	    if(!class_exists('Elementor\Plugin')){
	        return '';
	    }
	    if(!isset($atts['id']) || empty($atts['id'])){
	        return '';
	    }

	    $post_id = $atts['id'];

	    $response = Plugin::instance()->frontend->get_builder_content_for_display($post_id);
	    return $response;
	}
 
}

add_shortcode('ELKIT_INSERT_TPL','Elementor\Elkit_Insert_Elementor');


