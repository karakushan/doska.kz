<?php
/**
 *
 * @author  Designinvento
 * @copyright Copyright (c) Designinvento
 * @link  https://designinvento.net
 * @since  Version 2.0
 * @package  ClassiadsPro Framework
 */


add_action('blog_related_posts', 'pacz_blog_related_posts');

if ( !function_exists( 'pacz_blog_related_posts' ) ) {
	function pacz_blog_related_posts( $layout ) {
		global $post, $pacz_settings;

		//if($pacz_settings['blog-single-related-posts'] == 0 ) return;

		$output = '';
		$width = 259;
		$height = 161;

		if ( $layout == 'full' ) {
			$showposts = 2;
			$column_css = 'four-column';
		} else {
			$showposts = 2;
			$column_css = 'three-column';
		}
		$paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);

		$related = pacz_get_related_posts( $post->ID, $showposts, true );
		//$related['paged'] = $paged;
		//$related['posts_per_page'] = $paged;
		$grid_width    = $pacz_settings['grid-width'];
				$content_width = $pacz_settings['content-width'];
				//$item_id = (!empty($item_id)) ? $item_id : 1409305847;
				$atts   = array(
					'layout' => 'right',
					'style' => 'grid',
					'column' => '2',
					'image_height' => 380,
					'disable_meta' => 'false',
					'thumb_column' => 1,
					'classic_excerpt' => 'excerpt',
					'grid_avatar' => 'true',
					'read_more' => 'true',
					'grid_width' => $grid_width,
					'content_width' => $content_width,
					'image_width' => 740,
					'excerpt_length' => 200,
					'cropping' => 'false',
					'author' => 'false',
					'scroll' => 'false',
					'item_id' => $post->ID,
					'item_row' => 1
				);
		if ( $related->have_posts() ) {
			echo '<div class="pacz-related-post-wrapper">';
				echo '<section class="row pacz-related-post-contaner clearfix">' . "\n";
					while ( $related->have_posts() ) {
						$related->the_post();
						pacz_display_template('post/grid.php', array('atts' => $atts));
					}
					wp_reset_postdata();
				echo '</section>';
			echo '</div>';
			
		}
		pacz_theme_blog_pagenavi($related, $paged);
	}
	/*-----------------*/
}

