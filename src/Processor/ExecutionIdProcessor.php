<?php
/**
 * @package log-toolbox
 * @copyright Copyright © 2015 Danny Smart
 */

namespace Downsider\LogToolbox\Processor;

class ExecutionIdProcessor 
{

    protected $execId;

    public function __construct($serverId = "")
    {
        // generate a unique value to represent this script execution thread
        $pid = getmypid();
        $this->execId = sha1("$serverId-$pid-" . microtime(true));
    }

    public function __invoke(array $record)
    {
        $record["extra"]["exec_id"] = $this->execId;
        return $record;
    }

} 