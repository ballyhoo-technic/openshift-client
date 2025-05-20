<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Backups\Database;
use UniversityOfAdelaide\OpenShift\Objects\Backups\ScheduledBackup;

/**
 * Serializer for ScheduledBackup objects.
 */
class ScheduledBackupNormalizer extends BaseNormalizer {

    use BackupRestoreNormalizerTrait;

    /**
     * {@inheritdoc}
     */
    protected string|array $supportedInterfaceOrClass = ScheduledBackup::class;

    /**
     * {@inheritdoc}
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): ScheduledBackup {
        /** @var ScheduledBackup $schedule */
        $schedule = ScheduledBackup::create();
        $schedule->setName($data['metadata']['name'])
            ->setLabels($data['metadata']['labels'])
            ->setSchedule($data['spec']['schedule']['crontab'])
            ->setRetention($data['spec']['retention']['maxNumber'])
            ->setCreationTimestamp($data['metadata']['creationTimestamp'])
            ->setLastExecuted($data['status']['lastExecutedTime'] ?? '');

        foreach ($data['spec']['mysql'] as $id => $dbSpec) {
            $schedule->addDatabase(Database::createFromValues($id, $dbSpec['secret']['name'], $dbSpec['secret']['keys']));
        }

        foreach ($data['spec']['volumes'] as $id => $volumeSpec) {
            $schedule->addVolume($id, $volumeSpec['claimName']);
        }

        return $schedule;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): array {
        /** @var ScheduledBackup $data */
        return [
            'apiVersion' => 'extension.shepherd/v1',
            'kind' => 'BackupScheduled',
            'metadata' => [
                'labels' => $data->getLabels(),
                'name' => $data->getName(),
            ],
            'spec' => [
                'retention' => $this->normalizeRetention($data),
                'schedule' => $this->normalizeSchedule($data),
                'volumes' => $this->normalizeVolumes($data->getVolumes()),
                'mysql' => $this->normalizeMysqls($data->getDatabases()),
            ],
        ];
    }

    public function getSupportedTypes(?string $format): array
    {
        // TODO: Implement getSupportedTypes() method.
    }
}
