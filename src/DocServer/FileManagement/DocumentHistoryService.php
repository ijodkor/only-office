<?php

namespace OnlyOffice\DocServer\FileManagement;

use Illuminate\Support\Facades\File;
use OnlyOffice\DocServer\DocFile;
use OnlyOffice\FileSystem\OfficeFile;
use OnlyOffice\Security\TokenService;

class DocumentHistoryService {

    private string $storage;

    public function __construct(
        private readonly TokenService $tokenService
    ) {
        $this->storage = config('office.document.root');
    }

    public function getChanges(array $fileInfo): array {
        $filename = $fileInfo['name'];
        $filepath = $fileInfo['path'];

        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $timeStamp = time();

        $histories = [];
        $hChanges = [];

        // Absolute path
        $prefix = DocFile::class;
        $file = OfficeFile::info($filepath);
        $directory = $file['directory'];
        $dirname = $this->makeDirectory("$directory/$prefix");

        // Get directories in histories
        $items = scandir($dirname);
        $directories = [];
        foreach ($items as $item) {
            if (is_dir($item)) {
                $directories[] = $item;
            }
        }

        // Check every version of file in path
        $ver = 1;
        while (count($directories)) {
            $directory = $directories[0];
            $version = "$dirname/$directory";

            $xChanges = "$version/changes.json";
            $xKey = "$version/key.txt";
            if (File::exists($xChanges) && File::exists($xKey)) {
                $changes = json_decode(File::get($xChanges), true);
                $key = json_decode(File::get($xKey), true);

                if (array_key_exists('changes', $changes)) {
                    $histories[] = [
                        "changes" => $changes['changes'],
                        "created" => $changes['changes'][0]['created'],
                        "key" => $key,
                        "serverVersion" => $changes['serverVersion'],
                        "user" => $changes['changes'][0]['user'],
                        "version" => $ver
                    ];
                }

                $data = [
                    "changesUrl" => $this->changesUrl($version, $timeStamp),
                    "fileType" => $extension,
                    "key" => $key,
                    "previous" => [
                        "fileType" => $extension,
                        "key" => $key,
                        "url" => $this->previousDocUrl($version, $timeStamp, $extension)
                    ],
                    "url" => $this->getDocumentUrl($fileInfo),
                    "version" => $ver
                ];

                $data['token'] = $this->tokenService->generate($data);
                $hChanges[$ver] = $data;
            }

            $ver += 1;
            array_shift($directories);
        }

        return [
            'history' => [
                'currentVersion' => $ver - 1,
                'history' => $histories
            ],
            'historyData' => $hChanges
        ];
    }

    private function getDocumentUrl($fileInfo): string {
        $payload = [
            'filename' => $fileInfo['name'],
            'path' => $fileInfo['path']
        ];

        return route('template.download', [
            'token' => $this->tokenService->generate($payload)
        ]);
    }

    private function changesUrl($path, $timeStamp): string {
        $payload = [
            'filename' => 'diff.zip',
            'path' => $path
        ];

        return route('template.download', [
            'token' => $this->tokenService->generate($payload),
            'date' => $timeStamp
        ]);
    }

    private function previousDocUrl($path, $timeStamp, $extension): string {
        $payload = [
            'filename' => 'prev.' . $extension,
            'path' => "$this->storage/$path"
        ];

        return route('template.download', [
            'token' => $this->tokenService->generate($payload),
            'date' => $timeStamp
        ]);
    }

    public function makeDirectory(string $dirname): string {
        $path = getStorage(DocFile::PRIVATE . "/$dirname");
        if (!is_dir($path) && !file_exists($path)) {
            mkdir($path, recursive: true);
        }

        return $path;
    }
}
