<?php

/**
 * @since      1.0.0
 * @package    Designinvento_Elementor_Widgets
 * @subpackage Designinvento_Elementor_Widgets/includes
 * @author     Designinvento <team@designinvento.net>
 */
class Designinvento_Elementor_Widgets {

	
	protected $loader;
	protected $plugin_name;
	protected $version;

	public function __construct() {
		
		$this->version = DESIGNINVENTO_ELEMENTOR_WIDGETS_VERSION;
		$this->plugin_name = 'designinvento-elementor-widgets';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Designinvento_Elementor_Widgets_Loader. Orchestrates the hooks of the plugin.
	 * - Designinvento_Elementor_Widgets_i18n. Defines internationalization functionality.
	 * - Designinvento_Elementor_Widgets_Admin. Defines all hooks for the admin area.
	 * - Designinvento_Elementor_Widgets_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-designinvento-elementor-widgets-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-designinvento-elementor-widgets-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-designinvento-elementor-widgets-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-designinvento-elementor-widgets-public.php';

		$this->loader = new Designinvento_Elementor_Widgets_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Designinvento_Elementor_Widgets_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Designinvento_Elementor_Widgets_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Designinvento_Elementor_Widgets_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Designinvento_Elementor_Widgets_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		
		$this->loader->add_action('elementor/widgets/register', $this, 'register_elementor_widgets');
		
		$this->loader->run();
	}
	
	public function register_elementor_widgets() {
		
		// Register Widgets
		if(class_exists('DirectoryPress')){
			if(!class_exists('Pacz_Elementor_Advanced_Location_Widget')){
				require_once DEW_PATH . 'includes/elementor/advanced-locations.php';
				\Elementor\Plugin::instance()->widgets_manager->register( new Pacz_Elementor_Advanced_Location_Widget() );
			}
			if(!class_exists('Pacz_Elementor_Advanced_Categories_Widget')){
				require_once DEW_PATH . 'includes/elementor/advanced-categories.php';
				\Elementor\Plugin::instance()->widgets_manager->register( new Pacz_Elementor_Advanced_Categories_Widget() );
			}
			if(!class_exists('Pacz_Elementor_Advanced_Terms_Slider_Widget')){
				require_once DEW_PATH . 'includes/elementor/advanced-terms-slider.php';
				\Elementor\Plugin::instance()->widgets_manager->register( new Pacz_Elementor_Advanced_Terms_Slider_Widget() );
			}
			require_once DEW_PATH . 'includes/elementor/advanced-terms.php';
			\Elementor\Plugin::instance()->widgets_manager->register( new Pacz_Elementor_Advanced_Terms_Widget() );
			
		}
		
		if(class_exists('Header_Footer_Builder')){
			if(!class_exists('\HFB\WidgetsManager\Widgets\Pacz_Elementor_Login')){
				require_once DEW_PATH . 'includes/elementor/login.php';
				\Elementor\Plugin::instance()->widgets_manager->register( new \HFB\WidgetsManager\Widgets\Pacz_Elementor_Login() );
			}
		}
		if(class_exists('Header_Footer_Builder')){
			if(!class_exists('\HFB\WidgetsManager\Widgets\Pacz_Nav_Menu')){
				require_once DEW_PATH . 'includes/elementor/pacz-nav-menu.php';
				\Elementor\Plugin::instance()->widgets_manager->register( new \HFB\WidgetsManager\Widgets\Pacz_Nav_Menu() );
			}
		}
		if(class_exists('DirectoryPress') && class_exists('Header_Footer_Builder')){
			if(!class_exists('Pacz_DirectoryPress_Search_Widget')){
				require_once DEW_PATH . 'includes/elementor/directorypress-search.php';
				\Elementor\Plugin::instance()->widgets_manager->register( new Pacz_DirectoryPress_Search_Widget() );
			}
			require_once DEW_PATH . 'includes/elementor/directorypress-listing-mobile.php';
			\Elementor\Plugin::instance()->widgets_manager->register( new Pacz_DirectoryPress_Mobile_Listing_Widget() );
			
		}
		
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
	 * @return    Designinvento_Elementor_Widgets_Loader    Orchestrates the hooks of the plugin.
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
