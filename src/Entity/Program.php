<?php

namespace Dotmailer\Entity;

final class Program implements Arrayable
{
    const STATUS_ACTIVE = 'Active';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $status;

    /**
     * @var \DateTimeInterface
     */
    private $dateCreated;

    /**
     * @param int $id
     * @param string $name
     * @param string $status
     * @param \DateTimeInterface $dateCreated
     */
    public function __construct(int $id, string $name, string $status, \DateTimeInterface $dateCreated)
    {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
        $this->dateCreated = $dateCreated;
    }

    /**
     * @param array $data
     *
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['name'],
            $data['status'],
            new \DateTimeImmutable($data['dateCreated'])
        );
    }

    /**
     * @return int
     */
    public function getId(): int
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
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDateCreated(): \DateTimeInterface
    {
        return $this->dateCreated;
    }

    /**
     * @inheritdoc
     */
    public function asArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'dateCreated' => $this->dateCreated,
        ];
    }
}
