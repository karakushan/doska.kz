"use strict";

function wpbeGetPostTags() {
    let query;
    jQuery(".wpbe-select2-post-tags").select2({
        ajax: {
            type: "post",
            delay: 200,
            url: WPBE_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wpbe_get_post_tags",
                    nonce: WPBE_DATA.ajax_nonce,
                    search: params.term,
                };
                return query;
            },
        },
        placeholder: "Tag Name ...",
        minimumInputLength: 2,
        dropdownAutoWidth: true,
        width: "100%",
    });
}

function wpbeReloadPosts(edited_ids = [], current_page = wpbeGetCurrentPage()) {
    let data = wpbeGetCurrentFilterData();
    wpbePostsFilter(data, data.search_type, edited_ids, current_page);
}

function wpbePostsFilter(data, action, edited_ids = null, page = wpbeGetCurrentPage()) {
    if (action === "pagination") {
        wpbePaginationLoadingStart();
    } else {
        wpbeLoadingStart();
    }

    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_posts_filter",
            nonce: WPBE_DATA.ajax_nonce,
            filter_data: data,
            current_page: page,
            search_action: action,
            option_values: wpbeGetFilterFormOptionValues(),
        },
        success: function (response) {
            goToPageProcessing = false;
            if (response.success) {
                wpbeLoadingSuccess();
                wpbeSetPostsList(response, edited_ids);
                wpbeHistoryFilter(null, false);
            } else {
                wpbeLoadingError();
            }
        },
        error: function () {
            goToPageProcessing = false;
            wpbeLoadingError();
        },
    });
}

function wpbeSetPostsList(response, edited_ids = null) {
    let selectAll = wpbeSelectAllChecked();

    setTimeout(function () {
        jQuery("#wpbe-items-table").html(response.posts_list).ready(function () {
            if (selectAll === true) {
                jQuery('.wpbe-check-item-main[value="all"]').prop("checked", true).change();
            } else {
                if (edited_ids != null && !edited_ids.length) {
                    jQuery('.wpbe-check-item-main[value="visible"]').prop("checked", false).change();
                }
            }

            wpbeReInitDatePicker();
        });
    }, 100);

    jQuery(".wpbe-items-pagination").html(response.pagination).ready(function () {
        jQuery("#wpbe-top-nav-filters-go-to-page").attr("max", jQuery(".wpbe-top-nav-filters-paginate").attr("data-last-page"));
        if (parseInt(jQuery(".wpbe-top-nav-filters-paginate").attr("data-last-page")) < 2) {
            jQuery(".wpbe-table-item-selector-checkbox").addClass("wpbe-check-item-main");
            jQuery(".wpbe-table-item-selector").hide();
        } else {
            jQuery(".wpbe-table-item-selector-checkbox").removeClass("wpbe-check-item-main");
            jQuery(".wpbe-table-item-selector").show();
        }
    });

    wpbeSetStatusFilter(response.status_filters);

    jQuery(".wpbe-items-count").html(wpbeGetTableCount(jQuery("#wpbe-quick-per-page").val(), wpbeGetCurrentPage(), response.posts_count));

    if (edited_ids && edited_ids.length > 0) {
        jQuery("tr").removeClass("wpbe-item-edited");
        edited_ids.forEach(function (postID) {
            jQuery("tr[data-item-id=" + postID + "]").addClass("wpbe-item-edited");
            jQuery("input.wpbe-check-item[value=" + postID + "]").prop("checked", true);
        });
        wpbeShowSelectionTools();
    } else {
        wpbeHideSelectionTools();
    }

    wpbeSetTipsyTooltip();
    setTimeout(function () {
        let maxHeightScrollWrapper = jQuery(".scroll-wrapper > .scroll-content").css("max-height");
        jQuery(".scroll-wrapper > .scroll-content").css({
            "max-height": parseInt(maxHeightScrollWrapper) + 5,
        });
    }, 500);
}

function wpbeGetPostData(postID) {
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_get_post_data",
            nonce: WPBE_DATA.ajax_nonce,
            post_id: postID,
        },
        success: function (response) {
            if (response.success) {
                wpbeSetPostDataBulkEditForm(response.post_data);
            } else {
            }
        },
        error: function () { },
    });
}

function wpbeSetSelectedParent(postId) {
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_get_post_by_id",
            nonce: WPBE_DATA.ajax_nonce,
            post_id: postId,
        },
        success: function (response) {
            if (response.post_title) {
                let parentField = jQuery("#wpbe-select-post-value");
                if (parentField.length > 0) {
                    parentField.append("<option value='" + postId + "' selected>" + response.post_title + "</option>").prop("selected", true);
                }
            }
        },
        error: function () { },
    });
}

