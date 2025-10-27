# Firebase Push Notifications WordPress Plugin

**Author:** Vitaliy Karakushan  
**Version:** 1.0.0  
**License:** GPL v2 or later

## Description

Firebase Push Notifications plugin provides comprehensive Firebase Cloud Messaging integration for WordPress. It enables push notifications for various events including messages, ad expiration, and ad deactivation.

## Features

- ✅ **Firebase Cloud Messaging Integration**
- ✅ **Push Notifications for Messages**
- ✅ **Ad Expiration Notifications**
- ✅ **Ad Deactivation Notifications**
- ✅ **User Notification Preferences**
- ✅ **FCM Token Management**
- ✅ **Admin Dashboard**
- ✅ **Service Account JSON File Upload**
- ✅ **Comprehensive Diagnostics**
- ✅ **Multi-language Support**

## Installation

1. Upload the plugin files to `/wp-content/plugins/firebase-push-notifications/`
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to Firebase Push → Configuration to set up Firebase
4. Configure your Firebase project settings

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- Firebase project with Cloud Messaging enabled
- Service Account JSON file

## Configuration

### 1. Firebase Setup

1. Go to [Firebase Console](https://console.firebase.google.com/)
2. Create a new project or select existing one
3. Enable Cloud Messaging
4. Generate Service Account JSON

### 2. Plugin Configuration

1. Go to **Firebase Push → Configuration**
2. Enable Firebase Push Notifications
3. Enter your Firebase Project ID
4. Upload Service Account JSON file
5. Configure VAPID key for web push
6. Save settings

### 3. Testing

1. Go to **Firebase Push → Test Notifications**
2. Select a user with registered devices
3. Send test notification
4. Verify delivery

## Usage

### For Users

Users can manage their notification preferences in their dashboard:
- Enable/disable specific notification types
- View registered devices
- Delete device tokens

### For Administrators

Administrators have access to:
- Firebase configuration
- Notification testing
- User device management
- Comprehensive diagnostics

## API

### Hooks

```php
// Send notification to user
do_action('firebase_send_notification', $user_id, $title, $body, $data);

// Handle listing expiration
do_action('firebase_listing_expired', $listing_id);

// Handle listing deactivation
do_action('firebase_listing_deactivated', $listing_id);
```

### Functions

```php
// Get Firebase Manager instance
$firebase_manager = FirebaseManager::getInstance();

// Check if Firebase is initialized
if ($firebase_manager->isInitialized()) {
    // Send notification
    $firebase_manager->sendNotificationToUser($user_id, $title, $body);
}
```

## File Structure

```
firebase-push-notifications/
├── firebase-push-notifications.php    # Main plugin file
├── includes/
│   ├── class-firebase-manager.php     # Firebase Manager
│   ├── class-notification-handler.php # Notification Handler
│   └── class-firebase-push-notifications.php # Main class
├── admin/
│   ├── firebase-config.php            # Configuration page
│   ├── test-push-notifications.php    # Test notifications
│   └── firebase-test.php              # Diagnostics
├── assets/
│   ├── js/
│   │   ├── firebase-init.js           # Firebase initialization
│   │   └── service-worker.js          # Service Worker
│   └── css/
│       └── admin.css                  # Admin styles
├── vendor/                            # Composer dependencies
└── composer.json                      # Composer configuration
```

## Troubleshooting

### Common Issues

1. **Firebase not initialized**
   - Check Service Account JSON
   - Verify Composer dependencies
   - Check PHP error logs

2. **Notifications not received**
   - Verify FCM tokens
   - Check browser notification permissions
   - Test with Firebase Console

3. **Service Worker errors**
   - Check file paths
   - Verify HTTPS connection
   - Check browser console

### Diagnostics

Use the built-in diagnostics tool:
1. Go to **Firebase Push → Configuration**
2. Click **"🔍 Детальный тест Firebase"**
3. Review all test results
4. Fix any issues found

## Support

For support and bug reports, please visit:
- GitHub Issues: [https://github.com/vitaliy-karakushan/firebase-push-notifications](https://github.com/vitaliy-karakushan/firebase-push-notifications)
- Email: [vitaliy.karakushan@example.com](mailto:vitaliy.karakushan@example.com)

## Changelog

### 1.0.0
- Initial release
- Firebase Cloud Messaging integration
- Push notifications for messages, ads
- Admin dashboard
- Service Account JSON upload
- Comprehensive diagnostics

## License

This plugin is licensed under the GPL v2 or later.

## Credits

- Firebase PHP SDK by [Kreait](https://github.com/kreait/firebase-php)
- Firebase JavaScript SDK by Google
- WordPress Plugin API