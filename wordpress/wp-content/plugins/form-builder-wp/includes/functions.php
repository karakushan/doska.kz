<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Returns the first found number from an string
 * Parsing depends on given locale (grouping and decimal)
 *
 * Examples for input:
 * '  2345.4356,1234' = 23455456.1234
 * '+23,3452.123' = 233452.123
 * ' 12343 ' = 12343
 * '-9456km' = -9456
 * '0' = 0
 * '2 054,10' = 2054.1
 * '2'054.52' = 2054.52
 * '2,46 GB' = 2.46
 *
 * @param string|float|int $value
 * @return float|null
 */
function wpfb_form_get_number($value)
{
	if (is_null($value)) {
		return null;
	}

	if (!is_string($value)) {
		return floatval($value);
	}

	//trim spaces and apostrophes
	$value = str_replace(array('\'', ' '), '', $value);

	$separatorComa = strpos($value, ',');
	$separatorDot  = strpos($value, '.');

	if ($separatorComa !== false && $separatorDot !== false) {
		if ($separatorComa > $separatorDot) {
			$value = str_replace('.', '', $value);
			$value = str_replace(',', '.', $value);
		}
		else {
			$value = str_replace(',', '', $value);
		}
	}
	elseif ($separatorComa !== false) {
		$value = str_replace(',', '.', $value);
	}

	return floatval($value);
}

function wpfb_form_get_mailchimp_list($mailchimp_api,$default=array()){
	$lists = array();
    
    if(empty($mailchimp_api)){
        return $default;
    }
    
    try {
        $api = wpfb_mailchimp_get_api($mailchimp_api);
        $lists_data = $api->get_lists();
        if( ! empty( $lists_data ) ){
            $lists = array(__('Select a list &hellip;','form-builder-wp'));
            foreach( $lists_data as $list ){
                $lists[ $list->id ] = sprintf(__('ID: %1$s - Name: %2$s','form-builder-wp'), $list->id, $list->name);
            }
        }else{
            $lists = array(__("Unable to load MailChimp lists, check your API Key.", 'form-builder-wp'));
        }
    }catch (Exception $e){
        $lists = array($e->getMessage());
    }
    if(empty($lists)){
        $lists = $default;
    }
    return $lists;
}

function wpfb_form_to_int($val){
	if(''==$val)
		return 0;
	return $val;
}

function wpfb_form_require_field_name_notice(){
	return '<span class="wpfb-form-control-require-name-notice">'.__('Field name is required', 'form-builder-wp').'</span>';
}

function wpfb_form_get_currencies(){
	return apply_filters('wpfb_form_currencies', array(
		'AUD'=>'Australian Dollar - AUD',
		'BRL'=>'Brazilian Real - BRL',
		'CAD'=>'Canadian Dollar - CAD',
		'CZK'=>'Czech Koruna - CZK',
		'DKK'=>'Danish Krone - DKK',
		'EUR'=>'Euro - EUR',
		'HKD'=>'Hong Kong Dollar - HKD',
		'HUF'=>'Hungarian Forint - HUF',
		'ILS'=>'Israeli New Sheqel - ILS',
		'JPY'=>'Japanese Yen - JPY',
		'MYR'=>'Malaysian Ringgit - MYR',
		'MXN'=>'Mexican Peso - MXN',
		'NOK'=>'Norwegian Krone - NOK',
		'NZD'=>'New Zealand Dollar - NZD',
		'PHP'=>'Philippine Peso - PHP',
		'PLN'=>'Polish Zloty - PLN',
		'GBP'=>'Pound Sterling - GBP',
		'RUB'=>'Russian Ruble - RUB',
		'SGD'=>'Singapore Dollar - SGD',
		'SEK'=>'Swedish Krona - SEK',
		'CHF'=>'Swiss Franc - CHF',
		'TWD'=>'Taiwan New Dollar - TWD',
		'THB'=>'Thai Baht - THB',
		'TRY'=>'Turkish Lira - TRY',
		'USD'=>'U.S. Dollar - USD',
		
	));
}

