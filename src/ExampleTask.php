<?php

namespace Inspectioneering\TaskRunner;

class ExampleTask extends Task
{
    public function execute()
    {
        $this->log->info("Hello");
    }
}