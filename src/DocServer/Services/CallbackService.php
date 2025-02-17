<?php

namespace OnlyOffice\DocServer\Services;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use OnlyOffice\DocServer\Entities\ResultStatus;
use OnlyOffice\DocServer\FileManagement\FileService;
use OnlyOffice\DocServer\FileManagement\FileValidatorService;
use OnlyOffice\Security\TokenService;

class CallbackService {
    public function __construct(
        private readonly TokenService         $tokenService,
        private readonly FileService          $fileService,
        private readonly FileValidatorService $validatorService
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(array $data): array {
        Log::info("RECEIVE", $data);

        $decoded = $this->tokenService->verify($data['shared_key']);
        $status = $data["status"];

        // Check file not modified
        if (
            $status === ResultStatus::STATUS_MODIFIED &&
            (Arr::has($data, Str::lower('NotModified')) && ($data[Str::lower('NotModified')] === true))
        ) {
            Log::info('NOT_MODIFIED', $data);
            return [
                'error' => 0
            ];
        }

        // Check file extension
        $downloadUrl = $data['url'];
        $this->validatorService->checkExtension($downloadUrl);

        // File parameters
        $fileName = $decoded->filename;
        $path = $decoded->path;

        // Check file ready to save
        if (
            ($status == ResultStatus::STATUS_FINISH) ||
            ($status === ResultStatus::STATUS_MODIFIED && Arr::get($data, Str::lower('ForceSaveType')) === 0)
        ) {
            // Version control, reserve copy, Store history of file
            try {
                $this->fileService->reserve($path, $fileName, $data);
            } catch (Exception) {
            }

            // Modify current template file
            $this->fileService->update($downloadUrl, $path);
            Log::info('CHANGED', $data);

            return [
                'modified' => true,
                'error' => 0
            ];
        }

        // Default success
        return [
            'error' => 0
        ];
    }
}
