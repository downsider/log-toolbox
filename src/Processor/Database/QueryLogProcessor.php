<?php
/**
 * @package log-toolbox
 * @copyright Copyright © 2015 Danny Smart
 */

namespace Downsider\LogToolbox\Processor\Database;

use Downsider\LogToolbox\Processor\PreProcessorTrait;
use Silktide\Reposition\QueryInterpreter\CompiledQuery;
use Silktide\Reposition\Storage\Logging\QueryLogProcessorInterface;

class QueryLogProcessor implements QueryLogProcessorInterface
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

    public function recordQueryEnd()
    {
        $this->extra["duration"] = microtime(true) - $this->startTime;
        $this->startTime = null;
    }

    public function __invoke(array $record)
    {
        if (!empty($this->startTime)) {
            $this->recordQueryEnd();
        }
        return $this->invoke($record);
    }

} 