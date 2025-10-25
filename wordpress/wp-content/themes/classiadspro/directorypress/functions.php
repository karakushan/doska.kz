<?php
include 'updates.php';
include 'styles.php';
// DirectoryPress filters
add_action('directorypress_register_listing_styles', 'pacz_directorypress_register_styles');
add_filter ("directorypress_listing_gridview_styles" , "pacz_directorypress_listing_gridview_styles");
add_filter ("directorypress_mobile_listing_gridview_styles" , "pacz_directorypress_mobile_listing_grid_styles_fuction");
add_filter ("directorypress_listing_gridview_styles_featured_tags" , "pacz_directorypress_listing_gridview_styles_featured_tags");

add_filter ("directorypress_listing_listview_styles" , "pacz_directorypress_listing_listview_styles");

add_filter ("directorypress_single_listing_styles" , "pacz_directorypress_single_listing_styles");
add_filter ("directorypress_archive_page_styles" , "pacz_directorypress_archive_page_styles");
add_filter ("directorypress_sorting_panel_styles" , "pacz_directorypress_listing_sorting_styles");
add_action ("directorypress_listing_before_attachment_metabox" , "pacz_directorypress_listing_before_attachment_metabox");
add_action ("directorypress_pricing_plan_styles" , "pacz_directorypress_pricing_plan_styles");

add_filter ("directorypress_archive_form_layout" , "pacz_directorypress_archive_form_layout");
function pacz_directorypress_archive_form_layout() {
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	if($DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style'] == 2 || $DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style'] == 3){
		return 'vertical';
	}
	return 'horizontal';
}
// call back function 

function pacz_directorypress_register_styles() {
	wp_register_style('pacz_directorypress_common', PACZ_THEME_DIR_URI . '/directorypress/assets/css/common.css');
	wp_register_style('pacz_directorypress_rtl', PACZ_THEME_DIR_URI . '/directorypress/assets/css/common-rtl.css');
	wp_enqueue_style('pacz_directorypress_common');
	// listings
	wp_register_style('directorypress_listing_style_1', PACZ_THEME_DIR_URI . '/directorypress/assets/css/listing/listing-style-1.css');
	wp_register_style('directorypress_listing_style_2', PACZ_THEME_DIR_URI . '/directorypress/assets/css/listing/listing-style-2.css');
	wp_register_style('directorypress_listing_style_3', PACZ_THEME_DIR_URI . '/directorypress/assets/css/listing/listing-style-3.css');
	wp_register_style('directorypress_listing_style_4', PACZ_THEME_DIR_URI . '/directorypress/assets/css/listing/listing-style-4.css');
	wp_register_style('directorypress_listing_style_5', PACZ_THEME_DIR_URI . '/directorypress/assets/css/listing/listing-style-5.css');
	wp_register_style('directorypress_listing_style_6', PACZ_THEME_DIR_URI . '/directorypress/assets/css/listing/listing-style-6.css');
	wp_register_style('directorypress_listing_style_7', PACZ_THEME_DIR_URI . '/directorypress/assets/css/listing/listing-style-7.css');
	wp_register_style('directorypress_listing_style_8', PACZ_THEME_DIR_URI . '/directorypress/assets/css/listing/listing-style-8.css');
	wp_register_style('directorypress_listing_style_9', PACZ_THEME_DIR_URI . '/directorypress/assets/css/listing/listing-style-9.css');
	wp_register_style('directorypress_listing_style_10', PACZ_THEME_DIR_URI . '/directorypress/assets/css/listing/listing-style-10.css');
	wp_register_style('directorypress_listing_style_11', PACZ_THEME_DIR_URI . '/directorypress/assets/css/listing/listing-style-11.css');
	wp_register_style('directorypress_listing_style_12', PACZ_THEME_DIR_URI . '/directorypress/assets/css/listing/listing-style-12.css');
	wp_register_style('directorypress_listing_style_13', PACZ_THEME_DIR_URI . '/directorypress/assets/css/listing/listing-style-13.css');
	wp_register_style('directorypress_listing_style_14', PACZ_THEME_DIR_URI . '/directorypress/assets/css/listing/listing-style-14.css');
	wp_register_style('directorypress_listing_style_15', PACZ_THEME_DIR_URI . '/directorypress/assets/css/listing/listing-style-15.css');
	wp_register_style('directorypress_listing_style_16', PACZ_THEME_DIR_URI . '/directorypress/assets/css/listing/listing-style-16.css');
	wp_register_style('directorypress_listing_style_17', PACZ_THEME_DIR_URI . '/directorypress/assets/css/listing/listing-style-17.css');
	wp_register_style('directorypress_listing_style_18', PACZ_THEME_DIR_URI . '/directorypress/assets/css/listing/listing-style-18.css');
	wp_register_style('directorypress_listing_style_19', PACZ_THEME_DIR_URI . '/directorypress/assets/css/listing/listing-style-19.css');
	wp_register_style('directorypress_listing_style_mobile_1', PACZ_THEME_DIR_URI . '/directorypress/assets/css/listing/listing-style-mobile-1.css');
	
	if(is_rtl()){
		wp_enqueue_style('pacz_directorypress_rtl');
	}
}

