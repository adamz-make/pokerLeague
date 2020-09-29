<?php



namespace App\Domain\Model;


class Match implements \JsonSerializable {
    private $id = "";
    private $matchNr = "";
    private $dateOfMatch = "";
    
    public function jsonSerialize() 
    {
        
    }

}
