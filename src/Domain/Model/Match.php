<?php
declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Model\MatchPlayer;

class Match implements \JsonSerializable{
    private $id = "";
    private $matchNr = "";
    private $dateOfMatch = "";
    private $matchPlayers =[];
    private $matchType;
    public function __construct($id, $matchNr, $dateOfMatch, ?string $matchType)
    {
        $this->id = $id;s
        $this->matchNr = $matchNr;
        $this->dateOfMatch = $dateOfMatch;
        $this->matchType = $matchType;
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
        return ['id' => $this->id, 'matchNr' => $this->matchNr, 'dateOfMatch' => $this->dateOfMatch, 'matchPlayers' => $this->matchPlayers];
    }

}
