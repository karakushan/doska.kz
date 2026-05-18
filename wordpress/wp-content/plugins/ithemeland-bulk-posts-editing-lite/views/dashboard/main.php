<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div id="wpbe-body">
    <div class="wpbe-wrap">
        <div class="wpbe-header">
            <h2>
                <img src="<?php echo esc_url(WPBEL_IMAGES_URL . "wpbe_icon_original_black.svg"); ?>" alt="">
                <span>Welcome to</span>
                <strong>Wordpress Bulk Bundle</strong>

            </h2>
            <span class="wpbe-header-sub"><?php echo esc_html__("Version", 'ithemeland-bulk-posts-editing-lite') . esc_html(WPBEL_VERSION); ?></span>
            <span class="wpbe-header-sub-icon">
                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve">
                    <g>
                        <path d="M32,0C14.4,0,0,14.4,0,32s14.4,32,32,32s32-14.4,32-32S49.6,0,32,0z M32,58.7c-14.7,0-26.7-12-26.7-26.7S17.3,5.3,32,5.3s26.7,12,26.7,26.7S46.7,58.7,32,58.7z" />
                        <path d="M40.8,22.1l-12.3,12l-5.6-5.3c-1.1-1.1-2.7-1.1-3.7,0s-1.1,2.7,0,3.7l6.7,6.4c0.8,0.8,1.6,1.1,2.4,1.1c0.8,0,1.9-0.3,2.4-1.1l13.6-13.1c1.1-1.1,1.1-2.7,0-3.7C43.5,21.1,41.9,21.1,40.8,22.1z" />
                    </g>
                </svg>
                Activated
            </span>
        </div>
    </div>
    <div class="wpbe-dashboard-body">
        <div class="wpbe-wrap">
            <div class="wpbe-boxes">
                <div class="wpbe-box-3">
                    <div class="wpbe-box-image">
                        <img src="<?php echo esc_url(WPBEL_IMAGES_URL . "support.svg"); ?>" alt="">
                    </div>
                    <div class="wpbe-box-text">
                        <strong><?php esc_html_e('Need Some Help', 'ithemeland-bulk-posts-editing-lite'); ?></strong>
                        <span>We would love to be of any assistance</span>
                    </div>
                    <div class="wpbe-box-footer">
                        <a href="https://support.ithemelandco.com" class="wpbe-btn-green"><?php esc_html_e('Send Ticket', 'ithemeland-bulk-posts-editing-lite'); ?></a>
                    </div>
                </div>
                <div class="wpbe-box-3">
                    <div class="wpbe-box-image">
                        <img src="<?php echo esc_url(WPBEL_IMAGES_URL . "documentation.svg"); ?>" alt="">
                    </div>
                    <div class="wpbe-box-text">
                        <strong><?php esc_html_e('Documentation', 'ithemeland-bulk-posts-editing-lite'); ?></strong>
                        <span>We would love to be of any assistance</span>
                    </div>
                    <div class="wpbe-box-footer">
                        <a href="https://ithemelandco.com/Plugins/Documentations/Pro-Bulk-Editing/WooCommerce-Bulk-Product-Editing" class="wpbe-btn-orange"><?php esc_html_e('Start Reading', 'ithemeland-bulk-posts-editing-lite'); ?></a>
                    </div>
                </div>
                <div class="wpbe-box-3">
                    <div class="wpbe-box-image">
                        <img src="<?php echo esc_url(WPBEL_IMAGES_URL . "subscription.svg"); ?>" alt="">
                    </div>
                    <div class="wpbe-box-text">
                        <strong><?php esc_html_e('Subscription', 'ithemeland-bulk-posts-editing-lite'); ?></strong>
                        <span>We would love to be of any assistance</span>
                    </div>
                    <div class="wpbe-box-footer">
                        <a href="javascript:;" class="wpbe-btn-dark"><?php esc_html_e('Coming Soon', 'ithemeland-bulk-posts-editing-lite'); ?></a>
                    </div>
                </div>
            </div>
            <div class="wpbe-dashboard-change-log">
                <div class="wpbe-dashboard-change-log-header">
                    <div class="wpbe-dashboard-change-log-header-left">
                        <strong><?php esc_html_e("Changelog", 'ithemeland-bulk-posts-editing-lite') ?></strong>
                    </div>
                    <div class="wpbe-dashboard-change-log-header-right">
                        <span>Follow US </span>
                        <?php if (!empty($social_networks)) : ?>
                            <ul>
                                <?php foreach ($social_networks as $social_network) : ?>
                                    <li><a href="<?php echo esc_url($social_network['link']); ?>"><img src="<?php echo esc_url($social_network['icon']); ?>" alt=""></a></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
                <ul class="wpbe-dashboard-change-log-body">
                    <li>
                        <div class="wpbe-dashboard-log-title">
                            <strong>Version 1.9.2</strong>
                            <span>(2022.05.25)</span>
                        </div>
                        <ul>
                            <li>Compatible with WordPress 6.0</li>
                            <li>Compatible with WooCommerce 6.5</li>
                            <li>Change activation form</li>
                            <li>Fixed Some other issues</li>
                        </ul>
                    </li>
                    <li>
                        <div class="wpbe-dashboard-log-title">
                            <strong>Version 1.7.7</strong>
                            <span>(2021.09.10)</span>
                        </div>
                        <ul>
                            <li>Fixed: Meta fields for Taxonomy</li>
                            <li>Fixed: Export and Import issues</li>
                            <li>Fixed: ACF issues</li>
                            <li>Fixed: some other issues</li>
                        </ul>
                    </li>
                    <li>
                        <div class="wpbe-dashboard-log-title">
                            <strong>Version 1.6.1</strong>
                            <span>(2021.04.21)</span>
                        </div>
                        <ul>
                            <li>Fixed: appearance update plugins alarm</li>
                            <li>Fixed: some other issues</li>
                        </ul>
                    </li>
                    <li>
                        <div class="wpbe-dashboard-log-title">
                            <strong>Version 1.5.0</strong>
                            <span>(2021.02.18)</span>
                        </div>
                        <ul>
                            <li>Added: Compatible with iThemeland WooCommerce Dynamic Prices By User Role Plugin</li>
                            <li>Fixed: some other issues</li>
                        </ul>
                    </li>
                    <li>
                        <div class="wpbe-dashboard-log-title">
                            <strong>Version 1.4.1</strong>
                            <span>(2021.01.24)</span>
                        </div>
                        <ul>
                            <li>Updated: Optimizing the Core System</li>
                            <li>Fixed: some other issues</li>
                        </ul>
                    </li>
                    <li>
                        <div class="wpbe-dashboard-log-title">
                            <strong>Version 1.4.0</strong>
                            <span>(2021.01.09)</span>
                        </div>
                        <ul>
                            <li>Updated: Purchase verification system</li>
                            <li>Fixed: some other issues</li>
                        </ul>
                    </li>
                    <li>
                        <div class="wpbe-dashboard-log-title">
                            <strong>Version 1.3.0</strong>
                            <span>(2020.12.30)</span>
                        </div>
                        <ul>
                            <li>Fixed: Optimizing Core plugin installation via TGM</li>
                            <li>Added: Delete Row for each row</li>
                            <li>Updated: Change “Remove Duplicate” option details in “Bulk Edit”</li>
                            <li>Updated: Compatible with WordPress v5.6</li>
                            <li>Fixed: Undo/Redo operation</li>
                            <li>Fixed: some other issues</li>
                        </ul>
                    </li>
                    <li>
                        <div class="wpbe-dashboard-log-title">
                            <strong>Version 1.2.3</strong>
                            <span>(2020.12.08)</span>
                        </div>
                        <ul>
                            <li>Separate Taxonomy and Attribute in BULK and Search form (related tab)</li>
                            <li>Separating columns in Column profile based on New Product Tabs</li>
                            <li>Fixed: Column order in column manager</li>
                        </ul>
                    </li>
                    <li>
                        <div class="wpbe-dashboard-log-title">
                            <strong>Version 1.2.2</strong>
                            <span>(2020.11.18)</span>
                        </div>
                        <ul>
                            <li>Fixed Some other issues</li>
                        </ul>
                    </li>
                    <li>
                        <div class="wpbe-dashboard-log-title">
                            <strong>Version 1.2.1</strong>
                            <span>(2020.11.09)</span>
                        </div>
                        <ul>
                            <li>Fixed License issue</li>
                            <li>Fixed Some other issues</li>
                        </ul>
                    </li>
                    <li>
                        <div class="wpbe-dashboard-log-title">
                            <strong>Version 1.2.0</strong>
                            <span>(2020.09.12)</span>
                        </div>
                        <ul>
                            <li>Added auto update</li>
                        </ul>
                    </li>
                    <li>
                        <div class="wpbe-dashboard-log-title">
                            <strong>Version 1.1.0</strong>
                            <span>(2020.07.20)</span>
                        </div>
                        <ul>
                            <li>Added license manager</li>
                        </ul>
                    </li>
                    <li>
                        <div class="wpbe-dashboard-log-title">
                            <strong>Version 1.0.0</strong>
                            <span>(2020.06.03)</span>
                        </div>
                        <ul>
                            <li>Released</li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>