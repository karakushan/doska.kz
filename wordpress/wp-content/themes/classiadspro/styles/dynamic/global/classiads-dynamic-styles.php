<?php
global $pacz_settings;


/*
 *
 * Contains all the dynamic css rules generated based on theme settings.
 *
 */

//function pacz_dynamic_css() {

	//wp_enqueue_style('pacz-style', get_stylesheet_uri(), false, false, 'all');

	

	$output = $typekit_fonts_1 = $attach = $custom_breadcrumb_page = $custom_breadcrumb_hover_color = $custom_breadcrumb_color = '';

/* Get skin color from global $_GET for skin switcher panel */
	if (isset($_GET['skin'])) {
		$accent_color = '#' . $_GET['skin'];
		$btn_hover = '#' . $_GET['btn-hover'];
		$pacz_settings['footer-link-color']['hover'] = '#' . $_GET['skin'];
		$pacz_settings['dashboard-link-color']['hover'] = '#' . $_GET['skin'];
		$pacz_settings['sidebar-link-color']['hover'] = '#' . $_GET['skin'];
		$pacz_settings['link-color']['hover'] = '#' . $_GET['skin'];
		$pacz_settings['footer-social-color']['hover'] = '#' . $_GET['skin'];
		$pacz_settings['main-nav-top-color']['hover'] = '#' . $_GET['skin'];
		$pacz_settings['main-nav-sub-color']['bg-hover'] = '#' . $_GET['skin'];
		$pacz_settings['main-nav-sub-color']['bg-active'] = '#' . $_GET['skin'];

	} else {
		$accent_color = $pacz_settings['accent-color'];
		$btn_hover = $pacz_settings['btn-hover'];
	}
	$secondary_color = $pacz_settings['secondary-color'];
	$third_color = (isset($pacz_settings['third-color']) && !empty($pacz_settings['third-color']))? $pacz_settings['third-color'] : $pacz_settings['btn-hover'];
	
	$primary_rgba_color_5 = pacz_convert_rgba($accent_color, 0.05);
	$primary_rgba_color_10 = pacz_convert_rgba($accent_color, 0.10);
	$primary_rgba_color_15 = pacz_convert_rgba($accent_color, 0.15);
	$primary_rgba_color_20 = pacz_convert_rgba($accent_color, 0.20);
	$primary_rgba_color_25 = pacz_convert_rgba($accent_color, 0.25);
	$primary_rgba_color_30 = pacz_convert_rgba($accent_color, 0.30);
	$primary_rgba_color_35 = pacz_convert_rgba($accent_color, 0.35);
	$primary_rgba_color_40 = pacz_convert_rgba($accent_color, 0.40);
	$primary_rgba_color_45 = pacz_convert_rgba($accent_color, 0.45);
	$primary_rgba_color_50 = pacz_convert_rgba($accent_color, 0.50);
	$primary_rgba_color_55 = pacz_convert_rgba($accent_color, 0.55);
	$primary_rgba_color_60 = pacz_convert_rgba($accent_color, 0.60);
	$primary_rgba_color_65 = pacz_convert_rgba($accent_color, 0.65);
	$primary_rgba_color_70 = pacz_convert_rgba($accent_color, 0.70);
	$primary_rgba_color_75 = pacz_convert_rgba($accent_color, 0.75);
	$primary_rgba_color_80 = pacz_convert_rgba($accent_color, 0.80);
	$primary_rgba_color_85 = pacz_convert_rgba($accent_color, 0.85);
	$primary_rgba_color_90 = pacz_convert_rgba($accent_color, 0.90);
	$primary_rgba_color_95 = pacz_convert_rgba($accent_color, 0.95);
	
	$secondary_rgba_color_10 = pacz_convert_rgba($secondary_color, 0.10);
	$secondary_rgba_color_15 = pacz_convert_rgba($secondary_color, 0.15);
	$secondary_rgba_color_20 = pacz_convert_rgba($secondary_color, 0.20);
	$secondary_rgba_color_25 = pacz_convert_rgba($secondary_color, 0.25);
	$secondary_rgba_color_30 = pacz_convert_rgba($secondary_color, 0.30);
	$secondary_rgba_color_35 = pacz_convert_rgba($secondary_color, 0.35);
	$secondary_rgba_color_40 = pacz_convert_rgba($secondary_color, 0.40);
	$secondary_rgba_color_45 = pacz_convert_rgba($secondary_color, 0.45);
	$secondary_rgba_color_50 = pacz_convert_rgba($secondary_color, 0.50);
	$secondary_rgba_color_55 = pacz_convert_rgba($secondary_color, 0.55);
	$secondary_rgba_color_60 = pacz_convert_rgba($secondary_color, 0.60);
	$secondary_rgba_color_65 = pacz_convert_rgba($secondary_color, 0.65);
	$secondary_rgba_color_70 = pacz_convert_rgba($secondary_color, 0.70);
	$secondary_rgba_color_75 = pacz_convert_rgba($secondary_color, 0.75);
	$secondary_rgba_color_80 = pacz_convert_rgba($secondary_color, 0.80);
	$secondary_rgba_color_85 = pacz_convert_rgba($secondary_color, 0.85);
	$secondary_rgba_color_90 = pacz_convert_rgba($secondary_color, 0.90);
	$secondary_rgba_color_95 = pacz_convert_rgba($secondary_color, 0.95);
	
	$third_rgba_color_10 = pacz_convert_rgba($third_color, 0.10);
	$third_rgba_color_15 = pacz_convert_rgba($third_color, 0.15);
	$third_rgba_color_20 = pacz_convert_rgba($third_color, 0.20);
	$third_rgba_color_25 = pacz_convert_rgba($third_color, 0.25);
	$third_rgba_color_30 = pacz_convert_rgba($third_color, 0.30);
	$third_rgba_color_35 = pacz_convert_rgba($third_color, 0.35);
	$third_rgba_color_40 = pacz_convert_rgba($third_color, 0.40);
	$third_rgba_color_45 = pacz_convert_rgba($third_color, 0.45);
	$third_rgba_color_50 = pacz_convert_rgba($third_color, 0.50);
	$third_rgba_color_55 = pacz_convert_rgba($third_color, 0.55);
	$third_rgba_color_60 = pacz_convert_rgba($third_color, 0.60);
	$third_rgba_color_65 = pacz_convert_rgba($third_color, 0.65);
	$third_rgba_color_70 = pacz_convert_rgba($third_color, 0.70);
	$third_rgba_color_75 = pacz_convert_rgba($third_color, 0.75);
	$third_rgba_color_80 = pacz_convert_rgba($third_color, 0.80);
	$third_rgba_color_85 = pacz_convert_rgba($third_color, 0.85);
	$third_rgba_color_90 = pacz_convert_rgba($third_color, 0.90);
	$third_rgba_color_95 = pacz_convert_rgba($third_color, 0.95);
	
	$footer_link_regular_color = $pacz_settings['footer-link-color']['regular'];
	$footer_link_hover_color = $pacz_settings['footer-link-color']['hover'];
	$footer_link_hover_rgba_color_10 = pacz_convert_rgba($footer_link_hover_color, 0.10);
	$footer_link_hover_rgba_color_15 = pacz_convert_rgba($footer_link_hover_color, 0.15);
	$footer_link_hover_rgba_color_20 = pacz_convert_rgba($footer_link_hover_color, 0.20);
	$footer_link_hover_rgba_color_25 = pacz_convert_rgba($footer_link_hover_color, 0.25);
	$footer_link_hover_rgba_color_30 = pacz_convert_rgba($footer_link_hover_color, 0.30);
	$footer_link_hover_rgba_color_35 = pacz_convert_rgba($footer_link_hover_color, 0.35);
	$footer_link_hover_rgba_color_40 = pacz_convert_rgba($footer_link_hover_color, 0.40);
	$footer_link_hover_rgba_color_45 = pacz_convert_rgba($footer_link_hover_color, 0.45);
	$footer_link_hover_rgba_color_50 = pacz_convert_rgba($footer_link_hover_color, 0.50);
	$footer_link_hover_rgba_color_55 = pacz_convert_rgba($footer_link_hover_color, 0.55);
	$footer_link_hover_rgba_color_60 = pacz_convert_rgba($footer_link_hover_color, 0.60);
	$footer_link_hover_rgba_color_65 = pacz_convert_rgba($footer_link_hover_color, 0.65);
	$footer_link_hover_rgba_color_70 = pacz_convert_rgba($footer_link_hover_color, 0.70);
	$footer_link_hover_rgba_color_75 = pacz_convert_rgba($footer_link_hover_color, 0.75);
	$footer_link_hover_rgba_color_80 = pacz_convert_rgba($footer_link_hover_color, 0.80);
	$footer_link_hover_rgba_color_85 = pacz_convert_rgba($footer_link_hover_color, 0.85);
	$footer_link_hover_rgba_color_90 = pacz_convert_rgba($footer_link_hover_color, 0.90);
	$footer_link_hover_rgba_color_95 = pacz_convert_rgba($footer_link_hover_color, 0.95);

/**
 * Typekit fonts
 * */

	$typekit_id = isset($pacz_settings['typekit-id']) ? $pacz_settings['typekit-id'] : '';
	$typekit_elements_list_1 = isset($pacz_settings['typekit-element-names']) ? $pacz_settings['typekit-element-names'] : '';
	$typekit_font_family_1 = isset($pacz_settings['typekit-font-family']) ? $pacz_settings['typekit-font-family'] : '';

	if ($typekit_id != '' && $typekit_elements_list_1 != '' && $typekit_font_family_1 != '') {
		if (is_array($typekit_elements_list_1)) {
			$typekit_elements_list_1 = implode(', ', $typekit_elements_list_1);
		} else {
			$typekit_elements_list_1 = $typekit_elements_list_1;
		}
		$typekit_fonts_1 = $typekit_elements_list_1 . ' {font-family: "' . $typekit_font_family_1 . '"}';

	}

###########################################
# Structure
###########################################

// Sidebar Width deducted from content width percentage
global $post;
$pages_padding_top = (isset($pacz_settings['pages-padding']) && !empty($pacz_settings['pages-padding'][1]))? ('padding-top: '. $pacz_settings['pages-padding'][1] .'px;') : '';
$pages_padding_bottom = (isset($pacz_settings['pages-padding']) && !empty($pacz_settings['pages-padding'][2]))? ('padding-bottom: '. $pacz_settings['pages-padding'][2] .'px;') : '';	
$archive_padding_top = (isset($pacz_settings['archive-pages-padding']) && !empty($pacz_settings['archive-pages-padding'][1]))? ('padding-top: '. $pacz_settings['archive-pages-padding'][1] .'px;') : '';
$archive_padding_bottom = (isset($pacz_settings['archive-pages-padding']) && !empty($pacz_settings['archive-pages-padding'][2]))? ('padding-bottom: '. $pacz_settings['archive-pages-padding'][2] .'px;') : '';	

$singular_padding_top = (isset($pacz_settings['single-pages-padding']) && !empty($pacz_settings['single-pages-padding'][1]))? ('padding-top: '. $pacz_settings['single-pages-padding'][1] .'px;') : '';
$singular_padding_bottom = (isset($pacz_settings['single-pages-padding']) && !empty($pacz_settings['single-pages-padding'][2]))? ('padding-bottom: '. $pacz_settings['single-pages-padding'][2] .'px;') : '';	

if(is_page() && !has_shortcode($post->post_content, 'vc_row')){
	$output .= "
	.theme-content {padding:70px 0;}
	";
}
	$sidebar_width = 100 - $pacz_settings['content-width'];

	$boxed_layout_width = $pacz_settings['grid-width']+60;
	
Pacz_Static_Files::addGlobalStyle("
.pacz-grid,
.pacz-inner-grid
{
	max-width: {$pacz_settings['grid-width']}px;
}

.theme-page-wrapper.right-layout .theme-content, .theme-page-wrapper.left-layout .theme-content
{
	width: {$pacz_settings['content-width']}%;
}

.theme-page-wrapper #pacz-sidebar.pacz-builtin
{
	width: {$sidebar_width}%;
}
	body.single .theme-content,
	body.single #pacz-sidebar,
	body.single #theme-page .theme-page-wrapper #pacz-sidebar{
		{$singular_padding_top}
		{$singular_padding_bottom}
	}
	body.page .theme-content:not(.no-padding),
	body.page #pacz-sidebar,
	body.page #theme-page .theme-page-wrapper #pacz-sidebar{
		{$pages_padding_top}
		{$pages_padding_bottom}
	}
	body.archive .theme-content,
	body.archive #pacz-sidebar,
	body.archive #theme-page .theme-page-wrapper #pacz-sidebar{
		{$archive_padding_top}
		{$archive_padding_bottom}
	}


.pacz-boxed-enabled,
.pacz-boxed-enabled #pacz-header.sticky-header,
.pacz-boxed-enabled #pacz-header.transparent-header-sticky,
.pacz-boxed-enabled .pacz-secondary-header
{
	max-width: {$boxed_layout_width}px;

}
#pacz-header.postion-absolute{
	position:absolute;
}
@media handheld, only screen and (max-width: {$pacz_settings['grid-width']}px)
{

#sub-footer .item-holder
{
	margin:0 20px;
}

}

");

###########################################
# Backgrounds
###########################################

/**
 * Body background
 */
	$body_bg = 	$pacz_settings['body-bg']['background-color'] ? 'background-color:' . $pacz_settings['body-bg']['background-color'] . ';' : '';
	$body_bg .= $pacz_settings['body-bg']['background-image'] ? 'background-image:url(' . $pacz_settings['body-bg']['background-image'] . ');' : ' ';
	$body_bg .= $pacz_settings['body-bg']['background-position'] ? 'background-position:' . $pacz_settings['body-bg']['background-position'] . ';' : '';
	$body_bg .= $pacz_settings['body-bg']['background-attachment'] ? 'background-attachment:' . $pacz_settings['body-bg']['background-attachment'] . ';' : '';
	$body_bg .= $pacz_settings['body-bg']['background-repeat'] ? 'background-repeat:' . $pacz_settings['body-bg']['background-repeat'] . ';' : '';
	$body_bg .= $pacz_settings['body-bg']['background-size'] ? 'background-size:'. $pacz_settings['body-bg']['background-size'].';' : '';


/**
 * Page Title background
 */
	$page_title_bg = $pacz_settings['page-title-bg']['background-color'] ? 'background-color:' . $pacz_settings['page-title-bg']['background-color'] . ';' : '';
	$page_title_bg .= $pacz_settings['page-title-bg']['background-image'] ? 'background-image:url(' . $pacz_settings['page-title-bg']['background-image'] . ');' : ' ';
	$page_title_bg .= $pacz_settings['page-title-bg']['background-position'] ? 'background-position:' . $pacz_settings['page-title-bg']['background-position'] . ';' : '';
	$page_title_bg .= $pacz_settings['page-title-bg']['background-attachment'] ? 'background-attachment:' . $pacz_settings['page-title-bg']['background-attachment'] . ';' : '';
	$page_title_bg .= $pacz_settings['page-title-bg']['background-repeat'] ? 'background-repeat:' . $pacz_settings['page-title-bg']['background-repeat'] . ';' : '';
	$page_title_bg .= $pacz_settings['page-title-bg']['background-size'] ? 'background-size:'. $pacz_settings['page-title-bg']['background-size'].';' : '';
	//$page_title_bg .= $pacz_settings['page-title-bg']['border'] ? 'border-bottom:1px solid ' . $pacz_settings['page-title-bg']['border'] . ';' : '';

/**
 * Page background
 */
	$page_bg = $pacz_settings['page-bg']['background-color'] ? 'background-color:' . $pacz_settings['page-bg']['background-color'] . ';' : '';
	$page_bg .= $pacz_settings['page-bg']['background-image'] ? 'background-image:url(' . $pacz_settings['page-bg']['background-image'] . ');' : ' ';
	$page_bg .= $pacz_settings['page-bg']['background-position'] ? 'background-position:' . $pacz_settings['page-bg']['background-position'] . ';' : '';
	$page_bg .= $pacz_settings['page-bg']['background-attachment'] ? 'background-attachment:' . $pacz_settings['page-bg']['background-attachment'] . ';' : '';
	$page_bg .= $pacz_settings['page-bg']['background-repeat'] ? 'background-repeat:' . $pacz_settings['page-bg']['background-repeat'] . ';' : '';
	$page_bg .= $pacz_settings['page-bg']['background-size'] ? 'background-size:'. $pacz_settings['page-bg']['background-size'].';' : '';

/**
 * Footer background
 */
	$footer_bg = $pacz_settings['footer-bg']['background-color'] ? 'background-color:' . $pacz_settings['footer-bg']['background-color'] . ';' : '';
	$footer_bg .= $pacz_settings['footer-bg']['background-image'] ? 'background-image:url(' . $pacz_settings['footer-bg']['background-image'] . ');' : ' ';
	$footer_bg .= $pacz_settings['footer-bg']['background-position'] ? 'background-position:' . $pacz_settings['footer-bg']['background-position'] . ';' : '';
	$footer_bg .= $pacz_settings['footer-bg']['background-attachment'] ? 'background-attachment:' . $pacz_settings['footer-bg']['background-attachment'] . ';' : '';
	$footer_bg .= $pacz_settings['footer-bg']['background-repeat'] ? 'background-repeat:' . $pacz_settings['footer-bg']['background-repeat'] . ';' : '';
	$footer_bg .= $pacz_settings['footer-bg']['background-size'] ? 'background-size:'. $pacz_settings['footer-bg']['background-size'].';' : '';

	$page_title_color = $pacz_settings['page-title-color'];
	$page_title_size = $pacz_settings['page-title-size'];
	$page_title_padding = 200;
	$page_title_weight = '';
	$page_title_letter_spacing = '';

	$post_id = global_get_post_id();
	$enable = get_post_meta($post_id, '_custom_bg', true);
	if (global_get_post_id()) {


		$post_id = global_get_post_id();

		$intro = get_post_meta($post_id, '_page_title_intro', true);

		
		if($intro != 'none') {
			$attach = 'background-attachment: scroll;';
		}

		

		if ($enable == 'true') {
			$body_bg = get_post_meta($post_id, 'body_color', true) ? 'background-color: ' . get_post_meta($post_id, 'body_color', true) . ';' : '';
			$body_bg .= get_post_meta($post_id, 'body_image', true) ? 'background-image:url(' . get_post_meta($post_id, 'body_image', true) . ');' : '';
			$body_bg .= get_post_meta($post_id, 'body_repeat', true) ? 'background-repeat:' . get_post_meta($post_id, 'body_repeat', true) . ';' : '';
			$body_bg .= get_post_meta($post_id, 'body_position', true) ? 'background-position:' . get_post_meta($post_id, 'body_position', true) . ';' : '';
			$body_bg .= get_post_meta($post_id, 'body_attachment', true) ? 'background-attachment:' . get_post_meta($post_id, 'body_attachment', true) . ';' : '';
			$body_bg .= (get_post_meta($post_id, 'body_cover', true) == 'true') ? 'background-size: cover;background-repeat: no-repeat;-moz-background-size: cover;-webkit-background-size: cover;-o-background-size: cover;' : '';

			$header_bg = get_post_meta($post_id, 'header_color', true) ? 'background-color: ' . get_post_meta($post_id, 'header_color', true) . ';' : '';
			$header_bg_color = get_post_meta($post_id, 'header_color', true) ? 'background-color: ' . get_post_meta($post_id, 'header_color', true) . ';' : '';
			$header_bg .= get_post_meta($post_id, 'header_image', true) ? 'background-image:url(' . get_post_meta($post_id, 'header_image', true) . ');' : '';
			$header_bg .= get_post_meta($post_id, 'header_repeat', true) ? 'background-repeat:' . get_post_meta($post_id, 'header_repeat', true) . ';' : '';
			$header_bg .= get_post_meta($post_id, 'header_position', true) ? 'background-position:' . get_post_meta($post_id, 'header_position', true) . ';' : '';
			$header_bg .= get_post_meta($post_id, 'header_attachment', true) ? 'background-attachment:' . get_post_meta($post_id, 'header_attachment', true) . ';' : '';
			$header_bg .= (get_post_meta($post_id, 'header_cover', true) == 'true') ? 'background-size: cover;background-repeat: no-repeat;-moz-background-size: cover;-webkit-background-size: cover;-o-background-size: cover;' : '';

			$page_title_bg = get_post_meta($post_id, 'banner_color', true) ? 'background-color: ' . get_post_meta($post_id, 'banner_color', true) . ';' : '';
			$page_title_bg .= get_post_meta($post_id, 'banner_image', true) ? 'background-image:url(' . get_post_meta($post_id, 'banner_image', true) . ');' : '';
			$page_title_bg .= get_post_meta($post_id, 'banner_repeat', true) ? 'background-repeat:' . get_post_meta($post_id, 'banner_repeat', true) . ';' : '';
			$page_title_bg .= get_post_meta($post_id, 'banner_position', true) ? 'background-position:' . get_post_meta($post_id, 'banner_position', true) . ';' : '';
			$page_title_bg .= get_post_meta($post_id, 'banner_attachment', true) ? 'background-attachment:' . get_post_meta($post_id, 'banner_attachment', true) . ';' : '';
			$page_title_bg .= (get_post_meta($post_id, 'banner_cover', true) == 'true') ? 'background-size: cover;background-repeat: no-repeat;-moz-background-size: cover;-webkit-background-size: cover;-o-background-size: cover;' : '';

			$page_bg = get_post_meta($post_id, 'page_color', true) ? 'background-color: ' . get_post_meta($post_id, 'page_color', true) . ' !important;' : '';
			$page_bg .= get_post_meta($post_id, 'page_image', true) ? 'background-image:url(' . get_post_meta($post_id, 'page_image', true) . ') !important;' : '';
			$page_bg .= get_post_meta($post_id, 'page_repeat', true) ? 'background-repeat:' . get_post_meta($post_id, 'page_repeat', true) . ' !important;' : '';
			$page_bg .= get_post_meta($post_id, 'page_position', true) ? 'background-position:' . get_post_meta($post_id, 'page_position', true) . ' !important;' : '';
			$page_bg .= get_post_meta($post_id, 'page_attachment', true) ? 'background-attachment:' . get_post_meta($post_id, 'page_attachment', true) . ' !important;' : '';
			$page_bg .= (get_post_meta($post_id, 'page_cover', true) == 'true') ? 'background-size: cover;background-repeat: no-repeat;-moz-background-size: cover;-webkit-background-size: cover;-o-background-size: cover;' : '';

			$footer_bg = get_post_meta($post_id, 'footer_color', true) ? 'background-color: ' . get_post_meta($post_id, 'footer_color', true) . ';' : '';
			$footer_bg .= get_post_meta($post_id, 'footer_image', true) ? 'background-image:url(' . get_post_meta($post_id, 'footer_image', true) . ');' : '';
			$footer_bg .= get_post_meta($post_id, 'footer_repeat', true) ? 'background-repeat:' . get_post_meta($post_id, 'footer_repeat', true) . ';' : '';
			$footer_bg .= get_post_meta($post_id, 'footer_position', true) ? 'background-position:' . get_post_meta($post_id, 'footer_position', true) . ';' : '';
			$footer_bg .= get_post_meta($post_id, 'footer_attachment', true) ? 'background-attachment:' . get_post_meta($post_id, 'footer_attachment', true) . ';' : '';
			$footer_bg .= (get_post_meta($post_id, 'footer_cover', true) == 'true') ? 'background-size: cover;background-repeat: no-repeat;-moz-background-size: cover;-webkit-background-size: cover;-o-background-size: cover;' : '';

			$page_title_color = get_post_meta($post_id, '_page_title_color', true) ? get_post_meta($post_id, '_page_title_color', true) : $pacz_settings['page-title-color'];
			$page_title_weight = get_post_meta($post_id, '_page_title_weight', true) ? ('font-weight:' . get_post_meta($post_id, '_page_title_weight', true)) : '';
			$page_title_letter_spacing = get_post_meta($post_id, '_page_title_letter_spacing', true) ? ('letter-spacing:' . get_post_meta($post_id, '_page_title_letter_spacing', true) . 'px;') : '';

			$page_title_size = get_post_meta($post_id, '_page_title_size', true) ? get_post_meta($post_id, '_page_title_size', true) : $pacz_settings['page-title-size'];
			$page_title_padding = get_post_meta($post_id, '_page_title_padding', true) ? get_post_meta($post_id, '_page_title_padding', true) : 40;
			
			$header_grid_margin = get_post_meta($post_id, 'header-grid-margin-top', true) ? get_post_meta($post_id, 'header-grid-margin-top', true) : $pacz_settings['header-grid-margin-top'];
			$header_border_top = get_post_meta($post_id, 'header-border-top', true) ? get_post_meta($post_id, 'header-border-top', true) : $pacz_settings['header-border-top'];
		}
		/*** custom breadcrumb coloring ***/
		$custom_breadcrumb_page = get_post_meta($post_id, '_breadcrumb_skin', true) ? 1 : 0;
		$custom_breadcrumb_color = get_post_meta($post_id, '_breadcrumb_custom_color', true) ? get_post_meta($post_id, '_breadcrumb_custom_color', true) : '';
		$custom_breadcrumb_hover_color = get_post_meta($post_id, '_breadcrumb_custom_hover_color', true) ? get_post_meta($post_id, '_breadcrumb_custom_hover_color', true) : '';

	}

	$header_bottom_border = (isset($pacz_settings['header-bottom-border']) && !empty($pacz_settings['header-bottom-border'])) ? ('border-bottom:1px solid' . $pacz_settings['header-bottom-border'] . ';') : '';
	if($pacz_settings['header-grid']){
		$header_grid_margin = $pacz_settings['header-grid-margin-top'];
	}else{
		$header_grid_margin = '';
	}

		/**
		 * Header background
		 */
			$header_bg_color = $pacz_settings['header-bg']['background-color'] ? 'background-color:' . $pacz_settings['header-bg']['background-color'] . ';' : '';
			$header_bg = $pacz_settings['header-bg']['background-color'] ? 'background-color:' . $pacz_settings['header-bg']['background-color'] . ';' : '';
			$header_bg .= $pacz_settings['header-bg']['background-image'] ? 'background-image:url(' . $pacz_settings['header-bg']['background-image'] . ');' : ' ';
			$header_bg .= $pacz_settings['header-bg']['background-position'] ? 'background-position:' . $pacz_settings['header-bg']['background-position'] . ';' : '';
			$header_bg .= $pacz_settings['header-bg']['background-attachment'] ? 'background-attachment:' . $pacz_settings['header-bg']['background-attachment'] . ';' : '';
			$header_bg .= $pacz_settings['header-bg']['background-repeat'] ? 'background-repeat:' . $pacz_settings['header-bg']['background-repeat'] . ';' : '';
			$header_bg .= $pacz_settings['header-bg']['background-size'] ? 'background-size:'. $pacz_settings['header-bg']['background-size'].';' : '';
			
		/**

		 * Transparent Header background
		 */
			$theader_bg_color = isset($pacz_settings['theader-bg']['rgba']) ? 'background-color:' . $pacz_settings['theader-bg']['rgba'] . ';' : '';

			/**
		 * Header toolbar background
		 */
			$toolbar_bg = $pacz_settings['toolbar-bg']['background-color'] ? 'background-color:' . $pacz_settings['toolbar-bg']['background-color'] . ';' : '';
			$toolbar_bg .= $pacz_settings['toolbar-bg']['background-image'] ? 'background-image:url(' . $pacz_settings['toolbar-bg']['background-image'] . ');' : ' ';
			$toolbar_bg .= $pacz_settings['toolbar-bg']['background-position'] ? 'background-position:' . $pacz_settings['toolbar-bg']['background-position'] . ';' : '';
			$toolbar_bg .= $pacz_settings['toolbar-bg']['background-attachment'] ? 'background-attachment:' . $pacz_settings['toolbar-bg']['background-attachment'] . ';' : '';
			$toolbar_bg .= $pacz_settings['toolbar-bg']['background-repeat'] ? 'background-repeat:' . $pacz_settings['toolbar-bg']['background-repeat'] . ';' : '';
			$toolbar_bg .= $pacz_settings['toolbar-bg']['background-size'] ? 'background-size:'. $pacz_settings['toolbar-bg']['background-size'].';' : '';

			$logo_height = (isset($pacz_settings['logo_dimensions'])) ? $pacz_settings['logo_dimensions'] : 50;
			$pacz_header_padding = $pacz_settings['header-padding'];
			$squeeze_sticky_header = $pacz_settings['squeeze-sticky-header'];
			$header_shadow = ($enable)? get_post_meta($post_id, 'header_shadow', true): $pacz_settings['header_shadow'];
