<?php
	global $post, $pacz_settings;

	extract($atts);

	$attachment_ids = get_post_meta(get_the_id(), '_gallery_images', true);
	$attachment_ids = explode(',', $attachment_ids);
	if($attachment_ids){
		echo '<div class="pacz-post-gallery">';
			foreach($attachment_ids AS $id){
				$image_src_array = wp_get_attachment_image_src($id, 'full', true);
				if($cropping) {
					$image_src = bfi_thumb($image_src_array[0], array(
						'width' => $image_width,
						'height' => $image_height,
						'crop' => true
					));
				} else {
					$image_src = $image_src_array[0];
				}
				echo '<div class="gallery-image">';
					echo '<a title="' . get_the_title() . '" href="' . get_permalink() . '">';
						echo '<img alt="' . get_the_title() . '" title="' . get_the_title() . '" class="item-featured-image" width="' . $image_width . '" height="' . $image_height . '" src="' . $image_src . '" />';
					echo '</a>';
				echo '</div>';
			}
		echo '</div>';
	}