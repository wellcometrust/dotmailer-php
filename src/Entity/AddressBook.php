<?php

namespace Dotmailer\Entity;

final class AddressBook
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
    private $contacts = 0;

    /**
     * @param string $name
     * @param string $visibility
     */
    public function __construct(string $name, string $visibility = self::VISIBILITY_PRIVATE)
    {
        $this->name = $name;
        $this->visibility = $visibility;
    }

    /**
     * @param array $data
     *
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $addressBook = new self($data['name'], $data['visibility']);
        $addressBook->setId($data['id']);
        $addressBook->setContacts($data['contacts']);

        return $addressBook;
    }

    /**
     * @return array
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

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getVisibility(): string
    {
        return $this->visibility;
    }

    /**
     * @param string $visibility
     */
    public function setVisibility(string $visibility)
    {
        $this->visibility = $visibility;
    }

    /**
     * @return int
     */
    public function getContacts(): int
    {
        return $this->contacts;
    }

    /**
     * @param int $contacts
     */
    public function setContacts(int $contacts)
    {
        $this->contacts = $contacts;
    }
}
