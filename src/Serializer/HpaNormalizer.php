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
    public function denormalize($data, $class, $format = NULL, array $context = []): Hpa {
        /** @var Hpa $hpa */
        $hpa = Hpa::create()->setName($data['metadata']['name']);
        return $hpa;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = NULL, array $context = []): array {
        /** @var Hpa $object */
        $data = [
            'apiVersion' => 'autoscaling/v1',
            'kind' => 'HorizontalPodAutoscaler',
            'metadata' => [
                'name' => $object->getName(),
            ],
            'spec' => [
                'minReplicas' => $object->getMinReplicas(),
                'maxReplicas' => $object->getMaxReplicas(),
                'scaleTargetRef' => [
                    'apiVersion' => 'apps.openshift.io/v1',
                    'kind' => 'DeploymentConfig',
                    'name' => $object->getName(),
                ],
                'targetCPUUtilizationPercentage' => $object->getTargetCpu(),
            ],
        ];
        if ($object->getLabels()) {
            $data['metadata']['labels'] = $object->getLabels();
        }
        return $data;
    }

}
