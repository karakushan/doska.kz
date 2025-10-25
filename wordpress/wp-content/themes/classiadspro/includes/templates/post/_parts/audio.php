<?php
	global $post, $pacz_settings;

	extract($atts);
	
		/* Blog Audio */
	wp_enqueue_script('jsrapaudio');
    $audio_id = mt_rand(99, 999);
    $mp3_file = get_post_meta($post->ID, '_mp3_file', true);
    $iframe   = get_post_meta($post->ID, '_audio_iframe', true);
           
	if ($iframe) {
		echo '<div class="pacz-audio" data-src="'.$mp3_file.'"></div>';
	} elseif($mp3_file){
		echo '<div class="pacz-audio-player-wrapper">';
			echo '<div class="pacz-audio-payer-cover" style="background-image: url('. esc_url(PACZ_THEME_IMAGES .'/audio-payer-cover.png;') .')"></div>';
			$attr = array(
				'src'      =>  $mp3_file,
				'loop'     => '',
				'autoplay' => '',
				'preload' => 'none'
			);
			echo wp_audio_shortcode( $attr );
		echo '</div>';
	}