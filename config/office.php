<?php

return [
    /*
     |--------------------------------------------------------------------------
     | OnlyOffice
     |--------------------------------------------------------------------------
     */
    'server_url' => env('DOC_SERVER_URL'),
    'jwt_secret' => env('DOC_SERVER_JWT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Document storage path
    |--------------------------------------------------------------------------
    |
    */

    'storage' => env('OFFICE_STORAGE_PATH', ''),


    /*
    |--------------------------------------------------------------------------
    | Document file config
    |--------------------------------------------------------------------------
    |
    */
    'document' => [
        'root' => env('OFFICE_DOCUMENT_ROOT', 'templates'),
        'history_count' => env('OFFICE_DOCUMENT_HISTORY_COUNT', 5),
        'file_types' => env('OFFICE_DOCUMENT_FILE_TYPES', ['docx', 'doc'])
    ],
];
