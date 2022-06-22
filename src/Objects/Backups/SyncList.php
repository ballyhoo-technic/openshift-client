<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

use UniversityOfAdelaide\OpenShift\Objects\ObjectListBase;

/**
 * Defines a value object representing a SyncList.
 */
class SyncList extends ObjectListBase {

    /**
     * The list of backups.
     *
     * @var Sync[]
     */
    protected array $syncs = [];

    /**
     * Factory method for creating a new RestoreList.
     *
     * @return self
     *   Returns static object.
     */
    public static function create(): SyncList {
        return new static();
    }

    /**
     * Gets the sync list.
     *
     * @return Sync[]
     *   The list of syncs.
     */
    public function getSyncs(): array {
        return $this->syncs;
    }

    /**
     * Gets a list of syncs ordered by created time.
     *
     * @param string $operator
     *   Which way to order the list.
     *
     * @return Sync[]
     *   The list of restores.
     */
    public function getSyncsByCreatedTime(string $operator = 'DESC'): array {
        return $this->sortObjectsByCreationTime($this->getSyncs(), $operator);
    }

    /**
     * Adds a sync to the list.
     *
     * @param Sync $sync
     *   The sync to add to the list.
     *
     * @return $this
     *   The calling class.
     */
    public function addSync(Sync $sync): SyncList {
        $this->syncs[] = $sync;
        return $this;
    }

}
