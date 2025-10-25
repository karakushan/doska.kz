<?php
	global $post, $pacz_settings;

	extract($atts);
	
	/* Blog ReadMore */
	if( '' !== $post->post_content ) {
		echo '<div class="pacz-post-readmore">';
			echo '<a class="pacz-post-readmore-link" title="'.get_the_title().'" href="'.get_permalink().'">'.esc_html__("Continue Reading","classiadspro").'</a>';
		echo '</div>';
	}