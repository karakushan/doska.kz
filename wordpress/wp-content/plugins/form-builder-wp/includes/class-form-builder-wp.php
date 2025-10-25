<?php

/**
 * @since      1.0.0
 * @package    Form_Builder_Wp
 * @subpackage Form_Builder_Wp/includes
 * @author     Designinvento <team@designinvento.net>
 */
class Form_Builder_Wp {

	protected $loader;
	protected $plugin_name;
	protected $version;
	public $admin;
	public $admin_main;
	public function __construct() {
		if ( defined( 'FORM_BUILDER_WP_VERSION' ) ) {
			$this->version = FORM_BUILDER_WP_VERSION;
		} else {
			$this->version = '1.1.8';
		}
		$this->plugin_name = 'form-builder-wp';

		$this->load_dependencies();
		$this->set_locale();
		//$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-form-builder-wp-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-form-builder-wp-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-form-builder-wp-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-form-builder-wp-public.php';

		$this->loader = new Form_Builder_Wp_Loader();

	}

	private function set_locale() {

		$plugin_i18n = new Form_Builder_Wp_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	private function define_admin_hooks() {

		$plugin_admin = new Form_Builder_Wp_Admin_Main( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	private function define_public_hooks() {

		$plugin_public = new Form_Builder_Wp_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	public function run() {
		
		$this->admin_main =  new Form_Builder_Wp_Admin_Main($this->get_plugin_name(), $this->get_version());
		add_action( 'plugins_loaded', array($this,'plugins_loaded'), 9 );
		add_action('wpfb_roles', array($this, 'activate'));
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
	
	public function plugins_loaded(){
		if(!defined('ELEMENTOR_VERSION')){
			add_action('admin_notices', array($this,'notice'));
			return;
		}
		$this->_includes();
		$this->_init_hooks();
		
		add_action( 'init', array( __CLASS__, 'define_ajax' ), 0 );
		add_action( 'template_redirect', array( __CLASS__, 'do_ajax' ), 0 );
	}
	
	public static function define_ajax(){
		if ( ! empty( $_GET['wpfb-form-ajax'] ) ) {
			if(!defined('DOING_AJAX')){
				define('DOING_AJAX', true);
			}
			$GLOBALS['wpdb']->hide_errors();
		}
	}
	
	private static function _ajax_headers(){
		if ( ! headers_sent() ) {
			send_origin_headers();
			send_nosniff_header();
			if ( ! defined( 'DONOTCACHEPAGE' ) ) {
				define( 'DONOTCACHEPAGE', true );
			}
			if ( ! defined( 'DONOTCACHEOBJECT' ) ) {
				define( 'DONOTCACHEOBJECT', true );
			}
			if ( ! defined( 'DONOTCACHEDB' ) ) {
				define( 'DONOTCACHEDB', true );
			}
			nocache_headers();
			header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
			header( 'X-Robots-Tag: noindex' );
			status_header( 200 );
		} elseif ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			headers_sent( $file, $line );
			trigger_error( "wpfb_form_ajax_headers cannot set headers - headers already sent by {$file} on line {$line}", E_USER_NOTICE );
		}
	}
	
	public static function do_ajax(){
		global $wp_query;
	
		if ( ! empty( $_GET['wpfb-form-ajax'] ) ) {
			$wp_query->set( 'wpfb-form-ajax', sanitize_text_field( wp_unslash( $_GET['wpfb-form-ajax'] ) ) );
		}
	
		$action = $wp_query->get( 'wpfb-form-ajax' );
	
		if ( $action ) {
			self::_ajax_headers();
			$action = sanitize_text_field( $action );
			do_action( 'wpfb_form_ajax_' . $action );
			wp_die();
		}
	}
	
	private function _includes(){
		require_once FORM_BUILDER_WP_PATH.'includes/mailchimp/init.php';
		require_once FORM_BUILDER_WP_PATH .'includes/functions.php';
		require_once FORM_BUILDER_WP_PATH .'includes/shortcodes.php';
		require_once FORM_BUILDER_WP_PATH .'includes/scan_tag.php';
		require_once FORM_BUILDER_WP_PATH .'includes/form_actions.php';
		require_once FORM_BUILDER_WP_PATH .'includes/assets.php';
		require_once FORM_BUILDER_WP_PATH .'includes/db.php';
		require_once FORM_BUILDER_WP_PATH .'includes/post_types.php';
		
		require_once FORM_BUILDER_WP_PATH .'includes/editor.php';
		
		require_once FORM_BUILDER_WP_PATH .'includes/submission.php';
		require_once FORM_BUILDER_WP_PATH .'includes/field.php';
		require_once FORM_BUILDER_WP_PATH .'includes/paypal.php';
		
		if(is_admin()){
			//require_once FORM_BUILDER_WP_PATH .'includes/admin.php';
			require_once FORM_BUILDER_WP_PATH .'includes/entries.php';
			require_once FORM_BUILDER_WP_PATH .'includes/settings.php';
			require_once FORM_BUILDER_WP_PATH .'includes/meta_box.php';
		}
	}
	
	private function _init_hooks(){
		
		add_action('init',array($this,'init'));
		
		add_filter('template_include',array($this,'single_template'),1000);
		
		wpfb_form_get_request_uri();
		
		add_action('wpfb_form_ajax_submit', array($this,'form_submit'));
		
		add_action( 'elementor/init', array( $this, 'elementor_init' ),20);
	}
	
	public function elementor_init(){
		
		require_once FORM_BUILDER_WP_PATH .'includes/widgets/base.php';
		
		require_once FORM_BUILDER_WP_PATH .'includes/widgets/form.php';
		add_shortcode('wpfb_form', array(Form_Builder_Wp_ShortCode_Base::get_instance(),'render'));
		/**
		 * @param \Elementor\Widgets_Manager $widgets_manager
		 */
		add_action( 'elementor/widgets/register', function($widgets_manager){
			$widgets_manager->register( new Form_Builder_Wp_Widget_Form );
		});
		
		add_action('elementor/preview/enqueue_scripts', function() {
			Form_Builder_Wp_ShortCode_Base::get_instance()->editor_enqueue('wpfb_form');
		},1);
			
		foreach (wpfb_form_get_fields() as $shortcode_tag=>$setting){
			$file = FORM_BUILDER_WP_PATH .'includes/widgets/'.$setting['file'];
			if ( $file && is_readable( $file ) ) {
				require_once $file;
			}
			/**
			 * @param \Elementor\Widgets_Manager $widgets_manager
			 */
			add_action( 'elementor/widgets/register', function($widgets_manager) use($setting){
				$class_name = $setting['widget_class'];
				$widgets_manager->register( new $class_name );
			});
			
			add_action('elementor/preview/enqueue_scripts', function() use($shortcode_tag){
				Form_Builder_Wp_ShortCode_Base::get_instance()->editor_enqueue($shortcode_tag);
			},1);
			
			add_shortcode($shortcode_tag, array(Form_Builder_Wp_ShortCode_Base::get_instance(),'render'));
		}
	}
	
	
	public function init(){
		if(class_exists('WYSIJA')){
			define('FORM_BUILDER_WP_SUPORT_WYSIJA', true);
		}
		
		if(defined('MYMAIL_DIR')){
			define('FORM_BUILDER_WP_SUPORT_MYMAIL', true);
		}
		
		if(class_exists('Groundhogg')){
			define('FORM_BUILDER_WP_SUPORT_GROUNDHOGG', true);
		}
		
		//Custom WP User URL
		add_filter('login_url', array($this,'login_url'));
		add_filter('logout_url', array($this,'logout_url'));
		add_filter('register_url', array($this,'register_url'));
		add_filter('lostpassword_url', array($this,'lostpassword_url'));
		
		if(!is_admin()){
			//Popup Form
			add_action('wp_head', array($this,'get_popup_form'),1);
			add_action( 'wp_footer',array($this,'print_form_popup'), 50 );
		}
	}
	
	public function single_template($template){
		$object = get_queried_object();
		if ( ! empty( $object->post_type ) && 'wpfbform'===$object->post_type ) {
			return FORM_BUILDER_WP_PATH .'includes/editor-templates/single.php';
		}
		return $template;
	}
	
	public function notice(){
		echo '<div class="updated">
			    <p>' . sprintf('<strong>%s</strong> requires <strong><a href="https://wordpress.org/plugins/elementor/" target="_blank">Elementor Page Builder</a></strong> plugin  to be installed and activated on your site.', 'form-builder-wp') . '</p>
			  </div>';
	}
	
	
	public function form_submit(){
		if ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && 'XMLHttpRequest' !== $_SERVER['HTTP_X_REQUESTED_WITH'] && $_SERVER['REQUEST_METHOD'] !== 'POST' )
			die(0);
		
		$submission = Form_Builder_Wp_Submission::get_instance(true);
		$result = array(
			'form_id' => $submission->get_form_id(),
			'status' => $submission->get_status(),
			'message' => $submission->get_response()
		);
		if ( $submission->is( 'validation_failed' ) ) {
			$result['invalid_fields'] = $submission->get_invalid_fields();
			
		}
		if($submission->is('success')){

			if('refresh'==$submission->get_on_success_action())
				$result['refresh'] = 1;
			elseif( $redirect = $submission->get_redirect_url())
				$result['redirect'] = $redirect;
			
			if($on_ok = $submission->get_on_ok())
				$result['onOk']  = $on_ok;
		}
		do_action( 'wpfb_form_submit', $result, $submission);
		$response = apply_filters( 'wpfb_form_ajax_json_echo', $result, $submission );
		wp_send_json($response);
		die();
	}
	
	public function activate(){
		if(!class_exists('Form_Builder_Wp_DB'))
			require_once FORM_BUILDER_WP_PATH .'includes/db.php';
		global $wpfbform_db;
		$this->_create_roles();
		$wpfbform_db->create_table();
		$this->_create_upload_dir();
		flush_rewrite_rules();
	}
	
	private function _create_upload_dir(){
		$upload_dir = wp_upload_dir();
		$dir = $upload_dir['basedir'] . '/wpfbform';
		wp_mkdir_p($dir);
	}
	
	private function _create_roles(){
		global $wp_roles;
	
		if ( class_exists( 'WP_Roles' ) ) {
			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}
		}
	
		if ( is_object( $wp_roles ) ) {
	
			$capability = array(
				"edit_wpfbform",
				"read_wpfbform",
				"delete_wpfbform",
				"edit_wpfbforms",
				"edit_others_wpfbforms",
				"publish_wpfbforms",
				"read_private_wpfbforms",
				"delete_wpfbforms",
				"delete_private_wpfbforms",
				"delete_published_wpfbforms",
				"delete_others_wpfbforms",
				"edit_private_wpfbforms",
				"edit_published_wpfbforms",
			);
			foreach ( $capability as $cap ) {
				$wp_roles->add_cap( 'administrator', $cap );
			}
		}
	}
	
	private function _remove_roles(){
		global $wp_roles;
	
		if ( class_exists( 'WP_Roles' ) ) {
			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}
		}
	
		if ( is_object( $wp_roles ) ) {
	
			$capability = array(
				"edit_wpfbform",
				"read_wpfbform",
				"delete_wpfbform",
				"edit_wpfbforms",
				"edit_others_wpfbforms",
				"publish_wpfbforms",
				"read_private_wpfbforms",
				"delete_wpfbforms",
				"delete_private_wpfbforms",
				"delete_published_wpfbforms",
				"delete_others_wpfbforms",
				"edit_private_wpfbforms",
				"edit_published_wpfbforms",
			);
			foreach ( $capability as $cap ) {
				$wp_roles->remove_cap( 'administrator', $cap );
			}
		}
	}
	
	public function login_url($login_url){
		$user_login = wpfb_form_get_option('user_login');
		if($user_login){
			$login_url = get_permalink($user_login);
		}
		return $login_url;
	}
	
	public function register_url($register_url){
		$user_regiter = wpfb_form_get_option('user_regiter');
		if($user_regiter){
			$register_url = get_permalink($user_regiter);
		}
		return $register_url;
	}
	
	public function logout_url($logout_url,$redirect=''){
		$user_logout = wpfb_form_get_option('user_logout_redirect_to');
		$args = array();
		if($user_logout){
			$redirect_to = get_permalink($user_logout);
			$args['redirect_to'] = urlencode( $redirect_to );
		}
		return add_query_arg($args, $logout_url);
	}
	
	public function lostpassword_url($lostpassword_url){
		$user_forgotten = wpfb_form_get_option('user_forgotten');
		if($user_forgotten){
			$lostpassword_url = get_permalink($user_forgotten);
		}
		return $lostpassword_url;
	}
	
	public function get_popup_form(){
		global $wpfb_form_popup;
		
		if(\Elementor\Plugin::$instance->preview->is_preview_mode()){
			return;
		}
		
		$args = array(
			'post_type'=>'wpfbform',
			'posts_per_page'=> -1,
			'post_status'=>'publish',
			'meta_query' => array(
				array(
					'key' => '_form_popup',
					'value' => '1'
				)
			)
		);
		$form = new WP_Query($args);
		$popup = array();
		if($form->have_posts()):
		
			//enqueue cookie
			wp_enqueue_script('js-cookie');
		
			while ($form->have_posts()):
				$form->the_post(); global $post;
			
				$auto_open = get_post_meta($post->ID,'_form_popup_auto_open',true);
			
				$one = get_post_meta($post->ID,'_form_popup_one',true);
				$close = get_post_meta($post->ID,'_form_popup_auto_close',true);
				$title = get_post_meta($post->ID,'_form_popup_title',true);
				$data_attr = '';
				if(!empty($auto_open)){
					$data_attr = 'data-auto-open="1" data-open-delay="'.absint(get_post_meta($post->ID,'_form_popup_auto_open_delay',true)).'" '.(!empty($one) ? 'data-one-time="1"' : 'data-one-time="0"').' '.(!empty($close ) ? 'data-auto-close="1" data-close-delay="'.absint(get_post_meta($post->ID,'_form_popup_auto_close_delay',true)).'"':'data-auto-close="0"');
				}
				$popup[] = '<div id="wpfbformpopup-'.$post->ID.'" data-id="'.$post->ID.'" class="wpfb-form-popup" '.$data_attr.' style="display:none">';
				$popup[] = '<div class="wpfb-form-popup-container" style="width:'.absint(get_post_meta($post->ID,'_form_popup_width',true)).'px">';
				
				if(!empty($title)){
					$popup[] = '<div class="wpfb-form-popup-header has-title">';
					$popup[] = '<h3>'.get_the_title($post->ID).'</h3>';
				}else{
					$popup[] = '<div class="wpfb-form-popup-header">';
				}
				$popup[] = '<a class="wpfb-form-popup-close"></a>';
				$popup[] = '</div>';
				$popup[] = '<div class="wpfb-form-popup-body">';
				$popup[] = do_shortcode('[wpfb_form form_id="'.$post->ID.'"]');
				$popup[] = '</div>';
				$popup[] = '</div>';
				$popup[] = '</div>';
			endwhile;
		endif;
		$wpfb_form_popup = implode("\n", $popup);
		if(!empty($popup)){
			$wpfb_form_popup .= '<div class="wpfb-form-pop-overlay"></div>';
		}
		wp_reset_postdata();
	}
	
	public function print_form_popup(){
		global $wpfb_form_popup;
		if(\Elementor\Plugin::$instance->preview->is_preview_mode()){
			return;
		}
		if(!empty($wpfb_form_popup)){
			echo $wpfb_form_popup;
		}
	}

}
