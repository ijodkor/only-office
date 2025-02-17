<?php

namespace OnlyOffice\DocServer\Exceptions;

use Exception;

class CommandException extends Exception {
    public function __construct(string $message, int $code) {
        parent::__construct($message, $code, self::getPrevious());
    }
}
