<?php

declare(strict_types = 1);

namespace Statistics\Calculator;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;

class AveragePostNumberPerUser extends AbstractCalculator
{
    protected const UNITS = 'posts';

    /**
     * @var array
     */
    private array $authorIds = [];

    /**
     * @var int
     */
    private int $numTotalPosts = 0;

    /**
     * @inheritDoc
     */
    protected function doAccumulate(SocialPostTo $postTo): void
    {
        //IMPORTANT NOTE: keeping track of post authors through an associative array
        //allows us to avoid iterating over a simple array to check if the element exists
        //thus reducing time complexity from linear O(n) to constant O(1)
        $this->authorIds[$postTo->getAuthorId()] = true;
        $this->numTotalPosts++;
    }

    /**
     * @inheritDoc
     */
    protected function doCalculate(): StatisticsTo
    {
        $numAuthors = count($this->authorIds);

        if ($numAuthors > 0){
            $avgPostsPerUser = $this->numTotalPosts / $numAuthors;
        } else $avgPostsPerUser = 0;

        $stat = new StatisticsTo();
        $stat->setValue(round($avgPostsPerUser, 2));
        return $stat;
    }
}
