<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'realsale_reals');

/** MySQL database username */
define('DB_USER', 'realsale_reals');

/** MySQL database password */
define('DB_PASSWORD', 'szE)*uYj%+4E');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'hsX/U6}uICQ92>DvtaLjr`V=1N:qCp`F^eSpzOe1$Y[BG[J^*{8Op$lLLs@?CoPE');
define('SECURE_AUTH_KEY',  '61G?2+>de/ucM4?!Z7~*v@^DJ%xlG5K;0><|)@hE. d@ d)]mr}=+[qG)<s&sufo');
define('LOGGED_IN_KEY',    'V?&rd1JPSV|s@4;SsCTo5n;g9?fu~dVhf%!j(Fm /hLpDOEQ=@a$zZxMgeL*g,Qq');
define('NONCE_KEY',        '+hti0F.mM`DxC8*Snt}cl!:Se._XE:[=J@xp<QGevfQz!lja^}Ym(XC6Jj]q[ieC');
define('AUTH_SALT',        'H7Em_yUif9On&_|z? ZmV=dozcajPd8:0@`l{5k,DTT2o~!#p~6t+yoYdq%*2j{1');
define('SECURE_AUTH_SALT', 'S|oQ;^{u!PsAO(-s_DR:/Sf7@Lq($[ABxJ@obsdp{7P,ax]V3Ur-5vb/v4nXq(QY');
define('LOGGED_IN_SALT',   '^Pdz9!]tZipvuXe7{qW[^fXO%)u&:e!9K.ynG6Uid%6us>LR3E?ks.5m&.$5;5Or');
define('NONCE_SALT',       'jAb@Fo4C (&|?#Us.y_5.]-5X@U.a37K[-^nkJPVbe3{2!Bt,0^Zlj!ohR4$.Kf#');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);
define('WP_MEMORY_LIMIT', '256M');
define( 'WP_MAX_MEMORY_LIMIT', '256M' );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
