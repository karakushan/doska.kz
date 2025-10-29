<?php
/**
 * Advertising Admin Class
 * 
 * Admin interface for advertising system
 */

if (!defined('ABSPATH')) {
    exit;
}

class ClassiAdsPro_Advertising_Admin {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        // Admin-only hooks
        if (is_admin()) {
            // Admin menu
            add_action('admin_menu', array($this, 'add_admin_menu'));
            
            // Settings
            add_action('admin_init', array($this, 'register_settings'));
            
            // Meta boxes
            add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
            add_action('save_post', array($this, 'save_meta_boxes'), 10, 2);
            
            // Custom columns
            add_filter('manage_dp_listing_posts_columns', array($this, 'add_custom_columns'));
            add_action('manage_dp_listing_posts_custom_column', array($this, 'render_custom_columns'), 10, 2);
            
            // Admin filters
            add_action('restrict_manage_posts', array($this, 'add_advertising_filter'));
            add_filter('parse_query', array($this, 'filter_advertising_query'));
        }
        
        // Frontend sorting (works everywhere)
        add_filter('posts_orderby', array($this, 'custom_advertising_orderby'), 10, 2);
        
        // Featured badge for advertised listings
        add_action('directorypress_listing_grid_featured_tag', array($this, 'add_advertising_featured_badge'), 5, 1);
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'directorypress-admin-panel',
            'Настройки рекламирования',
            'Рекламирование',
            'manage_options',
            'classiadspro-advertising',
            array($this, 'render_settings_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('classiadspro_advertising_settings', 'classiadspro_advertising_price_1_day');
        register_setting('classiadspro_advertising_settings', 'classiadspro_advertising_price_3_days');
        register_setting('classiadspro_advertising_settings', 'classiadspro_advertising_price_7_days');
        
        add_settings_section(
            'classiadspro_advertising_prices',
            'Цены на рекламирование',
            array($this, 'render_prices_section'),
            'classiadspro_advertising_settings'
        );
        
        add_settings_field(
            'classiadspro_advertising_price_1_day',
            'Цена за 1 день ($)',
            array($this, 'render_price_field'),
            'classiadspro_advertising_settings',
            'classiadspro_advertising_prices',
            array('field' => 'classiadspro_advertising_price_1_day')
        );
        
        add_settings_field(
            'classiadspro_advertising_price_3_days',
            'Цена за 3 дня ($)',
            array($this, 'render_price_field'),
            'classiadspro_advertising_settings',
            'classiadspro_advertising_prices',
            array('field' => 'classiadspro_advertising_price_3_days')
        );
        
        add_settings_field(
            'classiadspro_advertising_price_7_days',
            'Цена за 7 дней ($)',
            array($this, 'render_price_field'),
            'classiadspro_advertising_settings',
            'classiadspro_advertising_prices',
            array('field' => 'classiadspro_advertising_price_7_days')
        );
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>Настройки рекламирования объявлений</h1>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('classiadspro_advertising_settings');
                do_settings_sections('classiadspro_advertising_settings');
                submit_button();
                ?>
            </form>
            
            <hr>
            
            <h2>Продукты WooCommerce</h2>
            <?php
            if (function_exists('classiadspro_get_advertising_product_ids')) {
                $product_ids = classiadspro_get_advertising_product_ids();
            } else {
                $product_ids = array('1_day' => 0, '3_days' => 0, '7_days' => 0);
            }
            ?>
            <table class="widefat">
                <thead>
                    <tr>
                        <th>Период</th>
                        <th>ID продукта</th>
                        <th>Название продукта</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>1 день</strong></td>
                        <td><?php echo esc_html($product_ids['1_day']); ?></td>
                        <td>
                            <?php 
                            if ($product_ids['1_day'] && ($product = get_post($product_ids['1_day']))) {
                                echo esc_html($product->post_title);
                            } else {
                                echo '<span style="color: #999;">Не создан</span>';
                            }
                            ?>
                        </td>
                        <td><?php echo $product_ids['1_day'] && get_post($product_ids['1_day']) ? '<span style="color:green;">✓ Существует</span>' : '<span style="color:red;">✗ Не найден</span>'; ?></td>
                        <td>
                            <?php if ($product_ids['1_day'] && get_post($product_ids['1_day'])): ?>
                                <a href="<?php echo admin_url('post.php?post=' . $product_ids['1_day'] . '&action=edit'); ?>" class="button button-small">
                                    Редактировать
                                </a>
                            <?php else: ?>
                                <span style="color: #999;">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>3 дня</strong></td>
                        <td><?php echo esc_html($product_ids['3_days']); ?></td>
                        <td>
                            <?php 
                            if ($product_ids['3_days'] && ($product = get_post($product_ids['3_days']))) {
                                echo esc_html($product->post_title);
                            } else {
                                echo '<span style="color: #999;">Не создан</span>';
                            }
                            ?>
                        </td>
                        <td><?php echo $product_ids['3_days'] && get_post($product_ids['3_days']) ? '<span style="color:green;">✓ Существует</span>' : '<span style="color:red;">✗ Не найден</span>'; ?></td>
                        <td>
                            <?php if ($product_ids['3_days'] && get_post($product_ids['3_days'])): ?>
                                <a href="<?php echo admin_url('post.php?post=' . $product_ids['3_days'] . '&action=edit'); ?>" class="button button-small">
                                    Редактировать
                                </a>
                            <?php else: ?>
                                <span style="color: #999;">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>7 дней</strong></td>
                        <td><?php echo esc_html($product_ids['7_days']); ?></td>
                        <td>
                            <?php 
                            if ($product_ids['7_days'] && ($product = get_post($product_ids['7_days']))) {
                                echo esc_html($product->post_title);
                            } else {
                                echo '<span style="color: #999;">Не создан</span>';
                            }
                            ?>
                        </td>
                        <td><?php echo $product_ids['7_days'] && get_post($product_ids['7_days']) ? '<span style="color:green;">✓ Существует</span>' : '<span style="color:red;">✗ Не найден</span>'; ?></td>
                        <td>
                            <?php if ($product_ids['7_days'] && get_post($product_ids['7_days'])): ?>
                                <a href="<?php echo admin_url('post.php?post=' . $product_ids['7_days'] . '&action=edit'); ?>" class="button button-small">
                                    Редактировать
                                </a>
                            <?php else: ?>
                                <span style="color: #999;">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <p>
                <a href="<?php echo admin_url('admin.php?page=classiadspro-advertising&action=create_products'); ?>" class="button button-primary">
                    Создать продукты
                </a>
            </p>
            
            <?php
            if (isset($_GET['action']) && $_GET['action'] === 'create_products') {
                $this->create_woocommerce_products();
            }
            ?>
        </div>
        <?php
    }
    
