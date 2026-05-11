<?php
/*
Plugin Name: Region Selector Full Stable
Description: Fully working region selector with emoji flags and server-side homepage per subdomain
Version: 7.1
Author: St4rc0w
License: GPL v2 or later
*/

if (!defined('ABSPATH')) exit;

define('RS_COOKIE_VERSION', 14);

/* ========================= HELPERS ========================= */

function rs_flag_emoji($code){
    $code = strtoupper(trim($code));
    if(strlen($code)!==2) return '';
    return mb_chr(127397 + ord($code[0])) . mb_chr(127397 + ord($code[1]));
}

function rs_parse_countries(){
    $raw = get_option('rs_countries');
    if(!$raw) return [];
    $out = [];
    foreach(explode("\n",$raw) as $line){
        $p = array_map('trim', explode('|',$line));
        if(count($p)<3) continue;
        [$code,$name,$url] = $p;
        $parts = parse_url(trim($url));
        if(empty($parts['host'])) continue;
        $out[$parts['host']] = [
            'code'=>strtolower($code),
            'name'=>$name,
            'url'=>rtrim($url,'/'),
            'emoji'=>rs_flag_emoji($code),
        ];
    }
    return $out;
}

function rs_get_cookie_name(){
    $salt = get_option('rs_cookie_salt', time());
    return 'rs_region_v'.RS_COOKIE_VERSION.'_'.$salt;
}

function rs_get_cookie_domain(){
    $host = $_SERVER['HTTP_HOST'];
    // Убираем порт если есть
    $host = preg_replace('/:\d+$/', '', $host);
    
    // Для localhost не ставим domain вообще
    if($host === 'localhost' || strpos($host, '.local') !== false){
        return '';
    }
    
    // Для IP адресов не ставим domain
    if(filter_var($host, FILTER_VALIDATE_IP)){
        return '';
    }
    
    // Получаем основной домен (последние 2 части)
    $parts = explode('.', $host);
    if(count($parts) >= 2){
        return '.' . implode('.', array_slice($parts, -2));
    }
    
    return '';
}

function rs_cookie_exists(){
    $cookie_name = rs_get_cookie_name();
    return isset($_COOKIE[$cookie_name]);
}

/* ========================= ADMIN ========================= */

add_action('admin_menu', function(){
    add_options_page('Region Selector','Region Selector','manage_options','region-selector','rs_admin_page');
});

function rs_admin_page(){
    if(isset($_POST['rs_save'])){
        update_option('rs_countries', wp_unslash($_POST['countries']));
        if(!empty($_POST['homepages'])){
            update_option('rs_homepages', array_map('intval', $_POST['homepages']));
        }
    }
    $countries = get_option('rs_countries');
    $parsed = rs_parse_countries();
    $saved = get_option('rs_homepages',[]);
    $pages = get_pages(['sort_column'=>'post_title']);
    ?>
    <div class="wrap" style="max-width:900px;margin:auto">
        <h1>🌍 Region Selector</h1>
        <form method="post">
            <h2>Languages / Domains</h2>
            <textarea name="countries" rows="8" style="width:100%;font-family:monospace"><?= esc_textarea($countries) ?></textarea>
            <h2 style="margin-top:30px;">Homepage per Domain</h2>
            <?php foreach($parsed as $host => $c): ?>
                <p>
                    <label>
                        <?= esc_html($c['emoji'].' '.$c['name'].' ('.$host.')') ?><br>
                        <select name="homepages[<?= esc_attr($host) ?>]" style="min-width:260px">
                            <option value="0">— default WordPress homepage —</option>
                            <?php foreach($pages as $p): ?>
                                <option value="<?= $p->ID ?>" <?= selected($saved[$host] ?? 0, $p->ID) ?>>
                                    <?= esc_html($p->post_title) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </p>
            <?php endforeach; ?>
            
            <p style="margin-top:30px"><button class="button button-primary" name="rs_save">💾 Save settings</button></p>
        </form>
    </div>
    <?php
}

/* ========================= HANDLE REGION SELECTION ========================= */

add_action('init', function(){
    $cookie_name = rs_get_cookie_name();
    $cookie_domain = rs_get_cookie_domain();
    $countries = rs_parse_countries();

    if(isset($_GET['rs_region']) && $countries){
        $selected_code = strtolower($_GET['rs_region']);
        foreach($countries as $host => $c){
            if($c['code'] === $selected_code){
                $cookie_value = $host;
                $cookie_path = '/';
                $cookie_expire = time() + 2592000; // 30 дней
                
                // Устанавливаем куку
                if($cookie_domain){
                    setcookie($cookie_name, $cookie_value, $cookie_expire, $cookie_path, $cookie_domain);
                } else {
                    setcookie($cookie_name, $cookie_value, $cookie_expire, $cookie_path);
                }
                $_COOKIE[$cookie_name] = $cookie_value;
                
                // Если текущий хост не совпадает с выбранным — редирект
                if(strtolower($_SERVER['HTTP_HOST']) !== strtolower($host)){
                    wp_redirect($c['url'].'/');
                    exit;
                }
                break;
            }
        }
    }
});



