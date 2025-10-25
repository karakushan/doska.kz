function wpfb_form_select_variable(element){
	var select = jQuery(element);
	var field = select.next()[0];
	var value = select.val();
	if(value != ''){
		//IE support
        if (document.selection)
        {
        	field.focus();
            sel = document.selection.createRange();
            sel.text = value;
        }

        //Mozilla/Firefox/Netscape 7+ support
        else if (field.selectionStart || field.selectionStart == '0')
        {
            var startPos = field.selectionStart;
            var endPos = field.selectionEnd;
            field.value = field.value.substring(0, startPos)+ value + field.value.substring(endPos, field.value.length);
        }

        else
        {
        	field.value += value;
        }
	}
	select.focus();
	select.val('');
}


function wpfb_form_recipient_remove(element){
	var element = jQuery(element);
	element.closest('tr').remove();
	return false;
}

function wpfb_form_recipient_add(element){
	var element = jQuery(element),
		recipient_table = element.closest('table'),
		name = recipient_table.data('name') + '[]',
		tmpl = jQuery(wpfb_form_admin.recipient_tmpl);
		tmpl.find('input').attr('name',name);
		
		recipient_table.append(tmpl);
		
	return false;
}

(function ($,window) {
	"use strict"; // jshint ;_;

	var setting_email = function(select){
		var smtp_arr = ['smtp_host','smtp_post','smtp_encryption','smtp_username','smtp_password'];
		if(select.val() == 'default'){
			$.each(smtp_arr,function(index,value){
				$('#'+value).closest('tr').hide();
			});
		}else{
			$.each(smtp_arr,function(index,value){
				$('#'+value).closest('tr').show();
			});
		}
	}
	setting_email($('#email_method'));
	$(document).on('change','#email_method',function(){
		setting_email($(this));
	});
	
	//sender email select
	var notice_email_type = function(select){
		if(select.is(':hidden'))
			return;
		
		var notice_email_field = $('p.notice_email_field');
		var notice_variables_field = $('p.notice_variables_field');
		notice_email_field.hide();
		notice_variables_field.hide();
		if(select.val() == 'email_text'){
			notice_email_field.show();
		}else if(select.val() == 'email_field'){
			notice_variables_field.show();
		}
	}
	notice_email_type($('select#notice_email_type'));
	$(document).on('change','select#notice_email_type',function(){
		notice_email_type($(this));
	})
	
	var setting_notice = function(checkbox){
		var notice_arr  = ['notice_name_field','notice_email_type_field','notice_variables_field','notice_email_field','notice_recipients_field','notice_reply_to_field','notice_subject_field','notice_body_field','notice_html_field'];
		$.each(notice_arr,function(index,value){
			$('.'+value).hide();
		});
		if(checkbox.is(':checked')){
			$.each(notice_arr,function(index,value){
				$('.'+value).show();
			});
		}
		notice_email_type($('select#notice_email_type'));
	}
	setting_notice($('input#notice'));
	$(document).on('click','input#notice',function(){
		setting_notice($(this));
	});
	
	
	var setting_reply = function(checkbox){
		var notice_arr  = ['reply_name_field','reply_email_field','reply_recipients_field','reply_subject_field','reply_body_field','reply_html_field'];
		$.each(notice_arr,function(index,value){
			$('p.'+value).hide();
		});
		if(checkbox.is(':checked')){
			$.each(notice_arr,function(index,value){
				$('p.'+value).show();
			});
		}
	}
	setting_reply($('input#reply'));
	$(document).on('click','input#reply',function(){
		setting_reply($(this));
	});
	
	var setting_action = function(select){
		if(select.val() == 'default'){
			$('p.action_url_field').hide();
			$('p.method_field').hide();
			$('p.form_action_field').show();
		}else{
			$('p.form_action_field').hide();
			$('p.method_field').show();
			$('p.action_url_field').show();
		}
	}
	setting_action($('select#action_type'));
	$(document).on('change','select#action_type',function(){
		setting_action($(this));
	});
	var setting_on_success = function(select){
		var message_field = $('p.message_field');
		var message_position_field = $('p.message_position_field');
		var redirect_to_field = $('p.redirect_to_field');
		redirect_to_field.hide();
		message_field.hide();
		message_position_field.hide();
		if(select.val() == 'redirect'){
			redirect_to_field.show();
			$('select#redirect_to').trigger('change');
		}else{
			message_field.show();
			message_position_field.show();
			var page_field = $('p.page_field');
			var post_field = $('p.post_field');
			var url_field  = $('p.url_field');
			page_field.hide();
			post_field.hide();
			url_field.hide();
			
		}
		
	}
	setting_on_success($('select#on_success'));
	$(document).on('change','select#on_success',function(){
		setting_on_success($(this));
	})
	
	var setting_redirect_to = function(select){
		if(select.is(':hidden'))
			return;
		
		var page_field = $('p.page_field');
		var post_field = $('p.post_field');
		var url_field  = $('p.url_field');
		page_field.hide();
		post_field.hide();
		url_field.hide();
		if(select.val() == 'to_page'){
			page_field.show();
		}else if(select.val() == 'to_post'){
			post_field.show();
		}else{
			url_field.show();
		}
	}
	
	setting_redirect_to($('select#redirect_to'));
	$(document).on('change','select#redirect_to',function(){
		setting_redirect_to($(this));
	})
	
	//form action select
	var form_action = function(select){
		if(select.is(':hidden'))
			return;
		
		var mailpoet_field = $('p.mailpoet_field'),
			mymail_field = $('p.mymail_field'),
			groundhogg_tags_field = $('p.groundhogg_tags_field'),
			mymail_double_opt_in = $('p.mymail_double_opt_in_field');
		
			groundhogg_tags_field.hide();
			mailpoet_field.hide();
			mymail_field.hide();
			mymail_double_opt_in.hide();
			
		if(select.val() == 'mailpoet'){
			mailpoet_field.show();
		}else if(select.val() == 'mymail'){
			mymail_field.show();
			mymail_double_opt_in.show();
		}else if(select.val() == 'groundhogg'){
			groundhogg_tags_field.show();
		}
	}
	
	form_action($('select#form_action'));
	$(document).on('change','select#form_action',function(){
		form_action($(this));
	})
	
	var setting_popup = function(checkbox){
		var popup_arr  = ['form_popup_title_field','form_popup_width_field','form_popup_labelpopup_field','form_popup_auto_open_field','form_popup_auto_open_delay_field','form_popup_auto_close_field','form_popup_auto_close_delay_field','form_popup_one_field'];
		$.each(popup_arr,function(index,value){
			$('p.'+value).hide();
		});
		if(checkbox.is(':checked')){
			$.each(popup_arr,function(index,value){
				$('p.'+value).show();
			});
		}
	}
	setting_popup($('input#form_popup'));
	$(document).on('click','input#form_popup',function(){
		setting_popup($(this));
	});
	
	var setting_form_popup_auto_open = function(checkbox){
		if(checkbox.is(':hidden'))
			return;
		
		var show_arr = ['form_popup_auto_open_delay_field','form_popup_auto_close_field','form_popup_auto_close_delay_field','form_popup_one_field'];
		if(checkbox.is(':checked')){
			$.each(show_arr,function(index,value){
				$('p.'+value).show();
			});
		}else{
			$.each(show_arr,function(index,value){
				$('p.'+value).hide();
			});
		}
	}
	setting_form_popup_auto_open($('input#form_popup_auto_open'));
	$(document).on('click','input#form_popup_auto_open',function(){
		setting_form_popup_auto_open($(this));
	});
	
	
	$(document).on('click','.wpfb-form-entry-list .submitdelete',function(){
		return confirm(wpfb_form_admin.delete_confirm);
	});
	
	$(document).on('click','a#wpfb_form_submitdelete',function(){
		return confirm(wpfb_form_admin.delete_confirm);
	});
	
	$('.wpfb-form-action,.wpfb-form-action2').on('click',function(){
		var action = $(this).closest('.bulkactions').find('select');
		if ($(this).closest('form').find('input[name="entry[]"]:checked').length > 0) {
			if (action.val() == 'delete') {
				return confirm(wpfb_form_admin.delete_confirm);
			}
		} else {
			return false;
		}
	});
	
	$('.wpfb-form-entry-select-action').change(function(){
		$('#wpfb_form_entry').submit();
	});
	
	$('#wpfbform-actions input, #wpfbform-actions a').on('click', function(){
		window.onbeforeunload = '';
	});
	
	$('#entry_note_form #add_note').on('click',function(e){
		$('#entry_note_form  #action').val('add_note');
		$('#entry_note_box').block({ message: null, overlayCSS: { background: '#fff url(' + wpfb_form_admin.plugin_url + 'assets/images/ajax-loader.gif) no-repeat center', opacity: 0.6 } });
		
		$.post(window.location,$('#entry_note_form').serialize(),function(){
			window.location = window.location.href;
		});
		return false;
	});
	
	$('#entry_note_form #delete_note').on('click',function(e){
		$('#entry_note_form  #action').val('delete_note');
		$('#entry_note_form  #note_id').val($(this).data('note-id'));
		$('#entry_note_box').block({ message: null, overlayCSS: { background: '#fff url(' + wpfb_form_admin.plugin_url + 'assets/images/ajax-loader.gif) no-repeat center', opacity: 0.6 } });
		
		$.post(window.location,$('#entry_note_form').serialize(),function(){
			window.location = window.location.href;
		});
		return false;
	});
	
})(jQuery,window);