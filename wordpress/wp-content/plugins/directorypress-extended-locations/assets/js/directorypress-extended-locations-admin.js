(function( $ ) {
	'use strict';
	
	$(function() {
		directorypress_create_location_level_form();
		directorypress_create_location_level_callback();
	});
	
	window.directorypress_create_location_level_form = function () {
		jQuery(document).on('click', '.directorypress-location-action-link', function (e) { 
			
			var _this = jQuery(this);
			
			var id = _this.attr('data-id');
			
			var callback = '';
			
			//get offcanvas title from action link
			var field_title = _this.attr('data-title');
				
			// link action to decide callback
			var action = _this.attr('data-action');
				
			// set offcanvas title
			jQuery('#directorypress-offcanvas-title').text(field_title);
				
			// set callback link id
			jQuery('.location_callback_link').attr("data-id", id);
				
			// set callback link action
			jQuery('.location_callback_link').attr("data-callback", action);
			
			//var footer = '<button type="button" class="btn btn-primary edit-locationlevel-action-button" data-id="'+id+'">Save</button><button type="button" class="btn btn-default cancel-btn" data-dismiss="modal">Cancel</button>';
			
			// declare callback based on action link data
			if(action == 'create_location'){
					jQuery('.offcanvas-footer .location_callback_link').removeClass('btn-danger').addClass('btn-primary').text('Create');
					jQuery('.offcanvas-footer .location_callback_link').removeClass('btn-success').addClass('btn-primary');
					callback = 'dpel_create_new_form';
			}else if(action == 'location_edit'){
					jQuery('.offcanvas-footer .location_callback_link').removeClass('btn-danger').addClass('btn-success').text('Update');
					jQuery('.offcanvas-footer .location_callback_link').removeClass('btn-primary').addClass('btn-success');
					callback = 'dpel_edit_form';
			}else if(action == 'location_delete'){
					jQuery('.offcanvas-footer .location_callback_link').removeClass('btn-success').addClass('btn-danger').text('Delete');
					jQuery('.offcanvas-footer .location_callback_link').removeClass('btn-primary').addClass('btn-danger');
					callback = 'dpel_delete_form';
			}
			
			jQuery('.offcanvas-body').html(dp_offcanvas_loader);
			
			jQuery.ajax({
				type: "POST",
				url: directorypress_js_instance.ajaxurl,
				data: { 'action': callback, 'id': id},
				dataType: "html",
				success: function (response) {
					jQuery('.offcanvas-body').find(dp_offcanvas_loader_wrapper).remove();
					jQuery('.offcanvas-body').html(response);
					jQuery('.offcanvas-body form .id').html('<input type="hidden" name="id" value="'+id+'">');
				}
			});
		});
	
	};
	
	window.directorypress_create_location_level_callback = function () {
		jQuery(document).on('click', '.location_callback_link', function (e) {
			
				
			var _this = jQuery(this);
			var callback = '';
			var id = _this.attr('data-id');
			var data = '';
			var after_ajax_text = '';
			var action = _this.attr('data-callback');
			var Form = jQuery('.offcanvas-body form').serialize();
			
			if(action == 'create_location'){
					jQuery('.offcanvas-footer .location_callback_link').removeClass('btn-danger').addClass('btn-primary').text('Create');
					jQuery('.offcanvas-footer .location_callback_link').removeClass('btn-success').addClass('btn-primary');
					callback = 'dpel_create_new_callback';
					after_ajax_text = 'Created';
			}else if(action == 'location_edit'){
					jQuery('.offcanvas-footer .location_callback_link').removeClass('btn-danger').addClass('btn-success').text('Update');
					jQuery('.offcanvas-footer .location_callback_link').removeClass('btn-primary').addClass('btn-success');
					callback = 'dpel_edit_callback';
					after_ajax_text = 'Updated';
			}else if(action == 'location_delete'){
					jQuery('.offcanvas-footer .location_callback_link').removeClass('btn-success').addClass('btn-danger').text('Delete');
					jQuery('.offcanvas-footer .location_callback_link').removeClass('btn-primary').addClass('btn-danger');
					callback = 'dpel_delete_callback';
					after_ajax_text = 'Deleted';
			}
			if(action == 'location_delete'){
					data = { 'action': callback, 'id': id};
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
					if(action == 'create_location'){
						if(jQuery('.offcanvas-body').find('.alert-danger').length != 0){
							_this.prop('disabled', false).text('Create');
						}else{
							_this.prop('disabled', true).text(after_ajax_text);
						}
						
					}else if(action == 'location_edit'){
						_this.prop('disabled', false).text(after_ajax_text);
					}else{
						_this.prop('disabled', true).text(after_ajax_text);
					}
					locations_list();
				}
			});
		});
	};
	
	window.locations_list = function () {
		jQuery(document).on('hide.bs.offcanvas', '.offcanvas', function () {
			jQuery('.location_callback_link').prop('disabled', false);
			jQuery('#locations_list').html(loader);
			jQuery.ajax({
				type: "POST",
				url: directorypress_js_instance.ajaxurl,
				data: { 'action': 'dpel_location_list'},
				dataType: "html",
				success: function (response) {
					jQuery('#locations_list').find(loader_wrapper).remove();
					jQuery('#locations_list').html(response);	
				}
			});
		});
		 
	};
})( jQuery );
