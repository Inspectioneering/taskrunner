<?php

namespace Inspectioneering\TaskRunner;

use Cron\CronExpression;
use Symfony\Component\Yaml\Parser;

class TaskRunner
{
    public function __construct($configDir = null)
    {
        $yaml = new Parser();

        if ($configDir) {
            $this->config = $yaml->parse(file_get_contents($configDir . 'tasks.yml'));
        } else {
            $this->config = $yaml->parse(file_get_contents(__DIR__ . '/../tasks.yml'));
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
            call_user_func(array($task['class'], 'execute'));
        }
    }
}