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
];