function wpbeSetPostDataBulkEditForm(postData) {
    let reviews_allowed = postData.reviews_allowed ? "yes" : "no";
    let sold_individually = postData.sold_individually ? "yes" : "no";
    let manage_stock = postData.manage_stock ? "yes" : "no";
    let featured = postData.featured ? "yes" : "no";
    let virtual = postData.virtual ? "yes" : "no";
    let downloadable = postData.downloadable ? "yes" : "no";

    let attributes = jQuery("#wpbe-float-side-modal-bulk-edit .wpbe-bulk-edit-form-group[data-type=attribute]");
    if (attributes.length > 0) {
        let attribute_name = "";
        attributes.each(function () {
            attribute_name = jQuery(this).attr("data-taxonomy");
            if (postData.attribute[attribute_name]) {
                jQuery("#wpbe-float-side-modal-bulk-edit .wpbe-bulk-edit-form-group[data-type=attribute][data-taxonomy=" + attribute_name + "]")
                    .find("select[data-field=value]")
                    .val(postData.attribute[attribute_name])
                    .change();
            }
        });
    }

    let custom_fields = jQuery("#wpbe-float-side-modal-bulk-edit .wpbe-bulk-edit-form-group[data-type=custom_fields]");
    if (custom_fields.length > 0) {
        let taxonomy_name = "";
        custom_fields.each(function () {
            taxonomy_name = jQuery(this).attr("data-taxonomy");
            if (postData.meta_field[taxonomy_name]) {
                jQuery("#wpbe-float-side-modal-bulk-edit .wpbe-bulk-edit-form-group[data-type=custom_fields][data-taxonomy=" + taxonomy_name + "]")
                    .find("[data-field=value]")
                    .val(postData.meta_field[taxonomy_name][0])
                    .change();
            }
        });
    }

    jQuery("#wpbe-bulk-edit-form-post-title").val(postData.post_title);
    jQuery("#wpbe-bulk-edit-form-post-slug").val(postData.post_slug);
    jQuery("#wpbe-bulk-edit-form-post-sku").val(postData.sku);
    jQuery("#wpbe-bulk-edit-form-post-description").val(postData.post_content);
    jQuery("#wpbe-bulk-edit-form-post-short-description").val(postData.post_excerpt);
    jQuery("#wpbe-bulk-edit-form-post-purchase-note").val(postData.purchase_note);
    jQuery("#wpbe-bulk-edit-form-post-menu-order").val(postData.menu_order);
    jQuery("#wpbe-bulk-edit-form-post-sold-individually").val(sold_individually).change();
    jQuery("#wpbe-bulk-edit-form-post-enable-reviews").val(reviews_allowed).change();
    jQuery("#wpbe-bulk-edit-form-post-post-status").val(postData.post_status).change();
    jQuery("#wpbe-bulk-edit-form-post-catalog-visibility").val(postData.catalog_visibility).change();
    jQuery("#wpbe-bulk-edit-form-post-date-created").val(postData.post_date);
    jQuery("#wpbe-bulk-edit-form-post-author").val(postData.post_author).change();
    jQuery("#wpbe-bulk-edit-form-categories").val(postData.post_cat).change();
    jQuery("#wpbe-bulk-edit-form-tags").val(postData.post_tag).change();
    jQuery("#wpbe-bulk-edit-form-regular-price").val(postData.regular_price);
    jQuery("#wpbe-bulk-edit-form-sale-price").val(postData.sale_price);
    jQuery("#wpbe-bulk-edit-form-sale-date-from").val(postData.date_on_sale_from);
    jQuery("#wpbe-bulk-edit-form-sale-date-to").val(postData.date_on_sale_to);
    jQuery("#wpbe-bulk-edit-form-tax-status").val(postData.tax_status).change();
    jQuery("#wpbe-bulk-edit-form-tax-class").val(postData.tax_class).change();
    jQuery("#wpbe-bulk-edit-form-shipping-class").val(postData.shipping_class).change();
    jQuery("#wpbe-bulk-edit-form-width").val(postData.width);
    jQuery("#wpbe-bulk-edit-form-height").val(postData.height);
    jQuery("#wpbe-bulk-edit-form-length").val(postData.length);
    jQuery("#wpbe-bulk-edit-form-weight").val(postData.weight);
    jQuery("#wpbe-bulk-edit-form-manage-stock").val(manage_stock).change();
    jQuery("#wpbe-bulk-edit-form-stock-status").val(postData.stock_status).change();
    jQuery("#wpbe-bulk-edit-form-stock-quantity").val(postData.stock_quantity);
    jQuery("#wpbe-bulk-edit-form-backorders").val(postData.backorders).change();
    jQuery("#wpbe-bulk-edit-form-post-type").val(postData.post_type).change();
    jQuery("#wpbe-bulk-edit-form-featured").val(featured).change();
    jQuery("#wpbe-bulk-edit-form-virtual").val(virtual).change();
    jQuery("#wpbe-bulk-edit-form-downloadable").val(downloadable).change();
    jQuery("#wpbe-bulk-edit-form-download-limit").val(postData.download_limit);
    jQuery("#wpbe-bulk-edit-form-download-expiry").val(postData.download_expiry).change();
    jQuery("#wpbe-bulk-edit-form-post-url").val(postData.meta_field._post_url);
    jQuery("#wpbe-bulk-edit-form-button-text").val(postData.meta_field._button_text);
    jQuery("#wpbe-bulk-edit-form-upsells").val(postData.upsell_ids).change();
    jQuery("#wpbe-bulk-edit-form-cross-sells").val(postData.cross_sell_ids).change();
}

function wpbeEditByCalculator(postIDs, field, values) {
    wpbeLoadingStart();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_edit_by_calculator",
            nonce: WPBE_DATA.ajax_nonce,
            post_ids: postIDs,
            field: field,
            operator: values.operator,
            value: values.value,
            operator_type: values.operator_type,
            round_item: values.roundItem,
        },
        success: function (response) {
            if (response.success) {
                wpbeReloadPosts(response.edited_ids);
            }
            wpbeCheckUndoRedoStatus(response.reverted, response.history_items);
            jQuery(".wpbe-history-items tbody").html(response.history_items);
        },
        error: function () {
            wpbeLoadingError();
        },
    });
}

function wpbeGetPostsChecked() {
    if (wpbeSelectAllChecked()) {
        return "all_filtered";
    } else {
        let postIds = [];
        let postsChecked = jQuery("input.wpbe-check-item:checkbox:checked");
        if (postsChecked.length > 0) {
            postIds = postsChecked
                .map(function (i) {
                    return jQuery(this).val();
                })
                .get();
        }
        return postIds;
    }
}

function wpbeDeletePost(postIDs, deleteType) {
    wpbeLoadingStart();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_delete_posts",
            nonce: WPBE_DATA.ajax_nonce,
            post_ids: postIDs,
            delete_type: deleteType,
            filter_data: wpbeGetCurrentFilterData(),
        },
        success: function (response) {
            if (response.success) {
                if (response.is_processing) {
                    wpbeIsProcessing();
                    wpbeLoadingProcessingStart(WPBE_DATA.background_process.loading_messages.processing, true, { total: response.total_tasks, completed: response.completed_tasks });
                } else {
                    wpbeReloadPosts();
                    wpbeHideSelectionTools();
                    wpbeCheckUndoRedoStatus(response.reverted, response.history_items);
                    jQuery(".wpbe-history-items tbody").html(response.history_items);
                }
            } else {
                wpbeLoadingError();
            }
        },
        error: function () {
            wpbeLoadingError();
        },
    });
}

function wpbeRestorePost(postIds) {
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_untrash_posts",
            nonce: WPBE_DATA.ajax_nonce,
            post_ids: postIds,
        },
        success: function (response) {
            if (response.success) {
                if (response.is_processing) {
                    wpbeIsProcessing();
                    wpbeLoadingProcessingStart(WPBE_DATA.background_process.loading_messages.processing, true, { total: response.total_tasks, completed: response.completed_tasks });
                } else {
                    wpbeReloadPosts();
                    wpbeHideSelectionTools();
                }
            } else {
                wpbeLoadingError();
            }
        },
        error: function () {
            wpbeLoadingError();
        },
    });
}

function wpbeEmptyTrash() {
    wpbeLoadingStart();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_empty_trash",
            nonce: WPBE_DATA.ajax_nonce,
        },
        success: function (response) {
            if (response.success) {
                if (response.is_processing) {
                    wpbeIsProcessing();
                    wpbeLoadingProcessingStart(WPBE_DATA.background_process.loading_messages.processing, true, { total: response.total_tasks, completed: response.completed_tasks });
                } else {
                    wpbeReloadPosts();
                    wpbeHideSelectionTools();
                }
            } else {
                wpbeLoadingError();
            }
        },
        error: function () {
            wpbeLoadingError();
        },
    });
}

function wpbeDuplicatePost(postIDs, duplicateNumber) {
    wpbeLoadingStart();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_duplicate_post",
            nonce: WPBE_DATA.ajax_nonce,
            post_ids: postIDs,
            duplicate_number: duplicateNumber,
        },
        success: function (response) {
            if (response.success) {
                if (response.is_processing) {
                    wpbeLoadingProcessingStart(WPBE_DATA.background_process.loading_messages.processing, true, { total: response.total_tasks, completed: response.completed_tasks });
                    wpbeIsProcessing();
                } else {
                    wpbeReloadPosts([], wpbeGetCurrentPage());
                    wpbeCloseModal();
                    wpbeHideSelectionTools();
                }
            } else {
                wpbeLoadingError();
            }
        },
        error: function () {
            wpbeLoadingError();
        },
    });
}

