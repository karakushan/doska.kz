<?php
/**
 * Define the demo import files (local files).
 *
 * You have to use the same filter as in above example,
 * but with a slightly different array keys: local_*.
 * The values have to be absolute paths (not URLs) to your import files.
 * To use local import files, that reside in your theme folder,
 * please use the below code.
 * Note: make sure your import files are readable!
 */

function designinvento_templates_import_files() {
	$protocol		= 'https://';
	$url = $protocol .'assets.designinvento.net/demo-data/classiadspro/';
	return array(
		// Elementor
		array(
			'id'                           => 0,
			'import_file_name'             => 'Classiads Shawk',
			'import_file_url'            => $url . 'elementor/classiads-shawk/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-shawk/widgets.wie',
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-shawk/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-shawk/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-shawk/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-shawk/classiads-shawk.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-shawk/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		),
		array(
			'id'                           => 1,
			'import_file_name'             => 'Classiads Malt',
			'import_file_url'            => $url . 'elementor/classiads-malt/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-malt/widgets.wie',
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-malt/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-malt/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-malt/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-malt/classiads-malt.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-malt/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		),
		array(
			'id'                           => 2,
			'import_file_name'             => 'Classiads Mode',
			'import_file_url'            => $url . 'elementor/classiads-mode/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-mode/widgets.wie',
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-mode/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-mode/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-mode/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-mode/classiads-mode.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-mode/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		),
		array(
			'id'                           => 3,
			'import_file_name'             => 'Classiads Ultra',
			'import_file_url'            => $url . 'elementor/classiads-ultra/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-ultra/widgets.wie',
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-ultra/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-ultra/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-ultra/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-ultra/classiads-ultra.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-ultra/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		),
		array(
			'id'                           => 4,
			'import_file_name'             => 'Classiads Ola',
			'import_file_url'            => $url . 'elementor/classiads-ola/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-ola/widgets.wie',
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-ola/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-ola/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-ola/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-ola/classiads-ola.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-ola/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		),
		array(
			'id'                           => 5,
			'import_file_name'             => 'Classiads Fantro',
			'import_file_url'            => $url . 'elementor/classiads-fantro/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-fantro/widgets.wie',
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-fantro/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-fantro/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-fantro/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-fantro/classiads-fantro.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-fantro/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		),
		array(
			'id'                           => 6,
			'import_file_name'             => 'Classiads Wind',
			'import_file_url'            => $url . 'elementor/classiads-wind/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-wind/widgets.wie',
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-wind/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-wind/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-wind/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-wind/classiads-wind.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-wind/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		),
		array(
			'id'                           => 7,
			'import_file_name'             => 'Classiads Zon',
			'import_file_url'            => $url . 'elementor/classiads-zon/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-zon/widgets.wie',
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-zon/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-zon/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-zon/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-zon/classiads-zon.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-zon/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		),
		array(
			'id'                           => 8,
			'import_file_name'             => 'Classiads Mono',
			'import_file_url'            => $url . 'elementor/classiads-mono/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-mono/widgets.wie',
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-mono/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-mono/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-mono/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-mono/classiads-mono.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-mono/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		),
		array(
			'id'                           => 9,
			'import_file_name'             => 'Classiads Flip',
			'import_file_url'            => $url . 'elementor/classiads-flip/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-flip/widgets.wie',
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-flip/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-flip/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-flip/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-flip/classiads-flip.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-flip/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		),
		array(
			'id'                           => 10,
			'import_file_name'             => 'Classiads Moto',
			'import_file_url'            => $url . 'elementor/classiads-moto/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-moto/widgets.wie',
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-moto/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-moto/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-moto/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-moto/classiads-moto.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-moto/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		),
		array(
			'id'                           => 11,
			'import_file_name'             => 'Classiads Dose',
			'import_file_url'            => $url . 'elementor/classiads-dose/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-dose/widgets.wie',
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-dose/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-dose/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-dose/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-dose/classiads-dose.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-dose/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		),
		array(
			'id'                           => 12,
			'import_file_name'             => 'Classiads Phone',
			'import_file_url'            => $url . 'elementor/classiads-phone/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-phone/widgets.wie',
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-phone/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-phone/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-phone/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-phone/classiads-phone.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-phone/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		),
		array(
			'id'                           => 13,
			'import_file_name'             => 'Classiads Wox',
			'import_file_url'            => $url . 'elementor/classiads-wox/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-wox/widgets.wie',
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-wox/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-wox/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-wox/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-wox/classiads-wox.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-wox/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		),
		array(
			'id'                           => 14,
			'import_file_name'             => 'Classiads Mintox',
			'import_file_url'            => $url . 'elementor/classiads-mintox/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-mintox/widgets.wie',
			'import_rev_slider_file_url' => $url . 'elementor/classiads-mintox/slider-mintox.zip',
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-mintox/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-mintox/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-mintox/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-mintox/classiads-mintox.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-mintox/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		),
		array(
			'id'                           => 15,
			'import_file_name'             => 'Classiads Solic',
			'import_file_url'            => $url . 'elementor/classiads-solic/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-solic/widgets.wie',
			'import_rev_slider_file_url' => $url . 'elementor/classiads-solic/slider2.zip',
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-solic/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-solic/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-solic/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-solic/classiads-solic.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-solic/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		),
		array(
			'id'                           => 16,
			'import_file_name'             => 'Classiads Zoco',
			'import_file_url'            => $url . 'elementor/classiads-zoco/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-zoco/widgets.wie',
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-zoco/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-zoco/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-zoco/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-zoco/classiads-zoco.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-zoco/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		),
		array(
			'id'                           => 17,
			'import_file_name'             => 'Classiads Flow',
			'import_file_url'            => $url . 'elementor/classiads-flow/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-flow/widgets.wie',
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-flow/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-flow/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-flow/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-flow/classiads-flow.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-flow/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		),
		array(
			'id'                           => 18,
			'import_file_name'             => 'Classiads Pets',
			'import_file_url'            => $url . 'elementor/classiads-pets/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-pets/widgets.wie',
			
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-pets/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-pets/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-pets/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-pets/classiads-pets.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-pets/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		),
		array(
			'id'                           => 19,
			'import_file_name'             => 'Classiads Directory',
			'import_file_url'            => $url . 'elementor/classiads-directory/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-directory/widgets.wie',
			
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-directory/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-directory/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-directory/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-directory/classiads-directory.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-directory/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		),
		array(
			'id'                           => 20,
			'import_file_name'             => 'Classiads Nova',
			'import_file_url'            => $url . 'elementor/classiads-nova/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-nova/widgets.wie',
			
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-nova/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-nova/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-nova/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-nova/classiads-nova.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-nova/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		),
		array(
			'id'                           => 21,
			'import_file_name'             => 'Classiads Realestate',
			'import_file_url'            => $url . 'elementor/classiads-realestate/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-realestate/widgets.wie',
			
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-realestate/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-realestate/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-realestate/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-realestate/classiads-realestate.jpg',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-realestate/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		),
		array(
			'id'                           => 22,
			'import_file_name'             => 'Classiads YP',
			'import_file_url'            => $url . 'elementor/classiads-yp/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-yp/widgets.wie',
			
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-yp/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-yp/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-yp/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-yp/classiads-yp.png',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-yp/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		),
		array(
			'id'                           => 23,
			'import_file_name'             => 'Classiads crglisto',
			'import_file_url'            => $url . 'elementor/classiads-crglisto/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-crglisto/widgets.wie',
			
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-crglisto/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-crglisto/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-crglisto/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-crglisto/classiads-crglisto.png',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-crglisto/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		),
		array(
			'id'                           => 24,
			'import_file_name'             => 'Classiads gen',
			'import_file_url'            => $url . 'elementor/classiads-gen/content.xml',
			'import_widget_file_url'     => $url . 'elementor/classiads-gen/widgets.wie',
			
			'local_import_redux'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-gen/theme-settings.json',
					'option_name' => 'pacz_settings',
				),
			),
			'local_import_redux2'           => array(
				array(
					'file_path' => DT_PATH . 'demos/elementor/classiads-gen/directorypress-settings.json',
					'option_name' => 'directorypress_admin_settings',
				),
			),
			'local_import_customizer_file' => DT_PATH . 'demos/elementor/classiads-gen/customizer.dat',
			'import_preview_image_url'     => $url . 'elementor/classiads-gen/classiads-gen.png',
			'import_notice'                => __( 'After you import this demo, you will have to setup the slider separately.', 'designinvento' ),
			'preview_url'                  => 'https://classiads.designinvento.net/elementor/classiads-gen/',
			'homepage'                     => 'home',
			'blog_page'                    => 'blog',
			'primary_menu'                 => 'main',
			'footer_menu'                  => 'footer',
			'page_builder'                 => 'elementor',
		)

	);
}
add_filter( 'designinvento_templates_import_files', 'designinvento_templates_import_files' );



function designinvento_register_query_vars( $vars ) {
	$vars[] = 'p';
	return $vars;
}
add_filter( 'query_vars', 'designinvento_register_query_vars' );