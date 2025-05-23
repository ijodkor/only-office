<?php

namespace OnlyOffice\DocServer;

use OnlyOffice\FileSystem\OfficeFile;

class DocFile {
    // Directories
    public const PRIVATE = "/app/private";
    // Example
    public const EXAMPLE_DIR = "examples";

    public const HISTORY_DIRECTORY = 'histories';

    private function __construct() {
    }

    public static function getSourcePath(string $file): string {
        $paths = [self::EXAMPLE_DIR, $file];

        return implode(DIRECTORY_SEPARATOR, $paths);
    }

    public static function getStoragePath(string $path): string {
        $paths = [self::PRIVATE, $path];
        return getStorage(implode(DIRECTORY_SEPARATOR, $paths));
    }

    public static function getDocumentPath(string $prefix, string $path): string {
        $paths = [self::PRIVATE, $prefix, $path];
        return getStorage(implode(DIRECTORY_SEPARATOR, $paths));
    }

    /* History */
    public static function getHistoryDirectory(string $path): string {
        $prefix = self::HISTORY_DIRECTORY;

        $info = OfficeFile::info($path);
        $directory = $info['directory'];
        $filename = $info['file'];

        $destination = "$directory/$prefix/$filename";
        self::makeDirectory($destination);

        return $destination;
    }

    /* Utils */
    public static function makeDirectory(string $dirname): void {
        if (!is_dir($dirname) && !file_exists($dirname)) {
            mkdir($dirname, recursive: true);
        }
    }
}
