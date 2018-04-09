<?php

namespace Dotmailer\Entity;

final class Address
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @param string $email
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * @param array $data
     *
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $address = new self($data['email']);
        $address->id = $data['id'];

        return $address;
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email
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
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }
}
