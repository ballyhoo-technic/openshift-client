<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\ConfigMap;

/**
 * Serializer for ConfigMap objects.
 */
class ConfigMapNormalizer extends BaseNormalizer {

    /**
     * {@inheritdoc}
     */
    protected string|array $supportedInterfaceOrClass = ConfigMap::class;

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $type, $format = NULL, array $context = []): ConfigMap {
        /** @var ConfigMap $configMap */
        $configMap = ConfigMap::create();
        $configMap->setName($data['metadata']['name'])
            ->setLabels($data['metadata']['labels'] ?? [])
            ->setCreationTimestamp($data['metadata']['creationTimestamp'])
            ->setData($data['data'] ?? []);

        return $configMap;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data, $format = NULL, array $context = []): array {
        /** @var ConfigMap $data */
        $object = [
            'apiVersion' => 'v1',
            'kind' => 'ConfigMap',
            'metadata' => [
                'name' => $data->getName(),
            ],
            'data' => $data->getData(),
        ];
        if (!empty($data->getLabels())) {
            $object['metadata']['labels'] = $data->getLabels();
        }
        return $object;
    }

    public function getSupportedTypes(?string $format): array
    {
        // TODO: Implement getSupportedTypes() method.
    }
}
