<?php

namespace App\Core;

class Controller
{
    private $db;
    private $data = [];
    
    final public function __construct(DatabaseConnection &$dbc)
    {
        $this->db = $dbc;
    }

    final public function &getConnection(): DatabaseConnection
    {
        return $this->db;
    }

    final protected function set(string $name, $value): bool
    {
        $result = false;

        if(preg_match("/^[a-z][a-z0-9]+([A-Z][a-z0-9]+)*$/", $name)){

            $this->data[$name] = $value;

            $result = true;
        }

        return $result;
    }

    final public function getData(): array
    {
        return $this->data;
    }
}