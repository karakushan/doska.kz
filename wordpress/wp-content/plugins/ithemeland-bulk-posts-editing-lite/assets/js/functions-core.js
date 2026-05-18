"use strict";

var wpbeOpenFullScreenIcon = '<i class="wpbe-icon-enlarge"></i>';
var wpbeCloseFullScreenIcon = '<i class="wpbe-icon-shrink"></i>';

function wpbeGetUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split("&"),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split("=");

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
}

function wpbeRemoveParamFromURL(url, param) {
    const [path, searchParams] = url.split("?");
    const newSearchParams = searchParams
        ?.split("&")
        .filter((p) => !(p === param || p.startsWith(`${param}=`)))
        .join("&");
    return newSearchParams ? `${path}?${newSearchParams}` : path;
}

function openFullscreen() {
    if (document.documentElement.requestFullscreen) {
        document.documentElement.requestFullscreen();
    } else if (document.documentElement.webkitRequestFullscreen) {
        document.documentElement.webkitRequestFullscreen();
    } else if (document.documentElement.msRequestFullscreen) {
        document.documentElement.msRequestFullscreen();
    }
}

function wpbeDataTableFixSize() {
    if (jQuery("html").attr("dir") == "rtl") {
        jQuery("#wpbe-main").css({
            top: jQuery("#wpadminbar").height() + "px",
            "padding-right": jQuery("#adminmenu:visible").length ? jQuery("#adminmenu").width() + "px" : 0,
        });
    } else {
        jQuery("#wpbe-main").css({
            top: jQuery("#wpadminbar").height() + "px",
            "padding-left": jQuery("#adminmenu:visible").length ? jQuery("#adminmenu").width() + "px" : 0,
        });
    }

    jQuery("#wpbe-loading").css({
        top: jQuery("#wpadminbar").height() + "px",
    });

    let height = parseInt(jQuery(window).height()) - parseInt(jQuery("#wpbe-header").height() + 85);

    jQuery(".wpbe-table").css({
        "max-height": height + "px",
    });
}

function exitFullscreen() {
    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
    }
}

function wpbeFullscreenHandler() {
    if (!document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement) {
        jQuery("#wpbe-full-screen").html(wpbeOpenFullScreenIcon).attr("title", "Full screen");
        jQuery("#adminmenuback, #adminmenuwrap").show();
        jQuery("#wpcontent, #wpfooter").css({ "margin-left": "160px" });
        jQuery(".wpbe-processing-loading").css({ width: "calc(100% - 160px)" });
    } else {
        jQuery("#wpbe-full-screen").html(wpbeCloseFullScreenIcon).attr("title", "Exit Full screen");
        jQuery("#adminmenuback, #adminmenuwrap").hide();
        jQuery("#wpcontent, #wpfooter").css({ "margin-left": 0 });
        jQuery(".wpbe-processing-loading").css({ width: "100%" });
    }

    wpbeDataTableFixSize();
}

function wpbeOpenTab(item) {
    let wpbeTabItem = item;
    let wpbeParentContent = wpbeTabItem.closest(".wpbe-tabs-list");
    let wpbeParentContentID = wpbeParentContent.attr("data-content-id");
    let wpbeDataBox = wpbeTabItem.attr("data-content");
    wpbeParentContent.find("li a.selected").removeClass("selected");
    if (wpbeTabItem.closest(".wpbe-sub-tab").length > 0) {
        wpbeTabItem.closest("li.wpbe-has-sub-tab").find("a").first().addClass("selected");
    } else {
        wpbeTabItem.addClass("selected");
    }

    if (item.closest(".wpbe-tabs-list").attr("data-content-id") && item.closest(".wpbe-tabs-list").attr("data-content-id") == "wpbe-main-tabs-contents") {
        jQuery('.wpbe-tabs-list[data-content-id="wpbe-main-tabs-contents"] li[data-depend] a').not(".wpbe-tab-item").addClass("disabled");
        jQuery('.wpbe-tabs-list[data-content-id="wpbe-main-tabs-contents"] li[data-depend="' + wpbeDataBox + '"] a').removeClass("disabled");
    }

    jQuery("#" + wpbeParentContentID)
        .children("div.selected")
        .removeClass("selected");
    jQuery("#" + wpbeParentContentID + " div[data-content=" + wpbeDataBox + "]").addClass("selected");

    if (item.attr("data-type") === "main-tab") {
        wpbeFilterFormClose();
    }
}

