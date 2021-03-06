TaskRunner
==========

Run all of your project's recurring tasks through a single PHP script instead of having to
create endless cron jobs.

[![Build Status](https://travis-ci.org/Inspectioneering/taskrunner.svg?branch=master)](https://travis-ci.org/Inspectioneering/taskrunner)

Installation
------------

1. Install the package via composer: `composer require inspectioneering/taskrunner`
2. Create a `tasks.yml` file (see below)
3. Create one or more Task classes to be executed. You can copy the `ExampleTask.php` file located at
`vendor/inspectioneering/taskrunner/src` to get started.

Setup
-----

TaskRunner will call individual tasks, which are extended from the `Inspectioneering\TaskRunner\Task` abstract class,
based on cron definitions you have provided in a `tasks.yml` file. This configuration file contains each of the
individual tasks and any required and optional parameters for each.

By default, TaskRunner will search for a `tasks.yml` file in your project root or in your `config` folder. You can
specify a different file location by using the `--config-dir=...` option.

Also by default, any output from the tasks will simply go to stdout. You may define one more more Monolog
channels/handlers within the tasks.yml file. See further section for details and examples.

Optionally, you can reference a bootstrap file that will be called before any tasks are run. This is useful, for
example, if you need to connect to your database for specific tasks.

Usage
-----

Tasks will only be executed if they meet the cron criteria you've defined in your tasks.yml file.

List all configured tasks via command line:

* `vendor/bin/taskrunner list`

Run a task via command line:

* `vendor/bin/taskrunner run` - Run all active tasks
* `vendor/bin/taskrunner run --task=[name]` - Run a single task

Or, add the following entry to your CRON (`crontab -e`):

`* * * * * /my/project/directory/vendor/bin/taskrunner run`

To force a task to run, regardless of whether or not the cron entry is valid:

* `vendor/bin/taskrunner run --task=[name] --force`

tasks.yml Configuration
-----------------------

The tasks.yml file should contain definitions for one or more tasks. Each task requires the following two arguments:

* `class` - Class name of the task to run. The class should extend `Task` and should include a namespace, if necessary.
* `cron` - CRON expression that determines how often the task should be executed.

Ideally, tasks should be configured with some sort of a locking mechanism. That is, if TaskRunner tries to run the same
task again before its previous execution has finished, it should fail. You can currently use file-based or database
locking methods.

You can also configure one or more logging channels using the Monolog library. To do so, define each of the channels
as an array in the `monolog.handlers` property using the following format:

    - [ "\\Namespace\\Of\\MonologHandler", "argument", "argument", ... "log level" ]

Where log level is one of:

 * 'emergency'
 * 'alert'
 * 'critical'
 * 'error'
 * 'warning'
 * 'notice'
 * 'info'
 * 'debug'

A full tasks.yml configuration file example:

    # Instantiate a bootstrap file when running tasks.
    bootstrap: "vendor/autoload.php"

    # Configure a locking mechanism
    locking:

        # Options are "file" or "database"
        type: "file"

        # Default path is /tmp.
        file_path: "relative/path/in/my/project"

    # Configure Monolog logging.
    monolog:

        # Optionally use a different name for the logger (defaults to 'tasks').
        name: "example"

        # Define one or more logging handlers.
        handlers:

            # First argument is fully-qualified class name, and subsequent arguments are constructor parameters.
            # See https://github.com/Seldaek/monolog
            - [ "\\Monolog\\Handler\\StreamHandler", "example/tasks.log", "info" ]

            # You can define as many handlers as you would like.
            - [ "\\Monolog\\Handler\\RotatingFileHandler", "example/tasks.log", 7, "debug" ]

    # Tasks are declared here.
    tasks:

        # Underscored name of the task. When calling this task individually from the command line, use this name.
        example_task:

            # The fully-qualified classname to call. The class must extend \Inspectioneering\TaskRunner\Task.
            class: "\\Namespace\\To\\ExampleTask"

            # Cron definition, using the standard crontab notation. This example would run every 5 minutes.
            cron: "5 * * * *"

Writing tasks
-------------

Tasks are classes that extend the `\Inspectioneering\TaskRunner\Task` class and must include the public `execute()`
function.

You can make use of Monolog with the `$log` class variable, using standard PSR-3 logging methods (e.g.
`$this->log->info('something');` or `$this->log->emergency('uh oh!');`. Ideally, you should take advantage of the
various handlers provided by Monolog to perform different actions at different error levels. For example, if the task
fails for any reason, you might log a critical error that is emailed to you, while info and warning logs simply go into
a file.

A sample task may look like this:

    <?php

    // src/MyApp/Tasks/ExampleTask.php

    namespace MyApp\Tasks;

    use Inspectioneering\TaskRunner\Task;

    class ExampleTask extends Task
    {
        public function execute()
        {
            $this->log->info("This is an example.");
        }
    }

