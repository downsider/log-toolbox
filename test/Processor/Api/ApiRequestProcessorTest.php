<?php
/**
 * @package log-toolbox
 * @copyright Copyright © 2015 Danny Smart
 */
namespace Downsider\LogToolbox\Test\Processor\Api;

use Downsider\LogToolbox\Processor\Api\ApiRequestProcessor;
use GuzzleHttp\TransferStats;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class ApiRequestProcessorTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Mockery\Mock|RequestInterface
     */
    protected $request;

    /**
     * @var \Mockery\Mock|ResponseInterface
     */
    protected $response;

    public function setUp()
    {
        $this->request = \Mockery::mock("Psr\\Http\\Message\\RequestInterface")->shouldIgnoreMissing();

        $this->response = \Mockery::mock("Psr\\Http\\Message\\ResponseInterface")->shouldIgnoreMissing();
    }

    protected function createTransferStats($response, $transferTime)
    {
        return new TransferStats(
            $this->request,
            $response,
            $transferTime
        );
    }

    public function testNoResponse()
    {
        $url = "www.blah.com";
        $method = "get";
        $requestContent = "I request this";
        $duration = 1.234;

        $this->request->shouldReceive("getUri->__toString")->andReturn($url);
        $this->request->shouldReceive("getBody->getContents")->andReturn($requestContent);
        $this->request->shouldReceive("getMethod")->andReturn($method);
        $this->request->shouldReceive("getHeaders")->andReturn([]);
        $transferStats = $this->createTransferStats(null, $duration);
        
        $processor = new ApiRequestProcessor();
        
        $processor->recordTransferStats($transferStats);
        
        $record = ["extra" => []];
        $record = $processor($record);
        
        $this->assertArrayHasKey("extra", $record);
        $extra = $record["extra"];
        
        $this->assertArrayHasKey("url", $extra);
        $this->assertArrayHasKey("method", $extra);
        $this->assertArrayHasKey("requestHeaders", $extra);
        $this->assertArrayHasKey("requestBody", $extra);
        $this->assertArrayHasKey("responseHeaders", $extra);
        $this->assertArrayHasKey("responseBody", $extra);
        $this->assertArrayHasKey("statusCode", $extra);
        $this->assertArrayHasKey("duration", $extra);

        $this->assertEquals($url, $extra["url"]);
        $this->assertEquals($method, $extra["method"]);
        $this->assertEquals("", $extra["requestHeaders"]);
        $this->assertEquals($requestContent, $extra["requestBody"]);
        $this->assertEquals("", $extra["responseHeaders"]);
        $this->assertEquals("", $extra["responseBody"]);
        $this->assertEquals(null, $extra["statusCode"]);
        $this->assertEquals($duration, $extra["duration"]);
    }

    public function testWithResponse()
    {
        $requestContent = "I request this";
        $responseContent = "I respond with this";
        $statusCode = 555;

        $this->request->shouldReceive("getUri->__toString")->andReturn("");
        $this->request->shouldReceive("getBody->getContents")->andReturn($requestContent);
        $this->request->shouldReceive("getHeaders")->andReturn([]);

        $this->response->shouldReceive("getBody->getContents")->andReturn($responseContent);
        $this->response->shouldReceive("getStatusCode")->andReturn($statusCode);
        $this->response->shouldReceive("getHeaders")->andReturn([]);
        $transferStats = $this->createTransferStats($this->response, null);

        $processor = new ApiRequestProcessor();

        $processor->recordTransferStats($transferStats);

        $record = ["extra" => []];
        $record = $processor($record);

        $this->assertArrayHasKey("extra", $record);
        $extra = $record["extra"];

        $this->assertArrayHasKey("requestBody", $extra);
        $this->assertArrayHasKey("responseHeaders", $extra);
        $this->assertArrayHasKey("responseBody", $extra);
        $this->assertArrayHasKey("statusCode", $extra);

        $this->assertEquals($requestContent, $extra["requestBody"]);
        $this->assertEquals("", $extra["responseHeaders"]);
        $this->assertEquals($responseContent, $extra["responseBody"]);
        $this->assertEquals($statusCode, $extra["statusCode"]);
    }

    public function testHeaderProcessing()
    {

        $headers = [
            "header1" => [],
            "header2" => ["value"],
            "header3" => ["value1", "value2"]
        ];

        $this->request->shouldReceive("getUri->__toString")->andReturn("");
        $this->request->shouldReceive("getBody->getContents")->andReturn("");
        $this->request->shouldReceive("getHeaders")->andReturn($headers);

        $transferStats = $this->createTransferStats(null, null);

        $processor = new ApiRequestProcessor();
        $processor->recordTransferStats($transferStats);

        $record = ["extra" => []];
        $record = $processor($record);

        $this->assertArrayHasKey("extra", $record);
        $extra = $record["extra"];
        $this->assertArrayHasKey("requestHeaders", $extra);

        $expected = "header2: value\nheader3: value1\nheader3: value2";
        $this->assertEquals($expected, $extra["requestHeaders"]);

    }

}
 