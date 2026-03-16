/*
If you are using a child theme and you have added custom scripts into this file, move this file to child theme root folder and continue your modifications there!
*/

(function ($) {
  "use strict";

  /*
        Your custom JS
    */

  /**
   * Avatar Upload Preview
   * Shows preview of selected image before form submission
   */
  $(document).ready(function () {
    // Handle avatar file input change
    $('input[name="user_avatar"]').on("change", function (e) {
      var input = this;
      var $wrapper = $(input).closest(".wpfb_form_avatar, .form-group");

      // Remove existing preview
      $wrapper.find(".wpfb-avatar-preview").remove();

      if (input.files && input.files[0]) {
        var file = input.files[0];

        // Validate file type
        var validTypes = ["image/jpeg", "image/jpg", "image/png", "image/gif"];
        if (validTypes.indexOf(file.type) === -1) {
          alert("Пожалуйста, выберите изображение (JPG, PNG или GIF)");
          $(input).val("");
          return;
        }

        // Validate file size (2MB max)
        var maxSize = 2 * 1024 * 1024; // 2MB in bytes
        if (file.size > maxSize) {
          alert("Размер файла слишком большой. Максимум 2MB");
          $(input).val("");
          return;
        }

        // Create preview
        var reader = new FileReader();

        reader.onload = function (e) {
          var previewHtml =
            '<div class="wpfb-avatar-preview">' +
            '<img src="' +
            e.target.result +
            '" alt="Avatar Preview" />' +
            '<p style="margin-top:10px; font-size:12px; color:#546b7e;">' +
            "Выбрано: " +
            file.name +
            "</p>" +
            "</div>";

          $wrapper.append(previewHtml);
        };

        reader.readAsDataURL(file);
      }
    });

    // Trigger file input when clicking on custom upload button (if exists)
    $(".wpfb-avatar-upload-btn").on("click", function (e) {
      e.preventDefault();
      $(this).siblings('input[name="user_avatar"]').trigger("click");
    });

    /**
     * Filters Header Toggle
     * Adds a "Filters" header that controls all filter visibility
     */

  });

  /**
   * Masonry ImagesLoaded Fix
   * Reinitialize masonry after images are loaded
   */
  function initMasonryWithImagesLoaded() {
    var $masonryGrid = $('.m-grid');
    
    if ($masonryGrid.length && typeof $.fn.masonry === 'function') {
      $masonryGrid.each(function() {
        var $grid = $(this);
        
        // Wait for images to load
        $grid.imagesLoaded(function() {
          $grid.masonry({
            itemSelector: '.m-grid-item',
            columnWidth: '.m-grid-item',
            percentPosition: true,
            isOriginLeft: !($('body').hasClass('rtl'))
          });
          
          // Trigger layout after a short delay for any dynamic content
          setTimeout(function() {
            $grid.masonry('layout');
          }, 100);
        });
      });
    }
  }

  // Run on page load
  $(window).on('load', function() {
    initMasonryWithImagesLoaded();
  });

  // Run after AJAX content loads (for directorypress)
  $(document).ajaxComplete(function(event, xhr, settings) {
    if (settings.url.indexOf('directorypress_handler_request') !== -1) {
      setTimeout(function() {
        initMasonryWithImagesLoaded();
      }, 300);
    }
  });

  // Also run when images are lazy loaded
  $(document).on('lazyloaded', function(e) {
    var $masonryGrid = $(e.target).closest('.m-grid');
    if ($masonryGrid.length && typeof $.fn.masonry === 'function') {
      $masonryGrid.masonry('layout');
    }
  });

})(jQuery);
