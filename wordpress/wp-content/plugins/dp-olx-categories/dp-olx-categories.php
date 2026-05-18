<?php 
/*
Plugin Name: OLX Style Categories Accordion
Plugin URI: https://example.com/olx-style-categories
Description: Выводит категории в стиле OLX с иконками и адаптивным аккордионом подкатегорий на всю ширину ряда. Подкатегории открываются под соответствующим рядом, не ломая сетку, без AJAX и с полной адаптивностью.
Version: 2
Author: St4rc0w
Author URI: https://t.me/st4rpay
*/

if (!defined('ABSPATH')) exit;

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('olx-cat-style', plugin_dir_url(__FILE__) . 'assets/style.css', [], '2.1');
    wp_enqueue_script('olx-cat-script', plugin_dir_url(__FILE__) . 'assets/script.js', ['jquery'], '2.1', true);
});

add_shortcode('olx_categories', function ($atts) {

    $atts = shortcode_atts([
        'taxonomy' => 'directorypress-category'
    ], $atts);

    $parents = get_terms([
        'taxonomy' => $atts['taxonomy'],
        'parent' => 0,
        'hide_empty' => false
    ]);

    if (!$parents) return '';

    ob_start(); ?>

    <div class="olx-cat-wrapper">
        <div class="olx-cat-grid">

            <?php foreach ($parents as $term):

                $subcats = get_terms([
                    'taxonomy' => $atts['taxonomy'],
                    'parent' => $term->term_id,
                    'hide_empty' => false
                ]);

                $icon = '';
                $meta = get_term_meta($term->term_id, 'directorypress_category_icon', true);
                if ($meta) {
                    $arr = maybe_unserialize($meta);
                    if (!empty($arr['src'])) $icon = $arr['src'];
                }
            ?>

            <div class="olx-cat-item" data-id="<?php echo $term->term_id; ?>">
                <a href="#" class="olx-cat-link">
                    <?php if ($icon): ?>
                        <img src="<?php echo esc_url($icon); ?>">
                    <?php endif; ?>
                    <span><?php echo esc_html($term->name); ?></span>
                </a>

                <?php if ($subcats): ?>
                <div class="olx-subcats-template">
                    <?php foreach ($subcats as $sub): ?>
                        <a class="olx-subcat" href="<?php echo esc_url(get_term_link($sub)); ?>">
                            <?php echo esc_html($sub->name); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <?php endforeach; ?>

        </div>

        <!-- FULL WIDTH SUBCATS -->
        <div class="olx-subcats-row">
            <div class="olx-subcats-grid"></div>
        </div>

    </div>

    <?php
    return ob_get_clean();
});
