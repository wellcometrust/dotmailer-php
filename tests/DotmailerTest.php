<?php

namespace Dotmailer;

use Dotmailer\Entity\Address;
use Dotmailer\Entity\AddressBook;
use Dotmailer\Entity\Campaign;
use Dotmailer\Entity\Contact;
use Dotmailer\Entity\Program;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use function GuzzleHttp\json_encode;

class DotmailerTest extends TestCase
{
    const ID = 1337;
    const EMAIL = 'test@email.address';
    const NAME = 'test name';
    const SUBJECT = 'test subject';
    const HTML_CONTENT = '<strong>foo</strong>';
    const PLAIN_TEXT_CONTENT = 'foo';
    const FROM_NAME = 'test name';

    /**
     * @var ClientInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $client;

    /**
     * @var Dotmailer
     */
    private $dotmailer;

    public function setUp()
    {
        $this->client = $this->createMock(ClientInterface::class);
        $this->dotmailer = new Dotmailer($this->client);
    }

    public function testGetAddressBooks()
    {
        $this->client
            ->expects($this->once())
            ->method('request')
            ->with('GET', '/v2/address-books')
            ->willReturn(
                $this->getResponse(
                    [$this->getAddressBook()->asArray()]
                )
            );

        $this->assertEquals([$this->getAddressBook()], $this->dotmailer->getAddressBooks());
    }

    public function testGetAllCampaigns()
    {
        $this->client
            ->expects($this->once())
            ->method('request')
            ->with('GET', '/v2/campaigns')
            ->willReturn(
                $this->getResponse(
                    [$this->getCampaign()->asArray()]
                )
            );

        $this->assertEquals([$this->getCampaign()], $this->dotmailer->getAllCampaigns());
    }

    public function testGetCampaign()
    {
        $this->client
            ->expects($this->once())
            ->method('request')
            ->with('GET', '/v2/campaigns/' . self::ID)
            ->willReturn(
                $this->getResponse($this->getCampaign()->asArray())
            );

        $this->assertEquals($this->getCampaign(), $this->dotmailer->getCampaign(self::ID));
    }

    public function testCreateContact()
    {
        $response = $this->getResponse();

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with('POST', '/v2/contacts', ['json' => $this->getContact()->asArray()])
            ->willReturn($response);

        $this->dotmailer->createContact($this->getContact());

        $this->assertEquals($response, $this->dotmailer->getResponse());
    }

    public function testUpdateContact()
    {
        $response = $this->getResponse();

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with('PUT', '/v2/contacts/' . self::ID, ['json' => $this->getContact()->asArray()])
            ->willReturn($response);

        $this->dotmailer->updateContact($this->getContact());

        $this->assertEquals($response, $this->dotmailer->getResponse());
    }

    public function testAddContactToAddressBook()
    {
        $response = $this->getResponse();

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with('POST', '/v2/address-books/' . self::ID . '/contacts', ['json' => $this->getContact()->asArray()])
            ->willReturn($response);

        $this->dotmailer->addContactToAddressBook($this->getContact(), $this->getAddressBook());

        $this->assertEquals($response, $this->dotmailer->getResponse());
    }

    public function testDeleteContactFromAddressBook()
    {
        $response = $this->getResponse();

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with('DELETE', '/v2/address-books/' . self::ID . '/contacts/' . self::ID)
            ->willReturn($response);

        $this->dotmailer->deleteContactFromAddressBook($this->getContact(), $this->getAddressBook());

        $this->assertEquals($response, $this->dotmailer->getResponse());
    }

    public function testGetContactByEmail()
    {
        $this->client
            ->expects($this->once())
            ->method('request')
            ->with('GET', '/v2/contacts/' . self::EMAIL)
            ->willReturn(
                $this->getResponse($this->getContact()->asArray())
            );

        $this->assertEquals($this->getContact(), $this->dotmailer->getContactByEmail(self::EMAIL));
    }

