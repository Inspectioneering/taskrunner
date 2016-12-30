<?php

namespace Inspectioneering\Component\TaskRunner;

class ExampleTask extends Task
{
    public static function execute()
    {
        echo "Hello\n";
    }
}