Pacz_Static_Files::addGlobalStyle("
	body,.theme-main-wrapper{
		{$body_bg}
	}

");
if($header_shadow == 'false' || $header_shadow == 0){
	Pacz_Static_Files::addGlobalStyle("
		#pacz-header {
			box-shadow:none;
		}

	");
}
$listing_header_btn_color_regular = (isset($pacz_settings['listing-header-btn-color']['regular']))? $pacz_settings['listing-header-btn-color']['regular'] : '';
$listing_header_btn_color_hover = (isset($pacz_settings['listing-header-btn-color']['hover']))? $pacz_settings['listing-header-btn-color']['hover'] : '';
$listing_header_btn_color_bg = (isset($pacz_settings['listing-header-btn-color']['bg']))? $pacz_settings['listing-header-btn-color']['bg'] : '';
$listing_header_btn_color_bghover = (isset($pacz_settings['listing-header-btn-color']['bg-hover']))? $pacz_settings['listing-header-btn-color']['bg-hover'] : '';

$listing_header_btn_color_regular_transparent = (isset($pacz_settings['listing-header-btn-color-transparent']['regular']))? $pacz_settings['listing-header-btn-color-transparent']['regular'] : '';
$listing_header_btn_color_hover_transparent  = (isset($pacz_settings['listing-header-btn-color-transparent']['hover']))? $pacz_settings['listing-header-btn-color-transparent']['hover'] : '';
$listing_header_btn_color_bg_transparent  = (isset($pacz_settings['listing-header-btn-color-transparent']['bg']) && !empty($pacz_settings['listing-header-btn-color-transparent']['bg']))? $pacz_settings['listing-header-btn-color-transparent']['bg'] : 'none';
$listing_header_btn_color_bghover_transparent  = (isset($pacz_settings['listing-header-btn-color-transparent']['bg-hover']))? $pacz_settings['listing-header-btn-color-transparent']['bg-hover'] : 'none';



$post_id = global_get_post_id();
$header_border_top = get_post_meta($post_id, 'header-border-top', true) ? get_post_meta($post_id, 'header-border-top', true) : $pacz_settings['header-border-top'];
if (is_page() && $header_border_top == 'true') {
		
Pacz_Static_Files::addGlobalStyle("
		.theme-main-wrapper:not(.vertical-header) #pacz-header,
		.theme-main-wrapper:not(.vertical-header) .pacz-secondary-header
		{
			border-top:1px solid {$accent_color};
		}
");
}
else if (isset($pacz_settings['header-border-top']) && ($pacz_settings['header-border-top'] == 1)) {
		
Pacz_Static_Files::addGlobalStyle("
		.theme-main-wrapper:not(.vertical-header) #pacz-header,
		.theme-main-wrapper:not(.vertical-header) .pacz-secondary-header
		{
			border-top:1px solid {$accent_color};
		}
");
}
$listing_button_padding_top = (isset($pacz_settings['listing_button_padding']['padding-top'])) ? ('padding-top:'.$pacz_settings['listing_button_padding']['padding-top'].';') : '';
$listing_button_padding_bottom = (isset($pacz_settings['listing_button_padding']['padding-bottom'])) ? ('padding-bottom:'.$pacz_settings['listing_button_padding']['padding-bottom'].';') : '';
$listing_button_padding_left = (isset($pacz_settings['listing_button_padding']['padding-left'])) ? ('padding-left:'.$pacz_settings['listing_button_padding']['padding-left'].';') : '';
$listing_button_padding_right = (isset($pacz_settings['listing_button_padding']['padding-right'])) ? ('padding-right:'.$pacz_settings['listing_button_padding']['padding-right'].';') : '';

$listing_button_border_radius_top_left = (isset($pacz_settings['listing_button_border_radius']['padding-top']) && !empty($pacz_settings['listing_button_border_radius']['padding-top'])) ? ('border-top-left-radius:'.$pacz_settings['listing_button_border_radius']['padding-top'].';') : '';
$listing_button_border_radius_bottom_right = (isset($pacz_settings['listing_button_border_radius']['padding-bottom']) && !empty($pacz_settings['listing_button_border_radius']['padding-bottom'])) ? ('border-bottom-right-radius:'.$pacz_settings['listing_button_border_radius']['padding-bottom'].';') : '';
$listing_button_border_radius_bottom_left = (isset($pacz_settings['listing_button_border_radius']['padding-left']) && !empty($pacz_settings['listing_button_border_radius']['padding-left'])) ? ('border-bottom-left-radius:'.$pacz_settings['listing_button_border_radius']['padding-left'].';') : '';
$listing_button_border_radius_top_right = (isset($pacz_settings['listing_button_border_radius']['padding-right']) && !empty($pacz_settings['listing_button_border_radius']['padding-right'])) ? ('border-top-right-radius:'.$pacz_settings['listing_button_border_radius']['padding-right'].';') : '';

$listing_button_border_width = (isset($pacz_settings['listing_button_border_width']))? ('border-width: '. $pacz_settings['listing_button_border_width'].'px;'): '';
$listing_button_border_color = (isset($pacz_settings['header_listing_button_border_color']['rgba']) && !empty($pacz_settings['header_listing_button_border_color']['color'])) ? ('border-color:'.$pacz_settings['header_listing_button_border_color']['rgba'].';') : '';
$listing_button_border_color_hover = (isset($pacz_settings['header_listing_button_border_color_hover']['rgba']) && !empty($pacz_settings['header_listing_button_border_color_hover']['color'])) ? ('border-color:'.$pacz_settings['header_listing_button_border_color_hover']['rgba'].';') : '';


$listing_button_border_color_transparent = (isset($pacz_settings['header_listing_button_border_color_transparent']['rgba']) && !empty($pacz_settings['header_listing_button_border_color_transparent']['color'])) ? ('border-color:'.$pacz_settings['header_listing_button_border_color_transparent']['rgba'].';') : '';
$listing_button_border_color_hover_transparent = (isset($pacz_settings['header_listing_button_border_color_hover_transparent']['rgba']) && !empty($pacz_settings['header_listing_button_border_color_hover_transparent']['color'])) ? ('border-color:'.$pacz_settings['header_listing_button_border_color_hover_transparent']['rgba'].';') : '';

Pacz_Static_Files::addGlobalStyle(" 
#pacz-header,.pacz-secondary-header, #pacz-header.transparent-header.header-offset-passed,.pacz-secondary-header.transparent-header.header-offset-passed{
	{$header_bg};
	{$header_bg_color};
	}
#pacz-header.transparent-header,.pacz-secondary-header.transparent-header{
	{$theader_bg_color} !important;
	}
.listing-btn{
	display:inline-block;
	
	}
.listing-btn .listing-header-btn,
.listing-btn .directorypress-new-listing-button .btn-primary,
.listing-btn .submit-listing-button-single.btn-primary{
	color:{$listing_header_btn_color_regular};
	background:{$listing_header_btn_color_bg};
	{$listing_button_padding_top}
	{$listing_button_padding_bottom}
	{$listing_button_padding_left}
	{$listing_button_padding_right}
	{$listing_button_border_radius_top_left}
	{$listing_button_border_radius_top_right}
	{$listing_button_border_radius_bottom_right}
	{$listing_button_border_radius_bottom_left}
	{$listing_button_border_width}
	{$listing_button_border_color}
	border-style: solid;
}
.listing-btn .listing-header-btn span,
.listing-btn .directorypress-new-listing-button .btn-primary span,
.listing-btn .submit-listing-button-single.btn-primary span{
	color:{$listing_header_btn_color_regular};
}

.transparent-header:not(.sticky-trigger-header) .listing-btn .listing-header-btn,
.transparent-header:not(.sticky-trigger-header) .listing-btn .directorypress-new-listing-button .btn-primary,
.transparent-header:not(.sticky-trigger-header) .listing-btn .submit-listing-button-single.btn-primary{
	color:{$listing_header_btn_color_regular_transparent};
	background:{$listing_header_btn_color_bg_transparent};
	{$listing_button_border_color_transparent}
	border-style: solid;
}

.listing-btn .listing-header-btn:hover,
.listing-btn .directorypress-new-listing-button .btn-primary:hover,
.listing-btn .submit-listing-button-single.btn-primary:hover,
.listing-btn.mobile-submit .directorypress-new-listing-button .btn-primary:hover{
	background:{$listing_header_btn_color_bghover} !important;
	color:{$listing_header_btn_color_hover} !important;
	{$listing_button_border_color_hover}
}

.trans.listing-btn .listing-header-btn:hover span,
.listing-btn .directorypress-new-listing-button .btn-primary:hover span,
.listing-btn .submit-listing-button-single.btn-primary:hover span{
	color:{$listing_header_btn_color_hover};
}
.transparent-header:not(.sticky-trigger-header) .listing-btn .listing-header-btn:hover,
.transparent-header:not(.sticky-trigger-header) .listing-btn .directorypress-new-listing-button .btn-primary:hover,
.transparent-header:not(.sticky-trigger-header) .listing-btn .submit-listing-button-single.btn-primary:hover,
.transparent-header:not(.sticky-trigger-header) .listing-btn.mobile-submit .directorypress-new-listing-button .btn-primary:hover{
	background:{$listing_header_btn_color_bghover_transparent} !important;
	color:{$listing_header_btn_color_hover_transparent} !important;
	{$listing_button_border_color_hover_transparent}
}

.submit-page-buton.hours-field-btn,
.cz-creat-listing-inner .submit .button.btn{
	color:#fff;
	background:{$pacz_settings['accent-color']};
}
.submit-page-buton.hours-field-btn:hover,
.cz-creat-listing-inner .submit .button.btn:hover{
	background:{$third_color};
}

");

/**
 * Header Toolbar font settings
 */
$toolbar_font = (isset($pacz_settings['toolbar-font']['font-family']) && !empty($pacz_settings['toolbar-font']['font-family'])) ? ('font-family:' . $pacz_settings['toolbar-font']['font-family'] . ';') : '';
$toolbar_font .= (isset($pacz_settings['toolbar-font']['font-weight']) && !empty($pacz_settings['toolbar-font']['font-weight'])) ? ('font-weight:' . $pacz_settings['toolbar-font']['font-weight'] . ';') : '';
$toolbar_font .= (isset($pacz_settings['toolbar-font']['font-size']) && !empty($pacz_settings['toolbar-font']['font-size'])) ? ('font-size:' . $pacz_settings['toolbar-font']['font-size'] . ';') : '';
$logo_height = (!empty($pacz_settings['logo_dimensions'])) ? $pacz_settings['logo_dimensions'] : 50;
$toolbar_height = $pacz_settings['toolbar_height'];
$page_title_padding = $toolbar_height+($pacz_settings['header-padding'] * 2) + 50;
$page_title_height = $page_title_padding+ 94;
$toolbar_lineheight = $pacz_settings['toolbar_height'] - 2; 

$toolbar =(isset($pacz_settings['header-toolbar']) && !empty($pacz_settings['header-toolbar'])) ? $pacz_settings['header-toolbar'] : 0;
$toolbar_check = get_post_meta( $post_id, '_header_toolbar', true );
$toolbar_option = !empty($toolbar_check) ? $toolbar_check : 'true';

if($toolbar){
        if($toolbar_option == 'true'){
			$header_margin_top = $toolbar_height;
			$sticky_header_padding_top =$toolbar_height+($pacz_settings['header-padding'] * 2) +50;
		}
}else{
	$header_margin_top = 1;
	$sticky_header_padding_top =($pacz_settings['header-padding'] * 2) +50;
}
Pacz_Static_Files::addGlobalStyle("
	#pacz-header.sticky-trigger-header{
	
	}
");

if($pacz_settings['top-footer'] == 0){
	
Pacz_Static_Files::addGlobalStyle("
	#pacz-footer .main-footer-top-padding{padding-top:100px;}

");	
	
}elseif($pacz_settings['top-footer'] == 1 && $pacz_settings['footer_form_style'] == 2){
Pacz_Static_Files::addGlobalStyle("
#pacz-footer{padding-top:100px;}
	.footer-top{margin-bottom:100px;border-top:2px solid {$accent_color};}
");	
}
Pacz_Static_Files::addGlobalStyle("

.pacz-header-toolbar{
{$toolbar_bg};
{$toolbar_font};
height:{$toolbar_height}px;
line-height:{$toolbar_lineheight}px;
}

.sticky-header-padding {
	{$header_bg_color}
	
}

#pacz-header.transparent-header-sticky,
#pacz-header.sticky-header {
{$header_bottom_border}}


.transparent-header.light-header-skin,
.transparent-header.dark-header-skin
 {
  border-top: none !important;
  
}
#pacz-header{
	margin-top:{$header_grid_margin}px;
}
#pacz-page-title {
{$page_title_bg}
{$attach}
}

#theme-page
{
{$page_bg}}

#pacz-footer
{
{$footer_bg}
}
#sub-footer
{
	background-color: {$pacz_settings['sub-footer-bg']};
}
.footer-top{
	background-color: {$pacz_settings['top-footer-bg']};
}



#pacz-page-title .pacz-page-heading{
	font-size:{$page_title_size}px;
	color:{$page_title_color};
	{$page_title_weight};
	{$page_title_letter_spacing};
}
#pacz-breadcrumbs {
	line-height:{$page_title_size}px;
}

");

