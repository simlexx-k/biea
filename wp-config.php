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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'biea' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         '#MIA3}pu**{Fxm:T6UBA3bP8zIcBAIFT jO$W;e)Cw:z|#ZN9?cLrVl sdX9Rnu6' );
define( 'SECURE_AUTH_KEY',  ']TYi0tI{-HMG1c+7Tb=GG5?)_.EicrK{R&lgm+VZkI*6XvQ!{%~q~0,+bkS/ADq<' );
define( 'LOGGED_IN_KEY',    '#o=XRu8c1NA(!U6+eR}R07_;}JFL*]2?]zUjD6<596p(~qmkJ4_S(DmUx%@{iG#E' );
define( 'NONCE_KEY',        '>$79u2nWM8%IVXaC5,NhxEOhN.]H7R!bRj^c]n]Zn$A&2B6i?m`QWhy{s=H8gUnE' );
define( 'AUTH_SALT',        'j:?96t-% <0p6zigNtg5VvR,ge8gIBBEO%7jVp;/O[0SNSfcz~*@83phdP@8.%~9' );
define( 'SECURE_AUTH_SALT', 'N$y#WI|G/N gVWS>U%+kG?EKlg6cws&`k0VPUnUfv,<LGpsW8eAQy*8YS|Y: O)e' );
define( 'LOGGED_IN_SALT',   ':`O=s(F2%R^f%q@*@L6*CMz!j^h)6f*TZTckwb0.!irgv32AdY(lT>=YATGagq-,' );
define( 'NONCE_SALT',       'B%O!X.hJ~y}63/g%aKC)j,%Z5Zl$FQJv/[nH@wS4h#m_v=*~O%pawwu.NuADr3VI' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'biea_';

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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
