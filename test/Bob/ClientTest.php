<?php
namespace SearChiliTest\Bob;

use PHPUnit\Framework\TestCase;
use SearChili\Bob\Api\Entity;
use SearChili\Bob\Api\Site;
use SearChili\Bob\Client as BobClient;
use SearChili\Exception\Exception;
use SearChili\Handler\Curl;
use SearChili\Handler\Http;

class ClientTest extends TestCase
{
    /**
     * @var BobClient
     */
    private $client;

    protected function setUp()
    {
        $this->client = new BobClient('8fde6f96-ede2-4601-9804-23502916f1e5');
    }

    /**
     * @test
     */
    public function constructShouldConfigureTheAttributes()
    {
        $this->assertAttributeSame('8fde6f96-ede2-4601-9804-23502916f1e5', 'apiSecret', $this->client);
        $this->assertSame('https://api.searchi.li/bob/v1/', BobClient::BASE_URI);
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