Pacz_Static_Files::addGlobalStyle("
	
	#pacz-page-title
{
	padding-top:20px;
	height:140px;
}
	
");
###########################################
	# Mobile Header
###########################################
$mobile_header_listing_button_color_regular = (isset($pacz_settings['mobile-listing-button-skin']['regular']))? ('color:'. $pacz_settings['mobile-listing-button-skin']['regular'] .';') : '';
$mobile_header_listing_button_color_hover = (isset($pacz_settings['mobile-listing-button-skin']['hover']))? ('color:'. $pacz_settings['mobile-listing-button-skin']['hover'] .';') : '';
$mobile_header_listing_button_color_bg = (isset($pacz_settings['mobile-listing-button-skin']['bg']))? ('background-color:'. $pacz_settings['mobile-listing-button-skin']['bg'] .';') : '';
$mobile_header_listing_button_color_bghover = (isset($pacz_settings['mobile-listing-button-skin']['bg-hover']))? ('background-color:'. $pacz_settings['mobile-listing-button-skin']['bg-hover'] .';') : '';

$mobile_header_login_button_color_regular = (isset($pacz_settings['mobile-login-button-skin']['regular']))? ('color:'. $pacz_settings['mobile-login-button-skin']['regular'] .';') : '';
$mobile_header_login_button_color_hover = (isset($pacz_settings['mobile-login-button-skin']['hover']))? ('color:'. $pacz_settings['mobile-login-button-skin']['hover'] .';') : '';
$mobile_header_login_button_color_bg = (isset($pacz_settings['mobile-login-button-skin']['bg']))? ('background-color:'. $pacz_settings['mobile-login-button-skin']['bg'] .';') : '';
$mobile_header_login_button_color_bghover = (isset($pacz_settings['mobile-login-button-skin']['bg-hover']))? ('background-color:'. $pacz_settings['mobile-login-button-skin']['bg-hover'] .';') : '';

$mobile_header_search_button_color_regular = (isset($pacz_settings['mobile-search-button-skin']['regular']))? ('color:'. $pacz_settings['mobile-search-button-skin']['regular'] .';') : '';
$mobile_header_search_button_color_hover = (isset($pacz_settings['mobile-search-button-skin']['hover']))? ('color:'. $pacz_settings['mobile-search-button-skin']['hover'] .';') : '';
$mobile_header_search_button_color_bg = (isset($pacz_settings['mobile-search-button-skin']['bg']))? ('background-color:'. $pacz_settings['mobile-search-button-skin']['bg'] .';') : '';
$mobile_header_search_button_color_bghover = (isset($pacz_settings['mobile-search-button-skin']['bg-hover']))? ('background-color:'. $pacz_settings['mobile-search-button-skin']['bg-hover'] .';') : '';


$mobile_header_bg = $pacz_settings['mobile-header-bg']['background-color'] ? 'background-color:' . $pacz_settings['mobile-header-bg']['background-color'] . ';' : '';
$mobile_header_bg .= $pacz_settings['mobile-header-bg']['background-image'] ? 'background-image:url(' . $pacz_settings['mobile-header-bg']['background-image'] . ');' : ' ';
$mobile_header_bg .= $pacz_settings['mobile-header-bg']['background-position'] ? 'background-position:' . $pacz_settings['mobile-header-bg']['background-position'] . ';' : '';
$mobile_header_bg .= $pacz_settings['mobile-header-bg']['background-attachment'] ? 'background-attachment:' . $pacz_settings['mobile-header-bg']['background-attachment'] . ';' : '';
$mobile_header_bg .= $pacz_settings['mobile-header-bg']['background-repeat'] ? 'background-repeat:' . $pacz_settings['mobile-header-bg']['background-repeat'] . ';' : '';
$mobile_header_bg .= $pacz_settings['mobile-header-bg']['background-size'] ? 'background-size:'. $pacz_settings['mobile-header-bg']['background-size'].';' : '';

$mobile_header_menu_container_bg = $pacz_settings['mobile-header-menu-wrapper-bg']['background-color'] ? 'background-color:' . $pacz_settings['mobile-header-menu-wrapper-bg']['background-color'] . ';' : '';
$mobile_header_menu_container_bg .= $pacz_settings['mobile-header-menu-wrapper-bg']['background-image'] ? 'background-image:url(' . $pacz_settings['mobile-header-menu-wrapper-bg']['background-image'] . ');' : ' ';
$mobile_header_menu_container_bg .= $pacz_settings['mobile-header-menu-wrapper-bg']['background-position'] ? 'background-position:' . $pacz_settings['mobile-header-menu-wrapper-bg']['background-position'] . ';' : '';
$mobile_header_menu_container_bg .= $pacz_settings['mobile-header-menu-wrapper-bg']['background-attachment'] ? 'background-attachment:' . $pacz_settings['mobile-header-menu-wrapper-bg']['background-attachment'] . ';' : '';
$mobile_header_menu_container_bg .= $pacz_settings['mobile-header-menu-wrapper-bg']['background-repeat'] ? 'background-repeat:' . $pacz_settings['mobile-header-menu-wrapper-bg']['background-repeat'] . ';' : '';
$mobile_header_menu_container_bg .= $pacz_settings['mobile-header-menu-wrapper-bg']['background-size'] ? 'background-size:'. $pacz_settings['mobile-header-menu-wrapper-bg']['background-size'].';' : '';

$mobile_header_autor_bg = $pacz_settings['mobile-header-author-bg']['background-color'] ? 'background-color:' . $pacz_settings['mobile-header-author-bg']['background-color'] . ';' : '';
$mobile_header_autor_bg .= $pacz_settings['mobile-header-author-bg']['background-image'] ? 'background-image:url(' . $pacz_settings['mobile-header-author-bg']['background-image'] . ');' : ' ';
$mobile_header_autor_bg .= $pacz_settings['mobile-header-author-bg']['background-position'] ? 'background-position:' . $pacz_settings['mobile-header-author-bg']['background-position'] . ';' : '';
$mobile_header_autor_bg .= $pacz_settings['mobile-header-author-bg']['background-attachment'] ? 'background-attachment:' . $pacz_settings['mobile-header-author-bg']['background-attachment'] . ';' : '';
$mobile_header_autor_bg .= $pacz_settings['mobile-header-author-bg']['background-repeat'] ? 'background-repeat:' . $pacz_settings['mobile-header-author-bg']['background-repeat'] . ';' : '';
$mobile_header_autor_bg .= $pacz_settings['mobile-header-author-bg']['background-size'] ? 'background-size:'. $pacz_settings['mobile-header-author-bg']['background-size'].';' : '';

$mobile_header_autor_display_name_color = (isset($pacz_settings['mobile-header-author-display-name-color']))? ('color:'. $pacz_settings['mobile-header-author-display-name-color'] .';') : '';
$mobile_header_autor_nickname_color = (isset($pacz_settings['mobile-header-author-nickname-color']))? ('color:'. $pacz_settings['mobile-header-author-nickname-color'] .';') : '';
$mobile_header_autor_links_color_regular = (isset($pacz_settings['mobile-header-author-links-color']['regular']))? ('color:'. $pacz_settings['mobile-header-author-links-color']['regular'] .';') : '';
$mobile_header_autor_links_color_hover = (isset($pacz_settings['mobile-header-author-links-color']['hover']))? ('color:'. $pacz_settings['mobile-header-author-links-color']['hover'] .';') : '';

$mobile_header_menu_color_regular = (isset($pacz_settings['mobile-nav-top-color']['regular']))? ('color:'. $pacz_settings['mobile-nav-top-color']['regular'] .';') : '';
$mobile_header_menu_color_hover = (isset($pacz_settings['mobile-nav-top-color']['hover']))? ('color:'. $pacz_settings['mobile-nav-top-color']['hover'] .';') : '';
$mobile_header_menu_color_bg = (isset($pacz_settings['mobile-nav-top-color']['bg']))? ('background-color:'. $pacz_settings['mobile-nav-top-color']['bg'] .';') : '';
$mobile_header_menu_color_bghover = (isset($pacz_settings['mobile-nav-top-color']['bg-hover']))? ('background-color:'. $pacz_settings['mobile-nav-top-color']['bg-hover'] .';') : '';
$mobile_header_menu_color_bgactive = (isset($pacz_settings['mobile-nav-top-color']['bg-active']))? ('background-color:'. $pacz_settings['mobile-nav-top-color']['bg-active'] .';') : '';
$mobile_header_menu_border_color = (isset($pacz_settings['mobile-top-menu-border-color']))? ('border-color:'. $pacz_settings['mobile-top-menu-border-color'] .';') : '';

$mobile_header_sub_menu_color_regular = (isset($pacz_settings['mobile-nav-sub-menu-color']['regular']))? ('color:'. $pacz_settings['mobile-nav-sub-menu-color']['regular'] .';') : '';
$mobile_header_sub_menu_color_hover = (isset($pacz_settings['mobile-nav-sub-menu-color']['hover']))? ('color:'. $pacz_settings['mobile-nav-sub-menu-color']['hover'] .';') : '';
$mobile_header_sub_menu_color_bg = (isset($pacz_settings['mobile-nav-sub-menu-color']['bg']))? ('background-color:'. $pacz_settings['mobile-nav-sub-menu-color']['bg'] .';') : '';
$mobile_header_sub_menu_color_bghover = (isset($pacz_settings['mobile-nav-sub-menu-color']['bg-hover']))? ('background-color:'. $pacz_settings['mobile-nav-sub-menu-color']['bg-hover'] .';') : '';
$mobile_header_sub_menu_color_bgactive = (isset($pacz_settings['mobile-nav-sub-menu-color']['bg-active']))? ('background-color:'. $pacz_settings['mobile-nav-sub-menu-color']['bg-active'] .';') : '';		

$mobile_header_menu_burger_color_regular = (isset($pacz_settings['mobile-header-menu-icon-color']['regular']))? ('background-color:'. $pacz_settings['mobile-header-menu-icon-color']['regular'] .';') : '';
$mobile_header_menu_burger_color_hover = (isset($pacz_settings['mobile-header-menu-icon-color']['hover']))? ('background-color:'. $pacz_settings['mobile-header-menu-icon-color']['hover'] .';') : '';
$mobile_header_menu_burger_color_active = (isset($pacz_settings['mobile-header-menu-icon-color']['active']))? ('background-color:'. $pacz_settings['mobile-header-menu-icon-color']['active'] .';') : '';


Pacz_Static_Files::addGlobalStyle("
	#pacz-header.mobile-header{
		{$mobile_header_bg}
	}
	.mobile-active-menu-user-wrap{
		{$mobile_header_autor_bg}
	}
	.mobile-responsive-nav-container{
		{$mobile_header_menu_container_bg}
	}
	.mobile-responsive-nav-container .res-menu-close{
		{$mobile_header_autor_bg}
		color:#fff;
	}
	.mobile-active-menu-logreg-links .author-displayname{
		{$mobile_header_autor_display_name_color}
	}
	.mobile-active-menu-logreg-links .author-nicename{
		{$mobile_header_autor_nickname_color}
	}
	.mobile-active-menu-logreg-links a{
		{$mobile_header_autor_links_color_regular}
	}
	.mobile-active-menu-logreg-links a:hover{
		{$mobile_header_autor_links_color_hover}
	}
	.pacz-mobile-listing-btn .submit-listing-button-single,
	.pacz-mobile-listing-btn .dropdown-toggle,
	.desktop .submit-listing-button-single,
	.desktop .dropdown.directorypress-new-listing-button:last-child .dropdown-toggle{
		{$mobile_header_listing_button_color_regular}
		{$mobile_header_listing_button_color_bg}
	}
	.pacz-mobile-listing-btn .submit-listing-button-single:hover,
	.pacz-mobile-listing-btn .dropdown-toggle:hover,
	.desktop .submit-listing-button-single:last-child:hover,
	.desktop .dropdown.directorypress-new-listing-button:last-child .dropdown-toggle:hover{
		{$mobile_header_listing_button_color_hover}
		{$mobile_header_listing_button_color_bghover}
	}
	.pacz-mobile-login{
		{$mobile_header_login_button_color_regular}
		{$mobile_header_login_button_color_bg}
	}
	.pacz-mobile-login:hover{
		{$mobile_header_login_button_color_hover}
		{$mobile_header_login_button_color_bghover}
	}
	.responsive-nav-search-link .search-burgur{
		{$mobile_header_search_button_color_regular}
		{$mobile_header_search_button_color_bg}
	}
	.responsive-nav-search-link .search-burgur:hover{
		{$mobile_header_search_button_color_hover}
		{$mobile_header_search_button_color_bghover}
	}
	.responsive-nav-link .pacz-burger-icon div{
		{$mobile_header_menu_burger_color_regular}
	}
	.responsive-nav-link .pacz-burger-icon:hover div{
		{$mobile_header_menu_burger_color_hover}
	}
	.responsive-nav-link.active-burger .pacz-burger-icon div{
		{$mobile_header_menu_burger_color_active}
	}
	.pacz-responsive-nav li a{
		{$mobile_header_menu_color_regular}
		{$mobile_header_menu_color_bg}
		{$mobile_header_menu_border_color}
	}
	.pacz-responsive-nav li a:hover{
		{$mobile_header_menu_color_hover}
		{$mobile_header_menu_color_bghover}
	}
	.pacz-responsive-nav li.current-menu-item a{
		{$mobile_header_menu_color_bgactive}
	}
	.pacz-responsive-nav li ul li a,
	.pacz-responsive-nav li ul li .megamenu-title{
		{$mobile_header_sub_menu_color_regular}
		{$mobile_header_sub_menu_color_bg}
	}
	.pacz-responsive-nav li ul li a:hover,
	.pacz-responsive-nav li ul li .megamenu-title:hover{
		{$mobile_header_sub_menu_color_hover}
		{$mobile_header_sub_menu_color_bghover}
	}
	.pacz-responsive-nav li ul li.current-menu-item a,
	.pacz-responsive-nav li ul li.current-menu-item .megamenu-title{
		{$mobile_header_sub_menu_color_bgactive}
	}
	
");

###########################################
	# Widgets
###########################################

	$widget_font_family = (isset($pacz_settings['widget-title']['font-family']) && !empty($pacz_settings['widget-title']['font-family'])) ? ('font-family:' . $pacz_settings['widget-title']['font-family'] . ';') : '';
	$widget_font_size = (isset($pacz_settings['widget-title']['font-size']) && !empty($pacz_settings['widget-title']['font-size'])) ? ('font-size:' . $pacz_settings['widget-title']['font-size'] . ';') : '';
	$widget_font_weight = (isset($pacz_settings['widget-title']['font-weight']) && !empty($pacz_settings['widget-title']['font-weight'])) ? ('font-weight:' . $pacz_settings['widget-title']['font-weight'] . ';') : '';
	$widget_title_divider = (isset($pacz_settings['widget-title-divider']) && $pacz_settings['widget-title-divider'] == 1) ? '' : 'display: none;'; 

	if(isset($pacz_settings['footer-col-border']) && $pacz_settings['footer-col-border'] == 1){
Pacz_Static_Files::addGlobalStyle("
#pacz-footer [class*='pacz-col-'] {
  border-right:1px solid {$pacz_settings['footer-col-border-color']};
}
#pacz-footer [class*='pacz-col-']:last-of-type {
  border-right:none;
}
#pacz-footer .pacz-col-1-2:nth-child(2),
#pacz-footer [class*='pacz-col-']:last-child {
  border-right:none;
}

");
}
Pacz_Static_Files::addGlobalStyle("
.widgettitle
{
{$widget_font_family}
{$widget_font_size}
{$widget_font_weight}
}

.widgettitle:after{
	{$widget_title_divider}
}

#pacz-footer .widget_posts_lists ul li .post-list-title{
	color:{$pacz_settings['footer-title-color']};
}
#pacz-footer .widget_posts_lists ul li .post-list-title:hover{
	color: {$pacz_settings['footer-link-color']['hover']};
}
.widget_posts_lists ul li {
	border-color:{$pacz_settings['footer-recent-lisitng-border-color']};
}
.classiadspro-form-row .classiadspro-subscription-button{
	background-color:{$accent_color};
}
.classiadspro-form-row .classiadspro-subscription-button:hover{
	background-color:{$third_color};
}
.widget-social-container.simple-style a.dark{
	color: {$pacz_settings['footer-social-color']['regular']} !important;
}
.widget-social-container.simple-style a.dark:hover{
	color: {$pacz_settings['footer-social-color']['hover']}!important;
}
.widget .phone-number i,
.widget .email-id i{
	color: {$pacz_settings['footer-social-color']['hover']}!important;
}
#pacz-sidebar .widgettitle,
#pacz-sidebar .widgettitle  a
{
	color: {$pacz_settings['sidebar-title-color']};
}


#pacz-sidebar,
#pacz-sidebar p
{
	color: {$pacz_settings['sidebar-txt-color']};
}


#pacz-sidebar a
{
	color: {$pacz_settings['sidebar-link-color']['regular']};
}

#pacz-sidebar a:hover
{
	color: {$pacz_settings['sidebar-link-color']['hover']};
}

#pacz-footer .widgettitle,
#pacz-footer .widgettitle a
{
	color: {$pacz_settings['footer-title-color']};
}

#pacz-footer,
#pacz-footer p
{
	color: {$pacz_settings['footer-txt-color']};
}

#pacz-footer a
{
	color: {$pacz_settings['footer-link-color']['regular']};
}

#pacz-footer a:hover
{
	color: {$pacz_settings['footer-link-color']['hover']};
}

.pacz-footer-copyright,
.pacz-footer-copyright a {
	color: {$pacz_settings['footer-socket-color']} !important;
}

.sub-footer .pacz-footer-social li a i{
	color: {$pacz_settings['footer-social-color']['regular']} !important;
}

.sub-footer .pacz-footer-social a:hover {
	color: {$pacz_settings['footer-social-color']['hover']}!important;
}

#sub-footer .pacz-footer-social li a.icon-twitter i,
#sub-footer .pacz-footer-social li a.icon-linkedin i,
#sub-footer .pacz-footer-social li a.icon-facebook i,
#sub-footer .pacz-footer-social li a.icon-pinterest i,
#sub-footer .pacz-footer-social li a.icon-google-plus i,
#sub-footer .pacz-footer-social li a.icon-instagram i,
#sub-footer .pacz-footer-social li a.icon-dribbble i,
#sub-footer .pacz-footer-social li a.icon-rss i,
#sub-footer .pacz-footer-social li a.icon-youtube-play i,
#sub-footer .pacz-footer-social li a.icon-behance i,
#sub-footer .pacz-footer-social li a.icon-whatsapp i,
#sub-footer .pacz-footer-social li a.icon-vimeo i,
#sub-footer .pacz-footer-social li a.icon-weibo i,
#sub-footer .pacz-footer-social li a.icon-spotify i,
#sub-footer .pacz-footer-social li a.icon-vk i,
#sub-footer .pacz-footer-social li a.icon-qzone i,
#sub-footer .pacz-footer-social li a.icon-wechat i,
#sub-footer .pacz-footer-social li a.icon-renren i,
#sub-footer .pacz-footer-social li a.icon-imdb i{
	color: {$pacz_settings['footer-social-color']['regular']} !important;
	
}
#sub-footer .pacz-footer-social li a:hover i{color: {$pacz_settings['footer-social-color']['hover']}!important;}

#sub-footer .pacz-footer-social li a.icon-twitter:hover,
#sub-footer .pacz-footer-social li a.icon-linkedin:hover,
#sub-footer .pacz-footer-social li a.icon-facebook:hover,
#sub-footer .pacz-footer-social li a.icon-pinterest:hover,
#sub-footer .pacz-footer-social li a.icon-google-plus:hover,
#sub-footer .pacz-footer-social li a.icon-instagram:hover,
#sub-footer .pacz-footer-social li a.icon-dribbble:hover,
#sub-footer .pacz-footer-social li a.icon-rss:hover,
#sub-footer .pacz-footer-social li a.icon-youtube-play:hover,
#sub-footer .pacz-footer-social li a.icon-tumblr:hover,
#sub-footer .pacz-footer-social li a.icon-behance:hover,
#sub-footer .pacz-footer-social li a.icon-whatsapp:hover,
#sub-footer .pacz-footer-social li a.icon-vimeo:hover,
#sub-footer .pacz-footer-social li a.icon-weibo:hover,
#sub-footer .pacz-footer-social li a.icon-spotify:hover,
#sub-footer .pacz-footer-social li a.icon-vk:hover,
#sub-footer .pacz-footer-social li a.icon-qzone:hover,
#sub-footer .pacz-footer-social li a.icon-wechat:hover,
#sub-footer .pacz-footer-social li a.icon-renren:hover,
#sub-footer .pacz-footer-social li a.icon-imdb:hover{
	background-color: {$pacz_settings['footer-social-color']['bg-hover']}!important;
	
}

#sub-footer .pacz-footer-social li a{
	background-color: {$pacz_settings['footer-social-color']['bg']}!important;
	box-shadow:none;
}

#pacz-sidebar .widget_posts_lists ul li .post-list-title{
	color:{$pacz_settings['heading-color']};
	
}
#pacz-sidebar .widget_archive ul li a:before,
#pacz-sidebar .widget_categories a:before{
	color:{$pacz_settings['accent-color']};
	
}
#pacz-sidebar .widget_archive ul li a:hover:before,
#pacz-sidebar .widget_categories a:hover:before{
	
}
#pacz-sidebar .widgettitle:before {
	background-color:{$pacz_settings['accent-color']};
	
}

.hover-overlay{
	 background: {$pacz_settings['accent-color']} !important;
}

");



###########################################
	# Typography & Coloring
	###########################################

	$body_font_backup = (isset($pacz_settings['body-font']['font-backup']) && !empty($pacz_settings['body-font']['font-backup'])) ? ('font-family:' . $pacz_settings['body-font']['font-backup'] . ';') : '';
	$body_font_family = (isset($pacz_settings['body-font']['font-family']) && !empty($pacz_settings['body-font']['font-family'])) ? ('font-family:' . $pacz_settings['body-font']['font-family'] . ';') : '';
	$heading_font_family = (isset($pacz_settings['heading-font']['font-family']) && !empty($pacz_settings['heading-font']['font-family'])) ? ('font-family:' . $pacz_settings['heading-font']['font-family'] . ';') : '';
	$heading_font_family_h2 = (isset($pacz_settings['heading-font-h2']['font-family']) && !empty($pacz_settings['heading-font-h2']['font-family'])) ? ('font-family:' . $pacz_settings['heading-font-h2']['font-family'] . ';') : $heading_font_family ;
	$heading_font_family_h3 = (isset($pacz_settings['heading-font-h3']['font-family']) && !empty($pacz_settings['heading-font-h3']['font-family'])) ? ('font-family:' . $pacz_settings['heading-font-h3']['font-family'] . ';') : $heading_font_family ;
	$heading_font_family_h4 = (isset($pacz_settings['heading-font-h4']['font-family']) && !empty($pacz_settings['heading-font-h4']['font-family'])) ? ('font-family:' . $pacz_settings['heading-font-h4']['font-family'] . ';') : $heading_font_family ;
	$heading_font_family_h5 = (isset($pacz_settings['heading-font-h5']['font-family']) && !empty($pacz_settings['heading-font-h5']['font-family'])) ? ('font-family:' . $pacz_settings['heading-font-h5']['font-family'] . ';') : $heading_font_family ;
	$heading_font_family_h6 = (isset($pacz_settings['heading-font-h6']['font-family']) && !empty($pacz_settings['heading-font-h6']['font-family'])) ? ('font-family:' . $pacz_settings['heading-font-h6']['font-family'] . ';') : $heading_font_family ;
	
	$headings_font_family = (isset($pacz_settings['headings_font_family']['font-family']) && !empty($pacz_settings['headings_font_family']['font-family'])) ? ('font-family:' . $pacz_settings['headings_font_family']['font-family'] . ';') : '';
	$headings_font_weight = (isset($pacz_settings['headings_font_family']['font-weight']) && !empty($pacz_settings['headings_font_family']['font-weight'])) ? ('font-weight:' . $pacz_settings['headings_font_family']['font-weight'] . ';') : '';
	//$heading_font_weight = (isset($pacz_settings['heading-font']['font-weight']) && !empty($pacz_settings['heading-font']['font-weight'])) ? ('font-weight:' . $pacz_settings['heading-font']['font-weight'] . ';') : '';
	$heading_font_color = (isset($pacz_settings['heading-color']) && !empty($pacz_settings['heading-color'])) ? ('color:' . $pacz_settings['heading-color'] . ';') : '';
	
	$buttons_font_family = (isset($pacz_settings['buttons_font_family']['font-family']) && !empty($pacz_settings['buttons_font_family']['font-family'])) ? ('font-family:' . $pacz_settings['buttons_font_family']['font-family'] . ';') : '';
	$buttons_font_weight = (isset($pacz_settings['buttons_font_family']['font-weight']) && !empty($pacz_settings['buttons_font_family']['font-weight'])) ? ('font-weight:' . $pacz_settings['buttons_font_family']['font-weight'] . ';') : '';
	
	
	$p_font_size = (isset($pacz_settings['p-text-size']) && !empty($pacz_settings['p-text-size'])) ? $pacz_settings['p-text-size'] : $pacz_settings['body-font']['font-size'];
	$cart_link_color_regular = (isset($pacz_settings['header_cart_link_color']['regular']))? ('color:'. $pacz_settings['header_cart_link_color']['regular'] .';') : '';
	$cart_link_color_hover = (isset($pacz_settings['header_cart_link_color']['hover']))? ('color:'. $pacz_settings['header_cart_link_color']['hover'] .';') : '';
	$cart_link_color_bg = (isset($pacz_settings['header_cart_link_color']['bg']) && !empty($pacz_settings['header_cart_link_color']['bg']))? ('background:'. $pacz_settings['header_cart_link_color']['bg'] .';') : '';
	$cart_link_color_bghover = (isset($pacz_settings['header_cart_link_color']['bg-hover']) && !empty($pacz_settings['header_cart_link_color']['bg-hover']))? ('background:'. $pacz_settings['header_cart_link_color']['bg-hover'] .';') : '';
	$cart_link_color_border = (isset($pacz_settings['header_cart_link_color']['bg']) && !empty($pacz_settings['header_cart_link_color']['bg']))? ('border-color:'. $pacz_settings['header_cart_link_color']['bg'] .';') : '';
	$cart_link_color_borderhover = (isset($pacz_settings['header_cart_link_color']['bg-hover']) && !empty($pacz_settings['header_cart_link_color']['bg-hover']))? ('border-color:'. $pacz_settings['header_cart_link_color']['bg-hover'] .';') : '';
	
	
	Pacz_Static_Files::addGlobalStyle("
	
	body{
	line-height: 20px;
{$body_font_backup}
{$body_font_family}
	font-size:{$pacz_settings['body-font']['font-size']};
	color:{$pacz_settings['body-txt-color']};
}

{$typekit_fonts_1}

p {
	font-size:{$p_font_size}px;
	color:{$pacz_settings['body-txt-color']};
	line-height:{$pacz_settings['p-line-height']}px;
}

#pacz-footer p {
	font-size:{$pacz_settings['footer-p-text-size']}px;
}
a {
	color:{$pacz_settings['link-color']['regular']};
}

a:hover {
	color:{$pacz_settings['link-color']['hover']};
}


.outline-button{
	background-color:{$pacz_settings['accent-color']} !important;
	}
.tweet-icon{
	border-color:{$pacz_settings['accent-color']};
	color:{$pacz_settings['accent-color']};
	
	}
.tweet-user,
.tweet-time{
	color:{$pacz_settings['accent-color']};
	
	}
#theme-page .pacz-custom-heading h4:hover{
	color:{$pacz_settings['heading-color']};
	
}

.title-divider span{background:{$pacz_settings['accent-color']};}
#theme-page h1,
#theme-page h2,
#theme-page h3,
#theme-page h4,
#theme-page h5,
#theme-page h6,
.subscription-form .title h5
{
	font-weight:{$pacz_settings['heading-font']['font-weight']};
	color:{$pacz_settings['heading-color']};
}
#theme-page h1:hover,
#theme-page h2:hover,
#theme-page h3:hover,
#theme-page h4:hover,
#theme-page h5:hover,
#theme-page h6:hover
{
	
}
.blog-tile-entry .blog-entry-heading .blog-title a,
.blog-title a,
.leave-comment-heading{
	color:{$pacz_settings['heading-color']};
}

.blog-tile-entry .blog-entry-heading .blog-title a:hover,
.blog-title a:hover,
.blog-tile-entry .item-holder .metatime a{
	color:{$pacz_settings['accent-color']};
}
.blog-tile-entry.tile-elegant .metatime a,
.blog-tile-entry.tile-elegant .blog-comments,
.blog-tile-entry.tile-elegant .author,
.blog-tile-entry.tile-elegant .author span:hover{
	color:{$pacz_settings['link-color']['regular']};
}
.blog-tile-entry.tile-elegant .metatime a:hover,
.blog-tile-entry.tile-elegant .blog-comments:hover,
.blog-tile-entry.tile-elegant .author:hover{
	color:{$pacz_settings['link-color']['hover']};
}
.tile-elegant .blog-readmore-btn a{
	color:{$pacz_settings['heading-color']};
}
.author-title{
	color:{$pacz_settings['heading-color']};
	{$heading_font_family}
}
.tile-elegant .blog-readmore-btn a:hover{
	color:{$pacz_settings['accent-color']};
}

