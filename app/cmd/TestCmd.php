<?php

declare(strict_types=1);

namespace app\cmd;

use herosphp\annotation\Action;
use herosphp\annotation\Command;
use herosphp\core\BaseCommand;
use herosphp\core\Input;
use herosphp\GF;
use herosphp\utils\Logger;

#[Command(TestCmd::class)]
class TestCmd extends BaseCommand
{
    #[Action(uri: '/cli/test')]
    public function test(Input $input)
    {
        GF::printInfo('Run in command mode.');
        GF::printSuccess('Actions Parameters:');
        var_dump($input->getAll());

        Logger::info('Start doing something...');
        while ($this->isRunning()) {
            Logger::info('Task is running, press Ctrl + C to exit.');
            sleep(1);
        }
        Logger::info('Task done.');
    }
}
