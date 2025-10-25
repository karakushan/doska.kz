<?php
	// Figure
	echo '<figure class="directorypress-listing-figure">';
		do_action('directorypress_listing_grid_thumbnail', $listing);
		do_action('directorypress_listing_grid_price_field', $listing);
		do_action('directorypress_listing_grid_featured_tag', $listing);
		do_action('directorypress_listing_grid_status_tag', $listing);
		do_action('directorypress_wcfm_add_to_cart', $listing->post->ID, 'pacz-fic-shopping-basket');
	echo '</figure>';
	echo '<div class="clearfix directorypress-listing-text-content-wrap">';
		echo '<span class="listing-cat-icon1">';
			do_action('directorypress_listing_grid_category_icon', $listing);
		echo '</span>';
		do_action('directorypress_listing_grid_title', $listing);
	echo '</div>';