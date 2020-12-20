<?php

namespace SearChiliTest\Api;

use PHPUnit\Framework\TestCase;
use SearChili\Alice\Client;
use SearChili\Api\Entity;
use SearChili\Exception\RequestException;
use SearChili\Handler\Response;

class EntityTest extends TestCase
{
    /**
     * @var Entity
     */
    private $entity;

    protected function setUp()
    {
        $client = $this->createMock(Client::class);
        $client->method('get')
            ->willReturn(
                (new Response())
                    ->setStatusCode(200)
                    ->setContent([
                        [
                            'id' => 1,
                            'title' => 'test',
                            'excerpt' => 'test excerpt',
                            'link' => 'http://domain.com',
                            'image' => 'http://domain.com/img.png',
                            'categories' => ['cat1'],
                            'tags' => ['tag1'],
                            'published_at' => '2020-10-16T09:00:34.000000Z',
                        ],
                        [
                            'id' => 2,
                            'title' => 'test 2',
                            'excerpt' => 'test 2 excerpt',
                            'link' => 'http://domain2.com',
                            'image' => 'http://domain2.com/img.png',
                            'categories' => ['cat2'],
                            'tags' => ['tag2'],
                            'published_at' => '2020-10-16T09:00:34.000000Z',
                        ],
                    ])
            );
        $client->method('put')
            ->willReturn(
                (new Response())->setStatusCode(201),
                (new Response())->setStatusCode(400)->setContent(['message' => 'domain is invalid!'])
            );
        $client->method('delete')
            ->willReturn(
                (new Response())->setStatusCode(200),
                (new Response())->setStatusCode(400)->setContent(['message' => 'entity not found!'])
            );

        $this->entity = new Entity($client);
    }

    public function testFunctionExistence()
    {
        $this->assertTrue(method_exists($this->entity, 'search'));
        $this->assertTrue(method_exists($this->entity, 'sayt'));
        $this->assertTrue(method_exists($this->entity, 'store'));
        $this->assertTrue(method_exists($this->entity, 'delete'));
    }

    public function testSearchQuery()
    {
        $result = $this->entity->search('test');
        $this->assertNotEmpty($result);
        $this->assertCount(2, $result);
        $this->assertInstanceOf(\SearChili\Model\Entity::class, $result[0]);
        $this->assertInstanceOf(\SearChili\Model\Entity::class, $result[1]);
    }

    public function testSaytQuery()
    {
        $result = $this->entity->sayt('test');
        $this->assertNotEmpty($result);
        $this->assertCount(2, $result);
        $this->assertInstanceOf(\SearChili\Model\Entity::class, $result[0]);
        $this->assertInstanceOf(\SearChili\Model\Entity::class, $result[1]);
    }

    public function testStore()
    {
        $result = $this->entity->store(3, 'title 3', 'http://domain3.com', 'test');
        $this->assertTrue($result);

        $this->expectException(RequestException::class);
        $this->expectExceptionMessage("Failed to store this entity, response body:\n{\"message\":\"domain is invalid!\"}");
        $this->entity->store(4, 'title 4', 'domain3.com');
    }

    public function testDelete()
    {
        $result = $this->entity->delete(3);
        $this->assertTrue($result);

        $this->expectException(RequestException::class);
        $this->expectExceptionMessage("Failed to delete this entity, response body:\n{\"message\":\"entity not found!\"}");
        $this->entity->delete(3);
    }
}
