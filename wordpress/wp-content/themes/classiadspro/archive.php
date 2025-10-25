<?php 



$columns = $pacz_settings['archive-columns'];
$loop_style = $pacz_settings['archive-loop-style'];
$layout_option = $pacz_settings['archive-layout'];

if(isset($layout_option) && is_active_sidebar("sidebar-6")) {
	$layout = $pacz_settings['archive-layout'];
}else{
	$layout = 'full';
}
$blog_style = '';
$column = '';
get_header(); ?>
<div id="theme-page">
	<div class="pacz-main-wrapper-holder">
		<div class="theme-page-wrapper pacz-main-wrapper <?php echo esc_attr($layout); ?>-layout pacz-grid vc_row-fluid">
			<div class="inner-page-wrapper">
			<div class="theme-content" itemprop="mainContentOfPage">
			<?php 
				$id = uniqid();
				$paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);

					$count = '';
					$query = array(
						'post_type' => 'post',
						'posts_per_page' => (int) $count,
						'paged' => $paged,
						'suppress_filters' => 0,
						'ignore_sticky_posts' => 1
					);
				

				$query['paged'] = $paged;

				$r = new WP_Query($query);

				$grid_width    = $pacz_settings['grid-width'];
				$content_width = $pacz_settings['content-width'];
				$item_id = (!empty($item_id)) ? $item_id : 1409305847;
				$cropping = $pacz_settings['blog-image-crop'];
				$style = (isset($pacz_settings['archive-loop-style']))? $pacz_settings['archive-loop-style'] : 'classic';
				$column = (isset($pacz_settings['archive-columns']))? $pacz_settings['archive-columns'] : 1;
				$image_width  = (isset($pacz_settings['blog-grid-image-width']) && $cropping)? $pacz_settings['blog-grid-image-width'] :'';
				$image_height  = (isset($pacz_settings['blog-grid-image-height']) && $cropping)? $pacz_settings['blog-grid-image-height'] :'';
				
				$atts   = array(
					'layout' => 'right',
					'style' => $style,
					'column' => $column,
					'disable_meta' => 'false',
					'thumb_column' => 1,
					'classic_excerpt' => 'excerpt',
					'grid_avatar' => 'true',
					'read_more' => 'true',
					'grid_width' => $grid_width,
					'content_width' => $content_width,
					'image_width' => $image_width,
					'image_height' => $image_height,
					'excerpt_length' => 200,
					'cropping' => $cropping,
					'author' => 'false',
					'scroll' => 'false',
					'item_id' => $item_id,
					'item_row' => 1
				);
				$wrapper_class = ($atts['style'] == 'classic')? '' : 'row';
				echo '<div class="loop-main-wrapper">';
					echo '<section id="pacz-post-loop-' . $id . '"  class="pacz-post-container clearfix pacz-post-wrapper post-style-'. $style .' '. $wrapper_class .'">' . "\n";

						$i = 0;

						if (have_posts()):
							while (have_posts()):
								the_post();
								$i++;
									
								if($atts['style'] == 'grid'){
									pacz_display_template('post/grid.php', array('atts' => $atts));
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
				pacz_theme_blog_pagenavi($r, $paged);
			?>
			<div class="clearboth"></div>
		</div>
		<?php if($layout != 'full') get_sidebar(); ?>	
		<div class="clearboth"></div>	
		</div>
		</div>
		<div class="clearboth"></div>
	</div>	
</div>

<?php get_footer(); ?>