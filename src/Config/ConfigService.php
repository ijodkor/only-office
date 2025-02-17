<?php

namespace OnlyOffice\Config;

use Exception;
use Illuminate\Support\Arr;
use OnlyOffice\DocServer\Traits\TConfigService;
use OnlyOffice\Security\TokenService;

class ConfigService implements IConfigService {
    use TConfigService;

    public const FILE_TYPE_DOCX = 'docx';
    public const FILE_TYPE_XLSX = 'xlsx';
    public const FILE_TYPE_PDF = 'pdf';

    public function __construct(
        private readonly KeygenService $keygen,
        private readonly TokenService  $tokenService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function view(array $user, array $fileInfo, string $fileType): array {
        return $this->getConfig($user, $fileInfo, $fileType, 'view');
    }

    /**
     * @throws Exception
     */
    public function modify(array $user, array $fileInfo, $fileType): array {
        return $this->getConfig($user, $fileInfo, $fileType);
    }

    /**
     * @throws Exception
     */
    public function getConfig(array $user, array $fileInfo, string $fileType, string $mode = 'edit'): array {
        $payload = [
            'filename' => $fileInfo['name'],
            'directory' => $fileInfo['directory'],
            'path' => $fileInfo['path']
        ];

        // Action time of document
        $timeStamp = time();
        $token = $this->tokenService->generate($payload);

        // Document download url
        $downloadUrl = route('template.download', [
            'token' => $token,
            'date' => $timeStamp
        ]);

        // Document callback action url
        $callBackUrl = route('only-office.callback', [
            'shared_key' => $token,
            'date' => $timeStamp
        ]);

        return $this->generateConfig($user, $fileInfo, $downloadUrl, $callBackUrl, $fileType, $mode);
    }

    /**
     * @throws Exception
     */
    public function generateConfig(array $user, array $fileInfo, string $downloadUrl, string $callBackUrl, string $fileType, string $mode): array {
        $name = $fileInfo['name'];
        $directory = $fileInfo['directory'];

        $title = "$directory/$name";

        $fileKey = $this->keygen->getKeyByDocumentName($name);
        $key = $this->keygen->getKey(13);

        $config = $this->conf([
            'author' => [
                'id' => Arr::get($user, 'id'),
                'name' => Arr::get($user, 'name'),
                'username' => Arr::get($user, 'username')
            ],
            'document' => [
                'type' => $this->documentType($fileType),
                "title" => $title,
                "url" => $downloadUrl,
                "fileType" => $fileType,
                "fileKey" => $fileKey,
                "key" => $key,
                'folder' => "Joy",
                "permissions" => $this->getPermission()
            ],
            'editor' => [
                'mode' => $mode,
            ],
            'urls' => [
                "callback" => $callBackUrl,
                "feedback" => config('app.url'),
                "back" => route('templates.index') // TODO
            ],
            "logo" => [
                "default" => config('app.url') . "/img/logo.ico",
                "dark" => config('app.url') . "/img/logo.ico",
                "url" => config('app.url')
            ],
        ]);

        // Bind token to config
        $config['token'] = $this->tokenService->generate($config);

        return $config;
    }

    /**
     * @throws Exception
     */
    private function documentType(string $file_type): string {
        return match ($file_type) {
            self::FILE_TYPE_DOCX => 'word',
            self::FILE_TYPE_XLSX => 'cell',
            self::FILE_TYPE_PDF => 'pdf',
            default => throw new Exception("Document type not found"),
        };
    }

    private function getPermission(): array {
        return [
            "comment" => true,
            "copy" => true,
            "download" => true,
            "edit" => true,
            "print" => true,
            "fillForms" => true,
            "modifyFilter" => true,
            "modifyContentControl" => true,
            "review" => true,
            "chat" => true,
            "reviewGroups" => true,
            "commentGroups" => true,
            "userInfoGroups" => true
        ];
    }
}
