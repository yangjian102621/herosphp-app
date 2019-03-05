<?php
/**
 * API 应用入口程序
 * @author yangjian
 * @since v3.0.0
 */
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:GET,POST');
header('Access-Control-Allow-Headers:x-requested-with,content-type');
require_once __DIR__."/server.php";
\herosphp\BootStrap::runApi(); // 启动 API 应用程序