function wpfb_form_get_currency_symbol( $currency ) {
	$symbols         = apply_filters(
		'wpfb_form_currency_symbols',
		array(
			'AED' => '&#x62f;.&#x625;',
			'AFN' => '&#x60b;',
			'ALL' => 'L',
			'AMD' => 'AMD',
			'ANG' => '&fnof;',
			'AOA' => 'Kz',
			'ARS' => '&#36;',
			'AUD' => '&#36;',
			'AWG' => 'Afl.',
			'AZN' => 'AZN',
			'BAM' => 'KM',
			'BBD' => '&#36;',
			'BDT' => '&#2547;&nbsp;',
			'BGN' => '&#1083;&#1074;.',
			'BHD' => '.&#x62f;.&#x628;',
			'BIF' => 'Fr',
			'BMD' => '&#36;',
			'BND' => '&#36;',
			'BOB' => 'Bs.',
			'BRL' => '&#82;&#36;',
			'BSD' => '&#36;',
			'BTC' => '&#3647;',
			'BTN' => 'Nu.',
			'BWP' => 'P',
			'BYR' => 'Br',
			'BYN' => 'Br',
			'BZD' => '&#36;',
			'CAD' => '&#36;',
			'CDF' => 'Fr',
			'CHF' => '&#67;&#72;&#70;',
			'CLP' => '&#36;',
			'CNY' => '&yen;',
			'COP' => '&#36;',
			'CRC' => '&#x20a1;',
			'CUC' => '&#36;',
			'CUP' => '&#36;',
			'CVE' => '&#36;',
			'CZK' => '&#75;&#269;',
			'DJF' => 'Fr',
			'DKK' => 'DKK',
			'DOP' => 'RD&#36;',
			'DZD' => '&#x62f;.&#x62c;',
			'EGP' => 'EGP',
			'ERN' => 'Nfk',
			'ETB' => 'Br',
			'EUR' => '&euro;',
			'FJD' => '&#36;',
			'FKP' => '&pound;',
			'GBP' => '&pound;',
			'GEL' => '&#x20be;',
			'GGP' => '&pound;',
			'GHS' => '&#x20b5;',
			'GIP' => '&pound;',
			'GMD' => 'D',
			'GNF' => 'Fr',
			'GTQ' => 'Q',
			'GYD' => '&#36;',
			'HKD' => '&#36;',
			'HNL' => 'L',
			'HRK' => 'kn',
			'HTG' => 'G',
			'HUF' => '&#70;&#116;',
			'IDR' => 'Rp',
			'ILS' => '&#8362;',
			'IMP' => '&pound;',
			'INR' => '&#8377;',
			'IQD' => '&#x639;.&#x62f;',
			'IRR' => '&#xfdfc;',
			'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
			'ISK' => 'kr.',
			'JEP' => '&pound;',
			'JMD' => '&#36;',
			'JOD' => '&#x62f;.&#x627;',
			'JPY' => '&yen;',
			'KES' => 'KSh',
			'KGS' => '&#x441;&#x43e;&#x43c;',
			'KHR' => '&#x17db;',
			'KMF' => 'Fr',
			'KPW' => '&#x20a9;',
			'KRW' => '&#8361;',
			'KWD' => '&#x62f;.&#x643;',
			'KYD' => '&#36;',
			'KZT' => 'KZT',
			'LAK' => '&#8365;',
			'LBP' => '&#x644;.&#x644;',
			'LKR' => '&#xdbb;&#xdd4;',
			'LRD' => '&#36;',
			'LSL' => 'L',
			'LYD' => '&#x644;.&#x62f;',
			'MAD' => '&#x62f;.&#x645;.',
			'MDL' => 'MDL',
			'MGA' => 'Ar',
			'MKD' => '&#x434;&#x435;&#x43d;',
			'MMK' => 'Ks',
			'MNT' => '&#x20ae;',
			'MOP' => 'P',
			'MRO' => 'UM',
			'MUR' => '&#x20a8;',
			'MVR' => '.&#x783;',
			'MWK' => 'MK',
			'MXN' => '&#36;',
			'MYR' => '&#82;&#77;',
			'MZN' => 'MT',
			'NAD' => '&#36;',
			'NGN' => '&#8358;',
			'NIO' => 'C&#36;',
			'NOK' => '&#107;&#114;',
			'NPR' => '&#8360;',
			'NZD' => '&#36;',
			'OMR' => '&#x631;.&#x639;.',
			'PAB' => 'B/.',
			'PEN' => 'S/',
			'PGK' => 'K',
			'PHP' => '&#8369;',
			'PKR' => '&#8360;',
			'PLN' => '&#122;&#322;',
			'PRB' => '&#x440;.',
			'PYG' => '&#8370;',
			'QAR' => '&#x631;.&#x642;',
			'RMB' => '&yen;',
			'RON' => 'lei',
			'RSD' => '&#x434;&#x438;&#x43d;.',
			'RUB' => '&#8381;',
			'RWF' => 'Fr',
			'SAR' => '&#x631;.&#x633;',
			'SBD' => '&#36;',
			'SCR' => '&#x20a8;',
			'SDG' => '&#x62c;.&#x633;.',
			'SEK' => '&#107;&#114;',
			'SGD' => '&#36;',
			'SHP' => '&pound;',
			'SLL' => 'Le',
			'SOS' => 'Sh',
			'SRD' => '&#36;',
			'SSP' => '&pound;',
			'STD' => 'Db',
			'SYP' => '&#x644;.&#x633;',
			'SZL' => 'L',
			'THB' => '&#3647;',
			'TJS' => '&#x405;&#x41c;',
			'TMT' => 'm',
			'TND' => '&#x62f;.&#x62a;',
			'TOP' => 'T&#36;',
			'TRY' => '&#8378;',
			'TTD' => '&#36;',
			'TWD' => '&#78;&#84;&#36;',
			'TZS' => 'Sh',
			'UAH' => '&#8372;',
			'UGX' => 'UGX',
			'USD' => '&#36;',
			'UYU' => '&#36;',
			'UZS' => 'UZS',
			'VEF' => 'Bs F',
			'VES' => 'Bs.S',
			'VND' => '&#8363;',
			'VUV' => 'Vt',
			'WST' => 'T',
			'XAF' => 'CFA',
			'XCD' => '&#36;',
			'XOF' => 'CFA',
			'XPF' => 'Fr',
			'YER' => '&#xfdfc;',
			'ZAR' => '&#82;',
			'ZMW' => 'ZK',
		)
	);
	$currency_symbol = isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : '';

	return apply_filters( 'wpfb_form_currency_symbol', $currency_symbol, $currency );
}

function wpfb_form_get_paypal_currency_format($form_id){
	$currency_pos = wpfb_form_get_post_meta( '_paypal_currency_position', $form_id, 'left');
	$format       = '%1$s%2$s';

	switch ( $currency_pos ) {
		case 'left':
			$format = '%1$s%2$s';
			break;
		case 'right':
			$format = '%2$s%1$s';
			break;
		case 'left_space':
			$format = '%1$s&nbsp;%2$s';
			break;
		case 'right_space':
			$format = '%2$s&nbsp;%1$s';
			break;
	}

	return apply_filters( 'wpfb_form_paypal_currency_format', $format, $currency_pos );
}

function wpfb_form_get_paypal_currency($form_id){
	$currency = wpfb_form_get_post_meta('_paypal_currency',$form_id,'USD');
	return apply_filters('wpfb_form_paypal_currency', $currency, $form_id);
}

function wpfb_form_price($form_id,$price){
	$price_format = wpfb_form_get_paypal_currency_format($form_id);
	$currency = wpfb_form_get_paypal_currency($form_id);
	return sprintf( $price_format, wpfb_form_get_currency_symbol($currency), $price );
}

function wpfb_form_shortcode_deafult_atts(){
	return array(
		'form_id'=>'',//Form
		'language'=>'',//recaptcha
		'theme'=>'',//recaptcha
		'captcha_type'=>'',//recaptcha
		'rate_option'=>'',//rate
		'order_description'=>'',//paypal
		'item_text'=>'',//paypal
		'qty_text'=>'',//paypal
		'price_text'=>'',//paypal
		'link'=>'', //link
		'link_text'=>'', //link
		'item_list'=>'',//paypal
		'type'=>'',//Datetime, Slider
		'min_date'=>'',//Datetime
		'max_date'=>'',//Datetime
		'min_time'=>'',//Datetime
		'max_time'=>'',//Datetime
		'range_field'=>'',//Date time
		'range_field_step'=>'',////Date time
		'label'=>'',//Submit
		'control_label'=>'',
		'control_name'=>'',
		'default_value'=>'',
		'confirmation'=>'',//Email, password
		'password_field'=>'',//Email, password
		'disabled'=>'',//Select field
		'options_list'=>'',//Select, checkbox, radio
		'option_width'=>'',//Checkbox
		'conditional'=>'',
		'field_type'=>'text',
		'minlength'=>'',
		'maxlength'=>'',
		'minimum_value'=>'',//slider
		'maximum_value'=>'',//slider
		'step'=>'',//slider
		'icon'=>'',
		'icon_align'=>'',//Submit button
		'button_size'=>'',//Submit button
		'is_math_fied'=>'',//Text, Number field
		'hover_animation'=>'',
		'placeholder'=>'',
		'help_text'=>'',
		'required'=>'',
		'readonly'=>'',
		'validator'=>'',
		'attributes'=>'',
		'el_class'=> '',
		'input_css'=>'',
	);
}

function wpfb_form_remove_wpautop($content, $autop = false){
	if ( $autop ) {
		$content = wpautop( preg_replace( '/<\/?p\>/', "\n", $content ) . "\n" );
	}
	return do_shortcode( shortcode_unautop( $content ) );
}

function wpfb_form_is_xhr() {
	if ( ! isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) )
		return false;

	return $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
}

