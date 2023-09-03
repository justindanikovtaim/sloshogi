<?php

/**
 * It defines the global configuration and constants used in the application.
 *
 * These variables help to maintain a cleaner and maintainable codebase.
 */

/**
 * Absolute path used for PHP file requires, it is set to root directory.
 */
defined('ABSPATH') or define('ABSPATH', __DIR__);

/**
 * Configuration for MariaDB database connection
 */
defined('DBHOST') or define('DBHOST', 'localhost');
defined('DBUSER') or define('DBUSER', 'christopherd');
defined('DBPASSWORD') or define('DBPASSWORD', 'A*3BYyM5o#Qcs');
defined('DBNAME') or define('DBNAME', 'sloshogi');
