<?php
	global $post, $pacz_settings;

	extract($atts);
	$views = get_post_meta( get_the_ID(), 'pacz_post_views_count', true );
	echo '<div class="pacz-post-meta clearfix">';
		if($date_meta){
			echo '<div class="metatime" itemprop="datePublished">';
				echo '<a href="'.get_month_link( get_the_time( "Y" ), get_the_time( "m" ) ).'">'.get_the_date().'</a>';
			echo '</div>';
		}
		if($category_meta){
			echo '<div class="pacz-post-categories">';
				the_category(', ');
			echo '</div>';
		}
		if($author_meta){
			echo '<div class="author">';
				echo '<span class="postedby">'.esc_html__('By :', 'classiadspro').'</span>';
				echo get_the_author();
			echo '</div>';
		}
		if($views_meta && $views){
			echo '<div class="pacz-post-views">';
				echo sprintf(esc_html__('%s views', 'classiadspro'), $views);
			echo '</div>';
		}
		if($comments_meta){
			echo '<div class="pacz-post-comments">';
				echo '<a href="' . get_permalink() . '#comments" class="blog-comments">';
					comments_number(esc_html__('0 Comments', 'classiadspro'), esc_html__('1 Comment', 'classiadspro'), esc_html__('% Comments', 'classiadspro'));
				echo '</a>';
			echo '</div>';
		}
	echo '</div>';