function wpbeFixModalHeight(modal) {
    let footerHeight = 0;
    let search = 0;
    let contentHeight = parseInt(modal.find(".wpbe-modal-content").height());
    let titleHeight = parseInt(modal.find(".wpbe-modal-title").height());
    if (modal.find(".wpbe-modal-footer").length > 0) {
        footerHeight = parseInt(modal.find(".wpbe-modal-footer").height()) + 20;
    }

    if (modal.find(".wpbe-modal-top-search").length > 0) {
        search = parseInt(parseInt(modal.find(".wpbe-modal-top-search").height()) + 14);
    }

    let modalMargin = parseInt((parseInt(jQuery("body").height()) * 20) / 100);
    let bodyHeight = modal.find(".wpbe-modal-body-content").length ? parseInt(parseInt(modal.find(".wpbe-modal-body-content").height()) + 30) : contentHeight;
    let bodyMaxHeight = parseInt(jQuery("body").height()) - (titleHeight + search + footerHeight + modalMargin);

    modal.find(".wpbe-modal-content").css({
        height: parseInt(titleHeight + search + footerHeight + bodyHeight) + "px",
    });
    modal.find(".wpbe-modal-body").css({
        height: parseInt(bodyHeight + search) + "px",
        "max-height": parseInt(bodyMaxHeight + search) + "px",
    });
    modal.find(".wpbe-modal-box").css({
        height: parseInt(titleHeight + search + footerHeight + bodyHeight) + "px",
    });
    modal.attr("data-height-fixed", "true");
}

function wpbeOpenFloatSideModal(targetId) {
    let modal = jQuery(targetId);
    modal.fadeIn(20);
    modal.find(".wpbe-float-side-modal-box").animate(
        {
            right: 0,
        },
        180
    );
}

function wpbeCloseFloatSideModal() {
    // fix conflict with "Woo Invoice Pro" plugin
    jQuery("body").removeClass("_winvoice-modal-open");
    jQuery("._winvoice-modal-backdrop").remove();

    jQuery(".wpbe-float-side-modal-box").animate(
        {
            right: "-80%",
        },
        180
    );
    jQuery(".wpbe-float-side-modal").fadeOut(200);
}

function wpbeCloseModal() {
    // fix conflict with "Woo Invoice Pro" plugin
    jQuery("body").removeClass("_winvoice-modal-open");
    jQuery("._winvoice-modal-backdrop").remove();

    let lastModalOpened = jQuery("#wpbe-last-modal-opened");
    let modal = jQuery(lastModalOpened.val());
    if (lastModalOpened.val() !== "") {
        modal.find(" .wpbe-modal-box").fadeOut();
        modal.fadeOut();
        lastModalOpened.val("");
    } else {
        let lastModal = jQuery(".wpbe-modal:visible").last();
        lastModal.find(".wpbe-modal-box").fadeOut();
        lastModal.fadeOut();
    }

    setTimeout(function () {
        modal.find(".wpbe-modal-box").css({
            height: "auto",
            "max-height": "80%",
        });
        modal.find(".wpbe-modal-body").css({
            height: "auto",
            "max-height": "90%",
        });
        modal.find(".wpbe-modal-content").css({
            height: "auto",
            "max-height": "92%",
        });
    }, 400);
}

