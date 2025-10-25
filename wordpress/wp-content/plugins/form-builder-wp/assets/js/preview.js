jQuery( function( $ ) {
	"use strict"; // jshint ;_;
	if ( undefined !== window.elementorFrontend ) {
		elementor.settings.page.addChangeCallback( 'form_layout', function(value){
			var $form_container = elementor.getPreviewView().$el.closest('.wpfb-form-container');
			$form_container.removeClass('wpfb-form-vertical wpfb-form-horizontal').addClass('wpfb-form-'+value)
		} );
		elementor.settings.page.addChangeCallback( 'input_icon_position', function(value){
			var $form_container = elementor.getPreviewView().$el.closest('.wpfb-form-container');
			$form_container.removeClass('wpfb-form-icon-pos-left wpfb-form-icon-pos-right').addClass('wpfb-form-icon-pos-'+value)
		} );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpfb_form_color.default', function( $scope ) {
			if($.isFunction( $.fn.minicolors ) ){
				$scope.find(".wpfb-form-control").minicolors({
					theme: 'bootstrap'
				});
			}
		});
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpfb_form_rate.default', function( $scope ) {
			if($.isFunction( $.fn.tooltip ) ){
				$scope.find('.wpfb-form-rate-star').tooltip({ html: true,container:$('body')});
			}
		});
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpfb_form_slider.default', function( $scope ) {
			if($.isFunction( $.fn.slider ) ){
				$scope.find('.wpfb-form-slider-control').each(function(){
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
			}
		});
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpfb_form_recaptcha.default', function( $scope ) {
			var initRecaptcha = function(){
				var $elements = $scope.find('[data-wpfbform-recaptcha="recaptcha"]');
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
		});
		
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wpfb_form_datetime.default', function( $scope ) {
			if($.isFunction( $.fn.xdsoftDatetimepicker ) ){
				var datepicker = $scope.find('.wpfb-form-datepicker');
				if(datepicker.length){
					datepicker.each(function(){
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
				var timepicker = $scope.find('.wpfb-form-timepicker');
				if(timepicker.length){
					timepicker.each(function(){
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
							minDate: _this.data('min-date'),
							maxDate: _this.data('max-date'),
							yearStart: _this.data('year-start'),
							yearEnd: _this.data('year-end'),
							step: parseInt(wpfb_form_params.time_picker_step)
						});
					});
				}
				
				$scope.find('.wpfb-form-datepicker[data-range_field]').each(function(){
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
				
				var datetimepicker = $scope.find('.wpfb-form-datetimepicker');
				if(datetimepicker.length){
					datetimepicker.each(function(){
						var _this = $(this);
						_this.xdsoftDatetimepicker({
							format: wpfb_form_params.date_format +' '+wpfb_form_params.time_format,
							datepicker:true,
							scrollMonth:false,
							scrollTime:true,
							scrollInput:false,
							minTime: _this.data('min-time'),
							maxTime: _this.data('max-time'),
							step: parseInt(wpfb_form_params.time_picker_step)
						});
					});
				}
			}
		});
	}
} );