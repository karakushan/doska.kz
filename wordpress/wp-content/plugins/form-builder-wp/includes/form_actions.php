<?php

function wpfb_form_action_groundhogg($data){
	if(!class_exists('Groundhogg')){
		return array(
			'success' => false,
			'message'  => __( 'Groundhogg not exists!','form-builder-wp' ),
		);
	}
	
	$form_id = $_REQUEST['_wpfb_form_id'];
	$groundhogg_tags = wpfb_form_get_post_meta('_groundhogg_tags',$form_id);
	
	$name = isset($data['name']) ? $data['name'] : ( isset($data['full_name']) ? $data['full_name'] : '' );
	
	if ( !empty($name) ){
		$parts = wpgh_split_name( $name );
		$args[ 'first_name' ] = $parts[ 0 ];
		$args[ 'last_name' ] = $parts[ 1 ];
	}else{
		$args['first_name'] = isset($data['first_name']) ? trim(preg_replace('/\s*\[[^)]*\]/', '', $data['first_name'])) : '';
		$args['last_name'] = isset($data['last_name']) ? trim(preg_replace('/\s*\[[^)]*\]/', '', $data['last_name'])) : '';
	}
	
	$email = isset($data['email']) ? $data['email'] : '';
	
	if(!wpfb_form_is_email($email)){
		return array(
			'success' => false,
			'message'  => __( 'The email address isn\'t correct.','form-builder-wp' ),
		);
	}
	$args['email'] = $email;
	
	//magic time
	$id = WPGH()->contacts->add( $args );
	
	if ( ! $id ){
		return array(
			'success' => false,
			'message'  => __( 'Can not add contact to Groundhogg','form-builder-wp' ),
		);
	}
	
	$contact = wpgh_get_contact( $id );
	
	$ignore = apply_filters('wpfb_form_groundhogg_contact_ignore', array(
		'first_name',
		'last_name',
		'email',
		'action',
		'_wpfb_form_action',
		'_wpfb_form_hidden_fields',
		'_wpfb_form_id',
		'_wpfb_form_url',
		'_wpfb_form_referer',
		'_wpfb_form_post_id',
		'_wpfb_form_nonce'
		
	));
	foreach ( $data as $key => $value ) {
		$key = sanitize_key( $key );
		if ( is_array( $value ) ){
			$value = implode( ', ', $value );
		}
		if ( strpos( $value, PHP_EOL  ) !== false ){
			$value = sanitize_textarea_field( stripslashes( $value ) );
		} else {
			$value = sanitize_text_field( stripslashes( $value ) );
		}
		if ( ! in_array( $key, $ignore ) ){
			$value = apply_filters( 'wpgh_sanitize_submit_value', $value, null );
			$contact->update_meta( $key, $value );
		}
	}
	wpgh_after_form_submit_handler( $contact );
	$contact->apply_tag($groundhogg_tags);
	return array(
		'success' => true,
		'message'  => __( 'Add contact to Groundhogg successful!', 'form-builder-wp' ),
	);
}

function wpfb_form_action_mymail($data){
	if(!defined('MYMAIL_DIR')){
		return array(
			'success' => false,
			'message'  => __( 'myMail Newsletters not exists!','form-builder-wp' ),
		);
	}
	$form_id = $_REQUEST['_wpfb_form_id'];
	$lists = wpfb_get_post_meta($form_id,'_mymail',array());
	$double_opt_in = wpfb_get_post_meta($form_id,'_mymail_double_opt_in',0) == '1' ? true : false ;
	$userdata['firstname'] = isset($data['firstname']) ? trim(preg_replace('/\s*\[[^)]*\]/', '', $data['firstname'])) : '';
	$userdata['lastname'] = isset($data['lastname']) ? trim(preg_replace('/\s*\[[^)]*\]/', '', $data['lastname'])) : '';
	$email = isset($data['email']) ? $data['email'] : '';
	if(!is_email($email)){
		return array(
			'success' => false,
			'message'  => __( 'The email address isn\'t correct.','form-builder-wp' ),
		);
	}
	$ret = mymail_subscribe( $email, $userdata, $lists, $double_opt_in, true);
	if (!$ret ) {
		return array(
			'success' => false,
			'message'  => __( 'Not Subscribe to our Newsletters!','form-builder-wp' ),
		);
	} else {
		return array(
			'success' => true,
			'message'  => __( 'Subscribe to our Newsletters successful!', 'form-builder-wp' ),
		);
	}
}

