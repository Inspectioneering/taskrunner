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

    example_task:
        class: \\ExampleTask
        minutes: 10
    second_task:
        class: \\MyNamespace\\SecondTask
        minutes: 30

Usage
-----

* `vendor/bin/taskrunner:all` - Run all active tasks
* `vendor/bin/taskrunner:task [name]` - Run a single task