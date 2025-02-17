<?php

namespace OnlyOffice\Conversion\Exceptions;

use Exception;

class ConversionException extends Exception {
    public function __construct(string $message, int $code) {
        parent::__construct($message, $code, self::getPrevious());
    }
}
