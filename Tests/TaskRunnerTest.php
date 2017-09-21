<?php

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
}