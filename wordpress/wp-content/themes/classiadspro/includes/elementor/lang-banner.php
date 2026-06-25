<?php
/**
 * Elementor Multi-Language Banner Widget.
 *
 * @package classiadspro
 */

namespace HFB\WidgetsManager\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
	exit;
}

class Multi_Lang_Banner extends Widget_Base {

	public function get_name() {
		return 'multi-lang-banner';
	}

	public function get_title() {
		return __('Multi-Language Banner', 'classiadspro');
	}

	public function get_icon() {
		return 'eicon-banner';
	}

	public function get_categories() {
		return ['general'];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'lang_banner_content',
			[
				'label' => esc_html__('Banners', 'classiadspro'),
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'banner_language',
			[
				'label'   => esc_html__('Language', 'classiadspro'),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_language_options(),
			]
		);

		$repeater->add_control(
			'banner_image',
			[
				'label'   => esc_html__('Image', 'classiadspro'),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'banner_url',
			[
				'label'       => esc_html__('URL', 'classiadspro'),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__('https://your-link.com', 'classiadspro'),
				'default'     => [
					'url'         => '',
					'is_external' => false,
					'nofollow'    => false,
				],
			]
		);

		$this->add_control(
			'lang_banners',
			[
				'label'       => esc_html__('Language Banners', 'classiadspro'),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ banner_language }}}',
				'default'     => [],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if (empty($settings['lang_banners'])) {
			return;
		}

		$current_lang = $this->get_current_language();

		$banner = null;
		foreach ($settings['lang_banners'] as $item) {
			if ($item['banner_language'] === $current_lang) {
				$banner = $item;
				break;
			}
		}

		if (!$banner) {
			return;
		}

		$image_url = '';
		if (!empty($banner['banner_image']['id'])) {
			$image_url = wp_get_attachment_image_url($banner['banner_image']['id'], 'full');
		} elseif (!empty($banner['banner_image']['url'])) {
			$image_url = $banner['banner_image']['url'];
		}

		if (empty($image_url)) {
			return;
		}

		$link_url = !empty($banner['banner_url']['url']) ? $banner['banner_url']['url'] : '';
		$is_external = !empty($banner['banner_url']['is_external']);
		$nofollow = !empty($banner['banner_url']['nofollow']);

		$target = $is_external ? ' target="_blank"' : '';
		$rel = $nofollow ? ' rel="nofollow"' : '';

		if ($link_url) {
			echo sprintf(
				'<a href="%s" class="multi-lang-banner"%s%s><img src="%s" alt="" /></a>',
				esc_url($link_url),
				$target,
				$rel,
				esc_url($image_url)
			);
		} else {
			echo sprintf(
				'<div class="multi-lang-banner"><img src="%s" alt="" /></div>',
				esc_url($image_url)
			);
		}
	}

	private function get_language_options(): array {
		$options = [];

		if (function_exists('dp_get_translatepress_languages')) {
			$languages = dp_get_translatepress_languages();
		} elseif (function_exists('trp_get_available_languages')) {
			$languages = trp_get_available_languages();
		} else {
			$languages = [];
		}

		if (is_array($languages)) {
			foreach ($languages as $code => $label) {
				$options[$code] = is_string($label) ? $label : strtoupper($code);
			}
		}

		if (empty($options)) {
			$options['en'] = 'EN';
			$options['ru'] = 'RU';
		}

		return $options;
	}

	private function get_current_language(): string {
		$locale = '';
		if (!empty($GLOBALS['TRP_LANGUAGE']) && is_string($GLOBALS['TRP_LANGUAGE'])) {
			$locale = $GLOBALS['TRP_LANGUAGE'];
		}

		if ($locale === '') {
			if (function_exists('determine_locale')) {
				$locale = determine_locale();
			} else {
				return 'en';
			}
		}

		$trp_settings = get_option('trp_settings', []);

		if (!empty($trp_settings['url-slugs'][$locale])) {
			return (string) $trp_settings['url-slugs'][$locale];
		}

		$parts = explode('_', $locale);
		return $parts[0];
	}
}