function wpbeOpenModal(targetId) {
    let modal = jQuery(targetId);
    modal.fadeIn();
    modal.find(".wpbe-modal-box").fadeIn();
    jQuery("#wpbe-last-modal-opened").val(targetId);

    // set height for modal body
    setTimeout(function () {
        wpbeFixModalHeight(modal);
    }, 150);
}

function wpbeReInitColorPicker() {
    if (jQuery(".wpbe-color-picker").length > 0) {
        jQuery(".wpbe-color-picker").wpColorPicker();
    }
    if (jQuery(".wpbe-color-picker-field").length > 0) {
        jQuery(".wpbe-color-picker-field").wpColorPicker();
    }
}

function wpbeReInitDatePicker() {
    if (jQuery.fn.datetimepicker) {
        jQuery(".wpbe-datepicker-with-dash").datetimepicker("destroy");
        jQuery(".wpbe-datepicker").datetimepicker("destroy");
        jQuery(".wpbe-timepicker").datetimepicker("destroy");
        jQuery(".wpbe-datetimepicker").datetimepicker("destroy");

        jQuery(".wpbe-datepicker").datetimepicker({
            timepicker: false,
            format: "Y/m/d",
            scrollMonth: false,
            scrollInput: false,
        });

        jQuery(".wpbe-datepicker-with-dash").datetimepicker({
            timepicker: false,
            format: "Y-m-d",
            scrollMonth: false,
            scrollInput: false,
        });

        jQuery(".wpbe-timepicker").datetimepicker({
            datepicker: false,
            format: "H:i",
            scrollMonth: false,
            scrollInput: false,
        });

        jQuery(".wpbe-datetimepicker").datetimepicker({
            format: "Y/m/d H:i",
            scrollMonth: false,
            scrollInput: false,
        });
    }
}

function wpbePaginationLoadingStart() {
    jQuery(".wpbe-pagination-loading").show();
}

function wpbePaginationLoadingEnd() {
    jQuery(".wpbe-pagination-loading").hide();
}

function wpbeLoadingStart() {
    jQuery("#wpbe-loading").removeClass("wpbe-loading-error").removeClass("wpbe-loading-success").text("Loading ...").slideDown(300);
}

function wpbeLoadingSuccess(message = "Success !") {
    jQuery("#wpbe-loading").removeClass("wpbe-loading-error").addClass("wpbe-loading-success").text(message).delay(1500).slideUp(200);
}

function wpbeLoadingProcessingStart(message = "Processing ...", stop_button = true, tasks = {}) {
    jQuery("#wpbe-loading").hide();
    jQuery("#wpbe-processing-loading").find('[data-type="loading"]').show();
    if (tasks.total) {
        setTimeout(function () {
            jQuery("#wpbe-processing-loading").find('[data-type="tasks"]').show();
            jQuery("#wpbe-processing-loading").find('[data-type="tasks"]').find('[data-type="total"]').text(tasks.total);
            jQuery("#wpbe-processing-loading")
                .find('[data-type="tasks"]')
                .find('[data-type="completed"]')
                .text(tasks.completed > 0 ? "+" + tasks.completed : tasks.completed);
        }, 10);
    } else {
        jQuery("#wpbe-processing-loading").find('[data-type="tasks"]').hide();
    }
    jQuery("#wpbe-processing-loading").find('[data-type="result_icon"]').hide();
    if (stop_button === true) {
        jQuery("#wpbe-processing-loading").find(".wpbe-processing-loading-stop-button").show();
    } else {
        jQuery("#wpbe-processing-loading").find(".wpbe-processing-loading-stop-button").hide();
    }
    jQuery("#wpbe-processing-loading")
        .find('[data-type="message"]')
        .html(message)
        .ready(function () {
            jQuery("#wpbe-processing-loading").find('[data-type="message"]').show();
            jQuery("#wpbe-processing-loading").fadeIn(150);
        });
}

