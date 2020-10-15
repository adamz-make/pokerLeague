<?php
declare (strict_types=1);

namespace App\Infrastructure\Model;

use App\Domain\Model\Match;
use App\Domain\Model\MatchRepositoryInterface;

class MatchRepository implements MatchRepositoryInterface {

    public function getLastMatch(): ?Match
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
    
    public function getAllMatches()
    {
        $db = Db::getInstance();
        $sql = 'select Id, nrMeczu, dataDodania from mecze'; 
        $array = [];
        $result = $db->select($sql, $array);
        foreach ($result as $row)
        {
            $matchArray[]= new Match($row['Id'], $row['nrMeczu'], $row['dataDodania']);
        }
        return $matchArray;      
    }
    
    public function getMatchByNr($nr): ?Match
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
    
    public function getMatchesByDate ($dateFrom, $dateTo): array
    {
        $db = Db::getInstance();
        $sql = 'select Id, nrMeczu, dataDodania from mecze where 1=1 ';
        $array = [];
        if($dateFrom !== null)
        {
            $sql .= 'and dataDodania >=? ';
            $array[] = $dateFrom;
        }
        if ($dateTo !== null)
        {
            $sql .= 'and dataDodania <?';
            $array[] = $dateTo;  
        }
        $result = $db->select($sql, $array);
        foreach ($result as $row)
        {
            $matchArray[]= new Match($row['Id'], $row['nrMeczu'], $row['dataDodania']);
        }
        return $matchArray;      
    }
}
