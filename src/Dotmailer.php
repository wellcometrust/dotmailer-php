<?php

namespace Dotmailer;

use Dotmailer\Entity\AddressBook;
use Dotmailer\Entity\Campaign;
use Dotmailer\Entity\Contact;
use Dotmailer\Entity\Program;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\json_decode;

class Dotmailer
{
    /**
     * @var ResponseInterface|null
     */
    private $response;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
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
     * @throws GuzzleException
     */
    public function getAddressBooks(): array
    {
        $this->response = $this->client->request('GET', '/v2/address-books');
        $addressBooks = [];

        foreach (json_decode($this->response->getBody()->getContents(), true) as $addressBook) {
            $addressBooks[] = AddressBook::fromArray($addressBook);
        }

        return $addressBooks;
    }

    /**
     * @return Campaign[]
     * @throws GuzzleException
     */
    public function getAllCampaigns(): array
    {
        $this->response = $this->client->request('GET', '/v2/campaigns');
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
     * @throws GuzzleException
     */
    public function getCampaign(int $id): Campaign
    {
        $this->response = $this->client->request('GET', '/v2/campaigns/' . $id);

        return Campaign::fromArray(
            json_decode($this->response->getBody()->getContents(), true)
        );
    }

    /**
     * @param Contact $contact
     *
     * @throws GuzzleException
     */
    public function createContact(Contact $contact)
    {
        $this->response = $this->client->request(
            'POST',
            '/v2/contacts',
            ['json' => $contact->asArray()]
        );
    }

    /**
     * @param Contact $contact
     *
     * @throws GuzzleException
     */
    public function updateContact(Contact $contact)
    {
        $this->response = $this->client->request(
            'PUT',
            '/v2/contacts/' . $contact->getId(),
            ['json' => $contact->asArray()]
        );
    }

    /**
     * @param Contact $contact
     * @param AddressBook $addressBook
     *
     * @throws GuzzleException
     */
    public function addContactToAddressBook(Contact $contact, AddressBook $addressBook)
    {
        $this->response = $this->client->request(
            'POST',
            '/v2/address-books/' . $addressBook->getId(). '/contacts',
            ['json' => $contact->asArray()]
        );
    }

    /**
     * @param Contact $contact
     * @param AddressBook $addressBook
     *
     * @throws GuzzleException
     */
    public function deleteContactFromAddressBook(Contact $contact, AddressBook $addressBook)
    {
        $this->response = $this->client->request(
            'DELETE',
            '/v2/address-books/' . $addressBook->getId(). '/contacts/' . $contact->getId()
        );
    }

    /**
     * @param string $email
     *
     * @return Contact
     * @throws GuzzleException
     */
    public function getContactByEmail(string $email): Contact
    {
        $this->response =  $this->client->request('GET', '/v2/contacts/' . $email);

        return Contact::fromArray(
            json_decode($this->response->getBody()->getContents(), true)
        );
    }

    /**
     * @param Contact $contact
     *
     * @return AddressBook[]
     * @throws GuzzleException
     */
    public function getContactAddressBooks(Contact $contact): array
    {
        $this->response = $this->client->request(
            'GET',
            '/v2/contacts/' . $contact->getId() . '/address-books'
        );

        $addressBooks = [];

        foreach (json_decode($this->response->getBody()->getContents(), true) as $addressBook) {
            $addressBooks[] = AddressBook::fromArray($addressBook);
        }

        return $addressBooks;
    }

    /**
     * @return Program[]
     * @throws GuzzleException
     */
    public function getPrograms(): array
    {
        $this->response = $this->client->request('GET', '/v2/programs');
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
     *
     * @throws GuzzleException
     */
    public function createProgramEnrolment(Program $program, array $contacts = [], array $addressBooks = [])
    {
        $this->response = $this->client->request(
            'POST',
            '/v2/programs/enrolments',
            [
                'json' => [
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
     *
     * @throws GuzzleException
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
        $this->response = $this->client->request(
            'POST',
            '/v2/email',
            [
                'json' => [
                    'toAddresses' => $toAddresses,
                    'subject' => $subject,
                    'fromAddress' => $fromAddress,
                    'htmlContent' => $htmlContent,
                    'plainTextContent' => $plainTextContent,
                    'ccAddresses' => $ccAddresses,
                    'bccAddresses' => $bccAddresses,
                ]
            ]
        );
    }

    /**
     * @param string[] $toAddresses
     * @param int $campaignId
     * @param string[] $personalizationValues
     *
     * @throws GuzzleException
     */
    public function sendTransactionalEmailUsingATriggeredCampaign(
        array $toAddresses,
        int $campaignId,
        array $personalizationValues
    ) {
        $this->response = $this->client->request(
            'POST',
            '/v2/email/triggered-campaign',
            [
                'json' => [
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
            ]
        );
    }
}
