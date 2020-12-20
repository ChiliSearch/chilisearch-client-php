<?php
namespace SearChiliTest;

use PHPUnit\Framework\TestCase;
use SearChili\Alice\Client as AliceClient;
use SearChili\Api\Entity;
use SearChili\Api\Site;
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
        $this->assertAttributeSame('ba8ea031-7942-466d-8dd7-39285af69466', 'apiSecret', $this->client);
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
        $site = $this->client->site;
        $entity = $this->client->entity;

        $this->assertInstanceOf(Site::class, $site);
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
