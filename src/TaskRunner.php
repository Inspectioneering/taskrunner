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

    /**
     * TaskRunner constructor. When $configDir is not specified, this method will search for a tasks.yml file in
     * either the current working directory or in the config folder.
     *
     * Also, if a bootstrap file is specified in the tasks.yml file, this method will traverse a few levels down to
     * search for the file.
     *
     * @todo Fix line 61 (the logger) -- allow monolog config in tasks.yml to address this.
     *
     * @param null|string $configDir
     *
     * @throws TaskException
     */
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
            throw new TaskException("Could not locate the tasks.yml configuration file.");
        }

        // If a custom bootstrap file was included in the config, load it.
        if (isset($this->config['bootstrap'])) {
            if (file_exists($file = __DIR__ . "/" . $this->config['bootstrap'])
                || file_exists($file = __DIR__ . "/../" . $this->config['bootstrap'])
                || file_exists($file = __DIR__ . "/../../" . $this->config['bootstrap'])
                || file_exists($file = __DIR__ . "/../../../" . $this->config['bootstrap'])
                || file_exists($file = $this->config['bootstrap'])
            ) {
                require_once($file);
            } else {
                throw new TaskException("Could not locate the bootstrap file specified in tasks.yml.");
            }
        }

        if (isset($taskLog)) {
            $this->logger = $taskLog;
        } else {
            $this->logger = new NullLogger();
        }

    }

    /**
     * Execute one or more tasks. If the $name argument is specified, only try to run that specific task according to its
     * cron definition (as defined in the tasks.yml file). If $name is null, try to run all tasks according to their cron
     * definitions. If $force is set to true, run the task[s] regardless of whether or not they meet the cron requirements.
     *
     * @param null|string $name
     * @param bool $force
     */
    public function execute($name = null, $force = false)
    {
        if ($name) {

            $this->runTask($name, $this->config['tasks'][$name], $force);

        } else {

            foreach ($this->config['tasks'] as $name => $task) {

                $this->runTask($name, $task, $force);

            }

        }
    }

    /**
     * Run a single task.
     *
     * @param $name
     * @param $task
     * @param $force
     */
    private function runTask($name, $task, $force)
    {
        $cron = CronExpression::factory($task['cron']);

        if ($cron->isDue() || $force) {

            $this->logger->info(sprintf("Running task [%s]", $name));

            /**
             * @var Task $taskObject
             */
            $taskObject = new $task['class']($this->logger);
            $taskObject->preExecute();

        }
    }
}