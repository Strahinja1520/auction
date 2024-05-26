<?php
namespace App\Core;


class DatabaseConfig
{
    private $dbhost;
    private $dbname;
    private $dbuser;
    private $dbpass;
    public function __construct(string $dbhost, string $dbname, string $dbuser, string $dbpass)
    {
        $this->dbhost = $dbhost;
        $this->dbname = $dbname;
        $this->dbuser = $dbuser;
        $this->dbpass = $dbpass;
    }

    public function getSource():string
    {
        return "mysql:host={$this->dbhost};dbname={$this->dbname};charset=utf8";
    }

    public function getUser(): string
    {
        return $this->dbuser;
    }
    public function getPass(): string
    {
        return $this->dbpass;
    }
}
