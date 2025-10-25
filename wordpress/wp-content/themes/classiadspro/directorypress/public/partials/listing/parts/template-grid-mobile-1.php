<?php
	echo '<figure class="directorypress-listing-figure">';
		do_action('directorypress_listing_grid_thumbnail', $listing);
		echo '<div class="listing-grid-buttons clearfix">';
			//do_action('directorypress_listing_grid_featured_tag', $listing);
			if ($listing->package->has_featured){
				echo '<span class="has_featured-tag-default"><i class="dicode-material-icons dicode-material-icons-star"></i></span>';
			}
			do_action('directorypress_listing_grid_status_tag', $listing);
			
		echo '</div>';
		do_action('directorypress_listing_grid_bookmark', $listing, 1);
		do_action('directorypress_wcfm_add_to_cart', $listing->post->ID, 'pacz-fic-shopping-basket');
		//do_action('directorypress_listing_grid_ratting', $listing);
	echo '</figure>';
	echo '<div class="clearfix directorypress-listing-text-content-wrap">';
		//do_action('directorypress_listing_grid_author', $listing, 60);
		//do_action('directorypress_listing_grid_category', $listing);
		do_action('directorypress_listing_grid_price_field', $listing);	
		do_action('directorypress_listing_grid_title', $listing);
		// fields
		//do_action('directorypress_listing_grid_inline_fields', $listing);
		//do_action('directorypress_listing_grid_block_fields', $listing);		
		//summary field		
		//do_action('directorypress_listing_grid_summary_field', $listing);
		$time = strtotime( $listing->post->post_date );
		echo '<div class="publish_date">'.directorypress_time_ago($time).'</div>';
	echo '</div>';	
	echo '<div class="listing-bottom-metas clearfix">';			
		//address
		do_action('directorypress_listing_grid_address', $listing);
		//echo '<div class="listing-ratting-icon-fields clearfix">';
			//echo '<div class="tooltip_fields clearfix">';
				//do_action('directorypress_listing_grid_bookmark', $listing, 2);				
				//do_action('directorypress_listing_grid_tooltip_fields', $listing);
			//echo '</div>';
		//echo '</div>';
	echo '</div>';