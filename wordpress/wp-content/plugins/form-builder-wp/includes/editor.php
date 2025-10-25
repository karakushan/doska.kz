<?php

class Form_Builder_Wp_Editor {

	public function __construct(){
		add_action( 'elementor/init', array( $this, 'add_category' ),15 );
		
		add_action( 'elementor/documents/register',array($this,'register_documents') );
		
		add_action('elementor/controls/controls_registered', array($this, 'controls_registered') );

		add_action( 'elementor/editor/after_enqueue_styles',array( $this, 'editor_enqueue_styles' ) );
		
		add_action( 'elementor/editor/after_save',array($this,'after_save'),10,2);

		add_action( 'elementor/preview/enqueue_scripts',array( $this, 'enqueue_preview_scripts' ) );
	
	}
	
	public function register_documents(){
		require_once FORM_BUILDER_WP_PATH .'includes/document.php';
		\Elementor\Plugin::$instance->documents->register_document_type('wpfbform', Form_Builder_Wp_Document::get_class_full_name());
	}
	
	public function after_save( $post_id, $editor_data ){
		$post = get_post($post_id);
		if ( empty( $post_id ) || empty( $post ) ) {
			return;
		}
		
		// Dont' save meta boxes for revisions or autosaves
		if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
			return;
		}
		
		// Check the post type
		if ('wpfbform'!==$post->post_type ) {
			return;
		}
		$scan_tag = new Form_Builder_Wp_Scan_Tag($post->post_content);
		update_post_meta($post->ID, '_form_control', $scan_tag->get_scaned_fields());
	}

	public function add_category(){
		\Elementor\Plugin::$instance->elements_manager->add_category( 'form-builder-wp', array(
			'title' => __( 'WP Form Builder', 'form-builder-wp' ),
		), 1 );
	}

	public function editor_enqueue_styles(){
		wp_enqueue_style('wpfb_form_editor', FORM_BUILDER_WP_URL . 'assets/css/editor.css',array(),FORM_BUILDER_WP_VERSION);
	}

	public function enqueue_preview_scripts(){
		wp_enqueue_script('wpfb_form_editor_preview',FORM_BUILDER_WP_URL . 'assets/js/preview.js',array('jquery'),FORM_BUILDER_WP_VERSION,true);
	}

	/**
	 *
	 * @param \Elementor\Controls_Manager $controls_manager
	 */
	public function controls_registered($controls_manager){
		
	}
	
}

new Form_Builder_Wp_Editor();