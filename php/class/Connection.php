<?php
class Connection{

    protected $db;

    public function __construct(){

    $conn = NULL;

        try{
            $conn = new PDO("mysql:host=localhost;dbname=digitalretail", "root", "digitalretail2019!");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e){
                echo 'ERROR: ' . $e->getMessage();
                }
            $this->db = $conn;
    }

    public function getConnection(){
        return $this->db;
    }
}

?>
