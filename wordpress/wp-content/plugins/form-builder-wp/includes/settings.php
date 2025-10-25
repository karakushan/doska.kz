<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Form_Builder_Wp_Settings{
	public function __construct(){
		add_action('admin_init', array($this,'admin_init'));
		add_action( 'admin_menu', array($this,'admin_menu'),25);
	}
	
	public function admin_init(){
		register_setting('wpfb_form','wpfb_form');
	}
	
	public function admin_menu(){
		$page = add_submenu_page('form-builder-wp',  __('Settings','form-builder-wp'),   __('Settings','form-builder-wp'), 'edit_wpfbforms', 'wpfb-form-setting',array($this,'render'));
	}
	
	protected function _get_setting_fields(){
		return array(
				'general'=>array(
					'type'=>'heading',
					'label'=>__('General settings','form-builder-wp'),
				),
				'allowed_file_extension'=>array(
					'type'=>'textarea',
					'default'=>'zip,rar,tar,7z,jpg,jpeg,png,gif,pdf,doc,docx,ppt,pptx,xls,xlsx',
					'label'=>__('Allowed Files Upload Types','form-builder-wp'),
					'help'=>__('Which files are allowed in the attachments? (Separate the extensions by a comma)','form-builder-wp'),
				),
				'date_format'=>array(
					'type'=>'text',
					'label'=>__('Date Format','form-builder-wp'),
					'default'=>'Y/m/d',
				),
				'time_format'=>array(
					'type'=>'text',
					'label'=>__('Time Format','form-builder-wp'),
					'default'=>'H:i',
					'help'=>sprintf('<a href="http://codex.wordpress.org/Formatting_Date_and_Time">%s</a>',__('Documentation on date and time formatting','form-builder-wp'))
				),
				'time_picker_step'=>array(
					'type'=>'select',
					'label'=>__('Time picker step','form-builder-wp'),
					'default'=>'60',
					'options'=>array(
						'5'=>5,
						'10'=>10,
						'15'=>15,
						'30'=>30,
						'60'=>60,
					),
				),
				'datetimepicker_lang'=>array(
					'type'=>'select',
					'label'=>__('Datetime Picker Language','form-builder-wp'),
					'default'=>'en',
					'options'=>apply_filters('datetimepicker_lang',array(
						'ar'=>'Arabic (ar)',
						'az'=>'Azerbaijanian (az)',
						'bg'=>'Bulgarian (bg)',
						'bs'=>'Bosanski (bs)',
						'ca'=>'Català (ca)',
						'ch'=>'Simplified Chinese (ch)',
						'cs'=>'Čeština (cs)',
						'da'=>'Dansk (da)',
						'de'=>'German (de)',
						'el'=>'Ελληνικά (el)',
						'en'=>'English (en)',
						'en-GB'=>'English - British  (en-GB)',
						'es'=>'Spanish (es)',
						'et'=>'Eesti  (et)',
						'eu'=>'Euskara (eu)',
						'fa'=>'Persian (fa)',
						'fi'=>'Finnish - Suomi (fi)',
						'fr'=>'French (fr)',
						'gl'=>'Galego (gl)',
						'he'=>'Hebrew - עברית  (he)',
						'hr'=>'Hrvatski (hr)',
						'hu'=>'Hungarian (hu)',
						'id'=>'Indonesian (id)',
						'it'=>'Italian (it)',
						'ja'=>'Japanese (ja)',
						'ko'=>'Korean 한국어  (ko)',
						'kr'=>'Korean (kr)',
						'lt'=>'Lithuanian - lietuvių  (lt)',
						'lv'=>'Latvian - Latviešu (lv)',
						'mk'=>'Macedonian - Македонски (mk)',
						'mn'=>'Mongolian - Монгол  (mn)',
						'nl'=>'Dutch (nl)',
						'no'=>'Norwegian (no)',
						'pl'=>'Polish (pl)',
						'pt'=>'Portuguese (pt)',
						'pt-BR'=>'Português - Brasil  (pt-BR)',
						'ro'=>'Romanian (ro)',
						'ru'=>'Russian (ru)',
						'se'=>'Swedish (se)',
						'sk'=>'Slovenčina (sk)',
						'sl'=>'Slovenščina (sl)',
						'sq'=>'Albanian - Shqip (sq)',
						'sr'=>'Serbian Cyrillic - Српски (sr)',
						'sr-YU'=>'Serbian - Srpski  (sr-YU)',
						'sv'=>'Svenska (sv)',
						'th'=>'Thai (th)',
						'tr'=>'Turkish (tr)',
						'uk'=>'Ukrainian (uk)',
						'vi'=>'Vietnamese (vi)',
						'zh'=>'Simplified Chinese - 简体中文  (zh)',
						'zh-TW'=>'Traditional Chinese - 繁體中文  (zh-TW)',
					)),
				),
				'container_class'=>array(
					'type'=>'text',
					'label'=>__('Conditional Container Element','form-builder-wp'),
					'default'=>'.elementor-column-wrap',
				),
				'user'=>array(
						'type'=>'heading',
						'label'=>__('Users page settings','form-builder-wp'),
				),
				'user_login'=>array (
						"type" => "select",
						"label" => __ ( "Login page", 'form-builder-wp' ),
						"options" => wpfb_form_get_pages(true),
				),
				'user_logout_redirect_to'=>array (
						"type" => "select",
						"label" => __ ( "Logout redirect to page", 'form-builder-wp' ),
						"options" => wpfb_form_get_pages(true),
				),
				'user_regiter'=>array (
						"type" => "select",
						"label" => __ ( "Register page", 'form-builder-wp' ),
						"options" => wpfb_form_get_pages(true),
				),
				'user_forgotten'=>array (
						"type" => "select",
						"label" => __ ( "Lost password page", 'form-builder-wp' ),
						"options" => wpfb_form_get_pages(true),
				),
				'email'=>array(
						'type'=>'heading',
						'label'=>__('Email settings','form-builder-wp'),
				),
				'email_method'=>array(
						'type'=>'select',
						'label'=>__('Sender method','form-builder-wp'),
						'default'=>'default',
						'options'=>array(
							'default'=>__('PHP Mailer','form-builder-wp'),
							'smtp'=>__('SMTP','form-builder-wp')
						)
				),
				'smtp_host'=>array(
						'type'=>'text',
						'label'=>__('SMTP host','form-builder-wp'),
				),
				'smtp_post'=>array(
						'type'=>'text',
						'value'=>25,
						'label'=>__('SMTP port','form-builder-wp'),
				),
				'smtp_encryption'=>array(
						'type'=>'select',
						'label'=>__('SMTP encryption','form-builder-wp'),
						'options'=>array(
							''=>__('None','form-builder-wp'),
							'tls'=>__('TLS','form-builder-wp'),
							'ssl'=>__('SSL','form-builder-wp')
						),
				),
				'smtp_username'=>array(
						'type'=>'text',
						'label'=>__('SMTP username','form-builder-wp'),
				),
				'smtp_password'=>array(
						'type'=>'password',
						'label'=>__('SMTP password','form-builder-wp'),
				),
				'recaptcha'=>array(
						'type'=>'heading',
						'label'=>__('reCaptcha settings','form-builder-wp'),
						'help'=>__('In order to use the reCAPTCHA element in your form you must <a target="_blank" href="https://www.google.com/recaptcha">sign up</a> for a free account to get your set of API keys.','form-builder-wp'),
				),
				'recaptcha_public_key'=>array(
						'type'=>'text',
						'label'=>__('Public key (Site Key)','form-builder-wp'),
				),
				'recaptcha_private_key'=>array(
						'type'=>'text',
						'label'=>__('Private key (Secret Key)','form-builder-wp'),
				),
				'mailchimp'=>array(
						'type'=>'heading',
						'label'=>__('MailChimp settings','form-builder-wp'),
				),
				'mailchimp_api'=>array(
						'type'=>'text',
						'label'=>__('MailChimp API Key','form-builder-wp'),
						'help'=>__('Enter your API Key. <a href="http://admin.mailchimp.com/account/api-key-popup" target="_blank">Get your API key</a>','form-builder-wp')
				),
				'mailchimp_list'=>array(
						'type'=>'mailchimp_list',
						'label'=>__('MailChimp List','form-builder-wp'),
						'options'=>array(''=>__('Nothing Found...','form-builder-wp')),
						'help'=>__('After you add your MailChimp API Key above and save it this list will be populated.','form-builder-wp')
				),
				'mailchimp_opt_in'=>array(
						'type'=>'checkbox',
						'label'=>__('Enable Double Opt-In','form-builder-wp'),
						'help'=>__("Learn more about <a href='http://kb.mailchimp.com/article/how-does-confirmed-optin-or-double-optin-work' target='_blank'>Double Opt-in</a>.",'form-builder-wp')
				),
				'mailchimp_update_existing'=>array(
    		        'type'=>'checkbox',
    		        'default' => '1',
    		        'label'=>__('Update existing subscribers?','form-builder-wp'),
    		        'help'=>__("Enable if you want to update existing subscribers with the data that is sent.",'form-builder-wp')
    		    ),
				'mailchimp_replace_interests'=>array(
						'type'=>'checkbox',
						'label'=>__('Replace Interests','form-builder-wp'),
						'help'=>__("Whether MailChimp will replace the interest groups with the groups provided or add the provided groups to the member's interest groups.",'form-builder-wp')
				),
				'mailchimp_tags'=>array(
    		        'type'=>'text',
    		        'label'=>__('Subscriber tags','form-builder-wp'),
    		        'placeholder' => 'Example: Tag 1, Tag 2',
    		        'help'=>__('The listed tags will be applied to all subscribers added or updated by this form. Separate multiple values with a comma.','form-builder-wp')
    		    ),
				
		);
	}
	
	public function render(){
		?>
		<div class="wrap">
			<h2><?php echo __('Settings','form-builder-wp')?></h2>
			<form action="options.php" method="post">	
				<?php settings_fields('wpfb_form'); ?>
				<table class="form-table">
					<tbody>
						<?php 
						foreach ($this->_get_setting_fields() as $id=>$params): 
						$params = wp_parse_args((array)$params,array(
								'type'=>'',
								'help'=>'',
								'label'=>'',
								'default'=>'',
								'options'=>array()
						));
		
						extract($params);
						?>
						<tr valign="top">
							<?php if($type=='heading'):?>
							<td colspan="2" style="padding: 0;">
								<h3 style="margin-bottom: 0px;"><?php echo $label ?></h3>
								<p><?php echo $help?></p></td>
							<?php else:?>
								<th scope="row"><label for="<?php echo $id ?>"><?php echo $label ?></label></th>
								<?php $this->_render_seting_field($id, $params);?>
							<?php endif;?>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
				<p class="submit">
					<input type="submit" value="<?php echo __('Save Changes','form-builder-wp') ?>" class="button button-primary" id="submit" name="submit">
				</p>
			</form>
		</div>
		<?php
	}
	
	protected function _render_seting_field($id,$params){
		$params = wp_parse_args((array)$params,array(
			'type'=>'',
			'help'=>'',
			'label'=>'',
			'default'=>'',
			'help' =>'',
			'options'=>array()
		));
	
		extract($params,EXTR_SKIP);
	
		$name = 'wpfb_form['.$id.']';
	
		echo '<td scope="row">';
		switch ($type){
			case 'text':
				echo '<input type="text" id="'.$id.'" value="'.wpfb_form_get_option($id,$default).'" name="'.$name.'" />';
				if(!empty($help)){
					echo '<p>'.$help.'</p>';
				}
				break;
			case 'textarea':
				echo '<textarea id="'.$id.'" name="'.$name.'" style=" height: 99px;width: 441px;">'.esc_textarea(wpfb_form_get_option($id,$default)).'</textarea>';
				if(!empty($help)){
					echo '<p>'.$help.'</p>';
				}
				break;
			case 'password':
				echo '<input type="password" id="'.$id.'" value="'.wpfb_form_get_option($id,$default).'" name="'.$name.'" />';
				if(!empty($help)){
					echo '<p>'.$help.'</p>';
				}
				break;
			case 'checkbox':
				echo '<input type="checkbox" id="'.$id.'" '.(wpfb_form_get_option($id,$default) == '1' ? ' checked="checked"' : '' ).' value="1" name="'.$name.'">';
				if(!empty($help)){
					echo '<p>'.$help.'</p>';
				}
				break;
			case 'color':
				echo '<input data-default-color="#336CA6" type="text" id="'.$id.'" value="'.wpfb_form_get_option($id,$default).'" name="'.$name.'" />';
				echo '<script type="text/javascript">
								jQuery(document).ready(function($){
								    $("#'.$id.'").wpColorPicker();
								});
							 </script>
							 ';
				break;
				if(!empty($help)){
					echo '<p>'.$help.'</p>';
				}
			case 'select':
				echo '<select id="'.$id.'" name="'.$name.'">';
				foreach ($options as $key=>$value){
					$selected = wpfb_form_get_option($id,$default) == $key ? ' selected="selected"' : '';
					echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
				}
				echo '</select>';
				if(!empty($help)){
					echo '<p>'.$help.'</p>';
				}
				break;
			case 'mailchimp_list':
				echo '<select id="'.$id.'" name="'.$name.'">';
				$options = [];
				if($mailchimp_api = wpfb_form_get_option('mailchimp_api',false)){
					$options = wpfb_form_get_mailchimp_list($mailchimp_api);
				}
				foreach ($options as $key=>$value){
					$selected = wpfb_form_get_option($id,$default) == $key ? ' selected="selected"' : '';
					echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
				}
				echo '</select>';
				if(!empty($help)){
					echo '<p>'.$help.'</p>';
				}
				break;
			default:
				break;
		}
		echo '</td>';
	}
}
new Form_Builder_Wp_Settings();