function wpbeCreateNewPost(count = 1, postData = {}) {
    wpbeLoadingStart();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_create_new_post",
            nonce: WPBE_DATA.ajax_nonce,
            count: count,
            postData: postData,
        },
        success: function (response) {
            if (response.success) {
                if (response.is_processing) {
                    wpbeIsProcessing();
                    wpbeLoadingProcessingStart(WPBE_DATA.background_process.loading_messages.processing, true, { total: response.total_tasks, completed: response.completed_tasks });
                } else {
                    wpbeReloadPosts(response.post_ids, 1);
                    wpbeCloseModal();
                }
            } else {
                wpbeLoadingError();
            }
        },
        error: function () {
            wpbeLoadingError();
        },
    });
}

function wpbeGetAllCombinations(attributes_arr) {
    var combinations = [],
        args = attributes_arr,
        max = args.length - 1;
    helper([], 0);

    function helper(arr, i) {
        for (let j = 0; j < args[i][1].length; j++) {
            let a = arr.slice(0);
            a.push([args[i][0], args[i][1][j]]);
            if (i === max) {
                combinations.push(a);
            } else {
                helper(a, i + 1);
            }
        }
    }

    return combinations;
}

function wpbeSaveColumnProfile(presetKey, items, type) {
    wpbeLoadingStart();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_save_column_profile",
            nonce: WPBE_DATA.ajax_nonce,
            preset_key: presetKey,
            items: items,
            type: type,
        },
        success: function (response) {
            if (response.success) {
                wpbeLoadingSuccess();
                location.href = location.href.replace(location.hash, "");
            } else {
                wpbeLoadingError();
            }
        },
        error: function () {
            wpbeLoadingError();
        },
    });
}

function wpbeLoadFilterProfile(presetKey) {
    wpbeLoadingStart();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_load_filter_profile",
            nonce: WPBE_DATA.ajax_nonce,
            preset_key: presetKey,
        },
        success: function (response) {
            if (response.success) {
                wpbeResetFilterForm();
                setTimeout(function () {
                    setFilterValues(response.filter_data);
                }, 500);
                wpbeLoadingSuccess();
                wpbeSetPostsList(response);
                wpbeCloseModal();
            } else {
                wpbeLoadingError();
            }
        },
        error: function () {
            wpbeLoadingError();
        },
    });
}

function wpbeDeleteFilterProfile(presetKey) {
    wpbeLoadingStart();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_delete_filter_profile",
            nonce: WPBE_DATA.ajax_nonce,
            preset_key: presetKey,
        },
        success: function (response) {
            if (response.success) {
                wpbeLoadingSuccess();
            } else {
                wpbeLoadingError();
            }
        },
        error: function () {
            wpbeLoadingError();
        },
    });
}

function wpbeFilterProfileChangeUseAlways(presetKey) {
    wpbeLoadingStart();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_filter_profile_change_use_always",
            nonce: WPBE_DATA.ajax_nonce,
            preset_key: presetKey,
        },
        success: function (response) {
            if (response.success) {
                jQuery('.wpbe-bulk-edit-filter-profile-load[value="' + presetKey + '"]').trigger("click");
            } else {
                wpbeLoadingError();
            }
        },
        error: function () {
            wpbeLoadingError();
        },
    });
}

function wpbeGetCurrentFilterData() {
    return jQuery("#wpbe-quick-search-text").val() ? wpbeGetQuickSearchData() : wpbeGetProSearchData();
}

function wpbeResetQuickSearchForm() {
    jQuery(".wpbe-top-nav-filters-search input").val("");
    jQuery(".wpbe-top-nav-filters-search select").prop("selectedIndex", 0);
    jQuery("#wpbe-quick-search-reset").hide();
    jQuery(".wpbe-quick-filter a").removeClass("active");
}

function wpbeResetFilterForm() {
    jQuery(".wpbe-reset-filter-form").closest("li").hide();

    jQuery("#wpbe-float-side-modal-filter input").val("").change();
    jQuery("#wpbe-float-side-modal-filter textarea").val("").change();
    jQuery('#wpbe-float-side-modal-filter select[data-type="operator"]').prop("selectedIndex", 0);
    jQuery('#wpbe-float-side-modal-filter select[data-field="value"]').val("").change();
    jQuery('#wpbe-float-side-modal-filter select[data-field="from"]').val("").change();
    jQuery('#wpbe-float-side-modal-filter select[data-field="to"]').val("").change();
    jQuery("#wpbe-float-side-modal-filter .wpbe-select2").val("").trigger("change");
    jQuery("#wpbe-float-side-modal-filter .select2-hidden-accessible").val("").trigger("change");
    jQuery("#wpbe-float-side-modal-filter .wpbe-select2-item").val("").trigger("change");
    jQuery(".wpbe-bulk-edit-status-filter-item").removeClass("active");
}

function wpbeResetFilters() {
    wpbeResetFilterForm();
    wpbeResetQuickSearchForm();

    jQuery(".wpbe-reset-filter-form").closest("li").hide();

    setTimeout(function () {
        if (window.location.search !== "?page=wpbe") {
            wpbeClearFilterDataWithRedirect();
        } else {
            let data = wpbeGetCurrentFilterData();
            wpbePostsFilter(data, "pro_search");
        }
    }, 250);
}

function wpbeCheckResetFilterButton() {
    jQuery(".wpbe-reset-filter-form").closest("li").hide();

    if (
        jQuery('#wpbe-bulk-edit-filter-tabs-contents [data-field="value"], #wpbe-bulk-edit-filter-tabs-contents [data-field="from"], #wpbe-bulk-edit-filter-tabs-contents [data-field="to"]').length > 0
    ) {
        jQuery('#wpbe-bulk-edit-filter-tabs-contents [data-field="value"], #wpbe-bulk-edit-filter-tabs-contents [data-field="from"], #wpbe-bulk-edit-filter-tabs-contents [data-field="to"]').each(
            function () {
                if (jQuery(this).val() != "" && jQuery(this).val() != null) {
                    jQuery(".wpbe-reset-filter-form").closest("li").show();
                    return true;
                }
            }
        );
    }
}

function wpbeClearFilterDataWithRedirect() {
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_clear_filter_data",
            nonce: WPBE_DATA.ajax_nonce,
        },
        success: function (response) {
            window.location.search = "?page=wpbe";
        },
        error: function () { },
    });
}

function wpbeChangeCountPerPage(countPerPage) {
    wpbeLoadingStart();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_change_count_per_page",
            nonce: WPBE_DATA.ajax_nonce,
            count_per_page: countPerPage,
        },
        success: function (response) {
            if (response.success) {
                wpbeReloadPosts([], 1);
            } else {
                wpbeLoadingError();
            }
        },
        error: function () {
            wpbeLoadingError();
        },
    });
}

