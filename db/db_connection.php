<?php

class DBConnection
{
    private $db_host = 'localhost';
    private $db_username = 'root';
    private $db_password = '';
    private $db_name = 'tms';
    public $conn = null;

    public function connect()
    {
        $this->conn = new mysqli(
                $this->db_host,
                $this->db_username,
                $this->db_password,
                $this->db_name
        );

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}
?>
