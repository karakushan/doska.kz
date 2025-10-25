<?php
/**
 * Plugin Name:  Classiads Templates
 * Description:  Import site demos built with Classiads theme
 * Version:      1.7.4
 * Author:       Designinvento
 * Author URI:   https://designinvento.net
 * License:      GPLv2 or later
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  classiads-templates
 * @package Classiads Templates
 */

/**
 * Set constants.
 */
if ( ! defined( 'DT_TITLE' ) ) {
	define( 'DT_TITLE', __( 'Classiads Templates', 'classiads-templates' ) );
}

if ( ! defined( 'DT_VERSION' ) ) {
	define( 'DT_VERSION', '1.7.4' );
}

if ( ! defined( 'DT_FILE' ) ) {
	define( 'DT_FILE', __FILE__ );
}

if ( ! defined( 'DT_BASE' ) ) {
	define( 'DT_BASE', plugin_basename( DT_FILE ) );
}

if ( ! defined( 'DT_PATH' ) ) {
	define( 'DT_PATH', plugin_dir_path( DT_FILE ) );
}

if ( ! defined( 'DT_URI' ) ) {
	define( 'DT_URI', plugins_url( '/', DT_FILE ) );
}

require_once DT_PATH . 'class-classiads-templates.php';
require_once DT_PATH . 'vendor/autoload.php';

function classiads_templates_activate() {
	wp_redirect(admin_url('admin.php?page=pacz-admin-theme-setup'));
}

function classiads_templates_deactivate() {
	
}

register_activation_hook( __FILE__, 'classiads_templates_activate' );
register_deactivation_hook( __FILE__, 'classiads_templates_deactivate' );
/**
 * Set directory locations, text strings, and settings.
 */
