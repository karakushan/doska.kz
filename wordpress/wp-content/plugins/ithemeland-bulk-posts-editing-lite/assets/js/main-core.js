"use strict";

var wpbeWpEditorSettings = {
    mediaButtons: true,
    tinymce: {
        branding: false,
        theme: "modern",
        skin: "lightgray",
        language: "en",
        formats: {
            alignleft: [
                { selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li", styles: { textAlign: "left" } },
                { selector: "img,table,dl.wp-caption", classes: "alignleft" },
            ],
            aligncenter: [
                { selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li", styles: { textAlign: "center" } },
                { selector: "img,table,dl.wp-caption", classes: "aligncenter" },
            ],
            alignright: [
                { selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li", styles: { textAlign: "right" } },
                { selector: "img,table,dl.wp-caption", classes: "alignright" },
            ],
            strikethrough: { inline: "del" },
        },
        relative_urls: false,
        remove_script_host: false,
        convert_urls: false,
        browser_spellcheck: true,
        fix_list_elements: true,
        entities: "38,amp,60,lt,62,gt",
        entity_encoding: "raw",
        keep_styles: false,
        paste_webkit_styles: "font-weight font-style color",
        preview_styles: "font-family font-size font-weight font-style text-decoration text-transform",
        end_container_on_empty_block: true,
        wpeditimage_disable_captions: false,
        wpeditimage_html5_captions: true,
        plugins: "charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview",
        menubar: false,
        wpautop: true,
        indent: false,
        resize: true,
        theme_advanced_resizing: true,
        theme_advanced_resize_horizontal: false,
        statusbar: true,
        toolbar1: "formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,unlink,wp_adv",
        toolbar2: "strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help",
        toolbar3: "",
        toolbar4: "",
        tabfocus_elements: ":prev,:next",
    },
    quicktags: {
        buttons: "strong,em,link,block,del,ins,img,ul,ol,li,code,more,close",
    },
};

jQuery(document).ready(function ($) {
    $(document).on("click", ".wpbe-timepicker, .wpbe-datetimepicker, .wpbe-datepicker", function () {
        $(this).attr("data-val", $(this).val());
    });

    wpbeReInitDatePicker();
    wpbeReInitColorPicker();

    // Select2
    if ($.fn.select2) {
        let wpbeSelect2 = $(".wpbe-select2");
        if (wpbeSelect2.length) {
            wpbeSelect2.select2({
                placeholder: "Select ...",
            });
        }
    }

    $(document).on("click", ".wpbe-tabs-list li a.wpbe-tab-item", function (event) {
        if ($(this).attr("data-disabled") !== "true") {
            event.preventDefault();

            if ($(this).closest(".wpbe-tabs-list").attr("data-type") == "url") {
                window.location.hash = $(this).attr("data-content");
            }

            wpbeOpenTab($(this));
        }
    });

    // Modal
    $(document).on("click", '[data-toggle="modal"]', function () {
        wpbeOpenModal($(this).attr("data-target"));
    });

    $(document).on("click", '[data-toggle="modal-close"]', function () {
        wpbeCloseModal();
    });

    // Float side modal
    $(document).on("click", '[data-toggle="float-side-modal"]', function () {
        wpbeOpenFloatSideModal($(this).attr("data-target"));
    });

    $(document).on("click", '[data-toggle="float-side-modal-close"]', function () {
        if ($(".wpbe-float-side-modal:visible").length && $(".wpbe-float-side-modal:visible").hasClass("wpbe-float-side-modal-close-with-confirm")) {
            swal(
                {
                    title: "Are you sure?",
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonClass: "wpbe-button wpbe-button-lg wpbe-button-white",
                    confirmButtonClass: "wpbe-button wpbe-button-lg wpbe-button-green",
                    confirmButtonText: wpbeTranslate.iAmSure,
                    closeOnConfirm: true,
                },
                function (isConfirm) {
                    if (isConfirm) {
                        $(".wpbe-float-side-modal:visible").removeClass("wpbe-float-side-modal-close-with-confirm");
                        wpbeCloseFloatSideModal();
                    }
                }
            );
        } else {
            wpbeCloseFloatSideModal();
        }
    });

    $(document).on("keyup", function (e) {
        if (e.keyCode === 27) {
            if (jQuery(".wpbe-modal:visible").length > 0) {
                wpbeCloseModal();
            } else {
                if ($(".wpbe-float-side-modal:visible").length && $(".wpbe-float-side-modal:visible").hasClass("wpbe-float-side-modal-close-with-confirm")) {
                    swal(
                        {
                            title:
                                $(".wpbe-float-side-modal:visible").attr("data-confirm-message") && $(".wpbe-float-side-modal:visible").attr("data-confirm-message") != ""
                                    ? $(".wpbe-float-side-modal:visible").attr("data-confirm-message")
                                    : "Are you sure?",
                            type: "warning",
                            showCancelButton: true,
                            cancelButtonClass: "wpbe-button wpbe-button-lg wpbe-button-white",
                            confirmButtonClass: "wpbe-button wpbe-button-lg wpbe-button-green",
                            confirmButtonText: wpbeTranslate.iAmSure,
                            closeOnConfirm: true,
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                $(".wpbe-float-side-modal:visible").removeClass("wpbe-float-side-modal-close-with-confirm");
                                wpbeCloseFloatSideModal();
                            }
                        }
                    );
                } else {
                    if (!$(".wpbe-float-side-modal:visible").hasClass("wpbe-disable-esc-for-close")) {
                        wpbeCloseFloatSideModal();
                    }
                }
            }

            $("[data-type=edit-mode]").each(function () {
                $(this).closest("span").html($(this).attr("data-val"));
            });

            if ($("#wpbe-filter-form-content").css("display") === "block") {
                $("#wpbe-bulk-edit-filter-form-close-button").trigger("click");
            }
        }
    });

    // Color Picker Style
    $(document).on("change", "input[type=color]", function () {
        this.parentNode.style.backgroundColor = this.value;
    });

    $(document).on("click", "#wpbe-full-screen", function () {
        if ($("#adminmenuback").css("display") === "block") {
            openFullscreen();
        } else {
            exitFullscreen();
        }
    });

    if (document.addEventListener) {
        document.addEventListener("fullscreenchange", wpbeFullscreenHandler, false);
        document.addEventListener("mozfullscreenchange", wpbeFullscreenHandler, false);
        document.addEventListener("MSFullscreenChange", wpbeFullscreenHandler, false);
        document.addEventListener("webkitfullscreenchange", wpbeFullscreenHandler, false);
    }

    $(document).on("click", ".wpbe-top-nav-duplicate-button", function () {
        let itemIds = $("input.wpbe-check-item:visible:checkbox:checked").map(function () {
            if ($(this).attr("data-item-type") === "variation") {
                swal({
                    title: "Duplicate for variations product is disabled !",
                    type: "warning",
                });
            } else {
                return $(this).val();
            }
        }).get();

        if (!itemIds.length) {
            swal({
                title: $('input.wpbe-check-item[data-item-type="variation"]:visible:checkbox:checked') ? "Duplicate for variations product is disabled !" : "Please select one product",
                type: "warning",
            });
            return false;
        } else {
            wpbeOpenModal("#wpbe-modal-item-duplicate");
        }
    });

    // Select Items (Checkbox) in table
    $(document).on("change", ".wpbe-check-item-main", function () {
        let checkbox_items = $(".wpbe-check-item");
        if ($(this).prop("checked") === true) {
            checkbox_items.prop("checked", true);
            $("#wpbe-items-list tr").addClass("wpbe-tr-selected");
            checkbox_items.each(function () {
                $("#wpbe-export-items-selected").append("<input type='hidden' name='item_ids[]' value='" + $(this).val() + "'>");
            });
            wpbeShowSelectionTools();
            $("#wpbe-export-only-selected-items").prop("disabled", false);
        } else {
            checkbox_items.prop("checked", false);
            $("#wpbe-items-list tr").removeClass("wpbe-tr-selected");
            $("#wpbe-export-items-selected").html("");
            wpbeHideSelectionTools();
            $("#wpbe-export-only-selected-items").prop("disabled", true);
            $("#wpbe-export-all-items-in-table").prop("checked", true);
        }
    });

    $(document).on("change", ".wpbe-check-item", function () {
        if ($(this).prop("checked") === true) {
            $("#wpbe-export-items-selected").append("<input type='hidden' name='item_ids[]' value='" + $(this).val() + "'>");
            $(this).closest("tr").addClass("wpbe-tr-selected");
        } else {
            $("#wpbe-export-items-selected")
                .find("input[value=" + $(this).val() + "]")
                .remove();
            $(this).closest("tr").removeClass("wpbe-tr-selected");
        }

        wpbeCheckSelectAllStatus();

        // Disable and enable "Only Selected items" in "Import/Export"
        if ($(".wpbe-check-item:checkbox:checked").length > 0) {
            $("#wpbe-export-only-selected-items").prop("disabled", false);
            wpbeShowSelectionTools();
        } else {
            wpbeHideSelectionTools();
            $("#wpbe-export-only-selected-items").prop("disabled", true);
            $("#wpbe-export-all-items-in-table").prop("checked", true);
        }
    });

    $(document).on("click", "#wpbe-bulk-edit-unselect", function () {
        $("input.wpbe-check-item").prop("checked", false);
        $("input.wpbe-check-item-main").prop("checked", false);
        wpbeHideSelectionTools();
    });

    // Start "Column Profile"
    $(document).on("change", "#wpbe-column-profiles-choose", function () {
        let preset = $(this).val();
        $('.wpbe-column-profiles-fields input[type="checkbox"]').prop("checked", false);
        $("#wpbe-column-profile-select-all").prop("checked", false);
        $(".wpbe-column-profile-select-all span").text("Select All");
        $("#wpbe-column-profiles-apply").attr("data-preset-key");
        if (defaultPresets && $.inArray(preset, defaultPresets) === -1) {
            $("#wpbe-column-profiles-update-changes").show();
        } else {
            $("#wpbe-column-profiles-update-changes").hide();
        }

        if (columnPresetsFields && columnPresetsFields[preset]) {
            columnPresetsFields[preset].forEach(function (val) {
                $('.wpbe-column-profiles-fields input[type="checkbox"][value="' + val + '"]').prop("checked", true);
            });
        }
    });

    $(document).on("keyup", "#wpbe-column-profile-search", function () {
        let wpbeSearchFieldValue = $(this).val().toLowerCase().trim();
        $(".wpbe-column-profile-fields ul li").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(wpbeSearchFieldValue) > -1);
        });
    });

    $(document).on("change", "#wpbe-column-profile-select-all", function () {
        if ($(this).prop("checked") === true) {
            $(this).closest("label").find("span").text("Unselect");
            $(".wpbe-column-profile-fields input:checkbox:visible").prop("checked", true);
        } else {
            $(this).closest("label").find("span").text("Select All");
            $(".wpbe-column-profile-fields input:checkbox").prop("checked", false);
        }
        $(".wpbe-column-profile-save-dropdown").show();
    });
    // End "Column Profile"

    // Calculator for numeric TD
    $(document).on(
        {
            mouseenter: function () {
                $(this).children(".wpbe-calculator").show();
            },
            mouseleave: function () {
                $(this).children(".wpbe-calculator").hide();
            },
        },
        "td[data-content-type=regular_price], td[data-content-type=sale_price], td[data-content-type=numeric]"
    );

    // delete items button
    $(document).on("click", ".wpbe-bulk-edit-delete-item", function () {
        $(this).find(".wpbe-bulk-edit-delete-item-buttons").slideToggle(200);
    });

    $(document).on("change", ".wpbe-column-profile-fields input:checkbox", function () {
        $(".wpbe-column-profile-save-dropdown").show();
    });

    $(document).on("click", ".wpbe-column-profile-save-dropdown", function () {
        $(this).find(".wpbe-column-profile-save-dropdown-buttons").slideToggle(200);
    });

    $("#wp-admin-bar-root-default").append('<li id="wp-admin-bar-wpbe-col-view"></li>');

    $(document).on(
        {
            mouseenter: function () {
                $("#wp-admin-bar-wpbe-col-view").html(
                    "#" + $(this).attr("data-item-id") + " | " + $(this).attr("data-item-title") + ' [<span class="wpbe-col-title">' + $(this).attr("data-col-title") + "</span>] "
                );
            },
            mouseleave: function () {
                $("#wp-admin-bar-wpbe-col-view").html("");
            },
        },
        "#wpbe-items-list td"
    );

    $(document).on("click", ".wpbe-open-uploader", function (e) {
        let target = $(this).attr("data-target");
        let element = $(this).closest("div");
        let type = $(this).attr("data-type");
        let mediaUploader;
        let wpbeNewImageElementID = $(this).attr("data-id");
        let wpbeProductID = $(this).attr("data-item-id");
        e.preventDefault();
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        if (type === "single") {
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: "Choose Image",
                button: {
                    text: "Choose Image",
                },
                multiple: false,
            });
        } else {
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: "Choose Images",
                button: {
                    text: "Choose Images",
                },
                multiple: true,
            });
        }

        mediaUploader.on("select", function () {
            let attachment = mediaUploader.state().get("selection").toJSON();
            if ($(target).length) {
                $(target).val(attachment[0].id);
                if ($(target + "-url").length) {
                    $(target + "-url").val(attachment[0].url);
                }
                if ($(target + "-preview").length) {
                    $(target + "-preview").html(
                        '<div><img src="' + attachment[0].url + '" width="43" height="43" alt=""><button type="button" class="wpbe-bulk-edit-form-remove-image"><i class="wpbe-icon-x"></i></button></div>'
                    );
                }
            } else {
                switch (target) {
                    case "inline-file":
                        $("#url-" + wpbeNewImageElementID).val(attachment[0].url);
                        break;
                    case "inline-file-custom-field":
                        $("#wpbe-file-url").val(attachment[0].url);
                        $("#wpbe-file-id").val(attachment[0].id);
                        break;
                    case "inline-edit":
                        $("#" + wpbeNewImageElementID).val(attachment[0].url);
                        $("#wpbe-modal-image button[data-item-id=" + wpbeProductID + "][data-button-type=save]")
                            .attr("data-image-id", attachment[0].id)
                            .attr("data-image-url", attachment[0].url)
                            .attr("data-height-fixed", "false");
                        $("[data-image-preview-id=" + wpbeNewImageElementID + "]")
                            .html("<img src='" + attachment[0].url + "' alt='' />")
                            .ready(function () {
                                $('#wpbe-modal-image button[data-button-type="save"]').prop("disabled", false);
                                setTimeout(function () {
                                    wpbeFixModalHeight($("#wpbe-modal-image"));
                                }, 150);
                            });
                        break;
                    case "variations-inline-edit":
                        $("#wpbe-variation-thumbnail-modal .wpbe-inline-image-preview").html("<img src='" + attachment[0].url + "' alt='' />");
                        $('#wpbe-variation-thumbnail-modal .wpbe-variations-table-thumbnail-inline-edit-button[data-button-type="save"]')
                            .attr("data-image-id", attachment[0].id)
                            .attr("data-image-url", attachment[0].url);
                        break;
                    case "inline-edit-gallery":
                        attachment.forEach(function (item) {
                            $("#wpbe-modal-gallery-items").append(
                                '<div class="wpbe-inline-edit-gallery-item"><img src="' + item.url + '" alt=""><input type="hidden" class="wpbe-inline-edit-gallery-image-ids" value="' + item.id + '"></div>'
                            );
                        });
                        break;
                    case "bulk-edit-image":
                        element.find(".wpbe-bulk-edit-form-item-image").val(attachment[0].id);
                        element
                            .find(".wpbe-bulk-edit-form-item-image-preview")
                            .html(
                                '<div><img src="' + attachment[0].url + '" width="43" height="43" alt=""><button type="button" class="wpbe-bulk-edit-form-remove-image"><i class="wpbe-icon-x"></i></button></div>'
                            );
                        break;
                    case "variations-bulk-actions-image":
                        element.find(".wpbe-variations-bulk-actions-image").val(attachment[0].id);
                        element
                            .find(".wpbe-variations-bulk-actions-image-preview")
                            .html(
                                '<div><img src="' +
                                attachment[0].url +
                                '" width="43" height="43" alt=""><button type="button" class="wpbe-variations-bulk-actions-remove-image"><i class="wpbe-icon-x"></i></button></div>'
                            );
                        break;
                    case "variations-bulk-actions-file":
                        element.find(".wpbe-variation-bulk-actions-file-item-url-input").val(attachment[0].url);
                        break;
                    case "bulk-edit-file":
                        element.find(".wpbe-bulk-edit-form-item-file").val(attachment[0].id);
                        break;
                    case "bulk-edit-gallery":
                        attachment.forEach(function (item) {
                            $(".wpbe-bulk-edit-form-item-gallery").append('<input type="hidden" value="' + item.id + '" data-field="value">');
                            $(".wpbe-bulk-edit-form-item-gallery-preview").append(
                                '<div><img src="' +
                                item.url +
                                '" width="43" height="43" alt=""><button type="button" data-id="' +
                                item.id +
                                '" class="wpbe-bulk-edit-form-remove-gallery-item"><i class="wpbe-icon-x"></i></button></div>'
                            );
                        });
                        break;
                }
            }
        });
        mediaUploader.open();
    });

    $(document).on("click", ".wpbe-inline-edit-gallery-image-item-delete", function () {
        $(this).closest("div").remove();
    });

    $(document).on("change", ".wpbe-column-manager-check-all-fields-btn input:checkbox", function () {
        if ($(this).prop("checked")) {
            $(this).closest("label").find("span").addClass("selected").text("Unselect");
            $(".wpbe-column-manager-available-fields[data-action=" + $(this).closest("label").attr("data-action") + "] li:visible").each(function () {
                $(this).find("input:checkbox").prop("checked", true);
            });
        } else {
            $(this).closest("label").find("span").removeClass("selected").text("Select All");
            $(".wpbe-column-manager-available-fields[data-action=" + $(this).closest("label").attr("data-action") + "] li:visible input:checked").prop("checked", false);
        }
    });

    $(document).on("click", ".wpbe-column-manager-add-field", function () {
        let fieldName = [];
        let fieldLabel = [];
        let action = $(this).attr("data-action");
        let checked = $(".wpbe-column-manager-available-fields[data-action=" + action + "] input[data-type=field]:checkbox:checked");
        if (checked.length > 0) {
            $(".wpbe-column-manager-empty-text").hide();
            if (action === "new") {
                $(".wpbe-column-manager-added-fields-wrapper .wpbe-box-loading").show();
            } else {
                $("#wpbe-modal-column-manager-edit-preset .wpbe-box-loading").show();
            }
            checked.each(function (i) {
                fieldName[i] = $(this).attr("data-name");
                fieldLabel[i] = $(this).val();
            });
            wpbeColumnManagerAddField(fieldName, fieldLabel, action);
        }
    });

    $(".wpbe-column-manager-delete-preset").on("click", function () {
        var $this = $(this);
        $("#wpbe_column_manager_delete_preset_key").val($this.val());
        swal(
            {
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wpbe-button wpbe-button-lg wpbe-button-white",
                confirmButtonClass: "wpbe-button wpbe-button-lg wpbe-button-green",
                confirmButtonText: "Yes, I'm sure !",
                closeOnConfirm: true,
            },
            function (isConfirm) {
                if (isConfirm) {
                    $("#wpbe-column-manager-delete-preset-form").submit();
                }
            }
        );
    });

    $(document).on("keyup", ".wpbe-column-manager-search-field", function () {
        let wpbeSearchFieldValue = $(this).val().toLowerCase().trim();
        $(".wpbe-column-manager-available-fields[data-action=" + $(this).attr("data-action") + "] ul li[data-added=false]").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(wpbeSearchFieldValue) > -1);
        });
    });

    $(document).on("click", ".wpbe-column-manager-remove-field", function () {
        $(".wpbe-column-manager-available-fields[data-action=" + $(this).attr("data-action") + "] li[data-name=" + $(this).attr("data-name") + "]")
            .attr("data-added", "false")
            .show();
        $(this).closest(".wpbe-column-manager-right-item").remove();
        if ($(".wpbe-column-manager-added-fields-wrapper .wpbe-column-manager-right-item").length < 1) {
            $(".wpbe-column-manager-empty-text").show();
        }
    });

    if ($.fn.sortable) {
        let wpbeColumnManagerFields = $(".wpbe-column-manager-added-fields .items");
        wpbeColumnManagerFields.sortable({
            handle: ".wpbe-column-manager-field-sortable-btn",
            cancel: "",
        });
        wpbeColumnManagerFields.disableSelection();

        let wpbeMetaFieldItems = $(".wpbe-meta-fields-right");
        wpbeMetaFieldItems.sortable({
            handle: ".wpbe-meta-field-item-sortable-btn",
            cancel: "",
        });
        wpbeMetaFieldItems.disableSelection();
    }

    $(document).on("click", "#wpbe-add-meta-field-manual", function () {
        $(".wpbe-meta-fields-empty-text").hide();
        let input = $("#wpbe-meta-fields-manual_key_name");
        wpbeAddMetaKeysManual(input.val().toLowerCase());
        input.val("");
    });

    $(document).on("click", ".wpbe-meta-field-remove", function () {
        $(this).closest(".wpbe-meta-fields-right-item").remove();
        if ($(".wpbe-meta-fields-right-item").length < 1) {
            $(".wpbe-meta-fields-empty-text").show();
        }
    });

    $(document).on("click", ".wpbe-history-delete-item", function () {
        $("#wpbe-history-clicked-id").attr("name", "delete").val($(this).val());
        swal(
            {
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wpbe-button wpbe-button-lg wpbe-button-white",
                confirmButtonClass: "wpbe-button wpbe-button-lg wpbe-button-green",
                confirmButtonText: "Yes, I'm sure !",
                closeOnConfirm: true,
            },
            function (isConfirm) {
                if (isConfirm) {
                    $("#wpbe-history-items").submit();
                }
            }
        );
    });

    $(document).on("click", "#wpbe-history-clear-all-btn", function () {
        swal(
            {
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wpbe-button wpbe-button-lg wpbe-button-white",
                confirmButtonClass: "wpbe-button wpbe-button-lg wpbe-button-green",
                confirmButtonText: "Yes, I'm sure !",
                closeOnConfirm: true,
            },
            function (isConfirm) {
                if (isConfirm) {
                    $("#wpbe-history-clear-all").submit();
                }
            }
        );
    });

    $(document).on("click", ".wpbe-history-revert-item", function () {
        $("#wpbe-history-clicked-id").attr("name", "revert").val($(this).val());
        swal(
            {
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wpbe-button wpbe-button-lg wpbe-button-white",
                confirmButtonClass: "wpbe-button wpbe-button-lg wpbe-button-green",
                confirmButtonText: "Yes, I'm sure !",
                closeOnConfirm: true,
            },
            function (isConfirm) {
                if (isConfirm) {
                    $("#wpbe-history-items").submit();
                }
            }
        );
    });

    $(document).on("click", ".wpbe-modal", function (e) {
        if ($(e.target).hasClass("wpbe-modal") || $(e.target).hasClass("wpbe-modal-container") || $(e.target).hasClass("wpbe-modal-box")) {
            wpbeCloseModal();
        }
    });

    $(document).on("change", 'select[data-field="operator"]', function () {
        if ($(this).val() === "number_formula") {
            $(this).closest("div").find("input[type=number]").attr("type", "text");
        }
    });

    $(document).on("change", "#wpbe-filter-form-content [data-field=value], #wpbe-filter-form-content [data-field=from], #wpbe-filter-form-content [data-field=to]", function () {
        wpbeCheckFilterFormChanges();
    });

    $(document).on("change", "input[type=number][data-field=to]", function () {
        let from = $(this).closest(".wpbe-form-group").find("input[type=number][data-field=from]");
        if (parseFloat($(this).val()) < parseFloat(from.val())) {
            from.val("").addClass("wpbe-input-danger").focus();
        }
    });

    $(document).on("change", "input[type=number][data-field=from]", function () {
        let to = $(this).closest(".wpbe-form-group").find("input[type=number][data-field=to]");
        if (parseFloat($(this).val()) > parseFloat(to.val())) {
            $(this).val("").addClass("wpbe-input-danger");
        } else {
            $(this).removeClass("wpbe-input-danger");
        }
    });

    $(document).on("change", "#wpbe-switcher", function () {
        wpbeLoadingStart();
        $("#wpbe-switcher-form").submit();
    });

    $(document).on("click", 'span[data-target="#wpbe-modal-image"]', function () {
        let tdElement = $(this).closest("td");
        let modal = $("#wpbe-modal-image");
        let col_title = tdElement.attr("data-col-title");
        let id = $(this).attr("data-id");
        let image_id = $(this).attr("data-image-id");
        let item_id = tdElement.attr("data-item-id");
        let full_size_url = $(this).attr("data-full-image-src");
        let field = tdElement.attr("data-field");
        let field_type = tdElement.attr("data-field-type");

        $("#wpbe-modal-image-item-title").text(col_title);
        modal.find(".wpbe-open-uploader").attr("data-id", id).attr("data-item-id", item_id);
        modal
            .find(".wpbe-inline-image-preview")
            .attr("data-image-preview-id", id)
            .html('<img src="' + full_size_url + '" />')
            .ready(function () {
                modal.find(".wpbe-inline-image-preview img").load(function () {
                    wpbeFixModalHeight(modal);
                });
            });
        modal.find(".wpbe-image-preview-hidden-input").attr("id", id);
        modal
            .find('button[data-button-type="save"]')
            .attr("data-item-id", item_id)
            .attr("data-field", field)
            .attr("data-image-url", full_size_url)
            .attr("data-image-id", image_id)
            .attr("data-field-type", field_type)
            .attr("data-name", tdElement.attr("data-name"))
            .attr("data-update-type", tdElement.attr("data-update-type"));
        modal
            .find('button[data-button-type="remove"]')
            .attr("data-item-id", item_id)
            .attr("data-field", field)
            .attr("data-field-type", field_type)
            .attr("data-name", tdElement.attr("data-name"))
            .attr("data-update-type", tdElement.attr("data-update-type"));
        modal.find('button[data-button-type="save"]').prop("disabled", true);

        if (image_id == "0") {
            modal.find('button[data-button-type="remove"]').prop("disabled", true);
        } else {
            modal.find('button[data-button-type="remove"]').prop("disabled", false);
        }
    });

    $(document).on("click", "#wpbe-modal-file-clear", function () {
        let modal = $("#wpbe-modal-file");
        modal.find("#wpbe-file-id").val(0).change();
        modal.find("#wpbe-file-url").val("").change();
    });

    $(document).on("click", ".wpbe-sub-tab-title", function () {
        $(this).closest(".wpbe-sub-tab-titles").find(".wpbe-sub-tab-title").removeClass("active");
        $(this).addClass("active");

        $(this).closest("div").find(".wpbe-sub-tab-content").hide();
        $(this)
            .closest("div")
            .find('.wpbe-sub-tab-content[data-content="' + $(this).attr("data-content") + '"]')
            .show();
    });

    if ($(".wpbe-sub-tab-titles").length > 0) {
        $(".wpbe-sub-tab-titles").each(function () {
            $(this).find(".wpbe-sub-tab-title").first().trigger("click");
        });
    }

    $(document).on("mouseenter", ".wpbe-thumbnail", function () {
        let position = $(this).offset();
        let imageHeight = $(this).find("img").first().height();
        let top = position.top - imageHeight > $("#wpadminbar").offset().top ? position.top - imageHeight : position.top + 15;

        $(".wpbe-thumbnail-hover-box")
            .css({
                top: top,
                left: position.left - 100,
                display: "block",
                height: imageHeight,
            })
            .html($(this).find(".wpbe-original-thumbnail").clone());
    });

    $(document).on("mouseleave", ".wpbe-thumbnail", function () {
        $(".wpbe-thumbnail-hover-box").hide();
    });

    setTimeout(function () {
        $("#wpbe-column-profiles-choose").trigger("change");
    }, 500);

    $(document).on("click", ".wpbe-filter-form-action", function () {
        wpbeFilterFormClose();
    });

    $(document).on("click", "#wpbe-license-renew-button", function () {
        $(this).closest("#wpbe-license").find(".wpbe-license-form").slideDown();
    });

    $(document).on("click", "#wpbe-license-form-cancel", function () {
        $(this).closest("#wpbe-license").find(".wpbe-license-form").slideUp();
    });

    $(document).on("click", "#wpbe-license-deactivate-button", function () {
        swal(
            {
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wpbe-button wpbe-button-lg wpbe-button-white",
                confirmButtonClass: "wpbe-button wpbe-button-lg wpbe-button-green",
                confirmButtonText: "Yes, I'm sure !",
                closeOnConfirm: true,
            },
            function (isConfirm) {
                if (isConfirm) {
                    $("#wpbe-license-deactivation-form").submit();
                }
            }
        );
    });

    wpbeSetTipsyTooltip();

    $(window).on("resize", function () {
        wpbeDataTableFixSize();
    });

    $(document).on("click", "body", function (e) {
        if (!$(e.target).hasClass("wpbe-status-filter-button") && $(e.target).closest(".wpbe-status-filter-button").length == 0) {
            $(".wpbe-top-nav-status-filter").hide();
        }

        if (
            !$(e.target).hasClass("wpbe-table-item-selector-container") &&
            !$(e.target).closest(".wpbe-table-item-selector-container").length &&
            $(".wpbe-table-item-selector-container ul:visible").length
        ) {
            $(".wpbe-table-item-selector-container ul:visible").fadeOut(50);
        }

        if (!$(e.target).hasClass("wpbe-quick-filter") && $(e.target).closest(".wpbe-quick-filter").length == 0) {
            $(".wpbe-top-nav-filters").hide();
        }

        if (!$(e.target).hasClass("wpbe-post-type-switcher") && $(e.target).closest(".wpbe-post-type-switcher").length == 0) {
            $(".wpbe-top-nav-filters-switcher").hide();
        }

        if (
            !$(e.target).hasClass("wpbe-float-side-modal") &&
            !$(e.target).closest(".wpbe-float-side-modal-box").length &&
            !$(".sweet-overlay:visible").length &&
            !$(".wpbe-modal:visible").length &&
            $(e.target).attr("data-toggle") != "float-side-modal" &&
            !$(e.target).closest(".select2-container").length &&
            !$(e.target).is("i") &&
            !$(e.target).hasClass("wpbe-bulk-edit-form-remove-image") &&
            !$(e.target).hasClass("wpbe-bulk-edit-custom-field-file-remove-item") &&
            !$(e.target).closest(".media-modal").length &&
            !$(e.target).closest(".sweet-alert").length &&
            !$(e.target).closest('[data-toggle="float-side-modal"]').length &&
            !$(e.target).closest('[data-toggle="float-side-modal-after-confirm"]').length
        ) {
            if ($(".wpbe-float-side-modal:visible").length && $(".wpbe-float-side-modal:visible").hasClass("wpbe-float-side-modal-close-with-confirm")) {
                swal(
                    {
                        title:
                            $(".wpbe-float-side-modal:visible").attr("data-confirm-message") && $(".wpbe-float-side-modal:visible").attr("data-confirm-message") != ""
                                ? $(".wpbe-float-side-modal:visible").attr("data-confirm-message")
                                : "Are you sure?",
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonClass: "wpbe-button wpbe-button-lg wpbe-button-white",
                        confirmButtonClass: "wpbe-button wpbe-button-lg wpbe-button-green",
                        confirmButtonText: wpbeTranslate.iAmSure,
                        closeOnConfirm: true,
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $(".wpbe-float-side-modal:visible").removeClass("wpbe-float-side-modal-close-with-confirm");
                            wpbeCloseFloatSideModal();
                        }
                    }
                );
            } else {
                wpbeCloseFloatSideModal();
            }
        }
    });

    $(document).on("click", ".wpbe-status-filter-button", function () {
        $(this).closest(".wpbe-status-filter-container").find(".wpbe-top-nav-status-filter").toggle();
    });

    $(document).on("click", ".wpbe-quick-filter > a", function (e) {
        if (!$(e.target).closest(".wpbe-top-nav-filters").length) {
            $(".wpbe-top-nav-filters").slideToggle(150);
        }
    });
    $(document).on("click", ".wpbe-post-type-switcher > a", function (e) {
        if (!$(e.target).closest(".wpbe-top-nav-filters-switcher").length) {
            $(".wpbe-top-nav-filters-switcher").slideToggle(150);
        }
    });

    $(document).on("click", ".wpbe-bind-edit-switch", function () {
        if ($("#wpbe-bind-edit").prop("checked") === true) {
            $("#wpbe-bind-edit").prop("checked", false);
            $(this).removeClass("active");
        } else {
            $("#wpbe-bind-edit").prop("checked", true);
            $(this).addClass("active");
        }
    });

    if ($("#wpbe-bind-edit").prop("checked") === true) {
        $(".wpbe-bind-edit-switch").addClass("active");
    } else {
        $(".wpbe-bind-edit-switch").removeClass("active");
    }

    if ($(".wpbe-flush-message").length) {
        setTimeout(function () {
            $(".wpbe-flush-message").slideUp();
        }, 3000);
    }

    $(document).on("input", "#wpbe-top-nav-filters-go-to-page", function () {
        if ($(this).val() == "") {
            return;
        }

        if (parseInt($(this).val()) < parseInt($(this).attr("min"))) {
            $(this).val($(this).attr("min"));
        }

        if (parseInt($(this).val()) > parseInt($(this).attr("max"))) {
            $(this).val($(this).attr("max"));
        }
    });

    $(document).on("click", ".wpbe-table-item-selector", function () {
        if ($(this).find("ul:visible").length) {
            $(this).find("ul").fadeOut(50);
        } else {
            $(this).find("ul").fadeIn(50);
        }
    });

    $(document).on("click", ".wpbe-table-item-selector label", function () {
        $(this).find("ul").fadeOut(50);
    });

    $(document).on("change", ".wpbe-table-item-selector label input:checkbox", function () {
        $(".wpbe-table-item-selector-checkbox").prop("checked", $(this).prop("checked"));

        if ($(this).val() == "visible") {
            $('.wpbe-check-item-main[value="all"]').prop("checked", false);
        } else {
            $('.wpbe-check-item-main[value="visible"]').prop("checked", false);
        }
    });

    setTimeout(function () {
        if ($("#wpbe-quick-search-reset").css("display") == "none") {
            $("li.wpbe-quick-filter a").removeClass("active");
        } else {
            $("li.wpbe-quick-filter a").addClass("active");
        }
    }, 150);

    wpbeDataTableFixSize();
});
