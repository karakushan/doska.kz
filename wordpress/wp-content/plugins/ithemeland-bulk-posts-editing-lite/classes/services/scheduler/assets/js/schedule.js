jQuery(document).ready(function ($) {
    "use strict";

    $(document).on("click", "#wpbe-bulk-edit-form-schedule-bulk", function () {
        $('.wpbe-tab-item[data-content="set_schedule"]').show().trigger("click");
    });

    $(document).on("click", "#wpbe-bulk-edit-form-reset", function () {
        $(".wpbe-set-schedule-enable-schedule").prop("checked", false).change();
        $(".wpbe-set-schedule-run-at").val("now").change();
    });

    $(document).on("change", ".wpbe-set-schedule-enable-schedule", function () {
        let container = $(this).closest("#wpbe-float-side-modal-bulk-edit");
        if (!container.length) {
            return;
        }

        if ($(this).prop("checked") === true) {
            container.find(".wpbe-set-schedule-form").show();
            container.find(".wpbe-set-schedule-run-at").change();
            $(".wpbe-bulk-edit-form-schedule-bulk-edit").show();
            $("#wpbe-bulk-edit-form-do-bulk-edit").hide();
        } else {
            container.find(".wpbe-set-schedule-form").hide();
            $(".wpbe-bulk-edit-form-schedule-bulk-edit").hide();
            $("#wpbe-bulk-edit-form-do-bulk-edit").show();
        }
    });

    $(document).on("change", ".wpbe-set-schedule-run-at", function () {
        let container;
        if ($(this).closest("#wpbe-modal-schedule-edit-job").length) {
            container = $(this).closest("#wpbe-modal-schedule-edit-job");
        } else {
            container = $('.wpbe-tab-content-item[data-content="set_schedule"]');
        }

        container.find(".wpbe-set-schedule-dependent > div").hide();

        let dependentElement = container.find(".wpbe-set-schedule-dependent");
        if (!dependentElement.length) {
            return;
        }

        if ($(this).val() != "") {
            dependentElement.find('div[data-content="revert_changes"]').show();
        } else {
            dependentElement.find('div[data-content="revert_changes"]').hide();
        }

        if ($(this).val() == "later") {
            dependentElement.find('div[data-content="later"]').show();
            dependentElement.find(".wpbe-set-schedule-run-for").change();
        } else {
            dependentElement.find('div[data-content="later"]').hide();
        }

        if ($(this).val() == "now") {
            dependentElement.find('div[data-content="stop_schedule"]').hide();
            dependentElement.find('div[data-content="now"]').show();
        } else {
            dependentElement.find('div[data-content="now"]').hide();
        }
    });

    $(document).on("change", ".wpbe-set-schedule-run-for", function () {
        let container;
        if ($(this).closest("#wpbe-modal-schedule-edit-job").length) {
            container = $(this).closest("#wpbe-modal-schedule-edit-job");
        } else {
            container = $('.wpbe-tab-content-item[data-content="set_schedule"]');
        }

        let dependentElement = container.find(".wpbe-set-schedule-dependent");
        if (!dependentElement.length) {
            return;
        }

        if ($(this).val() == "once") {
            dependentElement.find('div[data-content="once"]').show();
            dependentElement.find('div[data-content="stop_schedule"]').hide();
            dependentElement.find(".wpbe-set-schedule-once-type").change();
        } else {
            if ($(this).val() != "") {
                dependentElement.find('div[data-content="stop_schedule"]').show();
            } else {
                dependentElement.find('div[data-content="stop_schedule"]').hide();
            }
            dependentElement.find('div[data-content="once"]').hide();
            dependentElement.find('div[data-content="specific_date_time"]').hide();
            dependentElement.find('div[data-content="n_hours_later"]').hide();
            dependentElement.find('div[data-content="n_days_later"]').hide();
        }

        if ($(this).val() == "daily") {
            dependentElement.find('div[data-content="daily"]').show();
        } else {
            dependentElement.find('div[data-content="daily"]').hide();
        }

        if ($(this).val() == "weekly") {
            dependentElement.find('div[data-content="weekly"]').show();
        } else {
            dependentElement.find('div[data-content="weekly"]').hide();
        }

        if ($(this).val() == "monthly") {
            dependentElement.find('div[data-content="monthly"]').show();
        } else {
            dependentElement.find('div[data-content="monthly"]').hide();
        }
    });

    $(document).on("change", ".wpbe-set-schedule-once-type", function (e) {
        let container;
        if ($(this).closest("#wpbe-modal-schedule-edit-job").length) {
            container = $(this).closest("#wpbe-modal-schedule-edit-job");
        } else {
            container = $('.wpbe-tab-content-item[data-content="set_schedule"]');
        }

        let dependentElement = container.find(".wpbe-set-schedule-dependent");
        if (!dependentElement.length) {
            return;
        }

        dependentElement.find('div[data-content="specific_date_time"]').hide();
        dependentElement.find('div[data-content="n_hours_later"]').hide();
        dependentElement.find('div[data-content="n_days_later"]').hide();

        if ($(this).val() != "") {
            dependentElement.find('div[data-content="' + $(this).val() + '"]').show();
        }
    });

    $(document).on("click", ".wpbe-schedule-bulk-edit-button", function (e) {
        $(".wpbe-schedule-multiple-bulk-edit-container").slideToggle(100);
    });

    $(document).on("click", '.wpbe-schedule-jobs-list-action-button[data-action="delete"]', function (e) {
        let jobId = $(this).attr("data-id");
        swal(
            {
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wpbe-button wpbe-button-lg wpbe-button-white",
                confirmButtonClass: "wpbe-button wpbe-button-lg wpbe-button-green",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: true,
            },
            function (isConfirm) {
                if (isConfirm) {
                    wpbeScheduleDeleteJob(jobId);
                }
            }
        );
    });

    $(document).on("click", '.wpbe-schedule-jobs-list-action-button[data-action="stop"]', function (e) {
        let jobId = $(this).attr("data-id");
        swal(
            {
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wpbe-button wpbe-button-lg wpbe-button-white",
                confirmButtonClass: "wpbe-button wpbe-button-lg wpbe-button-green",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: true,
            },
            function (isConfirm) {
                if (isConfirm) {
                    wpbeScheduleStopJob(jobId);
                }
            }
        );
    });

    $(document).on("click", ".wpbe-schedule-edit-job-apply-button", function (e) {
        wpbeScheduleUpdateJob($(this).attr("data-id"));
    });

    $(document).on("click", '.wpbe-schedule-jobs-list-action-button[data-action="log"]', function (e) {
        $(".wpbe-schedule-job-log-loading").show();
        $(".wpbe-schedule-job-log-container").html("");
        wpbeScheduleGetJobLog($(this).attr("data-id"));
    });

    $(document).on("click", '.wpbe-schedule-jobs-list-action-button[data-action="show_edit_items"]', function (e) {
        $(".wpbe-schedule-job-edit-items-loading").show();
        $(".wpbe-schedule-job-edit-items-container").html("");
        wpbeScheduleGetEditItems($(this).attr("data-id"));
    });

    $(document).on("click", '.wpbe-schedule-jobs-list-action-button[data-action="edit"]', function (e) {
        $(".wpbe-schedule-edit-job-container").hide();
        $(".wpbe-schedule-edit-job-loading").show();
        wpbeScheduleGetJobData($(this).attr("data-id"));
    });

    $(document).on("click", 'a[data-target="#wpbe-float-side-modal-schedule-jobs"]', function () {
        $.ajax({
            url: WPBE_DATA.ajax_url,
            type: "post",
            dataType: "json",
            data: {
                action: WPBE_SCHEDULE_DATA.identifier + "_get_schedule_jobs",
                nonce: WPBE_DATA.ajax_nonce,
            },
            success: function (response) {
                $("#wpbe-float-side-modal-schedule-jobs table tbody")
                    .html(response.rows)
                    .ready(function () {
                        wpbeSetTipsyTooltip();
                    });
                wpbeScheduleAwaitingCountUpdate(response.awaiting_count);
            },
            error: function () { },
        });
    });

    $(document).on("click", '.wpbe-tab-item[data-content="set_schedule"]', function () {
        wpbeScheduleCurrentTimeUpdate();
    });

    $(document).on("click", ".wpbe-schedule-current-time-update-button", function () {
        jQuery(".wpbe-set-schedule-current-time").css({ color: "#9d9d9d" });
        wpbeScheduleCurrentTimeUpdate();
    });

    wpbeScheduleDateTimePickerInit();
});

