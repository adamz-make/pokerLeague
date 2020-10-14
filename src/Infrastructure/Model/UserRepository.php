<?php
declare(strict_types=1);

namespace App\Infrastructure\Model;

use App\Domain\Model\UserRepositoryInterface;
use App\Domain\Model\User;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class UserRepository implements UserRepositoryInterface, UserLoaderInterface{
   
   /**
    * 
    * @param type $attribute
    * @param type $value
    * @return \App\Domain\Models\User
    */
     public function getByLogin($login): ?User
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

    public function userExists(string $login, string $mail): ?User
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

    public function loadUserByUsername(string $username): ?User
    {
        $db = Db::getInstance();
        $array = [$username];
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
     * @return User[]
     */
    public function getAllUsers() : array
    {
        $db = Db::getInstance();
        $array = [];
        $sql = "Select Id, Login, Haslo, Mail from users";   
        $result = $db->select($sql, $array);
        $users = [];
        foreach ($result as $row)
        {
            $users[] = new User($row['Id'], $row['Login'], $row['Haslo'], $row['Mail']);
        }        
        return $users;
    }
    
    public function getById($id): ?User
    {
        $db = Db::getInstance();
        $array = [$id];
        $sql = "Select Id, Login, Haslo, Mail from users where id =? limit 1";   
        $result = $db->select($sql, $array);
        foreach ($result as $row)
        {
            return new User($row['Id'], $row['Login'], $row['Haslo'], $row['Mail']);
        }        
        return null;
    }

}
