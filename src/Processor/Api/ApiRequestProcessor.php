<?php
/**
 * @package log-toolbox
 * @copyright Copyright © 2015 Danny Smart
 *
 * @requires guzzlehttp/guzzle
 */

namespace Downsider\LogToolbox\Processor\Api;

use Downsider\LogToolbox\Processor\PreProcessorTrait;
use GuzzleHttp\TransferStats;

class ApiRequestProcessor
{
    use PreProcessorTrait;

    public function recordTransferStats(TransferStats $stats)
    {
        $request = $stats->getRequest();
        $response = $stats->hasResponse()? $stats->getResponse(): null;

        $this->extra["url"] = $request->getUri()->__toString();
        $this->extra["method"] = $request->getMethod();
        $this->extra["requestHeaders"] = $this->processHeaders($request->getHeaders());
        $this->extra["requestBody"] = $request->getBody()->getContents();
        $this->extra["statusCode"] = $response !== null? $response->getStatusCode(): null;
        $this->extra["responseHeaders"] = $response !== null? $this->processHeaders($request->getHeaders()): "";
        $this->extra["responseBody"] = $response !== null? $response->getBody()->getContents(): "";
        $this->extra["duration"] = $stats->getTransferTime();
    }

    protected function processHeaders(array $headers)
    {
        $flatHeaders = [];
        foreach ($headers as $header => $values) {
            foreach ($values as $value) {
                $flatHeaders[] = "$header: $value";
            }
        }
        return implode("\n", $flatHeaders);
    }

} 