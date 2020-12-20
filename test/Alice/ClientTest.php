<?php
namespace SearChiliTest\Alice;

use PHPUnit\Framework\TestCase;
use SearChili\Alice\Api\Entity;
use SearChili\Alice\Client as AliceClient;
use SearChili\Exception\Exception;
use SearChili\Handler\Curl;
use SearChili\Handler\Http;

class ClientTest extends TestCase
{
    /**
     * @var Client
     */
    private $client;

    protected function setUp()
    {
        $this->client = new AliceClient('8fde6f96-ede2-4601-9804-23502916f1e5');
    }

    /**
     * @test
     */
    public function constructShouldConfigureTheAttributes()
    {
        $this->assertAttributeSame('8fde6f96-ede2-4601-9804-23502916f1e5', 'apiKey', $this->client);
        $this->assertSame('https://api.searchi.li/alice/v1/', AliceClient::BASE_URI);
    }

    /**
     * @test
     */
    public function methodBuildRequestShouldInitializeTheCurlResource()
    {
        $resource = $this->client->buildRequest('entity', Http::GET);
        $this->assertEquals('object', gettype($resource));
        $this->assertInstanceOf(Curl::class, $resource);
    }

    /**
     * @test
     */
    public function queryTest()
    {
        $query = $this->client->query([]);
        $this->assertEquals('', $query);

        $query = $this->client->query(['query' => 'string']);
        $this->assertEquals("?query=string", $query);

        $query = $this->client->query(['query' => 'string', 'query2' => 'string2']);
        $this->assertEquals("?query=string&query2=string2", $query);
    }

    /**
     * @test
     */
    public function apiInstancesTest()
    {
        $entity = $this->client->entity;

        $this->assertInstanceOf(Entity::class, $entity);
    }

    /**
     * @test
     */
    public function apiThrowClientException()
    {
        $name = 'invalidapiitem';
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("The class could not be instantiated: $name");
        $this->client->$name;
    }
}
