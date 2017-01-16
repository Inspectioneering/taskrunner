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
    public function __construct(LoggerInterface $logger);
    public function execute();
}