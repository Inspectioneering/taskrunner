<?php

/**
 * This file is part of the Inspectioneering TaskRunner package.
 *
 * @license MIT License
 */

namespace Inspectioneering\TaskRunner;

use Cron\CronExpression;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class TaskRunner
{
    /**
     * @var mixed An array of configuration variables defined by tasks.yml
     */
    protected $config;

    /**
     * @var LoggerInterface
     */
    protected $log;

    /**
     * TaskRunner constructor.
     *
     * If a bootstrap file is specified in the tasks.yml file, this method will traverse a few levels down to
     * search for the file.
     *
     * @param array $config
     *
     * @throws TaskException
     */
    public function __construct($config)
    {
        $this->config = $config;

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

        // Configure logging per tasks.yml, or use stdout otherwise.
        $this->log = $this->configureLogger($this->config);
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

            $startTime = time();

            // Update the monolog processor to include the name of the task in the log record.
            $this->log->pushProcessor(function ($record) use ($name, $force) {
                $record['extra']['task'] = $name;
                $record['extra']['forced'] = $force ? "true" : "false";

                return $record;
            });

            $this->log->info("Running task");

            /**
             * @var Task $taskObject
             */
            $taskObject = new $task['class']($this->log);
            $taskObject->preExecute();

            $timestamp = time() - $startTime;

            $this->log->info(sprintf("Task completed in %d seconds", $timestamp));

        }
    }

    /**
     * If monolog is configured in the tasks.yml file, set up one or more handlers per the configuration. Otherwise, just
     * create a blank Logger (i.e. will output to stdout)
     *
     * @param $config
     * @return Logger
     */
    private function configureLogger($config)
    {
        if (isset($config['monolog'])) {

            // Can specify monolog.name in tasks.yml. If not set, just use 'tasks'
            $log = new Logger(!empty($config['monolog']['name']) ? $config['monolog']['name'] : "tasks");

            // Loop through each of the handlers defined in tasks.yml and instantiate their classes
            // Technically, monolog handlers don't NEED to be set - they will simply go to stdout.
            if (isset($config['monolog']['handlers'])) {
                foreach ($config['monolog']['handlers'] as $arguments) {

                    $class = array_shift($arguments);

                    $log->pushHandler(new $class(...$arguments));

                }
            }

            return $log;

        } else {
            return new Logger("tasks");
        }
    }
}