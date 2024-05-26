<?php 

namespace App\Core;


class DatabaseConnection
{
    private $connection;
    private $config;

    public function __construct(DatabaseConfig  $databaseConfig)
    {
        $this->config = $databaseConfig;

    }

    public function getConnection(): \PDO
    {
        if($this->connection === NULL){
            $this->connection = new \PDO($this->config->getSource(), 
                                        $this->config->getUser(), 
                                        $this->config->getPass());
        }

        return $this->connection;
    }
}
