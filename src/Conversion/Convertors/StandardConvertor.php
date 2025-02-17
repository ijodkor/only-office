<?php

namespace OnlyOffice\Conversion\Convertors;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use OnlyOffice\Conversion\Contracts\IConvertor;
use OnlyOffice\Security\TokenService;

class StandardConvertor implements IConvertor {
    private array $payload;

    public function __construct(private readonly TokenService $tokenService) {
    }

    public function getUrl() {
        return config('integration.office_server_url');
    }

    protected function request(): PendingRequest {
        return Http::baseUrl($this->getUrl())
            ->withHeader('Content-Type', 'application/json')
            ->accept('application/json');
    }

    public function docxToPdf(string $url, bool $async = false): static {
        $key = Str::random(20);

        $this->payload = [
            "key" => $key,
            "title" => "document.docx",
            "filetype" => "docx",
            Str::lower("outputType") => "pdf",
            "url" => $url,
            "async" => $async
        ];

        return $this;
    }

    /**
     * @throws ConnectionException
     */
    public function convert() {
        // Generate token based on payload
        $token = $this->tokenService->generate($this->payload);

        // Request to convert
        $response = $this->request()->post("/ConvertService.ashx", [
            'token' => $token
        ]);

        return $response->json();
    }
}
