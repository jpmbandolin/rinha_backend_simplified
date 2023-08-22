<?php

/**
 * Returns a PDO connection object.
 *
 * @throws PDOException if there is an error connecting to the database.
 * @return PDO the PDO connection object.
 */
function getPDOConnection(): PDO {
	$dsn = 'mysql:dbname=rinha;host=mysql';
	return new PDO($dsn, "root", "password");
}

/**
 * Executes an SQL query with optional parameters and returns a PDOStatement object.
 *
 * @param string $sql The SQL query to execute.
 * @param array $args An optional array of parameters to bind to the query.
 * @throws PDOException If there is an error executing the query.
 * @return PDOStatement The PDOStatement object representing the executed query.
 */
function execSQL(string $sql, array $args = []): PDOStatement
{
	$pdo = getPDOConnection();
	$preparedStatement = $pdo->prepare($sql);
	
	if (count($args)) {
		$preparedStatement->execute($args);
	} else {
		$preparedStatement->execute();
	}
	
	return $preparedStatement;
}

/**
 * Fetches data from the database using the provided SQL query.
 *
 * @param string $sql The SQL query to execute.
 * @param array $args An array of arguments to bind to the query.
 * @throws PDOException If there is an error executing the query.
 * @return array|false An array of objects representing the fetched data, or false if there was an error.
 */
function fetchDatabaseData(string $sql, array $args = []): array|false
{
	$preparedStatement = execSQL($sql, $args);
	
	$response = $preparedStatement->fetchAll(PDO::FETCH_CLASS, stdClass::class);
	$preparedStatement->closeCursor();
	
	return $response;
}