function wpfb_form_fixPContent($content = null){
	if ( $content ) {
		$s = array(
			'/' . preg_quote( '</div>', '/' ) . '[\s\n\f]*' . preg_quote( '</p>', '/' ) . '/i',
			'/' . preg_quote( '<p>', '/' ) . '[\s\n\f]*' . preg_quote( '<div ', '/' ) . '/i',
			'/' . preg_quote( '<p>', '/' ) . '[\s\n\f]*' . preg_quote( '<section ', '/' ) . '/i',
			'/' . preg_quote( '</section>', '/' ) . '[\s\n\f]*' . preg_quote( '</p>', '/' ) . '/i',
		);
		$r = array(
			'</div>',
			'<div ',
			'<section ',
			'</section>',
		);
		$content = preg_replace( $s, $r, $content );
	
		return $content;
	}
	
	return null;
}

function wpfb_form_is_enable_editor_frontend(){
	$post_id = isset($_GET['post']) ? intval($_GET['post']) : (isset($_GET['post_id']) ? intval($_GET['post_id']) : 0);
	$post_type = isset($_GET['post_type']) ? $_GET['post_type'] : '';
	$enable = 'wpfbform' === $post_type || 'wpfbform'===get_post_type($post_id);
	return apply_filters('wpfb_form_is_enable_editor_frontend',$enable);
}

function wpfb_form_allowed_size(){
	return apply_filters('wpfb_form_allowed_size', wp_max_upload_size());
}

function wpfb_form_strip_quote(){
	$text = trim( $text );

	if ( preg_match( '/^"(.*)"$/s', $text, $matches ) ) {
		$text = $matches[1];
	} elseif ( preg_match( "/^'(.*)'$/s", $text, $matches ) ) {
		$text = $matches[1];
	}

	return $text;
}

function wpfb_form_additional_setting( $name, $additional_settings, $max = 1  ) {
	$tmp_settings = (array) explode( "\n", $additional_settings );

	$count = 0;
	$values = array();

	foreach ( $tmp_settings as $setting ) {
		if ( preg_match('/^([a-zA-Z0-9_]+)[\t ]*:(.*)$/', $setting, $matches ) ) {
			if ( $matches[1] != $name )
				continue;

			if ( ! $max || $count < (int) $max ) {
				$values[] = trim( $matches[2] );
				$count += 1;
			}
		}
	}

	return $values;
}

function wpfb_form_strip_newline( $str ) {
	$str = (string) $str;
	$str = str_replace( array( "\r", "\n" ), '', $str );
	return trim( $str );
}

function wpfb_form_shortcode_custom_css_class( $param_value, $prefix = '' ) {
	$css_class = preg_match( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', $param_value ) ? $prefix . preg_replace( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', '$1', $param_value ) : '';

	return $css_class;
}

function wpfb_form_send_email($to, $subject, $message, $headers = '', $attachments = array()){
	if(wpfb_form_get_option('email_method','default') == 'smtp'){
		$ret = wpfb_form_phpmailer($to, $subject, $message, $headers, $attachments);
	}else{
		$ret = wp_mail($to, $subject, $message, $headers, $attachments);
	}
	return $ret;
}

/**
 * Retrieve get PHPMailer object
 * @return PHPMailer
 */
