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
    public function denormalize($data, $class, $format = NULL, array $context = []): NetworkPolicy {
        /** @var NetworkPolicy $np */
        $np = NetworkPolicy::create();
        $np->setName($data['metadata']['name']);
        return $np;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = NULL, array $context = []): array {
        /** @var NetworkPolicy $object */
        $data = [
            'apiVersion' => 'extensions/v1beta1',
            'kind' => 'NetworkPolicy',
            'metadata' => [
                'name' => $object->getName(),
            ],
            'spec' => [
                'ingress' => [
                    [
                        'from' => [
                            [
                                'podSelector' => [
                                    'matchLabels' => $object->getIngressMatchLabels(),
                                ],
                            ],
                        ],
                        'ports' => [
                            [
                                'port' => $object->getPort(),
                                'protocol' => 'TCP',
                            ],
                        ],
                    ],
                ],
                'podSelector' => [
                    'matchLabels' => $object->getPodSelectorMatchLabels(),
                ],
                'policyTypes' => [
                    'Ingress',
                ],
            ],
        ];
        if ($object->getLabels()) {
            $data['metadata']['labels'] = $object->getLabels();
        }
        return $data;
    }

}
