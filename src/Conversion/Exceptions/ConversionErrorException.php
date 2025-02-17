<?php

namespace OnlyOffice\Conversion\Exceptions;

use Exception;

class ConversionErrorException extends Exception {
    public const DESCRIPTIONS = [
        -1 => "Unknown error.",
        -2 => "Conversion timeout error.",
        -3 => "Conversion error.",
        -4 => "Error while downloading the document file to be converted.",
        -5 => "Incorrect password.",
        -6 => "Error while accessing the conversion result database.",
        -7 => "Input error.",
        -8 => "Invalid token.",
        -9 => "Incorrect output file format",
        -10 => "Size limit exceeded."
    ];

    public function __construct(int $code) {
        parent::__construct(self::DESCRIPTIONS[$code], $code, self::getPrevious());
    }
}
