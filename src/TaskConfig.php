<?php

/**
 * This file is part of the Inspectioneering TaskRunner package.
 *
 * @license MIT License
 */

namespace Inspectioneering\TaskRunner;

use Symfony\Component\Yaml\Parser;

class TaskConfig
{
    /**
     * Load the tasks.yml configuration file by traversing some paths. There has got to be a better way to do this.
     *
     * @param $directory
     * @return mixed
     * @throws TaskException
     */
    public static function loadFromYaml($directory = null)
    {
        $yaml = new Parser();

        // Find the tasks.yml file and pull everything into an array.
        if (($directory && file_exists($file = __DIR__ . "/../" . $directory . "/tasks.yml"))
            || ($directory && file_exists($file = __DIR__ . "/../../" . $directory . "/tasks.yml"))
            || ($directory && file_exists($file = __DIR__ . "/../../../" . $directory . "/tasks.yml"))
            || ($directory && file_exists($file = __DIR__ . "/../../../../" . $directory . "/tasks.yml"))
            || file_exists($file = __DIR__ . "/tasks.yml")
            || file_exists($file = __DIR__ . "/../tasks.yml")
            || file_exists($file = __DIR__ . "/../../tasks.yml")
            || file_exists($file = __DIR__ . "/../../tasks.yml")
            || file_exists($file = __DIR__ . "/config/tasks.yml")
            || file_exists($file = __DIR__ . "/../config/tasks.yml")
            || file_exists($file = __DIR__ . "/../../config/tasks.yml")
            || file_exists($file = __DIR__ . "/../../../config/tasks.yml")
        ) {
            return $yaml->parse(file_get_contents($file));
        } else {
            throw new TaskException("Could not locate the tasks.yml configuration file.");
        }
    }
}