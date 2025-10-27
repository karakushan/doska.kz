/**
 * Firebase Push Notifications - Service Worker
 * Handles background push notifications
 */

// Import Firebase scripts
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js');

// Firebase configuration will be injected by PHP
let firebaseConfig = null;

/**
 * Handle push event (top-level event listener)
 */
self.addEventListener('push', function(event) {
    console.log('🔔 Push event received');
    
    if (event.data) {
        try {
            const data = event.data.json();
            console.log('📨 Push data:', data); 
            
            // Log notification permission for debugging
            const permissionStatus = Notification.permission;
            console.log('📋 Notification permission:', permissionStatus);
            
            // If notification data is present, show it directly
            if (data.notification && data.notification.title) {
                console.log('✅ Showing notification:', data.notification.title);
                const notificationTitle = data.notification.title;
                const notificationOptions = {
                    body: data.notification.body || '',
                    icon: data.notification.icon || '/wp-content/plugins/firebase-push-notifications/assets/images/icon-192x192.png',
                    badge: data.notification.badge || '/wp-content/plugins/firebase-push-notifications/assets/images/badge-72x72.png',
                    data: data.data || {},
                    tag: (data.data && data.data.notification_type) || 'general',
                    requireInteraction: false
                };
                
                event.waitUntil(
                    self.registration.showNotification(notificationTitle, notificationOptions)
                        .then(function() {
                            console.log('✅ Notification shown successfully');
                        })
                        .catch(function(error) {
                            console.error('❌ Error showing notification:', error);
                        })
                );
            } else {
                console.warn('⚠️ No notification data in push event');
            }
        } catch (error) {
            console.error('❌ Error parsing push data:', error);
            // JSON parse error, handle as text
            const text = event.data.text();
            console.log('📝 Push text:', text);
            if (text) {
                event.waitUntil(
                    self.registration.showNotification('Notification', {
                        body: text,
                        icon: '/wp-content/plugins/firebase-push-notifications/assets/images/icon-192x192.png'
                    })
                        .then(function() {
                            console.log('✅ Text notification shown successfully');
                        })
                        .catch(function(error) {
                            console.error('❌ Error showing text notification:', error);
                        })
                );
            }
        }
    } else {
        console.warn('⚠️ Push event without data');
    }
});

/**
 * Handle notification click (top-level event listener)
 */
self.addEventListener('notificationclick', function(event) {
    console.log('👆 Notification clicked');
    event.notification.close();
    
    if (event.action === 'dismiss') {
        console.log('❌ Dismissed notification');
        return;
    }
    
    // Default action or 'view' action
    let urlToOpen = '/my-dashboard/';
    
    if (event.notification.data && event.notification.data.action_url) {
        urlToOpen = event.notification.data.action_url;
        console.log('🔗 Opening custom URL:', urlToOpen);
    } else {
        console.log('🔗 Opening default URL:', urlToOpen);
    }
    
    // Open the URL
    event.waitUntil(
        clients.matchAll({
            type: 'window',
            includeUncontrolled: true
        }).then(function(clientList) {
            console.log('Found ' + clientList.length + ' clients');
            // Check if there's already a window/tab open with the target URL
            for (let i = 0; i < clientList.length; i++) {
                const client = clientList[i];
                if (client.url.includes(urlToOpen) && 'focus' in client) {
                    console.log('✅ Focusing existing client');
                    return client.focus();
                }
            }
            
            // If no existing window, open a new one
            if (clients.openWindow) {
                console.log('✅ Opening new window');
                return clients.openWindow(urlToOpen);
            }
        })
    );
});

/**
 * Handle push subscription change (top-level event listener)
 */
self.addEventListener('pushsubscriptionchange', function(event) {
    event.waitUntil(
        self.registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: getApplicationServerKey()
        }).then(function(newSubscription) {
            // Send new subscription to server
        }).catch(function(error) {
            // Handle subscription error
        })
    );
});

/**
 * Get application server key (VAPID)
 */
function getApplicationServerKey() {
    // This should be passed from the main script
    // For now, return a placeholder
    return null;
}

