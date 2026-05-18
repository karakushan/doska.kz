<?php

if ( ! defined( 'ABSPATH' )) exit;

class CPFM_Feedback_Notice {
    
    private static $registered_notices = [];
    
    public function __construct() {
        
        add_action('admin_init', [ $this, 'cpfm_listen_for_external_notice_registration' ]);
        add_action('admin_enqueue_scripts', [ $this, 'cpfm_enqueue_assets' ]);
        add_action('wp_ajax_cpfm_handle_opt_in', [ $this, 'cpfm_handle_opt_in_choice' ]);
        add_action('admin_footer', [ $this, 'cpfm_render_notice_panel' ]);
        
    }
    
    public static function cpfm_register_notice( $key, $args ) {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
    
        $key  = sanitize_key( (string) $key );
        $args = is_array( $args ) ? $args : array();
        $args = wp_parse_args(
            $args,
            array(
                'title'          => '',
                'message'        => '',
                'pages'          => array(),
                'always_show_on' => array(),
                'plugin_name'    => '', // optional
            )
        );
    
        $args['title']          = sanitize_text_field( $args['title'] );
        $args['message']        = wp_kses_post( $args['message'] );
        $args['pages']          = array_values( array_filter( array_map( 'sanitize_key', (array) $args['pages'] ) ) );
        $args['always_show_on'] = array_values( array_filter( array_map( 'sanitize_key', (array) $args['always_show_on'] ) ) );
        $args['plugin_name']    = sanitize_key( $args['plugin_name'] );
    
        if ( ! isset( self::$registered_notices ) || ! is_array( self::$registered_notices ) ) {
            self::$registered_notices = array();
        }
        if ( ! isset( self::$registered_notices[ $key ] ) || ! is_array( self::$registered_notices[ $key ] ) ) {
            self::$registered_notices[ $key ] = array();
        }
    
        self::$registered_notices[ $key ][] = $args;
    }
    
    
    
    public function cpfm_listen_for_external_notice_registration() {
        if (!current_user_can('manage_options')) {
            return;
        }

        /**
         * Allow other plugins to register notices dynamically.
         * Example usage in other plugins:
         * do_action('cpf_cpfm_register_notice', 'crypto', [
         *     'title' => 'Crypto Plugin Notice',
         *     'message' => 'This is a crypto dashboard setup notice.',
         *     'pages' => ['dashboard', 'cpfm_'],
         * ]);
         */
        do_action('cpfm_register_notice');
    }