.tile-elegant .blog-readmore-btn:hover:before,
.blog-tile-entry.tile-elegant .blog-meta::before{
	background:{$pacz_settings['accent-color']};
}

.countdown_style_five ul li .countdown-timer{
	color:{$pacz_settings['heading-color']} !important;
	
}
.owl-nav .owl-prev, .owl-nav .owl-next{
	color:{$pacz_settings['accent-color']};
	
	}
.owl-nav .owl-prev:hover, .owl-nav .owl-next:hover{
	background:{$pacz_settings['accent-color']};
	
	}

.countdown_style_five ul li .countdown-text{
	color:{$pacz_settings['body-txt-color']} !important;
	
}

.single-social-share li a:hover,
.pacz-next-prev .pacz-next-prev-wrap a:hover {
  color: {$pacz_settings['accent-color']};
}


h1, h2, h3, h4, h5, h6{
	{$heading_font_family}
}
h2{
	{$heading_font_family_h2}
}
h3{
	{$heading_font_family_h3}
}
h4{
	{$heading_font_family_h4}
}
h5{
	{$heading_font_family_h5}
}
h5{
	{$heading_font_family_h6}
}
#pacz-footer .widget_posts_lists ul li .post-list-title{
	{$heading_font_family_h6}
}
.pacz-post-single-comments-heading,
		.post-list-title,
		.pacz_author_widget .pacz-post-author-name,
		#pacz-sidebar .pacz_author_widget .pacz-post-author-name,
		.pacz-post-comment-author a,
		.widget_recent_entries li a,
		ul.wp-block-latest-posts li a,
		.widget_recent_comments li .comment-author-link,
		.wp-block-latest-comments li .wp-block-latest-comments__comment-author,
		table tbody th,
		dt,
		.widget.widget_categories ul li a,
		.widget.widget_pages ul li a,
		.widget.widget_meta ul li a,
		.widget.widget_nav_menu ul li a,
		.widget.widget_archive ul li a,
		ul.wp-block-archives li a,
		ul.wp-block-categories li a,
		ul.wp-block-pages li a,
		ul.wp-block-meta li a,
		ul.wp-block-nav-menu li a,
		.widget_custom_menu ul li a,
		.widget_rss .rsswidget,
		ul.wp-block-rss .wp-block-rss__item-title,
		.pacz-post-single-tags-label,
		.pacz-post-readmore-link{
			{$headings_font_family}
			{$headings_font_weight}
			{$heading_font_color}
		}
		.comment-respond form .form-submit .submit,
		.pacz-post-single-content .post-password-form input[type='submit']{
			{$buttons_font_family}
			{$buttons_font_weight}
		}
input,
button,
textarea {
{$body_font_family}}

.comments-heading-label{
	{$heading_font_family_h5}
	color:{$pacz_settings['heading-color']};
	
}
");

###########################################
# Main Navigation
###########################################

	$nav_text_align = (isset($pacz_settings['nav-alignment']) && !empty($pacz_settings['nav-alignment'])) ? ('text-align:' . $pacz_settings['nav-alignment'] . ';') : ('text-align:left;');

	$main_nav_font_family = (isset($pacz_settings['main-nav-font']['font-family']) && !empty($pacz_settings['main-nav-font']['font-family'])) ? ('font-family:' . $pacz_settings['main-nav-font']['font-family'] . ';') : '';

	if($pacz_settings['header-structure'] == 'vertical'){
		$main_nav_top_level_space = (isset($pacz_settings['main-nav-item-space']) && !empty($pacz_settings['main-nav-item-space']) && isset($pacz_settings['vertical-nav-item-space']) && !empty($pacz_settings['vertical-nav-item-space'])) ? ('padding:'. $pacz_settings['vertical-nav-item-space'] . 'px ' . $pacz_settings['main-nav-item-space'] . 'px;') : ('padding: 9px 15px;');
		$plus_for_submenu = $pacz_settings['main-nav-item-space'] + 10;
		$main_nav_top_level_space_lr = (isset($pacz_settings['main-nav-item-space'])) && !empty($pacz_settings['main-nav-item-space']) ? ('padding: 0 '.$plus_for_submenu .'px ;') : ('padding: 0 15px;');

		$main_nav_top_level_space_bt = isset($pacz_settings['vertical-nav-item-space']) && !empty($pacz_settings['vertical-nav-item-space']) ? ('padding:'. $pacz_settings['vertical-nav-item-space'] . 'px 0;') : ('padding: 9px 0;');

		
	}else{
		$main_nav_top_level_space = (isset($pacz_settings['main-nav-item-space'])) && !empty($pacz_settings['main-nav-item-space']) ? ('padding: 0 ' . $pacz_settings['main-nav-item-space'] . 'px;') : ('padding: auto 17px;');
	}
	

	$main_nav_top_level_font_size = 'font-size:' . $pacz_settings['main-nav-font']['font-size'] . ';';

	$main_nav_top_level_font_transform = (isset($pacz_settings['main-nav-top-transform']) && !empty($pacz_settings['main-nav-top-transform'])) ? ('text-transform: ' . $pacz_settings['main-nav-top-transform'] . ';') : ('text-transform: uppercase;');

	$main_nav_top_level_font_weight = 'font-weight:' . $pacz_settings['main-nav-font']['font-weight'] . ';';

	$main_nav_sub_level_font_size = (isset($pacz_settings['sub-nav-top-size']) && !empty($pacz_settings['sub-nav-top-size'])) ? ('font-size:' . $pacz_settings['sub-nav-top-size'] . 'px;') : ('font-size:' . $pacz_settings['main-nav-font']['font-size'] . 'px;');

	$main_nav_sub_level_font_transform = (isset($pacz_settings['sub-nav-top-transform']) && !empty($pacz_settings['sub-nav-top-transform'])) ? ('text-transform: ' . $pacz_settings['sub-nav-top-transform'] . ';') : ('text-transform: uppercase;');
	
	$main_nav_sub_level_font_weight = (isset($pacz_settings['sub-nav-top-weight']) && !empty($pacz_settings['sub-nav-top-weight'])) ? ('font-weight:' . $pacz_settings['sub-nav-top-weight'] . ';') : ('font-weight:' . $pacz_settings['main-nav-font']['font-weight'] . ';');
	
	
	$header_toolbar_height = $logo_height;
	$header_height = ($pacz_header_padding * 2) + $logo_height;
	if ($squeeze_sticky_header) {
		$sticky_logo_height = round($logo_height / 1.5);
		$sticky_header_padding = round($pacz_header_padding / 2);
		$header_sticky_height = $sticky_logo_height + $pacz_header_padding;
	} else {
		$sticky_logo_height = $logo_height;
		$sticky_header_padding = $pacz_header_padding;
		$header_sticky_height = round($logo_height+(($pacz_header_padding) * 2));
	}
	$resposive_logo_height = round($logo_height / 1.5);
	$responsive_header_height = ($pacz_header_padding * 2) + $resposive_logo_height;
	$header_vertical_width = (isset($pacz_settings['header-vertical-width']) && !empty($pacz_settings['header-vertical-width'])) ? $pacz_settings['header-vertical-width'] : ('280');
	$header_vertical_padding = (isset($pacz_settings['header-padding-vertical']) && !empty($pacz_settings['header-padding-vertical'])) ? $pacz_settings['header-padding-vertical'] : ('30'); 

	$vertical_nav_width = $header_vertical_width - ($header_vertical_padding * 2);
	
	# Header Toolbar
	if($pacz_settings['header-toolbar'] == 1){
		$header_height_with_toolbar = $header_toolbar_height+($pacz_settings['header-padding'] * 2) + 30;
	}else{
		$header_height_with_toolbar = $logo_height+($pacz_settings['header-padding'] * 2);
	}
	$toolbar_border = isset($pacz_settings['toolbar-border-top']) && ($pacz_settings['toolbar-border-top'] == 1) ? '' : 'border:none;';
	$sticky_triger_translate = $header_toolbar_height + 60;
	//$sticky_header_padding_top = $logo_height+($pacz_settings['header-padding'] * 2) +100;
	$header_hover_style1_padding = $pacz_settings['header-padding'] / 1.8;
	if($pacz_settings['header-toolbar'] == 1){
		Pacz_Static_Files::addGlobalStyle("
		#pacz-header {
    box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
}
		");
	}
	$header_style = 'transparent';
	if($pacz_settings['header-toolbar'] == 1 && $header_style == 'transparent'){
		Pacz_Static_Files::addGlobalStyle("
		#pacz-header.transparent-header{
			top: {$toolbar_height}px;
			
		}
		#pacz-header.transparent-header.sticky-trigger-header {
			top: 0px !important;
			position:fixed !important;
			
		}
		");
	}else{
		$header_height_with_toolbar = $logo_height+($pacz_settings['header-padding'] * 2);
	}
if($pacz_settings['header-structure'] == 'full'){
	Pacz_Static_Files::addGlobalStyle("
	#pacz-header{
		padding-left:60px;
		padding-right:60px;
	}
	#pacz-header .pacz-grid{
		width:100%;
		max-width:100%;
	}
");
}
if($pacz_settings['header-logo-location'] == 'header_toolbar' && $pacz_settings['header-align'] == 'left'){
Pacz_Static_Files::addGlobalStyle("	
#pacz-header{border:0;}
#pacz-main-navigation{}
#pacz-main-navigation > ul { float: left;}
#pacz-main-navigation > ul li.menu-item { float: left;}
");
}


Pacz_Static_Files::addGlobalStyle("
.header-searchform-input input[type=text]{
	background-color:{$pacz_settings['header-bg']['background-color']};
}

.theme-main-wrapper:not(.vertical-header) .sticky-header.sticky-header-padding {
	
}
body:not(.vertical-header).sticky--header-padding .sticky-header-padding.sticky-header {
	
}

.bottom-header-padding.none-sticky-header {
	padding-top:{$header_height}px;	
}

.bottom-header-padding.none-sticky-header {
	padding-top:{$header_height}px;	
}

.bottom-header-padding.sticky-header {
	padding-top:{$header_sticky_height}px;	
}
.listing-btn{
	display:inline-block;
	
	}
");
if($pacz_settings['preset_headers'] != 12){
Pacz_Static_Files::addGlobalStyle("
#pacz-header:not(.header-structure-vertical) #pacz-main-navigation > ul > li.menu-item,
#pacz-header:not(.header-structure-vertical) #pacz-main-navigation > ul > li.menu-item > a,
#pacz-header:not(.header-structure-vertical) .pacz-header-search,
#pacz-header:not(.header-structure-vertical) .pacz-header-search a,
#pacz-header:not(.header-structure-vertical) .pacz-header-wpml-ls,
#pacz-header:not(.header-structure-vertical) .pacz-header-wpml-ls a,
#pacz-header:not(.header-structure-vertical) .pacz-shopping-cart,
#pacz-header:not(.header-structure-vertical) .pacz-responsive-cart-link,
#pacz-header:not(.header-structure-vertical) .dashboard-trigger,
#pacz-header:not(.header-structure-vertical) .pacz-header-social,
#pacz-header:not(.header-structure-vertical) .pacz-margin-header-burger,
#pacz-header:not(.header-structure-vertical) .listing-btn,
#pacz-header:not(.header-structure-vertical) .logreg-header,
.theme-main-header .responsive-nav-link
{
	height:{$header_height}px;
	line-height:{$header_height}px;
}
");
}
if($pacz_settings['preset_headers'] == 12){
	Pacz_Static_Files::addGlobalStyle("
	#pacz-header:not(.header-structure-vertical){
		padding-top:25px;
		padding-bottom:25px;
	}
	.classiads-fantro-logo{
		min-height:1px;
	}
	.pacz-header-logo{
		margin:0 !important;
		position:absolute;
		top:50%;
		left:0;
		transform:translateY(-50%);
	}
	.logreg-header .dropdown{
		margin-top:-10px;
	}
	.logreg-header .dropdown .author-nicename{
		display:none;
	}
	.logreg-header .dropdown .author-displayname {
		font-size: 14px;
	}
	.search-form-style-header1 .listing-btn{float:right;}
	.search-form-style-header1 .listing-btn .listing-header-btn,
	.search-form-style-header1 .listing-btn .directorypress-new-listing-button .btn-primary,
	.search-form-style-header1 .listing-btn .submit-listing-button-single.btn-primary{
		font-size:14px;
		min-width:150px;
		line-height:44px;
		min-height:44px;
		border-radius:5px;
		margin-left:15px;
	}
");
}
Pacz_Static_Files::addGlobalStyle("
#pacz-header:not(.header-structure-vertical).sticky-trigger-header #pacz-main-navigation > ul > li.menu-item,
#pacz-header:not(.header-structure-vertical).sticky-trigger-header #pacz-main-navigation > ul > li.menu-item > a,
#pacz-header:not(.header-structure-vertical).sticky-trigger-header .pacz-header-search,
#pacz-header:not(.header-structure-vertical).sticky-trigger-header .pacz-header-search a,
#pacz-header:not(.header-structure-vertical).sticky-trigger-header .pacz-shopping-cart,
#pacz-header:not(.header-structure-vertical).sticky-trigger-header .pacz-responsive-cart-link,
#pacz-header:not(.header-structure-vertical).sticky-trigger-header .dashboard-trigger,
#pacz-header:not(.header-structure-vertical).sticky-trigger-header .pacz-header-social,
#pacz-header:not(.header-structure-vertical).sticky-trigger-header .pacz-margin-header-burger,
#pacz-header:not(.header-structure-vertical).sticky-trigger-header .pacz-header-wpml-ls,
#pacz-header:not(.header-structure-vertical).sticky-trigger-header .pacz-header-wpml-ls a,
#pacz-header:not(.header-structure-vertical).sticky-trigger-header .listing-btn,
#pacz-header:not(.header-structure-vertical).sticky-trigger-header .logreg-header
{
	height:{$header_sticky_height}px;
	line-height:{$header_sticky_height}px;
}
#pacz-header:not(.header-structure-vertical).header-style-v12.sticky-trigger-header #pacz-main-navigation > ul > li.menu-item,
#pacz-header:not(.header-structure-vertical).header-style-v12.sticky-trigger-header #pacz-main-navigation > ul > li.menu-item > a,
#pacz-header:not(.header-structure-vertical).header-style-v12.sticky-trigger-header .pacz-header-search,
#pacz-header:not(.header-structure-vertical).header-style-v12.sticky-trigger-header .pacz-header-search a,
#pacz-header:not(.header-structure-vertical).header-style-v12.sticky-trigger-header .pacz-shopping-cart,
#pacz-header:not(.header-structure-vertical).header-style-v12.sticky-trigger-header .pacz-responsive-cart-link,
#pacz-header:not(.header-structure-vertical).header-style-v12.sticky-trigger-header .dashboard-trigger,
#pacz-header:not(.header-structure-vertical).header-style-v12.sticky-trigger-header .pacz-header-social,
#pacz-header:not(.header-structure-vertical).header-style-v12.sticky-trigger-header .pacz-margin-header-burger,
#pacz-header:not(.header-structure-vertical).header-style-v12.sticky-trigger-header .pacz-header-wpml-ls,
#pacz-header:not(.header-structure-vertical).header-style-v12.sticky-trigger-header .pacz-header-wpml-ls a,
#pacz-header:not(.header-structure-vertical).header-style-v12.sticky-trigger-header .listing-btn 
{
	height:auto;
	line-height:inherit;
}
.main-navigation-ul a.pacz-login-2,
.main-navigation-ul a.pacz-logout-2,
.main-navigation-ul a.pacz-register-2{
	line-height:{$header_height}px;
	color:{$pacz_settings['main-nav-top-color']['regular']};
	background-color:{$pacz_settings['main-nav-top-color']['bg']};
	
}
.main-navigation-ul .logreg-header i{
	color:{$pacz_settings['main-nav-top-color']['regular']};
}
.main-navigation-ul a.pacz-login-2:hover,
.main-navigation-ul a.pacz-logout-2:hover,
.main-navigation-ul a.pacz-register-2:hover{
	line-height:{$header_height}px;
	color:{$pacz_settings['main-nav-top-color']['hover']};
	background-color:{$pacz_settings['main-nav-top-color']['bg-hover']};
	
}
.transparent-header:not(.sticky-trigger-header) .main-navigation-ul a.pacz-login-2,
.transparent-header:not(.sticky-trigger-header) .main-navigation-ul a.pacz-logout-2,
.transparent-header:not(.sticky-trigger-header) .main-navigation-ul a.pacz-register-2{
	line-height:{$header_height}px;
	color:{$pacz_settings['main-nav-top-color-transparent']['regular']};
	background-color:{$pacz_settings['main-nav-top-color-transparent']['bg']};
	
}
.transparent-header:not(.sticky-trigger-header) .main-navigation-ul .logreg-header .pacz-login-2-div,
.transparent-header:not(.sticky-trigger-header) .main-navigation-ul .logreg-header i{
	color:{$pacz_settings['main-nav-top-color-transparent']['regular']};
}
.transparent-header:not(.sticky-trigger-header) .main-navigation-ul a.pacz-login-2:hover,
.transparent-header:not(.sticky-trigger-header) .main-navigation-ul a.pacz-logout-2:hover,
.transparent-header:not(.sticky-trigger-header) .main-navigation-ul a.pacz-register-2:hover{
	line-height:{$header_height}px;
	color:{$pacz_settings['main-nav-top-color-transparent']['hover']};
	background-color:{$pacz_settings['main-nav-top-color-transparent']['bg-hover']};
	
}
");

	if (isset($pacz_settings['squeeze-sticky-header']) && ($pacz_settings['squeeze-sticky-header'])) {
		Pacz_Static_Files::addGlobalStyle("
	#pacz-header:not(.header-structure-vertical).sticky-trigger-header #pacz-main-navigation > ul > li.menu-item > a {
		padding-left:15px;
		padding-right:15px;
	}
	");
	}

	Pacz_Static_Files::addGlobalStyle(".pacz-header-logo,
.pacz-header-logo a{
	height:{$logo_height}px;
	line-height:{$logo_height}px;
}

#pacz-header:not(.header-structure-vertical).sticky-trigger-header .pacz-header-logo,
#pacz-header:not(.header-structure-vertical).sticky-trigger-header .pacz-header-logo a{
	height:{$sticky_logo_height}px;
	line-height:{$sticky_logo_height}px;
}

.vertical-expanded-state #pacz-header.header-structure-vertical,
.vertical-condensed-state  #pacz-header.header-structure-vertical:hover{
	width: {$header_vertical_width}px !important;
}

#pacz-header.header-structure-vertical{
	padding-left: {$header_vertical_padding}px !important;
	padding-right: {$header_vertical_padding}px !important;
}

.vertical-condensed-state .pacz-vertical-menu {
  width:{$vertical_nav_width}px;
}


.theme-main-wrapper.vertical-expanded-state #theme-page > .pacz-main-wrapper-holder,
.theme-main-wrapper.vertical-expanded-state #theme-page > .pacz-page-section,
.theme-main-wrapper.vertical-expanded-state #theme-page > .wpb_row,
.theme-main-wrapper.vertical-expanded-state #pacz-page-title,
.theme-main-wrapper.vertical-expanded-state #pacz-footer {
	padding-left: {$header_vertical_width}px;
}

@media handheld, only screen and (max-width:{$pacz_settings['res-nav-width']}px) {
	.theme-main-wrapper.vertical-expanded-state #theme-page > .pacz-main-wrapper-holder,
	.theme-main-wrapper.vertical-expanded-state #theme-page > .pacz-page-section,
	.theme-main-wrapper.vertical-expanded-state #theme-page > .wpb_row,
	.theme-main-wrapper.vertical-expanded-state #pacz-page-title,
	.theme-main-wrapper.vertical-expanded-state #pacz-footer,
	.theme-main-wrapper.vertical-condensed-state #theme-page > .pacz-main-wrapper-holder,
	.theme-main-wrapper.vertical-condensed-state #theme-page > .pacz-page-section,
	.theme-main-wrapper.vertical-condensed-state #theme-page > .wpb_row,
	.theme-main-wrapper.vertical-condensed-state #pacz-page-title,
	.theme-main-wrapper.vertical-condensed-state #pacz-footer {
		padding-left: 0px;
	}
	.pacz-header-logo{
		
	}
	.header-align-left .pacz-header-logo{
		left:30px;
		right:auto;
	}
	.header-align-right .pacz-header-logo{
		left:auto;
		right:30px;
	}

.pacz-header-logo a{
	height:{$resposive_logo_height}px;
	line-height:{$resposive_logo_height}px;
	margin-top: 0px !important;
	margin-bottom: 0px !important;
}

	
}

.theme-main-wrapper.vertical-header #pacz-page-title,
.theme-main-wrapper.vertical-header #pacz-footer,
.theme-main-wrapper.vertical-header #pacz-header,
.theme-main-wrapper.vertical-header #pacz-header.header-structure-vertical .pacz-vertical-menu{
	box-sizing: border-box;
}


@media handheld, only screen and (min-width:{$pacz_settings['res-nav-width']}px) {
	.vertical-condensed-state #pacz-header.header-structure-vertical:hover ~ #theme-page > .pacz-main-wrapper-holder,
	.vertical-condensed-state #pacz-header.header-structure-vertical:hover ~ #theme-page > .pacz-page-section,
	.vertical-condensed-state #pacz-header.header-structure-vertical:hover ~ #theme-page > .wpb_row,
	.vertical-condensed-state #pacz-header.header-structure-vertical:hover ~ #pacz-page-title,
	.vertical-condensed-state #pacz-header.header-structure-vertical:hover ~ #pacz-footer {
		padding-left: {$header_vertical_width}px ;
	}
}

.pacz-header-logo,
#pacz-header.header-style-v13 .search-form-style-header1-wrapper
 {
	margin-top: {$pacz_header_padding}px;
	margin-bottom: {$pacz_header_padding}px;
}


#pacz-header:not(.header-structure-vertical).sticky-trigger-header .pacz-header-logo,
#pacz-header:not(.header-structure-vertical).header-style-v13.sticky-trigger-header .search-form-style-header1-wrapper
{
	margin-top:{$sticky_header_padding}px;
	margin-bottom: {$sticky_header_padding}px;
}


#pacz-main-navigation > ul > li.menu-item > a {
	{$main_nav_top_level_space}
	{$main_nav_font_family}
	{$main_nav_top_level_font_size}
	{$main_nav_top_level_font_transform}
	{$main_nav_top_level_font_weight}
}

.pacz-header-logo.pacz-header-logo-center{
	{$main_nav_top_level_space}
}
#pacz-main-navigation > ul > li.pacz-shopping-cart {
	{$main_nav_top_level_space}
}
#pacz-main-navigation > ul > li.pacz-shopping-cart a.pacz-cart-link{
	{$cart_link_color_regular}
	{$cart_link_color_bg}
	{$cart_link_color_border}
}
#pacz-main-navigation > ul > li.pacz-shopping-cart a.pacz-cart-link:hover {
	{$cart_link_color_hover}
	{$cart_link_color_bghover}
	{$cart_link_color_borderhover}
}

