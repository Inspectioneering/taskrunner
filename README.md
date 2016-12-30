TaskRunner
==========

Run all of your project's recurring tasks through a single PHP script instead of having to
create endless cron jobs.

Installation
------------

Setup
-----

By default, TaskRunner will search for a `tasks.yml` file in your project root or in your
`config` folder. You can specify a different file location by using the `--config-dir=...`
option.

The tasks.yml file contains each of the individual tasks and any required and optional parameters for each.
For example:

    tasks:
        example_task:
            class: \\ExampleTask
            cron: 10 * * * * *
        second_task:
            class: \\MyNamespace\\SecondTask
            cron: 0 1 * * * *

Usage
-----

* `vendor/bin/taskrunner` - Run all active tasks
* `vendor/bin/taskrunner --task=[name]` - Run a single task

Parameters
----------

* `class` - Class name of the task to run. The class should extend `Task` and should include a namespace,
if necessary.
* `cron` - CRON expression that determines how often the task should be executed.