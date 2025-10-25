<?php
	global $post, $pacz_settings, $wp_embed;;

	extract($atts);

	/* Blog Video */
	$link = get_post_meta($post->ID, '_video_url', true);
	if ($link) {
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
				
		}  
        echo '<div class="pacz-video-wrapper">';
			echo '<div class="pacz-video-container" style="height: ' . esc_attr($image_height) . 'px;">';
				echo '<div class="pacz-post-video-button"><i class="pacz-icon-play"></i></div>';
				echo '<img alt="' . get_the_title() . '" title="' . get_the_title() . '" class="item-featured-image" width="' . esc_attr($image_width) . '" height="' . esc_attr($image_height) . '" src="' . esc_url($image_src) . '" />';
				echo $wp_embed->run_shortcode('[embed]' . esc_url($link) . '[/embed]'); // phpcs: OK
			echo '</div>';
		echo '</div>';
    }