function wpfb_form_action_mailpoet($data){
	if(!class_exists('WYSIJA')){
		return array(
			'success' => false,
			'message'  => __( 'MailPoet Newsletters not exists!','form-builder-wp' ),
		);
	}
	$form_id = $_REQUEST['_wpfb_form_id'];
	$list_submit['user_list']['list_ids'] = wpfb_get_post_meta($form_id,'_mailpoet',array());
	$list_submit['user']['firstname'] = isset($data['firstname']) ? trim(preg_replace('/\s*\[[^)]*\]/', '', $data['firstname'])) : '';
	$list_submit['user']['lastname'] = isset($data['lastname']) ? trim(preg_replace('/\s*\[[^)]*\]/', '', $data['lastname'])) : '';
	$list_submit['user']['email'] = isset($data['email']) ? $data['email'] : '';
	if(!is_email($list_submit['user']['email'])){
		return array(
			'success' => false,
			'message'  => __( 'The email address isn\'t correct.','form-builder-wp' ),
		);
	}
	//WYSIJA_help_user
	$helper_user = WYSIJA::get('user','helper');
	$result = $helper_user->addSubscriber($list_submit);
	if(!$result){
		$message = $helper_user->getMsgs();
		return array(
			'success' => false,
			'message'  => implode('<br>',$message['error']),
		);
	}else{
		return array(
			'success' => true,
			'message'  => sprintf(__( 'MailPoet Added: %s to list <strong>%s</strong>','form-builder-wp' ),$list_submit['user']['email'],implode(', ',wpfb_form_get_mailpoet_subscribers_list($list_submit['user']['list_ids']))),
		);
	}
}