function wpfb_form_phpmailer($to, $subject, $message, $headers = '', $attachments = array()){
	global $phpmailer;

	// (Re)create it, if it's gone missing
	if ( ! ( $phpmailer instanceof PHPMailer ) ) {
		require_once ABSPATH . WPINC . '/class-phpmailer.php';
		require_once ABSPATH . WPINC . '/class-smtp.php';
		$phpmailer = new PHPMailer( true );
	}
	$phpmailer->ClearAllRecipients();
	$phpmailer->ClearAttachments();
	$phpmailer->ClearCustomHeaders();
	$phpmailer->ClearReplyTos();
	$phpmailer->IsMail();
	$phpmailer->IsSMTP();
	$smtp_host = wpfb_form_get_option('smtp_host');
	$smtp_post = wpfb_form_get_option('smtp_post');
	$smtp_username = wpfb_form_get_option('smtp_username');
	$smtp_password = wpfb_form_get_option('smtp_password');
	$smtp_encryption = wpfb_form_get_option('smtp_encryption');
	if (!empty($smtp_host)) {
		$phpmailer->Host = $smtp_host;
	}

	if (!empty($smtp_post)) {
		$phpmailer->Port = $smtp_post;
	}

	if (!empty($smtp_username) && !empty($smtp_password)) {
		$phpmailer->SMTPAuth = true;
		$phpmailer->Username = $smtp_username;
		$phpmailer->Password = $smtp_password;
	}

	if (in_array($smtp_encryption, array('tls', 'ssl'))) {
		$phpmailer->SMTPSecure = $smtp_encryption;
	}


	$atts = apply_filters( 'wpfb_form_phpmailer', compact( 'to', 'subject', 'message', 'headers', 'attachments' ) );

	if ( isset( $atts['to'] ) ) {
		$to = $atts['to'];
	}

	if ( isset( $atts['subject'] ) ) {
		$subject = $atts['subject'];
	}

	if ( isset( $atts['message'] ) ) {
		$message = $atts['message'];
	}

	if ( isset( $atts['headers'] ) ) {
		$headers = $atts['headers'];
	}

	if ( isset( $atts['attachments'] ) ) {
		$attachments = $atts['attachments'];
	}

	if ( ! is_array( $attachments ) ) {
		$attachments = explode( "\n", str_replace( "\r\n", "\n", $attachments ) );
	}

	// Headers
	$cc = $bcc = $reply_to = array();

	if ( empty( $headers ) ) {
		$headers = array();
	} else {
		if ( !is_array( $headers ) ) {
			// Explode the headers out, so this function can take both
			// string headers and an array of headers.
			$tempheaders = explode( "\n", str_replace( "\r\n", "\n", $headers ) );
		} else {
			$tempheaders = $headers;
		}
		$headers = array();

		// If it's actually got contents
		if ( !empty( $tempheaders ) ) {
			// Iterate through the raw headers
			foreach ( (array) $tempheaders as $header ) {
				if ( strpos($header, ':') === false ) {
					if ( false !== stripos( $header, 'boundary=' ) ) {
						$parts = preg_split('/boundary=/i', trim( $header ) );
						$boundary = trim( str_replace( array( "'", '"' ), '', $parts[1] ) );
					}
					continue;
				}
				// Explode them out
				list( $name, $content ) = explode( ':', trim( $header ), 2 );

				// Cleanup crew
				$name    = trim( $name    );
				$content = trim( $content );

				switch ( strtolower( $name ) ) {
					// Mainly for legacy -- process a From: header if it's there
					case 'from':
						$bracket_pos = strpos( $content, '<' );
						if ( $bracket_pos !== false ) {
							// Text before the bracketed email is the "From" name.
							if ( $bracket_pos > 0 ) {
								$from_name = substr( $content, 0, $bracket_pos - 1 );
								$from_name = str_replace( '"', '', $from_name );
								$from_name = trim( $from_name );
							}

							$from_email = substr( $content, $bracket_pos + 1 );
							$from_email = str_replace( '>', '', $from_email );
							$from_email = trim( $from_email );

							// Avoid setting an empty $from_email.
						} elseif ( '' !== trim( $content ) ) {
							$from_email = trim( $content );
						}
						break;
					case 'content-type':
						if ( strpos( $content, ';' ) !== false ) {
							list( $type, $charset_content ) = explode( ';', $content );
							$content_type = trim( $type );
							if ( false !== stripos( $charset_content, 'charset=' ) ) {
								$charset = trim( str_replace( array( 'charset=', '"' ), '', $charset_content ) );
							} elseif ( false !== stripos( $charset_content, 'boundary=' ) ) {
								$boundary = trim( str_replace( array( 'BOUNDARY=', 'boundary=', '"' ), '', $charset_content ) );
								$charset = '';
							}

							// Avoid setting an empty $content_type.
						} elseif ( '' !== trim( $content ) ) {
							$content_type = trim( $content );
						}
						break;
					case 'cc':
						$cc = array_merge( (array) $cc, explode( ',', $content ) );
						break;
					case 'bcc':
						$bcc = array_merge( (array) $bcc, explode( ',', $content ) );
						break;
					case 'reply-to':
						$reply_to = array_merge( (array) $reply_to, explode( ',', $content ) );
						break;
					default:
						// Add it to our grand headers array
						$headers[trim( $name )] = trim( $content );
						break;
				}
			}
		}
	}
	// From email and name
	// If we don't have a name from the input headers
	if ( !isset( $from_name ) )
		$from_name = 'WordPress';

	/* If we don't have an email from the input headers default to wordpress@$sitename
	 * Some hosts will block outgoing mail from this address if it doesn't exist but
	 * there's no easy alternative. Defaulting to admin_email might appear to be another
	 * option but some hosts may refuse to relay mail from an unknown domain. See
	 * https://core.trac.wordpress.org/ticket/5007.
	 */

	if ( !isset( $from_email ) ) {
		// Get the site domain and get rid of www.
		$sitename = strtolower( $_SERVER['SERVER_NAME'] );
		if ( substr( $sitename, 0, 4 ) == 'www.' ) {
			$sitename = substr( $sitename, 4 );
		}

		$from_email = 'wordpress@' . $sitename;
	}

	$from_email = apply_filters( 'wpfb_form_phpmailer_from', $from_email );

	/**
	 * Filters the name to associate with the "from" email address.
	 *
	 * @since 2.3.0
	 *
	 * @param string $from_name Name associated with the "from" email address.
	*/
	$from_name = apply_filters( 'wpfb_form_phpmailer_mail_from_name', $from_name );

	$phpmailer->setFrom( $from_email, $from_name, false );

	// Set destination addresses
	if ( !is_array( $to ) )
		$to = explode( ',', $to );

	// Set mail's subject and body
	$phpmailer->Subject = $subject;
	$phpmailer->Body    = $message;

	// Use appropriate methods for handling addresses, rather than treating them as generic headers
	$address_headers = compact( 'to', 'cc', 'bcc', 'reply_to' );

	foreach ( $address_headers as $address_header => $addresses ) {
		if ( empty( $addresses ) ) {
			continue;
		}

		foreach ( (array) $addresses as $address ) {
			try {
				// Break $recipient into name and address parts if in the format "Foo <bar@baz.com>"
				$recipient_name = '';

				if ( preg_match( '/(.*)<(.+)>/', $address, $matches ) ) {
					if ( count( $matches ) == 3 ) {
						$recipient_name = $matches[1];
						$address        = $matches[2];
					}
				}

				switch ( $address_header ) {
					case 'to':
						$phpmailer->addAddress( $address, $recipient_name );
						break;
					case 'cc':
						$phpmailer->addCc( $address, $recipient_name );
						break;
					case 'bcc':
						$phpmailer->addBcc( $address, $recipient_name );
						break;
					case 'reply_to':
						$phpmailer->addReplyTo( $address, $recipient_name );
						break;
				}
			} catch ( phpmailerException $e ) {
				continue;
			}
		}
	}


	// Set Content-Type and charset
	// If we don't have a content-type from the input headers
	if ( !isset( $content_type ) )
		$content_type = 'text/plain';

	/**
	 * Filters the wp_mail() content type.
	 *
	 * @since 2.3.0
	 *
	 * @param string $content_type Default wp_mail() content type.
	 */
	$content_type = apply_filters( 'wpfb_form_phpmailer_content_type', $content_type );

	$phpmailer->ContentType = $content_type;

	// Set whether it's plaintext, depending on $content_type
	if ( 'text/html' == $content_type )
		$phpmailer->IsHTML( true );

	// If we don't have a charset from the input headers
	if ( !isset( $charset ) )
		$charset = get_bloginfo( 'charset' );

	// Set the content-type and charset

	/**
	 * Filters the default wp_mail() charset.
	 *
	 * @since 2.3.0
	 *
	 * @param string $charset Default email charset.
	*/
	$phpmailer->CharSet = apply_filters( 'wpfb_form_phpmailer_charset', $charset );

	// Set custom headers
	if ( !empty( $headers ) ) {
		foreach ( (array) $headers as $name => $content ) {
			$phpmailer->AddCustomHeader( sprintf( '%1$s: %2$s', $name, $content ) );
		}

		if ( false !== stripos( $content_type, 'multipart' ) && ! empty($boundary) )
			$phpmailer->AddCustomHeader( sprintf( "Content-Type: %s;\n\t boundary=\"%s\"", $content_type, $boundary ) );
	}

	if ( !empty( $attachments ) ) {
		foreach ( $attachments as $attachment ) {
			try {
				$phpmailer->AddAttachment($attachment);
			} catch ( phpmailerException $e ) {
				continue;
			}
		}
	}

	// Send!
	try {
		return $phpmailer->Send();
	} catch ( phpmailerException $e ) {
		return false;
	}

}

