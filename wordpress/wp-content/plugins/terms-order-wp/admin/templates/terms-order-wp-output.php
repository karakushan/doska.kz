<div class="wrap">
                <h2><?php _e( "Terms Order", 'terms-order-wp' ) ?></h2>
                
               
                
                <noscript>
                    <div class="error message">
                        <p><?php _e( "Jquery library required to this plugin work.", 'terms-order-wp' ) ?></p>
                    </div>
                </noscript>

                <div class="clear"></div>
                
                <?php
                
                    $current_section_parent_file    =   '';
                    switch($post_type){ 
                        case "attachment" :
							$current_section_parent_file = "upload.php";
							break;                
                           default :
                                $current_section_parent_file    =    "edit.php";
                                break;
                    }
                ?>
                
                <form action="<?php echo esc_attr($current_section_parent_file); ?>" method="get" id="terms_order_wp_form">
                    <input type="hidden" name="page" value="wpto-<?php echo esc_attr($post_type) ?>" />
                    <?php
                
                    if (!in_array($post_type, array('post', 'attachment'))){
                        echo '<input type="hidden" name="post_type" value="'. esc_attr($post_type) .'" />';
					}
                    
                    $post_type_taxonomies = get_object_taxonomies($post_type);
                
                    foreach ($post_type_taxonomies as $key => $taxonomy_name)
                        {
                            $taxonomy_info = get_taxonomy($taxonomy_name);  
                            if ($taxonomy_info->hierarchical !== TRUE) 
                                unset($post_type_taxonomies[$key]);
                        }
                        
                    
                    if ($taxonomy == '' || !taxonomy_exists($taxonomy))
                        {
                            reset($post_type_taxonomies);   
                            $taxonomy = current($post_type_taxonomies);
                        }
                                            
                    if (count($post_type_taxonomies) > 1)
                        {
                
                            ?>
                            
                            <h2 class="subtitle"><?php echo esc_html(ucfirst($post_type_data->labels->name)); ?> <?php _e( "Taxonomies", 'terms-order-wp' ) ?></h2>
                            <table cellspacing="0" class="wp-list-taxonomy">
                                <thead>
                                <tr>
                                    <th style="" class="column-cb check-column" id="cb" scope="col">&nbsp;</th><th style="" class="" id="author" scope="col"><?php _e( "Taxonomy Title", 'terms-order-wp' ) ?></th><th style="" class="manage-column" id="categories" scope="col"><?php _e( "Total Posts", 'terms-order-wp' ) ?></th>    </tr>
                                </thead>

   
                                <tbody id="the-list">
                                <?php
                                    
                                    $alternate = FALSE;
                                    foreach ($post_type_taxonomies as $post_type_taxonomy){
                                            $taxonomy_info = get_taxonomy($post_type_taxonomy);
                                            $alternate = $alternate === TRUE ? FALSE :TRUE;
                                            $args = array(
                                                'hide_empty' =>  0,
                                                'taxonomy' =>  $post_type_taxonomy
                                            );
                                            $taxonomy_terms = get_terms( $args );
                                                             
                                            ?>
                                                <tr valign="top" class="<?php if ($alternate === TRUE) {echo 'alternate ';} ?>" id="taxonomy-<?php echo esc_attr($taxonomy)  ?>">
                                                        <th class="check-column" scope="row"><input type="radio" onclick="terms_order_wp_change_taxonomy(this)" value="<?php echo esc_attr($post_type_taxonomy) ?>" <?php if ($post_type_taxonomy == $taxonomy) {echo 'checked="checked"';} ?> name="taxonomy">&nbsp;</th>
                                                        <td class="categories column-categories"><b><?php echo esc_html($taxonomy_info->label); ?></b> (<?php echo  esc_html($taxonomy_info->labels->singular_name); ?>)</td>
                                                        <td class="categories column-categories"><?php echo esc_html(count($taxonomy_terms)) ?></td>
                                                </tr>
                                            
                                            <?php
                                    }
                                ?>
                                </tbody>
                            </table>
                            <br />
                            <?php
                        }
                            ?>

                <div id="order-terms">

                    <div id="post-body">                    
                        
                            <ul class="sortable" id="wp_term_sortable">
                                <?php $instance->wpto_terms_list($taxonomy); ?>
                            </ul>
                            
                            <div class="clear"></div>
                    </div>
                    
                    <div class="actions">
                        <p class="submit">
                            <a href="javascript:;" class="save-order button-primary"><?php _e( "Update", 'terms-order-wp' ) ?></a>
                        </p>
                    </div>
                    
                </div> 

                </form>
                
                <script type="text/javascript">
                    jQuery(document).ready(function() {
                        
                        jQuery("ul.sortable").sortable({
                                'tolerance':'intersect',
                                'cursor':'pointer',
                                'items':'> li',
                                'axi': 'y',
                                'placeholder':'placeholder',
                                'nested': 'ul'
                            });
                          
                        jQuery(".save-order").bind( "click", function() {
                                var mySortable = new Array();
                                jQuery(".sortable").each(  function(){
                                    
                                    var serialized = jQuery(this).sortable("serialize");
                                    
                                    var parent_tag = jQuery(this).parent().get(0).tagName;
                                    parent_tag = parent_tag.toLowerCase()
                                    if (parent_tag == 'li')
                                        {
                                            // 
                                            var tag_id = jQuery(this).parent().attr('id');
                                            mySortable[tag_id] = serialized;
                                        }
                                        else
                                        {
                                            //
                                            mySortable[0] = serialized;
                                        }
                                });
                                
                                //serialize the array
                                var serialize_data = JSON.stringify( array_to_object_conversion(mySortable));
                                //var update_message =  ;                                                       
                                jQuery.post( ajaxurl, { action:'update-taxonomy-order', order: serialize_data, nonce : '<?php echo wp_create_nonce( 'update-taxonomy-order' ); ?>' }, function() {
                                    jQuery("#ajax-response").html('<div class="message updated"><p><?php echo esc_html__( "Items Order Updated", "terms-order-wp" ); ?></p></div>');
                                    //jQuery("#ajax-response").delay(3000).hide("slow");
                                });
                            });
                        
      
                    });
                    
                </script>
     <div id="ajax-response"></div>            
</div>
