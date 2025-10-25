<?php
	global $post, $pacz_settings;

	extract($atts);
		
	/* Blog Meta */

	echo '<div class="pacz-post-publish-date">';
		echo '<span class="meta-publish-date">';
			the_time('d');
		echo '</span>';
		echo '<span class="meta-publish-month">';
			the_time('M');
		echo '</span>';
	echo '</div>';