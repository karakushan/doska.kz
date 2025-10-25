(function( $ ) {
	'use strict';
	$(function(){
		elkit_woo_slik_init();
		elkit_slik_init();
		
	});
	window.ElkitTabs = function(evt, index) {
	  // Declare all variables
	  var i, tabcontent, tablinks;

	  // Get all elements with class="tabcontent" and hide them
	  tabcontent = document.getElementsByClassName("elkit-tabs-content");
	  for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = "none";
	  }

	  // Get all elements with class="tablinks" and remove the class "active"
	  tablinks = document.getElementsByClassName("elkit-tabs-link");
	  for (i = 0; i < tablinks.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" active", "");
	  }

	  // Show the current tab, and add an "active" class to the button that opened the tab
	  document.getElementById(index).style.display = "block";
	  evt.currentTarget.className += " active";
	}
	window.elkit_slik_init = function () {
			$('.elkit-slick-carousel').each( function() {
				
				var $SlickCarousel = $(this);	
				var pre_arrow = $SlickCarousel.data("prev-arrow");
				console.log(pre_arrow);
				$SlickCarousel.not('.slick-initialized').slick({   
					dots: false,
					infinite: $SlickCarousel.data("loop"),
					slidesToShow: $SlickCarousel.data("items"),
					slidesToScroll: $SlickCarousel.data("slide-to-scroll"),
					speed: $SlickCarousel.data("slide-speed"),
					useTransform: true,
					autoplay: $SlickCarousel.data("autoplay"),
					autoplaySpeed: $SlickCarousel.data("autoplay-speed"),
					centerMode: $SlickCarousel.data("center"),
					centerPadding: $SlickCarousel.data("center-padding") + "%",
					lazyLoad: 'progressive',
					arrows: $SlickCarousel.data("arrow"),
					rtl: directorypress_js_instance.is_rtl,
					prevArrow: '<i class="elkit-slider-arrow-pre ' + $SlickCarousel.data("prev-arrow") + '" style="position:'+ $SlickCarousel.data("arrow-postion") +'; z-index:10;"></i>',
					nextArrow: '<i class="elkit-slider-arrow-next ' + $SlickCarousel.data("next-arrow") + '" style="position:'+ $SlickCarousel.data("arrow-postion") +'; z-index:10;"></i>',
					draggable: true,
					vertical: false,
					responsive: [
						{
							breakpoint: 858,
							settings: {
								slidesToShow: $SlickCarousel.data("items-tablet"),
							}
						},
						{
							breakpoint: 767,
							settings: {
								slidesToShow: $SlickCarousel.data("items-mobile"),
							}
						}
					]
				});
			});
		$('.elkit-testimonia-slider-nav').not('.slick-initialized').slick({
		
		  slidesToShow: 3,
		  slidesToScroll: 1,
		  asNavFor: '.elkit-slick-carousel',
		  dots: false,
		  arrows: false,
		  centerMode: true,
		  centerPadding: "50%",
		  variableWidth: true,
		  focusOnSelect: true,
		  rtl: directorypress_js_instance.is_rtl,
		  responsive: [
				{
					breakpoint: 480,
					settings: {
						slidesToShow: 3,
					}
				},
				{
					breakpoint: 300,
					settings: {
						slidesToShow: 2,
					}
				}
			]
		});
	}
	
	window.elkit_woo_slik_init = function () {
			
			//setTimeout(
			//alert('hello');
			$('.elkit-woo-products-widget.slider .products').each( function() {
				//alert('hello');
				//var $SlickCarousel = $(this);
				var $SlickCarousel = $('.elkit-woo-products-widget.slider');
				var pre_arrow = $SlickCarousel.data("prev-arrow");
				console.log('test');
				$(this).not('.slick-initialized').slick({   
					dots: false,
					infinite: $SlickCarousel.data("loop"),
					slidesToShow: $SlickCarousel.data("items"),
					slidesToScroll: $SlickCarousel.data("slide-to-scroll"),
					speed: $SlickCarousel.data("slide-speed"),
					useTransform: true,
					autoplay: $SlickCarousel.data("autoplay"),
					autoplaySpeed: $SlickCarousel.data("autoplay-speed"),
					centerMode: $SlickCarousel.data("center"),
					centerPadding: $SlickCarousel.data("center-padding") + "%",
					lazyLoad: 'progressive',
					arrows: $SlickCarousel.data("arrow"),
					rtl: directorypress_js_instance.is_rtl,
					prevArrow: '<i class="elkit-slider-arrow-pre ' + $SlickCarousel.data("prev-arrow") + '" style="position:'+ $SlickCarousel.data("arrow-postion") +'; z-index:10;"></i>',
					nextArrow: '<i class="elkit-slider-arrow-next ' + $SlickCarousel.data("next-arrow") + '" style="position:'+ $SlickCarousel.data("arrow-postion") +'; z-index:10;"></i>',
					draggable: true,
					vertical: false,
					responsive: [
						{
							breakpoint: 858,
							settings: {
								slidesToShow: $SlickCarousel.data("items-tablet"),
							}
						},
						{
							breakpoint: 767,
							settings: {
								slidesToShow: $SlickCarousel.data("items-mobile"),
							}
						}
					]
				});
			}); //, 5000);		
	}

})( jQuery );
