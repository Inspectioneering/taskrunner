<?php

/**
 * This file is part of the Inspectioneering TaskRunner package.
 *
 * @license MIT License
 */

namespace Inspectioneering\TaskRunner\Command;

use Inspectioneering\TaskRunner\TaskRunner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TaskRunnerCommand extends Command
{
    protected function configure()
    {
        $this->setName('run')
            ->setDescription('Run all tasks or a single task.')
            ->addOption(
                'task',
                't',
                InputOption::VALUE_REQUIRED,
                'Task name per tasks.yml file'
            )
            ->addOption(
                'config-dir',
                'c',
                InputOption::VALUE_REQUIRED,
                'Directory of the tasks.yml file',
                '.'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $task = ($input->getOption('task') ? $input->getOption('task') : null);
        $configDir = ($input->getOption('config-dir') ? $input->getOption('config-dir') : null);

        $taskRunner = new TaskRunner($configDir);
        $taskRunner->execute($task);

    }
}