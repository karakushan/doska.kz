

<?php
	global $pacz_settings, $DIRECTORYPRESS_ADIMN_SETTINGS;
	$post_id = global_get_post_id();
	$logedin_menu = (isset($post_id) && is_user_logged_in() && get_post_meta( $post_id, '_menu_location', true ))? get_post_meta( $post_id, '_menu_location', true ):$pacz_settings['loggedin_menu'];
	$normal_menu = (isset($post_id) && !is_user_logged_in() && get_post_meta( $post_id, '_menu_location_normal', true ))? get_post_meta( $post_id, '_menu_location_normal', true ):'primary-menu';
	if(is_user_logged_in() && !empty($pacz_settings['loggedin_menu'])) {
		$menu_location = (!empty($logedin_menu))? $logedin_menu: 'primary-menu';
	}else{
		$menu_location = (!empty($normal_menu))? $normal_menu: 'primary-menu';
	}
 ?>


<header id="pacz-header" class="mobile-header">
<?php
 
  echo '<div class="pacz-header-mobile1">';
		 echo '<div class="pacz-mobile-header-top clearfix">';
			echo '<div class="pacz-mobile-logo-wrap pull-left">';
				do_action('header_mobile_logo');
			echo'</div>';
			echo '<div class="pacz-mobile-header-content-wrap pull-right">';
				do_action('responsive_nav_trigger_link');
				if($pacz_settings['mobile-listing-button']){
					do_action( 'pacz_nav_mobile_listing_btn' );
				}
				if($pacz_settings['mobile-login-button']){
					echo '<div class="pacz-mobile-login-wrap">';
						do_action('pacz_mobile_header_login');
					echo '</div>';
				}
				if($pacz_settings['mobile-search-button']){
					do_action( 'responsive_nav_listing_search_link');
				}
			echo'</div>';
		echo'</div>';
    echo '</div>';

?>
</header>

<div class="responsive-search-form-container">
	<?php if(class_exists('alsp_plugin')): ?>
		<?php echo do_shortcode('[alsp-search search_form_type="4"]'); ?>
	<?php elseif(class_exists('DirectoryPress')): ?>
		<?php echo do_shortcode('[directorypress-search radius="'. esc_attr($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_radius_search_default']) .'" search_form_type="4" keyword_field_width="100" location_field_width="100" button_field_width="100"]'); ?>
	<?php endif; ?>
</div>
<div class="mobile-responsive-nav-container responsive-nav-container">
	<span class="res-menu-close pacz-fic5-close-1"></span>
	<?php do_action('pacz_header_login_active_menu_mobile'); ?>
	<?php do_action( 'main_navigation', $menu_location ); ?>
</div>