/* ========================= TEMPLATE REDIRECT FOR HOMEPAGE ========================= */

add_action('pre_get_posts', function ($q) {

    if (is_admin() || !$q->is_main_query()) {
        return;
    }

    $host = strtolower($_SERVER['HTTP_HOST']);
    $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

    $map = get_option('rs_homepages', []);
    if (empty($map[$host])) return;

    $page_id = (int) $map[$host];
    if ($page_id <= 0) return;

    // язык сабдомена (uk, es, de)
    $host_lang = substr($host, 0, 2);

    /**
     * Разрешаем ТОЛЬКО:
     *   /
     *   /uk   (для uk.adshelppro.com)
     */
    if ($path !== '' && $path !== $host_lang) {
        return;
    }

    // 🔥 ПОДМЕНЯЕМ ГЛАВНУЮ
    $q->set('page_id', $page_id);
    $q->set('post_type', 'page');

    $q->is_page       = true;
    $q->is_singular   = true;
    $q->is_front_page = true;
    $q->is_home       = false;
    $q->is_archive    = false;
    $q->is_404        = false;

});




/* ========================= FRONTEND MODAL ========================= */

add_action('wp_footer', function(){
    // Проверяем куку на сервере
    if(rs_cookie_exists()) return;

    $countries = rs_parse_countries();
    if(!$countries) return;

    $cookie_name = rs_get_cookie_name();
    $cookie_domain = rs_get_cookie_domain();

    ?>
    <!-- Проверка localStorage ДО рендера модального окна -->
    <script>
    if(localStorage.getItem('rs_region_selected')){
        window._rs_region_already_selected = true;
    }
    </script>

    <style>
    #rs-overlay {position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;display:none;align-items:center;justify-content:center}
    #rs-overlay.show {display:flex}
    .rs-modal {background:#fff;padding:30px;border-radius:14px;text-align:center;max-width:520px;width:90%}
    .rs-btn {padding:12px 18px;border-radius:8px;border:none;background:#0073aa;color:#fff;font-size:16px;cursor:pointer;margin:5px}
    .rs-grid {display:flex;flex-wrap:wrap;gap:12px;justify-content:center}
    </style>

    <div id="rs-overlay">
        <div class="rs-modal">
            <h3>Select your region</h3>
            <div class="rs-grid">
                <?php foreach($countries as $host => $c): ?>
                    <button class="rs-btn" 
                        data-host="<?= esc_attr($host) ?>" 
                        data-url="<?= esc_attr($c['url']) ?>" 
                        data-code="<?= esc_attr($c['code']) ?>">
                        <?= esc_html($c['emoji'].' '.$c['name']) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
    (function(){
        const cookieName = "<?= esc_js($cookie_name) ?>";
        const cookieDomain = "<?= esc_js($cookie_domain) ?>";
        const maxAge = 30*24*60*60; // 30 дней
        const localStorageKey = "rs_region_selected";
        const overlay = document.getElementById('rs-overlay');

        // Проверяем localStorage — если уже выбрано, не показываем
        if(window._rs_region_already_selected){
            return;
        }

        // Показываем модальное окно
        overlay.classList.add('show');

        function setCookie(name, value, domain, maxAgeSec){
            let cookieStr = name + "=" + encodeURIComponent(value) + ";path=/;max-age=" + maxAgeSec;
            if(domain){
                cookieStr += ";domain=" + domain;
            }
            document.cookie = cookieStr;
        }

        document.querySelectorAll('.rs-btn').forEach(btn => {
            btn.addEventListener('click', function(){
                const host = this.dataset.host;
                const url  = this.dataset.url;

                // Сохраняем в localStorage как fallback
                localStorage.setItem(localStorageKey, host);

                // Ставим куки
                setCookie(cookieName, host, cookieDomain, maxAge);

                if(host !== location.host){
                    // Редирект на полный URL из настроек
                    location.href = url;
                } else {
                    // Скрываем модалку без редиректа
                    overlay.classList.remove('show');
                }
            });
        });
    })();
    </script>
    <?php
});
/* ========================= SHORTCODE: COUNTRY SELECTOR ========================= */

add_shortcode('rs_region_selector', function($atts){
    $countries = rs_parse_countries();
    if(!$countries) return '';

    $cookie_name = rs_get_cookie_name();
    $cookie_domain = rs_get_cookie_domain();

    $current_host = strtolower($_SERVER['HTTP_HOST']);

    ob_start();

    /* ======================= MOBILE ======================= */
    if ( wp_is_mobile() ) : ?>

       🌍Choose region <select class="rs-region-select" onchange="rsSelectRegionMobile(this)">
            <option value="">Select region</option>
            <?php foreach($countries as $host => $c): ?>
                <option
                    value="<?= esc_attr($host) ?>"
                    data-url="<?= esc_attr($c['url']) ?>"
                    <?= selected($host, $current_host, false) ?>
                >
                    <?= esc_html($c['emoji'].' '.$c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <script>
        function rsSelectRegionMobile(select){
            const option = select.options[select.selectedIndex];
            if(!option || !option.value) return;

            const host = option.value;
            const url  = option.dataset.url;
            const cookieName = "<?= esc_js($cookie_name) ?>";
            const cookieDomain = "<?= esc_js($cookie_domain) ?>";
            const localStorageKey = "rs_region_selected";

            // Сохраняем в localStorage
            localStorage.setItem(localStorageKey, host);

            // Ставим куки
            let cookieStr = cookieName + "=" + encodeURIComponent(host) + ";path=/;max-age=2592000";
            if(cookieDomain){
                cookieStr += ";domain=" + cookieDomain;
            }
            document.cookie = cookieStr;

            if(host !== location.host){
                location.href = url;
            }else{
                location.reload();
            }
        }
        </script>

    <?php
    /* ======================= DESKTOP ======================= */
    else : ?>

        <div class="rs-dropdown">
            <button class="rs-dropdown-btn">🌍 Select region <span class="arrowx">&gt;</span></button>
            <ul class="rs-dropdown-list">
                <?php foreach($countries as $host => $c): ?>
                    <li data-host="<?= esc_attr($host) ?>" data-url="<?= esc_attr($c['url']) ?>">
                        <span class="rs-flag"><?= esc_html($c['emoji']) ?></span>
                        <span class="rs-name"><?= esc_html($c['name']) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <style>
        .rs-dropdown { position: relative; display: inline-block; font-family: sans-serif; }
        .arrowx {
            display: inline-block;
            transform: rotate(90deg) scaleX(0.6); /* вниз */
            font-size: 14px;
            margin-left: 6px;
            font-weight: bolder;
            font-size: 18px;
        }
        .rs-dropdown-btn {
            background: transparent; color:gray; border: none;
            padding: 8px 14px; border-radius: 6px;
            cursor: pointer; font-size: 14px;
            display: flex; align-items: center; gap: 6px;
           
        }
        .rs-dropdown-list {
            position: absolute; top: 100%; left: 0;
            background: #fff; border: 1px solid #ccc;
            border-radius: 6px; margin-top: 4px;
            list-style: none; display: none;
            min-width: 180px; max-height: 250px;
            overflow-y: auto; z-index: 999;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .rs-dropdown-list li {
            padding: 6px 10px; cursor: pointer;
            display: flex; align-items: center; gap: 8px;
        }
        .rs-dropdown-list li:hover { background: #f0f0f0; }
        .rs-flag { font-size: 18px; }
        .rs-name { font-size: 14px; }
        </style>

        <script>
        (function(){
            const dropdown = document.querySelector('.rs-dropdown');
            if(!dropdown) return;

            const btn = dropdown.querySelector('.rs-dropdown-btn');
            const list = dropdown.querySelector('.rs-dropdown-list');
            const cookieName = "<?= esc_js($cookie_name) ?>";
            const cookieDomain = "<?= esc_js($cookie_domain) ?>";
            const localStorageKey = "rs_region_selected";

            btn.addEventListener('click', (e)=>{
                e.stopPropagation();
                list.style.display = list.style.display === 'block' ? 'none' : 'block';
            });

            document.addEventListener('click', e=>{
                if(!dropdown.contains(e.target)) list.style.display = 'none';
            });

            list.querySelectorAll('li').forEach(li=>{
                li.addEventListener('click', ()=>{
                    const host = li.dataset.host;
                    const url  = li.dataset.url;

                    // Сохраняем в localStorage
                    localStorage.setItem(localStorageKey, host);

                    // Ставим куки
                    let cookieStr = cookieName + "=" + encodeURIComponent(host) + ";path=/;max-age=2592000";
                    if(cookieDomain){
                        cookieStr += ";domain=" + cookieDomain;
                    }
                    document.cookie = cookieStr;

                    location.href = url;
                });
            });
        })();
        </script>

    <?php endif;

    return ob_get_clean();
});


add_filter('wp_nav_menu_items', function($items, $args){
    return do_shortcode($items);
}, 10, 2);