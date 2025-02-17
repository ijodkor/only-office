<?php

namespace OnlyOffice\Config;

use Illuminate\Support\Str;

class KeygenService {
    public function getKey(int $length = 128): string {
        $timestamp = time();
        $characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz{$timestamp}-._=";
        $key = '';
        for ($i = 0; $i < $length; $i++) {
            $key .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $key;
    }

    public function getKeyByDocumentName(string $value): string {
        return Str::slug($value);
    }
}
