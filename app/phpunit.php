<?php
/**
 * * ---------------------------------------------------------------------
 * 单元测试入口程序
 * @author yangjian102621@gmail.com
 * ---------------------------------------------------------------------
 * Copyright (c) 2013-now http://www.r9it.com All rights reserved.
 * Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * ---------------------------------------------------------------------
 */
error_reporting(0);
require_once __DIR__."/server.php";
define('PHP_UNIT' , true); //开启phpunit模式
\herosphp\BootStrap::run(); //启动应用程序
