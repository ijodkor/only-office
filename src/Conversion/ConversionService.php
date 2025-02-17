<?php

namespace OnlyOffice\Conversion;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use OnlyOffice\Conversion\Convertors\StandardConvertor;
use OnlyOffice\Conversion\Exceptions\ConversionErrorException;
use OnlyOffice\Conversion\Exceptions\ConversionException;
use OnlyOffice\FileSystem\OfficeFile;

class ConversionService {
    use StorageHelper;

    // Directories
    public const PRIVATE = "/app/private";
    public const PUBLIC = "/app/public";

    public function __construct(private readonly StandardConvertor $convertor) {
    }

    /**
     * @param string $url - download url of document
     * @param string $path - path where pdf will be stored
     * @throws ConversionException
     */
    public function docToPdf(string $url, string $path): void {
        try {
            // Request to convert
            $res = $this->convertor->docxToPdf($url)->convert();

            // Check for error conversion
            if (Arr::get($res, 'error')) {
                throw new ConversionErrorException($res['error']);
            }

            // Download new file
            $content = file_get_contents($res['fileUrl']);

            // remove old converted
            if (File::exists($path)) {
                File::delete($path);
            }

            // Make directory if it does not exist!
            $info = OfficeFile::info($path);
            $this->makeDocDirectory($info['directory']);

            File::put($path, $content, LOCK_EX);
        } catch (Exception $e) {
            throw new ConversionException('Could not be converted to PDF: ' . $e->getMessage(), $e->getCode());
        }
    }
}
