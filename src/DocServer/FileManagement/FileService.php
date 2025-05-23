<?php

namespace OnlyOffice\DocServer\FileManagement;

use Exception;
use Illuminate\Support\Facades\File;
use OnlyOffice\DocServer\Container\IFileService;
use OnlyOffice\DocServer\DocFile;
use OnlyOffice\DocServer\Exceptions\WarningException;

class FileService implements IFileService {

    private string $storage;

    public function __construct() {
        $this->storage = config('office.document.root');
    }

    /**
     * Create a file (/storage/app/private)
     * @param string $source - Example or source file path in storage (school/file.docx)
     * @param string $destination - Destination file path (school/file.docx)
     * @return string
     */
    public function storeAs(string $source, string $destination): string {
        $storage = $this->storage;
        $src = DocFile::getStoragePath($source);
        if (!File::exists($src)) {
            throw new WarningException('Source file is not found!');
        }

        // Create directory if it does not exist
        $dirname = dirname($destination);
        $directory = DocFile::getDocumentPath($storage, $dirname);
        DocFile::makeDirectory($directory);

        // Create copy of file
        $target = DocFile::getDocumentPath($storage, $destination);
        if (!File::copy($src, $target)) {
            throw new WarningException('File could not be created!');
        }

        return $target;
    }

    /**
     * @param string $url - File url for download from OnlyOffice
     * @param string $path - File path in storage
     * @return string
     * @throws Exception
     */
    public function update(string $url, string $path): string {
        $options = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false
            ]
        ];

        $content = file_get_contents($url, false, stream_context_create($options));
        if (!$content) {
            throw new Exception("Bad Request");
        }

        // path to file in storage
        $dest = getStorage($path);

        // Store file,
        File::put($dest, $content, LOCK_EX);

        return $url;
    }

    /**
     * Remove file
     * @param string $path
     */
    public function remove(string $path): void {
        if (File::exists($path)) {
            File::delete($path);
        } else {
            throw new WarningException("Template version's file is not found to delete.");
        }
    }


    /**
     * Methods store every changes in file to storage
     * @param string $path
     * @param string $filename
     * @param array $payload
     * @return void
     */
    public function reserve(string $path, string $filename, array $payload): void {
        // copy the previous file version and save history version path
        $changesDir = DocFile::getHistoryDirectory($path); // get the path to the history direction

        File::copy($path, "$changesDir/$filename");

        $histData = json_encode($payload['history'], JSON_PRETTY_PRINT);
        if (!empty($histData)) {
            // write the history changes to the changes.json file
            File::put("$changesDir/changes.json", $histData, LOCK_EX);
        }

        // write the key value to the key.txt file
        File::put("$changesDir/key.txt", $payload['key'], LOCK_EX);
    }
}