    /**
     * Render prices section
     */
    public function render_prices_section() {
        echo '<p>Установите цены для разных периодов рекламирования</p>';
    }
    
    /**
     * Render price field
     * 
     * @param array $args Field arguments
     */
    public function render_price_field($args) {
        $field = $args['field'];
        $value = get_option($field, 0);
        ?>
        <input type="number" name="<?php echo esc_attr($field); ?>" value="<?php echo esc_attr($value); ?>" step="1" min="0" class="regular-text">
        <?php
    }
    
    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'classiadspro_advertising_meta',
            'Рекламирование',
            array($this, 'render_meta_box'),
            'dp_listing',
            'side',
            'high'
        );
    }
    
    /**
     * Render meta box
     * 
     * @param WP_Post $post Post object
     */
    public function render_meta_box($post) {
        wp_nonce_field('classiadspro_advertising_meta', 'classiadspro_advertising_meta_nonce');
        
        $is_advertised = get_post_meta($post->ID, '_is_advertised', true);
        $start_date = get_post_meta($post->ID, '_advertising_start_date', true);
        $end_date = get_post_meta($post->ID, '_advertising_end_date', true);
        
        ?>
        <div class="classiadspro-advertising-meta">
            <p>
                <label>
                    <input type="checkbox" name="is_advertised" value="1" <?php checked($is_advertised, 1); ?>>
                    Рекламировать объявление
                </label>
            </p>
            
            <p>
                <label>Дата начала:</label><br>
                <input type="datetime-local" name="advertising_start_date" value="<?php echo $start_date ? esc_attr(date('Y-m-d\TH:i', $start_date)) : ''; ?>" class="widefat">
            </p>
            
            <p>
                <label>Дата окончания:</label><br>
                <input type="datetime-local" name="advertising_end_date" value="<?php echo $end_date ? esc_attr(date('Y-m-d\TH:i', $end_date)) : ''; ?>" class="widefat">
            </p>
            
            
            <?php if ($is_advertised && $end_date): ?>
            <p>
                <strong>Статус:</strong>
                <?php if ($end_date >= current_time('timestamp')): ?>
                    <span style="color: green;">Активно до <?php echo date('d.m.Y H:i', $end_date); ?></span>
                <?php else: ?>
                    <span style="color: red;">Истекло <?php echo date('d.m.Y H:i', $end_date); ?></span>
                <?php endif; ?>
            </p>
            <?php endif; ?>
        </div>
        <?php
    }
    
    /**
     * Save meta boxes
     * 
     * @param int $post_id Post ID
     * @param WP_Post $post Post object
     */
    public function save_meta_boxes($post_id, $post) {
        // Check nonce
        if (!isset($_POST['classiadspro_advertising_meta_nonce']) || !wp_verify_nonce($_POST['classiadspro_advertising_meta_nonce'], 'classiadspro_advertising_meta')) {
            return;
        }
        
        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Check post type
        if ($post->post_type !== 'dp_listing') {
            return;
        }
        
        // Save is_advertised
        $is_advertised = isset($_POST['is_advertised']) ? 1 : 0;
        update_post_meta($post_id, '_is_advertised', $is_advertised);
        
        // Save dates
        if (isset($_POST['advertising_start_date']) && !empty($_POST['advertising_start_date'])) {
            $start_date = strtotime($_POST['advertising_start_date']);
            update_post_meta($post_id, '_advertising_start_date', $start_date);
        }
        
        if (isset($_POST['advertising_end_date']) && !empty($_POST['advertising_end_date'])) {
            $end_date = strtotime($_POST['advertising_end_date']);
            update_post_meta($post_id, '_advertising_end_date', $end_date);
        }
    }
    
    /**
     * Add custom columns
     * 
     * @param array $columns Columns array
     * @return array Modified columns
     */
    public function add_custom_columns($columns) {
        $columns['advertising_status'] = 'Реклама';
        return $columns;
    }
    
    /**
     * Render custom columns
     * 
     * @param string $column Column name
     * @param int $post_id Post ID
     */
    public function render_custom_columns($column, $post_id) {
        if ($column === 'advertising_status') {
            if (classiadspro_is_listing_advertised($post_id)) {
                $end_date = get_post_meta($post_id, '_advertising_end_date', true);
                echo '<span style="color: green;">✓ До ' . date('d.m.Y', $end_date) . '</span>';
            } else {
                echo '—';
            }
        }
    }
    
    /**
     * Create WooCommerce products
     */
    private function create_woocommerce_products() {
        if (!class_exists('WC_Product')) {
            echo '<div class="notice notice-error"><p>WooCommerce не активен</p></div>';
            return;
        }
        
        $prices = classiadspro_get_advertising_prices();
        $periods = array(
            '1_day' => 'Рекламирование объявления (1 день)',
            '3_days' => 'Рекламирование объявления (3 дня)',
            '7_days' => 'Рекламирование объявления (7 дней)',
        );
        
        foreach ($periods as $period => $title) {
            $existing_id = get_option('classiadspro_advertising_product_' . $period, 0);
            
            // Check if product already exists
            if ($existing_id && get_post($existing_id)) {
                continue;
            }
            
            // Create new product
            $product = new WC_Product_Simple();
            $product->set_name($title);
            $product->set_regular_price($prices[$period]);
            $product->set_virtual(true);
            $product->set_sold_individually(true);
            $product->set_catalog_visibility('hidden');
            $product->save();
            
            update_option('classiadspro_advertising_product_' . $period, $product->get_id());
        }
        
        echo '<div class="notice notice-success"><p>Продукты успешно созданы!</p></div>';
    }
    
    /**
     * Add advertising filter dropdown
     */
    public function add_advertising_filter() {
        global $typenow;
        
        if ($typenow === 'dp_listing') {
            $current_filter = isset($_GET['advertising_status']) ? $_GET['advertising_status'] : '';
            ?>
            <select name="advertising_status">
                <option value="">Все объявления</option>
                <option value="advertised" <?php selected($current_filter, 'advertised'); ?>>Рекламируемые</option>
                <option value="not_advertised" <?php selected($current_filter, 'not_advertised'); ?>>Не рекламируемые</option>
                <option value="expired" <?php selected($current_filter, 'expired'); ?>>Реклама истекла</option>
            </select>
            <?php
        }
    }
    
    /**
     * Filter query based on advertising status
     * 
     * @param WP_Query $query Query object
     */
    public function filter_advertising_query($query) {
        global $pagenow, $typenow;
        
        if ($pagenow === 'edit.php' && $typenow === 'dp_listing' && isset($_GET['advertising_status']) && !empty($_GET['advertising_status'])) {
            $advertising_status = $_GET['advertising_status'];
            
            $meta_query = array();
            
            switch ($advertising_status) {
                case 'advertised':
                    $meta_query[] = array(
                        'key' => '_is_advertised',
                        'value' => '1',
                        'compare' => '='
                    );
                    $meta_query[] = array(
                        'key' => '_advertising_end_date',
                        'value' => current_time('timestamp'),
                        'compare' => '>'
                    );
                    break;
                    
                case 'not_advertised':
                    $meta_query[] = array(
                        'relation' => 'OR',
                        array(
                            'key' => '_is_advertised',
                            'compare' => 'NOT EXISTS'
                        ),
                        array(
                            'key' => '_is_advertised',
                            'value' => '0',
                            'compare' => '='
                        )
                    );
                    break;
                    
                case 'expired':
                    $meta_query[] = array(
                        'key' => '_is_advertised',
                        'value' => '1',
                        'compare' => '='
                    );
                    $meta_query[] = array(
                        'key' => '_advertising_end_date',
                        'value' => current_time('timestamp'),
                        'compare' => '<='
                    );
                    break;
            }
            
            if (!empty($meta_query)) {
                $query->set('meta_query', $meta_query);
            }
        }
    }
    
    /**
     * Sort advertised listings first in directory queries
     * 
     * @param WP_Query $query Query object
     */
    public function sort_advertised_listings_first($query) {
        // Only apply to frontend main queries for listings
        if (!is_admin() && $query->is_main_query() && isset($query->query_vars['post_type']) && $query->query_vars['post_type'] === 'dp_listing') {
        
            
            // Set meta key and orderby for sorting (without filtering)
            $query->set('meta_key', '_is_advertised');
            $query->set('orderby', array(
                'meta_value_num' => 'DESC',
                'date' => 'DESC'
            ));
        }
    }
    
    /**
     * Sort advertised listings first in DirectoryPress queries
     * 
     * @param array $args Query arguments
     * @return array Modified query arguments
     */
    public function sort_advertised_listings_first_args($args) {
        // Only apply to frontend directory queries
        if (!is_admin() && isset($args['post_type']) && $args['post_type'] === 'dp_listing') {
            
            
            // Set meta key and orderby for sorting (without filtering)
            $args['meta_key'] = '_is_advertised';
            $args['orderby'] = array(
                'meta_value_num' => 'DESC',
                'date' => 'DESC'
            );
        }
        
        return $args;
    }
    
    /**
     * Custom orderby for advertising status
     * 
     * @param string $orderby Current ORDER BY clause
     * @param WP_Query $query Query object
     * @return string Modified ORDER BY clause
     */
    public function custom_advertising_orderby($orderby, $query) {
        // Only apply to frontend directory queries
        if (!is_admin() && isset($query->query_vars['post_type']) && $query->query_vars['post_type'] === 'dp_listing') {
            
            // Create custom ORDER BY that prioritizes advertised listings
            $custom_orderby = "
                CASE 
                    WHEN EXISTS (
                        SELECT 1 FROM {$GLOBALS['wpdb']->postmeta} pm1 
                        WHERE pm1.post_id = {$GLOBALS['wpdb']->posts}.ID 
                        AND pm1.meta_key = '_is_advertised' 
                        AND pm1.meta_value = '1'
                    ) AND EXISTS (
                        SELECT 1 FROM {$GLOBALS['wpdb']->postmeta} pm2 
                        WHERE pm2.post_id = {$GLOBALS['wpdb']->posts}.ID 
                        AND pm2.meta_key = '_advertising_end_date' 
                        AND CAST(pm2.meta_value AS UNSIGNED) > " . current_time('timestamp') . "
                    )
                    THEN 1
                    ELSE 0
                END DESC,
                " . $orderby;
            
            return $custom_orderby;
        }
        
        return $orderby;
    }
    
    /**
     * Add featured badge for advertised listings
     * 
     * @param object $listing Listing object
     */
    public function add_advertising_featured_badge($listing) {
        // Check if listing is advertised and not expired
        if (function_exists('classiadspro_is_listing_advertised') && classiadspro_is_listing_advertised($listing->post->ID)) {
            // Get the same styling as the original featured tag
            $feature_tag_style = (isset($listing->listing_has_featured_tag_style) && !empty($listing->listing_has_featured_tag_style)) ? $listing->listing_has_featured_tag_style : $listing->listing_post_style;
            
            $feature_tag = '';
            if($feature_tag_style == 1){
                $feature_tag = '<span class="has_featured-tag-1">'.esc_html__('Featured', 'classiadspro').'</span>';
            }elseif($feature_tag_style == 2){
                $feature_tag = '<span class="has_featured-tag-2">'.esc_html__('Featured', 'classiadspro').'</span>';
            }elseif($feature_tag_style == 3){
                $feature_tag = '<span class="has_featured-tag-3"><i class="dicode-material-icons dicode-material-icons-star"></i></span>';
            }elseif($feature_tag_style == 4){
                $feature_tag = '<span class="has_featured-tag-4"><i class="dicode-material-icons dicode-material-icons-star"></i></span>';
            }elseif($feature_tag_style == 5){
                $feature_tag = '<span class="has_featured-tag-5">'.esc_html__('Featured', 'classiadspro').'</span>';
            }elseif($feature_tag_style == 6){
                $feature_tag = '<span class="has_featured-tag-6">'.esc_html__('Featured', 'classiadspro').'</span>';
            }elseif($feature_tag_style == 7){
                $feature_tag = '<span class="has_featured-tag-7"><i class="dicode-material-icons dicode-material-icons-star"></i></span>';
            }elseif($feature_tag_style == 8){
                $feature_tag = '<span class="has_featured-tag-8"><i class="dicode-material-icons dicode-material-icons-star"></i></span>';
            }elseif($feature_tag_style == 9){
                $feature_tag = '<span class="has_featured-tag-9"><i class="dicode-material-icons dicode-material-icons-star"></i></span>';
            }elseif($feature_tag_style == 10){
                $feature_tag = '<span class="has_featured-tag-10">'.esc_html__('Featured', 'classiadspro').'</span>';
            }elseif($feature_tag_style == 11){
                $feature_tag = '<span class="has_featured-tag-11">'.esc_html__('Featured', 'classiadspro').'</span>';
            }elseif($feature_tag_style == 12){
                $feature_tag = '<span class="has_featured-tag-12">'.esc_html__('Featured', 'classiadspro').'</span>';
            }elseif($feature_tag_style == 13){
                $feature_tag = '<span class="has_featured-tag-13">'.esc_html__('Featured', 'classiadspro').'</span>';
            }elseif($feature_tag_style == 14){
                $feature_tag = '<span class="has_featured-tag-14">'.esc_html__('Featured', 'classiadspro').'</span>';
            }elseif($feature_tag_style == 15){
                $feature_tag = '<span class="has_featured-tag-15">'.esc_html__('Featured', 'classiadspro').'</span>';
            }elseif($feature_tag_style == 16){
                $feature_tag = '<span class="has_featured-tag-15">'.esc_html__('Featured', 'classiadspro').'</span>';
            }elseif($feature_tag_style == 17){
                $feature_tag = '<span class="has_featured-tag-17">'.esc_html__('Featured', 'classiadspro').'</span>';
            }elseif($feature_tag_style == 18){
                $feature_tag = '<span class="has_featured-tag-18">'.esc_html__('Featured', 'classiadspro').'</span>';
            }elseif($feature_tag_style == 19){
                $feature_tag = '<span class="has_featured-tag-19">'.esc_html__('Featured', 'classiadspro').'</span>';
            }else{
                $feature_tag = '<span class="has_featured-tag-default">'.esc_html__('Featured', 'classiadspro').'</span>';
            }
            
            echo wp_kses_post($feature_tag);
        }
    }
}

// Initialize
ClassiAdsPro_Advertising_Admin::get_instance();

