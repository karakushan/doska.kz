<?php
add_action ("directorypress_after_dynamic_style" , "pacz_directorypress_after_dynamic_style");
// dynamic styles

function pacz_directorypress_after_dynamic_style(){
	###########################################
	# Categories
	###########################################
	global $DIRECTORYPRESS_ADIMN_SETTINGS, $pacz_settings;
	$directorypress_primary_color = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_primary_color'];
	$directorypress_secondary_color = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_secondary_color'];
	
	$parent_cat_title_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['parent_cat_title_color']['regular'])) ? ('color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['parent_cat_title_color']['regular']. ';') : '';
	$parent_cat_title_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['parent_cat_title_color']['hover'])) ?('color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['parent_cat_title_color']['hover']. ';') : '';
	$parent_cat_title_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['parent_cat_title_color']['bg'])) ? ('background:'.$DIRECTORYPRESS_ADIMN_SETTINGS['parent_cat_title_color']['bg']. ';') : '';
	$parent_cat_title_bg_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['parent_cat_title_color']['bg-hover'])) ? ('background:'.$DIRECTORYPRESS_ADIMN_SETTINGS['parent_cat_title_color']['bg-hover']. ';') : '';

	$subcategory_title_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['subcategory_title_color']['regular'])) ? ('color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['subcategory_title_color']['regular']. ';') : '';
	$subcategory_title_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['subcategory_title_color']['hover'])) ? ('color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['subcategory_title_color']['hover']. ';') : '';

	$cat_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['cat_bg_color']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['cat_bg_color']['color'])) ? ('background:'.$DIRECTORYPRESS_ADIMN_SETTINGS['cat_bg_color']['rgba'].';') : '';
	$cat_bg_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['cat_bg_color_hover']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['cat_bg_color_hover']['color'])) ? ('background:'.$DIRECTORYPRESS_ADIMN_SETTINGS['cat_bg_color_hover']['rgba'].';') : '';

	$cat_border_color = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_color']['rgba'])  && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_color']['color'])) ? ('border-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_color']['rgba']. ';') : '';
	$cat_border_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_color_hover']['rgba'])  && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_color_hover']['color'])) ? ('border-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_color_hover']['rgba']. ';') : '';

	$cat_box_shadow = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_color']['rgba'])  && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_color']['color'])) ? ('box-shadow: 0 2px 0 0'.$DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_color']['rgba']. ';') : '';
	$cat_box_shadow_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_color_hover']['rgba'])  && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_color_hover']['color'])) ? ('box-shadow: 0 2px 0 0'.$DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_color_hover']['rgba']. ';') : '';


	$cat_border_radius_top = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_radius']['padding-top']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_radius']['padding-top'])) ? ('border-top-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_radius']['padding-top'].';') : '';
	$cat_border_radius_bottom = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_radius']['padding-bottom']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_radius']['padding-bottom'])) ? ('border-bottom-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_radius']['padding-bottom'].';') : '';
	$cat_border_radius_left = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_radius']['padding-left']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_radius']['padding-left'])) ? ('border-bottom-left-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_radius']['padding-left'].';') : '';
	$cat_border_radius_right = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_radius']['padding-right']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_radius']['padding-right'])) ? ('border-top-right-radius:'.$DIRECTORYPRESS_ADIMN_SETTINGS['cat_border_radius']['padding-right'].';') : '';



	DirectoryPress_Static_Files::addGlobalStyle("
		.cat-style-1 .directorypress-category-holder .directorypress-parent-category a .cat-icon{
			{$cat_bg}
			{$cat_border_radius_top}
			{$cat_border_radius_right}
			{$cat_border_radius_bottom}
			{$cat_border_radius_left}
			
		}
		.cat-style-1 .directorypress-category-holder .directorypress-parent-category a:hover .cat-icon,
		.cat-style-1 .directorypress-category-holder .directorypress-parent-category a .cat-icon:hover{
			{$cat_bg_hover}
		}
		.cat-style-2 .directorypress-category-holder .directorypress-parent-category a .cat-icon,
		.cat-style-3 .directorypress-category-holder,
		.cat-style-4 .directorypress-category-holder,
		.cat-style-5 .directorypress-category-holder,
		.cat-style-6 .directorypress-category-holder,
		.cat-style-7 .directorypress-category-holder,
		.cat-style-8 .directorypress-category-holder,
		.cat-style-9 .directorypress-category-holder{
			{$cat_bg}
			{$cat_border_radius_top}
			{$cat_border_radius_right}
			{$cat_border_radius_bottom}
			{$cat_border_radius_left}
			
		}
		.cat-style-2 .directorypress-category-holder .directorypress-parent-category a .cat-icon:hover,
		.cat-style-2 .directorypress-category-holder .directorypress-parent-category a:hover .cat-icon,
		.cat-style-3 .directorypress-category-holder:hover,
		.cat-style-4 .directorypress-category-holder:hover,
		.cat-style-5 .directorypress-category-holder:hover,
		.cat-style-6 .directorypress-category-holder:hover,
		.cat-style-7 .directorypress-category-holder:hover,
		.cat-style-8 .directorypress-category-holder:hover,
		.cat-style-9 .directorypress-category-holder:hover{
			{$cat_bg_hover}
		}
		
		.cat-style-6 .directorypress-category-holder{
			{$cat_box_shadow}
			{$cat_border_color}
		}
		.cat-style-6 .directorypress-category-holder:hover{
			{$cat_box_shadow_hover}
			{$cat_border_color_hover}
		}
		.cat-style-6 .directorypress-category-holder .directorypress-parent-category{
			{$parent_cat_title_bg}
		}
		.cat-style-6 .directorypress-category-holder:hover .directorypress-parent-category{
			{$parent_cat_title_bg_hover}
		}
		.cat-style-6 .directorypress-categories-wrapper .directorypress-category-holder .subcategories ul li a.view-all-btn{
			{$cat_border_color}
		}

		.cat-style-6 .directorypress-categories-wrapper .directorypress-category-holder:hover .subcategories ul li a.view-all-btn{
			{$cat_border_color_hover}
		}

		.cat-style-7 .directorypress-category-holder{
			{$cat_border_color}
		}

		.cat-style-7 .directorypress-category-holder:hover{
			border-color: {$cat_border_color_hover};
		}
		
		.cat-style-3 .directorypress-category-holder:hover .directorypress-parent-category a,
		.cat-style-4 .directorypress-category-holder:hover .directorypress-parent-category a,
		.cat-style-5 .directorypress-category-holder:hover .directorypress-parent-category a,
		.cat-style-6 .directorypress-category-holder:hover .directorypress-parent-category a,
		.cat-style-7 .directorypress-category-holder:hover .directorypress-parent-category a,
		.cat-style-9 .directorypress-category-holder:hover .directorypress-parent-category a{
			{$parent_cat_title_color_hover}
		}
		.subcategories ul li a:hover,
		.subcategories ul li a:hover span{
			{$subcategory_title_color_hover}
		}
		
	");
	
	// locations dynamic styles
	DirectoryPress_Static_Files::addGlobalStyle("
		.location-style2.directorypress-locations-columns .directorypress-location-item  .directorypress-parent-location a:before{
			background-color:{$directorypress_primary_color};
		}
		.location-style2.directorypress-locations-columns .directorypress-location-item  .directorypress-parent-location a:hover:before{
			background-color:{$directorypress_secondary_color};
		}
		
	");
	
	// Pricing dynamic styles
	DirectoryPress_Static_Files::addGlobalStyle("
	
		.pplan-style-3 .directorypress-choose-plan ul li .directorypress-price del .woocommerce-Price-amount,
		.pplan-style-3 .directorypress-choose-plan ul li .directorypress-price del .woocommerce-Price-amount .woocommerce-Price-currencySymbol,
		.pplan-style-3 .directorypress-choose-plan ul li .directorypress-price del,
		.directorypress-choose-plan ul li .directorypress-price del,
		.directorypress-price del .woocommerce-Price-amount,
		.pplan-style-3 .directorypress-choose-plan ul li .directorypress-price span,
		.pplan-style-3 .directorypress-choose-plan ul li .directorypress-price{
			color:{$pacz_settings['heading-color']} !important;
		}
		.pplan-style-3 .directorypress-choose-plan:hover ul li.directorypress-list-group-item:first-child {
			background-color:{$directorypress_primary_color} !important;
			border-color:#fff;
			box-shadow:none;
			
		}
		.pplan-style-3 .directorypress-choose-plan:hover ul li.directorypress-list-group-item:first-child span,
		.pplan-style-3 .directorypress-choose-plan:hover ul li.directorypress-list-group-item:first-child,
		.pplan-style-3 .directorypress-choose-plan:hover ul li.directorypress-list-group-item:first-child .directorypress-price,
		.pplan-style-3 .directorypress-choose-plan:hover ul li.directorypress-list-group-item:first-child .directorypress-price span{
			color:#fff !important;
		}
		
	");
	
	// listing dynamic styling
	$price_tag_height = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_height'])) ? ('min-height:'. $DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_tag_height'] .'px;') : '';
	$price_tag_bg = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_bg']['rgba']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_bg']['color'])) ? $DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_bg']['rgba'] : $directorypress_primary_color;
	
	$price_tag_border_top_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_bg_hover']['rgba'])  && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_bg_hover']['color'])) ? ('border-top-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_bg_hover']['rgba'] .';') : '';
	$price_tag_border_bottom_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_bg_hover']['rgba'])  && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_bg_hover']['color'])) ? ('border-bottom-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_bg_hover']['rgba'] .';') : '';
	$price_tag_border_left_color_hover = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_bg_hover']['rgba'])  && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_bg_hover']['color'])) ? ('border-left-color:'. $DIRECTORYPRESS_ADIMN_SETTINGS['listing_price_bg_hover']['rgba'] .';') : '';
	$has_featured_text = esc_html__('Featured', 'classiadspro');
	
	$listview_width = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_logo_width_listview'];
	$listview_height = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_logo_height_listview'];
	$calc_content_width = $listview_width + 145;
	DirectoryPress_Static_Files::addGlobalStyle("
		.listing-post-style-2 .has_featured-ad{
			background:{$directorypress_primary_color};
		}
		.listing-post-style-2:hover .has_featured-ad{
			background:{$directorypress_secondary_color};
		}
		.directorypress-listings-grid .listing-post-style-3 .directorypress-listing-item-holder:hover .directorypress-listing-text-content-wrap{
			background:{$pacz_settings['heading-color']};
		}
		.directorypress-listings-grid .listing-post-style-3 .directorypress-listing-item-holder:hover .directorypress-listing-text-content-wrap .directorypress-field-type-categories .directorypress-label-primary a{
			color:{$directorypress_secondary_color} !important;
		}
		.listing-post-style-3 figure .price,
		.listing-post-style-7 figure .price .directorypress-field-type-price .field-content{
			background:{$directorypress_primary_color};
		}
		.listing-post-style-3:hover figure .price,
		.listing-post-style-7:hover figure .price .directorypress-field-type-price .field-content{
			background:{$directorypress_secondary_color};
		}
		.directorypress-listings-grid .listing-post-style-3 .directorypress-listing-text-content-wrap .directorypress-field-type-price,
		.directorypress-listings-grid .listing-post-style-5 .directorypress-listing-text-content-wrap .directorypress-field-type-categories .field-content .directorypress-label,
		.directorypress-listings-grid .listing-post-style-9 .directorypress-listing-text-content-wrap .directorypress-field-type-categories .field-content .directorypress-label,
		.popular-package{
			background-color:{$directorypress_primary_color} !important;
		}
		.directorypress-listings-grid .listing-post-style-3 .directorypress-listing-item-holder:hover .directorypress-listing-text-content-wrap .directorypress-field-type-categories .field-content .directorypress-label,
		.directorypress-listings-grid .listing-post-style-5 .directorypress-listing-text-content-wrap .directorypress-field-type-price,
		.directorypress-listings-grid .listing-post-style-9 .directorypress-listing-text-content-wrap .directorypress-field-type-price {
			color:{$directorypress_primary_color} !important;
		}
		.directorypress-listing.listing-post-style-6.directorypress-has_featured .directorypress-listing-figure a.directorypress-listing-figure-img-wrap:after{
			content: '{$has_featured_text}';
			font-family: inherit;
			display: inline-block;
			height: auto;
			width: auto;
			padding: 7px 12px;
			position: absolute;
			bottom:30px;
			left:30px !important;
			color:#fff;
			z-index:1;
			font-size:14px;
			border-radius:3px;
			line-height:1;
			text-transform:uppercase;
			background-color:{$directorypress_secondary_color};
		}
		.directorypress-listings-grid .listing-post-style-7 .directorypress-listing-text-content-wrap .second-content-field .directorypress-field-type-string .field-label .directorypress-field-icon {
			color:{$directorypress_primary_color};
		}
		.directorypress-listing.listing-post-style-9 .directorypress-listing-figure .price .directorypress-field-item span.field-content{
			font-weight:bold;
		}
		.directorypress-listing.listing-post-style-9 .directorypress-listing-figure .price .directorypress-field-item span.field-content{
			background:{$pacz_settings['btn-hover']} !important;
		}
		.directorypress-listings-grid .listing-post-style-10 .directorypress-listing-item-holder .directorypress-listing-text-content-wrap .listing-location i{
			color:{$pacz_settings['body-txt-color']};
		}
		.directorypress-listings-grid .listing-post-style-10 .directorypress-listing-text-content-wrap .directorypress-field-type-price{
			color:{$pacz_settings['heading-color']};
		}
		.directorypress-listings-grid .listing-post-style-10 .directorypress-listing-text-content-wrap .listing-cat{
			color:{$pacz_settings['body-txt-color']};
		}
		.directorypress-listings-grid .listing-post-style-19 .directorypress-listing-item-holder .directorypress-listing-text-content-wrap .listing-location i{
			color:{$pacz_settings['body-txt-color']};
		}
		.directorypress-listings-grid .listing-post-style-19 .directorypress-listing-text-content-wrap .directorypress-field-type-price{
			color:{$pacz_settings['heading-color']};
		}
		.directorypress-listings-grid .listing-post-style-19 .directorypress-listing-text-content-wrap .listing-cat{
			color:{$pacz_settings['body-txt-color']};
		}
		
		.listing-post-style-14 .listing-rating.grid-rating .rating-numbers,
		.listing-post-style-listview_ultra .listing-rating.grid-rating .rating-numbers{
			background-color:{$directorypress_primary_color};
		}
		.listing-post-style-13 .directorypress-listing-item-holder figure .price .field-content::after {
			border-bottom-color: {$price_tag_bg};
			border-left-color: {$price_tag_bg};
			border-top-color: {$price_tag_bg};
			{$price_tag_height}
		}
		.listing-post-style-13 .directorypress-listing-item-holder:hover figure .price .field-content::after {
			{$price_tag_border_top_color_hover}
			{$price_tag_border_left_color_hover}
			{$price_tag_border_bottom_color_hover}
		}
		
		.cz-listview .listing-post-style-listview_ultra .directorypress-listing-text-content-wrap .price span.field-content{
			background:{$price_tag_bg} !important;
		}
		
		.map-wrapper .directorypress-map_toggle_button.active{
			color:{$directorypress_primary_color} !important;
			border-color:{$directorypress_primary_color} !important;
			border-radius:4px;
			background:#fff !important;
		}
		
		.listing-post-style-listview_ultra .directorypress-listing-text-content-wrap {
			width:calc(100% - {$listview_width}px);
			width: -webkit-calc(100% - {$listview_width}px);
			width: -moz-calc(100% - {$listview_width}px);
			float:left;
		}
		.listing-post-style-listview_ultra figure,
		.listing-post-style-listview_mod figure{
			width:{$listview_width}px;
			float:left;
		}
		.listing-post-style-listview_mod .directorypress-listing-text-content-wrap {
			width:calc(100% - {$calc_content_width}px);
			width: -webkit-calc(100% - {$calc_content_width}px);
			width: -moz-calc(100% - {$calc_content_width}px);
			float:left;
		}
		.listing-post-style-listview_mod .list-author-content-area{
			height:{$listview_height}px;
		}
	");
	$whatsapp_url = PACZ_THEME_IMAGES .'/whatsapp-icon.png';
	DirectoryPress_Static_Files::addGlobalStyle("
		
		.mobile-author-btn-panel-container .mobile-author-btn-panel a:hover{
			color:{$pacz_settings['heading-color']};
			border-color:{$pacz_settings['heading-color']};
		}
		.mobile-author-btn-panel-container .mobile-author-btn-panel .mobile-author-whatsapp a i:before{
			content:url({$whatsapp_url});
		}
	");
}