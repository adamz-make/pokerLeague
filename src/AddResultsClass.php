<?php

function __autoload($class)
{
    include_once($class.".php");
}

?>

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AddResultClass
 *
 * @author adamz
 */

session_start();

if ($_SESSION['loggedIn']==false)
        {
            header ('Location: LogIn.php');
        }
    AddResultClass::addMatch($_GET['nrMatch']);

    $addResult = new AddResultClass($_GET['user'],$_GET['points'],$_GET['beers'],$_GET['tokens'],$_GET['nrMatch']);
    $addResult->addUserResult();
    header('Location:AddResults.php');

class AddResultClass {

    private $tokens =0;
    private $beers=0;
    private $points =0;
    private $user = "";
    private $nrMatch;
    
    public function __construct($user, $tokens, $beers, $points, $nrMatch)
    {
        $this->tokens = $tokens;
        $this->points = $points;
        $this->beers = $beers;
        $this->user = $user;
        $this->nrMatch = $nrMatch;
    }
    public static function addMatch($nrMatch)
    {
        Db::addMatch($nrMatch);                
    }
    
    public function addUserResult()
    {
        if (Db::resultUserAlreadyAdded==false)
        Db::addUserResult($this->user, $this->points, $this->beers, $this->tokens, $this->nrMatch);        
    }
    
    
    
    
}
