<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'aablueprint_com');

/** MySQL database username */
define('DB_USER', 'aablueprintcom');

/** MySQL database password */
define('DB_PASSWORD', 'G!^Surdi');

/** MySQL hostname */
define('DB_HOST', 'mysql.aablueprint.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'EmXphsb*I:@4ZJbfD7Eau0jm"Z5/vz(U7@!*##_MtBcP/|)5o1gxNMy^~GSt*xy#');
define('SECURE_AUTH_KEY',  'ct)vKl+AO@NAbZb0M@GQmCF^q"^~Wg0kw2q/R()Vp&rZ~xZvA"*GN"oin6@XQ+Pn');
define('LOGGED_IN_KEY',    '/kunFKzsXAE&zhj0OBb#wrB2ArhGrxEs;*mBQYV8oskpUbXvIJWBG(4k;Zw(Gipx');
define('NONCE_KEY',        'ivRNX~aO)(hv:9FwBkh$/H26q|UOC9dKnbB;9HqOBT~V9R%t;(C5T&akSKd(+q7E');
define('AUTH_SALT',        'J8uLcf9w|`eKdUwk$?C$a)^a~D2wkql_##O^3;A???54u`UW_gQI&3OoK!F:DBR0');
define('SECURE_AUTH_SALT', '8X~rUmnFIw;(NNIvrEeJr1h4i2ycCMXIh`STk$wZW@eMqm6banb`MRltMX!CT(9j');
define('LOGGED_IN_SALT',   '8rS?VxoF6w"vUX#$uWyM2SjD$?!*95C`NxX5Y@PY9aUM6kk#1r|BJRa4q$IgF7Dz');
define('NONCE_SALT',       '^h;wo$)dT2l5%;uz#I(d7)R^|~*XFW6_FudKbycZN"V;qJ/T#iQZ00:/(ShCV9%2');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_423wab_';

/**
 * Limits total Post Revisions saved per Post/Page.
 * Change or comment this line out if you would like to increase or remove the limit.
 */
define('WP_POST_REVISIONS',  10);

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

