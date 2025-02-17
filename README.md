# Laravel OnlyOffice

## Installation

Install package

```
composer require only-office
```

Publish assets

```
php artisan vendor:publish --tag only-office-assets
```

## Usage

Go to ``/office/workspace`` page to open workspace

Document edit/view controller

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use OnlyOffice\Config\ConfigService;
use OnlyOffice\Config\OfficeConfig;
use OnlyOffice\DocServer\DocFile;
use OnlyOffice\DocServer\FileManagement\FileService;

class TemplateController {

    public function __construct(
        private FileService   $fileService,
        private ConfigService $configService
    ) {
    }

    public function create(): string {
        $filename = "school/document.docx";

        // File
        $example = "new.docx";
        $source = DocFile::getSourcePath($example);

        // File path (/storage/app/private/templates/school/document.docx)
        $path = $this->fileService->storeAs($source, $filename);
        return $path;
    }


    public function show(): View {
        $fileInfo = [
            "directory" => "school",
            "name" => "document.docx",
            'path' => '/app/private/templates/school/document.docx'
        ];

        $config = $this->configService->view([], $fileInfo, ConfigService::FILE_TYPE_DOCX);
        return view('office::index', [
            'config' => array_merge($config, [
                'js' => OfficeConfig::getUrl()
            ])
        ]);
    }


    public function edit(): View {
        $fileInfo = [
            "directory" => "school",
            "name" => "document.docx",
            'path' => '/app/private/templates/school/document.docx'
        ];

        $config = $this->configService->modify([], $fileInfo, ConfigService::FILE_TYPE_DOCX);
        return view('office::index', [
            'config' => array_merge($config, [
                'js' => OfficeConfig::getUrl()
            ])
        ]);
    }
}
```

There are command controller in package to store file via api
```php
/*** Callback ***/
Route::prefix('/template/document')->group(function () {
    Route::post('/callback', [CallbackController::class, 'handle'])->name('only-office.callback');
    Route::post('/store', [CommandController::class, 'command'])->name('only-office.command');
});
``` 

### Remainder

All files stored to /storage/app/private path

## Requirements

- PHP ^8.1
- Laravel ^8

## References

## Recommendations

- Use route name in package - It helps to get route in anywhere

### Links

- [Config 1](https://api1.onlyoffice.com/editors/config/document)
- [Config](https://api.onlyoffice.com/editors/config/document)
- [Callback](https://api.onlyoffice.com/editors/callback)
- [Conversion](https://api.onlyoffice.com/editors/conversionapi)
- [Command](https://api.onlyoffice.com/editors/command/)
- https://github.com/sibalonat/Dokumentat/blob/main/src/DokumentatServiceProvider.php
- https://jwt.io/

### Resources

- [Configuring](https://afterlogic.com/docs/aurora/frequently-asked-questions/configuring-onlyoffice-docs-with-non-standard-port)
- [OnlyOffice in docker](https://hub.docker.com/r/onlyoffice/documentserver)
- [Proxy OnlyOffice](https://helpcenter.onlyoffice.com/installation/docs-community-proxy.aspx)
- [install onlyOffice](https://helpcenter.onlyoffice.com/installation/docs-community-install-ubuntu.aspx)
- [Uninstall onlyOffice in ubuntu](https://helpcenter.onlyoffice.com/installation/docs-community-remove-linux.aspx)
- [OnlyOffice in docker](https://helpcenter.onlyoffice.com/installation/docs-community-docker-compose.aspx)
- [Office Convertor api](https://api.onlyoffice.com/editors/conversionapi)
- [DocEditor Events](https://api.onlyoffice.com/docs/docs-api/usage-api/config/events/#ondocumentready)

### Issues

- [https://github.com/ONLYOFFICE/DocumentServer/issues/1094]
- https://stackoverflow.com/questions/42995866/cant-start-onlyoffice-document-server-without-docker
- https://laracasts.com/discuss/channels/tips/convert-php-array-to-json-in-blade-file

### Remainders

!!! Do not use word **token** as key in document callback url. Because only-office use own token key. And it interrupts
custom
key.
Use key another name (shared_key) for example

### Co-author
- [Azizbek Gulaliyev](mailto:azizgulaliyev44@gmail.com)
