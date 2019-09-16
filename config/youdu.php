<?php
return [
    'api'            => env('YOUDU_API', ''),
    'buin'           => (int) env('YOUDU_BUIN', 0),

    'default'        => 'default',
    'apps'           => [
        'default' => [
            'app_id'  => env('YOUDU_APP_ID', ''),
            'aes_key' => env('YOUDU_AES_KEY', ''),
        ],
        // 'other' => [
        //     'app_id'  => env('YOUDU_APP_ID', ''),
        //     'aes_key' => env('YOUDU_AES_KEY', ''),
        // ],
    ],

    'file_save_path' => env('YOUDU_FILE_SAVE_PATH', storage_path('app/youdu')),

    'notification'   => [
        'queue' => env('YOUDU_NOTIFICATION_QUEUE', 'youdu_notification'),
        'delay' => env('YOUDU_NOTIFICATION_DELAY', 0),
        'tries' => env('YOUDU_NOTIFICATION_TRIES', 3),
    ],
];
