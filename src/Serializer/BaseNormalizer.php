<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Base normalizer class.
 */
abstract class BaseNormalizer extends AbstractNormalizer {

    use ClassNameSupportNormalizerTrait;

    /**
     * {@inheritdoc}
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        throw new \RuntimeException("Method not implemented.");
    }

    /**
     * {@inheritdoc}
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        throw new \RuntimeException("Method not implemented.");
    }

}
