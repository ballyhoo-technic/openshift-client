<?php

namespace UniversityOfAdelaide\OpenShift\Serializer;

/**
 * Provides support for checking a class name for normalization support.
 */
trait ClassNameSupportNormalizerTrait {

    /**
     * The interface or class that this Normalizer supports.
     *
     * @var string|array
     */
    protected string|array $supportedInterfaceOrClass;

    /**
     * Gets the string or array of supported classes.
     *
     * @return array|string
     *   The string or array of supported classes.
     */
    public function getSupportedInterfaceOrClass(): array|string {
        return $this->supportedInterfaceOrClass;
    }

    /**
     * Sets the string or array of supported classes.
     *
     * @param  array|string  $supported_interface_or_class
     *   The string or array of supported classes.
     *
     * @return BaseNormalizer|ClassNameSupportNormalizerTrait The current object.
     *   The current object.
     */
    public function setSupportedInterfaceOrClass(array|string $supported_interface_or_class): self {
        $this->supportedInterfaceOrClass = $supported_interface_or_class;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool {
        // If we aren't dealing with an object or the format is not supported return
        // now.
        if (!is_object($data)) {
            return FALSE;
        }

        $supported = (array)$this->supportedInterfaceOrClass;

        return (bool)array_filter($supported, function ($name) use ($data) {
            return $data instanceof $name;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool {
        $supported = (array)$this->supportedInterfaceOrClass;

        $subclass_check = function ($name) use ($type) {
            return (class_exists($name) || interface_exists($name)) && is_subclass_of($type,
                    $name, TRUE);
        };

        return in_array($type, $supported) || array_filter($supported,
                $subclass_check);
    }

}
