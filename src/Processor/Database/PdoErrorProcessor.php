<?php
/**
 * @package log-toolbox
 * @copyright Copyright © 2015 Danny Smart
 */

namespace Downsider\LogToolbox\Processor\Database;

use Downsider\LogToolbox\Processor\PreProcessorTrait;
use Silktide\Reposition\Storage\Logging\ErrorLogProcessorInterface;

class PdoErrorProcessor implements ErrorLogProcessorInterface
{

    use PreProcessorTrait;

    public function recordError($query, array $errorInfo)
    {
        $this->extra["query"] = $query;
        $this->extra["ansiErrorCode"] = $errorInfo[0];
        $this->extra["errorCode"] = $errorInfo[1];
    }

} 