<?php

class Classiadspro_Widget_Author extends WP_Widget {
	var $sites = array(
			
			//'twitter-square',
			'twitter',
			//'linkedin-square',
			'linkedin',
			//'facebook-square',
			'facebook',
			//'pinterest-square',
			'pinterest',
			//'google-plus-square',
			'google-plus',
			'dribbble',
			//'youtube-square',
			'youtube',
			//'tumblr-square',
			'tumblr',
			'instagram',
			//'rss-square',
			//'vimeo-square',
			'spotify',
	);
	function __construct() {
		$widget_ops = array( 'classname' => 'pacz_author_widget', 'description' => 'Author Widget' );
		WP_Widget::__construct( 'author', 'pacz-author', $widget_ops );
		add_action( 'admin_enqueue_scripts', array($this, 'scripts'));
		wp_enqueue_script( 'social-icon-widget', PACZ_THEME_ADMIN_ASSETS_URI . '/js/social-icon-widget.js', array( 'jquery' ) );
		add_action( 'enqueue_block_editor_assets', array($this, 'scripts') );
		

	}

	function scripts() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script('wp-color-picker');
		wp_enqueue_script('pacz_core_admin_script');	
	}

	function widget( $args, $instance ) {
		global $ALSP_ADIMN_SETTINGS, $post;
		extract( $args );
		
		$title = $instance['title'];
		$author_id = $post->post_author;
		$avatar_id = get_user_meta( $author_id, 'avatar_id', true );
		$author_name = get_the_author_meta('display_name', $author_id);
		$output = '';
		
		
		echo wp_kses_post($before_widget);
		if ( $title ){
			echo wp_kses_post($before_title . $title . $after_title);
		}
		echo '<div class="pacz-post-author-img">';
			if(!empty($avatar_id) && is_numeric($avatar_id)) {
				$author_avatar_url = wp_get_attachment_image_src( $avatar_id, 'full' ); 
				$image_src_array = $author_avatar_url[0];
				$params = array( 'width' => 150, 'height' => 150, 'crop' => true );
				echo '<img src="' . bfi_thumb( $image_src_array, $params ).'" alt="'.$author_name.'" />';
			} else { 
				$avatar_url = get_avatar_url($author_id, ['size' => '150']);
				echo'<img src="'.$avatar_url.'" alt="author" />';
			}
		echo'</div>';
		echo '<div class="pacz-post-author-content">';
			echo '<p class="pacz-post-author-name">'.$author_name.'</p>';
			echo '<p class="pacz-post-author-bio">'. get_the_author_meta('description', $author_id) .'</p>';
			if ( !empty( $instance['enable_sites'] ) ) {
				echo '<div class="pacz-post-author-social-links">';
					echo '<ul class="clearfix">';
						foreach ( $instance['enable_sites'] as $site ) {
							$link = isset( $instance[strtolower( $site )] )?$instance[strtolower( $site )]:'#';		
							$site_class = 'pacz-icon-'.$site;		
							echo '<li><a href="'.$link.'" rel="nofollow" class="builtin-icons '.$site.'" target="_blank" title=""><i class="'.$site_class.'"></i></a></li>';
						}
					echo '</ul>';
				echo'</div>';
			}
		echo'</div>';
		echo wp_kses_post($after_widget);
	}


	function update( $new_instance, $old_instance ) {
		//$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['fb_bg_color'] = $new_instance['fb_bg_color'];
		$instance['fb_bg_color_hover'] = $new_instance['fb_bg_color_hover'];
		$instance['enable_sites'] = $new_instance['enable_sites'];
		if ( !empty( $instance['enable_sites'] ) ) {
			foreach ( $instance['enable_sites'] as $site ) {
				$instance[strtolower( $site )] = isset( $new_instance[strtolower( $site )] )?strip_tags( $new_instance[strtolower( $site )] ):'';
			}
		}
		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$fb_bg_color = isset( $instance['fb_bg_color'] ) ? $instance['fb_bg_color'] : '';
		$fb_bg_color_hover = isset( $instance['fb_bg_color_hover'] ) ? $instance['fb_bg_color_hover'] : '';
		$enable_sites = isset( $instance['enable_sites'] ) ? $instance['enable_sites'] : array();
		foreach ( $this->sites as $site ) {
			$site = isset( $instance[strtolower( $site )] ) ? esc_attr( $instance[strtolower( $site )] ) : '';
		}
	
	?>
		<p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>">Title:</label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'enable_sites' ); ?>">Enable Social Icon:</label>
			<select name="<?php echo $this->get_field_name( 'enable_sites' ); ?>[]" style="height:10em" id="<?php echo $this->get_field_id( 'enable_sites' ); ?>" class="social_icon_select_sites widefat" multiple="multiple">
				<?php foreach ( $this->sites as $site ):?>
				<option value="<?php echo strtolower( $site );?>"<?php echo in_array( strtolower( $site ), $enable_sites )? 'selected="selected"':'';?>><?php echo $site;?></option>
				<?php endforeach;?>
			</select>
		</p>

		<p>
			<em><?php "Note: Please input FULL URL <br/>(e.g. <code>http://www.facebook.com/username</code>)";?></em>
		</p>
		
		<div class="social_icon_wrap">
		<?php foreach ( $this->sites as $site ):?>
		<p class="social_icon_<?php echo strtolower( $site );?>" <?php if ( !in_array( strtolower( $site ), $enable_sites ) ):?>style="display:none"<?php endif;?>>
			<label for="<?php echo $this->get_field_id( strtolower( $site ) ); ?>"><?php echo $site.' '.'URL:'?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( strtolower( $site ) ); ?>" name="<?php echo $this->get_field_name( strtolower( $site ) ); ?>" type="text" value="<?php echo $site; ?>" />
		</p>
		<?php endforeach;?>
		</div>
		
		
<?php

	}
}
/***************************************************/
