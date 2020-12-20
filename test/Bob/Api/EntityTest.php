<?php

namespace SearChiliTest\Bob\Api;

use PHPUnit\Framework\TestCase;
use SearChili\Bob\Client;
use SearChili\Bob\Api\Entity;
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
        $client->method('put')
            ->willReturn(
                (new Response())->setStatusCode(201)->setContent([
                    'id' => 1,
                    'title' => 'test',
                    'excerpt' => 'test excerpt',
                    'link' => 'http://domain.com',
                    'image' => 'http://domain.com/img.png',
                    'categories' => ['cat1'],
                    'tags' => ['tag1'],
                    'published_at' => '2020-10-16T09:00:34.000000Z',
                ]),
                (new Response())->setStatusCode(400)->setContent(['message' => 'domain is invalid!'])
            );
        $client->method('delete')
            ->willReturn(
                (new Response())->setStatusCode(200),
                (new Response())->setStatusCode(400)->setContent(['message' => 'entity not found!'])
            );
        $client->method('get')
            ->will($this->returnCallback(function ($route, $param) {
                switch ($route) {
                    case "entity/3":
                        return (new Response())->setStatusCode(200)->setContent([
                            'id' => 1,
                            'title' => 'test',
                            'excerpt' => 'test excerpt',
                            'link' => 'http://domain.com',
                            'image' => 'http://domain.com/img.png',
                            'categories' => ['cat1'],
                            'tags' => ['tag1'],
                            'published_at' => '2020-10-16T09:00:34.000000Z',
                        ]);
                    case "entity/345":
                        return (new Response())->setStatusCode(404)->setContent([
                            'message' => 'entity not found!',
                        ]);
                    case "entity":
                        if (!empty($param['page']) && $param['page'] == 1) {
                            return (new Response())->setStatusCode(200)->setContent([1, 2, 3, 4]);
                        } elseif (!empty($param['page']) && $param['page'] == 3) {
                            return (new Response())->setStatusCode(200)->setContent([]);
                        }
                    default:
                        return null;
                }
            }));

        $this->entity = new Entity($client);
    }

    public function testFunctionExistence()
    {
        $this->assertTrue(method_exists($this->entity, 'store'));
        $this->assertTrue(method_exists($this->entity, 'delete'));
        $this->assertTrue(method_exists($this->entity, 'get'));
        $this->assertTrue(method_exists($this->entity, 'getAll'));
    }

    public function testStore()
    {
        $result = $this->entity->store(3, 'title 3', 'http://domain3.com', 'test');
        $this->assertInstanceOf(\SearChili\Model\Entity::class, $result);

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

    public function testGet()
    {
        $result = $this->entity->get(3);
        $this->assertInstanceOf(\SearChili\Model\Entity::class, $result);

        $this->expectException(RequestException::class);
        $this->expectExceptionMessage("Failed to get entity, response body:\n{\"message\":\"entity not found!\"}");
        $this->entity->get(345);
    }

    public function testGetAll()
    {
        $result = $this->entity->getAll();
        $this->assertTrue(in_array(3, $result));

        $result = $this->entity->getAll(3);
        $this->assertEmpty($result);
    }
}
