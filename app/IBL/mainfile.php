<?php

namespace App\IBL;

class mainfile
{
    public $db;
    public $dbhost;
    public $dbuname;
    public $dbpass;
    public $dbname;
    public $mysqli_db;

    public function __construct()
    {
        $this->dbhost = config('database.connections.mysql.host');
        $this->dbuname = config('database.connections.mysql.username');
        $this->dbpass = config('database.connections.mysql.password');
        $this->dbname = config('database.connections.mysql.database');
        $this->db = new MySQL($this->dbhost, $this->dbuname, $this->dbpass, $this->dbname, false);
        $this->mysqli_db = new \mysqli($this->dbhost, $this->dbuname, $this->dbpass, $this->dbname);
    }
}