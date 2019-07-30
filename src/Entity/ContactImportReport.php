<?php
namespace Dotmailer\Entity;

final class ContactImportReport implements Arrayable
{
    private $newContacts;
    private $updatedContacts;
    private $globallySuppressed;
    private $invalidEntries;
    private $duplicateEmails;
    private $blocked;
    private $unsubscribed;
    private $hardBounced;
    private $softBounced;
    private $ispComplaints;
    private $mailBlocked;
    private $domainSuppressed;
    private $pendingDoubleOptin;
    private $failures;

    /**
     * @param int $newContacts
     * @param int $updatedContacts
     * @param int $globallySuppressed
     * @param int $invalidEntries
     * @param int $duplicateEmails
     * @param int $blocked
     * @param int $unsubscribed
     * @param int $hardBounced
     * @param int $softBounced
     * @param int $ispComplaints
     * @param int $mailBlocked
     * @param int $domainSuppressed
     * @param int $pendingDoubleOptin
     * @param int $failures
     */
    public function __construct(
        int $newContacts,
        int $updatedContacts,
        int $globallySuppressed,
        int $invalidEntries,
        int $duplicateEmails,
        int $blocked,
        int $unsubscribed,
        int $hardBounced,
        int $softBounced,
        int $ispComplaints,
        int $mailBlocked,
        int $domainSuppressed,
        int $pendingDoubleOptin,
        int $failures
    ) {
        $this->newContacts = $newContacts;
        $this->updatedContacts = $updatedContacts;
        $this->globallySuppressed = $globallySuppressed;
        $this->invalidEntries = $invalidEntries;
        $this->duplicateEmails = $duplicateEmails;
        $this->blocked = $blocked;
        $this->unsubscribed = $unsubscribed;
        $this->hardBounced = $hardBounced;
        $this->softBounced = $softBounced;
        $this->ispComplaints = $ispComplaints;
        $this->mailBlocked = $mailBlocked;
        $this->domainSuppressed = $domainSuppressed;
        $this->pendingDoubleOptin = $pendingDoubleOptin;
        $this->failures = $failures;
    }

    /**
     * Initialise object from JSON
     *
     * @param string $json
     * @return ContactImportReport
     */
    public static function fromJson(string $json): self
    {
        $result = json_decode($json);
        
        return new self(
            $result->newContacts,
            $result->updatedContacts,
            $result->globallySuppressed,
            $result->invalidEntries,
            $result->duplicateEmails,
            $result->blocked,
            $result->unsubscribed,
            $result->hardBounced,
            $result->softBounced,
            $result->ispComplaints,
            $result->mailBlocked,
            $result->domainSuppressed,
            $result->pendingDoubleOptin,
            $result->failures
        );
    }

    /**
     * @inheritdoc
     */
    public function asArray(): array
    {
        return [
            'newContacts' => $this->newContacts,
            'updatedContacts' => $this->updatedContacts,
            'globallySuppressed' => $this->globallySuppressed,
            'invalidEntries' => $this->invalidEntries,
            'duplicateEmails' => $this->duplicateEmails,
            'blocked' => $this->blocked,
            'unsubscribed' => $this->unsubscribed,
            'hardBounced' => $this->hardBounced,
            'softBounced' => $this->softBounced,
            'ispComplaints' => $this->ispComplaints,
            'mailBlocked' => $this->mailBlocked,
            'domainSuppressed' => $this->domainSuppressed,
            'pendingDoubleOptin' => $this->pendingDoubleOptin,
            'failures' => $this->failures,
        ];
    }
}