function wpbeLoadingProcessingPrepare(total_tasks = 0) {
    jQuery("#wpbe-loading").hide();
    jQuery("#wpbe-processing-loading").find(".wpbe-processing-loading-stop-button").hide();
    jQuery("#wpbe-processing-loading").find('[data-type="result_icon"]').hide();
    jQuery("#wpbe-processing-loading").find('[data-type="loading"]').show();
    jQuery("#wpbe-processing-loading").find('[data-type="tasks"]').find('[data-type="total"]').text(0);
    jQuery("#wpbe-processing-loading").find('[data-type="tasks"]').find('[data-type="completed"]').text(0);
    if (total_tasks > 0) {
        jQuery("#wpbe-processing-loading").find('[data-type="tasks"]').show();
        jQuery("#wpbe-processing-loading").find('[data-type="tasks"]').find('[data-type="total"]').text(total_tasks);
        jQuery("#wpbe-processing-loading").find('[data-type="tasks"]').find('[data-type="completed"]').text(0);
    } else {
        jQuery("#wpbe-processing-loading").find('[data-type="tasks"]').hide();
    }
    jQuery("#wpbe-processing-loading")
        .find('[data-type="message"]')
        .html("Your operation is being prepared. Please do not close the current tab.")
        .ready(function () {
            jQuery("#wpbe-processing-loading").find('[data-type="message"]').show();
            jQuery("#wpbe-processing-loading").fadeIn(150);
        });
}

function wpbeCheckSelectAllStatus() {
    if (parseInt(jQuery("input.wpbe-check-item:visible:checkbox:checked").length) === parseInt(jQuery("input.wpbe-check-item:visible:checkbox").length)) {
        if (jQuery('.wpbe-check-item-main[value="all"]').prop("checked") === true) {
            jQuery('.wpbe-check-item-main[value="all"]').prop("checked", true);
        } else {
            jQuery('.wpbe-check-item-main[value="visible"]').prop("checked", true);
        }
        jQuery(".wpbe-table-item-selector-checkbox").prop("checked", true);
    } else {
        jQuery(".wpbe-check-item-main").prop("checked", false);
        jQuery(".wpbe-table-item-selector-checkbox").prop("checked", false);
    }
}

function wpbeLoadingProcessingSuccess(message = "Success") {
    jQuery("#wpbe-processing-loading").find('[data-type="loading"]').hide();
    jQuery("#wpbe-processing-loading").find('[data-type="tasks"]').hide();
    jQuery("#wpbe-processing-loading").find('[data-type="result_icon"]').show();
    jQuery("#wpbe-processing-loading").find(".wpbe-processing-loading-stop-button").hide();
    jQuery("#wpbe-processing-loading").find('[data-type="result_icon"] i').attr("class", "wpbe-icon-check-circle");
    jQuery("#wpbe-processing-loading")
        .find('[data-type="message"]')
        .html(message)
        .ready(function () {
            jQuery("#wpbe-processing-loading").find('[data-type="message"]').show();
            jQuery("#wpbe-processing-loading").delay(2000).fadeOut(150);
        });
}

function wpbeLoadingProcessingComplete(message = "Your changes have been applied", icon = "wpbe-icon-check-circle") {
    jQuery("#wpbe-processing-loading").find('[data-type="loading"]').hide();
    jQuery("#wpbe-processing-loading").find('[data-type="tasks"]').hide();
    jQuery("#wpbe-processing-loading").find('[data-type="result_icon"]').show();
    jQuery("#wpbe-processing-loading").find(".wpbe-processing-loading-stop-button").hide();
    jQuery("#wpbe-processing-loading").find('[data-type="result_icon"] i').attr("class", icon);
    jQuery("#wpbe-processing-loading")
        .find('[data-type="message"]')
        .html(message)
        .ready(function () {
            jQuery("#wpbe-processing-loading").find('[data-type="message"]').show();
            jQuery("#wpbe-processing-loading").delay(3000).fadeOut(150);
        });
}

