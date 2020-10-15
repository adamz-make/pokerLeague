<?php
declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Model\MatchPlayer;

class ObjectSummaryReport
{
    private $nrMatch;
    private $userName;
    private $beers;
    private $tokens;
    private $points;
    private $cumulatedBeers;
    private $cumulatedTokens;
    private $cumulatedPoints;
    
    public function __construct($nrMatch, $userName, $beers, $tokens, $points, $cumulatedBeers, $cumulatedTokens, $cumulatedPoints )
    {
        $this->nrMatch = $nrMatch;
        $this->userName = $userName;
        $this->beers = $beers;
        $this->tokens = $tokens;
        $this->points = $points;
        $this->cumulatedBeers = $cumulatedBeers;
        $this->cumulatedTokens = $cumulatedTokens;
        $this->cumulatedPoints =$cumulatedPoints;
    }
    
    public function getUserName()
    {
        return $this->userName;
    }
    
    public function getBeers()
    {
        return $this->beers;
    }
    
    public function getTokens()
    {
        return $this->tokens;
    }
    
    public function getPoints()
    {
        return $this->points;
    }
}