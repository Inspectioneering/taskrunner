<?php

/**
 * This file is part of the Inspectioneering TaskRunner package.
 *
 * @license MIT License
 */

namespace Inspectioneering\TaskRunner;

use Psr\Log\LoggerInterface;

abstract class Task implements TaskInterface
{
    /**
     * @var LoggerInterface
     */
    protected $log;

    /**
     * Task constructor.
     *
     * @param LoggerInterface $log
     */
    public function __construct(LoggerInterface $log)
    {
        $this->setLogger($log);
    }

    /**
     * This function is called before the task is executed to handle errors.
     */
    public function preExecute()
    {
        try {
            $this->execute();
        } catch (\Exception $e) {
            $this->log->error(sprintf("Task encountered an error: %s", $e->getMessage()));
        }
    }

    /**
     * @param LoggerInterface $log The name of the logger to be set.
     */
    protected function setLogger(LoggerInterface $log)
    {
        $this->log = $log;
    }
}