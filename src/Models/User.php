<?php


namespace App\Models;

class User implements \JsonSerializable {
    private $id ="";
    private $login = "";
    private $password = "";
    private $mail = "";
    
    public function __construct($id, $login, $password, $mail)
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->mail = $mail;
    }    

    public function getPassword()
    {
        return $this->password;
    }

    public function jsonSerialize() 
    {
        $array = ['id' => $this->id, 'login' => $this->login, 'password' => $this->password, 'mail' => $this->mail];   
        return $array;      
    }

}
