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
define( 'DB_NAME', 'if0_37957997_wp100' );

/** Database username */
define( 'DB_USER', '37957997_15' );

/** Database password */
define( 'DB_PASSWORD', '2QSc8[-p9O' );

/** Database hostname */
define( 'DB_HOST', 'sql313.byetcluster.com' );

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
define( 'AUTH_KEY',         'rvfozdtth0eegx1xspfngkooa9ccyogtymbl3aggfol5rtx1wuvmen99ro4euzlu' );
define( 'SECURE_AUTH_KEY',  'haohd249zbjznnkebmqaxzijlcvh0xlvdaoxxbcxlocpzo9d6h8idztc30ngluzg' );
define( 'LOGGED_IN_KEY',    'hj6y6qcdadrfcoeyrs8j50v6hs4akeqi0yzs5qgh2ttnw5irhnox973bbc5qy3qt' );
define( 'NONCE_KEY',        'xkck7lj7tqibdaieggrfoq0fyip1qwvmr07gmdpdzozpvham62ph00mkh2eltyjv' );
define( 'AUTH_SALT',        '9yusasr2noaeqcuvpswigiv1bsp8odqyngiliwp9szmmnvdtgpiphchr6vceogys' );
define( 'SECURE_AUTH_SALT', 'ilkfgu9j16ivvd6zwgsaowtpxbzkdcnnbwqkkgd9icamjzgrrkdqcpgul1qbroad' );
define( 'LOGGED_IN_SALT',   'bdqpr1pcgf1g0urobqoqj9xovergyygqdy2psnxt7xonjdtlk8cvseqj3qjv8wp9' );
define( 'NONCE_SALT',       't48ntk4kbijplsvyiwrrcnqe2obqph2ntsx41nb8gyterh6a1icfypvdejecej1e' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpmi_';

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



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
