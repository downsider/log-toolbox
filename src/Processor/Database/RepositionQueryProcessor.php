<?php
/**
 * @package log-toolbox
 * @copyright Copyright © 2015 Danny Smart
 */

namespace Downsider\LogToolbox\Processor\Database;

use Downsider\LogToolbox\Processor\PreProcessorTrait;
use Silktide\Reposition\QueryInterpreter\CompiledQuery;

class RepositionQueryProcessor 
{
    use PreProcessorTrait {
        __invoke as protected invoke;
    }

    protected $startTime;

    public function recordQueryStart(CompiledQuery $query)
    {
        $this->extra = [
            "collection" => $query->getCollection(),
            "query" => $query->getQuery(),
            "method" => $query->getMethod(),
            "arguments" => $query->getArguments(),
            "calls" => $query->getCalls(),
        ];

        $this->startTime = microtime(true);
    }

    public function __invoke(array $record)
    {
        $this->extra["duration"] = microtime(true) - $this->startTime;
        return $this->invoke($record);
    }

} 