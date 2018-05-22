<?php

/**
 * This file is part of the Inspectioneering TaskRunner package.
 *
 * @license MIT License
 */

namespace Inspectioneering\TaskRunner;

use Psr\Log\LoggerInterface;

interface TaskInterface
{
    /**
     * TaskInterface constructor.
     * @param LoggerInterface $log
     * @param bool $dryRun
     */
    public function __construct(LoggerInterface $log, $dryRun);

    /**
     * @return mixed
     */
    public function execute();
}