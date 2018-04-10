<?php

namespace Dotmailer\Entity;

final class Campaign
{
    const REPLY_ACTION_UNSET = 'Unset';
    const STATUS_UNSENT = 'Unsent';

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
    private $subject;

    /**
     * @var string
     */
    private $fromName;

    /**
     * @var string|null
     */
    private $htmlContent;

    /**
     * @var string|null
     */
    private $plainTextContent;

    /**
     * @var Address|null
     */
    private $fromAddress;

    /**
     * @var string
     */
    private $replyAction;

    /**
     * @var string
     */
    private $replyToAddress;

    /**
     * @var bool
     */
    private $isSplitTest;

    /**
     * @var string
     */
    private $status;

    /**
     * @param string $name
     * @param string $subject
     * @param string $fromName
     * @param string $htmlContent
     * @param string $plainTextContent
     * @param Address $fromAddress
     * @param string $replyAction
     * @param string $replyToAddress
     * @param bool $isSplitTest
     * @param string $status
     */
    public function __construct(
        string $name,
        string $subject,
        string $fromName,
        string $htmlContent = null,
        string $plainTextContent = null,
        Address $fromAddress = null,
        string $replyAction = self::REPLY_ACTION_UNSET,
        string $replyToAddress = '',
        bool $isSplitTest = false,
        string $status = self::STATUS_UNSENT
    ) {
        $this->name = $name;
        $this->subject = $subject;
        $this->fromName = $fromName;
        $this->htmlContent = $htmlContent;
        $this->plainTextContent = $plainTextContent;
        $this->fromAddress = $fromAddress;
        $this->replyAction = $replyAction;
        $this->replyToAddress = $replyToAddress;
        $this->isSplitTest = $isSplitTest;
        $this->status = $status;
    }

    /**
     * @param array $data
     *
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $campaign = new self(
            $data['name'],
            $data['subject'],
            $data['fromName'],
            $data['htmlContent'],
            $data['plainTextContent'],
            Address::fromArray($data['fromAddress']),
            $data['replyAction'],
            $data['replyToAddress'] ?? '',
            $data['isSplitTest'],
            $data['status']
        );
        $campaign->setId($data['id']);

        return $campaign;
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'subject' => $this->subject,
            'fromName' => $this->fromName,
            'htmlContent' => $this->htmlContent,
            'plainTextContent' => $this->plainTextContent,
            'fromAddress' => $this->fromAddress ? $this->fromAddress->asArray() : null,
            'replyAction' => $this->replyAction,
            'replyToAddress' => $this->replyToAddress,
            'isSplitTest' => $this->isSplitTest,
            'status' => $this->status,
        ];
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
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
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getFromName(): string
    {
        return $this->fromName;
    }

    /**
     * @param string $fromName
     */
    public function setFromName(string $fromName)
    {
        $this->fromName = $fromName;
    }

    /**
     * @return string|null
     */
    public function getHtmlContent(): ?string
    {
        return $this->htmlContent;
    }

    /**
     * @param string $htmlContent
     */
    public function setHtmlContent(string $htmlContent)
    {
        $this->htmlContent = $htmlContent;
    }

    /**
     * @return string|null
     */
    public function getPlainTextContent(): ?string
    {
        return $this->plainTextContent;
    }

    /**
     * @param string $plainTextContent
     */
    public function setPlainTextContent(string $plainTextContent)
    {
        $this->plainTextContent = $plainTextContent;
    }

    /**
     * @return Address
     */
    public function getFromAddress(): ?Address
    {
        return $this->fromAddress;
    }

    /**
     * @param Address $fromAddress
     */
    public function setFromAddress(Address $fromAddress)
    {
        $this->fromAddress = $fromAddress;
    }

    /**
     * @return string
     */
    public function getReplyAction(): string
    {
        return $this->replyAction;
    }

    /**
     * @param string $replyAction
     */
    public function setReplyAction(string $replyAction)
    {
        $this->replyAction = $replyAction;
    }

    /**
     * @return string
     */
    public function getReplyToAddress(): string
    {
        return $this->replyToAddress;
    }

    /**
     * @param string $replyToAddress
     */
    public function setReplyToAddress(string $replyToAddress)
    {
        $this->replyToAddress = $replyToAddress;
    }

    /**
     * @return bool
     */
    public function isSplitTest(): bool
    {
        return $this->isSplitTest;
    }

    /**
     * @param bool $isSplitTest
     */
    public function setIsSplitTest(bool $isSplitTest)
    {
        $this->isSplitTest = $isSplitTest;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }
}
