<?php
/**
 * Advertising Display Class
 * 
 * Handles display of advertised listings
 */

if (!defined('ABSPATH')) {
    exit;
}

class ClassiAdsPro_Advertising_Display {
    
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
        // Display advertising block before listings
        add_action('directorypress_before_listings_loop', array($this, 'display_advertising_block'));
        
        // Add advertising badge to listings
        add_action('directorypress_after_listing_title', array($this, 'add_advertising_badge'), 10, 1);
    }
    
    /**
     * Display advertising block
     */
    public function display_advertising_block() {
        // Get current category
        $current_category_id = 0;
        
        if (is_tax(DIRECTORYPRESS_CATEGORIES_TAX)) {
            $term = get_queried_object();
            $current_category_id = $term->term_id;
        }
        
        // Query advertised listings
        $args = array(
            'post_type' => DIRECTORYPRESS_POST_TYPE,
            'post_status' => 'publish',
            'posts_per_page' => 6,
            'orderby' => 'rand',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_is_advertised',
                    'value' => '1',
                    'compare' => '='
                ),
                array(
                    'key' => '_advertising_end_date',
                    'value' => current_time('timestamp'),
                    'compare' => '>=',
                    'type' => 'NUMERIC'
                )
            )
        );
        
        $advertised_query = new WP_Query($args);
        
        if (!$advertised_query->have_posts()) {
            return;
        }
        
        // Filter by category if needed
        $filtered_listings = array();
        
        while ($advertised_query->have_posts()) {
            $advertised_query->the_post();
            $listing_id = get_the_ID();
            
            // Check if should display in current category
            if ($current_category_id == 0 || classiadspro_should_display_in_category($listing_id, $current_category_id)) {
                $filtered_listings[] = directorypress_get_listing($listing_id);
            }
        }
        
        wp_reset_postdata();
        
        if (empty($filtered_listings)) {
            return;
        }
        
        // Render template
        $template_path = get_stylesheet_directory() . '/directorypress/public/partials/advertising-block.php';
        
        if (file_exists($template_path)) {
            include $template_path;
        } else {
            $this->render_default_block($filtered_listings);
        }
    }
    
    /**
     * Render default advertising block
     * 
     * @param array $listings Array of listing objects
     */
    private function render_default_block($listings) {
        ?>
        <div class="advertising-listings-block">
            <div class="advertising-block-header">
                <h3 class="advertising-block-title">Рекомендуемые объявления</h3>
            </div>
            
            <div class="row advertising-listings-grid">
                <?php foreach ($listings as $listing): ?>
                    <div class="col-lg-4 col-md-6 col-sm-6 advertising-listing-item">
                        <div class="listing-item advertising-listing">
                            <span class="advertising-badge">Реклама</span>
                            
                            <?php if ($listing->logo_image): ?>
                                <div class="listing-thumbnail">
                                    <a href="<?php echo esc_url($listing->url()); ?>">
                                        <img src="<?php echo esc_url($listing->logo_image); ?>" alt="<?php echo esc_attr($listing->title()); ?>">
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="listing-content">
                                <h4 class="listing-title">
                                    <a href="<?php echo esc_url($listing->url()); ?>">
                                        <?php echo esc_html($listing->title()); ?>
                                    </a>
                                </h4>
                                
                                <?php if ($listing->post->post_excerpt): ?>
                                    <div class="listing-excerpt">
                                        <?php echo wp_trim_words($listing->post->post_excerpt, 20); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
    
    /**
     * Add advertising badge to listing
     * 
     * @param object $listing Listing object
     */
    public function add_advertising_badge($listing) {
        if (!$listing || !isset($listing->post)) {
            return;
        }
        
        if (classiadspro_is_listing_advertised($listing->post->ID)) {
            echo '<span class="advertising-badge">Реклама</span>';
        }
    }
}

// Initialize
ClassiAdsPro_Advertising_Display::get_instance();

