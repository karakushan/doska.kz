( function( $ ) {

	/**
	* Search widget JS
	*/

	var WidgethfbSearchButton = function( $scope, $ ){

		if ( 'undefined' == typeof $scope )
			return;

			var $input = $scope.find( "input.hfb-search-form__input" );
			var $clear = $scope.find( "button#clear" );
			var $clear_with_button = $scope.find( "button#clear-with-button" );
			var $search_button = $scope.find( ".hfb-search-submit" );
			var $toggle_search = $scope.find( ".hfb-search-icon-toggle input" );

		$scope.find( '.hfb-search-icon-toggle' ).on( 'click', function( ){
			$scope.find( ".hfb-search-form__input" ).focus();						
		});	
		
		$scope.find( ".hfb-search-form__input" ).focus( function(){
			$scope.find( ".hfb-search-button-wrapper" ).addClass( "hfb-input-focus" );
		});

		$scope.find( ".hfb-search-form__input" ).blur( function() {
			$scope.find( ".hfb-search-button-wrapper" ).removeClass( "hfb-input-focus" );
		});
  		   

		$search_button.on( 'touchstart click', function(){
			$input.submit();
		});

		$toggle_search.css( 'padding-right', $toggle_search.next().outerWidth() + 'px' );

	
		$input.on( 'keyup', function(){
			$clear.style = (this.value.length) ? $clear.css('visibility','visible'): $clear.css('visibility','hidden');
			$clear_with_button.style = (this.value.length) ? $clear_with_button.css('visibility','visible'): $clear_with_button.css('visibility','hidden');
			$clear_with_button.css( 'right', $search_button.outerWidth() + 'px' );
		});

		$clear.on("click",function(){
			this.style = $clear.css('visibility','hidden');
			$input.value = "";
		});
		$clear_with_button.on("click",function(){
			this.style = $clear_with_button.css('visibility','hidden');
			$input.value = "";
		});
		
	};
		/**
	 * Nav Menu handler Function.
	 *
	 */
	var WidgethfbNavMenuHandler = function( $scope, $ ) {

		if ( 'undefined' == typeof $scope )
			return;
		
		var id = $scope.data( 'id' );
		var wrapper = $scope.find('.elementor-widget-hfb-nav-menu ');		
		var layout = $( '.elementor-element-' + id + ' .hfb-nav-menu' ).data( 'layout' );
		var last_item = $( '.elementor-element-' + id + ' .hfb-nav-menu' ).data( 'last-item' );

		$( 'div.hfb-has-submenu-container' ).removeClass( 'sub-menu-active' );

		_toggleClick( id );

		if( 'horizontal' !== layout ){

			_eventClick( id );
		}else if ( 'horizontal' === layout && window.matchMedia( "( max-width: 767px )" ).matches ) {

			_eventClick( id );
		}else if ( 'horizontal' === layout && window.matchMedia( "( max-width: 1024px )" ).matches ) {

			_eventClick( id );
		}	


		$scope.find( '.sub-menu' ).each( function() {

			var parent = $( this ).closest( '.menu-item' );

			$scope.find( parent ).addClass( 'parent-has-child' );
			$scope.find( parent ).removeClass( 'parent-has-no-child' );
		});

		if( 'cta' == last_item ){
			$( '.elementor-element-' + id + ' li.menu-item:last-child a.hfb-menu-item' ).parent().addClass( 'hfb-menu-button-wrapper' );
			$( '.elementor-element-' + id + ' li.menu-item:last-child a.hfb-menu-item' ).addClass( 'hfb-menu-item-button' );			
		}

		_borderClass( id );	

		$( window ).resize( function(){ 

			if( 'horizontal' !== layout ) {

				_eventClick( id );
			}else if ( 'horizontal' === layout && window.matchMedia( "( max-width: 767px )" ).matches ) {

				_eventClick( id );
			}else if ( 'horizontal' === layout && window.matchMedia( "( max-width: 1024px )" ).matches ) {

				_eventClick( id );
			}

			if( 'horizontal' == layout && window.matchMedia( "( min-width: 977px )" ).matches){

				$( '.elementor-element-' + id + ' div.hfb-has-submenu-container' ).next().css( 'position', 'absolute');	
			}

			if ( 'horizontal' == layout ) {
				if( window.matchMedia( "( max-width: 767px )" ).matches && ($( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-tablet') || $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-mobile'))){

					_toggleClick( id );					
				}else if ( window.matchMedia( "( max-width: 1024px )" ).matches && $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-tablet') ) {
					
					_toggleClick( id );
				}
			}

			_borderClass( id );	

		});

        // Acessibility functions

  		$scope.find( '.parent-has-child .hfb-has-submenu-container a').attr( 'aria-haspopup', 'true' );
  		$scope.find( '.parent-has-child .hfb-has-submenu-container a').attr( 'aria-expanded', 'false' );

  		$scope.find( '.hfb-nav-menu__toggle').attr( 'aria-haspopup', 'true' );
  		$scope.find( '.hfb-nav-menu__toggle').attr( 'aria-expanded', 'false' );

  		// End of accessibility functions

		$( document ).trigger( 'hfb_nav_menu_init', id );

		$( '.elementor-element-' + id + ' div.hfb-has-submenu-container' ).on( 'keyup', function(e){

			var $this = $( this );

		  	if( $this.parent().hasClass( 'menu-active' ) ) {

		  		$this.parent().removeClass( 'menu-active' );

		  		$this.parent().next().find('ul').css( { 'visibility': 'hidden', 'opacity': '0', 'height': '0' } );
		  		$this.parent().prev().find('ul').css( { 'visibility': 'hidden', 'opacity': '0', 'height': '0' } );

		  		$this.parent().next().find( 'div.hfb-has-submenu-container' ).removeClass( 'sub-menu-active' );
		  		$this.parent().prev().find( 'div.hfb-has-submenu-container' ).removeClass( 'sub-menu-active' );
			}else { 

				$this.parent().next().find('ul').css( { 'visibility': 'hidden', 'opacity': '0', 'height': '0' } );
		  		$this.parent().prev().find('ul').css( { 'visibility': 'hidden', 'opacity': '0', 'height': '0' } );

		  		$this.parent().next().find( 'div.hfb-has-submenu-container' ).removeClass( 'sub-menu-active' );
		  		$this.parent().prev().find( 'div.hfb-has-submenu-container' ).removeClass( 'sub-menu-active' );

				$this.parent().siblings().find( '.hfb-has-submenu-container a' ).attr( 'aria-expanded', 'false' );

				$this.parent().next().removeClass( 'menu-active' );
		  		$this.parent().prev().removeClass( 'menu-active' );

				event.preventDefault();

				$this.parent().addClass( 'menu-active' );

				if( 'horizontal' !== layout ){
					$this.addClass( 'sub-menu-active' );	
				}
				
				$this.find( 'a' ).attr( 'aria-expanded', 'true' );

				$this.next().css( { 'visibility': 'visible', 'opacity': '1', 'height': 'auto' } );

				if ( 'horizontal' !== layout ) {
						
		  			$this.next().css( 'position', 'relative');			
				} else if ( 'horizontal' === layout && window.matchMedia( "( max-width: 767px )" ).matches && ($( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-tablet') || $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-mobile'))) {
										
  					$this.next().css( 'position', 'relative');		  					
				} else if ( 'horizontal' === layout && window.matchMedia( "( max-width: 1024px )" ).matches ) {
					
  					if ( $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-tablet') ) {

  						$this.next().css( 'position', 'relative');	
  					} else if ( $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-mobile') || $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-none') ) {
  						
  						$this.next().css( 'position', 'absolute');	
  					}
  				}		
			}
		});

		$( '.elementor-element-' + id + ' li.menu-item' ).on( 'keyup', function(e){
			var $this = $( this );

	 		$this.next().find( 'a' ).attr( 'aria-expanded', 'false' );
	 		$this.prev().find( 'a' ).attr( 'aria-expanded', 'false' );
	  		
	  		$this.next().find('ul').css( { 'visibility': 'hidden', 'opacity': '0', 'height': '0' } );
	  		$this.prev().find('ul').css( { 'visibility': 'hidden', 'opacity': '0', 'height': '0' } );
	  		
	  		$this.siblings().removeClass( 'menu-active' );
	  		$this.next().find( 'div.hfb-has-submenu-container' ).removeClass( 'sub-menu-active' );
		  	$this.prev().find( 'div.hfb-has-submenu-container' ).removeClass( 'sub-menu-active' );
		  		
		});
	};

	function _eventClick( id ){

		var layout = $( '.elementor-element-' + id + ' .hfb-nav-menu' ).data( 'layout' );

		$( '.elementor-element-' + id + ' div.hfb-has-submenu-container' ).off( 'click' ).on( 'click', function( event ) {

			var $this = $( this );
			
		  	if( $this.hasClass( 'sub-menu-active' ) ) {

		  		if( ! $this.next().hasClass( 'sub-menu-open' ) ) {

		  			$this.find( 'a' ).attr( 'aria-expanded', 'false' );

		  			if( 'horizontal' !== layout ){

						event.preventDefault();

		  				$this.next().css( 'position', 'relative' );	
					}else if ( 'horizontal' === layout && window.matchMedia( "( max-width: 767px )" ).matches && ($( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-tablet') || $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-mobile'))) {
						
						event.preventDefault();

		  				$this.next().css( 'position', 'relative' );	
					}else if ( 'horizontal' === layout && window.matchMedia( "( max-width: 1024px )" ).matches && ( $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-tablet') || $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-mobile'))) {
						
						event.preventDefault();	

		  				$this.next().css( 'position', 'relative' );	
					}	
	  			
					$this.removeClass( 'sub-menu-active' );
					$this.next().removeClass( 'sub-menu-open' );
					$this.next().css( { 'visibility': 'hidden', 'opacity': '0', 'height': '0' } );
					$this.next().css( { 'transition': 'none'} );										
		  		}else{

		  			$this.find( 'a' ).attr( 'aria-expanded', 'false' );
		  			
		  			$this.removeClass( 'sub-menu-active' );
					$this.next().removeClass( 'sub-menu-open' );
					$this.next().css( { 'visibility': 'hidden', 'opacity': '0', 'height': '0' } );
					$this.next().css( { 'transition': 'none'} );	
						  			  			
					if ( 'horizontal' !== layout ){

						$this.next().css( 'position', 'relative' );
					} else if ( 'horizontal' === layout && window.matchMedia( "( max-width: 767px )" ).matches && ($( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-tablet') || $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-mobile'))) {
						
						$this.next().css( 'position', 'relative' );	
						
					} else if ( 'horizontal' === layout && window.matchMedia( "( max-width: 1024px )" ).matches && ( $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-tablet') || $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-mobile'))) {
						
						$this.next().css( 'position', 'absolute' );				
					}	  								
		  		}		  											
			}else {

					$this.find( 'a' ).attr( 'aria-expanded', 'true' );
					if ( 'horizontal' !== layout ) {
						
						event.preventDefault();
			  			$this.next().css( 'position', 'relative');			
					} else if ( 'horizontal' === layout && window.matchMedia( "( max-width: 767px )" ).matches && ($( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-tablet') || $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-mobile'))) {
						
						event.preventDefault();
	  					$this.next().css( 'position', 'relative');		  					
					} else if ( 'horizontal' === layout && window.matchMedia( "( max-width: 1024px )" ).matches ) {
						event.preventDefault();

	  					if ( $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-tablet') ) {

	  						$this.next().css( 'position', 'relative');	
	  					} else if ( $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-mobile') || $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-none') ) {
	  						
	  						$this.next().css( 'position', 'absolute');	
	  					}
	  				}	
	  					
				$this.addClass( 'sub-menu-active' );
				$this.next().addClass( 'sub-menu-open' );	
				$this.next().css( { 'visibility': 'visible', 'opacity': '1', 'height': 'auto' } );
				$this.next().css( { 'transition': '0.3s ease'} );								
			}
		});

		$( '.elementor-element-' + id + ' .hfb-menu-toggle' ).off( 'click keyup' ).on( 'click keyup',function( event ) {

			var $this = $( this );

		  	if( $this.parent().parent().hasClass( 'menu-active' ) ) {

	  			event.preventDefault();

				$this.parent().parent().removeClass( 'menu-active' );
				$this.parent().parent().next().css( { 'visibility': 'hidden', 'opacity': '0', 'height': '0' } );

				if ( 'horizontal' !== layout ) {
						
		  			$this.parent().parent().next().css( 'position', 'relative');			
				} else if ( 'horizontal' === layout && window.matchMedia( "( max-width: 767px )" ).matches && ($( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-tablet') || $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-mobile'))) {
										
  					$this.parent().parent().next().css( 'position', 'relative');		  					
				} else if ( 'horizontal' === layout && window.matchMedia( "( max-width: 1024px )" ).matches ) {
					
  					if ( $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-tablet') ) {

  						$this.parent().parent().next().css( 'position', 'relative');	
  					} else if ( $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-mobile') || $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-none') ) {
  						
  						$this.parent().parent().next().css( 'position', 'absolute');	
  					}
  				}
			}else { 

				event.preventDefault();

				$this.parent().parent().addClass( 'menu-active' );

				$this.parent().parent().next().css( { 'visibility': 'visible', 'opacity': '1', 'height': 'auto' } );

				if ( 'horizontal' !== layout ) {
						
		  			$this.parent().parent().next().css( 'position', 'relative');			
				} else if ( 'horizontal' === layout && window.matchMedia( "( max-width: 767px )" ).matches && ($( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-tablet') || $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-mobile'))) {
										
  					$this.parent().parent().next().css( 'position', 'relative');		  					
				} else if ( 'horizontal' === layout && window.matchMedia( "( max-width: 1024px )" ).matches ) {
					
  					if ( $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-tablet') ) {

  						$this.parent().parent().next().css( 'position', 'relative');	
  					} else if ( $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-mobile') || $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-none') ) {
  						
  						$this.parent().parent().next().css( 'position', 'absolute');	
  					}
  				}		
			}
		});
	}

	function _borderClass( id ){

		var last_item = $( '.elementor-element-' + id + ' .hfb-nav-menu' ).data( 'last-item' );
		var layout = $( '.elementor-element-' + id + ' .hfb-nav-menu' ).data( 'layout' );

		$( '.elementor-element-' + id + ' nav').removeClass('hfb-dropdown');

		if ( window.matchMedia( "( max-width: 767px )" ).matches ) {

			if( $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-mobile') || $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-tablet')){
				
				$( '.elementor-element-' + id + ' nav').addClass('hfb-dropdown');
				if( 'cta' == last_item ){
					$( '.elementor-element-' + id + ' li.menu-item:last-child a.hfb-menu-item' ).parent().removeClass( 'hfb-menu-button-wrapper' );
					$( '.elementor-element-' + id + ' li.menu-item:last-child a.hfb-menu-item' ).removeClass( 'hfb-menu-item-button' );	
				}	
			}else{
				
				$( '.elementor-element-' + id + ' nav').removeClass('hfb-dropdown');
				if( 'cta' == last_item){
					$( '.elementor-element-' + id + ' li.menu-item:last-child a.hfb-menu-item' ).parent().addClass( 'hfb-menu-button-wrapper' );
					$( '.elementor-element-' + id + ' li.menu-item:last-child a.hfb-menu-item' ).addClass( 'hfb-menu-item-button' );	
				}
			}
		}else if ( window.matchMedia( "( max-width: 1024px )" ).matches ) {

			if( $( '.elementor-element-' + id ).hasClass('hfb-nav-menu__breakpoint-tablet') ) {
				
				$( '.elementor-element-' + id + ' nav').addClass('hfb-dropdown');
				if( 'cta' == last_item){
					$( '.elementor-element-' + id + ' li.menu-item:last-child a.hfb-menu-item' ).parent().removeClass( 'hfb-menu-button-wrapper' );
					$( '.elementor-element-' + id + ' li.menu-item:last-child a.hfb-menu-item' ).removeClass( 'hfb-menu-item-button' );	
				}
			}else{
				
				$( '.elementor-element-' + id + ' nav').removeClass('hfb-dropdown');
				if( 'cta' == last_item ){
					$( '.elementor-element-' + id + ' li.menu-item:last-child a.hfb-menu-item' ).parent().addClass( 'hfb-menu-button-wrapper' );
					$( '.elementor-element-' + id + ' li.menu-item:last-child a.hfb-menu-item' ).addClass( 'hfb-menu-item-button' );
				}
			}
		}
	}

	function _toggleClick( id ){

		if ( $( '.elementor-element-' + id + ' .hfb-nav-menu__toggle' ).hasClass( 'hfb-active-menu-full-width' ) ){

			$( '.elementor-element-' + id + ' .hfb-nav-menu__toggle' ).next().css( 'left', '0' );

			var width = $( '.elementor-element-' + id ).closest('.elementor-section').outerWidth();
			var sec_pos = $( '.elementor-element-' + id ).closest('.elementor-section').offset().left - $( '.elementor-element-' + id + ' .hfb-nav-menu__toggle' ).next().offset().left;
			$( '.elementor-element-' + id + ' .hfb-nav-menu__toggle' ).next().css( 'width', width + 'px' );
			$( '.elementor-element-' + id + ' .hfb-nav-menu__toggle' ).next().css( 'left', sec_pos + 'px' );
		}

		$( '.elementor-element-' + id + ' .hfb-nav-menu__toggle' ).off( 'click keyup' ).on( 'click keyup', function( event ) {

			var $this = $( this );
			var $selector = $this.next();

			if ( $this.hasClass( 'hfb-active-menu' ) ) {

				var layout = $( '.elementor-element-' + id + ' .hfb-nav-menu' ).data( 'layout' );
				var full_width = $selector.data( 'full-width' );
				var toggle_icon = $( '.elementor-element-' + id + ' nav' ).data( 'toggle-icon' );

				$( '.elementor-element-' + id).find( '.hfb-nav-menu-icon' ).html( toggle_icon );

				$this.removeClass( 'hfb-active-menu' );
				$this.attr( 'aria-expanded', 'false' );
				
				if ( 'yes' == full_width ){

					$this.removeClass( 'hfb-active-menu-full-width' );
				
					$selector.css( 'width', 'auto' );
					$selector.css( 'left', '0' );
					$selector.css( 'z-index', '0' );
				}				
			} else {

				var layout = $( '.elementor-element-' + id + ' .hfb-nav-menu' ).data( 'layout' );
				var full_width = $selector.data( 'full-width' );
				var close_icon = $( '.elementor-element-' + id + ' nav' ).data( 'close-icon' );

				$( '.elementor-element-' + id).find( '.hfb-nav-menu-icon' ).html( close_icon );
				
				$this.addClass( 'hfb-active-menu' );
				$this.attr( 'aria-expanded', 'true' );

				if ( 'yes' == full_width ){

					$this.addClass( 'hfb-active-menu-full-width' );

					var width = $( '.elementor-element-' + id ).closest('.elementor-section').outerWidth();
					var sec_pos = $( '.elementor-element-' + id ).closest('.elementor-section').offset().left - $selector.offset().left;
				
					$selector.css( 'width', width + 'px' );
					$selector.css( 'left', sec_pos + 'px' );
					$selector.css( 'z-index', '9999' );
				}
			}

			if( $( '.elementor-element-' + id + ' nav' ).hasClass( 'menu-is-active' ) ) {

				$( '.elementor-element-' + id + ' nav' ).removeClass( 'menu-is-active' );
			}else {

				$( '.elementor-element-' + id + ' nav' ).addClass( 'menu-is-active' );
			}				
		} );
	}

	$( window ).on( 'elementor/frontend/init', function () {

		elementorFrontend.hooks.addAction( 'frontend/element_ready/navigation-menu.default', WidgethfbNavMenuHandler );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hfb-search-button.default', WidgethfbSearchButton );
	});
	
	// Megamenu
        function hfb_megamenu($self){
            if ($self.closest('.hfb-menu-mobile-container') && $self.closest('.hfb-menu-mobile-container').length > 0) {
                return true;
            }

            if ($self.hasClass('full-container') || $self.hasClass('full-width')) {
                $self.closest('li').css({position: 'static'});
                $self.css({width: '1170px'});
            }

            if ($self.hasClass('full-width')) {
                $self.closest('.hfb-container').css({
                    position: 'static'
                });
            }

            if(!$self.hasClass('custom-width')) {
                return;
            }

            var $menuParent = $self.closest('li'),
                $header = $self.closest('.hfb-header-inside'),
                sw = ($self.outerWidth() / 2) - ($menuParent.outerWidth() / 2);
            var mrLeft = $menuParent.offset().left - $header.offset().left;

            //check left side
            if (sw < mrLeft) {
                mrLeft = sw;
            }

            // check right side
            var rHeader = $header.offset().left + $header.outerWidth(),
                rMegamenu = $self.offset().left + $self.outerWidth();
            if ((rMegamenu - mrLeft) > rHeader) {
                mrLeft = rMegamenu - rHeader;
            }

            // set pos
            if ($(window).width() < $self.outerWidth()) {
                mrLeft += 5;
            }
            $self.css({
                marginLeft: -(mrLeft)
            });
        }
        $('.hfb-mega-menu').each(function( index ) {
            hfb_megamenu($(this))
        });
} )( jQuery );
