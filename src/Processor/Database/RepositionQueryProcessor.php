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
    use PreProcessorTrait;

    public function recordQuery(CompiledQuery $query)
    {
        $this->extra = [
            "collection" => $query->getCollection(),
            "query" => $query->getQuery(),
            "method" => $query->getMethod(),
            "arguments" => $query->getArguments(),
            "calls" => $query->getCalls()
        ];
    }

} 