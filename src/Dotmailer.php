<?php

namespace Dotmailer;

use Dotmailer\Adapter\Adapter;
use Dotmailer\Entity\AddressBook;
use Dotmailer\Entity\Campaign;
use Dotmailer\Entity\Contact;
use Dotmailer\Entity\Program;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\json_decode;

class Dotmailer
{
    const DEFAULT_URI = 'https://r1-api.dotmailer.com';

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

        foreach (json_decode($this->response->getBody()->getContents(), true) as $addressBook) {
            $addressBooks[] = AddressBook::fromArray($addressBook);
        }

        return $addressBooks;
    }

    /**
     * @return Campaign[]
     */
    public function getAllCampaigns(): array
    {
        $this->response = $this->adapter->get('/v2/campaigns');
        $campaigns = [];

        foreach (json_decode($this->response->getBody()->getContents(), true) as $campaign) {
            $campaigns[] = Campaign::fromArray($campaign);
        }

        return $campaigns;
    }

    /**
     * @param int $id
     *
     * @return Campaign
     */
    public function getCampaign(int $id): Campaign
    {
        $this->response = $this->adapter->get('/v2/campaigns/' . $id);

        return Campaign::fromArray(
            json_decode($this->response->getBody()->getContents(), true)
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
        $this->response =  $this->adapter->get('/v2/contacts/' . $email);

        return Contact::fromArray(
            json_decode($this->response->getBody()->getContents(), true)
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

        foreach (json_decode($this->response->getBody()->getContents(), true) as $addressBook) {
            $addressBooks[] = AddressBook::fromArray($addressBook);
        }

        return $addressBooks;
    }

    /**
     * @return Program[]
     */
    public function getPrograms(): array
    {
        $this->response = $this->adapter->get('/v2/programs');
        $programs = [];

        foreach (json_decode($this->response->getBody()->getContents(), true) as $program) {
            $programs[] = Program::fromArray($program);
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
}
