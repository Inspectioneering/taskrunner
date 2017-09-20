<?php

/**
 * This file is part of the Inspectioneering TaskRunner package.
 *
 * @license MIT License
 */

namespace Inspectioneering\Component\TaskRunner\Tests;

use Inspectioneering\TaskRunner\TaskException;
use Inspectioneering\TaskRunner\TaskRunner;

class TaskRunnerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The constructor should throw an exception if an invalid config file is specified.
     */
    public function testMissingConfigFileTaskThrowsException()
    {
        $this->setExpectedException(TaskException::class);

        $runner = new TaskRunner('/path/to/some/fake/config/dir');
    }

    /**
     * runTask() should execute fully regardless of duplicate tasks if mutex is not configured
     */

    /**
     * runTask() should execute fully regardless of duplicate tasks if a task has ignore_mutex flag
     */

    /**
     * runTask() should not execute a task if a lock is in place
     */

    /**
     * configureLogger() should return a Logger class if no config is provided.
     */

    /**
     * configureLogger() should throw an exception if config parameters are missing or incorrect.
     */

    /**
     * configureMutex() should return an instance of NoMutex if no config is provided.
     */

    /**
     * configureMutex() should only allow specified locking types
     */

    /**
     * configureMutex() should throw an exception if config parameters are missing or incorrect.
     */

    /**
     * configureMutex() file locking should return a default file path if none is configured
     */
}