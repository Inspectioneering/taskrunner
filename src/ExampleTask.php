<?php

namespace Inspectioneering\TaskRunner;

class ExampleTask extends Task
{
    public function execute()
    {
        sleep(10);
        if ($this->dryRun) {
            $this->log->info("Dry run of task.");
        } else {
            $this->log->info("Not dry run (live) task.");
        }

    }
}