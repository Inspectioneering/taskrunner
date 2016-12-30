<?php

namespace Inspectioneering\TaskRunner;

use Cron\CronExpression;
use Symfony\Component\Yaml\Parser;

class TaskRunner
{
    public function __construct()
    {
        $yaml = new Parser();

        $this->config = $yaml->parse(file_get_contents(__DIR__ . '/../tasks.yml'));
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
            echo "Execute " . $task['class'] . " now\n";
            call_user_func(array($task['class'], 'execute'));
        } else {
            echo "Wait until " . $cron->getNextRunDate()->format('Y-m-d H:i:s') . " to run " . $task['class'] . "\n";
        }

    }
}