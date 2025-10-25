<?php

/**
 * @since      1.0.0
 * @package    Header_Footer_Builder
 * @subpackage Header_Footer_Builder/includes
 * @author     Designinvento <help.designinvento@gmail.com>
 */
 use HFB\Lib\HFB_Conditions_Setting;
class Header_Footer_Builder {

	
	protected $loader;
	protected $plugin_name;
	protected $version;
	public $template;
	public $admin_main;
	public $admin;
	private static $elementor_instance;
	private static $_instance = null;

	public function __construct() {
		if ( defined( 'HEADER_FOOTER_BUILDER_VERSION' ) ) {
			$this->version = HEADER_FOOTER_BUILDER_VERSION;
		} else {
			$this->version = '1.0.7';
		}
		$this->plugin_name = 'header-footer-builder';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_public_hooks();

	}

	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-header-footer-builder-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-header-footer-builder-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-header-footer-builder-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-header-footer-builder-public.php';

		$this->loader = new Header_Footer_Builder_Loader();

	}
	
	private function set_locale() {

		$plugin_i18n = new Header_Footer_Builder_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}
	
	private function define_admin_hooks() {
		//$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		//$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}
	public static function instance() {
		if ( ! isset( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function reset_unsupported_theme_notice() {
		delete_user_meta( get_current_user_id(), 'unsupported-theme' );
	}

	public function rating_notice_css() {
		wp_enqueue_style( 'el-hfb-admin-style', HEADER_FOOTER_BUILDER_ASSETS_URL . 'public/css/admin-header-footer-builder.css', [], HEADER_FOOTER_BUILDER_VERSION );
	}

	/**
	 * Prints the admin notics when Elementor is not installed or activated.
	 */
	public function elementor_not_available() {

		if ( ! did_action( 'elementor/loaded' ) ) {
			// Check user capability.
			if ( ! ( current_user_can( 'activate_plugins' ) && current_user_can( 'install_plugins' ) ) ) {
				return;
			}

			/* TO DO */
			$class = 'notice notice-error';
			/* translators: %s: html tags */
			$message = sprintf( __( 'The %1$sHeader Footer Builder%2$s plugin requires %1$sElementor%2$s plugin installed & activated.', 'header-footer-builder' ), '<strong>', '</strong>' );

			$plugin = 'elementor/elementor.php';

			if ( _is_elementor_installed() ) {

				$action_url   = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
				$button_label = __( 'Activate Elementor', 'header-footer-builder' );

			} else {

				$action_url   = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
				$button_label = __( 'Install Elementor', 'header-footer-builder' );
			}

			$button = '<p><a href="' . $action_url . '" class="button-primary">' . $button_label . '</a></p><p></p>';

			printf( '<div class="%1$s"><p>%2$s</p>%3$s</div>', esc_attr( $class ), wp_kses_post( $message ), wp_kses_post( $button ) );
		}
	}

	/**
	 * Loads the globally required files for the plugin.
	 */
	public function includes() {
		require_once HEADER_FOOTER_BUILDER_PATH . 'includes/hfb-functions.php';
		require_once HEADER_FOOTER_BUILDER_PATH . 'includes/class-hfb-elementor-canvas-compat.php';
		if ( defined( 'ICL_SITEPRESS_VERSION' ) || defined( 'POLYLANG_BASENAME' ) ) {
			require_once HEADER_FOOTER_BUILDER_PATH . 'includes/compatibility/class-hfb-wpml-compatibility.php';
		}
		require_once HEADER_FOOTER_BUILDER_PATH . 'includes/lib/notices/class-hfb-notices.php';
		require_once HEADER_FOOTER_BUILDER_PATH . 'includes/lib/target-rule/class-hfb-target-rules-fields.php';
		require_once HEADER_FOOTER_BUILDER_PATH . 'includes/class-hfb-update.php';
		require HEADER_FOOTER_BUILDER_PATH . 'includes/class-widgets-manager.php';
	}

	/**
	 * Loads textdomain for the plugin.
	 */
	//public function load_textdomain() {
		//load_plugin_textdomain( 'header-footer-builder' );
	//}

	/**
	 * Enqueue styles and scripts.
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'hfb-style', HEADER_FOOTER_BUILDER_ASSETS_URL . 'public/css/header-footer-builder.css', [], HEADER_FOOTER_BUILDER_VERSION );

		if ( class_exists( '\Elementor\Plugin' ) ) {
			$elementor = \Elementor\Plugin::instance();
			$elementor->frontend->enqueue_styles();
		}

		if ( class_exists( '\ElementorPro\Plugin' ) ) {
			$elementor_pro = \ElementorPro\Plugin::instance();
			$elementor_pro->enqueue_styles();
		}

		if ( hfb_header_enabled() ) {
			if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
				$css_file = new \Elementor\Core\Files\CSS\Post( get_hfb_header_id() );
			} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
				$css_file = new \Elementor\Post_CSS_File( get_hfb_header_id() );
			}

			$css_file->enqueue();
		}

		if ( hfb_footer_enabled() ) {
			if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
				$css_file = new \Elementor\Core\Files\CSS\Post( get_hfb_footer_id() );
			} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
				$css_file = new \Elementor\Post_CSS_File( get_hfb_footer_id() );
			}

			$css_file->enqueue();
		}

		if ( hfb_is_before_footer_enabled() ) {
			if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
				$css_file = new \Elementor\Core\Files\CSS\Post( hfb_get_before_footer_id() );
			} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
				$css_file = new \Elementor\Post_CSS_File( hfb_get_before_footer_id() );
			}
			$css_file->enqueue();
		}
	}

	/**
	 * admin styles.
	 */
	public function enqueue_admin_scripts() {
		global $pagenow;
		$screen = get_current_screen();

		if ( ( 'hfb-post' == $screen->id && ( 'post.php' == $pagenow || 'post-new.php' == $pagenow ) ) || ( 'edit.php' == $pagenow && 'edit-hfb-post' == $screen->id ) ) {
			wp_enqueue_style( 'el-hfb-admin-style', HEADER_FOOTER_BUILDER_ASSETS_URL . 'admin/css/admin.css', [], HEADER_FOOTER_BUILDER_VERSION );
			wp_enqueue_script( 'hfb-admin-script', HEADER_FOOTER_BUILDER_ASSETS_URL . 'admin/js/admin.js', [], HEADER_FOOTER_BUILDER_VERSION );
		}
	}

	/**
	 * Adds classes to the body tag conditionally.
	 *
	 * @param  Array $classes array with class names for the body tag.
	 *
	 * @return Array          array with class names for the body tag.
	 */
	public function body_class( $classes ) {
		if ( hfb_header_enabled() ) {
			$classes[] = 'ehf-header';
		}

		if ( hfb_footer_enabled() ) {
			$classes[] = 'ehf-footer';
		}

		$classes[] = 'ehf-template-' . $this->template;
		$classes[] = 'ehf-stylesheet-' . get_stylesheet();

		return $classes;
	}

	/**
	 * Display Unsupported theme notice if the current theme does add support for 'header-footer-builder'
	 *
	 * @since  1.0.3
	 */
	public function setup_unsupported_theme() {
		if ( ! current_theme_supports( 'header-footer-builder' ) ) {
			require_once HEADER_FOOTER_BUILDER_PATH . 'includes/theme-support/default/class-hfb-fallback-theme-support.php';
		}
	}

	/**
	 * Prints the Header content.
	 */
	public static function get_header_content() {
		echo self::$elementor_instance->frontend->get_builder_content_for_display( get_hfb_header_id() );
	}

	/**
	 * Prints the Footer content.
	 */
	public static function get_footer_content() {
		echo "<div class='footer-width-fixer'>";
		echo self::$elementor_instance->frontend->get_builder_content_for_display( get_hfb_footer_id() );
		echo '</div>';
	}

	/**
	 * Prints the Before Footer content.
	 */
	public static function get_before_footer_content() {
		echo "<div class='footer-width-fixer'>";
		echo self::$elementor_instance->frontend->get_builder_content_for_display( hfb_get_before_footer_id() );
		echo '</div>';
	}

	/**
	 * Get option for the plugin settings
	 *
	 * @param  mixed $setting Option name.
	 * @param  mixed $default Default value to be received if the option value is not stored in the option.
	 *
	 * @return mixed.
	 */
	public static function get_settings( $setting = '', $default = '' ) {
		if ( 'type_header' == $setting || 'type_footer' == $setting || 'type_before_footer' == $setting ) {
			$templates = self::get_template_id( $setting );

			$template = ! is_array( $templates ) ? $templates : $templates[0];

			$template = apply_filters( "hfb_get_settings_{$setting}", $template );

			return $template;
		}
	}

	/**
	 * Get header or footer template id based on the meta query.
	 *
	 * @param  String $type Type of the template header/footer.
	 *
	 * @return Mixed       Returns the header or footer template id if found, else returns string ''.
	 */
	public static function get_template_id( $type ) {
		$option = [
			'location'  => 'ehf_target_include_locations',
			'exclusion' => 'ehf_target_exclude_locations',
			'devices'     => 'ehf_target_devices',
			'users'     => 'ehf_target_user_roles',
		];

		$hfb_templates = HFB_Conditions_Setting::get_instance()->get_posts_by_conditions( 'hfb-post', $option );

		foreach ( $hfb_templates as $template ) {
			if ( get_post_meta( absint( $template['id'] ), 'ehf_template_type', true ) === $type ) {
				return $template['id'];
			}
		}

		return '';
	}

	/**
	 * Callback to shortcode.
	 *
	 * @param array $atts attributes for shortcode.
	 */
	public function render_template( $atts ) {
		$atts = shortcode_atts(
			[
				'id' => '',
			],
			$atts,
			'hfb_template'
		);

		$id = ! empty( $atts['id'] ) ? apply_filters( 'hfb_render_template_id', intval( $atts['id'] ) ) : '';

		if ( empty( $id ) ) {
			return '';
		}

		if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
			$css_file = new \Elementor\Core\Files\CSS\Post( $id );
		} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
			// Load elementor styles.
			$css_file = new \Elementor\Post_CSS_File( $id );
		}
			$css_file->enqueue();

		return self::$elementor_instance->frontend->get_builder_content_for_display( $id );
	}
	
	

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Header_Footer_Builder_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->init_classes();
		$this->template = get_template();

		if ( defined( 'ELEMENTOR_VERSION' ) && is_callable( 'Elementor\Plugin::instance' ) ) {
			self::$elementor_instance = Elementor\Plugin::instance();
			
			$this->includes();
			
			add_action( 'init', [ $this, 'setup_unsupported_theme' ] );
			
			// Scripts and styles.
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );

			add_filter( 'body_class', [ $this, 'body_class' ] );
			add_action( 'switch_theme', [ $this, 'reset_unsupported_theme_notice' ] );

			add_shortcode( 'hfb_template', [ $this, 'render_template' ] );

			add_action( 'hfb_notice_before_markup_header-footer-builder-rating', [ $this, 'rating_notice_css' ] );

		} else {
			add_action( 'admin_notices', [ $this, 'elementor_not_available' ] );
			add_action( 'network_admin_notices', [ $this, 'elementor_not_available' ] );
		}
		$this->loader->run();
	}
	public function init_classes() {
		$this->admin = new Header_Footer_Builder_Admin( $this->get_plugin_name(), $this->get_version() );
		Header_Footer_Builder::instance();
		
	}
	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Header_Footer_Builder_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
/**
 * Is elementor plugin installed.
 */
if ( ! function_exists( '_is_elementor_installed' ) ) {

	/**
	 * Check if Elementor is installed
	 *
	 * @since 1.6.0
	 *
	 * @access public
	 */
	function _is_elementor_installed() {
		return ( file_exists( WP_PLUGIN_DIR . '/elementor/elementor.php' ) ) ? true : false;
	}
}