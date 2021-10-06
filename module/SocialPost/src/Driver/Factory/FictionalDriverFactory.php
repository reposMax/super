<?php

namespace SocialPost\Driver\Factory;

use SocialPost\Cache\Factory\CacheFactory;
use SocialPost\Client\Factory\FictionalClientFactory;
use SocialPost\Driver\AuthService;
use SocialPost\Driver\FictionalDriver;
use SocialPost\Driver\SocialDriverInterface;

/**
 * Class FictionalSocialDriverFactory
 *
 * @package SocialPost\Driver\Factory
 */
class FictionalDriverFactory
{
    /**
     * @return FictionalDriver
     */
    public static function create(): SocialDriverInterface
    {
        try {
            $cache = CacheFactory::create();
        } catch (\Throwable $throwable) {
            //Cache not ready :(
            $cache = null;
        }

        $client = FictionalClientFactory::create();
        $authService = new AuthService();
        $driver = new FictionalDriver($client, $authService);
        $driver->setCache($cache);

        return $driver;
    }
}
