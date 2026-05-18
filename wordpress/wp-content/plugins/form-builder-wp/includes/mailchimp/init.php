<?php

if(!function_exists('wpfb_mailchimp_get_api')){
    /**
     * @param string $api_key
     * @return WPFB_Mailchimp_API_V3
     */
    function wpfb_mailchimp_get_api($api_key) {
        if(!class_exists('WPFB_Mailchimp_API_V3',false)){
            require_once __DIR__.'/class-api-v3.php';
            require_once __DIR__.'/class-api-v3-client.php';
            require_once __DIR__.'/class-api-exception.php';
            require_once __DIR__.'/class-api-connection-exception.php';
            require_once __DIR__.'/class-api-resource-not-found-exception.php';
        }
        $api = new WPFB_Mailchimp_API_V3($api_key);
        return $api;
    }
}