<?php
/**
 * HFB Fallback Theme Support.
 *
 * Add theme compatibility for all the WordPress themes.
 *
 * @since 1.2.0
 * @package hfb
 */

namespace HFB\Themes;

/**
 * Class HFB Theme Fallback support.
 *
 * @since 1.2.0
 */
class HFB_Fallback_Theme_Support {

	/**
	 * Constructor.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		$this->setup_fallback_support();
		//add_action( 'admin_menu', [ $this, 'hfb_register_settings_page' ] );
		add_action( 'admin_init', [ $this, 'hfb_admin_init' ] );
		add_action( 'admin_head', [ $this, 'hfb_global_css' ] );
		add_filter( 'views_edit-hfb-post', [ $this, 'hfb_settings' ], 10, 1 );
	}

	/**
	 * Adds CSS to Hide the extra submenu added for the settings tab.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public function hfb_global_css() {
		wp_enqueue_style( 'el-hfb-admin-style', HEADER_FOOTER_BUILDER_ASSETS_URL . 'admin/css/admin.css', [], HEADER_FOOTER_BUILDER_VERSION );
	}

	/**
	 * Adds a tab in plugin submenu page.
	 *
	 * @since 1.2.0
	 * @param string $views to add tab to current post type view.
	 *
	 * @return mixed
	 */
	public function hfb_settings( $views ) {
		//$this->hfb_tabs();
		return $views;
	}


	/**
	 * Function for registering the settings api.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public function hfb_admin_init() {
		register_setting( 'hfb-plugin-options', 'hfb_compatibility_option' );
		add_settings_section( 'hfb-options', __( 'Theme Support', 'header-footer-builder' ), [ $this, 'hfb_compatibility_callback' ], 'Settings' );
		add_settings_field( 'hfb-way', 'Options', [ $this, 'hfb_compatibility_option_callback' ], 'Settings', 'hfb-options' );
	}

	/**
	 * Call back function for the ssettings api function add_settings_section
	 *
	 * This function can be used to add description of the settings sections
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public function hfb_compatibility_callback() {
		
	}

	/**
	 * Call back function for the ssettings api function add_settings_field
	 *
	 * This function will contain the markup for the input feilds that we can add.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public function hfb_compatibility_option_callback() {
		$hfb_radio_button = get_option( 'hfb_compatibility_option', '1' );
			wp_enqueue_style( 'el-hfb-admin-style', HEADER_FOOTER_BUILDER_ASSETS_URL . 'admin/css/admin.css', [], HEADER_FOOTER_BUILDER_VERSION );
		?>

		<label>
			<input type="radio" name="hfb_compatibility_option" value= 1 <?php checked( $hfb_radio_button, 1 ); ?> > <div class="hfb_radio_options"><?php esc_html_e( 'Replace Theme Header/Footer files (Recommended)', 'header-footer-builder' ); ?></div>
		</label>
		<br>
		<br>
		<label>
			<input type="radio" name="hfb_compatibility_option" value= 2 <?php checked( $hfb_radio_button, 2 ); ?> > <div class="hfb_radio_options"><?php esc_html_e( 'Hide Header/Footer with CSS', 'header-footer-builder' ); ?></div>
		</label>

		<?php
	}

	/**
	 * Setup Theme Support.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public function setup_fallback_support() {
		$hfb_compatibility_option = get_option( 'hfb_compatibility_option', '1' );

		if ( '1' === $hfb_compatibility_option ) {
			require HEADER_FOOTER_BUILDER_PATH . 'includes/theme-support/default/class-hfb-default-compat.php';
		} elseif ( '2' === $hfb_compatibility_option ) {
			require HEADER_FOOTER_BUILDER_PATH . 'includes/theme-support/default/class-global-theme-compatibility.php';
		}
	}

	/**
	 * Show a settings page incase of unsupported theme.
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	/* public function hfb_register_settings_page() {
		add_submenu_page(
			'themes.php',
			__( 'Settings', 'header-footer-builder' ),
			__( 'Settings', 'header-footer-builder' ),
			'manage_options',
			'hfb-settings',
			[ $this, 'hfb_settings_page' ]
		);
	} */

	/**
	 * Settings page.
	 *
	 * Call back function for add submenu page function.
	 *
	 * @since 1.2.0
	 */
	public function hfb_settings_page() {
		echo '<h1 class="hfb-heading-inline">';
		esc_attr_e( 'Elementor - Header, Footer & Blocks ', 'header-footer-builder' );
		echo '</h1>';

		?>
		<h2 class="nav-tab-wrapper">
			<?php
			$tabs       = [
				'hfb_templates' => [
					'name' => __( 'All Templates', 'header-footer-builder' ),
					'url'  => admin_url( 'edit.php?post_type=hfb-post' ),
				],
				'hfb_settings'  => [
					'name' => __( 'Theme Support', 'header-footer-builder' ),
					'url'  => admin_url( 'themes.php?page=hfb-settings' ),
				],
			];
			$active_tab = 'hfb-settings' == isset( $_GET['page'] ) && $_GET['page'] ? 'hfb_settings' : 'hfb_templates';
			foreach ( $tabs as $tab_id => $tab ) {
				$active = $active_tab == $tab_id ? ' nav-tab-active' : '';
				echo '<a href="' . esc_url( $tab['url'] ) . '" class="nav-tab' . $active . '">';
				echo esc_html( $tab['name'] );
				echo '</a>';
			}
			?>
		</h2>
		<br />
		<?php
		$hfb_radio_button = get_option( 'hfb_compatibility_option', '1' );
		?>
		<form action="options.php" method="post">
			<?php settings_fields( 'hfb-plugin-options' ); ?>
			<?php do_settings_sections( 'Settings' ); ?>
			<?php submit_button(); ?>
		</form></div>
		<?php
	}

	/**
	 * Function for adding tabs
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public function hfb_tabs() {
		?>
		<h2 class="nav-tab-wrapper">
			<?php
			$tabs       = [
				'hfb_templates' => [
					'name' => __( 'All templates', 'header-footer-builder' ),
					'url'  => admin_url( 'edit.php?post_type=hfb-post' ),
				],
				'hfb_settings'  => [
					'name' => __( 'Theme Support', 'header-footer-builder' ),
					'url'  => admin_url( 'themes.php?page=hfb-settings' ),
				],
			];
			$active_tab = 'hfb-settings' == isset( $_GET['page'] ) && $_GET['page'] ? 'hfb_settings' : 'hfb_templates';
			foreach ( $tabs as $tab_id => $tab ) {
				$active = $active_tab == $tab_id ? ' nav-tab-active' : '';

				echo '<a href="' . esc_url( $tab['url'] ) . '" class="nav-tab' . esc_attr( $active ) . '">';
				echo esc_html( $tab['name'] );
				echo '</a>';
			}

			?>
		</h2>
		<br />
		<?php
	}

}

new HFB_Fallback_Theme_Support();
