<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',          'h6?&8vH^v=0wAa}w:tlHZ=-VWVA{jjyJF:dL)) )8.[s!]P8}@N&F:;c]u@K}j$4' );
define( 'SECURE_AUTH_KEY',   'vk7881_o/=GK03CMvXyq*|uI]<$}e|_Y;0&>Pv`LWTcA# 1<y,Z)*oUp9+]^L4-d' );
define( 'LOGGED_IN_KEY',     '@C{796xAv3NMZ7#X>!jUINXCIIjZ_V-_C2]< %[8a=4b%CP]N(;BJFiHrAgQ?QAJ' );
define( 'NONCE_KEY',         'JBg_Y_v,AOQd/p4;hnGu(cw>kMnKcJZ1yRsFOze[_p2K#S8,a-.]tf)bbBDxQ?,_' );
define( 'AUTH_SALT',         '+5tR1D5r;OSO~l)`{%#8Ltt!qbQR`,YrqKTx<P1GQ~wl4r EL$D]PF^`r*S`jHjm' );
define( 'SECURE_AUTH_SALT',  'ulVdl(y9_ btZJsMN{PIEjO|j[(Fz@|&r(j=h1{H:`0]br gd}dKZ$ct_%dE4pi2' );
define( 'LOGGED_IN_SALT',    '},RKSQL|!?QOeKbl#5o9Gxta@j}6ficpljPbG$[8|r}0l^:5m^}}b$<}2da}U-@o' );
define( 'NONCE_SALT',        'sJN6+D_i{{3u!8WlTycS}Vhr[nC^SL/io[[(R 9u=Grdw;hOgN;yk^]h_re[ 1C~' );
define( 'WP_CACHE_KEY_SALT', 'od/aT@.8={-(t#<S<L15&lm0B7y?=UDU@P,@jiL`^qH[)linLeYxST9=UF{%=~*F' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
