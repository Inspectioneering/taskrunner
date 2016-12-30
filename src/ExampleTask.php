<?php

namespace Inspectioneering\TaskRunner;

class ExampleTask extends Task
{
    public static function execute()
    {
        echo "Hello\n";
    }
}