//add_filter ("directorypress_listing_gridview_styles" , "pacz_directorypress_listing_gridview_styles");
function pacz_directorypress_listing_gridview_styles($styles){
	$styles = $styles + array(
		'1' => __('style 1 ', 'classiadspro'),	
		'2' => __('style 2 Emo ', 'classiadspro'),							
		'3' => __('style 3 Lemo', 'classiadspro'),						
		'4' => __('style 4 Max', 'classiadspro'),							
		'5' => __('style 5', 'classiadspro'),							
		'6' => __('style 6 Exo', 'classiadspro'),							
		'7' => __('style 7 Exotic', 'classiadspro'),							
		'8' => __('style 8 Snow', 'classiadspro'),							
		'9' => __('style 9 Zee', 'classiadspro'),							
		'10' => __('style 10 Ultra', 'classiadspro'),							
		'11' => __('style 11 Mintox', 'classiadspro'),							
		'12' => __('style 12 Solic', 'classiadspro'),							
		'13' => __('style 13 Zoco', 'classiadspro'),
		'14' => __('style 14 Fantro', 'classiadspro'),
		'15' => __('style 15 Directory', 'classiadspro'),
		'16' => __('style 16 ', 'classiadspro'),
		'17' => __('style 17 ', 'classiadspro'),
		'18' => __('style 18 ', 'classiadspro'),
		'19' => __('style 19 Hub ', 'classiadspro')
	);
	return $styles;
}
function pacz_directorypress_mobile_listing_grid_styles_fuction(){
	$styles = array(
		'mobile_1' => __('style 1 ', 'classiadspro'),
	);
	return $styles;
}
add_filter('directorypress_after_listing_post_style_settings', 'pacz_directorypress_after_listing_post_style_settings', 11);
function pacz_directorypress_after_listing_post_style_settings(){
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	return array(
		'type' => 'select',
		'id' => 'listing_post_style_mobile',
		'title' => __('Grid Style (Mobile)', 'classiadspro'),
		'options' => apply_filters("directorypress_mobile_listing_gridview_styles" , "pacz_directorypress_mobile_listing_grid_styles_fuction"),
		'default' => 'default',
	);
}
add_action('directorypress_el_listings_after_post_style_settings', 'pacz_directorypress_el_listings_after_post_style_settings', 1);
function pacz_directorypress_el_listings_after_post_style_settings($element){
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	$element->add_control(
			'listing_post_style_mobile',
			[
				'label' => __( 'Grid View Style (Mobile)', 'classiadspro' ),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT,
				'multiple' => false,
				'options' => apply_filters("directorypress_mobile_listing_gridview_styles" , "pacz_directorypress_mobile_listing_grid_styles_fuction"),
				'default' => '',
			]
	);
}
add_filter ("directorypress_listing_el_custom_settings" , "pacz_directorypress_listing_el_custom_settings", 1);
function pacz_directorypress_listing_el_custom_settings($element){
	$settings = $element->get_settings_for_display();
	$custom_settings = array(
		'listing_post_style_mobile' => $settings['listing_post_style_mobile'],
	);
	
	return $custom_settings;
}

