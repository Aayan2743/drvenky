<?php
define( 'WP_CACHE', true );
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
define( 'DB_NAME', 'kozlmwpj_wp180' );

/** Database username */
define( 'DB_USER', 'kozlmwpj_wp180' );

/** Database password */
define( 'DB_PASSWORD', 'S87uB(@49p' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',         'cewadbkb7krpqrwyvsc9e2kfduht0dhffs4byohtxevyxd3ktrwpmpg0vvqysdsx' );
define( 'SECURE_AUTH_KEY',  'vw2gwfbfcnzddwc7tvwpgwfiv4s9kum6luzobtdj5uussywa5z2jki3pwnpihcpa' );
define( 'LOGGED_IN_KEY',    'wkdcigtlxzr18woueuwofpckxtaytwv4lclru7qreiz5ilqjojoj9osluesjc76x' );
define( 'NONCE_KEY',        '2a0zyrnwg7ky9nxxglqtfr3y8jg7gjufsoveqzlp82gzwk2cg8vm7fdem9bg3pnr' );
define( 'AUTH_SALT',        'dn4bhjkdtu2wtzuxaapf33ejvkoq4sechkn4tdgptzwgwmxhmrmy8gulue7buzsp' );
define( 'SECURE_AUTH_SALT', 'xaq705qrjo8ezbjexlij5s8vvzqwzvbweq9b5ea2wdkkbadjm0zltu1jt7ipxgby' );
define( 'LOGGED_IN_SALT',   'saczjvijv9eclxjr2v8hgb0zqnzcvhejx5l6sbveicbprjaghljziptwkrow40oe' );
define( 'NONCE_SALT',       'zruzwuoxyr8txcr5mqmocmqynkravvmfpd5qbljxj2qnwbebsjawapfp3ujqqufn' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wphn_';

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



define( 'DISALLOW_FILE_EDIT', true );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
