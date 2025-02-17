<?php

namespace OnlyOffice\DocServer\FileManagement;

use Exception;

class FileValidatorService {
    private array $allowedExtensions;

    public function __construct() {
        $this->allowedExtensions = config('office.document.file_types');
    }

    /**
     * @throws Exception
     */
    public function checkExtension(string $url): void {
        $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
        if (!in_array($extension, $this->allowedExtensions)) {
            throw new Exception("Unsupported file type provided: $extension");
        }
    }
}