.pacz-vertical-menu > li.menu-item > a {
	{$main_nav_top_level_space}
	{$main_nav_font_family}
	{$main_nav_top_level_font_size}
	{$main_nav_top_level_font_transform}
	{$main_nav_top_level_font_weight}
}
");

	if ($pacz_settings['header-structure'] == 'vertical') {
		Pacz_Static_Files::addGlobalStyle("
	.header-structure-vertical .pacz-vertical-menu > .menu-item > .sub-menu {
		{$main_nav_top_level_space_lr}
	}
	");
	}

	Pacz_Static_Files::addGlobalStyle("


.pacz-vertical-menu li.menu-item > a,
.pacz-vertical-menu .pacz-header-logo {
	{$nav_text_align} 
}

.main-navigation-ul > li ul.sub-menu li.menu-item a.menu-item-link{
	{$main_nav_sub_level_font_size}
	{$main_nav_sub_level_font_transform}
	{$main_nav_sub_level_font_weight}
}

.pacz-vertical-menu > li ul.sub-menu li.menu-item a{
	{$main_nav_sub_level_font_size}
	{$main_nav_sub_level_font_transform}
	{$main_nav_sub_level_font_weight}
}

#pacz-main-navigation > ul > li.menu-item > a,
.pacz-vertical-menu li.menu-item > a
{
	color:{$pacz_settings['main-nav-top-color']['regular']};
	background-color:{$pacz_settings['main-nav-top-color']['bg']};
}
.transparent-header:not(.sticky-trigger-header) #pacz-main-navigation > ul > li.menu-item > a,
.transparent-header:not(.sticky-trigger-header) .pacz-vertical-menu li.menu-item > a
{
	color:{$pacz_settings['main-nav-top-color-transparent']['regular']};
	background-color:{$pacz_settings['main-nav-top-color-transparent']['bg']};
}

#pacz-main-navigation > ul > li.current-menu-item > a,
#pacz-main-navigation > ul > li.current-menu-ancestor > a,
#pacz-main-navigation > ul > li.menu-item:hover > a
{
	color:{$pacz_settings['main-nav-top-color']['hover']};
	background-color:{$pacz_settings['main-nav-top-color']['bg-hover']};
}
.transparent-header:not(.sticky-trigger-header) #pacz-main-navigation > ul > li.current-menu-item > a,
.transparent-header:not(.sticky-trigger-header) #pacz-main-navigation > ul > li.current-menu-ancestor > a,
.transparent-header:not(.sticky-trigger-header) #pacz-main-navigation > ul > li.menu-item:hover > a
{
	color:{$pacz_settings['main-nav-top-color-transparent']['hover']};
	background-color:{$pacz_settings['main-nav-top-color-transparent']['bg-hover']};
}
.header-hover-style-1 .nav-hover-style1{
	bottom: {$pacz_settings['header-padding']}px;
    left: 0;
    line-height: 2px !important;
    margin: 0 -1.5px;
    position: absolute;
    right: 0;
}

.header-hover-style-1.sticky-trigger-header .nav-hover-style1{
	bottom: {$header_hover_style1_padding}px;
}

.header-hover-style-1 .nav-hover-style1 span{
		margin:0 1.5px;
		display:inline-block;
		width:8px;
		height:2px;
		background:{$pacz_settings['main-nav-top-color']['hover']};
}
.transparent-header:not(.sticky-trigger-header) .header-hover-style-1 .nav-hover-style1 span{
	background:{$pacz_settings['main-nav-top-color-transparent']['hover']};
}
.header-hover-style-1 .sub-menu .nav-hover-style1{display:none;}
.pacz-vertical-menu > li.current-menu-item > a,
.pacz-vertical-menu > li.current-menu-ancestor > a,
.pacz-vertical-menu > li.menu-item:hover > a,
.pacz-vertical-menu ul li.menu-item:hover > a {
	color:{$pacz_settings['main-nav-top-color']['hover']};
}



#pacz-main-navigation > ul > li.menu-item > a:hover
{
	color:{$pacz_settings['main-nav-top-color']['hover']};
	background-color:{$pacz_settings['main-nav-top-color']['bg-hover']};
}

.dashboard-trigger,
.res-nav-active,
.pacz-responsive-cart-link {
	color:{$pacz_settings['main-nav-top-color']['regular']};
}

.dashboard-trigger:hover,
.res-nav-active:hover {
	color:{$pacz_settings['main-nav-top-color']['hover']};
}

.transparent-header:not(.sticky-trigger-header) #pacz-main-navigation > ul > li.menu-item > a:hover
{
	color:{$pacz_settings['main-nav-top-color-transparent']['hover']};
	background-color:{$pacz_settings['main-nav-top-color-transparent']['bg-hover']};
}

.transparent-header:not(.sticky-trigger-header) .dashboard-trigger,
.transparent-header:not(.sticky-trigger-header) .pacz-responsive-cart-link {
	color:{$pacz_settings['main-nav-top-color-transparent']['regular']};
}

.transparent-header:not(.sticky-trigger-header) .dashboard-trigger:hover{
	color:{$pacz_settings['main-nav-top-color-transparent']['hover']};
}

");

if (isset($pacz_settings['navigation-border-top']) && ($pacz_settings['navigation-border-top'] == 1)) {
		Pacz_Static_Files::addGlobalStyle("
		#pacz-main-navigation ul li.no-mega-menu > ul,
		#pacz-main-navigation ul li.has-mega-menu > ul,
		#pacz-main-navigation ul li.pacz-header-wpml-ls > ul{
			border-top:1px solid {$accent_color};
		}");
}


Pacz_Static_Files::addGlobalStyle("#pacz-main-navigation ul li.no-mega-menu ul,
#pacz-main-navigation > ul > li.has-mega-menu > ul,
.header-searchform-input .ui-autocomplete,
.pacz-shopping-box,
.shopping-box-header > span,
#pacz-main-navigation ul li.pacz-header-wpml-ls > ul {
	background-color:{$pacz_settings['main-nav-sub-bg']};
}

#pacz-main-navigation ul ul.sub-menu a.menu-item-link,
#pacz-main-navigation ul li.pacz-header-wpml-ls > ul li a
{
	color:{$pacz_settings['main-nav-sub-color']['regular']};
}

#pacz-main-navigation ul ul.sub-menu a.menu-item-link,
#pacz-main-navigation ul li.pacz-header-wpml-ls > ul li a
{
	color:{$pacz_settings['main-nav-sub-color']['regular']};
}

#pacz-main-navigation ul ul li.current-menu-item > a.menu-item-link,
#pacz-main-navigation ul ul li.current-menu-ancestor > a.menu-item-link {
	color:{$pacz_settings['main-nav-sub-color']['hover']};
	background-color:{$pacz_settings['main-nav-sub-color']['bg-active']} !important;
}


.header-searchform-input .ui-autocomplete .search-title,
.header-searchform-input .ui-autocomplete .search-date,
.header-searchform-input .ui-autocomplete i
{
	color:{$pacz_settings['main-nav-sub-color']['regular']};
}
.header-searchform-input .ui-autocomplete i,
.header-searchform-input .ui-autocomplete img
{
	border-color:{$pacz_settings['main-nav-sub-color']['regular']};
}

.header-searchform-input .ui-autocomplete li:hover  i,
.header-searchform-input .ui-autocomplete li:hover img
{
	border-color:{$pacz_settings['main-nav-sub-color']['hover']};
}


#pacz-main-navigation .megamenu-title,
.pacz-mega-icon,
.pacz-shopping-box .mini-cart-title,
.pacz-shopping-box .mini-cart-button {
	color:{$pacz_settings['main-nav-sub-color']['regular']};
}

#pacz-main-navigation ul ul.sub-menu a.menu-item-link:hover,
.header-searchform-input .ui-autocomplete li:hover,
#pacz-main-navigation ul li.pacz-header-wpml-ls > ul li a:hover
{
	color:{$pacz_settings['main-nav-sub-color']['hover']};
	background-color:{$pacz_settings['main-nav-sub-color']['bg-hover']} !important;
}

.header-searchform-input .ui-autocomplete li:hover .search-title,
.header-searchform-input .ui-autocomplete li:hover .search-date,
.header-searchform-input .ui-autocomplete li:hover i,
#pacz-main-navigation ul ul.sub-menu a.menu-item-link:hover i
{
	color:{$pacz_settings['main-nav-sub-color']['hover']};
}


.header-searchform-input input[type=text],
.dashboard-trigger,
.header-search-icon,
.header-search-close,
.header-wpml-icon
{
	color:{$pacz_settings['main-nav-top-color']['regular']};
}
.transparent-header:not(.sticky-trigger-header) .header-searchform-input input[type=text],
.transparent-header:not(.sticky-trigger-header) .dashboard-trigger,
.transparent-header:not(.sticky-trigger-header) .header-search-icon,
.transparent-header:not(.sticky-trigger-header) .header-search-close,
.transparent-header:not(.sticky-trigger-header) .header-wpml-icon
{
	color:{$pacz_settings['main-nav-top-color-transparent']['regular']};
}

");

$header_search_icon_color = (isset($pacz_settings['header-search-icon-color']) && !empty($pacz_settings['header-search-icon-color'])) ? $pacz_settings['header-search-icon-color'] : $pacz_settings['main-nav-top-color']['regular'];

Pacz_Static_Files::addGlobalStyle("
.header-search-icon {
	color:{$header_search_icon_color};	
}

.pacz-burger-icon div {
      background-color:{$pacz_settings['main-nav-top-color']['regular']};
 }



.header-search-icon:hover
{
	color: {$pacz_settings['main-nav-top-color']['regular']};
}


.responsive-shopping-box
{
	background-color:{$pacz_settings['main-nav-sub-bg']};
}

.pacz-responsive-nav a,
.pacz-responsive-nav .has-mega-menu .megamenu-title
{
	color:#fff;
	background-color:{$pacz_settings['main-nav-sub-color']['bg']};
}

");

$header_border_bottom_color = (isset($pacz_settings['toolbar-border-bottom-color']) && !empty($pacz_settings['toolbar-border-bottom-color'])) ? $pacz_settings['toolbar-border-bottom-color'] : 'transparent';
$header_phone_email_icon_color = (isset($pacz_settings['toolbar-phone-email-icon-color']) && !empty($pacz_settings['toolbar-phone-email-icon-color'])) ? $pacz_settings['toolbar-phone-email-icon-color'] : $pacz_settings['toolbar-text-color'];
if(isset($pacz_settings['toolbar-grid']) && $pacz_settings['toolbar-grid'] == 1){
Pacz_Static_Files::addGlobalStyle("
.pacz-header-toolbar {
	padding-left:50px;
	padding-right:50px;
}
");	
}

$social_link_bg = (isset($pacz_settings['toolbar-social-link-color-bg']['rgba'])) ? $pacz_settings['toolbar-social-link-color-bg']['rgba'] : '';
$social_link_bg_hover = (isset($pacz_settings['toolbar-social-link-color']['bg-hover'])) ? $pacz_settings['toolbar-social-link-color']['bg-hover'] : '';
Pacz_Static_Files::addGlobalStyle("
.pacz-header-toolbar {
	{$toolbar_border}	
	
	border-color:{$header_border_bottom_color};
}
.pacz-header-toolbar span {
	color:{$pacz_settings['toolbar-text-color']};	
}

.pacz-header-toolbar span i {
	color:{$header_phone_email_icon_color};	
}

.pacz-header-toolbar a,
.header-toolbar-log-reg-btn i.pacz-flaticon-user73,
.header-toolbar-log-reg-btn span{
	color:{$pacz_settings['toolbar-link-color']['regular']};	
}
.pacz-header-toolbar a:hover{
	color:{$pacz_settings['toolbar-link-color']['hover']};	
}

.pacz-header-toolbar a{
	color:{$pacz_settings['toolbar-link-color']['regular']};	
}
.pacz-header-toolbar .pacz-header-toolbar-social li a,
.pacz-header-social a{
	color:{$pacz_settings['toolbar-social-link-color']['regular']} !important;	
	background-color:{$social_link_bg};	
}
.pacz-header-toolbar .pacz-header-toolbar-social li a:hover,
.pacz-header-social a:hover{
	color:{$pacz_settings['toolbar-social-link-color']['hover']} !important;
	background-color:{$social_link_bg_hover};	
}

.single-listing .modal-dialog {
	margin-top:{$header_height}px;	
}
");

###########################################
	# Responsive Mode
	###########################################

	$grid_width_100 = $pacz_settings['grid-width']+100;

	Pacz_Static_Files::addGlobalStyle("

@media handheld, only screen and (max-width: {$grid_width_100}px)
{

.dashboard-trigger.res-mode {
	display:block !important;
}

.dashboard-trigger.desktop-mode {
	display:none !important;
}

}



@media only screen and (max-width: {$pacz_settings['res-nav-width']}px)
{

#pacz-header.sticky-header,
.pacz-secondary-header,
.transparent-header-sticky {
	position: relative !important;
	left:auto !important;
    right:auto!important;
    top:auto !important;
}

#pacz-header:not(.header-structure-vertical).put-header-bottom,
#pacz-header:not(.header-structure-vertical).put-header-bottom.sticky-trigger-header,
#pacz-header:not(.header-structure-vertical).put-header-bottom.header-offset-passed,
.admin-bar #pacz-header:not(.header-structure-vertical).put-header-bottom.sticky-trigger-header {
	position:relative;
	bottom:auto;
}

.pacz-margin-header-burger {
	display:none;
}

.main-navigation-ul li.menu-item,
.pacz-vertical-menu li.menu-item,
.main-navigation-ul li.sub-menu,
.sticky-header-padding,
.secondary-header-space
{
	display:none !important;
}
.theme-main-header .responsive-nav-link {
    display: inline-block;
}
.vertical-expanded-state #pacz-header.header-structure-vertical, .vertical-condensed-state #pacz-header.header-structure-vertical{
	width: 100% !important;
	height: auto !important;
}
.vertical-condensed-state  #pacz-header.header-structure-vertical:hover {
	width: 100% !important;
}
.header-structure-vertical .pacz-vertical-menu{
	position:relative;
	padding:0;
	width: 100%;
}
.header-structure-vertical .pacz-header-social.inside-grid{
	position:relative;
	padding:0;
	width: auto;
	bottom: inherit !important;
	height:{$header_height}px;
	line-height:{$header_height}px;
	float:right !important;
	top: 0 !important;
}
/*
.pacz-header-logo, .pacz-header-logo a {
	height:80px;
	line-height:80px;
}
#menu-main-navigation .pacz-header-logo {
	margin-bottom:20px;
	
}
.pacz-vertical-menu .responsive-nav-link {
	height:120px !important;
}
.pacz-vertical-header-burger {
	display:none!important;
}

.header-structure-vertical .pacz-header-social.inside-grid {
	height:120px;
	line-height:120px;
}
*/

.vertical-condensed-state .header-structure-vertical .pacz-vertical-menu>li.pacz-header-logo {
	-webkit-transform: translate(0,0);
	-moz-transform: translate(0,0);
	-ms-transform: translate(0,0);
	-o-transform: translate(0,0);
	opacity: 1!important;
	position: relative!important;
	left: 0!important;
}
.vertical-condensed-state .header-structure-vertical .pacz-vertical-header-burger{
	opacity:0 !important;
}


.pacz-header-logo {
	padding:0 !important;
}

.pacz-vertical-menu .responsive-nav-link{
	float:left !important;
	height:{$header_height}px;
}
.pacz-vertical-menu .responsive-nav-link i{
	height:{$header_height}px;
	line-height:{$header_height}px;
}
.pacz-vertical-menu .pacz-header-logo {
	float:left !important
}


.header-search-icon i,
.pacz-cart-link i{
	padding:0 !important;
	margin:0 !important;
	border:none !important;
}

.header-search-icon,
.pacz-cart-link{
	margin:0 8px !important;
	padding:0 !important;
}


.pacz-header-logo
{

	margin-left:20px !important;
	display:inline-block !important;
}


.main-navigation-ul
{
	text-align:center;
}
.header-align-left .main-navigation-ul{
	text-align:right;
}
.responsive-nav-link {
	display:inline-block !important;
}

.pacz-shopping-box {
	display:none !important;
}
.pacz-shopping-cart{
	display:none !important;
}
.pacz-responsive-shopping-cart{
	display: inline-block !important;
}

}


#pacz-header.transparent-header {
  position: absolute;
  left: 0;
}

.pacz-boxed-enabled #pacz-header.transparent-header {
  left: inherit;
}

.add-corner-margin .pacz-boxed-enabled #pacz-header.transparent-header {
  left: 0;
}

.transparent-header {
  transition: all 0.3s ease-in-out;
  -webkit-transition: all 0.3s ease-in-out;
  -moz-transition: all 0.3s ease-in-out;
  -ms-transition: all 0.3s ease-in-out;
  -o-transition: all 0.3s ease-in-out;
}

.transparent-header.transparent-header-sticky {
  opacity: 1;
  left: auto !important;
}
.transparent-header #pacz-main-navigation ul li .sub {
  border-top: none;
}
.transparent-header .pacz-cart-link:hover,
.transparent-header .pacz-responsive-cart-link:hover,
.transparent-header .dashboard-trigger:hover,
.transparent-header .res-nav-active:hover,
.transparent-header .header-search-icon:hover {
  opacity: 0.7;
}
.transparent-header .header-searchform-input input[type=text] {
  background-color: transparent;
}
.transparent-header.light-header-skin .dashboard-trigger,
.transparent-header.light-header-skin .dashboard-trigger:hover,
.transparent-header.light-header-skin .res-nav-active,
.transparent-header.light-header-skin #pacz-main-navigation > ul > li.menu-item > a,
.transparent-header.light-header-skin #pacz-main-navigation > ul > li.current-menu-item > a,
.transparent-header.light-header-skin #pacz-main-navigation > ul > li.current-menu-ancestor > a,
.transparent-header.light-header-skin #pacz-main-navigation > ul > li.menu-item:hover > a,
.transparent-header.light-header-skin #pacz-main-navigation > ul > li.menu-item > a:hover,
.transparent-header.light-header-skin .res-nav-active:hover,
.transparent-header.light-header-skin .header-searchform-input input[type=text],
.transparent-header.light-header-skin .header-search-icon,
.transparent-header.light-header-skin .header-search-close,
.transparent-header.light-header-skin .header-search-icon:hover,
.transparent-header.light-header-skin .pacz-cart-link,
.transparent-header.light-header-skin .pacz-responsive-cart-link,
.transparent-header.light-header-skin .pacz-header-social a,
.transparent-header.light-header-skin .pacz-header-wpml-ls a{
  color: #fff;
}
.transparent-header.light-header-skin .pacz-burger-icon div {
  background-color: #fff;
}
.transparent-header.light-header-skin .pacz-light-logo {
  display: inline-block !important;
}
.transparent-header.light-header-skin .pacz-dark-logo {
  
}
.transparent-header.light-header-skin.transparent-header-sticky .pacz-light-logo {
  display: none !important;
}
.transparent-header.light-header-skin.transparent-header-sticky .pacz-dark-logo {
  display: inline-block !important;
}

");

	###########################################
	# Accent Color
	###########################################


	Pacz_Static_Files::addGlobalStyle("
.pacz-skin-color,
.rating-star .rated,
.widget_testimonials .testimonial-position,
.entry-meta .cats a,
.search-meta span a,
.search-meta span,
.single-share-trigger:hover,
.single-share-trigger.pacz-toggle-active,
.project_content_section .project_cats a,
.pacz-love-holder i:hover,
.blog-comments span,
.comment-count i:hover,
.widget_posts_lists li .cats a,
.pacz-tweet-shortcode span a,
.pacz-pricing-table .pacz-icon-star,
.pacz-process-steps.dark-skin .step-icon,
.pacz-sharp-next,
.pacz-sharp-prev,
.prev-item-caption,
.next-item-caption,
.pacz-employees.column_rounded-style .team-member-position, 
.pacz-employees.column-style .team-member-position,
.pacz-employees .team-info-wrapper .team-member-position,
.pacz-event-countdown.accent-skin .countdown-timer,
.pacz-event-countdown.accent-skin .countdown-text,
.pacz-box-text:hover i,
.pacz-process-steps.light-skin .pacz-step:hover .step-icon,
.pacz-process-steps.light-skin .active-step-item .step-icon,
.blog-tile-entry time a,
#login-register-password .userid:before,
#login-register-password .userpass:before,
#login-register-password .useremail:before,
#login-register-password .userfname:before,
#login-register-password .userlname:before,
.radio-check-item:before,
.reg-page-link
{
	color: {$accent_color};
}

.form-inner input.user-submit{
	background: {$accent_color} ;
	color:#fff;
}
.form-inner input.user-submit:hover{
	background: {$third_color} ;
	color:#fff;
}


.blog-thumb-entry .blog-thumb-content .blog-thumb-content-inner a.blog-readmore:hover:before,
.blog-thumb-entry.two-column  .blog-thumb-content .blog-thumb-metas:before{
	background: {$accent_color} ;
}
.pacz-employeee-networks li a:hover {
	background: {$accent_color} ;
	border-color: {$accent_color} !important;
	
}
.pacz-testimonial.creative-style .slide{
	
	
}
.pacz-testimonial.boxed-style .testimonial-content{
	border-bottom:2px solid {$accent_color} !important;
	
}
.pacz-testimonial.modern-style .slide{
	
	
}
.testimonial3-style .owl-dot.active span,
.testimonial4-style .owl-dot.active span{background: {$accent_color} !important;}
.pacz-testimonial.modern-style .slide .author-details .testimonial-position,
.pacz-testimonial.modern-style .slide .author-details .testimonial-company{
	color: {$accent_color} !important;
	
}
.pacz-love-holder .item-loved i,
.widget_posts_lists .cats a,
#pacz-breadcrumbs a:hover,
.widget_social_networks a.light,
.widget_posts_tabs .cats a {
	color: {$accent_color} !important;
}

a:hover,
.pacz-tweet-shortcode span a:hover {
	color:{$pacz_settings['link-color']['hover']};
}

.blog-meta time a,
.entry-meta time a,
.entry-meta .entry-categories a,
.blog-author span,
.blog-comments span,
.blog-categories a,
.blog-comments{
	color:{$pacz_settings['link-color']['regular']};
}
.blog-meta time a:hover,
.entry-meta time a:hover,
.entry-meta .entry-categories a:hover,
.blog-author span:hover,
.blog-comments span:hover,
.blog-categories a:hover,
.blog-comments{
	color:{$pacz_settings['link-color']['hover']};
}

/* Main Skin Color : Background-color Property */

div.jp-play-bar,
.pacz-header-button:hover,
.next-prev-top .go-to-top:hover,
.masonry-border,
.author-social li a:hover,
.slideshow-swiper-arrows:hover,
.pacz-clients-shortcode .clients-info,
.pacz-contact-form-wrapper .pacz-form-row i.input-focused,
.pacz-login-form .form-row i.input-focused,
.comment-form-row i.input-focused,
.widget_social_networks a:hover,
.pacz-social-network a:hover,
.blog-masonry-entry .post-type-icon:hover,
.list-posttype-col .post-type-icon:hover,
.single-type-icon,
.demo_store,
.add_to_cart_button:hover,
.pacz-process-steps.dark-skin .pacz-step:hover .step-icon,
.pacz-process-steps.dark-skin .active-step-item .step-icon,
.pacz-process-steps.light-skin .step-icon,
.pacz-social-network a.light:hover,
.widget_tag_cloud a:hover,
.widget_categories a:hover,
.sharp-nav-bg,
.gform_wrapper .button:hover,
.pacz-event-countdown.accent-skin li:before,
.masonry-border,
.pacz-gallery.thumb-style .gallery-thumb-lightbox:hover,
.fancybox-close:hover,
.fancybox-nav span:hover,
.blog-scroller-arrows:hover,
ul.user-login li a i,
.pacz-isotop-filter ul li a.current,
.pacz-isotop-filter ul li a:hover
{
	border-color: {$accent_color};
	color: {$accent_color};
}




::-webkit-selection
{
	background-color: {$accent_color};
	color:#fff;
}

::-moz-selection
{
	background-color: {$accent_color};
	color:#fff;
}

::selection
{
	background-color: {$accent_color};
	color:#fff;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
  border-color: {$primary_rgba_color_40};
  background: {$primary_rgba_color_10};
}

.next-prev-top .go-to-top,
.pacz-contact-form-wrapper .text-input:focus, .pacz-contact-form-wrapper .pacz-textarea:focus,
.widget .pacz-contact-form-wrapper .text-input:focus, .widget .pacz-contact-form-wrapper .pacz-textarea:focus,
.pacz-contact-form-wrapper .pacz-form-row i.input-focused,
.comment-form-row .text-input:focus, .comment-textarea textarea:focus,
.comment-form-row i.input-focused,
.pacz-login-form .form-row i.input-focused,
.pacz-login-form .form-row input:focus,
.pacz-event-countdown.accent-skin li
{
	border-color: {$accent_color}!important;
}
.pacz-go-top {background-color:{$third_color};}

#wpadminbar {
  
}

