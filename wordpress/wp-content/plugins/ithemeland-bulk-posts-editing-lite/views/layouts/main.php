<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

use wpbel\classes\helpers\Sanitizer;

include WPBEL_VIEWS_DIR . "layouts/header.php"; ?>

<div id="wpbe-body">
    <div class="wpbe-tabs wpbe-tabs-main">
        <div class="wpbe-tabs-navigation">
            <nav class="wpbe-tabs-navbar">
                <ul class="wpbe-tabs-list" data-type="url" data-content-id="wpbe-main-tabs-contents">
                    <?php echo wp_kses(apply_filters('wpbe_top_navigation_buttons', ''), Sanitizer::allowed_html()); ?>
                </ul>
            </nav>

            <div class="wpbe-top-nav-filters-per-page">
                <select id="wpbe-quick-per-page" title="The number of products per page">
                    <?php
                    if (!empty($count_per_page_items)) :
                        $current_value = (!empty($current_settings['count_per_page'])) ? $current_settings['count_per_page'] : $settings['count_per_page'];
                        foreach ($count_per_page_items as $count_per_page_item) :
                    ?>
                            <option value="<?php echo intval($count_per_page_item); ?>" <?php echo ($settings['count_per_page'] == intval($count_per_page_item)) ? 'selected' : ''; ?>>
                                <?php echo esc_html($count_per_page_item); ?>
                            </option>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>

            <div class="wpbe-top-nav-filters-go-to-page">
                <input type="number" id="wpbe-top-nav-filters-go-to-page" title="Go to page" min="1" max="" placeholder="Page">
            </div>

            <div class="wpbe-items-pagination"></div>
        </div>

        <div class="wpbe-tabs-contents" id="wpbe-main-tabs-contents">
            <div class="wpbe-wrap">
                <div class="wpbe-tab-middle-content">
                    <div class="wpbe-table" id="wpbe-items-table">
                        <?php
                        if (!empty($table) && file_exists(esc_html($table))) :
                            include $table;
                        else :
                        ?>
                            <p style="width: 100%; text-align: center; padding: 10px 0;"><img src="<?php echo esc_url(WPBEL_IMAGES_URL . 'loading.gif'); ?>" width="30" height="30"></p>
                        <?php endif; ?>
                    </div>
                    <div class="wpbe-items-count"></div>
                </div>
            </div>
        </div>
        <div class="wpbe-table-loading">
            <img src="<?php echo esc_url(WPBEL_IMAGES_URL . 'loading-2.gif'); ?>" width="18" height="18">
        </div>
        <div class="wpbe-created-by">
            <a href="https://ithemelandco.com" target="_blank">Created by iThemelandCo</a>
        </div>
    </div>
</div>

<?php include_once  WPBEL_VIEWS_DIR . "layouts/footer.php"; ?>