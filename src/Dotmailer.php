<?php

namespace Dotmailer;

use Dotmailer\Adapter\Adapter;
use Dotmailer\Entity\AddressBook;
use Dotmailer\Entity\Campaign;
use Dotmailer\Entity\Contact;
use Dotmailer\Entity\DataField;
use Dotmailer\Entity\Program;
use Dotmailer\Factory\CampaignFactory;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\json_decode;
use Dotmailer\Entity\Suppression;
use Dotmailer\Entity\ContactImportStatus;
use Dotmailer\Entity\ContactImportReport;

class Dotmailer
{
    const DEFAULT_URI = 'https://r1-api.dotmailer.com';
    const GUID_REGEX = '/^[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}?$/i';

    /**
     * @var Adapter
     */
    private $adapter;

    /**
     * @var ResponseInterface|null
     */
    private $response;

    /**
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return ResponseInterface|null
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    /**
     * @return AddressBook[]
     */
    public function getAddressBooks(): array
    {
        $this->response = $this->adapter->get('/v2/address-books');
        $addressBooks = [];

        foreach (json_decode($this->response->getBody()->getContents()) as $addressBook) {
            $addressBooks[] = new AddressBook(
                $addressBook->id,
                $addressBook->name,
                $addressBook->visibility,
                $addressBook->contacts
            );
        }

        return $addressBooks;
    }

    /**
     * @return Campaign[]
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function getAllCampaigns(): array
    {
        $this->response = $this->adapter->get('/v2/campaigns');
        $campaigns = [];

        foreach (json_decode($this->response->getBody()->getContents()) as $campaign) {
            $campaigns[] = CampaignFactory::build($campaign);
        }

        return $campaigns;
    }

    /**
     * @param int $id
     *
     * @return Campaign
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function getCampaign(int $id): Campaign
    {
        $this->response = $this->adapter->get('/v2/campaigns/' . $id);

        return CampaignFactory::build(
            json_decode($this->response->getBody()->getContents())
        );
    }

    /**
     * @param Contact $contact
     */
    public function createContact(Contact $contact)
    {
        $this->response = $this->adapter->post('/v2/contacts', $contact->asArray());
    }

    /**
     * @param Contact $contact
     */
    public function deleteContact(Contact $contact)
    {
        $this->response = $this->adapter->delete('/v2/contacts/' . $contact->getId());
    }

    /**
     * @param Contact $contact
     */
    public function updateContact(Contact $contact)
    {
        $this->response = $this->adapter->put(
            '/v2/contacts/' . $contact->getId(),
            $contact->asArray()
        );
    }

    /**
     * @param Contact $contact
     * @param AddressBook $addressBook
     */
    public function addContactToAddressBook(Contact $contact, AddressBook $addressBook)
    {
        $this->response = $this->adapter->post(
            '/v2/address-books/' . $addressBook->getId(). '/contacts',
            $contact->asArray()
        );
    }

    /**
     * @param Contact $contact
     * @param AddressBook $addressBook
     */
    public function deleteContactFromAddressBook(Contact $contact, AddressBook $addressBook)
    {
        $this->response = $this->adapter->delete(
            '/v2/address-books/' . $addressBook->getId(). '/contacts/' . $contact->getId()
        );
    }

    /**
     * @param string $email
     *
     * @return Contact
     */
    public function getContactByEmail(string $email): Contact
    {
        $this->response = $this->adapter->get('/v2/contacts/' . $email);

        $contact = json_decode($this->response->getBody()->getContents());

        return new Contact(
            $contact->id,
            $contact->email,
            $contact->optInType,
            $contact->emailType,
            $contact->dataFields,
            $contact->status
        );
    }

    /**
     * @param Contact $contact
     *
     * @return AddressBook[]
     */
    public function getContactAddressBooks(Contact $contact): array
    {
        $this->response = $this->adapter->get('/v2/contacts/' . $contact->getId() . '/address-books');

        $addressBooks = [];

        foreach (json_decode($this->response->getBody()->getContents()) as $addressBook) {
            $addressBooks[] = new AddressBook(
                $addressBook->id,
                $addressBook->name,
                $addressBook->visibility,
                $addressBook->contacts
            );
        }

        return $addressBooks;
    }

