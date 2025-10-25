<?php 

/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage ClassiadsPro
 * @since ClassiadsPro 1.0
 */

	global $post,
	$pacz_settings;
	$layout = $pacz_settings['error-layout'];
	$template = $pacz_settings['error_page'];
	$template_page_id = (isset($pacz_settings['error_page_id']))? $pacz_settings['error_page_id'] : '';
	$error_small_text = $pacz_settings['error_page_small_text'];
	get_header();
	if($template == '2' && !empty($template_page_id)){ 
		if (did_action('elementor/loaded') && Elementor\Plugin::instance()->db->is_built_with_elementor($template_page_id) ) {
			echo do_shortcode( Elementor\Plugin::instance()->frontend->get_builder_content_for_display($template_page_id) );
		}else{
			$page   = get_post( $template_page_id );
			echo apply_filters( 'the_content', $page->post_content );
		}
	}else{
		get_template_part( 'includes/templates/404/error-404');
	}
?>
	
<?php get_footer(); ?>