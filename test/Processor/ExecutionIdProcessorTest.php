<?php
/**
 * @package log-toolbox
 * @copyright Copyright © 2015 Danny Smart
 */
namespace Downsider\LogToolbox\Test\Processor;

use Downsider\LogToolbox\Processor\ExecutionIdProcessor;

class ExecutionIdProcessorTest extends \PHPUnit_Framework_TestCase {

    public function testUsesSameExecutionId()
    {
        $execProcessor = new ExecutionIdProcessor();

        $result1 = $execProcessor(["extra" => []]);
        $result2 = $execProcessor(["extra" => []]);

        $this->assertArrayHasKey("extra", $result1);
        $this->assertArrayHasKey("extra", $result2);
        $this->assertArrayHasKey("exec_id", $result1["extra"]);
        $this->assertArrayHasKey("exec_id", $result2["extra"]);
        $this->assertNotEmpty($result1["extra"]["exec_id"]);
        $this->assertEquals($result1["extra"]["exec_id"], $result2["extra"]["exec_id"]);
    }

}
 