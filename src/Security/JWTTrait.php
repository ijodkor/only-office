<?php

namespace OnlyOffice\Security;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use stdClass;

trait JWTTrait {
    // encode a payload object into a token using a secret key
    private function jwtEncode(array $payload, string $secretKey): string {
        $algorithm = env('JWT_ALGORITHM', 'HS256');

        return JWT::encode($payload, $secretKey, $algorithm);
    }

    public function jwtDecode(string $token, string $secret): stdClass {
        $algorithm = env('JWT_ALGORITHM', 'HS256');
        return JWT::decode($token, new Key($secret, $algorithm));
    }
}
