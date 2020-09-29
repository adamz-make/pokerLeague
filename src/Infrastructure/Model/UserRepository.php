<?php

namespace App\Infrastructure\Model;
use App\Domain\Model\UserRepositoryInterface;
use App\Domain\Model\User;
class UserRepository implements UserRepositoryInterface{
   
   /**
    * 
    * @param type $attribute
    * @param type $value
    * @return \App\Models\User
    */
     public function getBy($attribute, $value): User
    {
        $db = Db::getInstance();
        $array = [$value];
        $sql = $attribute == 'mail' ? "Select Id, Login, Haslo, Mail from users where Mail =? limit 1" : "Select Id, Login, Haslo, Mail from users where Login =? limit 1";   
        $result = $db->select($sql, $array);
        foreach ($result as $row)
        {
            return new User($row['Login'], $row['Haslo'], $row['Mail'],$row['Id']);
        }
        
        return null;
    }
    
    // zostawić metodę getByLogin + dodać dodatkowo metodę exists (po mailu/loginie)
    /**
     * 
     * @param \App\Models\User $user
     * @return type
     */
        //register zamist registerNewUser
    public function registerNewUser(User $user)
    {
        $db = Db::getInstance();
        $array=$user->jsonSerialize(false);
        $sql = "Insert into users (Login, Haslo, Mail) values (?, ?, ?)";
        return $db->insert($sql, $array);

    }
    
}