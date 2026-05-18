jQuery(document).ready(function ($) {
    "use strict";

    $(document).on('click', '#deactivate-ithemeland-wordpress-bulk-posts-editing-pro', function (event) {
        event.preventDefault();
        $('#wpbe-deactivation-popup').show();
    });

    $(document).on('click', '#wpbe-deactivation-popup-close', function () {
        $('#wpbe-deactivation-popup').hide();
    });

    $(document).on('click', '#wpbe-deactivation-popup-deactivate', function () {
        $('.wpbe-deactivation-loading').show();
        if ('license_plugin' === $('.wpbe-deactivation-option:checked').val()) {
            $.ajax({
                url: WPBE_DATA.ajax_url,
                type: 'post',
                dataType: 'json',
                data: {
                    action: 'wpbe_deactivation_plugin',
                    nonce: WPBE_DATA.ajax_nonce,
                },
                success: function (response) {
                    window.location.href = $('#deactivate-ithemeland-wordpress-bulk-posts-editing-pro').attr('href');
                },
                error: function () {
                    window.location.href = $('#deactivate-ithemeland-wordpress-bulk-posts-editing-pro').attr('href');
                }
            })
        } else {
            window.location.href = $('#deactivate-ithemeland-wordpress-bulk-posts-editing-pro').attr('href');
        }
    });
});