<?php

namespace App\Http\Middleware;

use App\Models\RequestLog;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

/**
 * Class TraceRequestMiddleware
 *
 * @package App\Http\Middleware
 */
class TraceRequestMiddleware
{
    const X_REQUEST_ID = 'X-Request-ID';

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @param string $provider
     * @return mixed
     */
    public function handle($request, Closure $next, string $provider = 'api-in')
    {
        $startTime = microtime(true);
        if (!$request->hasHeader(self::X_REQUEST_ID)) {
            $request->headers->set(self::X_REQUEST_ID, (string)Str::uuid());
        }
        $reqId = $request->header(self::X_REQUEST_ID);

        $log = RequestLog::query()->where('request_id', $reqId)->first();

        session(["request_{$reqId}_start" => $startTime]);
        session(["request_{$reqId}_provider" => $provider]);

        if (!$log) {
            return $next($request);
        }

        return response()->json(
            ['message' => __(':request_name :request_id has been already used', ['request_name' => self::X_REQUEST_ID, 'request_id' => $reqId])],
            Response::HTTP_BAD_REQUEST,
            [self::X_REQUEST_ID => $reqId]
        );
    }

    /**
     * @param Request $request
     * @param         $response
     */
    public function terminate($request, $response)
    {
        $reqId = $request->header(self::X_REQUEST_ID);
        $startTime = session("request_{$reqId}_start");
        $endTime = microtime(true);
        $provider = session("request_{$reqId}_provider");

        $dataToLog = [
            'time' => gmdate("F j, Y, g:i a"),
            'duration' => number_format($endTime - $startTime, 3),
            'ip_address' => $request->ip(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'input' => (string)$request->getContent(),
        ];

        if ($response instanceof RedirectResponse || $response instanceof Closure) {
            $response = get_class($response);
        }

        RequestLog::log([
            'request_id' => $request->header(self::X_REQUEST_ID),
            'provider' => $provider,
            'request' => $dataToLog,
            'response' => $response,
        ]);
    }
}
