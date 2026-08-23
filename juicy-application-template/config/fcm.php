<?php

return [
    'driver' => env('FCM_PROTOCOL', 'http'),
    'log_enabled' => false,

    'http' => [
        'server_key' => env('FCM_SERVER_KEY', 'AAAARSRfCbw:APA91bFBz6yeRUCxJP2zQsJurj8AAkUC7EWWIGIk6YpQnP43WQ5obnyyqShwTbxpaM19Zj5T1Kt2XxKa6YddUy4bY3Jda68nEFYJcqp2LwyvXLNp5A1sj7-T_UqqL9MRFmWdpG-3gACl'),
        'sender_id' => env('FCM_SENDER_ID', '296962951612'),
        'server_send_url' => 'https://fcm.googleapis.com/fcm/send',
        'server_group_url' => 'https://android.googleapis.com/gcm/notification',
        'server_topic_url' => 'https://iid.googleapis.com/iid/v1/',
        'timeout' => 30.0, // in second
    ],
];
