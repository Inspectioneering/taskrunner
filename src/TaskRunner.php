<?php

/**
 * This file is part of the Inspectioneering TaskRunner package.
 *
 * @license MIT License
 */

namespace Inspectioneering\TaskRunner;

use Cron\CronExpression;
use Psr\Log\NullLogger;
use Symfony\Component\Yaml\Parser;

class TaskRunner
{
    protected $logger;

    public function __construct($configDir = null)
    {
        $yaml = new Parser();

        // Find the tasks.yml file and pull everything into an array.
        if (file_exists($file = getcwd() . "/tasks.yml")
            || file_exists($file = getcwd() . "/config/tasks.yml")
            || file_exists($file = $configDir . "/tasks.yml")
        ) {
            $this->config = $yaml->parse(file_get_contents($file));
        } else {
            throw new \Exception("Could not locate the tasks.yml configuration file.");
        }

        // If a custom bootstrap file was included in the config, load it.
        if (isset($this->config['bootstrap'])) {
            if (file_exists($file = __DIR__ . "/" . $this->config['bootstrap'])
                || file_exists($file = __DIR__ . "/../" . $this->config['bootstrap'])
                || file_exists($file = __DIR__ . "/../../" . $this->config['bootstrap'])
                || file_exists($file = __DIR__ . "/../../../" . $this->config['bootstrap'])
                || file_exists($file = $this->config['bootstrap'])
            )
            require_once($file);

            if (isset($taskLog)) {
                $this->logger = $taskLog;
            } else {
                $this->logger = new NullLogger();
            }
        }

    }

    public function execute($task = null)
    {
        if ($task) {

            $this->runTask($this->config['tasks'][$task]);

        } else {

            foreach ($this->config['tasks'] as $task) {

                $this->runTask($task);

            }

        }
    }

    private function runTask($task)
    {
        $cron = CronExpression::factory($task['cron']);

        if ($cron->isDue()) {
            $taskObject = new $task['class']($this->logger);
            $taskObject->execute();
        }
    }
}