$Classiads_Templates = new Classiads_Templates(

	$config = array(
		'designinvento_templates_url'   => 'classiads-templates', // The wp-admin page slug where Merlin WP loads.
		'parent_slug'          => 'themes.php', // The wp-admin parent page slug for the admin menu item.
		'capability'           => 'manage_options', // The capability required for this menu to be displayed to the user.
		'child_action_btn_url' => 'https://codex.wordpress.org/child_themes', // URL for the 'child-action-link'.
		'dev_mode'             => true, // Enable development mode for testing.
		'license_step'         => false, // EDD license activation step.
		'license_required'     => false, // Require the license activation step.
		'license_help_url'     => '', // URL for the 'license-tooltip'.
		'edd_remote_api_url'   => '', // EDD_Theme_Updater_Admin remote_api_url.
		'edd_item_name'        => '', // EDD_Theme_Updater_Admin item_name.
		'edd_theme_slug'       => '', // EDD_Theme_Updater_Admin item_slug.
		'ready_big_button_url' => '', // Link for the big button on the ready step.
	),

	$strings = array(
		'admin-menu'               => esc_html__( 'Classiads Templates', 'classiads-templates' ),

		/* translators: 1: Title Tag 2: Theme Name 3: Closing Title Tag */
		'title%s%s%s%s'            => esc_html__( '%1$s%2$s Themes &lsaquo; Theme Setup: %3$s%4$s', 'classiads-templates' ),
		'return-to-dashboard'      => esc_html__( 'Return to the dashboard', 'classiads-templates' ),
		'ignore'                   => esc_html__( 'Disable this wizard', 'classiads-templates' ),

		'btn-skip'                 => esc_html__( 'Skip', 'classiads-templates' ),
		'btn-next'                 => esc_html__( 'Next', 'classiads-templates' ),
		'btn-start'                => esc_html__( 'Start', 'classiads-templates' ),
		'btn-no'                   => esc_html__( 'Cancel', 'classiads-templates' ),
		'btn-plugins-install'      => esc_html__( 'Install', 'classiads-templates' ),
		'btn-child-install'        => esc_html__( 'Install', 'classiads-templates' ),
		'btn-content-install'      => esc_html__( 'Install', 'classiads-templates' ),
		'btn-import'               => esc_html__( 'Start Import', 'classiads-templates' ),
		'btn-license-activate'     => esc_html__( 'Activate', 'classiads-templates' ),
		'btn-license-skip'         => esc_html__( 'Later', 'classiads-templates' ),

		/* translators: Theme Name */
		'license-header%s'         => esc_html__( 'Activate %s', 'classiads-templates' ),
		/* translators: Theme Name */
		'license-header-success%s' => esc_html__( '%s is Activated', 'classiads-templates' ),
		/* translators: Theme Name */
		'license%s'                => esc_html__( 'Enter your license key to enable remote updates and theme support.', 'classiads-templates' ),
		'license-label'            => esc_html__( 'License key', 'classiads-templates' ),
		'license-success%s'        => esc_html__( 'The theme is already registered, so you can go to the next step!', 'classiads-templates' ),
		'license-json-success%s'   => esc_html__( 'Your theme is activated! Remote updates and theme support are enabled.', 'classiads-templates' ),
		'license-tooltip'          => esc_html__( 'Need help?', 'classiads-templates' ),

		/* translators: Theme Name */
		'welcome-header%s'         => esc_html__( 'Welcome to %s', 'classiads-templates' ),
		'welcome-header-success%s' => esc_html__( 'Hi. Welcome back', 'classiads-templates' ),
		'welcome%s'                => esc_html__( 'This wizard will set up your theme, install plugins, and import content. It is optional & should take only a few minutes.', 'classiads-templates' ),
		'welcome-success%s'        => esc_html__( 'You may have already run this theme setup wizard. If you would like to proceed anyway, click on the "Start" button below.', 'classiads-templates' ),

		/* translators: Theme Name */
		'builder-header%s'         => esc_html__( 'Page Builder Tool %s', 'classiads-templates' ),
		'builder-header-success%s' => esc_html__( 'You\'re up to speed!', 'classiads-templates' ),
		'builder%s'                => esc_html__( 'Select a page builder tool to build your site.', 'classiads-templates' ),
		'builder-success%s'        => esc_html__( 'You may have already run this theme setup wizard. If you would like to proceed anyway, click on the "Start" button below.', 'classiads-templates' ),
		
		'child-header'             => esc_html__( 'Install Child Theme', 'classiads-templates' ),
		'child-header-success'     => esc_html__( 'You\'re good to go!', 'classiads-templates' ),
		'child'                    => esc_html__( 'Let\'s build & activate a child theme so you may easily make theme changes.', 'classiads-templates' ),
		'child-success%s'          => esc_html__( 'Your child theme has already been installed and is now activated, if it wasn\'t already.', 'classiads-templates' ),
		'child-action-link'        => esc_html__( 'Learn about child themes', 'classiads-templates' ),
		'child-json-success%s'     => esc_html__( 'Awesome. Your child theme has already been installed and is now activated.', 'classiads-templates' ),
		'child-json-already%s'     => esc_html__( 'Awesome. Your child theme has been created and is now activated.', 'classiads-templates' ),

		'plugins-header'           => esc_html__( 'Install Plugins', 'classiads-templates' ),
		'plugins-header-success'   => esc_html__( 'You\'re up to speed!', 'classiads-templates' ),
		'plugins'                  => esc_html__( 'Let\'s install some essential WordPress plugins to get your site up to speed.', 'classiads-templates' ),
		'plugins-success%s'        => esc_html__( 'The required WordPress plugins are all installed and up to date. Press "Next" to continue the setup wizard.', 'classiads-templates' ),
		'plugins-action-link'      => esc_html__( 'Advanced', 'classiads-templates' ),

		'import-header'            => esc_html__( 'Import Starter Site', 'classiads-templates' ),
		'import'                   => esc_html__( 'Let\'s import content to your website, to help you get familiar with the theme.', 'classiads-templates' ),
		'import-action-link'       => esc_html__( 'Advanced', 'classiads-templates' ),

		'ready-header'             => esc_html__( 'All done. Have fun!', 'classiads-templates' ),

		/* translators: Theme Author */
		'ready%s'                  => esc_html__( 'Your theme has been all set up. Enjoy your new theme by Designinvento.', 'classiads-templates' ),
		'ready-action-link'        => esc_html__( 'Extras', 'classiads-templates' ),
		'ready-big-button'         => esc_html__( 'View your website', 'classiads-templates' ),
		'ready-link-1'             => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://designinvento.net/', esc_html__( 'Explore Designinvento', 'classiads-templates' ) ),
		'ready-link-2'             => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://designinvento.net/contact/', esc_html__( 'Get Theme Support', 'classiads-templates' ) ),
		'ready-link-3'             => sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'customize.php' ), esc_html__( 'Start Customizing', 'classiads-templates' ) ),
	)
);
require_once DT_PATH . 'includes/updates.php';
require_once DT_PATH . 'demos/demos.php';