    public function testGetContactAddressBooks()
    {
        $this->client
            ->expects($this->once())
            ->method('request')
            ->with('GET', '/v2/contacts/' . self::ID . '/address-books')
            ->willReturn(
                $this->getResponse(
                    [$this->getAddressBook()->asArray()]
                )
            );

        $this->assertEquals([$this->getAddressBook()], $this->dotmailer->getContactAddressBooks($this->getContact()));
    }

    public function testGetPrograms()
    {
        $programData = [
            'id' => 1,
            'name' => 'Birthday program',
            'status' => Program::STATUS_ACTIVE,
            'dateCreated' => '2013-01-08T14:56:53',
        ];

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with('GET', '/v2/programs')
            ->willReturn($this->getResponse([$programData]));

        $this->assertEquals([Program::fromArray($programData)], $this->dotmailer->getPrograms());
    }

    public function testCreateProgramEnrolment()
    {
        $response = $this->getResponse();

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                '/v2/programs/enrolments',
                [
                    'json' => [
                        'programId' => self::ID,
                        'contacts' => [self::ID],
                        'addressBooks' => [self::ID],
                    ],
                ]
            )->willReturn($response);

        $this->dotmailer->createProgramEnrolment($this->getProgram(), [$this->getContact()], [$this->getAddressBook()]);

        $this->assertEquals($response, $this->dotmailer->getResponse());
    }

    public function testSendTransactionalEmail()
    {
        $response = $this->getResponse();

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                '/v2/email',
                [
                    'json' => [
                        'toAddresses' => [self::EMAIL],
                        'subject' => self::SUBJECT,
                        'fromAddress' => self::EMAIL,
                        'htmlContent' => self::HTML_CONTENT,
                        'plainTextContent' => self::PLAIN_TEXT_CONTENT,
                        'ccAddresses' => [],
                        'bccAddresses' => [],
                    ]
                ]
            )
            ->willReturn($response);

        $this->dotmailer->sendTransactionalEmail(
            [self::EMAIL],
            self::SUBJECT,
            self::EMAIL,
            self::HTML_CONTENT,
            self::PLAIN_TEXT_CONTENT
        );

        $this->assertEquals($response, $this->dotmailer->getResponse());
    }

    public function testSendTransactionalEmailUsingATriggeredCampaign()
    {
        $response = $this->getResponse();

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                '/v2/email/triggered-campaign',
                [
                    'json' => [
                        'toAddresses' => [self::EMAIL],
                        'campaignId' => self::ID,
                        'personalizationValues' => [
                            ['Name' => 'FOO', 'Value' => 'qux'],
                            ['Name' => 'BAR', 'Value' => 'quux'],
                            ['Name' => 'BAZ', 'Value' => 'quuz'],
                        ],
                    ]
                ]
            )
            ->willReturn($response);

        $this->dotmailer->sendTransactionalEmailUsingATriggeredCampaign(
            [self::EMAIL],
            self::ID,
            [
                'foo' => 'qux',
                'bar' => 'quux',
                'baz' => 'quuz',
            ]
        );

        $this->assertEquals($response, $this->dotmailer->getResponse());
    }

    /**
     * @param array $contents
     *
     * @return ResponseInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getResponse(array $contents = [])
    {
        $body = $this->createMock(StreamInterface::class);
        $body->method('getContents')->willReturn(json_encode($contents));

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($body);

        return $response;
    }

    private function getAddressBook(): AddressBook
    {
        $addressBook = new AddressBook(self::NAME);
        $addressBook->setId(self::ID);

        return $addressBook;
    }

    private function getCampaign(): Campaign
    {
        $campaign = new Campaign(
            self::NAME,
            self::SUBJECT,
            self::FROM_NAME,
            self::HTML_CONTENT,
            self::PLAIN_TEXT_CONTENT
        );

        $campaign->setId(self::ID);

        $fromAddress = new Address(self::EMAIL);
        $fromAddress->setId(self::ID);
        $campaign->setFromAddress($fromAddress);

        return $campaign;
    }

    private function getContact(): Contact
    {
        $contact = new Contact(self::EMAIL);
        $contact->setId(self::ID);

        return $contact;
    }

    private function getProgram(): Program
    {
        return new Program(self::ID, 'test program', Program::STATUS_ACTIVE, new \DateTime());
    }
}
