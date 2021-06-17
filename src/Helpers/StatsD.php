<?php

namespace BluefynInternational\Sidekick\Helpers;

use Domnikl\Statsd\Client;
use Domnikl\Statsd\Connection\UdpSocket;

class StatsD
{
    /**
     * @var Client
     */
    protected static $instance;

    public static function getInstance() : Client
    {
        if (! self::$instance) {
            $connection = new UdpSocket('localhost', 8125);
            self::$instance = new Client($connection);
        }

        return self::$instance;
    }

    public static function increment(String $key, array $tags = [])
    {
        self::getInstance()->increment($key, 1.0, $tags);
    }

    public static function decrement(String $key, array $tags = [])
    {
        self::getInstance()->decrement($key, 1.0, $tags);
    }

    public static function count(String $key, int $count, array $tags = [])
    {
        self::getInstance()->count($key, $count, 1.0, $tags);
    }

    public static function startTiming(String $key)
    {
        self::getInstance()->startTiming($key);
    }

    public static function endTiming(String $key, array $tags = [])
    {
        self::getInstance()->endTiming($key, 1.0, $tags);
    }
}
