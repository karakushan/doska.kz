/**
 * Customizer controls toggles
 *
 * @package HFB
 */

( function( $ ) {

	/**
	 * Helper class for the main Customizer interface.
	 *
	 * @since 1.0.0
	 * @class ASTCustomizer
	 */
	HFBNotices = {

		/**
		 * Initializes our custom logic for the Customizer.
		 *
		 * @since 1.0.0
		 * @method init
		 */
		init: function()
		{
			this._bind();
		},

		/**
		 * Binds events for the HFB Portfolio.
		 *
		 * @since 1.0.0
		 * @access private
		 * @method _bind
		 */
		_bind: function()
		{
			$( document ).on('click', '.hfb-notice-close', HFBNotices._dismissNoticeNew );
			$( document ).on('click', '.hfb-notice .notice-dismiss', HFBNotices._dismissNotice );
		},

		_dismissNotice: function( event ) {
			event.preventDefault();

			var repeat_notice_after = $( this ).parents('.hfb-notice').data( 'repeat-notice-after' ) || '';
			var notice_id = $( this ).parents('.hfb-notice').attr( 'id' ) || '';

			HFBNotices._ajax( notice_id, repeat_notice_after );
		},

		_dismissNoticeNew: function( event ) {
			event.preventDefault();

			var repeat_notice_after = $( this ).attr( 'data-repeat-notice-after' ) || '';
			var notice_id = $( this ).parents('.hfb-notice').attr( 'id' ) || '';

			var $el = $( this ).parents('.hfb-notice');
			$el.fadeTo( 100, 0, function() {
				$el.slideUp( 100, function() {
					$el.remove();
				});
			});

			HFBNotices._ajax( notice_id, repeat_notice_after );

			var link   = $( this ).attr( 'href' ) || '';
			var target = $( this ).attr( 'target' ) || '';
			if( '' !== link && '_blank' === target ) {
				window.open(link , '_blank');
			}
		},

		_ajax: function( notice_id, repeat_notice_after ) {
			
			if( '' === notice_id ) {
				return;
			}

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action            : 'hfb-notice-dismiss',
					notice_id         : notice_id,
					repeat_notice_after : parseInt( repeat_notice_after ),
					nonce             : hfbNotices._notice_nonce
				},
			});

		}
	};

	$( function() {
		HFBNotices.init();
	} );
} )( jQuery );
