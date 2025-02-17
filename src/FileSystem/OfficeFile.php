<?php

namespace OnlyOffice\FileSystem;

class OfficeFile {
    /**
     * @param string $path - full path of file
     * @return array - full info of file: [directory, file]
     */
    public static function info(string $path): array {
        $pathInfo = pathinfo($path);

        return [
            'directory' => $pathInfo['dirname'],
            'file' => $pathInfo['basename']
        ];
    }
}
