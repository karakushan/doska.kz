<?php

/**
 * @package    Directorypress_Payment_Manager
 * @subpackage Directorypress_Payment_Manager/includes
 * @author     Designinvento <developers@designinvento.net>
 */
class Directorypress_Payment_Manager {

	protected $loader;
	protected $plugin_name;
	protected $version;
	
	public function __construct() {
		
		$this->version = DIRECTORYPRESS_PAYMENT_MANAGER_VERSION;
		$this->plugin_name = 'directorypress-payment-manager';
		$this->load_dependencies();
		$this->set_locale();

	}
	
	private function load_dependencies() {

		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-directorypress-payment-manager-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-directorypress-payment-manager-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-directorypress-packages.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-directorypress-payment-manager-admin.php';
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-directorypress-payment-manager-woocomerce.php';
		$this->loader = new Directorypress_Payment_Manager_Loader();

	}

	private function set_locale() {

		$plugin_i18n = new Directorypress_Payment_Manager_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	public function run() {
		$this->directorypress_init_classes();
		$this->loader->run();
	}
	
	public function directorypress_init_classes() {
		global $directorypress_object;
		if(is_object( $directorypress_object)){
			$directorypress_object->admin = new Directorypress_Payment_Manager_Admin;
		}
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