function wpbeUpdatePostTaxonomy(post_ids, field, data, reload) {
    wpbeLoadingStart();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_update_post_taxonomy",
            nonce: WPBE_DATA.ajax_nonce,
            post_ids: post_ids,
            field: field,
            values: data,
        },
        success: function (response) {
            if (response.success) {
                wpbeCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery(".wpbe-history-items tbody").html(response.history_items);

                if (reload === true) {
                    wpbeReloadPosts(post_ids);
                } else {
                    wpbeLoadingSuccess();
                }
            } else {
                wpbeLoadingError();
            }
        },
        error: function () {
            wpbeLoadingError();
        },
    });
}

function wpbeAddPostTaxonomy(taxonomyInfo, taxonomyName, postId) {
    wpbeLoadingStart();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_add_post_taxonomy",
            nonce: WPBE_DATA.ajax_nonce,
            taxonomy_info: taxonomyInfo,
            taxonomy_name: taxonomyName,
        },
        success: function (response) {
            if (response.success) {
                wpbeGetPostTaxonomyTerms(postId, taxonomyName);
                wpbeLoadingSuccess();
                wpbeCloseModal();
            } else {
                wpbeLoadingError();
            }
        },
        error: function () {
            wpbeLoadingError();
        },
    });
}

function wpbeAddPostAttribute(attributeInfo, attributeName) {
    wpbeLoadingStart();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_add_post_attribute",
            nonce: WPBE_DATA.ajax_nonce,
            attribute_info: attributeInfo,
            attribute_name: attributeName,
        },
        success: function (response) {
            if (response.success) {
                jQuery("#wpbe-modal-attribute-" + attributeName + "-" + attributeInfo.post_id + " .wpbe-post-items-list").html(response.attribute_items);
                wpbeLoadingSuccess();
                wpbeCloseModal();
            } else {
                wpbeLoadingError();
            }
        },
        error: function () {
            wpbeLoadingError();
        },
    });
}

function wpbeAddNewFileItem() {
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_add_new_file_item",
            nonce: WPBE_DATA.ajax_nonce,
        },
        success: function (response) {
            if (response.success) {
                jQuery("#wpbe-modal-select-files .wpbe-inline-select-files").prepend(response.file_item);
                wpbeSetTipsyTooltip();
            }
        },
        error: function () { },
    });
}

function wpbeAddCustomFieldFileItem() {
    jQuery("#wpbe-modal-custom-field-files-loading").show();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_add_custom_field_file_item",
            nonce: WPBE_DATA.ajax_nonce,
        },
        success: function (response) {
            if (response.success) {
                jQuery("#wpbe-modal-custom-field-files .wpbe-inline-custom-field-files").prepend(response.file_item);
                wpbeSetTipsyTooltip();
            }

            jQuery("#wpbe-modal-custom-field-files-loading").hide();
        },
        error: function () {
            jQuery("#wpbe-modal-custom-field-files-loading").hide();
        },
    });
}

function wpbeBulkEditAddCustomFieldFileItem() {
    jQuery("#wpbe-bulk-edit-custom-field-files-loading").show();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_bulk_edit_add_custom_field_file_item",
            nonce: WPBE_DATA.ajax_nonce,
        },
        success: function (response) {
            if (response.success) {
                jQuery("#wpbe-float-side-modal-bulk-edit .wpbe-bulk-edit-custom-field-files").prepend(response.file_item);
                wpbeSetTipsyTooltip();
            }
            jQuery("#wpbe-bulk-edit-custom-field-files-loading").hide();
        },
        error: function () {
            jQuery("#wpbe-bulk-edit-custom-field-files-loading").hide();
        },
    });
}

function wpbeGetPostCustomFieldFiles(postID, customFieldName) {
    jQuery("#wpbe-modal-custom-field-files-loading").show();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_get_post_custom_field_files",
            nonce: WPBE_DATA.ajax_nonce,
            post_id: postID,
            field_name: customFieldName,
        },
        success: function (response) {
            if (response.success) {
                jQuery(".wpbe-inline-custom-field-files").html(response.files);
            } else {
                jQuery(".wpbe-inline-custom-field-files").html("");
            }
            jQuery("#wpbe-modal-custom-field-files-loading").hide();
        },
        error: function () {
            jQuery(".wpbe-inline-custom-field-files").html("");
            jQuery("#wpbe-modal-custom-field-files-loading").hide();
        },
    });
}

function wpbeGetPostFiles(postID) {
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_get_post_files",
            nonce: WPBE_DATA.ajax_nonce,
            post_id: postID,
        },
        success: function (response) {
            if (response.success) {
                jQuery("#wpbe-modal-select-files .wpbe-inline-select-files").html(response.files);
                wpbeSetTipsyTooltip();
            } else {
                jQuery("#wpbe-modal-select-files .wpbe-inline-select-files").html("");
            }
        },
        error: function () {
            jQuery("#wpbe-modal-select-files .wpbe-inline-select-files").html("");
        },
    });
}

function changedTabs(item) {
    let change = false;
    let tab = jQuery("nav.wpbe-tabs-navbar a[data-content=" + item.closest(".wpbe-tab-content-item").attr("data-content") + "]");
    item
        .closest(".wpbe-tab-content-item")
        .find("[data-field=operator]")
        .each(function () {
            if (jQuery(this).val() === "text_remove_duplicate") {
                change = true;
                return false;
            }
        });
    item
        .closest(".wpbe-tab-content-item")
        .find('[data-field="value"], [data-field="from"], [data-field="to"]')
        .each(function () {
            if (jQuery(this).val() && jQuery(this).val() != "") {
                change = true;
                return false;
            }
        });
    if (change === true) {
        tab.addClass("wpbe-tab-changed");
    } else {
        tab.removeClass("wpbe-tab-changed");
    }
}

function wpbeGetQuickSearchData() {
    return {
        search_type: "quick_search",
        quick_search_text: jQuery("#wpbe-quick-search-text").val(),
        quick_search_field: jQuery("#wpbe-quick-search-field").val(),
        quick_search_operator: jQuery("#wpbe-quick-search-operator").val(),
    };
}

function wpbeSortByColumn(columnName, sortType) {
    wpbeLoadingStart();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_sort_by_column",
            nonce: WPBE_DATA.ajax_nonce,
            filter_data: wpbeGetCurrentFilterData(),
            column_name: columnName,
            sort_type: sortType,
        },
        success: function (response) {
            if (response.success) {
                wpbeLoadingSuccess();
                wpbeSetPostsList(response);
            } else {
                wpbeLoadingError();
            }
        },
        error: function () {
            wpbeLoadingError();
        },
    });
}

