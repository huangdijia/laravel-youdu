<?php
return [
    'api'     => env('YOUDU_API', ''),
    'buin'    => (int) env('YOUDU_BUIN', 0),

    'default' => 'default',
    'apps'    => [
        'default' => [
            'app_id'  => env('YOUDU_APP_ID', ''),
            'ase_key' => env('YOUDU_ASE_KEY', ''),
        ],
        // 'other' => [
        //     'app_id'  => env('YOUDU_APP_ID', ''),
        //     'ase_key' => env('YOUDU_ASE_KEY', ''),
        // ],
    ],

    'file_save_path' => env('YOUDU_FILE_SAVE_PATH', storage_path('app/youdu')),
];