    /**
     * @param \DateTimeInterface $dateTime
     * @param int|null $select
     * @param int|null $skip
     *
     * @return Contact[]
     */
    public function getUnsubscribedContactsSince(
        \DateTimeInterface $dateTime,
        int $select = null,
        int $skip = null
    ): array {
        $this->response = $this->adapter->get(
            '/v2/contacts/unsubscribed-since/' . $dateTime->format('Y-m-d'),
            array_filter([
                'select' => $select,
                'skip' => $skip,
            ])
        );

        $unsubscriptions = [];

        foreach (json_decode($this->response->getBody()->getContents()) as $unsubscription) {
            $unsubscriptions[] = [
                'suppressedContact' => new Contact(
                    $unsubscription->suppressedContact->id,
                    $unsubscription->suppressedContact->email,
                    $unsubscription->suppressedContact->optInType,
                    $unsubscription->suppressedContact->emailType
                ),
                'dateRemoved' => new \DateTime($unsubscription->dateRemoved),
                'reason' => $unsubscription->reason,
            ];
        }

        return $unsubscriptions;
    }

    /**
     * @param Contact $contact
     */
    public function unsubscribeContact(Contact $contact)
    {
        $this->response = $this->adapter->post('/v2/contacts/unsubscribe', ['email' => $contact->getEmail()]);
    }

    /**
     * @param Contact $contact
     * @param string|null $preferredLocale
     * @param string|null $challengeUrl
     */
    public function resubscribeContact(Contact $contact, string $preferredLocale = null, string $challengeUrl = null)
    {
        $content = [
            'unsubscribedContact' => [
                'email' => $contact->getEmail()
            ],
            'preferredLocale' => $preferredLocale,
            'returnUrlToUseIfChallenged' => $challengeUrl,
        ];

        $this->response = $this->adapter->post('/v2/contacts/resubscribe', array_filter($content));
    }
    
    /**
     * @param Contact $contact
     * @param string|null $preferredLocale
     * @param string|null $challengeUrl
     */
    public function resubscribeContactWithNoChallenge(Contact $contact)
    {
        $content = [
            'unsubscribedContact' => [
                'email' => $contact->getEmail()
            ],
        ];
        
        $this->response = $this->adapter->post('/v2/contacts/resubscribe-with-no-challenge', array_filter($content));
    }

    /**
     * @param DataField $dataField
     */
    public function createContactDataField(DataField $dataField)
    {
        $this->response = $this->adapter->post('/v2/data-fields', $dataField->asArray());
    }

    /**
     * @param DataField $dataField
     */
    public function deleteContactDataField(DataField $dataField)
    {
        $this->response = $this->adapter->delete('/v2/data-fields/' . $dataField->getName());
    }

    /**
     * @return Program[]
     */
    public function getPrograms(): array
    {
        $this->response = $this->adapter->get('/v2/programs');
        $programs = [];

        foreach (json_decode($this->response->getBody()->getContents()) as $program) {
            $programs[] = new Program(
                $program->id,
                $program->name,
                $program->status,
                new \DateTimeImmutable($program->dateCreated)
            );
        }

        return $programs;
    }

    /**
     * @param Program $program
     * @param Contact[] $contacts
     * @param AddressBook[] $addressBooks
     */
    public function createProgramEnrolment(Program $program, array $contacts = [], array $addressBooks = [])
    {
        $this->response = $this->adapter->post(
            '/v2/programs/enrolments',
            [
                'programId' => $program->getId(),
                'contacts' => array_map(
                    function (Contact $contact) {
                        return $contact->getId();
                    },
                    $contacts
                ),
                'addressBooks' => array_map(
                    function (AddressBook $addressBook) {
                        return $addressBook->getId();
                    },
                    $addressBooks
                ),
            ]
        );
    }

