<?php

namespace Dotmailer\Entity;

final class Contact implements Arrayable
{
    const OPT_IN_TYPE_UNKNOWN = 'Unknown';
    const OPT_IN_TYPE_SINGLE = 'Single';
    const OPT_IN_TYPE_DOUBLE = 'Double';
    const OPT_IN_TYPE_VERIFIED_DOUBLE = 'VerifiedDouble';

    const EMAIL_TYPE_HTML = 'Html';
    const EMAIL_TYPE_PLAIN_TEXT = 'PlainText';

    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $optInType;

    /**
     * @var string
     */
    private $emailType;

    /**
     * @var array
     */
    private $dataFields;

    /**
     * @var string
     */
    private $status;

    /**
     * @param int|null $id
     * @param string $email
     * @param string $optInType
     * @param string $emailType
     * @param array $dataFields
     */
    public function __construct(
        ?int $id,
        string $email,
        string $optInType = self::OPT_IN_TYPE_UNKNOWN,
        string $emailType = self::EMAIL_TYPE_PLAIN_TEXT,
        array $dataFields = [],
        string $status = null
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->optInType = $optInType;
        $this->emailType = $emailType;
        $this->dataFields = $dataFields;
        $this->status = $status;
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
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getOptInType(): string
    {
        return $this->optInType;
    }

    /**
     * @param string $optInType
     */
    public function setOptInType(string $optInType)
    {
        $this->optInType = $optInType;
    }

    /**
     * @return string
     */
    public function getEmailType(): string
    {
        return $this->emailType;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public function getDataFields(): array
    {
        return $this->dataFields;
    }

    /**
     * @param array $dataFields
     */
    public function setDataFields(array $dataFields)
    {
        $this->dataFields = $dataFields;
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function getDataField(string $key)
    {
        $key = strtoupper($key);

        foreach ($this->dataFields as $dataField) {
            if ($dataField->key === $key) {
                return $dataField->value;
            }
        }

        return null;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setDataField(string $key, $value)
    {
        $key = strtoupper($key);

        foreach ($this->dataFields as &$dataField) {
            if ($dataField->key === $key) {
                $dataField->value = $value;

                return;
            }
        }

        $this->dataFields[] = (object) ['key' => $key, 'value' => $value];
    }

    /**
     * @inheritdoc
     */
    public function asArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'optInType' => $this->optInType,
            'emailType' => $this->emailType,
            'dataFields' => $this->dataFields,
            'status' => $this->status,
        ];
    }
}
