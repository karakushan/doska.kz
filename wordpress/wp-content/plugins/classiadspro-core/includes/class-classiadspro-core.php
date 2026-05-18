<?php

/**
 * @since      1.0.0
 * @package    Classiadspro_Core
 * @subpackage Classiadspro_Core/includes
 * @author     Designinvento <team@designinvento.net>
 */
class Classiadspro_Core {

	protected $loader;
	protected $plugin_name;
	protected $version;

	public function __construct() {
		
		$this->version = CLASSIADSPRO_CORE_VERSION;
		$this->plugin_name = 'classiadspro-core';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		

	}

	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-classiadspro-core-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-classiadspro-core-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/register-posttype-clients.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/register-posttype-employees.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/register-posttype-testimonial.php';
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/dynamic-helper.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/dynamic.php';
	
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-classiadspro-core-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-classiadspro-core-public.php';

		$this->loader = new Classiadspro_Core_Loader();

	}
	
	private function set_locale() {

		$plugin_i18n = new Classiadspro_Core_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}
	
	private function define_admin_hooks() {

		$plugin_admin = new Classiadspro_Core_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}
	private function define_public_hooks() {

		$plugin_public = new Classiadspro_Core_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}
	
	public function classiadspro_widgets(){
		if(defined('PACZ_THEME_SETTINGS')){
			require_once PCPT_PATH . 'includes/widgets/widgets-popular-posts.php';
			require_once PCPT_PATH . 'includes/widgets/widgets-recent-posts.php';
			require_once PCPT_PATH . 'includes/widgets/widgets-video.php';
			require_once PCPT_PATH . 'includes/widgets/widgets-comments.php';
			require_once PCPT_PATH . 'includes/widgets/widgets-custom-menu.php';
			require_once PCPT_PATH . 'includes/widgets/widgets-image.php';
			require_once PCPT_PATH . 'includes/widgets/widgets-about.php';
			//require_once PCPT_PATH . 'includes/widgets/widgets-flickr-feeds.php';
			require_once PCPT_PATH . 'includes/widgets/widgets-author.php';
			require_once PCPT_PATH . 'includes/widgets/widgets-social-networks.php';
			require_once PCPT_PATH . 'includes/widgets/widgets-subscription.php';

			register_widget("Classiadspro_Widget_Popular_Posts");
			register_widget("Classiadspro_Widget_Recent_Posts");
			register_widget("Classiadspro_Widget_Video");
			//register_widget("Classiadspro_Widget_Flickr_Feeds");
			register_widget("Classiadspro_WP_Widget_Recent_Comments");
			register_widget("Classiadspro_WP_Nav_Menu_Widget");
			register_widget("Classiadspro_Widget_Image");
			register_widget("Classiadspro_Widget_About");
			register_widget("Classiadspro_Widget_Author");
			register_widget("Classiadspro_Widget_Social");
			register_widget("Classiadspro_Widget_Subscription_Form");
		}
	}
	
	public function classiadspro_register_elementor_widgets() {
		
		require_once PCPT_PATH . 'includes/elementor/blog.php';
		
		// Register Widgets
		\Elementor\Plugin::instance()->widgets_manager->register( new Pacz_Elementor_Blog_Widget() );

		if(class_exists('Header_Footer_Builder')){
			require_once PCPT_PATH . 'includes/elementor/page-title.php';
			\Elementor\Plugin::instance()->widgets_manager->register( new \HFB\WidgetsManager\Widgets\Pacz_Page_Title() );
		}
	
	}
	public function classiadspro_vc_init() {
		
		//if ( class_exists('WPBakeryShortCode')) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/vc-integration.php';
		//}
	}
	public function classiadspro_redux_init() {
		
		//if ( class_exists('Pacz_Admin')) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/redux-core/framework.php';
		//}
	}
	public function run() {
		
		$this->loader->add_action('widgets_init', $this, 'classiadspro_widgets');
		$this->loader->add_action('elementor/widgets/register', $this, 'classiadspro_register_elementor_widgets');
		$this->loader->add_action('plugins_loaded', $this, 'classiadspro_vc_init');
		//$this->loader->add_action('init ', $this, 'classiadspro_redux_init');
		$this->classiadspro_redux_init();
		$this->loader->run();
	}
	
	public function get_plugin_name() {
		return $this->plugin_name;
	}
	
	public function get_loader() {
		return $this->loader;
	}
	
	public function get_version() {
		return $this->version;
	}

}
