<?php

declare (strict_types=1);

namespace App\Domain\Model;


class User implements \JsonSerializable {
    private $id ="";
    private $login = "";
    private $password = "";
    private $mail = "";
    
    public function __construct($login, $password, $mail, $id = null)
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->mail = $mail;
    }    

    //dodać gettery
    public function getPassword()
    {
        return $this->password;
    }

    //zostawić domyślną tak jak było - metody, które istnieją, nie zmieniać
    public function jsonSerialize($withId = true)
    {
        $array = $withId === true ? ['id' => $this->id, 'login' => $this->login, 'password' => $this->password, 'mail' => $this->mail] : 
            ['login' => $this->login, 'password' => $this->password, 'mail' => $this->mail]; 
        return $array;      
    }

}
