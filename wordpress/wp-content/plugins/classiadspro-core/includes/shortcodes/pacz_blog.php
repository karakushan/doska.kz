<?php
		global $pacz_settings, $classiadspro_dynamic_styles;
		$atts = shortcode_atts(array(
			'style' => 'grid',
			'column' => 3,
			'thumb_column' => '',
			'disable_meta' => 'true',
			'image_height' => '260',
			'image_width' => '350', // Scroller Style Only
			'count' => '',
			'offset' => 0,
			'cat' => '',
			'posts' => '',
			'author' => '',
			'pagination' => 'true',
			'pagination_style' => '2',
			'orderby' => 'date',
			'order' => 'DESC',
			'grid_avatar' => 'true',
			'read_more' => 'false',
			'sortable' => 'false',
			'classic_excerpt' => 'excerpt',
			'magazine_strcutre' => 1,
			'excerpt_length' => 120,
			'cropping' => 'true',
			'slideshow_layout' => 'default',
			'author' => 'true',
			'item_id' => '',
			'autoplay' => 'false',
			'tab_landscape_items' => 3,
			'tab_items' => 2,
			'desktop_items' => 5,
			'autoplay_speed' => 2000,
			'delay' => 1500,
			'item_loop' => 'false',
			'owl_nav' => 'false',
			'gutter_space' => 0,
			'scroll' => 'false',
			'item_row' => 1,
			'grid_width'    => $pacz_settings['grid-width'],
			'content_width' => $pacz_settings['content-width'],
			'el_class' => ''

		), $atts);
		if (is_page()) {
			global $post;
			$atts['layout'] = get_post_meta($post->ID, '_layout', true);
		} else {

			if (is_archive()) {
				$atts['layout'] = $pacz_settings['archive-layout'];
			} else {
				$atts['layout'] = 'right';
			}


		}
		require_once PACZ_THEME_PLUGINS_CONFIG . "/image-cropping.php";    

		$paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);


		$query = array(
			'post_type' => 'post',
			'posts_per_page' => (int) $atts['count'],
			'paged' => $paged,
			'suppress_filters' => 0,
			'ignore_sticky_posts' => 1
		);

		if ($atts['cat']) {
			$query['cat'] = $atts['cat'];
		}
		if ($atts['author']) {
			$query['author'] = $atts['author'];
		}
		if ($atts['posts']) {
			$query['post__in'] = explode(',', $atts['posts']);
		}
		if ($atts['orderby']) {
			$query['orderby'] = $atts['orderby'];
		}
		if ($atts['order']) {
			$query['order'] = $atts['order'];
		}

		$id = uniqid();
		$item_id = (!empty($atts['item_id'])) ? $atts['item_id'] : 1409305847;
	
		$query['offset'] = $atts['offset'];

		$query['paged'] = $paged;

		$r = new WP_Query($query);

		$grid_row_class = ($atts['style'] != 'classic')? 'row' : '';

		echo '<div class="loop-main-wrapper">';
			echo '<section id="pacz-post-loop-' . $id . '"  class="pacz-post-container clearfix pacz-post-wrapper post-style-'. $atts['style'] .' '. $grid_row_class .'">' . "\n";
				$i = 0;
				if ($r->have_posts()):
					while ($r->have_posts()):
						$r->the_post();
						$i++;
						if($atts['style'] == 'grid'){
							pacz_display_template('post/grid.php', array('atts' => $atts));
						}elseif($atts['style'] == 'grid_mod'){
							pacz_display_template('post/grid-mod.php', array('atts' => $atts));
						}elseif($atts['style'] == 'tile'){
							pacz_display_template('post/tile.php', array('atts' => $atts));
						}elseif($atts['style'] == 'tile_elegant'){
							pacz_display_template('post/tile-elegant.php', array('atts' => $atts));
						}elseif($atts['style'] == 'tile_mod'){
							pacz_display_template('post/tile-mod.php', array('atts' => $atts));
						}else{
							pacz_display_template('post/classic.php', array('atts' => $atts));
						}
            
					endwhile;
				endif;
			echo '</section>';
		echo '</div>';
		if ($atts['pagination']) {
			pacz_theme_blog_pagenavi($r, $paged);
		}
		wp_reset_postdata();