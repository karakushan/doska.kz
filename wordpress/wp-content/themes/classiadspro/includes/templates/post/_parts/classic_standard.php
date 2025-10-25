<?php
	global $post, $pacz_settings;

	extract($atts);
	
    
	
	$sticky = (is_sticky())? 'sticky-post':'';
	
	echo '<article id="pacz-post-entry-' . get_the_ID() . '" class="pacz-post-classic '.$sticky.'">';
		
		/* Blog Thumbnail */
		if (has_post_thumbnail()) {
			$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true);
			if($cropping == 'true') {
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
		
		/* Blog Meta */
		echo '<div class="pacz-post-content-wrapper clearfix">';
			echo '<div class="pacz-post-publish-date">';
				echo '<span class="meta-publish-date">';
					the_time('d');
				echo '</span>';
				echo '<span class="meta-publish-month">';
					the_time('M');
				echo '</span>';
			echo '</div>';
			echo '<div class="pacz-post-meta clearfix">';
				echo '<div class="pacz-post-categories">';
					the_category(', ');
				echo '</div>';
				echo '<div class="pacz-post-views">';
					echo sprintf(esc_html__('%s views', 'classiadspro'), get_post_meta( get_the_ID(), 'pacz_post_views_count', true ));
				echo '</div>';
				echo '<div class="pacz-post-comments">';
					echo '<a href="' . get_permalink() . '#comments" class="blog-comments">';
						comments_number(esc_html__('0 Comments', 'classiadspro'), esc_html__('1 Comment', 'classiadspro'), esc_html__('% Comments', 'classiadspro'));
					echo '</a>';
				echo '</div>';
			echo '</div>';
		
			/* Blog Heading */
			echo '<div class="pacz-post-heading">';
				echo '<h2 class="pacz-post-title">';
					echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
				echo '</h2>';
			echo '</div>';
			
			/* Blog Content */
			echo '<div class="pacz-post-excerpt">';
				if($excerpt_length != 0) {
					if ($classic_excerpt == 'excerpt') {
						the_excerpt_max_charlength($excerpt_length); 	
					}else{
						str_replace(']]>', ']]&gt;', apply_filters('the_content', get_the_content()));	
					}
				}
			echo '</div>';
			
			/* Blog ReadMore */
			echo '<div class="pacz-post-readmore">';
				echo '<a class="pacz-post-readmore-link" title="'.get_the_title().'" href="'.get_permalink().'">'.esc_html__("Continue Reading","classiadspro").'</a>';
			echo '</div>';
		echo '</div>';
    echo '</article>';