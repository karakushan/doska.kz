<?php
/*
Plugin Name: DP Listing Auto Translator (TranslatePress)
Plugin URI: https://freelance.ua/user/st4rc0w2
Description: Автоматический перевод объявлений DirectoryPress (dp_listing) на все языки TranslatePress с использованием Google Gemini. Поддерживает автоперевод заголовков, описаний и SEO-метаданных с сохранением оригинальной структуры контента.
Version: 3.0
Author: St4rc0w
Author URI: https://t.me/st4rpay
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: dp-listing-auto-translator
Requires at least: 5.8
Tested up to: 6.5
Requires PHP: 7.4
*/

if(!defined('ABSPATH')) exit;

/* ======================================================
   НАСТРОЙКИ
====================================================== */
add_action('admin_menu', function(){
    add_options_page(
        'DP Translator Settings',
        'DP Translator',
        'manage_options',
        'dp-translator-settings',
        'dp_translator_settings_page'
    );
});

function dp_translator_settings_page(){
    if(isset($_POST['dp_translator_save'])){
        check_admin_referer('dp_translator_save_nonce');
        update_option('dp_translator_gemini_key', sanitize_text_field($_POST['gemini_api_key']));
        update_option('dp_translator_gemini_model', sanitize_text_field($_POST['gemini_model']));
        update_option('dp_translator_manual_langs', sanitize_text_field($_POST['manual_langs']));
        echo '<div class="updated"><p>Настройки сохранены</p></div>';
    }

    $api_key     = get_option('dp_translator_gemini_key', '');
    $model       = get_option('dp_translator_gemini_model', 'gemini-2.5-flash');
    $manual_langs = get_option('dp_translator_manual_langs', '');
    ?>
    <div class="wrap">
        <h1>DP Translator Settings</h1>
        <form method="POST">
            <?php wp_nonce_field('dp_translator_save_nonce'); ?>
            <p>Google Gemini API Key:<br>
                <input type="text" name="gemini_api_key" value="<?php echo esc_attr($api_key); ?>" style="width:50%">
                <br><small>Получить ключ: <a href="https://aistudio.google.com/app/apikey" target="_blank">aistudio.google.com</a></small>
            </p>
            <p>Модель Gemini:<br>
                <select name="gemini_model" style="width:50%">
                    <option value="gemini-2.5-flash" <?php selected($model,'gemini-2.5-flash'); ?>>Gemini 2.5 Flash (Fast) — рекомендуется</option>
                    <option value="gemini-2.5-flash-lite" <?php selected($model,'gemini-2.5-flash-lite'); ?>>Gemini 2.5 Flash-Lite (Cheaper &amp; Fast)</option>
                    <option value="gemini-2.5-pro" <?php selected($model,'gemini-2.5-pro'); ?>>Gemini 2.5 Pro (лучшее качество)</option>
                </select>
            </p>
            <p>Ручной список языков (en, es, de, tr):<br>
                <input type="text" name="manual_langs" value="<?php echo esc_attr($manual_langs); ?>" style="width:50%">
            </p>
            <p>
                <input type="submit" name="dp_translator_save" class="button button-primary" value="Сохранить">
            </p>
        </form>
    </div>
<hr>
<h2>Автоперевод всех объявлений</h2>

<button id="dp_translate_all_listings" class="button button-primary">
    Перевести все объявления
</button>

<div style="margin-top:15px;width:50%;background:#e5e5e5;">
    <div id="dp_translate_progress"
         style="width:0%;height:20px;background:#2271b1;color:#fff;text-align:center;">
        0%
    </div>
</div>

<div id="dp_translate_status" style="margin-top:10px;"></div>

<script>
jQuery(function($){
    let offset = 0;

    $('#dp_translate_all_listings').on('click', function(){
        offset = 0;
        $('#dp_translate_status').html('Старт перевода...');
        $('#dp_translate_progress').css('width','0%').text('0%');
        runStep();
    });

    function runStep(){
        $.post(ajaxurl, {
            action: 'dp_translate_all_step',
            offset: offset
        }, function(resp){
            if(!resp.success){
                $('#dp_translate_status').append('<div style="color:red">'+resp.data+'</div>');
                return;
            }

            $('#dp_translate_progress')
                .css('width', resp.data.progress + '%')
                .text(resp.data.progress + '%');

            if(resp.data.done){
                $('#dp_translate_status').append('<div><b>Готово</b></div>');
            } else {
                offset++;
                runStep();
            }
        });
    }
});
</script>
<?php
}

