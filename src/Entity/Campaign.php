<?php

namespace Dotmailer\Entity;

abstract class Campaign implements Arrayable
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
     * @var string
     */
    private $status;

    /**
     * @param int|null $id
     * @param string $name
     * @param string $subject
     * @param string $fromName
     * @param string $htmlContent
     * @param string $plainTextContent
     * @param Address $fromAddress
     * @param string $replyAction
     * @param string $replyToAddress
     * @param string $status
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        ?int $id,
        string $name,
        string $subject,
        string $fromName,
        string $htmlContent = null,
        string $plainTextContent = null,
        Address $fromAddress = null,
        string $replyAction = self::REPLY_ACTION_UNSET,
        string $replyToAddress = '',
        string $status = self::STATUS_UNSENT
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->subject = $subject;
        $this->fromName = $fromName;
        $this->htmlContent = $htmlContent;
        $this->plainTextContent = $plainTextContent;
        $this->fromAddress = $fromAddress;
        $this->replyAction = $replyAction;
        $this->replyToAddress = $replyToAddress;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getFromName(): string
    {
        return $this->fromName;
    }

    /**
     * @return string|null
     */
    public function getHtmlContent(): ?string
    {
        return $this->htmlContent;
    }

    /**
     * @return string|null
     */
    public function getPlainTextContent(): ?string
    {
        return $this->plainTextContent;
    }

    /**
     * @return Address
     */
    public function getFromAddress(): ?Address
    {
        return $this->fromAddress;
    }

    /**
     * @return string
     */
    public function getReplyAction(): string
    {
        return $this->replyAction;
    }

    /**
     * @return string
     */
    public function getReplyToAddress(): string
    {
        return $this->replyToAddress;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    abstract public function isSplitTest(): bool;

    /**
     * @inheritdoc
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
            'isSplitTest' => $this->isSplitTest(),
            'status' => $this->status,
        ];
    }
}
