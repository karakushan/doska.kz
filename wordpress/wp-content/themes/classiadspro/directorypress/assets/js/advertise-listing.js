/**
 * Advertise Listing Page JavaScript
 * Scripts for advertising listings page
 */

(function ($) {
    "use strict";

    /**
     * Advertising Package Selection
     * Ensures the recommended package (3 days) is selected by default
     */
    $(document).ready(function () {
        // Ensure the recommended package is selected on page load
        if ($('.advertising-form').length > 0) {
            console.log('Advertising form found');

            // Check if no radio button is selected
            if (!$('input[name="advertising_period"]:checked').length) {
                console.log('No radio button selected, selecting 3_days');
                // Select the recommended 3-day package
                $('input[name="advertising_period"][value="3_days"]').prop('checked', true);
            } else {
                console.log('Radio button already selected:', $('input[name="advertising_period"]:checked').val());
            }

            // Add visual feedback when radio buttons change
            $('input[name="advertising_period"]').on('change', function () {
                console.log('Radio button changed to:', $(this).val());
                // Remove selected styling from all cards
                $('.period-card').removeClass('period-selected');

                // Add selected styling to the chosen card
                $(this).siblings('.period-card').addClass('period-selected');
            });

            // Trigger change event for initially selected radio button
            $('input[name="advertising_period"]:checked').trigger('change');
        } else {
            console.log('Advertising form not found');
        }
    });

    /**
     * Form Validation Enhancement
     * Enhanced form validation
     */
    $(document).ready(function () {
        $('.advertising-form').on('submit', function (e) {
            console.log('Form submitted');
            var selectedPeriod = $('input[name="advertising_period"]:checked').val();
            console.log('Selected period:', selectedPeriod);
            console.log('Form action:', $(this).attr('action'));
            console.log('Form method:', $(this).attr('method'));

            if (!selectedPeriod) {
                console.log('No period selected, preventing submit');
                e.preventDefault();
                alert('Please select an advertising period');
                return false;
            }

            console.log('Form validation passed, submitting...');

            // Show loading indicator
            var submitBtn = $(this).find('button[type="submit"]');
            var originalText = submitBtn.text();
            submitBtn.prop('disabled', true).text('Processing...');

            // Allow form to submit normally
            return true;
        });
    });

    /**
     * Period Card Hover Effects
     * Hover effects for period cards
     */
    $(document).ready(function () {
        $('.period-card').hover(
            function () {
                // On hover
                if (!$(this).hasClass('period-selected')) {
                    $(this).addClass('period-hover');
                }
            },
            function () {
                // On mouse leave
                $(this).removeClass('period-hover');
            }
        );
    });

    /**
     * Smooth Scroll to Form
     * Smooth scroll to form on page load
     */
    $(document).ready(function () {
        if ($('.advertising-form').length > 0 && window.location.hash === '') {
            $('html, body').animate({
                scrollTop: $('.advertising-section').offset().top - 100
            }, 800);
        }
    });

    /**
     * Price Animation
     * Price animation when selecting package
     */
    $(document).ready(function () {
        $('input[name="advertising_period"]').on('change', function () {
            var selectedCard = $(this).siblings('.period-card');
            var priceElement = selectedCard.find('.period-price');

            // Price animation
            priceElement.addClass('price-animate');
            setTimeout(function () {
                priceElement.removeClass('price-animate');
            }, 600);
        });
    });

})(jQuery);

/**
 * CSS Animation Classes (added dynamically)
 */
$(document).ready(function () {
    // Add CSS styles for animations
    if (!$('#advertise-dynamic-styles').length) {
        $('<style id="advertise-dynamic-styles">')
            .text(`
                .period-hover {
                    transform: translateY(-3px) !important;
                    box-shadow: 0 6px 18px rgba(0, 123, 255, 0.15) !important;
                }
                
                .price-animate {
                    animation: priceScale 0.6s ease-in-out;
                }
                
                @keyframes priceScale {
                    0% { transform: scale(1); }
                    50% { transform: scale(1.1); }
                    100% { transform: scale(1); }
                }
                
                .advertising-form button[disabled] {
                    opacity: 0.7;
                    cursor: not-allowed;
                }
            `)
            .appendTo('head');
    }
});