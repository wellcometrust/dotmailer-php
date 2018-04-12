<?php

namespace Dotmailer;

use Dotmailer\Adapter\Adapter;
use Dotmailer\Entity\Address;
use Dotmailer\Entity\AddressBook;
use Dotmailer\Entity\Campaign;
use Dotmailer\Entity\Contact;
use Dotmailer\Entity\Program;
use Dotmailer\Entity\StandardCampaign;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
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
     * @var Adapter|MockObject
     */
    private $adapter;

    /**
     * @var Dotmailer
     */
    private $dotmailer;

    /**
     * @throws \ReflectionException
     */
    public function setUp()
    {
        $this->adapter = $this->createMock(Adapter::class);
        $this->dotmailer = new Dotmailer($this->adapter);
    }

    public function testGetAddressBooks()
    {
        $this->adapter
            ->expects($this->once())
            ->method('get')
            ->with('/v2/address-books')
            ->willReturn(
                $this->getResponse(
                    [$this->getAddressBook()->asArray()]
                )
            );

        $this->assertEquals([$this->getAddressBook()], $this->dotmailer->getAddressBooks());
    }

    public function testGetAllCampaigns()
    {
        $this->adapter
            ->expects($this->once())
            ->method('get')
            ->with('/v2/campaigns')
            ->willReturn(
                $this->getResponse(
                    [$this->getCampaign()->asArray()]
                )
            );

        $this->assertEquals([$this->getCampaign()], $this->dotmailer->getAllCampaigns());
    }

    public function testGetCampaign()
    {
        $this->adapter
            ->expects($this->once())
            ->method('get')
            ->with('/v2/campaigns/' . self::ID)
            ->willReturn(
                $this->getResponse($this->getCampaign()->asArray())
            );

        $this->assertEquals($this->getCampaign(), $this->dotmailer->getCampaign(self::ID));
    }

    public function testCreateContact()
    {
        $response = $this->getResponse();

        $this->adapter
            ->expects($this->once())
            ->method('post')
            ->with('/v2/contacts', $this->getContact()->asArray())
            ->willReturn($response);

        $this->dotmailer->createContact($this->getContact());

        $this->assertEquals($response, $this->dotmailer->getResponse());
    }

    public function testUpdateContact()
    {
        $response = $this->getResponse();

        $this->adapter
            ->expects($this->once())
            ->method('put')
            ->with('/v2/contacts/' . self::ID, $this->getContact()->asArray())
            ->willReturn($response);

        $this->dotmailer->updateContact($this->getContact());

        $this->assertEquals($response, $this->dotmailer->getResponse());
    }

    public function testAddContactToAddressBook()
    {
        $response = $this->getResponse();

        $this->adapter
            ->expects($this->once())
            ->method('post')
            ->with('/v2/address-books/' . self::ID . '/contacts', $this->getContact()->asArray())
            ->willReturn($response);

        $this->dotmailer->addContactToAddressBook($this->getContact(), $this->getAddressBook());

        $this->assertEquals($response, $this->dotmailer->getResponse());
    }

    public function testDeleteContactFromAddressBook()
    {
        $response = $this->getResponse();

        $this->adapter
            ->expects($this->once())
            ->method('delete')
            ->with('/v2/address-books/' . self::ID . '/contacts/' . self::ID)
            ->willReturn($response);

        $this->dotmailer->deleteContactFromAddressBook($this->getContact(), $this->getAddressBook());

        $this->assertEquals($response, $this->dotmailer->getResponse());
    }

    public function testGetContactByEmail()
    {
        $this->adapter
            ->expects($this->once())
            ->method('get')
            ->with('/v2/contacts/' . self::EMAIL)
            ->willReturn(
                $this->getResponse($this->getContact()->asArray())
            );

        $this->assertEquals($this->getContact(), $this->dotmailer->getContactByEmail(self::EMAIL));
    }

    public function testGetContactAddressBooks()
    {
        $this->adapter
            ->expects($this->once())
            ->method('get')
            ->with('/v2/contacts/' . self::ID . '/address-books')
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

        $this->adapter
            ->expects($this->once())
            ->method('get')
            ->with('/v2/programs')
            ->willReturn($this->getResponse([$programData]));

        $this->assertEquals(
            [
                new Program(
                    $programData['id'],
                    $programData['name'],
                    $programData['status'],
                    new \DateTime($programData['dateCreated'])
                )
            ],
            $this->dotmailer->getPrograms()
        );
    }

    public function testCreateProgramEnrolment()
    {
        $response = $this->getResponse();

        $this->adapter
            ->expects($this->once())
            ->method('post')
            ->with(
                '/v2/programs/enrolments',
                [
                    'programId' => self::ID,
                    'contacts' => [self::ID],
                    'addressBooks' => [self::ID],
                ]
            )->willReturn($response);

        $this->dotmailer->createProgramEnrolment($this->getProgram(), [$this->getContact()], [$this->getAddressBook()]);

        $this->assertEquals($response, $this->dotmailer->getResponse());
    }

    public function testSendTransactionalEmail()
    {
        $response = $this->getResponse();

        $this->adapter
            ->expects($this->once())
            ->method('post')
            ->with(
                '/v2/email',
                [
                    'toAddresses' => [self::EMAIL],
                    'subject' => self::SUBJECT,
                    'fromAddress' => self::EMAIL,
                    'htmlContent' => self::HTML_CONTENT,
                    'plainTextContent' => self::PLAIN_TEXT_CONTENT,
                    'ccAddresses' => [],
                    'bccAddresses' => [],
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

        $this->adapter
            ->expects($this->once())
            ->method('post')
            ->with(
                '/v2/email/triggered-campaign',
                [
                    'toAddresses' => [self::EMAIL],
                    'campaignId' => self::ID,
                    'personalizationValues' => [
                        ['Name' => 'FOO', 'Value' => 'qux'],
                        ['Name' => 'BAR', 'Value' => 'quux'],
                        ['Name' => 'BAZ', 'Value' => 'quuz'],
                    ],
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
     * @return Response
     */
    private function getResponse(array $contents = []): Response
    {
        return new Response(200, [], json_encode($contents));
    }

    /**
     * @return AddressBook
     */
    private function getAddressBook(): AddressBook
    {
        $addressBook = new AddressBook(self::ID, self::NAME);

        return $addressBook;
    }

    /**
     * @return Campaign
     */
    private function getCampaign(): Campaign
    {
        $campaign = new StandardCampaign(
            self::ID,
            self::NAME,
            self::SUBJECT,
            self::FROM_NAME,
            self::HTML_CONTENT,
            self::PLAIN_TEXT_CONTENT,
            new Address(self::ID, self::EMAIL)
        );

        return $campaign;
    }

    /**
     * @return Contact
     */
    private function getContact(): Contact
    {
        $contact = new Contact(self::ID, self::EMAIL);

        return $contact;
    }

    /**
     * @return Program
     */
    private function getProgram(): Program
    {
        return new Program(self::ID, 'test program', Program::STATUS_ACTIVE, new \DateTime());
    }
}
