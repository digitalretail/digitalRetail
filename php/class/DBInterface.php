<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of d
 *
 * @author T2A5VVW
 */
class DBInterface extends Connection {



    public function __construct() {
        parent::__construct();
    }

    public function getAllPlayouts(){
      try {
          $db = $this->getConnection();
          $stmt = $db->query("SELECT Count(*) as 'anzahl', AVG(sessionDuration) as 'duration', cre_date FROM playout group by cre_date order by cre_date ASC" );
          $stmt -> execute();
          return $results;
      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }

    public function getAnswerListByQustion($id){
      try {
          $db = $this->getConnection();
          $stmt = $db->query("SELECT value from survey where nr=$id" );

          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

          $result[0] = 0;
          $result[1] = 0;
          $result[2] = 0;
          $result[3] = 0;
          $result[4] = 0;
          $result[5] = 0;
          $a =1;
          foreach($results as $row) {
              $result[$row['value']]++;
              $a++;
          }
          return $result;
      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }

    public function getAnswerSelectByQustion($id){
      try {
          $db = $this->getConnection();
          $stmt = $db->query("SELECT value from survey where nr=$id" );

          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
          return $results;

      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }

    public function storeImg($img,$token,$audiCode,$view,$name){
      try {



          $db = $this->getConnection();
          $query = ("Insert into images(image,token,audiCode,view,name) values (:img,:token,:audiCode,:view,:name)");

          $stmt  = $db -> prepare($query);
          $stmt-> bindParam(':img',$img,PDO::PARAM_LOB); //PDO::PARAM_STR, 12
          $stmt-> bindParam(':audiCode',$audiCode,PDO::PARAM_STR,40);
          $stmt-> bindParam(':view',$view,PDO::PARAM_STR,40);
          $stmt-> bindParam(':token',$token,PDO::PARAM_STR,40);
            $stmt-> bindParam(':name',$name,PDO::PARAM_STR,40);

          $stmt->execute();
      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }

    function getImage($id){
      try {
          $db = $this->getConnection();
          $stmt = $db->query("Select image as image from images where id=$id" );
          $stmt -> execute();
          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
          return $results;
      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }

    function getImageByToke($token){
      try {
          $db = $this->getConnection();
          $stmt = $db->query("Select image as image from images where token='$token'" );
          $stmt -> execute();
          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
          return $results;
      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }

    function getAudiCode($token){
      try {
          $db = $this->getConnection();
          $stmt = $db->query("Select audiCode as audiCode from images where token='$token'" );
          $stmt -> execute();
          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
          foreach($results as $row) {
            return $row['audiCode'];
          }
      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }

    function getName($token){
      try {
          $db = $this->getConnection();
          $stmt = $db->query("Select name as name from images where token='$token'" );
          $stmt -> execute();
          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
          foreach($results as $row) {
            return $row['name'];
          }
      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }


    public function saveAnswer($id,$val,$token){
      try {



          $db = $this->getConnection();

          $query = "Select * from survey where token=:token and nr=:id ";
          $stmt  = $db -> prepare($query);

          $stmt-> bindParam(':id',$id,PDO::PARAM_INT,2); //PDO::PARAM_STR, 12
          $stmt-> bindParam(':token',$token,PDO::PARAM_STR,40); //PDO::PARAM_STR, 12

          $stmt -> execute();
          echo "<br><hr>";
          echo $anzahl = $stmt->rowCount();
          if($anzahl>0){
          $query = ("Update survey set value=:val where token=:token and nr=:id");
          }else{
          $query = ("Insert into survey(nr,value,token) values(:id,:val,:token)");
          }
          $stmt  = $db -> prepare($query);
          $stmt-> bindParam(':id',$id,PDO::PARAM_INT,2); //PDO::PARAM_STR, 12
          $stmt-> bindParam(':val',$val,PDO::PARAM_STR,40);
          $stmt-> bindParam(':token',$token,PDO::PARAM_STR,40);

          $stmt->execute();
          print_r($stmt);
      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }

    public function getAllPlayoutsMarket($market){
      try {

          $date = date("Y-m-d", time()-(60*60*24));
          $db = $this->getConnection();
          $query = ("select p.cre_date,count(*) as numbers from (`digitalretail`.`dealer` `d` left join (`digitalretail`.`client` `c` left join `digitalretail`.`playout` `p` on((`p`.`clientId` = `c`.`clientId`))) on((`d`.`dealerId` = `c`.`dealerId`))) where  (`c`.`cre_date` = '$date') and (`d`.`cre_date` ='$date') and (`d`.`country` = '$market') group by p.cre_date  order by p.cre_date ASC" );
            $stmt = $db->query($query);
          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
          return $results;
      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }

    public function getPLayoutByDealerKPI($start_date,$end_date,$dealerId){
      try {
          $db = $this->getConnection();
          $result = new ArrayObject();
        $query = ("select c.clientClass,p.playoutId,p.startTime,p.sessionDuration,p.playoutMode from  client c
                                   left join playout p  on `p`.`clientId` = `c`.`clientId`
                                   where  (`p`.`startTime` >='$start_date') and (`p`.`startTime` <= '$end_date') and c.dealerId='$dealerId'  order by startTime ASC" );
          $stmt = $db->query($query);
          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
          foreach($results as $eintrag){
            $result->append($eintrag);
          }

          return $result;
      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }





    public function getTopDealerByMarket($start_date,$end_date,$dealerId){
      try {
          $db = $this->getConnection();
          $result = new ArrayObject();
          $query = ("select count(p.playoutId) as anzahl,c.dealerId,m.code from  client c
                                   left join playout p  on `p`.`clientId` = `c`.`clientId`
                                   left join dealer d on d.dealerId = c.dealerId
                                   left join mat m on m.id=d.dealerId
                                   where  (`p`.`startTime` >='$start_date') and (`p`.`startTime` <= '$end_date') and d.country='$dealerId' group by c.dealerId,m.code order by anzahl DESC" );
          $stmt = $db->query($query);
          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
          foreach($results as $eintrag){
            $result->append($eintrag);
          }

          return $result;
      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }

    public function getPLayoutByMarketKPI($start_date,$end_date,$dealerId){
      try {
          $db = $this->getConnection();
          $result = new ArrayObject();
          $query = ("select c.clientClass,p.playoutId,p.startTime,p.sessionDuration,p.playoutMode,c.dealerId from  client c
                                   left join playout p  on `p`.`clientId` = `c`.`clientId`
                                   left join dealer d on d.dealerId = c.dealerId
                                   where  (`p`.`startTime` >='$start_date') and (`p`.`startTime` <= '$end_date') and d.country='$dealerId'  order by startTime ASC" );
          $stmt = $db->query($query);
          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
          foreach($results as $eintrag){
            $result->append($eintrag);
          }

          return $result;
      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }

    public function getDealerToMarket($market){
      try {
          $db = $this->getConnection();
          $result = new ArrayObject();
          $query = ("Select d.dealerId,m.id,m.code from dealer d left join mat m on m.id=d.dealerId where d.country='$market' order by code ASC " );
          $stmt = $db->query($query);
          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
          foreach($results as $eintrag){
            $result->append($eintrag);
          }

          return $result;
      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }

    public function getAllPlayoutsByMode($market){
      try {

          $date = date("Y-m-d", time()-(60*60*24));
          $db = $this->getConnection();
          $query = ("select p.playoutMode,count(*) as numbers from (`digitalretail`.`dealer` `d` left join (`digitalretail`.`client` `c` left join `digitalretail`.`playout` `p` on((`p`.`clientId` = `c`.`clientId`))) on((`d`.`dealerId` = `c`.`dealerId`))) where  (`c`.`cre_date` = '$date') and (`d`.`cre_date` ='$date') and (`d`.`country` = '$market') group by p.playoutMode  order by numbers ASC" );
          $stmt = $db->query($query);
          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
          return $results;
      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }

    public function getPlayoutsByMode($mode){
      try {
          $db = $this->getConnection();
          $stmt = $db->query("SELECT Count(*) as 'anzahl', AVG(sessionDuration) as 'duration', cre_date FROM playout where playoutMode='$mode' group by cre_date order by cre_date ASC" );
          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
          return $results;
      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }

    public function getRegisteredDeviceTyp($market){
     try {
       $db = $this->getConnection();
       if(strlen($market) <3){

       $stmt = $db->query("SELECT count(*) as 'anzahl',c.clientClass,c.cre_date FROM `client` c left join dealer d on d.dealerId = c.dealerId where d.country='$market' and c.clientClass<>'ADVISE' group by c.clientClass,c.cre_date ORDER BY anzahl DESC" );

       }else{
         $stmt = $db->query("SELECT count(*) as 'anzahl',c.clientClass,c.cre_date  FROM `client` c  where c.dealerId='$market' group by c.clientClass,c.cre_date ORDER BY anzahl DESC" );

       }
       $result = new ArrayObject();
       $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
       foreach($results as $eintrag){
         $result->append($eintrag);
       }

       return $result;
      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }

    public function getDevicesOverall(){
     try {
       $db = $this->getConnection();
       $stmt = $db->query("SELECT count(*) as 'anzahl',d.country FROM `client` c
                                                left join dealer d on d.dealerId = c.dealerId
                                                where c.clientClass='AVE' or  c.clientClass='MTT'  group by d.country" );


       $result = new ArrayObject();
       $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
       foreach($results as $eintrag){
         $result->append($eintrag);
       }

       return $result;
      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }

    public function getRegisterDeviceTypMarket($market){
      try {
          $date = date("Y-m-d", time()-(60*60*24));
          $db = $this->getConnection();
          $query = "SELECT count(*) as 'anzahl',c.clientClass FROM `client` c left join dealer d on d.dealerId =c.dealerId where c.cre_date='$date' and d.cre_date='$date' and d.country='$market' group by c.clientClass ORDER BY anzahl DESC";
          $stmt = $db->query($query);
          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

          return $results;
      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }

    public function getRegisterDeviceByDealerId($dealerId,$type){
      try {
          $date = date("Y-m-d", time()-(60*60*24));
          $db = $this->getConnection();
          $query = "SELECT c.clientId as id,c.clientVersion as clientVersion, c.language as language, c.os as os  FROM `client` c where c.dealerId='$dealerId' and c.clientClass='$type' and c.lastActive>='1551394800000' and c.cre_date='$date'";
          $stmt = $db->query($query);
          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

          return $results;
      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }



    public function getTopTenDealerByDate($date){
      try {
          $db = $this->getConnection();
          $sql = "SELECT count(p.id) as 'anzahl',c.dealerId as dealerId FROM `playout` p inner join client c on c.clientId=p.clientId where p.cre_date='$date' and c.cre_date='$date' group by c.dealerId ORDER BY `anzahl` DESC limit 0,10";
          //echo $sql;
          $stmt = $db->query($sql);
          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
          return $results;
      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }

    public function getPlayoutByMarket($market,$start_date,$end_date){
      try {

          $db = $this->getConnection();
          $sql = "SELECT count(p.id) as 'anzahl' FROM `playout` p
                  inner join client c on c.clientId=p.clientId
                  inner join dealer d on d.dealerId = c.dealerId
                  where  d.country='$market'
                        and (`p`.`startTime` >='$start_date') and (`p`.`startTime` <= '$end_date')

                   group by d.country";
          //echo $sql;
          $stmt = $db->query($sql);
          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
          foreach($results as $row) {
            return $row['anzahl'];
          }
      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }

    public function getPlayoutDealerByMarket($market){
      try {
            $date = date("Y-m-d", time()-(60*60*24));
          $db = $this->getConnection();
           $sql = "SELECT count(p.id) as 'anzahl',c.dealerId as dealerId FROM `playout` p
                  inner join client c on c.clientId=p.clientId
                  inner join dealer d on d.dealerId = c.dealerId
                  where d.cre_date='$date' and c.cre_date='$date' and d.country='$market' group by c.dealerId ORDER BY `anzahl` DESC";
          //echo $sql;
          $stmt = $db->query($sql);
          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
          return $results;
      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }

    public function getPlayoutDealerByMarketandType($market){
      try {
            $date = date("Y-m-d", time()-(60*60*24));
          $db = $this->getConnection();
           $sql = "SELECT c.clientClass, count(c.dealerId) as 'anzahl',c.dealerId as dealerId FROM `playout` p
                  inner join client c on c.clientId=p.clientId
                  inner join dealer d on d.dealerId = c.dealerId
                  where d.cre_date='$date' and c.cre_date='$date' and d.country='$market' group by c.dealerId,c.clientClass ORDER BY `anzahl` DESC";
          //echo $sql;
          $stmt = $db->query($sql);
          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
          return $results;
      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }

    public function getPlayoutsByDealer($dealerId){

      try {
          $db = $this->getConnection();
          $sql = "SELECT * FROM `playout` p inner join client c on c.clientId=p.clientId where c.dealerId='$dealerId'  order by p.startTime DESC";
          $stmt = $db->query($sql);
          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
          return $results;
      } catch(PDOException $ex) {
          print_r($ex);
          echo "An error has occured!";

      }
    }


    public function login($username,$password){
    try {
        $db = $this->getConnection();
        $stmt = $db->query("SELECT Count(*) FROM user WHERE email='$username' and pass='$password'");
        if(intval($stmt->fetchColumn()) === 1)
        {
           $stmt = $db->query("SELECT id,vorname,nachname,register,role,email,DATE_FORMAT(geburt,'%d.%m.%Y')as geburt FROM user WHERE email='$username' and pass='$password'");
           $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
           foreach($results as $row) {

                  return  $user = new User($row['id'],$row['vorname'], $row['nachname'], $row['email'], $row['role'],$row['geburt'],$row['register']);
           }
        }else
        {
          return 'false';
        }
    } catch(PDOException $ex) {
        print_r($ex);
        echo "An error has occured!";

    }
    }
    public function user_saveNewPasswort(User $user,$password){
        try {
        $db = $this->getConnection();
        $query = ("Update user set pass='$password', register='0' where id=:id");
        $stmt = $db->prepare($query);
        $stmt-> bindParam(':id',$user->getId(),PDO::PARAM_INT); //PDO::PARAM_STR, 12
        $stmt -> execute();
        return true;
        } catch(PDOException $ex) {
            print_r($ex);
            echo "An error has occured!";

        }
    }

    public function spieler_getGesamtSchnitt(){
        try {
        $db = $this->getConnection();
        $query = ("Select round(avg(note),2) as schnitt from noten;");
        $stmt = $db->prepare($query); //PDO::PARAM_STR, 12
        $stmt -> execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;

        } catch(PDOException $ex) {
            print_r($ex);
            echo "An error has occured!";

        }
    }

    public function user_saveToDB(User $user,$nr,$position,$kader,$handy){
        try{
        $nr = 0;
        $db = $this->getConnection();
        $db->beginTransaction();
        // Zufallsgenerator schütteln
        mt_srand((double) microtime() * 1000000);

        // Basiszeichenpool
        $set = "ABCDEFGHIKLMNPQRSTUVWXYZ123456789";
        $pin1 = "";

        // 10 stelligen PIN aus den o.a. Zeichen erzeugen
        for ($n=1;$n<=6;$n++){
                $pin1 .= $set[mt_rand(0,(strlen($set)-1))];
        }
        $pin = md5($pin1);
        $query = "Insert into user(pass,vorname,nachname,email,register,role,geburt,handy) values('$pin',:vorname,:nachname,:email,1,2,:geburt,:handy)";
        $stmt  = $db -> prepare($query);
        $stmt-> bindParam(':vorname',$user->getVorname(),PDO::PARAM_STR,40); //PDO::PARAM_STR, 12
        $stmt-> bindParam(':nachname',$user->getNachname(),PDO::PARAM_STR,40);
        $stmt-> bindParam(':email',$user->getEmail(),PDO::PARAM_STR,30);
        $stmt-> bindParam(':geburt',$user->getGeburtsdatum(),PDO::PARAM_STR,40);
        $stmt-> bindParam(':handy',$handy,PDO::PARAM_STR,40);

        $stmt -> execute();
        $id_user = $db->lastInsertId();
        $query = "Insert into spieler(user_id,nr,position,funktion,kader_id,img) values(:user_id,:nr,:position,'1',:kader,'')";
        $stmt  = $db -> prepare($query);
        $stmt-> bindParam(':user_id',$id_user,PDO::PARAM_INT); //PDO::PARAM_STR, 12
        $stmt-> bindParam(':position',$position,PDO::PARAM_INT); //PDO::PARAM_STR, 12
        $stmt-> bindParam(':nr',$nr,PDO::PARAM_INT); //PDO::PARAM_STR, 12
        $stmt-> bindParam(':kader',$kader,PDO::PARAM_INT); //PDO::PARAM_STR, 12
        $stmt -> execute();
        $db->commit();
        $text ="Herzlich Willkommen beim FCS
                <br><br>
                für Sie wurde ein Account angelegt.<br><br>
                <center>
                User: ".$user->getEmail()."<br>
                Pass: ".$pin1."
                <br><br></center>
                Nach der ersten Anmeldung müssen Sie Ihr Kennwort ändern.
                <br><br><b><center>
                Für uns - Für Euch - Für Sandersdorf </b>
                <br>               </center><br>
                Hier gehts zur APP (Android):<a href='http://www.winkler-it.com/fcs.apk'>Klick</a><br>
Hier gehts zur Web-APP:<a href='http://www.winkler-it.com/verein/'>Klick</a><br>
                FC S!!!";
        $betreff ="Registrierung FCS";
        $empfaenger = $user->getEmail();
        $mail = new Mail($text, $betreff, $empfaenger);
        $mail->sendMail();
        }catch(PDOException $e){
            $db->rollback();
            print_r($e);
        }
    }
    public function spieler_getSpielerZuZahlen(){
        try {
             $db = $this->getConnection();
            $sql ="SELECT t.id as termin_id,t.art,DATE_FORMAT(t.datum,'%d.%m.%Y')as datum, u.vorname, u.nachname, u.id
                    FROM zu_spieler_termine z
                    INNER JOIN user u ON z.spieler_id = u.id
                    INNER JOIN termine t ON t.id = z.id_termin
                    WHERE z.status_coach =0
                    AND z.status_spieler =1 and t.datum <= CURDATE() and t.datum >='2014-07-28'
                    and t.art=1
                    and z.gezahlt is null
                    order by u.nachname";
             $stmt  = $db -> prepare($sql);
             $stmt -> execute();
             $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
             return $results;
        }catch(PDOException $e){
            print_r($e);
        }
    }
    public function spiel_getAll(){
        try {
             $db = $this->getConnection();
             $sql ="Select spiel_name from noten group by spiel_name order by timestamp DESC";
             $stmt  = $db -> prepare($sql);
             $stmt -> execute();
             return $stmt;
        }catch(PDOException $e){
            print_r($e);
        }

    }

    public function spiel_getNoten($spiel){
          try {
             $db = $this->getConnection();
             $sql ="Select u.vorname,u.nachname,n.note,n.bemerkung from noten n inner join user u on u.id =
                    n.spieler_id where spiel_name=:spiel order by u.nachname ASC";
             $stmt  = $db -> prepare($sql);
             $stmt-> bindParam(':spiel',$spiel,PDO::PARAM_STR); //PDO::PARAM_STR, 12
             $stmt -> execute();
             $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
             return $results;
        }catch(PDOException $e){
            print_r($e);
        }
    }

    public function test(){
        try{
        $db = $this->getConnection();
        $query = 'SELECT * from s;';
        $stmt  = $db -> prepare($query);
        $stmt -> execute();
        $result = $stmt->fetch();
        print_r($result);

        }catch(PDOException $e){
            print_r($e);
        }
    }


    //Spielerfunktionen


    public function spieler_getValuesById(User $user){
        if(is_a($user, "User")){
            try{
              $db = $this->getConnection();
              $query = "Select nr,position,funktion,kader_id,img,lottozahl from spieler where user_id=:id";
              $stmt  = $db -> prepare($query);
              $stmt-> bindParam(':id',$user->getId(),PDO::PARAM_INT); //PDO::PARAM_STR, 12

              $stmt -> execute();
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
              foreach($results as $row) {
                       return $row;
                }

            } catch (PDOException $ex) {
                print_r($ex);
            }
        }else{
            print_r("Bitte anmelden");
        }
    }


    public function getAllTerminByArt($id,$art){
        if(isset($id)){
            try{
              $db = $this->getConnection();
              //1 = Training
              $query = "Select * from zu_spieler_termine z inner join termine t on z.id_termin=t.id where  t.final=1 and spieler_id=:id and t.art=:art";
              $stmt  = $db -> prepare($query);
              $stmt-> bindParam(':id',$id,PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt-> bindParam(':art',$art,PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt -> execute();
              return $stmt->rowCount();


            } catch (PDOException $ex) {
                print_r($ex);
            }
        }
    }
public function spieler_saveZahlung($id,$termin_id){
    try{
              $db = $this->getConnection();
              //1 = Training
              $query = "Update zu_spieler_termine set gezahlt=1 where spieler_id=:id and id_termin=:id_termin ";
              $stmt  = $db -> prepare($query);
              $stmt-> bindParam(':id',$id,PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt-> bindParam(':id_termin',$termin_id,PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt -> execute();
              return $stmt->rowCount();


            } catch (PDOException $ex) {
                print_r($ex);
            }
}

    public function kunde_saveKunde(Kunde $kunde){
            $save =false;
            $db = $this->getConnection();
            $vorname =$kunde->getVorname();
            $nachname =$kunde->getNachname();
            $strasse = $kunde->getStrasse();
            $plz = $kunde->getPlz();
            $email =$kunde->getEmail();
            $ort =$kunde->getOrt();
            $query = "Insert into kunde(vorname,nachname,strasse,plz,email,ort)
                       values(:vorname,:nachname,:strasse,:plz,:email,:ort)";

           try {
                $db->beginTransaction();
                $stmt  = $db -> prepare($query);
                $stmt-> bindParam(':vorname',$vorname,PDO::PARAM_STR,800);
                $stmt-> bindParam(':nachname',$nachname,PDO::PARAM_STR,200);
                $stmt-> bindParam(':strasse',$strasse,PDO::PARAM_STR,200);
                $stmt-> bindParam(':plz',$plz,PDO::PARAM_INT);
                $stmt-> bindParam(':ort',$ort,PDO::PARAM_STR,200);
                $stmt-> bindParam(':email',$email,PDO::PARAM_STR);
                $stmt->execute();

                 $id = $db->lastInsertId("id");
                $_SESSION['kunden_id'] = $id;
                $db->commit();
            } catch(PDOExecption $e) {
                $db->rollback();
                print "Error!: " . $e->getMessage() . "</br>";
            }

            return $id;
    }


    public function getAnzahlBarcode(){
         $db = $this->getConnection();
          $db->beginTransaction();
            $query = "Select id from barcode;";

           try {
                $stmt  = $db -> prepare($query);
                $stmt->execute();
                $anzahl = $stmt->rowCount();
                return $anzahl;
            } catch(PDOExecption $e) {
                $db->rollback();
                print "Error!: " . $e->getMessage() . "</br>";
            }
    }

    public function order_saveBarcode($code,$kunden_id,$order_id){
          $db = $this->getConnection();
          $db->beginTransaction();
            $query = "Insert into barcode(bestell_id,barcode,kunden_id,gesendet,time)
                   values(:bestell_id,:barcode,:kunden_id,0,CURRENT_TIMESTAMP);";

           try {
                $stmt  = $db -> prepare($query);
                $stmt-> bindParam(':bestell_id',$order_id,PDO::PARAM_INT);
                $stmt-> bindParam(':barcode',$code,PDO::PARAM_INT);
                $stmt-> bindParam(':kunden_id',$kunden_id,PDO::PARAM_INT);

                $stmt->execute();
               $query_1 = "Update bestellung set gezahlt=1,gezahlt_date=CURRENT_TIMESTAMP where id='$order_id'";
                $stmt  = $db -> prepare($query_1);
                $stmt->execute();
                $db->commit();
            } catch(PDOExecption $e) {
                $db->rollback();
                print "Error!: " . $e->getMessage() . "</br>";
            }

    }

    public function order_saveRemember($id){

           try {
            $db = $this->getConnection();
            $db->beginTransaction();
            $query = "Insert into remember(order_id)
                   values(:id);";

                $stmt  = $db -> prepare($query);
                $stmt-> bindParam(':id',$id,PDO::PARAM_INT);
                $stmt->execute();
                $db->commit();
            } catch(PDOExecption $e) {
                $db->rollback();
                print "Error!: " . $e->getMessage() . "</br>";
            }

    }

    public function order_getRemember($id){
           try{
              $timestamp = "";
              $db = $this->getConnection();
              //1 = Training
              $query = "Select DATE_FORMAT(timestamp,'%d.%m.%Y')as time from remember where                                     order_id='$id'";
              $stmt  = $db -> prepare($query);
              $stmt -> execute();
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
              foreach($results as $eintrag){
                  $timestamp = $eintrag['time'];
              }
              return $timestamp;


            } catch (PDOException $ex) {
                print_r($ex);
            }

    }

    public function order_getOrderById($id){
        try{
              $db = $this->getConnection();
              //1 = Training
              $query = "Select o.id,k.id as kunde_id, k.vorname, k.nachname, k.strasse,o.anzahl, o.verwendungszweck
                        from bestellung o inner join kunde k on k.id = o.id_kunde where o.id=$id";
              $stmt  = $db -> prepare($query);
              $stmt -> execute();
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
              foreach($results as $eintrag){
                  $order = new Order();
                  $order->setId($eintrag['id']);
                  $order->setAnzahl($eintrag['anzahl']);
                  $order->setKunde($eintrag['kunde_id']);
                  $order->setVerwendungszweck($eintrag['verwendungszweck']);


              }
              return $order;


            } catch (PDOException $ex) {
                print_r($ex);
            }
    }

    public function kunde_saveMail($body,$email,$betreff){

           $query = "Insert into mail(empfaenger,betreff,text)
                       values(:email,:betreff,:body)";
            $db = $this->getConnection();
           try {
                $db->beginTransaction();
                $stmt  = $db -> prepare($query);
                $stmt-> bindParam(':email',$email,PDO::PARAM_INT,800);
                $stmt-> bindParam(':betreff',$betreff,PDO::PARAM_INT,200);
                $stmt-> bindParam(':body',$body,PDO::PARAM_STR,800);
                $save = $stmt->execute();
                $db->commit();
            } catch(PDOExecption $e) {
                $db->rollback();
                print "Error!: " . $e->getMessage() . "</br>";
            }
            return $save;
    }

     public function order_getAllResult(){

         try{
              $db = $this->getConnection();
              //1 = Training
              $query = "Select o.bemerkung,o.id,DATE_FORMAT(o.time,'%d.%m.%Y') AS bestelldatum, k.id as kunde_id, k.vorname, k.nachname, k.strasse,o.anzahl, o.verwendungszweck, b.barcode
                        from bestellung o inner join kunde k on k.id = o.id_kunde inner join barcode b on b.bestell_id=o.id where o.gezahlt=1";
              $stmt  = $db -> prepare($query);
              $stmt -> execute();
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
              $arrayList = new ArrayObject();
              foreach($results as $eintrag){
                  $order = new Order();
                  $order->setId($eintrag['id']);
                  $order->setAnzahl($eintrag['anzahl']);
                  $order->setKunde($eintrag['kunde_id']);
                  $order->setBestelldatum($eintrag['bestelldatum']);
                  $order->setVerwendungszweck($eintrag['verwendungszweck']);
                  $order->setBemerkung($eintrag['bemerkung']);
                  $arrayList->append($order);

              }
              return $arrayList;


            } catch (PDOException $ex) {
                print_r($ex);
            }
    }

    public function order_getAll(){

         try{
              $db = $this->getConnection();
              //1 = Training
              $query = "Select o.bemerkung,o.id,DATE_FORMAT(o.time,'%d.%m.%Y') AS bestelldatum, k.id as kunde_id, k.vorname, k.nachname, k.strasse,o.anzahl, o.verwendungszweck
                        from bestellung o left join kunde k on k.id = o.id_kunde where o.gezahlt=0 ";
              $stmt  = $db -> prepare($query);
              $stmt -> execute();
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
              $arrayList = new ArrayObject();
              foreach($results as $eintrag){
                  $order = new Order();
                  $order->setId($eintrag['id']);
                  $order->setAnzahl($eintrag['anzahl']);
                  $order->setKunde($eintrag['kunde_id']);
                  $order->setBestelldatum($eintrag['bestelldatum']);
                  $order->setVerwendungszweck($eintrag['verwendungszweck']);
                  $order->setBemerkung($eintrag['bemerkung']);
                  $arrayList->append($order);

              }
              return $arrayList;


            } catch (PDOException $ex) {
                print_r($ex);
            }
    }

    public function checkBarcodeGenerierung($vz){
        try{
              $db = $this->getConnection();
              //1 = Training
              $query = "Select id from barcode where barcode='$vz'";
              $stmt  = $db -> prepare($query);
              $stmt -> execute();
              $anzahl = $stmt->rowCount();
              if($anzahl == 1){
                    $vorhanden = true;

              }else{
                   $vorhanden = false;
              }
              return $vorhanden;

            } catch (PDOException $ex) {
                $vorhanden = true;
                print_r($ex);
            }
    }


    public function checkVerwendungszweck($vz){
        try{
              $db = $this->getConnection();
              //1 = Training
              $query = "Select id from bestellung where verwendungszweck='$vz'";
              $stmt  = $db -> prepare($query);
              $stmt -> execute();
              $anzahl = $stmt->rowCount();
              if($anzahl == 1){
                    $vorhanden = true;

              }else{
                   $vorhanden = false;
              }
              return $vorhanden;

            } catch (PDOException $ex) {
                $vorhanden = true;
                print_r($ex);
            }
    }

     public function order_getBarcodeList(){

         try{
              $db = $this->getConnection();
              //1 = Training
              $query = "Select b.kunden_id,b.barcode,k.vorname,k.nachname,k.email,k.ort from barcode b inner join kunde k on k.id=b.kunden_id";
              $stmt  = $db -> prepare($query);
              $stmt -> execute();
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
              $arrayList = new ArrayObject();
              foreach($results as $eintrag){
                  $arrayList->append($eintrag);

              }
              return $arrayList;


            } catch (PDOException $ex) {
                print_r($ex);
            }
    }



    public function order_getAnwesenheit(){

        try{
            $db = $this->getConnection();
              //1 = Training
              $query = "Select count(b.id) as Anzahl from barcode b where b.gescannt = 1 ";
              $stmt  = $db -> prepare($query);
              $stmt -> execute();
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
              $arrayList = new ArrayObject();
              foreach($results as $eintrag){
                  $anzahl_1  = $eintrag['Anzahl'];
              }
             // $query = "Select value from hardticket where id=1";
            //  $stmt  = $db -> prepare($query);
            //  $stmt -> execute();
            //  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //  $arrayList = new ArrayObject();
            //  foreach($results as $eintrag){
            //      $anzahl_2  = $eintrag['value'];
            //  }
            //  $summe = $anzahl_1 + $anzahl_2;
             // return $summe;
            return $anzahl_1;

        }catch (PDOException $ex) {
                print_r($ex);
            }
    }

    public function order_checkBarcode($code){
         try{
              $db = $this->getConnection();
              $db->beginTransaction();

            if($code =='1516201625069'){
                $vorhanden = true;
                $query = "Update hardticket set value = value+1 where id=1;";
              $stmt  = $db -> prepare($query);
              $stmt -> execute();
                $db->commit();
            }else{
              //1 = Training
              $query = "Select barcode,kunden_id from barcode where barcode='$code' and gescannt=0";
              $stmt  = $db -> prepare($query);
              $stmt -> execute();
              $anzahl = $stmt->rowCount();
              if($anzahl == 1){
                    $vorhanden = true;
                    $query="Update barcode set gescannt=1,time_eintritt=CURRENT_TIMESTAMP where barcode='$code'";
                    $stmt  = $db -> prepare($query);
                    $stmt -> execute();
              }else{
                   $vorhanden = false;
              }
              $db->commit();
            }
              return $vorhanden;

            } catch (PDOException $ex) {
                $db->rollBack();
                $vorhanden = false;
                print_r($ex);
            }
    }

    public function kunde_getDataById($id){
              try{
              $db = $this->getConnection();
              //1 = Training
              $query = "Select vorname,nachname,email,plz,strasse,ort from kunde where id=$id";
              $stmt  = $db -> prepare($query);
              $stmt -> execute();
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
              foreach($results as $eintrag){
                  $kunde = new Kunde($eintrag['vorname'],$eintrag['nachname'],
                                     $eintrag['email'],$eintrag['strasse'],$eintrag['plz'],$eintrag['ort']);
                  $kunde->setId($id);



              }
              return $kunde;


            } catch (PDOException $ex) {
                print_r($ex);
            }
    }

    public function order_getStatistik(){
        try{
              $db = $this->getConnection();
              //1 = Training
              $query = "Select sum(anzahl) as anzahl from bestellung";
              $stmt  = $db -> prepare($query);
              $stmt -> execute();
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
              foreach($results as $eintrag){
                  $statistik[0] = $eintrag['anzahl'];
              }
            $query = "Select sum(anzahl) as anzahl from bestellung where gezahlt='1'";
              $stmt  = $db -> prepare($query);
              $stmt -> execute();
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
              foreach($results as $eintrag){
                  $statistik[1] = $eintrag['anzahl'];
              }
              $query = "Select count(id_kunde) as anzahl from bestellung";
              $stmt  = $db -> prepare($query);
              $stmt -> execute();
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
              foreach($results as $eintrag){
                  $statistik[2] = $eintrag['anzahl'];
              }
              return $statistik;


            } catch (PDOException $ex) {
                print_r($ex);
            }
    }

    public function kunde_updateNewsletter($id){
            $query = "Update kunde set newsletter=1 where id=$id";
            $db = $this->getConnection();
           try {
                $stmt  = $db -> prepare($query);
                $save = $stmt->execute();
            } catch(PDOExecption $e) {
                print "Error!: " . $e->getMessage() . "</br>";
            }
            return $save;
    }

    public function kunde_saveOrder($anzahl,$verwendungszweck,$id,$bemerkung){

           $query = "Insert into bestellung(id_kunde,anzahl,verwendungszweck,bemerkung)
                       values(:id_kunde,:anzahl,:verwendungszweck,:bemerkung)";
            $db = $this->getConnection();
           try {
                $db->beginTransaction();
                $stmt  = $db -> prepare($query);
                $stmt-> bindParam(':id_kunde',$id,PDO::PARAM_INT,800);
                $stmt-> bindParam(':anzahl',$anzahl,PDO::PARAM_INT,200);
                $stmt-> bindParam(':verwendungszweck',$verwendungszweck,PDO::PARAM_STR,200);
                $stmt-> bindParam(':bemerkung',$bemerkung,PDO::PARAM_STR,500);
                $save = $stmt->execute();
                $db->commit();
            } catch(PDOExecption $e) {
                $db->rollback();
                print "Error!: " . $e->getMessage() . "</br>";
            }
            return $save;
    }

    public function mail_saveMail(Mail $mail){
        try{
              $db = $this->getConnection();
              //1 = Training
              $query = "Insert into mail(text,betreff,empfaenger,status) values(:text,:betreff,:empfaenger,:status)";
              $stmt  = $db -> prepare($query);
              $stmt-> bindParam(':text',$mail->getText(),PDO::PARAM_STR,800);
              $stmt-> bindParam(':betreff',$mail->getBetreff(),PDO::PARAM_STR,200);
              $stmt-> bindParam(':empfaenger',$mail->getEmpfaenger(),PDO::PARAM_STR,200);
              $stmt-> bindParam(':status',$mail->getStatus(),PDO::PARAM_INT);

              $stmt -> execute();
              $stmt;


            } catch (PDOException $ex) {
                print_r($ex);
            }
    }

    public function spieler_getNotenDurchschnitt($id){
         try{
              $db = $this->getConnection();
              //1 = Training

             $query = "Select Round(avg(note),2) durchschnitt from noten where spieler_id=:id ";
              $stmt  = $db -> prepare($query);
              $stmt-> bindParam(':id',$id,PDO::PARAM_INT);
              $stmt-> execute();
              return $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


            } catch (PDOException $ex) {
                print_r($ex);
            }
    }
    public function spieler_getAllSpieler(){
        try{
              $db = $this->getConnection();
              //1 = Training
              $query = "Select u.id,u.email,u.vorname,u.nachname,DATE_FORMAT(u.geburt,'%d.%m.%Y')as geburt,u.handy from user u inner join spieler s on s.user_id=u.id
                        order by s.kader_id,u.nachname";
              $stmt  = $db -> prepare($query);
              $stmt-> bindParam(':id',$id,PDO::PARAM_INT);
              $stmt -> execute();
              return $stmt;


            } catch (PDOException $ex) {
                print_r($ex);
            }
    }

    public function message_saveNachricht(User $user,$text,$start_datum,$ende_datum){
           try{
              $db = $this->getConnection();
              //1 = Training
              $db->beginTransaction();
              $query = "Insert into messages(user_id,text,start_datum,ende_datum)
                        values(:id,:text,:start_datum,:ende_datum)";
              $stmt  = $db -> prepare($query);
              $stmt-> bindParam(':id',$user->getId(),PDO::PARAM_INT);
              $stmt-> bindParam(':text',$text,PDO::PARAM_STR);
              $stmt-> bindParam(':start_datum',$start_datum,PDO::PARAM_STR);
              $stmt-> bindParam(':ende_datum',$ende_datum,PDO::PARAM_STR);

              $stmt -> execute();
              $id_message = $db->lastInsertId();
              $query = "Select user_id from spieler";
              $stmt  = $db -> prepare($query);

              $stmt -> execute();
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

              foreach($results as $row){
                  $sql = "Insert into zu_messages_spieler(id_spieler,id_messages,status)
                          values(:spieler_id,:id_messages,0)";
                  $stmt  = $db -> prepare($sql);
                  $stmt-> bindParam(':spieler_id',$row['user_id'],PDO::PARAM_INT);
                  $stmt-> bindParam(':id_messages',$id_message,PDO::PARAM_INT);
                  $stmt -> execute();

              }
              $db->commit();
              return $stmt;


            } catch (PDOException $ex) {
                $db->rollback();
                print_r($ex);
            }
    }

    public function message_updateMsg($id_msg,$id_user){
        try{
              $db = $this->getConnection();
              //1 = Training
              $query = "Update zu_messages_spieler set status=1 where id_messages=:id_msg and id_spieler=:id_user";
              $stmt  = $db -> prepare($query);
              $stmt-> bindParam(':id_msg',$id_msg,PDO::PARAM_INT);
              $stmt-> bindParam(':id_user',$id_user,PDO::PARAM_INT);
              $stmt -> execute();
            } catch (PDOException $ex) {
                print_r($ex);
            }
    }
    public function message_checkMsgToSpieler(User $user,$msg_id){
         try{
              $db = $this->getConnection();
              //1 = Training
              $query = "Select status from zu_messages_spieler where id_spieler=:id and id_messages=:msg_id";
              $stmt  = $db -> prepare($query);
              $stmt-> bindParam(':id',$user->getId(),PDO::PARAM_INT);
              $stmt-> bindParam(':msg_id',$msg_id,PDO::PARAM_INT);
              $stmt -> execute();
              $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
              if($result[0]['status']<1){
                  return true;
              }else{
                  return false;
              }


            } catch (PDOException $ex) {
                print_r($ex);
            }
    }

    public function noten_save(Noten $noten){

        try{
            $db = $this->getConnection();
            $db->beginTransaction();
            $sql = "Insert into noten(spieler_id,note,bemerkung,spiel_name)
                    values (:spieler_id,:note,:bemerkung,:spiel_name);";
            $stmt  = $db -> prepare($sql);
            $stmt-> bindParam(':spieler_id',$noten->getSpieler_id(),PDO::PARAM_INT);
            $stmt-> bindParam(':note',$noten->getNote(),PDO::PARAM_INT);
            $stmt-> bindParam(':bemerkung',$noten->getBemerkung(),PDO::PARAM_STR);
            $stmt-> bindParam(':spiel_name',$noten->getSpiel(),PDO::PARAM_STR);
            $stmt -> execute();
            $db->commit();
        }catch(PDOException $e){
            print_r($e);
        }
    }

        public function message_getNachricht(){
           try{
              $db = $this->getConnection();
              //1 = Training
              $query = "Select n.id,n.text,n.start_datum,n.ende_datum, u.nachname as author from messages n
                        inner join user u on u.id=n.user_id order by n.start_datum ASC";
              $stmt  = $db -> prepare($query);

              $stmt -> execute();
              return $stmt;


            } catch (PDOException $ex) {
                print_r($ex);
            }
    }

    public function message_getCurrentMessages(){
        try{
              $db = $this->getConnection();
              //1 = Training
              $query = "Select n.id,n.text,n.start_datum,n.ende_datum, u.nachname as author from messages n
                        inner join user u on u.id=n.user_id where n.start_datum >=CURDATE() and n.ende_datum>=CURDATE() order by n.start_datum ASC";
              $stmt  = $db -> prepare($query);
              $stmt -> execute();
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
              $arrayList = new ArrayObject();
              foreach($results as $eintrag){
                  $nachricht = new Nachrichten();
                  $nachricht->setNachricht($eintrag);
                  $arrayList->append($nachricht);

              }
              return $arrayList;


            } catch (PDOException $ex) {
                print_r($ex);
            }

    }


    public function statistik_getTrainingsranking(){
           try{
              $db = $this->getConnection();
              //1 = Training
              $query = "Select u.vorname,u.nachname,sum(status_coach) as anzahl from zu_spieler_termine z inner join user u on u.id = z.spieler_id group by u.id order by anzahl DESC";
              $stmt  = $db -> prepare($query);
              $stmt -> execute();
              return $stmt;


            } catch (PDOException $ex) {
                print_r($ex);
            }
    }

    public function statistik_getPersonalNoten($id){
        try{
              $db = $this->getConnection();
              //1 = Training
              $query = "Select spiel_name,note,bemerkung from noten where spieler_id=:id order by timestamp ASC";

              $stmt  = $db -> prepare($query);
              $stmt-> bindParam(':id',$id,PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt -> execute();
              return $stmt;


            } catch (PDOException $ex) {
                print_r($ex);
            }
    }


    public function statistik_getAnzahlSpielerByPosition($position){
          try{
              $db = $this->getConnection();
              //1 = Training
              $query = "Select * from spieler where position=:position";
              $stmt  = $db -> prepare($query);
              $stmt-> bindParam(':position',$position,PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt -> execute();
              return $stmt;


            } catch (PDOException $ex) {
                print_r($ex);
            }
    }

    public function statistik_getNotenRanking(){
          try{
              $db = $this->getConnection();
              //1 = Training
              $query = "SELECT u.vorname, u.nachname, AVG( n.note ) AS noten
                        FROM noten n
                        INNER JOIN user u ON n.spieler_id = u.id
                        GROUP BY n.spieler_id order by noten ASC
                            ";
              $stmt  = $db -> prepare($query);
              $stmt -> execute();
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
              return $results;


            } catch (PDOException $ex) {
                print_r($ex);
            }
    }

     public function statistik_getPositionRanking(){
           try{
              $db = $this->getConnection();
              //1 = Training
              $query = "SELECT s.position, count( * ) AS anzahl
                        FROM zu_spieler_termine z
                        INNER JOIN user u ON u.id = z.spieler_id
                        INNER JOIN spieler s ON s.user_id = u.id
                        WHERE z.status_coach =1
                        GROUP BY s.position
                        ORDER BY anzahl ASC
                            ";
              $stmt  = $db -> prepare($query);
              $stmt -> execute();
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
              return $results;


            } catch (PDOException $ex) {
                print_r($ex);
            }
    }


    public function statistik_getAnzahlTermineByArt($art){
        try{
              $db = $this->getConnection();
              //1 = Training
              $query = "Select * from termine t where t.final=1 and t.art=:art";
              $stmt  = $db -> prepare($query);
              $stmt-> bindParam(':art',$art,PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt -> execute();
              return $stmt->rowCount();


            } catch (PDOException $ex) {
                print_r($ex);
            }
    }
    public function getTeilnahmeTraining($id,$art){
        if(isset($id)){
            try{
              $db = $this->getConnection();
              //1 = Training
              $query = "Select * from zu_spieler_termine z inner join termine t on z.id_termin=t.id where t.final=1 and spieler_id=:id and t.art=:art  and z.status_coach=1";
              $stmt  = $db -> prepare($query);
              $stmt-> bindParam(':id',$id,PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt-> bindParam(':art',$art,PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt -> execute();
              return $stmt->rowCount();


            } catch (PDOException $ex) {
                print_r($ex);
            }
        }
    }


    //Termine

    public function termine_getNextTermin($user){

        if(is_a($user, "User")){
            try{
              $db = $this->getConnection();
              $query = "Select t.uhr,t.id,t.bemerkung,DATE_FORMAT(t.datum,'%d.%m.%Y')as datum_format,t.frist,t.art,t.kader_id as kader,t.treffpunkt from termine t
                       where datum>=CURDATE() order by datum ASC";
              $stmt  = $db -> prepare($query);
              $stmt -> execute();
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
              return $results;

            } catch (PDOException $ex) {
                print_r($ex);
            }
        }else{
            print_r("Bitte anmelden");
        }
    }


    public function termine_getAnzEingeladen_DB($id){
         try{
        $db = $this->getConnection();
        $query = "Select count(spieler_id) as anzahl_spieler from zu_spieler_termine where id_termin=:id";

        $stmt  = $db -> prepare($query);
        $stmt-> bindParam(':id',$id,PDO::PARAM_INT); //PDO::PARAM_STR, 12
        $stmt -> execute();
       return   $result = $stmt->fetch();

        }catch(PDOException $e){
            print_r($e);
        }
    }

     public function termine_getAnzTeilnehmer_DB($id){
         try{
        $db = $this->getConnection();
        $query = "Select sum(status_coach) as anzahl_teilnehmer from zu_spieler_termine where id_termin=:id";
        $stmt  = $db -> prepare($query);
        $stmt-> bindParam(':id',$id,PDO::PARAM_INT); //PDO::PARAM_STR, 12
        $stmt -> execute();
        return $result = $stmt->fetch();
        }catch(PDOException $e){
            print_r($e);
        }
    }


    public function termine_getAllTermineByUser(User $user){
         if(is_a($user, "User")){
            try{
              $db = $this->getConnection();
              $query = "Select t.uhr,t.id,DATE_FORMAT(t.datum,'%d.%m.%Y')as datum_format,t.frist,t.art,t.kader_id as kader,t.bemerkung,z.status_coach,z.status_spieler from termine t
                        inner join zu_spieler_termine z on z.id_termin=t.id
                        inner join spieler s on s.user_id=z.spieler_id where z.spieler_id=:id and  z.status_coach=1 and t.final=1";
              $stmt  = $db -> prepare($query);
              $stmt-> bindParam(':id',$user->getId(),PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt -> execute();
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
              return $results;

            } catch (PDOException $ex) {
                print_r($ex);
            }
        }else{
            print_r("Bitte anmelden");
        }
    }

    public function termine_getTermineByUser(User $user){
         if(is_a($user, "User")){
            try{
              $db = $this->getConnection();
              $query = "Select t.uhr,t.treffpunkt,t.id,DATE_FORMAT(t.datum,'%d.%m.%Y')as datum_format,t.frist,t.art,t.kader_id as kader,t.bemerkung,z.status_coach,z.status_spieler from termine t
                        inner join zu_spieler_termine z on z.id_termin=t.id
                        inner join spieler s on s.user_id=z.spieler_id where s.user_id=:id and t.datum>=CURDATE();";
              $stmt  = $db -> prepare($query);
              $stmt-> bindParam(':id',$user->getId(),PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt -> execute();
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
              return $results;

            } catch (PDOException $ex) {
                print_r($ex);
            }
        }else{
            print_r("Bitte anmelden");
        }
    }

    public function termine_saveTerminFinal($id){
         try{
              $final = 1;
              $db = $this->getConnection();
              $query = "Update termine set final=:final where id=:id";

              $stmt  = $db -> prepare($query);
              $stmt-> bindParam(':id',$id,PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt-> bindParam(':final',$final,PDO::PARAM_INT); //PDO::PARAM_STR, 12

              $stmt -> execute();
            } catch (PDOException $ex) {
                print_r($ex);
            }
    }
     public function termine_updateStatusCoachById($user,$id_termin,$id_spieler,$wert){
         if(is_a($user, "User") || is_null($id_termin) || is_null($wert)){
            try{
              $db = $this->getConnection();
             $query = "Update zu_spieler_termine set status_coach=:wert where id_termin=:id_termin and spieler_id=:id  ";
              $stmt  = $db -> prepare($query);
              $stmt-> bindParam(':id',$id_spieler,PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt-> bindParam(':id_termin',$id_termin,PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt-> bindParam(':wert',$wert,PDO::PARAM_INT); //PDO::PARAM_STR, 12

              $stmt -> execute();
            } catch (PDOException $ex) {
                print_r($ex);
            }
        }else{
            print_r("Bitte anmelden");
        }
    }


    public function termine_getStatistikById($id,$user){
         if(is_a($user, "User")){
            try{
              $db = $this->getConnection();

              $query = "Select Count(*)as zusagen from zu_spieler_termine z inner join termine t on t.id=z.id_termin where z.id_termin=:id and z.status_spieler=1;";
              $stmt  = $db -> prepare($query);
              $stmt-> bindParam(':id',$id,PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt -> execute();
              $results = $stmt->fetchAll();
              $statistik['zusagen'] = $results[0]['zusagen'];

              $query = "Select Count(*)as absagen from zu_spieler_termine z inner join termine t on t.id=z.id_termin where z.id_termin=:id and z.status_spieler =0;";
              $stmt  = $db -> prepare($query);
              $stmt-> bindParam(':id',$id,PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt -> execute();
              $results = $stmt->fetchAll();
              $statistik['absagen'] = $results[0]['absagen'];

              $query = "Select Count(*)as gesamt from zu_spieler_termine z inner join termine t on t.id=z.id_termin where z.id_termin=:id and (z.status_spieler=1 or z.status_spieler =0);";
              $stmt  = $db -> prepare($query);
              $stmt-> bindParam(':id',$id,PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt -> execute();
              $results = $stmt->fetchAll();
              $statistik['gesamt'] = $results[0]['gesamt'];




              return $statistik;

            } catch (PDOException $ex) {
                print_r($ex);
            }
        }else{
            print_r("Bitte anmelden");
        }


    }

    public function termine_getAllTermine(User $user){
         if(is_a($user, "User")){
            try{
              $db = $this->getConnection();
              $query = "Select t.id,DATE_FORMAT(t.datum,'%d.%m.%Y')as datum_format,t.frist,t.art,t.kader_id as kader,t.notiz as bemerkung,t.uhr from termine t
                        inner join zu_spieler_termine z on z.id_termin=t.id  where t.final<1 and t.art=1 group by t.id order by t.datum ASC ";
              $stmt  = $db -> prepare($query);
              $stmt-> bindParam(':id',$user->getId(),PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt -> execute();
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
              return $results;

            } catch (PDOException $ex) {
                print_r($ex);
            }
        }else{
            print_r("Bitte anmelden");
        }
    }

    public function termin_saveGrund($terminId,$grund){
        $user = $_SESSION['user'];
        try{
            $db = $this->getConnection();
            $query = "Update zu_spieler_termine set grund=:grund where spieler_id=:id and id_termin=:termin_id";
            $stmt  = $db -> prepare($query);
            $stmt-> bindParam(':id',$user->getId(),PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt-> bindParam(':termin_id',$terminId,PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt-> bindParam(':grund',$grund,PDO::PARAM_STR,200); //PDO::PARAM_STR, 12
             $stmt -> execute();

        }catch(PDOException $e){
            print_r($e);
        }
    }

    public function termine_createTermin($user,$date,$kader,$frist,$bemerkung,$art,$treffpunkt,$uhr){
        if(is_a($user, "User")){
            try{
              $db = $this->getConnection();

              $query = "Insert into termine(datum,frist,art,bemerkung,kader_id,treffpunkt,user_create,uhr)
                        values(:datum,:frist,:art,:bemerkung,:kader,:treffpunkt,:id,:uhr)";

              $stmt  = $db -> prepare($query);
              $db->beginTransaction();
              $stmt-> bindParam(':id',$user->getId(),PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt-> bindParam(':datum',$date,PDO::PARAM_STR,20); //PDO::PARAM_STR, 12
              $stmt-> bindParam(':kader',$kader,PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt-> bindParam(':frist',$frist,PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt-> bindParam(':bemerkung',$bemerkung,PDO::PARAM_STR,500); //PDO::PARAM_STR, 12
              $stmt-> bindParam(':treffpunkt',$treffpunkt,PDO::PARAM_STR,100); //PDO::PARAM_STR, 12
              $stmt-> bindParam(':uhr',$uhr,PDO::PARAM_STR,100); //PDO::PARAM_STR, 12
              $stmt-> bindParam(':art',$art,PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt -> execute();
              $id_termin = $db->lastInsertId();
                if($kader != "x"){
                    $query = "Select user_id from spieler where kader_id=:kader";
                    $stmt  = $db -> prepare($query);
                    $stmt-> bindParam(':kader',$kader,PDO::PARAM_INT);
                }else{
                    $query = "Select user_id from spieler where kader_id='1' or kader_id=2"; //Beide Mannschaften
                     $stmt  = $db -> prepare($query);
                }

              $stmt -> execute();
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

              foreach($results as $row){
                  $row['user_id'];
                  $sql = "Insert into zu_spieler_termine(spieler_id,id_termin,status_spieler,status_coach)
                          values(:spieler_id,:id_termin,1,0)";
                  $stmt  = $db -> prepare($sql);
                  $stmt-> bindParam(':spieler_id',$row['user_id'],PDO::PARAM_INT);
                  $stmt-> bindParam(':id_termin',$id_termin,PDO::PARAM_INT);
                  $stmt -> execute();

              }


              $db->commit();






            } catch (PDOException $ex) {
                print_r($ex);
                $dbh->rollback();
            }
        }else{
            print_r("Bitte anmelden");
        }
    }

    public function termine_updateTerminById($user,$id_termin,$wert){
         if(is_a($user, "User") || is_null($id_termin) || is_null($wert)){
            try{
              $db = $this->getConnection();
              $query = "Update zu_spieler_termine set status_spieler=:wert where id_termin=:id_termin and spieler_id=:id  ";
              $stmt  = $db -> prepare($query);
              $stmt-> bindParam(':id',$user->getId(),PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt-> bindParam(':id_termin',$id_termin,PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt-> bindParam(':wert',$wert,PDO::PARAM_INT); //PDO::PARAM_STR, 12

              $stmt -> execute();
            } catch (PDOException $ex) {
                print_r($ex);
            }
        }else{
            print_r("Bitte anmelden");
        }
    }

    public function termine_getAllSpielerToTerminById(User $user,$id){
         if(is_a($user, "User")){
            try{
              $db = $this->getConnection();
              $query = "Select u.id as user_id,
                               u.vorname as vorname,
                               u.nachname as nachname,
                               z.grund as grund,
                               z.status_spieler as zusage_spieler,
                               z.status_coach as zusage_coach
                               from user u inner join spieler s on u.id=s.user_id
                               inner join zu_spieler_termine z on z.spieler_id=s.user_id
                               where z.id_termin = :id and u.role=2 order by u.nachname ASC;
                        ";
              $stmt  = $db -> prepare($query);
              $stmt-> bindParam(':id',$id,PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt -> execute();
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
              return $results;

            } catch (PDOException $ex) {
                print_r($ex);
            }
        }else{
            print_r("Bitte anmelden");
        }
    }

    public function checkUser(User $user){
        if(is_a($user, "User")){
            try{
              $db = $this->getConnection();
              $query = "Select role from user where email=:email and vorname=:vorname and nachname=:nachname";
              $stmt  = $db -> prepare($query);
              $stmt-> bindParam(':email',$user->getEmail(),PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt-> bindParam(':vorname',$user->getVorname(),PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt-> bindParam(':nachname',$user->getNachname(),PDO::PARAM_INT); //PDO::PARAM_STR, 12
              $stmt -> execute();
              $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
              return $results;

            } catch (PDOException $ex) {
                print_r($ex);
            }
        }else{
            print_r("Bitte anmelden");
        }
    }
}
