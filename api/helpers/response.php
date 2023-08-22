<?php

/**
 * Responds to a request by setting the HTTP response code, adding headers, and sending a JSON response.
 *
 * @param mixed $response          The response data to be encoded as JSON.
 * @param int   $status            The HTTP status code to be set in the response.
 * @param array $additionalHeaders Additional headers to be included in the response.
 *
 * @return never
 */
function respondRequest(mixed $response, int $status = 200, array $additionalHeaders = []): never {
	http_response_code($status);
	header("Content-Type: application/json");
	
	if (count($additionalHeaders)){
		foreach ($additionalHeaders as $headerName => $headerValue) {
			header($headerName . ": " . $headerValue);
		}
	}
	
	exit(json_encode($response));
}