<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Document storage path
    |--------------------------------------------------------------------------
    |
    */

    'root' => env('OFFICE_STORAGE_PATH', 'app/private'),


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