function wpbeLoadingProcessingError(message = "Error") {
    jQuery("#wpbe-processing-loading").find('[data-type="loading"]').hide();
    jQuery("#wpbe-processing-loading").find('[data-type="tasks"]').hide();
    jQuery("#wpbe-processing-loading").find(".wpbe-processing-loading-stop-button").hide();
    jQuery("#wpbe-processing-loading").find('[data-type="result_icon"]').show();
    jQuery("#wpbe-processing-loading").find('[data-type="result_icon"] i').attr("class", "wpbe-icon-x");
    jQuery("#wpbe-processing-loading")
        .find('[data-type="message"]')
        .html(message)
        .ready(function () {
            jQuery("#wpbe-processing-loading").find('[data-type="message"]').show();
            jQuery("#wpbe-processing-loading").delay(2000).fadeOut(150);
        });
}

function wpbeLoadingError(message = "Error !") {
    jQuery("#wpbe-loading").removeClass("wpbe-loading-success").removeClass("wpbe-loading-processing").addClass("wpbe-loading-error").text(message).delay(1500).slideUp(200);
}

function wpbeSetColorPickerTitle() {
    jQuery(".wpbe-column-manager-right-item .wp-picker-container").each(function () {
        let title = jQuery(this).find(".wpbe-column-manager-color-field input").attr("title");
        jQuery(this).attr("title", title);
        wpbeSetTipsyTooltip();
    });
}

function wpbeFilterFormClose() {
    if (jQuery("#wpbe-filter-form-content").attr("data-visibility") === "visible") {
        jQuery(".wpbe-filter-form-icon").addClass("wpbe-icon-chevron-down").removeClass("wpbe-icon-chevron-up");
        jQuery("#wpbe-filter-form-content").slideUp(200).attr("data-visibility", "hidden");
    }
}

function wpbeSetTipsyTooltip() {
    jQuery("[title]").tipsy({
        html: true,
        arrowWidth: 10, //arrow css border-width * 2, default is 5 * 2
        attr: "data-tipsy",
        cls: null,
        duration: 150,
        offset: 7,
        position: "top-center",
        trigger: "hover",
        onShow: null,
        onHide: null,
    });
}

function wpbeCheckUndoRedoStatus(reverted, history, pro_active_count) {
    var isLiteVersion = jQuery("#wpbe-bulk-edit-undo").hasClass("wpbe-lite-version");

    if (isLiteVersion) {
        return;
    }

    if (reverted) {
        wpbeEnableRedo();
    } else {
        wpbeDisableRedo();
    }
    if (history) {
        wpbeEnableUndo();
    } else {
        wpbeDisableUndo();
    }
}

function wpbeDisableUndo() {
    jQuery("#wpbe-bulk-edit-undo").prop("disabled", true);
}

function wpbeEnableUndo() {
    jQuery("#wpbe-bulk-edit-undo").prop("disabled", false);
}

function wpbeDisableRedo() {
    jQuery("#wpbe-bulk-edit-redo").prop("disabled", true);
}

function wpbeEnableRedo() {
    jQuery("#wpbe-bulk-edit-redo").prop("disabled", false);
}

function wpbeHideSelectionTools() {
    jQuery(".wpbe-bulk-edit-form-selection-tools").hide();
    jQuery("#wpbe-bulk-edit-trash-restore").hide();
}

function wpbeShowSelectionTools() {
    jQuery(".wpbe-bulk-edit-form-selection-tools").show();
    jQuery("#wpbe-bulk-edit-trash-restore").show();
}

function wpbeSetColorPickerTitle() {
    jQuery(".wpbe-column-manager-right-item .wp-picker-container").each(function () {
        let title = jQuery(this).find(".wpbe-column-manager-color-field input").attr("title");
        jQuery(this).attr("title", title);
        wpbeSetTipsyTooltip();
    });
}

