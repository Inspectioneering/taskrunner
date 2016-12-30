<?php

require_once 'vendor/autoload.php';

echo getcwd(); die();

$runner = new \Inspectioneering\TaskRunner\TaskRunner();
$runner->execute();