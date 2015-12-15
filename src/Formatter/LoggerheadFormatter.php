<?php
/**
 * @package log-toolbox
 * @copyright Copyright © 2015 Danny Smart
 */

namespace Downsider\LogToolbox\Formatter;

use Monolog\Formatter\JsonFormatter;

class LoggerheadFormatter extends JsonFormatter
{

    public function format(array $record)
    {
        // extract format and extra fields from the record
        $format = $record["channel"];
        unset($record["channel"]);
        $extra = $record["extra"];
        unset($record["extra"]);

        // merge the record and extra arrays
        $record = array_replace($record, $extra);

        // add a timestamp field if we don;t have one
        if (!isset($record["timestamp"]) && !empty($record["datetime"])) {
            /** @var \DateTime $dateTime */
            $dateTime = $record["datetime"];
            $record["timestamp"] = $dateTime->getTimestamp();
        }

        return parent::format(["format" => $format, "entry" => $record]);
    }

} 