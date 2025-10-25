<?php
	// Figure
	echo '<figure class="directorypress-listing-figure">';
		do_action('directorypress_listing_grid_thumbnail', $listing);
		do_action('directorypress_listing_grid_bookmark', $listing, 2);
		do_action('directorypress_listing_grid_featured_tag', $listing);
		do_action('directorypress_listing_grid_status_tag', $listing);
		do_action('directorypress_wcfm_add_to_cart', $listing->post->ID, 'pacz-fic-shopping-basket');
	echo '</figure>';
	echo '<div class="clearfix directorypress-listing-text-content-wrap">';
		do_action('directorypress_listing_grid_author', $listing, 60);
		do_action('directorypress_listing_grid_category', $listing);
		do_action('directorypress_listing_grid_title', $listing);
		// fields
		echo '<div class="listing-content-fields">';
			do_action('directorypress_listing_grid_summary_field', $listing);
			do_action('directorypress_listing_grid_inline_fields', $listing);
			do_action('directorypress_listing_grid_block_fields', $listing);
		echo '</div>';
		do_action('directorypress_listing_grid_address', $listing);
		echo '<div class="listing-bottom-metas clearfix">';
			do_action('directorypress_listing_grid_views', $listing);
			do_action('directorypress_listing_grid_price_field', $listing);
		echo '</div>';
	echo '</div>';