function wpbeColumnManagerFieldsGetForEdit(presetKey) {
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_column_manager_get_fields_for_edit",
            nonce: WPBE_DATA.ajax_nonce,
            preset_key: presetKey,
        },
        success: function (response) {
            jQuery("#wpbe-modal-column-manager-edit-preset .wpbe-box-loading").hide();
            jQuery(".wpbe-column-manager-added-fields[data-action=edit] .items").html(response.html);
            setTimeout(function () {
                wpbeSetColorPickerTitle();
            }, 250);
            jQuery(".wpbe-column-manager-available-fields[data-action=edit] li").each(function () {
                if (jQuery.inArray(jQuery(this).attr("data-name"), response.fields.split(",")) !== -1) {
                    jQuery(this).attr("data-added", "true").hide();
                } else {
                    jQuery(this).attr("data-added", "false").show();
                }
            });
            jQuery(".wpbe-color-picker").wpColorPicker();
        },
    });
}

function wpbeAddMetaKeysByPostID(postID) {
    wpbeLoadingStart();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "html",
        data: {
            action: "wpbe_add_meta_keys_by_post_id",
            nonce: WPBE_DATA.ajax_nonce,
            post_id: postID,
        },
        success: function (response) {
            jQuery("#wpbe-meta-fields-items").append(response);
            wpbeLoadingSuccess();
        },
        error: function () {
            wpbeLoadingError();
        },
    });
}

function wpbeHistoryUndo() {
    wpbeLoadingStart();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_history_undo",
            nonce: WPBE_DATA.ajax_nonce,
        },
        success: function (response) {
            if (response.wpbe === false) {
                wpbeLoadingError(response.message);
            } else {
                if (response.success) {
                    if (response.is_processing) {
                        wpbeLoadingProcessingStart(WPBE_DATA.background_process.loading_messages.processing, true, { total: response.total_tasks, completed: response.completed_tasks });
                        wpbeIsProcessing();
                    } else {
                        wpbeLoadingSuccess();
                        wpbeCheckUndoRedoStatus(response.reverted, response.history_items);
                        jQuery(".wpbe-history-items tbody").html(response.history_items);
                        jQuery(".wpbe-history-pagination-container").html(response.history_pagination);
                        wpbeReloadPosts(response.post_ids);
                    }
                }
            }
        },
        error: function () {
            wpbeLoadingError();
        },
    });
}

function wpbeHistoryRedo() {
    wpbeLoadingStart();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_history_redo",
            nonce: WPBE_DATA.ajax_nonce,
        },
        success: function (response) {
            if (response.wpbe === false) {
                wpbeLoadingError(response.message);
            } else {
                if (response.success) {
                    if (response.is_processing) {
                        wpbeLoadingProcessingStart(WPBE_DATA.background_process.loading_messages.processing, true, { total: response.total_tasks, completed: response.completed_tasks });
                        wpbeIsProcessing();
                    } else {
                        wpbeLoadingSuccess();
                        wpbeCheckUndoRedoStatus(response.reverted, response.history_items);
                        jQuery(".wpbe-history-items tbody").html(response.history_items);
                        jQuery(".wpbe-history-pagination-container").html(response.history_pagination);
                        wpbeReloadPosts(response.post_ids);
                    }
                }
            }
        },
        error: function () {
            wpbeLoadingError();
        },
    });
}

function wpbeHistoryFilter(filters = null, loading = true) {
    if (loading === true) {
        wpbeLoadingStart();
    }
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_history_filter",
            nonce: WPBE_DATA.ajax_nonce,
            filters: filters,
        },
        success: function (response) {
            if (response.success) {
                if (loading === true) {
                    wpbeLoadingSuccess();
                }

                wpbeCheckUndoRedoStatus(response.reverted, response.history_items);
                if (response.history_items) {
                    jQuery(".wpbe-history-items tbody").html(response.history_items);
                    jQuery(".wpbe-history-pagination-container").html(response.history_pagination);
                } else {
                    jQuery(".wpbe-history-items tbody").html("<td colspan='100%'><span>No data available!</span></td>");
                }
            } else {
                if (loading === true) {
                    wpbeLoadingError();
                }
            }
        },
        error: function () {
            if (loading === true) {
                wpbeLoadingError();
            }
        },
    });
}

function wpbeHistoryChangePage(page = 1, filters = null) {
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_history_change_page",
            nonce: WPBE_DATA.ajax_nonce,
            page: page,
            filters: filters,
        },
        success: function (response) {
            if (response.success) {
                wpbeLoadingSuccess();
                if (response.history_items) {
                    jQuery(".wpbe-history-items tbody").html(response.history_items);
                    jQuery(".wpbe-history-pagination-container").html(response.history_pagination);
                } else {
                    jQuery(".wpbe-history-items tbody").html("<td colspan='4'><span>" + wpbeTranslate.notFound + "</span></td>");
                }
                jQuery(".wpbe-history-pagination-loading").hide();
            } else {
                jQuery(".wpbe-history-pagination-loading").hide();
            }
        },
        error: function () {
            jQuery(".wpbe-history-pagination-loading").hide();
        },
    });
}

function wpbeGetCurrentPage() {
    return jQuery(".wpbe-tabs-navigation .wpbe-top-nav-filters-paginate a.current").attr("data-index");
}

function wpbeGetDefaultFilterProfilePosts() {
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_get_default_filter_profile_posts",
            nonce: WPBE_DATA.ajax_nonce,
        },
        success: function (response) {
            wpbeCheckUndoRedoStatus(response.reverted, response.history);
            if (response.success) {
                setTimeout(function () {
                    setFilterValues(response.filter_data);
                }, 500);
                wpbeSetPostsList(response);
            }
        },
        error: function () { },
    });
}

