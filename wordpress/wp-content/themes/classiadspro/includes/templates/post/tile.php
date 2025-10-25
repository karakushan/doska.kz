<?php

    global $post, $pacz_settings;
	
	extract($atts);
    $post_type = (get_post_format(get_the_id()) == '0' || get_post_format(get_the_id()) == '') ? 'image' : get_post_format(get_the_id());
	$atts['category_meta'] = false;
	$atts['date_meta'] = true;
	$atts['author_meta'] = false;
	$atts['views_meta'] = false;
	$atts['comments_meta'] = false;
	$sticky = (is_sticky())? 'sticky-post':'';
	if($column == 1){
		$col = 'col-md-12';
	}elseif($column == 2){
		$col = 'col-lg-6 col-md-6 col-sm-12';
	}
	elseif($column == 3){
		$col = 'col-lg-4 col-md-6 col-sm-12';
	}elseif($column == 4){
		$col = 'col-lg-3 col-md-6 col-sm-12';
	}
	if(has_post_thumbnail()){
		$thumbnail_class = ' has_thumbnail';
	}else{
		$thumbnail_class = ' no_thumbnail';
	}
	
	echo '<div class="'. $col .'">';
		echo '<article id="pacz-post-entry-' . get_the_ID() . '" class="pacz-post-tile '. $sticky . $thumbnail_class .' pacz-post-format-'. $post_type .'">';
		
			switch ($post_type) {
				
				case 'image':
					if (has_post_thumbnail()) {
						echo '<div class="pacz-post-thumbnail-wrapper">';
							pacz_display_template('post/_parts/thumbnail.php', array('atts' => $atts));
						echo '</div>';
					}
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
				//if ((!has_post_thumbnail() && ($post_type == 'image' || $post_type == 'standard')) || ($post_type == 'gallery' || $post_type == 'audio' || $post_type == 'video')) {
					//pacz_display_template('post/_parts/published_date.php', array('atts' => $atts));
				//}
				
				pacz_display_template('post/_parts/heading.php', array('atts' => $atts));
				pacz_display_template('post/_parts/meta.php', array('atts' => $atts));
				pacz_display_template('post/_parts/content.php', array('atts' => $atts));
				pacz_display_template('post/_parts/readmore.php', array('atts' => $atts));
			echo '</div>';
		echo '</article>';
	echo '</div>';