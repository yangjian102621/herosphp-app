<?php

namespace client\tasks;

use client\tasks\interfaces\ITask;

/**
 * @author yangjian102621@gmail.com
 * @version 1.0.0
 * @since 15-4-27
 */
class TestTask implements ITask {

        public function run() {

            tprintOk("Hello, Body! You are running a Task.");
        }

} 