function wpbeColumnManagerAddField(fieldName, fieldLabel, action) {
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "html",
        data: {
            action: "wpbe_column_manager_add_field",
            nonce: WPBE_DATA.ajax_nonce,
            field_name: fieldName,
            field_label: fieldLabel,
            field_action: action,
        },
        success: function (response) {
            jQuery(".wpbe-box-loading").hide();
            jQuery(".wpbe-column-manager-added-fields[data-action=" + action + "] .items").append(response);
            fieldName.forEach(function (name) {
                jQuery(".wpbe-column-manager-available-fields[data-action=" + action + "] input:checkbox[data-name=" + name + "]")
                    .prop("checked", false)
                    .closest("li")
                    .attr("data-added", "true")
                    .hide();
            });
            wpbeReInitColorPicker();
            jQuery(".wpbe-column-manager-check-all-fields-btn[data-action=" + action + "] input:checkbox").prop("checked", false);
            jQuery(".wpbe-column-manager-check-all-fields-btn[data-action=" + action + "] span")
                .removeClass("selected")
                .text("Select All");
            setTimeout(function () {
                wpbeSetColorPickerTitle();
            }, 250);
        },
        error: function () { },
    });
}

function wpbeAddMetaKeysManual(meta_key_name) {
    jQuery("#wpbe-add-meta-field-manual").attr("disabled", true);
    wpbeLoadingStart();
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "html",
        data: {
            action: "wpbe_add_meta_keys_manual",
            nonce: WPBE_DATA.ajax_nonce,
            meta_key_name: meta_key_name,
        },
        success: function (response) {
            if (jQuery(".wpbe-meta-fields-items").length) {
                jQuery(".wpbe-meta-fields-items").append(response);
            } else {
                jQuery("#wpbe-meta-fields-items").append(response);
            }
            wpbeLoadingSuccess();
        },
        error: function () {
            wpbeLoadingError();
        },
    });
}

function wpbeCheckFilterFormChanges() {
    let isChanged = false;
    jQuery('#wpbe-filter-form-content [data-field="value"]').each(function () {
        if (jQuery.isArray(jQuery(this).val())) {
            if (jQuery(this).val().length > 0) {
                isChanged = true;
            }
        } else {
            if (jQuery(this).val()) {
                isChanged = true;
            }
        }
    });
    jQuery('#wpbe-filter-form-content [data-field="from"]').each(function () {
        if (jQuery(this).val()) {
            isChanged = true;
        }
    });
    jQuery('#wpbe-filter-form-content [data-field="to"]').each(function () {
        if (jQuery(this).val()) {
            isChanged = true;
        }
    });

    jQuery("#filter-form-changed").val(isChanged);

    if (isChanged === true) {
        jQuery("#wpbe-bulk-edit-reset-filter").show();
    } else {
        jQuery('.wpbe-top-nav-status-filter a[data-status="all"]').addClass("active");
    }
}

function wpbeGetCheckedItem() {
    let itemIds;
    let itemsChecked = jQuery("input.wpbe-check-item:checkbox:checked");
    if (itemsChecked.length > 0) {
        itemIds = itemsChecked
            .map(function (i) {
                return jQuery(this).val();
            })
            .get();
    }

    return itemIds;
}

function wpbeGetTableCount(countPerPage, currentPage, total) {
    currentPage = currentPage ? currentPage : 1;
    let showingTo = parseInt(currentPage * countPerPage);
    let showingFrom = total > 0 ? parseInt(showingTo - countPerPage) + 1 : 0;
    showingTo = showingTo < total ? showingTo : total;
    return "Showing " + showingFrom + " to " + showingTo + " of " + total + " entries";
}

function wpbeSelectAllChecked() {
    return jQuery('.wpbe-check-item-main[value="all"]').prop("checked") === true;
}

function wpbeSelectVisibleChecked() {
    return jQuery('.wpbe-check-item-main[value="visible"]').prop("checked") === true;
}
