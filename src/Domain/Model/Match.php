<?php
declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Model\MatchPlayer;

class Match implements \JsonSerializable{
    private $id = "";
    private $matchNr = "";
    private $dateOfMatch = "";
    private $matchPlayers =[];
    
    public function __construct($id, $matchNr, $dateOfMatch)
    {
        $this->id = $id;
        $this->matchNr = $matchNr;
        $this->dateOfMatch = $dateOfMatch;
    }
    
    public function getMatchPlayers()
    {
        return $this->matchPlayers;
    }
    
    public function setMatchPlayers($matchPlayers)
    {
        $this->matchPlayers[] = $matchPlayers;
    }
    /**
     * 
     * @param MatchPlayer $matchPlayer
     */
    public function addMatchPlayer ($matchPlayer)
    {
        $this->matchPlayers[] = $matchPlayer;
    }
            
    public function getMatchNr()
    {
        return $this->matchNr;    
    }
    
    public function getMatchId()
    {
        return $this->id;
    }
    
    public function getDateOfMatch()
    {
        return $this->dateOfMatch;
    }

    public function jsonSerialize() 
    {
        return ['id' => $this->id, 'matchNr' => $this->matchNr, 'dateOfMatch' => $this->dateOfMatch];
    }

}
