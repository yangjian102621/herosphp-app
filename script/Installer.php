<?php
namespace script;
use Composer\Script\Event;

class Installer
{
    public static function install(Event $event)
    {
        $installerSet = new InstallerSet($event->getIO());
        $installerSet->setRuntimeDir();
        $installerSet->setPublicDir();
        $event->getIO()->write("enjoy herosphp\n");
        $event->getIO()->write('success!!!');
    }
}

class InstallerSet
{
    public function __construct($io)
    {
        $this->io = $io;
    }

    public function setRuntimeDir()
    {
        $dir = __DIR__ . '/../runtime';
        if (! is_dir(__DIR__ . '/../runtime') && ! mkdir($dir, 0777, true) && ! is_dir($dir)) {
            $this->io->write('Directory "%s" was not created', $dir);
        }
        $this->io->write('设置 runtime目录成功');
    }

    public function setPublicDir()
    {
        $dir = __DIR__ . '/../public';
        if (! is_dir(__DIR__ . '/../public') && ! mkdir($dir, 0777, true) && ! is_dir($dir)) {
            $this->io->write('Directory "%s" was not created', $dir);
        }
        $this->io->write('设置 public目录成功');
    }


}