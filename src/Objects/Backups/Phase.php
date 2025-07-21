<?php

namespace UniversityOfAdelaide\OpenShift\Objects\Backups;

/**
 * Defines phase constants.
 */
class Phase {

    const string NEW = 'New';
    const string FAILED_VALIDATION = 'FailedValidation';
    const string IN_PROGRESS = 'InProgress';
    const string COMPLETED = 'Completed';
    const string ENABLED = 'Enabled';
    const string FAILED = 'Failed';

    /**
     * Returns the friendly name for a phase.
     *
     * @param string $phase
     *   The phase string.
     *
     * @return string
     *   The friendly string of the phase.
     */
    public static function getFriendlyPhase(string $phase): string {
        return implode(' ', preg_split('/(?<=[a-z])(?=[A-Z])|(?<=[A-Z])(?=[A-Z][a-z])/', $phase));
    }

}
