<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Form_Builder_Wp_Entries {
	public function __construct(){
		add_action( 'admin_menu', array($this,'admin_menu'),15);
	}
	
	public function admin_menu(){
		add_submenu_page('form-builder-wp',  __('Entries','form-builder-wp'),   __('Entries','form-builder-wp'), 'edit_wpfbforms', 'wpfb-form-entry',array($this,'render'));
	}
	
	public function render(){
		if(isset($_GET['action']) && $_GET['action'] == 'view'){
			if ( ! current_user_can('edit_wpfbforms') )
				wp_die( __( 'Cheatin&#8217; uh?' ) );
				
			$this->_view_entry();
		}else{
			if ( ! current_user_can('edit_wpfbforms'))
				wp_die( __( 'Cheatin&#8217; uh?' ) );
				
			$this->_list_entry();
		}
	}
	
	protected function _get_current_page_num(){
		$current = isset($_GET['paged']) ? absint($_GET['paged']) : 0;
		return  max(1, $current);
	}
	
	protected function _get_pagination($per_page, $total_items, $which)
	{
		$total_pages = ceil( $total_items / $per_page );
		$current = $this->_get_current_page_num();
		$output = '<span class="displaying-num">' . sprintf( _n( '1 item', '%s items', $total_items ), number_format_i18n( $total_items ) ) . '</span>';
	
		$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	
		$page_links = array();
	
		$disable_first = $disable_last = '';
	
		if ( $current == 1 )
			$disable_first = ' disabled';
		if ( $current == $total_pages )
			$disable_last = ' disabled';
	
		$page_links[] = sprintf( "<a class='%s' title='%s' href='%s'>%s</a>",
			'first-page' . $disable_first,
			esc_attr__( 'Go to the first page' ),
			esc_url( remove_query_arg( 'paged', $current_url ) ),
			'&laquo;'
		);
	
		$page_links[] = sprintf( "<a class='%s' title='%s' href='%s'>%s</a>",
			'prev-page' . $disable_first,
			esc_attr__( 'Go to the previous page' ),
			esc_url( add_query_arg( 'paged', max( 1, $current-1 ), $current_url ) ),
			'&lsaquo;'
		);
	
		if ( 'bottom' == $which )
			$html_current_page = $current;
		else
			$html_current_page = sprintf( "<input class='current-page' title='%s' type='text' name='%s' value='%s' size='%d' />",
				esc_attr__( 'Current page' ),
				esc_attr( 'paged' ),
				$current,
				strlen( $total_pages )
			);
	
		$html_total_pages = sprintf( "<span class='total-pages'>%s</span>", number_format_i18n( $total_pages ) );
		$page_links[] = '<span class="paging-input">' . sprintf( _x( '%1$s of %2$s', 'paging' ), $html_current_page, $html_total_pages ) . '</span>';
	
		$page_links[] = sprintf( "<a class='%s' title='%s' href='%s'>%s</a>",
			'next-page' . $disable_last,
			esc_attr__( 'Go to the next page' ),
			esc_url( add_query_arg( 'paged', min( $total_pages, $current+1 ), $current_url ) ),
			'&rsaquo;'
		);
	
		$page_links[] = sprintf( "<a class='%s' title='%s' href='%s'>%s</a>",
			'last-page' . $disable_last,
			esc_attr__( 'Go to the last page' ),
			esc_url( add_query_arg( 'paged', $total_pages, $current_url ) ),
			'&raquo;'
		);
	
		$output .= "\n<span class='pagination-links'>" . join( "\n", $page_links ) . '</span>';
	
		if ( $total_pages )
			$page_class = $total_pages < 2 ? ' one-page' : '';
		else
			$page_class = ' no-pages';
	
		return "<div class='tablenav-pages{$page_class}'>$output</div>";
	}
	
	protected function _list_entry(){
		global $wpfbform_db;
		$message = '';
		$action = isset($_GET['action']) ? $_GET['action'] : '';
		switch ($action){
			case 'read':
				$entry_id = absint($_GET['entry_id']);
				if(wp_verify_nonce($_GET['_wpnonce'], 'read_entry_' . $entry_id)){
					$count = $wpfbform_db->read_entry($entry_id);
					$message = $count > 0 ? sprintf(__("%s entry mask as read",'form-builder-wp'),$count) : '';
				}
				break;
			case 'unread':
				$entry_id = absint($_GET['entry_id']);
				if(wp_verify_nonce($_GET['_wpnonce'], 'unread_entry_' . $entry_id)){
					$count = $wpfbform_db->unread_entry($entry_id);
					$message = $count > 0 ?  sprintf(__("%s entry mask as un-read",'form-builder-wp'),$count): '';
				}
				break;
			case 'delete':
				$entry_id = absint($_GET['entry_id']);
				if(wp_verify_nonce($_GET['_wpnonce'], 'delete_entry_' . $entry_id)){
					$count = $wpfbform_db->delete_entry($entry_id);
					$message =  $count > 0 ?  sprintf(__("%s entry deleted",'form-builder-wp'),$count): '';
				}
				break;
			default:
				break;
		}
	
		$bulk_action = '';
		if (isset($_GET['bulk_action']) && $_GET['bulk_action'] != '-1') {
			$bulk_action = $_GET['bulk_action'];
		} elseif (isset($_GET['bulk_action2']) && $_GET['bulk_action2'] != '-1') {
			$bulk_action = $_GET['bulk_action2'];
		}
		switch ($bulk_action){
			case 'read':
				$entry_id = isset($_GET['entry']) ? $_GET['entry'] : array();
				$count = $wpfbform_db->read_entry($entry_id);
				$message = $count > 0 ? sprintf(__("%s entry mask as read",'form-builder-wp'),$count) : '';
	
				break;
			case 'unread':
				$entry_id = isset($_GET['entry']) ? $_GET['entry'] : array();
				$count = $wpfbform_db->unread_entry($entry_id);
				$message = $count > 0 ?  sprintf(__("%s entry mask as un-read",'form-builder-wp'),$count): '';
	
				break;
			case 'delete':
				$entry_id = isset($_GET['entry']) ? $_GET['entry'] : array();
				$count = $wpfbform_db->delete_entry($entry_id);
				$message =  $count > 0 ?  sprintf(__("%s entry deleted",'form-builder-wp'),$count): '';
				break;
			default:
				break;
		}
	
	
		$orderby = (isset($_GET['orderby'])  ) ? $_GET['orderby'] : 'submitted';
		$order = isset($_GET['order']) && strtolower($_GET['order']) == 'asc' ? 'asc' : 'desc';
		$reverseOrder = $order == 'asc' ? 'desc' : 'asc';
	
		$form_id = isset($_GET['form_id']) ? $_GET['form_id'] : 0;
		$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
	
		$offset =  $limit * ($this->_get_current_page_num() - 1);
	
		$columns= array('id'=>__('ID','form-builder-wp'),'date'=>__('Date','form-builder-wp'),'form_name'=>__('Form Name','form-builder-wp'));
		$topPagination ='';
		$entries = $wpfbform_db->get_entries($form_id,$orderby,$order,$limit,$offset);
		$total = $wpfbform_db->get_entries_count($form_id);
		?>
	<div class="wrap">
		<h2><?php echo __('Entries','form-builder-wp')?></h2>
		<?php if(!empty($message)):?>
		<div id="message" class="updated below-h2">
			<p><?php echo esc_html_($message); ?></p>
		</div>
		<?php endif;?>
		<form id="wpfb_form_entry" action="" method="get">
			<input type="hidden" value="wpfb-form-entry" name="page">
			<ul class="subsubsub">
				<li class="all">
					<a class="current" href="#"><?php echo __('All','form-builder-wp')?> <span class="count">(<?php echo (int) $total ?>)</span></a>
				</li>
			</ul>
			<div class="tablenav top">
	            <div class="alignleft actions bulkactions">
	                <select name="bulk_action">
	                    <option selected="selected" value="-1"><?php esc_html_e('Bulk Actions', 'form-builder-wp'); ?></option>
	                    <option value="read"><?php esc_html_e('Mark as read', 'form-builder-wp'); ?></option>
	                    <option value="unread"><?php esc_html_e('Mark as unread', 'form-builder-wp'); ?></option>
	                    <option value="delete"><?php esc_html_e('Delete', 'form-builder-wp'); ?></option>
	                </select>
	                <input type="submit" value="<?php esc_attr_e('Apply', 'form-builder-wp'); ?>" class="button action wpfb-form-action" id="doaction" name="" />
	           </div>
	           <div class="alignleft actions">
	               	<select name="limit" class="wpfb-form-entry-select-action" style="float: none">
	                    <option value="10" <?php selected($limit, 10); ?>>10</option>
	                    <option value="20" <?php selected($limit, 20); ?>>20</option>
	                    <option value="40" <?php selected($limit, 40); ?>>40</option>
	                    <option value="60" <?php selected($limit, 60); ?>>60</option>
	                    <option value="80" <?php selected($limit, 80); ?>>80</option>
	                    <option value="100" <?php selected($limit, 100); ?>>100</option>
	                    <option value="-1" <?php selected($limit, -1); ?>><?php esc_html_e('All', 'form-builder-wp'); ?></option>
	                </select>
	                <span><?php esc_html_e('per page', 'form-builder-wp'); ?></span>
	                <?php 
	                $forms = get_posts(array(
						'numberposts'=>-1,
						'post_type'=>'wpfbform'
					));
	                ?>
	                <span style="margin-left: 30px;font-weight: bold;"><?php esc_html_e('Filter by form to export:', 'form-builder-wp'); ?></span>
	                <select name="form_id" class="wpfb-form-entry-select-action" style="float: none;margin-left: 10px">
	                	<option value="0" <?php selected($limit, 0); ?>><?php echo __('View all form')?></option>
	                	<?php foreach ($forms as $form):?>
	                    <option value="<?php echo esc_attr($form->ID); ?>" <?php selected($form_id,$form->ID); ?>><?php echo esc_attr($form->ID) .' - '. $form->post_title; ?></option>
	                    <?php endforeach;?>
	                </select>
	                <?php if(!empty($form_id)):?>
	                <a href="<?php echo plugins_url('/wpfb-form/export.php?form_id='.$form_id); ?>" target="_blank" class="button" style="display: inline-block;"><?php _e('Export','form-builder-wp')?></a>
	            	<?php endif;?>
	            </div>
	             <?php echo $this->_get_pagination($limit, $total, 'top'); ?>
	            <br class="clear" />
	        </div>
	        <table class="wp-list-table widefat fixed wpfb-form-entry-list">
	            <thead>
	                <tr>
	                    <th class="manage-column column-cb check-column" id="cb" scope="col">
	                        <input type="checkbox" class="headercb" />
	                    </th>
	                    <?php ob_start(); ?>
	                    
	                        <?php foreach ($columns as $key=>$label) : ?>
	                            <?php if ($key == $orderby) : ?>
	                                <th class="manage-column entry-<?php echo $key; ?> sorted <?php echo $order; ?>" scope="col">
	                                    <a href="<?php echo esc_url(add_query_arg(array('orderby' => $key, 'order' => strtolower($reverseOrder)))); ?>">
	                            <?php else : ?>
	                                <th class="manage-column entry-<?php echo $key; ?> sortable desc" scope="col">
	                                    <a href="<?php echo esc_url(add_query_arg(array('orderby' => $key, 'order' => 'asc'))); ?>">
	                            <?php endif; ?>
	                                    <span><?php echo esc_html($label); ?></span>
	                                    <span class="sorting-indicator"></span>
	                                    </a>
	                                </th>
	
	                        <?php endforeach; ?>
	                    <?php echo $headings = ob_get_clean(); ?>
	                </tr>
	            </thead>
	
	            <tfoot>
	                <tr>
	                    <th class="manage-column column-cb check-column" scope="col">
	                        <input type="checkbox" />
	                    </th>
	                    <?php echo $headings; ?>
	                </tr>
	            </tfoot>
	
	            <tbody id="the-list">
	                <?php if (count($entries)) : ?>
	                    <?php $i = 1; ?>
	                    <?php foreach ($entries as $entry) : ?>
	                        <tr valign="top" class="<?php echo (++$i % 2 == 1) ? 'alternate ' : ''; ?> wpfb-form-entry-<?php echo ($entry->readed == 0 ? 'read' : 'unread')?>" id="wpfb-form-entry-<?php echo $entry->id; ?>">
	                            <th class="check-column" scope="row">
	                                <input type="checkbox" value="<?php echo $entry->id; ?>" name="entry[]" />
	                            </th>
	                            <td class="wpfb-form-entry-id">
	                            	<?php echo $entry->id?>
	                            		<span class="wpfb-form-entry-icon wpfb-form-entry-icon-<?php echo ($entry->readed == 0 ? 'read' : 'unread')?>"></span>
	                            </td>
	                            <td class="wpfb-form-entry-date">
	                            	<?php 
	                            	$t_time = sprintf( __( '%1$s at %2$s' ),
											mysql2date(get_option('date_format'), $entry->submitted),
											mysql2date( get_option( 'time_format' ), $entry->submitted )
										);
	                            	?>
	                            	<a href="<?php echo esc_url(add_query_arg(array('action' => 'view', 'entry_id' => $entry->id),'admin.php?page=wpfb-form-entry')); ?>"><strong class="row-title"><abbr title="<?php echo $t_time ?>"><?php echo $t_time ?></abbr></strong></a>
	                            	<div class="row-actions">
									    <span class="view"><a href="<?php echo esc_url(add_query_arg(array('action' => 'view', 'entry_id' => $entry->id),'admin.php?page=wpfb-form-entry')); ?>" title="<?php esc_attr_e('View this entry', 'form-builder-wp'); ?>"><?php esc_html_e('View', 'form-builder-wp'); ?></a> |</span>
									    <?php if ($entry->readed == 0) : ?>
									        <span class="mark-read"><a href="<?php echo esc_url(add_query_arg(array('action' => 'read', 'entry_id' => $entry->id, '_wpnonce' => wp_create_nonce('read_entry_' . $entry->id)), 'admin.php?page=wpfb-form-entry')); ?>" title="<?php esc_attr_e('Mark as read', 'form-builder-wp'); ?>"><?php esc_html_e('Mark as read', 'form-builder-wp'); ?></a> |</span>
									    <?php else : ?>
									        <span class="mark-unread"><a href="<?php echo esc_url(add_query_arg(array('action' => 'unread', 'entry_id' => $entry->id, '_wpnonce' => wp_create_nonce('unread_entry_' . $entry->id)), 'admin.php?page=wpfb-form-entry')); ?>" title="<?php esc_attr_e('Mark as unread', 'form-builder-wp'); ?>"><?php esc_html_e('Mark as unread','form-builder-wp'); ?></a> |</span>
									    <?php endif; ?>
									    <span class="trash"><a class="submitdelete " title="<?php esc_attr_e('Delete this entry', 'form-builder-wp'); ?>" href="<?php echo esc_url(add_query_arg(array('action' => 'delete', 'entry_id' => $entry->id, '_wpnonce' => wp_create_nonce('delete_entry_' . $entry->id)), 'admin.php?page=wpfb-form-entry')); ?>"><?php esc_html_e('Delete','form-builder-wp'); ?></a></span>
									</div>	
	                            </td>
	                            <td class="wpfb-form-entry-form-name">
	                            	<a href="<?php echo get_edit_post_link($entry->form_id); ?>" title="<?php esc_attr_e('Edit Form','form-builder-wp')?>"><?php echo get_the_title($entry->form_id)?></a>
	                            </td>
	                        </tr>
	                    <?php endforeach; ?>
	                <?php else: ?>
	                    <tr class="no-items">
	                        <td colspan="<?php echo (count($columns) + 1); ?>" class="colspanchange"><p><?php esc_html_e('No entries found.', 'form-builder-wp'); ?></p></td>
	                    </tr>
	                <?php endif; ?>
	                </tbody>
	        </table>
	        <div class="tablenav bottom">
	            <div class="alignleft actions bulkactions">
	                <select name="bulk_action2">
	                    <option selected="selected" value="-1"><?php esc_html_e('Bulk Actions', 'form-builder-wp'); ?></option>
	                    <option value="read"><?php esc_html_e('Mark as read', 'form-builder-wp'); ?></option>
	                    <option value="unread"><?php esc_html_e('Mark as unread', 'form-builder-wp'); ?></option>
	                    <option value="delete"><?php esc_html_e('Delete', 'form-builder-wp'); ?></option>
	                </select>
	                <input type="submit" value="<?php esc_attr_e('Apply', 'form-builder-wp'); ?>" class="button action wpfb-form-action2" id="doaction" name="" />
	            </div>
	            <?php echo $this->_get_pagination($limit, $total, 'buttom'); ?>
	            <br class="clear" />
	        </div>
		</form>
	</div>
	<?php
		}
		
		protected function _view_entry(){
			global $wpfbform_db;
			$entry_id = isset($_GET['entry_id']) ? absint($_GET['entry_id']) : 0;
			$entry = $wpfbform_db->get_entry($entry_id);
			if(!empty($entry)):
			
			//mask as read
			$wpfbform_db->read_entry($entry_id);
			$form_control = get_post_meta($entry->form_id,'_form_control',true);
			$current_user = wp_get_current_user();
		
			$action = isset($_POST['action']) ? $_POST['action'] : '';
			switch ($action){
				case 'add_note':
					check_admin_referer('_wpfb_form_entry_note', '_wpfb_form_entry_note');
					$note_data = array(
						'entry_id'=>$entry->id,
						'user_id'=>( isset( $current_user->ID ) ? (int) $current_user->ID : 0 ),
						'message'=>isset($_POST['entry_message']) ? $_POST['entry_message']:'',
						'created'=>gmdate('Y-m-d H:i:s'),
					);
					$wpfbform_db->insert_entry_note($note_data);
					break;
				case 'delete_note':
					check_admin_referer('_wpfb_form_entry_note', '_wpfb_form_entry_note');
					$note_id = isset($_POST['note_id']) ? absint($_POST['note_id']) : 0;
					$wpfbform_db->delete_entry_note($note_id);
					break;
				default:
					break;
			}
		
			?>
		<div class="wrap">
			<h2><?php echo sprintf(__('Entry "%s"','form-builder-wp'),$entry->id)?></h2>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="postbox ">
							<div class="handlediv" title="<?php echo esc_html_e('Click to toggle','form-builder-wp') ?>"><br></div>
							<h3 class="hndle"><span><?php echo esc_html_e('Submitted form data','form-builder-wp')?></span></h3>
							<div class="inside">
								<div class="wpfbform_options">
									<?php if($form_control):?>
									<?php $entry_data =  maybe_unserialize($entry->entry_data);?>
									<?php $form_control_arr = $form_control?>
									<?php foreach ($form_control_arr as $control):?>
										<?php $control_name = isset($control['control_name'])? $control['control_name'] : null;?>
										<?php if($control_name && isset($entry_data[$control_name])):?>
											<div class="form-field">
												<label><strong><?php echo (!empty($control['control_label']) ? $control['control_label'] : $control_name)?></strong></label>
												<div>
												<?php if('wpfb_form_file'!==$control['tag']):?>
													<?php if($control['tag'] === 'wpfb_form_password'):?>
														<?php if(!apply_filters('wpfb_form_password_view_entry_password', false)):?>
															<?php echo '*****' ?>
														<?php else:?>
															<?php echo $entry_data[$control_name] ?>
														<?php endif;?>
													<?php else:?>
														<?php echo is_array($entry_data[$control_name]) ? implode(', ', $entry_data[$control_name]) : $entry_data[$control_name] ?>
													<?php endif;?>
												<?php elseif('wpfb_form_file'===$control['tag']):?>
													<?php 
													$file_arr = $entry_data[$control_name];
													$file_name = isset($file_arr['file_name']) && !empty($file_arr['file_name']) ? $file_arr['file_name'] : null;
													if($file_name):
													?>
													<a href="<?php echo $file_arr['file_url'] ?>" title="<?php echo esc_html_e('Click to download','form-builder-wp')?>"><?php echo ($file_name ?$file_name:'No filename')?></a>
													<?php endif;?>
												<?php endif;?>
												</div>
											</div>
										<?php endif;?>
									<?php endforeach;?>
									<?php endif;?>
								</div>
							</div>
						</div>
						
						<div class="postbox" id="entry_note_box">
							<div class="handlediv" title="<?php echo esc_html_e('Click to toggle','form-builder-wp') ?>"><br></div>
							<h3 class="hndle"><span><?php echo esc_html_e('Notes','form-builder-wp')?></span></h3>
							<div class="inside">
								<form method="post" id="entry_note_form">
									<input id="action" type="hidden" value="" name="action">
									<input id="note_id" type="hidden" value="0" name="note_id">
		                            <?php wp_nonce_field('_wpfb_form_entry_note', '_wpfb_form_entry_note') ?>
		                            <table class="widefat fixed entry-detail-notes">
		                            	<tbody id="the-comment-list" class="list:comment">
		                            		<?php 
		                            		$notes = $wpfbform_db->get_entry_notes($entry->id);
		                            		if(count($notes)):
		                            		?>
		                            		<?php foreach ($notes as $note):?>
		                            		<?php $note_author = get_userdata($note->user_id);?>
		                            		<tr valign="top">
						                        <td class="entry-note">
						                            <div style="margin-top:4px;">
						                                <div class="note-avatar"><?php echo  get_avatar($note->user_id, 48) ?></div>
						                                <div class="note-author"> <?php echo esc_html($note_author->display_name)?></div>
						                                <p style="line-height:130%; text-align:left; margin-top:3px;">
						                                	<a href="mailto:<?php echo esc_attr($note_author->user_email)?>"><?php echo esc_html($note_author->user_email) ?></a><br />
						                                	<span style="font-size: 11px;color: #999">
						                                	<?php _e("added on", 'form-builder-wp'); ?> <?php echo esc_html(mysql2date( __( 'Y/m/d g:i:s A' ),$note->created,true )) ?>  <a href="javascript:void(0)" id="delete_note" data-note-id = "<?php echo $note->id ?>" style="color: #a00;text-decoration: underline;"><?php _e('Delete note','form-builder-wp')?></a>
						                                	</span>	
						                                </p>
						                            </div>
						                            <div class="detail-note-content"><?php echo esc_html($note->message) ?></div>
						                        </td>
							                </tr>
		                            		<?php endforeach;?>
		                            		<?php endif;?>
							                <tr>
												<td style="padding:10px;" class="lastrow">
													<textarea name="entry_message" style="width:100%; height:50px; margin-bottom:4px;"></textarea>
													<?php
													$note_button = '<input type="button" id="add_note" name="add_note" value="' . __("Add Note", 'form-builder-wp') . '" class="button" style="width:auto;padding-bottom:2px;"/>';
													echo $note_button;
													?>
												</td>
											</tr>
							        	</tbody>
		                            </table>       
		                        </form>
							</div>
						</div>
					</div>
					<div id="postbox-container-1" class="postbox-container">
						<div class="postbox ">
							<div class="handlediv" title="<?php echo esc_html_e('Click to toggle','form-builder-wp') ?>"><br></div>
							<h3 class="hndle"><span><?php echo esc_html_e('Additional information','form-builder-wp')?></span></h3>
							<div class="inside">
								<div class="wpfbform_additional_information">
									<p>
										<label><strong><?php echo esc_html_e('Date','form-builder-wp') ?>:</strong></label>
										<span style="display: block;margin:5px 0 0"><?php echo mysql2date( __( 'Y/m/d g:i:s A' ),$entry->submitted,true ); ?></span>
									</p>
									<p>
										<label><strong><?php echo esc_html_e('Form','form-builder-wp') ?>:</strong></label>
										<span style="display: block;margin:5px 0 0;"><a href="<?php echo get_edit_post_link($entry->form_id); ?>"><?php echo get_the_title($entry->form_id); ?></a></span>
									</p>
									<p>
										<label><strong><?php echo esc_html_e('Embed Url','form-builder-wp') ?>:</strong></label>
										<span style="display: block;margin:5px 0 0;"><a href="<?php echo $entry->form_url; ?>"><?php echo $entry->form_url; ?></a></span>
									</p>
									<?php if(!empty($entry->user_id) &&  $usermeta = get_userdata($entry->user_id)):?>
									<p>
										<label><strong><?php echo esc_html_e('User','form-builder-wp') ?>:</strong></label>
										<span style="display: block;margin:5px 0 0;">
											<a href="user-edit.php?user_id=<?php echo absint($entry->user_id) ?>" title="<?php _e("View user profile",'form-builder-wp'); ?>"><?php echo esc_html($usermeta->user_login) ?></a>                                     
										</span>
									</p>
									<?php endif;?>
									<p>
										<label><strong><?php echo esc_html_e('IP Address','form-builder-wp') ?>:</strong></label>
										<span style="display: block;margin:5px 0 0;"> <?php echo $entry->ip_address; ?> </span>
									</p>
								</div>	
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		endif;
	}
}

new Form_Builder_Wp_Entries();