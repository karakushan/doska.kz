<?php
	global $post, $pacz_settings;

	extract($atts);
			
	/* Blog Content */
	if( ('' !== $post->post_content) && $excerpt_length != 0) {
		echo '<div class="pacz-post-excerpt">';
			if ($classic_excerpt == 'excerpt') {
				the_excerpt_max_charlength($excerpt_length); 	
			}else{
				str_replace(']]>', ']]&gt;', apply_filters('the_content', get_the_content()));	
			}
		echo '</div>';
	}