function wpfb_form_action_mailchimp($data){
	$mailchimp_api = wpfb_form_get_option('mailchimp_api',false);
    $success=false;
    $message='';
    if($mailchimp_api){
        $api = wpfb_mailchimp_get_api($mailchimp_api);
        
        $fname = isset($data['name']) ? '':'';
        
        $mailchimp_list_id = wpfb_form_get_post_meta('_mailchimp_list', $data['_wpfb_form_id']);
        if(empty($mailchimp_list_id)){
            $mailchimp_list_id = wpfb_form_get_option('mailchimp_list',0);
        }
        $mailchimp_list_id = apply_filters('wpfb_form_mailchimp_list', $mailchimp_list_id, $data);
        
        $first_name = isset($data['first_name']) ? $data['first_name']:(isset($data['name']) ? $data['name'] : '');
        $last_name = isset($data['last_name']) ? $data['last_name']:(isset($data['name']) ? $data['name'] : '');
        $email_address = isset($data['email']) ? $data['email'] : '';
        $merge_vars = array(
            'FNAME' => $first_name,
            'LNAME' => $last_name,
        );
        $merge_vars = apply_filters('wpfb_form_mailchimp_merge_vars', $merge_vars, $data);
        $double_optin = wpfb_form_get_option('mailchimp_opt_in','') === '1' ? true : false;
        $replace_interests = wpfb_form_get_option('mailchimp_replace_interests','') === '1' ? true : false;
        
        $mailchimp_tags = trim(wpfb_form_get_post_meta('_mailchimp_tags', $data['_wpfb_form_id']));
        if(empty($mailchimp_tags)){
            $mailchimp_tags = trim(wpfb_form_get_option('mailchimp_tags',''));
        }
        
        if(!empty($mailchimp_tags)){
            $mailchimp_tags = explode( ',', $mailchimp_tags );
            $mailchimp_tags = array_map( 'trim', $mailchimp_tags );
            
            // remove empty tag values
            foreach ( $mailchimp_tags as $i => $mailchimp_tag ) {
                if ( $mailchimp_tag === '' ) {
                    unset( $mailchimp_tags[ $i ] );
                }
            }
            
            $mailchimp_tags =  array_values( $mailchimp_tags );
        }
        $update_existing = wpfb_form_get_option('mailchimp_update_existing','') === '1' ? true : false;
        $existing_member_data = null;
        
        $request_params = apply_filters('wpfb_form_mailchimp_list_subscriber_request_data', [
            'email_address' => $email_address,
            'status' => $double_optin ? 'pending' : 'subscribed', //"subscribed", "unsubscribed", "cleaned", "pending", or "transactional".
            'email_type' => 'html',
            'ip_signup' => wpfb_form_get_request_ip_address(),
            //'interests' => (object)[]
        ]);
        if(!empty($mailchimp_tags)){
            $request_params['tags'] = (array) $mailchimp_tags;
        }
        
        $should_update = true;
        
        try{
            $existing_member_data = $api->get_list_member( $mailchimp_list_id, $email_address );
            if($existing_member_data->status === 'subscribed'){
                // if we're not supposed to update, bail.
                if ( ! $update_existing ) {
                    throw new Exception(__('That subscriber already exists.','form-builder-wp'), 214);
                }
                $request_params['status'] = 'subscribed';
                // this key only exists if list actually has interests
                if ( isset( $existing_member_data->interests ) ) {
                    $existing_interests = (array) $existing_member_data->interests;
                    
                    // if replace, assume all existing interests disabled
                    if ( $replace_interests ) {
                        $existing_interests = array_fill_keys( array_keys( $existing_interests ), false );
                    }
                    
                    $request_params['interests'] = array_replace( $existing_interests, $request_params['interests'] );
                }
            }elseif ( $request_params['status'] === 'pending' && $existing_member_data->status === 'pending' ) {
                // this ensures that a new double opt-in email is send out
                $api->update_list_member($mailchimp_list_id, $email_address, [
                    'status' => 'unsubscribed'
                ]);
            }
        }catch ( WPFB_Mailchimp_API_Resource_Not_Found_Exception $e ) {
            // subscriber does not exist (not an issue in this case)
        }catch ( WPFB_Mailchimp_API_Exception $e ) {
            $success = false;
            $message = $e->response_data->detail;
            $should_update = false;
        }catch (Exception $e){
            if($e->getCode() == 214){
                $success = true;
                $message = __('This email is already subscribed.','form-builder-wp');
            }else{
                $success = false;
                $message = $e->getMessage();
            }
            $should_update = false;
        }
        
        if($should_update){
        
            try {
                if ( $existing_member_data ) {
                    $data = $api->update_list_member( $mailchimp_list_id, $email_address, $request_params );
                    if ( isset( $request_params['tags'] ) && is_array( $request_params['tags'] ) && !empty($request_params['tags']) ) {
                        
                        $existing_member_data_tags = array_map(
                            function ( $tag ) {
                                return $tag->name;
                            },
                            $existing_member_data->tags
                            );
                        $tags = array_unique( array_merge( $existing_member_data_tags, $request_params['tags'] ), SORT_REGULAR );
                        $tags = array_map(
                            function ( $tag ) {
                                return array(
                                    'name'   => $tag,
                                    'status' => 'active',
                                );
                            },
                            $tags
                            );
                        $response = $api->update_list_member_tags($mailchimp_list_id,$email_address, [
                            "tags" => $tags,
                        ]);
                    }
                } else {
                    $response = $api->add_new_list_member($mailchimp_list_id,$request_params);
                }
                $success = true;
            }catch ( WPFB_Mailchimp_API_Exception $e ) {
                $success = false;
                $message = $e->response_data->detail;
            }catch ( Exception $e ) {
                $success = false;
                $message = $e->getMessage();
            }
        }
    }
    // Check the results of our Subscribe and provide the needed feedback
    if (!$success ) {
        return array(
            'success' => false,
            'message'  => !empty($message) ? $message : __( 'Not Subscribe to Mailchimp!','form-builder-wp' ),
        );
    } else {
        return array(
            'success' => true,
            'message'  => !empty($message) ? $message : __( 'Subscribe to Mailchimp Successful!', 'form-builder-wp' ),
        );
    }
}

function wpfb_form_action_login($data){
	$data['user_login']         = isset($data['username']) ?  $data['username'] : '';
	$data['user_password']      = isset($data['password']) ?  $data['password']: '';
	$data['remember']           = isset($data['rememberme']) ? $data['rememberme']:'';
	$secure_cookie = is_ssl() ? true : false;;
	$secure_cookie = apply_filters('wpfb_form_login_secure_cookie', $secure_cookie);
	$user = wp_signon( $data, $secure_cookie );
	// Check the results of our login and provide the needed feedback
	if ( is_wp_error( $user ) ) {
		return array(
			'success' => false,
			'message'  => __( 'Wrong Username or Password!','form-builder-wp' ),
		);
	} else {
		return array(
			'success' => true,
			'message'  => __( 'Login Successful!', 'form-builder-wp' ),
		);
	}
}

