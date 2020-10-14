<?php
declare(strict_types=1);

namespace App\Infrastructure\Model;
    
class Db {
    
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
        
        try
        {
            $this->connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams);
        }
        catch (Exception $ex)
        {
            echo $ex;
        }     
    }    
}
