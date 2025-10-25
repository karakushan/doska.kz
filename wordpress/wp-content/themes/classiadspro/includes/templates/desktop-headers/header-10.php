

<?php
	global $pacz_settings;
	$post_id = global_get_post_id();
	/* preset values */
	
	if($post_id) {
		$header_search_form_onpage = get_post_meta( $post_id, '_header_search_form', true );
		$header_search_form = $pacz_settings['_header_search_form'];
	}
	
	/* Header content */

	echo '<div class="classiads-fantro-logo">';
			do_action('header_logo');
		echo '</div>';
		echo '<div class="classiads-fantro-header-content">';
	  if(is_user_logged_in() && !empty($pacz_settings['loggedin_menu'])) {
          $menu_location = $pacz_settings['loggedin_menu'];
          do_action( 'vertical_navigation', $menu_location );
          do_action( 'main_navigation', $menu_location );
		  if($header_search_form){
			  if($header_search_form_onpage == 'true'){
				echo do_shortcode('[alsp-search search_form_type="4"]');
			  }
		  }
        }else{
			$pacz_menu_location = 'primary-menu';
          do_action( 'vertical_navigation', 'primary-menu' );
          do_action( 'main_navigation', 'primary-menu' );
		  if($header_search_form){
			  if($header_search_form_onpage == 'true'){
				echo do_shortcode('[alsp-search search_form_type="4"]');
			  }
		  }
        }
		echo '</div>';



