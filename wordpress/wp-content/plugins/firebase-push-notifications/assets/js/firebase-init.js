/**
 * Firebase Push Notifications - Client Side
 * Handles FCM initialization and token management
 */

(function () {
  "use strict";

  // Check if Firebase is available
  if (typeof firebase === "undefined") {
    return;
  }

  // Debug flag - enable to see detailed logs in console
  const DEBUG = false;

  // Debug logging helper
  function log(message, data) {
    if (DEBUG) {
      if (data) {
        console.log("[Firebase Push] " + message, data);
      } else {
        console.log("[Firebase Push] " + message);
      }
    }
  }

  // ==================== CROSS-DOMAIN COOKIE HELPERS ====================
  // These cookies work across all subdomains (e.g., de.adshelppro.com, es.adshelppro.com)

  /**
   * Get the root domain for cross-subdomain cookies
   * Dynamically extracts root domain from current hostname
   */
  function getRootDomain() {
    const host = window.location.hostname;
    
    // For localhost or IP addresses, don't set domain (cookie will be for current host)
    if (host === 'localhost' || host.indexOf('localhost:') !== -1 || /^(\d{1,3}\.){3}\d{1,3}$/.test(host)) {
      return null;
    }
    
    // Extract root domain (last two parts for standard domains, last three for country TLDs)
    const parts = host.split('.');
    
    // Handle country-code TLDs like .co.uk, .com.au, etc.
    const countryTLDs = ['co.uk', 'com.au', 'co.nz', 'co.jp', 'co.kr', 'com.br', 'com.mx'];
    const lastTwoParts = parts.slice(-2).join('.');
    
    if (parts.length >= 2) {
      // For country-code TLDs, use last 3 parts, otherwise last 2
      if (countryTLDs.indexOf(lastTwoParts) !== -1 && parts.length >= 3) {
        return '.' + parts.slice(-3).join('.');
      }
      return '.' + parts.slice(-2).join('.');
    }
    
    return null;
  }

  /**
   * Set a cookie that works across all subdomains
   */
  function setCrossDomainCookie(name, value, days) {
    let cookieStr = name + '=' + encodeURIComponent(value) + '; path=/; SameSite=Lax';

    const rootDomain = getRootDomain();
    if (rootDomain) {
      cookieStr += '; domain=' + rootDomain;
    }

    if (days) {
      const date = new Date();
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      cookieStr += '; expires=' + date.toUTCString();
    }

    // Add Secure flag for HTTPS
    if (window.location.protocol === 'https:') {
      cookieStr += '; Secure';
    }

    document.cookie = cookieStr;
    log('Cross-domain cookie set: ' + name + ', domain: ' + (rootDomain || 'current'));
  }

  /**
   * Get a cookie value
   */
  function getCookie(name) {
    const nameEQ = name + '=';
    const cookies = document.cookie.split(';');
    for (let i = 0; i < cookies.length; i++) {
      let cookie = cookies[i];
      while (cookie.charAt(0) === ' ') {
        cookie = cookie.substring(1);
      }
      if (cookie.indexOf(nameEQ) === 0) {
        return decodeURIComponent(cookie.substring(nameEQ.length));
      }
    }
    return null;
  }

  /**
   * Delete a cookie
   */
  function deleteCookie(name) {
    let cookieStr = name + '=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC';
    const rootDomain = getRootDomain();
    if (rootDomain) {
      cookieStr += '; domain=' + rootDomain;
    }
    document.cookie = cookieStr;
  }

  // Global error handler for Firebase
  window.addEventListener("unhandledrejection", function (event) {
    if (
      event.reason &&
      event.reason.message &&
      event.reason.message.includes("pushManager")
    ) {
      event.preventDefault();
      // Silently handle pushManager errors
    }
  });

  // Global error handler for Firebase messaging errors
  window.addEventListener("error", function (event) {
    if (
      event.error &&
      event.error.message &&
      event.error.message.includes("pushManager")
    ) {
      event.preventDefault();
      // Silently handle pushManager errors
    }
  });

  // Initialize Firebase
  let messaging = null;
  let isInitialized = false;
  let serviceWorkerRegistration = null;

  // Get Firebase config from WordPress localized data
  let firebaseConfig =
    typeof firebasePushNotifications !== "undefined" &&
      firebasePushNotifications.config
      ? firebasePushNotifications.config
      : null;

  /**
   * Initialize Firebase Messaging
   */
  function initializeFirebase() {
    try {
      // Check if browser supports required features
      if (!("Notification" in window)) {
        log("Notification API not supported");
        return;
      }

      log("Initializing Firebase App");

      // Initialize Firebase app
      if (!firebase.apps.length) {
        firebase.initializeApp(firebaseConfig);
      }

      // Check if Service Worker is supported before creating messaging instance
      if ("serviceWorker" in navigator) {
        log("Service Worker supported, initializing service worker first");
        
        // Initialize service worker FIRST, then create messaging instance
        initializeServiceWorker()
          .then(function (registration) {
            if (registration) {
              log("Service Worker registered successfully");
              // Now create messaging instance with custom service worker
              try {
                messaging = firebase.messaging();
                log("Messaging instance created with custom service worker");
              } catch (error) {
                log("Error creating messaging instance: " + error.message);
              }
            } else {
              log("Service Worker registration failed, creating messaging without SW");
              messaging = firebase.messaging();
            }
            
            // Continue with initialization
            completeInitialization();
          })
          .catch(function (error) {
            log("Service Worker initialization error: " + error.message);
            // Fallback: create messaging without service worker
            try {
              messaging = firebase.messaging();
              completeInitialization();
            } catch (e) {
              log("Fallback messaging creation failed: " + e.message);
            }
          });
      } else {
        // Fallback: create messaging instance without Service Worker
        try {
          log("Service Worker not supported, using fallback");
          messaging = firebase.messaging();
          completeInitialization();
        } catch (error) {
          log("Firebase messaging not supported: " + error.message);
          return;
        }
      }
    } catch (error) {
      log("Firebase initialization error: " + error.message);
    }
  }

  /**
   * Complete initialization after messaging is ready
   */
  function completeInitialization() {
    isInitialized = true;
    log("Firebase initialization complete");

      // Set up notification permission button listener (only for logged in users)
      setupNotificationButton();

      // Show permission request ONLY for logged in users
      // This prevents showing the dialog to guest users
      if (!isUserLoggedIn()) {
        log("User not logged in, skipping permission dialog");
        // Mark that we need to show dialog after login
        setCrossDomainCookie('fcm_show_after_login', '1', 1);
        return;
      }

      // Check if this is a fresh login (should show permission dialog)
      const showAfterLogin = getCookie('fcm_show_after_login');
      if (showAfterLogin === '1') {
        log("Fresh login detected, will show permission dialog");
        // Clear the flag
        deleteCookie('fcm_show_after_login');
        
        // Show permission dialog after a short delay
        if (Notification.permission === "default") {
          setTimeout(function () {
            showPermissionDialog();
          }, 1500);
          return;
        }
      }

      // Show permission request if permission is default (not yet decided)
      // Check if we should show the dialog (not asked in last 24 hours)
      if (Notification.permission === "default") {
        const lastAskedTime = getCookie('fcm_permission_asked_time');
        const now = Date.now();
        const twentyFourHours = 24 * 60 * 60 * 1000;
        
        if (!lastAskedTime || (now - parseInt(lastAskedTime)) > twentyFourHours) {
          log("Permission not yet asked, showing custom permission dialog");
          // Small delay to ensure UI is ready
          setTimeout(function () {
            showPermissionDialog();
          }, 2000);
        } else {
          log("Permission dialog was shown recently, skipping");
        }
      }
      
      if (Notification.permission === "granted") {
        // If permission already granted, handle existing tokens
        const storedToken = getStoredToken();
        if (storedToken) {
          log("Permission already granted, found stored token");
          log("Logged in user with stored token, syncing with server");
          saveTokenToServer(storedToken);
        } else {
          log("Permission granted but no stored token, getting new token");
          if (messaging) {
            getToken();
          }
        }
      }
  }

  /**
   * Set up notification permission button
   * Only sets up listeners for logged in users
   */
  function setupNotificationButton() {
    // CRITICAL: Only set up notification buttons for logged in users
    if (!isUserLoggedIn()) {
      log("User not logged in, skipping notification button setup");
      return;
    }

    const button = document.getElementById("firebase-enable-notifications");
    if (button) {
      button.addEventListener("click", requestPermission);
      log("Notification button found and listener attached");
      return;
    }

    // If permission is not granted and no button exists, create one automatically
    if (Notification.permission === "default") {
      log("No notification button found, creating one automatically");

      // Try to find a suitable container
      let container = document.querySelector(
        "[data-firebase-notifications-container]"
      );
      if (!container) {
        container =
          document.querySelector(".user-settings") ||
          document.querySelector(".dashboard-settings") ||
          document.querySelector(".profile-settings") ||
          document.querySelector(".user-preferences") ||
          document.querySelector("main") ||
          document.body;
      }

      if (container) {
        const newButton = document.createElement("button");
        newButton.id = "firebase-enable-notifications";
        newButton.textContent = "Enable Notifications";
        newButton.className =
          "button button-primary firebase-notifications-btn";
        newButton.style.marginTop = "10px";
        newButton.style.marginBottom = "10px";
        newButton.addEventListener("click", requestPermission);

        container.appendChild(newButton);
        log("Notification button created and appended");
      }
    }

    // NOTE: Removed automatic permission request on first interaction
    // Permission should only be requested after user explicitly clicks
    // on the custom dialog or notification button
  }

  /**
   * Request notification permission
   */
  function requestPermission() {
    // Check if browser supports notifications
    if (!("Notification" in window)) {
      log("Notification API not supported");
      return;
    }

    log("Current permission: " + Notification.permission);
    log("requestPermission function called");

    // Safari requires synchronous call to requestPermission in click handler
    // Try Promise-based API first (modern browsers)
    try {
      log("Attempting Promise-based requestPermission");
      const permissionPromise = Notification.requestPermission();

      if (permissionPromise && permissionPromise.then) {
        // Promise-based API (modern browsers)
        permissionPromise.then(handlePermissionResult).catch(function (error) {
          log("Permission request error: " + error.message);
        });
      } else {
        // Callback-based API (old Safari)
        log("Using callback-based requestPermission");
        Notification.requestPermission(handlePermissionResult);
      }
    } catch (error) {
      log("Exception in requestPermission: " + error.message);
    }
  }

  /**
   * Get FCM token
   */
  function getToken() {
    if (!messaging) {
      log("❌ ERROR: Messaging not initialized, cannot get token");
      return;
    }

    log("🔄 Getting FCM token...");
    log("Browser: " + (isSafari() ? "Safari" : "Other"));
    log("Service Worker supported: " + ("serviceWorker" in navigator));
    log("Service Worker Registration: " + (serviceWorkerRegistration ? "exists" : "null"));
    log("VAPID Key available: " + (firebaseConfig.vapidKey ? "yes" : "no"));
    log("Notification permission: " + Notification.permission);

    // Check if Service Worker is supported
    if (!("serviceWorker" in navigator)) {
      log("Service Worker not supported, using fallback");
      // Fallback: get token without service worker
      messaging
        .getToken({ vapidKey: firebaseConfig.vapidKey })
        .then(function (token) {
          if (token) {
            log("✅ Token obtained (fallback): " + token.substring(0, 20) + "...");
            log("Full token length: " + token.length);
            saveTokenToServer(token);
          } else {
            log("❌ No token received (fallback)");
          }
        })
        .catch(function (err) {
          log("❌ Error retrieving token (fallback): " + err.message);
          console.error("Token error details:", err);
        });
      return;
    }

    // Use saved registration or fallback
    if (serviceWorkerRegistration) {
      log("Getting token with Service Worker Registration");
      // Get token with VAPID key and custom service worker
      messaging
        .getToken({
          vapidKey: firebaseConfig.vapidKey,
          serviceWorkerRegistration: serviceWorkerRegistration,
        })
        .then(function (token) {
          if (token) {
            log("✅ Token obtained: " + token.substring(0, 20) + "...");
            log("Full token length: " + token.length);
            saveTokenToServer(token);
          } else {
            log("❌ No token received with Service Worker");
          }
        })
        .catch(function (err) {
          log("❌ Error retrieving token: " + err.message + ", trying fallback");
          console.error("Token error details:", err);
          // Error retrieving token, try without service worker
          messaging
            .getToken({ vapidKey: firebaseConfig.vapidKey })
            .then(function (token) {
              if (token) {
                log(
                  "Token obtained (fallback 2): " +
                  token.substring(0, 20) +
                  "..."
                );
                saveTokenToServer(token);
              }
            })
            .catch(function (err) {
              log("Error retrieving token (fallback 2): " + err.message);
            });
        });
    } else {
      log("Service Worker Registration not available, using fallback");
      // Fallback: get token without service worker
      messaging
        .getToken({ vapidKey: firebaseConfig.vapidKey })
        .then(function (token) {
          if (token) {
            log(
              "Token obtained (fallback 3): " + token.substring(0, 20) + "..."
            );
            saveTokenToServer(token);
          }
        })
        .catch(function (err) {
          log("Error retrieving token (fallback 3): " + err.message);
        });
    }
  }

  /**
   * Save FCM token to server
   */
  function saveTokenToServer(token) {
    if (!token) return;

    // Always store token in localStorage first
    storeToken(token);

    // Only save to server if user is logged in
    if (!isUserLoggedIn()) {
      log("User not logged in, token stored locally only");
      return;
    }

    log("Saving token to server for logged in user");

    // Check if we recently sent this token to avoid spam
    const lastSentKey = 'fcm_last_sent_' + token.substring(0, 20);
    const lastSent = localStorage.getItem(lastSentKey);
    const now = Date.now();

    if (lastSent && (now - parseInt(lastSent)) < 60000) { // 1 minute cooldown
      log("Token was recently sent to server, skipping duplicate request");
      return;
    }

    // Get AJAX URL and nonce from WordPress localized data
    const ajaxUrl =
      typeof firebasePushNotifications !== "undefined"
        ? firebasePushNotifications.ajaxUrl
        : "/wp-admin/admin-ajax.php";
    const nonce =
      typeof firebasePushNotifications !== "undefined"
        ? firebasePushNotifications.nonce
        : "";

    log(
      "AJAX URL: " + ajaxUrl + ", Nonce available: " + (nonce ? "yes" : "no")
    );

    const data = new FormData();
    data.append("action", "save_fcm_token");
    data.append("token", token);
    data.append("nonce", nonce);

    fetch(ajaxUrl, {
      method: "POST",
      body: data,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          log("Token saved successfully to server");
          // Mark token as recently sent
          localStorage.setItem(lastSentKey, now.toString());
          updateNotificationStatus(true);
        } else {
          log("Token save failed: " + data.data);
        }
      })
      .catch((error) => {
        log("Token save error: " + error.message);
      });
  }

  /**
   * Update notification status in UI
   */
  function updateNotificationStatus(enabled) {
    const statusElements = document.querySelectorAll(
      ".notification-status .status-value"
    );
    statusElements.forEach((element) => {
      if (
        element.textContent.includes("Enabled") ||
        element.textContent.includes("Disabled")
      ) {
        element.textContent = enabled ? "Enabled" : "Disabled";
        element.className = enabled
          ? "status-value enabled"
          : "status-value disabled";
      }
    });

    // Enable/disable form elements
    const formElements = document.querySelectorAll(
      '.firebase-notification-settings input[type="checkbox"]'
    );
    formElements.forEach((element) => {
      element.disabled = !enabled;
    });

    const submitButton = document.querySelector(
      '.firebase-notification-settings button[type="submit"]'
    );
    if (submitButton) {
      submitButton.disabled = !enabled;
    }
  }

  /**
   * Handle incoming messages
   */
  function handleIncomingMessage() {
    if (!messaging) return;

    messaging.onMessage(function (payload) {
      // Show notification if browser supports it
      if ("Notification" in window && Notification.permission === "granted") {
        const notification = new Notification(payload.notification.title, {
          body: payload.notification.body,
          icon:
            payload.notification.icon ||
            "/wp-content/plugins/firebase-push-notifications/assets/images/icon-192x192.png",
          badge:
            payload.notification.badge ||
            "/wp-content/plugins/firebase-push-notifications/assets/images/badge-72x72.png",
          tag: payload.data.notification_type || "general",
          data: payload.data,
        });

        // Handle notification click
        notification.onclick = function (event) {
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
    if (!("serviceWorker" in navigator)) {
      return Promise.resolve(null);
    }

    // First, unregister any default Firebase service worker that might exist
    return navigator.serviceWorker.getRegistrations().then(function(registrations) {
      // Remove any old firebase-messaging-sw.js registrations
      const cleanupPromises = registrations.map(function(registration) {
        if (registration.active && registration.active.scriptURL &&
            registration.active.scriptURL.includes('firebase-messaging-sw.js')) {
          return registration.unregister();
        }
        return Promise.resolve();
      });
      
      return Promise.all(cleanupPromises);
    }).then(function() {
      // Register our custom service worker
      return navigator.serviceWorker.register(
        "/wp-content/plugins/firebase-push-notifications/assets/js/service-worker.js"
      );
    }).then(function (registration) {
        serviceWorkerRegistration = registration;
        // Send Firebase config to service worker
        if (registration.active) {
          registration.active.postMessage({
            type: "FIREBASE_CONFIG",
            config: firebaseConfig,
          });
        } else {
          // Wait for service worker to be ready
          registration.addEventListener("updatefound", function () {
            const newWorker = registration.installing;
            newWorker.addEventListener("statechange", function () {
              if (newWorker.state === "activated") {
                newWorker.postMessage({
                  type: "FIREBASE_CONFIG",
                  config: firebaseConfig,
                });
              }
            });
          });
        }

        return registration;
      })
      .catch(function (error) {
        log("Service worker registration error: " + error.message);
        return null;
      });
  }

  /**
   * Check if user is logged in
   */
  function isUserLoggedIn() {
    // First check from WordPress localized data
    if (typeof firebasePushNotifications !== 'undefined' && firebasePushNotifications.isLoggedIn !== undefined) {
      return firebasePushNotifications.isLoggedIn;
    }
    // Fallback: check if WordPress user is logged in via body class
    return document.body.classList.contains("logged-in");
  }

  /**
   * Get token from localStorage
   */
  function getStoredToken() {
    try {
      return localStorage.getItem('fcm_token');
    } catch (error) {
      log("Error getting stored token: " + error.message);
      return null;
    }
  }

  /**
   * Store token in localStorage
   */
  function storeToken(token) {
    try {
      localStorage.setItem('fcm_token', token);
      log("✅ Token stored in localStorage: " + token.substring(0, 20) + "...");

      // Verify it was stored
      const stored = localStorage.getItem('fcm_token');
      if (stored === token) {
        log("✅ Token storage verified");
      } else {
        log("❌ Token storage verification failed");
      }
    } catch (error) {
      log("❌ Error storing token: " + error.message);
      console.error("Storage error details:", error);
    }
  }

  /**
   * Get current user ID from WordPress
   */
  function getCurrentUserId() {
    // Try to get from localized data
    if (typeof firebasePushNotifications !== 'undefined' && firebasePushNotifications.userId) {
      return firebasePushNotifications.userId;
    }
    // Try to get from body class (WordPress adds logged-in class)
    const body = document.body;
    if (body.classList.contains('logged-in')) {
      // Try to extract user ID from admin bar
      const adminBarProfile = document.querySelector('#wp-admin-bar-my-account a');
      if (adminBarProfile) {
        const href = adminBarProfile.getAttribute('href');
        const match = href.match(/user_id=(\d+)/);
        if (match) {
          return parseInt(match[1]);
        }
      }
    }
    return null;
  }

  /**
   * Save user notification status to Firebase Firestore
   * This allows tracking user's notification status across devices
   */
  function saveUserNotificationStatusToFirebase(token, enabled) {
    if (!isUserLoggedIn()) {
      log("User not logged in, skipping Firebase user status save");
      return;
    }

    // Get user ID from WordPress
    const userId = getCurrentUserId();
    if (!userId) {
      log("Could not get user ID, skipping Firebase user status save");
      return;
    }

    // Use Firestore if available
    if (typeof firebase !== 'undefined' && firebase.firestore) {
      try {
        const db = firebase.firestore();
        const userRef = db.collection('notification_users').doc(userId.toString());

        userRef.set({
          uid: userId,
          token: token,
          notificationsEnabled: enabled,
          updatedAt: firebase.firestore.FieldValue.serverTimestamp(),
          platform: navigator.platform,
          userAgent: navigator.userAgent
        }, { merge: true })
        .then(function() {
          log("✅ User notification status saved to Firebase");
        })
        .catch(function(error) {
          log("❌ Error saving user status to Firebase: " + error.message);
        });
      } catch (error) {
        log("❌ Firestore not available: " + error.message);
      }
    } else {
      log("Firestore not available, skipping Firebase user status save");
    }
  }

  /**
   * Check if user has already been asked for permission
   * Uses cross-domain cookie to persist across subdomain changes
   */
  function hasBeenAskedForPermission() {
    // Check cookie only (works across subdomains)
    return getCookie('fcm_permission_asked') === 'true';
  }

  /**
   * Mark that user has been asked for permission
   * Uses cross-domain cookie to persist across subdomain changes
   */
  function markPermissionAsked() {
    // Set cookie for 1 year (works across all subdomains)
    setCrossDomainCookie('fcm_permission_asked', 'true', 365);
    // Set timestamp cookie for rate limiting (24 hour cooldown)
    setCrossDomainCookie('fcm_permission_asked_time', Date.now().toString(), 1);
  }

  /**
   * Check and sync token for logged in users
   */
  function checkAndSyncToken() {
    if (!isUserLoggedIn()) {
      return;
    }

    const storedToken = getStoredToken();
    if (storedToken && Notification.permission === "granted") {
      log("Found stored token for logged in user with granted permission, syncing with server");
      saveTokenToServer(storedToken);
    } else if (storedToken && Notification.permission === "denied") {
      log("User has stored token but denied permission, clearing stored token");
      try {
        localStorage.removeItem('fcm_token');
      } catch (error) {
        log("Error clearing stored token: " + error.message);
      }
    }
  }

  /**
   * Monitor permission changes
   */
  function monitorPermissionChanges() {
    // Check if browser supports permission monitoring
    if ('permissions' in navigator) {
      navigator.permissions.query({ name: 'notifications' }).then(function (permission) {
        permission.addEventListener('change', function () {
          log("Notification permission changed to: " + permission.state);

          if (permission.state === 'granted') {
            log("Permission granted, checking for stored token");
            const storedToken = getStoredToken();
            if (storedToken && isUserLoggedIn()) {
              log("Found stored token after permission granted, syncing with server");
              saveTokenToServer(storedToken);
            } else if (!storedToken && messaging) {
              log("No stored token after permission granted, getting new token");
              getToken();
            }
          } else if (permission.state === 'denied') {
            log("Permission denied, clearing stored token if exists");
            try {
              localStorage.removeItem('fcm_token');
            } catch (error) {
              log("Error clearing stored token: " + error.message);
            }
          }
        });
      }).catch(function (error) {
        log("Error monitoring permission changes: " + error.message);
      });
    }
  }

  /**
   * Initialize everything
   */
  function init() {
    log("Initializing Firebase Push Notifications");

    // Initialize for all users, not just logged in ones
    log("Initializing for all users");

    // Check if Firebase config is available
    if (typeof firebaseConfig === "undefined") {
      log("Firebase config not available");
      return;
    }

    log("Firebase config available, proceeding with initialization");

    // Initialize Firebase
    initializeFirebase();

    // Initialize service worker
    initializeServiceWorker();

    // Handle incoming messages
    handleIncomingMessage();

    // Handle token refresh
    handleTokenRefresh();

    // Check and sync token for logged in users
    checkAndSyncToken();

    // Monitor permission changes
    monitorPermissionChanges();
  }

  // Initialize when DOM is ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }

  // Expose functions globally for debugging, merge with existing localized data
  if (typeof window.firebasePushNotifications === "undefined") {
    window.firebasePushNotifications = {};
  }
  window.firebasePushNotifications.initialize = init;
  window.firebasePushNotifications.requestPermission = requestPermission;
  window.firebasePushNotifications.saveToken = saveTokenToServer;
  window.firebasePushNotifications.updateStatus = updateNotificationStatus;
  window.firebasePushNotifications.getToken = getToken;
  window.firebasePushNotifications.getStoredToken = getStoredToken;

  // Debug function to check current state
  window.firebasePushNotifications.debugState = function () {
    console.log("=== Firebase Push Notifications Debug State ===");
    console.log("Browser:", isSafari() ? "Safari" : "Other");
    console.log("Notification permission:", Notification.permission);
    console.log("Firebase initialized:", isInitialized);
    console.log("Messaging available:", !!messaging);
    console.log("Service Worker supported:", "serviceWorker" in navigator);
    console.log("Service Worker registered:", !!serviceWorkerRegistration);
    console.log("User logged in:", isUserLoggedIn());
    console.log("Stored token:", getStoredToken() ? getStoredToken().substring(0, 20) + "..." : "none");
    console.log("Permission asked:", hasBeenAskedForPermission());
    console.log("Firebase config:", firebaseConfig);
    console.log("===============================================");
  };

  /**
   * Create and inject notification permission button
   * Call this function to add a button for enabling notifications
   */
  window.firebasePushNotifications.createNotificationButton = function (
    containerId,
    buttonText,
    buttonClass
  ) {
    const container = document.getElementById(
      containerId || "firebase-notifications-container"
    );
    if (!container) {
      return;
    }

    const button = document.createElement("button");
    button.id = "firebase-enable-notifications";
    button.textContent = buttonText || "Enable Notifications";
    button.className = buttonClass || "button";
    button.addEventListener("click", requestPermission);

    container.appendChild(button);
    log("Notification button created in container: " + containerId);
  };

  /**
   * Show message when Safari user denies permission
   */
  function showSafariPermissionDeniedMessage() {
    log("Showing Safari permission denied message");

    // Create a temporary notification
    const notification = document.createElement("div");
    notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #ff6b6b;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 10001;
            max-width: 300px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 14px;
            line-height: 1.4;
        `;

    notification.innerHTML = `
            <div style="display: flex; align-items: flex-start; gap: 10px;">
                <span style="font-size: 18px;">⚠️</span>
                <div>
                    <strong>Notifications Disabled</strong><br>
                    <span style="opacity: 0.9; font-size: 12px;">
                        To enable them later, go to Safari → Preferences → Websites → Notifications
                    </span>
                </div>
            </div>
        `;

    document.body.appendChild(notification);

    // Auto-remove after 8 seconds
    setTimeout(() => {
      if (notification.parentNode) {
        notification.style.transition =
          "transform 0.3s ease-out, opacity 0.3s ease-out";
        notification.style.transform = "translateX(100%)";
        notification.style.opacity = "0";
        setTimeout(() => notification.remove(), 300);
      }
    }, 8000);
  }

  /**
   * Show message for iOS Safari users
   */
  function showIOSSafariMessage() {
    log("Showing iOS Safari message");

    // Check if message already shown
    if (localStorage.getItem("ios-safari-message-shown")) {
      return;
    }

    const message = document.createElement("div");
    message.style.cssText = `
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: linear-gradient(135deg, #ff9a56 0%, #ff6b6b 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            z-index: 10000;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            text-align: center;
        `;

    message.innerHTML = `
            <div style="margin-bottom: 15px;">
                <span style="font-size: 24px;">📱</span><br>
                <strong>iOS Notifications</strong>
            </div>
            <p style="margin: 0 0 15px 0; opacity: 0.9;">
                To receive push notifications on iPhone/iPad, add this site to your home screen using the "Share" button → "Add to Home Screen"
            </p>
            <button onclick="this.parentElement.remove(); localStorage.setItem('ios-safari-message-shown', 'true');" style="
                background: rgba(255,255,255,0.2);
                border: 1px solid rgba(255,255,255,0.3);
                color: white;
                padding: 10px 20px;
                border-radius: 8px;
                cursor: pointer;
                font-size: 14px;
                font-weight: 500;
            ">
                Got it
            </button>
        `;

    document.body.appendChild(message);

    // Auto-hide after 15 seconds
    setTimeout(() => {
      if (message.parentNode) {
        message.remove();
        localStorage.setItem("ios-safari-message-shown", "true");
      }
    }, 15000);
  }

  /**
   * Show Safari-specific notification banner
   */
  function showSafariNotificationBanner() {
    log("Showing Safari notification banner");

    // Check if banner already exists
    if (document.getElementById("safari-notification-banner")) {
      return;
    }

    // Create banner element
    const banner = document.createElement("div");
    banner.id = "safari-notification-banner";
    banner.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            text-align: center;
            z-index: 10000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 14px;
            line-height: 1.4;
        `;

    banner.innerHTML = `
            <div style="max-width: 800px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                <div style="flex: 1; min-width: 200px;">
                    <strong>🔔 Enable Notifications</strong><br>
                    <span style="opacity: 0.9;">Get important updates and messages directly in your browser</span>
                </div>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <button id="safari-enable-notifications" style="
                        background: rgba(255,255,255,0.2);
                        border: 1px solid rgba(255,255,255,0.3);
                        color: white;
                        padding: 8px 16px;
                        border-radius: 6px;
                        cursor: pointer;
                        font-size: 13px;
                        font-weight: 500;
                        transition: all 0.2s ease;
                    " onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                        Enable
                    </button>
                    <button id="safari-banner-close" style="
                        background: transparent;
                        border: none;
                        color: white;
                        font-size: 18px;
                        cursor: pointer;
                        padding: 5px;
                        opacity: 0.7;
                        transition: opacity 0.2s ease;
                    " onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.7'">
                        ✕
                    </button>
                </div>
            </div>
        `;

    // Add to page
    document.body.appendChild(banner);

    // Add event listeners
    document
      .getElementById("safari-enable-notifications")
      .addEventListener("click", function () {
        log("Safari banner clicked, requesting permission synchronously");

        // Call requestPermission synchronously for Safari
        try {
          const permissionPromise = Notification.requestPermission();
          if (permissionPromise && permissionPromise.then) {
            // Promise-based API
            permissionPromise.then(function (permission) {
              handlePermissionResult(permission);
            }).catch(function (error) {
              log("Permission request error: " + error.message);
            });
          } else {
            // Callback-based API (old Safari)
            Notification.requestPermission(function (permission) {
              handlePermissionResult(permission);
            });
          }
        } catch (error) {
          log("Exception in requestPermission: " + error.message);
        }

        banner.remove();
      });

    document
      .getElementById("safari-banner-close")
      .addEventListener("click", function () {
        banner.remove();
        // Remember that user dismissed the banner
        localStorage.setItem(
          "safari-notification-banner-dismissed",
          Date.now()
        );
      });

    // Auto-hide after 10 seconds
    setTimeout(function () {
      if (banner.parentNode) {
        banner.style.transition =
          "transform 0.3s ease-out, opacity 0.3s ease-out";
        banner.style.transform = "translateY(-100%)";
        banner.style.opacity = "0";
        setTimeout(() => banner.remove(), 300);
      }
    }, 10000);

    // Check if user previously dismissed the banner (don't show again for 24 hours)
    const dismissed = localStorage.getItem(
      "safari-notification-banner-dismissed"
    );
    if (dismissed && Date.now() - parseInt(dismissed) < 24 * 60 * 60 * 1000) {
      banner.remove();
      return;
    }
  }

  /**
   * Check if browser is Safari
   */
  function isSafari() {
    return /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
  }

  /**
   * Check if device is iOS Safari (iPhone/iPad)
   */
  function isiOSSafari() {
    return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
  }

  /**
   * Handle permission result
   */
  function handlePermissionResult(permission) {
    log("Permission result: " + permission);
    if (permission === "granted") {
      log("Permission granted, checking for existing token");

      // Check if user is logged in and has stored token
      if (isUserLoggedIn()) {
        const storedToken = getStoredToken();
        if (storedToken) {
          log("Found stored token for logged in user, sending to server immediately");
          saveTokenToServer(storedToken);
          // Save user status to Firebase
          saveUserNotificationStatusToFirebase(storedToken, true);
        } else {
          log("No stored token found, getting new token");
          if (messaging) {
            getToken();
          } else {
            log("Messaging not initialized yet");
          }
        }
      }

      // Hide Safari banner if it exists
      const banner = document.getElementById("safari-notification-banner");
      if (banner) {
        banner.remove();
      }
    } else if (permission === "denied") {
      log("Permission denied");
      // Mark permission as asked (denied) in cross-domain cookie
      markPermissionAsked();
      // For Safari, show a helpful message
      if (isSafari()) {
        showSafariPermissionDeniedMessage();
      }
    } else {
      log("Permission dismissed or unknown result: " + permission);
    }
  }

  /**
   * Show permission request dialog for logged in users only
   */
  function showPermissionDialog() {
    log("Showing permission dialog");

    // Double-check that user is logged in
    if (!isUserLoggedIn()) {
      log("User not logged in, not showing permission dialog");
      return;
    }

    // Check if dialog already exists
    if (document.getElementById("firebase-permission-dialog")) {
      return;
    }

    // Mark that we've asked for permission
    markPermissionAsked();

    // Create overlay
    const overlay = document.createElement("div");
    overlay.id = "firebase-permission-dialog";
    overlay.style.cssText = `
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: 10000;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    `;

    // Create dialog
    const dialog = document.createElement("div");
    dialog.style.cssText = `
      background: white;
      border-radius: 12px;
      padding: 30px;
      max-width: 400px;
      margin: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
      text-align: center;
      position: relative;
    `;

    dialog.innerHTML = `
      <div style="font-size: 48px; margin-bottom: 20px;">🔔</div>
      <h3 style="margin: 0 0 15px 0; color: #333; font-size: 20px; font-weight: 600;">
        Enable Notifications?
      </h3>
      <p style="margin: 0 0 25px 0; color: #666; line-height: 1.5; font-size: 14px;">
        Get important updates, new listings and messages directly in your browser. 
        You can always disable them in settings.
      </p>
      <div style="display: flex; gap: 10px; justify-content: center;">
        <button id="firebase-dialog-allow" style="
          background: #4CAF50;
          color: white;
          border: none;
          padding: 12px 24px;
          border-radius: 8px;
          cursor: pointer;
          font-size: 14px;
          font-weight: 500;
          transition: background 0.2s ease;
        ">
          Allow
        </button>
        <button id="firebase-dialog-deny" style="
          background: #f5f5f5;
          color: #666;
          border: 1px solid #ddd;
          padding: 12px 24px;
          border-radius: 8px;
          cursor: pointer;
          font-size: 14px;
          font-weight: 500;
          transition: all 0.2s ease;
        ">
          Not Now
        </button>
      </div>
    `;

    overlay.appendChild(dialog);
    document.body.appendChild(overlay);

    // Add event listeners
    document.getElementById("firebase-dialog-allow").addEventListener("click", function () {
      // For Safari, we need to call requestPermission synchronously
      log("User clicked Allow, requesting permission synchronously");

      // Call requestPermission immediately in the click handler
      if (isSafari()) {
        log("Safari detected, calling requestPermission synchronously");
        // Remove overlay first
        overlay.remove();

        // Call requestPermission synchronously for Safari
        try {
          const permissionPromise = Notification.requestPermission();
          if (permissionPromise && permissionPromise.then) {
            // Promise-based API
            permissionPromise.then(function (permission) {
              handlePermissionResult(permission);
            }).catch(function (error) {
              log("Permission request error: " + error.message);
            });
          } else {
            // Callback-based API (old Safari)
            Notification.requestPermission(function (permission) {
              handlePermissionResult(permission);
            });
          }
        } catch (error) {
          log("Exception in requestPermission: " + error.message);
        }
      } else {
        // For other browsers, use the regular flow
        overlay.remove();
        requestPermission();
      }
    });

    document.getElementById("firebase-dialog-deny").addEventListener("click", function () {
      overlay.remove();
      log("User declined permission dialog");
    });

    // Close on overlay click
    overlay.addEventListener("click", function (e) {
      if (e.target === overlay) {
        overlay.remove();
        log("Permission dialog closed by clicking overlay");
      }
    });

    // Auto-close after 30 seconds
    setTimeout(function () {
      if (overlay.parentNode) {
        overlay.remove();
        log("Permission dialog auto-closed after 30 seconds");
      }
    }, 30000);
  }
})();
