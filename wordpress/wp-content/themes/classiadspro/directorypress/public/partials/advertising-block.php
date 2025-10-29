<?php
/**
 * Advertising Block Template
 * 
 * Displays advertised listings in a special block
 * 
 * Available variables:
 * - $filtered_listings: Array of listing objects to display
 */

if (empty($filtered_listings)) {
    return;
}

global $DIRECTORYPRESS_ADIMN_SETTINGS;
?>

<div class="advertising-listings-block">
    <div class="advertising-block-header">
        <h3 class="advertising-block-title">
            <span class="advertising-icon">🔥</span>
            <?php esc_html_e('Featured Listings', 'classiadspro'); ?>
        </h3>
    </div>
    
    <div class="row advertising-listings-grid">
        <?php foreach ($filtered_listings as $listing): ?>
            <?php
            // Get listing image
            if (isset($listing->logo_image) && !empty($listing->logo_image)) {
                $image_src_array = wp_get_attachment_image_src($listing->logo_image, 'medium');
                $image_src = $image_src_array[0];
            } elseif (isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']['url']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']['url'])) {
                $image_src = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']['url'];
            } else {
                $image_src = DIRECTORYPRESS_RESOURCES_URL . 'images/no-thumbnail.jpg';
            }
            
            // Get categories
            $categories = wp_get_post_terms($listing->post->ID, DIRECTORYPRESS_CATEGORIES_TAX);
            $category_names = array();
            if (!is_wp_error($categories) && !empty($categories)) {
                foreach ($categories as $cat) {
                    $category_names[] = $cat->name;
                }
            }
            ?>
            
            <div class="col-lg-4 col-md-6 col-sm-12 advertising-listing-item">
                <div class="listing-item advertising-listing">
                    <span class="advertising-badge">
                        <span class="badge-icon">⭐</span>
                        <?php esc_html_e('Advertised', 'classiadspro'); ?>
                    </span>
                    
                    <div class="listing-thumbnail">
                        <a href="<?php echo esc_url($listing->url()); ?>">
                            <img src="<?php echo esc_url($image_src); ?>" alt="<?php echo esc_attr($listing->title()); ?>">
                        </a>
                    </div>
                    
                    <div class="listing-content">
                        <?php if (!empty($category_names)): ?>
                            <div class="listing-categories">
                                <?php echo esc_html(implode(', ', $category_names)); ?>
                            </div>
                        <?php endif; ?>
                        
                        <h4 class="listing-title">
                            <a href="<?php echo esc_url($listing->url()); ?>">
                                <?php echo esc_html($listing->title()); ?>
                            </a>
                        </h4>
                        
                        <?php if ($listing->post->post_excerpt): ?>
                            <div class="listing-excerpt">
                                <?php echo wp_trim_words($listing->post->post_excerpt, 15); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php
                        // Display price field if available
                        global $wpdb;
                        $price_field = $wpdb->get_row($wpdb->prepare(
                            "SELECT id FROM {$wpdb->prefix}directorypress_fields WHERE type = %s AND slug = %s LIMIT 1",
                            'price',
                            'price'
                        ));
                        
                        if ($price_field) {
                            $listing->display_content_field($price_field->id);
                        }
                        ?>
                        
                        <div class="listing-footer">
                            <a href="<?php echo esc_url($listing->url()); ?>" class="btn btn-sm btn-primary">
                                <?php esc_html_e('View Details', 'classiadspro'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