function wpfb_form_action_register($data){
	if(get_option( 'users_can_register' )){
		$user_login = isset($data['user_login']) ? $data['user_login'] : '';
		$user_email = isset($data['user_email']) ? $data['user_email'] : '';
		$user_password  = isset($data['user_password']) ? $data['user_password'] : '';
		$cuser_password = isset($data['cuser_password']) ? $data['cuser_password'] : '';
			
		$ret = _wpfb_form_register_new_user($user_login, $user_email,$user_password,$cuser_password,$data);


		if ( is_wp_error( $ret ) ) {
			return array(
				'success' => false,
				'message'   => $ret->get_error_message(),
			);
		} else {
			if ( apply_filters( 'wpfb_form_registration_auth_new_customer', true, $ret ) ) {
				wp_set_auth_cookie( $ret );
			}
			
			do_action('wpfb_form_register_new_user_success', $ret, $data);
			
			return array(
				'success'     => true,
				'message'	=> __( 'Registration complete.', 'form-builder-wp' )
			);
		}
	}else {
		return array(
			'success' => false,
			'message'   =>__( 'Not allow register in site.', 'form-builder-wp' ),
		);
	}
}

function _wpfb_form_register_new_user( $user_login, $user_email, $user_password='', $cuser_password='',$data=array()) {

	$errors = new WP_Error();
	$sanitized_user_login = sanitize_user( $user_login );
	$user_email = apply_filters( 'user_registration_email', $user_email );

	// Check the username was sanitized
	if ( $sanitized_user_login == '' ) {
		$errors->add( 'empty_username', __( 'Please enter a username.', 'form-builder-wp' ) );
	} elseif ( ! validate_username( $user_login ) ) {
		$errors->add( 'invalid_username', __( 'This username is invalid because it uses illegal characters. Please enter a valid username.', 'form-builder-wp' ) );
		$sanitized_user_login = '';
	} elseif ( username_exists( $sanitized_user_login ) ) {
		$errors->add( 'username_exists', __( 'This username is already registered. Please choose another one.', 'form-builder-wp' ) );
	}

	// Check the email address
	if ( $user_email == '' ) {
		$errors->add( 'empty_email', __( 'Please type your email address.', 'form-builder-wp' ) );
	} elseif ( ! is_email( $user_email ) ) {
		$errors->add( 'invalid_email', __( 'The email address isn\'t correct.', 'form-builder-wp' ) );
		$user_email = '';
	} elseif ( email_exists( $user_email ) ) {
		$errors->add( 'email_exists', __( 'This email is already registered, please choose another one.', 'form-builder-wp' ) );
	}
	$form_has_password = false;
	//Check the password
	if(empty($user_password)){
		$user_password = wp_generate_password( 12, false );
	}else{
		$form_has_password = true;
		if(strlen($user_password) < 6){
			$errors->add( 'minlength_password', __( 'Password must be 6 character long.', 'form-builder-wp' ) );
		}elseif (empty($cuser_password)){
			$errors->add( 'not_cpassword', __( 'Not see password confirmation field.', 'form-builder-wp' ) );
		}elseif ($user_password != $cuser_password){
			$errors->add( 'unequal_password', __( 'Passwords do not match.', 'form-builder-wp' ) );
		}
	}

	$errors = apply_filters( 'registration_errors', $errors, $sanitized_user_login, $user_email );

	if ( $errors->get_error_code() )
		return $errors;

	$new_user_data = array(
		'user_login' => wp_slash($sanitized_user_login),
		'user_pass'  => $user_password,
		'user_email' => wp_slash($user_email),
	);
	$optional_user_data = array('user_nicename','user_url','user_email','display_name','nickname','first_name','last_name','description','rich_editing','user_registered','role','jabber','aim','yim','show_admin_bar_front');
	foreach ($optional_user_data as $v)
		if(isset($data[$v]) && !empty($data[$v]))
			$new_user_data[$v] = $data[$v];
		$new_user_data = apply_filters('wpfb_form_new_user_data', $new_user_data);
		$user_id = wp_insert_user( $new_user_data );
		//$user_id = wp_create_user( $sanitized_user_login, $user_password, $user_email );

		if ( ! $user_id ) {
			$errors->add( 'registerfail', __( 'Couldn\'t register you... please contact the site administrator', 'form-builder-wp' ) );

			return $errors;
		}
		if(apply_filters('wpfb_form_user_password_nag', false))
			update_user_option( $user_id, 'default_password_nag', true, true ); // Set up the Password change nag.

		do_action( 'register_new_user', $user_id );

		$user = get_userdata( $user_id );
		//@todo
		$notify = $form_has_password ? 'admin' : '';
		$notify = apply_filters('wpfb_form_new_user_notify', $notify);

		wp_new_user_notification( $user_id, null, $notify );

		if(!empty($user_password)){
			$data_login['user_login']             = $user->user_login;
			$data_login['user_password']          = $user_password;
			$user_login                    	      = wp_signon( $data_login, false );
		}


		return $user_id;
}

