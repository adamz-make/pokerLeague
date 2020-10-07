<?php
declare (strict_types=1);

namespace App\Infrastructure\Model;

use App\Domain\Model\Result;
use App\Domain\Model\ResultRepositoryInterface;
use App\Domain\Model\TotalResultForUser;
use App\Infrastructure\Model\UserRepository;

class ResultRepository implements ResultRepositoryInterface{
    
    public function add(Result $result)
    {
        $db = Db::getInstance();
        $table = 'wyniki';
        $array = ['idUsera' => $result->getUserId() , 'idMeczu' => $result->getMatchId(), 'liczbaPiw' => $result->getBeers(),
            'LiczbaPunktow' => $result->getPoints(), 'LiczbaZetonow' => $result->getTokens()];
        $db->insert($table, $array);        
    }
    
    public function isResultForUserAdded(Result $result): ?Result
    {
        $db = Db::getInstance();
        $sql = 'select Id, idUsera, IdMeczu, liczbaPunktow, LiczbaPiw, LiczbaZetonow from wyniki where idUsera =? and idMeczu=?';
        $array = [$result->getUserId(), $result->getMatchId()];
        $result = $db->select($sql, $array);
        if (!empty($result))
        {
            var_dump($result);
            exit;
            return new Result($result['Id'], $result['idUsera'], $result['IdMeczu'], $result['liczbaPunktow'], $result['LiczbaPiw'], $result['LiczbaZetonow']);
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
    
}
