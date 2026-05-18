<?php
namespace TranslatePressAutoTranslateAddon\Register;

require_once TPAP_PATH . '/admin/Registration/AutomaticTranslateAddonForTranslatePressBase.php';
if(!class_exists("TranslatepressAutomaticTranslateAddonPro")) {
class TranslatepressAutomaticTranslateAddonPro {
	public $responseObj;
	public $licenseMessage;
	public $showMessage = false;
	public $slug        = 'translatepress-tpap-dashboard';
	private const OPTION_LICENSE_KEY = 'TranslatepressAutomaticTranslateAddonPro_lic_Key';
	private const OPTION_LICENSE_EMAIL = 'TranslatepressAutomaticTranslateAddonPro_lic_email';
	public static $form_status = false;

	function __construct() {
		$this->tpap_register_hooks();
		$this->tpap_initialize_license();
		// Add error notice hook
		add_action('tpap_display_admin_notices', array($this, 'tpap_display_license_error_messages'));
	}

	private function tpap_register_hooks() {
		add_action( 'admin_enqueue_scripts', [ $this, 'tpap_set_admin_style' ] );
		add_action( 'admin_menu', array( $this, 'tpap_add_sub_menu' ), 101 );
		add_action('admin_post_tpap_activate_license', [$this, 'tpap_handle_license_activation']);
		add_action('admin_post_tpap_deactivate_license', [$this, 'tpap_handle_license_deactivation']);
		add_action('wp_ajax_tpap_refresh_license_ajax', [$this, 'tpap_handle_refresh_license_ajax']);
	}

	private function tpap_initialize_license() {
		$licenseKey = get_option(self::OPTION_LICENSE_KEY, "");
		$liceEmail = get_option(self::OPTION_LICENSE_EMAIL, get_bloginfo('admin_email'));
		
		\AutomaticTranslateAddonForTranslatePressBase::add_on_delete(function(){
		   delete_option(self::OPTION_LICENSE_KEY);
		   delete_option(self::OPTION_LICENSE_EMAIL);
		});

		if (\AutomaticTranslateAddonForTranslatePressBase::CheckWPPlugin($licenseKey, $liceEmail, $this->licenseMessage, $this->responseObj, TPAP_FILE)) {
			self::$form_status = true;
		} else {
			self::$form_status = false;
			if(!empty($licenseKey) && !empty($this->licenseMessage)) {
				$this->showMessage = true;
			}
		}
	}

	function tpap_set_admin_style() {
		
		wp_enqueue_script(
			'tpap-plugin-setting',
			esc_url(TPAP_URL . 'admin/tpa-dashboard/js/tpap-plugin-setting.js'),
			array('jquery'),
			TPAP_VERSION,
			true
		);

		if (isset($_GET['page']) && $_GET['page'] === 'translatepress-tpap-dashboard') {
			wp_enqueue_style( 'tpap-editor-styles', TPAP_URL . 'admin/tpa-dashboard/css/admin-styles.css', null, TPAP_VERSION, 'all' );
			wp_enqueue_script( 'tpap-data-share-setting', TPAP_URL . 'admin/tpa-dashboard/js/tpap-data-share-setting.js', array('jquery'), TPAP_VERSION, true );
		}

        wp_localize_script( 'tpap-data-share-setting', 'tpap_ajax', array(
            'nonce' => wp_create_nonce('tpap_refresh_license_nonce'),
            'ajaxurl' => admin_url('admin-ajax.php')
        ) );
	}

	/*
	|-------------------------------------------------------
	|    Automatic Translate Addon For TranslatePress  admin page
	|-------------------------------------------------------
	*/
	function tpap_add_sub_menu() {
		add_options_page(
			__( 'AI Translation [TranslatePress]', 'tpap' ),
			__( 'AI Translation [TranslatePress]', 'tpap' ),
			'manage_options',
			$this->slug,
			array(
				$this,
				'tpap_dashboard_page',
			)
		);
	}

	/**
	 * Render the dashboard page with dynamic text domain support
	 * 
	 * @param string $text_domain The text domain for translations (default: 'tpap')
	 */
		function tpap_dashboard_page() {
			$text_domain = 'tpap';
			$file_prefix = 'admin/tpa-dashboard/views/';
			
			$valid_tabs = $this->tpap_get_valid_tabs($text_domain);
			$buttons = $this->tpap_get_action_buttons($text_domain);

			// Get current tab with fallback
			$tab 			= isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'dashboard';
			$current_tab 	= array_key_exists($tab, $valid_tabs) ? $tab : 'dashboard';
			
			// Start HTML output
			?>
			<div class="tpap-dashboard-wrapper">
				<div class="tpap-dashboard-header">
					<div class="tpap-dashboard-header-left">
						<img src="<?php echo esc_url(TPAP_URL . 'admin/tpa-dashboard/images/translatepress-addon.svg'); ?>" 
							alt="<?php esc_attr_e('TranslatePress Addon', $text_domain); ?>">
						<div class="tpap-dashboard-tab-title">
							<span>↳</span> <?php echo esc_html($valid_tabs[$current_tab]); ?>
						</div>
					</div>
					<div class="tpap-dashboard-header-right">
						<span><?php esc_html_e('Auto translate pages and posts.', $text_domain); ?></span>
						<?php foreach ($buttons as $button): ?>
							<a href="<?php echo esc_url($button['url']); ?>" 
							class="tpap-dashboard-btn" 
							target="_blank"
							aria-label="<?php echo isset($button['alt']) ? esc_attr($button['alt']) : ''; ?>">
								<?php if (isset($button['img'])): ?>
									<img src="<?php echo esc_url(TPAP_URL . 'admin/tpa-dashboard/images/' . $button['img']); ?>" 
										alt="<?php echo esc_attr($button['alt']); ?>">
								<?php endif; ?>
								<?php if (isset($button['text'])): ?>
									<span><?php echo esc_html($button['text']); ?></span>
								<?php endif; ?>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
				
				<nav class="nav-tab-wrapper" aria-label="<?php esc_attr_e('Dashboard navigation', $text_domain); ?>">
					<?php foreach ($valid_tabs as $tab_key => $tab_title): ?>
						<a href="?page=translatepress-tpap-dashboard&tab=<?php echo esc_attr($tab_key); ?>" 
						class="nav-tab <?php echo esc_attr($tab === $tab_key ? 'nav-tab-active' : ''); ?>">
							<?php echo esc_html($tab_title); ?>
						</a>
					<?php endforeach; ?>
				</nav>
				
				<div class="tab-content">
					<?php
					require_once TPAP_PATH . $file_prefix . $tab . '.php';
					if($tab === 'license'){
						$licenseKey = get_option(self::OPTION_LICENSE_KEY,"");
						$liceEmail = get_option( self::OPTION_LICENSE_EMAIL,"");
						\AutomaticTranslateAddonForTranslatePressBase::add_on_delete(function(){
						   delete_option(self::OPTION_LICENSE_KEY);
						});
						if(\AutomaticTranslateAddonForTranslatePressBase::CheckWPPlugin($licenseKey,$liceEmail,$this->licenseMessage,$this->responseObj,__FILE__)){
							tpap_render_license_page_pro($this->responseObj);
						}else{
							tpap_render_license_page();
						}
					}
					require_once TPAP_PATH . $file_prefix . 'sidebar.php';
					
					?>
				</div>
				<?php require_once TPAP_PATH . $file_prefix . 'footer.php'; ?>
			</div>
			<?php
		}

	private function tpap_get_valid_tabs($text_domain) {
		return [
			'dashboard'       => __('Dashboard', $text_domain),
			'ai-translations' => __('AI Translations', $text_domain),
			'settings'        => __('Settings', $text_domain),
			'license'         => __('License', $text_domain)
		];
	}

	private function tpap_get_action_buttons($text_domain) {
		return [
			[
				'url'  => 'https://example.net/products/?utm_source=tpa_plugin&utm_medium=inside&utm_campaign=view_plugin&utm_content=dashboard_header',
				'alt'  => __('Explore Cool Plugins', $text_domain),
				'img'  => 'upgrade-now.svg',
				'text' => __('Explore Cool Plugins', $text_domain)
			],
			[
				'url' => 'https://docs.example.net/docs/automatic-translate-addon-for-translatepress-pro/?utm_source=tpa_plugin&utm_medium=inside&utm_campaign=docs&utm_content=dashboard_header',
				'img' => 'document.svg',
				'alt' => __('document', $text_domain)
			],
			[
				'url' => 'https://example.net/support/?utm_source=tpa_plugin&utm_medium=inside&utm_campaign=support&utm_content=dashboard_header',
				'img' => 'contact.svg',
				'alt' => __('contact', $text_domain)
			]
		];
	}

	function tpap_handle_license_activation() {
		$this->tpap_check_user_capabilities();
		check_admin_referer('tpap-license');
		
		// Validate license key
		$license_key = !empty($_POST['license_code']) ? sanitize_text_field(wp_unslash($_POST['license_code'])) : '';
		if (empty($license_key)) {
			$this->tpap_redirect_with_error('missing_key');
			return;
		}
		
		// Validate email
		$license_email = !empty($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
		if (empty($license_email)) {
			$this->tpap_redirect_with_error('missing_email');
			return;
		}

		// Validate email format
		if (!is_email($license_email)) {
			$this->tpap_redirect_with_error('invalid_email');
			return;
		}
		
		if (!$this->tpap_is_valid_license_key($license_key)) {
			$this->tpap_redirect_with_error('invalid_format');
			return;
		}

		$error = '';
		$responseObj = null;
		
		if ($this->tpap_activate_license($license_key, $license_email, $error, $responseObj)) {
			update_option(self::OPTION_LICENSE_KEY, $license_key);
			update_option(self::OPTION_LICENSE_EMAIL, $license_email);
			delete_site_transient('update_plugins');
			$this->tpap_redirect_with_success();
		}
		
		// Map error message to error code
		$error_code = $this->tpap_map_error_to_code($error);
		$this->tpap_redirect_with_error($error_code);
	}

	private function tpap_check_user_capabilities() {
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.', 'tpap'));
		}
	}

	private function tpap_activate_license($licenseKey, $licenseEmail, &$error, &$responseObj) {
		if (\AutomaticTranslateAddonForTranslatePressBase::CheckWPPlugin($licenseKey, $licenseEmail, $error, $responseObj, __FILE__)) {
			update_option(self::OPTION_LICENSE_KEY, $licenseKey);
			update_option(self::OPTION_LICENSE_EMAIL, $licenseEmail);
			delete_site_transient('update_plugins');
			return true;
		}
		return false;
	}

	private function tpap_redirect_with_success() {
		wp_redirect(admin_url('admin.php?page=translatepress-tpap-dashboard&tab=license&activated=true'));
		exit;
	}

	private function tpap_redirect_with_error($error) {
		wp_redirect(admin_url('admin.php?page=translatepress-tpap-dashboard&tab=license&error=' . urlencode($error)));
		exit;
	}

	function tpap_handle_license_deactivation() {
		try {
			// Check user capabilities
			if (!current_user_can('manage_options')) {
				wp_die(__('You do not have sufficient permissions to access this page.', 'tpap'));
			}
			
			// Verify nonce
			check_admin_referer('tpap-license');
			
			// Get current license info
			$licenseKey = get_option(self::OPTION_LICENSE_KEY, '');
			
			// Match the base class name and file constant with the license.php file
			$message = '';
			if (\AutomaticTranslateAddonForTranslatePressBase::RemoveLicenseKey(__FILE__, $message)) {
				// Success - clean up using same option names as license.php
				delete_option('TranslatepressAutomaticTranslateAddonPro_lic_Key');
				delete_option('TranslatepressAutomaticTranslateAddonPro_lic_email');
				delete_site_transient('update_plugins');
				
				wp_safe_redirect(admin_url('admin.php?page=translatepress-tpap-dashboard&tab=license&deactivated=true'));
				exit;
			}
			
			wp_safe_redirect(admin_url('admin.php?page=translatepress-tpap-dashboard&tab=license&error=' . urlencode($message)));
			exit;
			
		} catch (\Exception $e) {
			wp_safe_redirect(admin_url('admin.php?page=translatepress-tpap-dashboard&tab=license&error=unexpected_error'));
			exit;
		}
	}

    
        /**
         * Handle AJAX license refresh request
         */
        function tpap_handle_refresh_license_ajax() {
            // Check nonce for security
            if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'tpap_refresh_license_nonce')) {
                wp_send_json_error(array('message' => 'Security check failed.'));
                return;
            }

            $this->tpap_check_user_capabilities();
            
            // Get existing license key and email
            $license_key    = get_option(self::OPTION_LICENSE_KEY, '');
            $license_email  = get_option(self::OPTION_LICENSE_EMAIL, '');

            if (empty($license_key) || empty($license_email)) {
                wp_send_json_error(array('message' => 'No license information found to refresh.'));
                return;
            }

            $error = '';
            $responseObj = null;

            // Use the same activation logic to refresh the license
            if ($this->tpap_activate_license($license_key, $license_email, $error, $responseObj)) {
                
                // Get updated license information
                $license_info = array(
                    'is_valid' => $responseObj->is_valid ? $responseObj->is_valid:false,
                    'license_title' => $responseObj->license_title ? $responseObj->license_title : '',
                    'expire_date' => $responseObj->expire_date ? $responseObj->expire_date : '',
                    'market' => $responseObj->market ? $responseObj->market : '',
                    'support_end' => $responseObj->support_end ? $responseObj->support_end : '',
                );
                
                
                wp_send_json_success(array(
                    'message' => 'License Status Updated successfully!',
                    'license_info' => $license_info,
                    'version_available_message' => \TranslatePress_Addon_Pro::tpapGetVersionAvailableMessage()
                ));
            } else {
                // Map error message to error code
                $error_code = $this->tpap_map_error_to_code($error);
                $error_message = $this->tpap_get_error_message($error_code);
                wp_send_json_error(array('message' => $error_message));
            }
        }

	private function tpap_is_valid_license_key($licenseKey) {
		// Define the pattern for the license key structure
		$pattern = '/^[0-9A-F][0-9A-F]{7}-[0-9A-F]{8}-[0-9A-F]{8}-[0-9A-F]{8}$/i';
		
		// Check if the license key matches the pattern
		return preg_match($pattern, $licenseKey) ? true : false;
	}

	// Add new method to display error messages
	public function tpap_display_license_error_messages() {
		if (isset($_GET['page']) && $_GET['page'] === 'translatepress-tpap-dashboard' && isset($_GET['tab']) && $_GET['tab'] === 'license') {
			// Display error message if exists
			if (isset($_GET['error'])) {
				$error_message = $this->tpap_get_error_message($_GET['error']);
				?>
				<div class="notice notice-error is-dismissible">
					<p><?php echo esc_html($error_message); ?></p>
				</div>
				<?php
			}

			// Display success message
			if (isset($_GET['activated']) && $_GET['activated'] === 'true') {
				?>
				<div class="notice notice-success is-dismissible">
					<p><?php _e('License activated successfully!', 'tpap'); ?></p>
				</div>
				<?php
			}

			// Display deactivation message
			if (isset($_GET['deactivated']) && $_GET['deactivated'] === 'true') {
				?>
				<div class="notice notice-info is-dismissible">
					<p><?php _e('License deactivated successfully!', 'tpap'); ?></p>
				</div>
				<?php
			}

			// Display license message if exists
			if ($this->showMessage && !empty($this->licenseMessage)) {
				?>
				<div class="notice notice-error is-dismissible">
					<p><?php echo esc_html($this->licenseMessage); ?></p>
				</div>
				<?php
			}
		}
	}

	// Add new method to get error messages
	private function tpap_get_error_message($error_code) {
		$messages = array(
			'missing_key' => __('License key is required.', 'tpap'),
			'missing_email' => __('Email address is required.', 'tpap'),
			'invalid_email' => __('Please enter a valid email address.', 'tpap'),
			'invalid_format' => __('Invalid license key format. Please check your license key.', 'tpap'),
			'invalid_key' => __('Invalid license key. Please check your license key and try again.', 'tpap'),
			'expired' => __('Your license key has expired. Please renew your license.', 'tpap'),
			'disabled' => __('Your license key has been disabled.', 'tpap'),
            'no_activations' => __('License quota has been over, you can not add more domain with this license key.', 'tpap'),
            'refunded' => __('Your purchase key has been refunded.', 'tpap'),
            'wrong_license_status' => __('Your license key is inactive, has been refunded, or has exceeded the allowed domain limit.', 'tpap'),
            'domain_exceeded' => __('Your license key has exceeded the allowed domain limit.', 'tpap'),
			'default' => __('An error occurred while validating your license. Please try again.', 'tpap')
		);

		return isset($messages[$error_code]) ? $messages[$error_code] : $messages['default'];
	}

	// Add new method to map error messages to codes
	private function tpap_map_error_to_code($error) {
		$error = strtolower($error);
		if (strpos($error, 'invalid') !== false) {
			return 'invalid_key';
		} elseif (strpos($error, 'disabled') !== false || strpos($error, 'temporary inactivated') !== false || strpos($error, 'inactive_license') !== false) {
			return 'disabled';
        } elseif (strpos($error, 'license quota has been over') !== false || strpos($error, 'installed on another domain') !== false) {
            return 'no_activations';
        } elseif (strpos($error, 'refunded') !== false || strpos($error, 'refunded_license') !== false) {
            return 'refunded';
        } elseif (strpos($error, 'expired') !== false) {
            return 'expired';
        } elseif (strpos($error, 'wrong_license_status') !== false) {
            return 'wrong_license_status';
        } elseif (strpos($error, 'domain_exceeded') !== false) {
            return 'domain_exceeded';
		}
		return 'default';
	}
}
}

new TranslatepressAutomaticTranslateAddonPro();
