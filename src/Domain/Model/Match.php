<?php
declare (strict_types=1);


namespace App\Domain\Model;


class Match implements \JsonSerializable{
    private $id = "";
    private $matchNr = "";
    private $dateOfMatch = "";
    
    public function __construct($id, $matchNr, $dateOfMatch)
    {
        $this->id = $id;
        $this->matchNr = $matchNr;
        $this->dateOfMatch = $dateOfMatch;
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
