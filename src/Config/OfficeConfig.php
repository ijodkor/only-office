<?php

namespace OnlyOffice\Config;

class OfficeConfig {
    /**
     * Get the specified configuration value.
     *
     * @return string
     */
    public static function getUrl(): string {
        return config('office.server_url');
    }

    public static function getSecret(): string {
        return config('office.jwt_secret');
    }
}
