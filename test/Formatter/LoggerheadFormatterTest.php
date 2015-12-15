<?php
/**
 * @package log-toolbox
 * @copyright Copyright © 2015 Danny Smart
 */
namespace Downsider\LogToolbox\Test\Formatter;

use Downsider\LogToolbox\Formatter\LoggerheadFormatter;

class LoggerheadFormatterTest extends \PHPUnit_Framework_TestCase {

    public function testFormatting()
    {
        $channel = "test";
        $level = 300;
        $extraField = "custom";
        $datetime = new \DateTime();
        $timestamp = $datetime->getTimestamp();
        $record = [
            "channel" => $channel,
            "level" => $level,
            "datetime" => $datetime,
            "extra" => [
                "extraField" => $extraField
            ]
        ];

        $formatter = new LoggerheadFormatter();

        $result = json_decode($formatter->format($record), true);

        $this->assertInternalType("array", $result, "Check the result decoded from JSON correctly");

        $this->assertArrayHasKey("format", $result);
        $this->assertArrayNotHasKey("channel", $result, "Should have removed the original channel field");
        $this->assertEquals($channel, $result["format"], "Check the format was set from the channel");

        $this->assertArrayHasKey("entry", $result);
        $this->assertArrayNotHasKey("extra", $result, "Should have removed the original extra field");
        $this->assertInternalType("array", $result["entry"], "Check the entry field is an array");

        $entry = $result["entry"];
        $this->assertArrayHasKey("level", $entry);
        $this->assertArrayHasKey("datetime", $entry);
        $this->assertArrayHasKey("extraField", $entry);
        $this->assertArrayHasKey("timestamp", $entry);

        $this->assertEquals($level, $entry["level"], "Check 'level' was merged from the main record");
        $this->assertEquals($extraField, $entry["extraField"], "Check 'extraField' was merged from the extra array");
        $this->assertEquals($timestamp, $entry["timestamp"], "Check 'timestamp' was set from the datetime field");

    }

}
 