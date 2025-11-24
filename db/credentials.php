<?php

class DbLogin {
    private $server = "";
    private $usr = "";
    private $passwd = "";
    private $db = "";

    public function getServer() {
        return $this->server;
    }
    
    public function getDb() {
        return $this->db;
    }
    
    public function getUsr() {
        return $this->usr;
    }

    public function getPasswd() {
        return $this->passwd;
    }
}
