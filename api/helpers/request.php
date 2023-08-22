<?php

/**
 * Retrieves the request body as a JSON object.
 *
 * @throws Exception if there is an error decoding the JSON or reading the file.
 * @return mixed the decoded JSON object representing the request body.
 */
function getRequestBody(): mixed {
	return json_decode(file_get_contents('php://input'));
}

/**
 * Extracts the request path from the current URL.
 *
 * @return string The request path.
 */
function extractRequestPath(): string {
	return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
}

/**
 * Retrieves the query parameters from the current HTTP request.
 *
 * @return array The query parameters as an associative array.
 */
function getQueryParams(): array {
	return $_GET;
}

/**
 * Retrieves the request method used in the current HTTP request.
 *
 * @return string The request method (e.g., "GET", "POST", "PUT", etc.).
 */
function getRequestMethod(): string {
	return $_SERVER["REQUEST_METHOD"];
}