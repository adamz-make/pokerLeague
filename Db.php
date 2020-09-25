<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Db
 *
 * @author adamz
 */
    class Db {
    //put your code here
        static $conn;
    
    public static function connect()
    {
        
       //$serverName = "localhost";
        //$userName = "root";
        //$password ="";
        $serverName = "localhost";
        $userName = "srv32332_psl";
        $password ="QYA3IA81";
        
        try{
            
            //self::$conn = new PDO("mysql:host=$serverName;dbname = pokerleague",$userName,$password);
            self::$conn = new PDO("mysql:host=$serverName;dbname = srv32332_psl",$userName,$password);
        } catch (Exception $ex) {
          echo $ex;
        }     
    }
    public static function addNewUser($login, $password, $mail)
    {
        $array = [$login,$password,$mail];
        //$Sql = "Insert into pokerleague.users (Login, Haslo, Mail) values (?, ?, ?)";
        $Sql = "Insert into srv32332_psl.users (Login, Haslo, Mail) values (?, ?, ?)";
        self::executeSql($Sql,$array,2);
    }
    private static function executeSql($Sql,$array,$param)
    {  //1 - select; 2 -insert,
        $stmt = self::$conn->prepare($Sql);
        if ($param ==1)
        {
         
            $stmt->execute($array); 
            $result = $stmt->fetchAll();
            return $result;
        }
        else
        {
            $stmt->execute($array); 
        }
        return null;
      
    }
    public static function getAllUsers()
    {
         if (!self::IsConnectedToDb())
        {   
          self::connect();
        }
        $array = [];
        
        //$Sql = "Select Login from pokerleague.users";
        $Sql = "Select Login from srv32332_psl.users";
        $result = self::executeSql($Sql,$array,1); 
        return $result;
    }
    
    private static function IsConnectedToDb()
    {
        return (isset (self::$conn))? true:false;
    }
    
    public static function checkLoginAndPass($login,$password)
    {
        if (!self::IsConnectedToDb())
        {   
          self::connect();
        }
        $array = [$login,$password];
        
        //$Sql = "Select * from pokerleague.users where Login =? and Haslo =?";
        $Sql = "Select * from srv32332_psl.users where Login =? and Haslo =?";
        $result = self::executeSql($Sql,$array,1); 
        return (empty($result)) ? false : true;
    }
    
    public static function getNrMatch()
    {
        if (!self::IsConnectedToDb())
        {   
          self::connect();
        }
        
        $array = [];
        
        $Sql = "Select count(*) from srv32332_psl.mecze";
        //$Sql = "Select count(*) from pokerleague.mecze";
        $result = self::executeSql($Sql,$array,1); 
        return $result;
       
    }
    public static function isMatchAdded($nrMatch) :bool
    {
         if (!self::IsConnectedToDb())
        {   
          self::connect();
        }
        $array = [$nrMatch];
        
        //$Sql = "Select 1 from pokerleague.mecze where nrMeczu=? ";
        $Sql = "Select 1 from srv32332_psl.mecze where nrMeczu=? ";
        $result = self::executeSql($Sql,$array,1); 
        
        return (empty($result))? false : true;
    }
    
    public static function addMatch($nrMatch)
    {
         if (!self::IsConnectedToDb())
        {   
          self::connect();
        }
        if (self::isMatchAdded($nrMatch) == false)
        {
        $date = date("Y-m-d H:i:s"); 
        $array = [$nrMatch,$date];
        
        //$Sql = "Insert into pokerleague.mecze (nrMeczu,dataDodania) values (?,?)";
        $Sql = "Insert into srv32332_psl.mecze (nrMeczu,dataDodania) values (?,?)";
        self::executeSql($Sql,$array,2);
        }
        
        
    }
    
    public static function addUserResult($user, $points, $beers, $tokens, $nrMatch)
    {
          if (!self::IsConnectedToDb())
        {   
          self::connect();
        }
        $userId = self::getUserIdbyLogin($user)[0][0];

        $matchId = self::getMatchId($nrMatch)[0][0];
        
        //$Sql = "Insert into pokerleague.wyniki (idUsera, IdMeczu, liczbaPunktow, liczbaPiw, liczbaZetonow) values (?,?,?,?,?)";
        $Sql = "Insert into srv32332_psl.wyniki (idUsera, IdMeczu, liczbaPunktow, liczbaPiw, liczbaZetonow) values (?,?,?,?,?)";
        $array = [$userId,$matchId, $points, $beers, $tokens,];
        
        self::executeSql($Sql,$array,2);
    }
    private static function getUserIdbyLogin($login)
    {  
          if (!self::IsConnectedToDb())
        {   
          self::connect();
        }
        
        $array = [$login];
        
        $Sql = "Select id from srv32332_psl.users where Login =?";
        //$Sql = "Select id from pokerleague.users where Login =?";
        $result = self::executeSql($Sql,$array,1); 
        return $result;
    }
    private static function getMatchId($nrMatch)
    {
        if (!self::IsConnectedToDb())
        {   
          self::connect();
        }
        $array = [$nrMatch];
        
        $Sql = "Select id from srv32332_psl.mecze order by dataDodania desc limit 1";
        //$Sql = "Select id from pokerleague.mecze order by dataDodania desc limit 1";
        $result = self::executeSql($Sql,$array,1); 
        return $result;
    }
    
    public static function getAttributeForUserLogin($userLogin, $attribute)
    {
        if (!self::IsConnectedToDb())
        {   
          self::connect();
        }
        
        $user = self::getUserIdbyLogin($userLogin);
        $array = [$user[0][0]];
        $Sql ="";
        switch ($attribute)
        {
          case "points":
              
                    $Sql = "Select SUM(liczbaPunktow) from srv32332_psl.wyniki where idUsera = ? ";
                    //$Sql = "Select SUM(liczbaPunktow) from pokerleague.wyniki where idUsera = ? ";
                 break;
             case  "beers":
                 $Sql = "Select SUM(liczbaPiw) from srv32332_psl.wyniki where idUsera = ? ";
                 //$Sql = "Select SUM(liczbaPiw) from pokerleague.wyniki where idUsera = ? ";
                 break;
          case "tokens":
              $Sql = "Select SUM(liczbaZetonow) from srv32332_psl.wyniki where idUsera = ? ";
              //$Sql = "Select SUM(liczbaZetonow) from pokerleague.wyniki where idUsera = ? ";
              break;
        }
        $result = self::executeSql($Sql,$array,1); 
        return $result;
        
    }
    
    public static function resultUserAlreadyAdded($user, $nrMatch) : bool
    {
        if (!self::IsConnectedToDb())
        {   
          self::connect();
        }
        $userId = self::getUserIdbyLogin($user)[0][0];

        $matchId = self::getMatchId($nrMatch)[0][0];
        $array = [$userId,$MatchId];
        
        $Sql = "Select 1 from srv32332_psl.wyniki where idUsera = ? and idMeczu = ?";
        //$Sql = "Select 1 from pokerleague.wyniki where idUsera = ? and idMeczu = ?";
        $result = self::executeSql($Sql,$array,1); 
        return empty($result)? false : true;
    }
    
}