function setFilterValues(filterData) {
    if (filterData) {
        if (filterData.fields && Object.keys(filterData.fields).length) {
            jQuery.each(filterData.fields, function (key, object) {
                if (object.value instanceof Object) {
                    if (object.operator) {
                        jQuery('#wpbe-float-side-modal-filter .wpbe-form-group[data-name="' + object.name + '"]').find('[data-field="operator"]').val(object.operator).change();
                    }
                    if (object.value.length) {
                        let selectElement = jQuery('#wpbe-float-side-modal-filter .wpbe-form-group[data-name="' + object.name + '"]').find('[data-field="value"]');
                        jQuery.each(object.value, function (i, valueItem) {
                            if (WPBE_DATA.filter_option_values && WPBE_DATA.filter_option_values[object.name] && WPBE_DATA.filter_option_values[object.name][valueItem]) {
                                selectElement.append('<option value="' + valueItem + '" selected>' + WPBE_DATA.filter_option_values[object.name][valueItem] + "</option>");
                            }
                        });
                        selectElement.change();
                    }
                    if (object.value.from) {
                        jQuery('#wpbe-float-side-modal-filter .wpbe-form-group[data-name="' + object.name + '"]').find('[data-field="from"]').val(object.value.from).change();
                    }
                    if (object.value.to) {
                        jQuery('#wpbe-float-side-modal-filter .wpbe-form-group[data-name="' + object.name + '"]').find('[data-field="to"]').val(object.value.to);
                    }
                } else {
                    let element = jQuery('#wpbe-float-side-modal-filter .wpbe-form-group[data-name="' + object.name + '"]').find('[data-field="value"]');
                    if (element.hasClass("wpbe-filter-form-select2-option-values")) {
                        if (WPBE_DATA.filter_option_values && WPBE_DATA.filter_option_values[object.name] && WPBE_DATA.filter_option_values[object.name][object.value]) {
                            element.html('<option value="' + object.value + '" selected>' + WPBE_DATA.filter_option_values[object.name][object.value] + "</option>").change();
                        }
                    } else {
                        element.val(object.value).change();
                    }
                }
            });


            setTimeout(function () {
                jQuery(".wpbe-bulk-edit-status-filter-item").removeClass("active");
                let statusFilter = jQuery("#wpbe-filter-form-post_status").val() && jQuery("#wpbe-filter-form-post_status").val() != "" ? jQuery("#wpbe-filter-form-post_status").val() : "all";
                if (jQuery.isArray(statusFilter)) {
                    statusFilter.forEach(function (val) {
                        jQuery('.wpbe-bulk-edit-status-filter-item[data-status="' + val + '"]').addClass("active");
                    });
                } else {
                    let activeItem = jQuery('.wpbe-bulk-edit-status-filter-item[data-status="' + statusFilter + '"]');
                    activeItem.addClass("active");
                    jQuery(".wpbe-status-filter-selected-name").text(" - " + activeItem.text());
                }
            }, 500);
            wpbeCheckFilterFormChanges();
            wpbeCheckResetFilterButton();
        }
    }
}

function wpbeGetFilterFormOptionValues() {
    let optionValues = {};
    if (jQuery(".wpbe-filter-form-select2-option-values").length) {
        jQuery(".wpbe-filter-form-select2-option-values option:selected").each(function () {
            if (jQuery(this).attr("value") != "" && jQuery(this).attr("value") != null) {
                let optionName = jQuery(this).closest(".wpbe-filter-form-select2-option-values").attr("data-option-name");
                if (!optionValues[optionName]) {
                    optionValues[optionName] = {};
                }
                optionValues[optionName][jQuery(this).attr("value")] = jQuery(this).text();
            }
        });
    }
    return optionValues;
}

function checkedCurrentCategory(id, categoryIds) {
    categoryIds.forEach(function (value) {
        jQuery(id + " input[value=" + value + "]").prop("checked", "checked");
    });
}

function wpbeSaveFilterPreset(data, presetName) {
    wpbeLoadingStart();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_save_filter_preset",
            nonce: WPBE_DATA.ajax_nonce,
            filter_data: data,
            preset_name: presetName,
            option_values: wpbeGetFilterFormOptionValues(),
        },
        success: function (response) {
            if (response.success) {
                wpbeLoadingSuccess();
                jQuery("#wpbe-float-side-modal-filter-profiles").find("tbody").append(response.new_item);
            } else {
                wpbeLoadingError();
            }
        },
        error: function () {
            wpbeLoadingError();
        },
    });
}

function wpbeResetBulkEditForm() {
    jQuery("#wpbe-float-side-modal-bulk-edit input, #wpbe-float-side-modal-bulk-new-posts input").val("").change();
    jQuery('#wpbe-float-side-modal-bulk-edit input[type="checkbox"], #wpbe-float-side-modal-bulk-new-posts input[type="checkbox"]').prop("checked", false).change();
    jQuery(
        '#wpbe-float-side-modal-bulk-edit select[data-field="value"], #wpbe-float-side-modal-bulk-edit select[data-field="operator"], #wpbe-float-side-modal-bulk-edit select[data-field="round"], #wpbe-float-side-modal-bulk-edit select[data-field="variable"]'
    )
        .prop("selectedIndex", 0)
        .change();
    jQuery(
        '#wpbe-float-side-modal-bulk-new-posts select[data-field="value"], #wpbe-float-side-modal-bulk-new-posts select[data-field="operator"], #wpbe-float-side-modal-bulk-new-posts select[data-field="round"], #wpbe-float-side-modal-bulk-new-posts select[data-field="variable"]'
    )
        .prop("selectedIndex", 0)
        .change();
    jQuery("#wpbe-float-side-modal-bulk-edit .wpbe-select2, #wpbe-float-side-modal-bulk-new-posts .wpbe-select2").val("").trigger("change");
    jQuery("#wpbe-float-side-modal-bulk-edit .wpbe-bulk-edit-custom-field-files").html("");
    jQuery("#wpbe-float-side-modal-bulk-edit .wpbe-bulk-edit-form-item-image-preview").html("");
    jQuery("#wpbe-float-side-modal-bulk-new-posts .wpbe-bulk-edit-form-item-image-preview").html("");
    jQuery("#wpbe-float-side-modal-bulk-edit .wpbe-bulk-edit-form-item-image-preview").closest(".wpbe-form-group").find('input[data-field="value"]').val("").change();
    jQuery("#wpbe-float-side-modal-bulk-new-posts .wpbe-bulk-edit-form-item-image-preview").closest(".wpbe-form-group").find('input[data-field="value"]').val("").change();
    jQuery("#wpbe-float-side-modal-bulk-edit .wpbe-bulk-edit-form-item-gallery").html("");
    jQuery("#wpbe-float-side-modal-bulk-edit .wpbe-bulk-edit-form-item-gallery").html("");
    jQuery("#wpbe-float-side-modal-bulk-new-posts .wpbe-bulk-edit-form-item-gallery-preview").html("");
    jQuery("nav.wpbe-tabs-navbar li a").removeClass("wpbe-tab-changed");
}

function wpbeGetProSearchData() {
    let data = {
        search_type: "pro_search",
        fields: {},
    };

    jQuery('#wpbe-float-side-modal-filter .wpbe-form-group [data-field="value"], #wpbe-float-side-modal-filter .wpbe-form-group [data-field="from"]').each(function () {
        if ((jQuery(this).val() != "" && jQuery(this).val() != null) || jQuery(this).attr("data-field") == "from") {
            let fromGroupElement = jQuery(this).closest(".wpbe-form-group");
            let value;

            if (jQuery(this).attr("data-field") == "value") {
                value = jQuery(this).val();
            }

            if (jQuery(this).attr("data-field") == "from") {
                let toField = fromGroupElement.find('[data-field="to"]');
                if (toField.val() != "" || jQuery(this).val() != "") {
                    value = {
                        from: jQuery(this).val(),
                        to: toField.val(),
                    };
                }
            }

            if (value) {
                data["fields"][fromGroupElement.attr("data-name")] = {
                    name: fromGroupElement.attr("data-name"),
                    filter_type: fromGroupElement.attr("data-filter-type"),
                    field_type: fromGroupElement.attr("data-field-type"),
                    operator: fromGroupElement.find('[data-field="operator"]').length ? fromGroupElement.find('[data-field="operator"]').val() : "",
                    value: value,
                };
            }
        }
    });

    return data;
}

