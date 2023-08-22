<?php

require_once 'helpers/redis.php';
require_once 'helpers/challenge.php';
require_once 'helpers/database.php';
require_once 'helpers/request.php';
require_once 'helpers/response.php';

/**
 * Runs the application and handles different request paths and methods.
 *
 * @return void
 * @throws RedisException
 * @throws Exception
 */
function runApplication(): void {
	$requestPath = extractRequestPath();
	
	if ($requestPath === "/pessoas") {
		$requestMethod = getRequestMethod();

		if ($requestMethod === "GET") {
			$term = getQueryParams()["t"] ?? null;
			if (!isset($term)) {
				respondRequest(["error"=> "missing t variable"], 400);
			}
			
			$sql = "SELECT uuid, nickname, name, birthdate, stack FROM people WHERE nickname LIKE ? OR name LIKE ? OR stack LIKE ?  LIMIT 50";

			$term = "% " . $term . "%";
			
			respondRequest(array_map(static function($person) {
				$person->stack = explode("ß", $person->stack);
				return $person;
			} , fetchDatabaseData($sql, [$term, $term, $term])));
		} else {
			$redisConnection = getRedisConnection();
			$requestBody = getRequestBody();
			
			if (!isPostPersonRequestValid($requestBody) || $redisConnection->get($requestBody->apelido)) {
				respondRequest(["error" => "invalid data"], 422);
			}
			
			$uuid = uniqid("", true);
			
			setRedisKeyValue($requestBody->apelido, 1, redisConnection: $redisConnection);
			$requestBody->id = $uuid;
			setRedisKeyValue($uuid, $requestBody, redisConnection: $redisConnection);
			
			$requestBody->stack = getStackString($requestBody->stack);
			$jsonEncodedMessage = json_encode($requestBody);
			
			$redisConnection->publish("createUser", $jsonEncodedMessage);
			

			respondRequest("", 201, ["Location" => "/pessoas/" . $uuid]);
		}
	} else if (str_contains($requestPath, "/pessoas/")) { // Busca por ID
		$personId = getPersonIdFromRequestPath($requestPath);
		$redisData = getRedisConnection()->get($personId);

		if (!$redisData) {
			respondRequest(["error"=>"not found"], 404);
		}
		
		respondRequest(json_decode($redisData));
	} else if ($requestPath === "/contagem-pessoas") {
		$sql = "SELECT COUNT(uuid) AS ammount FROM people";
		
		respondRequest(fetchDatabaseData($sql)[0]->ammount ?? 0);
	} else {
		respondRequest(null, 404);
	}
}


runApplication();