function wpfb_form_htmlize_email_body($body,$subject,$context){
	if(!preg_match( '%<html[>\s].*</html>%is', $body )){
		$header = apply_filters( 'wpfb_form_htmlize_email_body_header',
			'<!doctype html>
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<title>' . esc_html( $subject ) . '</title>
	</head>
	<body>
	', $context );
		
		$footer = apply_filters( 'wpfb_form_htmlize_email_body_footer',
			'</body>
	</html>', $context );
		
		$html = $header . wpautop( $body ) . $footer;
		return $html;
	}
	return $body;
}

function wpfb_form_allowed_file_extension(){
	$file_types  = wpfb_form_get_option('allowed_file_extension','zip,rar,tar,7z,jpg,jpeg,png,gif,pdf,doc,docx,ppt,pptx,xls,xlsx');
	$allowed_file_types = array();
	$file_types = explode( ',', $file_types );
	
	foreach ( $file_types as $file_type ) {
		$allowed_file_types[] = $file_type;
	}
	$allowed_file_types = array_unique( $allowed_file_types );
	return $allowed_file_types;
}

function wpfb_form_get_user_ip($checkproxy = true){
	$ip_addr = '';

	if ( isset( $_SERVER['REMOTE_ADDR'] ) && WP_Http::is_ip_address( $_SERVER['REMOTE_ADDR'] ) ) {
		$ip_addr = $_SERVER['REMOTE_ADDR'];
	}
	return apply_filters('wpfb_form_user_ip', $ip_addr);
}

function wpfb_form_find_field($find_type,$_form_id){
	$fields =wpfb_form_get_post_meta('_form_control',$_form_id);
	if(empty($fields))
		return array();

	$output = array();
	foreach ($fields as $field){
		$field_type = isset($field['tag']) ? str_replace('wpfb_form_', '', $field['tag']) : '';
		if($find_type === $field_type){
			$output[] = new Form_Builder_Wp_Field($field);
		}
	}
	return $output;
}

function wpfb_form_antiscript_file_name( $filename ) {
	$filename = basename( $filename );
	$parts = explode( '.', $filename );

	if ( count( $parts ) < 2 ) {
		return $filename;
	}

	$script_pattern = '/^(php|phtml|pl|py|rb|cgi|asp|aspx)\d?$/i';

	$filename = array_shift( $parts );
	$extension = array_pop( $parts );

	foreach ( (array) $parts as $part ) {
		if ( preg_match( $script_pattern, $part ) ) {
			$filename .= '.' . $part . '_';
		} else {
			$filename .= '.' . $part;
		}
	}

	if ( preg_match( $script_pattern, $extension ) ) {
		$filename .= '.' . $extension . '_.txt';
	} else {
		$filename .= '.' . $extension;
	}

	return $filename;
}

function wpfb_form_canonicalize( $text, $strto = 'lower' ) {
	if ( function_exists( 'mb_convert_kana' )
		&& 'UTF-8' == get_option( 'blog_charset' ) ) {
			$text = mb_convert_kana( $text, 'asKV', 'UTF-8' );
		}

		if ( 'lower' == $strto ) {
			$text = strtolower( $text );
		} elseif ( 'upper' == $strto ) {
			$text = strtoupper( $text );
		}

		$text = trim( $text );
		return $text;
}

function wpfb_form_upload_tmp_dir() {
	$tmp_dir = wpfb_form_upload_dir( 'dir' ) . '/uploads/{year}/{month}';
	$tmp_dir = str_replace(array('{year}', '{month}'), array(date('Y'), date('m')), $tmp_dir);
	return $tmp_dir;
}


function wpfb_form_init_uploads() {
	$dir = wpfb_form_upload_tmp_dir();
	return wp_mkdir_p( $dir );
}

function wpfb_form_upload_dir( $type = false ) {
	$uploads = wp_get_upload_dir();
	
	$uploads = apply_filters( 'wpfb_form_upload_dir', array(
		'dir' => $uploads['basedir'].'/wpfbform',
		'url' => $uploads['baseurl'].'/wpfbform',
	) );

	if ( 'dir' == $type ) {
		return $uploads['dir'];
	} if ( 'url' == $type ) {
		return $uploads['url'];
	}

	return $uploads;
}

function wpfb_form_get_messages(){
	$default_messages = apply_filters('wpfb_form_messages',array(
		'success'					=>	__('Thank you for your message. It has been sent.','form-builder-wp'),
		'upload_failed_php_error'	=>__('There was an error uploading the file.','form-builder-wp'),
		'invalid_recaptcha'			=>__('reCaptcha Invalid','form-builder-wp'),
		'recaptcha_not_check'		=>__('Please verify that you are not a robot.','form-builder-wp'),
		'captcha_not_match'			=>__('Your entered code is incorrect.','form-builder-wp'),
		'validation_error'			=>__('One or more fields have an error. Please check and try again.','form-builder-wp'),
		'spam'						=>__('There was an error trying to send your message. Please try again later.','form-builder-wp'),
		'error'						=>__('There was an error trying to send your message. Please try again later.','form-builder-wp'),
		'invalid_required'			=>__("This field is required.",'form-builder-wp'),
		'invalid_email'				=>__("Please enter a valid email address.",'form-builder-wp'),
		'invalid_url'				=>__("Please enter a valid URL.",'form-builder-wp'),
		'invalid_date'				=>__("Please enter a valid date.",'form-builder-wp'),
		'invalid_time'				=>__("Please enter a valid time.",'form-builder-wp'),
		'invalid_number'			=>__("Please enter a valid number.",'form-builder-wp'),
		'invalid_number2'			=>__("Please use only numbers (0-9) or brackets (), dashes – and plus +",'form-builder-wp'),
		'invalid_digits'			=>__("Please enter only digits.",'form-builder-wp'),
		'invalid_max'				=>__("Please enter a value less than or equal to %s.",'form-builder-wp'),
		'invalid_min'				=>__("Please enter a value greater than or equal to %s.",'form-builder-wp'),
		'invalid_too_long'			=>__("This input is too long.",'form-builder-wp'),
		'invalid_too_short'			=>__("This input is too short.",'form-builder-wp'),
		'invalid_alpha'				=>__('Please use letters only (a-z or A-Z) in this field.','form-builder-wp'),
		'invalid_alphanum'			=>__('Please use only letters (a-z or A-Z) or numbers (0-9) only in this field. No spaces or other characters are allowed.','form-builder-wp'),
		'invalid_url'				=>__('Please enter a valid URL. Protocol is required (http://, https:// or ftp://)','form-builder-wp'),
		'invalid_zip'				=>__('Please enter a valid zip code. For example 90602 or 90602-1234.','form-builder-wp'),
		'invalid_fax'				=>__('Please enter a valid fax number. For example (123) 456-7890 or 123-456-7890.','form-builder-wp'),
		'invalid_cpassword'			=>__('Please make sure your passwords match.','form-builder-wp'),
		'invalid_cemail'			=>__('Please make sure your email match.','form-builder-wp'),
		'invalid_select'			=>__('Please select an option','form-builder-wp'),
		'upload_file_type_invalid'	=>__('You are not allowed to upload files of this type.','form-builder-wp'),
		'upload_failed'				=>__('There was an unknown error uploading the file.','form-builder-wp'),
		'upload_file_too_large'		=>__('The file is too big.','form-builder-wp')
	));
	return $default_messages;
}

function wpfb_form_get_message($key,$value=''){
	$submition = Form_Builder_Wp_Submission::get_instance();
	$form_id = $submition->get_form_id();
	if(!empty($form_id) && $messages = wpfb_form_get_post_meta('_wpfb_form_messages',$form_id,array())){
		if(isset($messages[$key])){
			$f_mesg = $messages[$key];
			if(''!= $value)
				return sprintf($f_mesg,$value);
			return $f_mesg;
		}
	}
	$default_messages = wpfb_form_get_messages();
	if(isset($default_messages[$key])){
		$mesg = $default_messages[$key];
		if(''!= $value)
			return sprintf($mesg,$value);
		return $mesg;
	}
	return __('No Message','form-builder-wp');
}

function wpfb_form_get_option($id,$default=null){
	global $wpfb_form_options;

	if ( empty( $wpfb_form_options ) ) {
		$wpfb_form_options = get_option('wpfb_form');
	}
	$value = $default;
	if (isset($wpfb_form_options[$id])) {
		$value =  $wpfb_form_options[$id];
	}
	return apply_filters('wpfb_form_get_option', $value, $id);
}

/**
 * Get IP address for client making current request
 *
 * @return string|null
 */
function wpfb_form_get_request_ip_address() {
    if ( isset( $_SERVER['X-Forwarded-For'] ) ) {
        $ip_address = $_SERVER['X-Forwarded-For'];
    } elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }
    
    if ( isset( $ip_address ) ) {
        if ( ! is_array( $ip_address ) ) {
            $ip_address = explode( ',', $ip_address );
        }
        
        // use first IP in list
        $ip_address = trim( $ip_address[0] );
        
        // if IP address is not valid, simply return null
        if ( ! filter_var( $ip_address, FILTER_VALIDATE_IP ) ) {
            return null;
        }
        
        return $ip_address;
    }
    
    return null;
}

function wpfb_form_get_post_meta($meta, $post_id =null, $default=null){
	$post_id = empty($post_id) ? get_the_ID() : $post_id;
	
	$value = get_post_meta($post_id,$meta, true);
	
	if($value !== null && $value !== array() && $value !== false){
		return apply_filters('wpfb_form_get_post_meta', $value, $meta);
	}
	return apply_filters('wpfb_form_get_post_meta', $default, $meta);
}

function wpfb_form_translate_variable($content,$html=false){
	if ( $submission = Form_Builder_Wp_Submission::get_instance() ) {
		$new_regex = '/(\[?)'
			. '\[([a-zA-Z_][0-9a-zA-Z:._-]*)(?:[\r\n\t ](.*?))?(?:[\r\n\t ](\/))?\]'
				. '(?:([^[]*?)\[\/\2\])?'
					. '(\]?)/';
		if($html)
			$content = preg_replace_callback( $new_regex, 'wpfb_form_email_replace_tag_callback_html', $content );
		else 
			$content = preg_replace_callback( $new_regex, 'wpfb_form_email_replace_tag_callback', $content );
	}
	return apply_filters('wpfb_form_translate_variable', $content);
}

function wpfb_form_email_replace_tag_callback_html($matches){
	return wpfb_form_email_replace_tag_callback($matches,true);
}

function wpfb_form_email_replace_tag_callback($matches,$html=false){
	// allow [[foo]] syntax for escaping a tag
	if ( $matches[1] == '[' && $matches[6] == ']' )
		return substr( $matches[0], 1, -1 );

	$tagname = $matches[2];
	$with_line = $matches[3]=='with_line' ? true : false;
	if ( !$submission = Form_Builder_Wp_Submission::get_instance() ) {
		return $matches[0];
	}
	
	$data = array_merge(
		$submission->get_posted_data(),
		$submission->get_meta(),
		array('form_body'=>wpfb_form_parse_email_body($submission,$html))
	);
	if ( isset( $data[$tagname] ) ) {
		$submitted = $data[$tagname];
		if(!wpfb_form_use_email_empty_field_value() && empty($submitted) ){
			return '';
		}
		if('wpfb_form_file'===$tagname)
			$submitted = $submitted['file_url'];
		$replaced = $submitted;
			
		$output = array();
		foreach ( (array) $replaced as $value )
			$output[] = trim( (string) $value );
			
		$replaced = implode( ', ', $output );
			
		if ( $html ) {
			$replaced = wptexturize( $replaced );
		}
		$replaced = wp_unslash( trim($replaced ));
		if($with_line && $field = $submission->get_form_field($tagname)){
			$label = isset($field['control_label']) ? $field['control_label'] : $tagname;
			return wpfb_form_email_line($label,$replaced,$field,$submission,$html);
		}
		return $replaced;
	}
	
	return $matches[0];
}

function wpfb_form_use_email_empty_field_value(){
	return apply_filters('wpfb_form_use_email_empty_field_value', true);
}

function wpfb_email_ignore_field_name($form_id, $posted_data, $form_fields){
	return apply_filters('wpfb_email_ignore_field_name', array(), $form_id, $posted_data, $form_fields);
}

function wpfb_form_ignore_fields(){
	return apply_filters('wpfb_form_ignore_fields', array(
		'wpfb_form_steps',
		'wpfb_form_recaptcha',
		'wpfb_form_captcha',
		'wpfb_form_paypal',
		'wpfb_form_response',
		'wpfb_form_submit_button'
	));
}

/**
 * 
 * @param Form_Builder_Wp_Submission $context
 * @return string
 */
function wpfb_form_parse_email_body($context,$html=false){
	$email_form_body = '';
	$wpfb_form_use_email_empty_field_value = wpfb_form_use_email_empty_field_value();
	$fields = $context->get_form_fields();
	$data = $context->get_posted_data();
	$ignore_field_by_name = wpfb_email_ignore_field_name($context->get_form_id(), $data, $fields);
	$ignore_field_by_tag = wpfb_form_ignore_fields();
	foreach ($fields as $field){
		$name = isset($field['control_name']) ? $field['control_name'] : '';
		$label = isset($field['control_label']) ? $field['control_label'] : $name;
		$value = isset($data[$name]) ? $data[$name] : '';
		if((in_array($field['tag'], $ignore_field_by_tag)) || (!$wpfb_form_use_email_empty_field_value && (''===$value || false===$value))){
			continue;
		}
		if(in_array($name, $ignore_field_by_name)){
			continue;
		}
		if('wpfb_form_file'===$field['tag']){
			$value = $value['file_url'];
		}
		$email_form_body .= wpfb_form_email_line($label,$value,$field,$context,$html);
	}
	return $email_form_body;
}

function wpfb_form_email_line($label,$value, $field=array(),$context=null,$html=false){
	if(is_array($value))
		$value = implode(',', $value);
	$newline = wpfb_form_email_newline();
	if($html)
		$line = '<strong>'.$label.':</strong> '.$value.$newline;
	else 
		$line = $label.': '.$value.$newline;
	return apply_filters('wpfb_form_email_line', $line, $label, $value, $field, $context, $html);
}

function wpfb_form_email_newline(){
	return apply_filters('wpfb_form_email_newline', "\n");
}

function wpfb_form_add_messages($form_id,$message='',$type='message'){
	$messages[$type] = $message;
	return update_post_meta($form_id, '_wpfb_form_messages', $messages);
}

function wpfb_form_clear_messages($form_id){
	return delete_post_meta($form_id, '_wpfb_form_messages');
}

function wpfb_form_the_messages($form_id){
	$messages = get_post_meta($form_id,'_wpfb_form_messages',true);
	$html = '';
	if(is_array($messages)){
		$html .= '<div class="wpfb-form-message-list">';
		foreach ($messages as $key=>$message){
			$html .= '<div class="'.$key.'">'.$message.'</div>';
		}
		$html .= '</div>';
	}elseif(is_string($messages)){
		$html .= '<span class="">'.$messages.'</span>';
	}
	wpfb_form_clear_messages($form_id);
	return $html;
}

function wpfb_form_get_request_uri() {
	static $wpfb_form_request_uri = '';

	if ( empty( $wpfb_form_request_uri ) ) {
		$wpfb_form_request_uri = add_query_arg( array() );
	}

	return esc_url_raw( $wpfb_form_request_uri );
}

function wpfb_form_get_current_url()
{
	$home_url = untrailingslashit( home_url() );
	$url = preg_replace( '%(?<!:|/)/.*$%', '', $home_url ). wpfb_form_get_request_uri();
	return $url;
}

function wpfb_form_get_http_referer()
{
	if (isset($_SERVER['HTTP_REFERER'])) {
		return $_SERVER['HTTP_REFERER'];
	}
}

function wpfb_form_blacklist_check( $target ) {
	$mod_keys = trim( get_option( 'blacklist_keys' ) );

	if ( empty( $mod_keys ) ) {
		return false;
	}

	$words = explode( "\n", $mod_keys );

	foreach ( (array) $words as $word ) {
		$word = trim( $word );

		if ( empty( $word ) || 256 < strlen( $word ) ) {
			continue;
		}

		$pattern = sprintf( '#%s#i', preg_quote( $word, '#' ) );

		if ( preg_match( $pattern, $target ) ) {
			return true;
		}
	}

	return false;
}

function wpfb_form_array_flatten( $input ) {
	if ( ! is_array( $input ) ) {
		return array( $input );
	}

	$output = array();

	foreach ( $input as $value ) {
		$output = array_merge( $output, wpfb_form_array_flatten( $value ) );
	}

	return $output;
}

function wpfb_form_has_submit_shortcode($form){
	if(wpfb_form_has_shortcode($form, 'wpfb_form_steps'))
		return true;
	return wpfb_form_has_shortcode($form,'wpfb_form_submit_button');
}

function wpfb_form_has_shortcode($form,$shortcode){
	return false !== strpos($form->post_content,$shortcode);
}

function wpfb_form_get_elementor_page_settings($post_id,$key=false){
	global $wpfb_form_elementor_page_settings;
	if(empty($wpfb_form_elementor_page_settings)){
		$wpfb_form_elementor_page_settings = get_post_meta( $post_id, '_elementor_page_settings', true );
	}
	if($key){
		if(isset($wpfb_form_elementor_page_settings[$key]))
			return $wpfb_form_elementor_page_settings[$key];
		return null;
	}
	return $wpfb_form_elementor_page_settings;
}

function wpfb_form_include_editor_template($template, $variables = array(), $once = false){
	is_array($variables) && extract($variables);
	if($once) {
		require_once FORM_BUILDER_WP_PATH .'includes/editor-templates/'.$template;
	} else {
		require FORM_BUILDER_WP_PATH .'includes/editor-templates/'.$template;
	}
}

function wpfb_form_get_actions(){
	$action= array('login','register','forgotten','mailchimp');
	if(defined('FORM_BUILDER_WP_SUPORT_WYSIJA')){
		$action[]='mailpoet';
	}
	if(defined('FORM_BUILDER_WP_SUPORT_MYMAIL')){
		$action[]='mymail';
	}
	if(defined('FORM_BUILDER_WP_SUPORT_GROUNDHOGG')){
		$action[]='groundhogg';
	}
	return $action;
}

function wpfb_form_get_pages($none_field=false){
	$pages = get_pages();
	$options = array();

	if($none_field)
		$options['']=__('Select a page...','form-builder-wp');

	if(!empty($pages)){
		foreach ($pages as $page){
			$options[$page->ID] = $page->post_title;
		}
	}
	return $options;
}

function wpfb_form_get_posts(){
	$posts = get_posts(array('numberposts'=>-1));
	$options = array();
	if(!empty($posts)){
		foreach ($posts as $post){
			$options[$post->ID] = $post->post_title;
		}
	}
	return $options;
}

function wpfb_form_get_mailpoet_subscribers_list($selected=array()){
	$Subscribers_list=array();
	if(defined('FORM_BUILDER_WP_SUPORT_WYSIJA')){
		$model_list = WYSIJA::get('list','model');
		$lists = $model_list->get(array('name', 'list_id', 'is_public'), array('is_enabled' => 1));
		if(is_array($lists) && !empty($lists)){
			foreach ($lists as $list){
				if(!empty($selected) && in_array($list['list_id'], $selected))
					$Subscribers_list[$list['list_id']] = $list['name'];
				else
					$Subscribers_list[$list['list_id']] = $list['name'];
			}
		}
	}
	return $Subscribers_list;
}

function wpfb_form_get_mymail_subscribers_list($selected=array()){
	$Subscribers_list=array();
	if(defined('FORM_BUILDER_WP_SUPORT_MYMAIL')){
		$lists = mymail('lists')->get();
		if(!empty($lists)){
			foreach( $lists as $list){
				if(!empty($selected) && in_array($list->ID, $selected))
					$Subscribers_list[$list->ID] = $list->name;
				else
					$Subscribers_list[$list->ID] = $list->name;
			}
		}
	}
	return $Subscribers_list;
}

function wpfb_form_get_recaptcha_lang(){
	$lang = array(
		'en'=>__('English','form-builder-wp'),
		'pt'=>__('Portuguese','form-builder-wp'),
		'fr'=>__('French','form-builder-wp'),
		'de'=>__('German','form-builder-wp'),
		'nl'=>__('Dutch','form-builder-wp'),
		'ru'=>__('Russian','form-builder-wp'),
		'es'=>__('Spanish','form-builder-wp'),
		'tr'=>__('Turkish','form-builder-wp')
	);
	return $lang;
}

function wpfb_form_get_fields(){
	return apply_filters('wpfb_form_fields', array(
		'wpfb_form_captcha' => array(
			'widget_class'	=>'Form_Builder_Wp_Widget_Captcha',
			'file' => 'captcha.php',
		),
		'wpfb_form_checkbox' => array(
			'widget_class'	=>'Form_Builder_Wp_Widget_Checkbox',
			'file' 	=> 'checkbox.php',
		),
		/* 'wpfb_form_color' => array(
			'widget_class'	=>'Form_Builder_Wp_Widget_Color',
			'file' 	=> 'color.php',
		), */
		/* 'wpfb_form_datetime' => array(
			'widget_class'	=>'Form_Builder_Wp_Widget_Datetime',
			'file' 	=> 'datetime.php',
		), */
		'wpfb_form_email' => array(
			'widget_class'	=>'Form_Builder_Wp_Widget_Email',
			'file' 	=> 'email.php',
		),
		/* 'wpfb_form_file' => array(
			'widget_class'	=>'Form_Builder_Wp_Widget_File',
			'file' 	=> 'file.php',
		), */
		'wpfb_form_hidden' => array(
			'widget_class'	=>'Form_Builder_Wp_Widget_Hidden',
			'file' 	=> 'hidden.php',
		),
		'wpfb_form_label' => array(
			'widget_class'	=>'Form_Builder_Wp_Widget_Label',
			'file' 	=> 'label.php',
		),
		'wpfb_form_link' => array(
			'widget_class'	=>'Form_Builder_Wp_Widget_Link',
			'file' 	=> 'link.php',
		),
		'wpfb_form_password' => array(
			'widget_class'	=>'Form_Builder_Wp_Widget_Password',
			'file' 	=> 'password.php',
		),
		/* 'wpfb_form_multiple_select' => array(
			'widget_class'	=>'Form_Builder_Wp_Widget_Multiple_Select',
			'file' 	=> 'multiple_select.php',
		), */
		/* 'wpfb_form_paypal' => array(
			'widget_class'	=>'Form_Builder_Wp_Widget_Paypal',
			'file' 	=> 'paypal.php',
		), */
		'wpfb_form_radio' => array(
			'widget_class'	=>'Form_Builder_Wp_Widget_Radio',
			'file' 	=> 'radio.php',
		),
		/* 'wpfb_form_rate' => array(
			'widget_class'	=>'Form_Builder_Wp_Widget_Rate',
			'file' 	=> 'rate.php',
		), */
		'wpfb_form_recaptcha' => array(
			'widget_class'	=>'Form_Builder_Wp_Widget_Recaptcha',
			'file' 	=> 'recaptcha.php',
		),
		/* 'wpfb_form_response' => array(
			'widget_class'	=>'Form_Builder_Wp_Widget_Response',
			'file' 	=> 'response.php',
		), */
		'wpfb_form_select' => array(
			'widget_class'	=>'Form_Builder_Wp_Widget_Select',
			'file' 	=> 'select.php',
		),
		/* 'wpfb_form_slider' => array(
			'widget_class'	=>'Form_Builder_Wp_Widget_Slider',
			'file' 	=> 'slider.php',
		), */
// 		'wpfb_form_steps' => array(
// 			'widget_class'	=>'Form_Builder_Wp_Widget_steps',
// 			'file' 	=> 'steps.php',
// 		),
		'wpfb_form_text' => array(
			'widget_class'	=>'Form_Builder_Wp_Widget_Text',
			'file' 	=> 'text.php',
		),
		'wpfb_form_textarea' => array(
			'widget_class'	=>'Form_Builder_Wp_Widget_Textarea',
			'file' 	=> 'textarea.php',
		),
		'wpfb_form_submit_button' => array(
			'widget_class'	=>'Form_Builder_Wp_Widget_Submit_Button',
			'file' 	=> 'submit_button.php',
		),
	));
}


function wpfb_form_get_variables(){
	return apply_filters('wpfb_form_variables', array(
		__('Site URL','form-builder-wp')=>"[site_url]",
		__('User IP','form-builder-wp')=>"[ip_address]",
		__('User display name','form-builder-wp')=>"[user_display_name]",
		__('User email','form-builder-wp')=>"[user_email]",
		__('User login','form-builder-wp')=>"[user_login]",
		__('Form URL','form-builder-wp')=>"[form_url]",
		__('Form ID','form-builder-wp')=>"[form_id]",
		__('Form Title','form-builder-wp')=>"[form_title]",
		__('Post/page ID','form-builder-wp')=>"[post_id]",
		__('Post/page title','form-builder-wp')=>"[post_title]",
		__('Datetime submitted','form-builder-wp')=>"[submitted]",
	));
}

function wpfb_form_get_validation(){
	return apply_filters('wpfb_form_validation', array(
		'wpfb-form-validate-date'		=>__('Date (only date)','form-builder-wp'),
		'wpfb-form-validate-number'		=>__('Number (only number)','form-builder-wp'),
		'wpfb-form-validate-number2'		=>__('Number or brackets (), dashes – and plus +','form-builder-wp'),
		'wpfb-form-validate-digits'		=>__('Digits (only number, avoid spaces or other characters such as dots or commas)','form-builder-wp'),
		'wpfb-form-validate-alpha'		=>__('Alpha (only a-z or A-Z)','form-builder-wp'),
		'wpfb-form-validate-alphanum'	=>__('Alphanum (only a-z or A-Z or 0-9)','form-builder-wp'),
		'wpfb-form-validate-url'			=>__('Url (only URL. Protocol is required: http://, https:// or ftp://)','form-builder-wp'),
		'wpfb-form-validate-zip'			=>__('Zip (example 90602 or 90602-1234)','form-builder-wp'),
		'wpfb-form-validate-fax'			=>__('Fax (example (123) 456-7890 or 123-456-7890)','form-builder-wp'),
			
	));
}

function wpfb_form_is_digits($digits){
	$result = preg_match('/^\\d+$/',$digits);
	return apply_filters('wpfb_form_is_digits', $result, $digits);
}

function wpfb_form_is_date($string,$include_time=false){
	if($include_time){
		$format = wpfb_form_get_option('date_format','Y/m/d').' '.wpfb_form_get_option('time_format','H:i');;
	}else{
		$format = wpfb_form_get_option('date_format','Y/m/d');
	}
	$date = DateTime::createFromFormat($format, $string);
	$date_errors = DateTime::getLastErrors();
	$result = true;
	if ($date_errors['warning_count'] + $date_errors['error_count'] > 0) {
	    $result = false;
	}

	return apply_filters( 'wpfb_form_is_date', $result, $string );
}

function wpfb_form_is_number2($number){
	$result = preg_match('/^(?=.*[0-9])[- +()0-9]+$/', $number);
	return apply_filters('wpfb_form_is_number2', $result, $number);
}

function wpfb_form_is_number($number){
	$result = is_numeric( $number );
	return apply_filters( 'wpfb_form_is_number', $result, $number );
}

function wpfb_form_is_alpha($string){
	$result = preg_match('/^[a-zA-Z]+$/', $string);
	return apply_filters('wpfb_form_is_alpha', $result, $string);
}

function wpfb_form_is_alphanum($string){
	$result = preg_match('/^[a-zA-Z0-9]+$/', $string);
	return apply_filters('wpfb_form_is_alphanum', $result, $string);
}

function wpfb_form_is_url($url){
	$result = ( false !== filter_var( $url, FILTER_VALIDATE_URL ) );
	return apply_filters( 'wpfb_form_is_url', $result, $url );
}

function wpfb_form_is_zip($string){
	$result = preg_match('/(^\\d{5}$)|(^\\d{5}-\\d{4}$)/', $string);
	return apply_filters('wpfb_form_is_zip', $result, $string);
}

function wpfb_form_is_fax($string){
	$result = preg_match('/^(\\()?\\d{3}(\\))?(-|\\s)?\\d{3}(-|\\s)\\d{4}$/', $string);
	return apply_filters('wpfb_form_is_zip', $result, $string);
}

function wpfb_form_is_email($email){
	$result = is_email( $email );
	return apply_filters( 'wpfb_form_is_email', $result, $email );
}