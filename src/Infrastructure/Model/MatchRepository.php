<?php
declare (strict_types=1);

namespace App\Infrastructure\Model;

use App\Domain\Model\Match;
use App\Domain\Model\MatchRepositoryInterface;
use App\Domain\Model\MatchPlayer;

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
            $match = new Match($row['Id'], $row['nrMeczu'], $row['dataDodania']);
            $this->applyMatchPlayers($match);
            return $match;
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
            $match = new Match($row['Id'], $row['nrMeczu'], $row['dataDodania']);
            $this->applyMatchPlayers($match);
            $matchArray[] = $match;
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
            $match = new Match($row['Id'], $row['nrMeczu'], $row['dataDodania']);
            $this->applyMatchPlayers($match);
            return $match;
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
    
    public function getMatchesByDate ($dateFrom, $dateTo): ?array
    {
        $db = Db::getInstance();
        $sql = 'select Id, nrMeczu, dataDodania from mecze where 1=1 ';
        $array = [];
        $matchArray = [];
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
            $match = new Match($row['Id'], $row['nrMeczu'], $row['dataDodania']);
            $this->applyMatchPlayers($match);
            $matchArray[] = $match;
        }
        return $matchArray;      
    }
    
    private function applyMatchPlayers(Match $match)
    {
        // Na podstawie $match wyciągam dane z Result Repository i USer Repository
        // w tej metodzie robiłbym $match->addMatchPlayer($matchPlayer);
        $resultRepo = new ResultRepository();
        $userRepo = new UserRepository();
        $results = $resultRepo->getAllResults();
        foreach ($results as $result)
        {
            if ($result->getMatchId() === $match->getMatchId())
            {
                $user = $userRepo->getById($result->getUserId());
                $beers = $result->getBeers();
                $tokens = $result->getTokens();
                $points = $result->getPoints();
                $matchPlayer = new MatchPlayer($user, $tokens, $points, $beers);
                $match->addMatchPlayer($matchPlayer);         
            }
        }
    }
}
