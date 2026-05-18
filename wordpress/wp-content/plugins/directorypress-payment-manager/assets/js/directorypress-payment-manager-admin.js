(function( $ ) {
	'use strict';
	
	$(function() {
		directorypress_create_package_form();
		directorypress_create_package_callback();
		directorypress_pricing();
	});
	
	window.directorypress_create_package_form = function () {
		jQuery(document).on('click', '.directorypress-package-action-link', function (e) { 
			
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
			jQuery('.package_callback_link').attr("data-id", id);
				
			// set callback link action
			jQuery('.package_callback_link').attr("data-callback", action);
			
			//var footer = '<button type="button" class="btn btn-primary edit-locationlevel-action-button" data-id="'+id+'">Save</button><button type="button" class="btn btn-default cancel-btn" data-dismiss="modal">Cancel</button>';
			
			// declare callback based on action link data
			jQuery('.offcanvas-footer').show();
			
			if(action == 'create_package'){
					jQuery('.offcanvas-footer .package_callback_link').removeClass('btn-danger').addClass('btn-primary').text('Create');
					jQuery('.offcanvas-footer .package_callback_link').removeClass('btn-success').addClass('btn-primary');
					callback = 'dppm_create_new_form';
			}else if(action == 'package_edit'){
					jQuery('.offcanvas-footer .package_callback_link').removeClass('btn-danger').addClass('btn-success').text('Update');
					jQuery('.offcanvas-footer .package_callback_link').removeClass('btn-primary').addClass('btn-success');
					callback = 'dppm_edit_form';
			}else if(action == 'package_upgrade'){
					jQuery('.offcanvas-footer .package_callback_link').removeClass('btn-danger').addClass('btn-success').text('Update');
					jQuery('.offcanvas-footer .package_callback_link').removeClass('btn-primary').addClass('btn-success');
					callback = 'dppm_upgrade_downgrade_form';
			}else if(action == 'package_pricing'){
					jQuery('.offcanvas-footer .package_callback_link').removeClass('btn-danger').addClass('btn-success').text('Update');
					jQuery('.offcanvas-footer .package_callback_link').removeClass('btn-primary').addClass('btn-success');
					callback = 'directorypress_create_woo_package_product';
					//after_ajax_text = 'Updated';
			}else if(action == 'package_delete'){
					jQuery('.offcanvas-footer .package_callback_link').removeClass('btn-success').addClass('btn-danger').text('Delete');
					jQuery('.offcanvas-footer .package_callback_link').removeClass('btn-primary').addClass('btn-danger');
					callback = 'dppm_delete_form';
			}else if(action == 'package_info'){
					jQuery('.offcanvas-footer').hide();
					callback = 'dppm_package_info';
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
	
	window.directorypress_create_package_callback = function () {
		jQuery(document).on('click', '.package_callback_link', function (e) {
			
				
			var _this = jQuery(this);
			var callback = '';
			var id = _this.attr('data-id');
			var new_directory_id = '';
			var after_ajax_text = '';
			var data = '';
			var action = _this.attr('data-callback');
			var Form = jQuery('.offcanvas-body form').serialize();
			
			if(action == 'create_package'){
					jQuery('.offcanvas-footer .package_callback_link').removeClass('btn-danger').addClass('btn-primary').text('Create');
					jQuery('.offcanvas-footer .package_callback_link').removeClass('btn-success').addClass('btn-primary');
					callback = 'dppm_create_new_callback';
					after_ajax_text = 'Created';
			}else if(action == 'package_edit'){
					jQuery('.offcanvas-footer .package_callback_link').removeClass('btn-danger').addClass('btn-success').text('Update');
					jQuery('.offcanvas-footer .package_callback_link').removeClass('btn-primary').addClass('btn-success');
					callback = 'dppm_edit_callback';
					after_ajax_text = 'Updated';
			}else if(action == 'package_upgrade'){
					jQuery('.offcanvas-footer .package_callback_link').removeClass('btn-danger').addClass('btn-success').text('Update');
					jQuery('.offcanvas-footer .package_callback_link').removeClass('btn-primary').addClass('btn-success');
					callback = 'dppm_upgrade_downgrade_callback';
					after_ajax_text = 'Updated';
			}else if(action == 'package_pricing'){
					jQuery('.offcanvas-footer .package_callback_link').removeClass('btn-danger').addClass('btn-success').text('Save');
					jQuery('.offcanvas-footer .package_callback_link').removeClass('btn-primary').addClass('btn-success');
					callback = 'dppm_package_price_callback';
					after_ajax_text = 'Saved';
			}else if(action == 'package_delete'){
					jQuery('.offcanvas-footer .package_callback_link').removeClass('btn-success').addClass('btn-danger').text('Delete');
					jQuery('.offcanvas-footer .package_callback_link').removeClass('btn-primary').addClass('btn-danger');
					callback = 'dppm_delete_callback';
					after_ajax_text = 'Deleted';
			}
			
			if(action == 'package_delete'){
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
					//_this.prop('disabled', false);
					
					jQuery('.offcanvas-body').html(response);
					jQuery('.offcanvas-body form .id').html('<input type="hidden" name="id" value="'+id+'">');
					if(action == 'create_package'){
						if(jQuery('.offcanvas-body').find('.alert-danger').length != 0){
							_this.prop('disabled', false).text('Create');
						}else{
							_this.text(after_ajax_text);
						}
					}else if(action == 'package_edit' || action == 'package_upgrade' || action == 'package_pricing'){
						_this.prop('disabled', false).text(after_ajax_text);
					}else if(action == 'package_delete'){
						_this.prop('disabled', true).text(after_ajax_text);
					}
					packages_list();
				}
			});
		});
	};
	
	window.packages_list = function () {
		jQuery(document).on('hide.bs.offcanvas', '.offcanvas', function () {
			jQuery('.package_callback_link').prop('disabled', false);
			jQuery('#packages_list .packages_list_wrapper').html(loader);
			jQuery.ajax({
				type: "POST",
				url: directorypress_js_instance.ajaxurl,
				data: { 'action': 'dppm_package_list'},
				dataType: "html",
				success: function (response) {
					jQuery('#packages_list .packages_list_wrapper').find(loader_wrapper).remove();
					jQuery('#packages_list .packages_list_wrapper').html(response);	
				}
			});
		});
		 
	};
	// assign field group
	window.directorypress_pricing = function(){
		jQuery(document).on('click', '.directorypress-package-price-action-link', function (e) { 
			e.preventDefault();
			var id = jQuery(this).attr('data-id');
			jQuery.ajax({
				type: "POST",
				url: directorypress_js_instance.ajaxurl,
				data: { 'action': 'directorypress_create_woo_package_product', 'id': id},
				dataType: "json",
				success: function (response) {
					if(response.type == 'success'){
						window.open(response.message, '_blank');
					}
					

				}
			});
		});
	}
	$(function() {
			$("#packages_list .packages_list_wrapper .dp-list-section").sortable({
				placeholder: "ui-sortable-placeholder",
				helper: function(e, ui) {
					ui.children().each(function() {
						//$(this).width($(this).width());
					});
					return ui;
				},
				start: function(e, ui){
					ui.placeholder.height(ui.item.height());
				},
				update: function( event, ui ) {
					$("#packages_order").val($(".package_weight_id").map(function() {
						return $(this).val();
					}).get());
				},
				stop: function( event, ui ) {
					var new_order = $("#packages_order").val();
					$('#packages_list .order-response').append(loader);
					$.ajax({
						type: "POST",
						url: directorypress_js_instance.ajaxurl,
						data: { 'action': 'dppm_reorder', 'new_order': new_order},
						dataType: "html",
						success: function (response) {
							$('#packages_list .order-response').find('.dpbackend-loader-wrapper').remove();
							$('#packages_list .order-response').html(response);	
						}
					});
				}
		    }).disableSelection();
		});
})( jQuery );
