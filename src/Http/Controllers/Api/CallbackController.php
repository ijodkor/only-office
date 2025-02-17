<?php

namespace OnlyOffice\Http\Controllers\Api;

use Exception;
use Illuminate\Http\JsonResponse;
use OnlyOffice\DocServer\Services\CallbackService;
use OnlyOffice\Http\Controllers\Controller;
use OnlyOffice\Http\Requests\DocumentStoreRequest;

class CallbackController extends Controller {
    public function __construct(private readonly CallbackService $service) {
    }

    /**
     * @throws Exception
     */
    public function handle(DocumentStoreRequest $request): JsonResponse {
        try {
            $result = $this->service->handle($request->validated());
            return response()->json($result);
        } catch (Exception) {
            return response()->json([
                'error' => 0
            ]);
        }
    }
}
