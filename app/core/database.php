<?php

class Database {
    
    private static $instance = null;
    
    private $connection ;
    private $config ;

    
    private function __construct(){
        
       
         $this->config = require_once __DIR__ . '/../../config/db.php';
         
        try {
            $this->connection = new PDO("mysql:host={$this->config['host']};dbname={$this->config['dbname']}",
            $this->config['username'],$this->config['password']);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die( "Erreur: " . $e->getMessage());
        }
    }


    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();

        }
        return self::$instance;
    }

  
    public function getConnection() {
        return $this->connection;
    }

   
}


