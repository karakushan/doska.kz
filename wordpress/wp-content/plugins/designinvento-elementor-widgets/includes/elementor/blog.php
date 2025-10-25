<?php

use Elementor\Plugin;
class Pacz_Elementor_Blog_Widget extends \Elementor\Widget_Base {
	
	public function get_name() {
		return 'pacz_posts';
	}
	
	public function get_title() {
		return __( 'Posts', 'designinvento-elementor-widgets' );
	}

	public function get_icon() {
		return 'fab fa-searchengin';
	}

	public function get_categories() {
		return [ 'general' ];
	}
	protected function register_controls() {
		
		$this->start_controls_section(
			'setting_section',
			[
				'label' => __( 'Setting', 'designinvento-elementor-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'post_style',
			[
				'label' => __( 'Post Style', 'designinvento-elementor-widgets' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'classic' => __( 'Classic', 'designinvento-elementor-widgets' ),
					'grid' => __( 'Grid', 'designinvento-elementor-widgets' ),
					'grid-2' => __( 'Grid Style 2', 'designinvento-elementor-widgets' ),
					'list' => __( 'List Style', 'designinvento-elementor-widgets' ),
				],
				'default' => 'classic',
			]
		);
		$this->add_control(
			'columns',
			[
				'label' => __( 'Grid Columns', 'designinvento-elementor-widgets' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'2' => __( '2 Columns', 'designinvento-elementor-widgets' ),
					'3' => __( '3 Columns', 'designinvento-elementor-widgets' ),
					'4' => __( '4 Columns', 'designinvento-elementor-widgets' ),
				],
				'default' => 3,
				'condition' => [
					'post_style' => ['grid', 'grid-2', 'list'],
				],
			]
		);
		$this->add_control(
			'pagination',
			[
				'label' => __( 'Turn On Pagination', 'designinvento-elementor-widgets' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'designinvento-elementor-widgets' ),
					'1' => __( 'Yes', 'designinvento-elementor-widgets' ),
				],
				'default' => 0,
			]
		);
		$this->add_control(
			'count',
			[
				'label' => __( 'Per Page Items', 'designinvento-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 3,
			]
		);
		$this->end_controls_section();

	}

	/**
	 * Render oEmbed widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		global $pacz_settings;
		$settings = $this->get_settings_for_display();
		$grid_width    = $pacz_settings['grid-width'];
		$content_width = $pacz_settings['content-width'];
		if (is_page()) {
			global $post;
			$layout = get_post_meta($post->ID, '_layout', true);
		} else {

			if (is_archive()) {
				$layout = $pacz_settings['archive-layout'];
			} else {
				$layout = 'right';
			}


		}
		$atts = array(
			'style' => $settings['post_style'],
			'layout' => $layout,
			'column' => $settings['columns'],
			'thumb_column' => '',
			'disable_meta' => 'true',
			'image_height' => '260',
			'image_width' => '370', // Scroller Style Only
			'count' => $settings['count'],
			'offset' => 0,
			'cat' => '',
			'posts' => '',
			'author' => '',
			'pagination' => $settings['pagination'],
			'pagination_style' => 1,
			'orderby' => 'date',
			'order' => 'DESC',
			'grid_avatar' => 'true',
			'read_more' => 'false',
			'sortable' => 'false',
			'classic_excerpt' => 'excerpt',
			'magazine_strcutre' => 1,
			'excerpt_length' => 120,
			'cropping' => 'true',
			'slideshow_layout' => 'default',
			'item_id' => '',
			'autoplay' => 'false',
			'tab_landscape_items' => 3,
			'tab_items' => 2,
			'desktop_items' => 5,
			'autoplay_speed' => 2000,
			'delay' => 1500,
			'item_loop' => 'false',
			'owl_nav' => 'false',
			'gutter_space' => 0,
			'scroll' => 'false',
			'item_row' => 1,
			'grid_width'    => $pacz_settings['grid-width'],
			'content_width' => $pacz_settings['content-width'],
		);


		$paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);


		$query = array(
			'post_type' => 'post',
			'posts_per_page' => (int) $atts['count'],
			'paged' => $paged,
			'suppress_filters' => 0,
			'ignore_sticky_posts' => 1
		);
		
		if ($atts['cat']) {
			$query['cat'] = $atts['cat'];
		}
		if ($atts['author']) {
			$query['author'] = $atts['author'];
		}
		if ($atts['posts']) {
			$query['post__in'] = explode(',', $atts['posts']);
		}
		if ($atts['orderby']) {
			$query['orderby'] = $atts['orderby'];
		}
		if ($atts['order']) {
			$query['order'] = $atts['order'];
		}

		$id = uniqid();
		$item_id = (!empty($atts['item_id'])) ? $atts['item_id'] : 1409305847;
	
		$query['offset'] = $atts['offset'];

		$query['paged'] = $paged;

		$r = new WP_Query($query);

		$grid_row_class = ($atts['style'] == 'grid' || $atts['style'] == 'grid-2' || $atts['style'] == 'list')? 'row' : '';



		echo '<div class="loop-main-wrapper">';
			echo '<section id="pacz-post-loop-' . $id . '"  class="pacz-post-container clearfix pacz-post-wrapper post-style-'. $atts['style'] .' '. $grid_row_class .'">' . "\n";
				$i = 0;
				if ($r->have_posts()):
					while ($r->have_posts()):
						$r->the_post();
						$i++;
						if($atts['style'] == 'grid'){
							pacz_display_template('post/grid.php', array('atts' => $atts));
						}elseif($atts['style'] == 'grid-2'){
							pacz_display_template('post/grid-2.php', array('atts' => $atts));
						}elseif($atts['style'] == 'list'){
							pacz_display_template('post/list.php', array('atts' => $atts));
						}else{
							pacz_display_template('post/classic.php', array('atts' => $atts));
						}
            
					endwhile;
				endif;
			echo '</section>';
		echo '</div>';
		if ($atts['pagination']) {
			pacz_theme_blog_pagenavi($r, $paged);
		}
		
		wp_reset_postdata();

	}

}