/**
 * Firebase Push Notifications - Client Side
 * Handles FCM initialization and token management
 */

(function() {
    'use strict';
    
    // Check if Firebase is available
    if (typeof firebase === 'undefined') {
        return;
    }
    
    // Debug flag - enable to see detailed logs in console
    const DEBUG = localStorage.getItem('firebase_push_debug') === '1';
    
    // Debug logging helper
    function log(message, data) {
        if (DEBUG) {
            if (data) {
            } else {
            }
        }
    }
    
    // Global error handler for Firebase
    window.addEventListener('unhandledrejection', function(event) {
        if (event.reason && event.reason.message && event.reason.message.includes('pushManager')) {
            event.preventDefault();
            // Silently handle pushManager errors
        }
    });
    
    // Global error handler for Firebase messaging errors
    window.addEventListener('error', function(event) {
        if (event.error && event.error.message && event.error.message.includes('pushManager')) {
            event.preventDefault();
            // Silently handle pushManager errors
        }
    });
    
    // Initialize Firebase
    let messaging = null;
    let isInitialized = false;
    let serviceWorkerRegistration = null;
    
    // Get Firebase config from WordPress localized data
    let firebaseConfig = (typeof firebasePushNotifications !== 'undefined' && firebasePushNotifications.config) 
        ? firebasePushNotifications.config 
        : null;
    
    /**
     * Initialize Firebase Messaging
     */
    function initializeFirebase() {
        try {
            // Check if browser supports required features
            if (!('Notification' in window)) {
                log('Notification API not supported');
                return;
            }
            
            log('Initializing Firebase App');
            
            // Initialize Firebase app
            if (!firebase.apps.length) {
                firebase.initializeApp(firebaseConfig);
            }
            
            // Check if Service Worker is supported before creating messaging instance
            if ('serviceWorker' in navigator) {
                log('Service Worker supported, initializing messaging');
                // Get messaging instance with Service Worker support
                messaging = firebase.messaging();
                
                // Initialize service worker first
                initializeServiceWorker().then(function(registration) {
                    log('Service Worker initialized, waiting for user gesture to request permission');
                }).catch(function(error) {
                    log('Service Worker initialization error: ' + error.message);
                });
            } else {
                // Fallback: create messaging instance without Service Worker
                try {
                    log('Service Worker not supported, using fallback');
                    messaging = firebase.messaging();
                } catch (error) {
                    log('Firebase messaging not supported: ' + error.message);
                    return;
                }
            }
            
            isInitialized = true;
            log('Firebase initialization complete');
            
            // Set up notification permission button listener
            setupNotificationButton();
            
            // For non-Safari browsers, try to request permission automatically after a short delay
            // Safari requires user gesture, so we skip it
            if (!isSafari() && Notification.permission === 'default') {
                // Small delay to ensure UI is ready
                setTimeout(function() {
                    log('Auto-requesting permission for non-Safari browser');
                    requestPermission();
                }, 1000);
            }
            
        } catch (error) {
            log('Firebase initialization error: ' + error.message);
        }
    }
    
    /**
     * Set up notification permission button
     */
    function setupNotificationButton() {
        const button = document.getElementById('firebase-enable-notifications');
        if (button) {
            button.addEventListener('click', requestPermission);
            log('Notification button found and listener attached');
            return;
        }
        
        // If permission is not granted and no button exists, create one automatically
        if (Notification.permission === 'default') {
            log('No notification button found, creating one automatically');
            
            // Try to find a suitable container
            let container = document.querySelector('[data-firebase-notifications-container]');
            if (!container) {
                container = document.querySelector('.user-settings') || 
                          document.querySelector('.dashboard-settings') ||
                          document.querySelector('.profile-settings') ||
                          document.querySelector('.user-preferences') ||
                          document.querySelector('main') ||
                          document.body;
            }
            
            if (container) {
                const newButton = document.createElement('button');
                newButton.id = 'firebase-enable-notifications';
                newButton.textContent = 'Enable Notifications';
                newButton.className = 'button button-primary firebase-notifications-btn';
                newButton.style.marginTop = '10px';
                newButton.style.marginBottom = '10px';
                newButton.addEventListener('click', requestPermission);
                
                container.appendChild(newButton);
                log('Notification button created and appended');
            }
        }
        
        // Also listen for first user interaction as fallback
        const requestOnFirstInteraction = function() {
            if (Notification.permission === 'default') {
                document.removeEventListener('click', requestOnFirstInteraction);
                log('User interaction detected, requesting permission');
                requestPermission();
            }
        };
        
        document.addEventListener('click', requestOnFirstInteraction);
    }
    
    /**
     * Request notification permission
     */
    function requestPermission() {
        if (!messaging) return;
        
        // Check if browser supports notifications
        if (!('Notification' in window)) {
            log('Notification API not supported');
            return;
        }
        
        log('Current permission: ' + Notification.permission);
        
        // Request permission
        Notification.requestPermission().then(function(permission) {
            log('Permission result: ' + permission);
            if (permission === 'granted') {
                log('Permission granted, getting token');
                getToken();
            }
        }).catch(function(error) {
            log('Permission request error: ' + error.message);
        });
    }
    
    /**
     * Get FCM token
     */
    function getToken() {
        if (!messaging) return;
        
        log('Getting FCM token, Service Worker Registration: ' + (serviceWorkerRegistration ? 'exists' : 'null'));
        
        // Check if Service Worker is supported
        if (!('serviceWorker' in navigator)) {
            log('Service Worker not supported, using fallback');
            // Fallback: get token without service worker
            messaging.getToken({ vapidKey: firebaseConfig.vapidKey })
                .then(function(token) {
                    if (token) {
                        log('Token obtained (fallback): ' + token.substring(0, 20) + '...');
                        saveTokenToServer(token);
                    }
                })
                .catch(function(err) {
                    log('Error retrieving token (fallback): ' + err.message);
                });
            return;
        }
        
        // Use saved registration or fallback
        if (serviceWorkerRegistration) {
            log('Getting token with Service Worker Registration');
            // Get token with VAPID key and custom service worker
            messaging.getToken({ 
                vapidKey: firebaseConfig.vapidKey,
                serviceWorkerRegistration: serviceWorkerRegistration
            })
            .then(function(token) {
                if (token) {
                    log('Token obtained: ' + token.substring(0, 20) + '...');
                    saveTokenToServer(token);
                }
            })
            .catch(function(err) {
                log('Error retrieving token: ' + err.message + ', trying fallback');
                // Error retrieving token, try without service worker
                messaging.getToken({ vapidKey: firebaseConfig.vapidKey })
                    .then(function(token) {
                        if (token) {
                            log('Token obtained (fallback 2): ' + token.substring(0, 20) + '...');
                            saveTokenToServer(token);
                        }
                    })
                    .catch(function(err) {
                        log('Error retrieving token (fallback 2): ' + err.message);
                    });
            });
        } else {
            log('Service Worker Registration not available, using fallback');
            // Fallback: get token without service worker
            messaging.getToken({ vapidKey: firebaseConfig.vapidKey })
                .then(function(token) {
                    if (token) {
                        log('Token obtained (fallback 3): ' + token.substring(0, 20) + '...');
                        saveTokenToServer(token);
                    }
                })
                .catch(function(err) {
                    log('Error retrieving token (fallback 3): ' + err.message);
                });
        }
    }
    
    /**
     * Save FCM token to server
     */
    function saveTokenToServer(token) {
        if (!token) return;
        
        log('Saving token to server');
        
        // Get AJAX URL and nonce from WordPress localized data
        const ajaxUrl = (typeof firebasePushNotifications !== 'undefined') ? firebasePushNotifications.ajaxUrl : '/wp-admin/admin-ajax.php';
        const nonce = (typeof firebasePushNotifications !== 'undefined') ? firebasePushNotifications.nonce : '';
        
        log('AJAX URL: ' + ajaxUrl + ', Nonce available: ' + (nonce ? 'yes' : 'no'));
        
        const data = new FormData();
        data.append('action', 'save_fcm_token');
        data.append('token', token);
        data.append('nonce', nonce);
        
        fetch(ajaxUrl, {
            method: 'POST',
            body: data
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                log('Token saved successfully');
                updateNotificationStatus(true);
            } else {
                log('Token save failed: ' + data.data);
            }
        })
        .catch(error => {
            log('Token save error: ' + error.message);
        });
    }
    
    /**
     * Update notification status in UI
     */
    function updateNotificationStatus(enabled) {
        const statusElements = document.querySelectorAll('.notification-status .status-value');
        statusElements.forEach(element => {
            if (element.textContent.includes('Enabled') || element.textContent.includes('Disabled')) {
                element.textContent = enabled ? 'Enabled' : 'Disabled';
                element.className = enabled ? 'status-value enabled' : 'status-value disabled';
            }
        });
        
        // Enable/disable form elements
        const formElements = document.querySelectorAll('.firebase-notification-settings input[type="checkbox"]');
        formElements.forEach(element => {
            element.disabled = !enabled;
        });
        
        const submitButton = document.querySelector('.firebase-notification-settings button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = !enabled;
        }
    }
    
    /**
     * Handle incoming messages
     */
    function handleIncomingMessage() {
        if (!messaging) return;
        
        messaging.onMessage(function(payload) {
            
            // Show notification if browser supports it
            if ('Notification' in window && Notification.permission === 'granted') {
                const notification = new Notification(payload.notification.title, {
                    body: payload.notification.body,
                    icon: payload.notification.icon || '/wp-content/plugins/firebase-push-notifications/assets/images/icon-192x192.png',
                    badge: payload.notification.badge || '/wp-content/plugins/firebase-push-notifications/assets/images/badge-72x72.png',
                    tag: payload.data.notification_type || 'general',
                    data: payload.data
                });
                
                // Handle notification click
                notification.onclick = function(event) {
                    event.preventDefault();
                    window.focus();
                    
                    // Navigate to action URL if provided
                    if (payload.data.action_url) {
                        window.location.href = payload.data.action_url;
                    }
                    
                    notification.close();
                };
                
                // Auto close after 5 seconds
                setTimeout(() => {
                    notification.close();
                }, 5000);
            }
        });
    }
    
    /**
     * Handle token refresh
     */
    function handleTokenRefresh() {
        if (!messaging) return;
        
        // In Firebase v10+, onTokenRefresh is replaced with onMessage
        // Token refresh is handled automatically by Firebase
    }
    
    /**
     * Initialize service worker
     */
    function initializeServiceWorker() {
        if (!('serviceWorker' in navigator)) {
            return Promise.resolve(null);
        }
        
        return navigator.serviceWorker.register('/wp-content/plugins/firebase-push-notifications/assets/js/service-worker.js')
                .then(function(registration) {
                serviceWorkerRegistration = registration;
                // Send Firebase config to service worker
                if (registration.active) {
                    registration.active.postMessage({
                        type: 'FIREBASE_CONFIG',
                        config: firebaseConfig
                    });
                } else {
                    // Wait for service worker to be ready
                    registration.addEventListener('updatefound', function() {
                        const newWorker = registration.installing;
                        newWorker.addEventListener('statechange', function() {
                            if (newWorker.state === 'activated') {
                                newWorker.postMessage({
                                    type: 'FIREBASE_CONFIG',
                                    config: firebaseConfig
                                });
                            }
                        });
                    });
                }
                
                return registration;
                })
                .catch(function(error) {
                return null;
                });
    }
    
    /**
     * Check if user is logged in
     */
    function isUserLoggedIn() {
        // Check if WordPress user is logged in
        return document.body.classList.contains('logged-in');
    }
    
    /**
     * Initialize everything
     */
    function init() {
        log('Initializing Firebase Push Notifications');
        
        // Only initialize for logged-in users
        if (!isUserLoggedIn()) {
            log('User not logged in, skipping initialization');
            return;
        }
        
        log('User is logged in');
        
        // Check if Firebase config is available
        if (typeof firebaseConfig === 'undefined') {
            log('Firebase config not available');
            return;
        }
        
        log('Firebase config available, proceeding with initialization');
        
        // Initialize Firebase
        initializeFirebase();
        
        // Initialize service worker
        initializeServiceWorker();
        
        // Handle incoming messages
        handleIncomingMessage();
        
        // Handle token refresh
        handleTokenRefresh();
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // Expose functions globally for debugging, merge with existing localized data
    if (typeof window.firebasePushNotifications === 'undefined') {
        window.firebasePushNotifications = {};
    }
    window.firebasePushNotifications.initialize = init;
    window.firebasePushNotifications.requestPermission = requestPermission;
    window.firebasePushNotifications.saveToken = saveTokenToServer;
    window.firebasePushNotifications.updateStatus = updateNotificationStatus;
    
    /**
     * Create and inject notification permission button
     * Call this function to add a button for enabling notifications
     */
    window.firebasePushNotifications.createNotificationButton = function(containerId, buttonText, buttonClass) {
        const container = document.getElementById(containerId || 'firebase-notifications-container');
        if (!container) {
            return;
        }
        
        const button = document.createElement('button');
        button.id = 'firebase-enable-notifications';
        button.textContent = buttonText || 'Enable Notifications';
        button.className = buttonClass || 'button';
        button.addEventListener('click', requestPermission);
        
        container.appendChild(button);
        log('Notification button created in container: ' + containerId);
    };
    
    /**
     * Check if browser is Safari
     */
    function isSafari() {
        return /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
    }
    
})();
