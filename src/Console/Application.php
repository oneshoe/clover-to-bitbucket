<?php

namespace Oneshoe\CloverToBitbucket\Console;

use Oneshoe\CloverToBitbucket\Command\ReportCommand;
use Symfony\Component\Console\Application as BaseApplication;

/**
 * Class Application
 * @package CloverToBitbucket\Console
 */
class Application extends BaseApplication
{
    public function __construct()
    {
        parent::__construct('CloverToBitbucket', '1.0.0');
    }

    /**
     * Gets the default commands that should always be available.
     *
     * @return \Symfony\Component\Console\Command\Command[] An array of default Command instances
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    public function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();
        $commands[] = new ReportCommand();
        return $commands;
    }
}
