<?php

namespace OnlyOffice\DocServer\Exceptions;

use RuntimeException;

class WarningException extends RuntimeException {
    public function __construct($message = '') {
        if ($message) {
            $this->message = $message;
        } else {
            $this->message = __('messages.Object not found');
        }

        parent::__construct($message, 0, parent::getPrevious());
    }
}
