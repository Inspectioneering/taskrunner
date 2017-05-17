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
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->setLogger($logger);
    }

    public function preExecute()
    {
        try {
            $this->execute();
        } catch (\Exception $e) {
            $this->logger->error(sprintf("Task encountered an error: %s", $e->getMessage()));
        }
    }

    /**
     * @param LoggerInterface $logger The name of the logger to be set.
     */
    protected function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}