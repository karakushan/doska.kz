<?php
/**
 * Classic WordPress widget for language-specific banners.
 *
 * @package classiadspro
 */

if (!defined('ABSPATH')) {
	exit;
}

class Classiadspro_Multi_Language_Banner_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'classiadspro_multi_language_banner',
			esc_html__('Language Banner', 'classiadspro'),
			array(
				'classname' => 'classiadspro_multi_language_banner',
				'description' => esc_html__('Shows a different banner image and URL for each TranslatePress language.', 'classiadspro'),
			)
		);
	}

	public function widget($args, $instance) {
		$language = $this->get_current_language_keys();
		$banner = $this->get_banner_for_language($instance, $language);

		if (empty($banner['image_url'])) {
			return;
		}

		$title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title'], $instance, $this->id_base) : '';

		echo $args['before_widget'];

		if ($title !== '') {
			echo $args['before_title'] . esc_html($title) . $args['after_title'];
		}

		$image = sprintf(
			'<img src="%s" alt="%s" loading="lazy" />',
			esc_url($banner['image_url']),
			esc_attr($banner['alt'])
		);

		if (!empty($banner['link_url'])) {
			$rel_parts = array();
			if (!empty($banner['nofollow'])) {
				$rel_parts[] = 'nofollow';
			}
			if (!empty($banner['new_tab'])) {
				$rel_parts[] = 'noopener';
			}

			printf(
				'<a class="classiadspro-language-banner" href="%s"%s%s>%s</a>',
				esc_url($banner['link_url']),
				!empty($banner['new_tab']) ? ' target="_blank"' : '',
				!empty($rel_parts) ? ' rel="' . esc_attr(implode(' ', $rel_parts)) . '"' : '',
				$image
			);
		} else {
			printf(
				'<div class="classiadspro-language-banner">%s</div>',
				$image
			);
		}

		echo $args['after_widget'];
	}

	public function form($instance) {
		$title = isset($instance['title']) ? (string) $instance['title'] : '';
		$languages = $this->get_language_options();
		$banners = !empty($instance['banners']) && is_array($instance['banners']) ? $instance['banners'] : array();
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title', 'classiadspro'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
		</p>
		<div class="classiadspro-language-banner-widget">
			<?php foreach ($languages as $language_code => $language_label) : ?>
				<?php
				$field_key = $this->field_key($language_code);
				$banner = isset($banners[$field_key]) && is_array($banners[$field_key]) ? $banners[$field_key] : array();
				$image_url = isset($banner['image_url']) ? (string) $banner['image_url'] : '';
				$link_url = isset($banner['link_url']) ? (string) $banner['link_url'] : '';
				$alt = isset($banner['alt']) ? (string) $banner['alt'] : '';
				$new_tab = !empty($banner['new_tab']);
				$nofollow = !empty($banner['nofollow']);
				$base_name = $this->get_field_name('banners') . '[' . $field_key . ']';
				?>
				<div class="classiadspro-language-banner-widget__item">
					<h4><?php echo esc_html($language_label); ?> <code><?php echo esc_html($language_code); ?></code></h4>
					<p>
						<label><?php esc_html_e('Banner image URL', 'classiadspro'); ?></label>
						<input class="widefat classiadspro-language-banner-image" name="<?php echo esc_attr($base_name); ?>[image_url]" type="url" value="<?php echo esc_url($image_url); ?>">
						<button type="button" class="button classiadspro-language-banner-upload"><?php esc_html_e('Choose image', 'classiadspro'); ?></button>
					</p>
					<p>
						<label><?php esc_html_e('Banner link URL', 'classiadspro'); ?></label>
						<input class="widefat" name="<?php echo esc_attr($base_name); ?>[link_url]" type="url" value="<?php echo esc_url($link_url); ?>">
					</p>
					<p>
						<label><?php esc_html_e('Image alt text', 'classiadspro'); ?></label>
						<input class="widefat" name="<?php echo esc_attr($base_name); ?>[alt]" type="text" value="<?php echo esc_attr($alt); ?>">
					</p>
					<p>
						<label>
							<input type="checkbox" name="<?php echo esc_attr($base_name); ?>[new_tab]" value="1" <?php checked($new_tab); ?>>
							<?php esc_html_e('Open link in a new tab', 'classiadspro'); ?>
						</label>
						<br>
						<label>
							<input type="checkbox" name="<?php echo esc_attr($base_name); ?>[nofollow]" value="1" <?php checked($nofollow); ?>>
							<?php esc_html_e('Add nofollow to link', 'classiadspro'); ?>
						</label>
					</p>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['title'] = isset($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
		$instance['banners'] = array();

		if (!empty($new_instance['banners']) && is_array($new_instance['banners'])) {
			foreach ($new_instance['banners'] as $language_key => $banner) {
				if (!is_array($banner)) {
					continue;
				}

				$field_key = $this->field_key($language_key);
				$instance['banners'][$field_key] = array(
					'image_url' => !empty($banner['image_url']) ? esc_url_raw($banner['image_url']) : '',
					'link_url' => !empty($banner['link_url']) ? esc_url_raw($banner['link_url']) : '',
					'alt' => !empty($banner['alt']) ? sanitize_text_field($banner['alt']) : '',
					'new_tab' => !empty($banner['new_tab']) ? 1 : 0,
					'nofollow' => !empty($banner['nofollow']) ? 1 : 0,
				);
			}
		}

		return $instance;
	}

	private function get_banner_for_language($instance, $language_keys) {
		$banners = !empty($instance['banners']) && is_array($instance['banners']) ? $instance['banners'] : array();

		foreach ($language_keys as $language_key) {
			$field_key = $this->field_key($language_key);

			if (empty($banners[$field_key]) || !is_array($banners[$field_key])) {
				continue;
			}

			return array(
				'image_url' => !empty($banners[$field_key]['image_url']) ? (string) $banners[$field_key]['image_url'] : '',
				'link_url' => !empty($banners[$field_key]['link_url']) ? (string) $banners[$field_key]['link_url'] : '',
				'alt' => !empty($banners[$field_key]['alt']) ? (string) $banners[$field_key]['alt'] : '',
				'new_tab' => !empty($banners[$field_key]['new_tab']),
				'nofollow' => !empty($banners[$field_key]['nofollow']),
			);
		}

		return array(
			'image_url' => '',
			'link_url' => '',
			'alt' => '',
			'new_tab' => false,
			'nofollow' => false,
		);
	}

	private function get_language_options() {
		$options = array();

		if (function_exists('dp_get_translatepress_languages')) {
			$languages = dp_get_translatepress_languages();
		} elseif (function_exists('trp_get_available_languages')) {
			$languages = trp_get_available_languages();
		} else {
			$languages = array();
		}

		if (is_array($languages)) {
			foreach ($languages as $code => $label) {
				$options[(string) $code] = is_string($label) ? $label : strtoupper((string) $code);
			}
		}

		if (empty($options)) {
			$options['en'] = 'EN';
			$options['ru'] = 'RU';
		}

		return $options;
	}

	private function get_current_language_keys() {
		$locale = '';

		if (!empty($GLOBALS['TRP_LANGUAGE']) && is_string($GLOBALS['TRP_LANGUAGE'])) {
			$locale = $GLOBALS['TRP_LANGUAGE'];
		}

		if ($locale === '') {
			if (function_exists('determine_locale')) {
				$locale = determine_locale();
			} else {
				return array('en');
			}
		}

		$trp_settings = get_option('trp_settings', array());

		if (!empty($trp_settings['url-slugs'][$locale])) {
			return array((string) $trp_settings['url-slugs'][$locale]);
		}

		$parts = explode('_', (string) $locale);
		return array($parts[0]);
	}

	private function field_key($language_code) {
		return sanitize_key(strtolower(str_replace('-', '_', (string) $language_code)));
	}
}

