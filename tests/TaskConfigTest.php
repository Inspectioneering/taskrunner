<?php

namespace Inspectioneering\Component\TaskRunner\Tests;

use Inspectioneering\TaskRunner\TaskException;
use Inspectioneering\TaskRunner\TaskConfig;

class TaskConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * An exception should be thrown if an invalid config directory is specified.
     */
    public function testMissingConfigFileTaskThrowsException()
    {
        $this->setExpectedException(TaskException::class);

        $config = TaskConfig::loadFromYaml('/path/to/some/fake/config/dir');
    }
}