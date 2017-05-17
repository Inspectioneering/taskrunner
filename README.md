TaskRunner
==========

Run all of your project's recurring tasks through a single PHP script instead of having to
create endless cron jobs.

Installation
------------

1. Install the package via composer: `composer require inspectioneering/taskrunner`
2. Create a `tasks.yml` file (see below)
3. Create one or more Task classes to be executed. You can copy the `ExampleTask.php` file located at `vendor/inspectioneering/taskrunner/src` to get started.

Setup
-----

By default, TaskRunner will search for a `tasks.yml` file in your project root or in your
`config` folder. You can specify a different file location by using the `--config-dir=...`
option.

Optionally, you can reference a bootstrap file that will be called before any tasks are run. This is useful, for example, if you
need to connect to your database for specific tasks. Furthermore, you may use a PSR-3 logger such as Monolog by assigning it to the `$taskLog` variable
in your bootstrap file.

The tasks.yml file contains each of the individual tasks and any required and optional parameters for each.
For example:

    bootstrap: "vendor/autoload.php" # or "src/MyApp/bootstrap.php"
    tasks:
        example_task:
            class: "\\ExampleTask"
            cron: "10 * * * *"
        second_task:
            class: "\\MyNamespace\\SecondTask"
            cron: "0 1 * * *"

Usage
-----

Tasks will only be executed if they meet the criteria you've defined in your tasks.yml file.

Via command line:

* `vendor/bin/taskrunner run` - Run all active tasks
* `vendor/bin/taskrunner run --task=[name]` - Run a single task

Or, add the following entry to your CRON (`crontab -e`):

`* * * * * php /my/project/directory/vendor/bin/taskrunner run`

To force a task to run, regardless of whether or not the cron entry is valid:

* `vendor/bin/taskrunner run --task=[name] --force`

Parameters
----------

* `class` - Class name of the task to run. The class should extend `Task` and should include a namespace,
if necessary.
* `cron` - CRON expression that determines how often the task should be executed.