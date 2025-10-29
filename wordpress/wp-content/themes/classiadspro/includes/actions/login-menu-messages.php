<?php
/**
 * Extend Login Menu widget output with Messages functionality
 * Using JavaScript/DOM manipulation approach instead of class override
 * 
 * @package classiadspro
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Messages functionality to Login Menu widget via JavaScript
 */
add_action('wp_footer', 'classiadspro_login_menu_messages_script', 99);
function classiadspro_login_menu_messages_script() {
    
    // Only for logged in users
    if (!is_user_logged_in()) {
        return;
    }
    
    // Check if required functions exist
    if (!function_exists('difp_get_new_message_number') || !function_exists('directorypress_dashboardUrl')) {
        return;
    }
    
    // Get unread messages count
    $unread_count = difp_get_new_message_number();
    $messages_url = directorypress_dashboardUrl(array('directory_action' => 'messages'));
    
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        console.log('Login Menu Messages Extension: Script loaded');
        
        // Function to add messages menu item and badge
        function addMessagesSupport() {
            console.log('Login Menu Messages Extension: Running addMessagesSupport()');
            
            // Debug: check if user menu exists
            var $userMenu = $('.hfb-user-menu');
            console.log('User menu found:', $userMenu.length);
            
            // Add Messages menu item before Logout
            var $logoutItem = $('.hfb-user-menu .dropdown-content ul li').filter(function() {
                return $(this).find('a').text().toLowerCase().indexOf('logout') !== -1 ||
                       $(this).find('a').text().toLowerCase().indexOf('выход') !== -1;
            });
            
            console.log('Logout item found:', $logoutItem.length);
            
            // Check if messages link already exists
            var $existingMessages = $('.hfb-user-menu .dropdown-content ul li').find('a[href*="messages"]');
            console.log('Existing messages link:', $existingMessages.length);
            
            if ($logoutItem.length && !$existingMessages.length) {
                var messageBadgeHTML = <?php echo $unread_count > 0 ? "' <span class=\"message-count-badge\">" . esc_js($unread_count) . "</span>'" : "''"; ?>;
                var messagesMenuItem = '<li><a href="<?php echo esc_js($messages_url); ?>"><i class="fas fa-envelope"></i><?php echo esc_js(__('Messages', 'classiadspro')); ?>' + messageBadgeHTML + '</a></li>';
                $logoutItem.before(messagesMenuItem);
                console.log('Messages menu item added');
            }
            
            // Add badge to profile image
            <?php if ($unread_count > 0): ?>
            var $profileIcon = $('.hfb-user-menu .user-menu-icon');
            console.log('Profile icon found:', $profileIcon.length);
            if ($profileIcon.length && !$profileIcon.find('.user-message-badge').length) {
                $profileIcon.append('<span class="user-message-badge"><?php echo esc_js($unread_count); ?></span>');
                console.log('Profile badge added');
            }
            <?php endif; ?>
        }
        
        // Run on page load
        addMessagesSupport();
        
        // Run after AJAX loads (if Elementor uses AJAX)
        $(document).on('elementor/popup/show', function() {
            setTimeout(addMessagesSupport, 100);
        });
    });
    </script>
    <?php
}

/**
 * Enqueue custom styles for message badges
 */
add_action('wp_enqueue_scripts', 'classiadspro_messages_badge_styles', 20);
function classiadspro_messages_badge_styles() {
    
    // Enqueue our extended styles
    wp_enqueue_style(
        'classiadspro-messages-badges',
        get_template_directory_uri() . '/includes/elementor/css/user-menu-extended.css',
        array(),
        '1.0.1'
    );
}

