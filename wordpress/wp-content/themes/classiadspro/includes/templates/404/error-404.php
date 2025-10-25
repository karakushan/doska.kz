<?php 
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage ClassiadsPro
 * @since ClassiadsPro 1.0
 */

	global $post,
	$pacz_settings;
	$layout = $pacz_settings['error-layout'];
	$error_temp = $pacz_settings['error_page'];
	$error_small_text = $pacz_settings['error_page_small_text'];
?>
<div id="theme-page">
	<div class="pacz-main-wrapper-holder">
		<div class="theme-page-wrapper pacz-main-wrapper <?php echo esc_attr($layout); ?>-layout pacz-grid">
			<div class="inner-page-wrapper clearfix">
				<div class="theme-content" itemprop="mainContentOfPage">
					<div class="error-404-wrapper">
						<div class="error-404-big-banner">
							<img src="<?php echo esc_url(PACZ_THEME_IMAGES .'/404-error.png'); ?>" alt="<?php esc_attr_e('Not Found', 'classiadspro'); ?>" width="359" height="152" />
						</div>
						<div class="error-404-big-text">
							<h3><?php echo esc_html__('Sorry The Page You are looking for does not exist', 'classiadspro'); ?></h3>
						</div>
						<div class="error-404-small-text">
							<p><?php echo wp_kses_post($error_small_text); ?></p>
						</div>
						<div class="error-404-home-button">
							<a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html__('go back to home', 'classiadspro'); ?></a>
						</div>
					</div>
				</div>
				<?php if($layout != 'full') get_sidebar(); ?>	
			</div>
		</div>
	</div>
</div>