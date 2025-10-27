<?php
/**
 * Firebase Diagnostics Tool
 * Проверяет все аспекты инициализации Firebase
 */

// Find WordPress root
$wp_root = dirname(__FILE__);
for ($i = 0; $i < 5; $i++) {
    $wp_root = dirname($wp_root);
    if (file_exists($wp_root . '/wp-load.php')) {
        break;
    }
}

if (!file_exists($wp_root . '/wp-load.php')) {
    die('Could not find wp-load.php');
}

require_once($wp_root . '/wp-load.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    wp_die('Unauthorized');
}

echo "<h1>🔍 Firebase Push Notifications - Полная диагностика</h1>";

// Get current settings
$firebase_enabled = get_option('firebase_enabled', false);
$project_id = get_option('firebase_project_id', '');
$file_path = get_option('firebase_service_account_file_path', '');

?>
<style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    .diagnostic-section { margin: 20px 0; padding: 15px; border-radius: 5px; }
    .pass { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }
    .fail { background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }
    .warn { background: #fff3cd; color: #856404; border-left: 4px solid #ffc107; }
    .info { background: #cfe2ff; color: #084298; border-left: 4px solid #0d6efd; }
    .code { background: #f5f5f5; padding: 10px; border-radius: 3px; font-family: monospace; overflow-x: auto; }
    table { border-collapse: collapse; width: 100%; margin: 10px 0; }
    th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
    th { background: #f8f9fa; }
</style>

<div class="diagnostic-section <?php echo $firebase_enabled ? 'pass' : 'fail'; ?>">
    <h2>1️⃣ Firebase включен?</h2>
    <p><strong>Статус:</strong> <?php echo $firebase_enabled ? '✅ Да' : '❌ Нет'; ?></p>
    <p><strong>Значение в БД:</strong> <code><?php echo var_export($firebase_enabled, true); ?></code></p>
</div>

<div class="diagnostic-section <?php echo !empty($file_path) ? 'pass' : 'warn'; ?>">
    <h2>2️⃣ Путь к файлу Service Account</h2>
    <p><strong>Сохранен?</strong> <?php echo !empty($file_path) ? '✅ Да' : '⚠️ Нет'; ?></p>
    <?php if ($file_path): ?>
        <p><strong>Путь:</strong></p>
        <div class="code"><?php echo esc_html($file_path); ?></div>
        
        <?php 
        $file_exists = file_exists($file_path);
        $file_readable = file_exists($file_path) && is_readable($file_path);
        ?>
        
        <table>
            <tr>
                <th>Проверка</th>
                <th>Результат</th>
                <th>Детали</th>
            </tr>
            <tr class="<?php echo $file_exists ? 'pass' : 'fail'; ?>">
                <td>Файл существует</td>
                <td><?php echo $file_exists ? '✅ Да' : '❌ Нет'; ?></td>
                <td><?php echo $file_exists ? 'Файл найден на диске' : 'Файл не найден на диске'; ?></td>
            </tr>
            <tr class="<?php echo $file_readable ? 'pass' : 'fail'; ?>">
                <td>Файл читаемый</td>
                <td><?php echo $file_readable ? '✅ Да' : '❌ Нет'; ?></td>
                <td><?php echo $file_readable ? 'Разрешено читать файл' : 'Нет прав доступа на чтение'; ?></td>
            </tr>
            <?php if ($file_exists): ?>
                <tr>
                    <td>Размер файла</td>
                    <td><?php echo filesize($file_path); ?> bytes</td>
                    <td><?php echo filesize($file_path) > 1000 ? '✅ Размер нормальный' : '⚠️ Файл может быть пустым'; ?></td>
                </tr>
                <tr>
                    <td>Права доступа</td>
                    <td><code><?php echo substr(sprintf('%o', fileperms($file_path)), -4); ?></code></td>
                    <td><?php 
                        $perms = substr(sprintf('%o', fileperms($file_path)), -4);
                        echo $perms === '0600' ? '✅ Правильные права (0600)' : '⚠️ Неоптимальные права: ' . $perms;
                    ?></td>
                </tr>
            <?php endif; ?>
        </table>
    <?php else: ?>
        <div class="code">Путь не сохранен в БД</div>
    <?php endif; ?>
</div>

<div class="diagnostic-section">
    <h2>3️⃣ Firebase PHP SDK</h2>
    <?php 
    $autoloader = FIREBASE_PUSH_NOTIFICATIONS_PLUGIN_DIR . 'vendor/autoload.php';
    $autoloader_exists = file_exists($autoloader);
    $factory_exists = class_exists('\Kreait\Firebase\Factory');
    ?>
    <table>
        <tr>
            <th>Проверка</th>
            <th>Результат</th>
            <th>Детали</th>
        </tr>
        <tr class="<?php echo $autoloader_exists ? 'pass' : 'fail'; ?>">
            <td>Composer autoloader</td>
            <td><?php echo $autoloader_exists ? '✅ Да' : '❌ Нет'; ?></td>
            <td><?php echo $autoloader_exists ? esc_html($autoloader) : 'Не найден'; ?></td>
        </tr>
        <tr class="<?php echo $factory_exists ? 'pass' : 'fail'; ?>">
            <td>Firebase Factory class</td>
            <td><?php echo $factory_exists ? '✅ Да' : '❌ Нет'; ?></td>
            <td><?php echo $factory_exists ? '\\Kreait\\Firebase\\Factory доступен' : 'Класс не найден'; ?></td>
        </tr>
    </table>
</div>

<div class="diagnostic-section">
    <h2>4️⃣ Firebase Manager</h2>
    <?php 
    $firebase_manager_exists = class_exists('FirebaseManager');
    if ($firebase_manager_exists) {
        $manager = FirebaseManager::getInstance();
        $is_initialized = $manager->isInitialized();
        $status = $manager->getInitializationStatus();
    }
    ?>
    <table>
        <tr>
            <th>Проверка</th>
            <th>Результат</th>
            <th>Детали</th>
        </tr>
        <tr class="<?php echo $firebase_manager_exists ? 'pass' : 'fail'; ?>">
            <td>FirebaseManager class</td>
            <td><?php echo $firebase_manager_exists ? '✅ Да' : '❌ Нет'; ?></td>
            <td><?php echo $firebase_manager_exists ? 'Класс загружен' : 'Класс не найден'; ?></td>
        </tr>
        <?php if ($firebase_manager_exists): ?>
            <tr class="<?php echo $is_initialized ? 'pass' : 'fail'; ?>">
                <td>Firebase инициализирован</td>
                <td><?php echo $is_initialized ? '✅ Да' : '❌ Нет'; ?></td>
                <td><?php echo $is_initialized ? 'Успешно инициализирован' : 'Не инициализирован'; ?></td>
            </tr>
            <tr>
                <td>Service Account configured</td>
                <td><?php echo $status['service_account_configured'] ? '✅' : '❌'; ?></td>
                <td><?php echo $status['service_account_configured'] ? 'Да' : 'Нет'; ?></td>
            </tr>
            <tr>
                <td>Service Account valid</td>
                <td><?php echo $status['service_account_valid'] ? '✅' : '❌'; ?></td>
                <td><?php echo $status['service_account_valid'] ? 'Да' : 'Нет'; ?></td>
            </tr>
            <tr class="<?php echo $status['composer_autoloader_exists'] ? 'pass' : 'fail'; ?>">
                <td>Composer autoloader exists</td>
                <td><?php echo $status['composer_autoloader_exists'] ? '✅' : '❌'; ?></td>
                <td><?php echo $status['composer_autoloader_exists'] ? 'Да' : 'Нет'; ?></td>
            </tr>
            <tr class="<?php echo $status['firebase_classes_available'] ? 'pass' : 'fail'; ?>">
                <td>Firebase classes available</td>
                <td><?php echo $status['firebase_classes_available'] ? '✅' : '❌'; ?></td>
                <td><?php echo $status['firebase_classes_available'] ? 'Да' : 'Нет'; ?></td>
            </tr>
        <?php endif; ?>
    </table>
    
    <?php if ($firebase_manager_exists && !empty($status['details'])): ?>
        <h3>Детали инициализации:</h3>
        <div class="code">
            <?php foreach ($status['details'] as $detail): ?>
                • <?php echo esc_html($detail); ?><br>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div class="diagnostic-section info">
    <h2>📊 Итоговый результат:</h2>
    <?php 
    $all_checks = array(
        'Firebase enabled' => $firebase_enabled,
        'File exists' => $file_exists,
        'File readable' => $file_readable,
        'Autoloader exists' => $autoloader_exists,
        'Factory class' => $factory_exists,
    );
    
    if ($firebase_manager_exists) {
        $all_checks['Firebase initialized'] = $is_initialized;
    }
    
    $passed = count(array_filter($all_checks));
    $total = count($all_checks);
    $percentage = ($passed / $total) * 100;
    ?>
    
    <p><strong>Пройдено проверок:</strong> <?php echo $passed; ?> / <?php echo $total; ?> (<?php echo round($percentage); ?>%)</p>
    
    <?php if ($percentage === 100): ?>
        <div class="pass" style="padding: 20px; text-align: center;">
            <h2 style="margin: 0; color: #155724;">✅ ВСЕ СИСТЕМЫ РАБОТАЮТ!</h2>
            <p>Firebase Push Notifications готов к использованию 🎉</p>
        </div>
    <?php elseif ($percentage >= 80): ?>
        <div class="warn" style="padding: 20px; text-align: center;">
            <h2 style="margin: 0; color: #856404;">⚠️ БОЛЬШИНСТВО ПРОВЕРОК ПРОЙДЕНО</h2>
            <p>Некоторые компоненты требуют внимания</p>
        </div>
    <?php else: ?>
        <div class="fail" style="padding: 20px; text-align: center;">
            <h2 style="margin: 0; color: #721c24;">❌ НЕКОТОРЫЕ ПРОБЛЕМЫ</h2>
            <p>Нужно исправить ошибки для корректной работы</p>
        </div>
    <?php endif; ?>
</div>

<p style="margin-top: 30px; text-align: center;">
    <a href="<?php echo admin_url('admin.php?page=firebase-configuration'); ?>" class="button button-primary">
        ← Вернуться в конфигурацию
    </a>
</p>
