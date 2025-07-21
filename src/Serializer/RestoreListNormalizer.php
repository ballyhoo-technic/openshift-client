<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Backups\Restore;
use UniversityOfAdelaide\OpenShift\Objects\Backups\RestoreList;

/**
 * Serializer for RestoreList objects.
 */
class RestoreListNormalizer extends BaseNormalizer {

    /**
     * {@inheritdoc}
     */
    protected string|array $supportedInterfaceOrClass = RestoreList::class;

    /**
     * {@inheritdoc}
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): RestoreList {
        $restores = RestoreList::create();

        foreach ($data['items'] as $restoreData) {
            $restores->addRestore($this->serializer->denormalize($restoreData, Restore::class));
        }

        return $restores;
    }

    public function getSupportedTypes(?string $format): array
    {
        // TODO: Implement getSupportedTypes() method.
    }
}
