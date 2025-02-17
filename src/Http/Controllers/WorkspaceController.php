<?php

namespace OnlyOffice\Http\Controllers;

use Exception;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\View;
use OnlyOffice\Config\ConfigService;
use OnlyOffice\Config\OfficeConfig;

class WorkspaceController extends Controller {

    public function __construct(private readonly ConfigService $configService) {
    }

    /**
     * @throws Exception
     */
    public function index(): View {
        $fileInfo = [
            "directory" => "workspace",
            "name" => "example.docx",
            'path' => '/app/private/examples/new.docx'
        ];

        $config = $this->configService->modify([], $fileInfo, ConfigService::FILE_TYPE_DOCX);

        return view('office::index', [
            'config' => array_merge($config, [
                'js' => OfficeConfig::getUrl()
            ]),
            'errors' => new ViewErrorBag()
        ]);
    }
}
