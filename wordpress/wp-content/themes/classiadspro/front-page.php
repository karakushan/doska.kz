<?php
/**
 * Template name: Front Page
 *
 * @package WordPress
 * @subpackage ClassiadsPro
 * @since ClassiadsPro 1.0
 *
 */
global $pacz_settings;
if(empty(global_get_post_id())){
	$post_id = $post->ID;
}else{
	$post_id = global_get_post_id();
}
$mobile_template_id = (isset($pacz_settings['mobile_front_page']))? $pacz_settings['mobile_front_page'] : '';
$blog_style = (isset($pacz_settings['archive-loop-style']))? $pacz_settings['archive-loop-style']: 'classic';
$column = $pacz_settings['archive-columns'];
$padding = get_post_meta($post_id, '_padding', true );
if(is_home()){
	$layout = (isset($pacz_settings['archive-layout']))? $pacz_settings['archive-layout']: 'full';
}elseif(!is_home() && ('page' == get_option('show_on_front') && !empty(get_option('page_on_front'))) && !wp_is_mobile()){
	$layout = get_post_meta(get_option('page_on_front'), '_layout', true );
}else{
	$layout = 'full';
}
$column = '';
$padding = ($padding == 'true') ? 'no-padding' : '';
get_header();

	?>
	<div id="theme-page">
	<div class="pacz-main-wrapper-holder">
	<div class="theme-page-wrapper  <?php echo esc_attr($layout); ?>-layout  pacz-grid row-fluid">
	<div class="inner-page-wrapper">
		<div class="theme-content <?php echo esc_attr($padding); ?>" itemprop="mainContentOfPage">
			<?php 
				if('page' == get_option('show_on_front') && !empty(get_option('page_on_front'))){
	
					$id = (wp_is_mobile() && !empty($mobile_template_id))? $mobile_template_id : get_option('page_on_front');
					
					if (did_action('elementor/loaded') && \Elementor\Plugin::$instance->documents->get( $id )->is_built_with_elementor() ) {
						//echo $id;
						echo do_shortcode( \Elementor\Plugin::instance()->frontend->get_builder_content($id) );
					}else{
						$page   = get_post( $id );
						echo apply_filters( 'the_content', $page->post_content );
					}
				}else{ 
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
				}
			?>
			<div class="clearboth"></div>
		</div>
		<?php 
		//if(is_home()){
			if($layout != 'full') get_sidebar();
		//}
		?>	
		<div class="clearboth"></div>
		</div>
	</div>
	</div>
</div>
<?php get_footer(); ?>