    public function cpfm_enqueue_assets() {
        if ( ! current_user_can( 'manage_options' ) ) return;
        if ( ! function_exists( 'get_current_screen' ) ) return;
    
        $screen = get_current_screen();
        if ( ! $screen ) return;
    
        $current_page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';
    
        // Gather pages from all bucketed notices
        $allowed_pages = array();
        foreach ( self::$registered_notices as $bucket_notices ) {
            if ( is_array( $bucket_notices ) ) {
                foreach ( $bucket_notices as $notice_args ) {
                    if ( ! empty( $notice_args['pages'] ) && is_array( $notice_args['pages'] ) ) {
                        $allowed_pages = array_merge( $allowed_pages, $notice_args['pages'] );
                    }
                }
            }
        }
        $allowed_pages = array_unique( array_map( 'sanitize_key', $allowed_pages ) );
    
        if ( empty( $current_page ) || ( ! empty( $allowed_pages ) && ! in_array( $current_page, $allowed_pages, true ) ) ) {
            return;
        }
    
        wp_enqueue_style(
            'cpfm-common-review-style',
            TPAP_URL . 'admin/cpfm-feedback/css/cpfm-admin-feedback.css',
            array(),
            defined( 'TPAP_VERSION' ) ? TPAP_VERSION : false
        );
        wp_enqueue_script(
            'cpfm-common-review-script',
            TPAP_URL . 'admin/cpfm-feedback/js/cpfm-admin-feedback.js',
            array( 'jquery' ),
            defined( 'TPAP_VERSION' ) ? TPAP_VERSION : false,
            true
        );
    
        // Build autoShowPages from nested buckets
        $auto_show_pages = array();
        foreach ( self::$registered_notices as $bucket_notices ) {
            if ( is_array( $bucket_notices ) ) {
                foreach ( $bucket_notices as $notice_args ) {
                    if ( ! empty( $notice_args['always_show_on'] ) && is_array( $notice_args['always_show_on'] ) ) {
                        $auto_show_pages = array_merge( $auto_show_pages, array_map( 'sanitize_key', $notice_args['always_show_on'] ) );
                    }
                }
            }
        }
        $auto_show_pages = array_values( array_unique( $auto_show_pages ) );
    
        wp_localize_script(
            'cpfm-common-review-script',
            'adminNotice',
            array(
                'ajaxurl'       => admin_url( 'admin-ajax.php' ),
                'nonce'         => wp_create_nonce( 'dismiss_admin_notice' ),
                'autoShowPages' => $auto_show_pages,
            )
        );
    }
    
  
    public function cpfm_handle_opt_in_choice() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Unauthorized access.', 'ccpw' ) ), 403 );
        }
    
        check_ajax_referer( 'dismiss_admin_notice', 'nonce' );
    
        $category = isset( $_POST['category'] ) ? sanitize_key( wp_unslash( $_POST['category'] ) ) : '';
        $opt_in   = isset( $_POST['opt_in'] ) ? sanitize_text_field( wp_unslash( $_POST['opt_in'] ) ) : '';
        $opt_in   = ( 'yes' === $opt_in ) ? 'yes' : 'no';
    
        if ( '' === $category || ! isset( self::$registered_notices[ $category ] ) || ! is_array( self::$registered_notices[ $category ] ) ) {
            wp_send_json_error( array( 'message' => __( 'Invalid notice category.', 'ccpw' ) ), 400 );
        }
    
        update_option( "cpfm_opt_in_choice_{$category}", $opt_in, false );
    
        if ( 'yes' === $opt_in ) {
            foreach ( self::$registered_notices[ $category ] as $notice ) {
                $plugin_name = isset( $notice['plugin_name'] ) ? sanitize_key( $notice['plugin_name'] ) : '';
                if ( $plugin_name ) {
                    do_action( 'cpfm_after_opt_in_' . $plugin_name, $category );
                }
            }
        }
    
        wp_send_json_success();
    }
    

    public function cpfm_render_notice_panel() {
        if ( ! current_user_can( 'manage_options' ) || ! function_exists( 'get_current_screen' ) ) {
            return;
        }
        $screen = get_current_screen();
        if ( ! $screen ) return;
    
        $current_page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';
    
        $unread_count = 0;
        $auto_show    = false;
    
        // auto_show if any notice wants it on this page
        foreach ( self::$registered_notices as $bucket_notices ) {
            if ( is_array( $bucket_notices ) ) {
                foreach ( $bucket_notices as $notice ) {
                    if ( ! empty( $notice['always_show_on'] ) && in_array( $current_page, (array) $notice['always_show_on'], true ) ) {
                        $auto_show = true;
                        break 2;
                    }
                }
            }
        }
    
        $output  = '';
        $output .= '<div id="cpfNoticePanel" class="notice-panel"' . ( $auto_show ? ' data-auto-show="true"' : '' ) . '>';
        $output .= '<div class="notice-panel-header">' . esc_html__( 'Help Improve Plugins', 'ccpw' ) . ' <span class="dashicons dashicons-no" id="cpfm_remove_notice"></span></div>';
        $output .= '<div class="notice-panel-content">';
    
        foreach ( self::$registered_notices as $key => $bucket_notices ) {
            if ( ! is_array( $bucket_notices ) || empty( $bucket_notices ) ) continue;
    
            $choice = get_option( "cpfm_opt_in_choice_{$key}" );
            if ( false !== $choice ) continue;
    
            $should_show = false;
            foreach ( $bucket_notices as $notice ) {
                $pages = isset( $notice['pages'] ) ? (array) $notice['pages'] : array();
                foreach ( $pages as $match ) {
                    $match = sanitize_key( $match );
                    if ( '' !== $match && ( $current_page === $match || 0 === strpos( $current_page, $match ) ) ) {
                        $should_show = true;
                        break 2;
                    }
                }
            }
            if ( ! $should_show ) continue;
    
            $unread_count++;
    
            $first_notice = $bucket_notices[0];
            $title        = isset( $first_notice['title'] ) ? $first_notice['title'] : '';
            $message      = isset( $first_notice['message'] ) ? $first_notice['message'] : '';
    
            $output .= '<div class="notice-item unread" data-notice-id="' . esc_attr( $key ) . '">';
            $output .= '<strong>' . esc_html( $title ) . '</strong>';
            $output .= '<div class="notice-message-with-toggle">';
            $output .= '<p>' . esc_html( $message ) . '<a href="#" class="cpf-toggle-extra">' . esc_html__( ' More info', 'ccpw' ) . '</a></p>';
            $output .= '</div>';
            $output .= '<div class="cpf-extra-info">';
            $output .= '<p>' . esc_html__( 'Opt in to receive email updates about security improvements, new features, helpful tutorials, and occasional special offers. We\'ll collect:', 'ccpw' ) . '</p>';
            $output .= '<ul>';
            $output .= '<li>' . esc_html__( 'Your website home URL and WordPress admin email.', 'ccpw' ) . '</li>';
            $output .= '<li>' . esc_html__( 'To check plugin compatibility, we will collect the following: list of active plugins and themes, server type, MySQL version, WordPress version, memory limit, site language and database prefix.', 'ccpw' ) . '</li>';
            $output .= '</ul>';
            $output .= '</div>';
            $output .= '<div class="notice-actions">';
            $output .= '<button class="button button-primary opt-in-yes" data-category="' . esc_attr( $key ) . '" id="yes-share-data" value="yes">' . esc_html__( 'Yes, I Agree', 'ccpw' ) . '</button>';
            $output .= '<button class="button opt-in-no" data-category="' . esc_attr( $key ) . '" id="no-share-data" value="no">' . esc_html__( 'No, Thanks', 'ccpw' ) . '</button>';
            $output .= '</div>';
            $output .= '</div>';
        }
    
        $output .= '</div></div>';
    
        if ( $unread_count > 0 ) {
            echo wp_kses_post( $output );
        }
    }
    
}
new CPFM_Feedback_Notice();
