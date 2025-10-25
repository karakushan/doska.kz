<?php
	global $post, $pacz_settings;

	extract($atts);
	
	/* Blog Heading */
	echo '<div class="pacz-post-heading">';
		echo '<h2 class="pacz-post-title">';
			echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
		echo '</h2>';
	echo '</div>';