<?php

/**
 * This file is part of the Inspectioneering TaskRunner package.
 *
 * @license MIT License
 */

namespace Inspectioneering\TaskRunner\Command;

use Cron\CronExpression;
use Inspectioneering\TaskRunner\TaskConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends Command
{
    protected function configure()
    {
        $this->setName('list')
            ->setDescription('List all tasks.')
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
        $configDir = ($input->getOption('config-dir') ? $input->getOption('config-dir') : null);

        $config = TaskConfig::loadFromYaml($configDir);

        if (!empty($config['tasks'])) {

            $output->writeln("\nThe following tasks are available:");

            foreach ($config['tasks'] as $name => $task) {
                $output->writeln("\n<info>" . $name . ":</info>\n");

                $cron = CronExpression::factory($task['cron']);

                $output->writeln("  Cron     : " . $task['cron']);
                $output->writeln("  Next run : " . $cron->getNextRunDate()->format('Y-m-d H:i:s'));
            }

            $output->writeln("");
        } else {
            $output->writeln("\n<error>  No tasks are configured.  </error>\n");
        }
    }
}