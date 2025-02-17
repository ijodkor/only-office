<?php

namespace OnlyOffice\Conversion;

use Illuminate\Support\Facades\File;

trait StorageHelper {
    private function makeDocDirectory(string $path): void {
        if (!File::exists($path)) {
            mkdir($path, recursive: true);
        }
    }
}