function wpbePostEdit(postIds, postData) {
    wpbeLoadingStart();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_post_edit",
            nonce: WPBE_DATA.ajax_nonce,
            post_ids: postIds,
            post_data: postData,
            current_page: wpbeGetCurrentPage(),
            filter_data: wpbeGetCurrentFilterData(),
        },
        success: function (response) {
            if (response.success) {
                if (response.is_processing) {
                    wpbeIsProcessing();
                    wpbeLoadingProcessingStart(WPBE_DATA.background_process.loading_messages.processing, true, { total: response.total_tasks, completed: response.completed_tasks });
                } else {
                    wpbeReloadRows(response.posts, response.post_statuses);
                    wpbeSetStatusFilter(response.status_filters);
                    wpbeCheckUndoRedoStatus(response.reverted, response.history_items);
                    jQuery(".wpbe-history-items tbody").html(response.history_items);
                    jQuery(".wpbe-history-pagination-container").html(response.history_pagination);
                    wpbeReInitDatePicker();
                    wpbeReInitColorPicker();
                    let wpbeTextEditors = jQuery('input[name="wpbe-editors[]"]');
                    if (wpbeTextEditors.length > 0) {
                        wpbeTextEditors.each(function () {
                            tinymce.execCommand("mceRemoveEditor", false, jQuery(this).val());
                            tinymce.execCommand("mceAddEditor", false, jQuery(this).val());
                        });
                    }
                    wpbeLoadingSuccess();
                }
            } else {
                wpbeLoadingError();
            }
        },
        error: function () {
            wpbeLoadingError();
        },
    });
}

function wpbeSetStatusFilter(statusFilters) {
    jQuery(".wpbe-top-nav-status-filter").html(statusFilters);
    jQuery(".wpbe-bulk-edit-status-filter-item").removeClass("active");
    let statusFilter = jQuery("#wpbe-filter-form-post_status").val() && jQuery("#wpbe-filter-form-post_status").val() != "" ? jQuery("#wpbe-filter-form-post_status").val() : "all";
    if (jQuery.isArray(statusFilter)) {
        statusFilter.forEach(function (val) {
            jQuery('.wpbe-bulk-edit-status-filter-item[data-status="' + val + '"]').addClass("active");
        });
    } else {
        let activeItem = jQuery('.wpbe-bulk-edit-status-filter-item[data-status="' + statusFilter + '"]');
        activeItem.addClass("active");
        jQuery(".wpbe-status-filter-selected-name").text(" - " + activeItem.text());
    }
}

function wpbeReloadRows(posts, statuses) {
    let reloadNeeded = false;
    let currentStatus = jQuery("#wpbe-filter-form-post_status").val();
    let oldEdited = jQuery("tr.wpbe-item-edited");
    oldEdited.removeClass("wpbe-item-edited");
    if (!wpbeSelectAllChecked() && !wpbeSelectVisibleChecked()) {
        oldEdited.find(".wpbe-check-item").prop("checked", false);
    }
    if (Object.keys(posts).length > 0) {
        jQuery.each(posts, function (key, val) {
            if (statuses[key] === currentStatus || (!currentStatus && statuses[key] !== "trash")) {
                jQuery("#wpbe-items-list")
                    .find('tr[data-item-id="' + key + '"]')
                    .replaceWith(val);
                jQuery('tr[data-item-id="' + key + '"]')
                    .addClass("wpbe-item-edited")
                    .find(".wpbe-check-item")
                    .prop("checked", true);
            } else {
                reloadNeeded = true;
                // jQuery('#wpbe-items-list').find('tr[data-item-id="' + key + '"]').remove();
            }
        });

        if (reloadNeeded === true) {
            wpbeReloadPosts();
        }
        wpbeShowSelectionTools();
    } else {
        wpbeHideSelectionTools();
    }
}

function wpbeUpdatePostAttribute(post_ids, field, data, reload) {
    wpbeLoadingStart();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_update_post_attribute",
            nonce: WPBE_DATA.ajax_nonce,
            post_ids: post_ids,
            field: field,
            values: data,
        },
        success: function (response) {
            if (response.success) {
                if (reload === true) {
                    wpbeReloadPosts(post_ids);
                } else {
                    wpbeLoadingSuccess();
                }
                wpbeCheckUndoRedoStatus(response.reverted, response.history_items);
                jQuery(".wpbe-history-items tbody").html(response.history_items);
            } else {
                wpbeLoadingError();
            }
        },
        error: function () {
            wpbeLoadingError();
        },
    });
}

function wpbeGetTaxonomyParentSelectBox(taxonomy) {
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_get_taxonomy_parent_select_box",
            nonce: WPBE_DATA.ajax_nonce,
            taxonomy: taxonomy,
        },
        success: function (response) {
            if (response.success) {
                jQuery("#wpbe-new-post-taxonomy-parent").html(response.options);
            }
        },
        error: function () { },
    });
}

function getAttributeValues(name, target) {
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_get_attribute_values",
            nonce: WPBE_DATA.ajax_nonce,
            attribute_name: name,
        },
        success: function (response) {
            if (response.success) {
                jQuery(target).append(response.attribute_item);
                jQuery(".wpbe-select2-ajax").select2();
            } else {
            }
        },
        error: function () { },
    });
}

function getAttributeValuesForDelete(name, target) {
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_get_attribute_values_for_delete",
            nonce: WPBE_DATA.ajax_nonce,
            attribute_name: name,
        },
        success: function (response) {
            if (response.success) {
                jQuery(target).append(response.attribute_item);
            } else {
            }
        },
        error: function () { },
    });
}

function wpbeGetPostTaxonomyTerms(postId, taxonomy) {
    jQuery(".wpbe-modal-post-taxonomy-terms-list").html("");
    jQuery(".wpbe-modal-post-taxonomy-loading").show();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_get_post_taxonomy_terms",
            nonce: WPBE_DATA.ajax_nonce,
            post_id: postId,
            taxonomy: taxonomy,
        },
        success: function (response) {
            if (response.success) {
                jQuery(".wpbe-modal-post-taxonomy-terms-list").html(response.terms);
            } else {
                jQuery(".wpbe-modal-post-taxonomy-terms-list").html("");
            }
            jQuery(".wpbe-modal-post-taxonomy-loading").hide();
        },
        error: function () {
            jQuery(".wpbe-modal-post-taxonomy-loading").hide();
        },
    });
}

