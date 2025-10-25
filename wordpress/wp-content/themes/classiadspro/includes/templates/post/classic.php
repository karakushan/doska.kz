<?php

    global $post, $pacz_settings;
    extract($atts);
	$post_type = (get_post_format(get_the_id()) == '0' || get_post_format(get_the_id()) == '') ? 'image' : get_post_format(get_the_id());

	$sticky = (is_sticky())? 'sticky-post':'';
	$atts['category_meta'] = true;
	$atts['date_meta'] = false;
	$atts['author_meta'] = false;
	$atts['views_meta'] = true;
	$atts['comments_meta'] = true;
	if(has_post_thumbnail()){
		$thumbnail_class = ' has_thumbnail';
	}else{
		$thumbnail_class = ' no_thumbnail';
	}
	$atts['image_width'] = ($layout == 'full')? $grid_width : ($content_width / 100) * $grid_width;
	$atts['image_height'] = $pacz_settings['blog-single-image-height'];
	
	echo '<article id="pacz-post-entry-' . get_the_ID() . '" class="pacz-post-classic '.$sticky . $thumbnail_class .' pacz-post-format-'. $post_type .'">';
	
		switch ($post_type) {
			
			case 'image':
				pacz_display_template('post/_parts/thumbnail.php', array('atts' => $atts));
				break;
			case 'gallery':
				pacz_display_template('post/_parts/gallery.php', array('atts' => $atts));
				break;
			case 'video':
				pacz_display_template('post/_parts/video.php', array('atts' => $atts));
				break;
			case 'audio':
				pacz_display_template('post/_parts/audio.php', array('atts' => $atts));
				break;
			default:
				pacz_display_template('post/_parts/thumbnail.php', array('atts' => $atts));
				break;
		}
		echo '<div class="pacz-post-content-wrapper clearfix">';
			pacz_display_template('post/_parts/published_date.php', array('atts' => $atts));
			pacz_display_template('post/_parts/meta.php', array('atts' => $atts));
			pacz_display_template('post/_parts/heading.php', array('atts' => $atts));
			pacz_display_template('post/_parts/content.php', array('atts' => $atts));
			pacz_display_template('post/_parts/readmore.php', array('atts' => $atts));
		echo '</div>';
    echo '</article>';
	
