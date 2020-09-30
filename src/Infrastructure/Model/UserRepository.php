<?php
declare (strict_types=1);

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
     public function getByLogin($login): User
    {
        $db = Db::getInstance();
        $array = [$login];
        $sql = "Select Id, Login, Haslo, Mail from users where Login =? limit 1";   
        $result = $db->select($sql, $array);
        foreach ($result as $row)
        {
            return new User($row['Id'], $row['Login'], $row['Haslo'], $row['Mail']);
        }        
        return null;
    }
    
    /**
     * 
     * @param \App\Models\User $user
     * @return type
     */
    
    public function register(User $user)
    {
        $db = Db::getInstance();
        $table = 'users';
        $array = ['Login' => $user->getLogin(),'Haslo' => $user->getPassword(), 'Mail' => $user->getMail()];
        return $db->insert($table, $array);
    }
   /**
    * 
    * @param string $login
    * @param string $mail
    * @return User
    */

    public function userExists(string $login, string $mail)
    {
        $db = Db::getInstance();
        $array =[$login, $mail];
        $sql = "Select Id, Login, Haslo, Mail from users where Login =? or Mail =?";
        $result = $db->select($sql, $array);
        foreach ($result as $row)
        {
            return new User($row['Id'], $row['Login'], $row['Haslo'], $row['Mail']);
        }               
        return null;
    }


}
