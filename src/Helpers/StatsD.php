<?php

namespace AlwaysOpen\Sidekick\Helpers;

use Domnikl\Statsd\Client;
use Domnikl\Statsd\Connection\UdpSocket;

class StatsD
{
    /**
     * @var ?Client
     */
    protected static ?Client $instance = null;

    public static function getInstance() : Client
    {
        if (! self::$instance) {
            $connection = new UdpSocket('localhost', 8125);
            self::$instance = new Client($connection);
        }

        return self::$instance;
    }

    public static function increment(String $key, array $tags = []) : void
    {
        self::getInstance()->increment($key, 1.0, $tags);
    }

    public static function decrement(String $key, array $tags = []) : void
    {
        self::getInstance()->decrement($key, 1.0, $tags);
    }

    public static function count(String $key, int $count, array $tags = []) : void
    {
        self::getInstance()->count($key, $count, 1.0, $tags);
    }

    public static function startTiming(String $key) : void
    {
        self::getInstance()->startTiming($key);
    }

    public static function endTiming(String $key, array $tags = []) : void
    {
        self::getInstance()->endTiming($key, 1.0, $tags);
    }
}
