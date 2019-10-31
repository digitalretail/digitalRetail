<?php

//372fd13065067ab01ff0eb3b95f43cb1

//$2y$10$AY0eTrPOtLmPGwrRGOs1K.OgFtekrsaQPaobLZOsAIp/nzPEvCfI6  asdasd
class sql {

    protected $con = NULL;
    private $result = NULL;
    protected $key = NULL;
    protected $key_public = NULL;

    public function __construct() {
	    if($this->con){
			$this->con->close();
		}
        $this->connect();
        $this->key = md5(base64_decode("QVVESTA5RXNjcg=="));
        $this->key_public = md5(base64_decode("QVVESTA5RXNjcg=="));
        $this->key;
    }

    private function connect(){
       $this->con =  new mysqli(DB_HOST,DB_USER,DB_PASS, DB_NAME);
       if (mysqli_connect_errno()) {
        printf("Verbindung fehlgeschlagen: %s\n", mysqli_connect_error());
        exit();
      }else{
      }
    }

	public function __deconstruct(){
	   $this->close();
	}

}

?>
