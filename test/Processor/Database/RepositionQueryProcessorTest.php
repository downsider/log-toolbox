<?php
/**
 * @package log-toolbox
 * @copyright Copyright © 2015 Danny Smart
 */
namespace Downsider\LogToolbox\Test\Processor\Database;

use Downsider\LogToolbox\Processor\Database\RepositionQueryProcessor;

class RepositionQueryProcessorTest extends \PHPUnit_Framework_TestCase {

    public function testCaptureDuration()
    {
        $oneMil = 1000000;
        $max = 10000 / $oneMil;
        $duration = 3500;
        
        $query  = \Mockery::mock("Silktide\\Reposition\\QueryInterpreter\\CompiledQuery")->shouldIgnoreMissing("");
        
        $processor = new RepositionQueryProcessor();
        
        $record = ["extra" => []];
        $processor->recordQueryStart($query);
        usleep($duration);
        $result = $processor($record);

        $duration /= $oneMil;
        
        $this->assertArrayHasKey("extra", $result);
        $this->assertArrayHasKey("duration", $result["extra"]);
        $this->assertGreaterThanOrEqual($duration, $result["extra"]["duration"]);
        $this->assertLessThan($max, $result["extra"]["duration"]);
    }

}
 