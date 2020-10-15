<?php
declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Model\User;

class MatchPlayer {
    /**
     *
     * @var User
     */
    private $user;
    private $tokens;
    private $points;
    private $beers;
    
    public function __construct(User $user, $tokens, $points, $beers)
    {
        $this->user = $user;
        $this->tokens = $tokens;
        $this->points = $points;
        $this->beers = $beers;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function getTokens()
    {
        return $this->tokens;
    }
    
    public function getPoints()
    {
        return $this->points;
    }
    
    public function getBeers()
    {
        return $this->beers;
    }
    
}
