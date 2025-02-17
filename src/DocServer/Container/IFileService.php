<?php

namespace OnlyOffice\DocServer\Container;

interface IFileService {
    public function storeAs(string $source, string $destination);

    public function update(string $url, string $path);

    public function remove(string $path);
}
