<?php
declare (strict_types=1);

namespace App\Infrastructure\Model;

use App\Domain\Model\Result;
use App\Domain\Model\ResultRepositoryInterface;
use App\Domain\Model\TotalResultForUser;
use App\Infrastructure\Model\UserRepository;
use App\Domain\Model\Match;

class ResultRepository implements ResultRepositoryInterface
{
    
    public function persist(Result $result)
    {
        $db = Db::getInstance();
        $resultAdded = $this->isResultForUserAdded($result);
        if ($resultAdded === null)
        {
            $this->add($result);
        }
        else
        {
            $this->update(new Result ($resultAdded->getId(),$result->getUserId(), $result->getMatchId(), $result->getPoints(),
                                        $result->getBeers(), $result->getTokens()));
        }
    }
    
    private function update(Result $result)
    {
        $db = Db::getInstance();
        $table = 'wyniki';
        $arrayData = ['LiczbaPiw' => $result->getBeers(), 'LiczbaPunktow' => $result->getPoints(), 'LiczbaZetonow' => $result->getTokens()];
        $arrayFilters = ['Id' => $result->getId()];
        $db->update($table, $arrayData, $arrayFilters);
    }
    
    private function add(Result $result)
    {
        $db = Db::getInstance();
        $table = 'wyniki';
        $array = ['idUsera' => $result->getUserId() , 'idMeczu' => $result->getMatchId(), 'liczbaPiw' => $result->getBeers(),
            'LiczbaPunktow' => $result->getPoints(), 'LiczbaZetonow' => $result->getTokens()];
        $db->insert($table, $array);        
    }
    
    private function isResultForUserAdded(Result $result): ?Result
    {
        $db = Db::getInstance();
        $sql = 'select Id, idUsera, IdMeczu, liczbaPunktow, LiczbaPiw, LiczbaZetonow from wyniki where idUsera =? and idMeczu=?';
        $array = [$result->getUserId(), $result->getMatchId()];
        $result = $db->select($sql, $array);
        foreach ($result as $row)
        {
            return new Result($row['Id'], $row['idUsera'], $row['IdMeczu'], $row['liczbaPunktow'], $row['LiczbaPiw'], $row['LiczbaZetonow']);
        }
        return null;
    }
    
    public function getResultsForRanking()
    {
        $db = Db::getInstance();
        $sql = 'select idUsera, sum(liczbaPiw) as liczbaPiw, sum(LiczbaPunktow) as liczbaPunktow, sum(LiczbaZetonow) as liczbaZetonow'
                . ' from wyniki group by IdUsera order by sum(LiczbaPunktow) desc';
        $array =[];
        $result = $db->select($sql, $array);
        foreach($result as $row)
        {
            $userRepo = new UserRepository();
            $resultArray[] = new TotalResultForUser( $userRepo->getById($row['idUsera']), $row['liczbaPunktow'], $row['liczbaPiw'], $row['liczbaZetonow']);  
        }
        return $resultArray;
    }
    
    public function getAllResults()
    {
        $db = Db::getInstance();
        $sql = 'select id,idUsera, idMeczu, liczbaPiw, LiczbaPunktow, LiczbaZetonow  from wyniki';
        $array =[];
        $result = $db->select($sql, $array);
        $resultArray = [];
        foreach($result as $row)
        {
            $resultArray[] = new Result($row['id'], $row['idUsera'], $row['idMeczu'], $row['LiczbaPunktow'], $row['liczbaPiw'], $row['LiczbaZetonow']);  
        }
        return $resultArray;
    }
    //w 1 zapytaniu to chcę pobierać, dlatego przekazuję tablicę meczy, czy mi jest to potrzebne??
    public function getResultsByMatchAndUser($matchesArray): array
    {
        foreach($matchesArray as $match)
        {
            $matchesNumbersString .= $match->getMatchNr() . ',';
        }
            
            
            
        
    }
            
    
}
