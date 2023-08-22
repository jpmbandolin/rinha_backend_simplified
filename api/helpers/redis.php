<?php

/**
 * Returns a Redis connection object.
 *
 * @param Redis|null $redisConnection Optional Redis connection object.
 *
 * @return Redis The Redis connection object.
 * @throws RedisException
 */
function getRedisConnection(?Redis $redisConnection = null): Redis
{
	if ($redisConnection) {
		return $redisConnection;
	}
	
	$connection = new \Redis;
	$connection->pconnect("redis");
	
	return $connection;
}

/**
 * Sets a key-value pair in Redis.
 *
 * @param string $key The key to set.
 * @param mixed $value The value to set. If it's not a string, it will be JSON encoded before setting.
 * @param null|int|array $timeout The timeout for the key. Can be null, an integer, or an array of parameters for the EXPIRE command.
 * @param ?Redis $redisConnection The Redis connection to use. If not provided, a default connection will be used.
 * @throws RedisException If there is an error setting the key-value pair in Redis.
 * @return bool Returns true if the key-value pair was set successfully, false otherwise.
 */
function setRedisKeyValue(string $key, mixed $value, null|int|array $timeout = null, ?Redis $redisConnection = null): bool {
	if (!is_string($value)){
		$value = json_encode($value);
	}
	
	return ($redisConnection ?? getRedisConnection())->set($key, $value, $timeout);
}