<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

/**
 * Value object for scheduled backups.
 */
class ScheduledBackup extends BackupObjectBase {

    /**
     * Schedule as a Cron expression defining when to run the backups.
     *
     * @var string
     */
    protected string $schedule;

    /**
     * Retention as a number of scheduled backups to retain.
     *
     * @var int
     */
    protected int $retention;

    /**
     * Timestamp for when the last backup ran.
     *
     * @var string
     */
    protected string $lastExecuted;

    /**
     * Schedule requires a starting deadline in seconds.
     *
     * @var int
     */
    protected int $startingDeadlineSeconds = 3600;

    /**
     * @return string
     */
    public function getPhase(): string {
        return $this->phase;
    }

    /**
     * @param string $phase
     *
     * @return ScheduledBackup
     */
    public function setPhase(string $phase): self {
        $this->phase = $phase;
        return $this;
    }

    /**
     * Gets the value of schedule.
     *
     * @return string
     *   Value of schedule.
     */
    public function getSchedule(): string {
        return $this->schedule;
    }

    /**
     * Sets the value of schedule.
     *
     * @param string $schedule
     *   The value for schedule.
     *
     * @return $this
     *   The calling class.
     */
    public function setSchedule(string $schedule): self {
        $this->schedule = $schedule;
        return $this;
    }

    /**
     * Gets the value of schedule.
     *
     * @return string
     *   Value of schedule.
     */
    public function getRetention(): string {
        return $this->retention;
    }

    /**
     * Sets the value of retention.
     *
     * @param string $retention
     *   The value for schedule.
     *
     * @return $this
     *   The calling class.
     */
    public function setRetention(string $retention): self {
        $this->retention = $retention;
        return $this;
    }

    /**
     * Gets the value of lastExecuted.
     *
     * @return string
     *   Value of lastExecuted.
     */
    public function getLastExecuted(): string {
        return $this->lastExecuted;
    }

    /**
     * Sets the value of lastExecuted.
     *
     * @param string $lastExecuted
     *   The value for lastExecuted.
     *
     * @return $this
     *   The calling class.
     */
    public function setLastExecuted(string $lastExecuted): self {
        $this->lastExecuted = $lastExecuted;
        return $this;
    }

    /**
     * Gets the value of startingDeadlineSeconds.
     *
     * @return int
     */
    public function getStartingDeadlineSeconds(): int {
        return $this->startingDeadlineSeconds;
    }

    /**
     * Sets the value of startingDeadlineSeconds.
     *
     * @param int $startingDeadlineSeconds
     *
     * @return ScheduledBackup
     */
    public function setStartingDeadlineSeconds(int $startingDeadlineSeconds): self {
        $this->startingDeadlineSeconds = $startingDeadlineSeconds;
        return $this;
    }

}
