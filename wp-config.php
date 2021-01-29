<?php
define( 'WP_CACHE', true ); // Added by WP Rocket
define('EMPTY_TRASH_DAYS', 0);
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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'subJPauto' );

/** MySQL database username */
define( 'DB_USER', 'subJPauto' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Kh3ume28ON' );

/** MySQL hostname */
define( 'DB_HOST', 'mariadb103.websupport.sk:3313' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'ewqCcVZ@;1r5zyJ(K:oZbbMin]HON#@4TPB;W=(?_:Da0#U$fl97~wBQpVX+J.n2' );
define( 'SECURE_AUTH_KEY',  'bu|{z}:J@Poe|2TrKErL(4>geBlTTc6+=%4z8<$NegY_3>vT2[j(7L)n8d>m:u_o' );
define( 'LOGGED_IN_KEY',    'U>lt[zjFH|yS.#61,y+%KY&9KHs%dfN^gI=pTbr]E_qDZxz5YAsOa^5oyUsrax^&' );
define( 'NONCE_KEY',        'HEOaph5$:>2jZg]jm`LxI_1.1<Q[q8dTN[Z?K[;uq-b_`m~}E<lMDmAU>r8[_*YP' );
define( 'AUTH_SALT',        'rt/*@^F$I,#jk EFC~iI4JL,@KkFFgDq].?x)[jJ |G7g03izq)LTx[l!h).tv{x' );
define( 'SECURE_AUTH_SALT', '9E&1]QVk- {L<8KZ#aqR[gC+X`~I_g.$}5Ld]4Vc]XuEP9R;KS,F)4:|/W26K/SO' );
define( 'LOGGED_IN_SALT',   'cSixyr_c_W[(kXo|N<F4kw+2VJ&cbD<eo!3tbP7BJ%T, %z4p5sV&g7=r_4IsJmE' );
define( 'NONCE_SALT',       'H:Gp{q*i6EprN@N!c9Ja<*q}_?#@ieubZO/#n/i{xB1wo0SNLwQ!%xC&i%ysn=wQ' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );
define('WPCF7_AUTOP', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
