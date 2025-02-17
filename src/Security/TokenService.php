<?php

namespace OnlyOffice\Security;

use OnlyOffice\Config\OfficeConfig;
use stdClass;

class TokenService implements ITokenService {
    use JWTTrait;

    public function generate(array $payload, string $secret = null): string {
        if (!$secret) {
            $secret = OfficeConfig::getSecret();
        }

        return $this->jwtEncode($payload, $secret);
    }

    public function verify(string $token, string $secret = null): stdClass {
        if (!$secret) {
            $secret = OfficeConfig::getSecret();
        }

        return $this->jwtDecode($token, $secret);
    }
}
