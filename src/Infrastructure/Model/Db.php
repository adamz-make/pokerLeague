<?php
declare (strict_types=1);

namespace App\Infrastructure\Model;
    
class Db {
    //put your code here
    static $conn;
    private static $INSTANCE = null;
    /**
     *
     * @var \Doctrine\DBAL\Connection
     */
    private $connection = null;
    
    private function __construct()
    {
        
    }
    /**
     * 
     * @return Db
     */
    public static function getInstance()
    {
        if (self::$INSTANCE != null)
        {
            return self::$INSTANCE;
        }
        self::$INSTANCE = new Db();
        self::$INSTANCE->init();
        return self::$INSTANCE;
    }
    
    public function select($sql, $array)
    {
        $stmt = $this->connection->executeQuery($sql,$array);
        return $stmt->fetchAllAssociative();
    }
    
    public function insert ($table, $array)
    {
        try
        {
         //   $stmt = $this->connection->executeQuery($sql, $array);
            $this->connection->insert($table, $array);
            return true;
        }   catch (Exception $ex) 
        {
            return false;
        }
        
        
    }
       
    private function init()
    {
        $connectionParams =[
            'dbname' => $_ENV['DB_NAME'],
            'user' => $_ENV['DB_USER_NAME'],
            'password' => $_ENV['DB_PASSWORD'],
            'host' => $_ENV['DB_SERVER_NAME'],
            'driver' => 'pdo_mysql',
        ]; 
        
        try{
            $this->connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams);
        } catch (Exception $ex) {
          echo $ex;
        }     
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
    
    private static function IsConnectedToDb()
    {
        return (isset (self::$conn))? true:false;
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
        $Sql = "select sum($attribute) from srv32332_psl.wyniki where idUsera = ?";
       // switch ($attribute)
       // {
         // case "points":
              
                    /*$Sql = "Select SUM(liczbaPunktow) from srv32332_psl.wyniki where idUsera = ? ";
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
                     * */
                     
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