add_filter ("directorypress_listing_shortcode_grid_style" , "pacz_directorypress_listing_shortcode_grid_style_fuction", 10, 2);
function pacz_directorypress_listing_shortcode_grid_style_fuction($style, $custom_settings){
	
	if(wp_is_mobile() &&(!strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')) && ($custom_settings && isset($custom_settings['listing_post_style_mobile']) && $custom_settings['listing_post_style_mobile'] != '')){
		$style = $custom_settings['listing_post_style_mobile'];
	}
	
	return $style;
}

add_filter ("directorypress_archive_page_grid_style" , "pacz_directorypress_archive_page_listing_grid_style_fuction", 10, 2);

function pacz_directorypress_archive_page_listing_grid_style_fuction($style){
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	
	if(wp_is_mobile() &&(!strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')) && (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_post_style_mobile']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_post_style_mobile']))){
		
		$style = $DIRECTORYPRESS_ADIMN_SETTINGS['listing_post_style_mobile'];
	}
	
	return $style;
}
function pacz_directorypress_listing_listview_styles($styles){
	$styles = $styles + array(
		'listview_ultra' => __('Ultra', 'classiadspro'),
		'listview_mod' => __('Modern', 'classiadspro'),
	);
	return $styles;
}

function pacz_directorypress_listing_gridview_styles_featured_tags($styles){
	$styles = $styles + array(
		'1' => __('style 1', 'classiadspro'),
		'2' => __('style 2 Emo ', 'classiadspro'),							
		'3' => __('style 3 Lemo', 'classiadspro'),						
		'4' => __('style 4 Max', 'classiadspro'),							
		'5' => __('style 5 default', 'classiadspro'),							
		'6' => __('style 6 Exo', 'classiadspro'),							
		'7' => __('style 7 Exotic', 'classiadspro'),							
		'8' => __('style 8 Snow', 'classiadspro'),							
		'9' => __('style 9 Zee', 'classiadspro'),							
		'10' => __('style 10 Ultra', 'classiadspro'),							
		'11' => __('style 11 Mintox', 'classiadspro'),							
		'12' => __('style 12 Solic', 'classiadspro'),							
		'13' => __('style 13 Zoco', 'classiadspro'),
		'14' => __('style 14 Fantro', 'classiadspro'),
		'15' => __('style 15 Directory', 'classiadspro'),
		'16' => __('style 16 ', 'classiadspro'),
		'17' => __('style 17 ', 'classiadspro')
	);
	return $styles;
}

function pacz_directorypress_single_listing_styles($styles){
	$styles = $styles + array(
		'2' => __('style 2 Radius ', 'classiadspro'),
		'3' => __('style 3 Directory ', 'classiadspro')
	);
	return $styles;
}

function pacz_directorypress_archive_page_styles($styles){
	$styles = $styles + array(
		'3' => __('Sticky Map ', 'classiadspro')
	);
	return $styles;
}

function pacz_directorypress_listing_sorting_styles($styles){
	$styles = $styles + array(
		'2' => __('style 2 Fantro ', 'classiadspro'),
		'3' => __('style 3 Zoco ', 'classiadspro'),
		'4' => __('style 4 ', 'classiadspro')
	);
	return $styles;
}
function pacz_directorypress_pricing_plan_styles($styles){
	$styles = $styles + array(
		'pplan-style-2' => __('Style 2', 'classiadspro'),
		'pplan-style-3' => __('style 3', 'classiadspro'),
		'pplan-style-4' => __('style 4 Zoco', 'classiadspro'),
		'style-5' => __('style 5', 'classiadspro'),
	);
	return $styles;
}

function pacz_directorypress_listing_before_attachment_metabox(){
	$listing = directorypress_pull_current_listing_admin();
	directorypress_display_template('partials/listing/company-logo-metabox.php', array('listing' => $listing));
	directorypress_display_template('partials/listing/company-cover-metabox.php', array('listing' => $listing));
}

add_filter ("directorypress_category_styles" , "pacz_directorypress_category_styles");
function pacz_directorypress_category_styles($styles){
	$styles = $styles + array(
		'1' => __('Style 1', 'classiadspro'),
		'2' => __('Style 2 Echo', 'classiadspro'),
		'3' => __('Style 3 Zee', 'classiadspro'),
		'4' => __('Style 4 Wox', 'classiadspro'),
		'5' => __('Style 5 Ultra', 'classiadspro'),
		'6' => __('Style 6 Mintox', 'classiadspro'),
		'7' => __('Style 7 Zoco', 'classiadspro'),
		'8' => __('Style 8 Fantro (List)', 'classiadspro'),
		'9' => __('Style 9 ', 'classiadspro'),
		'10' => __('Style 10 ', 'classiadspro'),
		'11' => __('Style 11 ', 'classiadspro'),
		'12' => __('Style 12 ', 'classiadspro'),		
	);
	return $styles;
}
add_filter ("directorypress_category_styles_has_depth" , "pacz_directorypress_category_styles_has_depth");
function pacz_directorypress_category_styles_has_depth($styles){
	$styles = $styles + array('2','3','5','6','7','8','10','11');
	return $styles;
}
add_filter ("directorypress_location_styles" , "pacz_directorypress_location_styles");
function pacz_directorypress_location_styles($styles){
	$styles = $styles + array(
		'2' => __('Style 2 Echo', 'classiadspro'),
		'3' => __('Style 3 Zee', 'classiadspro'),						
	);
	return $styles;
}
add_action('directorypress_sorting_panel_buttons_after', 'pacz_directorypress_map_toggle_function');
function pacz_directorypress_map_toggle_function(){
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	$mapview_toggle_class = isset($_COOKIE['directorypress_mapview'])? $_COOKIE['directorypress_mapview']:'';
	if($DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style'] == 3){
		echo '<a href="#" class="directorypress-map_toggle_button '.$mapview_toggle_class.'"><span class="fas fa-map-marker" aria-hidden="true"></span></a>';
	}
}
add_action('directorypress_sorting_panel_before', 'pacz_directorypress_search_toggle_function');
function pacz_directorypress_search_toggle_function(){
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	if($DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style'] == 3){
		echo '<a href="#" class="directorypress_search_toggle_button"><span class="fas fa-sliders-h" aria-hidden="true"></span></a>';
	}
}



function pacz_directorypress_package_duration($package) {
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	//$price = apply_filters('directorypress_submitlisting_package_price', null, $package);
	$spliter = esc_html__('For ', 'classiadspro');
	$for = '';
	$price = '';
	//if (!is_null($price)) {
		if (!$package->package_no_expiry) {
			if ($package->package_duration_unit == 'day' && $package->package_duration == 1)
				$price .= '<span class="pacz-directorypress-price-period">'.$spliter . __('One Day', 'classiadspro') . '</span>';
			elseif ($package->package_duration_unit == 'day' && $package->package_duration > 1)
				$price .= '<span class="pacz-directorypress-price-period">'.$spliter . $for.' '.  $package->package_duration . ' ' . _n('day', 'days', $package->package_duration, 'classiadspro') . '</span>';
			elseif ($package->package_duration_unit == 'week' && $package->package_duration == 1)
				$price .= '<span class="pacz-directorypress-price-period">'.$spliter . __('One week', 'classiadspro');
			elseif ($package->package_duration_unit == 'week' && $package->package_duration > 1)
				$price .= '<span class="pacz-directorypress-price-period">'.$spliter . $for.' '. $package->package_duration . ' ' . _n('week', 'weeks', $package->package_duration, 'classiadspro') . '</span>';
			elseif ($package->package_duration_unit == 'month' && $package->package_duration == 1)
				$price .= '<span class="pacz-directorypress-price-period">'.$spliter . __('One month', 'classiadspro') . '</span>';
			elseif ($package->package_duration_unit == 'month' && $package->package_duration > 1)
				$price .= '<span class="pacz-directorypress-price-period">'.$spliter . $for.' '. $package->package_duration . ' ' . _n('month', 'months', $package->package_duration, 'classiadspro') . '</span>';
			elseif ($package->package_duration_unit == 'year' && $package->package_duration == 1)
				$price .= '<span class="pacz-directorypress-price-period">'.$spliter . __('One Year', 'classiadspro') . '</span>';
			elseif ($package->package_duration_unit == 'year' && $package->package_duration > 1)
				$price .= '<span class="pacz-directorypress-price-period">'.$spliter . $for.' '. $package->package_duration . ' ' . _n('year', 'years', $package->package_duration, 'classiadspro') . '</span>';
		}
		return $price;
	//}
}


add_action('pacz_single_listing_footer_panel_html', 'pacz_single_listing_footer_panel_html');

function pacz_single_listing_footer_panel_html(){
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	if(directorypress_is_listing_page() && wp_is_mobile()){
		$authorID = $GLOBALS['authorID2'];
		$listing = $GLOBALS['listing_id'];
		$listing_url = get_permalink($listing->post->ID);
		$phone_number = get_the_author_meta('user_phone', $authorID);
		$whatsapp_number = get_the_author_meta('user_whatsapp_number', $authorID);
		$email_id = get_the_author_meta('user_email', $authorID);
		if($phone_number || $whatsapp_number || $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_bidding'] || $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_contact']){
			echo '<div class="mobile-author-btn-panel-container">';
				echo '<div class="mobile-author-btn-panel">';
					echo '<ul>';
						if($phone_number && !empty($phone_number)){
							echo '<li class="mobile-author-phone"><a href="tel:'.esc_attr($phone_number) .'"><i class="dicode-material-icons dicode-material-icons-phone"></i>'. esc_html__('Call', 'classiadspro').'</a></li>';	
						}
						if($whatsapp_number && !empty($whatsapp_number)){
							echo '<li class="mobile-author-whatsapp"><a href="https://wa.me/'. esc_attr($whatsapp_number) .'?text='.sprintf(esc_html__('Sent From %s', 'classiadspro'), esc_url($listing_url)).'"><i></i></a></li>';
						}
						if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_contact']){
							echo '<li class="mobile-author-msg"><a data-popup-open="single_contact_form" href="#"><i class="dicode-material-icons dicode-material-icons-email-outline"></i>'.esc_html__('Msg', 'classiadspro').'</a></li>'; 	
						}
						if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_bidding']){
							echo '<li class="mobile-author-offer"><a data-popup-open="single_contact_form_bid" href="#"><i class="dicode-material-icons dicode-material-icons-comment-text-outline"></i>'. esc_html__('Offer', 'classiadspro').'</a></li>'; 	
						}
					echo '</ul>';
				echo '</div>';
			echo '</div>';
		}
	}
}

add_action('wp_footer', 'pacz_single_listing_footer_action_panel');

function pacz_single_listing_footer_action_panel(){
	do_action('pacz_single_listing_footer_panel_html');
}

add_filter( 'template_include', 'pacz_directorypress_core_load_template', 99 );
function pacz_directorypress_core_load_template( $template ) {
    global $post, $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object;
	$host = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	if($post){
		if ( directorypress_is_listing_page() && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_single_listing_style'] == 3 && ($directorypress_object->action != 'printlisting' && $directorypress_object->action != 'pdflisting')) {
			$new_template = directorypress_display_template('partials/templates/template-single-full.php');
			return $new_template;
		}elseif(directorypress_is_archive_page()){
			if($DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style'] == 3 || $DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style'] == 4){
				$new_template = directorypress_display_template('partials/templates/template-archive-full.php');
				return $new_template;
			}
		}
	}
    return $template;

}
