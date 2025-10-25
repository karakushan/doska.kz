<?php

/**
 * @package    Form_Builder_Wp
 * @subpackage Form_Builder_Wp/admin
 * @author     Designinvento <team@designinvento.net>
 */
class Form_Builder_Wp_Admin_Main {

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action( 'admin_menu', array( $this, 'admin_menus' ) );
		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );
		add_action( 'admin_print_scripts', array( $this, 'disable_autosave' ) );
		
		add_action( 'admin_enqueue_scripts', array($this,'enqueue_styles') );
		add_action( 'admin_enqueue_scripts', array($this,'enqueue_scripts') );
		
		add_action('delete_post', array($this,'delete_post'));
		
		// Admin Columns
		add_filter( 'manage_edit-wpfbform_columns', array( $this, 'edit_columns' ) );
		add_action( 'manage_wpfbform_posts_custom_column', array( $this, 'custom_columns' ), 2 );
		
		// Views and filtering
		add_filter( 'views_edit-wpfbform', array( $this, 'custom_order_views' ) );
		add_filter( 'post_row_actions', array( $this, 'remove_row_actions' ), 100, 1 );
		add_filter( 'post_row_actions', array( $this, 'add_row_actions' ), 100, 2 );

	}
	public function admin_menus() {
		
			add_menu_page(
				esc_html__( 'WP Forms Builder', 'form-builder-wp' ),
				esc_html__( 'WP Forms Builder', 'form-builder-wp' ),
				'edit_wpfbforms',
				'form-builder-wp',
				array($this, 'screen_welcome'),
				'',
				15
			);
	}
	public function screen_welcome() {
		echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';
		//do_action('directorypress_dashboad_panel');
	}
	public function enqueue_styles($hook_suffix) {

		$screen         = get_current_screen();
		//if('wpfbform'===$screen->post_type || false!==strpos($hook_suffix, 'form-builder-wp')){
			wp_enqueue_style('wpfb_form_admin', FORM_BUILDER_WP_URL . 'assets/css/admin.css');
			
		//}
	}

	public function enqueue_scripts($hook_suffix){
		$screen         = get_current_screen();
		//if('wpfbform'===$screen->post_type || false!==strpos($hook_suffix, 'form-builder-wp')){
			wp_register_script( 'wpfb_form_admin', FORM_BUILDER_WP_URL . 'assets/js/admin.js', array( 'jquery' ,'jquery-blockui'), FORM_BUILDER_WP_VERSION, true );
			wp_localize_script( 'wpfb_form_admin', 'wpfb_form_admin', array(
				'ajax_url'=>admin_url( 'admin-ajax.php', 'relative' ),
				'plugin_url'=>FORM_BUILDER_WP_URL,
				'delete_confirm'=>__('Are your sure?','form-builder-wp'),
				'recipient_tmpl'=>$this->_recipient_tmpl(),
			) );
		//}
		wp_enqueue_script('wpfb_form_admin');
	}
	
	protected function _recipient_tmpl(){
		$recipient_tmpl = '';
		$recipient_tmpl .=  '<tr>';
		$recipient_tmpl .=  '<td>';
		$recipient_tmpl .=  '<input type="text" name="" value="" />';
		$recipient_tmpl .=  '</td>';
		$recipient_tmpl .=  '<td>';
		$recipient_tmpl .=  '<a href="#" class="button" onclick="return wpfb_form_recipient_remove(this)">'.__('Remove','form-builder-wp').'</a>';
		$recipient_tmpl .=  '</td>';
		$recipient_tmpl .=  '</tr>';
		return $recipient_tmpl;
	}
	
	public function delete_post($post_id){
		global $wpfbform_db;
		if ( ! current_user_can( 'delete_posts' ) )
			return;
	
	
		if ( $post_id > 0 ) {
			$post_type = get_post_type( $post_id );
			if($post_type === 'wpfbform')
				$wpfbform_db->delete_entry_by_form($post_id);
		}
	}
	
	public function disable_autosave(){
		global $post;
	
		if ( $post && get_post_type( $post->ID ) === 'wpfbform' ) {
			wp_dequeue_script( 'autosave' );
		}
	}
	
	public function post_updated_messages( $messages ) {
		global $post;
		$messages['wpfbform'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => __( 'Form updated.', 'form-builder-wp' ),
			2 => __( 'Custom field updated.', 'form-builder-wp' ),
			3 => __( 'Custom field deleted.', 'form-builder-wp' ),
			4 => __( 'Form updated.', 'form-builder-wp' ),
			5 => isset($_GET['revision']) ? sprintf( __( 'Form restored to revision from %s', 'form-builder-wp' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => __( 'Form updated.', 'form-builder-wp' ),
			7 => __( 'Form saved.', 'form-builder-wp' ),
			8 => __( 'Form submitted.', 'form-builder-wp' ),
			9 => sprintf( __( 'Form scheduled for: <strong>%1$s</strong>.', 'form-builder-wp' ),date_i18n( __( 'M j, Y @ G:i', 'form-builder-wp' ), strtotime( $post->post_date ) ) ),
			10 => __( 'Form draft updated.', 'form-builder-wp' )
		);
		return $messages;
	}
	
	public function edit_columns( $existing_columns ) {
		$columns = array();
	
		$columns['cb']             = isset($existing_columns['cb']) ? $existing_columns['cb'] : '';
		$columns['form_id']        = __( 'ID', 'form-builder-wp' );
		$columns['title']          = __( 'Title', 'form-builder-wp' );
		$columns['shortcode']      = __( 'Shortcode', 'form-builder-wp' );
	
		unset($existing_columns['title']);
		unset($existing_columns['cb']);
	
		return array_merge($columns,$existing_columns);
	}
	
	public function custom_columns( $column ) {
		global $post;
		switch ( $column ) {
			case 'shortcode':
				echo '<input class="wp-ui-text-highlight code" type="text" onfocus="this.select();" readonly="readonly" value="'.esc_attr('[wpfb_form form_id="'.$post->ID.'"]').'" style="width:99%">';
				break;
			case 'form_id':
				echo get_the_ID();
				break;
		}
	}
	
	public function custom_order_views($views){
		unset( $views['publish'] );
	
		if ( isset( $views['trash'] ) ) {
			$trash = $views['trash'];
			unset( $views['draft'] );
			unset( $views['trash'] );
			$views['trash'] = $trash;
		}
	
		return $views;
	}
	
	public function add_row_actions($actions){
		global $post;
		$actions['delete'] = "<a class='submitdelete' id='wpfb_form_submitdelete' title='" . esc_attr( __( 'Delete this item permanently' ) ) . "' href='" . get_delete_post_link( $post->ID, '', true ) . "'>" . __( 'Delete Permanently' ) . "</a>";
		return $actions;
	}
	
	public function remove_row_actions( $actions ) {
		if ( 'wpfbform' === get_post_type() ) {
			unset( $actions['view'] );
			unset( $actions['edit_vc'] );
			unset( $actions['trash'] );
			unset( $actions['inline hide-if-no-js'] );
		}
	
		return $actions;
	}

}
