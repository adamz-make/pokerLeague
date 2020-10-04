<?php
declare (strict_types=1);

namespace App\Infrastructure\Model;

use App\Domain\Model\Result;
use App\Domain\Model\ResultRepositoryInterface;

class ResultRepository implements ResultRepositoryInterface{
    
    public function add(Result $result)
    {
        $db = Db::getInstance();
        $table = 'wyniki';
        $array = ['idUsera' => $result->getUserId() , 'idMeczu' => $result->getMatchId(), 'liczbaPiw' => $result->getBeers(),
            'LiczbaPunktow' => $result->getPoints(), 'LiczbaZetonow' => $result->getTokens()];
        $db->insert($table, $array);
        
    }
}