function classiadspro_register_multi_language_banner_widget() {
	register_widget('Classiadspro_Multi_Language_Banner_Widget');
}
add_action('widgets_init', 'classiadspro_register_multi_language_banner_widget');

function classiadspro_multi_language_banner_widget_admin_assets($hook_suffix) {
	if (!in_array($hook_suffix, array('widgets.php', 'customize.php'), true)) {
		return;
	}

	wp_enqueue_media();
	wp_add_inline_script(
		'jquery',
		"(function($){
			$(document).on('click', '.classiadspro-language-banner-upload', function(e){
				e.preventDefault();
				var button = $(this);
				var input = button.siblings('.classiadspro-language-banner-image');
				var frame = wp.media({
					title: '" . esc_js(__('Choose banner image', 'classiadspro')) . "',
					button: { text: '" . esc_js(__('Use this image', 'classiadspro')) . "' },
					multiple: false
				});
				frame.on('select', function(){
					var attachment = frame.state().get('selection').first().toJSON();
					if (attachment && attachment.url) {
						input.val(attachment.url).trigger('change');
					}
				});
				frame.open();
			});
		})(jQuery);"
	);

	wp_add_inline_style(
		'wp-admin',
		'.classiadspro-language-banner-widget__item{border:1px solid #ccd0d4;padding:10px 12px;margin:0 0 12px;background:#fff}.classiadspro-language-banner-widget__item h4{margin:0 0 10px}.classiadspro-language-banner-upload{margin-top:6px}'
	);
}
add_action('admin_enqueue_scripts', 'classiadspro_multi_language_banner_widget_admin_assets');

function classiadspro_multi_language_banner_widget_frontend_style() {
	?>
	<style>
		.classiadspro-language-banner {
			display: block;
			max-width: 100%;
		}

		.classiadspro-language-banner img {
			display: block;
			height: auto;
			max-width: 100%;
		}
	</style>
	<?php
}
add_action('wp_head', 'classiadspro_multi_language_banner_widget_frontend_style');
