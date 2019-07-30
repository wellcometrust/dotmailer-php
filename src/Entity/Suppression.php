<?php
namespace Dotmailer\Entity;

final class Suppression implements Arrayable
{
    /**
     * Unsubscribed
     *
     * The contact is unsubscribed from your communications
     */
    const REASON_UNSUBSCRIBED = 'Unsubscribed';
        
    /**
     * Soft bounced
     *
     * The contact's address is temporarily unavailable, possibly because their mailbox is too full, or their mail
     * server won't accept a message of the size sent, or their server is having temporary issues accepting any mail
     */
    const REASON_SOFTBOUNCED = 'SoftBounced';
    
    /**
     * Hard bounced
     *
     * The contact's address is permanently unreachable, most likely because they, or the server they were hosted on,
     * does not exist
     */
    const REASON_HARDBOUNCED = 'HardBounced';
    
    /**
     * ISP complained
     *
     * The contact has submitted a spam complaint to us via their internet service provider
     */
    const REASON_ISPCOMPLAINED = 'IspComplained';
    
    /**
     * Mail blocked
     *
     * The mail server indicated that it didn’t want to receive the mail. No reason was given
     */
    const REASON_MAILBLOCKED = 'MailBlocked';
    
    /**
     * Direct complaint
     *
     * The contact has complained directly to either us, a hosting facility or possibly even a blacklist about
     * receiving your communications
     */
    const REASON_DIRECTCOMPLAINT = 'DirectComplaint';
    
    /**
     * Suppressed
     *
     * The contact that has been actively suppressed by you in your account
     */
    const REASON_SUPPRESSED = 'Suppressed';
    
    /**
     * Not allowed
     *
     * The contact's email address is fully blocked from our system
     */
    const REASON_NOTALLOWED = 'NotAllowed';
    
    /**
     * Domain suppression
     *
     * The contact's email domain is on your domain suppression list
     */
    const REASON_DOMAINSUPPRESSION = 'DomainSuppression';
    
    /**
     * No MX record
     *
     * The contact's email domain does not have an MX DNS record. A mail exchange record provides the address of the
     * mail server for that domain.
     */
    const REASON_NOMXRECORD = 'NoMxRecord';

    /**
     * @var Contact
     */
    private $suppressedContact;

    /**
     * @var \DateTime
     */
    private $dateRemoved;

    /**
     * @var string
     */
    private $reason;

    /**
     * @param int|null $id
     * @param \DateTime $dateRemoved
     * @param string $reason
     */
    public function __construct(
        Contact $suppressedContact,
        \DateTime $dateRemoved,
        string $reason
    ) {
        $this->suppressedContact = $suppressedContact;
        $this->dateRemoved = $dateRemoved;
        $this->reason = $reason;
    }

    /**
     * @return Contact
     */
    public function getSuppressedContact(): Contact
    {
        return $this->suppressedContact;
    }

    /**
     * @return \DateTime
     */
    public function getDateRemoved(): \DateTime
    {
        return $this->dateRemoved;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * @inheritdoc
     */
    public function asArray(): array
    {
        return [
            'suppressedContact' => $this->suppressedContact,
            'dateRemoved' => $this->dateRemoved,
            'reason' => $this->reason,
        ];
    }
}
