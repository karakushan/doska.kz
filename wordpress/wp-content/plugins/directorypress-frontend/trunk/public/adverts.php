<?php
	echo'<div class="row listing-counts-wrap clearfix">';
		echo'<div class="total-listing-count col-lg-3 col-md-6 col-sm-6">';
			echo'<div class="total-listing-count-item total">';
				echo '<span class="listing-number">'. esc_html($public_handler->listings_count) .'</span>';
				echo '<span class="listing-conut-text">'.esc_html__('Total Listing', 'directorypress-frontend').'</span>';
			echo'</div>';
		echo'</div>';
		echo'<div class="total-listing-count col-lg-3 col-md-6 col-sm-6">';
			echo'<div class="total-listing-count-item active">';
				echo '<span class="listing-number">'. esc_html($public_handler->listings_count2) .'</span>';
				echo '<span class="listing-conut-text">'.esc_html__('Active Listing', 'directorypress-frontend').'</span>';
			echo'</div>';
		echo'</div>';
		echo'<div class="total-listing-count col-lg-3 col-md-6 col-sm-6">';
			echo'<div class="total-listing-count-item expired">';
				echo '<span class="listing-number">'. esc_html($public_handler->listings_count3) .'</span>';
				echo '<span class="listing-conut-text">'.esc_html__('Expired Listing', 'directorypress-frontend').'</span>';
			echo'</div>';
		echo'</div>';
		echo'<div class="total-listing-count col-lg-3 col-md-6 col-sm-6">';
			echo'<div class="total-listing-count-item pending">';
				echo '<span class="listing-number">'. esc_html($public_handler->listings_count4) .'</span>';
				echo '<span class="listing-conut-text">'.esc_html__('Pending Approval', 'directorypress-frontend').'</span>';
			echo'</div>';
		echo'</div>';
	echo'</div>';
	if ($public_handler->listings){
		echo '<div class="directorypress-table directorypress-table-striped clearfix">';
			echo '<div class="dashboard-listing-header">'. esc_html__('Your Listings', 'directorypress-frontend') .'</div>';
			echo '<div id="panel-listing-ajax-response"></div>';
			if(wp_is_mobile() && (!strpos($_SERVER['HTTP_USER_AGENT'], 'iPad'))){
				dpfl_renderTemplate('partials/listing/mobile.php', array('public_handler' => $public_handler));
			}else{
				dpfl_renderTemplate('partials/listing/desktop.php', array('public_handler' => $public_handler));
			}
		echo '</div>';
		directorypress_pagination_display($public_handler->query, '', false);
	}