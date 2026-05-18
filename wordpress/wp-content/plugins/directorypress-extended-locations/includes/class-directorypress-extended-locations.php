<?php

/**
 * @package    Directorypress_Extended_Locations
 * @subpackage Directorypress_Extended_Locations/includes
 * @author     Designinvento <developers@designinvento.net>
 */
class Directorypress_Extended_Locations {

	protected $loader;
	protected $plugin_name;
	protected $version;
	
	public function __construct() {

		$this->version = DIRECTORYPRESS_EXTENDED_LOCATIONS_VERSION;
		$this->plugin_name = 'directorypress-extended-locations';
		$this->load_dependencies();
		$this->set_locale();

	}
	
	private function load_dependencies() {
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-directorypress-extended-locations-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-directorypress-extended-locations-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-directorypress-extended-locations-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/directorypress_class_main_locations_depths.php';
		
		$this->loader = new Directorypress_Extended_Locations_Loader();

	}

	private function set_locale() {

		$plugin_i18n = new Directorypress_Extended_Locations_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}
	
	public function run() {
		$this->directorypress_init_classes();
		$this->loader->run();
	}
	
	public function directorypress_init_classes() {
		global $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object;
		if(is_object( $directorypress_object)){
			$directorypress_object->admin = new Directorypress_Extended_Locations_Admin;
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
