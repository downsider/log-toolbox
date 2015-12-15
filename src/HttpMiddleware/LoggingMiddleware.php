<?php
/**
 * @package log-toolbox
 * @copyright Copyright © 2015 Danny Smart
 */

namespace Downsider\LogToolbox\HttpMiddleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class LoggingMiddleware
{

    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(ResponseInterface $response)
    {
        $level = $response->getStatusCode() >= 400? LogLevel::ERROR: LogLevel::INFO;
        $message = "Request returned " . $response->getStatusCode() . " - " . $response->getReasonPhrase();
        $this->logger->{$level}($message);
        return $response;
    }

} 