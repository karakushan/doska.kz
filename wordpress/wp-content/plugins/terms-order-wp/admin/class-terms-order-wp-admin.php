<?php

/**
 * @package   Terms_Order_Wp
 * @subpackage Terms_Order_Wp/admin
 * @author     Designinvento <team@designinvento.net>
 */
class Terms_Order_Wp_Admin {

	private $plugin_name;
	private $version;
	
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action('admin_menu', array($this, 'wpto_terms_menu'));
		add_filter('terms_clauses', array($this, 'terms_order_filter'), 10, 3);
		add_filter('get_terms_orderby', array($this, 'get_terms_orderby'), 1, 2);
		add_action('wp_ajax_update-taxonomy-order', array($this, 'save_terms_order'));
	}

	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, TOWP_ASSETS_URL . 'css/terms-order-wp-admin.css', array(), $this->version, 'all' );
	
	}

	public function enqueue_scripts() {

		wp_enqueue_script('jquery');  
        wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script( $this->plugin_name, TOWP_ASSETS_URL . 'js/terms-order-wp-admin.js', array( 'jquery' ), $this->version, false );

	}
		
	public function wpto_terms_menu($input) {
        $post_types = get_post_types();
        foreach( $post_types as $post_type){

				$post_type_taxonomies = get_object_taxonomies($post_type);
				foreach ($post_type_taxonomies as $key => $taxonomy_name){
                    $taxonomy_info = get_taxonomy($taxonomy_name);  
                    if (empty($taxonomy_info->hierarchical) ||  $taxonomy_info->hierarchical !== TRUE) 
                        unset($post_type_taxonomies[$key]);
                    }
                        
                    if (count($post_type_taxonomies) == 0){
                        continue; 
					}						
                    
                    if ($post_type == 'post'){
						
                        add_submenu_page('edit.php', __('Terms Order', 'terms-order-wp'), __('Terms Order', 'terms-order-wp'), 'manage_options', 'wpto-'.$post_type, array($this, 'wpto_output') );
					
					} elseif ($post_type == 'attachment'){
						
                        add_submenu_page('upload.php', __('Terms Order', 'terms-order-wp'), __('Terms Order', 'terms-order-wp'), 'manage_options', 'wpto-'.$post_type, array($this, 'wpto_output') );   
					
					}else{
						
                        add_submenu_page('edit.php?post_type='.$post_type, __('Taxonomy Order', 'terms-order-wp'), __('Terms Order', 'terms-order-wp'), 'manage_options', 'wpto-'.$post_type, array($this, 'wpto_output') );
					
					}
                
		}
	}
		
	function terms_order_filter( $clauses, $taxonomies, $args){
	        
			if ( apply_filters('wpto/get_terms_orderby/ignore', FALSE, $clauses['orderby'], $args) ){
                return $clauses;
            }

            if (is_admin()){
                    if (isset($_GET['orderby']) && $_GET['orderby'] !=  'term_order'){
                        return $clauses;
					}
                    
                    if ((!isset($args['ignore_term_order']) ||  (isset($args['ignore_term_order'])  &&  $args['ignore_term_order']  !== TRUE) ) ){
                        $clauses['orderby'] =   'ORDER BY t.term_order';
					}
                        
                    return $clauses;    
			}
            
            if ((!isset($args['ignore_term_order']) ||  (isset($args['ignore_term_order'])  &&  $args['ignore_term_order']  !== TRUE) ) ){
                    $clauses['orderby'] =   'ORDER BY t.term_order';
			}
                
            return $clauses; 
	}
		
	public function get_terms_orderby($orderby, $args){
            if ( apply_filters('wpto/get_terms_orderby/ignore', FALSE, $orderby, $args) ){
                return $orderby;
			}
                
            if (isset($args['orderby']) && $args['orderby'] == "term_order" && $orderby != "term_order")
                return "t.term_order";
                
            return $orderby;
    }
		
	public function save_terms_order(){
            global $wpdb;
            
            if  ( ! wp_verify_nonce( $_POST['nonce'], 'update-taxonomy-order' ) ){
                die();
			}
            
            $data = stripslashes(sanitize_text_field($_POST['order']));
            $unserialised_data = json_decode($data, TRUE);
                    
            if(is_array($unserialised_data)){
				foreach($unserialised_data as $key => $values ){
						
					$items = explode("&", $values);
					unset($item);
					foreach ($items as $item_key => $item_){
						$items[$item_key] = trim(str_replace("item[]=", "",$item_));
					}
						
					if (is_array($items) && count($items) > 0){
						foreach( $items as $item_key => $term_id ){
							$wpdb->update( $wpdb->terms, array('term_order' => ($item_key + 1)), array('term_id' => $term_id) );
						}
						clean_term_cache($items);
					} 
				}
			}
                
            do_action('wpto/update-order');
             
            die();
    }
		
	public function wpto_output(){
            global $wpdb, $wp_locale;
            
            $taxonomy = isset($_GET['taxonomy']) ? sanitize_key($_GET['taxonomy']) : '';
            $post_type = isset($_GET['post_type']) ? sanitize_key($_GET['post_type']) : '';
            if(empty($post_type)){
                $screen = get_current_screen();
                    
                if(isset($screen->post_type)    && !empty($screen->post_type)){
                    $post_type = $screen->post_type;
				}else{
                    switch($screen->parent_file){
                        case "upload.php" :
                            $post_type  =   'attachment';
                        break;            
						default:
							$post_type  =   'post';   
					}
				}       
			} 
                                            
            $post_type_data = get_post_type_object($post_type);
            
            if (!taxonomy_exists($taxonomy)){
                $taxonomy = '';
			}
			
			$instance = $this;
			include('templates/terms-order-wp-output.php'); 
            
            
    }
	public function wpto_terms_list($taxonomy){
        $args = array(
            'orderby' => 'term_order',
            'depth' => 0,
            'child_of' => 0,
            'hide_empty' => 0
        );
		$taxonomy_terms = get_terms($taxonomy, $args);

        $output = '';
        if (count($taxonomy_terms) > 0){
            $output = $this->terms_order_wp_list_hierarchy($taxonomy_terms, $args['depth'], $args);    
        }
        echo wp_kses_post($output); 
                
    }
        
    public function terms_order_wp_list_hierarchy($taxonomy_terms, $depth, $r){
        $walker = new Terms_Order_Wp_Walker; 
        $args = array($taxonomy_terms, $depth, $r);
        return call_user_func_array(array(&$walker, 'walk'), $args);
    }
}
