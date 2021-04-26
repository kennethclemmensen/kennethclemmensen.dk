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
define('DB_NAME', 'kennethclemmensen_test_db');

/** MySQL database username */
define('DB_USER', 'TestUser');

/** MySQL database password */
define('DB_PASSWORD', 'TestUserForLocalhost');

/** MySQL hostname */
define('DB_HOST', 'localhost'); //Docker: host.docker.internal

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
define('AUTH_KEY', '_M;*sC%.me{vPPZ?#yWZ/^Zk-GF@M:}jaV_r8O-~Ow/qXf2H+*b~z]lMLlQ,rZ:f');
define('SECURE_AUTH_KEY', '}V>GCHl8?6pk/}P8Qd?QnU-$Wg[i!2|3||{wAjmyXAbJOFODvC-]fRb{WJ9&tY+X');
define('LOGGED_IN_KEY', 'NV` E:I-W-8DqkfgHvEZ(61!dwR51_J,De=ffHA/?ViGqy8$08F*)Yc=q!=ReVvD');
define('NONCE_KEY', '1tH`[ux++qhc-hN&ml,gsxG GkHG#-cNZHf1{NL|Wx%im(bsvu32[)QDq%C0-9zp');
define('AUTH_SALT', 'Dj4t#:+KTz:<32B6|uLzSQ.zF]!JC0DU3cFRiQaU=%[<q#_Gz&TEZ7wC$Rq|?tY_');
define('SECURE_AUTH_SALT', 'mBOS]>9tU2~A~V.r?A[4 oevK(8--@gh,>1rQR@~Sng]ok>8AeXwPs~m6r|{PSv0');
define('LOGGED_IN_SALT', '+I8)+aF$el#TUl AlE`/5S 05;^^a7joU#Srq~AuWTh!qoo^u-3hcOpd*sdifoR-');
define('NONCE_SALT', ']M3!K|a08G6nNI:a?FU-#uopOqF,`m#GGB`j(y<fkA+L*Tmy81_G%Py-rIJ]@+m%');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'kcwp_';

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
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if(!defined('ABSPATH'))
    define('ABSPATH', dirname(__FILE__).'/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH.'wp-settings.php');
