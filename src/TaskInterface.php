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
     */
    public function __construct(LoggerInterface $log);

    /**
     * @return mixed
     */
    public function execute();
}