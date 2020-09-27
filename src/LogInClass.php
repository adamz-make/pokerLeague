<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 function __autoload($class)
{
 include_once($class.".php");   
}
session_start();
$logowanie = new LogInClass($_POST);
$_SESSION["loggedIn"]=false;
if ($logowanie->getLoggedIn() == true)
{
    $_SESSION["loggedIn"]=true;
    header('Location: LoggedInto.php'); 
}
else
{
    echo "Niepoprawne haslo lub login";
}


class LogInClass {
    //put your code here
    
    private $login="";
    private $password="";
    private $loggedIn = false;
    
    public function getLoggedIn()
    {
        return $this->loggedIn;
    }
    public function __construct($dane)
    {
       $this->login = $dane['login'];
       $this->password = $dane['pass'];
       $this->validate();
}

    private function validate()
    {//polacz z DB
        if(Db::checkLoginAndPass($this->login, $this->password))
        {
            $this->loggedIn = true;
        }
       
    }
}


