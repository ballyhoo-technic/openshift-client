<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Backups\Restore;

/**
 * Serializer for Restore objects.
 */
class RestoreNormalizer extends BaseNormalizer {

    use BackupRestoreNormalizerTrait;

    /**
     * {@inheritdoc}
     */
    protected string|array $supportedInterfaceOrClass = Restore::class;

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $type, $format = NULL, array $context = []): Restore {
        /** @var Restore $restore */
        $restore = Restore::create();
        $restore->setName($data['metadata']['name'])
            ->setCreationTimestamp($data['metadata']['creationTimestamp'])
            ->setBackupName($data['spec']['backupName'])
            ->setLabels($data['metadata']['labels'])
            ->setPhase($data['status']['phase'] ?? '')
            ->setStartTimeStamp($data['status']['startTime'] ?? '')
            ->setCompletionTimeStamp($data['status']['completionTime'] ?? '');
        return $restore;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data, $format = NULL, array $context = []): array {
        /** @var Restore $data */
        $object = [
            'apiVersion' => 'extension.shepherd/v1',
            'kind' => 'Restore',
            'metadata' => [
                'labels' => $data->getLabels(),
                'name' => $data->getName(),
            ],
            'spec' => [
                'volumes' => $this->normalizeVolumes($data->getVolumes()),
                'mysql' => $this->normalizeMysqls($data->getDatabases()),
                'backupName' => $data->getBackupName(),
            ],
        ];

        return $object;
    }

    public function getSupportedTypes(?string $format): array
    {
        // TODO: Implement getSupportedTypes() method.
    }
}
