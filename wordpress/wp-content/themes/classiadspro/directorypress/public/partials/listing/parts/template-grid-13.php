<?php
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	echo '<figure class="directorypress-listing-figure">';
		do_action('directorypress_listing_grid_thumbnail', $listing);
		do_action('directorypress_listing_grid_featured_tag', $listing);
		do_action('directorypress_listing_grid_price_field', $listing);
		do_action('directorypress_listing_grid_status_tag', $listing);
		do_action('directorypress_wcfm_add_to_cart', $listing->post->ID, 'pacz-fic-shopping-basket');
	echo '</figure>';
	echo '<div class="clearfix directorypress-listing-text-content-wrap">';
		echo '<div class="listing-content-left">';
			do_action('directorypress_listing_grid_title', $listing);
			do_action('directorypress_listing_grid_category', $listing);
		echo '</div>';
		echo '<div class="listing-content-right">';
			echo '<span id="post-cat-'.$listing->post->ID.'" class="listing-cat-icon1">';
				do_action('directorypress_listing_grid_category_icon', $listing);
			echo '</span>';
		echo '</div>';
	echo '</div>';
	if($DIRECTORYPRESS_ADIMN_SETTINGS['cat_icon_type_on_listing'] == 1){
		echo '<script>
			$("#post-'.$listing->post->ID.' .directorypress-listing-item-holder").hover(function(e) {
				$("#post-cat-'.$listing->post->ID.' .font-icon").css("background-color",e.type === "mouseenter"?"'.$cat_color.'":"transparent");
			});
		</script>';
	}