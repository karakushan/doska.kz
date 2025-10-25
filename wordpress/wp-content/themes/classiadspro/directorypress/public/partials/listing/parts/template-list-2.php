<?php
	// style list Ultra
	echo '<figure class="directorypress-listing-figure">';
		do_action('directorypress_listing_listview_thumbnail', $listing);
		do_action('directorypress_listing_grid_status_tag', $listing);
	echo '</figure>';
	echo '<div class="clearfix directorypress-listing-text-content-wrap">';
		do_action('directorypress_listing_grid_category', $listing);
		echo '<div class="listing-listview-buttons clearfix">';
			do_action('directorypress_listing_grid_price_field', $listing);
			echo '<span class="has_featured-tag-default"><i class="fas fa-star"></i></span>';
			do_action('directorypress_listing_grid_bookmark', $listing, 1);
			do_action('directorypress_wcfm_add_to_cart', $listing->post->ID, 'pacz-fic-shopping-basket');
		echo '</div>';
		do_action('directorypress_listing_grid_title', $listing);
		echo '<div class="listing-location-wrapper clearfix">';
			do_action('directorypress_listing_grid_address', $listing);
		echo '</div>';
		echo '<div class="listing-metas clearfix">';
			echo '<div class="listing-meta-left">';
				echo '<em class="directorypress-listing-date" itemprop="dateCreated" datetime="'.date("Y-m-d", mysql2date('U', $listing->post->post_date)).'T'.date("H:i", mysql2date('U', $listing->post->post_date)).'"><i class="directorypress-fic3-clock-circular-outline"></i>'. get_the_date().'</em>';
				do_action('directorypress_listing_grid_views', $listing);
			echo '</div>';
			echo '<div class="listing-meta-right">';
				do_action('directorypress_listing_grid_ratting', $listing);
			echo '</div>';
		echo '</div>';
	echo '</div>';