");


if (isset($pacz_settings['sub-footer-border-top']) && ($pacz_settings['sub-footer-border-top'] == 1)) {
	$subfooter_border_top_color = (isset($pacz_settings['sub-footer-border-top-color']['rgba']))? $pacz_settings['sub-footer-border-top-color']['rgba'] : '';
	Pacz_Static_Files::addGlobalStyle("
	#sub-footer .pacz-grid{
		border-top:1px solid {$subfooter_border_top_color};
	}");
}


###########################################
	# Accent Color
	###########################################
	
	Pacz_Static_Files::addGlobalStyle("
.dynamic-btn{
		background-color:{$accent_color} !important;
		border-color:{$accent_color} !important;
		color:#fff !important;
	}
.dynamic-btn:hover{
		background-color:{$third_color} !important;
		border-color:{$third_color} !important;
		color:#fff !important;
	}
	
	
	
	
	
	");
###########################################
# MISC
###########################################
global $post;
//$post_id = global_get_post_id();
	$stick_template = get_post_meta($post_id, '_padding', true);
if(is_page() && !has_shortcode($post->post_content, 'vc_row')){
	Pacz_Static_Files::addGlobalStyle("
	.theme-content:not(.no-padding) {padding:70px 0;}
	");
}
if($pacz_settings['header-grid'] && (is_page() || pacz_is_default_pages() || ($post_id && !$stick_template)) && !is_front_page()){
	Pacz_Static_Files::addGlobalStyle("
		.theme-page-wrapper{
			padding-top:{$header_height}px;
		}
	");
}
Pacz_Static_Files::addGlobalStyle("
.widget_author .classiadspro-author.style2 .author-social-follow-ul li a:hover{
	background-color:{$accent_color};
	color:#fff !important;
}
.pacz-divider .divider-inner i
{
	background-color: {$pacz_settings['page-bg']['background-color']};
}
.pacz-body-loader-overlay {
	background-color: {$pacz_settings['preloader-bg-color']};
}
.pacz-loader
{
	border: 2px solid {$accent_color};
}
.progress-bar.bar .bar-tip {
	color:{$accent_color};
	
}
.custom-color-heading{
	color:{$accent_color};
	
}

.alt-title span,
.single-post-fancy-title span
{
	
}

.pacz-box-icon .pacz-button-btn a.pacz-button:hover {
	background-color:{$accent_color};
	border-color:{$accent_color};
}


 
.ls-btn1:hover{
	color:{$accent_color} !important;
}
.pacz-commentlist li .comment-author a{
	font-weight:400 !important;
	color:{$pacz_settings['heading-color']} !important;
	{$heading_font_family_h5}
}

.form-submit #submit {
  color:#fff;
  background-color:{$accent_color};
}
.form-submit #submit:hover {
  background-color:{$third_color};
}

.pacz-loadmore-button:hover {
  background-color:{$accent_color} !important;
	color:#fff !important;
}
.pacz-searchform .pacz-icon-search:hover {
  background-color:{$accent_color} !important;
  color:#fff;
}
.footer-sell-btn a{
	background-color:{$accent_color};
}
.footer-sell-btn a:hover{
	background-color:{$third_color};
}
");

###########################################
# Sidebar
###########################################
	
	$sidebar_widget_background = (isset($pacz_settings['sidebar-widget-background-color']) && !empty($pacz_settings['sidebar-widget-background-color']))? ('background-color:'. $pacz_settings['sidebar-widget-background-color'] .';') : '';
	$sidebar_widget_border_color = (isset($pacz_settings['sidebar-widget-border']) && !empty($pacz_settings['sidebar-widget-border']['border-color']))? ('border-color: '. $pacz_settings['sidebar-widget-border']['border-color'] .';') : '';
	$sidebar_widget_border_style = (isset($pacz_settings['sidebar-widget-border']) && !empty($pacz_settings['sidebar-widget-border']['border-style']))? ('border-style: '. $pacz_settings['sidebar-widget-border']['border-style'] .';') : '';
	$sidebar_widget_border_top = (isset($pacz_settings['sidebar-widget-border']) && !empty($pacz_settings['sidebar-widget-border']['border-top']))? ('border-top-width: '. $pacz_settings['sidebar-widget-border']['border-top'] .';') : '';
	$sidebar_widget_border_bottom = (isset($pacz_settings['sidebar-widget-border']) && !empty($pacz_settings['sidebar-widget-border']['border-bottom']))? ('border-bottom-width: '. $pacz_settings['sidebar-widget-border']['border-bottom'] .';') : '';
	$sidebar_widget_border_right = (isset($pacz_settings['sidebar-widget-border']) && !empty($pacz_settings['sidebar-widget-border']['border-right']))? ('border-right-width: '. $pacz_settings['sidebar-widget-border']['border-right'] .';') : '';
	$sidebar_widget_border_left = (isset($pacz_settings['sidebar-widget-border']) && !empty($pacz_settings['sidebar-widget-border']['border-left']))? ('border-left-width: '. $pacz_settings['sidebar-widget-border']['border-left'] .';') : '';
	$sidebar_widget_radius = (isset($pacz_settings['sidebar-widget-border-radius']) && !empty($pacz_settings['sidebar-widget-border-radius']))? ('border-radius: '. $pacz_settings['sidebar-widget-border-radius'] .'px;') : '';
	
	
	Pacz_Static_Files::addGlobalStyle("
		#pacz-sidebar .widget{
			{$sidebar_widget_background}
			{$sidebar_widget_border_color}
			{$sidebar_widget_border_style}
			{$sidebar_widget_border_top}
			{$sidebar_widget_border_bottom}
			{$sidebar_widget_border_right}
			{$sidebar_widget_border_left}
			{$sidebar_widget_radius}
		}
	");
	if(isset($pacz_settings['sidebar-widget-box-shadow'])){
		
		$sidebar_widget_box_shadow = $pacz_settings['sidebar-widget-box-shadow']['drop-shadow'];
		$sidebar_widget_box_shadow_color = $sidebar_widget_box_shadow['color'];
		$sidebar_widget_box_shadow_horizontal = ($sidebar_widget_box_shadow['horizontal'] != 0)? $sidebar_widget_box_shadow['horizontal'] .'px' : 0;
		$sidebar_widget_box_shadow_vertical = ($sidebar_widget_box_shadow['vertical'] != 0)? $sidebar_widget_box_shadow['vertical'] .'px' : 0;
		$sidebar_widget_box_shadow_blur = ($sidebar_widget_box_shadow['blur'] != 0)? $sidebar_widget_box_shadow['blur'] .'px' : 0;
		$sidebar_widget_box_shadow_spread = ($sidebar_widget_box_shadow['spread'] != 0)? $sidebar_widget_box_shadow['spread'] .'px' : 0;
		if(!empty($sidebar_widget_box_shadow_color)){
			$sidebar_widget_box_shadow_css = $sidebar_widget_box_shadow_horizontal .' '. $sidebar_widget_box_shadow_vertical .' '. $sidebar_widget_box_shadow_blur .' '. $sidebar_widget_box_shadow_spread .' '. $sidebar_widget_box_shadow_color;
			Pacz_Static_Files::addGlobalStyle("
				#pacz-sidebar .widget{
					box-shadow: {$sidebar_widget_box_shadow_css};
					-webkit-box-shadow: {$sidebar_widget_box_shadow_css};
					-moz-box-shadow: {$sidebar_widget_box_shadow_css};
					-o-box-shadow: {$sidebar_widget_box_shadow_css};
				}
			");
		}
	}
	
###########################################
	# Widgets
###########################################

	
	Pacz_Static_Files::addGlobalStyle("
		
		.widgettitle{
			{$widget_font_family}
			{$widget_font_size}
			{$widget_font_weight}
		}
		.widgettitle:after{
			{$widget_title_divider}
		}
		
		#pacz-sidebar .widgettitle,
		#pacz-sidebar .widgettitle  a{
			color: {$pacz_settings['sidebar-title-color']};
		}
		#pacz-sidebar,
		#pacz-sidebar p{
			color: {$pacz_settings['sidebar-txt-color']};
		}
		#pacz-sidebar a{
			color: {$pacz_settings['sidebar-link-color']['regular']};
		}
		#pacz-sidebar a:hover{
			color: {$pacz_settings['sidebar-link-color']['hover']};
		}
		#pacz-sidebar .widget_posts_lists ul li .post-list-title{
			color:{$pacz_settings['heading-color']};	
		}
		#pacz-sidebar .widget_archive ul li a:before,
		#pacz-sidebar .widget_categories a:before{
			color:{$pacz_settings['accent-color']};	
		}
		#pacz-sidebar .widgettitle:before {
			background-color:{$pacz_settings['accent-color']};	
		}
		.pacz-native-search-button,
		.wp-block-search .wp-block-search__button{
			background-color:{$pacz_settings['accent-color']};
			border-color:{$pacz_settings['accent-color']};
		}
		.pacz-native-search-button:hover,
		.wp-block-search .wp-block-search__button:hover{
			background-color:{$third_color};
			border-color:{$third_color};
		}
	");
###########################################
	# Post
	###########################################
	$single_post_content_box_background = (isset($pacz_settings['single-post-content-box-background']) && !empty($pacz_settings['single-post-content-box-background']))? ('background-color:'. $pacz_settings['single-post-content-box-background'] .';') : '';
	$single_post_content_box_border_color = (isset($pacz_settings['single-post-content-box-border']) && !empty($pacz_settings['single-post-content-box-border']['border-color']))? ('border-color: '. $pacz_settings['single-post-content-box-border']['border-color'] .';') : '';
	$single_post_content_box_border_style = (isset($pacz_settings['single-post-content-box-border']) && !empty($pacz_settings['single-post-content-box-border']['border-style']))? ('border-style: '. $pacz_settings['single-post-content-box-border']['border-style'] .';') : '';
	$single_post_content_box_border_top = (isset($pacz_settings['single-post-content-box-border']) && !empty($pacz_settings['single-post-content-box-border']['border-top']))? ('border-top-width: '. $pacz_settings['single-post-content-box-border']['border-top'] .';') : '';
	$single_post_content_box_border_bottom = (isset($pacz_settings['single-post-content-box-border']) && !empty($pacz_settings['single-post-content-box-border']['border-bottom']))? ('border-bottom-width: '. $pacz_settings['single-post-content-box-border']['border-bottom'] .';') : '';
	$single_post_content_box_border_right = (isset($pacz_settings['single-post-content-box-border']) && !empty($pacz_settings['single-post-content-box-border']['border-right']))? ('border-right-width: '. $pacz_settings['single-post-content-box-border']['border-right'] .';') : '';
	$single_post_content_box_border_left = (isset($pacz_settings['single-post-content-box-border']) && !empty($pacz_settings['single-post-content-box-border']['border-left']))? ('border-left-width: '. $pacz_settings['single-post-content-box-border']['border-left'] .';') : '';
	$single_post_content_box_radius = (isset($pacz_settings['single-post-content-box-border-radius']) && !empty($pacz_settings['single-post-content-box-border-radius']))? ('border-radius: '. $pacz_settings['single-post-content-box-border-radius'] .'px;') : '';
	
	$single_post_comments_box_background = (isset($pacz_settings['single-post-comments-box-background']) && !empty($pacz_settings['single-post-comments-box-background']))? ('background-color:'. $pacz_settings['single-post-comments-box-background'] .';') : '';
	Pacz_Static_Files::addGlobalStyle("
		.pacz-post-container .sticky-post:not(.pacz-post-tile):not(.pacz-post-grid-mod) .pacz-post-content-wrapper,
		.pacz-post-container .sticky-post.pacz-post-tile,
		.pacz-post-container .sticky-post.pacz-post-grid-mod .pacz-post-thumbnail-wrapper{
			background-color:{$primary_rgba_color_10};
		}
		.pacz-post-container .sticky-post .pacz-post-content-wrapper{
			border-color:{$primary_rgba_color_10};
		}
		.pacz-post-single .pacz-post-single-content-wrapper,
		.comment-respond form,
		#pacz-post-comments .pacz-commentlist .comment .pacz-single-comment,
		.pacz-post-single-author-box{
			{$single_post_content_box_background}
			{$single_post_content_box_border_color}
			{$single_post_content_box_border_style}
			{$single_post_content_box_border_top}
			{$single_post_content_box_border_bottom}
			{$single_post_content_box_border_right}
			{$single_post_content_box_border_left}
			{$single_post_content_box_radius}
		}
		.pacz-post-single-author-box .pacz-post-single-author-img{
			{$single_post_content_box_border_color}
		}
		.pacz-post-single .pacz-post-single-content-wrapper{
			border-top-left-radius: 0;
			border-top-right-radius:0;
		}
		#pacz-post-comments .pacz-commentlist .comment .pacz-single-comment,
		#pacz-post-comments .comment-respond form{
			{$single_post_comments_box_background}
		}
	");
	if(isset($pacz_settings['single-post-content-box-shadow'])){
		
		$single_post_content_box_shadow = $pacz_settings['single-post-content-box-shadow']['drop-shadow'];
		$single_post_content_box_shadow_color = $single_post_content_box_shadow['color'];
		$single_post_content_box_shadow_horizontal = ($single_post_content_box_shadow['horizontal'] != 0)? $single_post_content_box_shadow['horizontal'] .'px' : 0;
		$single_post_content_box_shadow_vertical = ($single_post_content_box_shadow['vertical'] != 0)? $single_post_content_box_shadow['vertical'] .'px' : 0;
		$single_post_content_box_shadow_blur = ($single_post_content_box_shadow['blur'] != 0)? $single_post_content_box_shadow['blur'] .'px' : 0;
		$single_post_content_box_shadow_spread = ($single_post_content_box_shadow['spread'] != 0)? $single_post_content_box_shadow['spread'] .'px' : 0;
		
		if(!empty($single_post_content_box_shadow_color)){
			$single_post_content_box_shadow_css = $single_post_content_box_shadow_horizontal .' '. $single_post_content_box_shadow_vertical .' '. $single_post_content_box_shadow_blur .' '. $single_post_content_box_shadow_spread .' '. $single_post_content_box_shadow_color;
			Pacz_Static_Files::addGlobalStyle("
				.pacz-post-single .pacz-post-single-content-wrapper,
				.comment-respond form,
				#pacz-post-comments .pacz-commentlist .comment .pacz-single-comment,
				.pacz-post-single-author-box{
					box-shadow: {$single_post_content_box_shadow_css};
					-webkit-box-shadow: {$single_post_content_box_shadow_css};
					-moz-box-shadow: {$single_post_content_box_shadow_css};
					-o-box-shadow: {$single_post_content_box_shadow_css};
				}
			");
		}
	}
	Pacz_Static_Files::addGlobalStyle("
		.pacz-post-gallery .pacz-post-pre-arrow:hover,
		.pacz-post-gallery .pacz-post-next-arrow:hover{
			background:{$accent_color};
		}
		.pacz-video-container .pacz-post-video-button i{
			color:{$accent_color};
		}
		.pacz-post-publish-date,
		.pacz-post-classic .pacz-post-publish-date,
		.pacz-post-grid .pacz-post-publish-date,
		.pacz-post-grid-mod .pacz-post-publish-date,
		.pacz-post-single .pacz-post-single-publish-date{
			background-color:{$accent_color};
		}
		.pacz-post-grid-mod .pacz-post-categories a{
			border-color:{$primary_rgba_color_40};
			color:{$primary_rgba_color_80};
			background-color:{$primary_rgba_color_10};
		}
		.pacz-post-single .pacz-post-single-meta div.pacz-post-views:hover,
		.pacz-post-single .pacz-post-single-meta div a:hover,
		.pacz-post-meta div.pacz-post-views:hover,
		.pacz-post-meta div a:hover,
		.pacz-post-readmore a:hover{
			color:{$accent_color};
		}
		.pacz-post-grid .pacz-post-meta .pacz-post-categories,
		.pacz-post-grid .pacz-post-meta .pacz-post-categories a,
		.pacz-post-single-comments-heading .comments_numbers,
		.pacz-post-comment-author a:hover,
		.widget .widgettitle::after,
		.widget.widget_block h2::after{
			color:{$accent_color};
		}
		.pacz-post-single-pre-next nav .nav-links .nav-next a,
		.pacz-post-single-pre-next nav .nav-links .nav-previous a{
			{$buttons_font_family}
			{$buttons_font_weight}
			background:{$secondary_color};
			
		}
		.pacz-post-single-pre-next nav .nav-links .nav-next a:hover,
		.pacz-post-single-pre-next nav .nav-links .nav-previous a:hover{
			background:{$accent_color};
		}
		.comment-respond form .form-submit .submit,
		.pacz-post-single-content .post-password-form input[type='submit']{
			background:{$accent_color};
		}
		.comment-respond form .form-submit .submit:hover,
		.pacz-post-single-content .post-password-form input[type='submit']:hover{
			background:{$third_color};
		}
		.pacz-post-comment-reply .comment-reply-link,
		#cancel-comment-reply-link{
			background:{$secondary_color};
		}
		.pacz-post-comment-reply .comment-reply-link:hover,
		#cancel-comment-reply-link:hover{
			background:{$accent_color};
		}
		blockquote,
		.wp-block-quote.is-large,
		.wp-block-quote.is-style-large{
			border-color:{$secondary_color};
			background-color:{$secondary_rgba_color_40};
		}
		
	");

###########################################
	# Pagination
	###########################################
	//$rgba_color = pacz_convert_rgba($accent_color, 0.5);
	Pacz_Static_Files::addGlobalStyle("
		.pacz-pagination .current-page{
			border-color:{$primary_rgba_color_40};
			color:{$primary_rgba_color_60};
			background-color:{$primary_rgba_color_10};
		}
		.pacz-pagination .page-number:hover,
		.pacz-pagination .next a:hover,
		.pacz-pagination .prev a:hover{
			border-color:{$primary_rgba_color_40};
			color:{$primary_rgba_color_60};
			background-color:{$primary_rgba_color_10};
		}
	");

###########################################
	# Tags Widget
	###########################################
	//$rgba_color = pacz_convert_rgba($accent_color, 0.5);
	Pacz_Static_Files::addGlobalStyle("
		.widget .tag-cloud-link:hover, 
		.wp-block-tag-cloud .tag-cloud-link:hover {
			border-color:{$primary_rgba_color_40};
			color:{$primary_rgba_color_60};
			background-color:{$primary_rgba_color_10};
		}
		#pacz-footer .widget .tag-cloud-link:hover, 
		#pacz-footer .wp-block-tag-cloud .tag-cloud-link:hover {
			border-color:{$footer_link_hover_rgba_color_40};
			color:{$footer_link_hover_rgba_color_60};
			background-color:{$footer_link_hover_rgba_color_10};
		}
	");

###########################################
# subscription form
###########################################

Pacz_Static_Files::addGlobalStyle("
	.subscription-form  form#signup-1 .subs-form-btn{
		background-color:{$accent_color} !important;
	}
	.subscription-form  form#signup-1 .subs-form-btn:hover{
		background-color:{$pacz_settings['subs-btn-hover']} !important;
		
	}
");

