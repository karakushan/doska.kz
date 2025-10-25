<?php

//namespace Elkit;
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Elkit_Helper {
 	
   static function Elkit_Drop_Posts($post_type){ 
        $args = array(
          'numberposts' => -1,
          'post_type'   => $post_type
        );

        $posts = get_posts( $args );        
        $list = array();
        foreach ($posts as $cpost){

            $list[$cpost->ID] = $cpost->post_title;
        }
        return $list;
    }
    

}

