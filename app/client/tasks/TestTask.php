<?php

namespace client\tasks;

use client\tasks\interfaces\ITask;
use herosphp\gmodel\utils\SimpleHtmlDom;
use herosphp\http\HttpClient;
use herosphp\string\StringUtils;

/**
 * @author yangjian102621@gmail.com
 * @version 1.0.0
 * @since 15-4-27
 */
class TestTask implements ITask {

        public function run() {

            tprintOk("OK, The task is done.");
        }

} 
