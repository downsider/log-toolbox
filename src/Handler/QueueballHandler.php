<?php
/**
 * @package log-toolbox
 * @copyright Copyright © 2015 Danny Smart
 *
 * @requires silktide/queueball
 */

namespace Downsider\LogToolbox\Handler;

use Monolog\Formatter\JsonFormatter;
use Silktide\QueueBall\Queue\AbstractQueue;
use Monolog\Handler\AbstractProcessingHandler;

class QueueBallHandler extends AbstractProcessingHandler
{

    protected $queueId;

    protected $queue;

    public function __construct(AbstractQueue $queue, JsonFormatter $formatter, $queueId = null)
    {
        $this->queue = $queue;
        $this->formatter = $formatter;
        $this->queueId = $queueId;
    }

    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param  array $record
     *
     * @return void
     */
    protected function write(array $record)
    {
        $this->queue->sendMessage($record["formatted"], $this->queueId);
    }

}