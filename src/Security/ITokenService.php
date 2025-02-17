<?php

namespace OnlyOffice\Security;

interface ITokenService {
    public function generate(array $payload);

    public function verify(string $token);
}
