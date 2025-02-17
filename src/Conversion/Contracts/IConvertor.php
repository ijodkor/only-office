<?php

namespace OnlyOffice\Conversion\Contracts;

interface IConvertor {
    public function convert();

    public function docxToPdf(string $url, bool $async = false): static;
}