function wpfb_form_action_forgotten($data){
	$user_login = isset($data['user_login']) ? $data['user_login'] : '';
	// 		if ( wpfb_form_validate_email($user_login) ) {
	// 			$username = sanitize_email( $user_login );
	// 		} else {
	// 			$username = sanitize_user( $user_login );
	// 		}

	$user_forgotten = _wpfb_form_action_retrieve_password( $user_login );

	if ( is_wp_error( $user_forgotten ) ) {
		return array(
			'success' 	 => false,
			'message' => $user_forgotten->get_error_message(),
		);
	} else {
		return array(
			'success'   => true,
			'message' => __( 'Password Reset. Please check your email.', 'form-builder-wp' ),
		);
	}

}

function _wpfb_form_action_retrieve_password($post_user_login) {
	global $wpdb, $wp_hasher;

	$errors = new WP_Error();

	if ( empty( $post_user_login ) ) {
		$errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or email address.'));
	} elseif ( strpos( $post_user_login, '@' ) ) {
		$user_data = get_user_by( 'email', trim( $post_user_login ) );
		if ( empty( $user_data ) )
			$errors->add('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.'));
	} else {
		$login = trim($post_user_login);
		$user_data = get_user_by('login', $login);
	}

	do_action( 'lostpassword_post', $errors );

	if ( $errors->get_error_code() )
		return $errors;

	if ( !$user_data ) {
		$errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or email.'));
		return $errors;
	}

	// Redefining user_login ensures we return the right case in the email.
	$user_login = $user_data->user_login;
	$user_email = $user_data->user_email;

	do_action( 'retreive_password', $user_login );

	$allow = apply_filters( 'allow_password_reset', true, $user_data->ID );

	if ( ! $allow )
		return new WP_Error('no_password_reset', __('Password reset is not allowed for this user'));
	else if ( is_wp_error($allow) )
		return $allow;
	if(function_exists('get_password_reset_key')){
		$key = get_password_reset_key( $user_data );
	}else{
		$key = wp_generate_password( 20, false );
		do_action( 'retrieve_password_key', $user_login, $key );
		if ( empty( $wp_hasher ) ) {
			require_once ABSPATH . 'wp-includes/class-phpass.php';
			$wp_hasher = new PasswordHash( 8, true );
		}
		$hashed = time() . ':' . $wp_hasher->HashPassword( $key );
		$key_saved = $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_login ) );
		if ( false === $key_saved ) {
			return new WP_Error( 'no_password_key_update', __( 'Could not save password reset key to database.' ) );
		}
	}


	if ( is_wp_error( $key ) ) {
		return $key;
	}

	$message = __('Someone has requested a password reset for the following account:') . "\r\n\r\n";
	$message .= network_home_url( '/' ) . "\r\n\r\n";
	$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
	$message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
	$message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
	$message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . ">\r\n";

	if ( is_multisite() )
		$blogname = $GLOBALS['current_site']->site_name;
	else
		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$title = sprintf( __('[%s] Password Reset'), $blogname );

	$title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );

	$message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );

	if ( $message && !wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) )
		return new WP_Error( __('The email could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function.') );

	return true;
}