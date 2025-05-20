<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\NetworkPolicy;

/**
 * Serializer for NetworkPolicy objects.
 */
class NetworkPolicyNormalizer extends BaseNormalizer {

    /**
     * {@inheritdoc}
     */
    protected string|array $supportedInterfaceOrClass = NetworkPolicy::class;

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $type, $format = NULL, array $context = []): NetworkPolicy {
        /** @var NetworkPolicy $np */
        $np = NetworkPolicy::create();
        $np->setName($data['metadata']['name']);
        return $np;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data, $format = NULL, array $context = []): array {
        /** @var NetworkPolicy $data */
        $object = [
            'apiVersion' => 'extensions/v1beta1',
            'kind' => 'NetworkPolicy',
            'metadata' => [
                'name' => $data->getName(),
            ],
            'spec' => [
                'ingress' => [
                    [
                        'from' => [
                            [
                                'podSelector' => [
                                    'matchLabels' => $data->getIngressMatchLabels(),
                                ],
                            ],
                        ],
                        'ports' => [
                            [
                                'port' => $data->getPort(),
                                'protocol' => 'TCP',
                            ],
                        ],
                    ],
                ],
                'podSelector' => [
                    'matchLabels' => $data->getPodSelectorMatchLabels(),
                ],
                'policyTypes' => [
                    'Ingress',
                ],
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
