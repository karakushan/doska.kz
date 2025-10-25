<?php
	// Figure
	echo '<figure class="directorypress-listing-figure">';
		do_action('directorypress_listing_grid_thumbnail', $listing);
		do_action('directorypress_business_hours_status', $listing, false);
		echo '<div class="listing-grid-buttons clearfix">';
			do_action('directorypress_listing_grid_featured_tag', $listing);
			do_action('directorypress_listing_grid_status_tag', $listing);
		echo '</div>';
		do_action('directorypress_wcfm_add_to_cart', $listing->post->ID, 'pacz-fic-shopping-basket');
	echo '</figure>';
	// content
	echo '<div class="clearfix directorypress-listing-text-content-wrap">';
		do_action('directorypress_listing_grid_author', $listing, 60);
		echo '<div class="listing-grid-header-wrapper clearfix">';
			echo '<span class="listing-cat-icon1">';
				do_action('directorypress_listing_grid_category_icon', $listing);
			echo '</span>';
			do_action('directorypress_listing_grid_title', $listing);
		echo '</div>';
		do_action('directorypress_listing_grid_ratting', $listing);
		do_action('directorypress_listing_grid_bookmark', $listing, 4, 'dicode-material-icons dicode-material-icons-bookmark', 'dicode-material-icons dicode-material-icons-bookmark-outline');
		do_action('directorypress_listing_grid_summary_field', $listing);
	echo '</div>';	
	// bottom meta
	echo '<div class="listing-bottom-metas clearfix">';
		//address
		do_action('directorypress_listing_grid_address', $listing);
		do_action('directorypress_listing_grid_price_field', $listing);	
	echo '</div>';