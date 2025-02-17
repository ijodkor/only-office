<?php

namespace OnlyOffice\Http\Controllers\Api;

use Exception;
use Illuminate\Http\JsonResponse;
use OnlyOffice\DocServer\Services\CommandService;
use OnlyOffice\Http\Controllers\Controller;
use OnlyOffice\Http\Requests\CommandRequest;

class CommandController extends Controller {
    public function __construct(private readonly CommandService $service) {
    }

    public function command(CommandRequest $request): JsonResponse {
        try {
            $data = $this->service->forceSave($request->validated());

            return $this->success(['result' => $data]);
        } catch (Exception $e) {
            return $this->success([
                'result' => [
                    'error' => 4
                ]
            ], $e->getMessage());
        }
    }
}
