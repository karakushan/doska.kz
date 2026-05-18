<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Form_Builder_Wp_Metabox {
	public function __construct(){
		add_action( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ), 1000 );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
		add_action ( 'save_post', array ($this,'save_meta_boxes' ), 1, 2 );
	}
	
	public function remove_meta_boxes(){
		remove_meta_box( 'vc_teaser', 'wpfbform' , 'side' );
		remove_meta_box( 'commentsdiv', 'wpfbform' , 'normal' );
		remove_meta_box( 'commentstatusdiv', 'wpfbform' , 'normal' );
		remove_meta_box( 'slugdiv', 'wpfbform' , 'normal' );
		remove_meta_box( 'mymetabox_revslider_0', 'wpfbform', 'normal');
		remove_meta_box( 'pageparentdiv', 'wpfbform', 'side');
	}
	
	public function save_meta_boxes($post_id, $post){
		// $post_id and $post are required
		if ( empty( $post_id ) || empty( $post ) ) {
			return;
		}
		
		// Dont' save meta boxes for revisions or autosaves
		if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
			return;
		}
		
		// Check the post being saved == the $post_id to prevent triggering this call for other save_post events
		if ( empty( $_POST['post_ID'] ) || $_POST['post_ID'] != $post_id ) {
			return;
		}
		
		// Check user has permission to edit
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		// Check the post type
		if ('wpfbform'!==$post->post_type ) {
			return;
		}
		$sections = array(
			'_general_setting',
			'_mail_setting',
			'_advanced_setting',
			'_notifications_setting',
			'_payment_setting'
		);
		foreach ($sections as $item){
			$call_fnc = $item;
			if(is_callable(array($this,$call_fnc))){
				$settings = call_user_func(array($this,$call_fnc));
				foreach ($settings as $meta_box){
					if(isset($meta_box['name'])){
						$meta_name = false !== strpos($meta_box['name'], 'wpfb_form_messages') ? 'wpfb_form_messages': $meta_box['name'];
						$meta_value = isset($_POST[$meta_name]) ? $_POST[$meta_name] : null;
						if(is_array($meta_value)){
							$meta_value = array_map( 'sanitize_text_field', (array) $meta_value ) ;
							if(false === strpos($meta_box['name'], 'wpfb_form_messages'))
								$meta_value = array_filter($meta_value);
						}
						if (empty( $meta_value ) && false === strpos($meta_box['name'], 'wpfb_form_messages') ) {
							delete_post_meta( $post_id, '_'.$meta_name );
						} elseif($meta_value !== null) {
							update_post_meta( $post_id, '_'.$meta_name , $meta_value );
						}
					}
				}
			}
		}
		
	}
	
	protected function _get_form_acition_options(){
		$actions = wpfb_form_get_actions();
		$options = array('');
		foreach ($actions as $action){
			$options[$action] = ucfirst($action);
		}
		return $options;
	}
	
	public function add_meta_boxes(){
		add_meta_box( 'wpfbform-general', __( 'General Settings', 'form-builder-wp' ), array($this,'render_general_settings'), 'wpfbform', 'side', 'default' );
		add_meta_box( 'wpfbform-mail', __( 'Mail Settings', 'form-builder-wp' ), array($this,'render_mail_settings'), 'wpfbform', 'side', 'default' );
		add_meta_box( 'wpfbform-advanced', __( 'Advanced Settings', 'form-builder-wp' ), array($this,'render_avanced_settings'), 'wpfbform', 'side', 'default' );
		add_meta_box( 'wpfbform-notifications', __( 'Notifications Settings', 'form-builder-wp' ), array($this,'render_notifications_settings'), 'wpfbform', 'side', 'default' );
		add_meta_box( 'wpfbform-payments', __( 'Payment Settings', 'form-builder-wp' ), array($this,'render_payment_settings'), 'wpfbform', 'side', 'default' );
	}
	
	private function _general_setting(){
		$settings = array(
			array (
				"type" => "heading",
				"label"=>__('General','form-builder-wp')
			),
			array (
				"type" => "checkbox",
				"label" => __ ( "Save Submitted Form to Data ?", 'form-builder-wp' ),
				"name" => "save_data",
				"cbvalue" =>1,
				'description' => __('If checked, the submitted form data will be saved to your database.','form-builder-wp')
			),
			array (
				"type" => "select",
				"label" => __ ( "Action Type", 'form-builder-wp' ),
				"name" => "action_type",
				"options" => array (
					'default'=>__ ( 'Default', 'form-builder-wp' ),
					'external_url'=>__ ( 'External URL', 'form-builder-wp' )
				)
			),
			array (
				"type" => "text",
				"label" => __ ( "Enter URL", 'form-builder-wp' ),
				"name" => "action_url",
				"dependency" => array ('element' => "action_type",'value' => array ('external_url')),
				'description' => __('Enter a action URL.','form-builder-wp')
			),
			array (
				"type" => "select",
				"label" => __ ( "Use form action", 'form-builder-wp' ),
				"name" => "form_action",
				"options"=>$this->_get_form_acition_options()
			),
			array (
		        "type" => "mailchimp_list",
		        "label" => __ ( "MailChimp List", 'form-builder-wp' ),
		        "name" => "mailchimp_list",
		        "dependency" => array ('element' => "form_action",'value' => array ('mailchimp')),
		        'description' => __('If not select a list. The form will use default Mailchimp list in Form Settings','form-builder-wp')
		    ),
		    array (
		        "type" => "text",
		        "label" => __ ( "MailChimp Tags", 'form-builder-wp' ),
		        "name" => "mailchimp_tags",
		        "dependency" => array ('element' => "form_action",'value' => array ('mailchimp')),
		        'placeholder' => 'Example: Tag 1, Tag 2',
		        'description' => __('The listed tags will be applied to all subscribers added or updated by this form. Separate multiple values with a comma.','form-builder-wp')
		    )
		);
		if(defined('FORM_BUILDER_WP_SUPORT_WYSIJA')){
			$settings[] = array (
				"type" => "checklist",
				"label" => __ ( "Mailpoet subscribers to These Lists", 'form-builder-wp' ),
				"name" => "mailpoet",
				"options" => wpfb_form_get_mailpoet_subscribers_list(),
			);
		}
		if(defined('FORM_BUILDER_WP_SUPORT_MYMAIL')){
			$settings[] = array (
				"type" => "checklist",
				"label" => __ ( "Mymail subscribers to These Lists", 'form-builder-wp' ),
				"name" => "mymail",
				"options" => wpfb_form_get_mymail_subscribers_list(),
			);
			$settings[] = array (
				"type" => "checkbox",
				"label" => __ ( "Mymail Double Opt In ", 'form-builder-wp' ),
				"name" => "mymail_double_opt_in",
				'description'=>__('Users have to confirm their subscription','form-builder-wp'),
				"cbvalue" =>1
			);
		}
		if(defined('FORM_BUILDER_WP_SUPORT_GROUNDHOGG')){
			$settings[] = array (
				"type" => "checklist",
				"label" => __ ( "Apply Groundhogg Tags", 'form-builder-wp' ),
				"name" => "groundhogg_tags",
				"options" =>WPGH()->tags->get_tags_select(),
				'description'=>__('Once a contact is created this tag will be applied.','form-builder-wp')
			);
		}
		return array_merge($settings, array(
			array (
				"type" => "select",
				"label" => __ ( "Method", 'form-builder-wp' ),
				"name" => "method",
				"options" => array (
					'post'=>__ ( 'Post', 'form-builder-wp' ),
					'get'=>__ ( 'Get', 'form-builder-wp' )
				)
			),
			array (
				"type" => "heading",
				"label"=>__('Successful submit settings','form-builder-wp')
			),
			array (
				"type" => "select",
				"label" => __ ( "On successful submit", 'form-builder-wp' ),
				"name" => "on_success",
				"options" => array (
					'message'=>__ ( 'Display a message', 'form-builder-wp' ),
					'redirect'=>__ ( 'Redirect to another page', 'form-builder-wp' ),
					'refresh'=>__ ( 'Refresh current page', 'form-builder-wp' ),
				)
			),
			array (
				"type" => "textarea_variable",
				"label" => __ ( "Message", 'form-builder-wp' ),
				"name" => "message",
				"value"=>'Your message has been sent. Thanks!',
				"dependency" => array ('element' => "on_success",'value' => array ('message')),
				'description' =>  __('This is the text or HTML that is displayed when the form is successfully submitted','form-builder-wp')
			),
			array (
				"type" => "select",
				"label" => __ ( "Message Position", 'form-builder-wp' ),
				"name" => "message_position",
				'description' =>  __('You can use "Form Response" shortcode to locating response message box anywhere','form-builder-wp'),
				"options"=>array(
					'top'=>__('Top','form-builder-wp'),
					'bottom'=>__('Bottom','form-builder-wp')
				),
			),
			array (
				"type" => "select",
				"label" => __ ( "Redirect to", 'form-builder-wp' ),
				"name" => "redirect_to",
				"dependency" => array ('element' => "on_success",'value' => array ('redirect')),
				"options" => array (
					'to_page'=>__ ( 'Page', 'form-builder-wp' ),
					'to_post'=>__ ( 'Post', 'form-builder-wp' ),
					'to_url'=>__ ( 'Url', 'form-builder-wp' ),
				),
				"description"=>__('When the form is successfully submitted you can redirect the user to post, page or URL.','form-builder-wp'),
			),
			array (
				"type" => "select",
				"label" => __ ( "Select page", 'form-builder-wp' ),
				"name" => "page",
				"options" => wpfb_form_get_pages(),
				"dependency" => array ('element' => "redirect_to",'value' => array ('to_page')),
			),
			array (
				"type" => "select",
				"label" => __ ( "Select post", 'form-builder-wp' ),
				"name" => "post",
				"options" => wpfb_form_get_posts(),
				"dependency" => array ('element' => "redirect_to",'value' => array ('to_post')),
			),
			array (
				"type" => "text",
				"label" => __ ( "Enter URL", 'form-builder-wp' ),
				"name" => "url",
				"dependency" => array ('element' => "redirect_to",'value' => array ('to_url')),
			),
				
			array (
				"type" => "heading",
				"label"=>__('Form popup settings','form-builder-wp')
			),
			array (
				"type" => "checkbox",
				"label" => __ ( "Display the form in a popup ?", 'form-builder-wp' ),
				"name" => "form_popup",
				"cbvalue" =>1
			),
			array (
				"type" => "labelpopup",
				"name" => 'form_popup_labelpopup',
				"label" => __ ('Set data-toggle="wpfbformpopup" on a controller element, like a button, along with a data-target="#wpfbformpopup-{form_ID}" or href="#wpfbformpopup-{form_ID}" to target a specific form popup to toggle.', 'form-builder-wp' ),
			),
			array (
				"type" => "checkbox",
				"label" => __ ( "Show popup title ?", 'form-builder-wp' ),
				"name" => "form_popup_title",
				"cbvalue" =>1
			),
			array (
				'type' => 'text',
				'label' => __ ( 'Form popup width (px)', 'form-builder-wp' ),
				'name' => 'form_popup_width',
				'value'=>600,
			),
			array (
				"type" => "checkbox",
				"label" => __ ( "Auto open popup ?", 'form-builder-wp' ),
				"name" => "form_popup_auto_open",
				"cbvalue" =>1,
				"description"=>__('If selected, form popup will auto open when load page.','form-builder-wp'),
			),
			array (
				'type' => 'text',
				'label' => __ ( 'Popup open delay (ms)', 'form-builder-wp' ),
				'name' => 'form_popup_auto_open_delay',
				'value'=>2000,
				"description"=>__('Time delay for open popup.','form-builder-wp'),
			),
			array (
				"type" => "checkbox",
				"label" => __ ( "Auto close popup ?", 'form-builder-wp' ),
				"name" => "form_popup_auto_close",
				"cbvalue" =>1,
				"description"=>__('If selected, form popup will auto close.','form-builder-wp'),
			),
			array (
				'type' => 'text',
				'label' => __ ( 'Popup close delay (ms)', 'form-builder-wp' ),
				'name' => 'form_popup_auto_close_delay',
				'value'=>10000,
				"description"=>__('Time delay for close popup.','form-builder-wp'),
			),
			array (
				"type" => "checkbox",
				"label" => __ ( "Only one time ?", 'form-builder-wp' ),
				"name" => "form_popup_one",
				"cbvalue" =>1,
				"description"=>__('If selected,form will opens only on the first visit your site.','form-builder-wp'),
			),
		) );

	}
	
	private function _mail_setting(){
		return array(
			array (
				"type" => "heading",
				"label"=>__('Notifications email settings','form-builder-wp')
			),
			array (
				"type" => "checkbox",
				"label" => __ ( "Send form data via email ?", 'form-builder-wp' ),
				"name" => "notice",
				"cbvalue" =>1
			),
			array (
				'type' => 'text',
				'label' => __ ( 'Sender Name', 'form-builder-wp' ),
				'name' => 'notice_name',
				'value'=>get_bloginfo('name'),
				"dependency" => array ('element' => "notice",'not_empty' => true),
			),
			array (
				'type' => 'select',
				'label' => __ ( 'Sender Email Type', 'form-builder-wp' ),
				'name' => 'notice_email_type',
				'value'=>'email_text',
				'options'=>array(
					'email_text'=>__ ( 'Email', 'form-builder-wp' ),
					'email_field'=>__ ( 'Email Field', 'form-builder-wp' ),
				),
				"dependency" => array ('element' => "notice",'not_empty' => true),
			),
			array (
				'type' => 'text',
				'label' => __ ( 'Sender Email', 'form-builder-wp' ),
				'name' => 'notice_email',
				'value'=>get_bloginfo('admin_email'),
				"dependency" => array ('element' => "notice",'not_empty' => true),
			),
			array (
				'type' => 'select_recipient',
				'label' => __ ( 'Sender Field', 'form-builder-wp' ),
				'name' => 'notice_variables',
				"description"=>__('The form must have at least one Email Address element to use this feature.','form-builder-wp')
			),
			array (
				'type' => 'recipient',
				'label' => __ ( 'Recipients', 'form-builder-wp' ),
				'name' => 'notice_recipients',
				'value'=>get_bloginfo('admin_email'),
				"dependency" => array ('element' => "notice",'not_empty' => true),
				"description"=>__('Add email address(es) which the submitted form data will be sent to.','form-builder-wp')
			),
			array (
				'type' => 'select_recipient',
				'label' => __ ( 'Reply To', 'form-builder-wp' ),
				'name' => 'notice_reply_to',
				"description"=>__('The form must have at least one Email Address element to use this feature.','form-builder-wp')
			),
			array (
				'type' => 'input_variable',
				'label' => __ ( 'Email subject', 'form-builder-wp' ),
				'name' => 'notice_subject',
				"dependency" => array ('element' => "notice",'not_empty' => true),
				'value'=>__('New form submission','form-builder-wp')
			),
			array (
				'type' => 'textarea_variable',
				'label' => __ ( 'Email body', 'form-builder-wp' ),
				'name' => 'notice_body',
				'value'=>'[form_body]',
				"description"=>__("Use the label [form_body] to insert the form data in the email body. To use form control in email. please enter form control variables <strong>[form_control_name]</strong> in email.",'form-builder-wp')
			),
			array (
				"type" => "checkbox",
				"label" => __ ( "Use HTML content type ?", 'form-builder-wp' ),
				"name" => "notice_html",
				"cbvalue" =>1
			),
			array (
				"type" => "heading",
				"label"=>__('Autoreply email settings','form-builder-wp')
			),
			array (
				"type" => "checkbox",
				"label" => __ ( "Send autoreply email ?", 'form-builder-wp' ),
				"name" => "reply",
				"cbvalue" => 1
			),
			array (
				'type' => 'text',
				'label' => __ ( 'Sender Name', 'form-builder-wp' ),
				'name' => 'reply_name',
				'value'=>get_bloginfo('name'),
				"dependency" => array ('element' => "reply",'not_empty' => true),
			),
			array (
				'type' => 'text',
				'label' => __ ( 'Sender Email', 'form-builder-wp' ),
				'name' => 'reply_email',
				'value'=>get_bloginfo('admin_email'),
				"dependency" => array ('element' => "reply",'not_empty' => true),
			),
			array (
				'type' => 'select_recipient',
				'label' => __ ( 'Recipients', 'form-builder-wp' ),
				'name' => 'reply_recipients',
				"description"=>__('The form must have at least one Email Address element to use this feature.','form-builder-wp')
			),
			array (
				'type' => 'input_variable',
				'label' => __ ( 'Email subject', 'form-builder-wp' ),
				'name' => 'reply_subject',
				"dependency" => array ('element' => "reply",'not_empty' => true),
				'value'=>__('Just Confirming','form-builder-wp')
			),
			array (
				'type' => 'textarea_variable',
				'label' => __ ( 'Email body', 'form-builder-wp' ),
				'name' => 'reply_body',
				"dependency" => array ('element' => "reply",'not_empty' => true),
				'value'=>__('This is just a confirmation message. We have received you reply.','form-builder-wp'),
				"description"=>__("Use the label [form_body] to insert the form data in the email body. To use form control in email. please enter form control variables <strong>[form_control_name]</strong> in email.",'form-builder-wp')
			),
			array (
				"type" => "checkbox",
				"label" => __ ( "Use HTML content type ?", 'form-builder-wp' ),
				"name" => "reply_html",
				"cbvalue" =>1
			),
		);
	}
	
	private function _advanced_setting(){
		return array(
			array (
				"type" => "heading",
				"label"=>__('Additional settings','form-builder-wp')
			),
			array(
				'type'=>'textarea',
				'label'=>__('Additional Settings','form-builder-wp'),
				"description"=>__('Trigger with form AJAX.','form-builder-wp'),
				'name'=>'additional_setting'
			),
		);
	}
	
	private function _notifications_setting(){
		$default_messages = wpfb_form_get_messages();
		$settings = array();
		$settings[] = array (
			"type" => "heading",
			"label"=>__('Messagess settings','form-builder-wp')
		);
		foreach ($default_messages as $key=>$message){
			$label = 'On '.ucwords(implode(' ', explode('_', $key)));
			$settings[] = array (
				'type' => 'text',
				'label' => $label,
				'name' =>'wpfb_form_messages['.$key.']',
				'value'=>$message
			);
		}
		return $settings;
	}
	
	private function _payment_setting(){
		return array(
			array (
				"type" => "heading",
				"label"=>__('PayPal Settings','form-builder-wp')
			),
			array (
				'type' => 'text',
				'label' => __ ( 'PayPal Email', 'form-builder-wp' ),
				'name' => 'paypal_email',
			),
			array (
				'type' => 'select',
				'label' => __ ( 'PayPal Currency', 'form-builder-wp' ),
				'name' => 'paypal_currency',
				'value'=>'USD',
				'options'=>wpfb_form_get_currencies()
			),
			array (
				'type' => 'select',
				'label' => __ ( 'Currency position', 'form-builder-wp' ),
				'name' => 'paypal_currency_position',
				'value'=>'left',
				'options'=>array(
					'left'        =>__('Left','form-builder-wp'),
					'right'       =>__('Right','form-builder-wp'),
					'left_space'  =>__('Left with space','form-builder-wp'),
					'right_space' =>__('Right with space','form-builder-wp'),
				)
			),
			array (
				"type" => "checkbox",
				"label" => __ ( "PayPal sandbox", 'form-builder-wp' ),
				"name" => "paypal_sandbox",
				"cbvalue" => 1,
				'description'=>sprintf(__('PayPal sandbox can be used to test payments. Sign up for a <a href="%s" target="_blank">developer account</a>.','form-builder-wp'),'https://developer.paypal.com/')
			),
			array (
				'type' => 'text',
				'label' => __ ( 'PayPal Cancel URL', 'form-builder-wp' ),
				'name' => 'paypal_cancel_url',
				'description'=>__('Optional','form-builder-wp')
			),
			array (
				'type' => 'text',
				'label' => __ ( 'PayPal Return URL', 'form-builder-wp' ),
				'name' => 'paypal_return_url',
				'description'=>__('Optional','form-builder-wp')
			),
			array (
				"type" => "heading",
				"label"=>__('Paypal checkout','form-builder-wp')
			),
			array (
				"type" => "checkbox",
				"label" => __ ( "Submit form to paypal checkout", 'form-builder-wp' ),
				"name" => "paypal_checkout",
				"cbvalue" => 1
			),
			array (
				'type' => 'text',
				'label' => __ ( 'Order Description', 'form-builder-wp' ),
				'name' => 'paypal_order_description',
				'description'=>__('Optional, if left blank customer will be able to enter their own description at checkout','form-builder-wp')
			),
			array (
				'type' => 'text',
				'label' => __ ( 'Order Price', 'form-builder-wp' ),
				'name' => 'paypal_order_price',
				'description'=>__('Optional, if left blank customer will be able to enter their own price at checkout','form-builder-wp')
			),
			array (
				'type' => 'text',
				'label' => __ ( 'Order ID', 'form-builder-wp' ),
				'name' => 'paypal_order_id',
				'description'=>__('Optional','form-builder-wp')
			),
		);
	}
	
	
	public function render_general_settings(){

		echo '<div class="wpfbform_options">';
			foreach ($this->_general_setting() as $setting){
				echo $this->_render_metabox_field($setting);
			}
		echo '</div>';
	}
	public function render_mail_settings(){

		echo '<div class="wpfbform_options">';
			foreach ($this->_mail_setting() as $setting){
				echo $this->_render_metabox_field($setting);
			}
		echo '</div>';
	}
	public function render_avanced_settings(){

		echo '<div class="wpfbform_options">';
			foreach ($this->_advanced_setting() as $setting){
				echo $this->_render_metabox_field($setting);
			}
		echo '</div>';
	}
	public function render_notifications_settings(){

		echo '<div class="wpfbform_options">';
			foreach ($this->_notifications_setting() as $setting){
				echo $this->_render_metabox_field($setting);
			}
		echo '</div>';
	}
	public function render_payment_settings(){

		echo '<div class="wpfbform_options">';
			foreach ($this->_payment_setting() as $setting){
				echo $this->_render_metabox_field($setting);
			}
		echo '</div>';
	}
	public function render(){
		?>
		<div class="wpfbform_options">
			<?php 
			foreach ($this->_get_meta_boxs_fields() as $meta_box){
				$this->_render_metabox_field($meta_box);
			}	
			?>
		</div>
		<?php
	}
	
	protected function _render_metabox_field($field){
		global $post;
	
		if(!isset($field['type']))
			echo '';
	
		$field['name']          = isset( $field['name'] ) ? $field['name'] : '';
		$value_name = false !== strpos($field['name'], 'wpfb_form_messages') ? '_wpfb_form_messages':'_'.$field['name'];
		$value = get_post_meta( $post->ID, $value_name, true );
		$field['value']         = isset( $field['value'] ) ? $field['value'] : '';
		
		if($value){
			if('_wpfb_form_messages'===$value_name){
				$field_name = str_replace(array('wpfb_form_messages[',']'),'', $field['name']);
				$field['value'] = isset($value[$field_name]) ? $value[$field_name] : '';
			}else{
				$field['value'] = $value;
			}
		}
	
		$field['id'] 			= isset( $field['id'] ) ? $field['id'] : $field['name'];
		$field['description'] 	= isset($field['description']) ? $field['description'] : '';
		$field['label'] 		= isset( $field['label'] ) ? $field['label'] : '';
		$field['placeholder']   = isset( $field['placeholder'] ) ? $field['placeholder'] : $field['label'];
		$field['dependency']    = isset($field['dependency']) ? $field['dependency'] : array();
		$data_dependency = '';
		switch ($field['type']){
			case 'heading':
				echo '<h3>'.esc_html($field['label']).'</h3>';
				break;
			case 'labelpopup':
				echo '<p '.$data_dependency.' class="form-field ' . esc_attr( $field['id'] ) . '_field ">';
				echo $field['label'].__('Example:','form-builder-wp').'<br><strong><em>'.esc_html('<button type="button" data-toggle="wpfbformpopup" data-target="#wpfbformpopup-'.get_the_ID().'">'.__('Launch form popup','form-builder-wp').'</button>').'</strong></em>';
				echo '</p>';
				break;
			case 'input_variable':
				echo '<p '.$data_dependency.' class="form-field ' . esc_attr( $field['id'] ) . '_field "><label for="' . esc_attr( $field['id'] ) . '">' . ( $field['label'] ) . '</label>';
				echo '<select onchange="wpfb_form_select_variable(this)" class="wpfb-form-select-variable">';
				echo '<option value="">'.__('Insert variable...','form-builder-wp').'</option>';
				foreach (wpfb_form_get_variables() as $label=>$key){
					echo '<option value="'.esc_attr($key).'">'.esc_html($label).'</option>';
				}
				echo  '</select>';
				echo '<input type="text" class="input_text" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['value'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" /> ';
					
				if ( ! empty( $field['description'] ) ) {
					if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
						echo '<img class="help_tip" data-tip="' . esc_attr( $field['description'] ) . '" src="' . FORM_BUILDER_WP_URL . 'assets/images/help.png" height="16" width="16" />';
					} else {
						echo '<span class="description">' . ( $field['description'] ) . '</span>';
					}
				}
				echo '</p>';
				break;
			case 'text':
				echo '<p '.$data_dependency.' class="form-field ' . esc_attr( $field['id'] ) . '_field "><label for="' . esc_attr( $field['id'] ) . '">' . ( $field['label'] ) . '</label><input type="text" class="input_text" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['value'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" /> ';
					
				if ( ! empty( $field['description'] ) ) {
					if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
						echo '<img class="help_tip" data-tip="' . esc_attr( $field['description'] ) . '" src="' . FORM_BUILDER_WP_URL . 'assets/images/help.png" height="16" width="16" />';
					} else {
						echo '<span class="description">' . ( $field['description'] ) . '</span>';
					}
				}
				echo '</p>';
				break;
			case 'color':
				wp_enqueue_style( 'wp-color-picker');
				wp_enqueue_script( 'wp-color-picker'); 
				
				echo '<p '.$data_dependency.' class="form-field ' . esc_attr( $field['id'] ) . '_field "><label for="' . esc_attr( $field['id'] ) . '">' . ( $field['label'] ) . '</label><input data-default-color="'.esc_attr( $field['value'] ).'" type="text" class="input_text" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['value'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" /> ';
				if ( ! empty( $field['description'] ) ) {
					if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
						echo '<img class="help_tip" data-tip="' . esc_attr( $field['description'] ) . '" src="' . FORM_BUILDER_WP_URL . 'assets/images/help.png" height="16" width="16" />';
					} else {
						echo '<span class="description">' . ( $field['description'] ) . '</span>';
					}
				}
				echo '<script type="text/javascript">
						jQuery(document).ready(function($){
						    $("#'.$field['id'].'").wpColorPicker();
						});
					 </script>
					 ';
				echo '</p>';
				break;
			case 'hidden':
				echo '<input type="hidden" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['value'] ) .  '" /> ';
				break;
			case 'textarea_variable':
				echo '<p  '.$data_dependency.' class="form-field ' . esc_attr( $field['id'] ) . '_field "><label for="' . esc_attr( $field['id'] ) . '">' . ( $field['label'] ) . '</label>';
				echo '<select onchange="wpfb_form_select_variable(this)" class="wpfb-form-select-variable">';
				echo '<option value="">'.__('Insert variable...','form-builder-wp').'</option>';
				foreach (wpfb_form_get_variables() as $label=>$key){
					echo '<option value="'.esc_attr($key).'">'.esc_html($label).'</option>';
				}
				echo  '</select>';
				echo '<textarea name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" rows="5" cols="20">' . esc_textarea( $field['value'] ) . '</textarea> ';
	
				if ( ! empty( $field['description'] ) ) {
	
					if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
						echo '<img class="help_tip" data-tip="' . esc_attr( $field['description'] ) . '" src="' . FORM_BUILDER_WP_URL . 'assets/images/help.png" height="16" width="16" />';
					} else {
						echo '<span class="description">' . ( $field['description'] ) . '</span>';
					}
	
				}
				echo '</p>';
				break;
			case 'textarea':
				echo '<p  '.$data_dependency.' class="form-field ' . esc_attr( $field['id'] ) . '_field "><label for="' . esc_attr( $field['id'] ) . '">' . ( $field['label'] ) . '</label><textarea name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" rows="5" cols="20">' . esc_textarea( $field['value'] ) . '</textarea> ';
	
				if ( ! empty( $field['description'] ) ) {
	
					if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
						echo '<img class="help_tip" data-tip="' . esc_attr( $field['description'] ) . '" src="' . FORM_BUILDER_WP_URL . 'assets/images/help.png" height="16" width="16" />';
					} else {
						echo '<span class="description">' . ( $field['description'] ) . '</span>';
					}
	
				}
				echo '</p>';
				break;
			case 'recipient':
				echo '<div  '.$data_dependency.' class="form-field ' . esc_attr( $field['id'] ) . '_field "><label for="' . esc_attr( $field['id'] ) . '">' . ( $field['label'] ) . '</label>';
				//echo '<textarea name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" rows="5" cols="20">' . esc_textarea( $field['value'] ) . '</textarea> ';
	
				$values = (array)$field['value'];
				echo '<table  cellspacing="0" data-name="' . esc_attr( $field['name'] ) . '" class="wpfb-form-recipient-lists">';
				echo '<thead><tr><td>'.__('Email','form-builder-wp').'</td><td></td></tr></thead>';
				echo '<tbody>';
				foreach ($values as $val){
					echo '<tr>';
					echo '<td>';
					echo '<input type="text" name="' . esc_attr( $field['name'] ) . '[]" value="'.esc_attr($val).'" />';
					echo '</td>';
					echo '<td>';
					echo '<a href="#" class="button" onclick="return wpfb_form_recipient_remove(this)">'.__('Remove','form-builder-wp').'</a>';
					echo '</td>';
					echo '</tr>';
				}
				echo '<thead><tr><td><a href="#" class="button" onclick="return wpfb_form_recipient_add(this)">'.__('Add','form-builder-wp').'</a></td><td></td></tr></thead>';
				echo '</tbody>';
				echo '</table>';
				if ( ! empty( $field['description'] ) ) {
	
					if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
						echo '<img class="help_tip" data-tip="' . esc_attr( $field['description'] ) . '" src="' . FORM_BUILDER_WP_URL . 'assets/images/help.png" height="16" width="16" />';
					} else {
						echo '<span class="description">' . ( $field['description'] ) . '</span>';
					}
	
				}
				echo '</div>';
				break;
					
			case 'checkbox':
	
				$field['cbvalue']       = isset( $field['cbvalue'] ) ? $field['cbvalue'] : 'yes';
	
				echo '<p '.$data_dependency.' class="form-field ' . esc_attr( $field['id'] ) . '_field"><label for="' . esc_attr( $field['id'] ) . '">' . ( $field['label'] ) . '</label><input class="checkbox" type="checkbox" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['cbvalue'] ) . '" ' . checked( $field['value'], $field['cbvalue'], false ) . ' /> ';
	
				if ( ! empty( $field['description'] ) ) echo '<span class="description">' . ( $field['description'] ) . '</span>';
	
				echo '</p>';
				break;
			case 'checklist':
				$field['options']       = isset( $field['options'] ) ? $field['options'] : array();
				
				if(!is_array($field['value']))
					$field['value'] = array();
				
				echo '<p '.$data_dependency.' class="form-field ' . esc_attr( $field['id'] ) . '_field"><label for="' . esc_attr( $field['id'] ) . '">' . ( $field['label'] ) . '</label>';
	
				foreach ( $field['options'] as $key => $value ) {
					echo '<input class="checkbox" type="checkbox" '.(in_array(esc_attr($key), $field['value']) ? 'checked':'').' name="' . esc_attr( $field['name'] ) . '[]" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $key ) . '"  /> '.esc_html( $value ) .'<br/>';
	
				}
	
				if ( ! empty( $field['description'] ) ) {
	
					if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
						echo '<img class="help_tip" data-tip="' . esc_attr( $field['description'] ) . '" src="' . FORM_BUILDER_WP_URL . 'assets/images/help.png" height="16" width="16" />';
					} else {
						echo '<span class="description">' . ( $field['description'] ) . '</span>';
					}
	
				}
				echo '</p>';
				break;
			case 'select':
				$field['options']       = isset( $field['options'] ) ? $field['options'] : array();
	
				echo '<p '.$data_dependency.' class="form-field ' . esc_attr( $field['id'] ) . '_field"><label for="' . esc_attr( $field['id'] ) . '">' . ( $field['label'] ) . '</label><select id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['id'] ) . '">';
	
				foreach ( $field['options'] as $key => $value ) {
	
					echo '<option value="' . esc_attr( $key ) . '" ' . selected( esc_attr( $field['value'] ), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
	
				}
	
				echo '</select> ';
	
				if ( ! empty( $field['description'] ) ) {
	
					if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
						echo '<img class="help_tip" data-tip="' . esc_attr( $field['description'] ) . '" src="' . FORM_BUILDER_WP_URL . 'assets/images/help.png" height="16" width="16" />';
					} else {
						echo '<span class="description">' . ( $field['description'] ) . '</span>';
					}
	
				}
				echo '</p>';
				break;
			case 'select_recipient':
				$form_control = get_post_meta($post->ID,'_form_control',true);
				if($form_control){
					$form_control_arr = $form_control;
					if(is_array($form_control_arr) && !empty($form_control_arr)){
						$options = array();
						foreach ($form_control_arr as $control){
							if($control['tag'] == 'wpfb_form_email'){
								$option_label = !empty($control['control_label']) ? $control['control_label'] : $control['control_name'];
								if(!empty($control['control_name']))
									$options[$control['control_name']] = $option_label;
							}
						}
						$field['options']       = $options;
	
						echo '<p '.$data_dependency.' class="form-field ' . esc_attr( $field['id'] ) . '_field"><label for="' . esc_attr( $field['id'] ) . '">' . ( $field['label'] ) . '</label>';
						if(!empty($options)){
							echo '<select id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['id'] ) . '">';
							echo '<option value="" ></option>';
							foreach ( $field['options'] as $key => $value ) {
									
								echo '<option value="' . esc_attr( $key ) . '" ' . selected( esc_attr( $field['value'] ), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
									
							}
								
							echo '</select> ';
						}
	
						if ( ! empty( $field['description'] ) ) {
	
							if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
								echo '<img class="help_tip" data-tip="' . esc_attr( $field['description'] ) . '" src="' . FORM_BUILDER_WP_URL . 'assets/images/help.png" height="16" width="16" />';
							} else {
								echo '<span class="description">' . ( $field['description'] ) . '</span>';
							}
	
						}
						echo '</p>';
					}
				}
				break;
			case 'radio':
				$field['options']       = isset( $field['options'] ) ? $field['options'] : array();
				echo '<fieldset '.$data_dependency.' class="form-field ' . esc_attr( $field['id'] ) . '_field"><legend>' . ( $field['label'] ) . '</legend><ul class="wpfb-form-meta-radios">';
	
				foreach ( $field['options'] as $key => $value ) {
	
					echo '<li><label><input
				        		name="' . esc_attr( $field['name'] ) . '"
				        		value="' . esc_attr( $key ) . '"
				        		type="radio"
								class="radio"
				        		' . checked( esc_attr( $field['value'] ), esc_attr( $key ), false ) . '
				        		/> ' . esc_html( $value ) . '</label>
				    	</li>';
				}
				echo '</ul>';
	
				if ( ! empty( $field['description'] ) ) {
	
					if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
						echo '<img class="help_tip" data-tip="' . esc_attr( $field['description'] ) . '" src="' .FORM_BUILDER_WP_URL . 'assets/images/help.png" height="16" width="16" />';
					} else {
						echo '<span class="description">' . ( $field['description'] ) . '</span>';
					}
	
				}
				echo '</fieldset>';
				break;
			case 'mailchimp_list':
			    echo '<p '.$data_dependency.' class="form-field ' . esc_attr( $field['id'] ) . '_field"><label for="' . esc_attr( $field['id'] ) . '">' . ( $field['label'] ) . '</label>';
			    $options = array();
			    if(($mailchimp_api = wpfb_form_get_option('mailchimp_api',false))){
			        $options = wpfb_form_get_mailchimp_list($mailchimp_api);
			    }
			    echo '<select id="'.esc_attr( $field['id'] ).'" name="'.esc_attr( $field['id'] ).'">';
			    foreach ($options as $key => $value){
			        echo '<option value="'.$key.'" '.selected( esc_attr( $field['value'] ), esc_attr( $key ), false ).'>'.$value.'</option>';
			    }
			    echo '</select>';
			    if ( ! empty( $field['description'] ) ) {
			        echo '<span class="description">'.$field['description'].'</span>';
			    }
			    break;		
			default:
				break;
		}
	
	}
}

new Form_Builder_Wp_Metabox();