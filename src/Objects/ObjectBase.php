<?php

namespace UniversityOfAdelaide\OpenShift\Objects;

/**
 * A base class for objects.
 */
abstract class ObjectBase {

    /**
     * The name of the object.
     *
     * @var string
     */
    protected string $name;

    /**
     * An array of labels.
     *
     * @var array
     */
    protected array $labels = [];

    /**
     * The time the object was created.
     *
     * @var string
     */
    protected string $creationTimestamp;

    /**
     * Factory method for creating a new object.
     *
     * @return self
     *   Returns static object.
     */
    public static function create(): self {
        return new static();
    }

    /**
     * Gets the value of name.
     *
     * @return string
     *   Value of name.
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Sets the name.
     *
     * @param string $name
     *   The name of the object.
     *
     * @return $this
     *   The calling class.
     */
    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets the value of labels.
     *
     * @return array
     *   Value of labels.
     */
    public function getLabels(): array {
        return $this->labels;
    }

    /**
     * Sets the array of labels.
     *
     * @param array $labels
     *   An array of labels.
     *
     * @return $this
     *   The calling class.
     */
    public function setLabels(array $labels): self {
        $this->labels = $labels;
        return $this;
    }

    /**
     * Set a single label.
     *
     * @param Label $label
     *   The label object.
     *
     * @return $this
     *   The calling class.
     */
    public function setLabel(Label $label): self {
        $this->labels[$label->getKey()] = $label->getValue();
        return $this;
    }

    /**
     * Get a single label.
     *
     * @param string $key
     *   The key of the label.
     *
     * @return string|bool
     *   The label value, or FALSE if it doesn't exist.
     */
    public function getLabel(string $key): string|false {
        return isset($this->getLabels()[$key]) ? $this->getLabels()[$key] : FALSE;
    }

    /**
     * Gets the value of creationTimestamp.
     *
     * @return string
     *   Value of creationTimestamp.
     */
    public function getCreationTimestamp(): string {
        return $this->creationTimestamp;
    }

    /**
     * Sets the value of creationTimestamp.
     *
     * @param string $creationTimestamp
     *   The value for creationTimestamp.
     *
     * @return $this
     *   The calling class.
     */
    public function setCreationTimestamp(string $creationTimestamp): self {
        $this->creationTimestamp = $creationTimestamp;
        return $this;
    }

}