function wpbeScheduleCurrentTimeUpdate() {
    jQuery.ajax({
        url: WPBE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: "wpbe_schedule_get_current_time",
            nonce: WPBE_DATA.ajax_nonce,
        },
        success: function (response) {
            jQuery(".wpbe-set-schedule-current-time").html(response.time).css({ color: "#444" });
        },
        error: function () { },
    });
}

function wpbeScheduleStopJob(jobId) {
    wpbeLoadingStart();
    jQuery.ajax({
        url: WPBE_SCHEDULE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: WPBE_SCHEDULE_DATA.identifier + "_schedule_job_stop",
            nonce: WPBE_SCHEDULE_DATA.ajax_nonce,
            job_id: jobId,
        },
        success: function (response) {
            if (response.success) {
                jQuery("#wpbe-float-side-modal-schedule-jobs table tbody")
                    .html(response.rows)
                    .ready(function () {
                        wpbeLoadingSuccess();
                        wpbeScheduleAwaitingCountUpdate(response.awaiting_count);
                    });
            } else {
                wpbeLoadingError();
            }
        },
        error: function () {
            wpbeLoadingError();
        },
    });
}

function wpbeScheduleUpdateJob(jobId) {
    wpbeLoadingStart();

    let container = jQuery("#wpbe-modal-schedule-edit-job .wpbe-set-schedule-form");
    container
        .find(".required:visible")
        .each(function () {
            if (jQuery(this).val() != "") {
                jQuery(this).removeClass("error");
            } else {
                jQuery(this).addClass("error");
            }
        })
        .promise()
        .done(function () {
            if (container.find(".error").length) {
                wpbeLoadingError("Please fill the required fields");
                return;
            }

            let dates = wpbeScheduleGetDatesFromJobForm(container);
            jQuery.ajax({
                url: WPBE_SCHEDULE_DATA.ajax_url,
                type: "post",
                dataType: "json",
                data: {
                    action: WPBE_SCHEDULE_DATA.identifier + "_schedule_update_job",
                    nonce: WPBE_SCHEDULE_DATA.ajax_nonce,
                    job_id: jobId,
                    label: container.find(".wpbe-set-schedule-name").val(),
                    description: container.find(".wpbe-set-schedule-description").val(),
                    run_at: container.find(".wpbe-set-schedule-run-at").val(),
                    run_for: container.find(".wpbe-set-schedule-run-for:visible").length ? container.find(".wpbe-set-schedule-run-for").val() : null,
                    dates: dates,
                    stop_date: container.find(".wpbe-set-schedule-stop-date-time:visible").length ? container.find(".wpbe-set-schedule-stop-date-time").val() : null,
                    revert_date: container.find(".wpbe-set-schedule-revert-date-time:visible").length ? container.find(".wpbe-set-schedule-revert-date-time").val() : null,
                },
                success: function (response) {
                    if (response.success) {
                        if (container.find(".wpbe-set-schedule-run-at").val() == "now") {
                            wpbeLoadingSuccess("Success | Reloading ...");
                            location.reload();
                        } else {
                            wpbeLoadingSuccess();
                            jQuery(".wpbe-schedule-jobs-list-navigation-button>a").trigger("click");
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
        });
}

function wpbeScheduleDeleteJob(jobId) {
    wpbeLoadingStart();
    jQuery.ajax({
        url: WPBE_SCHEDULE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: WPBE_SCHEDULE_DATA.identifier + "_schedule_job_delete",
            nonce: WPBE_SCHEDULE_DATA.ajax_nonce,
            job_id: jobId,
        },
        success: function (response) {
            if (response.success) {
                jQuery("#wpbe-float-side-modal-schedule-jobs table tbody")
                    .html(response.rows)
                    .ready(function () {
                        wpbeLoadingSuccess();
                        wpbeScheduleAwaitingCountUpdate(response.awaiting_count);
                    });
            } else {
                wpbeLoadingError();
            }
        },
        error: function () {
            wpbeLoadingError();
        },
    });
}

function wpbeScheduleGetJobLog(jobId) {
    jQuery.ajax({
        url: WPBE_SCHEDULE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: WPBE_SCHEDULE_DATA.identifier + "_schedule_get_job_log",
            nonce: WPBE_SCHEDULE_DATA.ajax_nonce,
            job_id: jobId,
        },
        success: function (response) {
            jQuery(".wpbe-schedule-job-log-loading").hide();
            if (response.success) {
                jQuery(".wpbe-schedule-job-log-container")
                    .html(response.html)
                    .ready(function () {
                        wpbeFixModalHeight(jQuery("#wpbe-modal-schedule-job-log"));
                        wpbeSetTipsyTooltip();
                    });
            } else {
                jQuery(".wpbe-schedule-job-log-container").html("No data available");
            }
        },
        error: function () {
            jQuery(".wpbe-schedule-job-log-container").html("No data available");
        },
    });
}

function wpbeScheduleGetEditItems(jobId) {
    jQuery.ajax({
        url: WPBE_SCHEDULE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: WPBE_SCHEDULE_DATA.identifier + "_schedule_get_edit_items",
            nonce: WPBE_SCHEDULE_DATA.ajax_nonce,
            job_id: jobId,
        },
        success: function (response) {
            jQuery(".wpbe-schedule-job-edit-items-loading").hide();
            if (response.success && response.edit_items) {
                jQuery(".wpbe-schedule-job-edit-items-container").html(response.edit_items);

                setTimeout(function () {
                    wpbeFixModalHeight(jQuery("#wpbe-modal-schedule-job-edit-items"));
                }, 150);
            }
        },
        error: function () { },
    });
}

function wpbeScheduleGetJobData(jobId) {
    jQuery.ajax({
        url: WPBE_SCHEDULE_DATA.ajax_url,
        type: "post",
        dataType: "json",
        data: {
            action: WPBE_SCHEDULE_DATA.identifier + "_schedule_get_job_data",
            nonce: WPBE_SCHEDULE_DATA.ajax_nonce,
            job_id: jobId,
        },
        success: function (response) {
            jQuery(".wpbe-schedule-edit-job-loading").hide();
            if (response.success && response.job.id) {
                let container = jQuery(".wpbe-schedule-edit-job-container");
                container.find(".wpbe-set-schedule-name").val(response.job.label).change();
                container.find(".wpbe-set-schedule-description").val(response.job.description).change();
                container.find(".wpbe-set-schedule-run-at").val(response.job.run_at).change();
                container.find(".wpbe-set-schedule-run-for").val(response.job.run_for).change();
                if (response.job.run_for != "") {
                    switch (response.job.run_for) {
                        case "once":
                            if (response.job.dates.type) {
                                container.find(".wpbe-set-schedule-once-type").val(response.job.dates.type).change();
                                switch (response.job.dates.type) {
                                    case "specific_date_time":
                                        container.find(".wpbe-set-schedule-once-date-time").val(response.job.dates.date_time).change();
                                        break;
                                    case "n_hours_later":
                                        container.find(".wpbe-set-schedule-once-hours").val(response.job.dates.time).change();
                                        break;
                                    case "n_days_later":
                                        container.find(".wpbe-set-schedule-once-days").val(response.job.dates.days).change();
                                        break;
                                }
                            }
                            break;
                        case "daily":
                            if (response.job.dates.time) {
                                container.find(".wpbe-set-schedule-daily-time").val(response.job.dates.time).change();
                            }
                            break;
                        case "weekly":
                            if (response.job.dates.days && response.job.dates.time) {
                                container.find(".wpbe-set-schedule-weekly-days").val(response.job.dates.days).change();
                                container.find(".wpbe-set-schedule-weekly-time").val(response.job.dates.time).change();
                            }
                            break;
                        case "monthly":
                            if (response.job.dates.days && response.job.dates.time) {
                                container.find(".wpbe-set-schedule-monthly-days").val(response.job.dates.days).change();
                                container.find(".wpbe-set-schedule-monthly-time").val(response.job.dates.time).change();
                            }
                            break;
                    }
                }
                container.find(".wpbe-set-schedule-stop-date-time").val(response.job.stop_date).change();
                container.find(".wpbe-set-schedule-revert-date-time").val(response.job.revert_date).change();
                container.show();

                setTimeout(function () {
                    wpbeFixModalHeight(jQuery("#wpbe-modal-schedule-edit-job"));
                    jQuery(".wpbe-schedule-edit-job-apply-button").attr("data-id", response.job.id).prop("disabled", false);
                }, 150);
            } else {
            }
        },
        error: function () { },
    });
}

var wpbeScheduleAllowTimes = [];
for (let hour = 0; hour < 24; hour++) {
    for (let minute = 0; minute < 60; minute += 5) {
        let formattedHour = ("0" + hour).slice(-2);
        let formattedMinute = ("0" + minute).slice(-2);
        wpbeScheduleAllowTimes.push(formattedHour + ":" + formattedMinute);
    }
}

function wpbeScheduleDateTimePickerInit() {
    if (!jQuery.fn.datetimepicker) {
        return false;
    }

    jQuery(".wpbe-schedule-datetimepicker").datetimepicker("destroy");
    jQuery(".wpbe-schedule-timepicker").datetimepicker("destroy");
    jQuery(".wpbe-schedule-datetimepicker").datetimepicker({
        format: "Y-m-d H:i",
        scrollMonth: false,
        scrollInput: false,
        allowTimes: wpbeScheduleAllowTimes,
    });
    jQuery(".wpbe-schedule-timepicker").datetimepicker({
        datepicker: false,
        format: "H:i",
        scrollMonth: false,
        scrollInput: false,
        allowTimes: wpbeScheduleAllowTimes,
    });
}

function wpbeScheduleAwaitingCountUpdate(awaitingCount) {
    if (awaitingCount && parseInt(awaitingCount) > 0) {
        if (jQuery(".wpbe-jobs-list-button-number").length) {
            jQuery(".wpbe-jobs-list-button-number").text(awaitingCount);
        } else {
            jQuery(".wpbe-schedule-jobs-list-navigation-button").append('<span class="wpbe-jobs-list-button-number">' + awaitingCount + "</span>");
        }
    } else {
        jQuery(".wpbe-jobs-list-button-number").remove();
    }
}

function wpbeScheduleGetDatesFromJobForm(containerElement) {
    let dates = {};

    if (containerElement.find(".wpbe-set-schedule-run-for").val() != "") {
        switch (containerElement.find(".wpbe-set-schedule-run-for").val()) {
            case "once":
                dates["type"] = containerElement.find(".wpbe-set-schedule-once-type").val();
                switch (containerElement.find(".wpbe-set-schedule-once-type").val()) {
                    case "specific_date_time":
                        dates["date_time"] = containerElement.find(".wpbe-set-schedule-once-date-time").val();
                        break;
                    case "n_hours_later":
                        dates["time"] = containerElement.find(".wpbe-set-schedule-once-hours").val();
                        break;
                    case "n_days_later":
                        dates["days"] = containerElement.find(".wpbe-set-schedule-once-days").val();
                        break;
                }
                break;
            case "daily":
                dates["time"] = containerElement.find(".wpbe-set-schedule-daily-time").val();
                break;
            case "weekly":
                dates["days"] = containerElement.find(".wpbe-set-schedule-weekly-days").val();
                dates["time"] = containerElement.find(".wpbe-set-schedule-weekly-time").val();
                break;
            case "monthly":
                dates["days"] = containerElement.find(".wpbe-set-schedule-monthly-days").val();
                dates["time"] = containerElement.find(".wpbe-set-schedule-monthly-time").val();
                break;
        }
    }

    return dates;
}
