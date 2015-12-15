<?php
/**
 * @package log-toolbox
 * @copyright Copyright © 2015 Danny Smart
 */

namespace Downsider\LogToolbox\Processor\Database;

use Downsider\LogToolbox\Processor\PreProcessorTrait;

class PdoErrorProcessor
{

    use PreProcessorTrait;

    public function recordSqlError($query, array $errorInfo)
    {
        $this->extra["query"] = $query;
        $this->extra["ansiErrorCode"] = $errorInfo[0];
        $this->extra["errorCode"] = $errorInfo[1];
    }

} 