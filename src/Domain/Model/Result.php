<?php
declare (strict_types=1);

namespace App\Domain\Model;

class Result implements \JsonSerializable{
    
    private $id;
    private $userId;
    private $matchId;
    private $points;
    private $beers;
    private $tokens;
    
    public function __construct($id, $userId, $matchId, $points, $beers, $tokens)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->matchId = $matchId;
        $this->points = $points;
        $this->beers = $beers;
        $this->tokens = $tokens;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getUserId()
    {
        return $this->userId;
    }
    
    public function getMatchId()
    {
        return $this->matchId;
    }
    
    public function getPoints()
    {
        return $this->points;
    }
            
    public function getBeers()
    {
        return $this->beers;
    }
    
    public function getTokens()
    {
        return $this->tokens;
    }

    public function jsonSerialize() 
    {
        return ['id' => $this->id, 'userId' => $this->userId, 'matchId' => $this->matchId, 'points' => $this->points,
                'beers' => $this->beers, 'tokens' => $this->tokens];
    }

}