/* Login AND REGISTER Buttons */
$toolbar_content_padding_top = round($toolbar_height / 2) - 17;
Pacz_Static_Files::addGlobalStyle("
.transparent-header:not(.sticky-trigger-header) .author-displayname{
	color:#fff !important;
}
.author-displayname{
	color:{$pacz_settings['heading-color']} !important;
}
.pacz-header-toolbar .header-toolbar-contact{
	padding-top:{$toolbar_content_padding_top}px;

}
.pacz-header-toolbar .header-toolbar-contact i{
	background-color:{$accent_color};
	color:#fff !important;
}


");
if(!is_404() && !is_search() && !is_author() && class_exists('DHVCForm') && is_object($post)){
	$dhvc_input_border_color = get_post_meta( $post->ID, '_input_border_color', true );
	$dhvc_input_hover_border_color = get_post_meta( $post->ID, '_input_hover_border_color', true );
	$dhvc_input_focus_border_color = get_post_meta( $post->ID, '_input_focus_border_color', true );
	$dhvc_input_border_size = get_post_meta( $post->ID, '_input_border_size', true );
	$dhvc_input_height = get_post_meta( $post->ID, '_input_height', true );
	$dhvc_button_bg_color = get_post_meta( $post->ID, '_button_bg_color', true );
	$dhvc_button_height = get_post_meta( $post->ID, '_button_height', true );
if(isset($dhvc_input_border_color) && empty($dhvc_input_border_color)){
	Pacz_Static_Files::addGlobalStyle("
	.dhvc-form-flat .dhvc-form-input input, .dhvc-form-flat .dhvc-form-file input[type=text], .dhvc-form-flat .dhvc-form-captcha input, .dhvc-form-flat .dhvc-form-select select, .dhvc-form-flat .dhvc-form-textarea textarea, .dhvc-form-flat .dhvc-form-radio i, .dhvc-form-flat .dhvc-form-checkbox i {
	border-color:#eee;
	}
");
	}
if(isset($dhvc_input_border_size) && empty($dhvc_input_border_size)){
	Pacz_Static_Files::addGlobalStyle("
	.dhvc-form-flat .dhvc-form-input input, .dhvc-form-flat .dhvc-form-file input[type=text], .dhvc-form-flat .dhvc-form-captcha input, .dhvc-form-flat .dhvc-form-select select, .dhvc-form-flat .dhvc-form-textarea textarea, .dhvc-form-flat .dhvc-form-radio i, .dhvc-form-flat .dhvc-form-checkbox i {
	border-width:1px;
	}
");
	}
if(isset($dhvc_input_height) && empty($dhvc_input_height)){
	Pacz_Static_Files::addGlobalStyle("
	.dhvc-form-flat .dhvc-form-input input, .dhvc-form-flat .dhvc-form-file input[type=text], .dhvc-form-flat .dhvc-form-captcha input, .dhvc-form-flat .dhvc-form-select select, .dhvc-form-flat .dhvc-form-textarea textarea, .dhvc-form-flat .dhvc-form-radio i, .dhvc-form-flat .dhvc-form-checkbox i {
	height:50px;
	}
");
	}
if(isset($dhvc_button_bg_color) && empty($dhvc_button_bg_color)){
	Pacz_Static_Files::addGlobalStyle("
	.dhvc-form-submit, .dhvc-form-submit:focus, .dhvc-form-submit:hover, .dhvc-form-submit:active {
    background-color:{$accent_color};
}
");
	}
if(isset($dhvc_input_focus_border_color) && empty($dhvc_input_focus_border_color) || isset($dhvc_input_hover_border_color) && empty($dhvc_input_hover_border_color)){
	Pacz_Static_Files::addGlobalStyle("
	.dhvc-form-flat .dhvc-form-input input:focus, .dhvc-form-flat .dhvc-form-captcha input:focus, .dhvc-form-flat .dhvc-form-file:hover input[type='text']:focus, .dhvc-form-flat .dhvc-form-select select:focus, .dhvc-form-flat .dhvc-form-textarea textarea:focus, .dhvc-form-flat .dhvc-form-radio input:checked + i, .dhvc-form-flat .dhvc-form-checkbox input:checked + i{
	border-color:{$accent_color};
	}
");
	}
if(isset($dhvc_button_height) && empty($dhvc_button_height)){
	Pacz_Static_Files::addGlobalStyle("
	.dhvc-form-submit, .dhvc-form-submit:focus, .dhvc-form-submit:hover, .dhvc-form-submit:active {
    height: 50px;
}
");
	}
Pacz_Static_Files::addGlobalStyle("
	.dhvc-form-flat .dhvc-form-input input, .dhvc-form-flat .dhvc-form-file input[type=text], .dhvc-form-flat .dhvc-form-captcha input, .dhvc-form-flat .dhvc-form-select select, .dhvc-form-flat .dhvc-form-textarea textarea, .dhvc-form-flat .dhvc-form-radio i, .dhvc-form-flat .dhvc-form-checkbox i,.dhvc-form-flat .dhvc-form-action.dhvc_form_submit_button {
	margin:7px 0 !important;
	}
	.footer-form-style4 .dhvc-form-flat .dhvc-form-input input, .footer-form-style4 .dhvc-form-flat .dhvc-form-action.dhvc_form_submit_button{
		margin: 0 !important;
	}
	.dhvc-form-submit{
		background-color:{$accent_color};
		display:block;
		width:100%;
	}
	.dhvc-form-submit:hover, .dhvc-form-submit:active, .dhvc-form-submit:focus {
		background-color:{$third_color};
	}
	.dhvc-form-submit, .dhvc-form-submit:hover, .dhvc-form-submit:active, .dhvc-form-submit:focus {
		opacity:1;
	}
	.dhvc-form-add-on i{color:{$accent_color};}
	.dhvc-form-group .dhvc-form-control {padding-left:20px;padding-right:50px}
	.dhvc-register-link{color:{$accent_color}}
");
if(isset($dhvc_button_height) && !empty($dhvc_button_height)){
	Pacz_Static_Files::addGlobalStyle("
	.dhvc-form-add-on{width:{$dhvc_button_height}; line-height:{$dhvc_button_height};height:{$dhvc_button_height};}
");
	}else{
	Pacz_Static_Files::addGlobalStyle("
	.dhvc-form-add-on{width:50px !important;line-height:50px !important;height:50px !important;border-left:1px solid #eee;}
");	
	}
	
}
	
###########################################
# BREADCRUMB CUSTOM SKIN STYLES
###########################################

$breadcrumb_skin = (isset($pacz_settings['breadcrumb-skin']) && !empty($pacz_settings['breadcrumb-skin']) && $pacz_settings['breadcrumb-skin'] == 'custom' ) ? 1 : 0;
$breadcrumb_custom_color_regular = (isset($pacz_settings['breadcrumb-skin-custom']['regular']) && !empty($pacz_settings['breadcrumb-skin-custom']['regular']) ) ? $pacz_settings['breadcrumb-skin-custom']['regular'] : $custom_breadcrumb_color ;
$breadcrumb_custom_color_hover = (isset($pacz_settings['breadcrumb-skin-custom']['hover']) && !empty($pacz_settings['breadcrumb-skin-custom']['hover']) ) ? $pacz_settings['breadcrumb-skin-custom']['hover'] : $custom_breadcrumb_hover_color ;

if($breadcrumb_skin == 1){

	if($custom_breadcrumb_page == 1){
		
		Pacz_Static_Files::addGlobalStyle(" #pacz-breadcrumbs .custom-skin{
			color: {$breadcrumb_custom_color_regular} !important;
		}
		#pacz-breadcrumbs .custom-skin a{
			color: {$breadcrumb_custom_color_regular} !important;
		}
		#pacz-breadcrumbs .custom-skin a:hover{
			color: {$breadcrumb_custom_color_hover} !important;
		}

		");
	}

}
###########################################star-rating
	# PreLoader
###########################################
$preloader_background = ($pacz_settings['preloader-bg-color']) ? ('background-color:' . $pacz_settings['preloader-bg-color'] . ';') : '';
$preloader_image = ($pacz_settings['preloader-logo']['url']) ? ('background-image: url("' . $pacz_settings['preloader-logo']['url'] . '");') : '';
Pacz_Static_Files::addGlobalStyle("
	.pacz-preloader {
		{$preloader_image}
		{$preloader_background}
	}
");

###########################################star-rating
	# Eror 404
###########################################

Pacz_Static_Files::addGlobalStyle("
	.error-404-wrapper .error-404-home-button a{
		background: {$accent_color};
	}
	.error-404-wrapper .error-404-home-button a:hover{
		background: {$third_color};
	}
");

###########################################
	# Post Search Page
	###########################################
	Pacz_Static_Files::addGlobalStyle("
		.post-search-page .pacz-search-form-wrapper{
			background:{$secondary_rgba_color_50};
		}
	");
	
###########################################star-rating
	# DIRECTORYPRESS STYLES
	###########################################

$search_button_border_radius = (isset($pacz_settings['header_search_button_border_radius']['padding-top'])) ? ('border-top-left-radius:'.$pacz_settings['header_search_button_border_radius']['padding-top'].';') : '';
$search_button_border_radius .= (isset($pacz_settings['header_search_button_border_radius']['padding-bottom'])) ? ('border-bottom-right-radius:'.$pacz_settings['header_search_button_border_radius']['padding-bottom'].';') : '';
$search_button_border_radius .= (isset($pacz_settings['header_search_button_border_radius']['padding-left'])) ? ('border-bottom-left-radius:'.$pacz_settings['header_search_button_border_radius']['padding-left'].';') : '';
$search_button_border_radius .= (isset($pacz_settings['header_search_button_border_radius']['padding-right'])) ? ('border-top-right-radius:'.$pacz_settings['header_search_button_border_radius']['padding-right'].';') : '';

Pacz_Static_Files::addGlobalStyle("

.search-form-style-header1-wrapper .search-form-style1.directorypress-content-wrap.directorypress-search-form .directorypress-search-holder .directorypress-search-form-button .btn.btn-primary{
	{$search_button_border_radius}
}
");

// pricing plan
Pacz_Static_Files::addGlobalStyle("
.pricing-plan-style-5 .directorypress-plan-column .directorypress-choose-plan:hover{
	border-color: {$accent_color};
}
.directorypress-submit-section-adv.pricing-plan-style-5 .pricing-button{
	border-color: {$primary_rgba_color_40};
	color:{$accent_color};
	background-color: {$primary_rgba_color_5};
}
.directorypress-submit-section-adv.pricing-plan-style-5 .pricing-button:hover{
	background-color: {$accent_color};
	color:#fff;
}

");


// ui styling
$pacz_ui_style = (isset($pacz_settings['checkbox_styles']))? $pacz_settings['checkbox_styles'] : 1;
if($pacz_ui_style == 2){
	
	Pacz_Static_Files::addGlobalStyle("
		.search-checkbox input:checked ~ .search-checkbox-item,
		.input-checkbox input:checked ~ .input-checkbox-item{
		  border-color: {$accent_color} !important;
		  background: {$accent_color};
		  color: #fff !important;
		}
		.input-checkbox .input-checkbox-item::after{
			 color: #fff !important;
		}
		.search-checkbox input:checked ~ .checkbox-item-name a,
		.search-checkbox:hover .checkbox-item-name a,
		.input-checkbox input:checked ~ .checkbox-item-name a,
		.input-checkbox:hover .checkbox-item-name a{
			color: {$accent_color};
		}
		.search-checkbox input:checked ~ .checkbox-item-name .field-item-count,
		.search-checkbox:hover .checkbox-item-name .field-item-count{
			color: #777;
		}
		
	");

}

###########################################
# Woocommerce Shop
###########################################

	$pacz_woo_font_family = (isset($pacz_settings['pacz-woo-loop-product_title']['font-family']) && !empty($pacz_settings['pacz-woo-loop-product_title']['font-family'])) ? ('font-family:' . $pacz_settings['pacz-woo-loop-product_title']['font-family'] . ';') : '';
	$pacz_woo_font_size = (isset($pacz_settings['pacz-woo-loop-product_title']['font-size']) && !empty($pacz_settings['pacz-woo-loop-product_title']['font-size'])) ? ('font-size:' . $pacz_settings['pacz-woo-loop-product_title']['font-size'] . ';') : '';
	$pacz_woo_font_weight = (isset($pacz_settings['pacz-woo-loop-product_title']['font-weight']) && !empty($pacz_settings['pacz-woo-loop-product_title']['font-weight'])) ? ('font-weight:' . $pacz_settings['pacz-woo-loop-product_title']['font-weight'] . ';') : '';
	$pacz_woo_line_height = (isset($pacz_settings['pacz-woo-loop-product_title']['line-height']) && !empty($pacz_settings['pacz-woo-loop-product_title']['line-height'])) ? ('line-height:' . $pacz_settings['pacz-woo-loop-product_title']['line-height'] . ';') : '';	
	$pacz_woo_text_align = (isset($pacz_settings['pacz-woo-loop-product_title']['text-align']) && !empty($pacz_settings['pacz-woo-loop-product_title']['text-align'])) ? ('text-align:' . $pacz_settings['pacz-woo-loop-product_title']['text-align'] . ';') : '';	
	
	$pacz_woo_font_color = (isset($pacz_settings['pacz-woo-loop-product_title-color']) && !empty($pacz_settings['pacz-woo-loop-product_title-color'])) ? ('color:' . $pacz_settings['pacz-woo-loop-product_title-color'] . ';') : '';
	$pacz_woo_font_color_hover = (isset($pacz_settings['pacz-woo-loop-product_title-color-hover']) && !empty($pacz_settings['pacz-woo-loop-product_title-color-hover'])) ? ('color:' . $pacz_settings['pacz-woo-loop-product_title-color-hover'] . ';') : '';
	
	$pacz_woo_cat_font_family = (isset($pacz_settings['pacz-woo-loop-product_cat']['font-family']) && !empty($pacz_settings['pacz-woo-loop-product_cat']['font-family'])) ? ('font-family:' . $pacz_settings['pacz-woo-loop-product_cat']['font-family'] . ';') : '';
	$pacz_woo_cat_font_size = (isset($pacz_settings['pacz-woo-loop-product_cat']['font-size']) && !empty($pacz_settings['pacz-woo-loop-product_cat']['font-size'])) ? ('font-size:' . $pacz_settings['pacz-woo-loop-product_cat']['font-size'] . ';') : '';
	$pacz_woo_cat_font_weight = (isset($pacz_settings['pacz-woo-loop-product_cat']['font-weight']) && !empty($pacz_settings['pacz-woo-loop-product_cat']['font-weight'])) ? ('font-weight:' . $pacz_settings['pacz-woo-loop-product_cat']['font-weight'] . ';') : '';
	$pacz_woo_cat_line_height = (isset($pacz_settings['pacz-woo-loop-product_cat']['line-height']) && !empty($pacz_settings['pacz-woo-loop-product_cat']['line-height'])) ? ('line-height:' . $pacz_settings['pacz-woo-loop-product_cat']['line-height'] . ';') : '';	
	$pacz_woo_cat_text_align = (isset($pacz_settings['pacz-woo-loop-product_cat']['text-align']) && !empty($pacz_settings['pacz-woo-loop-product_cat']['text-align'])) ? ('text-align:' . $pacz_settings['pacz-woo-loop-product_cat']['text-align'] . ';') : '';	
	$pacz_woo_cat_color = (isset($pacz_settings['pacz-woo-loop-product_cat-color']) && !empty($pacz_settings['pacz-woo-loop-product_cat-color'])) ? ('color:' . $pacz_settings['pacz-woo-loop-product_cat-color'] . ';') : '';
	
	$pacz_woo_price_font_family = (isset($pacz_settings['pacz-woo-loop-product_price']['font-family']) && !empty($pacz_settings['pacz-woo-loop-product_price']['font-family'])) ? ('font-family:' . $pacz_settings['pacz-woo-loop-product_price']['font-family'] . ';') : '';
	$pacz_woo_price_font_size = (isset($pacz_settings['pacz-woo-loop-product_price']['font-size']) && !empty($pacz_settings['pacz-woo-loop-product_price']['font-size'])) ? ('font-size:' . $pacz_settings['pacz-woo-loop-product_price']['font-size'] . ';') : '';
	$pacz_woo_price_font_weight = (isset($pacz_settings['pacz-woo-loop-product_price']['font-weight']) && !empty($pacz_settings['pacz-woo-loop-product_price']['font-weight'])) ? ('font-weight:' . $pacz_settings['pacz-woo-loop-product_price']['font-weight'] . ';') : '';
	$pacz_woo_price_line_height = (isset($pacz_settings['pacz-woo-loop-product_price']['line-height']) && !empty($pacz_settings['pacz-woo-loop-product_price']['line-height'])) ? ('line-height:' . $pacz_settings['pacz-woo-loop-product_price']['line-height'] . ';') : '';	
	$pacz_woo_price_text_align = (isset($pacz_settings['pacz-woo-loop-product_price']['text-align']) && !empty($pacz_settings['pacz-woo-loop-product_price']['text-align'])) ? ('text-align:' . $pacz_settings['pacz-woo-loop-product_price']['text-align'] . ';') : '';	
	$pacz_woo_price_color = (isset($pacz_settings['pacz-woo-loop-product_price-color']) && !empty($pacz_settings['pacz-woo-loop-product_price-color'])) ? ('color:' . $pacz_settings['pacz-woo-loop-product_price-color'] . ';') : '';
	
	$pacz_woo_sale_tag_background_color = (isset($pacz_settings['pacz-woo-product_sale-tag-background-color']) && !empty($pacz_settings['pacz-woo-product_sale-tag-background-color']))? ('background-color:'. $pacz_settings['pacz-woo-product_sale-tag-background-color'] .';') : '';
	$pacz_woo_sale_tag_color = (isset($pacz_settings['pacz-woo-product_sale-tag-color']) && !empty($pacz_settings['pacz-woo-product_sale-tag-color']))? ('color:'. $pacz_settings['pacz-woo-product_sale-tag-color'] .';') : '';

	$pacz_woo_product_addtocart_icon_color = (isset($pacz_settings['pacz-woo-product_addtocart-icon-color']) && !empty($pacz_settings['pacz-woo-product_addtocart-icon-color']))? ('color:'. $pacz_settings['pacz-woo-product_addtocart-icon-color'] .';') : '';
	$pacz_woo_product_addtocart_icon_color_hover = (isset($pacz_settings['pacz-woo-product_addtocart-icon-color-hover']) && !empty($pacz_settings['pacz-woo-product_addtocart-icon-color-hover']))? ('color:'. $pacz_settings['pacz-woo-product_addtocart-icon-color-hover'] .';') : '';
	$pacz_woo_product_addtocart_background_color = (isset($pacz_settings['pacz-woo-product_addtocart-background-color']) && !empty($pacz_settings['pacz-woo-product_addtocart-background-color']))? ('background-color:'. $pacz_settings['pacz-woo-product_addtocart-background-color'] .';') : '';
	$pacz_woo_product_addtocart_background_color_hover = (isset($pacz_settings['pacz-woo-product_addtocart-background-color-hover']) && !empty($pacz_settings['pacz-woo-product_addtocart-background-color-hover']))? ('background-color:'. $pacz_settings['pacz-woo-product_addtocart-background-color-hover'] .';') : '';
	
	$pacz_woo_product_addtocart_border_color = (isset($pacz_settings['pacz-woo-product_addtocart-border']) && !empty($pacz_settings['pacz-woo-product_addtocart-border']))? ('border-color:'. $pacz_settings['pacz-woo-product_addtocart-border']['border-color'] .';') : '';
	$pacz_woo_product_addtocart_border_style = (isset($pacz_settings['pacz-woo-product_addtocart-border']) && !empty($pacz_settings['pacz-woo-product_addtocart-border']['border-style']))? ('border-style: '. $pacz_settings['pacz-woo-product_addtocart-border']['border-style'] .';') : '';
	$pacz_woo_product_addtocart_border_top = (isset($pacz_settings['pacz-woo-product_addtocart-border']) && !empty($pacz_settings['pacz-woo-product_addtocart-border']['border-top']))? ('border-top-width: '. $pacz_settings['pacz-woo-product_addtocart-border']['border-top'] .';') : '';
	$pacz_woo_product_addtocart_border_bottom = (isset($pacz_settings['pacz-woo-product_addtocart-border']) && !empty($pacz_settings['pacz-woo-product_addtocart-border']['border-bottom']))? ('border-bottom-width: '. $pacz_settings['pacz-woo-product_addtocart-border']['border-bottom'] .';') : '';
	$pacz_woo_product_addtocart_border_right = (isset($pacz_settings['pacz-woo-product_addtocart-border']) && !empty($pacz_settings['pacz-woo-product_addtocart-border']['border-right']))? ('border-right-width: '. $pacz_settings['pacz-woo-product_addtocart-border']['border-right'] .';') : '';
	$pacz_woo_product_addtocart_border_left = (isset($pacz_settings['pacz-woo-product_addtocart-border']) && !empty($pacz_settings['pacz-woo-product_addtocart-border']['border-left']))? ('border-left-width: '. $pacz_settings['pacz-woo-product_addtocart-border']['border-left'] .';') : '';
	
	$pacz_woo_product_addtocart_border_color_hover = (isset($pacz_settings['pacz-woo-product_addtocart-border-hover']) && !empty($pacz_settings['pacz-woo-product_addtocart-border-hover']))? ('border-color:'. $pacz_settings['pacz-woo-product_addtocart-border-hover']['border-color'] .';') : '';
	$pacz_woo_product_addtocart_border_style_hover = (isset($pacz_settings['pacz-woo-product_addtocart-border-hover']) && !empty($pacz_settings['pacz-woo-product_addtocart-border-hover']['border-style']))? ('border-style: '. $pacz_settings['pacz-woo-product_addtocart-border-hover']['border-style'] .';') : '';
	$pacz_woo_product_addtocart_border_top_hover = (isset($pacz_settings['pacz-woo-product_addtocart-border-hover']) && !empty($pacz_settings['pacz-woo-product_addtocart-border-hover']['border-top']))? ('border-top-width: '. $pacz_settings['pacz-woo-product_addtocart-border-hover']['border-top'] .';') : '';
	$pacz_woo_product_addtocart_border_bottom_hover = (isset($pacz_settings['pacz-woo-product_addtocart-border-hover']) && !empty($pacz_settings['pacz-woo-product_addtocart-border-hover']['border-bottom']))? ('border-bottom-width: '. $pacz_settings['pacz-woo-product_addtocart-border-hover']['border-bottom'] .';') : '';
	$pacz_woo_product_addtocart_border_right_hover = (isset($pacz_settings['pacz-woo-product_addtocart-border-hover']) && !empty($pacz_settings['pacz-woo-product_addtocart-border-hover']['border-right']))? ('border-right-width: '. $pacz_settings['pacz-woo-product_addtocart-border-hover']['border-right'] .';') : '';
	$pacz_woo_product_addtocart_border_left_hover = (isset($pacz_settings['pacz-woo-product_addtocart-border-hover']) && !empty($pacz_settings['pacz-woo-product_addtocart-border-hover']['border-left']))? ('border-left-width: '. $pacz_settings['pacz-woo-product_addtocart-border-hover']['border-left'] .';') : '';
	$pacz_woo_product_addtocart_border_radius = (isset($pacz_settings['pacz-woo-product_addtocart-border-radius']) && !empty($pacz_settings['pacz-woo-product_addtocart-border-radius']))? ('border-radius: '. $pacz_settings['pacz-woo-product_addtocart-border-radius'] .'px;') : '';
	
	$pacz_woo_product_loop_wrapper_bg_color = (isset($pacz_settings['product-loop-wrapper-bg']) && !empty($pacz_settings['product-loop-wrapper-bg']))? ('background-color:'. $pacz_settings['product-loop-wrapper-bg'] .';') : '';
	$pacz_woo_product_loop_wrapper_bg_color_hover = (isset($pacz_settings['product-loop-wrapper-bg-hover']) && !empty($pacz_settings['product-loop-wrapper-bg-hover']))? ('background-color:'. $pacz_settings['product-loop-wrapper-bg-hover'] .';') : '';

	$pacz_woo_product_loop_wrapper_border_color = (isset($pacz_settings['product-loop-wrapper-border']) && !empty($pacz_settings['product-loop-wrapper-border']))? ('border-color:'. $pacz_settings['product-loop-wrapper-border']['border-color'] .';') : '';
	$pacz_woo_product_loop_wrapper_border_style = (isset($pacz_settings['product-loop-wrapper-border']) && !empty($pacz_settings['product-loop-wrapper-border']['border-style']))? ('border-style: '. $pacz_settings['product-loop-wrapper-border']['border-style'] .';') : '';
	$pacz_woo_product_loop_wrapper_border_top = (isset($pacz_settings['product-loop-wrapper-border']) && !empty($pacz_settings['product-loop-wrapper-border']['border-top']))? ('border-top-width: '. $pacz_settings['product-loop-wrapper-border']['border-top'] .';') : '';
	$pacz_woo_product_loop_wrapper_border_bottom = (isset($pacz_settings['product-loop-wrapper-border']) && !empty($pacz_settings['product-loop-wrapper-border']['border-bottom']))? ('border-bottom-width: '. $pacz_settings['product-loop-wrapper-border']['border-bottom'] .';') : '';
	$pacz_woo_product_loop_wrapper_border_right = (isset($pacz_settings['product-loop-wrapper-border']) && !empty($pacz_settings['product-loop-wrapper-border']['border-right']))? ('border-right-width: '. $pacz_settings['product-loop-wrapper-border']['border-right'] .';') : '';
	$pacz_woo_product_loop_wrapper_border_left = (isset($pacz_settings['product-loop-wrapper-border']) && !empty($pacz_settings['product-loop-wrapper-border']['border-left']))? ('border-left-width: '. $pacz_settings['product-loop-wrapper-border']['border-left'] .';') : '';
	$pacz_woo_product_loop_wrapper_border_radius = (isset($pacz_settings['product-loop-wrapper-border-radius']) && !empty($pacz_settings['product-loop-wrapper-border-radius']))? ('border-radius: '. $pacz_settings['product-loop-wrapper-border-radius'] .'px;') : '';
		
	$pacz_woo_product_loop_wrapper_border_color_hover = (isset($pacz_settings['product-loop-wrapper-border-hover']) && !empty($pacz_settings['product-loop-wrapper-border-hover']))? ('border-color:'. $pacz_settings['product-loop-wrapper-border-hover']['border-color'] .';') : '';
	$pacz_woo_product_loop_wrapper_border_style_hover = (isset($pacz_settings['product-loop-wrapper-border-hover']) && !empty($pacz_settings['product-loop-wrapper-border-hover']['border-style']))? ('border-style: '. $pacz_settings['product-loop-wrapper-border-hover']['border-style'] .';') : '';
	$pacz_woo_product_loop_wrapper_border_top_hover = (isset($pacz_settings['product-loop-wrapper-border-hover']) && !empty($pacz_settings['product-loop-wrapper-border-hover']['border-top']))? ('border-top-width: '. $pacz_settings['product-loop-wrapper-border-hover']['border-top'] .';') : '';
	$pacz_woo_product_loop_wrapper_border_bottom_hover = (isset($pacz_settings['product-loop-wrapper-border-hover']) && !empty($pacz_settings['product-loop-wrapper-border-hover']['border-bottom']))? ('border-bottom-width: '. $pacz_settings['product-loop-wrapper-border-hover']['border-bottom'] .';') : '';
	$pacz_woo_product_loop_wrapper_border_right_hover = (isset($pacz_settings['product-loop-wrapper-border-hover']) && !empty($pacz_settings['product-loop-wrapper-border-hover']['border-right']))? ('border-right-width: '. $pacz_settings['product-loop-wrapper-border-hover']['border-right'] .';') : '';
	$pacz_woo_product_loop_wrapper_border_left_hover = (isset($pacz_settings['product-loop-wrapper-border-hover']) && !empty($pacz_settings['product-loop-wrapper-border-hover']['border-left']))? ('border-left-width: '. $pacz_settings['product-loop-wrapper-border-hover']['border-left'] .';') : '';

	
	
Pacz_Static_Files::addGlobalStyle("
	.pacz-product-title h2 a{
		{$pacz_woo_font_color}
	}
	.pacz-product-title h2 a:hover{
		{$pacz_woo_font_color_hover}
	}
	.pacz-product-title h2,
	#theme-page .pacz-product-title h2{
		{$pacz_woo_font_family}
		{$pacz_woo_font_size}
		{$pacz_woo_font_weight}
		{$pacz_woo_line_height}
		{$pacz_woo_text_align}
	}
	.pacz-product-content .pacz-product-terms{
		{$pacz_woo_cat_color}
		{$pacz_woo_cat_font_family}
		{$pacz_woo_cat_font_size}
		{$pacz_woo_cat_font_weight}
		{$pacz_woo_cat_line_height}
		{$pacz_woo_cat_text_align}
	}
	.product_bottom .product_bottom_left .price{
		{$pacz_woo_price_color}
		{$pacz_woo_price_font_family}
		{$pacz_woo_price_font_size}
		{$pacz_woo_price_font_weight}
		{$pacz_woo_price_line_height}
		{$pacz_woo_price_text_align}
	}
	.product-loop-wrapper .onsale,
	.single.single-product .pacz-woo-gallery .onsale{
		{$pacz_woo_sale_tag_color}
	}
	.product-loop-wrapper .onsale,
	.single.single-product .pacz-woo-gallery .onsale{
		{$pacz_woo_sale_tag_background_color}
	}
	.product_bottom .add_to_cart_button i {
		{$pacz_woo_product_addtocart_icon_color}
	}
	.product_bottom .add_to_cart_button:hover i {
		{$pacz_woo_product_addtocart_icon_color_hover}
	}
	.product_bottom .add_to_cart_button {
		{$pacz_woo_product_addtocart_background_color}
		{$pacz_woo_product_addtocart_border_color}
		{$pacz_woo_product_addtocart_border_style}
		{$pacz_woo_product_addtocart_border_top}
		{$pacz_woo_product_addtocart_border_bottom}
		{$pacz_woo_product_addtocart_border_right}
		{$pacz_woo_product_addtocart_border_left}
		{$pacz_woo_product_addtocart_border_radius}
	}
	.product_bottom .add_to_cart_button:hover {
		{$pacz_woo_product_addtocart_background_color_hover}
		{$pacz_woo_product_addtocart_border_color_hover}
		{$pacz_woo_product_addtocart_border_style_hover}
		{$pacz_woo_product_addtocart_border_top_hover}
		{$pacz_woo_product_addtocart_border_bottom_hover}
		{$pacz_woo_product_addtocart_border_right_hover}
		{$pacz_woo_product_addtocart_border_left_hover}
	}
	.pacz-product-loop-item .product-loop-wrapper {
		{$pacz_woo_product_loop_wrapper_bg_color}
		{$pacz_woo_product_loop_wrapper_border_color}
		{$pacz_woo_product_loop_wrapper_border_style}
		{$pacz_woo_product_loop_wrapper_border_top}
		{$pacz_woo_product_loop_wrapper_border_bottom}
		{$pacz_woo_product_loop_wrapper_border_right}
		{$pacz_woo_product_loop_wrapper_border_left}
		{$pacz_woo_product_loop_wrapper_border_radius}
	}
	.pacz-product-loop-item .product-loop-wrapper:hover {
		{$pacz_woo_product_loop_wrapper_bg_color_hover}
		{$pacz_woo_product_loop_wrapper_border_color_hover}
		{$pacz_woo_product_loop_wrapper_border_style_hover}
		{$pacz_woo_product_loop_wrapper_border_top_hover}
		{$pacz_woo_product_loop_wrapper_border_bottom_hover}
		{$pacz_woo_product_loop_wrapper_border_right_hover}
		{$pacz_woo_product_loop_wrapper_border_left_hover}
		
	}
	
");


if(isset($pacz_settings['product-loop-wrapper-box-shadow']) &&  $pacz_settings['product-loop-wrapper-box-shadow']){
	
	$pacz_woo_product_loop_wrapper_box_shadow = $pacz_settings['product-loop-wrapper-box-shadow']['drop-shadow'];
	$pacz_woo_product_loop_wrapper_box_shadow_color = $pacz_woo_product_loop_wrapper_box_shadow['color'];
	$pacz_woo_product_loop_wrapper_box_shadow_horizontal = ($pacz_woo_product_loop_wrapper_box_shadow['horizontal'] != 0)? $pacz_woo_product_loop_wrapper_box_shadow['horizontal'] .'px' : 0;
	$pacz_woo_product_loop_wrapper_box_shadow_vertical = ($pacz_woo_product_loop_wrapper_box_shadow['vertical'] != 0)? $pacz_woo_product_loop_wrapper_box_shadow['vertical'] .'px' : 0;
	$pacz_woo_product_loop_wrapper_box_shadow_blur = ($pacz_woo_product_loop_wrapper_box_shadow['blur'] != 0)? $pacz_woo_product_loop_wrapper_box_shadow['blur'] .'px' : 0;
	$pacz_woo_product_loop_wrapper_box_shadow_spread = ($pacz_woo_product_loop_wrapper_box_shadow['spread'] != 0)? $pacz_woo_product_loop_wrapper_box_shadow['spread'] .'px' : 0;
		
		if(!empty($pacz_woo_product_loop_wrapper_box_shadow_color)){
			$pacz_woo_product_loop_wrapper_box_shadow_css = $pacz_woo_product_loop_wrapper_box_shadow_horizontal .' '. $pacz_woo_product_loop_wrapper_box_shadow_vertical .' '. $pacz_woo_product_loop_wrapper_box_shadow_blur .' '. $pacz_woo_product_loop_wrapper_box_shadow_spread .' '. $pacz_woo_product_loop_wrapper_box_shadow_color;
			Pacz_Static_Files::addGlobalStyle("
			.pacz-product-loop-item .product-loop-wrapper{
				box-shadow: {$pacz_woo_product_loop_wrapper_box_shadow_css};
				-webkit-box-shadow: {$pacz_woo_product_loop_wrapper_box_shadow_css};
				-moz-box-shadow: {$pacz_woo_product_loop_wrapper_box_shadow_css};
				-o-box-shadow: {$pacz_woo_product_loop_wrapper_box_shadow_css};
				}
			
			
			");
		}
	}
	
if(isset($pacz_settings['product-loop-wrapper-box-shadow-hover']) && $pacz_settings['product-loop-wrapper-box-shadow-hover']){
	
	$pacz_woo_product_loop_wrapper_box_shadow_hover = $pacz_settings['product-loop-wrapper-box-shadow-hover']['drop-shadow'];
	$pacz_woo_product_loop_wrapper_box_shadow_color_hover = $pacz_woo_product_loop_wrapper_box_shadow_hover['color'];
	$pacz_woo_product_loop_wrapper_box_shadow_horizontal_hover = ($pacz_woo_product_loop_wrapper_box_shadow_hover['horizontal'] != 0)? $pacz_woo_product_loop_wrapper_box_shadow_hover['horizontal'] .'px' : 0;
	$pacz_woo_product_loop_wrapper_box_shadow_vertical_hover = ($pacz_woo_product_loop_wrapper_box_shadow_hover['vertical'] != 0)? $pacz_woo_product_loop_wrapper_box_shadow_hover['vertical'] .'px' : 0;
	$pacz_woo_product_loop_wrapper_box_shadow_blur_hover = ($pacz_woo_product_loop_wrapper_box_shadow_hover['blur'] != 0)? $pacz_woo_product_loop_wrapper_box_shadow_hover['blur'] .'px' : 0;
	$pacz_woo_product_loop_wrapper_box_shadow_spread_hover = ($pacz_woo_product_loop_wrapper_box_shadow_hover['spread'] != 0)? $pacz_woo_product_loop_wrapper_box_shadow_hover['spread'] .'px' : 0;
		
		if(!empty($pacz_woo_product_loop_wrapper_box_shadow_color_hover)){
			$pacz_woo_product_loop_wrapper_box_shadow_hover_css = $pacz_woo_product_loop_wrapper_box_shadow_horizontal_hover .' '. $pacz_woo_product_loop_wrapper_box_shadow_vertical_hover .' '. $pacz_woo_product_loop_wrapper_box_shadow_blur_hover .' '. $pacz_woo_product_loop_wrapper_box_shadow_spread_hover .' '. $pacz_woo_product_loop_wrapper_box_shadow_color_hover;
			Pacz_Static_Files::addGlobalStyle("
			.pacz-product-loop-item .product-loop-wrapper:hover{
				box-shadow: {$pacz_woo_product_loop_wrapper_box_shadow_hover_css};
				-webkit-box-shadow: {$pacz_woo_product_loop_wrapper_box_shadow_hover_css};
				-moz-box-shadow: {$pacz_woo_product_loop_wrapper_box_shadow_hover_css};
				-o-box-shadow: {$pacz_woo_product_loop_wrapper_box_shadow_hover_css};
				}
			
			
			");
		}
	}

	$pacz_woo_product_loop_wrapper_padding_top = (isset($pacz_settings['product-loop-wrapper_padding']['padding-top'])) ? ('padding-top:'.$pacz_settings['product-loop-wrapper_padding']['padding-top'].';') : '';
	$pacz_woo_product_loop_wrapper_padding_bottom = (isset($pacz_settings['product-loop-wrapper_padding']['padding-bottom'])) ? ('padding-bottom:'.$pacz_settings['product-loop-wrapper_padding']['padding-bottom'].';') : '';
	$pacz_woo_product_loop_wrapper_padding_left = (isset($pacz_settings['product-loop-wrapper_padding']['padding-left'])) ? ('padding-left:'.$pacz_settings['product-loop-wrapper_padding']['padding-left'].';') : '';
	$pacz_woo_product_loop_wrapper_padding_right = (isset($pacz_settings['product-loop-wrapper_padding']['padding-right'])) ? ('padding-right:'.$pacz_settings['product-loop-wrapper_padding']['padding-right'].';') : '';

Pacz_Static_Files::addGlobalStyle("
	.pacz-product-loop-item .product-loop-wrapper{
		{$pacz_woo_product_loop_wrapper_padding_top}
		{$pacz_woo_product_loop_wrapper_padding_bottom}
		{$pacz_woo_product_loop_wrapper_padding_left}
		{$pacz_woo_product_loop_wrapper_padding_right}
	}		
			
");		
	$pacz_woo_product_loop_content_padding_top = (isset($pacz_settings['product-loop-content_padding']['padding-top'])) ? ('padding-top:'.$pacz_settings['product-loop-content_padding']['padding-top'].';') : '';
	$pacz_woo_product_loop_content_padding_bottom = (isset($pacz_settings['product-loop-content_padding']['padding-bottom'])) ? ('padding-bottom:'.$pacz_settings['product-loop-content_padding']['padding-bottom'].';') : '';
	$pacz_woo_product_loop_content_padding_left = (isset($pacz_settings['product-loop-content_padding']['padding-left'])) ? ('padding-left:'.$pacz_settings['product-loop-content_padding']['padding-left'].';') : '';
	$pacz_woo_product_loop_content_padding_right = (isset($pacz_settings['product-loop-content_padding']['padding-right'])) ? ('padding-right:'.$pacz_settings['product-loop-content_padding']['padding-right'].';') : '';

Pacz_Static_Files::addGlobalStyle("
	.pacz-product-loop-item .pacz-product-content{
		{$pacz_woo_product_loop_content_padding_top}
		{$pacz_woo_product_loop_content_padding_bottom}
		{$pacz_woo_product_loop_content_padding_left}
		{$pacz_woo_product_loop_content_padding_right}
	}		
");		

	$pacz_woo_product_wishlist_icon_color = (isset($pacz_settings['pacz-woo-product_wishlist-icon-color']) && !empty($pacz_settings['pacz-woo-product_wishlist-icon-color']))? ('color:'. $pacz_settings['pacz-woo-product_wishlist-icon-color'] .';') : '';
	$pacz_woo_product_wishlist_icon_color_hover = (isset($pacz_settings['pacz-woo-product_wishlist-icon-color-hover']) && !empty($pacz_settings['pacz-woo-product_wishlist-icon-color-hover']))? ('color:'. $pacz_settings['pacz-woo-product_wishlist-icon-color-hover'] .';') : '';
	$pacz_woo_product_wishlist_background_color = (isset($pacz_settings['pacz-woo-product_wishlist-background-color']) && !empty($pacz_settings['pacz-woo-product_wishlist-background-color']))? ('background-color:'. $pacz_settings['pacz-woo-product_wishlist-background-color'] .';') : '';
	$pacz_woo_product_wishlist_background_color_hover = (isset($pacz_settings['pacz-woo-product_wishlist-background-color-hover']) && !empty($pacz_settings['pacz-woo-product_wishlist-background-color-hover']))? ('background-color:'. $pacz_settings['pacz-woo-product_wishlist-background-color-hover'] .';') : '';
	
	$pacz_woo_product_wishlist_border_color = (isset($pacz_settings['pacz-woo-product_wishlist-border']) && !empty($pacz_settings['pacz-woo-product_wishlist-border']))? ('border-color:'. $pacz_settings['pacz-woo-product_wishlist-border']['border-color'] .';') : '';
	$pacz_woo_product_wishlist_border_style = (isset($pacz_settings['pacz-woo-product_wishlist-border']) && !empty($pacz_settings['pacz-woo-product_wishlist-border']['border-style']))? ('border-style: '. $pacz_settings['pacz-woo-product_wishlist-border']['border-style'] .';') : '';
	$pacz_woo_product_wishlist_border_top = (isset($pacz_settings['pacz-woo-product_wishlist-border']) && !empty($pacz_settings['pacz-woo-product_wishlist-border']['border-top']))? ('border-top-width: '. $pacz_settings['pacz-woo-product_wishlist-border']['border-top'] .';') : '';
	$pacz_woo_product_wishlist_border_bottom = (isset($pacz_settings['pacz-woo-product_wishlist-border']) && !empty($pacz_settings['pacz-woo-product_wishlist-border']['border-bottom']))? ('border-bottom-width: '. $pacz_settings['pacz-woo-product_wishlist-border']['border-bottom'] .';') : '';
	$pacz_woo_product_wishlist_border_right = (isset($pacz_settings['pacz-woo-product_wishlist-border']) && !empty($pacz_settings['pacz-woo-product_wishlist-border']['border-right']))? ('border-right-width: '. $pacz_settings['pacz-woo-product_wishlist-border']['border-right'] .';') : '';
	$pacz_woo_product_wishlist_border_left = (isset($pacz_settings['pacz-woo-product_wishlist-border']) && !empty($pacz_settings['pacz-woo-product_wishlist-border']['border-left']))? ('border-left-width: '. $pacz_settings['pacz-woo-product_wishlist-border']['border-left'] .';') : '';
	
	$pacz_woo_product_wishlist_border_color_hover = (isset($pacz_settings['pacz-woo-product_wishlist-border-hover']) && !empty($pacz_settings['pacz-woo-product_wishlist-border-hover']))? ('border-color:'. $pacz_settings['pacz-woo-product_wishlist-border-hover']['border-color'] .';') : '';
	$pacz_woo_product_wishlist_border_style_hover = (isset($pacz_settings['pacz-woo-product_wishlist-border-hover']) && !empty($pacz_settings['pacz-woo-product_wishlist-border-hover']['border-style']))? ('border-style: '. $pacz_settings['pacz-woo-product_wishlist-border-hover']['border-style'] .';') : '';
	$pacz_woo_product_wishlist_border_top_hover = (isset($pacz_settings['pacz-woo-product_wishlist-border-hover']) && !empty($pacz_settings['pacz-woo-product_wishlist-border-hover']['border-top']))? ('border-top-width: '. $pacz_settings['pacz-woo-product_wishlist-border-hover']['border-top'] .';') : '';
	$pacz_woo_product_wishlist_border_bottom_hover = (isset($pacz_settings['pacz-woo-product_wishlist-border-hover']) && !empty($pacz_settings['pacz-woo-product_wishlist-border-hover']['border-bottom']))? ('border-bottom-width: '. $pacz_settings['pacz-woo-product_wishlist-border-hover']['border-bottom'] .';') : '';
	$pacz_woo_product_wishlist_border_right_hover = (isset($pacz_settings['pacz-woo-product_wishlist-border-hover']) && !empty($pacz_settings['pacz-woo-product_wishlist-border-hover']['border-right']))? ('border-right-width: '. $pacz_settings['pacz-woo-product_wishlist-border-hover']['border-right'] .';') : '';
	$pacz_woo_product_wishlist_border_left_hover = (isset($pacz_settings['pacz-woo-product_wishlist-border-hover']) && !empty($pacz_settings['pacz-woo-product_wishlist-border-hover']['border-left']))? ('border-left-width: '. $pacz_settings['pacz-woo-product_wishlist-border-hover']['border-left'] .';') : '';
	$pacz_woo_product_wishlist_border_radius = (isset($pacz_settings['pacz-woo-product_wishlist-border-radius']) && !empty($pacz_settings['pacz-woo-product_wishlist-border-radius']))? ('border-radius: '. $pacz_settings['pacz-woo-product_wishlist-border-radius'] .'px;') : '';
	
Pacz_Static_Files::addGlobalStyle("
	.pacz-product-loop-item .love-main i {
		{$pacz_woo_product_wishlist_icon_color}
	}
	.pacz-product-loop-item .love-main:hover i {
		{$pacz_woo_product_wishlist_icon_color_hover}
	}
	.pacz-product-loop-item .love-main {
		{$pacz_woo_product_wishlist_background_color}
		{$pacz_woo_product_wishlist_border_color}
		{$pacz_woo_product_wishlist_border_style}
		{$pacz_woo_product_wishlist_border_top}
		{$pacz_woo_product_wishlist_border_bottom}
		{$pacz_woo_product_wishlist_border_right}
		{$pacz_woo_product_wishlist_border_left}
		{$pacz_woo_product_wishlist_border_radius}
	}
	.pacz-product-loop-item .love-main:hover {
		{$pacz_woo_product_wishlist_background_color_hover}
		{$pacz_woo_product_wishlist_border_color_hover}
		{$pacz_woo_product_wishlist_border_style_hover}
		{$pacz_woo_product_wishlist_border_top_hover}
		{$pacz_woo_product_wishlist_border_bottom_hover}
		{$pacz_woo_product_wishlist_border_right_hover}
		{$pacz_woo_product_wishlist_border_left_hover}
	}	
");	


// Single product details
	Pacz_Static_Files::addGlobalStyle("
	.single.single-product .price ins {
		color: {$accent_color};
	}
	.product_meta .posted_in a:hover, .product_meta .tagged_as a:hover{
		color: {$accent_color};
	}
	.single_add_to_cart_button:hover {
		background: {$accent_color};
	}
	.single.single-product .product .pacz-woo-gallery .slick-active:active,
	.single.single-product .product .pacz-woo-gallery .slick-active:focus{
		border: 1px solid {$accent_color};
	}
	.related .slider-related i.woo-single-pre:hover,
	.related .slider-related i.woo-single-next:hover {
		background: {$accent_color};
	}
	
		
	");
// add to cart
	Pacz_Static_Files::addGlobalStyle("
	.cart-collaterals-wrapper .coupon .button:hover, .cart-update-wrap .button:hover {
		background: {$accent_color};
	}
	.cart-collaterals tbody tr.order-total td {
		color: {$accent_color};
	}
	.cart-collaterals .checkout-button:hover{
		background: {$accent_color};
	}

		
	");
// checkout page
	Pacz_Static_Files::addGlobalStyle("
	.woocommerce-error, .woocommerce-info, .woocommerce-message {
		border-top: 4px solid {$accent_color};
	}
	.woocommerce-error a:hover,
	.woocommerce-info a:hover,
	.woocommerce-message a:hover{
		color: {$accent_color};
	}
	.woocommerce-form-coupon-wrapper .coupon-button:hover{
		background: {$accent_color};
	}
	.woocommerce-checkout-payment .place-order #place_order:hover, .woocommerce-checkout-payment #place_order.button:hover {
		background: {$accent_color};
	}
	.woocommerce-form-login .lost_password a:hover{
		color: {$accent_color};
	}
	.woocommerce-form-login .woocommerce-form-login__submit:hover{
		background: {$accent_color};
	}
	.woocommerce-terms-and-conditions-checkbox-text a:hover {
	color: {$accent_color};
	}

		
	");
// woo background

	$woo_bg = (isset($pacz_settings['woo-bg']['background-color'])) ? ('background-color:' . $pacz_settings['woo-bg']['background-color'] . ';') : '';
	$woo_bg .= (isset($pacz_settings['woo-bg']['background-image'])) ? ('background-image:url(' . $pacz_settings['woo-bg']['background-image'] . ');') : ' ';
	$woo_bg .= (isset($pacz_settings['woo-bg']['background-position'])) ? ('background-position:' . $pacz_settings['woo-bg']['background-position'] . ';') : '';
	$woo_bg .= (isset($pacz_settings['woo-bg']['background-attachment'])) ? ('background-attachment:' . $pacz_settings['woo-bg']['background-attachment'] . ';') : '';
	$woo_bg .= (isset($pacz_settings['woo-bg']['background-repeat'])) ? ('background-repeat:' . $pacz_settings['woo-bg']['background-repeat'] . ';') : '';
	$woo_bg .= (isset($pacz_settings['woo-bg']['background-size'])) ? ('background-size:'. $pacz_settings['woo-bg']['background-size'].';') : '';

	Pacz_Static_Files::addGlobalStyle("
	.woocommerce-page #theme-page {
		{$woo_bg}
	}
		
	");
	
	
// checkout page
	Pacz_Static_Files::addGlobalStyle("
	.pagination > .active > a, .pagination > .active > a:focus, .pagination > .active > a:hover,
	.pagination > .active > span, .pagination > .active > span:focus, .pagination > .active > span:hover {
		background-color: {$accent_color};
		border-color: {$accent_color};
	}
	.pagination > li > a:focus, .pagination > li > a:hover, .pagination > li > span:focus, .pagination > li > span:hover {
		background-color: {$accent_color};
		border-color: {$accent_color};
	}

		
	");
	
	// submit listing page
	Pacz_Static_Files::addGlobalStyle("
	.directorypress-upload-item .directorypress-drop-zone .btn {
		background-color: #fff !important;
	}
	
	");
	// Price widget 
	Pacz_Static_Files::addGlobalStyle("
	.directorypress_widget_price .directorypress-price-style3 .field-content {
		color: {$accent_color};
	}
	
	");