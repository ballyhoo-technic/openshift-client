<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

use UniversityOfAdelaide\OpenShift\Objects\ObjectBase;

/**
 * A base class for backup and restore objects.
 */
abstract class BackupObjectBase extends ObjectBase {

    /**
     * Defines the annotation to store a backups friendly name on.
     */
    const string FRIENDLY_NAME_ANNOTATION = 'backups.shepherd/friendly-name';

    /**
     * The phase the object is in.
     *
     * @var string
     */
    protected string $phase = '';

    /**
     * The time the backup was started.
     *
     * @var string
     */
    protected string $startTimestamp = '';

    /**
     * The time the backup completed.
     *
     * @var string
     */
    protected string $completionTimestamp = '';

    /**
     * The time the backup was deleted.
     *
     * @var string
     */
    protected string $deletionTimestamp = '';

    /**
     * The array of volumes to backup/restore.
     *
     * Keyed by an identifier, with the value being the PVC name.
     *
     * @var array
     */
    protected array $volumes = [];

    /**
     * The array of databases to backup/restore.
     *
     * @var Database[]
     */
    protected array $databases = [];

    /**
     * Gets the value of phase.
     *
     * @return string
     *   Value of phase.
     */
    public function getPhase(): string {
        return $this->phase;
    }

    /**
     * Sets the value of phase.
     *
     * @param string $phase
     *   The value for phase.
     *
     * @return $this
     *   The calling class.
     */
    public function setPhase(string $phase): self {
        $this->phase = $phase;
        return $this;
    }

    /**
     * Check if the object phase is completed.
     *
     * @return bool
     *   Whether the object phase is completed.
     */
    public function isCompleted(): bool {
        return $this->getPhase() === Phase::COMPLETED;
    }

    /**
     * Gets the value of startTimestamp.
     *
     * @return string
     *   Value of startTimestamp.
     */
    public function getStartTimestamp(): string {
        return $this->startTimestamp;
    }

    /**
     * Sets the value of startTimestamp.
     *
     * @param string $startTimestamp
     *   The value for startTimestamp.
     *
     * @return $this
     *   The calling class.
     */
    public function setStartTimestamp(string $startTimestamp): static {
        $this->startTimestamp = $startTimestamp;
        return $this;
    }

    /**
     * Gets the value of completionTimestamp.
     *
     * @return string
     *   Value of completionTimestamp.
     */
    public function getCompletionTimestamp(): string {
        return $this->completionTimestamp;
    }

    /**
     * Sets the value of completionTimestamp.
     *
     * @param string $completionTimestamp
     *   The value for completionTimestamp.
     *
     * @return $this
     *   The calling class.
     */
    public function setCompletionTimestamp(string $completionTimestamp): static {
        $this->completionTimestamp = $completionTimestamp;
        return $this;
    }

    /**
     * Gets the value of DeletionTimestamp.
     *
     * @return string
     *   Value of DeletionTimestamp.
     */
    public function getDeletionTimestamp(): string {
        return $this->deletionTimestamp;
    }

    /**
     * Sets the value of DeletionTimestamp.
     *
     * @param string $deletionTimestamp
     *   The value for DeletionTimestamp.
     *
     * @return $this
     *   The calling class.
     */
    public function setDeletionTimestamp(string $deletionTimestamp): static {
        $this->deletionTimestamp = $deletionTimestamp;
        return $this;
    }

    /**
     * Gets the value of Volumes.
     *
     * @return array
     *   Value of Volumes.
     */
    public function getVolumes(): array {
        return $this->volumes;
    }

    /**
     * Sets the value of Volumes.
     *
     * @param array $volumes
     *   The value for Volumes.
     *
     * @return $this
     *   The calling class.
     */
    public function setVolumes(array $volumes): static {
        $this->volumes = $volumes;
        return $this;
    }

    /**
     * Add a new volume.
     *
     * @param string $id
     *   The volume name.
     * @param string $claimName
     *   The claim name.
     *
     * @return $this
     *   The calling class.
     */
    public function addVolume(string $id, string $claimName): static {
        $this->volumes[$id] = $claimName;
        return $this;
    }

    /**
     * Gets the value of Databases.
     *
     * @return Database[]
     *   Value of Databases.
     */
    public function getDatabases(): array {
        return $this->databases;
    }

    /**
     * Sets the value of Databases.
     *
     * @param Database[] $databases
     *   The value for Databases.
     *
     * @return $this
     *   The calling class.
     */
    public function setDatabases(array $databases): static {
        $this->databases = $databases;
        return $this;
    }

    /**
     * Adds a database.
     *
     * @param Database $db
     *   The db to add.
     *
     * @return $this
     *   The calling class.
     */
    public function addDatabase(Database $db): static {
        $this->databases[] = $db;
        return $this;
    }

    /**
     * Returns the friendly name of the backup.
     *
     * @return string
     *   The friendly name if set, otherwise the backup name.
     */
    public function getFriendlyName(): string {
        return $this->getAnnotation(self::FRIENDLY_NAME_ANNOTATION) ?: $this->getName();
    }

}
