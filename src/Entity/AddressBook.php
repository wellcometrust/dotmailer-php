<?php

namespace Dotmailer\Entity;

final class AddressBook implements Arrayable
{
    const VISIBILITY_PRIVATE = 'Private';
    const VISIBILITY_PUBLIC = 'Public';

    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $visibility;

    /**
     * @var int
     */
    private $contacts;

    /**
     * @param int|null $id
     * @param string $name
     * @param string $visibility
     * @param int $contacts
     */
    public function __construct(
        ?int $id,
        string $name,
        string $visibility = self::VISIBILITY_PRIVATE,
        int $contacts = 0
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->visibility = $visibility;
        $this->contacts = $contacts;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
    public function getVisibility(): string
    {
        return $this->visibility;
    }

    /**
     * @return int
     */
    public function getContacts(): int
    {
        return $this->contacts;
    }

    /**
     * @inheritdoc
     */
    public function asArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'visibility' => $this->visibility,
            'contacts' => $this->contacts,
        ];
    }
}
