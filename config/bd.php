<?php
require_once '/xampp/htdocs/Super-Market/vendor/autoload.php';

class Database {
    private $client;
    private $db;

    public function __construct() {
        $this->client = new MongoDB\Client("mongodb://localhost:27017");
        $this->db = $this->client->pyc_fidelizacion;
    }

    public function getConnection() {
        return $this->db;
    }
}
?>