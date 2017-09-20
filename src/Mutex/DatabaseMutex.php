<?php

namespace Inspectioneering\TaskRunner\Mutex;

use malkusch\lock\mutex\LockMutex;
use malkusch\lock\exception\LockAcquireException;
use malkusch\lock\exception\LockReleaseException;

class DatabaseMutex extends LockMutex
{
    /**
     * @var \PDO $pdo The PDO.
     */
    private $pdo;

    /**
     * @var string $table The database table to use for records
     */
    private $table;

    /**
     * @var string $task The name of the task to be locked/unlocked
     */
    private $task;

    /**
     * DatabaseMutex constructor.
     *
     * @param \PDO $pdo
     * @param $table
     * @param $task
     */
    public function __construct(\PDO $pdo, $table, $task)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->task = $task;

        $this->pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        // Create a database table if one doesn't exist already.
$query = <<<EOL
CREATE TABLE IF NOT EXISTS `$table` (
`id` INT AUTO_INCREMENT NOT NULL,
`name`  VARCHAR(128) NOT NULL,
`started_at` DATETIME NOT NULL,
`completed_at` DATETIME,
PRIMARY KEY (`id`));
EOL;

        $pdo->exec($query);
    }

    /**
     * @internal
     */
    protected function lock()
    {
        $record = $this->getOpenRecordId($this->task);

        if ($record) {
            throw new LockAcquireException("Could not create database lock entry.");
        } else {
            $time = new \DateTime();
            $start = $time->format('Y-m-d H:i:s');

            $query = "INSERT INTO `$this->table` (`name`, `started_at`) VALUES (:name, :start);";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':name', $this->task);
            $stmt->bindParam(':start', $start);
            $stmt->execute();
        }
    }

    /**
     * @internal
     */
    protected function unlock()
    {
        $record = $this->getOpenRecordId($this->task);

        if ($record) {
            $time = new \DateTime();
            $end = $time->format('Y-m-d H:i:s');

            $query = "UPDATE `$this->table` SET `completed_at` = :end WHERE `id` = :id;";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':end', $end);
            $stmt->bindParam(':id', $record);
            $stmt->execute();
        } else {
            throw new LockReleaseException("Failed to complete the database entry.");
        }
    }

    /**
     * Given a $name to use as a column lookup, return the associated ID from the database or
     * return null.
     *
     * @param $name
     * @return mixed
     */
    private function getOpenRecordId($name)
    {
        $query = "SELECT id FROM `$this->table` WHERE `name` = :name AND `completed_at` IS NULL;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $record = $stmt->fetch();

        return $record['id'];
    }
}