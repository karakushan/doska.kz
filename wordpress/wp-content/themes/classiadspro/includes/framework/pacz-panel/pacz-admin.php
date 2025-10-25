<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pacz_Admin {
	
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menus' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}
	
	static function dashboard_menu() {
		global $submenu;

		$menus			= $submenu['pacz-admin-classiads-settings'];
		$menu_size		= sizeof( $menus );
		$menu			= '';
		$crt_pg_name	= get_admin_page_title();
		$base			= explode( '_pacz', get_current_screen()->base);
		$base			= 'pacz' . $base[1];

		foreach ($menus as $sub_menu ) {
			$acive_page = ( $base == $sub_menu[2] ) ? ' nav-tab-active' : '' ;
			$menu .= '<a class="nav-tab' . $acive_page . '" href="' . esc_url( self_admin_url( 'admin.php?page='.$sub_menu[2] ) ) . '">' . esc_html( $sub_menu[0], 'classiadspro' ) . '</a>';
		}
		$allowed_html = array(
			'a' => array(
				'href' => array(),
				'title' => array(),
				'class' => array(),
				
			)
		);
		echo wp_kses($menu, $allowed_html);
	}
	static function listing_dashboard_link() {
		
	}
	
	static function pacz_dashboard_link() {
		
	}
	
	static function pacz_dashboard_header() {
		echo '<h1>'. esc_html__( 'Welcome to ', 'classiadspro' ) . Pacz_Admin::theme( 'name' ).'</h1>';
		echo '<div class="about-text">'.Pacz_Admin::theme( 'name' ) . esc_html__( ' is now installed and ready to use! Let’s convert your imaginations to real things on the web!', 'classiadspro' ).'</div>';
		echo '<div class="wp-badge">'. esc_html__( 'Version', 'classiadspro' ). Pacz_Admin::theme( 'version' ).'</div>';
			Pacz_Admin::listing_dashboard_link();
		echo '<h2 class="nav-tab-wrapper wp-clearfix">';
			Pacz_Admin::dashboard_menu();
		echo '</h2>';
		
	}
	
	public function enqueue_scripts() {
		 if ( isset( $_GET['page'] ) ) :
			if ( substr( $_GET['page'], 0, 11 ) == "pacz-admin-") :
				
				// admin pages style
				
				wp_enqueue_style('bootstrap', PACZ_THEME_STYLES . '/bootstrap.min.css');
				wp_enqueue_style( 'pacz-admin-panel-styles', PACZ_THEME_CONTROL_PANEL_URI . '/assets/css/admin-panel.css', 99 );
				if ( $_GET['page'] == 'pacz-admin-icon-library' ) :
					wp_enqueue_style('pacz-icon-libs', PACZ_THEME_ADMIN_ASSETS_URI . '/css/icon-library.css');
					wp_enqueue_style('paczfont-icon', PACZ_THEME_STYLES . '/fonticon-custom.min.css');
					wp_enqueue_script('icon-libs-filter', PACZ_THEME_ADMIN_ASSETS_URI . '/js/icon-libs-filter.js',  array( 'jquery' ), time(), true);
				endif;
				wp_enqueue_script('bootstrap', PACZ_THEME_JS . '/bootstrap.min.js',  array( 'jquery' ), time(), true);
			endif; // substr
		endif; // isset 
	}

	public function admin_menus() {
		
		call_user_func_array( 'add' . '_menu_' . 'page', array(
				esc_html__( 'Classiads Dashboard', 'classiadspro' ),
				esc_html__( 'Classiads Dashboard', 'classiadspro' ),
				'manage_options',
				'pacz-admin-classiads-settings',
				array($this, 'screen_welcome'),
				'',
				0
		));
		call_user_func_array( 'add' . '_sub' . 'menu_' . 'page', array(
			'pacz-admin-classiads-settings',
			esc_html__( 'Classiads Dashboard', 'classiadspro' ),
			esc_html__( 'Classiads Dashboard', 'classiadspro' ),
			'manage_options',
			'pacz-admin-classiads-settings',
			array($this, 'screen_welcome')
		));
		
		if(class_exists('Classiads_Templates')){
			call_user_func_array( 'add' . '_sub' . 'menu_' . 'page', array(
				'pacz-admin-classiads-settings',
				esc_html__( 'Theme Setup', 'classiadspro' ),
				esc_html__( 'Theme Setup', 'classiadspro' ),
				'manage_options',
				'pacz-admin-theme-setup',
				array( $this, 'screen_theme_setup' ),
				11
			));
			/* call_user_func_array( 'add' . '_sub' . 'menu_' . 'page', array(
				'pacz-admin-classiads-settings',
				esc_html__( 'Extensions', 'classiadspro' ),
				esc_html__( 'Extensions', 'classiadspro' ),
				'manage_options',
				'pacz-admin-extensions',
				array( $this, 'screen_plugins' ),
				12
			)); */
		}
		call_user_func_array( 'add' . '_sub' . 'menu_' . 'page', array(
			'pacz-admin-classiads-settings',
			esc_html__( 'Icon Library', 'classiadspro' ),
			esc_html__( 'Icon Library', 'classiadspro' ),
			'manage_options',
			'pacz-admin-icon-library',
			array( $this, 'screen_icon_library' ),
			11
		));
		
		// Tutorials
		call_user_func_array( 'add' . '_sub' . 'menu_' . 'page', array(
			'pacz-admin-classiads-settings',
			esc_html__( 'Tutorials', 'classiadspro' ),
			esc_html__( 'Tutorials', 'classiadspro' ),
			'manage_options',
			'pacz-admin-tutorial',
			array( $this, 'screen_tutorial' ),
			12
		));
	}
	
	public function screen_welcome() {
		echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';
		do_action('pacz_dashboad_panel');
	}
	
	public function screen_plugins() {
		echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';
		require_once( 'templates/plugins.php' );
	}
	public function screen_icon_library() {
		echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';
		include_once( 'templates/icon-library.php' );
	}
	
	public function screen_demo_importer() {
		echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';
		include_once( 'templates/demo-import.php' );
	}
	
	public function screen_tutorial() {
		echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';
		include_once( 'templates/tutorial.php' );
	}
	
	public function registration() {
		require_once( 'templates/registration.php' );
		
	}
	public function screen_theme_setup() {
		require_once( 'templates/theme-setup.php' );
		
	}
	
	
	/* public function screen_performance() {
		// Stupid hack for Wordpress alerts and warnings
		echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';
		include_once( '_partials/performance.php' );
	} */
	
	static function theme( $property = '' ) {

		// Gets a WP_Theme object for a theme
		$theme_data		= wp_get_theme();	
		
		if ( $theme_data->parent_theme ) {
			$theme_data = wp_get_theme( basename( get_template_directory() ) );
		}

		switch ( $property ) :
			case 'name':
				$data = $theme_data->Name;
				break;
			case 'version':
				$data = $theme_data->Version;
				break;
			default:
				$data = '';
				break;
		endswitch;

		return $data;
	}

}
new Pacz_Admin();