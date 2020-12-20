<?php
namespace SearChiliTest\Handler;

use PHPUnit\Framework\TestCase;
use SearChili\Handler\Response;

class ResponseTest extends TestCase
{
    /**
     * @var Response
     */
    private $response;
    
    protected function setUp()
    {
        $this->response = new Response();
        $this->response->setStatusCode(200);
        $this->response->setContent(["message" => "This is message!"]);
    }

    /**
     * @test
     */
    public function testStatusCodeIsNotNull()
    {
        $this->assertNotNull($this->response->getStatusCode());
    }

    /**
     * @test
     */
    public function testStatusCodeIsInteger()
    {
        $this->assertInternalType("int", $this->response->getStatusCode());
    }

    /**
     * @test
     */
    public function testStatusCodeIsEquals()
    {
        $this->assertEquals(200, $this->response->getStatusCode());
    }

    /**
     * @test
     */
    public function testContentIsNotNull()
    {
        $this->assertNotNull($this->response->getContent());
    }

    /**
     * @test
     */
    public function testContentIsArray()
    {
        $this->assertInternalType("array", $this->response->getContent());
    }

    /**
     * @test
     */
    public function testContentIsEquals()
    {
        $this->assertEquals(["message" => "This is message!"], $this->response->getContent());
    }
}
