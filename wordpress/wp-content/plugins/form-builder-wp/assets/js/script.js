/*
 *DHVC Form Script 
 * @param jQuery
 */
window.wpfb_form_recatptcha_widgets = [];
!function( $ ) {
	"use strict"; // jshint ;_;
	
	$(document).ready(function () {
		
		$('[data-auto-open].wpfb-form-popup').each(function(){
			var $this = $(this),
				id = $this.attr('id'),
				open_delay = $this.data('open-delay'),
				auto_close = $this.data('auto-close'),
				close_delay = $this.data('close-delay'),
				one_time = $this.data('one-time'),
				open_timeout,
				close_timeout;
			clearTimeout(open_timeout);
			clearTimeout(close_timeout);
			open_timeout = setTimeout(function(){
				clearTimeout(close_timeout);	
				
				if(one_time){
					if(!$.cookie(id)){
						$('.wpfb-form-pop-overlay').show();
						$('body').addClass('wpfb-form-opening');
						$this.show();
						$.cookie(id,1,{ expires: 360 * 10 , path: "/" });
					}
				}else{
					$.cookie(id,0,{ expires: -1});
					$('.wpfb-form-pop-overlay').show();
					$this.show();
				}
			},open_delay);
			
			if(auto_close){
				close_timeout = setTimeout(function(){
					clearTimeout(open_timeout);
					$('.wpfb-form-pop-overlay').hide();
					$('body').addClass('wpfb-form-opening');
					$this.hide();
					
				},close_delay);
			}
			
		});
		$(document).on('click','[data-toggle="wpfbformpopup"],[rel="wpfbformpopup"],[href^="#wpfbformpopup-"]',function(e){
			e.stopPropagation();
			e.preventDefault();
			var href;
			var $this = $(this);
			var $target = $($this.attr('data-target') || (href = $this.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '')); // strip for ie7
			if ($this.is('a')) e.preventDefault();
			$('.wpfb-form-pop-overlay').show();
			$('body').addClass('wpfb-form-opening');
			$target.show();
			$target.off('click').on('click',function(e){
				 if (e.target !== e.currentTarget) return
				$('.wpfb-form-pop-overlay').hide();
				$('body').removeClass('wpfb-form-opening');
				$target.hide();
			});
		});
		
		$(document).on('click','.wpfb-form-popup-close',function(e){
			$('.wpfb-form-pop-overlay').hide();
			$('body').removeClass('wpfb-form-opening');
			$(this).closest('.wpfb-form-popup').hide();
		});
		
		
		$('.wpfb-form-slider-control').each(function(){
			var $this = $(this);
			$this.slider({
				 min: $this.data('min'),
			     max: $this.data('max'),
			     step: $this.data('step'),
			     range: ($this.data('type') == 'range' ? true : 'min'),
			     slide: function(event, ui){
			    	 if($this.data('type') == 'range'){
			    		 $this.closest('.wpfb-form-group').find('.wpfb-form-slider-value-from').text(ui.values[0]);
			    		 $this.closest('.wpfb-form-group').find('.wpfb-form-slider-value-to').text(ui.values[1]);
			    		 $this.closest('.wpfb-form-group').find('input[type="hidden"]').val(ui.values[0] + '-' + ui.values[1]).trigger('change');
			    	 }else{
			    		 $this.closest('.wpfb-form-group').find('.wpfb-form-slider-value').text(ui.value);
			    		 $this.closest('.wpfb-form-group').find('input[type="hidden"]').val(ui.value).trigger('change');
			    	 }
			     }
			});
			if($this.data('type') == 'range'){
				$this.slider('values',[0,$this.data('minmax')]);
			}else{
				$this.slider('value',$this.data('value'));
			}
		});
		
		var basename = function(path, suffix) {
		  //  discuss at: http://phpjs.org/functions/basename/
		  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		  // improved by: Ash Searle (http://hexmen.com/blog/)
		  // improved by: Lincoln Ramsay
		  // improved by: djmix
		  // improved by: Dmitry Gorelenkov
		  //   example 1: basename('/www/site/home.htm', '.htm');
		  //   returns 1: 'home'
		  //   example 2: basename('ecra.php?p=1');
		  //   returns 2: 'ecra.php?p=1'
		  //   example 3: basename('/some/path/');
		  //   returns 3: 'path'
		  //   example 4: basename('/some/path_ext.ext/','.ext');
		  //   returns 4: 'path_ext'

		  var b = path;
		  var lastChar = b.charAt(b.length - 1);

		  if (lastChar === '/' || lastChar === '\\') {
		    b = b.slice(0, -1);
		  }

		  b = b.replace(/^.*[\/\\]/g, '');

		  if (typeof suffix === 'string' && b.substr(b.length - suffix.length) == suffix) {
		    b = b.substr(0, b.length - suffix.length);
		  }

		  return b;
		}

		
		var operators = {
		    '>': function(a, b) { return a > b },
		    '=': function(a, b) { return a == b },
		    '<': function(a, b) { return a < b }
		};
		
		var get_field_val = function(field, form){
			var $form = $(form),
				$this=$(field);
			return $this.is(':checkbox') ? $.map($form.find('[data-conditional-name=' + $this.data('conditional-name') + '].wpfb-form-value:checked'),
	                function (element) {
						return $(element).val();
	            	})
	            : ($this.is(':radio') ? $form.find('[data-conditional-name=' + $this.data('conditional-name') + '].wpfb-form-value:checked').val() : $this.val() );
	       
		}
		
		var conditional_hook = function(e){
			var $this = $(e.currentTarget),
				form = $this.closest('form'),
				container_class = wpfb_form_params.container_class,
				master_container = $this.closest(container_class),
				master_value,
				is_empty,
				conditional_data = $this.data('conditional'),
				conditional_data2=[],
				conditional_current=null;
			
			master_value = get_field_val($this,form);
	       is_empty = $this.is(':checkbox') ? !form.find('[data-conditional-name=' + $this.data('conditional-name') + '].wpfb-form-value:checked').length
                 : ( $this.is(':radio') ? !form.find('[data-conditional-name=' + $this.data('conditional-name') + '].wpfb-form-value:checked').val() : !master_value.length )  ;
	       
	       
	        if(is_empty){
	        	$.each(conditional_data,function(i,conditional){
	        		var elements = conditional.element.split(',');
	        		$.each(elements,function(index,element){
						var $this = form.find('.wpfb-form-control-'+element);
						$this.closest(container_class).addClass('wpfb-form-hidden');
						$this.trigger('change');
					});
	        	});
	        	$.each(conditional_data,function(i,conditional){
					var elements = conditional.element.split(',');
		        	if(conditional.type == 'is_empty'){
		        		if(conditional.action == 'hide'){
							$.each(elements,function(index,element){
								var $this = form.find('.wpfb-form-control-'+element);
								$this.closest(container_class).addClass('wpfb-form-hidden');
								$this.trigger('change');
							});
						}else{
							$.each(elements,function(index,element){
								var $this = form.find('.wpfb-form-control-'+element);
								$this.closest(container_class).removeClass('wpfb-form-hidden');
								$this.trigger('change');
							});
						}
		        	}
	        	});
	        }else{
	        	if ($.isNumeric(master_value))
		        {
		        	master_value = parseInt(master_value);
		        }
	        	$.each(conditional_data,function(i,conditional){
	        		if(conditional.value == master_value){
	        			conditional_current = conditional;
	        		}else{
	        			conditional_data2.push(conditional);
	        		}
	        	});
	        	if(conditional_current != null){
		        	conditional_data2.push(conditional_current)
		        	conditional_data = conditional_data2;
	        	}
				$.each(conditional_data,function(i,conditional){
					var elements = conditional.element.split(',');
					
					if(master_container.hasClass('wpfb-form-hidden')) {
						$.each(elements,function(index,element){
							var $this = form.find('.wpfb-form-control-'+element);
							$this.closest(container_class).addClass('wpfb-form-hidden');
							$this.trigger('change');
						});
					}else{
						if(conditional.type == 'not_empty'){
							if(conditional.action == 'hide'){
								$.each(elements,function(index,element){
									var $this = form.find('.wpfb-form-control-'+element);
									$this.closest(container_class).addClass('wpfb-form-hidden');
									$this.trigger('change');
								});
							}else{
								$.each(elements,function(index,element){
									var $this = form.find('.wpfb-form-control-'+element);
									$this.closest(container_class).removeClass('wpfb-form-hidden');
									$this.trigger('change');
								});
							}
						}else if(conditional.type == 'is_empty'){
							
							if(conditional.action == 'hide'){
								$.each(elements,function(index,element){
									var $this = form.find('.wpfb-form-control-'+element);
									$this.closest(container_class).removeClass('wpfb-form-hidden');
									$this.trigger('change');
								});
							}else{
								$.each(elements,function(index,element){
									var $this = form.find('.wpfb-form-control-'+element);
									$this.closest(container_class).addClass('wpfb-form-hidden');
									$this.trigger('change');
								});
							}
						}else{
							if($.isArray(master_value)){
								if($.inArray(conditional.value,master_value) > -1){
									if(conditional.action == 'hide'){
										$.each(elements,function(index,element){
											var $this = form.find('.wpfb-form-control-'+element);
											$this.closest(container_class).addClass('wpfb-form-hidden');
											$this.trigger('change');
										});
									}else{
										$.each(elements,function(index,element){
											var $this = form.find('.wpfb-form-control-'+element);
											$this.closest(container_class).removeClass('wpfb-form-hidden');
											$this.trigger('change');
										});
									}
								}else{
									if(conditional.action == 'hide'){
										$.each(elements,function(index,element){
											var $this = form.find('.wpfb-form-control-'+element);
											$this.closest(container_class).removeClass('wpfb-form-hidden');
											$this.trigger('change');
										});
									}else{
										$.each(elements,function(index,element){
											var $this = form.find('.wpfb-form-control-'+element);
											$this.closest(container_class).addClass('wpfb-form-hidden');
											$this.trigger('change');
										});
									}
								}
							}else{
								
						        if ($.isNumeric(master_value))
						        {
						        	master_value = parseInt(master_value);
						        }
						        if ($.isNumeric(conditional.value) &&  conditional.value !='0')
						        {
						        	conditional.value = parseInt(conditional.value);
						        }
								if(conditional.type != 'not_empty' && conditional.type != 'is_empty' && operators[conditional.type](master_value,conditional.value)){
									
									if(conditional.action == 'hide'){
										$.each(elements,function(index,element){
											var $this = form.find('.wpfb-form-control-'+element);
											$this.closest(container_class).addClass('wpfb-form-hidden');
											$this.trigger('change');
										});
									}else{
										$.each(elements,function(index,element){
											var $this = form.find('.wpfb-form-control-'+element);
											$this.closest(container_class).removeClass('wpfb-form-hidden');
											$this.trigger('change');
										});
									}
								}else{
									if(conditional.action == 'hide'){
										$.each(elements,function(index,element){
											var $this = form.find('.wpfb-form-control-'+element);
											$this.closest(container_class).removeClass('wpfb-form-hidden');
											$this.trigger('change');
										});
									}else{
										$.each(elements,function(index,element){
											var $this = form.find('.wpfb-form-control-'+element);
											$this.closest(container_class).addClass('wpfb-form-hidden');
											$this.trigger('change');
										});
									}
								}
							}
						}
					}
					
				});
	        }
	        return true;
		}
		
		var update_hidden_fields = function(form){
			var $form = $(form);
			var fields = [];
			$form.find('.wpfb-form-value').filter(function(){
				var name = $(this).attr('data-name') || this.name;
				if($.inArray(name,fields) >= 0 || $(this).is(":visible"))
					return false;
				fields.push(name);
				return true;
			})
			$form.find('#_wpfb_form_hidden_fields').val(JSON.stringify(fields))
		}
		
		var conditional_form = function(form,ignore_bind){
			var $form = $(form),
				master_box = $form.find('.wpfb-form-conditional'),
				ignore_bind = ignore_bind || false;
			
			if('yes'===wpfb_form_params.is_preview_mode)
				return;
			
			$.each(master_box,function(){
				var masters = $(this).find('[data-conditional].wpfb-form-value');
				if(false===ignore_bind){
					$(masters).on('keyup change',function(e){
						conditional_hook(e);
					});
				}
				$.each(masters,function(){
					var $this = $(this);
					conditional_hook({currentTarget: $this });
				});
			});
		};
		
		var form_price_format = function(form,price){
			var $form = $(form),
				currency = $form.data('currency'),
				currency_symbol = $form.data('currency_symbol'),
				price_format = $form.data('price_format');
			return price_format.replace('%s', currency_symbol).replace('%v',price);
		}
		
		var field_calculation = function(form){
			var $form = $(form),
				maths = [];
			$('[data-field_calculation]',$form).each(function(){
				var match,
					match_value=0,
					$this = $(this),
					pattern = /\[(.*?)\]/g,
					operators = $this.data('field_calculation'),
					value_format = $this.data('calculation_value_format');
				
				if(!$.isNumeric(operators)){
					if(operators.replace(/[^.*()\-+\/]+/g, '') === ''){
						var $el = $('[data-field-name=' + operators +']',$form);
						var field_value = parseFloat(get_field_val($el,$form));
						field_value = isNaN(field_value) ? 0 : field_value;
						match_value = field_value;
					}else{
						var fields = operators.split(/[*()\-+\/]/);
						$.each(fields,function(key,field){
							if(''!=field.trim()){
								var $el = $('[data-field-name=' + field +']',$form);
								if($el.length){
									var field_value = parseFloat(get_field_val($el,$form));
									field_value = isNaN(field_value) ? 0 : field_value;
									var reg = new RegExp(field, 'g');
									operators = operators.replace(reg,field_value);
								}
							}
						})
						try {
							match_value = parseFloat(eval(operators).toFixed(2));
					     } catch (e) {
					    	 match_value = 0;
					     }
					}
					match_value = value_format.replace('%v',match_value);
					if($this.hasClass('wpfb-form-control-label')){
						$this.html(match_value);
					}else{
						$this.val(match_value).prop('value',match_value);
					}
					$( document.body ).trigger( 'wpfb_field_calculation_change', [$form] );
				}
			});
		}
		
		var paypal_calculation = function(form){
			var $form = $(form),
				maths = [];
			
			$('.wpfb-paypal-calculation',$form).each(function(){
				var match,
					match_value=0,
					$this = $(this),
					pattern = /\[(.*?)\]/g,
					operators = $this.data('value-math');
				
				if(!$.isNumeric(operators)){
					if(operators.replace(/[^.*()\-+\/]+/g, '') === ''){
						var $el = $('[data-field-name=' + operators +']',$form);
						var field_value = parseFloat(get_field_val($el,$form));
						field_value = isNaN(field_value) ? 0 : field_value;
						match_value = field_value;
					}else{
						var fields = operators.split(/[*()\-+\/]/);
						$.each(fields,function(key,field){
							if(''!=field.trim()){
								//console.log(field)
								var $el = $('[data-field-name=' + field +']',$form);
								if($el.length){
									var field_value = parseFloat(get_field_val($el,$form));
									field_value = isNaN(field_value) ? 0 : field_value;
									var reg = new RegExp(field, 'g');
									operators = operators.replace(reg,field_value);
								}
							}
						})
						try {
							match_value = parseFloat(eval(operators).toFixed(2));
					     } catch (e) {
					    	 match_value = 0;
					     }
					}
					if($this.hasClass('paypal-item-price-value')){
						var match_value_formated = form_price_format($form,match_value);
						$this.html(match_value_formated);
						$this.data('result-math',match_value);
					}else{
						$this.html(match_value)
						$this.data('result-math',match_value);
					}
					$( document.body ).trigger( 'wpfb_paypal_calculation_change', [$form] );
				}
			});
			
		}
		
		$(document.body).on('wpfb_paypal_calculation_change', function(event, form) {
			var pp_total = 0,
				$form = $(form);
			$('.paypal-item-price-value',$form).each(function(){
				pp_total += parseFloat($(this).data('result-math'));
			})
			$('.paypal-total-value',$form).html(form_price_format($form,pp_total));
		});
		
		var initRecaptcha = function(){
			var $elements = $('[data-wpfbform-recaptcha="recaptcha"]');

			if (!$elements.length) {
				return;
			}
			var addRecaptcha = function($elements){
				$elements.each(function(){
					var el = this,
						$this=$(el);
					
					if($this.hasClass('wpfb-form-recaptcha2')){

						var $widget_id = grecaptcha.render(el, $this.data()),
						    $form = $this.closest('form');
						
						$this.data('widget_id', $widget_id);

						$(document.body).on('wpfb_form_submit', function(event, form, data) {
							switch ( data.status ) {
								case 'validation_failed':
								case 'spam':
								case 'success':
								case 'upload_failed':
								case 'form_not_exist':
								case 'action_failed':
								case 'call_action_failed':
									grecaptcha.reset( $this.data('widget_id') );
								break;
							}
						});
					}else{
						grecaptcha.ready(function() {
				             grecaptcha.execute($this.data('sitekey'), {action: 'homepage'}).then(function(token) {
				                 el.setAttribute( 'value', token );
				             });
				         })
					}
					
				});
			}
			
			var onRecaptchaApiReady = function(callback){
				if (window.grecaptcha && window.grecaptcha.render) {
					callback();
				} else {
					// If not ready check again by timeout..
					setTimeout(function () {
						onRecaptchaApiReady(callback);
					}, 350);
				}
			}
			
			onRecaptchaApiReady(function () {
				addRecaptcha($elements);
			});
		}
		initRecaptcha();
		

		if($.fn.xdsoftDatetimepicker && 'function' === typeof $.xdsoftDatetimepicker.setLocale){
			$.xdsoftDatetimepicker.setLocale(wpfb_form_params.datetimepicker_lang);
		}
		
		var form_submit_loading = function(form,loaded){
			loaded = loaded || false;
			var $form = $(form);
			var submit = $form.find('.wpfb-form-submit');
			var button_label = $form.find('.wpfb-form-submit-label');
			var ajax_spinner = $form.find('.wpfb-form-submit-spinner');
			if(loaded){
				submit.removeAttr('disabled');
				button_label.removeClass('wpfb-form-submit-label-hidden');
	        	ajax_spinner.hide();
			}else{
				submit.attr('disabled','disabled');
	        	button_label.addClass('wpfb-form-submit-label-hidden');
	        	ajax_spinner.show();
			}
		}
		
		if($('.wpfb-form-datepicker').length){
			$('.wpfb-form-datepicker').each(function(){
				var _this = $(this);
				_this.xdsoftDatetimepicker({
					format: wpfb_form_params.date_format,
					formatDate: wpfb_form_params.date_format,
					timepicker:false,
					scrollMonth:false,
					dayOfWeekStart: parseInt(wpfb_form_params.dayofweekstart),
					scrollTime:false,
					minDate: _this.data('min-date'),
					maxDate: _this.data('max-date'),
					yearStart: _this.data('year-start'),
					yearEnd: _this.data('year-end'),
					scrollInput:false
				});
			});
			
		}
		
		if($('.wpfb-form-timepicker').length){
			$('.wpfb-form-timepicker').each(function(){
				var _this = $(this);
				_this.xdsoftDatetimepicker({
					format: wpfb_form_params.time_format,
					formatTime: wpfb_form_params.time_format,
					datepicker:false,
					scrollMonth:false,
					scrollTime:true,
					scrollInput:false,
					dayOfWeekStart: parseInt(wpfb_form_params.dayofweekstart),
					minTime: _this.data('min-time'),
					maxTime: _this.data('max-time'),
					yearStart: _this.data('year-start'),
					yearEnd: _this.data('year-end'),
					step: parseInt(wpfb_form_params.time_picker_step)
				});
			});
		}
		
		$('.wpfb-form-control[data-range_field]').each(function(){
			var $this = $(this),
				$range_field_name = $this.data('range_field'),
				$range_field = $('.wpfb-form-control[data-field-name="' + $range_field_name + '"]');

			$range_field.xdsoftDatetimepicker('setOptions',{
				onChangeDateTime: function(_datetimepicker , _currentTime, _input, _event){
					var nextDate = new Date(
								_datetimepicker.getFullYear(), 
								_datetimepicker.getMonth(), 
								_datetimepicker.getDate() + parseInt($this.data('range_field_start_current')), 
								_datetimepicker.getHours(), 
								_datetimepicker.getMinutes(), 
								_datetimepicker.getSeconds(),
								_datetimepicker.getMilliseconds() ).toString();
					
				   
					if($this.data('range_field_set_value')==='yes'){
						 var dateHelper = $range_field.data('xdsoft_datetimepicker').getDateHelper(),
							format = $this.hasClass('wpfb-form-datetimepicker') ? dhvcformL10n.date_format +' '+dhvcformL10n.time_format : dhvcformL10n.date_format,
							value = dateHelper.formatDate(new Date(nextDate),format);
								
						$this.val(value).attr('value',value);
						$this.trigger('change'); 
					}
					
					$this.xdsoftDatetimepicker('reset').xdsoftDatetimepicker('setOptions',{
						minDate: new Date(nextDate) 
					})
				}
			})
		})
		
		if($('.wpfb-form-datetimepicker').length){
			$('.wpfb-form-datetimepicker').each(function(){
				var _this = $(this);
				_this.xdsoftDatetimepicker({
					format: wpfb_form_params.date_format +' '+wpfb_form_params.time_format,
					datepicker:true,
					scrollMonth:false,
					scrollTime:true,
					scrollInput:false,
					minTime: _this.data('min-time'),
					maxTime: _this.data('max-time'),
					minDate: _this.data('min-date'),
					maxDate: _this.data('max-date'),
					yearStart: _this.data('year-start'),
					yearEnd: _this.data('year-end'),
					step: parseInt(wpfb_form_params.time_picker_step)
				});
			});
		}
		
		$('.wpfb-form-minicolors').each(function(){
			$(".wpfb-form-control",$(this)).minicolors({
				theme: 'bootstrap'
			});
		});
		
		
		var initForm = function(form){

			var $form = $( form ),
				submiting=false;
			
			var submitBtn = $form.find('.wpfb-form-submit');
			
			$('.wpfb-form-file',$form).find('input[type=file]').on('change',function(){
				var _val = $(this).val();
				$(this).closest('label').find('.wpfb-form-control').prop('value',basename(_val));
			});
			$('.wpfb-form-file',$form).each(function(){
				$(this).find('input[type="text"]').css({'padding-right':$(this).find('.wpfb-form-file-button').outerWidth(true) + 'px'});
				$(this).find('input[type="text"]').on('click',function(){
					$(this).closest('label').trigger('click');
				});
			});
			
			if($.fn.tooltip)
				$('.wpfb-form-rate .wpfb-form-rate-star',$form).tooltip({ html: true,container:$('body')});
			
			
			var clearResponse = function(form){
				var $form = $( form );
				$form.removeClass( 'invalid spam sent failed' );
				$( '[aria-invalid]', $form ).attr( 'aria-invalid', 'false' );
				$( '.wpfb-form-error', $form ).remove();
				$( '.wpfb-form-control', $form ).removeClass( 'wpfb-form-not-valid' );
				$( '.wpfb-form-message', $form.parent() )
				.hide().empty().removeAttr( 'role' )
				.removeClass('wpfb-form-validation-errors wpfb-form-spam wpfb-form-errors wpfb-form-success');
			}
			
			var refill = function(form, data){
				var $form = $(form);
				var refillCaptcha = function( $form, items ) {
					$.each( items, function( i, n ) {
						$form.find( ':input[name="' + i + '"]' ).val( '' );
						$form.find( 'img.wpfb-form-captcha-img-' + i ).attr( 'src', n );
						var match = /([0-9]+)\.(png|gif|jpeg)$/.exec( n );
						$form.find( 'input:hidden[name="_wpfb_form_captcha_challenge_' + i + '"]' ).attr( 'value', match[ 1 ] );
					} );
				};
				if ( data.captcha ) {
					refillCaptcha( $form, data.captcha );
				}
			}
			
			var notValidTip = function(target, message){
				if(message=='')
					return;
				var $target = $( target );
				$( '.wpfb-form-error', $target ).remove();
				var error = $( '<span role="alert" class="wpfb-form-error"></span>' );
				
				error.text(message);
				
				if ( $target.is( ':radio' ) || $target.is( ':checkbox' ) )
					error.appendTo( $target.parent().parent() );
				else if($target.attr('data-wpfbform-recaptcha')=='recaptcha')
					error.appendTo($target.closest('.wpfb-form-group') );
				else
					error.appendTo( $target.parent().parent());
			}
			
			var form_step_click_init = function(form){
				var $form = $(form);
				$('.wpfb-form-step',$form).on('click',function(e){
					var $this = $(this);
					e.stopPropagation();
					e.preventDefault();
					
					if(!$this.hasClass('actived'))
						return;
					
					$( '.wpfb-form-message.wpfb-form-success', $form.parent() )
					.hide().empty().removeAttr( 'role' )
					.removeClass('wpfb-form-validation-errors wpfb-form-spam wpfb-form-errors wpfb-form-success');
					
					var click_step = parseInt($(this).data('step-index')),
						current_step = parseInt($('#_wpfb_form_current_step',$form).val());
					//step control
					$this.siblings('.wpfb-form-step.active').removeClass('active').addClass('actived');
					//$('.wpfb-form-steps',$form).find('.active').removeClass('active');
					$this.removeClass('actived').addClass('active');
					
					//step content
					$('.wpfb-form-step-content',$form).removeClass('active');
					$('.wpfb-form-step-content-'+click_step,$form).addClass('active');
					
					$('#_wpfb_form_current_step',$form).val(click_step);
					
					$('.wpfb-form-value',$form).on('input.wpfbform change.wpfbform',function(){
						var current_content_step = $(this).closest('.wpfb-form-step-content').data('content_step'),
							current_control_step = $form.find('[data-step-index=' + current_content_step + ']');			
						current_control_step.nextAll().removeClass('active actived');
						$('#_wpfb_form_current_step',$form).val(click_step);
					})
					//
				});
			}
			form_step_click_init($form);
			
			var ajaxSuccess = function(data, status, xhr, $form ){
				var $message = $( '.wpfb-form-message', $form.parent() );
				
				switch ( data.status ) {
					case 'validation_failed':
						var firstInvalidFields = null;
						$.each( data.invalid_fields, function( i, n ) {
							if(!firstInvalidFields)
								firstInvalidFields = $(n.into);
							notValidTip( $(n.into), n.reason );
							$( '.wpfb-form-control', $(n.into).closest('.wpfb-form-group') ).addClass( 'wpfb-form-not-valid' );
							$( '[aria-invalid]', $(n.into) ).attr( 'aria-invalid', 'true' );
						} );
						try {
							firstInvalidFields.focus()

							// Manually trigger focusin event; without it, focusin handler isn't called, findLastActive won't have anything to find
							.trigger( "focusin" );
						} catch ( e ) {

							// Ignore IE throwing errors when focusing hidden elements
						}
						
						$message.addClass( 'wpfb-form-validation-errors' );
						
						$form.addClass( 'invalid' );
						
						$( document.body ).trigger( 'wpfb_form_invalid', [$form, data] );
					break;
					case 'success':
						if($form.find('#_wpfb_form_steps').length){
							var step_final = '<div class="wpfb-form-steps-final"></div>';
							$form.find('.wpfb-form-step-contents').append($(step_final));
						}
						if ( data.onOk ) {
							$.each( data.onOk, function( i, n ) { eval( n ) } );
						}
						$message.addClass( 'wpfb-form-success' );
						$( document.body ).trigger( 'wpfb_form_success', [$form, data] );
					break;
					case 'spam':
						$message.addClass( 'wpfb-form-spam' );
						$( document.body ).trigger( 'wpfb_form_spam', [$form, data] );
					break;
					case 'upload_failed':
						$message.addClass( 'wpfb-form-errors' );
						$( document.body ).trigger( 'wpfb_form_upload_failed', [$form, data] );
					break;
					case 'form_not_exist':
						$message.addClass( 'wpfb-form-errors' );
						$( document.body ).trigger( 'wpfb_form_not_exist', [$form, data] );
					break;
					case 'action_failed':
						$message.addClass( 'wpfb-form-errors' );
						$( document.body ).trigger( 'wpfb_form_action_failed', [$form, data] );
					break;
					case 'call_action_failed':
						$message.addClass( 'wpfb-form-errors' );
						$( document.body ).trigger( 'wpfb_form_call_action_failed', [$form, data] );
					break;
					case 'next_step':
						var $current_step_input = $('#_wpfb_form_current_step',$form);
						var $current_step = parseInt($current_step_input.val());
						var $all_steps = parseInt($('#_wpfb_form_steps',$form).val());
						var $next_step = $current_step + 1;
						
						if($next_step<=$all_steps ){
							$('.wpfb-form-steps',$form).find('.active').removeClass('active');
							$('.wpfb-form-step-'+$current_step,$form).addClass('actived');
							$('.wpfb-form-step-'+$next_step,$form).removeClass('actived').addClass('active');
							
							$('.wpfb-form-step-content',$form).removeClass('active');
							$('.wpfb-form-step-content-'+$next_step,$form).addClass('active');
							$('#_wpfb_form_current_step',$form).val($next_step);
						}
					break;
				}

				refill( $form, data );

				$( document.body ).trigger( 'wpfb_form_submit', [$form, data] );

				if ( 'success' === data.status ) {
					$form.each( function() {
						this.reset();
					} );
					
					conditional_form($form,true);
					
					if(data.refresh)
						window.location.reload();
					else if(data.redirect)
						 window.location = data.redirect;
					
				}
				if(!data.redirect && data.message!=''){
					$message.html( data.message )
					$message.attr( 'role', 'alert' );
					if($form.find('.wpfb-form-steps-final').length){
						$('.wpfb-form-step-content').each(function(){
							$(this).remove();
						})
						$form.find('.wpfb-form-steps-final').html($message.clone());
						$message.remove();
					}else{
						$message.slideDown( 'fast' );
					}
				}
			}
			
			$form.submit( function( event ) {
				if ('yes'===wpfb_form_params.is_preview_mode ||  submiting || typeof window.FormData !== 'function' ) {
					return;
				}
				
				submiting = true;
				
				clearResponse($form);
				
		    	update_hidden_fields($form);
				
				var formData = new FormData( $form.get( 0 ) );
				
				$.ajax( {
					type: 'POST',
					url:wpfb_form_params.ajax_submit_url,
					data: formData,
					dataType: 'json',
					processData: false,
					contentType: false,
					beforeSend: function(){
						$( document.body ).trigger( 'wpfb_form_before_submit', [$form, submitBtn] );
				    	form_submit_loading($form,false);
			        }
				} ).done( function( data, status, xhr ) {
					$( document.body ).trigger( 'wpfb_form_after_submit', [ $(form), submitBtn , data] );
					ajaxSuccess( data, status, xhr, $form );
					submiting = false;
					form_submit_loading($form,true);
				} ).fail( function( xhr, status, error ) {
					submiting = false;
					form_submit_loading($form,true);
				} );
				event.preventDefault();
			} );
		}
		
		$('form.wpfbform').each(function(){
			var $form = $(this);
			paypal_calculation($form);
			field_calculation($form);
		    $('.wpfb-form-value',$form).on('keyup change',function(e){
		    	paypal_calculation($(this).closest('form'));
		    	field_calculation($(this).closest('form'));
			});
		    conditional_form($form);
		    if($form.hasClass('wpfbform-action-default'))
		    	initForm($form);
		});

		$( document ).on( 'elementor/popup/show',function(event, id){
			$('#elementor-popup-modal-'+id).find('form.wpfbform').each(function(){
				var $form = $(this);
				paypal_calculation($form);
				field_calculation($form);
			    $('.wpfb-form-value',$form).on('keyup change',function(e){
			    	paypal_calculation($(this).closest('form'));
			    	field_calculation($(this).closest('form'));
				});
			    conditional_form($form);
			    if($form.hasClass('wpfbform-action-default'))
			    	initForm($form);
			});
		});
	});
	
}(window.jQuery);