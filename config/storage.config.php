<?php

declare(strict_types=1);

use herosphp\plugin\storage\handler\LocalFileHandler;
use herosphp\plugin\storage\handler\AliOssFileHandler;
use herosphp\plugin\storage\handler\QiniuFileSaveHandler;

return [
    // Allowed file extesion
    'allow_ext' => 'jpg|jpeg|png|gif|txt|pdf|rar|zip|swf|bmp|c|java|mp3',
    // Rejected file extension
    'reject_ext' => 'exe|sh|bat',
    // Allowed max file size, default value is  5MiB,
    // if no limits, set it to 0, default: 5MiB
    'max_size' => 5242880,

    'default_handler' => 'local',
    'handlers' => [
        'local' => [
            'class' => LocalFileHandler::class,
            'config' => [
                'root' => PUBLIC_PATH . 'upload',
                'url' => 'http://127.0.0.1:2345/upload/'
            ]
        ],

        'qiniu' => [
            'class' => QiniuFileSaveHandler::class,
            'config' => [
                'access_key' => 'QINIU_ACCESS_KEY',
                'accessKey' => 'QINIU_ACCESS_KEY',
                'secretKey' => 'QINIU_SECRET_KEY',
                'bucket' => 'QINIU_BUCKET',
                'domain' => 'QINBIU_DOMAIN',
            ]
        ],

        'aliyun' => [
            'class' => AliOssFileHandler::class,
            'config' => [
                'accessId' => 'OSS_ACCESS_ID',
                'accessSecret' => 'OSS_ACCESS_SECRET',
                'bucket' => 'OSS_BUCKET',
                'endpoint' => 'OSS_ENDPOINT',
            ]
        ]
    ]

];
