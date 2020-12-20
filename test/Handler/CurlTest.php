<?php
namespace SearChiliTest\Handler;

use PHPUnit\Framework\TestCase;
use SearChili\Handler\Curl;

class CurlTest extends TestCase
{
    /**
     * @var Curl
     */
    private $curl;
    
    protected function setUp()
    {
        $this->curl = new Curl();
    }

    /**
     * @test
     * @expectedException \SearChili\Exception\Exception
     */
    public function serializeMethodShouldReturnExceptionCaseNotArray()
    {
        $this->curl->serialize('');
    }

    /**
     * @test
     */
    public function serializeShouldReturnStringJSON()
    {
        $return = $this->curl->serialize(['id' => '1']);
        $this->assertEquals('{"id":"1"}', $return);
    }
}
