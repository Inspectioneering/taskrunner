<?php

namespace Inspectioneering\TaskRunner\Mutex;

use malkusch\lock\mutex\LockMutex;
use malkusch\lock\exception\LockAcquireException;
use malkusch\lock\exception\LockReleaseException;

class FileMutex extends LockMutex
{
    /**
     * @var resource $filePath The path of the lock file.
     */
    private $filePath;

    /**
     * Sets the file handle.
     *
     * @param string $filePath The path of the lock file.
     */
    public function __construct($filePath)
    {
        // Append .lock to the end to prevent an accidental or purposeful unlink oops
        $this->filePath = $filePath . '.lock';
    }

    /**
     * @internal
     */
    protected function lock()
    {
        if (file_exists($this->filePath)) {
            throw new LockAcquireException("Could not create lock file or it exists already.");
        }

        touch($this->filePath);
    }

    /**
     * @internal
     */
    protected function unlock()
    {
        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        } else {
            throw new LockReleaseException("Failed to unlock the file.");
        }
    }
}