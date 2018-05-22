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
     * @var bool True if --dry-run is passed from commandline
     */
    protected $dryRun;

    /**
     * Task constructor.
     *
     * @param LoggerInterface $log
     * @param bool $dryRun
     */
    public function __construct(LoggerInterface $log, $dryRun)
    {
        $this->log = $log;
        $this->dryRun = $dryRun;
    }

    /**
     * This function is called before the task is executed to handle errors.
     *
     * @return string "failed" if execution fails, "success" if it succeeds
     */
    public function preExecute()
    {
        try {
            $this->execute();
        } catch (\Exception $e) {
            $this->log->error(sprintf("Task encountered an error: %s", $e->getMessage()));
            return "failed";
        }

        return "success";
    }
}