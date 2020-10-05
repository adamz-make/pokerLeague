<?php
declare (strict_types=1);

namespace App\Infrastructure\Model;

use App\Domain\Model\Match;
use App\Domain\Model\MatchRepositoryInterface;

class MatchRepository implements MatchRepositoryInterface {

    public function getLastMatch()
    {
        $db = Db::getInstance();
        $sql = 'select Id, nrMeczu, dataDodania from mecze order by nrMeczu desc limit 1'; 
        $array = [];
        $result = $db->select($sql, $array);
        if (!empty ($result))
        {
            $row = reset($result);
            return new Match($row['Id'], $row['nrMeczu'], $row['dataDodania']);
        }
        return null;        
    }
    
    public function getMatchByNr($nr)
    {
        $db = Db::getInstance();
        $sql = 'select Id, nrMeczu, dataDodania from mecze where nrMeczu =?'; 
        $array = [$nr];
        $result = $db->select($sql, $array);
        if (!empty ($result))
        {
            $row = reset($result);
            return new Match($row['Id'], $row['nrMeczu'], $row['dataDodania']);
        }
        return null;   
    }
    
    public function add(Match $match)
    {
        $db = Db::getInstance();
        $table = 'mecze';
        $array = ['nrMeczu' => $match->getMatchNr(), 'dataDodania' => $match->getDateOfMatch()];
        $db->insert($table, $array);        
    }
}
