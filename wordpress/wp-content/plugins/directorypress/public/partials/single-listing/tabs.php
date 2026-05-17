<?php 

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/
global $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object;
if($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_tab']): 
	$tab_ordering = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress-listings-tabs-order']['enabled'];
	unset($tab_ordering['placebo']);
	$default_tab_keys = array();
	
	foreach ($tab_ordering as $key=>$value){
		$default_tab_keys[] = $key;
	}
	
?>
<div class="single-listing-tabs-wrapper">
	<script>
		(function($) {
			"use strict";
	
			$(function() {				
				directorypress_show_tab($('.directorypress-listing-tabs a:first'));
			});
		})(jQuery);
	</script>
	<?php if (($fields_groups = $listing->get_fields_groups_in_tabs()) || ($listing->is_map() && $listing->locations) || (directorypress_is_reviews_allowed()) || ($listing->package->videos_allowed && $listing->videos) || $DIRECTORYPRESS_ADIMN_SETTINGS['message_system'] == 'email_messages'):
		$tabs_class = (wp_is_mobile())? 'navbar-nav':'nav-tabs';
		$tab_icon = array(
			'addresses-tab' => 'dicode-material-icons dicode-material-icons-map-marker',
			'comments-tab' => 'dicode-material-icons dicode-material-icons-star',
			'videos-tab' => 'dicode-material-icons dicode-material-icons-video',
		);
		foreach ($directorypress_object->fields->fields_groups_array AS $fields_group){
			$tab_icon['field-group-tab-'.$fields_group->id] = $fields_group->icon;
		}
		echo '<ul class="directorypress-listing-tabs nav clearfix '. esc_attr($tabs_class) .'" role="tablist">';		
			foreach ($tab_ordering as $key=>$value):
				echo '<li class="nav-item">';
					echo '<a class="nav-link" href="javascript: void(0);" data-tab="#'. esc_attr($key) .'" data-bs-toggle="directorypress-tab" role="tab">';
						echo '<i class="directorypress-tab-icon '. $tab_icon[$key] .'"></i>';
						echo esc_html($value);
					echo '</a>';
				echo '</li>';
			endforeach;			
		echo '</ul>';
		echo '<div class="tab-content">';
			if ($listing->is_map() && $listing->locations && directorypress_has_map()):
				echo '<div id="addresses-tab" class="tab-pane fade" role="tabpanel">';
					$listing->display_map($hash, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_show_directions'], false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_radius_search_cycle'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_clusters'], false, false);
				echo '</div>';
			endif;
			if (directorypress_is_reviews_allowed() && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_comments_position'] == 'intab'):
				echo '<div id="comments-tab" class="tab-pane fade" role="tabpanel">';
					comments_template( '', true );
				echo '</div>';
			endif;
			if ($listing->package->videos_allowed && $listing->videos && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_video_position'] == 'intab'):
				echo '<div id="videos-tab" class="tab-pane fade" role="tabpanel">';
					foreach ($listing->videos AS $video):
						if (strlen($video['id']) == 11):
							echo '<iframe width="100%" height="400" class="directorypress-video-iframe fitvidsignore" src="//www.youtube.com/embed/'. esc_attr($video['id']) .'" frameborder="0" allowfullscreen></iframe>';
						elseif (strlen($video['id']) == 9):
							echo '<iframe width="100%" height="400" class="directorypress-video-iframe fitvidsignore" src="https://player.vimeo.com/video/'. esc_attr($video['id']) .'?color=d1d1d1&title=0&byline=0&portrait=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
						endif;
					endforeach;
				echo '</div>';
			endif;
			foreach ($fields_groups AS $fields_group):
				echo '<div id="field-group-tab-'. esc_attr($fields_group->id) .'" class="tab-pane fade" role="tabpanel">';
					echo wp_kses_post($fields_group->display_output($listing));
				echo '</div>';
			endforeach;
		echo '</div>';
	endif; ?>
</div>
<?php endif; ?>

 