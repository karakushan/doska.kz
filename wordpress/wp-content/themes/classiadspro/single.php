<?php

global $post;
$layout_meta = get_post_meta( $post->ID, '_layout', true );
if(isset($layout_meta) && !empty($layout_meta)){
	$layout = $layout_meta;	
}elseif(is_active_sidebar('sidebar-3')){
	$layout = 'right';	
}else{
	$layout = 'full';
}
$image_height = $pacz_settings['blog-single-image-height'];
$image_width = pacz_content_width($layout);

$padding = get_post_meta( $post->ID, '_padding', true );
$padding = ($padding == 'true') ? 'no-padding' : '';
$sticky_class = (is_sticky())? 'sticky-post':'';
$show_featured = get_post_meta( $post->ID, '_featured_image', true );
$show_featured = (isset($show_featured) && !empty($show_featured)) ? $show_featured  : 'true' ;

$show_meta = get_post_meta( $post->ID, '_meta', true );
$show_meta = (isset($show_meta) && !empty($show_meta)) ? $show_meta  : 'true' ;

$classes = array(
	'pacz-post-single',
    'theme-content',
	'clearfix',
    $padding,
    $sticky_class,
);
get_header(); ?>

<div id="theme-page" class="pacz-post-single">
	<div class="pacz-main-wrapper-holder">
		<div class="theme-page-wrapper <?php echo esc_attr($layout); ?>-layout pacz-grid vc_row-fluid <?php echo esc_attr($padding); ?>">
			<div class="theme-inner-wrapper clearfix">
				<?php if ( have_posts() ) while ( have_posts() ) : the_post();
					$post_type = (get_post_format( get_the_id()) == '0' || get_post_format( get_the_id()) == '') ? 'image' : get_post_format( get_the_id());
					$image_src_array = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full', true );
					if(isset($pacz_settings['blog-image-crop']) && $pacz_settings['blog-image-crop']) {
						$image_src = $image_src_array[ 0 ];
					} else {
						$image_src = bfi_thumb( $image_src_array[ 0 ], array('width' => $image_width, 'height' => $image_height, 'crop'=>true));
					}
				?>
					<div id="pacz-post-single-entry-<?php the_ID(); ?>" <?php post_class($classes); ?>>
						<div class="inner-content clearfix">
							<?php 
							if($show_featured == 'true') {
								if(isset($pacz_settings['blog-featured-image']) && $pacz_settings['blog-featured-image'] == 1) {
									if($post_type == 'image' || $post_type == 'portfolio') {
										if(has_post_thumbnail()) : ?>
											<div class="featured-image">
												<a href="<?php echo esc_url($image_src_array[ 0 ]); ?>" class="pacz-lightbox"><img alt="<?php the_title(); ?>" title="<?php the_title(); ?>" src="<?php echo pacz_thumbnail_image_gen($image_src, $image_width, $image_height); ?>" height="<?php echo esc_attr($image_height); ?>" width="<?php echo esc_attr($image_width); ?>" itemprop="image" /></a>
											</div>
										<?php endif;

									} elseif($post_type == 'video') {
										
									} elseif($post_type == 'audio') {
			
									}else if($post_type == 'gallery') {
					
									}
								}
							} 
							?>
							<div class="pacz-post-single-content-wrapper clearfix">
								<?php if($show_meta == 'true') : ?>
									<div class="pacz-post-single-publish-date">
										<span class="meta-publish-date">
											<?php the_time('d'); ?>
										</span>
										<span class="meta-publish-month">
											<?php the_time('M'); ?>
										</span>
									</div>
									<div class="pacz-post-single-meta clearfix">
										<div class="pacz-post-categories">
											<?php the_category(', '); ?>
										</div>
										<div class="pacz-post-views">
											<?php echo sprintf(esc_html__('%s views', 'classiadspro'), get_post_meta( get_the_ID(), 'pacz_post_views_count', true )); ?>
										</div>
										<div class="pacz-post-comments">
											<a href="<?php echo get_permalink(); ?>'#comments" class="blog-comments">
												<?php comments_number(esc_html__('0 Comments', 'classiadspro'), esc_html__('1 Comment', 'classiadspro'), esc_html__('% Comments', 'classiadspro')); ?>
											</a>
										</div>
									</div>
								<?php endif; ?>
								<div class="pacz-post-single-heading">
									<h2 class="pacz-post-single-title"><?php the_title() ?></h2>
								</div>
								<div class="pacz-post-single-content">
									<?php the_content(); ?>
									<?php if(has_tag()) { ?>
										<div class="pacz-post-single-tags">
											<?php
											$before = '<span class="pacz-post-single-tags-label">'.esc_html__('Tags:', 'classiadspro').' </span>';
											$sep = ', ';
											the_tags($before, $sep, ''); ?>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
						<?php if(get_next_post() || get_previous_post()): ?>
							<div class="inner-content">
								<div class="pacz-post-single-pre-next clearfix">
									<?php 
									the_post_navigation(
										array(
											'prev_text' => '<span class="previous_post">'. esc_html__('Prev Post', 'classiadspro') .'</span>',
											'next_text' => '<span class="next_post">'. esc_html__('Next Post', 'classiadspro') .'</span>',
										)
									);
									
									wp_link_pages( array(
										'before'      => '<div class="pacz-page-links">',
										'after'       => '</div>',
										'link_before' => '<span>',
										'link_after'  => '</span>',
									) );
									?>
								</div>
							</div>
						<?php endif ?>
						<?php
						/* About Author section */
						$author_id = $post->post_author;
						$author_disc = get_the_author_meta('description', $author_id);
						$avatar_id = 	get_user_meta( $author_id, 'avatar_id', true );
						if($pacz_settings['blog-single-about-author'] && !empty($author_disc)) :
							
							?>
							<div class="inner-content clearfix">
								<div class="pacz-post-single-author">
									
									<div class="pacz-post-single-author-box clearfix">
										<div class="pacz-post-single-author-img">
											<?php 
												if(!empty($avatar_id) && is_numeric($avatar_id)) {
													$author_avatar_url = wp_get_attachment_image_src( $avatar_id, 'full' ); 
													$image_src_array = $author_avatar_url[0];
													$params = array( 'width' => 180, 'height' => 180, 'crop' => true );
													echo '<img src="' . bfi_thumb( $image_src_array, $params ).'" alt="'.$author_name.'" />';
												} else { 
													$avatar_url = get_avatar_url($author_id, ['size' => '180']);
													echo'<img src="'.$avatar_url.'" alt="author" />';
												}
											?>
										</div>
										
										<div class="pacz-post-single-author-info">
											<div class="pacz-post-single-author-name">
												<?php echo get_the_author_meta('display_name', $author_id);?>
											</div>
											<div class="pacz-post-single-author-bio">
												<p><?php the_author_meta('description'); ?></p>
											</div>
										</div>	
									</div>
								</div>
							</div>
						<?php endif; ?>
						<?php do_action('blog_related_posts', 'full'); ?>
						<?php
						if($pacz_settings['blog-single-comments'] && comments_open($post->ID)) { ?>
							<div class="inner-content-comments clearfix">
								<?php comments_template( '', true ); ?>
							</div>
						<?php } ?>


					</div>
				<?php endwhile; ?>
				<?php  if($layout != 'full') get_sidebar();  ?>
			</div>
		</div>
	</div>
</div>
<?php pacz_post_views($post->ID); ?>
<?php get_footer(); ?>