/* ======================================================
   ЯЗЫКИ
====================================================== */
function dp_get_translatepress_languages(){
    $langs = [];

    if(function_exists('trp_get_available_languages')){
        $langs = trp_get_available_languages();
    }

    if(empty($langs)){
        $manual = get_option('dp_translator_manual_langs','');
        if($manual){
            foreach(array_map('trim', explode(',',$manual)) as $code){
                if($code){
                    $langs[$code] = strtoupper($code);
                }
            }
        }
    }

    return $langs;
}

/* ======================================================
   ОПРЕДЕЛЕНИЕ ТЕКУЩЕГО ЯЗЫКА
====================================================== */
function dp_get_current_lang(){
    if(function_exists('trp_get_current_language')){
        return trp_get_current_language();
    }

    $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

    if ($path === '') {
        return 'en';
    }

    $segments = explode('/', $path);
    $langs = dp_get_translatepress_languages();

    if (!empty($segments[0]) && isset($langs[$segments[0]])) {
        return $segments[0];
    }

    return 'en';
}

/* ======================================================
   МЕТАБОКСЫ
====================================================== */
add_action('add_meta_boxes', function(){
    add_meta_box(
        'dp_listing_translations',
        'Переводы объявления',
        'dp_listing_translations_meta_box',
        'dp_listing',
        'normal',
        'high'
    );
});

function dp_listing_translations_meta_box($post){
    $langs = dp_get_translatepress_languages();
    $translations = get_post_meta($post->ID,'translations',true) ?: [];

    if(empty($langs)){
        echo "<p>Языки не настроены.</p>";
        return;
    }

    echo '<p>
        <button id="dp_translate_post" class="button button-primary">Перевести на все языки</button>
        <button id="dp_save_translations" class="button button-secondary">Сохранить переводы</button>
    </p>
    <div id="dp_translate_result"></div>
    <div id="dp_trans_tabs"><ul>';

    foreach($langs as $code=>$label){
        echo "<li><a href='#tab_$code'>$label ($code)</a></li>";
    }
    echo '</ul>';

    foreach($langs as $code=>$label){
        $t = $translations[$code] ?? [];
        ?>
        <div id="tab_<?php echo esc_attr($code); ?>">
            <p>Название:
                <input type="text" name="translations[<?php echo $code; ?>][title]"
                       value="<?php echo esc_attr($t['title'] ?? ''); ?>" style="width:100%">
            </p>
            <p>Описание:<br>
                <textarea name="translations[<?php echo $code; ?>][content]" style="width:100%;height:120px"><?php
                    echo esc_textarea($t['content'] ?? '');
                ?></textarea>
            </p>
            <p>SEO title:
                <input type="text" name="translations[<?php echo $code; ?>][seo][title]"
                       value="<?php echo esc_attr($t['seo']['title'] ?? ''); ?>" style="width:100%">
            </p>
            <p>SEO description:<br>
                <textarea name="translations[<?php echo $code; ?>][seo][description]" style="width:100%;height:60px"><?php
                    echo esc_textarea($t['seo']['description'] ?? '');
                ?></textarea>
            </p>
            <p>SEO keywords:
                <input type="text" name="translations[<?php echo $code; ?>][seo][keywords]"
                       value="<?php echo esc_attr($t['seo']['keywords'] ?? ''); ?>" style="width:100%">
            </p>
        </div>
        <?php
    }
    echo '</div>';
    ?>
    <script>
    jQuery(function($){
        if($.ui && $.ui.tabs) $('#dp_trans_tabs').tabs();

        $('#dp_translate_post').on('click', function(e){
            e.preventDefault();
            $('#dp_translate_result').html('Перевод...');
            $.post(ajaxurl,{
                action:'dp_translate_single_post_ajax',
                post_id: <?php echo $post->ID; ?>,
                nonce:'<?php echo wp_create_nonce('dp_translate_single_post_nonce'); ?>'
            },function(resp){
                if(resp.success){
                    $('#dp_translate_result').html('Готово');
                    location.reload();
                } else {
                    $('#dp_translate_result').html(resp.data);
                }
            });
        });

        $('#dp_save_translations').on('click', function(e){
            e.preventDefault();
            let data = $('#dp_trans_tabs').find('input,textarea').serializeArray();
            data.push({name:'action',value:'dp_save_translations_ajax'});
            data.push({name:'nonce',value:'<?php echo wp_create_nonce('dp_save_translations_nonce'); ?>'});
            data.push({name:'post_id',value:<?php echo $post->ID; ?>});

            $.post(ajaxurl,data,function(resp){
                $('#dp_translate_result').html(resp.success ? 'Сохранено' : resp.data);
            });
        });
    });
    </script>
    <?php
}

