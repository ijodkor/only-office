<?php

namespace OnlyOffice\Config;

interface IConfigService {
    public function getConfig(array $user, array $fileInfo, string $fileType);
}
