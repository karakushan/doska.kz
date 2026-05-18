jQuery(document).ready(function ($) {
    "use strict";

    // Select2
    if ($.fn.select2) {
        wpbeGetPostTags();
    }

    let userQuery;
    $(".wpbe-select2-users").select2({
        ajax: {
            type: "post",
            delay: 800,
            url: WPBE_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                userQuery = {
                    action: "wpbe_get_users",
                    nonce: WPBE_DATA.ajax_nonce,
                    search: params.term,
                };
                return userQuery;
            },
        },
        placeholder: "Username ...",
        minimumInputLength: 3,
    });

    // Inline edit
    $(document).on("click", "td[data-action=inline-editable]", function (e) {
        if ($(e.target).attr("data-type") !== "edit-mode" && $(e.target).find("[data-type=edit-mode]").length === 0) {
            // Close All Inline Edit
            $("[data-type=edit-mode]").each(function () {
                $(this).closest("span").html($(this).attr("data-val"));
            });

            let text = $(this).find('[data-action="inline-editable"]').text().trim();
            let fullText =
                $(this).find('[data-action="inline-editable"]').attr("data-full-text") && $(this).find('[data-action="inline-editable"]').attr("data-full-text") != ""
                    ? $(this).find('[data-action="inline-editable"]').attr("data-full-text").trim()
                    : text;

            // Open Clicked Inline Edit
            switch ($(this).attr("data-content-type")) {
                case "text":
                    $(this)
                        .children("span")
                        .html(
                            "<textarea data-item-id='" +
                            $(this).attr("data-item-id") +
                            "' data-field='" +
                            $(this).attr("data-field") +
                            "' data-field-type='" +
                            $(this).attr("data-field-type") +
                            "' data-type='edit-mode' data-val='" +
                            text +
                            "'>" +
                            fullText +
                            "</textarea>"
                        )
                        .children("textarea")
                        .focus()
                        .select();
                    break;
                case "numeric":
                case "regular_price":
                case "sale_price":
                    $(this)
                        .children("span")
                        .html(
                            "<input type='number' min='-1' data-item-id='" +
                            $(this).attr("data-item-id") +
                            "' data-field='" +
                            $(this).attr("data-field") +
                            "' data-field-type='" +
                            $(this).attr("data-field-type") +
                            "' data-type='edit-mode' data-val='" +
                            text +
                            "' value='" +
                            fullText.replaceAll(",", "") +
                            "'>"
                        )
                        .children("input[type=number]")
                        .focus()
                        .select();
                    break;
            }
        }
    });

    // Discard Save
    $(document).on("click", function (e) {
        if ($(e.target).attr("data-action") !== "inline-editable" && $(e.target).attr("data-type") !== "edit-mode") {
            $("[data-type=edit-mode]").each(function () {
                $(this).closest("span").html($(this).attr("data-val"));
            });
        }
    });

    $(document).on("input", "#wpbe-meta-fields-manual_key_name", function () {
        let containerElement = $(this).closest(".wpbe-meta-fields-manual-field");
        let errorMessageElement = containerElement.find(".wpbe-add-meta-field-message");
        if ($(this).val() != "") {
            if ($.inArray($(this).val().toLowerCase(), WPBE_DATA.reserved_field_keys) === -1 && !$('input.wpbe_meta_field_key_input[value="' + $(this).val().toLowerCase() + '"]').length) {
                $("#wpbe-add-meta-field-manual").prop("disabled", false);
                containerElement.removeClass("wpbe-add-meta-field-name-error");
                errorMessageElement.text("");
            } else {
                $("#wpbe-add-meta-field-manual").prop("disabled", true);
                containerElement.addClass("wpbe-add-meta-field-name-error");
                errorMessageElement.text("This name exists");
            }
        } else {
            $("#wpbe-add-meta-field-manual").prop("disabled", true);
            containerElement.removeClass("wpbe-add-meta-field-name-error");
            errorMessageElement.text("");
        }
    });

    // Save Inline Edit By Enter Key
    $(document).on("keypress", '[data-type="edit-mode"]', function (event) {
        let wpbeKeyCode = event.keyCode ? event.keyCode : event.which;
        if (wpbeKeyCode === 13) {
            let postData = [];
            let postIds = [];
            let tdElement = $(this).closest("td");

            if (wpbeSelectAllChecked() && $("#wpbe-bind-edit").prop("checked") === true) {
                postIds = "all_filtered";
            } else {
                if ($("#wpbe-bind-edit").prop("checked") === true) {
                    postIds = wpbeGetPostsChecked();
                } else {
                    postIds = [];
                }
                if ($.isArray(postIds)) {
                    postIds.push($(this).attr("data-item-id"));
                }
            }

            postData.push({
                name: tdElement.attr("data-name"),
                sub_name: tdElement.attr("data-sub-name") ? tdElement.attr("data-sub-name") : "",
                type: tdElement.attr("data-update-type"),
                value: $(this).val(),
                operation: "inline_edit",
            });

            $(this).closest("span").html($(this).val());
            wpbePostEdit(postIds, postData);
        }
    });

    // fetch post data by click to bulk edit button
    $(document).on("click", "#wpbe-bulk-edit-bulk-edit-btn", function () {
        if ($(this).attr("data-fetch-post") === "yes") {
            let postID = $("input.wpbe-check-item:checkbox:checked");
            if (postID.length === 1) {
                wpbeGetPostData(postID.val());
            } else {
                wpbeResetBulkEditForm();
            }
        }
    });

    $(document).on("change", ".wpbe-inline-edit-action", function (e) {
        let $this = $(this);
        setTimeout(function () {
            if ($("div.xdsoft_datetimepicker:visible").length > 0) {
                e.preventDefault();
                return false;
            }

            if ($this.hasClass("wpbe-datepicker") || $this.hasClass("wpbe-timepicker") || $this.hasClass("wpbe-datetimepicker")) {
                if ($this.attr("data-val") == $this.val()) {
                    e.preventDefault();
                    return false;
                }
            }

            let postData = [];
            let postIds = [];
            let tdElement = $this.closest("td");
            if ($("#wpbe-bind-edit").prop("checked") === true) {
                postIds = wpbeGetPostsChecked();
            }
            postIds.push($this.attr("data-item-id"));
            let wpbeValue;
            switch (tdElement.attr("data-content-type")) {
                case "checkbox_dual_mode":
                case "checkbox":
                    wpbeValue = $this.prop("checked") ? "yes" : "no";
                    break;
                default:
                    wpbeValue = $this.val();
                    break;
            }

            postData.push({
                name: tdElement.attr("data-name"),
                sub_name: tdElement.attr("data-sub-name") ? tdElement.attr("data-sub-name") : "",
                type: tdElement.attr("data-update-type"),
                value: wpbeValue,
                operation: "inline_edit",
            });

            wpbePostEdit(postIds, postData);
        }, 250);
    });

    $(document).on("click", ".wpbe-inline-edit-clear-date", function () {
        let postData = [];
        let postIds = [];
        let tdElement = $(this).closest("td");

        if ($("#wpbe-bind-edit").prop("checked") === true) {
            postIds = wpbeGetPostsChecked();
        }
        postIds.push($(this).attr("data-item-id"));
        postData.push({
            name: tdElement.attr("data-name"),
            sub_name: tdElement.attr("data-sub-name") ? tdElement.attr("data-sub-name") : "",
            type: tdElement.attr("data-update-type"),
            value: "",
            operation: "inline_edit",
        });

        wpbePostEdit(postIds, postData);
    });

    $(document).on("click", ".wpbe-bulk-edit-delete-duplicate-action", function () {
        let deleteType = $(this).attr("data-delete-type");

        let alertMessage = "Are you sure you want to delete duplicates?";

        let checkedValues = $("input.wpbe-check-item:checkbox:checked")
            .map(function () {
                return $(this).val(); // Get the value of each checked checkbox
            })
            .get(); // Convert jQuery object to a JavaScript array

        swal(
            {
                title: alertMessage,
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wpbe-button wpbe-button-lg wpbe-button-white",
                confirmButtonClass: "wpbe-button wpbe-button-lg wpbe-button-red",
                confirmButtonText: "Yes, delete duplicates!",
                closeOnConfirm: true,
            },
            function (isConfirm) {
                if (isConfirm) {
                    wpbeDeletePost(checkedValues, deleteType);
                }
            }
        );
        if (productIds.length == 1) {
            swal({
                title: "Please Select More Than One Option!",
                type: "warning",
            });
        }
    });

    $(document).on("click", ".wpbe-bulk-edit-delete-action", function () {
        let deleteType = $(this).attr("data-delete-type");
        let postIds = wpbeGetPostsChecked();

        if (!postIds.length && deleteType != "all") {
            swal({
                title: "Please select one post",
                type: "warning",
            });
            return false;
        }

        let alertMessage = "Are you sure?";

        if (deleteType == "all") {
            alertMessage = $(".wpbe-reset-filter-form:visible").length ? "All of filtered posts will be delete. Are you sure?" : "All of posts will be delete. Are you sure?";
        }

        swal(
            {
                title: alertMessage,
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wpbe-button wpbe-button-lg wpbe-button-white",
                confirmButtonClass: "wpbe-button wpbe-button-lg wpbe-button-green",
                confirmButtonText: "Yes, I'm sure !",
                closeOnConfirm: true,
            },
            function (isConfirm) {
                if (isConfirm) {
                    if (postIds.length > 0 || deleteType == "all") {
                        wpbeDeletePost(postIds, deleteType);
                    } else {
                        swal({
                            title: "Please Select Post !",
                            type: "warning",
                        });
                    }
                }
            }
        );
    });

    $(document).on("click", "#wpbe-bulk-edit-duplicate-start", function () {
        let postIDs = $("input.wpbe-check-item:checkbox:checked")
            .map(function () {
                return $(this).val();
            })
            .get();
        wpbeDuplicatePost(postIDs, parseInt($("#wpbe-bulk-edit-duplicate-number").val()));
    });

    $(document).on("click", "#wpbe-bulk-new-form-do-bulk-new", function () {
        // Get The Quantity Of New Products
        let quantity = $("#wpbe-bulk-new-form-post-quantity").val();
        if (quantity < 1) {
            swal({
                title: "The 'Quantity' most be one or more!",
                type: "warning",
            });
        }
        let post_type = $("#wpbe-new-item-select-custom-post") ? $("#wpbe-new-item-select-custom-post").val() : null;
        let title = $("#wpbe-bulk-new-form-post-title").val();
        let slug = $("#wpbe-bulk-new-form-post-slug").val();
        let password = $("#wpbe-bulk-new-form-post-password").val();
        let description = $("#wpbe-bulk-new-form-post-description").val();
        let short_description = $("#wpbe-bulk-new-form-post-short-description").val();
        let menu_order = $("#wpbe-bulk-new-form-post-menu-order").val();
        let parent = $("#wpbe-bulk-new-form-post-parent").val();
        let comment_status = $("#wpbe-bulk-new-form-post-comment-status").val();
        let allow_ping_back = $("#wpbe-bulk-new-form-post-ping-status").val();
        let author = $("#wpbe-bulk-new-form-post-author").val();
        let image = $("#wpbe-bulk-edit-form-item-image").val();
        //Taxonomies
        let taxonomies = {};
        jQuery(".wpbe-form-group").each(function () {
            // Get the name of the taxonomy
            let taxonomyName = jQuery(this).data("name");
            let selectedValues = jQuery(this).find('select[data-field="value"]').val();
            // Store the selected values in the selectedData object
            if (selectedValues && selectedValues.length > 0) {
                taxonomies[taxonomyName] = selectedValues;
            }
        });
        //Data and Type
        let post_status = $("#wpbe-bulk-new-form-post-post-status").val();
        let date_published = $("#wpbe-bulk-new-form-post-date-published").val();

        let postData = {
            //action: "create_bulk_posts",
            post_type: post_type,
            title: title,
            slug: slug,
            password: password,
            description: description,
            short_description: short_description,
            menu_order: menu_order,
            parent: parent,
            comment_status: comment_status,
            allow_ping_back: allow_ping_back,
            author: author,
            image: image,
            post_status: post_status,
            date_published: date_published,
            taxonomies: taxonomies,
        };
        wpbeCreateNewPost(quantity, postData);
    });

    $(document).ready(function () {
        $("#wpbe-bulk-new-form-do-bulk-new").on("click", function () {
            setTimeout(function () {
                $(".wpbe-float-side-modal-close").click();
            }, 1000); // 1000ms (1 second) delay before clicking the close button
        });
    });

    $(document).on("click", "#wpbe-column-profiles-save-as-new-preset", function () {
        let presetKey = $("#wpbe-column-profiles-choose").val();
        let items = $(".wpbe-column-profile-fields input:checkbox:checked")
            .map(function () {
                return $(this).val();
            })
            .get();
        wpbeSaveColumnProfile(presetKey, items, "save_as_new");
    });

    $(document).on("click", "#wpbe-column-profiles-update-changes", function () {
        let presetKey = $("#wpbe-column-profiles-choose").val();
        let items = $(".wpbe-column-profile-fields input:checkbox:checked")
            .map(function () {
                return $(this).val();
            })
            .get();
        wpbeSaveColumnProfile(presetKey, items, "update_changes");
    });

    $(document).on("click", ".wpbe-bulk-edit-filter-profile-load", function () {
        wpbeLoadFilterProfile($(this).val());
        $(".wpbe-filter-profiles-items tr").removeClass("wpbe-filter-profile-loaded");
        $(this).closest("tr").addClass("wpbe-filter-profile-loaded");
        if (WPBE_DATA.wpbe_settings.close_popup_after_applying == "yes") {
            wpbeCloseFloatSideModal();
        }
    });

    $(document).on("click", ".wpbe-bulk-edit-filter-profile-delete", function () {
        let presetKey = $(this).val();
        let item = $(this).closest("tr");
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
                    wpbeDeleteFilterProfile(presetKey);
                    if (item.hasClass("wpbe-filter-profile-loaded")) {
                        $(".wpbe-filter-profiles-items tbody tr:first-child").addClass("wpbe-filter-profile-loaded");
                        $('.wpbe-filter-profile-use-always-item[value="default"]').prop("checked", true);
                        $("#wpbe-bulk-edit-reset-filter").trigger("click");
                    }
                    if (item.length > 0) {
                        item.remove();
                    }
                }
            }
        );
    });

    $(document).on("change", "input.wpbe-filter-profile-use-always-item", function () {
        wpbeFilterProfileChangeUseAlways($(this).val());
    });

    $(document).on("click", ".wpbe-filter-form-action", function (e) {
        let data = wpbeGetCurrentFilterData();
        let page;
        let action = $(this).attr("data-search-action");
        if (action === "pagination") {
            page = $(this).attr("data-index");
        }
        if (action === "quick_search" && $("#wpbe-quick-search-text").val() !== "") {
            wpbeResetFilterForm();
        }
        if (action === "pro_search") {
            wpbeResetQuickSearchForm();
        }
        wpbePostsFilter(data, action, null, page);

        if (WPBE_DATA.wpbe_settings.close_popup_after_applying == "yes") {
            wpbeCloseFloatSideModal();
        }

        wpbeCheckResetFilterButton();
    });

    $(document).on("click", "#wpbe-filter-form-reset", function () {
        wpbeResetFilters();
    });

    $(document).on("click", "#wpbe-bulk-edit-reset-filter", function () {
        wpbeResetFilters();
    });

    $(document).on("change", "#wpbe-quick-search-field", function () {
        let options = $("#wpbe-quick-search-operator option");
        switch ($(this).val()) {
            case "title":
                options.each(function () {
                    $(this).closest("select").prop("selectedIndex", 0);
                    $(this).prop("disabled", false);
                });
                break;
            case "id":
                options.each(function () {
                    $(this).closest("select").prop("selectedIndex", 1);
                    if ($(this).attr("value") === "exact") {
                        $(this).prop("disabled", false);
                    } else {
                        $(this).prop("disabled", true);
                    }
                });
                break;
        }
    });

    // Quick Per Page
    $(document).on("change", "#wpbe-quick-per-page", function () {
        wpbeChangeCountPerPage($(this).val());
    });

    $(document).on("click", ".wpbe-edit-action-with-button", function () {
        let postData = [];
        let postIds = [];

        if ($("#wpbe-bind-edit").prop("checked") === true) {
            postIds = wpbeGetPostsChecked();
        }
        postIds.push($(this).attr("data-item-id"));

        let wpbeValue;
        switch ($(this).attr("data-content-type")) {
            case "textarea":
                wpbeValue = tinymce.get("wpbe-text-editor").getContent();
                break;
            case "select_post":
                wpbeValue = $("#wpbe-select-post-value").val();
                break;
            case "select_user":
                wpbeValue = $("#wpbe-modal-select-user-input").val();
                break;
            case "image":
                wpbeValue = $(this).attr("data-image-id");
                break;
            case "custom_field_files":
                wpbeValue = [];
                if ($(".wpbe-modal-custom-field-file-item").length > 0) {
                    $(".wpbe-modal-custom-field-file-item").each(function () {
                        let name = $(this).find("input.wpbe-inline-edit-file-name").val();
                        let url = $(this).find("input.wpbe-inline-edit-file-url").val();
                        if (url != "") {
                            wpbeValue.push({
                                name: name,
                                url: url,
                            });
                        }
                    });
                }
                break;
        }

        postData.push({
            name: $(this).attr("data-name"),
            sub_name: $(this).attr("data-sub-name") ? $(this).attr("data-sub-name") : "",
            type: $(this).attr("data-update-type"),
            value: wpbeValue,
            operation: "inline_edit",
        });

        wpbePostEdit(postIds, postData);
    });

    $(document).on("click", ".wpbe-load-text-editor", function () {
        tinymce.get("wpbe-text-editor").setContent("");
        let tdElement = $(this).closest("td");
        let postId = $(this).attr("data-item-id");
        let field = $(this).attr("data-field");
        let fieldType = $(this).attr("data-field-type");
        $("#wpbe-modal-text-editor-item-title").text($(this).attr("data-item-name"));
        $("#wpbe-text-editor-apply")
            .attr("data-field", field)
            .attr("data-field-type", fieldType)
            .attr("data-item-id", postId)
            .attr("data-update-type", tdElement.attr("data-update-type"))
            .attr("data-name", tdElement.attr("data-name"));

        $.ajax({
            url: WPBE_DATA.ajax_url,
            type: "post",
            dataType: "json",
            data: {
                action: "wpbe_get_text_editor_content",
                nonce: WPBE_DATA.ajax_nonce,
                post_id: postId,
                field: field,
                field_type: fieldType,
            },
            success: function (response) {
                if (response.success) {
                    tinymce.get("wpbe-text-editor").setContent(response.content);
                    tinymce.execCommand("mceFocus", false, "wpbe-text-editor");
                }
            },
            error: function () { },
        });
    });

    $(document).on("click", ".wpbe-inline-edit-taxonomy-save", function () {
        let postData = [];
        let postIds = [];

        if ($("#wpbe-bind-edit").prop("checked") === true) {
            postIds = wpbeGetPostsChecked();
        }
        postIds.push($(this).attr("data-item-id"));

        let value = $("#wpbe-modal-post-taxonomy input:checkbox:checked")
            .map(function () {
                return $(this).val();
            })
            .get();

        postData.push({
            name: $(this).attr("data-name"),
            sub_name: $(this).attr("data-sub-name") ? $(this).attr("data-sub-name") : "",
            type: $(this).attr("data-update-type"),
            value: value,
            operation: "inline_edit",
        });

        wpbePostEdit(postIds, postData);
    });

    //Search
    $(document).on("keyup", ".wpbe-search-in-list", function () {
        let wpbeSearchValue = this.value.toLowerCase().trim();
        $($(this).attr("data-target")).filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(wpbeSearchValue) > -1);
        });
    });

    $(document).on("click", "#wpbe-create-new-post-attribute", function () {
        if ($("#wpbe-new-post-attribute-name").val() !== "") {
            let attributeInfo = {
                name: $("#wpbe-new-post-attribute-name").val(),
                slug: $("#wpbe-new-post-attribute-slug").val(),
                description: $("#wpbe-new-post-attribute-description").val(),
                post_id: $(this).attr("data-item-id"),
            };
            wpbeAddPostAttribute(attributeInfo, $(this).attr("data-field"));
        } else {
            swal({
                title: "Attribute Name is required !",
                type: "warning",
            });
        }
    });

    $(document).on("click", 'button[data-target="#wpbe-modal-select-post"]', function () {
        $("#wpbe-modal-select-post-item-title").text($(this).attr("data-item-name"));
        $("#wpbe-modal-select-post .wpbe-edit-action-with-button")
            .attr("data-item-id", $(this).attr("data-item-id"))
            .attr("data-field", $(this).attr("data-field"))
            .attr("data-field-type", $(this).attr("data-field-type"));
        $("#wpbe-select-post-value").val("").change();
        wpbeSetSelectedParent(parseInt($(this).attr("data-parent-id")));
    });

    let select2Query;
    $(".wpbe-get-posts-ajax").select2({
        ajax: {
            type: "post",
            delay: 800,
            url: WPBE_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                select2Query = {
                    action: "wpbe_get_posts_by_name",
                    nonce: WPBE_DATA.ajax_nonce,
                    post_title: params.term,
                };
                return select2Query;
            },
        },
        placeholder: "Post name ...",
        minimumInputLength: 3,
    });

    $(document).on("click", "#wpbe-modal-select-files-add-file-item", function () {
        wpbeAddNewFileItem();
    });

    $(document).on("click", "#wpbe-modal-custom-field-files-add-file-item", function () {
        wpbeAddCustomFieldFileItem();
    });

    $(document).on("click", "#wpbe-bulk-edit-custom-field-files-add-file-item", function () {
        wpbeBulkEditAddCustomFieldFileItem();
    });

    $(document).on("click", 'button[data-toggle=modal][data-target="#wpbe-modal-select-files"]', function () {
        $(".wpbe-inline-select-files").html("");
        let tdElement = $(this).closest("td");
        $("#wpbe-modal-select-files-apply")
            .attr("data-item-id", $(this).attr("data-item-id"))
            .attr("data-field", $(this).attr("data-field"))
            .attr("data-name", tdElement.attr("data-name"))
            .attr("data-update-type", tdElement.attr("data-update-type"));
        $("#wpbe-modal-select-files-item-title").text($(this).closest("td").attr("data-col-title"));
        wpbeGetPostFiles($(this).attr("data-item-id"));
    });

    $(document).on("click", 'button[data-toggle="modal"][data-target="#wpbe-modal-custom-field-files"]', function () {
        $(".wpbe-inline-custom-field-files").html("");
        let tdElement = $(this).closest("td");
        $("#wpbe-modal-custom-field-files-apply")
            .attr("data-item-id", $(this).attr("data-item-id"))
            .attr("data-field", $(this).attr("data-field"))
            .attr("data-name", tdElement.attr("data-name"))
            .attr("data-update-type", tdElement.attr("data-update-type"));
        $("#wpbe-modal-custom-field-files-item-title").text($(this).closest("td").attr("data-col-title"));
        wpbeGetPostCustomFieldFiles($(this).attr("data-item-id"), tdElement.attr("data-name"));
    });

    $(document).on("click", ".wpbe-inline-edit-file-remove-item", function () {
        $(this).closest(".wpbe-modal-select-files-file-item").remove();
    });

    $(document).on("click", ".wpbe-custom-field-file-remove-item", function () {
        $(this).closest(".wpbe-modal-custom-field-file-item").remove();
    });

    $(document).on("click", ".wpbe-bulk-edit-custom-field-file-remove-item", function () {
        $(this).closest(".wpbe-bulk-edit-custom-field-file-item").remove();
    });

    if ($.fn.sortable) {
        let wpbeSelectFiles = $(".wpbe-inline-select-files");
        wpbeSelectFiles.sortable({
            handle: ".wpbe-select-files-sortable-btn",
            cancel: "",
        });
        wpbeSelectFiles.disableSelection();

        let wpbeCustomFieldFiles = $(".wpbe-inline-custom-field-files");
        wpbeCustomFieldFiles.sortable({
            handle: ".wpbe-custom-field-files-sortable-btn",
            cancel: "",
        });
        wpbeCustomFieldFiles.disableSelection();

        let wpbeBulkEditCustomFieldFiles = $(".wpbe-bulk-edit-custom-field-files");
        wpbeBulkEditCustomFieldFiles.sortable({
            handle: ".wpbe-bulk-edit-custom-field-files-sortable-btn",
            cancel: "",
        });
        wpbeBulkEditCustomFieldFiles.disableSelection();
    }

    $(document).on("change", ".wpbe-bulk-edit-form-variable", function () {
        let newVal = $(this).val() ? $(this).closest("div").find("input[type=text]").val() + "{" + $(this).val() + "}" : "";
        $(this).closest("div").find("input[type=text]").val(newVal).change();
    });

    $(document).on("change", 'select[data-field="operator"]', function () {
        let id = $(this).closest(".wpbe-form-group").find("label").attr("for");
        if ($(this).val() === "text_replace") {
            $(this)
                .closest(".wpbe-form-group")
                .append(
                    '<div class="wpbe-bulk-edit-form-extra-field"><select id="' +
                    id +
                    '-sensitive"><option value="yes">Same Case</option><option value="no">Ignore Case</option></select><input type="text" id="' +
                    id +
                    '-replace" placeholder="Text ..."><select class="wpbe-bulk-edit-form-variable" title="Select Variable" data-field="variable"><option value="">Variable</option><option value="title">Title</option><option value="id">ID</option><option value="sku">SKU</option><option value="menu_order">Menu Order</option><option value="parent_id">Parent ID</option><option value="parent_title">Parent Title</option><option value="parent_sku">Parent SKU</option><option value="regular_price">Regular Price</option><option value="sale_price">Sale Price</option></select></div>'
                );
        } else if ($(this).val() === "number_round") {
            $(this)
                .closest(".wpbe-form-group")
                .append(
                    '<div class="wpbe-bulk-edit-form-extra-field"><select id="' +
                    id +
                    '-round-item"><option value="5">5</option><option value="10">10</option><option value="19">19</option><option value="29">29</option><option value="39">39</option><option value="49">49</option><option value="59">59</option><option value="69">69</option><option value="79">79</option><option value="89">89</option><option value="99">99</option></select></div>'
                );
        } else {
            $(this).closest(".wpbe-form-group").find(".wpbe-bulk-edit-form-extra-field").remove();
        }
        if ($.inArray($(this).val(), ["number_clear", "text_remove_duplicate", "text_clear"]) !== -1) {
            $(this).closest(".wpbe-form-group").find('[data-field="value"]').prop("disabled", true);
            $(this).closest(".wpbe-form-group").find('[data-field="variable"]').prop("disabled", true);
        } else {
            $(this).closest(".wpbe-form-group").find('[data-field="value"]').prop("disabled", false);
            $(this).closest(".wpbe-form-group").find('[data-field="variable"]').prop("disabled", false);
        }
        changedTabs($(this));
    });

    $("#wpbe-float-side-modal-bulk-edit, #wpbe-float-side-modal-filter, #wpbe-float-side-modal-bulk-new-posts").on("change", '[data-field="value"], [data-field="from"], [data-field="to"]', function () {
        changedTabs($(this));
    });

    $(document).on("change", ".wpbe-date-from", function () {
        let field_to = $("#" + $(this).attr("data-to-id"));
        field_to.val("");
        field_to.datepicker("destroy");
        field_to.datepicker({
            dateFormat: "yy/mm/dd",
            minDate: $(this).val(),
        });
    });

    $(document).on("click", ".wpbe-bulk-edit-form-remove-image", function () {
        $(this).closest("div").remove();
        $(".wpbe-bulk-edit-form-item-image").val("");
    });

    $(document).on("click", ".wpbe-bulk-edit-form-remove-gallery-item", function () {
        $(this).closest("div").remove();
        $("#wpbe-bulk-edit-form-post-gallery input[value=" + $(this).attr("data-id") + "]").remove();
    });

    var sortType = "DESC";
    $(document).on("click", ".wpbe-sortable-column", function () {
        if (sortType === "DESC") {
            sortType = "ASC";
            $(this).find("i.wpbe-sortable-column-icon").text("d");
        } else {
            sortType = "DESC";
            $(this).find("i.wpbe-sortable-column-icon").text("u");
        }
        wpbeSortByColumn($(this).attr("data-column-name"), sortType);
    });

    $(document).on("click", ".wpbe-column-manager-edit-field-btn", function () {
        $("#wpbe-modal-column-manager-edit-preset .wpbe-box-loading").show();
        let presetKey = $(this).val();
        $("#wpbe-modal-column-manager-edit-preset .items").html("");
        $("#wpbe-column-manager-edit-preset-key").val(presetKey);
        $("#wpbe-column-manager-edit-preset-name").val($(this).attr("data-preset-name"));
        wpbeColumnManagerFieldsGetForEdit(presetKey);
    });

    $(document).on("click", "#wpbe-get-meta-fields-by-post-id", function () {
        $(".wpbe-meta-fields-empty-text").hide();
        let input = $("#wpbe-add-meta-fields-post-id");
        wpbeAddMetaKeysByPostID(input.val());
        input.val("");
    });

    $(document).on("click", "#wpbe-bulk-edit-undo", function () {
        $(this).prop("disabled", true);
        wpbeHistoryUndo();
    });

    $(document).on("click", "#wpbe-bulk-edit-redo", function () {
        $(this).prop("disabled", true);
        wpbeHistoryRedo();
    });

    $(document).on("click", '[data-target="#wpbe-float-side-modal-history"]', function () {
        if ($(this).attr("data-loaded") == "true") {
            return;
        } else {
            $(this).attr("data-loaded", "true");
            $(".wpbe-history-filter-fields input").val("");
            $(".wpbe-history-filter-fields select").val("").change();
            wpbeHistoryFilter(null, false);
        }
    });

    $(document).on("click", "#wpbe-history-filter-apply", function () {
        let filters = {
            operation: $("#wpbe-history-filter-operation").val(),
            author: $("#wpbe-history-filter-author").val(),
            fields: $("#wpbe-history-filter-fields").val(),
            date: {
                from: $("#wpbe-history-filter-date-from").val(),
                to: $("#wpbe-history-filter-date-to").val(),
            },
        };
        wpbeHistoryFilter(filters);
    });

    $(document).on("click", "#wpbe-history-filter-reset", function () {
        $(".wpbe-history-filter-fields input").val("");
        $(".wpbe-history-filter-fields select").val("").change();
        wpbeHistoryFilter();
    });

    $(document).on("change", ".wpbe-meta-fields-main-type", function () {
        let item = $(this).closest(".wpbe-meta-fields-right-item");
        if ($(this).val() === "textinput") {
            item.find(".wpbe-meta-fields-sub-type").show();
        } else {
            item.find(".wpbe-meta-fields-sub-type").hide();
        }

        if ($.inArray($(this).val(), ["select", "array", "radio"]) !== -1) {
            item.find(".wpbe-meta-fields-key-value").show();
        } else {
            item.find(".wpbe-meta-fields-key-value").hide();
        }
    });

    $(document).on("submit", "#wpbe-column-manager-add-new-preset", function (e) {
        if ($(this).find(".wpbe-column-manager-added-fields .items .wpbe-column-manager-right-item").length < 1) {
            e.preventDefault();
            swal({
                title: "Please Add Columns !",
                type: "warning",
            });
        }
    });

    $(document).on("click", "#wpbe-bulk-edit-form-reset", function () {
        wpbeResetBulkEditForm();
        $("nav.wpbe-tabs-navbar li a").removeClass("wpbe-tab-changed");
    });

    $(document).on("click", "#wpbe-filter-form-save-preset", function () {
        let presetName = $("#wpbe-filter-form-save-preset-name").val();
        if (presetName !== "") {
            let data = wpbeGetProSearchData();
            wpbeSaveFilterPreset(data, presetName);
        } else {
            swal({
                title: "Preset name is required !",
                type: "warning",
            });
        }
    });

    $(document).on("click", ".wpbe-bulk-edit-form-do-bulk-edit", function (e) {
        let postIds = wpbeGetPostsChecked();
        let postData = wpbeGetBulkEditData();

        if (postIds.length > 0) {
            if (WPBE_DATA.wpbe_settings.close_popup_after_applying == "yes") {
                wpbeCloseFloatSideModal();
            }
            wpbePostEdit(postIds, postData);
            if (WPBE_DATA.wpbe_settings.keep_filled_data_in_bulk_edit_form == "no") {
                wpbeResetBulkEditForm();
            }
        } else {
            swal(
                {
                    title: "Your changes will be applied to all of filtered posts. Are you sure?",
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonClass: "wpbe-button wpbe-button-lg wpbe-button-white",
                    confirmButtonClass: "wpbe-button wpbe-button-lg wpbe-button-green",
                    confirmButtonText: "Yes, I'm sure",
                    closeOnConfirm: true,
                },
                function (isConfirm) {
                    if (isConfirm) {
                        if (WPBE_DATA.wpbe_settings.close_popup_after_applying == "yes") {
                            wpbeCloseFloatSideModal();
                        }
                        wpbePostEdit("all_filtered", postData);
                        if (WPBE_DATA.wpbe_settings.keep_filled_data_in_bulk_edit_form == "yes") {
                            wpbeResetBulkEditForm();
                        }
                    }
                }
            );
        }
    });

    $(document).on("click", ".wpbe-bulk-edit-form-schedule-bulk-edit", function () {
        let container = $('#wpbe-float-side-modal-bulk-edit .wpbe-tab-content-item[data-content="set_schedule"]');

        container
            .find(".wpbe-set-schedule-form .required:visible")
            .each(function () {
                if ($(this).val() != "") {
                    $(this).removeClass("error");
                } else {
                    $(this).addClass("error");
                }
            })
            .promise()
            .done(function () {
                if (container.find(".wpbe-set-schedule-form").find(".error").length) {
                    container.find('.wpbe-tab-item[data-content="set_schedule"]').trigger("click");
                    wpbeLoadingError("Please fill the required fields");
                    return;
                }

                wpbeLoadingStart();

                let editItems = wpbeGetBulkEditData();
                let postIds = wpbeGetPostsChecked();
                let filterItems = $.isArray(postIds) && postIds.length ? { post_ids: postIds } : wpbeGetCurrentFilterData();

                if (!editItems.length) {
                    wpbeLoadingError("Bulk edit form is empty !");
                    return;
                }

                let dates = wpbeScheduleGetDatesFromJobForm(container);

                setTimeout(function () {
                    $.ajax({
                        url: WPBE_DATA.ajax_url,
                        type: "post",
                        dataType: "json",
                        data: {
                            action: "wpbe_add_schedule_job",
                            nonce: WPBE_DATA.ajax_nonce,
                            label: container.find(".wpbe-set-schedule-name").val(),
                            description: container.find(".wpbe-set-schedule-description").val(),
                            run_at: container.find(".wpbe-set-schedule-run-at").val(),
                            run_for: container.find(".wpbe-set-schedule-run-for:visible").length ? container.find(".wpbe-set-schedule-run-for").val() : null,
                            dates: dates,
                            filter_items: filterItems,
                            edit_items: editItems,
                            stop_date: container.find(".wpbe-set-schedule-stop-date-time:visible").length ? container.find(".wpbe-set-schedule-stop-date-time").val() : null,
                            revert_date: container.find(".wpbe-set-schedule-revert-date-time:visible").length ? container.find(".wpbe-set-schedule-revert-date-time").val() : null,
                        },
                        success: function (response) {
                            if (response.success) {
                                if (container.find(".wpbe-set-schedule-run-at").val() === "now") {
                                    if (response.is_processing) {
                                        wpbeLoadingProcessingStart(WPBE_DATA.background_process.loading_messages.processing, true, { total: 0, completed: 0 });
                                        wpbeIsProcessing();
                                    } else {
                                        $(".wpbe-reload-table").trigger("click");
                                    }
                                } else {
                                    wpbeLoadingSuccess();
                                }

                                wpbeScheduleAwaitingCountUpdate(response.awaiting_count);
                            } else {
                                wpbeLoadingError(response.message && response.message != "" ? response.message : "Error !");
                            }
                        },
                        error: function () {
                            wpbeLoadingError();
                        },
                    });
                }, 250);
            });
    });

    $(document).on("click", "#wpbe-quick-search-button", function () {
        if ($("#wpbe-quick-search-text").val() !== "") {
            $("#wpbe-quick-search-reset").show();
            $(".wpbe-quick-filter a").addClass("active");
        }
    });

    // keypress: Enter
    $(document).on("keypress", function (e) {
        if (e.keyCode === 13) {
            if ($("#wpbe-float-side-modal-filter").attr("data-visibility") === "visible") {
                wpbeReloadPosts();
                $("#wpbe-bulk-edit-reset-filter").show();
                wpbeFilterFormClose();
            }
            if ($("#wpbe-quick-search-text").val() !== "" && $($("#wpbe-last-modal-opened").val()).css("display") !== "block" && $(".wpbe-tabs-list a[data-content=bulk-edit]").hasClass("selected")) {
                wpbeReloadPosts();
                $("#wpbe-quick-search-reset").show();
                $(".wpbe-quick-filter a").addClass("active");
            }
            if ($("#wpbe-modal-new-post-taxonomy").css("display") === "block") {
                $("#wpbe-create-new-post-taxonomy").trigger("click");
            }
            if ($("#wpbe-modal-new-item").css("display") === "block") {
                $("#wpbe-create-new-item").trigger("click");
            }
            if ($("#wpbe-modal-post-duplicate").css("display") === "block") {
                $("#wpbe-bulk-edit-duplicate-start").trigger("click");
            }

            let metaFieldManualInput = $("#wpbe-meta-fields-manual_key_name");
            let metaFieldPostId = $("#wpbe-add-meta-fields-post-id");
            if (metaFieldManualInput.val() !== "" && $("#wpbe-add-meta-field-manual").prop("disabled") === false) {
                $(".wpbe-meta-fields-empty-text").hide();
                wpbeAddMetaKeysManual(metaFieldManualInput.val());
                metaFieldManualInput.val("");
            }
            if (metaFieldPostId.val() !== "") {
                $(".wpbe-meta-fields-empty-text").hide();
                wpbeAddMetaKeysByPostID(metaFieldPostId.val());
                metaFieldPostId.val("");
            }

            // filter form
            if ($("#wpbe-float-side-modal-filter:visible").length) {
                $("#wpbe-float-side-modal-filter:visible").find(".wpbe-filter-form-action").trigger("click");
            }
        }
    });

    $(document).on("click", ".wpbe-inline-edit-attribute-save", function () {
        let reload = true;
        let PostIds;
        let postsChecked = $("input.wpbe-item-id:checkbox:checked");
        let bindEdit = $("#wpbe-bind-edit");
        if (bindEdit.prop("checked") === true && postsChecked.length > 0) {
            PostIds = postsChecked
                .map(function (i) {
                    return $(this).val();
                })
                .get();
            PostIds[postsChecked.length] = $(this).attr("data-item-id");
        } else {
            PostIds = [];
            PostIds[0] = $(this).attr("data-item-id");
        }
        let field = $(this).attr("data-field");
        let data = $("#wpbe-modal-attribute-" + field + "-" + $(this).attr("data-item-id") + " input:checkbox:checked")
            .map(function () {
                return $(this).val();
            })
            .get();
        wpbeUpdatePostAttribute(PostIds, field, data, reload);
    });

    $(document).on("click", ".wpbe-reset-filter-form", function () {
        wpbeResetFilters();
    });

    $(document).on("click", ".wpbe-inline-edit-add-new-attribute", function () {
        $("#wpbe-create-new-post-attribute").attr("data-field", $(this).attr("data-field")).attr("data-item-id", $(this).attr("data-item-id"));
        $("#wpbe-modal-new-post-attribute-item-title").text($(this).attr("data-item-name"));
    });

    $(document).on("click", 'button.wpbe-calculator[data-target="#wpbe-modal-numeric-calculator"]', function () {
        let btn = $("#wpbe-modal-numeric-calculator .wpbe-edit-action-numeric-calculator");
        let tdElement = $(this).closest("td");
        btn.attr("data-item-id", $(this).attr("data-item-id"));
        btn.attr("data-field", $(this).attr("data-field"));
        btn.attr("data-name", tdElement.attr("data-name"));
        btn.attr("data-update-type", tdElement.attr("data-update-type"));
        btn.attr("data-field-type", $(this).attr("data-field-type"));

        $("#wpbe-modal-numeric-calculator #wpbe-numeric-calculator-type").show();
        $("#wpbe-modal-numeric-calculator #wpbe-numeric-calculator-round").show();

        $("#wpbe-modal-numeric-calculator-item-title").text($(this).attr("data-item-name"));
    });

    $(document).on("click", ".wpbe-edit-action-numeric-calculator", function () {
        let postIds = [];
        let postData = [];

        if ($("#wpbe-bind-edit").prop("checked") === true) {
            postIds = wpbeGetPostsChecked();
        }
        postIds.push($(this).attr("data-item-id"));

        postData.push({
            name: $(this).attr("data-name"),
            sub_name: $(this).attr("data-name") ? $(this).attr("data-name") : "",
            type: $(this).attr("data-update-type"),
            operator: $("#wpbe-numeric-calculator-operator").val(),
            value: $("#wpbe-numeric-calculator-value").val(),
            operator_type: $("#wpbe-numeric-calculator-type").val() ? $("#wpbe-numeric-calculator-type").val() : "n",
            round: $("#wpbe-numeric-calculator-round").val(),
        });

        wpbePostEdit(postIds, postData);
    });

    $(document).on("keyup", "input[type=number][data-field=download_limit], input[type=number][data-field=download_expiry]", function () {
        if ($(this).val() < -1) {
            $(this).val(-1);
        }
    });

    $(document).on("click", "#wpbe-quick-search-reset", function () {
        wpbeResetFilters();
    });

    $(document).on("click", ".wpbe-bulk-edit-status-filter-item", function () {
        $(".wpbe-top-nav-status-filter").hide();
        $(".wpbe-status-filter-selected-name").text(" - " + $(this).text());
        if ($(this).attr("data-status") === "all") {
            $("#wpbe-filter-form-reset").trigger("click");
        } else {
            $("#wpbe-filter-form-post_status").val($(this).attr("data-status")).change();
            setTimeout(function () {
                $("#wpbe-filter-form-get-posts").trigger("click");
            }, 250);
        }
    });

    $(document).on("click", '[data-target="#wpbe-modal-new-item"]', function () {
        let title;
        let description;
        switch ($(this).attr("data-post-type")) {
            case "post":
                title = "New Post";
                description = "Enter how many new post(s) to create!";
                break;
            case "page":
                title = "New Page";
                description = "Enter how many new page(s) to create!";
                break;
            case "custom_post":
                title = "New Custom Post Item";
                description = "Enter how many new custom post(s) to create!";
                break;
        }

        $("#wpbe-new-item-title").html(title);
        $("#wpbe-new-item-description").html(description);
    });

    if (itemTypeInUrl && itemTypeInUrl !== "" && itemTypeInUrl !== $("#wpbe-switcher").val()) {
        $("#wpbe-switcher").val(itemTypeInUrl).trigger("change");
    }

    if (itemIdInUrl && itemIdInUrl > 0) {
        wpbeResetFilterForm();
        setTimeout(function () {
            $("#wpbe-filter-form-post-ids").val(itemIdInUrl);
            $("#wpbe-filter-form-get-posts").trigger("click");
        }, 500);
    }

    $(document).on("click", ".wpbe-delete-item-btn", function () {
        let postIds = [];
        postIds.push($(this).attr("data-item-id"));
        let deleteType = $(this).attr("data-delete-type");
        swal(
            {
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wpbe-button wpbe-button-lg wpbe-button-white",
                confirmButtonClass: "wpbe-button wpbe-button-lg wpbe-button-green",
                confirmButtonText: "Yes, i'm sure",
                closeOnConfirm: true,
            },
            function (isConfirm) {
                if (isConfirm) {
                    wpbeDeletePost(postIds, deleteType);
                }
            }
        );
    });

    $(document).on("click", ".wpbe-restore-item-btn", function () {
        let postIds = [];
        postIds.push($(this).attr("data-item-id"));
        swal(
            {
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wpbe-button wpbe-button-lg wpbe-button-white",
                confirmButtonClass: "wpbe-button wpbe-button-lg wpbe-button-green",
                confirmButtonText: "Yes, i'm sure",
                closeOnConfirm: true,
            },
            function (isConfirm) {
                if (isConfirm) {
                    wpbeRestorePost(postIds);
                }
            }
        );
    });

    $(document).on("change", "#wpbe-filter-form-post_status", function () {
        if ($(this).val() === "trash") {
            $(".wpbe-nav-trash-button").find('div[data-page="general"]').hide();
            $(".wpbe-nav-trash-button").find('div[data-page="trash"]').show();
        } else {
            $(".wpbe-nav-trash-button").find('div[data-page="general"]').show();
            $(".wpbe-nav-trash-button").find('div[data-page="trash"]').hide();
        }
    });

    $(document).on("click", "#wpbe-bulk-edit-trash-empty", function () {
        swal(
            {
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wpbe-button wpbe-button-lg wpbe-button-white",
                confirmButtonClass: "wpbe-button wpbe-button-lg wpbe-button-green",
                confirmButtonText: "Yes, i'm sure",
                closeOnConfirm: true,
            },
            function (isConfirm) {
                if (isConfirm) {
                    wpbeEmptyTrash();
                }
            }
        );
    });

    $(document).on("click", "#wpbe-bulk-edit-trash-restore", function () {
        let postIds = wpbeGetPostsChecked();
        wpbeRestorePost(postIds);
    });

    $(document).on("click", ".wpbe-history-pagination-item", function () {
        $(".wpbe-history-pagination-loading").show();

        let filters = {
            operation: $("#wpbe-history-filter-operation").val(),
            author: $("#wpbe-history-filter-author").val(),
            fields: $("#wpbe-history-filter-fields").val(),
            date: {
                from: $("#wpbe-history-filter-date-from").val(),
                to: $("#wpbe-history-filter-date-to").val(),
            },
        };

        wpbeHistoryChangePage($(this).attr("data-index"), filters);
    });

    $(document).on("click", ".wpbe-reload-table", function () {
        wpbeReloadPosts();
    });

    $(document).on("change", "#wpbe-export-type", function () {
        if ($(this).val() == "xml") {
            $('.wpbe-export-radio input[name="fields"]').prop("checked", false).change();
            $('.wpbe-export-radio input[name="fields"][value="all"]').prop("checked", true).change();
            $('.wpbe-export-radio input[name="fields"]').prop("disabled", true);
        } else {
            $(".wpbe-export-radio input").prop("disabled", false);
        }
    });

    $(document).on("click", ".wpbe-trash-option-restore-selected-items", function () {
        let postIds = wpbeGetPostsChecked();
        if (!postIds.length) {
            swal({
                title: "Please select one post",
                type: "warning",
            });
            return false;
        } else {
            swal(
                {
                    title: "Are you sure?",
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonClass: "wpbe-button wpbe-button-lg wpbe-button-white",
                    confirmButtonClass: "wpbe-button wpbe-button-lg wpbe-button-green",
                    confirmButtonText: "Yes, i'm sure",
                    closeOnConfirm: true,
                },
                function (isConfirm) {
                    if (isConfirm) {
                        if (wpbeSelectAllChecked()) {
                            postIds = "all_filtered";
                        }
                        wpbeLoadingStart();
                        wpbeRestorePost(postIds);
                    }
                }
            );
        }
    });

    $(document).on("click", ".wpbe-trash-option-restore-all", function () {
        swal(
            {
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wpbe-button wpbe-button-lg wpbe-button-white",
                confirmButtonClass: "wpbe-button wpbe-button-lg wpbe-button-green",
                confirmButtonText: "Yes, i'm sure",
                closeOnConfirm: true,
            },
            function (isConfirm) {
                if (isConfirm) {
                    wpbeRestorePost([]);
                }
            }
        );
    });

    $(document).on("click", ".wpbe-trash-option-delete-selected-items", function () {
        let postIds = wpbeGetPostsChecked();
        if (!postIds.length) {
            swal({
                title: "Please select one post",
                type: "warning",
            });
            return false;
        } else {
            swal(
                {
                    title: "Are you sure?",
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonClass: "wpbe-button wpbe-button-lg wpbe-button-white",
                    confirmButtonClass: "wpbe-button wpbe-button-lg wpbe-button-green",
                    confirmButtonText: "Yes, i'm sure",
                    closeOnConfirm: true,
                },
                function (isConfirm) {
                    if (isConfirm) {
                        if (wpbeSelectAllChecked()) {
                            postIds = "all_filtered";
                        }
                        wpbeDeletePost(postIds, "permanently");
                    }
                }
            );
        }
    });

    $(document).on("click", ".wpbe-trash-option-delete-all", function () {
        swal(
            {
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wpbe-button wpbe-button-lg wpbe-button-white",
                confirmButtonClass: "wpbe-button wpbe-button-lg wpbe-button-green",
                confirmButtonText: "Yes, i'm sure",
                closeOnConfirm: true,
            },
            function (isConfirm) {
                if (isConfirm) {
                    wpbeEmptyTrash();
                }
            }
        );
    });

    // jQuery(document).ready(function ($) {
    //   $("#save-meta-fields-button").on("click", function (e) {
    //     e.preventDefault(); // Prevent the default form submission
    //     wpbeGetMetaFieldsItem(); // Call your AJAX function
    //   });
    // });

    $(document).on("click", 'button[data-target="#wpbe-modal-select-user"]', function () {
        $("#wpbe-modal-select-user-input").val("").change();
        $("#wpbe-modal-select-user .wpbe-edit-action-with-button").attr("data-item-id", $(this).attr("data-item-id"));
        $("#wpbe-modal-select-user-current").text($(this).attr("data-current-user"));
    });

    $(document).on("click", 'span[data-target="#wpbe-modal-post-taxonomy"]', function () {
        let tdElement = $(this).closest("td");
        $(".wpbe-inline-edit-taxonomy-save").attr("data-item-id", tdElement.attr("data-item-id")).attr("data-name", tdElement.attr("data-name"));
        $(".wpbe-inline-edit-add-new-taxonomy").attr("data-item-id", tdElement.attr("data-item-id")).attr("data-field", tdElement.attr("data-name"));
        wpbeGetPostTaxonomyTerms(tdElement.attr("data-item-id"), tdElement.attr("data-name"));
    });

    $(document).on("click", ".wpbe-inline-edit-add-new-taxonomy", function () {
        $("#wpbe-create-new-post-taxonomy").attr("data-field", $(this).attr("data-field")).attr("data-item-id", $(this).attr("data-item-id"));
        wpbeGetTaxonomyParentSelectBox($(this).attr("data-field"));
        $("#wpbe-modal-new-post-taxonomy input").val("");
        $("#wpbe-modal-new-post-taxonomy select").val("").change();
        $("#wpbe-modal-new-post-taxonomy textarea").val("");
    });

    $(document).on("click", "#wpbe-create-new-post-taxonomy", function () {
        if ($("#wpbe-new-post-category-name").val() !== "") {
            let taxonomyInfo = {
                name: $("#wpbe-new-post-taxonomy-name").val(),
                slug: $("#wpbe-new-post-taxonomy-slug").val(),
                parent: $("#wpbe-new-post-taxonomy-parent").val(),
                description: $("#wpbe-new-post-taxonomy-description").val(),
                post_id: $(this).attr("data-item-id"),
            };
            wpbeAddPostTaxonomy(taxonomyInfo, $(this).attr("data-field"), $(this).attr("data-item-id"));
        } else {
            swal({
                title: "Taxonomy Name is required !",
                type: "warning",
            });
        }
    });

    $(document).on("click", ".wpbe-processing-loading-stop-button", function () {
        let $this = $(this);
        swal(
            {
                title: "Your changes have been applied to a number of rows. Do you want to stop the operation?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wpbe-button wpbe-button-lg wpbe-button-white",
                confirmButtonClass: "wpbe-button wpbe-button-lg wpbe-button-green",
                confirmButtonText: "Yes",
                closeOnConfirm: true,
            },
            function (isConfirm) {
                if (isConfirm) {
                    $this.hide();
                    jQuery('.wpbe-processing-loading span[data-type="message"]').text("Stopping ...");
                    wpbeBackgroundProcessForceStop();
                }
            }
        );
    });

    $(document).on("change", "#wpbe-top-nav-filters-go-to-page", function () {
        if (goToPageProcessing === false && $(this).val() != "") {
            goToPageProcessing = true;
            wpbePostsFilter(wpbeGetCurrentFilterData(), "pagination", [], $(this).val());
        }
    });

    wpbeGetDefaultFilterProfilePosts();
    wpbeBackgroundProcessingCheck();
});
