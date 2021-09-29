<?php

declare(strict_types = 1);

namespace Tests\integration;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use SocialPost\Dto\FetchParamsTo;
use SocialPost\Dto\SocialPostTo;
use SocialPost\Service\Factory\SocialPostServiceFactory;

$dotEnv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotEnv->load();

/**
 * Class ApiIntegrationTest
 *
 * @package Tests\unit
 */
class ApiIntegrationTest extends TestCase
{
    /**
     * @test
     */
    public function fetchSocialPosts(): void
    {
        $socialService = SocialPostServiceFactory::create();
        $fetchParams = new FetchParamsTo(1,1);

        $postIterator = $socialService->fetchPosts($fetchParams);
        $this->assertIsIterable($postIterator);

        $postArray = iterator_to_array($postIterator);
        $this->assertContainsOnlyInstancesOf(SocialPostTo::class, $postArray);
    }

    /**
     * @test
     */
    public function socialPostHasIdAndAuthor(): void
    {
        $socialService = SocialPostServiceFactory::create();
        $fetchParams = new FetchParamsTo(1,1);
        $postIterator = $socialService->fetchPosts($fetchParams);
        $this->assertIsIterable($postIterator);

        $post = $postIterator->current();
        $this->assertInstanceOf(SocialPostTo::class, $post);
        $this->assertNotEmpty($post->getId());
        $this->assertNotEmpty($post->getAuthorId());
    }
}
