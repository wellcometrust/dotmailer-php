<?php

namespace Dotmailer\Entity;

final class DataField implements Arrayable
{
    const TYPE_STRING = 'String';
    const TYPE_NUMERIC = 'Numeric';
    const TYPE_DATE = 'Date';
    const TYPE_BOOLEAN = 'Boolean';

    const VISIBILITY_PRIVATE = 'Private';
    const VISIBILITY_PUBLIC = 'Public';

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $visibility;

    /**
     * @var mixed|null
     */
    private $defaultValue;

    /**
     * @param string $name
     * @param string $type
     * @param string $visibility
     * @param mixed|null $defaultValue
     */
    public function __construct(
        string $name,
        string $type,
        string $visibility = self::VISIBILITY_PRIVATE,
        $defaultValue = null
    ) {
        $this->name = strtoupper($name);
        $this->type = $type;
        $this->visibility = $visibility;
        $this->defaultValue = $defaultValue;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getVisibility(): string
    {
        return $this->visibility;
    }

    /**
     * @return mixed|null
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @inheritdoc
     */
    public function asArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'visibility' => $this->visibility,
            'defaultValue' => $this->defaultValue,
        ];
    }
}
