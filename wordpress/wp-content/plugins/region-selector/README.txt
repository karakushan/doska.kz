=== Region Selector for Subdomains ===
Contributors: adshelppro
Donate link: https://t.me/st4rpay
Tags: region, language, subdomain, geoip, translatepress, multi-language
Requires at least: 5.8
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 2.3.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Smart region and language selector for WordPress using subdomains. Detects user location via browser language and optional GeoIP, displays a modal country selector, and redirects users to the appropriate localized subdomain. Fully compatible with TranslatePress.

== Description ==

Region Selector for Subdomains is a WordPress plugin that:

* Detects user location via browser language.
* Optionally detects location using GeoIP (Cloudflare / Apache / CDN).
* Displays a modal with flag and country name.
* Redirects to the correct subdomain (en, es, de, tr, etc.).
* Compatible with TranslatePress and multi-domain setups.
* Includes ESC key and click-on-overlay to close modal.
* Cookie-based system prevents repeated pop-ups.

== Installation ==

1. Upload the `region-selector` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings → Region Selector to configure your countries.
4. Optional: Enable GeoIP detection for soft auto-redirection.
5. Save settings and test.

== Frequently Asked Questions ==

= How does GeoIP work? =

The plugin reads the country from standard headers (`HTTP_CF_IPCOUNTRY`, `GEOIP_COUNTRY_CODE`, `HTTP_X_COUNTRY_CODE`). No redirect occurs if a cookie is already set.

= Can I customize modal text? =

Yes, in the admin panel you can set the modal title for each country.

= Will it work with TranslatePress? =

Yes, it's fully compatible with TranslatePress subdomains setup.

== Screenshots ==

1. Admin settings page
2. Modal with country selection
3. Flag + country buttons

== Changelog ==

= 2.3.0 =
* Added GeoIP detection
* Auto redirect by browser language
* ESC and overlay click close modal
* Animated modal open/close
* Cookie versioning to force reset after settings change

= 2.2.0 =
* Initial public release
* Modal with country buttons
* Flag emoji support
* Admin page for countries

== Upgrade Notice ==

v2.3 introduces GeoIP detection and better auto-redirect logic. If upgrading from older versions, your old cookie may need to be cleared for modal to reappear.

== Arbitrary section ==

For developers: https://adshelppro.com/region-selector