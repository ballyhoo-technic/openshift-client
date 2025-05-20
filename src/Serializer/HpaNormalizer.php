<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Hpa;

/**
 * Serializer for Hpa objects.
 */
class HpaNormalizer extends BaseNormalizer {

    /**
     * {@inheritdoc}
     */
    protected string|array $supportedInterfaceOrClass = Hpa::class;

    /**
     * {@inheritdoc}
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): Hpa {
        /** @var Hpa $hpa */
        $hpa = Hpa::create()->setName($data['metadata']['name']);
        return $hpa;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): array {
        /** @var Hpa $data */
        $object = [
            'apiVersion' => 'autoscaling/v1',
            'kind' => 'HorizontalPodAutoscaler',
            'metadata' => [
                'name' => $data->getName(),
            ],
            'spec' => [
                'minReplicas' => $data->getMinReplicas(),
                'maxReplicas' => $data->getMaxReplicas(),
                'scaleTargetRef' => [
                    'apiVersion' => 'apps.openshift.io/v1',
                    'kind' => 'DeploymentConfig',
                    'name' => $data->getName(),
                ],
                'targetCPUUtilizationPercentage' => $data->getTargetCpu(),
            ],
        ];
        if ($data->getLabels()) {
            $object['metadata']['labels'] = $data->getLabels();
        }
        return $object;
    }

    public function getSupportedTypes(?string $format): array
    {
        // TODO: Implement getSupportedTypes() method.
    }
}
