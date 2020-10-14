<?php

declare(strict_types=1);

namespace App\Domain\Model;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

class User implements UserInterface, EquatableInterface, \JsonSerializable
{
    private $id ="";
    private $login = "";
    private $password = "";
    private $mail = "";
    
    public function __construct($id, $login, $password, $mail )
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->mail = $mail;
    }    

    public function getId()
    {
        return $this->id;
    }
    //dodać gettery
    public function getLogin()
    {
        return $this->login;
    }
    public function getMail()
    {
        return $this->mail;
    }
    public function getPassword()
    {
        return $this->password;
    }
    
    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function jsonSerialize()
    {
        $array = ['id' => $this->id, 'login' => $this->login, 'password' => $this->password, 'mail' => $this->mail] ;
        return $array;      
    }
    //w zaleznsoci od tego kto sie rejestruje to roznze role mozna by nadawac i zapisywac w BD
    public function getRoles()
    {
        return ['ROLE_USER'];
    }
    
    public function getSalt()
    {
        return null;
    }
    
    public function getUsername()
    {
        return $this->login;
    }
    
    public function eraseCredentials()
    {
        $this->password = null;
    }

    public function isEqualTo(UserInterface $user): bool {
        // @TODO implement
        return true;
    }

}
