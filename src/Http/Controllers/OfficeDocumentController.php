<?php

namespace OnlyOffice\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OnlyOffice\Security\TokenService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OfficeDocumentController extends Controller {
    public function __construct(private readonly TokenService $tokenService) {
    }

    public function download(Request $request): BinaryFileResponse|JsonResponse {
        try {
            $decoded = $this->tokenService->verify($request->get('token')); // $request->get('date')
            // $path = $this->fileService->getTemplatePath("/$decoded->directory/$decoded->filename");
            $path = getStorage($decoded->path);

            return response()->download($path);
        } catch (Exception $ex) {
            return $this->fail([], $ex->getMessage());
        }
    }

    public function token(Request $request): JsonResponse {
        return $this->success([
            'token' => $this->tokenService->generate($request->get('payload'), $request->get('token'))
        ]);
    }
}
