<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use UniversityOfAdelaide\OpenShift\Objects\Route;

/**
 * Serializer for Route objects.
 */
class RouteNormalizer extends BaseNormalizer {

    /**
     * {@inheritdoc}
     */
    protected string|array $supportedInterfaceOrClass = Route::class;

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $type, $format = NULL, array $context = []): Route {
        /** @var Route $route */
        $route = Route::create();
        $route->setName($data['metadata']['name']);
        return $route;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data, $format = NULL, array $context = []): array {
        /** @var Route $data */
        $object = [
            'apiVersion' => 'v1',
            'kind' => 'Route',
            'metadata' => [
                'name' => $data->getName(),
            ],
            'spec' => [
                'host' => $data->getHost(),
                'path' => $data->getPath(),
                'tls' => [
                    'insecureEdgeTerminationPolicy' => $data->getInsecureEdgeTerminationPolicy(),
                    'termination' => $data->getTermination(),
                ],
                'to' => [
                    'kind' => $data->getToKind(),
                    'name' => $data->getToName(),
                    'weight' => $data->getToWeight(),
                ],
                'wildcardPolicy' => $data->getWildcardPolicy(),
            ],
        ];
        if ($data->getLabels()) {
            $object['metadata']['labels'] = $data->getLabels();
        }
        if ($data->getAnnotations()) {
            $object['metadata']['annotations'] = $data->getAnnotations();
        }
        return $object;
    }

    public function getSupportedTypes(?string $format): array
    {
        // TODO: Implement getSupportedTypes() method.
    }
}
