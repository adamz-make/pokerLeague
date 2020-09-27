<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Rejestruj
 *
 * @author adamz
 */

$rejestracja = new RegisterClass($_POST);

function __autoload($class)
{
    include_once($class.".php");
}


class RegisterClass {
    //put your code here
    private $mail;
    private $login;
    private $password;
    private $checkPassword;
           
    public function __construct($dane)
    {
        $this->mail = $dane['Email'];
        $this->login = $dane['login'];
        $this->password = $dane['pass'];
        $this->checkPassword = $dane ['pass2'];
        if ($this->checkPass())
        {
            if ($this->checkMail())
            {
                $this->register();
            }
            else
            {
                echo "Nie poprawny mail";
            }
        }
        else
        {
            echo "Hasla nie są takie same";
        }
        
        
    }
   public function checkMail():bool{
   $pattern ='/^[a-zA-Z0-9\.\-_]+\@[a-zA-Z0-9\.\-_]+\.[a-z A-Z]+/'; 
   $result = preg_match($pattern,$this->mail);
       if ($result ==1)
       {
            return true; 
       }
         return false;
           
       
}
public function checkPass(): bool{
    if ($this->password == $this->checkPassword)
    {
        return true;
    }
    return false;
}

private function register()
{
    //przerzucic do klasy db i dodac rekord do Bazy
    Db::connect();
    DB::addNewUser($this->login, $this->password, $this->mail);
}



}
