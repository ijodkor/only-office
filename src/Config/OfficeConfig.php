<?php

namespace OnlyOffice\Config;

class OfficeConfig {
    /**
     * Get the specified configuration value.
     *
     * @return string
     */
    public static function getUrl(): string {
        return config('integration.office_server_url');
    }

    public static function getSecret(): string {
        return config('integration.office_jwt_secret');
    }
}
