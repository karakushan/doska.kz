const tpAutoTranslator = (function (window, $) {
  //get plugin configuration object.
  const configData = window.extradata;
  const {
    ajax_url: ajaxUrl,
    nonce: nonce,
    license_key: licensekeys,
    extra_class: rtlClass,
    dashboard_url
  } = configData;
  var dict_id = new Array();
  var gettxt_id = new Array();
  var chromeAIStatus = false;
  onLoad();
  async function onLoad() {
    var default_lang = $("#trp-language-select")
      .find("option:first-child")
      .val();
    translationStartTime = new Date();
    translationEndTime = new Date();
    var pageLang = tpap_language_code(default_lang);
    localStorage.setItem("page_lang", pageLang);

    //create strings modal
    createStringsModal("yandex");
    createStringsModal("google");

    //create strings modal
    chromeAIStatus = await checkChromeAILangStatus();
    createStringsModal('chrome-ai-translator');

  }
  async function checkChromeAILangStatus(){
    const status = await ChromeAiTranslator.languageSupportedStatus(localStorage.getItem("page_lang"),localStorage.getItem("language_code"),localStorage.getItem("target_language_name"),localStorage.getItem("source_language_name"));
    return status;
  }
  async function initialize() {

    var default_lang = $("#trp-language-select")
      .find("option:first-child")
      .val();
    var getSelectedlang = $("#trp-language-select").val();

    // Embbed Auto Translate button inside Translatepress editor
    if ($("#tpap-auto-btn").length === 0) {
      addAutoTranslatepressBtn(default_lang, getSelectedlang);
    }

    //append auto translate settings model
    settingsModel();

    //on click on auto tranlsate button
    $("#tpap-auto-btn").on("click", function () {
      openSettingsModel();
    });

    //on click on yandex transllate button
    $("#tpap_yandex_transate_btn").on("click", function () {
      onYandexTranslateClick();
    });

    //on click on google translate button
    $("#tpap_gtranslate_btn").on("click", function () {
      onGoogleTranslateClick();
    });

    $("#chrome-ai-translator_settings_btn").on("click", function () {
      onChromeTranslateClick();
    });

    //on google translation
    $("#google_translate_element").change(function () {
      translation_process();
    });

    // Change from direct binding to delegated event handling
    $(document).on('click', '.tpap-chromeai-disabled-message', async function(e) {
      e.preventDefault(); 
      
      // Close the parent dialog if it's open
      if ($("#tpap-dialog").dialog("instance")) {
          $("#tpap-dialog").dialog('close');
      }

      // Create or update the status dialog
      const $statusDialog = $("#tpap-chromeai-disabled-message-content");
      
      // Ensure content is sanitized before insertion
      $statusDialog.html(chromeAIStatus); 

      initializeClipboard();
      
      // Initialize or reinitialize the dialog
      if ($statusDialog.dialog("instance")) {
          $statusDialog.dialog("open");
      } else {
          $statusDialog.dialog({
              title: 'Chrome AI Translator Status',
              modal: true,
              width: 500,
              draggable: false,
              closeOnEscape: true,
              dialogClass: rtlClass,
              buttons: {
                Cancel: function() {
                      $(this).dialog('close');
                  }
              },
              close: function() {
                  // Cleanup when dialog closes
                  $(this).dialog('destroy');
              }
          });
      }
    });
    
    $(document).on('click','.save_it',onSaveClick)
  }

  function addAutoTranslatepressBtn(default_lang, getSelectedlang) {
    $("#trp-language-switch").before(
      '<div><label class="tpap-steps">Step 1 - Select Language</label></div>'
    );
    $("#trp-next-previous").after(
      '<div><label class="tpap-steps">Step 2 - Click Auto Translate Button</label></div><button id="tpap-auto-btn">Auto Translate</button><div class="tpap-user-message">Translate all plain strings of current page </div>'
    );
    $("#tpap-auto-btn").attr("disabled", true);
    $("#tpap-auto-btn").removeClass("enabled");
    $(".tpap-user-message").html('*To enable the button, change the default language in Step 1.');
  }

  //Enable/disable the automatic translate button
  setInterval(enableAutotranslateButton, 200);
  function enableAutotranslateButton() {
    var default_lang = $("#trp-language-select")
      .find("option:first-child")
      .val();
    var getSelectedlang = $("#trp-language-select").val();

    // Check if the loader is hidden
    var isLoaderHidden = $("#trp-preview-loader").css("display") === "none";
    var isAjaxLoaderHidden = $('#trp-string-saved-ajax-loader').css("display") == "none";
    var newButtonState = default_lang !== getSelectedlang && isLoaderHidden && isAjaxLoaderHidden;

    // Enable or disable the button based on the condition
    if (newButtonState) {
      $("#tpap-auto-btn").addClass("enabled");
      $("#tpap-auto-btn").attr("disabled", false);
      $("#tpap-auto-btn").removeAttr('title');
      $(".tpap-user-message").html('Translate all plain strings of current page.');
    } else {
      $("#tpap-auto-btn").removeClass("enabled");
      $("#tpap-auto-btn").attr("disabled", true);
      $(".tpap-user-message").html('*To enable the button, change the default language in Step 1.');
    }
  }

  function settingsModel() {
    const icons = {
        yandex: extradata['yt_preview'],
        google: extradata['gt_preview'],
        chrome: extradata['ct_preview'],
        docs: extradata['document_preview'],
        info: extradata['information_preview'],
        error: extradata['error_preview']
    };

    const licenseKey = extradata["license_key"];
    const isPro = licenseKey !== "No";

    const licenseLink = dashboard_url + '&tab=license';
    const url = 'https://docs.example.net/docs/automatic-translate-addon-for-translatepress-pro/';

    const TPAP_IMG = (key) => icons[key];
    const DOC_ICON_IMG = `<img src="${TPAP_IMG('docs')}" width="20" alt="Docs">`;

    const rows = [
        {
            name: 'Yandex Translate',
            icon: 'yandex',
            info: 'https://translate.yandex.com/',
            doc: `${url}how-to-translate-your-website-content-automatically-via-yandex/?utm_source=tpa_plugin&utm_medium=inside&utm_campaign=docs&utm_content=popup_yandex`,
            btn: `<button id="tpap_yandex_transate_btn" class="tpap-provider-btn translate" data-translate-engen="yandex">Translate</button>`
        },
        {
            name: 'Google Translate',
            icon: 'google',
            info: 'https://translate.google.com/',
            doc: `${url}how-to-translate-your-website-content-automatically-via-google/?utm_source=tpa_plugin&utm_medium=inside&utm_campaign=docs&utm_content=popup_google_pro`,
            btn: isPro
                ? `<button id="tpap_gtranslate_btn" class="tpap-provider-btn translate" data-translate-engen="google">Translate</button>`
                : `
                    <button id="tpap_gtranslate_btn" class="tpap-provider-btn translate" data-translate-engen="google">Translate</button>
                `
        },
        {
            name: 'Chrome Built-in AI',
            icon: 'chrome',
            info: 'https://developer.chrome.com/docs/ai/translator-api',
            doc: `${url}how-to-translate-your-website-content-automatically-via-chrome-ai/?utm_source=tpa_plugin&utm_medium=inside&utm_campaign=docs&utm_content=popup_chrome_pro`,
            btn: isPro
                ? (
                    chromeAIStatus === true
                        ? `<button id="chrome-ai-translator_settings_btn" class="tpap-provider-btn translate" data-translate-engen="chrome-ai-translator">Translate</button>`
                        : `
                            <button class="tpap-chromeai-disabled-message tpap-provider-btn error">
                                <img src="${TPAP_IMG('error')}" alt="error" style="height:16px; vertical-align:middle; margin-right:5px;">
                                View Error
                            </button>
                            <div id="tpap-chromeai-disabled-message-content" style="display:none;"></div>
                        `
                )
                : `
                    <button id="chrome-ai-translator_settings_btn" class="tpap-provider-btn translate" data-translate-engen="chrome-ai-translator">Translate</button>
                `
        }
    ];

    const rowHTML = rows.map(row => `
        <tr>
            <td class="tpap-provider-name">
                <a href="${row.info}" target="_blank">
                    <img src="${TPAP_IMG(row.icon)}" class="tpap-provider-icon" alt="${row.name}">
                </a>
                ${row.name}
            </td>
            <td>${row.btn}</td>
            <td>
                <a href="${row.doc}" target="_blank" class="tpap-provider-docs-btn">${DOC_ICON_IMG}</a>
            </td>
        </tr>
    `).join('');

    const modelHTML = `
        <div class="tpap-provider-modal" id="tpap-dialog" title="Step 3 - Select Translation Provider" style="display:none;">
            <table class="tpap-provider-table">
                <thead>
                    <tr><th>Name</th><th>Translate</th><th>Docs</th></tr>
                </thead>
                <tbody>${rowHTML}</tbody>
            </table>
        </div>
    `;
    $("body").append(modelHTML);
}

  function openSettingsModel() {
    //Get Dictionary Ids
    $('#trp-string-categories optgroup option[data-group="String List"]').each(
      function (x, el) {
        var data_group = $(this).attr("data-group");
        var database_id = $(this).attr("data-database-id");
        var id = $(this).attr("value");
        var person = database_id;
        dict_id[x] = database_id;
      }
    );

    //Get Gettext Ids
    $('#trp-string-categories optgroup option[data-group="Gettext Strings"]').each(function (x, el) {
        var data_group = $(this).attr("data-group");
        var database_id = $(this).attr("data-database-id");
        var id = $(this).attr("value");
        var person = database_id;
        gettxt_id[x] = database_id;
    });
      var getSelectedlang = $("#trp-language-select").val();
      var getTargetlangName = $("#trp-language-select").find("option:selected").text();
      var getDefaultlangName = $("#trp-language-select").find("option:first-child").text()
      var default_lang = $("#trp-language-select")
        .find("option:first-child")
        .val();
      var defaultLang = tpap_language_code(getSelectedlang);
      localStorage.setItem("language_code", defaultLang);
      localStorage.setItem("language_name", getSelectedlang);
      localStorage.setItem("default_language", default_lang);
      localStorage.setItem("source_language_name", getDefaultlangName);
      localStorage.setItem("target_language_name", getTargetlangName);
      localStorage.setItem("dictionary_id", dict_id);
      localStorage.setItem("gettxt_id", gettxt_id);

    createPopup();
  }

  // create auto translate popup
  function createPopup() {
    var style = $("#tpap-dialog")
      .dialog({
        resizable: false,
        height: "auto",
        width: 400,
        modal: true,
        draggable: false,
        dialogClass: rtlClass,
        buttons: {
          Cancel: function () {
            $(this).dialog("close");
          },
        },
      })
      .css("background-color", "#E4D4D4");
  }

  function onYandexTranslateClick() {
    $('.tpap-preloader-wrap').show();
    $('.modal-body').hide();
    var tr_type = $('#tpap_yandex_transate_btn').attr("data-translate-engen");
    $(".save_it").prop("disabled", true);
    $(".tpap-stats").css("display", "none");
    var default_code = localStorage.getItem("language_code");
    var arr = ['iw','ki', 'en', 'pl', 'af', 'jv', 'no', 'am', 'ar', 'az', 'ba', 'be', 'bg', 'bn', 'bs', 'ca', 'ceb', 'cs', 'cy', 'da', 'de', 'el', 'en', 'eo', 'es', 'et', 'eu', 'fa', 'fi', 'fr', 'ga', 'gd', 'gl', 'gu', 'he', 'hi', 'hr', 'ht', 'hu', 'hy', 'id', 'is', 'it', 'ja', 'jv', 'ka', 'kk', 'km', 'kn', 'ko', 'ky', 'la', 'lb', 'lo', 'lt', 'lv', 'mg', 'mhr', 'mi', 'mk', 'ml', 'mn', 'mr', 'mrj', 'ms', 'mt', 'my', 'ne', 'nl', 'no', 'pa', 'pap', 'pl', 'pt', 'ro', 'ru', 'si', 'sk', 'sl', 'sq', 'sr', 'su', 'sv', 'sw', 'ta', 'te', 'tg', 'th', 'tl', 'tr', 'tt', 'udm', 'uk', 'ur', 'uz', 'vi', 'xh', 'yi', 'zh'];
    if (arr.includes(default_code)) {
      $(".modal-body .translator-widget, .notice-info, .is-dismissible").show();
      addStringsInModal(tr_type);
    } else {
      $('.tpap-preloader-wrap').hide();
      $('.modal-body').show();
      $(".notice-container")
        .addClass("notice inline notice-warning")
        .html("Yandex Automatic Translator Does not support this language.");
      $(
        ".string_container, .choose-lang, .save_it, .notice-info, .is-dismissible"
      ).hide();
      $(".modal-body .translator-widget").hide();
    }
    var style1 = {};
    $("#tpap_yandex_transate_btn").css(style1);
    $("#tpap-dialog").dialog("close");
    $(".yandex-widget-container").fadeIn("slow");
  }

  function onGoogleTranslateClick(){
    $('.tpap-preloader-wrap').show();
    $('.modal-body').hide();
    var tr_type = $('#tpap_gtranslate_btn').attr("data-translate-engen");
    $(".save_it").prop("disabled", true);
    $(".tpap-stats").css("display", "none");
    var default_code = localStorage.getItem("language_code");
    var arr = ['ck', 'sz', 'oc','ki','dz' ,'fu', 'bo', 'as', 'af', 'en', 'pl', 'af', 'jv', 'no', 'am', 'ar', 'az', 'ba', 'be', 'bg', 'bn', 'bs', 'ca', 'ceb', 'cs', 'cy', 'da', 'de', 'el', 'en', 'eo', 'es', 'et', 'eu', 'fa', 'fi', 'fr', 'ga', 'gd', 'gl', 'gu', 'he', 'hi', 'hr', 'ht', 'hu', 'hy', 'id', 'is', 'it', 'ja', 'jv', 'ka', 'kk', 'km', 'kn', 'ko', 'ky', 'la', 'lb', 'lo', 'lt', 'lv', 'mg', 'mhr', 'mi', 'mk', 'ml', 'mn', 'mr', 'mrj', 'ms', 'mt', 'my', 'ne', 'nl', 'no', 'pa', 'pap', 'pl', 'pt', 'ro', 'ru', 'si', 'sk', 'sl', 'sq', 'sr', 'su', 'sv', 'sw', 'ta', 'te', 'tg', 'th', 'tl', 'tr', 'tt', 'udm', 'uk', 'ur', 'uz', 'vi', 'xh', 'yi', 'zh'];
    if (arr.includes(default_code)) {
      $(".modal-body .translator-widget, .notice-info, .is-dismissible").show();
      addStringsInModal(tr_type, default_code);
    }else {
      $('.tpap-preloader-wrap').hide();
      $('.modal-body').show();
      $(".notice-container")
        .addClass("notice inline notice-warning")
        .html("Google Automatic Translator Does not support this language.");
      $(
        ".string_container, .choose-lang, .save_it, .notice-info, .is-dismissible"
      ).hide();
      $(".modal-body .translator-widget").hide();
    }
    var style1 = {};
    $("#tpap_gtranslate_btn").css(style1);
    $("#tpap-dialog").dialog("close");
    $(".google-widget-container").fadeIn("slow");
  }

  function onChromeTranslateClick(){
    $('.tpap-preloader-wrap').show();
    $('.modal-body').hide();
    var tr_type = $('#chrome-ai-translator_settings_btn').attr("data-translate-engen");
    $(".save_it").prop("disabled", true);
    $(".tpap-stats").css("display", "none");
    var default_code = localStorage.getItem("language_code");
    var arr = ['en', 'pl', 'af', 'jv', 'no', 'am', 'ar', 'az', 'ba', 'be', 'bg', 'bn', 'bs', 'ca', 'ceb', 'cs', 'cy', 'da', 'de', 'el', 'en', 'eo', 'es', 'et', 'eu', 'fa', 'fi', 'fr', 'ga', 'gd', 'gl', 'gu', 'he', 'hi', 'hr', 'ht', 'hu', 'hy', 'id', 'is', 'it', 'ja', 'jv', 'ka', 'kk', 'km', 'kn', 'ko', 'ky', 'la', 'lb', 'lo', 'lt', 'lv', 'mg', 'mhr', 'mi', 'mk', 'ml', 'mn', 'mr', 'mrj', 'ms', 'mt', 'my', 'ne', 'nl', 'no', 'pa', 'pap', 'pl', 'pt', 'ro', 'ru', 'si', 'sk', 'sl', 'sq', 'sr', 'su', 'sv', 'sw', 'ta', 'te', 'tg', 'th', 'tl', 'tr', 'tt', 'udm', 'uk', 'ur', 'uz', 'vi', 'xh', 'yi', 'zh'];
    if (arr.includes(default_code)) {
      $(".modal-body .translator-widget, .notice-info, .is-dismissible").show();
      addStringsInModal(tr_type, default_code);
    }else {
      $('.tpap-preloader-wrap').hide();
      $('.modal-body').show();
      $(".notice-container")
        .addClass("notice inline notice-warning")
        .html("Chrome Translator Does not support this language.");
      $(
        ".string_container, .choose-lang, .save_it, .notice-info, .is-dismissible"
      ).hide();
      $(".modal-body .translator-widget").hide();
    }
    var style1 = {};
    $("#chrome-ai-translator_settings_btn").css(style1);
    $("#tpap-dialog").dialog("close");
    $(".chrome-ai-translator-container").fadeIn("slow");
  }

  function calculateAndSaveTranslationTime() {
    const endTime = new Date().getTime();
    const startTime = parseInt(localStorage.getItem("translationStartTime")); 
    const totalTime = (endTime - startTime) / 1000;
    localStorage.setItem("total_translation_time", totalTime);
  }

  function translation_process() {
    var container = $(".google-widget-container");
    container.find(".string_container").scrollTop(0);
    var scrollHeight = container.find(".string_container").get(0).scrollHeight;
    var scrollSpeed = 800;
    if (scrollHeight > scrollSpeed) {
      scrollSpeed = scrollHeight;
    }
    if (scrollHeight !== undefined && scrollHeight > 100) {
      // Store start time as timestamp instead of Date object
      localStorage.setItem("translationStartTime", new Date().getTime());
      container.find(".progress-wrapper").show();
      container.find(".my_translate_progress").fadeIn("slow");
      const progressBar = container.find(".progress-wrapper .progress-bar");
      setTimeout(() => {
        container.find(".string_container").animate(
          {
            scrollTop: scrollHeight + 2000,
          },
          scrollSpeed * 2,
          "linear"
        );
      }, 1000);
      container.find(".string_container").on("scroll", function (e) {
        var scrollTop = e.target.scrollTop;
        var scrollHeight = e.target.scrollHeight;
        var clientHeight = e.target.clientHeight;
        var scrollPercentage = (scrollTop / (scrollHeight - clientHeight)) * 100;
        progressBar.css('width', scrollPercentage + '%');
        progressBar.find('#progressText').text((Math.round(scrollPercentage * 10) / 10).toFixed(1) + '%');
        if (
          $(this).scrollTop() + $(this).innerHeight() + 50 >=
          $(this)[0].scrollHeight
        ) {
          setTimeout(() => {
            calculateAndSaveTranslationTime();
            container.find(".save_it").prop("disabled", false);
            container.find(".tpap-stats").fadeIn("slow");
            container.find(".my_translate_progress").fadeOut("slow");
            container.find(".string_container").stop();
            $("body").css("top", "0");
          }, 1500);
          
        }
      });
      if (
        container.find(".string_container").innerHeight() + 10 >=
        scrollHeight
      ) {
        setTimeout(() => {
          calculateAndSaveTranslationTime();
          container.find(".save_it").prop("disabled", false);
          container.find(".tpap-stats").fadeIn("slow");
          container.find(".my_translate_progress").fadeOut("slow");
          container.find(".string_container").stop();
          $("body").css("top", "0");
        }, 1500);
      }
    } else {
      setTimeout(() => {}, 2000);
    }
  }

  function addStringsInModal(tr_type, default_code) {
    var language_code = localStorage.getItem("language_name");
    var default_lang = localStorage.getItem("default_language");
    var current_page_db_id = localStorage.getItem("dictionary_id");
    var gettxt_id = localStorage.getItem("gettxt_id");
    var request_data = {
      action: "tpap_get_strings",
      data: language_code,
      dictionary_id: current_page_db_id,
      gettxt_id: gettxt_id,
      default_lang: default_lang,
      _ajax_nonce: nonce,
    };
    $.ajax({
      type: "POST",
      url: ajaxUrl,
      dataType: "json",
      data: request_data,
      success: function (response) {
        var plainStrArr = response;
        var strings = new Array();
        var group = new Array();
        var idss = new Array();
        var i = 0;
        plainStrArr.forEach(function (entry) {
          strings[i] = entry.strings;
          group[i] = entry.data_group;
          idss[i] = entry.database_ids;
          i++;
        });
        if (plainStrArr.length > 0) {
          $(".tpap-preloader-wrap").hide();
          $("#tpap-notice-check").show();
          $(".modal-body").show();
          if (tr_type == "google") {
            if (false) {
              $("#tpap-notice-check").remove();
              $(".choose-lang").before(
                '<div class="notice inline notice-info is-dismissible" id="tpap-notice-check" style="">AI Translation For TranslatePress (Pro) - License key is missing! Please add your License key in the settings panel to activate all premium features.</div>'
              );
              return;
            } else {
              $("html").addClass("notranslate");
              googleTranslateElementInit(default_code);
              printStringsInPopup(strings, tr_type, group, idss);
            }
          } else {
            $("html").attr("translate", "no");
            printStringsInPopup(strings, tr_type, group, idss);
          }

          if(tr_type == "chrome-ai-translator"){
            tpapChromeAiInit();
          }
        } else {
          $('.tpap-preloader-wrap').hide();
          $('.modal-body .translator-widget, .notice-info, .is-dismissible').hide();
          $(".modal-body").show();

          if ($(".tpap_custom_model .notice-container").length > 0) {
            $(".notice-container")
              .addClass("notice inline notice-warning")
              .html("There is no plain string available for translations.");
          } else {
            $(".modal-content").append("<div class='notice-container'></div>");
            $(".notice-container")
              .addClass("notice inline notice-warning")
              .html("There is no plain string available for translations.");
          }
          $(".string_container").hide();
          $(".choose-lang").hide();
          $(".save_it").hide();
        }
      },
    });
  }

  function printStringsInPopup(jsonObj, type, group, idss) {
    if (false) {
      return;
    }
    $(".notice-container.notice.inline.notice-warning").remove();
    $(".string_container").show();
    $(".choose-lang").show();
    $(".save_it").show();
    var html = "";
    var totalTChars = 0;
    var index = 1;
    if (jsonObj) {
      for (const key in jsonObj) {
        if (jsonObj.hasOwnProperty(key)) {
          const groups = group[key];
          const element = jsonObj[key];
          if (element.source != "") {
            if (type == "google" || type == "yandex" || type == "chrome-ai-translator") {
              html += `<tr id="${key}"><td>${index}</td><td  class="notranslate source" data-group= "${group[key]}" data-db-id =" ${idss[key]}">${element}</td>`;
            } else {
              if (key > 2500) {
                break;
              }
              html += `<tr id="${key}"><td>${index}</td><td  class="notranslate source" data-group= "${group[key]}" data-db-id =" ${idss[key]}">${element}</td>`;
            }
            if (type == "google" || type == "yandex" || type == "chrome-ai-translator") {
              html += `<td translate ="yes" class = "target translate">${element}</td></tr>`;
            } else {
              html += `<td class ="target translate"></td></tr>`;
            }
            index++;
            totalTChars += element.length;
          }
        }
      }
      $(".tpap-stats").each(function () {
        $(this).find(".totalChars").html(totalTChars);
      });
    }
    $(`#${type}_tpap_string_tbl_body`).html(html);
  }

  $(".tpap_custom_model").find(".notice-dismiss").on("click", function () {
    $(".notice.inline.notice-info.is-dismissible").fadeOut("slow");
  });
  
  $(document).on('click','.tpap_custom_model .modal-content .close',()=>{
    $(".tpap_custom_model").fadeOut("slow");
    location.reload(true);
    tpa_google_Reset();
  })

  // Get the <span> element that closes the modal
  // $(".tpap_custom_model").find(".close").on("click", function () {
  //   $(".tpap_custom_model").fadeOut("slow");
  //   location.reload(true);
  //   tpa_google_Reset();
  // });
  
  window.tpa_google_Reset = () => $('#\\:1\\.container').contents().find('#\\:1\\.restore').click();
  function createStringsModal(widgetType) {
    //Set wrapper, header, and body classes based on widgetType
    let { wrapperCls, headerCls, bodyCls, footerCls } = getWidgetClasses(widgetType);
    let modelHTML = `
    <div id="tpap_strings_model" class="modal tpap_custom_model ${wrapperCls} ${rtlClass}">
      <div class="modal-content">
        <input type="hidden" id="project_id" >
        ${modelHeaderHTML(widgetType, headerCls)}   
        ${modelBodyHTML(widgetType, bodyCls)}   
        ${modelFooterHTML(widgetType, footerCls)}
      </div>
    </div>`;
    $("body").prepend(modelHTML);
  }

  // Get widget classes based on widgetType
  function getWidgetClasses(widgetType) {
    let wrapperCls = "";
    let headerCls = "";
    let bodyCls = "";
    let footerCls = "";
    let wrapperId; 
    switch (widgetType) {
        case "yandex":
            wrapperCls = "yandex-widget-container";
            headerCls = "yandex-widget-header";
            bodyCls = "yandex-widget-body";
            footerCls = "yandex-widget-footer";
            break;

        case "google":
            wrapperCls = "google-widget-container";
            headerCls = "google-widget-header";
            bodyCls = "google-widget-body";
            footerCls = "google-widget-footer";
            break;

        case "chrome-ai-translator":
            wrapperCls = "chrome-ai-translator-container chrome-ai-translator-modal";
            headerCls = "chrome-ai-translator-header";
            bodyCls = "chrome-ai-translator-body";
            footerCls = "chrome-ai-translator-footer";
            break;

        default:
            wrapperCls = "yandex-widget-container";
            headerCls = "yandex-widget-header";
            bodyCls = "yandex-widget-body";
            footerCls = "yandex-widget-footer";
            break;
    }
    return {
        wrapperCls,
        headerCls,
        bodyCls,
        footerCls
      };
  }

  function modelHeaderHTML(widgetType, headerCls) {
    const HTML = `
    <div class="modal-header ${headerCls}">
          <span class="close">&times;</span>
          <h2 class="notranslate">Step 4 - Start Automatic Translation Process</h2>
          <div class="save_btn_cont">
              <button class="notranslate save_it button button-primary" disabled="true">Merge Translation</button>
          </div>
          <div style="display:none" class="tpap-stats hidden">
              Wahooo! You have saved your valuable time via auto translating 
              <strong class="totalChars"></strong> characters using 
              <strong>
                  <a href="https://wordpress.org/plugins/automatic-translate-addon-for-translatepress/#reviews" target="_new">
                      AI Translation For TranslatePress
                  </a>
              </strong>
          </div>
      </div>

      <div class="notice inline notice-info is-dismissible" id="tpap-notice-check">
          Machine translations are not 100% correct. Please verify strings before using on production website.
          <button type="button" class="notice-dismiss">
              <span class="screen-reader-text">Dismiss this notice.</span>
          </button>
      </div>

      <div class="tpap-preloader-wrap">
          <div class="ph-item">
              <div class="ph-col-12">
                  <div class="ph-row">
                      <div class="ph-col-6 big"></div>
                      <div class="ph-col-4 big"></div>
                      <div class="ph-col-2 big"></div>
                      <div class="ph-col-4"></div>
                      <div class="ph-col-8"></div>
                      <div class="ph-col-6"></div>
                      <div class="ph-col-6"></div>
                      <div class="ph-col-12"></div>
                      <div class="ph-col-4"></div>
                      <div class="ph-col-8"></div>
                      <div class="ph-col-6"></div>
                      <div class="ph-col-6"></div>
                      <div class="ph-col-12"></div>
                      <div class="ph-col-4"></div>
                      <div class="ph-col-8"></div>
                      <div class="ph-col-6"></div>
                      <div class="ph-col-6"></div>
                      <div class="ph-col-12"></div>
                      <div class="ph-col-6 big"></div>
                      <div class="ph-col-4 big"></div>
                      <div class="ph-col-2 big"></div>
                  </div>
              </div>
          </div>
      </div>`;
    return HTML;
  }

  function modelBodyHTML(widgetType, bodyCls) {
    const HTML = `
    <div class="modal-body ${bodyCls}">
      <div class="my_translate_progress">
            Automatic translation is in progress....<br/>
            It will take a few minutes, enjoy ☕ coffee in this time!<br/><br/>
            Please do not leave this window or browser tab while the translation is in progress...
              <div class="progress-wrapper">
                <div class="progress-container">
                  <div class="progress-bar" id="myProgressBar">
                    <span id="progressText">0%</span>
                </div>
              </div>
            </div>
          </div>
      ${translatorWidget(widgetType)}
      <div class="string_container">
          <table class="scrolldown" id="stringTemplate" data-widget = "${widgetType}">
              <thead>
                  <th class="notranslate">S.No</th>
                  <th class="notranslate">Source Text</th>
                  <th class="notranslate">Translation</th>
              </thead>
              <tbody id="${widgetType}_tpap_string_tbl_body">
              </tbody>
          </table>
      </div>
      <div class="notice-container"></div>
    </div>
  `;
    return HTML;
  }

  function modelFooterHTML(widgetType, footerCls) {
    const HTML = `
    <div class="modal-footer ${footerCls}">
      <div class="save_btn_cont">
          <button class="notranslate save_it button button-primary" disabled="true">
              Merge Translation
          </button>
      </div>
      <div style="display:none" class="tpap-stats">
          Wahooo! You have saved your valuable time via auto translating
          <strong class="totalChars"></strong> characters using
          <strong>
              <a href="https://wordpress.org/plugins/automatic-translate-addon-for-translatepress/#reviews" target="_new">
                  AI Translation For TranslatePress
              </a>
          </strong>
      </div>
    </div>
  `;
    return HTML;
  }

  function translatorWidget(widgetType) {
    if (widgetType === "yandex") {
      const widgetPlaceholder = '<div id="ytWidget">..Loading</div>';
      return `
            <div class="translator-widget">
            <h3 class="choose-lang">Choose language <span class="dashicons-before dashicons-translation"></span></h3>
                ${widgetPlaceholder}
            </div>
            </br>`;
    } else if (widgetType === "google") {
      const widgetPlaceholder =
        '<div id="google_translate_element"></div>';
      return `
            <div class="translator-widget">
            <h3 class="choose-lang">Choose language <span class="dashicons-before dashicons-translation"></span></h3>
                ${widgetPlaceholder}
            </div>
            </br>`;
    } else if (widgetType === 'chrome-ai-translator'){
      return `
      <div class="translator-widget ${widgetType}">
        <h3 class="choose-lang">Translate Using Chrome AI Translator <span class="dashicons-before dashicons-translation"></span></h3>
        <div id="chrome_ai_translator_element"></div>
      </div>`
    } else {
      return ""; // Return an empty string for non-yandex widget types
    }
  }

  /**
   *
   * This function is used to get language code
   */
  function tpap_language_code(getSelectedlang) {
    var response = getSelectedlang.substring(0, 3);
    var default_code = "";
    var sbstr = getSelectedlang.substring(0, 3);
    if (sbstr == "nb_") {
      default_code = "no";
    } else if (sbstr == "azb") {
      default_code = "azb";
    } else if (sbstr == "ceb") {
      default_code = "ceb";
    } else if (sbstr == "arg") {
      default_code = "arg";
    } else {
      default_code = getSelectedlang.substring(0, 2);
    }
    return default_code;
  }

  function onSaveClick(){
    var translatedObj = [];
    var updatedataObj = [];
    let totalCharacterCount = 0;
    let totalWordCount = 0;
    let totalTranslationTime = localStorage.getItem("total_translation_time");
    $("#stringTemplate tbody tr").each(function (index) {
        
        var provider = $(this).closest('#stringTemplate').data("widget");
        // Get the source text from the current tr
        var sourceText = $(this).find("td.source").text().trim();

        // Calculate the word count
        var sourceWordCount = sourceText ? sourceText.trim().split(/\s+/).filter(word => /[^\p{L}\p{N}]/.test(word)).length : 0; // Split by whitespace

        // Calculate the character count
        var sourceCharacterCount = sourceText.length;
        totalCharacterCount += sourceCharacterCount;
        totalWordCount += sourceWordCount;
        post_id = extradata["post_id"];
        var index = $(this).find("td.source").text();
        var source = $(this).find("td.source").text();
        var target = $(this).find("td.target").text();
        var type = $(this).find("td.source").data("group");
        var db_id = $(this).find("td.source").data("db-id");
        var language_code = localStorage.getItem("language_name");
        var default_lang = localStorage.getItem("default_language");
        var date = new Date().toISOString();
        translatedObj.push({
            "original": source,
            "translated": target,
            "data_group": type,
            "language_code": language_code,
            "id": db_id,
            "status": "2",
            "default_lang": default_lang
        });
        updatedataObj = {
            'language_code': language_code,
            'default_lang': default_lang,
            'provider' : provider,
            'timeTaken' : totalTranslationTime,
            'totalWordCount' : totalWordCount,
            'totalCharacterCount' : totalCharacterCount,
            'date' : date,
            'post_id' : post_id
          };
    });
    
    var data = {
        'action': 'tpap_save_translations',
        'data': JSON.stringify(translatedObj),
        '_ajax_nonce': nonce,
    };
    // Close merge translation function
    jQuery.post(ajaxUrl, data, function (response) {
        var updateData = {
          'action': 'tpap_update_translate_data',
          'data': JSON.stringify(updatedataObj),
          '_ajax_nonce': nonce,
        };

        jQuery.post(ajaxUrl, updateData, function (updateResponse) {
          if (updateResponse.success) {
              console.log("Translation metadata saved successfully.");
          } else {
              
          }
        });
        $(".tpap_custom_model").fadeOut("slow");
        location.reload();
        tpa_google_Reset();
    });
  }

  function googleTranslateElementInit(default_codes) {
    if (false) {
      $("#tpap-notice-check").remove();
      $(".choose-lang").before(
        '<div class="notice inline notice-info is-dismissible" id="tpap-notice-check" style="">AI Translation For TranslatePress (Pro) - License key is missing! Please add your License key in the settings panel to activate all premium features.</div>'
      );
      return;
    }
    var defaultlang = "";
    var default_code = default_codes;
    var page_lang = localStorage.getItem("page_lang");
    switch (default_code) {
      case 'sz':
        defaultlang='szl';
        break;
      case 'ki':
        defaultlang='ky';
        break;
      case "ck":
        defaultlang = "ckb";
        break;
      case "fu":
        defaultlang =  'fur';
      break;
      case "bel":
        defaultlang = "be";
        break;
      case "he":
        defaultlang = "iw";
        break;
      case "snd":
        defaultlang = "sd";
        break;
      case "jv":
        defaultlang = "jw";
        break;
      case "nb":
        defaultlang = "no";
        break;
      case "nn":
        defaultlang = "no";
        break;
      case "zh":
        defaultlang = "zh-CN,zh-TW";
        break;
      default:
        defaultlang = default_code;
        break;
        return defaultlang;
    }

    new google.translate.TranslateElement(
      {
        pageLanguage: page_lang,
        includedLanguages: defaultlang,
        defaultLanguage: defaultlang,
        multilanguagePage: true,
      },
      "google_translate_element"
    );
  }

  function initializeClipboard() {
    const clipboardElements = document.querySelectorAll('.chrome-ai-translator-flags');
    
    const copyClipboard = async (text, startCopyStatus, endCopyStatus) => {
        if (!text || text === "") return;
        
        try {
            if (navigator?.clipboard?.writeText) {
                await navigator.clipboard.writeText(text);
            } else {
              const div = document.createElement('div');
              div.textContent = text;
              document.body.appendChild(div);

              if (window.getSelection && document.createRange) {
                const range = document.createRange();
                range.selectNodeContents(div);

                const selection = window.getSelection();
                selection.removeAllRanges(); // clear any existing selection
                selection.addRange(range);   // select the range
              }

              if (document.execCommand) {
                document.execCommand('copy');
              }
              // document.body.removeChild(textArea);
            }
            
            startCopyStatus();
            setTimeout(endCopyStatus, 800);
        } catch (err) {
            console.error('Error copying text to clipboard:', err);
        }
    };

    clipboardElements.forEach(element => {
        element.classList.add('tapa-tooltip-element');
        
        element.addEventListener('click', (e) => {
            e.preventDefault();
            
            const toolTipExists = element.querySelector('.tapa-tooltip');
            if (toolTipExists) {
                return;
            }
            
            const toolTipElement = document.createElement('span');
            toolTipElement.textContent = "Text to be copied.";
            toolTipElement.className = 'tapa-tooltip';
            element.appendChild(toolTipElement);
            
            copyClipboard(
                element.getAttribute('data-clipboard-text'),
                () => {
                    toolTipElement.classList.add('tapa-tooltip-active');
                },
                () => {
                    setTimeout(() => {
                        toolTipElement.remove();
                    }, 800);
                }
            );
        });
    });
  }

  // oninit
  $(document).ready(function () {
    initialize();
  });
})(window, jQuery);
