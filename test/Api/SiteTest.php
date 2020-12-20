<?php

namespace SearChiliTest\Api;

use PHPUnit\Framework\TestCase;
use SearChili\Api\Site;
use SearChili\Alice\Client;
use SearChili\Exception\RequestException;
use SearChili\Handler\Response;

class SiteTest extends TestCase
{
    /**
     * @var Site
     */
    private $site;

    protected function setUp()
    {
        $client = $this->createMock(Client::class);
        $client->method('get')
            ->willReturn(
                (new Response())
                    ->setStatusCode(200)
                    ->setContent([
                        'name' => "test site",
                        'url' => 'test.com',
                        'timezone' => 'Europe/Berlin',
                        'apiKey' => '8fde6f96-ede2-4601-9804-23502916f1e5',
                        'usedSpace' => 10,
                        'entitiesCount' => 20,
                        'thisMonthRequestCount' => 2000,
                    ]),
                (new Response())
                    ->setStatusCode(401)
                    ->setContent([
                        'message' => "Unauthorized!",
                    ])
            );

        $this->site = new Site($client);
    }

    public function testFunctionExistence()
    {
        $this->assertTrue(method_exists($this->site, 'get'));
    }

    public function testGet()
    {
        $result = $this->site->get();
        $this->assertInstanceOf(\SearChili\Model\Site::class, $result);

        $this->expectException(RequestException::class);
        $this->expectExceptionMessage("Failed to retrieve, response body:\n{\"message\":\"Unauthorized!\"}");
        $this->site->get();
    }
}
