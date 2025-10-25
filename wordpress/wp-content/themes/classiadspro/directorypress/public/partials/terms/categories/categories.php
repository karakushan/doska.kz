<?php
 
	global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
	$slider_arrow_position = (isset($instance->slider_arrow_position))? $instance->slider_arrow_position: 'absolute';
	$slider_arrow_icon_pre = (!empty($instance->slider_arrow_icon_pre))? $instance->slider_arrow_icon_pre : 'fas fa-angle-left';
	$slider_arrow_icon_next = (!empty($instance->slider_arrow_icon_next))? $instance->slider_arrow_icon_next : 'fas fa-angle-right';

	$slick_attrs = 'data-items="'. $instance->desktop_items .'"';
	$slick_attrs .= 'data-items-tablet="'. $instance->tab_items .'"';
	$slick_attrs .= 'data-items-mobile="'. $instance->mobile_items .'"';
	$slick_attrs .= 'data-slide-to-scroll="1"';
	$slick_attrs .= 'data-slide-speed="1000"';
	$slick_attrs .= ($instance->autoplay)? 'data-autoplay="true"' : 'data-autoplay="false"';
	$slick_attrs .= 'data-center-padding=""';
	$slick_attrs .= 'data-center="false"';
	$slick_attrs .= 'data-autoplay-speed="'. $instance->autoplay_speed .'"';
	$slick_attrs .= ($instance->loop)? 'data-loop="true"' : 'data-loop="false"';
	$slick_attrs .= 'data-list-margin="'. $instance->gutter .'"';
	$slick_attrs .= ($instance->owl_nav)? 'data-arrow="true"': 'data-arrow="false"';
	$slick_attrs .= 'data-prev-arrow="'. $slider_arrow_icon_pre .'"';
	$slick_attrs .= 'data-next-arrow="'. $slider_arrow_icon_next .'"';
	$slick_attrs .= 'data-arrow-postion ="'. $slider_arrow_position .'"';
	$instance->slick_attrs = $slick_attrs;
	
	if ($instance->cat_style == 1){
		directorypress_display_template('partials/terms/categories/parts/category-style-default.php', array('instance' => $instance, 'terms' => $terms));
	}elseif ($instance->cat_style == 2){
		directorypress_display_template('partials/terms/categories/parts/category-style-2.php', array('instance' => $instance, 'terms' => $terms));
	}elseif ($instance->cat_style == 3){
		directorypress_display_template('partials/terms/categories/parts/category-style-3.php', array('instance' => $instance, 'terms' => $terms));
	}elseif ($instance->cat_style == 4){
		directorypress_display_template('partials/terms/categories/parts/category-style-4.php', array('instance' => $instance, 'terms' => $terms));
	}elseif ($instance->cat_style == 5){
		directorypress_display_template('partials/terms/categories/parts/category-style-5.php', array('instance' => $instance, 'terms' => $terms));
	}elseif ($instance->cat_style == 6){
		directorypress_display_template('partials/terms/categories/parts/category-style-6.php', array('instance' => $instance, 'terms' => $terms));
	}elseif ($instance->cat_style == 7){
		directorypress_display_template('partials/terms/categories/parts/category-style-7.php', array('instance' => $instance, 'terms' => $terms));
	}elseif ($instance->cat_style == 8){
		directorypress_display_template('partials/terms/categories/parts/category-style-8.php', array('instance' => $instance, 'terms' => $terms));
	}elseif ($instance->cat_style == 9){
		directorypress_display_template('partials/terms/categories/parts/category-style-9.php', array('instance' => $instance, 'terms' => $terms));
	}elseif ($instance->cat_style == 10){
		directorypress_display_template('partials/terms/categories/parts/category-style-10.php', array('instance' => $instance, 'terms' => $terms));
	}elseif ($instance->cat_style == 11){
		directorypress_display_template('partials/terms/categories/parts/category-style-11.php', array('instance' => $instance, 'terms' => $terms));
	}elseif ($instance->cat_style == 12){
		directorypress_display_template('partials/terms/categories/parts/category-style-12.php', array('instance' => $instance, 'terms' => $terms));
	}elseif ($instance->cat_style == 'advanced'){
		directorypress_display_template('partials/terms/categories/parts/category-style-advanced.php', array('instance' => $instance, 'terms' => $terms));
	}elseif ($instance->cat_style == 'advanced-term-slider'){
		directorypress_display_template('partials/terms/categories/parts/category-style-advanced2.php', array('instance' => $instance, 'terms' => $terms));
	}else{
		directorypress_display_template('partials/terms/categories/parts/category-style-default.php', array('instance' => $instance, 'terms' => $terms));
	}
	
	/* custom styles */
	
		$directorypress_styles = '';
		$category_id = '#directorypress-category-'.$instance->args['id'];
		$id = $instance->args['id'];
		
		$cat_font_size = (isset($instance->cat_font_size) && !empty($instance->cat_font_size))? ('font-size:' . $instance->cat_font_size . 'px;') : '';
		$cat_font_weight = (isset($instance->cat_font_weight) && !empty($instance->cat_font_weight))? ('font-weight:' . $instance->cat_font_weight . ';') : '';
		$cat_font_line_height = (isset($instance->cat_font_line_height) && !empty($instance->cat_font_line_height))? ('line-height:' . $instance->cat_font_line_height . 'px;') : '';
		$cat_font_transform = (isset($instance->cat_font_transform) && !empty($instance->cat_font_transform)) ? ('text-transform: ' . $instance->cat_font_transform . ';') : '';
		
		$parent_cat_title_color = (isset($instance->parent_cat_title_color) && !empty($instance->parent_cat_title_color))? ('color:' . $instance->parent_cat_title_color . ' !important;') : '';
		$parent_cat_title_color_hover = (isset($instance->parent_cat_title_color_hover)  && !empty($instance->parent_cat_title_color_hover))? ('color:' . $instance->parent_cat_title_color_hover . ' !important;') : '';
		
		$child_cat_font_size = (isset($instance->child_cat_font_size) && !empty($instance->child_cat_font_size))? ('font-size:' . $instance->child_cat_font_size . 'px;') : '';
		$child_cat_font_weight = (isset($instance->child_cat_font_weight) && !empty($instance->child_cat_font_weight))? ('font-weight:' . $instance->child_cat_font_weight . ';') : '';
		$child_cat_font_line_height = (isset($instance->child_cat_font_line_height) && !empty($instance->child_cat_font_line_height))? ('line-height:' . $instance->child_cat_font_line_height . 'px;') : '';
		$child_cat_font_transform = (isset($instance->child_cat_font_transform) && !empty($instance->child_cat_font_transform)) ? ('text-transform: ' . $instance->child_cat_font_transform . ';') : '';
		
		$subcategory_title_color = (isset($instance->subcategory_title_color) && !empty($instance->subcategory_title_color))? ('color:' . $instance->subcategory_title_color . ' !important;') : '';
		$subcategory_title_color_hover = (isset($instance->subcategory_title_color_hover) && !empty($instance->subcategory_title_color_hover))? ('color:' . $instance->subcategory_title_color_hover . ' !important;') : '';
		
		if(isset($instance->cat_bg) && !empty($instance->cat_bg)){
			DirectoryPress_Static_Files::addCSS('
				.theme-page-wrapper '.$category_id.'.cat-style-1 .directorypress-category-holder .directorypress-parent-category a .cat-icon,
				'.$category_id.'.cat-style-1 .directorypress-category-holder .directorypress-parent-category a .cat-icon,
				'.$category_id.'.cat-style-2 .directorypress-category-holder .directorypress-parent-category a .cat-icon,
				'.$category_id.'.cat-style-3 .directorypress-category-holder,
				'.$category_id.'.cat-style-4 .directorypress-category-holder,
				'.$category_id.'.cat-style-5 .directorypress-category-holder,
				'.$category_id.'.cat-style-6 .directorypress-category-holder,
				'.$category_id.'.cat-style-7 .directorypress-category-holder,
				'.$category_id.'.cat-style-8 .directorypress-category-holder,
				'.$category_id.'.cat-style-9 .directorypress-category-holder{
					background:'.$instance->cat_bg .';
				}
			', $id);
		}
		if(isset($instance->cat_bg_hover) && !empty($instance->cat_bg_hover)){
			DirectoryPress_Static_Files::addCSS('
				.theme-page-wrapper '.$category_id.'.cat-style-1 .directorypress-category-holder .directorypress-parent-category a:hover .cat-icon,
				.theme-page-wrapper '.$category_id.'.cat-style-1 .directorypress-category-holder .directorypress-parent-category a .cat-icon:hover,
				'.$category_id.'.cat-style-1 .directorypress-category-holder .directorypress-parent-category a:hover .cat-icon,
				'.$category_id.'.cat-style-1 .directorypress-category-holder .directorypress-parent-category a .cat-icon:hover,
				'.$category_id.'.cat-style-2 .directorypress-category-holder .directorypress-parent-category a .cat-icon:hover,
				'.$category_id.'.cat-style-2 .directorypress-category-holder .directorypress-parent-category a:hover .cat-icon,
				'.$category_id.'.cat-style-3 .directorypress-category-holder:hover,
				'.$category_id.'.cat-style-4 .directorypress-category-holder:hover,
				'.$category_id.'.cat-style-5 .directorypress-category-holder:hover,
				'.$category_id.'.cat-style-6 .directorypress-category-holder:hover,
				'.$category_id.'.cat-style-7 .directorypress-category-holder:hover,
				'.$category_id.'.cat-style-8 .directorypress-category-holder:hover,
				'.$category_id.'.cat-style-9 .directorypress-category-holder:hover{
					background:'.$instance->cat_bg_hover.';
				}
			', $id);
		}
		if(isset($instance->cat_border_color) && !empty($instance->cat_border_color)){
			DirectoryPress_Static_Files::addCSS('
				'.$category_id.'.cat-style-6 .directorypress-category-holder{
					box-shadow: 0 2px 0 0 '.$instance->cat_border_color.';
					border-color: '.$instance->cat_border_color.';
				}
			', $id);
		}
		if(isset($instance->cat_border_color_hover) && !empty($instance->cat_border_color_hover)){
			DirectoryPress_Static_Files::addCSS('
				'.$category_id.'.cat-style-6 .directorypress-category-holder:hover{
					box-shadow: 0 2px 0 0 '.$instance->cat_border_color_hover.';
					border-color: '.$instance->cat_border_color_hover.';
				}
			', $id);
		}
		if(isset($instance->parent_cat_title_bg) && !empty($instance->parent_cat_title_bg) && $instance->cat_style == 6){
			DirectoryPress_Static_Files::addCSS('
				'.$category_id.'.cat-style-6 .directorypress-category-holder .directorypress-parent-category{
					background:'.$instance->parent_cat_title_bg.' !important;
				}
			', $id);
		}
		if(isset($instance->parent_cat_title_bg_hover) && !empty($instance->parent_cat_title_bg_hover) && $instance->cat_style == 6){
			DirectoryPress_Static_Files::addCSS('
				'.$category_id.'.cat-style-6 .directorypress-category-holder:hover .directorypress-parent-category{
					background:'.$instance->parent_cat_title_bg_hover.' !important;
				}
			', $id);
		}
		if(isset($instance->cat_border_color) && !empty($instance->cat_border_color) && $instance->cat_style == 6){
			DirectoryPress_Static_Files::addCSS('
				'.$category_id.'.cat-style-6 .directorypress-categories-wrapper .directorypress-category-holder .subcategories ul li a.view-all-btn{
					border-color: '.$instance->cat_border_color.';
				}
			', $id);
		}
		if(isset($instance->cat_border_color_hover) && !empty($instance->cat_border_color_hover) && $instance->cat_style == 6){
			DirectoryPress_Static_Files::addCSS('
				'.$category_id.'.cat-style-6 .directorypress-categories-wrapper .directorypress-category-holder:hover .subcategories ul li a.view-all-btn{
					border-color: '.$instance->cat_border_color_hover.';
				}
			', $id);
		}
		if(isset($instance->cat_border_color) && !empty($instance->cat_border_color) && $instance->cat_style == 7){
			DirectoryPress_Static_Files::addCSS('
				'.$category_id.'.cat-style-7 .directorypress-category-holder{
					border-color: '.$instance->cat_border_color.';
				}
			', $id);
		}
		if(isset($instance->cat_border_color_hover) && !empty($instance->cat_border_color_hover) && $instance->cat_style == 7){
			DirectoryPress_Static_Files::addCSS('
				'.$category_id.'.cat-style-7 .directorypress-category-holder:hover{
					border-color: '.$instance->cat_border_color_hover.';
				}
			', $id);
		}
		DirectoryPress_Static_Files::addCSS('
			'.$category_id.' .directorypress-parent-category a{
				'.$parent_cat_title_color.'
				'.$cat_font_size.'
				'.$cat_font_weight.'
				'.$cat_font_line_height.'
				'.$cat_font_transform.'
			}
			'.$category_id.' .directorypress-parent-category a:hover,
			'.$category_id.'.cat-style-3 .directorypress-category-holder:hover .directorypress-parent-category a,
			'.$category_id.'.cat-style-4 .directorypress-category-holder:hover .directorypress-parent-category a,
			'.$category_id.'.cat-style-5 .directorypress-category-holder:hover .directorypress-parent-category a,
			'.$category_id.'.cat-style-6 .directorypress-category-holder:hover .directorypress-parent-category a,
			'.$category_id.'.cat-style-7 .directorypress-category-holder:hover .directorypress-parent-category a,
			'.$category_id.'.cat-style-9 .directorypress-category-holder:hover .directorypress-parent-category a{
				'.$parent_cat_title_color_hover.'
			}
			'.$category_id.' .subcategories ul li a,
			'.$category_id.' .subcategories ul li a span{
				'.$subcategory_title_color.'
				'.$child_cat_font_size.'
				'.$child_cat_font_weight.'
				'.$child_cat_font_line_height.'
				'.$child_cat_font_transform.'
			}
			'.$category_id.' .subcategories ul li a:hover,
			'.$category_id.' .subcategories ul li a:hover span{
				'.$subcategory_title_color_hover.'
			}
			.cat-style-7 .directorypress-category-holder:hover .cat-7-icon .cat-icon.font-icon{
				color:#fff !important;
			}
		', $id);