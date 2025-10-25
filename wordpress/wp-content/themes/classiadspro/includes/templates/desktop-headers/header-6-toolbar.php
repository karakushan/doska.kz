<?php
	global $pacz_settings;
	$post_id = global_get_post_id();
	/* preset values */
	$header_structure = 'standard';
	$header_align = 'left';
	$header_grid = 'false';
	$sticky_header = 0;
	$squeeze_sticky_header = 0;
	
	$boxed_layout = $pacz_settings['body-layout'];
	$header_style = $trans_header_skin = $header_padding_class = $header_grid_margin = $trans_header_skin_class = $pacz_main_wrapper_class = '';
	
	$toolbar = 1;
	$toolbar_check = get_post_meta( $post_id, '_header_toolbar', true );
	$toolbar_option = !empty($toolbar_check) ? $toolbar_check : 'true';
	$header_toolbar_grid = 0;
	$pacz_logo_location = 'header_section';
	$pacz_logo_align = 'left' ;
	
	$header_toolbar_social_location = 'header_toolbar'; 
	$header_toolbar_social_align = 'right';
	$listing_btn_location = 'disabled';
	$listing_btn_align = 'right';

	$login_reg_btn_location = 'disabled';
	$login_reg_btn_align =  'right';
	
	$header_contact_details_location = 'header_toolbar' ;
	$header_contact_details_align = 'left' ;
	
	$boxed_header = 1;
	
	/* Header content */
	//if($header_structure != 'vertical') {
	if($toolbar && is_page()){
				if($toolbar_option == 'true'){
					if(isset($header_toolbar_grid) && $header_toolbar_grid == 1){
					echo '<div class="pacz-header-toolbar transparent-header pacz-grid">';
					}else{
					echo '<div class="pacz-header-toolbar">';
					}
						echo esc_attr($boxed_header) && $header_structure != 'vertical' ? '<div class="pacz-grid">' : '';
					  
						if($pacz_logo_location == 'header_toolbar' && $pacz_logo_align == 'left' )  {
							echo '<div class="logo-left"><ul>';
							do_action( 'header_logo' );
							echo'</ul></div>';
						}
						elseif($pacz_logo_location == 'header_toolbar' && $pacz_logo_align == 'center' )  {
							echo '<div class="logo-center"><ul>';
							do_action( 'header_logo' );
							echo'</ul></div>';
						}
						elseif($pacz_logo_location == 'header_toolbar' && $pacz_logo_align == 'right' )  {
							echo '<div class="logo-right"><ul>';
							do_action( 'header_logo' );
							echo'</ul></div>';
						}
						if($pacz_settings['checkout-box'] && $pacz_settings['checkout-box-location'] == 'header_toolbar' && $pacz_settings['checkout-box-align'] == 'left')   {
							echo '<ul class="header-checkout-left ">';
								do_action( 'header_checkout' );
							echo '</ul>';
						}
						elseif($pacz_settings['checkout-box'] && $pacz_settings['checkout-box-location'] == 'header_toolbar' && $pacz_settings['checkout-box-align'] == 'center')   {
							echo '<ul class="header-checkout-center ">';
								do_action( 'header_checkout' );
							echo '</ul>';
						}
						elseif($pacz_settings['checkout-box'] && $pacz_settings['checkout-box-location'] == 'header_toolbar' && $pacz_settings['checkout-box-align'] == 'right')   {
							echo '<ul class="header-checkout-right ">';
								do_action( 'header_checkout' );
							echo '</ul>';
						}
						if($listing_btn_location == 'header_toolbar' && $listing_btn_align == 'left' )  {
							echo '<div class="listing-btn-left"><ul>';
							do_action( 'nav_listing_btn' );
							echo'</ul></div>';
						}
						elseif($listing_btn_location == 'header_toolbar' && $listing_btn_align == 'center' )  {
							echo '<div class="listing-btn-center"><ul>';
							do_action( 'nav_listing_btn' );
							echo'</ul></div>';
						}
						elseif($listing_btn_location == 'header_toolbar' && $listing_btn_align == 'right' )  {
							echo '<div class="listing-btn-right"><ul>';
							do_action( 'nav_listing_btn' );
							echo'</ul></div>';
						}
						if($login_reg_btn_align == 'left' && $login_reg_btn_location == 'header_toolbar'){
							echo '<div class="header-toolbar-log-reg-btn aligh-left">';
							do_action('header_logreg');
							echo '</div>';
						}elseif($login_reg_btn_align == 'center' && $login_reg_btn_location == 'header_toolbar'){
							echo '<div class="header-toolbar-log-reg-btn aligh-center">';
							do_action('header_logreg');
							echo '</div>';
						}elseif($login_reg_btn_align == 'right' && $login_reg_btn_location == 'header_toolbar'){
							echo '<div class="header-toolbar-log-reg-btn aligh-right">';
							do_action('header_logreg');
							echo '</div>';
						}
						if($header_contact_details_location == 'header_toolbar' && $header_contact_details_align == 'left' ) {
							echo '<div class="contact-left">';
								do_action('header_toolbar_contact');
							echo'</div>';
						}
						elseif($header_contact_details_location == 'header_toolbar' && $header_contact_details_align == 'center' ) {
							echo '<div class="contact-center">';
								do_action('header_toolbar_contact');
							echo'</div>';
						}
						elseif($header_contact_details_location == 'header_toolbar' && $header_contact_details_align == 'right' ) {
							echo '<div class="contact-right">';
								do_action('header_toolbar_contact');
							echo'</div>';
						}
						if($header_toolbar_social_location == 'header_toolbar' && $header_toolbar_social_align == 'left' ) {
							echo '<ul class="pacz-header-toolbar-social left ">';
								do_action('header_toolbar_social');
							echo '</ul>';
						}
						elseif($header_toolbar_social_location == 'header_toolbar' && $header_toolbar_social_align == 'center' ) {
							echo '<ul class="pacz-header-toolbar-social center ">';
								do_action('header_toolbar_social');
							echo '</ul>';
						}
						elseif($header_toolbar_social_location == 'header_toolbar' && $header_toolbar_social_align == 'right' ) {
							echo '<ul class="pacz-header-toolbar-social right ">';
								do_action('header_toolbar_social');
							echo '</ul>';
						}
						do_action('header_toolbar_menu');
						
						echo esc_attr($boxed_header) && $header_structure != 'vertical' ? '</div>' : '' ;
					echo '</div>';
					echo '<div class="pacz-responsive-header-toolbar"><a href="#" class="pacz-toolbar-responsive-icon"><i class="pacz-icon-chevron-down"></i></a></div>';
				}
			
			}else if($toolbar){
				if($toolbar){
					if(isset($header_toolbar_grid) && $header_toolbar_grid == 1){
					echo '<div class="pacz-header-toolbar transparent-header pacz-grid">';
					}else{
					echo '<div class="pacz-header-toolbar">';
					}
						echo esc_attr($boxed_header) && $header_structure != 'vertical' ? '<div class="pacz-grid">' : '';
					  
						if($pacz_logo_location == 'header_toolbar' && $pacz_logo_align == 'left' )  {
							echo '<div class="logo-left"><ul>';
							do_action( 'header_logo' );
							echo'</ul></div>';
						}
						elseif($pacz_logo_location == 'header_toolbar' && $pacz_logo_align == 'center' )  {
							echo '<div class="logo-center"><ul>';
							do_action( 'header_logo' );
							echo'</ul></div>';
						}
						elseif($pacz_logo_location == 'header_toolbar' && $pacz_logo_align == 'right' )  {
							echo '<div class="logo-right"><ul>';
							do_action( 'header_logo' );
							echo'</ul></div>';
						}
						if($pacz_settings['checkout-box'] && $pacz_settings['checkout-box-location'] == 'header_toolbar' && $pacz_settings['checkout-box-align'] == 'left')   {
							echo '<ul class="header-checkout-left ">';
								do_action( 'header_checkout' );
							echo '</ul>';
						}
						elseif($pacz_settings['checkout-box'] && $pacz_settings['checkout-box-location'] == 'header_toolbar' && $pacz_settings['checkout-box-align'] == 'center')   {
							echo '<ul class="header-checkout-center ">';
								do_action( 'header_checkout' );
							echo '</ul>';
						}
						elseif($pacz_settings['checkout-box'] && $pacz_settings['checkout-box-location'] == 'header_toolbar' && $pacz_settings['checkout-box-align'] == 'right')   {
							echo '<ul class="header-checkout-right ">';
								do_action( 'header_checkout' );
							echo '</ul>';
						}
						if($listing_btn_location == 'header_toolbar' && $listing_btn_align == 'left' )  {
							echo '<div class="listing-btn-left"><ul>';
							do_action( 'nav_listing_btn' );
							echo'</ul></div>';
						}
						elseif($listing_btn_location == 'header_toolbar' && $listing_btn_align == 'center' )  {
							echo '<div class="listing-btn-center"><ul>';
							do_action( 'nav_listing_btn' );
							echo'</ul></div>';
						}
						elseif($listing_btn_location == 'header_toolbar' && $listing_btn_align == 'right' )  {
							echo '<div class="listing-btn-right"><ul>';
							do_action( 'nav_listing_btn' );
							echo'</ul></div>';
						}
						if($login_reg_btn_align == 'left' && $login_reg_btn_location == 'header_toolbar'){
							echo '<div class="header-toolbar-log-reg-btn aligh-left">';
							do_action('header_logreg');
							echo '</div>';
						}elseif($login_reg_btn_align == 'center' && $login_reg_btn_location == 'header_toolbar'){
							echo '<div class="header-toolbar-log-reg-btn aligh-center">';
							do_action('header_logreg');
							echo '</div>';
						}elseif($login_reg_btn_align == 'right' && $login_reg_btn_location == 'header_toolbar'){
							echo '<div class="header-toolbar-log-reg-btn aligh-right">';
							do_action('header_logreg');
							echo '</div>';
						}
						if($header_contact_details_location == 'header_toolbar' && $header_contact_details_align == 'left' ) {
							echo '<div class="contact-left">';
								do_action('header_toolbar_contact');
							echo'</div>';
						}
						elseif($header_contact_details_location == 'header_toolbar' && $header_contact_details_align == 'center' ) {
							echo '<div class="contact-center">';
								do_action('header_toolbar_contact');
							echo'</div>';
						}
						elseif($header_contact_details_location == 'header_toolbar' && $header_contact_details_align == 'right' ) {
							echo '<div class="contact-right">';
								do_action('header_toolbar_contact');
							echo'</div>';
						}
						if($header_toolbar_social_location == 'header_toolbar' && $header_toolbar_social_align == 'left' ) {
							echo '<ul class="pacz-header-toolbar-social left ">';
								do_action('header_toolbar_social');
							echo '</ul>';
						}
						elseif($header_toolbar_social_location == 'header_toolbar' && $header_toolbar_social_align == 'center' ) {
							echo '<ul class="pacz-header-toolbar-social center ">';
								do_action('header_toolbar_social');
							echo '</ul>';
						}
						elseif($header_toolbar_social_location == 'header_toolbar' && $header_toolbar_social_align == 'right' ) {
							echo '<ul class="pacz-header-toolbar-social right ">';
								do_action('header_toolbar_social');
							echo '</ul>';
						}
						do_action('header_toolbar_menu');
						
						echo esc_attr($boxed_header) && $header_structure != 'vertical' ? '</div>' : '' ;
					echo '</div>';
					echo '<div class="pacz-responsive-header-toolbar"><a href="#" class="pacz-toolbar-responsive-icon"><i class="pacz-icon-chevron-down"></i></a></div>';
				}
			}
	//}


