<?php
	// Figure
	echo '<figure class="directorypress-listing-figure">';
		do_action('directorypress_listing_grid_thumbnail', $listing);
		do_action('directorypress_listing_grid_featured_tag', $listing);
		do_action('directorypress_listing_grid_status_tag', $listing);
		do_action('directorypress_wcfm_add_to_cart', $listing->post->ID, 'pacz-fic-shopping-basket');
	echo '</figure>';
	echo '<div class="clearfix directorypress-listing-text-content-wrap">';
		do_action('directorypress_listing_grid_price_field', $listing);	
		do_action('directorypress_listing_grid_title', $listing);
		//address
		do_action('directorypress_listing_grid_address', $listing);
	echo '</div>';	
	echo '<div class="listing-bottom-metas clearfix">';
		$time = strtotime( $listing->post->post_date );
		echo '<div class="publish_date">'.directorypress_time_ago($time).'</div>';
		do_action('directorypress_listing_grid_bookmark', $listing, 1);					
	echo '</div>';