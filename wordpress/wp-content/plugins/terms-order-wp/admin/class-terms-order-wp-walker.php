<?php
	// Exit direct access
    if ( ! defined( 'ABSPATH' ) ) exit; 
    
    class Terms_Order_Wp_Walker extends Walker{

        var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

        public function start_lvl(&$output, $depth = 0, $args = array() ){
            extract($args, EXTR_SKIP); 
            $indent = str_repeat("\t", $depth);
            $output .= "\n$indent<ul class='children sortable'>\n";
        }


        public function end_lvl(&$output, $depth = 0, $args = array()){
            extract($args, EXTR_SKIP);     
            $indent = str_repeat("\t", $depth);
            $output .= "$indent</ul>\n";
        }


        public function start_el(&$output, $term, $depth = 0, $args = array(), $current_object_id = 0) {
            if ( $depth ){
                $indent = str_repeat("\t", $depth);
            }else{
                $indent = '';
			}
                  
            $taxonomy = get_taxonomy($term->term_taxonomy_id);
            $output .= $indent . '<li class="term_type_li" id="item_'.$term->term_id.'"><div class="item"><span class="dashicons dashicons-move"></span><span>'.apply_filters( 'wpto/term_title', $term->name, $term ).' </span></div>';
        }


        public function end_el(&$output, $object, $depth = 0, $args = array()) {
            $output .= "</li>\n";
        }

    }