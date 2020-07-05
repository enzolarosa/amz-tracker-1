<?php

namespace App\Logging;

use App\Models\RequestLog;
use Exception;
use GuzzleHttp\MessageFormatter;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GuzzleLogger extends MessageFormatter
{
    protected string $provider;

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * @param string $provider
     *
     * @return GuzzleLogger
     */
    public function setProvider(string $provider): self
    {
        $this->provider = $provider;
        return $this;
    }

    /**
     * Returns a formatted message string.
     *
     * @param RequestInterface  $request  Request that was sent
     * @param ResponseInterface $response Response that was received
     * @param Exception         $error    Exception that was received
     *
     * @return string
     */
    public function format(RequestInterface $request, ResponseInterface $response = null, Exception $error = null)
    {
        RequestLog::log([
            'request_id' => Arr::first($request->getHeader('X-Request-ID')) ?? Str::uuid(),
            'provider'   => $this->getProvider(),
            'response'   => (string)json_encode([
                'body'     => !is_null($response) ? json_decode((string)$response->getBody()) : (!is_null($error) ? ['msg' => $error->getMessage(), 'trace' => $error->getTrace()] : []),
                'headers'  => $this->headers($response),
            ]),
            'request'    => (string)json_encode([
                'body'     => json_decode((string)$request->getBody()),
                'method'   => $request->getMethod(),
                'target'   => $request->getRequestTarget(),
                'headers'  => $this->headers($request),
            ]),
        ]);

        return parent::format($request, $response, $error);
    }

    /**
     * Get headers from message as string
     *
     * @param MessageInterface $message
     *
     * @return array
     */
    private function headers(MessageInterface $message): array
    {
        $result = [];
        foreach ($message->getHeaders() as $name => $values) {
            $result[$name] = implode(', ', $values);
        }

        return $result;
    }
}
