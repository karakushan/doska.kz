<!DOCTYPE html>
<html <?php if(function_exists('custom_vc_init')){ pacz_html_tag_schema();} ?> <?php language_attributes(); ?>>

	<head>
			<meta charset="<?php bloginfo( 'charset' ); ?>" />
			<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
			
			<?php if ( ! function_exists( 'wp_site_icon' ) ) : ?>
			<?php $pacz_settings = $GLOBALS['pacz_settings'];?>
			<?php if ( $pacz_settings['favicon']['url'] ) { ?>
			  <link rel="shortcut icon" href="<?php echo esc_url($pacz_settings['favicon']['url']); ?>"  />
			<?php } ?>
			<?php endif; ?>
			
		<?php wp_head(); ?>
	</head>
<body <?php body_class('skin-blue'); ?>>
	<?php wp_body_open(); ?>
	<?php

	global $pacz_settings;
	if(defined('GD_SINGLE_PAGE_TEMP_ID')){
		$post_id = GD_SINGLE_PAGE_TEMP_ID;	
	}else{
		$post_id = global_get_post_id();	
	}

	 $preset_headers = $pacz_settings['preset_headers'];
	if($preset_headers == 10){
		
	}else if($preset_headers == 12){ 
		$boxed_header = $pacz_settings['boxed-header'];
		if($post_id || !$post_id) {
		global $pacz_settings;

			$header_structure = (get_post_meta( $post_id, '_custom_bg', true ) == 'true') ? get_post_meta( $post_id, 'header-structure', true ) : $pacz_settings['header-structure'];
			$header_align = (get_post_meta( $post_id, '_custom_bg', true ) == 'true') ? get_post_meta( $post_id, 'header-align', true ) : $pacz_settings['header-align'];
			$header_grid = (get_post_meta( $post_id, '_custom_bg', true ) == 'true') ? get_post_meta( $post_id, 'header-grid', true ) : $pacz_settings['header-grid'];
			$sticky_header = (get_post_meta( $post_id, '_custom_bg', true ) == 'true') ? get_post_meta( $post_id, 'sticky-header', true ) : $pacz_settings['sticky-header'];
			$squeeze_sticky_header =isset($pacz_settings['squeeze-sticky-header']) ? $pacz_settings['squeeze-sticky-header'] : 1;
			
		}
		$toolbar =(isset($pacz_settings['header-toolbar']) && !empty($pacz_settings['header-toolbar'])) ? $pacz_settings['header-toolbar'] : 0;
		$toolbar_check = get_post_meta( $post_id, '_header_toolbar', true );
		$toolbar_option = !empty($toolbar_check) ? $toolbar_check : 'true';
		
		$header_toolbar_grid = $pacz_settings['toolbar-grid'];
		
		$pacz_logo_location = $pacz_settings['header-logo-location'];
		$pacz_logo_align = $pacz_settings['header-logo-align']; 
		
		$header_toolbar_social_location = $pacz_settings['header-social-select']; 
		$header_toolbar_social_align = $pacz_settings['header-social-align'];
		
		$listing_btn_location = $pacz_settings['listing-btn-location'];
		$listing_btn_align = $pacz_settings['listing-btn-align'];
		
		$login_reg_btn_location = 'header-section';
		$login_reg_btn_align =  'right';
		
		$header_contact_details_location = $pacz_settings['header-contact-select'] ;
		$header_contact_details_align = $pacz_settings['header-contact-align'] ;

	}else{
			$boxed_header = $pacz_settings['boxed-header'];
		if($post_id || !$post_id) {
		global $pacz_settings;

			$header_structure = (get_post_meta( $post_id, '_custom_bg', true ) == 'true') ? get_post_meta( $post_id, 'header-structure', true ) : $pacz_settings['header-structure'];
			$header_align = (get_post_meta( $post_id, '_custom_bg', true ) == 'true') ? get_post_meta( $post_id, 'header-align', true ) : $pacz_settings['header-align'];
			$header_grid = (get_post_meta( $post_id, '_custom_bg', true ) == 'true') ? get_post_meta( $post_id, 'header-grid', true ) : $pacz_settings['header-grid'];
			$sticky_header = (get_post_meta( $post_id, '_custom_bg', true ) == 'true') ? get_post_meta( $post_id, 'sticky-header', true ) : $pacz_settings['sticky-header'];
			$squeeze_sticky_header =isset($pacz_settings['squeeze-sticky-header']) ? $pacz_settings['squeeze-sticky-header'] : 1;
			
		}
		$toolbar =(isset($pacz_settings['header-toolbar']) && !empty($pacz_settings['header-toolbar'])) ? $pacz_settings['header-toolbar'] : 0;
		$toolbar_check = get_post_meta( $post_id, '_header_toolbar', true );
		$toolbar_option = !empty($toolbar_check) ? $toolbar_check : 'true';
		
		$header_toolbar_grid = $pacz_settings['toolbar-grid'];
		
		$pacz_logo_location = $pacz_settings['header-logo-location'];
		$pacz_logo_align = $pacz_settings['header-logo-align']; 
		
		$header_toolbar_social_location = $pacz_settings['header-social-select']; 
		$header_toolbar_social_align = $pacz_settings['header-social-align'];
		
		$listing_btn_location = $pacz_settings['listing-btn-location'];
		$listing_btn_align = $pacz_settings['listing-btn-align'];
		
		$login_reg_btn_location = $pacz_settings['header-login-reg-location'];
		$login_reg_btn_align =  $pacz_settings['log-reg-btn-align'];
		
		$header_contact_details_location = $pacz_settings['header-contact-select'] ;
		$header_contact_details_align = $pacz_settings['header-contact-align'] ;
	}

	$boxed_layout = $pacz_settings['body-layout'];

	$header_style = $trans_header_skin = $header_padding_class = $header_grid_margin = $trans_header_skin_class = $pacz_main_wrapper_class = '';

	if($header_structure == 'margin' && $preset_headers == 12) {
		$pacz_main_wrapper_class = ' add-corner-margin';  
	}else if($header_structure == 'vertical') {
		$header_state = $pacz_settings['vertical-header-state'];
		$pacz_main_wrapper_class = ' vertical-header vertical-' . $header_state . '-state';
	 }
	  
	?>
	<div class="mobile-overlay"></div>
	<div class="theme-main-wrapper <?php echo esc_attr($pacz_main_wrapper_class); ?>">

		<?php if($header_structure == 'margin' && $preset_headers == 12) { ?>
			<div class="pacz-top-corner"></div>
			<div class="pacz-right-corner"></div>
			<div class="pacz-left-corner"></div>
			<div class="pacz-bottom-corner"></div>
		<?php } ?>
	<div id="pacz-boxed-layout" class="pacz-<?php echo esc_attr($boxed_layout); ?>-enabled">
	<?php
		$layout_template = $post_id ? get_post_meta($post_id, '_template', true ) : '';
		if($layout_template == 'no-header-title' || $layout_template == 'no-header-title-footer' || $layout_template == 'no-header-title-only-footer') return;
		
		if($layout_template != 'no-header' && $layout_template !='no-header-footer') :
		
		//$detect_mobile = new Mobile_Detect();
		if(wp_is_mobile()){
			get_template_part( 'includes/templates/mobile-header');
		}else{
			
			if($preset_headers == 1){
				get_template_part( 'includes/templates/desktop-headers/header-1');
			}elseif($preset_headers == 2){
				get_template_part( 'includes/templates/desktop-headers/header-2');
			}elseif($preset_headers == 3){
				get_template_part( 'includes/templates/desktop-headers/header-3');
			}elseif($preset_headers == 4){
				get_template_part( 'includes/templates/desktop-headers/header-toolbar');
				get_template_part( 'includes/templates/desktop-headers/header-4');
			}elseif($preset_headers == 5){
				get_template_part( 'includes/templates/desktop-headers/header-5-toolbar');
				get_template_part( 'includes/templates/desktop-headers/header-5');
			}elseif($preset_headers == 6){
				get_template_part( 'includes/templates/desktop-headers/header-6-toolbar');
				get_template_part( 'includes/templates/desktop-headers/header-6');
			}elseif($preset_headers == 7){
				get_template_part( 'includes/templates/desktop-headers/header-7-toolbar');
				get_template_part( 'includes/templates/desktop-headers/header-7');
			}elseif($preset_headers == 8){
				get_template_part( 'includes/templates/desktop-headers/header-8-toolbar');
				get_template_part( 'includes/templates/desktop-headers/header-8');
			}elseif($preset_headers == 9){
				get_template_part( 'includes/templates/desktop-headers/header-9');
			}elseif($preset_headers == 10){
				get_template_part( 'includes/templates/desktop-headers/header-10');
			}elseif($preset_headers == 11){
				get_template_part( 'includes/templates/desktop-headers/header-custom-toolbar');
				get_template_part( 'includes/templates/desktop-headers/header-11');
			}elseif($preset_headers == 12){
				get_template_part( 'includes/templates/desktop-headers/header-12');
			}elseif($preset_headers == 13){
				get_template_part( 'includes/templates/desktop-headers/header-13');
			}else{
				get_template_part( 'includes/templates/desktop-headers/header-1');
			}
			
			//if($header_toolbar_social_location == 'header_section') {
				//do_action('header_social', 'outside-grid');
			//}
			echo '<div class="desktop-responsive-nav-container responsive-nav-container">';
				echo '<span class="res-menu-close pacz-fic5-close-1"></span>';
				echo '<div class="classiads-logo-wrapper">';
					do_action('header_logo');
				echo '</div>';
			echo '</div>';		
		} ?>
		<?php if($pacz_settings['header-location'] != 'bottom') : ?>
		<div class="sticky-header-padding <?php echo esc_attr($header_padding_class);?>"></div>
		<?php endif; ?>

	<?php endif; ?>
	<?php
		if($post_id && $layout_template != 'no-title') {
		  if($layout_template != 'no-footer-title' && $layout_template != 'no-sub-footer-title' && $layout_template != 'no-title-footer' && $layout_template != 'no-title-sub-footer' && $layout_template != 'no-title-footer-sub-footer') {
			  do_action('page_title');
		  }
		}
	?>

