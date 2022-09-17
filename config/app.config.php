<?php

// WebApp configs

return [
    'debug' => true,
    'error_reporting' => E_ALL,
    'timezone' => 'Asia/Shanghai',

    'template' => ['rules' => [], 'skin' => 'default'],

    //app log
    'log_path' => RUNTIME_PATH . 'logs/',
    // worker process pid path
    'pid_path' => RUNTIME_PATH . 'worker.pid',
    // worker log path
    'worker_log_path' => RUNTIME_PATH . 'logs/worker.log',
    // every connection tcp package max size 1024000 byte
    'default_max_package_size' => 1024 * 10 * 10 * 10,

    // server configs
    'server' => [
        'name' => 'WebApp',
        'listen' => 'http://0.0.0.0:2345',
        'max_package_size' => 10, // 最大请求包，单位 MiB
        'context' => [],
        'worker_count' => 1,
        'reloadable' => true,
        'reusePort' => true,
    ],

    'machine_id' => 0x01 // machine id for generate UUID
];
