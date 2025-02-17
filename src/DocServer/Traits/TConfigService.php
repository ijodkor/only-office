<?php

namespace OnlyOffice\DocServer\Traits;

use Illuminate\Support\Str;

trait TConfigService {

    private function conf(array $settings): array {
        $author = $settings['author'];
        $document = $settings['document'];
        $editor = $settings['editor'];

        $urls = $settings['urls'];
        $logo = $settings['logo'];

        return [
            "type" => 'desktop',
            "documentType" => $document["type"],
            "document" => [
                "title" => $document['title'],
                "url" => $document['url'],
                "fileType" => $document['fileType'],
                "key" => $document['key'],
                "referenceData" => [
                    "fileKey" => $document["fileKey"],
                    // "instanceId" => "",
                    "key" => $document['key'],
                ],
                "info" => [
                    "owner" => $author['username'],
                    "uploaded" => date('d.m.y'),
                    "favorite" => true,
                    "folder" => $document['folder']
                ],
                "permissions" => $document['permissions'],
            ],
            "editorConfig" => [
                "mode" => $editor['mode'],
                "lang" => 'ru',
                "location" => "ru",
                "callbackUrl" => $urls['callback'],  // absolute URL to the document storage service
                "user" => [  // the user currently viewing or editing the document
                    "id" => (string)$author['id'],
                    "name" => $author['username'],
                    "group" => null
                ],
                "customization" => [  // the parameters for the editor interface
                    "about" => true,  // the About section display
                    "comments" => true,
                    "feedback" => [
                        "url" => $urls['feedback'],
                        "visible" => true
                    ],
                    "logo" => [
                        "image" => $logo['default'],
                        "imageDark" => $logo['dark'],
                        "url" => $logo['url']
                    ],
                    Str::lower("forceSave") => false,  // adds the request for the forced file saving to the callback handler when saving the document
                    Str::lower("goBack") => [  // settings for the Open file location menu button and upper right corner button
                        "url" => $urls['back'],  // the absolute URL to the website address which will be opened when clicking the Open file location menu button
                    ]
                ]
            ],
            "events" => []
        ];
    }
}
