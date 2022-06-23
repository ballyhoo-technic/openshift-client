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
    public function denormalize($data, $class, $format = NULL, array $context = []): Backup {
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
    public function normalize($object, $format = NULL, array $context = []): array {
        /** @var Backup $object */
        $data = [
            'apiVersion' => 'extension.shepherd/v1',
            'kind' => 'Backup',
            'metadata' => [
                'labels' => $object->getLabels(),
                'name' => $object->getName(),
            ],
            'spec' => [
                'volumes' => $this->normalizeVolumes($object->getVolumes()),
                'mysql' => $this->normalizeMysqls($object->getDatabases()),
            ],
        ];
        if ($object->hasAnnotations()) {
            $data['metadata']['annotations'] = $object->getAnnotations();
        }

        return $data;
    }

}
