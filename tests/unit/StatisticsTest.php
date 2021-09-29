<?php

declare(strict_types = 1);

namespace Tests\unit;

use PHPUnit\Framework\TestCase;
use SocialPost\Hydrator\FictionalPostHydrator;
use Statistics\Builder\ParamsBuilder;
use Statistics\Dto\StatisticsTo;
use Statistics\Enum\StatsEnum;
use Statistics\Service\Factory\StatisticsServiceFactory;

/**
 * Class StatisticsTest
 *
 * @package Tests\unit
 */
class StatisticsTest extends TestCase
{
    private $mockPosts;

    protected function setUp(): void
    {
        $this->mockPosts = $this->fetchPostsMock();
    }

    /**
     * @test
     */
    public function averagePostLength(): void
    {
        $calculatedStat = $this->calculateIndividualStat($this->mockPosts,StatsEnum::AVERAGE_POST_LENGTH);

        $this->assertNotEmpty($calculatedStat->getChildren());
        $avgPostStat = $calculatedStat->getChildren()[0];

        $this->assertInstanceOf(StatisticsTo::class, $avgPostStat);
        $this->assertEquals(495.25, $avgPostStat->getValue());
    }

    /**
     * @test
     */
    public function averagePostNumberPerUser(): void
    {
        $calculatedStat = $this->calculateIndividualStat($this->mockPosts,StatsEnum::AVERAGE_POST_NUMBER_PER_USER);

        $this->assertNotEmpty($calculatedStat->getChildren());
        $avgPostStat = $calculatedStat->getChildren()[0];

        $this->assertInstanceOf(StatisticsTo::class, $avgPostStat);
        $this->assertEquals(1.0, $avgPostStat->getValue());
    }

    private function calculateIndividualStat(\Traversable $posts, string $filterByStatName): StatisticsTo
    {
        $statsService = StatisticsServiceFactory::create();

        $date = new \DateTime('2018-08-01T00:00:00+00:00');
        $params = ParamsBuilder::reportStatsParams($date);

        $filteredParams = [];
        foreach ($params as $param){
            if ($param->getStatName() === $filterByStatName){
                $filteredParams[] = $param;
            }
        }
        return $statsService->calculateStats($posts, $filteredParams);
    }

    private function fetchPostsMock(): \Traversable
    {
        $postsApiJsonResponse = file_get_contents(__DIR__ . '/../data/social-posts-response.json');
        $response = json_decode($postsApiJsonResponse, true);
        $postsData = $response['data']['posts'];

        $hydrator = new FictionalPostHydrator();
        $postsDtos = [];
        foreach ($postsData as $postData){
            $postsDtos[] = $hydrator->hydrate($postData);
        }
        $postIterator = new \ArrayObject($postsDtos);
        return $postIterator;
    }
}
