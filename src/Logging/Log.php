<?php

namespace EdLugz\VAS\Logging;

use Exception;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Log
{
    /**
     * All the available debug levels.
     *
     * @var array
     */
    protected static array $levels = [
        'DEBUG'     => Logger::DEBUG,
        'INFO'      => Logger::INFO,
        'NOTICE'    => Logger::NOTICE,
        'WARNING'   => Logger::WARNING,
        'ERROR'     => Logger::ERROR,
        'CRITICAL'  => Logger::CRITICAL,
        'ALERT'     => Logger::ALERT,
        'EMERGENCY' => Logger::EMERGENCY,
    ];

    /**
     * Set up the logging requirements for the Guzzle package.
     *
     * @param $options
     *
     * @return int
     * @throws Exception
     */
    public static function enable($options): int
    {
        $level = self::getLogLevel();

        $handler = new Logger(
            'SMS',
            [
                new StreamHandler(storage_path('logs/sms.log'), $level),
            ]
        );

        $stack = HandlerStack::create();
        $stack->push(
            Middleware::log(
                $handler,
                new MessageFormatter('{method} {uri} HTTP/{version} {req_body} RESPONSE: {code} - {res_body}')
            )
        );

        $options['handler'] = $stack;

        return $options;
    }

    /**
     * Determine the log level specified in the configurations.
     *
     * @throws Exception
     *
     * @return mixed
     */
    protected static function getLogLevel(): mixed
    {
        $level = strtoupper(config('vas.logs.level'));

        if (array_key_exists($level, self::$levels)) {
            return self::$levels[$level];
        }

        throw new Exception('Debug level not recognized');
    }
}
