<?php
if ( ! class_exists( 'AutomaticTranslateAddonForTranslatePressBase' ) ) {
	class AutomaticTranslateAddonForTranslatePressBase {
		public $key              = 'NulledByGobyv';
		private $product_id      = '14';
		private $product_base    = 'TPAP';
		private $server_host     = 'https://license.example.net/wp-json/licensor/';
    	private $has_check_update=true;
		private $is_encrypt_update = true;
    	private $plugin_file;
        private static $selfobj=null;
        private $version="";
        private $is_theme=false;
        private $email_address = "";
        private static $_on_delete_license=[];
		function __construct($plugin_base_file='')
		{

				$dir=str_replace('\\','/',dirname($plugin_base_file));
				$this->plugin_file = $plugin_base_file;

			if(strpos($dir,'wp-content/themes')!==FALSE){
				$this->is_theme=true;
			}
            if(empty($this->version)){
	            $this->version=$this->get_current_version();
            }
			
			if($this->has_check_update) {
				if(function_exists("add_action")){
					add_action( 'admin_post_automatic-translate-addon-for-translatepress_fupc', function(){
						update_option('_site_transient_update_plugins','');
						update_option('_site_transient_update_themes','');
						set_site_transient('update_themes', null);
                        delete_transient($this->product_base."_up");
						wp_redirect(  admin_url( 'plugins.php' ) );
						exit;
					});
					add_action( 'init', [$this,"init_action_handler"]);

				}
				if(function_exists("add_filter")) {
					if($this->is_theme){
						add_filter('pre_set_site_transient_update_themes', [$this, "plugin_update"]);
						add_filter('themes_api', [$this, 'check_update_info'], 10, 3);
					}else{
						add_filter('pre_set_site_transient_update_plugins', [$this, "plugin_update"]);
						add_filter('plugins_api', [$this, 'check_update_info'], 10, 3);
						add_filter( 'plugin_row_meta', function($links, $plugin_file ){
							if (  plugin_basename( $this->plugin_file ) == $plugin_file ) {
								$links[] = " <a class='edit coption' href='" . esc_url( admin_url( 'admin-post.php' ) . '?action=automatic-translate-addon-for-translatepress_fupc' ) . "'>Update Check</a>";
							}
							return $links;
						}, 10, 2 );
						add_action( "in_plugin_update_message-".plugin_basename( $this->plugin_file ), [$this,'update_message_cb'], 20, 2 );
					}
				}


			}
		}
		public function set_email_address( $email_address ) {
            $this->email_address = $email_address;
        }
		function init_action_handler(){
			$handler=hash("crc32b",$this->product_id.$this->key.$this->get_domain())."_handle";
			if(isset($_GET['action']) && sanitize_text_field($_GET['action']) == $handler){
				$this->handle_server_request();
				exit;
			}
		}
		function handle_server_request(){
			$type = isset( $_GET['type'] ) ? strtolower(sanitize_text_field(wp_unslash($_GET['type']))) : '';
			switch ($type) {
				case "rl": //remove license
					$this->clean_update_info();
					$this->remove_old_wp_response();
					$obj          = new stdClass();
					$obj->product = $this->product_id;
					$obj->status  = true;
					call_user_func('printf','%s', esc_html($this->encrypt_obj( $obj ) ) );
					return;
				case "rc": //remove license
					$key  = $this->getKeyName();
					delete_option( $key );
					$obj          = new stdClass();
					$obj->product = $this->product_id;
					$obj->status  = true;
					call_user_func('printf','%s', esc_html($this->encrypt_obj( $obj ) ) );
					return;
				case "dl": //delete plugins
					$obj          = new stdClass();
					$obj->product = $this->product_id;
					$obj->status  = false;
					$this->remove_old_wp_response();
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
					if ( $this->is_theme ) {
						$res = delete_theme( $this->plugin_file );
						if ( ! is_wp_error( $res ) ) {
							$obj->status = true;
						}
						call_user_func('printf','%s', esc_html($this->encrypt_obj( $obj ) ) );
					} else {
					    deactivate_plugins( [ plugin_basename( $this->plugin_file ) ] );
						$res = delete_plugins( [ plugin_basename( $this->plugin_file ) ] );
						if ( ! is_wp_error( $res ) ) {
							$obj->status = true;
						}
						call_user_func('printf','%s', esc_html($this->encrypt_obj( $obj ) ) );
					}
					
					return;
				default:
					return;
			}
		}
		 
		/**
         * @param callable $func
         */
        static function add_on_delete( $func){
            self::$_on_delete_license[]=$func;
        }
		function get_current_version(){
			if( !function_exists('get_plugin_data') ){
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}
			$data=get_plugin_data($this->plugin_file,false,false);
			if(isset($data['Version'])){
				return $data['Version'];
			}
			return 0;
		}
		public function clean_update_info(){
            update_option('_site_transient_update_plugins','');
            update_option('_site_transient_update_themes','');
            delete_transient($this->product_base."_up");
        }
        public function update_message_cb($data, $response){
            if(is_array($data)){
                $data=(object)$data;
            }
            if(isset($data->package) && empty($data->package)) {
                if(empty($data->update_denied_type)) {
                    print  "<br/><span style='display: block; border-top: 1px solid #ccc;padding-top: 5px; margin-top: 10px;'>Please <strong>active product</strong> or  <strong>renew support period</strong> to get latest version</span>";
                }elseif("L" == $data->update_denied_type){
                    print  "<br/><span style='display: block; border-top: 1px solid #ccc;padding-top: 5px; margin-top: 10px;'>Please <strong>active product</strong> to get latest version</span>";
                }elseif("S" == $data->update_denied_type){
                    print  "<br/><span style='display: block; border-top: 1px solid #ccc;padding-top: 5px; margin-top: 10px;'>Please <strong>renew support period</strong> to get latest version</span>";
                }
            }
        }

		function el_plugin_update_info(){
            if(function_exists("wp_remote_get")) {
                $response = get_transient( $this->product_base."_up" );
                $old_found = false;
                if ( ! empty( $response['data'] ) ) {
                    $response = unserialize( $this->decrypt( $response['data'] ) );
                    if ( is_array( $response ) ) {
                        $old_found = true;
                    }
                }

                if ( ! $old_found ) {
                    $license_info=self::get_register_info();
                    $url=$this->server_host . "product/update/" . $this->product_id;
                    if(!empty($license_info->license_key)) {
                        $url.="/".$license_info->license_key."/".$this->version;
                    }
                    $args=[
                        'sslverify' => true,
                        'timeout' => 120,
                        'redirection' => 5,
                        'cookies' => array()
                    ];
                    $response = wp_remote_get( $url,$args);
                    if (is_wp_error($response)) {
                        $args['sslverify']=false;
                        $response = wp_remote_get( $url,$args);
                    }
                }

                if (!is_wp_error($response)) {
                    $body         = $response['body'];
                    $response_json = @json_decode( $body );

					$licenseKey = get_option("TranslatepressAutomaticTranslateAddonPro_lic_Key","");

                    if ( ! $old_found ) {
                        set_transient( $this->product_base."_up", [ "data" => $this->encrypt( serialize( [ 'body' => $body ] ) ) ], DAY_IN_SECONDS );
                    }

                    if(!(is_object( $response_json ) && isset($response_json->status )) && $this->is_encrypt_update){
                        $body=$this->decrypt($body,$this->key);
                        $response_json = json_decode( $body );
                    }

                    if ( is_object( $response_json ) && ! empty( $response_json->status ) && ! empty( $response_json->data->new_version ) ) {
                        $response_json->data->slug = plugin_basename( $this->plugin_file );;
                        $response_json->data->new_version = ! empty( $response_json->data->new_version ) ? $response_json->data->new_version : "";
                        $response_json->data->url         = ! empty( $response_json->data->url ) ? $response_json->data->url : "";
                        $response_json->data->package     = (!empty($licenseKey) && ! empty( $response_json->data->download_link )) ? $response_json->data->download_link : "";
                        $response_json->data->update_denied_type     = ! empty( $response_json->data->update_denied_type ) ? $response_json->data->update_denied_type : "";

                        $response_json->data->sections    = (array) $response_json->data->sections;
                        $response_json->data->plugin      = plugin_basename( $this->plugin_file );
                        $response_json->data->icons       = (array) $response_json->data->icons;
                        $response_json->data->banners     = (array) $response_json->data->banners;
                        $response_json->data->banners_rtl = (array) $response_json->data->banners_rtl;
                        unset( $response_json->data->is_stopped_update );

                        return $response_json->data;
                    }
                }
            }

            return null;
        }
		function plugin_update($transient)
		{
			if(empty($transient)){
				$transient =new stdClass();
				$transient->response=[];
			}
			$response = $this->el_plugin_update_info();
			if(!empty($response->plugin)){
				if ( $this->is_theme ) {
					$theme_data = wp_get_theme();
					$index_name = '' . $theme_data->get_template();
				} else {
					$index_name = $response->plugin;
				}
                if (!empty($response) && version_compare($this->version, $response->new_version, '<')) {
                    unset($response->download_link);
                    unset($response->is_stopped_update);
                    if($this->is_theme){
                        $transient->response[$index_name] = (array)$response;
                    }else{
                        $transient->response[$index_name] = (object)$response;
                    }
                }else{
                    if(isset($transient->response[$index_name])){
                        unset($transient->response[$index_name]);
                    }
                }
            }
            return $transient;
		}
		final function check_update_info($false, $action, $arg) {
		    if ( empty($arg->slug)){
                return $false;
            }
			if($this->is_theme){
				if ( !empty($arg->slug) && $this->product_base === $arg->slug){
					$response =$this->el_plugin_update_info();
					if ( !empty($response)) {
						return $response;
					}
				}
			}else{
				if ( !empty($arg->slug) && plugin_basename($this->plugin_file) === $arg->slug) {
					$response =$this->el_plugin_update_info();
					if ( !empty($response)) {
						return $response;
					}
				}
			}

			return $false;
		}
		
		// phpcs:ignore WordPress.NamingConventions.ValidFunctionName
		public static function &getInstance($plugin_base_file=null) {
	            return self::get_instance($plugin_base_file);
		}
		/**
		 * @param $plugin_base_file
		 *
		 * @return self|null
		 */
		public static function &get_instance($plugin_base_file=null) {
			if ( empty( self::$selfobj ) ) {
				if ( ! empty( $plugin_base_file ) ) {
					self::$selfobj = new self( $plugin_base_file );
				}
			}
			
			return self::$selfobj;
		}


		private function encrypt($plain_text,$password='') {
			if(empty($password)){
				$password=$this->key;
			}
			$plain_text=rand(10,99).$plain_text.rand(10,99);
			$method = 'aes-256-cbc';
			$key = substr( hash( 'sha256', $password, true ), 0, 32 );
			$iv = substr(strtoupper(md5($password)),0,16);
			return $this->b64_en( openssl_encrypt( $plain_text, $method, $key, OPENSSL_RAW_DATA, $iv ) );
		}
		private function decrypt($encrypted,$password='') {
			if(empty($password)){
				$password=$this->key;
			}
			$method = 'aes-256-cbc';
			$key = substr( hash( 'sha256', $password, true ), 0, 32 );
			$iv = substr(strtoupper(md5($password)),0,16);
			$plaintext=openssl_decrypt( $this->b64_dc( $encrypted ), $method, $key, OPENSSL_RAW_DATA, $iv );
			return substr($plaintext,2,-2);
		}
         function b64_dc($encrypted){
            $b64=preg_replace('#[^a-z0-9\_]#i','','ba*s-e#6-4#_d$e!c#o!d#e');
            return $b64($encrypted);
        }
        function b64_en($str){
            $b64=preg_replace('#[^a-z0-9\_]#i','','ba*s-e#6-4#_e$n!c#o!d#e');
            return $b64($str);
        }
		function encrypt_obj( $obj ) {
			$text = serialize( $obj );

			return $this->encrypt( $text );
		}

		private function decrypt_obj( $ciphertext ) {
			$text = $this->decrypt( $ciphertext );

			return unserialize( $text );
		}

		private function get_domain() {
			if ( defined( 'WPINC' ) && function_exists( 'get_bloginfo' ) ) {
				$server_name = get_bloginfo( 'url' );
				$domain      = $this->get_domains( $server_name );
				return $domain;
			} else {
				$base_url  = ( ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) ? 'https' : 'http' );
				$base_url .= '://' . $_SERVER['HTTP_HOST'];
				$base_url .= str_replace( basename( $_SERVER['SCRIPT_NAME'] ), '', $_SERVER['SCRIPT_NAME'] );

				return $base_url;
			}
		}
		private function get_domains( $url ) {
			$pieces = parse_url( $url );
			$domain = isset( $pieces['host'] ) ? $pieces['host'] : $pieces['path'];
			if ( preg_match( '/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs ) ) {
				return $regs['domain'];
			}
			return $domain;
		}

		private function get_eml() {
            return $this->email_address;
        }
		private function processs_response($response){
			$resbk="";
			if ( ! empty( $response ) ) {
				if ( ! empty( $this->key ) ) {
					$resbk=$response;
					$response = $this->decrypt( $response );
				}
				$response = json_decode( $response );

				if ( is_object( $response ) ) {
					return $response;
				} else {
					$response=new stdClass();
					$response->status = false;
					$response->msg    = "Response Error, contact with the author or update the plugin or theme";
					if(!empty($bkjson)){
                        $bkjson=@json_decode($resbk);
                        if(!empty($bkjson->msg)){
                            $response->msg    = $bkjson->msg;
                        }
					}
					$response->data = NULL;
					return $response;

				}
			}
			$response=new stdClass();
			$response->msg    = "unknown response";
			$response->status = false;
			$response->data = NULL;

			return $response;
		}
		private function _request( $relative_url, $data, &$error = '' ) {
            $response         = new stdClass();
            $response->status = false;
            $response->msg    = "Empty Response";
            $response->is_request_error = false;
            $final_data        = json_encode( $data );
            if ( ! empty( $this->key ) ) {
                $final_data = $this->encrypt( $final_data );
            }
            $url = rtrim( $this->server_host, '/' ) . "/" . ltrim( $relative_url, '/' );
            if(function_exists('wp_remote_post')) {
                $rq_params=[
                    'method' => 'POST',
                    'sslverify' => true,
                    'timeout' => 120,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking' => true,
                    'headers' => [],
                    'body' => $final_data,
                    'cookies' => []
                ];
                $server_response = wp_remote_post($url, $rq_params);

                if (is_wp_error($server_response)) {
                    $rq_params['sslverify']=false;
                    $server_response = wp_remote_post($url, $rq_params);
                     if (is_wp_error($server_response)) {
                        $response->msg    = $server_response->get_error_message();;
                        $response->status = false;
                        $response->data = NULL;
                        $response->is_request_error = true;
                        return $response;
                    }else{
                         if(!empty($server_response['body']) && (is_array($server_response) && 200 === (int) wp_remote_retrieve_response_code( $server_response )) && 'GET404' != $server_response['body']){
                            return $this->processs_response($server_response['body']);
                        }
                    }
                } else {
                    if(!empty($server_response['body']) && (is_array($server_response) && 200 === (int) wp_remote_retrieve_response_code( $server_response )) && 'GET404' != $server_response['body'] ){
                        return $this->processs_response($server_response['body']);
                    }
                }

            }

                $response->msg    = "No valid request method works for license checking";
                $response->status = false;
                $response->data = NULL;
                $response->is_request_error = true;
                return $response;


        }

		private function get_param( $purchase_key, $app_version, $admin_email = '' ) {
			$req               = new stdClass();
			$req->license_key  = $purchase_key;
			$req->email        = ! empty( $admin_email ) ? $admin_email : $this->get_eml();
			$req->domain       = $this->get_domain();
			$req->app_version  = $app_version;
			$req->product_id   = $this->product_id;
			$req->product_base = $this->product_base;

			return $req;
		}

		private function get_key_name() {
            return hash( 'crc32b', $this->get_domain() . $this->plugin_file . $this->product_id . $this->product_base . $this->key . "LIC" );
        }

        private function save_wp_response( $response ) {
            $key  = $this->get_key_name();
            $data = $this->encrypt( serialize( $response ), $this->get_domain() );
            update_option( $key, $data ) OR add_option( $key, $data );
        }

        private function get_old_wp_response() {
            $key  = $this->get_key_name();
            $response = get_option( $key, NULL );
            if ( empty( $response ) ) {
                return NULL;
            }

            return unserialize( $this->decrypt( $response, $this->get_domain() ) );
        }

        private function remove_old_wp_response() {
            $key  = $this->get_key_name();
            $is_deleted = delete_option( $key );
            foreach ( self::$_on_delete_license as $func ) {
                if ( is_callable( $func ) ) {
                    call_user_func( $func );
                }
            }

            return $is_deleted;
        }
        /**
         * The RemoveLicenseKey is generated by appsbd
         *
         * @deprecated deprecated 
         * @see remove_license_key()
         *
         * @param mixed $plugin_base_file
         * @param string $message
         *
         * @return mixed
         */
		public static function RemoveLicenseKey($plugin_base_file,&$message = "") {
			return self::remove_license_key($plugin_base_file,$message);
		}
		
		/**
		 * The remove license key is generated by appsbd
		 *
		 * @param mixed $plugin_base_file
		 * @param string $message
		 *
		 * @return mixed
		 */
		public static function remove_license_key($plugin_base_file,&$message = "") {
			$obj=self::get_instance($plugin_base_file);
			$obj->clean_update_info();
			return $obj->_remove_wp_plugin_license($message);
		}
		/**
		 * The CheckWPPlugin is generated by appsbd
		 * @deprecated deprecated 
		 * @see base::check_wp_plugin()
		 *
		 * @param mixed $purchase_key
		 * @param mixed $email
		 * @param string $error
		 * @param null $response_obj
		 * @param string $plugin_base_file
		 *
		 * @return mixed
		 */
		public static function CheckWPPlugin($purchase_key, $email,&$error = "", &$response_obj = null,$plugin_base_file="") {
			return self::check_wp_plugin($purchase_key, $email,$error, $response_obj,$plugin_base_file);
		}
		
		/**
		 * The check wp plugin is generated by appsbd
		 *
		 * @param mixed $purchase_key
		 * @param mixed $email
		 * @param string $error
		 * @param null $response_obj
		 * @param string $plugin_base_file
		 *
		 * @return mixed
		 */
		public static function check_wp_plugin($purchase_key, $email,&$error = "", &$response_obj = null,$plugin_base_file="") {
			$obj=self::get_instance($plugin_base_file);
			$obj->set_email_address($email);
			return $obj->_check_wp_plugin($purchase_key, $error, $response_obj);
		}
		
		final function _remove_wp_plugin_license(&$message=''){
			$old_respons=$this->get_old_wp_response();
			if(!empty($old_respons->is_valid)) {
				if ( ! empty( $old_respons->license_key ) ) {
					$param    = $this->get_param( $old_respons->license_key, $this->version );
					$response = $this->_request( 'product/deactive/'.$this->product_id, $param, $message );
					if ( empty( $response->code ) ) {
						if ( ! empty( $response->status ) ) {
							$message = $response->msg;
							$this->remove_old_wp_response();
							return true;
						}else{
							$message = $response->msg;
						}
					}else{
						$message=$response->message;
					}
				}
			}else{
                $this->remove_old_wp_response();
				return true;
			}
			return false;

		}
		
		/**
		 * The GetRegisterInfo is generated by appsbd
		 *
		 * @deprecated deprecated 
		 * @see get_register_info()
		 *
		 * @return mixed
		 */
		public static function GetRegisterInfo() {
			return self::get_register_info();
		}
		/**
		 * The get register info is generated by appsbd
		 *
		 * @return |null
		 */
		public static function get_register_info() {
			if(!empty(self::$selfobj)){
				return self::$selfobj->get_old_wp_response();
			}
			return null;

		}

		final function _check_wp_plugin( $purchase_key, &$error = "", &$response_obj = null ) {
            if(empty($purchase_key)){
                $this->remove_old_wp_response();
                $error="";
                return false;
            }
            $old_respons=$this->get_old_wp_response();
            $is_force=false;
			if(!empty($_POST['action']) && sanitize_key($_POST['action']) == 'tpap_refresh_license_ajax'){
				$is_force=true;
				$ajax_sent = 'yes';
				// Force refresh license verification
				$res = $this->Tpap_verify_license( $purchase_key, $old_respons,$ajax_sent);
				$response_obj = $this->get_old_wp_response();
			
				return $res;
			}else if ( !empty($old_respons)) {
				if ( ! empty( $old_respons->expire_date ) && strtolower( $old_respons->expire_date ) != 'no expiry' && strtotime( $old_respons->expire_date ) < time() ||  ! empty( $old_respons->support_end) && strtolower( $old_respons->support_end ) != 'unlimited' && strtotime( $old_respons->support_end ) < time()) {
				
					$valid = $this->Tpap_verify_license( $purchase_key, $old_respons,'no');
					return $valid;
				}
                if ( ! $is_force && ! empty( $old_respons->is_valid ) && $old_respons->next_request > time() && ( ! empty( $old_respons->license_key ) && $purchase_key == $old_respons->license_key ) ) {
                    $response_obj = clone $old_respons;
                    unset( $response_obj->next_request );

                    return true;
                }
            }

            $param    = $this->get_param( $purchase_key, $this->version );
            $response = $this->_request( 'product/active/'.$this->product_id, $param, $error );
			$error = $this->is_license_error($response->msg );
			if($error){
				return ;
			}
            if(empty($response->is_request_error)) {
                if ( empty( $response->code ) ) {
                    if ( ! empty( $response->status ) ) {
                        if ( ! empty( $response->data ) ) {
                            $serial_obj = $this->decrypt( $response->data, $param->domain );

                            $license_obj = unserialize( $serial_obj );
                            if ( $license_obj->is_valid ) {
                                $response_obj           = new stdClass();
                                $response_obj->is_valid = $license_obj->is_valid;
                                if ( $license_obj->request_duration > 0 ) {
                                    $response_obj->next_request = strtotime( "+ {$license_obj->request_duration} hour" );
                                } else {
                                    $response_obj->next_request = time();
                                }
                                $response_obj->expire_date   = $license_obj->expire_date;
                                $response_obj->support_end   = $license_obj->support_end;
                                $response_obj->license_title = $license_obj->license_title;
                                $response_obj->license_key   = $purchase_key;
								$response_obj->market = $license_obj->market;
								$response_obj->re_tried = 0;
                                $response_obj->msg           = $response->msg;
                                $this->save_wp_response( $response_obj );
                                unset( $response_obj->next_request );
                                delete_transient($this->product_base."_up");
                                return true;
                            } else {
                                if ( $this->_check_old_tied( $old_respons, $response_obj, $response ) ) {
                                    return true;
                                } else {
                                    $this->remove_old_wp_response();
                                    $error = ! empty( $response->msg ) ? $response->msg : "";
                                }
                            }
                        } else {
							if ( $this->_check_old_tied( $old_respons, $response_obj, $response ) ) {
								return true;
							} else {
									$this->remove_old_wp_response();
									$error = 'Invalid data';
							}
                        }

                    } else {
						
						$res = $this->Tpap_verify_license( $purchase_key, $old_respons, 'no' );
						if($res === 'wrong_license_status'){
							
							$error = $res;
							if($error){
								return;
							}	
						}
						elseif ($res === 'domain_exceeded'){
							$error = $res;
							if($error){
								return;
							}	
						}
						elseif ($res === 'inactive_license'){
							$error = $res;
							if($error){
								return;
							}	
						}
						elseif ($res === 'refunded_license'){
							$error = $res;
							if($error){
								return;
							}	
						}
						elseif ($res === true) {
							// Get the updated license info from cache after Tpap_verify_license
							$updatedResponse = $this->get_old_wp_response();
							if ($updatedResponse) {
								$response_obj = clone $updatedResponse;
								unset($response_obj->next_request);
								if (isset($response_obj->re_tried)) {
									unset($response_obj->re_tried);
								}
							}
						}
						return $res;
                    }
                } else {
                    $error = $response->message;
                }
            }else{
                if ( $this->_check_old_tied( $old_respons, $response_obj, $response ) ) {
                    return true;
                } else {
                    $this->remove_old_wp_response();
                    $error = ! empty( $response->msg ) ? $response->msg : "";
                }
            }
            return $this->_check_old_tied($old_respons,$response_obj);
        }
        private function _check_old_tied(&$old_respons,&$response_obj){
            if ( ! empty( $old_respons ) && ( empty( $old_respons->tried ) || $old_respons->tried <= 12 ) ) {
                $old_respons->next_request = strtotime("+ 6 hour");
                $old_respons->tried=empty($old_respons->tried)?1:($old_respons->tried+1);
                $response_obj = clone $old_respons;
                unset( $response_obj->next_request );
                if(isset($response_obj->tried)) {
                    unset( $response_obj->tried );
                }
                $this->save_wp_response($old_respons);
                return true;
            }
            return false;
        }

			
	/**
	 * Check if the response contains a known license error.
	 *
	 * @param string $response The response string from the API.
	 * @return bool True if license error exists, false otherwise.
	 */
	private function is_license_error( $response ) {
		if ( ! empty( $response ) ) {

			$license_errors = [
				'Invalid license code',
				'License quota has been over, you can not add more domain with this license key',
				'Your purchase key has been temporary inactivated',
				'Your key has been installed on another domain',
				'License information is invalid',
				'invalid_license_details',
				'wrong_license_status',
				'domain_exceeded',
				'Your purchase key has been refunded',
				'inactive_license',
				'refunded_license',
			];

			foreach ( $license_errors as $error ) {
				if ( strpos( $response, $error ) !== false ) {
					$this->remove_old_wp_response();
					return $response;
				}
			}
		}
	}
	/**
	 * Verify license with the remote server
	 * 
	 * @param string $purchase_key The license key to verify
	 * @param object|null $old_respons Previous response object from cache
	 * @param object|null $response Response object from previous request
	 * @param string $ajax_sent Whether this is an AJAX request ('yes' or 'null')
	 * @return bool|void Returns true if license is valid, false if invalid, void if license is inactive/revoked
	 */
	public function Tpap_verify_license( $purchase_key, $old_respons = null, $ajax_sent = 'no' ) {

		if(empty($purchase_key)){

			return;
		}
	
		// 1. Use cached valid response if still within the allowed window
		if($ajax_sent==='no'){
		
			if ( ! empty( $old_respons->is_valid ) &&
					! empty( $old_respons->next_request ) &&
					$old_respons->next_request > time() &&
					! empty( $old_respons->license_key ) &&
					$purchase_key === $old_respons->license_key ) {
					
						$response_obj = clone $old_respons;
						unset( $response_obj->next_request );
						return true;
			} 

			// 2. Block if too many failed attempts

			if ( ! empty( $old_respons->re_tried ) && $old_respons->re_tried >= 120 ) {

					$old_respons->msg = 'limit_reached';

					// Save the updated object
					$this->save_wp_response( $old_respons );
				
				return true;
			}
		}
		$param    = $this->get_param( $purchase_key, $this->version );
		// 3. Make remote API request
		$api_url = $this->server_host . 'product/v1/license-view';
		$response = wp_remote_post($api_url, [
			'headers' => [
				'Content-Type' => 'application/json',
			],
			'body' => json_encode([
				'license_key' => $purchase_key,
				'product_id' => $this->product_id,
				'param'     => $param,
			]),
		]);
		
		if ( is_wp_error( $response ) ) {

			$response_obj = new stdClass();
			$response_obj->is_valid = false;
			$response_obj->msg = 'License server unavailable';
			$this->save_wp_response( $response_obj );
			return false;
		}

		// 4. Handle response from server
		$body = wp_remote_retrieve_body( $response );
		$body_data = json_decode($body);
		if($body_data && ! empty($body_data->message) && ($body_data->message == "Max domain exceeded." || $body_data->message == "Maximum domain limit reached.")){
			$error = $this->is_license_error( 'domain_exceeded' );
			if($error){
				return $error;
			}
		}else if($body_data && ! empty($body_data->status) && $body_data->status == "I"){
			$error = $this->is_license_error( 'inactive_license' );
			if($error){
				return $error;
			}
		}else if($body_data && ! empty($body_data->status) && $body_data->status == "R"){
			$error = $this->is_license_error( 'refunded_license' );
			if($error){
				return $error;
			}
		}
		if (!is_wp_error($response)) {
			$body = wp_remote_retrieve_body($response);
			$data = json_decode($body, true);
		}
		// Check for API response success and data structure
		if (empty($data['success']) || empty($data['data']) || empty($data['data']['status'])) {
			$err_msg = 'invalid_license_details';
    		$error = $this->is_license_error($err_msg);
			
			return false;
		}
		
		$max_domains = '';
		$active_domains_count = '';
		
		// Handle infinity case for max_domain
		if ( ! empty( $data['data']['max_domain'] ) ) {

				$max_domains = (int) $data['data']['max_domain'];
				$active_domains_count = ! empty( $data['data']['active_domain_count'] ) ? (int) $data['data']['active_domain_count'] : 0;

				if($max_domains  === '∞' ){

					$max_domains = ''; 
					$active_domains_count = ''; 
				}
		}
		if($data['data']['status']=='I' || $data['data']['status']=='R' || ( $max_domains !== '' && $active_domains_count > $max_domains ) ){
			$error = $this->is_license_error('wrong_license_status' );
		   if($error){
				return $error;
		   }
			
		}else{

			$status = true;
			$current_time = new DateTime();


			if($data['data']['has_support']=='Y'){

				$support_end_time = $data['data']['support_end_time'];
				$support_expire = new DateTime($support_end_time);
				
				if($current_time > $support_expire){

					$status 		= "support_expired";
					$support_end 	= $support_end_time;
				}else{
					$support_end = $support_end_time;
				}

			}elseif($data['data']['has_support']=='U'){

				$support_end = 'unlimited';

			}else{
				$status 		= "support_expired";
				$support_end 	= 'no support';
			}
			if($data['data']['has_expiry']=='Y'){

				$expiry_time = $data['data']['expiry_time'];
				$expiry_date = new DateTime($expiry_time);
				
				if ($current_time > $expiry_date) {

					$status = "license_expired";
					$expire_date = $expiry_time;

				}elseif($current_time < $expiry_date){
					
					$expire_date = $expiry_time;
				}
				
			}
			
			$response_obj = new stdClass();
			$response_obj->is_valid     = $status;
			$response_obj->license_key  = $purchase_key;
			$response_obj->msg          = '';
			$response_obj->next_request = strtotime( '+6 hours' );
			

			// 5. Add retry counter if license is invalid
			if ($response_obj->is_valid === 'license_expired' || $response_obj->is_valid === 'support_expired'  ) {

				$response_obj->re_tried = empty( $old_respons->re_tried ) ? 1 : ( $old_respons->re_tried + 1 );

				if ( $response_obj->re_tried >= 120 ) {
					$response_obj->msg = 'Maximum verification attempts reached';
				}
			}

			// 6. Store additional license info
			if ( ! empty( $data['data'] ) ) {
				$response_obj->expire_date   = isset($expire_date)?$expire_date:'no expiry';
				$response_obj->support_end   = $support_end;
				$response_obj->market       = $data['data']['market'];
				$response_obj->license_title = $data['data']['license_title'];
				$response_obj->status 		= $data['data']['status'];
			}
			
			// Save response
			$this->save_wp_response( $response_obj );


			return true;
		}
		
	}

	}
}
