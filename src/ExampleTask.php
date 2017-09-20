<?php

namespace Inspectioneering\TaskRunner;

class ExampleTask extends Task
{
    public function execute()
    {
        sleep(10);
        $this->log->info("Hello");
    }
}