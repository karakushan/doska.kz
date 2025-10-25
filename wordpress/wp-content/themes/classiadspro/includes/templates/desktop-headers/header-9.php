

<?php
	global $pacz_settings;
	$post_id = global_get_post_id();
	$preset_headers = $pacz_settings['preset_headers'];
	
	/* preset values */
	
	$header_structure = 'standard';
	$header_align = 'left';
	$header_grid = 0;
	$sticky_header = 0;
	$squeeze_sticky_header = 0;
	
	$header_style = $trans_header_skin = $header_padding_class = $header_grid_margin = $trans_header_skin_class = $pacz_main_wrapper_class = '';
	
	$boxed_header = 1;
	
	$toolbar = 0;
	$toolbar_check = get_post_meta( $post_id, '_header_toolbar', true );
	$toolbar_option = !empty($toolbar_check) ? $toolbar_check : 'true';
	$header_toolbar_grid = 0;
	$pacz_logo_location = 'header_section';
	$pacz_logo_align = 'left' ;
	
	$header_toolbar_social_location = 'disabled'; 
	$header_toolbar_social_align = 'right';
	$listing_btn_location = 'header_section';
	$listing_btn_align = 'right';

	$login_reg_btn_location = 'header_section';
	$login_reg_btn_align =  'right';
	
	$header_contact_details_location = 'disabled' ;
	$header_contact_details_align = 'left' ;
	
	/* Header Main Settings  */
	
	if($post_id) {
		global $pacz_settings, $pacz_accent_color;
		$post_id = global_get_post_id();
		$header_search_form_onpage = get_post_meta( $post_id, '_header_search_form', true );
		$header_search_form = $pacz_settings['_header_search_form'];
		$boxed_layout = (get_post_meta( $post_id, '_custom_bg', true ) == 'true') ? get_post_meta( $post_id, 'background_selector_orientation', true ) : $pacz_settings['body-layout'];
		if($preset_headers == 6){
			$header_style = 'transparent';
		}else{
			$header_style = get_post_meta( $post_id, '_header_style', true );
		}
		$trans_header_skin = get_post_meta( $post_id, '_trans_header_skin', true );
		$trans_header_skin_class = ($trans_header_skin != '') ? ($trans_header_skin.'-header-skin') : '';

	} 
	
	$logo_height = (!empty($pacz_settings['logo']['height'])) ? $pacz_settings['logo']['height'] : 50;
		if(isset($squeeze_sticky_header)) {
			$header_sticky_height =	$logo_height/1.2 + ($pacz_settings['header-padding']/2.4 * 2);
		} else {
		}
		$header_height = $logo_height + ($pacz_settings['header-padding'] * 2);

		// Export settings to json 
		$classiadspro_json[] = array(
			'name' => 'theme_header',
			'params' => array(
				'stickyHeight' => $header_sticky_height
			)
		);
	
		if($header_style == 'transparent') {
		  $header_class = 'transparent-header ' . $trans_header_skin_class;
		} else {
		  $header_class = $sticky_header ? 'sticky-header' : '';
		  $header_padding_class = $sticky_header ? 'sticky-header' : '';
		}
		if($header_grid == 'true' && is_page() && $header_structure != 'vertical'){
			$header_grid = $header_grid ? 'pacz-grid' : '';
		}elseif($pacz_settings['header-grid'] && $header_structure != 'vertical'){
			$header_grid = $header_grid ? 'pacz-grid' : '';
		}

		$header_grid_margin ='header_grid_margin';



		$header_class .= ($boxed_header) ? ' boxed-header' : ' full-header';
		$header_class .= ($preset_headers) ? ' header-style-v'.$preset_headers : 'header-style-v12';
		$header_class .= ($header_structure != 'vertical') ? ($header_align) ? ' header-align-'.$header_align : '' : '';
		$header_class .= ($header_structure) ? ' header-structure-'.$header_structure : '';
		$header_class .= ($header_structure == 'standard') ? (' header-hover-style-'.$pacz_settings['header-hover-style']) : '';
		$header_class .= ($header_structure == 'standard') ? (' put-header-'.$pacz_settings['header-location']) : '';
		
		/* Header content */
	echo '<header id="pacz-header" class="'.esc_attr($header_class).' '.esc_attr($header_grid).' '.esc_attr($header_grid_margin).' theme-main-header pacz-header-module" data-header-style="'.esc_attr($header_style).'" data-header-structure="'.esc_attr($header_structure).'" data-transparent-skin="'.esc_attr($trans_header_skin).'" data-height="'.intval($header_height).'" data-sticky-height="'.intval($header_sticky_height).'">';
		if($boxed_header && $header_structure != 'vertical') {
			echo '<div class="pacz-header-mainnavbar"><div class="pacz-grid clearfix">';
		}	
			if(is_user_logged_in() && !empty($pacz_settings['loggedin_menu'])) {
				$menu_location = $pacz_settings['loggedin_menu'];
				do_action( 'vertical_navigation', $menu_location );
				do_action( 'main_navigation', $menu_location );
			}else{
				$pacz_menu_location = 'primary-menu';
				do_action( 'vertical_navigation', 'primary-menu' );
				do_action( 'main_navigation', 'primary-menu' );
			}
		if($pacz_settings['boxed-header'] && $header_structure != 'vertical') {
			echo '</div></div>';
		}
	echo '</header>';
