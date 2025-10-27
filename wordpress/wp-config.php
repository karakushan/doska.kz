<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', "wordpress" );

/** Database username */
define( 'DB_USER', "wordpress" );

/** Database password */
define( 'DB_PASSWORD', "wordpress" );

/** Database hostname */
define( 'DB_HOST', "db" );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'f`_jZeoEW)2C9;$(9<JfKzDZ.`s2GjF6v-_s5%Wwy1z1y5? /Z6xX&0RW>/ )pPu' );
define( 'SECURE_AUTH_KEY',  'C6M<5T%#4SD!q5`gH..)>I~V#:vT`hwo.pLop`cI@#bi;#+3XrCzr=pa=gi$+m%e' );
define( 'LOGGED_IN_KEY',    '3VBSw?,uDv~;SO{{h$rv[Uw~zyQKr?YQ,>v3inh|,{/=3!1=0P~{sbP=PY+R=/Co' );
define( 'NONCE_KEY',        'PsxUe-8y)oHHl|P=lhX0PGE7,&K9gYN_mA1xyS_A9+`{&)P~%W0F2uM}8E~OSY/M' );
define( 'AUTH_SALT',        'kFEF[k[6;0 SA{U.ZAeud@fqeyOEhU)n&^)w;@CX| R~:g;NWqU.AR`b&DquhjG0' );
define( 'SECURE_AUTH_SALT', 'vb8,;a2>7WCNF130[/@r,qAHyM<fMiR6-d|HWp#w~G)oZZu!xS5N`S ct!S)D>uj' );
define( 'LOGGED_IN_SALT',   '{EDLSR%2-[_wbQTQ-DiQYsEkX{i$soe=VcK3`X8S%_pQu_5n<syH2H,wO*/iUzdH' );
define( 'NONCE_SALT',       'X*tE!| *%~vem,%VQ2X]O+~i HqZf3Y<n|[a5Ds6UzYhxYWUtVZT+fB$(_b`8?8x' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', true );
// Enable Debug logging to the /wp-content/debug.log file
define( 'WP_DEBUG_LOG', true );
// Disable displaying debug info on the page
define( 'WP_DEBUG_DISPLAY', false );
// Error reporting - exclude Deprecated, Strict, and Notices
error_reporting( E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE );

/* Add any custom values between this line and the "stop editing" line. */
// Увеличиваем лимиты PHP
ini_set('memory_limit', '512M');
ini_set('upload_max_filesize', '64M');
ini_set('post_max_size', '64M');

// WordPress memory limit
define( 'WP_MEMORY_LIMIT', '512M' );
define( 'WP_MAX_MEMORY_LIMIT', '512M' );

// PHP Maximum Execution Time
ini_set('max_execution_time', '300');


/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
