<?php

/**
 * Retrieves the person ID from the given request path.
 *
 * @param string $requestPath The request path containing the person ID.
 * @throws InvalidArgumentException If the request path is not a string.
 * @return string The person ID extracted from the request path.
 */
function getPersonIdFromRequestPath(string $requestPath): string {
	$explodedPath = explode("/", $requestPath);
	return $explodedPath[count($explodedPath) - 1];
}

/**
 * Validates if the provided person data is valid for a POST request.
 *
 * @param object $personData The person data object to be validated.
 *
 * @return bool Returns true if the person data is valid, false otherwise.
 */
function isPostPersonRequestValid(object $personData): bool {
	$personData->stack = $personData->stack ?? [];
	
	if (!isset($personData->apelido, $personData->nome, $personData->nascimento)){
		return false;
	}
	
	if (!is_array($personData->stack) || strlen($personData->apelido) > 32 || strlen($personData->nome) > 100 || !isDateValid($personData->nascimento)) {
		return false;
	}
	
	foreach ($personData->stack as $item) {
		if (!is_string($item) || strlen($item) > 32){
			return false;
		}
	}
	
	return true;
}

/**
 * Generates a string representation of the given stack.
 *
 * @param array $stack The stack to convert into a string.
 *
 * @return string The string representation of the stack.
 *@throws false|string If the stack contains non-string items or if any item's length exceeds 32 characters.
 */
function getStackString(array $stack): string {
	$stackString = "";
	
	foreach ($stack as $index => $item) {
		if (!is_string($item) || strlen($item) > 32){
			return false;
		}
		
		$stackString .= (($index === 0 ? "" : "ß") . $item);
	}
	
	return $stackString;
}

/**
 * Determines whether a given date is valid.
 *
 * @param string $date The date to check in the format "Y-m-d".
 *
 * @return bool Returns true if the date is valid, false otherwise.
 */
function isDateValid(string $date): bool
{
	$d = \DateTime::createFromFormat("Y-m-d", $date);
	
	return $d && $d->format("Y-m-d") === $date;
}