// Initialize Firebase when config is available
function initializeFirebase() {
    console.log('🔥 Initializing Firebase');
    if (!firebaseConfig) {
        console.warn('⚠️ Firebase config is not available');
        return;
    }
    
    try {
        // Initialize Firebase
        console.log('📱 Initializing Firebase app');
        firebase.initializeApp(firebaseConfig);
        console.log('✅ Firebase app initialized');
        
        // Get messaging instance
        console.log('📮 Getting messaging instance');
        const messaging = firebase.messaging();
        console.log('✅ Messaging instance created');
        
        // Set up message handlers
        console.log('🔧 Setting up message handlers');
        setupMessageHandlers(messaging);
        console.log('✅ Message handlers set up');
    } catch (error) {
        console.error('❌ Firebase initialization error:', error);
    }
}

// Set up Firebase messaging handlers
function setupMessageHandlers(messaging) {
    console.log('🔔 Setting up onBackgroundMessage handler');
    /**
     * Handle background messages
     */
    messaging.onBackgroundMessage(function(payload) {
        console.log('📬 Background message received:', payload);
        const notificationTitle = payload.notification.title || 'New Notification';
        const notificationOptions = {
            body: payload.notification.body || 'You have a new notification',
            icon: payload.notification.icon || '/wp-content/plugins/firebase-push-notifications/assets/images/icon-192x192.png',
            badge: payload.notification.badge || '/wp-content/plugins/firebase-push-notifications/assets/images/badge-72x72.png',
            tag: payload.data.notification_type || 'general',
            data: payload.data,
            actions: [
                {
                    action: 'view',
                    title: 'View',
                    icon: '/wp-content/plugins/firebase-push-notifications/assets/images/view-icon.png'
                },
                {
                    action: 'dismiss',
                    title: 'Dismiss',
                    icon: '/wp-content/plugins/firebase-push-notifications/assets/images/dismiss-icon.png'
                }
            ],
            requireInteraction: false,
            silent: false,
            vibrate: [200, 100, 200],
            timestamp: Date.now()
        };
        
        console.log('✅ Showing background message as notification:', notificationTitle);
        // Show notification
        return self.registration.showNotification(notificationTitle, notificationOptions)
            .then(() => console.log('✅ Background notification shown'))
            .catch(error => console.error('❌ Error showing background notification:', error));
    });
}

/**
 * Handle notification close (top-level event listener)
 */
self.addEventListener('notificationclose', function(event) {
    // Track notification dismissal if needed
    if (event.notification.data && event.notification.data.tracking_id) {
        // Send analytics event or log dismissal
        fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=track_notification_dismissal&tracking_id=' + event.notification.data.tracking_id
        }).catch(error => {
        });
    }
});

/**
 * Handle service worker installation (top-level event listener)
 */
self.addEventListener('install', function(event) {
    // Skip waiting to activate immediately
    self.skipWaiting();
});

/**
 * Handle service worker activation (top-level event listener)
 */
self.addEventListener('activate', function(event) {
    // Take control of all clients immediately
    event.waitUntil(
        clients.claim()
    );
});

/**
 * Handle service worker fetch (optional)
 */
self.addEventListener('fetch', function(event) {
    // Handle fetch events if needed
    // For example, caching strategies, offline support, etc.
});

/**
 * Handle service worker message (top-level event listener)
 */
self.addEventListener('message', function(event) {
    console.log('📨 Message received:', event.data);
    if (event.data && event.data.type === 'FIREBASE_CONFIG') {
        console.log('🔧 Setting Firebase config from main script');
        firebaseConfig = event.data.config;
        initializeFirebase();
    } else if (event.data && event.data.type === 'SKIP_WAITING') {
        console.log('⏩ Skip waiting signal received');
        self.skipWaiting();
    }
});

/**
 * Handle service worker sync (background sync)
 */
self.addEventListener('sync', function(event) {
    if (event.tag === 'background-sync') {
        event.waitUntil(
            // Perform background sync operations
            doBackgroundSync()
        );
    }
});

/**
 * Background sync function
 */
function doBackgroundSync() {
    // Implement background sync logic here
    // For example, sending queued notifications, updating data, etc.
    return Promise.resolve();
}

/**
 * Handle service worker error (top-level event listener)
 */
self.addEventListener('error', function(event) {
});

/**
 * Handle service worker unhandled promise rejection (top-level event listener)
 */
self.addEventListener('unhandledrejection', function(event) {
    if (event.reason && event.reason.toString && event.reason.toString().includes('404')) {
        // Silently handle 404 errors
        event.preventDefault();
    }
});
