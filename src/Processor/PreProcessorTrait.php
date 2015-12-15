<?php
/**
 * @package log-toolbox
 * @copyright Copyright © 2015 Danny Smart
 */

namespace Downsider\LogToolbox\Processor;

trait PreProcessorTrait
{

    protected $extra = [];

    public function __invoke(array $record) {
        $record["extra"] = array_replace($record["extra"], $this->extra);
        $this->extra = [];
        return $record;
    }

} 