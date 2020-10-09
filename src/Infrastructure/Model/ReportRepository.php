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
    
}
