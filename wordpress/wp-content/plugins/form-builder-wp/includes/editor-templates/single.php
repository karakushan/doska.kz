<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>
	<?php if ( ! current_theme_supports( 'title-tag' ) ) : ?>
		<title><?php echo wp_get_document_title(); ?></title>
	<?php endif; ?>
	<?php 
	wp_head();
	// Keep the following line after `wp_head()` call, to ensure it's not overridden by another templates.
	echo \Elementor\Utils::get_meta_viewport( 'canvas' );
	?>
	<style type="text/css">
		.wpfb_form_preview-content{
			background: #fff none repeat scroll 0 0;
		    margin: 40px auto;
		    max-width: 800px;
		    padding: 20px;
		}
	</style>
</head>
<body <?php body_class()?>>
<div id="wpfb_form_editor_frontend-primary" class="wpfb_form_preview-content"> 
		<?php 
		$post_id = get_the_ID();
		echo do_shortcode("[wpfb_form form_id='{$post_id}']");
		?>
</div>
<?php wp_footer();?>
</body>
</html>
