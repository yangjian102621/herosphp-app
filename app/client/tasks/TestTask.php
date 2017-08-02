<?php

namespace client\tasks;

use client\tasks\interfaces\ITask;
use herosphp\string\StringUtils;

/**
 * @author yangjian102621@gmail.com
 * @version 1.0.0
 * @since 15-4-27
 */
class TestTask implements ITask {

        public function run() {

            for ($i = 0; $i < 100; $i++) {
                tprintError(StringUtils::genGlobalUid());
            }

            tprintOk("Hello, Body! You are running a Task.");
        }

} 
