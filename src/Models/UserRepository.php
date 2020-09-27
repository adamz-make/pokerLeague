<?php


namespace App\Models;


class UserRepository {
   
    /**
     * 
     * @param type $login
     * @return \App\Models\User
     */
    public function getByLogin($login)
    {
        $db = Db::getInstance();
        $array = [$login];
        $Sql = "Select Id, Login, Haslo, Mail from users where Login =? limit 1";
        $result = $db->select($Sql, $array);
        foreach ($result as $row)
        {
            return new User($row['Id'], $row['Login'], $row['Haslo'], $row['Mail']);
        }
        
        return null;
    }
}
