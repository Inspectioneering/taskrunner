<?php

/**
 * This file is part of the Inspectioneering TaskRunner package.
 *
 * @license MIT License
 */

namespace Inspectioneering\Component\TaskRunner\Tests;

use Inspectioneering\TaskRunner\Task;
use Inspectioneering\TaskRunner\TaskException;
use Inspectioneering\TaskRunner\TaskRunner;

class TaskRunnerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * If the config contains an invalid/missing bootstrap file, it should throw an error.
     */
    public function testMissingBootstrapFileTaskThrowsException()
    {
        $config = array(
            'bootstrap' => 'path/to/some/fake/bootstrap/file.php',
        );

        $this->setExpectedException(TaskException::class);

        return new TaskRunner($config);
    }

    /**
     * Executing a task that isn't defined in config should throw an exception.
     */
    public function testUndefinedTaskThrowsException()
    {
        $config = array();

        $this->setExpectedException(TaskException::class);

        $runner = new TaskRunner($config);
        $runner->execute('dummy_task');
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