    /**
     * @param string[] $toAddresses
     * @param string $subject
     * @param string $fromAddress
     * @param string $htmlContent
     * @param string $plainTextContent
     * @param string[] $ccAddresses
     * @param string[] $bccAddresses
     */
    public function sendTransactionalEmail(
        array $toAddresses,
        string $subject,
        string $fromAddress,
        string $htmlContent,
        string $plainTextContent,
        array $ccAddresses = [],
        array $bccAddresses = []
    ) {
        $this->response = $this->adapter->post(
            '/v2/email',
            [
                'toAddresses' => $toAddresses,
                'subject' => $subject,
                'fromAddress' => $fromAddress,
                'htmlContent' => $htmlContent,
                'plainTextContent' => $plainTextContent,
                'ccAddresses' => $ccAddresses,
                'bccAddresses' => $bccAddresses,
            ]
        );
    }

    /**
     * @param string[] $toAddresses
     * @param int $campaignId
     * @param string[] $personalizationValues
     */
    public function sendTransactionalEmailUsingATriggeredCampaign(
        array $toAddresses,
        int $campaignId,
        array $personalizationValues
    ) {
        $this->response = $this->adapter->post(
            '/v2/email/triggered-campaign',
            [
                'toAddresses' => $toAddresses,
                'campaignId' => $campaignId,
                'personalizationValues' => array_map(
                    function (string $name, string $value) {
                        return [
                            'Name' => strtoupper($name),
                            'Value' => $value
                        ];
                    },
                    array_keys($personalizationValues),
                    $personalizationValues
                ),
            ]
        );
    }

    /**
     * @param \DateTimeInterface $dateTime
     * @param int|null $select
     * @param int|null $skip
     *
     * @return Suppression[]
     */
    public function getSuppressedContactsSince(
        \DateTimeInterface $dateTime,
        int $select = null,
        int $skip = null
    ): array {
        $this->response = $this->adapter->get(
            '/v2/contacts/suppressed-since/' . $dateTime->format('Y-m-d'),
            array_filter([
                'select' => $select,
                'skip' => $skip,
            ])
        );

        $suppressions = [];

        foreach (json_decode($this->response->getBody()->getContents()) as $suppression) {
            $suppressions[] = new Suppression(
                new Contact(
                    $suppression->suppressedContact->id,
                    $suppression->suppressedContact->email,
                    $suppression->suppressedContact->optInType,
                    $suppression->suppressedContact->emailType
                ),
                new \DateTime($suppression->dateRemoved),
                $suppression->reason
            );
        }

        return $suppressions;
    }
    
    /**
     * Bulk creates, or bulk updates, contacts in an address book
     *
     * @param AddressBook $addressBook Object containing the ID of the address book
     * @param string $filePath Local filesystem path of the file to be imported
     * @param string $fileName Discrete file name to pass to API
     *
     * @return \Dotmailer\Entity\ContactImportStatus
     */
    public function bulkCreateContactsInAddressBook(AddressBook $addressBook, string $filePath, string $fileName)
    {
        $this->response = $this->adapter->postfile(
            '/v2/address-books/' . $addressBook->getId() . '/contacts/import',
            $filePath,
            $fileName,
            'text/csv'
        );
        
        $response = json_decode($this->response->getBody()->getContents());
        
        $importStatus = new ContactImportStatus($response->id, $response->status);
        
        return $importStatus;
    }
    
    /**
     * @param string $id GUID import ID
     *
     * @return ContactImportStatus
     */
    public function getContactImportStatus(string $id): ContactImportStatus
    {
        if (!preg_match(self::GUID_REGEX, $id)) {
            throw new \Exception('ID did not contain a valid GUID');
        }
        
        $this->response = $this->adapter->get('/v2/contacts/import/' . $id);
        
        $response = json_decode($this->response->getBody()->getContents());
        
        $importStatus = new ContactImportStatus($response->id, $response->status);
        
        return $importStatus;
    }
    
    /**
     * @param string $id GUID import ID
     *
     * @return ContactImportReport
     */
    public function getContactImportReport(string $id): ContactImportReport
    {
        if (!preg_match(self::GUID_REGEX, $id)) {
            throw new \Exception('ID did not contain a valid GUID');
        }
        
        $this->response = $this->adapter->get('/v2/contacts/import/' . $id . '/report');
        
        $report = ContactImportReport::fromJson($this->response->getBody()->getContents());
        
        return $report;
    }
}
