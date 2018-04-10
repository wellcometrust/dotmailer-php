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
     * @param string $email
     * @param string $optInType
     * @param string $emailType
     * @param array $dataFields
     */
    public function __construct(
        string $email,
        string $optInType = self::OPT_IN_TYPE_UNKNOWN,
        string $emailType = self::EMAIL_TYPE_PLAIN_TEXT,
        array $dataFields = []
    ) {
        $this->email = $email;
        $this->optInType = $optInType;
        $this->emailType = $emailType;
        $this->dataFields = $dataFields;
    }

    /**
     * @param array $data
     *
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $contact = new Contact(
            $data['email'],
            $data['optInType'],
            $data['emailType'],
            $data['dataFields']
        );
        $contact->setId($data['id']);

        return $contact;
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
     * @param string $emailType
     */
    public function setEmailType(string $emailType)
    {
        $this->emailType = $emailType;
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
            if ($dataField['key'] === $key) {
                return $dataField['value'];
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
            if ($dataField['key'] === $key) {
                $dataField['value'] = $value;

                return;
            }
        }

        $this->dataFields[] = ['key' => $key, 'value' => $value];
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
        ];
    }
}
