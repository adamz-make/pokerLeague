<?php

namespace App\Infrastructure\Model;

use App\Domain\Model\ReportRepositoryInterface;

class ReportRepository implements ReportRepositoryInterface{
    
    public function allUsers()
    {
       $db = Db::getInstance();
       $sql = 'select Id, Login, Haslo, Mail from users';
       $array = [];
       $result = $db ->select($sql, $array);
       $header[] = ['id', 'login', 'Haslo', 'Mail'];//nagłowki
       $array = array_merge($header,$result);
       return $array;
    }
    
     public function resultsForUsers()
    {
       $db = Db::getInstance();
       $sql = 'select u.Login, sum(w.liczbaPunktow), sum(w.LiczbaPiw),sum(w.LiczbaZetonow) from wyniki w'
               . ' join users u on w.idUsera = u.id'
               . ' group by u.Login';
       $array = [];
       $result = $db ->select($sql, $array);
       $header[] = ['Login', 'liczbaPunktow', 'liczbaPiw', 'liczbaZetonow'];//nagłowki
       $array = array_merge($header,$result);
       return $array;
    }
    
}
