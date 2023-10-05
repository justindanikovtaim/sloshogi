<?php
/**
* Defines global configuration and constants used in the application.
*
* @description These variables help maintain a cleaner and maintainable codebase.
* @namespace
*/

/**
* Absolute path used for PHP file requires, set to root directory.
*
* @constant {string} ABSPATH - Root directory path for PHP file includes.
*/
defined('ABSPATH') or define('ABSPATH', __DIR__ . '/src/');

/**
* Path to shared resources used across the application.
*
* @constant {string} SHAREDPATH - Path to shared resources directory.
*/
defined('SHAREDPATH') or define('SHAREDPATH', __DIR__ . '/src/shared/');

/**
* Configuration for MariaDB database connection.
*
* @constant {string} DBHOST - Database host name.
* @constant {string} DBUSER - Database username.
* @constant {string} DBPASSWORD - Database password.
* @constant {string} DBNAME - Database name.
*/
defined('DBHOST') or define('DBHOST', 'localhost');
defined('DBUSER') or define('DBUSER', 'christopherd');
defined('DBPASSWORD') or define('DBPASSWORD', 'A*3BYyM5o#Qcs');
defined('DBNAME') or define('DBNAME', 'sloshogi');
