<?php
declare(strict_types=1);

namespace App\Application\Services\Payload;

use App\Application\Payload\AbstractRulesToMatch;
use App\Application\Payload\RulesToBeerMatch;

 class MatchPlayerParameters
{
    private $rulesToMatch;
    private $tokens = null;
    private $beers = null;
    private $points = null;

    public function __construct(AbstractRulesToMatch $rulesToMatch, string $tokens)
    {
        $this->rulesToMatch = $rulesToMatch;
        $this->tokens = $tokens;
    }

    public function getMatchPlayerParameters($pointsOrBeers) : array
    {
        if ($this->rulesToMatch instanceof RulesToBeerMatch) {
            $this->beers = $pointsOrBeers;
        } else {
            $this->points = $pointsOrBeers;
        }
        $outArray =[];
        $outArray['tokens'] = $this->tokens;
        $outArray['beers'] = $this->beers;
        $outArray['points'] = $this->points;
        return $outArray;


    }
}