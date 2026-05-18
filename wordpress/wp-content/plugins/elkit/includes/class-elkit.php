<?php

/**
 * @since      1.0.0
 * @package    Elkit
 * @subpackage Elkit/includes
 * @author     Designinvento <team@designinvento.net>
 */


class Elkit {

	protected $loader;
	protected $plugin_name;
	protected $version;

	public function __construct() {
		if ( defined( 'ELKIT_VERSION' ) ) {
			$this->version = ELKIT_VERSION;
		} else {
			$this->version = '1.0.7';
		}
		$this->plugin_name = 'elkit';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	private function load_dependencies() {

		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-elkit-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-elkit-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/helper.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/template-lib.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-elkit-admin.php';
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-elkit-public.php';

		$this->loader = new Elkit_Loader();

	}

	private function set_locale() {

		$plugin_i18n = new Elkit_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	private function define_admin_hooks() {

		$plugin_admin = new Elkit_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	private function define_public_hooks() {

		$plugin_public = new Elkit_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	public function run() {
		// enqueue scripts
		//add_action('wp_head', [$this, 'inline_script']);
		require_once ELKIT_PATH . 'includes/section-controls.php';
		require_once ELKIT_PATH . 'includes/icons.php';
		add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
		add_action('elementor/frontend/before_enqueue_scripts', [$this, 'editor_scripts'], 99);
		new \Elementor\Elkit_Font_Icons();
		new \Elementor\Elkit_Section_Effect_Controls();
		add_action( 'elementor/widgets/register', [ $this, 'elkit_register_widgets' ] );
		add_action( 'elementor/elements/categories_registered', [ $this, 'elkit_widget_categories'] );
		$this->loader->run();
	}
	
	public function elkit_widget_categories( $elements_manager ) {

		$elements_manager->add_category(
			'elkit',
			[
				'title' => __( 'ElKit', 'elkit' ),
				'icon' => 'fa fa-plug',
			]
		);

	}

	public function elkit_register_widgets($widget_manager) {
		
		require_once ELKIT_PATH . 'includes/widget-controls.php';
		require_once ELKIT_PATH . 'includes/widgets/heading.php';
		require_once ELKIT_PATH . 'includes/widgets/text-editor.php';
		require_once ELKIT_PATH . 'includes/widgets/image-box.php';
		require_once ELKIT_PATH . 'includes/widgets/tabs.php';
		require_once ELKIT_PATH . 'includes/widgets/icon-list.php';
		require_once ELKIT_PATH . 'includes/widgets/icon-box.php';
		require_once ELKIT_PATH . 'includes/widgets/clients.php';
		require_once ELKIT_PATH . 'includes/widgets/button.php';
		require_once ELKIT_PATH . 'includes/widgets/woo-products.php';
		require_once ELKIT_PATH . 'includes/widgets/testimonials.php';
		require_once ELKIT_PATH . 'includes/widgets/countdown.php';
		
		// Register Widgets
		
		new \Elementor\Elkit_Widget_Effect_Controls();
		
		\Elementor\Plugin::instance()->widgets_manager->register( new Elkit_Elementor_Heading_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Elkit_Elementor_TextEditor_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Elkit_Elementor_ImageBox_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Elkit_Elementor_Tabs_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Elkit_Icon_List_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Elkit_Icon_Box_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Elkit_Elementor_Clients_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Elkit_Button() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Elkit_Woo_Products() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Elkit_Testimonials_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Elkit_Countdown() );
	}

	public function enqueue_scripts() {
		//wp_enqueue_style( 'elementskit-parallax-style', ELKIT_RESOURCES_URL . 'assets/css/style.css' , null, \ElementsKit::version() );
		wp_enqueue_script( 'jarallax', ELKIT_RESOURCES_URL . 'js/jarallax.js', array('jquery'), ELKIT_VERSION, false );
		wp_enqueue_script( 'elementskit-parallax-frontend-defer', ELKIT_RESOURCES_URL . 'js/parallax-frontend-scripts.js', array('jquery'), ELKIT_VERSION, true );
	}

	public function editor_scripts(){
		wp_enqueue_script( 'elementskit-parallax-admin-defer', ELKIT_RESOURCES_URL . 'js/parallax-admin-scripts.js', array('jquery', 'elementor-frontend'), ELKIT_VERSION, true );
	}

	public function inline_script(){
		echo '
			<script type="text/javascript">
				var elementskit_module_parallax_url = "'.$this->url.'"
			</script>
		';
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
