<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JwtService
{
    public function generate(array $payload): string
    {
        $now = time();

        $payload = array_merge([
            'iat' => $now,
            'exp' => $now + config('jwt.ttl'),
        ], $payload);

        return JWT::encode(
            $payload,
            config('jwt.secret'),
            config('jwt.algorithm')
        );
    }

    public function decode(string $token): object
    {
        return JWT::decode(
            $token,
            new Key(
                config('jwt.secret'),
                config('jwt.algorithm')
            )
        );
    }
}