<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

use UniversityOfAdelaide\OpenShift\Objects\ObjectListBase;

/**
 * Defines a value object representing a RestoreList.
 */
class RestoreList extends ObjectListBase {

    /**
     * The list of backups.
     *
     * @var Restore[]
     */
    protected array $restores = [];

    /**
     * Factory method for creating a new RestoreList.
     *
     * @return self
     *   Returns static object.
     */
    public static function create(): RestoreList {
        return new static();
    }

    /**
     * Gets the restore list.
     *
     * @return Restore[]
     *   The list of restores.
     */
    public function getRestores(): array {
        return $this->restores;
    }

    /**
     * Gets a list of restores ordered by created time.
     *
     * @param string $operator
     *   Which way to order the list.
     *
     * @return Restore[]
     *   The list of restores.
     */
    public function getRestoresByCreatedTime(string $operator = 'DESC'): array {
        return $this->sortObjectsByCreationTime($this->getRestores(), $operator);
    }

    /**
     * Adds a restore to the list.
     *
     * @param Restore $restore
     *   The restore to add to the list.
     *
     * @return $this
     *   The calling class.
     */
    public function addRestore(Restore $restore): RestoreList {
        $this->restores[] = $restore;
        return $this;
    }

    /**
     * Gets the number of restores.
     *
     * @return int
     *   The number of restores in this list.
     */
    public function getRestoreCount(): int {
        return count($this->getRestores());
    }

    /**
     * Checks there are restores in this list.
     *
     * @return bool
     *   TRUE if there are any restores.
     */
    public function hasRestores(): bool {
        return (bool)$this->getRestoreCount();
    }

}
