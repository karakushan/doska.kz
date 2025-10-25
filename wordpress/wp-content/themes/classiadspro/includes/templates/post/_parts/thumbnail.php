<?php
	global $post, $pacz_settings;

	extract($atts);
	
    
		/* Blog Thumbnail */
	if (has_post_thumbnail()) {
		$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true);
		if($cropping) {
				$image_src = bfi_thumb($image_src_array[0], array(
					'width' => $image_width,
					'height' => $image_height,
					'crop' => true
				));
		} else {
			$image_src = $image_src_array[0];
		}
           echo '<div class="featured-image">';
			echo '<a title="' . get_the_title() . '" href="' . get_permalink() . '">';
				echo '<img alt="' . get_the_title() . '" title="' . get_the_title() . '" class="item-featured-image" width="' . $image_width . '" height="' . $image_height . '" src="' . $image_src . '" />';
			echo '</a>';
		echo '</div>';
    }