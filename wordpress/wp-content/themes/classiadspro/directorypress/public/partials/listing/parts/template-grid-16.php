<?php
	// Figure
	echo '<figure class="directorypress-listing-figure">';
		do_action('directorypress_listing_grid_thumbnail', $listing);
		do_action('directorypress_listing_grid_featured_tag', $listing);
		do_action('directorypress_listing_grid_status_tag', $listing);
	echo '</figure>';
	echo '<div class="clearfix directorypress-listing-text-content-wrap">';
		do_action('directorypress_listing_grid_title', $listing);
	echo '</div>';	