<?php

namespace Inspectioneering\Component\TaskRunner\Tests;

use Inspectioneering\TaskRunner\TaskRunner;

class TaskRunnerTest extends \PHPUnit_Framework_TestCase
{
    public function testMissingConfigFileTaskThrowsException()
    {
        $this->setExpectedException(\Exception::class);

        $runner = new TaskRunner('/path/to/some/fake/config/dir');
    }
}