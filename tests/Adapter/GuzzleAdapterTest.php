<?php

namespace Dotmailer\Adapter;

use Dotmailer\Dotmailer;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class GuzzleAdapterTest extends TestCase
{
    const USERNAME = 'foo';
    const PASSWORD = 'bar';
    const URL = 'foo.bar/baz';
    const CONTENT = ['foo' => 'bar'];

    /**
     * @var ClientInterface|MockObject
     */
    private $client;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var GuzzleAdapter
     */
    private $adapter;

    /**
     * @throws \ReflectionException
     */
    public function setUp()
    {
        $this->client = $this->createMock(ClientInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->adapter = new GuzzleAdapter($this->client);
    }

    public function testFromCredentials()
    {
        $adapter = GuzzleAdapter::fromCredentials(self::USERNAME, self::PASSWORD);
        $client = $this->getClient($adapter);

        $this->assertEquals([self::USERNAME, self::PASSWORD], $client->getConfig('auth'));
        $this->assertEquals(Dotmailer::DEFAULT_URI, $client->getConfig('base_uri'));
        $this->assertArraySubset(
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            $client->getConfig('headers')
        );
    }

    public function testFromCredentialsOverrideBaseUri()
    {
        $adapter = GuzzleAdapter::fromCredentials(self::USERNAME, self::PASSWORD, self::URL);
        $client = $this->getClient($adapter);

        $this->assertEquals(self::URL, $client->getConfig('base_uri'));
    }

    public function testGet()
    {
        $this->client
            ->expects($this->once())
            ->method('request')
            ->with('GET', self::URL, ['query' => self::CONTENT])
            ->willReturn($this->response);

        $this->assertEquals($this->response, $this->adapter->get(self::URL, self::CONTENT));
    }

    public function testPost()
    {
        $this->client
            ->expects($this->once())
            ->method('request')
            ->with('POST', self::URL, ['json' => self::CONTENT])
            ->willReturn($this->response);

        $this->assertEquals($this->response, $this->adapter->post(self::URL, self::CONTENT));
    }

    public function testPut()
    {
        $this->client
            ->expects($this->once())
            ->method('request')
            ->with('PUT', self::URL, ['json' => self::CONTENT])
            ->willReturn($this->response);

        $this->assertEquals($this->response, $this->adapter->put(self::URL, self::CONTENT));
    }

    public function testDelete()
    {
        $this->client
            ->expects($this->once())
            ->method('request')
            ->with('GET', self::URL)
            ->willReturn($this->response);

        $this->assertEquals($this->response, $this->adapter->get(self::URL));
    }

    /**
     * @param GuzzleAdapter $adapter
     *
     * @return ClientInterface
     */
    private function getClient(GuzzleAdapter $adapter): ClientInterface
    {
        $client = new \ReflectionProperty(GuzzleAdapter::class, 'client');
        $client->setAccessible(true);

        return $client->getValue($adapter);
    }
}
