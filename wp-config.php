<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'redtagtr_bfwp');

/** MySQL database username */
define('DB_USER', 'redtagtr_bfwp');

/** MySQL database password */
define('DB_PASSWORD', 'Beaufairy123!');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/* Disable wordpress auto update including plugins */
define( 'AUTOMATIC_UPDATER_DISABLED', true );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'fic|+S;g}.n~0jm&W@swr|j?wCgCr<F9vM!vwcb4t?DUqRJtNBAk&Wd2eL=p5/A*');
define('SECURE_AUTH_KEY',  '|9)EjC!!3r),SCKLy3]i `SDQ)BMP.E14)K=2yZt@WKgA1-A^sU33b4>fM<F<-cw');
define('LOGGED_IN_KEY',    '81?vYl_+;UPQr1/>r;&ODy~%i!p-s-GUKK{GxuUY/u~j.dxxT=B#s!Q!Tzj2`xX&');
define('NONCE_KEY',        'UYD4U|vuTe3/RB-ywH5<$42!k|}m@X40lLuVVkhC<5y{vN8e+zn)6QealST#,AR~');
define('AUTH_SALT',        'f0lpsl&(1h.jD7-%.>OU+sJNr}SHhxhtF`DG^pP3pcrurt)a+hK)J%x$9a%%Fl|w');
define('SECURE_AUTH_SALT', 'kVw_yr8If_L,~2wH|mz4%Z$g%KC(5!U{I |5Qt|NPS:xCzMVaPIg}9]JvWUARj;^');
define('LOGGED_IN_SALT',   't^c?7o&lHjiggmMFEk}bmIn&bFja&iR|0m~jsiY=Wg5w!oB=b|RO(ql Bc+8z-$#');
define('NONCE_SALT',       '9Ck1P0J)i5O8KPPDS45`*|Ykng+-W:yJ<vNSr~xtt{hy|t_{h>)k;K`%imK*M~*7');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
