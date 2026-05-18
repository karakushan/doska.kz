<?php
/**
 * Plugin Name: AI Translation For TranslatePress (Pro)
 * Description: Auto language translator add-on for TranslatePress to translate your website into any language using AI & Machine Translation tools—No API Key Needed!.
 * Author: Cool Plugins
 * Author URI: https://example.net/?utm_source=tpa_plugin&utm_medium=inside&utm_campaign=author_page&utm_content=plugins_list
 * Plugin URI:
 * Version: 1.5.9
 * License: GPL2
 * Text Domain:tpap
 * Domain Path: languages
 * Requires Plugins: translatepress-multilingual
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( defined( 'TPAP_VERSION' ) ) {
	return;
}
define( 'TPAP_VERSION', '1.5.9' );
define( 'TPAP_FILE', __FILE__ );
define( 'TPAP_PATH', plugin_dir_path( TPAP_FILE ) );
define( 'TPAP_URL', plugin_dir_url( TPAP_FILE ) );
define('TPAP_FEEDBACK_API',"https://feedback.example.net/");
define('TPAP_PLUGIN_BASE', plugin_basename(TPAP_FILE));
if ( ! class_exists( 'TranslatePress_Addon_Pro' ) ) {
	final class TranslatePress_Addon_Pro {

		/**
		 * Plugin instance.
		 *
		 * @var TranslatePress_Addon_Pro
		 * @access private
		 */
		private static $instance = null;

		/**
		 *  Construct the plugin object
		 */
		private function __construct() {
			register_activation_hook( __FILE__, array( $this, 'tpap_activate' ) );
			register_deactivation_hook( __FILE__, array( $this, 'tpap_deactivate' ) );
			add_filter( 'trp_string_groups', array( $this, 'tpap_string_groups' ) );
			add_action( 'activated_plugin', array( $this, 'tpa_plugin_redirection' ) );
			add_action( 'init', array( $this, 'tpap_load_plugin_text_domain' ) );
			add_action( 'plugins_loaded', array( $this, 'tpap_check_required_plugin' ) );
			if ( ! is_admin() ) {
				add_action( 'trp_translation_manager_footer', array( $this, 'tpap_register_assets' ) );
			}
			add_action( 'wp_ajax_tpap_get_strings', 'tpap_getstrings' );
			add_action( 'wp_ajax_tpap_save_translations', 'tpap_save_translations' );
			add_action('wp_ajax_tpap_update_translate_data', array($this, 'tpap_update_translate_data'));
			add_filter( 'trp_translation_manager_footer', array( $this, 'add_custom_class' ) );

			if ( is_admin() ) {
				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'tpap_settings_page_link' ) );
				add_filter('plugin_row_meta', array( $this,'tpap_add_docs_link_to_plugin_meta'), 10, 2);
				add_action( 'admin_init', array( $this, 'tpap_is_free_version_active' ) );
				add_action( 'init', array( $this, 'tpap_onInit' ) );
				add_action( 'after_plugin_row_' . TPAP_PLUGIN_BASE, array( $this, 'tpap_plugin_custom_notice' ), 10, 2);
				add_filter( 'site_transient_update_plugins', array( $this, 'tpap_hide_plugin_update_notice' ) );
			}

			// Initialize cron
			$this->init_cron();

			// Initialize feedback notice
			$this->init_feedback_notice();

					// Add the action to hide unrelated notices
		if(isset($_GET['page']) && sanitize_key($_GET['page']) == 'translatepress-tpap-dashboard'){
			add_action('admin_print_scripts', array($this, 'tpa_hide_unrelated_notices'));
		}

			add_action( 'init', array( $this, 'tpap_set_gtranslate_cookie' ) );
			$this->tpap_required_files();
			if(!class_exists('TPAP_Dashboard')) {
				require_once TPAP_PATH . 'admin/cpt_dashboard/cpt_dashboard.php';
				new Tpap_Dashboard();
			}

		}
		/**
		 * create 'settings' link in plugins page
		 */
		public function tpap_settings_page_link( $links ) {
			$links[] = '<a style="font-weight:bold" href="' . esc_url( get_admin_url( null, 'options-general.php?page=translatepress-tpap-dashboard' ) ) . '">Settings</a>';
			return $links;
		}

		public function tpap_add_docs_link_to_plugin_meta($links, $file) {
			if (plugin_basename(TPAP_FILE) === $file) {
				$docs_link = '<a href="https://docs.example.net/plugin/ai-translation-for-translatepress/?utm_source=tpa_plugin&utm_medium=inside&utm_campaign=docs&utm_content=plugins_list" target="_blank">Docs</a>';
				$links[] = $docs_link;
			}
			return $links;
		}
		
		public function tpap_onInit() {
			$key = get_option( 'TranslatepressAutomaticTranslateAddonPro_lic_Key' );
			if ( empty( $key ) ) {
				add_action( 'tpap_display_admin_notices', array( $this, 'tpap_add_license_notice' ) );
			}
		}
		public function tpap_add_license_notice() {
			echo '<div class="notice notice-warning is-dismissible">
				<p>' . wp_kses_post( __( 'Please <a href="admin.php?page=translatepress-tpap-dashboard&tab=license">enter your license key</a> to enable automatic updates and premium support for AI Translation For TranslatePress (Pro).', 'tpap' ) ) . '</p>
			</div>';
		}

		/**
		 * Display custom update notices for the AI Translation For TranslatePress (Pro) plugin in the WordPress plugins list table.
		 *
		 * This function checks the plugin's license status and update availability, and then conditionally
		 * displays messages in the plugin list:
		 *
		 * - If an update is available but the license key is not entered, it shows a warning to activate the license.
		 * - If the license or support period has expired, it displays a renewal message with appropriate links.
		 * - If the license is valid, no custom notice is shown.
		 *
		 * Hooked into the 'after_plugin_row' action to insert notices below the plugin row.
		 *
		 * @param string $plugin_file The plugin file path relative to the plugins directory.
		 * @param array  $plugin_data An array of plugin header data.
		*/

		public function tpap_plugin_custom_notice( $plugin_file, $plugin_data ) {
					
					// Get license info and update info for the plugin
		$license_info = AutomaticTranslateAddonForTranslatePressBase::GetRegisterInfo();
		$update_info = AutomaticTranslateAddonForTranslatePressBase::getInstance()->el_plugin_update_info();

		$version_available_message = TranslatePress_Addon_Pro::tpapGetVersionAvailableMessage();

		$plugin_basename = plugin_basename(TPAP_FILE);

		if ( ! \TranslatePressAutoTranslateAddon\Register\TranslatepressAutomaticTranslateAddonPro::$form_status &&  $plugin_basename === TPAP_PLUGIN_BASE ) {
				
				$renew_link = wp_kses_post(
					__( $version_available_message.' Please <a href="admin.php?page=translatepress-tpap-dashboard&tab=license">enter your license key</a> to enable automatic updates and premium support for AI Translation For TranslatePress (Pro)', 'tpap' ),
					array(
						'a' => array(
							'href' => array()
						)
					)
				);

			} else {

				if ( empty( $update_info->download_link ) ) {
					
					$is_expired = (empty($update_info->is_downloadable) ) ? 'license' : 'support';
					
					$message = ' Your ' . $is_expired . ' has expired,';
					$renew_text = ( ! empty( $license_info->market ) && $license_info->market === 'E' ) 
						? 'Please renew your ' . $is_expired . ' to continue receiving automatic updates and priority support.'
						: 'Please <a href="https://my.example.net/account/subscriptions/" target="_blank" rel="noopener noreferrer">Renew now</a> to continue receiving automatic updates and priority support.';
					
					$renew_link = $version_available_message . $message . ' ' . $renew_text;
				}
			}

			if ( ! empty( $renew_link )) {
					?>
					<tr class="plugin-update-tr active tpap-pro">
						<td colspan="4" class="plugin-update colspanchange">
							<div class="update-message notice inline notice-warning notice-alt"> 
								<p><?php echo wp_kses_post( $renew_link ); ?></p>
							</div>
						</td>
					</tr>
					<?php
			}
		}

		
		/**
		 * Hide WordPress core plugin update notice when license is not valid
		 * 
		 * @param object $transient The site transient for plugin updates
		 * @return object Modified transient
		 */
		public function tpap_hide_plugin_update_notice( $transient ) {

			$update_info = AutomaticTranslateAddonForTranslatePressBase::getInstance()->el_plugin_update_info();
			$license_info = AutomaticTranslateAddonForTranslatePressBase::GetRegisterInfo();

			if ( empty($update_info->download_link) || $license_info == null){
					if ( isset( $transient->response ) && isset( $transient->response[TPAP_PLUGIN_BASE] ) ) {
						unset( $transient->response[TPAP_PLUGIN_BASE] );
					}
				}
			
			return $transient;
		}

		public function tpap_update_translate_data() {
					// Check user capabilities first - restrict to administrators only
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'Insufficient permissions to update translation data. Administrator access required.', 'tpap' ) );
			wp_die( '0', 403 );
			exit();
		}

			if ( ! isset( $_POST['_ajax_nonce'] ) || ! wp_verify_nonce( $_POST['_ajax_nonce'], 'auto-translate-press-pro-nonces' ) ) {
				wp_send_json_error( __( 'Invalid security token sent.', 'automatic-translations-for-polylang' ) );
				wp_die( '0', 400 );
				exit();
			}
			// Validate and decode the JSON data
			$raw_data = isset($_POST['data']) ? wp_unslash($_POST['data']) : '';
			$data = json_decode($raw_data, true);
			
			$provider = isset($data['provider']) ? sanitize_text_field($data['provider']) : '';
			$total_word_count = isset($data['totalWordCount']) ? absint($data['totalWordCount']) : 0;
			$total_char_count = isset($data['totalCharacterCount']) ? absint($data['totalCharacterCount']) : 0;
			$date = isset($data['date']) ? date('Y-m-d H:i:s', strtotime(sanitize_text_field($data['date']))) : '';
			$source_lang = isset($data['default_lang']) ? sanitize_text_field($data['default_lang']) : '';
			$target_lang = isset($data['language_code']) ? sanitize_text_field($data['language_code']) : '';
			$time_taken = isset($data['timeTaken']) ? absint($data['timeTaken']) : 0;
			$post_id = isset($data['post_id']) ? absint($data['post_id']) : 0;
			if (class_exists('Tpap_Dashboard')) {
				$translation_data = array(
					'post_id' => $post_id,
					'service_provider' => $provider,
					'source_language' => $source_lang,
					'target_language' => $target_lang,
					'time_taken' => $time_taken,
					'string_count' => $total_word_count,
					'character_count' => $total_char_count,
					'date_time' => $date,
					'version_type' => 'pro'
				);

				Tpap_Dashboard::store_options(
					'tpa',
					'post_id', 
					'update',
					$translation_data
				);

							wp_send_json_success( array(
				'message' => esc_html__('Translation data updated successfully', 'tpap')
			) );
		} else {
			wp_send_json_error( array(
				'message' => esc_html__('Tpap_Dashboard class not found', 'tpap') 
			) );
		}
			exit;
		}

		/**
		 * Get plugin instance.
		 *
		 * @return TranslatePress_Addon_Pro
		 * @static
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
		public function tpap_is_free_version_active() {
			if ( is_plugin_active( 'automatic-translate-addon-for-translatepress/automatic-translate-addon-for-translatepress.php' ) ) {
				deactivate_plugins( 'automatic-translate-addon-for-translatepress/automatic-translate-addon-for-translatepress.php' );

								add_action(
					'admin_notices',
					function () {?>
		<style>div#message.updated {
			display: none;
		}</style>
			<div class="notice notice-error is-dismissible">
				<p>
					  <?php
						echo wp_kses_post( __( 'AI Translation For TranslatePress : AI Translation For TranslatePress is <strong>deactivated</strong> as you have already activated the pro version.', 'tpap' ) );
						?>
				</p>
			</div>

					  <?php
				}
			);
			}

			// Review Notice
			$already_rated     = get_option( 'tpa-ratingDiv' ) != false ? get_option( 'tpa-ratingDiv' ) : 'no';
			if(class_exists('Tpap_Dashboard') && ($already_rated === 'no')) {
				Tpap_Dashboard::review_notice(
					'tpa', // Required
					'AI Translation For TranslatePress (Pro)', // Required
					'https://wordpress.org/support/plugin/automatic-translate-addon-for-translatepress/reviews/#new-post', // Required
				);
			}
		}

		public function tpa_plugin_redirection($plugin) {
			if (plugin_basename(__FILE__) === $plugin) {
				wp_redirect(admin_url('options-general.php?page=translatepress-tpap-dashboard'));
				exit;
			}
		}

		public function tpa_hide_unrelated_notices()
		{ // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded, Generic.Metrics.NestingLevel.MaxExceeded
			$cfkef_pages = false;

					if(isset($_GET['page']) && sanitize_key($_GET['page']) == 'translatepress-tpap-dashboard'){
			$cfkef_pages = true;
		}

			if ($cfkef_pages) {
				global $wp_filter;
				// Define rules to remove callbacks.
				$rules = [
					'user_admin_notices' => [], // remove all callbacks.
					'admin_notices'      => [],
					'all_admin_notices'  => [],
					'admin_footer'       => [
						'render_delayed_admin_notices', // remove this particular callback.
					],
				];
				$notice_types = array_keys($rules);
				foreach ($notice_types as $notice_type) {
					if (empty($wp_filter[$notice_type]->callbacks) || ! is_array($wp_filter[$notice_type]->callbacks)) {
						continue;
					}
					$remove_all_filters = empty($rules[$notice_type]);
					foreach ($wp_filter[$notice_type]->callbacks as $priority => $hooks) {
						foreach ($hooks as $name => $arr) {
							if (is_object($arr['function']) && is_callable($arr['function'])) {
								if ($remove_all_filters) {
									unset($wp_filter[$notice_type]->callbacks[$priority][$name]);
								}
								continue;
							}
							$class = ! empty($arr['function'][0]) && is_object($arr['function'][0]) ? strtolower(get_class($arr['function'][0])) : '';
							// Remove all callbacks except WPForms notices.
							if ($remove_all_filters && strpos($class, 'wpforms') === false) {
								unset($wp_filter[$notice_type]->callbacks[$priority][$name]);
								continue;
							}
							$cb = is_array($arr['function']) ? $arr['function'][1] : $arr['function'];
							// Remove a specific callback.
							if (! $remove_all_filters) {
								if (in_array($cb, $rules[$notice_type], true)) {
									unset($wp_filter[$notice_type]->callbacks[$priority][$name]);
								}
								continue;
							}
						}
					}
				}
			}

			add_action( 'admin_notices', [ $this, 'tpa_admin_notices' ], PHP_INT_MAX );
		}

		function tpa_admin_notices() {
			do_action( 'tpap_display_admin_notices' );
		}

		public function tpap_load_plugin_text_domain(){
			load_plugin_textdomain( 'tpap', false, basename( dirname( TPAP_FILE ) ) . '/languages/' );

			if(!get_option('tpap-install-date')) {
				add_option('tpap-install-date', gmdate('Y-m-d h:i:s'));
			}

			if (!get_option( 'tpap_initial_save_version' ) ) {
                add_option( 'tpap_initial_save_version', TPAP_VERSION );
            }
		}
		
		public function tpap_required_files() {
			 require_once TPAP_PATH . 'includes/tpap-functions.php';
		}

		public function add_custom_class( $classes ) {
			return "$classes notranslate";
		}

		/**
		 * Initialize the cron job for the plugin.
		 */
		public function init_cron(){
			require_once TPAP_PATH . '/admin/cpfm-feedback/cron/tpap-cron.php';
			$cron = new Tpap_cronjob();
			$cron->tpap_cron_init_hooks();
		}

		/**
		 * Initialize the feedback notice for the plugin.
		 */

		 public function init_feedback_notice() {
			if (is_admin()) {

				if(!class_exists('CPFM_Feedback_Notice')){
					require_once TPAP_PATH . '/admin/cpfm-feedback/cpfm-common-notice.php';
					
				}

			add_action('cpfm_register_notice', function () {
				if (!class_exists('CPFM_Feedback_Notice') || !current_user_can('manage_options')) {
					return;
				}
				
				$notice = [
					'title' => esc_html__('AI Translation For TranslatePress', 'tpap'),
					'message' => esc_html__('Help us make this plugin more compatible with your site by sharing non-sensitive site data.', 'tpap'),
					'pages' => ['translatepress-tpap-dashboard'],
					'always_show_on' => ['translatepress-tpap-dashboard'], // This enables auto-show
					'plugin_name'=>'tpa'
				];
				CPFM_Feedback_Notice::cpfm_register_notice('cool_translations', $notice);
					if (!isset($GLOBALS['cool_plugins_feedback'])) {
						$GLOBALS['cool_plugins_feedback'] = [];
					}
					$GLOBALS['cool_plugins_feedback']['cool_translations'][] = $notice;
			});

			add_action('cpfm_after_opt_in_tpa', function($category) {
				// Security: Sanitize category parameter
				$category = sanitize_key($category);
				if ($category === 'cool_translations') {
					Tpap_cronjob::tpap_send_data();
					update_option('tpa_feedback_opt_in', 'yes');	
				}
			  });
		    }
		}
		

		// set settings on plugin activation
		public function tpap_activate() {
			update_option( 'tpap-v', TPAP_VERSION );
			update_option( 'tpap-type', 'PRO' );
			update_option( 'tpap-pro-installDate', date( 'Y-m-d h:i:s' ) );
			update_option( 'tpap-pro-ratingDiv', 'no' );

			if(!get_option('tpap-install-date')) {
				add_option('tpap-install-date', gmdate('Y-m-d h:i:s'));
			}

			if (!get_option( 'tpap_initial_save_version' ) ) {
                add_option( 'tpap_initial_save_version', TPAP_VERSION );
            }

					$get_opt_in = sanitize_text_field(get_option('tpa_feedback_opt_in', ''));

		if ($get_opt_in =='yes' && !wp_next_scheduled('tpap_extra_data_update')) {

			wp_schedule_event(time(), 'every_30_days', 'tpap_extra_data_update');
		}
		}

		public static function tpap_get_user_info() {

			global $wpdb;
		
			// Server and WP environment details
			$server_info = [
				'server_software'        => isset($_SERVER['SERVER_SOFTWARE']) ? sanitize_text_field($_SERVER['SERVER_SOFTWARE']) : 'N/A',
				'mysql_version'          => $wpdb ? sanitize_text_field($wpdb->get_var("SELECT VERSION()")) : 'N/A',
				'php_version'            => sanitize_text_field(phpversion() ?: 'N/A'),
				'wp_version'             => sanitize_text_field(get_bloginfo('version') ?: 'N/A'),
				'wp_debug'               => (defined('WP_DEBUG') && WP_DEBUG) ? 'Enabled' : 'Disabled',
				'wp_memory_limit'        => sanitize_text_field(ini_get('memory_limit') ?: 'N/A'),
				'wp_max_upload_size'     => sanitize_text_field(ini_get('upload_max_filesize') ?: 'N/A'),
				'wp_permalink_structure' => sanitize_text_field(get_option('permalink_structure') ?: 'Default'),
				'wp_multisite'           => is_multisite() ? 'Enabled' : 'Disabled',
				'wp_language'            => sanitize_text_field(get_option('WPLANG') ?: get_locale()),
				'wp_prefix'              => isset($wpdb->prefix) ? sanitize_key($wpdb->prefix) : 'N/A',
			];
		
			// Theme details
			$theme = wp_get_theme();
	
			$theme_data = [
				'name'      => sanitize_text_field($theme->get('Name')),
				'version'   => sanitize_text_field($theme->get('Version')),
				'theme_uri' => esc_url($theme->get('ThemeURI')),
			];
		
			// Ensure plugin functions are loaded
			if ( ! function_exists('get_plugins') ) {
	
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
		
			// Active plugins details
			$active_plugins = get_option('active_plugins', []);
			$plugin_data = [];
		
			foreach ( $active_plugins as $plugin_path ) {
	
				$plugin_info = get_plugin_data(WP_PLUGIN_DIR . '/' . sanitize_text_field($plugin_path));
				$author_url = ( isset( $plugin_info['AuthorURI'] ) && !empty( $plugin_info['AuthorURI'] ) ) ? esc_url( $plugin_info['AuthorURI'] ) : 'N/A';
				$plugin_url = ( isset( $plugin_info['PluginURI'] ) && !empty( $plugin_info['PluginURI'] ) ) ? esc_url( $plugin_info['PluginURI'] ) : '';
	
				$plugin_data[] = [
	
					'name'       => sanitize_text_field($plugin_info['Name']),
					'version'    => sanitize_text_field($plugin_info['Version']),
					'plugin_uri' => !empty($plugin_url) ? $plugin_url : $author_url,
				];
			}
		
			return [
				'server_info'   => $server_info,
				'extra_details' => [
					'wp_theme'       => $theme_data,
					'active_plugins' => $plugin_data,
				],
			];
		}

		    /**
     * Get version available message if update is available
     * 
     * @return string Version available message or empty string
     */
    public static function tpapGetVersionAvailableMessage() {
        
        // Get license info and update info for the plugin
        $update_info = AutomaticTranslateAddonForTranslatePressBase::getInstance()->el_plugin_update_info();

        // Initialize and sanitize version information
        $latest_version = isset($update_info->new_version) ? sanitize_text_field($update_info->new_version) : '';
        $version_available_message = '';

        // Prepare update available message if current version is outdated
        $plugin_basename = plugin_basename(TPAP_FILE);
        if ( ! empty($latest_version) && version_compare(TPAP_VERSION, $latest_version, '<') ) {

            list($plugin_slug, $plugin_file) = explode('/', $plugin_basename);

            $plugin_info_url = add_query_arg([
                'tab'       => 'plugin-information',
                'plugin'    => $plugin_slug . '/' . $plugin_file,
                'section'   => 'changelog',
                'TB_iframe' => 'true',
                'width'     => 772,
                'height'    => 390,
            ], admin_url('plugin-install.php'));

            $version_available_message = sprintf(
                /* translators: %s: version number with link */
                __('Version %s is available.', 'tpap'),
                sprintf(
                    '<a href="%s" class="thickbox open-plugin-details-modal" aria-label="%s">%s</a>',
                    esc_url($plugin_info_url),
                    esc_attr(sprintf(__('View changelog for version %s', 'tpap'), $latest_version)),
                    esc_html(sprintf(__('%s (View details)', 'tpap'), $latest_version))
                )
            );
        }
        
        return $version_available_message;
    }

		/**
		 * Deactivate the plugin
		 */

		public function tpap_deactivate() {
			wp_clear_scheduled_hook('tpap_extra_data_update');
		}

		/**
		 * Change string groups
		 */
		public function tpap_string_groups() {
			$string_groups = array(
				'slugs'           => 'Slugs',
				'metainformation' => 'Meta Information',
				'stringlist'      => 'String List',
				'gettextstrings'  => 'Gettext Strings',
				'images'          => 'Images',
				'dynamicstrings'  => 'Dynamically Added Strings',
			);
			return $string_groups;
		}
		/*
		|----------------------------------------------------------------------
		| check if required "TranslatePress - Multilingual" plugin is active
		| also register the plugin text domain
		|----------------------------------------------------------------------
		 */
		public function tpap_check_required_plugin() {

			if ( is_admin() ) {
				// require_once TPAP_PATH . 'admin/Registration/AutomaticTranslateAddonForTranslatePressBase.php';
				// new AutomaticTranslateAddonForTranslatePressBase( TPAP_FILE );
				include_once TPAP_PATH . 'admin/Registration/TranslatepressAutomaticTranslateAddonPro.php';
			}
		}

		/**
		 *  Register Assets
		 * Hooked to trp_translation_manager_footer.
		 */
		public function tpap_register_assets() {
			wp_register_script( 'tpap-chrome-ai-translation', TPAP_URL . 'assets/js/chrome-ai-translation.js', TPAP_VERSION );
			wp_register_script( 'tpap-custom-script', TPAP_URL . 'assets/js/tpap-custom-script.js', array( 'jquery', 'jquery-ui-dialog','tpap-chrome-ai-translation' ), TPAP_VERSION );

			wp_register_script( 'tpap-yandex-widget', TPAP_URL . 'assets/js/yandex-widget.js?widgetId=ytWidget&pageLang=en&widgetTheme=light&autoMode=false', array('tpap-custom-script'), TPAP_VERSION, true );
			wp_register_style( 'tpap-editor-styles', TPAP_URL . 'assets/css/tpap-custom.css', null, TPAP_VERSION, 'all' );
			$key                      = ! empty( get_option( 'TranslatepressAutomaticTranslateAddonPro_lic_Key' ) ) ? get_option( 'TranslatepressAutomaticTranslateAddonPro_lic_Key' ) : 'No';
			$extraData = array(
				'gt_preview'  => TPAP_URL . '/assets/images/google.png',
				'yt_preview'  => TPAP_URL . '/assets/images/yandex.png',
				'ct_preview'  => TPAP_URL . '/assets/images/chrome.png',
				'error_preview' => TPAP_URL . '/assets/images/error-icon.svg',
				'document_preview'  => TPAP_URL . '/assets/images/document.svg',
				'dashboard_url'    => admin_url('admin.php?page=translatepress-tpap-dashboard'),
				'extra_class'       => is_rtl() ? 'tpap-rtl' : '',
				'ajax_url'    => admin_url( 'admin-ajax.php' ),
				'nonce'       => wp_create_nonce( 'auto-translate-press-pro-nonces' ),
				'plugin_url'  => plugins_url(),
				'license_key' => $key,
				'post_id'     => get_the_ID(),
			);
			$this->tpap_load_gtranslate_scripts();
			wp_enqueue_script( 'tpap-chrome-ai-translation' );
			wp_enqueue_script( 'tpap-custom-script' );
			wp_localize_script( 'tpap-custom-script', 'extradata', $extraData );
			wp_enqueue_script( 'tpap-yandex-widget' );
			wp_print_styles( 'tpap-editor-styles' );
		}

		/*
		|----------------------------------------------------------------------
		| Google Translate Widget integrations
		| load Google Translate widget scripts
		|----------------------------------------------------------------------
		*/
		public function tpap_load_gtranslate_scripts() {

			echo "<script>
           function googleTranslateElementInit() {
               var locale=locoConf.conf.locale;
               var defaultcode = locale.lang?locale.lang:null;
               switch(defaultcode){
                   case 'bel':
                   defaultlang='be';
                   break;
                   case 'he':
                       defaultlang='iw';
                       break;
                   case'snd':
                       defaultlang='sd';
                   break;
                   case 'jv':
                       defaultlang='jw';
                       break;
                       case 'nb':
                           defaultlang='no';
                           break;
             
                           case 'nn':
                             defaultlang='no';
                             break;
                   default:
                   defaultlang=defaultcode;
               break;
               return defaultlang;
               }
              if(defaultlang=='zh'){
              new google.translate.TranslateElement(
                   {
                   pageLanguage: 'en',
                   includedLanguages: 'zh-CN,zh-TW',
                   defaultLanguage: 'zh-CN,zh-TW',
                   multilanguagePage: true
                   },
                   'google_translate_element'
               );
           }
           else{
               new google.translate.TranslateElement(
                   {
                   pageLanguage: 'en',
                   includedLanguages: defaultlang,
                   defaultLanguage: defaultlang,
                   multilanguagePage: true
                   },
                   'google_translate_element'
               );
           }
           }
           </script>
           <script src='https://translate.google.com/translate_a/element.js'></script>
           ";
		}

		// set default option in google translate widget using cookie
		public function tpap_set_gtranslate_cookie() {
			// setting your cookies there
			if ( ! isset( $_COOKIE['googtrans'] ) ) {
				setcookie( 'googtrans', '/en/Select Language', 2147483647 );
			}
		}
		/**
		 * TranslatePress_Addon_Pro Class Close
		 */
	}
}

$translatepress_pro_addon = TranslatePress_Addon_Pro::get_instance();
