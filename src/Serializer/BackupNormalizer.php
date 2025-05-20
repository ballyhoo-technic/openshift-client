<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Backups\Backup;
use UniversityOfAdelaide\OpenShift\Objects\Backups\Database;

/**
 * Serializer for Backup objects.
 */
class BackupNormalizer extends BaseNormalizer {

    use BackupRestoreNormalizerTrait;

    /**
     * {@inheritdoc}
     */
    protected string|array $supportedInterfaceOrClass = Backup::class;

    /**
     * {@inheritdoc}
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): Backup {
        /** @var Backup $backup */
        $backup = Backup::create();
        $backup->setName($data['metadata']['name'])
            ->setLabels($data['metadata']['labels'])
            ->setCreationTimestamp($data['metadata']['creationTimestamp'])
            ->setDeletionTimestamp($data['metadata']['deletionTimestamp'] ?? '')
            ->setAnnotations($data['metadata']['annotations'] ?? [])
            ->setPhase($data['status']['phase'] ?? '')
            ->setStartTimeStamp($data['status']['startTime'] ?? '')
            ->setCompletionTimeStamp($data['status']['completionTime'] ?? '')
            ->setResticId($data['status']['resticId'] ?? '');

        foreach ($data['spec']['mysql'] as $id => $dbSpec) {
            $backup->addDatabase(Database::createFromValues($id, $dbSpec['secret']['name'], $dbSpec['secret']['keys']));
        }

        foreach ($data['spec']['volumes'] as $id => $volumeSpec) {
            $backup->addVolume($id, $volumeSpec['claimName']);
        }

        return $backup;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): array {
        /** @var Backup $data */
        $object = [
            'apiVersion' => 'extension.shepherd/v1',
            'kind' => 'Backup',
            'metadata' => [
                'labels' => $data->getLabels(),
                'name' => $data->getName(),
            ],
            'spec' => [
                'volumes' => $this->normalizeVolumes($data->getVolumes()),
                'mysql' => $this->normalizeMysqls($data->getDatabases()),
            ],
        ];
        if ($data->hasAnnotations()) {
            $object['metadata']['annotations'] = $data->getAnnotations();
        }

        return $object;
    }

    public function getSupportedTypes(?string $format): array
    {
        // TODO: Implement getSupportedTypes() method.
    }
}
