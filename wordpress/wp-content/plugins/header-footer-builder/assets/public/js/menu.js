(function($) {
    'use strict';

    $('document').ready(function() {
        // Megamenu
        function hfb_megamenu($self){
            if ($self.closest('.hfb-menu-mobile-container') && $self.closest('.hfb-menu-mobile-container').length > 0) {
                return true;
            }

            if ($self.hasClass('full-container') || $self.hasClass('full-width')) {
                $self.closest('li').css({position: 'static'});
                $self.css({width: '1170px', transform: 'translateX(-49%)'});
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
        // $('.menu-item-object-hfb_megamenu').on('hover', function(){
        //     hfb_megamenu($(this).children('.hfb-mega-menu').first());
        // });


        // Search
        $('.hfb-btn-search').on('click', function(){
            var $target = $(this).next('.hfb-search-box');
            $target.addClass('hfb-visible');
            return false;
        });
        $('.hfb-search-close').on('click', function(){
            var $self = $( this );
            var $target = $self.closest('.hfb-search-box');
            $target.removeClass('hfb-visible');
            return false;
        });

        // Minicart
        var $hfb_mini_cart = $('.hfb-mini-cart');
        $hfb_mini_cart.on('click', function(e) {
            $(this).closest('.hfb-cart-container').addClass('open');
        });
        $('.hfb-cart-container').on('click', function (e) {
            $(this).addClass('open');
        });

        $(document).on('click', function(e) {
            if ( (
                     $( e.target ).closest( '.hfb-cart-container' ).length == 0
                 ) && (
                     $( e.target ).closest( '.hfb-mini-cart' ).length == 0
                 ) ) {
                if ( $('.hfb-cart-container').hasClass( 'open' ) ) {
                    $('.hfb-cart-container').removeClass('open');
                }
            }
        });

        // Menu canvas
        $('.hfb-slideout-sidebar-icon').on('click', function () {
            var $self = $( this );
            $self.closest('.hfb-slideout-sidebar-container').addClass('open');
            return false;
        });
        $('.hfb-close-slideout-sidebar').on('click', function () {
            var $self = $( this );
            $self.closest('.hfb-slideout-sidebar-container').removeClass('open');
            return false;
        });
        $(document).on('click', function (e) {
            if ((
                    $(e.target).closest('.hfb-slideout-sidebar-container').length == 0
                ) && (
                    $(e.target).closest('.hfb-slideout-sidebar-icon').length == 0
                )) {
                if ($('.hfb-slideout-sidebar-container').hasClass('open')) {
                    $('.hfb-slideout-sidebar-container').removeClass('open');
                }
            }
        });

        // Menu icon
        $('.hfb-menu-icon').not('.open').on('click', function () {
            var $self = $( this );
            $('div.hfb-menu-icon.open').removeClass('open');
            $self.closest('.hfb-menu-icon').addClass('open');
            return false;
        });
        $(document).not('.hfb-menu-icon').on('click', function () {
            var $self = $( this );
            $self.closest('.hfb-menu-icon').removeClass('open');
            return false;
        });
        $(document).on('click', function (e) {
            if ((
                    $(e.target).closest('.hfb-menu-icon').length == 0
                ) && (
                    $(e.target).closest('.hfb-link-icon').length == 0
                )) {
                if ($('.hfb-menu-icon').hasClass('open')) {
                    $('.hfb-menu-icon').removeClass('open');
                }
            }
        });
        $('.hfb-menu-icon.open').on('click', function (e) {
            $(this).removeClass('open');
        });
        // Menu mobile
        var $hfbMenu = $('.hfb-menu-mobile-container');

        $hfbMenu.find('.hfb-dropdown-menu-toggle').on('click', function (e) {
            var subMenu;
            if ($(this).closest('li').find('.sub-menu').length > 0) {
                subMenu = $(this).closest('li').find('.sub-menu');
                console.log(subMenu.css('display'));
                if (subMenu.css('display') == 'block') {
                    subMenu.css('display', 'block').slideUp().parent().removeClass('expand');
                } else {
                    subMenu.css('display', 'none').slideDown().parent().addClass('expand');
                }
            }
            e.stopPropagation();
        });
        ;
        $hfbMenu.find('.hfb-close-mm-mobile').on('click', function(event) {
            $hfbMenu.removeClass('open');
            event.stopPropagation();
        });

        $hfbMenu.find('.hfb-menu-mobile-icon').on('click', function (event) {
            $(this).closest('.hfb-menu-mobile-container').addClass('open');

            var $hfb_menu_icon = $hfbMenu.find('.hfb-dropdown-menu-toggle').first();
            $hfbMenu.find('.hfb-dropdown-menu-toggle').css({
                lineHeight: $hfb_menu_icon.closest('li > a').outerHeight(true) + 'px'
            })
            event.stopPropagation();
        });
        $('.hfb-menu-mobile-container .hfb-header-menuside a').on('click', function(e) {
            if($(this).attr('href').indexOf('#') != -1) {
                $(this).closest('.hfb-menu-mobile-container').removeClass('open');
                e.stopPropagation();
            }
        });

        // Sticky menu
        var $hfbSticky = 1;
        if ($('.hfb-header-container').hasClass('hfb-sticky')) {
            $hfbSticky = $('.hfb-sticky .hfb-header-inside');
        } else if ($('.hfb-section.hfb-sticky').length > 0) {
            $hfbSticky = $('.hfb-section.hfb-sticky');
        }
        if ($hfbSticky !== 1) {
            $hfbSticky.each(function (index) {
                var $self = $( this );
                if($('#wpadminbar').length > 0 && $('#wpadminbar').css('position') == 'fixed') {
                    $self.sticky({
                        topSpacing: parseFloat($('html').css('marginTop')),
                        zIndex: 999,
                    });
                }
                else {
                    $self.sticky({
                        topSpacing: 0,
                        zIndex: 999,
                    });
                }

                if($self.hasClass('hfb-overlay')) {
                    $self.closest('.sticky-wrapper').addClass('hfb-overlay');
                }

            });
        }
    });

    // Auto add header
    var $bb_header = $('.hfb-auto-add-header');
    if($bb_header.length > 0) {
        $('body').prepend($bb_header);
    }

})(jQuery);