/* ======================================================
   СОХРАНЕНИЕ
====================================================== */
add_action('wp_ajax_dp_save_translations_ajax', function(){
    check_ajax_referer('dp_save_translations_nonce','nonce');

    $post_id = intval($_POST['post_id']);
    if(!$post_id || !current_user_can('edit_post',$post_id)){
        wp_send_json_error('Нет прав');
    }

    update_post_meta($post_id,'translations',$_POST['translations'] ?? []);
    wp_send_json_success();
});

/* ======================================================
   АВТОПЕРЕВОД ПРИ СОХРАНЕНИИ
====================================================== */
add_action('save_post_dp_listing', function($post_id){
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if(!current_user_can('edit_post',$post_id)) return;

    $api_key = get_option('dp_translator_gemini_key');
    if(!$api_key) return;

    foreach(dp_get_translatepress_languages() as $lang=>$label){
        dp_check_or_generate_translation($post_id,$lang);
    }
});

/* ======================================================
   HASH
====================================================== */
function dp_translation_hash($text){
    return md5(trim(wp_strip_all_tags($text)));
}

/* ======================================================
   ГЕНЕРАЦИЯ ПЕРЕВОДА
====================================================== */
function dp_check_or_generate_translation($post_id,$lang){
    $translations = get_post_meta($post_id,'translations',true) ?: [];
    $hashes = $translations[$lang]['_hashes'] ?? [];

    $original_title   = get_post_field('post_title',$post_id);
    $original_content = get_post_field('post_content',$post_id);
    $seo_title        = get_post_meta($post_id,'_yoast_wpseo_title',true) ?: $original_title;
    $seo_desc         = get_post_meta($post_id,'_yoast_wpseo_metadesc',true)
                        ?: mb_substr(strip_tags($original_content),0,160);

    if(empty($translations[$lang]['title'])){
        $translations[$lang]['title'] = dp_translator_gemini($original_title, $lang);
        $hashes['title'] = dp_translation_hash($original_title);
    }

    if(empty($translations[$lang]['content'])){
        $translations[$lang]['content'] = dp_translator_gemini($original_content, $lang);
        $hashes['content'] = dp_translation_hash($original_content);
    }

    if(empty($translations[$lang]['seo']['title'])){
        $translations[$lang]['seo']['title'] = dp_translator_gemini($seo_title,$lang);
    }

    if(empty($translations[$lang]['seo']['description'])){
        $translations[$lang]['seo']['description'] = dp_translator_gemini($seo_desc,$lang);
    }

    if(empty($translations[$lang]['seo']['keywords'])){
        $translations[$lang]['seo']['keywords'] = dp_generate_keywords_translated(
            [
                'title'   => $translations[$lang]['title'],
                'content' => strip_tags($translations[$lang]['content'])
            ],
            $lang,
            8
        );
    }

    $translations[$lang]['_hashes'] = $hashes;
    update_post_meta($post_id,'translations',$translations);
}

/* ======================================================
   STOPWORDS
====================================================== */
function dp_get_stopwords($lang){
    $common = [
        'and','or','the','with','for','from','that','this','you','your',
        'are','can','will','they','them','their','into','using'
    ];

    $langs = [
        'tr' => ['ve','bir','bu','için','ile','olarak','da','de','en','çok','olan','gibi'],
        'ru' => ['и','в','на','для','как','что','это','или','по','из'],
        'uk' => ['і','в','на','для','як','що','це','або','з','по']
    ];

    return array_merge($common, $langs[$lang] ?? []);
}

