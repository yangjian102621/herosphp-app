<?php

namespace app\init;

use herosphp\annotation\Bootstrap;

#[Bootstrap(name: 'bootstrap')]
class DemoStarter
{
    public static function init()
    {
        new A('aaa');
    }
}

DemoStarter::init();
