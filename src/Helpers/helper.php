<?php

use Illuminate\Support\Str;

function getStorage(string $path = ""): string {
    $storage = config('office.storage');
    if ($storage && $path) {
        $path = Str::start($path, '/') ? Str::ltrim($path, '/') : $path;
        return implode('/', [$storage, $path]);
    }

    return storage_path($path);
}