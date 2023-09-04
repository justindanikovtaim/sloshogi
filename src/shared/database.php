<?php

/**
 * Utilities for interacting with the database.
 *
 * @package database
 */

/**
 * Connects to the database.
 *
 * @return mysqli The database connection
 */
function connect_to_db()
{
    $connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBNAME);

    if (!$connection) {
        require_once SHAREDPATH . 'db-error.php';
        die();
    }

    return $connection;
}

/**
 * Closes the database connection.
 *
 * @param mysqli $connection The database connection
 */
function close_db_connection($connection)
{
    mysqli_close($connection);
}

/**
 * Executes a query on the database.
 *
 * @param string $query The query to execute
 * @param array $params The parameters to bind to the query
 * @return mysqli_result|bool The result of the query
 */

function safe_sql_query(string $query, array $params = [])
{
    $connection = connect_to_db();
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, ...$params);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        die('Could not execute query: ' . mysqli_error($connection));
    }
    close_db_connection($connection);

    return $result;
}
