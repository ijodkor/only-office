<?php

namespace OnlyOffice\DocServer\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use OnlyOffice\Config\OfficeConfig;
use OnlyOffice\DocServer\Exceptions\CommandErrorException;
use OnlyOffice\Security\TokenService;

class CommandService {
    public function __construct(private readonly TokenService $tokenService) {
    }

    protected function request(): PendingRequest {
        return Http::baseUrl(OfficeConfig::getUrl())
            ->acceptJson();
    }

    /**
     * @throws RequestException
     * @throws CommandErrorException
     */
    public function forceSave(array $data) {
        $response = $this->request()->post("/coauthoring/CommandService.ashx", [
            'token' => $this->tokenService->generate($data)
        ])->throw();

        $result = $response->json();
        if (Arr::get($result, 'error')) {
            throw new CommandErrorException($result['error']);
        }

        return $response->json();
    }
}
