<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function createJWT($id)
{
    $key = $_ENV['JWT_KEY'];
    $issued = time();
    $expiration = $issued + (600 * 600);

    $payload = [
        'id' => $id,
        'iat' => $issued,
        'exp' => $expiration
    ];

    $jwt = JWT::encode($payload, $key, 'HS256');
    return $jwt;
}

function validateJWT($jwt, $key)
{
    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    return $decoded;
}
