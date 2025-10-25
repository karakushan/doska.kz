<?php 

/**
 * Template name: Single Listing Template Full Width
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/



global $post, $ALSP_ADIMN_SETTINGS;

get_header();
?>
<div class="single-listing-page-full">
	<?php do_action('page_add_before_content'); ?>
		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
			<?php the_content();?>
			<div class="clearboth"></div>
		<?php endwhile; ?>
	<?php do_action('page_add_after_content'); ?>			
</div>
<?php get_footer(); ?>