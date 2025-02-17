<?php

namespace OnlyOffice\DocServer\Exceptions;

use Exception;

class CommandErrorException extends Exception {
    public const DESCRIPTIONS = [
        1 => "Document key is missing or no document with such key could be found",
        2 => "Callback url not correct",
        3 => "Internal server error.",
        4 => "No changes were applied to the document before the ForceSave command was received",
        5 => "Command not correct.",
        6 => "Invalid token",
    ];

    public function __construct(int $code) {
        parent::__construct(self::DESCRIPTIONS[$code], $code, self::getPrevious());
    }
}
