(function( $ ) {
	'use strict';
	$(function() {
		directorypress_create_directory_form();
		directorypress_create_directory_callback();
	});
	
	window.directorypress_create_directory_form = function () {
		jQuery(document).on('click', '.directorypress-directory-action-link', function (e) { 
			
			var _this = jQuery(this);
			
			var id = _this.attr('data-id');
			
			var callback = '';
			var after_ajax_text = '';
			
			//get offcanvas title from action link
			var field_title = _this.attr('data-title');
				
			// link action to decide callback
			var action = _this.attr('data-action');
				
			// set offcanvas title
			jQuery('#directorypress-offcanvas-title').text(field_title);
				
			// set callback link id
			jQuery('.directory_callback_link').attr("data-id", id);
				
			// set callback link action
			jQuery('.directory_callback_link').attr("data-callback", action);
			
			//var footer = '<button type="button" class="btn btn-primary edit-locationlevel-action-button" data-id="'+id+'">Save</button><button type="button" class="btn btn-default cancel-btn" data-bs-dismiss="modal">Cancel</button>';
			
			// declare callback based on action link data
			jQuery('.offcanvas-footer').show();
			if(action == 'create_directory'){
					jQuery('.offcanvas-footer .directory_callback_link').removeClass('btn-danger').addClass('btn-primary').text('Create');
					jQuery('.offcanvas-footer .directory_callback_link').removeClass('btn-success').addClass('btn-primary');
					callback = 'dpmd_create_new_form';
					after_ajax_text = 'Created';
			}else if(action == 'directory_edit'){
					jQuery('.offcanvas-footer .directory_callback_link').removeClass('btn-danger').addClass('btn-success').text('Update');
					jQuery('.offcanvas-footer .directory_callback_link').removeClass('btn-primary').addClass('btn-success');
					callback = 'dpmd_edit_form';
					after_ajax_text = 'Updated';
			}else if(action == 'directory_delete'){
					jQuery('.offcanvas-footer .directory_callback_link').removeClass('btn-success').addClass('btn-danger').text('Delete');
					jQuery('.offcanvas-footer .directory_callback_link').removeClass('btn-primary').addClass('btn-danger');
					callback = 'dpmd_delete_form';
					after_ajax_text = 'Deleted';
			}else if(action == 'directory_info'){
					jQuery('.offcanvas-footer').hide();
					callback = 'dpmd_directory_info';
			}
			
			jQuery('.offcanvas-body').html(dp_offcanvas_loader);
			//_this.html(button_loader).prop('disabled', true);
			
			jQuery.ajax({
				type: "POST",
				url: directorypress_js_instance.ajaxurl,
				data: { 'action': callback, 'id': id},
				dataType: "html",
				success: function (response) {
					jQuery('.offcanvas-body').find(dp_offcanvas_loader_wrapper).remove();
					jQuery('.offcanvas-body').html(response);
					//_this.html(button_loader).prop('disabled', false);
					jQuery('.offcanvas-body form .id').html('<input type="hidden" name="id" value="'+id+'">');
				}
			});
		});
	
	};
	
	window.directorypress_create_directory_callback = function () {
		jQuery(document).on('click', '.directory_callback_link', function (e) {
			
				
			var _this = jQuery(this);
			var callback = '';
			var after_ajax_text = '';
			var id = _this.attr('data-id');
			var new_directory_id = '';
			var data = '';
			var action = _this.attr('data-callback');
			var Form = jQuery('.offcanvas-body form').serialize();
			
			if(action == 'create_directory'){
					jQuery('.offcanvas-footer .directory_callback_link').removeClass('btn-danger').addClass('btn-primary').text('Create');
					jQuery('.offcanvas-footer .directory_callback_link').removeClass('btn-success').addClass('btn-primary');
					callback = 'dpmd_create_new_callback';
					after_ajax_text = 'Created';
			}else if(action == 'directory_edit'){
					jQuery('.offcanvas-footer .directory_callback_link').removeClass('btn-danger').addClass('btn-success').text('Update');
					jQuery('.offcanvas-footer .directory_callback_link').removeClass('btn-primary').addClass('btn-success');
					callback = 'dpmd_edit_callback';
					after_ajax_text = 'Updated';
			}else if(action == 'directory_delete'){
					jQuery('.offcanvas-footer .directory_callback_link').removeClass('btn-success').addClass('btn-danger').text('Delete');
					jQuery('.offcanvas-footer .directory_callback_link').removeClass('btn-primary').addClass('btn-danger');
					callback = 'dpmd_delete_callback';
					after_ajax_text = 'Deleted';
			}
			if(action == 'directory_delete'){
					new_directory_id = jQuery(".offcanvas-body input[name='new_directory']:checked"). val();
					data = { 'action': callback, 'id': id, 'new_directory_id': new_directory_id};
			}else{
					data = Form + '&action='+callback;
			}
			jQuery('.offcanvas-body').html(dp_offcanvas_loader);
			_this.html(button_loader).prop('disabled', true);
			
			jQuery.ajax({
				type: "POST",
				url: directorypress_js_instance.ajaxurl,
				data: data,
				dataType: "html",
				success: function (response) {
					jQuery('.offcanvas-body').find(dp_offcanvas_loader_wrapper).remove();
					_this.find(button_loader_wrapper).remove();
					
					jQuery('.offcanvas-body').html(response);	
					jQuery('.offcanvas-body form .id').html('<input type="hidden" name="id" value="'+id+'">');
					if(action == 'create_directory'){
						if(jQuery('.offcanvas-body').find('.alert-danger').length != 0){
							_this.prop('disabled', false).text('Create');
						}else{
							_this.prop('disabled', true).text(after_ajax_text);
						}
						
					}else if(action == 'directory_edit'){
						_this.prop('disabled', false).text(after_ajax_text);
					}else{
						_this.prop('disabled', true).text(after_ajax_text);
					}
					directories_list();
				}
			});
		});
	};

	window.directories_list = function () {
		jQuery(document).on('hide.bs.offcanvas', '.offcanvas', function () {
			jQuery('.directory_callback_link').prop('disabled', false);
			jQuery('#directories_list form').html(loader);
			jQuery.ajax({
				type: "POST",
				url: directorypress_js_instance.ajaxurl,
				data: { 'action': 'dpmd_directory_list'},
				dataType: "html",
				success: function (response) {
					jQuery('#directories_list form').find(loader_wrapper).remove();
					jQuery('#directories_list form').html(response);	
				}
			});
		});
		 
	};
})( jQuery );