/* ======================================================
   KEYWORDS
====================================================== */
function dp_generate_keywords_translated($translation, $lang, $count = 10){
    $text = mb_strtolower(
        ($translation['title'] ?? '') . ' ' . ($translation['content'] ?? ''),
        'UTF-8'
    );

    $text = preg_replace('/[^\p{L}\s]+/u', ' ', $text);
    $text = preg_replace('/\s+/u', ' ', $text);

    $words = explode(' ', trim($text));
    $stopwords = dp_get_stopwords($lang);

    $filtered = [];
    foreach ($words as $word) {
        if (mb_strlen($word,'UTF-8') < 3) continue;
        if (in_array($word,$stopwords,true)) continue;
        $filtered[] = $word;
    }

    $freq = array_count_values($filtered);
    arsort($freq);

    return implode(', ', array_slice(array_keys($freq), 0, $count));
}

/* ======================================================
   FRONT
====================================================== */
add_filter('the_title', function($title, $post_id){
    if (is_admin()) return $title;
    if (get_post_type($post_id) !== 'dp_listing') return $title;

    $lang = dp_get_current_lang();
    $translations = get_post_meta($post_id, 'translations', true);

    return $translations[$lang]['title'] ?? $title;
}, 20, 2);

/* ======================================================
   GEMINI API — замена OpenAI
====================================================== */
function dp_translator_gemini($text, $lang) {
    if (!$text) return '';

    $api_key = get_option('dp_translator_gemini_key');
    if (!$api_key) return '';

    $model = get_option('dp_translator_gemini_model', 'gemini-2.5-flash');

    $prompt = "Translate the following text to language code \"$lang\". "
            . "Preserve the original meaning, style, formatting and SEO value. "
            . "Return only the translated text, no explanations.\n\n$text";

    $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$api_key}";

    $body = json_encode([
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ],
        'generationConfig' => [
            'temperature' => 0
        ]
    ]);

    $response = wp_remote_post($url, [
        'headers' => ['Content-Type' => 'application/json'],
        'body'    => $body,
        'timeout' => 30,
    ]);

    if (is_wp_error($response)) {
        error_log('DP Translator Gemini error: ' . $response->get_error_message());
        return $text;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    // Извлекаем текст из ответа Gemini
    return $data['candidates'][0]['content']['parts'][0]['text'] ?? $text;
}

/* ======================================================
   AJAX SINGLE
====================================================== */
add_action('wp_ajax_dp_translate_single_post_ajax', function(){
    check_ajax_referer('dp_translate_single_post_nonce','nonce');

    $post_id = intval($_POST['post_id']);
    if(!$post_id || !current_user_can('edit_post',$post_id)){
        wp_send_json_error('Нет прав');
    }

    foreach(dp_get_translatepress_languages() as $lang=>$label){
        dp_check_or_generate_translation($post_id,$lang);
    }

    wp_send_json_success();
});

/* ======================================================
   SHORTCODE
====================================================== */
add_shortcode('dp_listing_content', function() {
    global $post;
    if (!$post || $post->post_type !== 'dp_listing') return '';

    $lang = dp_get_current_lang();
    $translations = get_post_meta($post->ID, 'translations', true);

    if (!empty($translations[$lang]['content'])) {
        return wpautop($translations[$lang]['content']);
    }
    return '';
});

/* ======================================================
   AJAX BULK
====================================================== */
add_action('wp_ajax_dp_translate_all_step', function(){
    if (!current_user_can('manage_options')) wp_send_json_error('Нет прав');

    $offset = intval($_POST['offset'] ?? 0);

    $q = new WP_Query([
        'post_type'      => 'dp_listing',
        'posts_per_page' => 1,
        'offset'         => $offset,
        'post_status'    => 'publish',
        'fields'         => 'ids'
    ]);

    if (!$q->have_posts()) {
        wp_send_json_success(['done' => true, 'progress' => 100]);
    }

    $post_id = $q->posts[0];
    foreach (dp_get_translatepress_languages() as $lang => $label){
        dp_check_or_generate_translation($post_id, $lang);
    }

    $total    = wp_count_posts('dp_listing')->publish ?: 1;
    $progress = min(100, round((($offset + 1) / $total) * 100));

    wp_send_json_success(['done' => false, 'progress' => $progress]);
});

/* ======================================================
   ADMIN UI
====================================================== */
add_action('admin_enqueue_scripts', function($hook){
    if (
        ($hook === 'post.php' || $hook === 'post-new.php')
        && get_post_type() === 'dp_listing'
    ) {
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_style(
            'jquery-ui-wp',
            'https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css',
            [],
            '1.13.2'
        );
    }
});