function wpbeIsProcessing(postIds = []) {
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_is_processing",
            nonce: WPBE_DATA.ajax_nonce,
        },
        success: function (response) {
            if (response.crashed === true) {
                wpbeLoadingProcessingError("Error !");
                wpbeBackgroundProcessClearCompleteMessage();
                wpbeHistoryFilter(null, false);
            } else if (response.is_processing === false) {
                wpbeBackgroundProcessClearCompleteMessage();
                setTimeout(function () {
                    wpbeCloseModal();
                    wpbeLoadingProcessingSuccess("Your changes have been applied");
                }, 10);
                wpbeReloadPosts();
            } else {
                if (!jQuery(".wpbe-processing-loading:visible").length) {
                    wpbeLoadingProcessingStart(
                        WPBE_DATA.background_process.loading_messages.processing,
                        {
                            total: response.total_tasks,
                            completed: response.completed_tasks,
                        },
                        response.remaining_time
                    );
                } else {
                    if (response.total_tasks && response.completed_tasks) {
                        jQuery('.wpbe-processing-loading span[data-type="tasks"]').show();
                        jQuery('.wpbe-processing-loading span[data-type="tasks"]').find('[data-type="total"]').text(response.total_tasks);
                        jQuery('.wpbe-processing-loading span[data-type="tasks"]')
                            .find('[data-type="completed"]')
                            .text(response.completed_tasks > 0 ? "+" + response.completed_tasks : response.completed_tasks);
                    }

                    if (response.remaining_time) {
                        jQuery("#wpbe-processing-loading").find('[data-type="time_remaining"]').find('[data-type="time"]').text(response.remaining_time);
                        jQuery("#wpbe-processing-loading").find('[data-type="time_remaining"]').show();
                    }
                }

                setTimeout(function () {
                    if (!response.is_force_stopped) {
                        wpbeIsProcessing(postIds);
                    }
                }, 3000);
            }
        },
        error: function () { },
    });
}

function wpbeBackgroundProcessClearCompleteMessage() {
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_background_process_clear_complete_message",
            nonce: WPBE_DATA.ajax_nonce,
        },
        success: function () {
            //
        },
        error: function () {
            //
        },
    });
}

function wpbeStopProcessCheck() {
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_is_processing",
            nonce: WPBE_DATA.ajax_nonce,
        },
        success: function (response) {
            if (response.is_processing === false) {
                wpbeBackgroundProcessClearCompleteMessage();
                wpbeReloadPosts();
                setTimeout(function () {
                    wpbeLoadingProcessingSuccess("Stopped");
                    if (response.total_tasks && response.completed_tasks) {
                        jQuery('.wpbe-processing-loading span[data-type="tasks"]').show();
                        jQuery('.wpbe-processing-loading span[data-type="tasks"]').find('[data-type="total"]').text(response.total_tasks);
                        jQuery('.wpbe-processing-loading span[data-type="tasks"]').find('[data-type="completed"]').text(response.completed_tasks);
                    }
                    wpbeHistoryFilter(null, false);
                }, 500);
            } else {
                setTimeout(function () {
                    wpbeStopProcessCheck();
                }, 3000);
            }
        },
        error: function () { },
    });
}

function wpbeBackgroundProcessClearTasksCount() {
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_background_process_clear_tasks_count",
            nonce: WPBE_DATA.ajax_nonce,
        },
        success: function () { },
        error: function () { },
    });
}

function wpbeBackgroundProcessingCheck() {
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_is_processing",
            nonce: WPBE_DATA.ajax_nonce,
        },
        success: function (response) {
            if (response.is_processing === true) {
                if (response.is_force_stopped) {
                    wpbeStopProcessCheck();
                    wpbeLoadingProcessingStart(WPBE_DATA.background_process.loading_messages.stopping, false);
                } else {
                    wpbeIsProcessing();
                    wpbeLoadingProcessingStart(WPBE_DATA.background_process.loading_messages.processing, true, { total: response.total_tasks, completed: response.completed_tasks });
                }
            }
            if (response.complete_message && response.complete_message.message) {
                wpbeLoadingProcessingComplete(response.complete_message.message, response.complete_message.icon);
                wpbeBackgroundProcessClearCompleteMessage();
            }
        },
        error: function () { },
    });
}

function wpbeBackgroundProcessForceStop() {
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_background_process_force_stop",
            nonce: WPBE_DATA.ajax_nonce,
        },
        success: function (response) {
            if (response.success === true) {
                wpbeStopProcessCheck();
            }
        },
        error: function () { },
    });
}

function wpbeGetBulkEditData() {
    let postIds = wpbeGetPostsChecked();
    let postData = [];

    jQuery("#wpbe-float-side-modal-bulk-edit .wpbe-form-group").each(function () {
        let value;

        if (jQuery(this).find('[data-field="value"]').length > 1) {
            value = jQuery(this)
                .find('[data-field="value"]')
                .map(function () {
                    if (jQuery(this).val() !== "") {
                        return jQuery(this).val();
                    }
                })
                .get();
        } else {
            value = jQuery(this).find('[data-field="value"]').val();
        }

        if (typeof jQuery(this).attr("data-name") != "undefined") {
            if (
                (jQuery.isArray(value) && value.length > 0) ||
                (!jQuery.isArray(value) && value != "" && value != null && typeof value != "undefined") ||
                jQuery.inArray(jQuery(this).find('[data-field="operator"]').val(), ["text_remove_duplicate", "number_clear", "text_clear"]) !== -1
            ) {
                let name = jQuery(this).attr("data-name");
                let type = jQuery(this).attr("data-type");

                if (jQuery(this).find("[data-field=operator]").val() == "text_remove_duplicate") {
                    name = "remove_duplicate";
                    type = "remove_duplicate";
                    value = "trash";
                    if (postIds.length < 1) {
                        postIds = [0];
                    }
                }

                postData.push({
                    name: name,
                    sub_name: jQuery(this).attr("data-sub-name") ? jQuery(this).attr("data-sub-name") : "",
                    type: type,
                    operator: jQuery(this).find('[data-field="operator"]').val(),
                    value: value,
                    replace: jQuery(this).find("[data-field=replace]").val(),
                    sensitive: jQuery(this).find("[data-field=sensitive]").val(),
                    round: jQuery(this).find("[data-field=round]").val(),
                    operation: "bulk_edit",
                });
            }
        }
    });

    if (jQuery(".wpbe-bulk-edit-custom-field-file-item").length) {
        let customFieldFiles = [];
        let containerElement = jQuery(".wpbe-bulk-edit-custom-field-file-item").first().closest(".wpbe-form-group");
        jQuery(".wpbe-bulk-edit-custom-field-file-item").each(function () {
            let fileElement = jQuery(this);
            customFieldFiles.push({
                name: fileElement.find("input.wpbe-bulk-edit-file-name").val(),
                url: fileElement.find("input.wpbe-bulk-edit-file-url").val(),
            });
        });

        if (customFieldFiles.length) {
            postData.push({
                name: containerElement.attr("data-name"),
                sub_name: "",
                type: containerElement.attr("data-type"),
                operator: "",
                value: customFieldFiles,
                operation: "bulk_edit",
            });
        }
    }

    return postData;
}

// function wpbeGetMetaFieldsItem() {
//   console.log("is it in!");
//   jQuery.ajax({
//     url: WPBE_DATA.ajax_url,
//     type: "post",
//     dataType: "json",
//     data: {
//       action: "save_meta_fields",
//       nonce: WPBE_DATA.ajax_nonce,
//     },
//     success: function (response) {
//       console.log("tessssssst");
//     },
//     error: function () {
//       // Handle error
//     },
//   });
// }
